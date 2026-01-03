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
     * Afficher toutes les catégories avec statistiques
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Récupérer les catégories avec leurs statistiques
        $categories = Category::when($search, function ($query) use ($search) {
            return $query->where('nom', 'like', '%' . $search . '%');
        })
        ->withCount('produits') // Nombre de produits par catégorie
        ->paginate(10);

        // Ajouter des statistiques détaillées pour chaque catégorie
        foreach ($categories as $category) {
            // Nombre total de produits
            $category->total_produits = $category->produits()->count();
            
            // Nombre de produits actifs
            $category->produits_actifs = $category->produits()->where('actif', true)->count();
            
            // Nombre de produits en rupture de stock
            $category->produits_rupture = $category->produits()
                ->where('quantite_stock', '<=', 0)
                ->count();
            
            // Valeur totale du stock
            $category->valeur_stock = $category->produits()
                ->sum(DB::raw('quantite_stock * COALESCE(prix_achat, 0)'));
            
            // Chiffre d'affaires total de la catégorie
            $category->ca_total = DB::table('recu_items')
                ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->where('produits.category_id', $category->id)
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('produits.deleted_at')
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.sous_total');
            
            // Marge totale de la catégorie
            $category->marge_totale = DB::table('recu_items')
                ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->where('produits.category_id', $category->id)
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('produits.deleted_at')
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.marge_totale');
            
            // Quantité totale vendue
            $category->quantite_vendue = DB::table('recu_items')
                ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->where('produits.category_id', $category->id)
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('produits.deleted_at')
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.quantite');
        }

        // Statistiques globales
        $stats = [
            'total_categories' => Category::count(),
            'total_produits' => Produit::count(),
            'valeur_stock_total' => Produit::sum(DB::raw('quantite_stock * COALESCE(prix_achat, 0)')),
            'ca_total' => RecuItem::whereHas('recuUcg', function($q) {
                $q->whereIn('statut', ['en_cours', 'livre']);
            })->sum('sous_total'),
        ];

        return view('categories.index', compact('categories', 'stats'));
    }

    /**
     * Afficher les détails d'une catégorie avec graphiques
     */
    public function show($id)
    {
        $category = Category::with('produits')->findOrFail($id);

        // Top 10 produits les plus vendus de cette catégorie
        $topProduits = DB::table('produits')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->where('produits.category_id', $id)
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

        // Évolution des ventes par mois (6 derniers mois)
        $ventesParMois = DB::table('recu_items')
            ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->where('produits.category_id', $id)
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

        // Statistiques globales de la catégorie
        $stats = [
            'total_produits' => $category->produits()->count(),
            'produits_actifs' => $category->produits()->where('actif', true)->count(),
            'produits_rupture' => $category->produits()->where('quantite_stock', '<=', 0)->count(),
            'valeur_stock' => $category->produits()->sum(DB::raw('quantite_stock * COALESCE(prix_achat, 0)')),
            'ca_total' => DB::table('recu_items')
                ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->where('produits.category_id', $id)
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('produits.deleted_at')
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.sous_total'),
            'marge_totale' => DB::table('recu_items')
                ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->where('produits.category_id', $id)
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('produits.deleted_at')
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.marge_totale'),
        ];

        return view('categories.show', compact('category', 'topProduits', 'ventesParMois', 'stats'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        Category::create($request->all());
        return redirect()->route('categories.index')->with('success', 'Catégorie ajoutée.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nom' => 'required|string|max:255']);
        $category = Category::findOrFail($id);
        $category->update($request->all());
        return redirect()->route('categories.index')->with('success', 'Catégorie modifiée.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Vérifier s'il y a des produits associés
        if ($category->produits()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
        }
        
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée.');
    }
}