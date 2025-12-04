<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Category;
use App\Models\Achat;
use App\Models\RecuItem;
use App\Models\RecuUcg;
use App\Models\StockMovement;
use App\Models\Paiement;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardStockController extends Controller
{
    /**
     * Afficher le tableau de bord principal
     */
    public function index(Request $request)
    {
        // Par défaut: depuis le début de l'application jusqu'à maintenant
        $dateDebut = $request->input('date_debut', null);
        $dateFin = $request->input('date_fin', null);
        
        // Si pas de dates, prendre  depuis la première vente
        if (!$dateDebut) {
            $premiereVente = RecuUcg::oldest('created_at')->first();
            $dateDebut = $premiereVente 
                ? $premiereVente->created_at->format('Y-m-d') 
                : now()->startOfMonth()->format('Y-m-d');
        }
        
        if (!$dateFin) {
            $dateFin = now()->format('Y-m-d');
        }
        
        $dateDebut = Carbon::parse($dateDebut)->startOfDay();
        $dateFin = Carbon::parse($dateFin)->endOfDay();

        // ========== KPIs PRINCIPAUX ==========
        $kpis = [
            // Chiffre d'affaires (avec remise appliquée)
            'ca_total' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->whereIn('statut', ['en_cours', 'livre'])
                ->sum('total'),
            
            // Total des achats
            'total_achats' => Achat::whereBetween('date_achat', [$dateDebut, $dateFin])
                ->sum('total_achat'),
            
            // Marge totale (profit)
            'marge_totale' => DB::table('recu_items')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.marge_totale'),
            
            // Nombre de ventes
            'nombre_ventes' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->whereIn('statut', ['en_cours', 'livre'])
                ->count(),
            
            // Valeur du stock actuel
            'valeur_stock' => DB::table('produits')
                ->whereNull('deleted_at')
                ->selectRaw('SUM(quantite_stock * COALESCE(prix_achat, 0)) as total')
                ->value('total') ?? 0,
            
            // Nombre de produits actifs
            'produits_actifs' => Produit::where('actif', true)->count(),
            
            // Produits en alerte stock
            'produits_alerte' => Produit::whereColumn('quantite_stock', '<=', 'stock_alerte')
                ->where('actif', true)
                ->count(),
            
            // Paiements du jour
            'paiements_jour' => Paiement::whereDate('date_paiement', now())->sum('montant'),
        ];

        // Calculer le taux de marge
        $kpis['taux_marge'] = $kpis['ca_total'] > 0 
            ? ($kpis['marge_totale'] / $kpis['ca_total']) * 100 
            : 0;

        // ========== GRAPHIQUE: ÉVOLUTION CA vs ACHATS ==========
        $evolutionVentes = $this->getEvolutionVentes($dateDebut, $dateFin);

        // ========== GRAPHIQUE: TOP 10 PRODUITS LES PLUS VENDUS ==========
        $topProduits = DB::table('produits')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at')
            ->select(
                'produits.nom',
                DB::raw('SUM(recu_items.quantite) as quantite_vendue'),
                DB::raw('SUM(recu_items.sous_total) as ca_total')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('quantite_vendue')
            ->take(10)
            ->get();

        // ========== GRAPHIQUE: VENTES PAR CATÉGORIE ==========
        $ventesByCategorie = DB::table('categories')
            ->leftJoin('produits', 'categories.id', '=', 'produits.category_id')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('recus_ucgs.deleted_at')
            ->select(
                'categories.nom as categorie',
                DB::raw('SUM(recu_items.sous_total) as total_ventes'),
                DB::raw('SUM(recu_items.marge_totale) as marge')
            )
            ->groupBy('categories.id', 'categories.nom')
            ->orderByDesc('total_ventes')
            ->get();

        // ========== GRAPHIQUE: RÉPARTITION MODES DE PAIEMENT ==========
        $paiementsModes = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->select('mode_paiement', DB::raw('SUM(montant) as total'))
            ->groupBy('mode_paiement')
            ->get();

        // ========== PRODUITS EN RUPTURE DE STOCK ==========
        $produitsRupture = Produit::whereColumn('quantite_stock', '<=', 'stock_alerte')
            ->where('actif', true)
            ->with('category')
            ->orderBy('quantite_stock', 'asc')
            ->take(10)
            ->get();

        // ========== DERNIÈRES VENTES ==========
        $dernieresVentes = RecuUcg::with(['items.produit', 'user'])
            ->whereIn('statut', ['en_cours', 'livre'])
            ->latest()
            ->take(5)
            ->get();

        // ========== COMPARAISON AVEC PÉRIODE PRÉCÉDENTE ==========
        $comparaison = $this->getComparaison($dateDebut, $dateFin);

        // Liste des mois disponibles pour le filtre
        $moisDisponibles = $this->getMoisDisponibles();

        return view('dashboardstock.index', compact(
            'kpis',
            'evolutionVentes',
            'topProduits',
            'ventesByCategorie',
            'paiementsModes',
            'produitsRupture',
            'dernieresVentes',
            'comparaison',
            'dateDebut',
            'dateFin',
            'moisDisponibles'
        ));
    }

    /**
     * Obtenir la liste des mois disponibles depuis la première vente
     */
    private function getMoisDisponibles()
    {
        $premiereVente = RecuUcg::oldest('created_at')->first();
        
        if (!$premiereVente) {
            return collect();
        }

        $mois = collect();
        $dateDebut = $premiereVente->created_at->copy()->startOfMonth();
        $dateFin = now()->endOfMonth();

        while ($dateDebut->lte($dateFin)) {
            $mois->push([
                'value' => $dateDebut->format('Y-m'),
                'label' => $dateDebut->locale('fr')->isoFormat('MMMM YYYY'),
                'date_debut' => $dateDebut->format('Y-m-d'),
                'date_fin' => $dateDebut->copy()->endOfMonth()->format('Y-m-d'),
            ]);
            $dateDebut->addMonth();
        }

        return $mois->reverse();
    }

    /**
     * Obtenir l'évolution des ventes et achats
     */
    private function getEvolutionVentes($dateDebut, $dateFin)
    {
        $nbJours = $dateDebut->diffInDays($dateFin) + 1;
        
        // Si la période est trop longue (>60 jours), grouper par mois
        if ($nbJours > 60) {
            return $this->getEvolutionVentesParMois($dateDebut, $dateFin);
        }
        
        // Sinon, afficher jour par jour
        return $this->getEvolutionVentesParJour($dateDebut, $dateFin);
    }

    /**
     * Évolution par jour
     */
    private function getEvolutionVentesParJour($dateDebut, $dateFin)
    {
        $jours = [];
        $ventes = [];
        $achats = [];
        $marges = [];

        $dateActuelle = $dateDebut->copy();
        
        while ($dateActuelle->lte($dateFin)) {
            $jours[] = $dateActuelle->format('d/m');
            
            $caJour = RecuUcg::whereDate('created_at', $dateActuelle)
                ->whereIn('statut', ['en_cours', 'livre'])
                ->sum('total');
            $ventes[] = round($caJour, 2);
            
            $achatsJour = Achat::whereDate('date_achat', $dateActuelle)
                ->sum('total_achat');
            $achats[] = round($achatsJour, 2);
            
            $margeJour = DB::table('recu_items')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->whereDate('recus_ucgs.created_at', $dateActuelle)
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.marge_totale');
            $marges[] = round($margeJour, 2);
            
            $dateActuelle->addDay();
        }

        return [
            'labels' => $jours,
            'ventes' => $ventes,
            'achats' => $achats,
            'marges' => $marges,
        ];
    }

    /**
     * Évolution par mois (pour longues périodes)
     */
    private function getEvolutionVentesParMois($dateDebut, $dateFin)
    {
        $mois = [];
        $ventes = [];
        $achats = [];
        $marges = [];

        $dateActuelle = $dateDebut->copy()->startOfMonth();
        
        while ($dateActuelle->lte($dateFin)) {
            $mois[] = $dateActuelle->locale('fr')->isoFormat('MMM YY');
            
            $debutMois = $dateActuelle->copy()->startOfMonth();
            $finMois = $dateActuelle->copy()->endOfMonth();
            
            $caMois = RecuUcg::whereBetween('created_at', [$debutMois, $finMois])
                ->whereIn('statut', ['en_cours', 'livre'])
                ->sum('total');
            $ventes[] = round($caMois, 2);
            
            $achatsMois = Achat::whereBetween('date_achat', [$debutMois, $finMois])
                ->sum('total_achat');
            $achats[] = round($achatsMois, 2);
            
            $margeMois = DB::table('recu_items')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->whereBetween('recus_ucgs.created_at', [$debutMois, $finMois])
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.marge_totale');
            $marges[] = round($margeMois, 2);
            
            $dateActuelle->addMonth();
        }

        return [
            'labels' => $mois,
            'ventes' => $ventes,
            'achats' => $achats,
            'marges' => $marges,
        ];
    }

    /**
     * Comparaison avec la période précédente
     */
    private function getComparaison($dateDebut, $dateFin)
    {
        $nbJours = $dateDebut->diffInDays($dateFin) + 1;
        $dateDebutPrecedent = $dateDebut->copy()->subDays($nbJours);
        $dateFinPrecedent = $dateDebut->copy()->subDay();

        $caActuel = RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
            ->whereIn('statut', ['en_cours', 'livre'])
            ->sum('total');

        $caPrecedent = RecuUcg::whereBetween('created_at', [$dateDebutPrecedent, $dateFinPrecedent])
            ->whereIn('statut', ['en_cours', 'livre'])
            ->sum('total');

        $variation = $caPrecedent > 0 
            ? (($caActuel - $caPrecedent) / $caPrecedent) * 100 
            : 0;

        return [
            'ca_actuel' => $caActuel,
            'ca_precedent' => $caPrecedent,
            'variation' => round($variation, 2),
            'positif' => $variation >= 0,
        ];
    }

    /**
     * API pour récupérer les données en temps réel (AJAX)
     */
    public function getStats(Request $request)
    {
        $type = $request->input('type', 'today');
        
        switch ($type) {
            case 'today':
                $dateDebut = now()->startOfDay();
                $dateFin = now()->endOfDay();
                break;
            case 'week':
                $dateDebut = now()->startOfWeek();
                $dateFin = now()->endOfWeek();
                break;
            case 'month':
                $dateDebut = now()->startOfMonth();
                $dateFin = now()->endOfMonth();
                break;
            default:
                $dateDebut = now()->startOfMonth();
                $dateFin = now()->endOfMonth();
        }

        $stats = [
            'ca' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->whereIn('statut', ['en_cours', 'livre'])
                ->sum('total'),
            'ventes' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->whereIn('statut', ['en_cours', 'livre'])
                ->count(),
            'marge' => DB::table('recu_items')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->sum('recu_items.marge_totale'),
        ];

        return response()->json($stats);
    }
}