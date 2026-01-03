<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\RecuUcg;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class RecuUcgController extends Controller
{

    public function __construct()
{
    $this->middleware('permission:recu-list|recu-create|recu-edit|recu-delete', ['only' => ['index', 'show']]);
    $this->middleware('permission:recu-create', ['only' => ['create', 'store', 'addItem']]);
    $this->middleware('permission:recu-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:recu-delete', ['only' => ['destroy', 'removeItem']]);
    $this->middleware('permission:recu-statut-change', ['only' => ['updateStatut']]);
    $this->middleware('permission:recu-print', ['only' => ['print']]);
    $this->middleware('permission:recu-statistiques', ['only' => ['statistiques']]);
}
    public function index(Request $request)
    {
        $query = RecuUcg::with(['user', 'items.produit']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('statut_paiement')) {
            $query->where('statut_paiement', $request->statut_paiement);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_recu', 'like', "%{$search}%")
                  ->orWhere('client_nom', 'like', "%{$search}%")
                  ->orWhere('client_telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [
                $request->date_debut,
                $request->date_fin . ' 23:59:59'
            ]);
        }

        $recus = $query->latest()->paginate(20);

        return view('recus.index', compact('recus'));
    }

public function create()
{
    // Charger toutes les catégories actives
    $categories = \App\Models\Category::
        orderBy('nom')
        ->get();

    // Charger tous les produits (comme avant)
    $produits = Produit::with(['variants' => function($query) {
        $query->where('actif', true)
              ->where('quantite_stock', '>', 0);
    }])
    ->where('actif', true)
    ->where(function($q) {
        $q->where('quantite_stock', '>', 0)
          ->orWhereHas('variants', function($query) {
              $query->where('actif', true)
                    ->where('quantite_stock', '>', 0);
          });
    })
    ->orderBy('nom')
    ->get();

    return view('recus.create', compact('produits', 'categories'));
}

/**
 * ✅ NOUVELLE MÉTHODE : Récupère les produits par catégorie via AJAX
 */
public function getProduitsByCategory($categoryId)
{
    try {
        $produits = Produit::with(['variants' => function($query) {
            $query->where('actif', true)
                  ->where('quantite_stock', '>', 0);
        }])
        ->where('actif', true)
        ->where('category_id', $categoryId)
        ->where(function($q) {
            $q->where('quantite_stock', '>', 0)
              ->orWhereHas('variants', function($query) {
                  $query->where('actif', true)
                        ->where('quantite_stock', '>', 0);
              });
        })
        ->orderBy('nom')
        ->get()
        ->map(function($produit) {
            return [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'prix_vente' => $produit->prix_vente,
                'quantite_stock' => $produit->quantite_stock,
                'has_variants' => $produit->variants->count() > 0
            ];
        });

        return response()->json([
            'success' => true,
            'produits' => $produits
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors du chargement des produits: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * ✅ VERSION FIXED  - BASITA O KHDAM  100%
     */
   // ✅ MÉTHODE STORE MODIFIÉE

public function store(Request $request)
{
    $validated = $request->validate([
        'client_nom' => 'required|string|max:255',
        'client_prenom' => 'nullable|string|max:255',
        'client_telephone' => 'nullable|string|max:20',
        'client_email' => 'nullable|email|max:255',
        'client_adresse' => 'nullable|string',
        'equipement' => 'nullable|string|max:255',
        'details' => 'nullable|string',
        'type_garantie' => 'required|in:30_jours,90_jours,180_jours,360_jours,sans_garantie',
        'remise' => 'nullable|numeric|min:0',
        'tva' => 'nullable|numeric|min:0',
        'mode_paiement' => 'required|in:especes,carte,cheque,virement,credit',
        'montant_paye' => 'nullable|numeric|min:0',
        'date_paiement' => 'nullable|date',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.produit_id' => 'required|exists:produits,id',
        'items.*.product_variant_id' => 'nullable|exists:product_variants,id', // ✅ NOUVEAU
        'items.*.quantite' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();
    try {
        $recu = RecuUcg::create([
            'user_id' => auth()->id(),
            'client_nom' => $validated['client_nom'],
            'client_prenom' => $validated['client_prenom'] ?? null,
            'client_telephone' => $validated['client_telephone'] ?? null,
            'client_email' => $validated['client_email'] ?? null,
            'client_adresse' => $validated['client_adresse'] ?? null,
            'equipement' => $validated['equipement'] ?? null,
            'details' => $validated['details'] ?? null,
            'type_garantie' => $validated['type_garantie'],
            'remise' => $validated['remise'] ?? 0,
            'tva' => $validated['tva'] ?? 0,
            'mode_paiement' => $validated['mode_paiement'],
            'date_paiement' => $validated['date_paiement'] ?? now(),
            'notes' => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $itemData) {
            // ✅ Vérifier si c'est un variant ou produit simple
            if (!empty($itemData['product_variant_id'])) {
                $variant = ProductVariant::findOrFail($itemData['product_variant_id']);
                
                if ($variant->quantite_stock < $itemData['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$variant->full_name}. Stock: {$variant->quantite_stock}");
                }

                // Créer item avec variant
                $recu->items()->create([
                    'produit_id' => $itemData['produit_id'],
                    'product_variant_id' => $itemData['product_variant_id'], // ✅ Important
                    'quantite' => $itemData['quantite'],
                ]);

            } else {
                // Produit simple (sans variant)
                $produit = Produit::findOrFail($itemData['produit_id']);
                
                if ($produit->quantite_stock < $itemData['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}. Stock: {$produit->quantite_stock}");
                }

                $recu->items()->create([
                    'produit_id' => $itemData['produit_id'],
                    'quantite' => $itemData['quantite'],
                ]);
            }
        }
        
        $recu->refresh();

        // Ajouter paiement
        $montantPaye = $validated['montant_paye'] ?? $recu->total;

        if ($montantPaye > 0) {
            $recu->ajouterPaiement(
                $montantPaye,
                $validated['mode_paiement'],
                null
            );
        }

        DB::commit();

        return redirect()
            ->route('recus.show', $recu)
            ->with('success', "Reçu {$recu->numero_recu} créé avec succès!");

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->with('error', "Erreur: " . $e->getMessage());
    }
}
    public function show(RecuUcg $recu)
    {
        $recu->load([
            'items.produit',
            'paiements.user',
            'stockMovements.produit',
            'user'
        ]);

        return view('recus.show', compact('recu'));
    }

    public function edit(RecuUcg $recu)
    {
        if ($recu->statut !== 'en_cours') {
            return back()->with('error', 'Ce reçu ne peut pas être modifié');
        }

        $produits = Produit::where('actif', true)
            ->orderBy('nom')
            ->get();

        $recu->load('items.produit');

        return view('recus.edit', compact('recu', 'produits'));
    }

    public function update(Request $request, RecuUcg $recu)
    {
        if ($recu->statut !== 'en_cours') {
            return back()->with('error', 'Ce reçu ne peut pas être modifié');
        }

        $validated = $request->validate([
            'client_nom' => 'required|string|max:255',
            'client_prenom' => 'nullable|string|max:255',
            'client_telephone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_adresse' => 'nullable|string',
            'equipement' => 'nullable|string|max:255',
            'details' => 'nullable|string',
'type_garantie' => 'required|in:30_jours,90_jours,180_jours,360_jours,sans_garantie',
            'remise' => 'nullable|numeric|min:0',
            'tva' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $recu->update($validated);
            $recu->calculerTotal();

            DB::commit();

            return redirect()
                ->route('recus.show', $recu)
                ->with('success', 'Reçu mis à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function updateStatut(Request $request, RecuUcg $recu)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_cours,livre,annule,retour'
        ]);

        DB::beginTransaction();
        try {
            if (in_array($validated['statut'], ['annule', 'retour'])) {
                foreach ($recu->items as $item) {
                    $item->delete();
                }
            }

            $recu->update(['statut' => $validated['statut']]);

            DB::commit();

            return back()->with('success', 'Statut mis à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(RecuUcg $recu)
    {
        DB::beginTransaction();
        try {
            foreach ($recu->items as $item) {
                $item->delete();
            }

            $recu->delete();

            DB::commit();

            return redirect()
                ->route('recus.index')
                ->with('success', 'Reçu supprimé avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function addItem(Request $request, RecuUcg $recu)
    {
        if ($recu->statut !== 'en_cours') {
            return back()->with('error', 'Impossible d\'ajouter des articles à ce reçu');
        }

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $produit = Produit::find($validated['produit_id']);

            if ($produit->quantite_stock < $validated['quantite']) {
                throw new \Exception("Stock insuffisant pour {$produit->nom}");
            }

            $recu->items()->create($validated);

            DB::commit();

            return back()->with('success', 'Article ajouté avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function removeItem(RecuUcg $recu, $itemId)
    {
        if ($recu->statut !== 'en_cours') {
            return back()->with('error', 'Impossible de supprimer des articles de ce reçu');
        }

        DB::beginTransaction();
        try {
            $item = $recu->items()->findOrFail($itemId);
            $item->delete();

            DB::commit();

            return back()->with('success', 'Article supprimé avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

   public function print(RecuUcg $recu)
{
    $recu->load(['items.produit', 'paiements', 'user']);
    
    $pdf = Pdf::loadView('recus.print', compact('recu'))
        ->setPaper('a4', 'portrait')
        ->setOption('margin-top', 10)
        ->setOption('margin-right', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('margin-left', 10);
        
    return $pdf->stream("recu_{$recu->numero_recu}.pdf"); 
}
    public function statistiques(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth());
        $dateFin = $request->input('date_fin', now()->endOfMonth());

        $stats = [
            'total_recus' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])->count(),
            'total_ventes' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])->sum('total'),
            'total_marges' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->get()
                ->sum(fn($recu) => $recu->margeGlobale()),
            'recus_payes' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->where('statut_paiement', 'paye')
                ->count(),
            'recus_impayes' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->where('statut_paiement', 'impaye')
                ->count(),
        ];

        return view('recus.statistiques', compact('stats', 'dateDebut', 'dateFin'));
    }

    public function trash(Request $request)
    {
        $query = RecuUcg::onlyTrashed()->with(['user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_recu', 'like', "%{$search}%")
                  ->orWhere('client_nom', 'like', "%{$search}%")
                  ->orWhere('client_telephone', 'like', "%{$search}%")
                  ->orWhereHas('user', function (Builder $q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $recus = $query->latest('deleted_at')->paginate(20);

        return view('recus.trash', compact('recus'));
    }

    /**
     * Restaure un reçu depuis la corbeille.
     */
    public function restore($id)
{
    $recu = RecuUcg::onlyTrashed()->findOrFail($id);
    
    DB::beginTransaction();
    try {
        // 1️⃣ Restaurer le reçu
        $recu->restore();

        // 2️⃣ Récupérer et restaurer tous les items supprimés
        $items = \App\Models\RecuItem::onlyTrashed()
            ->where('recu_ucg_id', $recu->id)
            ->get();

        foreach ($items as $item) {
            // L'événement 'restored' dans RecuItem va :
            // - Vérifier le stock
            // - Décrémenter le stock
            // - Créer le mouvement de stock
            // - Recalculer le total du reçu
            $item->restore();
        }
        
        DB::commit();
        
        return redirect()
            ->route('recus.show', $recu->id)
            ->with('success', "Reçu {$recu->numero_recu} restauré avec succès!");

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', "Erreur lors de la restauration: " . $e->getMessage());
    }
}

/**
 * Supprime définitivement un reçu de la base de données.
 * ✅ VERSION SIMPLIFIÉE
 */
public function forceDelete($id)
{
    $recu = RecuUcg::onlyTrashed()->findOrFail($id);

    DB::beginTransaction();
    try {
        // 1️⃣ Supprimer définitivement les items
        $items = \App\Models\RecuItem::onlyTrashed()
            ->where('recu_ucg_id', $recu->id)
            ->get();
            
        foreach ($items as $item) {
            // Force delete ne touche pas au stock (déjà ajusté lors du soft delete)
            $item->forceDelete();
        }
        
        // 2️⃣ Supprimer les mouvements de stock (optionnel, gardez l'historique si vous voulez)
        // \App\Models\StockMovement::where('recu_ucg_id', $recu->id)->delete();
        
        // 3️⃣ Supprimer les paiements
        \App\Models\Paiement::where('recu_ucg_id', $recu->id)->forceDelete();
        
        // 4️⃣ Supprimer définitivement le reçu
        $recu->forceDelete();

        DB::commit();
        
        return redirect()
            ->route('recus.trash')
            ->with('success', "Reçu {$recu->numero_recu} supprimé définitivement!");
            
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', "Erreur lors de la suppression définitive: " . $e->getMessage());
    }
}
}
