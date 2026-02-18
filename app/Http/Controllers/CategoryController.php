<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Produit;
use App\Models\RecuItem;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Afficher toutes les catégories avec statistiques et hiérarchie
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Récupérer les catégories PARENT avec leurs enfants
        $categories = Category::parents()
            ->when($search, function ($query) use ($search) {
                return $query->where('nom', 'like', '%' . $search . '%')
                    ->orWhereHas('children', function($q) use ($search) {
                        $q->where('nom', 'like', '%' . $search . '%');
                    });
            })
            ->with(['children.produits', 'produits'])
            ->withCount('produits')
            ->paginate(10);

        // Ajouter des statistiques détaillées pour chaque catégorie (parent + enfants)
        foreach ($categories as $category) {
            $this->calculateCategoryStats($category);
            
            // Calculer stats pour chaque enfant
            foreach ($category->children as $child) {
                $this->calculateCategoryStats($child);
            }
        }

        // Statistiques globales
        $stats = [
            'total_categories' => Category::count(),
            'total_categories_parent' => Category::parents()->count(),
            'total_produits' => Produit::count(),
            'valeur_stock_total' => Produit::sum(DB::raw('quantite_stock * COALESCE(prix_achat, 0)')),
            'ca_total' => RecuItem::whereHas('recuUcg', function($q) {
                $q->whereIn('statut', ['en_cours', 'livre']);
            })->sum('sous_total'),
        ];

        // Liste des catégories pour les selects (parent seulement)
        $categoriesForSelect = Category::parents()->orderBy('nom')->get();

        return view('categories.index', compact('categories', 'stats', 'categoriesForSelect'));
    }

    /**
     * Calculer les statistiques d'une catégorie
     */
    private function calculateCategoryStats($category)
    {
        // Si c'est un parent, on inclut les produits des enfants
        if ($category->is_parent) {
            $allProduits = $category->allProduits();
            $category->total_produits = $allProduits->count();
            $category->produits_actifs = $allProduits->where('actif', true)->count();
            $category->produits_rupture = $allProduits->where('quantite_stock', '<=', 0)->count();
            $category->valeur_stock = $allProduits->sum(function($p) {
                return $p->quantite_stock * ($p->prix_achat ?? 0);
            });
        } else {
            $category->total_produits = $category->produits()->count();
            $category->produits_actifs = $category->produits()->where('actif', true)->count();
            $category->produits_rupture = $category->produits()->where('quantite_stock', '<=', 0)->count();
            $category->valeur_stock = $category->produits()->sum(DB::raw('quantite_stock * COALESCE(prix_achat, 0)'));
        }

        // CA et marge
        $categoryIds = $category->is_parent 
            ? array_merge([$category->id], $category->children->pluck('id')->toArray())
            : [$category->id];

        $category->ca_total = DB::table('recu_items')
            ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereIn('produits.category_id', $categoryIds)
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at')
            ->sum('recu_items.sous_total');

        $category->marge_totale = DB::table('recu_items')
            ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereIn('produits.category_id', $categoryIds)
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at')
            ->sum('recu_items.marge_totale');

        $category->quantite_vendue = DB::table('recu_items')
            ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereIn('produits.category_id', $categoryIds)
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at')
            ->sum('recu_items.quantite');
    }

    /**
     * Afficher les détails d'une catégorie avec graphiques
     */
    public function show($id)
    {
        $category = Category::with(['produits', 'children.produits', 'parent'])->findOrFail($id);

        // Déterminer les IDs à inclure (catégorie + enfants si parent)
        $categoryIds = $category->is_parent 
            ? array_merge([$category->id], $category->children->pluck('id')->toArray())
            : [$category->id];

        // Top 10 produits les plus vendus
        $topProduits = DB::table('produits')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereIn('produits.category_id', $categoryIds)
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at')
            ->select(
                'produits.id',
                'produits.nom',
                'produits.reference',
                DB::raw('SUM(recu_items.quantite) as quantite_vendue'),
                DB::raw('SUM(recu_items.sous_total) as ca_total'),
                DB::raw('SUM(recu_items.marge_totale) as marge_totale')
            )
            ->groupBy('produits.id', 'produits.nom', 'produits.reference')
            ->orderByDesc('quantite_vendue')
            ->take(10)
            ->get();

        // Évolution des ventes par mois
        $ventesParMois = DB::table('recu_items')
            ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereIn('produits.category_id', $categoryIds)
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at')
            ->where('recus_ucgs.created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(recus_ucgs.created_at, "%Y-%m") as mois'),
                DB::raw('SUM(recu_items.sous_total) as ca'),
                DB::raw('SUM(recu_items.marge_totale) as marge')
            )
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Statistiques globales
        $this->calculateCategoryStats($category);
        $stats = [
            'total_produits' => $category->total_produits,
            'produits_actifs' => $category->produits_actifs,
            'produits_rupture' => $category->produits_rupture,
            'valeur_stock' => $category->valeur_stock,
            'ca_total' => $category->ca_total,
            'marge_totale' => $category->marge_totale,
        ];

        return view('categories.show', compact('category', 'topProduits', 'ventesParMois', 'stats'));
    }

    public function create()
    {
        $categoriesParent = Category::parents()->orderBy('nom')->get();
        return view('categories.create', compact('categoriesParent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create($request->all());
        
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie ajoutée avec succès');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categoriesParent = Category::parents()->where('id', '!=', $id)->orderBy('nom')->get();
        return view('categories.edit', compact('category', 'categoriesParent'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $id
        ]);

        // Vérifier qu'on ne crée pas une boucle
        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            if ($parent && $parent->parent_id == $id) {
                return back()->withErrors(['parent_id' => 'Impossible: créerait une boucle dans la hiérarchie']);
            }
            
            // Vérifier si le nouveau parent est un enfant de cette catégorie
            if ($category->children()->where('id', $request->parent_id)->exists()) {
                return back()->withErrors(['parent_id' => 'Impossible: vous ne pouvez pas sélectionner une de vos sous-catégories comme parent']);
            }
        }

        // Si cette catégorie parent devient une sous-catégorie, ses enfants deviennent orphelins (parent)
        if ($request->parent_id && $category->children()->count() > 0) {
            // Transformer tous les enfants en catégories parent
            $category->children()->update(['parent_id' => null]);
            
            $message = 'Catégorie modifiée avec succès. Ses ' . $category->children()->count() . ' sous-catégorie(s) sont maintenant des catégories parent.';
        } else {
            $message = 'Catégorie modifiée avec succès';
        }

        $category->update($request->all());
        
        return redirect()->route('categories.index')
            ->with('success', $message);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Vérifier s'il y a des produits associés (dans cette catégorie ou ses enfants)
        $totalProduits = $category->is_parent 
            ? $category->allProduits()->count()
            : $category->produits()->count();

        if ($totalProduits > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer: cette catégorie contient ' . $totalProduits . ' produit(s)');
        }

        // Vérifier s'il y a des sous-catégories
        if ($category->children()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer: cette catégorie contient des sous-catégories');
        }
        
        $category->delete();
        
        return redirect()->route('categories.index')
            ->with('success', 'Catégorie supprimée avec succès');
    }

    /**
     * ✅ Changer le parent d'une catégorie (AJAX)
     */
    public function changeParent(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'parent_id' => 'nullable|exists:categories,id|not_in:' . $id
        ]);

        // Vérifier boucle
        if ($request->parent_id) {
            $parent = Category::find($request->parent_id);
            if ($parent->parent_id == $id) {
                return response()->json(['success' => false, 'message' => 'Impossible: créerait une boucle'], 400);
            }
        }

        $category->update(['parent_id' => $request->parent_id]);

        return response()->json(['success' => true, 'message' => 'Parent modifié avec succès']);
    }

    /**
     * ✅ Déplacer des produits vers une autre catégorie (AJAX)
     */
    public function moveProducts(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'target_category_id' => 'required|exists:categories,id',
        ]);

        $moved = Produit::where('category_id', $request->category_id)
            ->update(['category_id' => $request->target_category_id]);

        return response()->json([
            'success' => true, 
            'message' => $moved . ' produit(s) déplacé(s) avec succès'
        ]);
    }

    public function hierarchy()
{
    return view('categories.hierarchy');
}
}