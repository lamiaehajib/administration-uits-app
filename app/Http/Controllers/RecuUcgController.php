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

    // ✅ Filtre par catégorie
    if ($request->filled('category_id')) {
        $query->whereHas('items.produit', function($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });
    }

    // ✅ Filtre par produit
    if ($request->filled('produit_id')) {
        $query->whereHas('items', function($q) use ($request) {
            $q->where('produit_id', $request->produit_id);
        });
    }

    $recus = $query->latest()->paginate(20);

    $categories = \App\Models\Category::orderBy('nom')->get();
    $produits = \App\Models\Produit::where('actif', true)->orderBy('nom')->get();

    return view('recus.index', compact('recus', 'categories', 'produits'));
}

public function create()
{
    $categories = \App\Models\Category::orderBy('nom')->get();

    // ✅ جيب المنتجات اللي عندهم stock
    $produits = Produit::with(['variants' => function($query) {
        $query->where('actif', true)->where('quantite_stock', '>', 0);
    }])
    ->where('actif', true)
    ->where(function($q) {
        $q->where('quantite_stock', '>', 0)
          ->orWhereHas('variants', fn($q) => $q->where('actif', true)->where('quantite_stock', '>', 0));
    })
    ->orderBy('nom')
    ->get()
    ->map(function($produit) {
        // ✅ جيب كل lots d'achat اللي عندهم quantite_restante > 0
        $lots = \App\Models\Achat::where('produit_id', $produit->id)
            ->where('quantite_restante', '>', 0)
            ->orderBy('date_achat', 'asc')
            ->get()
            ->map(fn($lot) => [
                'id'                 => $lot->id,
                'date_achat'         => $lot->date_achat->format('d/m/Y'),
                'fournisseur'        => $lot->fournisseur ?? 'N/A',
                'quantite_restante'  => $lot->quantite_restante,
                'prix_achat'         => $lot->prix_achat,
                'prix_vente_suggere' => $lot->prix_vente_suggere,
                'marge_pourcentage'  => $lot->marge_pourcentage,
            ]);

        // ✅ Stock "manuel" (غير مسجل في achats)
        $stockFifo   = $lots->sum('quantite_restante');
        $stockManuel = max(0, $produit->quantite_stock - $stockFifo);

        if ($stockManuel > 0) {
            $lots->prepend([
                'id'                 => 'manuel',
                'date_achat'         => 'Stock initial',
                'fournisseur'        => 'Stock manuel',
                'quantite_restante'  => $stockManuel,
                'prix_achat'         => $produit->prix_achat ?? 0,
                'prix_vente_suggere' => $produit->prix_vente ?? 0,
                'marge_pourcentage'  => $produit->prix_achat > 0
                    ? round((($produit->prix_vente - $produit->prix_achat) / $produit->prix_achat) * 100, 2)
                    : 0,
            ]);
        }

        $produit->lots_disponibles = $lots;
        return $produit;
    });

    return view('recus.create', compact('produits', 'categories'));
}


public function getProduitsByCategory($categoryId)
{
    try {
        $produits = Produit::with(['variants' => function($query) {
            $query->where('actif', true)->where('quantite_stock', '>', 0);
        }])
        ->where('actif', true)
        ->where('category_id', $categoryId)
        ->where(function($q) {
            $q->where('quantite_stock', '>', 0)
              ->orWhereHas('variants', function($query) {
                  $query->where('actif', true)->where('quantite_stock', '>', 0);
              });
        })
        ->orderBy('nom')
        ->get()
        ->map(function($produit) {
            // ✅ جيب les lots مثل create()
            $lots = \App\Models\Achat::where('produit_id', $produit->id)
                ->where('quantite_restante', '>', 0)
                ->orderBy('date_achat', 'asc')
                ->get()
                ->map(fn($lot) => [
                    'id'                 => $lot->id,
                    'date_achat'         => $lot->date_achat->format('d/m/Y'),
                    'fournisseur'        => $lot->fournisseur ?? 'N/A',
                    'quantite_restante'  => $lot->quantite_restante,
                    'prix_achat'         => $lot->prix_achat,
                    'prix_vente_suggere' => $lot->prix_vente_suggere,
                    'marge_pourcentage'  => $lot->marge_pourcentage,
                ]);

            // ✅ Stock manuel
            $stockFifo   = $lots->sum('quantite_restante');
            $stockManuel = max(0, $produit->quantite_stock - $stockFifo);

            if ($stockManuel > 0) {
                $lots->prepend([
                    'id'                 => 'manuel',
                    'date_achat'         => 'Stock initial',
                    'fournisseur'        => 'Stock manuel',
                    'quantite_restante'  => $stockManuel,
                    'prix_achat'         => $produit->prix_achat ?? 0,
                    'prix_vente_suggere' => $produit->prix_vente ?? 0,
                    'marge_pourcentage'  => $produit->prix_achat > 0
                        ? round((($produit->prix_vente - $produit->prix_achat) / $produit->prix_achat) * 100, 2)
                        : 0,
                ]);
            }

            return [
                'id'             => $produit->id,
                'nom'            => $produit->nom,
                'prix_vente'     => $produit->prix_vente,
                'quantite_stock' => $produit->quantite_stock,
                'has_variants'   => $produit->variants->count() > 0,
                'lots'           => $lots->values()->toArray(), // ✅ الجديد
            ];
        });

        return response()->json([
            'success' => true,
            'produits' => $produits
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * ✅ VERSION FIXED  - BASITA O KHDAM  100%
     */
   // ✅ MÉTHODE STORE MODIFIÉE



// ✅ MÉTHODE STORE CORRIGÉE - Remise appliquée sur produits simples ET variants

public function store(Request $request)
{
    $validated = $request->validate([
        'client_nom'                    => 'required|string|max:255',
        'client_prenom'                 => 'nullable|string|max:255',
        'client_telephone'              => 'nullable|string|max:20',
        'client_email'                  => 'nullable|email|max:255',
        'client_adresse'                => 'nullable|string',
        'equipement'                    => 'nullable|string|max:255',
        'details'                       => 'nullable|string',
        'type_garantie'                 => 'required|in:30_jours,90_jours,180_jours,360_jours,sans_garantie',
        'remise'                        => 'nullable|numeric|min:0',
        'tva'                           => 'nullable|numeric|min:0',
        'mode_paiement'                 => 'required|in:especes,carte,cheque,virement,credit',
        'montant_paye'                  => 'nullable|numeric|min:0',
        'date_paiement'                 => 'nullable|date',
        'notes'                         => 'nullable|string',
        'items'                         => 'required|array|min:1',
        'items.*.produit_id'            => 'required|exists:produits,id',
        'items.*.product_variant_id'    => 'nullable|exists:product_variants,id',
        'items.*.quantite'              => 'required|integer|min:1',
        'items.*.remise_appliquee'      => 'nullable',
        'items.*.remise_pourcentage'    => 'nullable|numeric|min:0|max:100',
        'items.*.remise_montant'        => 'nullable|numeric|min:0',
        'items.*.achat_id'              => 'nullable',
        'items.*.prix_unitaire'         => 'nullable|numeric|min:0',
        'items.*.prix_achat'            => 'nullable|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        $recu = RecuUcg::create([
            'user_id'          => auth()->id(),
            'client_nom'       => $validated['client_nom'],
            'client_prenom'    => $validated['client_prenom'] ?? null,
            'client_telephone' => $validated['client_telephone'] ?? null,
            'client_email'     => $validated['client_email'] ?? null,
            'client_adresse'   => $validated['client_adresse'] ?? null,
            'equipement'       => $validated['equipement'] ?? null,
            'details'          => $validated['details'] ?? null,
            'type_garantie'    => $validated['type_garantie'],
            'remise'           => $validated['remise'] ?? 0,
            'tva'              => $validated['tva'] ?? 0,
            'mode_paiement'    => $validated['mode_paiement'],
            'date_paiement'    => $validated['date_paiement'] ?? now(),
            'notes'            => $validated['notes'] ?? null,
        ]);

        foreach ($validated['items'] as $itemData) {

            $remiseData = [
                'remise_appliquee'   => !empty($itemData['remise_appliquee']) && $itemData['remise_appliquee'] == '1',
                'remise_pourcentage' => $itemData['remise_pourcentage'] ?? 0,
                'remise_montant'     => $itemData['remise_montant'] ?? 0,
            ];

            if (!empty($itemData['product_variant_id'])) {
                // ✅ Produit avec variant
                $variant = ProductVariant::findOrFail($itemData['product_variant_id']);

                if ($variant->quantite_stock < $itemData['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$variant->full_name}. Stock: {$variant->quantite_stock}");
                }

                $recu->items()->create(array_merge([
                    'produit_id'         => $itemData['produit_id'],
                    'product_variant_id' => $itemData['product_variant_id'],
                    'quantite'           => $itemData['quantite'],
                ], $remiseData));

            } else {
                // ✅ Produit simple
                $produit = Produit::findOrFail($itemData['produit_id']);

                // ✅ Vérification stock selon lot choisi
                $achatId = $itemData['achat_id'] ?? null;

                if ($achatId && $achatId !== 'manuel') {
                    // Vérifier stock du lot spécifique
                    $achat = \App\Models\Achat::findOrFail($achatId);
                    if ($achat->quantite_restante < $itemData['quantite']) {
                        throw new \Exception("Stock insuffisant dans ce lot pour {$produit->nom}. Stock lot: {$achat->quantite_restante}");
                    }
                } else {
                    // Vérifier stock global
                    if ($produit->quantite_stock < $itemData['quantite']) {
                        throw new \Exception("Stock insuffisant pour {$produit->nom}. Stock: {$produit->quantite_stock}");
                    }
                }

                $recu->items()->create(array_merge([
                    'produit_id'    => $itemData['produit_id'],
                    'quantite'      => $itemData['quantite'],
                    'achat_id'      => ($achatId && $achatId !== 'manuel') ? $achatId : null,
                    'prix_unitaire' => !empty($itemData['prix_unitaire']) ? $itemData['prix_unitaire'] : null,
                    'prix_achat'    => !empty($itemData['prix_achat']) ? $itemData['prix_achat'] : null,
                ], $remiseData));
            }
        }

        $recu->refresh();

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
        // ✅ Charger TOUTES les catégories
        $categories = \App\Models\Category::orderBy('nom')->get();

        // ✅ Charger TOUS les produits actifs (sans filtre de stock)
        $produits = Produit::with(['category', 'variants' => function($query) {
            $query->where('actif', true); // Pas de filtre sur quantite_stock
        }])
        ->where('actif', true)
        ->orderBy('nom')
        ->get();

        // ✅ Charger les relations complètes
        $recu->load(['items.produit.category', 'items.variant', 'paiements']);

        return view('recus.edit', compact('recu', 'produits', 'categories'));
    }

    /**
     * ✅ MÉTHODE UPDATE CORRIGÉE - Fix paiement en double
     */
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
            // ✅ Validation remise
            'items.*.remise_appliquee' => 'nullable',
            'items.*.remise_pourcentage' => 'nullable|numeric|min:0|max:100',
            'items.*.remise_montant' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1️⃣ Sauvegarder les anciens items AVANT de les supprimer
            $oldItems = $recu->items()->get();

            // 2️⃣ Soft delete anciens items (stock restauré automatiquement via event)
            foreach ($oldItems as $item) {
                $item->delete();
            }

            // 3️⃣ Mettre à jour les informations du reçu
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

            // ✅ Mettre à jour created_at avec la date de paiement
            $datePaiement = $validated['date_paiement'] ?? now();
            $recu->timestamps = false;
            DB::table('recus_ucgs')
                ->where('id', $recu->id)
                ->update(['created_at' => $datePaiement]);
            $recu->timestamps = true;

            // 4️⃣ Créer les nouveaux items avec remise + vérification stock
            foreach ($validated['items'] as $itemData) {

                // ✅ Champs remise communs
                $remiseData = [
                    'remise_appliquee' => !empty($itemData['remise_appliquee']) && $itemData['remise_appliquee'] == '1',
                    'remise_pourcentage' => $itemData['remise_pourcentage'] ?? 0,
                    'remise_montant' => $itemData['remise_montant'] ?? 0,
                ];

                if (!empty($itemData['product_variant_id'])) {
                    // ✅ Produit avec variant
                    $variant = ProductVariant::findOrFail($itemData['product_variant_id']);

                    $stockDisponible = $variant->quantite_stock;
                    $oldSameVariantItem = $oldItems->where('product_variant_id', $itemData['product_variant_id'])->first();
                    if ($oldSameVariantItem) {
                        $stockDisponible += $oldSameVariantItem->quantite;
                    }

                    if ($stockDisponible < $itemData['quantite']) {
                        throw new \Exception("Stock insuffisant pour {$variant->full_name}. Stock disponible: {$stockDisponible}");
                    }

                    $recu->items()->create(array_merge([
                        'produit_id' => $itemData['produit_id'],
                        'product_variant_id' => $itemData['product_variant_id'],
                        'quantite' => $itemData['quantite'],
                    ], $remiseData));

                } else {
                    // ✅ Produit simple (sans variant)
                    $produit = Produit::findOrFail($itemData['produit_id']);

                    $stockDisponible = $produit->quantite_stock;
                    $oldSameProductItem = $oldItems->where('produit_id', $itemData['produit_id'])
                                                   ->whereNull('product_variant_id')
                                                   ->first();
                    if ($oldSameProductItem) {
                        $stockDisponible += $oldSameProductItem->quantite;
                    }

                    if ($stockDisponible < $itemData['quantite']) {
                        throw new \Exception("Stock insuffisant pour {$produit->nom}. Stock disponible: {$stockDisponible}");
                    }

                    $recu->items()->create(array_merge([
                        'produit_id' => $itemData['produit_id'],
                        'quantite' => $itemData['quantite'],
                    ], $remiseData));
                }
            }

            // 5️⃣ Recalculer le total
            $recu->refresh();
            $recu->calculerTotal();

            // 6️⃣ Gérer le paiement SANS DOUBLER
            $montantPaye = $validated['montant_paye'] ?? 0;
            $montantDejaPayé = $recu->paiements->sum('montant');

            if ($montantPaye != $montantDejaPayé) {
                $recu->paiements()->delete();
                $recu->updateQuietly(['montant_paye' => 0]);

                if ($montantPaye > 0) {
                    $recu->ajouterPaiement(
                        $montantPaye,
                        $validated['mode_paiement'],
                        null
                    );
                } else {
                    $recu->update([
                        'montant_paye' => 0,
                        'reste' => $recu->total,
                        'statut_paiement' => RecuUcg::STATUT_PAIEMENT_IMPAYE
                    ]);
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
        // ⚠️ IMPORTANT: Ma n3awdoch stock hna!
        // Stock déjà restauré wakt soft delete (deleting event)
        
        // 1️⃣ Supprimer définitivement les items (sans toucher au stock)
        $items = \App\Models\RecuItem::onlyTrashed()
            ->where('recu_ucg_id', $recu->id)
            ->get();
            
        foreach ($items as $item) {
            // ✅ Force delete direct - pas de modification stock
            $item->forceDelete();
        }
        
        // 2️⃣ Supprimer les paiements définitivement
        \App\Models\Paiement::onlyTrashed()
            ->where('recu_ucg_id', $recu->id)
            ->forceDelete();
        
        // 3️⃣ (Optionnel) Supprimer mouvements de stock pour nettoyage
        // ⚠️ Recommandé: GARDER les mouvements pour historique/audit
        // \App\Models\StockMovement::where('recu_ucg_id', $recu->id)->delete();
        
        // 4️⃣ Supprimer définitivement le reçu
        $recu->forceDelete();

        DB::commit();
        
        \Log::info("🗑️ PERMANENT: Reçu #{$recu->numero_recu} supprimé définitivement (stock inchangé)");
        
        return redirect()
            ->route('recus.trash')
            ->with('success', "Reçu {$recu->numero_recu} supprimé définitivement!");
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error("❌ Erreur force delete: " . $e->getMessage());
        return back()->with('error', "Erreur lors de la suppression définitive: " . $e->getMessage());
    }
}


public function appliquerRemiseItem(Request $request, RecuUcg $recu, RecuItem $item)
{
    // Vérifier que l'item appartient au reçu
    if ($item->recu_ucg_id !== $recu->id) {
        return response()->json([
            'success' => false,
            'message' => 'Article invalide'
        ], 400);
    }

    $validated = $request->validate([
        'type_remise' => 'required|in:montant,pourcentage',
        'valeur_remise' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();
    try {
        if ($validated['type_remise'] === 'pourcentage') {
            if ($validated['valeur_remise'] > 100) {
                throw new \Exception("Pourcentage maximum: 100%");
            }
            
            $item->update([
                'remise_appliquee' => true,
                'remise_pourcentage' => $validated['valeur_remise'],
                'remise_montant' => 0,
            ]);
            
        } else {
            if ($validated['valeur_remise'] > $item->sous_total) {
                throw new \Exception("Remise ne peut pas dépasser le sous-total");
            }
            
            $item->update([
                'remise_appliquee' => true,
                'remise_montant' => $validated['valeur_remise'],
                'remise_pourcentage' => 0,
            ]);
        }

        $item->refresh();
        $item->calculerRemise();
        $item->save();
        
        $recu->calculerTotal();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Remise appliquée avec succès',
            'item' => [
                'sous_total' => number_format($item->sous_total, 2),
                'remise' => number_format($item->montant_remise, 2),
                'total_apres_remise' => number_format($item->total_apres_remise, 2),
                'marge_totale' => number_format($item->marge_totale, 2),
            ],
            'recu' => [
                'total' => number_format($recu->total, 2),
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * ✅ Supprimer remise d'un article
 */
public function supprimerRemiseItem(RecuUcg $recu, RecuItem $item)
{
    if ($item->recu_ucg_id !== $recu->id) {
        return response()->json([
            'success' => false,
            'message' => 'Article invalide'
        ], 400);
    }

    DB::beginTransaction();
    try {
        $item->update([
            'remise_appliquee' => false,
            'remise_montant' => 0,
            'remise_pourcentage' => 0,
            'total_apres_remise' => $item->sous_total,
        ]);
        
        // Recalculer marge originale
        $item->marge_totale = $item->marge_unitaire * $item->quantite;
        $item->save();
        
        $recu->calculerTotal();

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Remise supprimée'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
}
