<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Models\RecuItem;
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
        'items.*.product_variant_id' => 'nullable|exists:product_variants,id', 
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
            
            if (!empty($itemData['product_variant_id'])) {
                $variant = ProductVariant::findOrFail($itemData['product_variant_id']);
                
                if ($variant->quantite_stock < $itemData['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$variant->full_name}. Stock: {$variant->quantite_stock}");
                }

                // Créer item avec variant
                $recu->items()->create([
                    'produit_id' => $itemData['produit_id'],
                    'product_variant_id' => $itemData['product_variant_id'], 
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
    if (!in_array($recu->statut, ['en_cours', 'livre'])) {
        return back()->with('error', 'Ce reçu ne peut pas être modifié');
    }

    // Charger toutes les catégories actives
    $categories = \App\Models\Category::orderBy('nom')->get();

    // Charger tous les produits avec variants
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

    // Charger les items du reçu avec les relations nécessaires
    $recu->load(['items.produit', 'items.variant', 'paiements']);

    return view('recus.edit', compact('recu', 'produits', 'categories'));
}

public function update(Request $request, RecuUcg $recu)
{
    if (!in_array($recu->statut, ['en_cours', 'livre'])) {
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
        'mode_paiement' => 'required|in:especes,carte,cheque,virement,credit',
        'montant_paye' => 'nullable|numeric|min:0',
        'date_paiement' => 'nullable|date',
        'notes' => 'nullable|string',
        'items' => 'required|array|min:1',
        'items.*.produit_id' => 'required|exists:produits,id',
        'items.*.product_variant_id' => 'nullable|exists:product_variants,id',
        'items.*.quantite' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();
    try {
        // Sauvegarder les anciens items pour restaurer le stock
        $oldItems = $recu->items()->get();

        // 1️⃣ Restaurer le stock des anciens items (soft delete va le faire automatiquement)
        foreach ($oldItems as $item) {
            $item->delete(); // Le stock sera restauré via l'événement 'deleted' dans RecuItem
        }

        // 2️⃣ Mettre à jour les informations du reçu
        $recu->update([
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

        // 3️⃣ Créer les nouveaux items
        foreach ($validated['items'] as $itemData) {
            
            if (!empty($itemData['product_variant_id'])) {
                // Produit avec variant
                $variant = ProductVariant::findOrFail($itemData['product_variant_id']);
                
                if ($variant->quantite_stock < $itemData['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$variant->full_name}. Stock disponible: {$variant->quantite_stock}");
                }

                $recu->items()->create([
                    'produit_id' => $itemData['produit_id'],
                    'product_variant_id' => $itemData['product_variant_id'],
                    'quantite' => $itemData['quantite'],
                ]);

            } else {
                // Produit simple (sans variant)
                $produit = Produit::findOrFail($itemData['produit_id']);
                
                if ($produit->quantite_stock < $itemData['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}. Stock disponible: {$produit->quantite_stock}");
                }

                $recu->items()->create([
                    'produit_id' => $itemData['produit_id'],
                    'quantite' => $itemData['quantite'],
                ]);
            }
        }

        // 4️⃣ Recalculer le total
        $recu->refresh();
        $recu->calculerTotal();

        // 5️⃣ Gérer le paiement (si montant_paye a changé)
        $montantPaye = $validated['montant_paye'] ?? 0;
        $montantDejaPayé = $recu->paiements->sum('montant');

        if ($montantPaye != $montantDejaPayé) {
            // Supprimer tous les anciens paiements
            $recu->paiements()->delete();

            // Ajouter le nouveau paiement si montant > 0
            if ($montantPaye > 0) {
                $recu->ajouterPaiement(
                    $montantPaye,
                    $validated['mode_paiement'],
                    null
                );
            }
        }

        DB::commit();

        return redirect()
            ->route('recus.show', $recu)
            ->with('success', "Reçu {$recu->numero_recu} mis à jour avec succès!");

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
        if (!in_array($recu->statut, ['en_cours', 'livre'])) {
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
       if (!in_array($recu->statut, ['en_cours', 'livre'])) {
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


public function appliquerRemise(Request $request, RecuUcg $recu, RecuItem $item)
    {
        // Vérifier que l'item appartient bien au reçu
        if ($item->recu_ucg_id !== $recu->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cet article n\'appartient pas à ce reçu'
            ], 400);
        }

        // Vérifier qu'il y a une remise
        if ($recu->remise <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune remise définie sur ce reçu'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // 1️⃣ Enlever la remise de tous les autres items
            RecuItem::where('recu_ucg_id', $recu->id)
                ->where('id', '!=', $item->id)
                ->update(['remise_appliquee' => false]);

            // 2️⃣ Appliquer la remise sur l'item sélectionné
            $item->update(['remise_appliquee' => true]);

            // 3️⃣ Recalculer le total
            $recu->calculerTotal();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Remise appliquée avec succès',
                'recu' => [
                    'sous_total' => number_format($recu->sous_total, 2),
                    'remise' => number_format($recu->remise, 2),
                    'total' => number_format($recu->total, 2),
                    'marge_globale' => number_format($recu->margeGlobale(), 2),
                    'marge_apres_remise' => number_format($recu->margeApresRemise(), 2),
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 500);
        }
    }

}
