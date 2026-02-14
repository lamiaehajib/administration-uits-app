<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Category;
use App\Models\Achat;
use App\Models\RecuItem;
use App\Models\RecuUcg;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:produit-list|produit-create|produit-edit|produit-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:produit-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:produit-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:produit-delete', ['only' => ['destroy']]);
        $this->middleware('permission:produit-rapport', ['only' => ['rapport', 'getTotals']]);
        $this->middleware('permission:produit-export', ['only' => ['exportPDF']]);
    }

    /**
     * عرض جميع المنتجات مع معلومات المبيعات والمارج من RecuItem
     * ✅ AVEC FILTRES PERSISTANTS
     */
   public function index(Request $request)
    {
        $search      = $request->input('search');
        $category_id = $request->input('category_id');
        $statut      = $request->input('statut');
        $sort_by     = $request->input('sort_by', 'nom');
        $sort_order  = $request->input('sort_order', 'asc');

        // ✅ Filtres date pour les statistiques
        $stat_mois   = $request->input('stat_mois');   // ex: "2025-02"
        $stat_annee  = $request->input('stat_annee');  // ex: "2025"

        // Calcul de la période pour les stats
        if ($stat_mois) {
            $dateDebutStats = Carbon::parse($stat_mois . '-01')->startOfMonth();
            $dateFinStats   = Carbon::parse($stat_mois . '-01')->endOfMonth();
        } elseif ($stat_annee) {
            $dateDebutStats = Carbon::create($stat_annee, 1, 1)->startOfYear();
            $dateFinStats   = Carbon::create($stat_annee, 12, 31)->endOfYear();
        } else {
            $dateDebutStats = null;
            $dateFinStats   = null;
        }

        $categories = Category::orderBy('nom')->get();

        $query = Produit::with(['category']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('reference', 'like', '%' . $search . '%');
            });
        }
        if ($category_id) {
            $query->where('category_id', $category_id);
        }
        if ($statut) {
            switch ($statut) {
                case 'actif':
                    $query->where('actif', true);
                    break;
                case 'inactif':
                    $query->where('actif', false);
                    break;
                case 'alerte_stock':
                    $query->whereColumn('quantite_stock', '<=', 'stock_alerte');
                    break;
            }
        }

        switch ($sort_by) {
            case 'prix_vente':
                $query->orderBy('prix_vente', $sort_order);
                break;
            case 'stock':
                $query->orderBy('quantite_stock', $sort_order);
                break;
            case 'nom':
            default:
                $query->orderBy('nom', $sort_order);
                break;
        }

        $produits = $query->paginate(10)->appends([
            'search'      => $search,
            'category_id' => $category_id,
            'statut'      => $statut,
            'sort_by'     => $sort_by,
            'sort_order'  => $sort_order,
            'stat_mois'   => $stat_mois,
            'stat_annee'  => $stat_annee,
        ]);

        foreach ($produits as $produit) {
            // Quantité vendue (filtrée par période si applicable)
            $qVendue = RecuItem::where('produit_id', $produit->id)
                ->whereHas('recuUcg', function($q) use ($dateDebutStats, $dateFinStats) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                    if ($dateDebutStats && $dateFinStats) {
                        $q->whereBetween('created_at', [$dateDebutStats, $dateFinStats]);
                    }
                });
            $produit->quantite_vendue = $qVendue->sum('quantite');

            // Total ventes CA brut (filtré)
            $produit->total_vendu_montant = RecuItem::where('produit_id', $produit->id)
                ->whereHas('recuUcg', function($q) use ($dateDebutStats, $dateFinStats) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                    if ($dateDebutStats && $dateFinStats) {
                        $q->whereBetween('created_at', [$dateDebutStats, $dateFinStats]);
                    }
                })
                ->sum('sous_total');

            // ✅ MARGE = profit réel, indépendant de la remise (filtré par période)
            $produit->marge_totale = RecuItem::where('produit_id', $produit->id)
                ->whereHas('recuUcg', function($q) use ($dateDebutStats, $dateFinStats) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                    if ($dateDebutStats && $dateFinStats) {
                        $q->whereBetween('created_at', [$dateDebutStats, $dateFinStats]);
                    }
                })
                ->sum('marge_totale');

            // Dernier prix d'achat
            $dernierAchat = Achat::where('produit_id', $produit->id)
                ->latest('date_achat')
                ->first();
            $produit->dernier_prix_achat = $dernierAchat ? $dernierAchat->prix_achat : ($produit->prix_achat ?? 0);

            // Catégorie
            $produit->categorie_nom = $produit->category ? $produit->category->nom : 'N/A';

            // Marge en pourcentage
            if ($produit->prix_achat > 0 && $produit->prix_vente > 0) {
                $produit->marge_pourcentage = (($produit->prix_vente - $produit->prix_achat) / $produit->prix_achat) * 100;
            } else {
                $produit->marge_pourcentage = 0;
            }
        }

        // ✅ STATISTIQUES GLOBALES (filtrées par période)
        $statsGlobales = $this->getStatsGlobales($dateDebutStats, $dateFinStats);

        // Années disponibles pour le filtre
        $anneesDisponibles = RecuUcg::whereIn('statut', ['en_cours', 'livre'])
            ->selectRaw('YEAR(created_at) as annee')
            ->distinct()
            ->orderByDesc('annee')
            ->pluck('annee');

        return view('produits.index', compact(
            'produits',
            'categories',
            'search',
            'category_id',
            'statut',
            'sort_by',
            'sort_order',
            'stat_mois',
            'stat_annee',
            'statsGlobales',
            'anneesDisponibles'
        ));
    }

    /**
     * ✅ Calcul des statistiques globales filtrées par période
     */
    private function getStatsGlobales($dateDebut = null, $dateFin = null)
    {
        // Total produits actifs
        $totalProduits = Produit::count();
        $produitsActifs = Produit::where('actif', true)->count();
        $alertesStock = Produit::whereColumn('quantite_stock', '<=', 'stock_alerte')->count();

        // CA total (filtré)
        $caQuery = RecuUcg::whereIn('statut', ['en_cours', 'livre']);
        if ($dateDebut && $dateFin) {
            $caQuery->whereBetween('created_at', [$dateDebut, $dateFin]);
        }
        $caTotalBrut = $caQuery->sum('total');

        // Remises totales (filtrées)
        $remiseQuery = RecuUcg::whereIn('statut', ['en_cours', 'livre']);
        if ($dateDebut && $dateFin) {
            $remiseQuery->whereBetween('created_at', [$dateDebut, $dateFin]);
        }
        $totalRemises = $remiseQuery->sum('remise');

        $caNet = $caTotalBrut - $totalRemises;

        // Marge totale (filtrée)
        $margeQuery = DB::table('recu_items')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('recus_ucgs.deleted_at');
        if ($dateDebut && $dateFin) {
            $margeQuery->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin]);
        }
        $margeTotale = $margeQuery->sum('recu_items.marge_totale');

        // Total ventes (nb de reçus filtrés)
        $nbVentesQuery = RecuUcg::whereIn('statut', ['en_cours', 'livre']);
        if ($dateDebut && $dateFin) {
            $nbVentesQuery->whereBetween('created_at', [$dateDebut, $dateFin]);
        }
        $nbVentes = $nbVentesQuery->count();

        // Total unités vendues (filtrées)
        $unitesVenduesQuery = RecuItem::whereHas('recuUcg', function($q) use ($dateDebut, $dateFin) {
            $q->whereIn('statut', ['en_cours', 'livre']);
            if ($dateDebut && $dateFin) {
                $q->whereBetween('created_at', [$dateDebut, $dateFin]);
            }
        });
        $unitesVendues = $unitesVenduesQuery->sum('quantite');

        // Valeur stock totale (toujours globale, pas filtrée par date)
        $valeurStockFifo = DB::table('achats')
            ->whereNull('deleted_at')
            ->where('quantite_restante', '>', 0)
            ->selectRaw('SUM(quantite_restante * prix_achat) as total')
            ->value('total') ?? 0;

        $produitsTous = Produit::whereNull('deleted_at')->get();
        $valeurStockNonEnregistre = 0;
        foreach ($produitsTous as $p) {
            $stockFifo = Achat::where('produit_id', $p->id)->where('quantite_restante', '>', 0)->sum('quantite_restante');
            $stockNonEnregistre = max(0, $p->quantite_stock - $stockFifo);
            $valeurStockNonEnregistre += $stockNonEnregistre * ($p->prix_achat ?? 0);
        }
        $valeurStock = $valeurStockFifo + $valeurStockNonEnregistre;

        return [
            'total_produits'  => $totalProduits,
            'produits_actifs' => $produitsActifs,
            'alertes_stock'   => $alertesStock,
            'ca_net'          => $caNet,
            'ca_brut'         => $caTotalBrut,
            'total_remises'   => $totalRemises,
            'marge_totale'    => $margeTotale,
            'nb_ventes'       => $nbVentes,
            'unites_vendues'  => $unitesVendues,
            'valeur_stock'    => $valeurStock,
        ];
    }

    // ===== MÉTHODES INCHANGÉES =====

    public function getTotals(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m'));
        $dateDebut = Carbon::parse($date . '-01')->startOfMonth();
        $dateFin   = Carbon::parse($date . '-01')->endOfMonth();

        $totalAchat = Achat::whereBetween('date_achat', [$dateDebut, $dateFin])->sum('total_achat');
        $totalVente = RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])->whereIn('statut', ['en_cours', 'livre'])->sum('total');
        $totalStock = Produit::sum('quantite_stock');

        $valeurStockFifo = DB::table('achats')->whereNull('deleted_at')->where('quantite_restante', '>', 0)
            ->selectRaw('SUM(quantite_restante * prix_achat) as total')->value('total') ?? 0;

        $produits = Produit::whereNull('deleted_at')->get();
        $valeurStockNonEnregistre = 0;
        foreach ($produits as $produit) {
            $stockFifo = Achat::where('produit_id', $produit->id)->where('quantite_restante', '>', 0)->sum('quantite_restante');
            $stockNonEnregistre = max(0, $produit->quantite_stock - $stockFifo);
            $valeurStockNonEnregistre += $stockNonEnregistre * ($produit->prix_achat ?? 0);
        }
        $valeurStock = $valeurStockFifo + $valeurStockNonEnregistre;

        $totalMarge = DB::table('recu_items')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('recus_ucgs.deleted_at')
            ->sum('recu_items.marge_totale');

        $benefice = $totalMarge;

        return view('produits.totals', compact('totalAchat', 'totalVente', 'totalStock', 'valeurStock', 'benefice', 'totalMarge', 'date'));
    }

    public function exportPDF(Request $request)
    {
        $dateFin   = $request->input('date') ? Carbon::parse($request->input('date'))->endOfMonth() : Carbon::now()->endOfMonth();
        $dateDebut = $dateFin->copy()->startOfMonth();

        $produits = DB::table('produits')
            ->leftJoin('categories', 'produits.category_id', '=', 'categories.id')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', function($join) use ($dateDebut, $dateFin) {
                $join->on('recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                    ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
                    ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                    ->whereNull('recus_ucgs.deleted_at');
            })
            ->whereNull('produits.deleted_at')
            ->whereNotNull('recus_ucgs.id')
            ->select(
                'produits.id', 'produits.nom', 'produits.reference', 'produits.prix_achat',
                'categories.nom as categorie_nom',
                DB::raw('SUM(recu_items.quantite) as quantite_vendue'),
                DB::raw('SUM(recu_items.sous_total) as total_vendu_montant'),
                DB::raw('SUM(recu_items.marge_totale) as marge_totale')
            )
            ->groupBy('produits.id', 'produits.nom', 'produits.reference', 'produits.prix_achat', 'categories.nom')
            ->havingRaw('SUM(recu_items.quantite) > 0')
            ->orderByDesc('quantite_vendue')
            ->get();

        foreach ($produits as $produit) {
            $dernierAchat = Achat::where('produit_id', $produit->id)->latest('date_achat')->first();
            $produit->prix_achat_moyen = $dernierAchat ? $dernierAchat->prix_achat : ($produit->prix_achat ?? 0);
            $produit->categorie_nom = $produit->categorie_nom ?? 'N/A';
        }

        $pdf = Pdf::loadView('produits.rapport_ventes', compact('produits', 'dateDebut', 'dateFin'));
        return $pdf->download('rapport_ventes_' . $dateFin->format('Y-m-d') . '.pdf');
    }

    public function create()
    {
        $categories = Category::all();
        return view('produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255|unique:produits',
            'reference' => 'nullable|string|max:255|unique:produits',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'prix_achat' => 'nullable|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'stock_alerte' => 'nullable|integer|min:0',
            'actif' => 'sometimes|boolean',
        ]);

        $validatedData['actif'] = $request->boolean('actif', true);
        
        if (empty($request->reference)) {
            $lastId = Produit::max('id') ?? 0;
            $validatedData['reference'] = 'PROD-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
        }

        Produit::create($validatedData);

        return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
    }

   public function show($id)
{
    $produit = Produit::with(['category', 'achats', 'stockMovements'])
        ->findOrFail($id);

    // Total achats
    $totalAchats = Achat::where('produit_id', $id)->sum('quantite');
    
    // Total ventes
    $totalVentes = RecuItem::where('produit_id', $id)
        ->whereHas('recuUcg', function($q) {
            $q->whereIn('statut', ['en_cours', 'livre']);
        })
        ->sum('quantite');

    // ✅ CALCUL VALEUR STOCK HYBRIDE (FIFO + Stock Non-Enregistré)
    
    // 1️⃣ Stock enregistré dans les achats (FIFO)
    $stockFifo = Achat::where('produit_id', $id)
        ->where('quantite_restante', '>', 0)
        ->sum('quantite_restante');
    
    $valeurStockFifo = Achat::where('produit_id', $id)
        ->where('quantite_restante', '>', 0)
        ->selectRaw('SUM(quantite_restante * prix_achat) as total')
        ->value('total') ?? 0;
    
    // 2️⃣ Stock NON enregistré (différence entre quantite_stock et achats)
    $stockNonEnregistre = max(0, $produit->quantite_stock - $stockFifo);
    
    // 3️⃣ Valeur du stock non-enregistré (utiliser prix_achat du produit)
    $valeurStockNonEnregistre = $stockNonEnregistre * ($produit->prix_achat ?? 0);
    
    // 4️⃣ TOTAL = FIFO + Non-Enregistré
    $valeurStock = $valeurStockFifo + $valeurStockNonEnregistre;
    
    \Log::info("🔍 Calcul Valeur Stock Produit #{$id}:");
    \Log::info("   Stock FIFO: {$stockFifo} unités = {$valeurStockFifo} DH");
    \Log::info("   Stock Non-Enregistré: {$stockNonEnregistre} unités × {$produit->prix_achat} = {$valeurStockNonEnregistre} DH");
    \Log::info("   💰 TOTAL: {$valeurStock} DH");

    // ✅ CA RÉEL (avec remise proportionnelle)
    // ✅ MARGE = profit réel (prix_vente - prix_achat), indépendant de la remise
    $recusAvecItems = RecuUcg::with(['items' => function($q) use ($id) {
        $q->where('produit_id', $id);
    }])
    ->whereIn('statut', ['en_cours', 'livre'])
    ->whereHas('items', function($q) use ($id) {
        $q->where('produit_id', $id);
    })
    ->get();

    $caTotal     = 0;
    $margeReelle = 0;

    foreach ($recusAvecItems as $recu) {
        // Somme brute de TOUS les items du reçu (pour calculer la proportion)
        $totalBrutRecu = $recu->items->sum('sous_total');

        foreach ($recu->items as $item) {
            if ($item->produit_id != $id) continue;

            // ✅ CA réel = sous_total - part proportionnelle de la remise
            $proportion    = $totalBrutRecu > 0 ? ($item->sous_total / $totalBrutRecu) : 0;
            $remiseProduit = $proportion * ($recu->remise ?? 0);
            $caTotal      += $item->sous_total - $remiseProduit;

            // ✅ MARGE = marge brute telle quelle (prix_vente - prix_achat * quantite)
            // La remise ne touche pas la marge — elle montre le vrai profit réalisé
            $margeReelle += $item->marge_totale;
        }
    }

    $stats = [
        'total_achats' => $totalAchats,
        'total_ventes' => $totalVentes,
        'valeur_stock' => $valeurStock,
        'ca_total'     => $caTotal,
        'marge_totale' => $margeReelle,
    ];

    $derniersAchats = Achat::where('produit_id', $id)
        ->latest('date_achat')
        ->take(5)
        ->get();

    $dernieresVentes = RecuItem::where('produit_id', $id)
        ->with('recuUcg')
        ->latest('created_at')
        ->take(5)
        ->get();

    return view('produits.show', compact('produit', 'stats', 'derniersAchats', 'dernieresVentes'));
}



    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        $categories = Category::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, Produit $produit)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255|unique:produits,nom,' . $produit->id,
            'reference' => 'nullable|string|max:255|unique:produits,reference,' . $produit->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'prix_achat' => 'nullable|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'stock_alerte' => 'nullable|integer|min:0',
            'actif' => 'nullable|boolean',
        ]);

        $validatedData['actif'] = $request->has('actif') ? true : false;

        $produit->update($validatedData);

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);

        $hasVentes = RecuItem::where('produit_id', $id)->exists();
        
        if ($hasVentes) {
            return back()->with('error', 'Impossible de supprimer ce produit car il a des ventes associées.');
        }

        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }

    public function getProduitsByCategory($category_id)
    {
        $produits = Produit::where('category_id', $category_id)
            ->where('actif', true)
            ->where('quantite_stock', '>', 0)
            ->select('id', 'nom', 'reference', 'prix_vente', 'quantite_stock', 'prix_achat')
            ->get();

        return response()->json($produits);
    }

    public function rapport(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth());
        $dateFin = $request->input('date_fin', now()->endOfMonth());

        // ✅ Top ventes avec marge réelle
        $topVentes = $this->getTopProduits($dateDebut, $dateFin, 'quantite_vendue');
        $topMarges = $this->getTopProduits($dateDebut, $dateFin, 'marge_totale'); // ✅ Corrigé ici

        $alerteStock = Produit::whereColumn('quantite_stock', '<=', 'stock_alerte')
            ->where('actif', true)
            ->orderBy('quantite_stock')
            ->get();

        return view('produits.rapport', compact(
            'topVentes',
            'topMarges',
            'alerteStock',
            'dateDebut',
            'dateFin'
        ));
    }

    /**
     * ✅ HELPER - Calcul top produits avec marge réelle
     * @param string $orderBy 'quantite_vendue' ou 'marge_totale'
     */
    private function getTopProduits($dateDebut, $dateFin, $orderBy = 'quantite_vendue')
    {
        $produits = Produit::whereHas('recuItems.recuUcg', function($q) use ($dateDebut, $dateFin) {
            $q->whereBetween('created_at', [$dateDebut, $dateFin])
              ->whereIn('statut', ['en_cours', 'livre']);
        })
        ->with(['recuItems' => function($q) use ($dateDebut, $dateFin) {
            $q->whereHas('recuUcg', function($q2) use ($dateDebut, $dateFin) {
                $q2->whereBetween('created_at', [$dateDebut, $dateFin])
                   ->whereIn('statut', ['en_cours', 'livre']);
            });
        }])
        ->get();

        $results = $produits->map(function($produit) {
            $quantite_vendue = 0;
            $total_ventes = 0;
            $marge_reelle = 0;

            foreach ($produit->recuItems as $item) {
                $recu = $item->recuUcg;
                
                $quantite_vendue += $item->quantite;
                $total_ventes += $item->sous_total;
                
                // Marge brute
                $marge_item = $item->marge_totale;
                
                // ✅ Si remise appliquée sur CET item
                if ($recu && $recu->remise > 0 && $item->remise_appliquee) {
                    $marge_item -= $recu->remise;
                }
                
                $marge_reelle += $marge_item;
            }

            return (object)[
                'id' => $produit->id,
                'nom' => $produit->nom,
                'reference' => $produit->reference,
                'quantite_vendue' => $quantite_vendue,
                'total_ventes' => $total_ventes,
                'marge_totale' => $marge_reelle, // ✅ Utiliser marge_totale pour la vue
            ];
        });

        return $results->sortByDesc($orderBy)->take(10);
    }




    public function trash(Request $request)
    {
        $search = $request->input('search');

        $produits = Produit::onlyTrashed()
            ->with('category')
            ->when($search, function ($query) use ($search) {
                return $query->where('nom', 'like', '%' . $search . '%')
                    ->orWhere('reference', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        return view('produits.trash', compact('produits'));
    }

    public function restore($id)
    {
        $produit = Produit::onlyTrashed()->findOrFail($id); 
        $produit->restore();

        return redirect()->route('produits.trash')->with('success', 'Produit restauré avec succès.');
    }

    public function forceDelete($id)
    {
        $produit = Produit::onlyTrashed()->findOrFail($id); 
        $produit->forceDelete();

        return redirect()->route('produits.trash')->with('success', 'Produit supprimé définitivement avec succès.');
    }


    public function quickUpdate(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'prix_achat' => 'nullable|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'stock_alerte' => 'nullable|integer|min:0',
            'actif' => 'nullable|boolean',
        ]);

        $validatedData['actif'] = $request->has('actif') ? true : false;

        $produit->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Produit mis à jour avec succès',
            'produit' => $produit->load('category')
        ]);
    }

    /**
     * ✅ NOUVELLE MÉTHODE: Get product data for quick edit
     */
    public function getQuickEditData($id)
    {
        $produit = Produit::with('category')->findOrFail($id);
        $categories = Category::orderBy('nom')->get();
        
        return response()->json([
            'success' => true,
            'produit' => $produit,
            'categories' => $categories
        ]);
    }

    public function stockFifo($id)
{
    $produit = Produit::with('category')->findOrFail($id);
    
    $achatsAvecStock = Achat::where('produit_id', $id)
        ->where('quantite_restante', '>', 0)
        ->orderBy('date_achat', 'asc')
        ->get();
    
}
}