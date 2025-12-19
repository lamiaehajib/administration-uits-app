<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProductVariantController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:produit-list', ['only' => ['index', 'show']]);
        $this->middleware('permission:produit-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:produit-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:produit-delete', ['only' => ['destroy']]);
    }

    /**
     * 📋 عرض جميع variants لمنتج معين
     */
    public function index(Produit $produit)
    {
        $variants = $produit->variants()
            ->withCount('recuItems')
            ->orderByDesc('actif')
            ->orderBy('ram')
            ->paginate(20);

        // احصائيات
        $stats = [
            'total_variants' => $produit->variants()->count(),
            'variants_actifs' => $produit->variants()->where('actif', true)->count(),
            'stock_total' => $produit->variants()->sum('quantite_stock'),
            'total_vendu' => $produit->variants()
                ->withSum('recuItems', 'quantite')
                ->get()
                ->sum('recu_items_sum_quantite'),
        ];

        return view('produits.index', compact('produit', 'variants', 'stats'));
    }

    /**
     * 📝 عرض نموذج إنشاء variant جديد
     */
    public function create(Produit $produit)
    {
        return view('produits.variants.create', compact('produit'));
    }

    /**
     * 💾 حفظ variant جديد
     */
    public function store(Request $request, Produit $produit)
    {
        $validated = $request->validate([
            'ram' => 'nullable|string|max:50',
            'ssd' => 'nullable|string|max:50',
            'cpu' => 'nullable|string|max:100',
            'gpu' => 'nullable|string|max:100',
            'ecran' => 'nullable|string|max:100',
            'autres_specs' => 'nullable|array',
            'prix_supplement' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'actif' => 'boolean',
        ]);

        // ✅ التحقق من عدم تكرار نفس المواصفات
        $exists = $produit->variants()
            ->where('ram', $validated['ram'] ?? null)
            ->where('ssd', $validated['ssd'] ?? null)
            ->where('cpu', $validated['cpu'] ?? null)
            ->where('gpu', $validated['gpu'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['ram' => 'Ce variant existe déjà avec ces mêmes spécifications.'])
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $validated['produit_id'] = $produit->id;
            $validated['actif'] = $request->boolean('actif', true);

            $variant = ProductVariant::create($validated);

            // ✅ Mettre à jour le stock total du produit
            $this->updateProduitStockTotal($produit);

            DB::commit();

            return redirect()
                ->route('produits.index', $produit)
                ->with('success', "Variant '{$variant->variant_name}' ajouté avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * 👁️ عرض تفاصيل variant محدد
     */
    public function show(Produit $produit, ProductVariant $variant)
    {
        $variant->load(['produit', 'recuItems.recuUcg']);

        // احصائيات الvariant
        $stats = [
            'total_ventes' => $variant->recuItems()
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('quantite'),
            
            'ca_total' => $variant->recuItems()
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('sous_total'),
            
            'marge_totale' => $variant->recuItems()
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('marge_totale'),
        ];

        // آخر 10 مبيعات
        $dernieresVentes = $variant->recuItems()
            ->with('recuUcg')
            ->latest()
            ->take(10)
            ->get();

        return view('produits.variants.show', compact('variant', 'stats', 'dernieresVentes'));
    }

    /**
     * ✏️ عرض نموذج تعديل variant
     */
    public function edit(Produit $produit, ProductVariant $variant)
    {
        return view('produits.variants.edit', compact('produit', 'variant'));
    }

    /**
     * 🔄 تحديث variant
     */
    public function update(Request $request, Produit $produit, ProductVariant $variant)
{
    $validated = $request->validate([
        'ram' => 'nullable|string|max:50',
        'ssd' => 'nullable|string|max:50',
        'cpu' => 'nullable|string|max:100',
        'gpu' => 'nullable|string|max:100',
        'ecran' => 'nullable|string|max:100',
        'autres_specs' => 'nullable|array',
        'prix_supplement' => 'required|numeric|min:0',
        'quantite_stock' => 'required|integer|min:0',
        'actif' => 'boolean',
    ]);

    // ✅ التحقق من عدم تكرار المواصفات (باستثناء الvariant الحالي)
    $exists = $produit->variants()
        ->where('id', '!=', $variant->id)
        ->where('ram', $validated['ram'] ?? null)
        ->where('ssd', $validated['ssd'] ?? null)
        ->where('cpu', $validated['cpu'] ?? null)
        ->where('gpu', $validated['gpu'] ?? null)
        ->exists();

    if ($exists) {
        // ✅ Retour JSON pour AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Un autre variant existe déjà avec ces mêmes spécifications.',
                'errors' => ['ram' => ['Un autre variant existe déjà avec ces mêmes spécifications.']]
            ], 422);
        }
        
        return back()
            ->withErrors(['ram' => 'Un autre variant existe déjà avec ces mêmes spécifications.'])
            ->withInput();
    }

    DB::beginTransaction();
    try {
        $validated['actif'] = $request->boolean('actif');
        $variant->update($validated);

        // ✅ Mettre à jour le stock total du produit
        $this->updateProduitStockTotal($produit);

        DB::commit();

        // ✅ Retour JSON pour AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Variant '{$variant->variant_name}' mis à jour avec succès!",
                'variant' => $variant->fresh()
            ]);
        }

        return redirect()
            ->route('produits.index', $produit)
            ->with('success', "Variant '{$variant->variant_name}' mis à jour avec succès!");

    } catch (\Exception $e) {
        DB::rollBack();
        
        // ✅ Retour JSON pour AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 500);
        }
        
        return back()
            ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
            ->withInput();
    }
}

    /**
     * 🗑️ حذف variant
     */
    public function destroy(Produit $produit, ProductVariant $variant)
    {
        // ✅ التحقق من عدم وجود مبيعات
        if ($variant->hasVentes()) {
            return back()->with('error', 'Impossible de supprimer ce variant car il a des ventes associées.');
        }

        DB::beginTransaction();
        try {
            $variantName = $variant->variant_name;
            $variant->delete();

            // ✅ Mettre à jour le stock total du produit
            $this->updateProduitStockTotal($produit);

            DB::commit();

            return redirect()
                ->route('produits.index', $produit)
                ->with('success', "Variant '{$variantName}' supprimé avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * 📊 Ajustement rapide du stock
     */
    public function ajusterStock(Request $request, Produit $produit, ProductVariant $variant)
    {
        $validated = $request->validate([
            'quantite' => 'required|integer',
            'type' => 'required|in:ajout,retrait,correction',
            'motif' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $stockAvant = $variant->quantite_stock;

            switch ($validated['type']) {
                case 'ajout':
                    $variant->increment('quantite_stock', abs($validated['quantite']));
                    break;

                case 'retrait':
                    if ($stockAvant < abs($validated['quantite'])) {
                        throw new \Exception('Stock insuffisant pour ce retrait.');
                    }
                    $variant->decrement('quantite_stock', abs($validated['quantite']));
                    break;

                case 'correction':
                    $variant->update(['quantite_stock' => abs($validated['quantite'])]);
                    break;
            }

            $variant->refresh();

            // Créer mouvement de stock
            \App\Models\StockMovement::create([
                'produit_id' => $produit->id,
                'user_id' => auth()->id(),
                'type' => 'ajustement',
                'quantite' => abs($validated['quantite']),
                'stock_avant' => $stockAvant,
                'stock_apres' => $variant->quantite_stock,
                'motif' => $validated['motif'] . " (Variant: {$variant->variant_name})",
                'reference' => "VARIANT-{$variant->id}",
            ]);

            // ✅ Mettre à jour le stock total du produit
            $this->updateProduitStockTotal($produit);

            DB::commit();

            return back()->with('success', 'Stock ajusté avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * 🔄 Dupliquer un variant existant
     */
    public function duplicate(Produit $produit, ProductVariant $variant)
    {
        DB::beginTransaction();
        try {
            $newVariant = $variant->replicate();
            $newVariant->sku = null; // Sera généré automatiquement
            $newVariant->quantite_stock = 0; // Reset stock
            $newVariant->actif = false; // Désactivé par défaut
            $newVariant->save();

            DB::commit();

            return redirect()
                ->route('produits.variants.edit', [$produit, $newVariant])
                ->with('success', 'Variant dupliqué! Modifiez les spécifications avant activation.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * 🔄 Activer/Désactiver variant
     */
    public function toggleActif(Produit $produit, ProductVariant $variant)
    {
        try {
            $variant->update(['actif' => !$variant->actif]);

            $status = $variant->actif ? 'activé' : 'désactivé';
            return back()->with('success', "Variant {$status} avec succès!");

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    // ==================== API ENDPOINTS ====================

    /**
     * 🌐 API: Récupérer les variants d'un produit (pour AJAX)
     */
    /**
 * 🌐 API: Récupérer les variants d'un produit (pour AJAX)
 * ✅ FIX: Accepte maintenant un ID numérique au lieu de model binding
 */
public function getVariants($id)
{
    $produit = Produit::findOrFail($id);
    
    // Pour l'administration, on retire généralement les filtres "actif" 
    // et "stock > 0" pour pouvoir gérer tous les variants.
    $variants = $produit->variants()
        ->orderBy('created_at', 'desc') // Optionnel : voir les plus récents en premier
        ->get()
        ->map(function ($variant) {
            return [
                'id' => $variant->id,
                'variant_name' => $variant->variant_name,
                'full_name' => $variant->full_name,
                'sku' => $variant->sku,
                'specs' => [
                    'ram' => $variant->ram,
                    'ssd' => $variant->ssd,
                    'cpu' => $variant->cpu,
                    'gpu' => $variant->gpu,
                    'ecran' => $variant->ecran,
                ],
                'prix_vente_final' => (float) $variant->prix_vente_final,
                'stock' => $variant->quantite_stock,
                'is_alert_stock' => $variant->is_alert_stock,
                'actif' => $variant->actif, // Important pour afficher le statut dans le modal
            ];
        });

    return response()->json([
        'success' => true,
        'variants' => $variants,
    ]);
}

/**
 * 🌐 API: Informations d'un variant spécifique
 * ✅ FIX: Accepte maintenant un ID numérique
 */
public function getVariant($id)
{
    // ✅ Charger le variant manuellement
    $variant = ProductVariant::with('produit')->findOrFail($id);
    
    return response()->json([
        'success' => true,
        'variant' => [
            'id' => $variant->id,
            'produit_id' => $variant->produit_id,
            'produit_nom' => $variant->produit->nom,
            'variant_name' => $variant->variant_name,
            'full_name' => $variant->full_name,
            'sku' => $variant->sku,
            
            'specs' => [
                'ram' => $variant->ram,
                'ssd' => $variant->ssd,
                'cpu' => $variant->cpu,
                'gpu' => $variant->gpu,
                'ecran' => $variant->ecran,
            ],
            
            'prix_base' => (float) $variant->produit->prix_vente,
            'prix_supplement' => (float) $variant->prix_supplement,
            'prix_vente_final' => (float) $variant->prix_vente_final,
            'prix_achat' => (float) $variant->prix_achat,
            'marge_unitaire' => (float) $variant->marge_unitaire,
            
            'quantite_stock' => $variant->quantite_stock,
            'is_alert_stock' => $variant->is_alert_stock,
            'actif' => $variant->actif,
            
            'description' => $variant->description_complete,
        ]
    ]);
}

    /**
     * 🌐 API: Rechercher variants
     */
    public function search(Request $request)
    {
        $search = $request->input('q');
        
        $variants = ProductVariant::with('produit')
            ->where('actif', true)
            ->where('quantite_stock', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('variant_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('ram', 'like', "%{$search}%")
                    ->orWhere('ssd', 'like', "%{$search}%")
                    ->orWhereHas('produit', function($q) use ($search) {
                        $q->where('nom', 'like', "%{$search}%")
                          ->orWhere('reference', 'like', "%{$search}%");
                    });
            })
            ->limit(20)
            ->get()
            ->map(function($variant) {
                return [
                    'id' => $variant->id,
                    'text' => $variant->full_name,
                    'prix' => $variant->prix_vente_final,
                    'stock' => $variant->quantite_stock,
                ];
            });

        return response()->json($variants);
    }

    // ==================== HELPER METHODS ====================

    /**
     * ✅ Mettre à jour le stock total du produit (somme des variants)
     */
    private function updateProduitStockTotal(Produit $produit)
    {
        $totalStock = $produit->variants()->sum('quantite_stock');
        $produit->update(['quantite_stock' => $totalStock]);
    }

    /**
     * 📊 Export rapport variants (PDF)
     */
    public function exportPDF(Produit $produit)
    {
        $variants = $produit->variants()
            ->withCount('recuItems')
            ->withSum('recuItems', 'quantite')
            ->get();

        $pdf = \PDF::loadView('produits.variants.rapport', compact('produit', 'variants'));
        
        return $pdf->download("variants_{$produit->reference}_" . now()->format('Y-m-d') . ".pdf");
    }
}