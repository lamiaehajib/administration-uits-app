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
use Illuminate\Support\Facades\Auth;

class DashboardStockController extends Controller
{
    /**
     * Afficher le tableau de bord principal
     */
    public function index(Request $request)
    {
        // Vérifier le rôle de l'utilisateur
        $user = Auth::user();
        $isGerant = $user->hasRole('Gérant_de_stock');
        $isVendeur = $user->hasRole('Vendeur');

        // Par défaut: depuis le début de l'application jusqu'à maintenant
        $dateDebut = $request->input('date_debut', null);
        $dateFin = $request->input('date_fin', null);
        
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
        
        // Query de base pour les ventes (filtrée par user si pas Gérant)
        $ventesQuery = RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
            ->whereIn('statut', ['en_cours', 'livre']);
        
        // Si pas Gérant, filtrer par user_id
        if (!$isGerant) {
            $ventesQuery->where('user_id', $user->id);
        }

        $kpis = [
            // Chiffre d'affaires
            'ca_total' => (clone $ventesQuery)->sum('total'),
            
            // Total des achats (visible uniquement pour Gérant)
            'total_achats' => $isGerant ? Achat::whereBetween('date_achat', [$dateDebut, $dateFin])
                ->sum('total_achat') : null,
            
            // Marge totale (visible uniquement pour Gérant)
            'marge_totale' => $isGerant ? DB::table('recu_items')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('recus_ucgs.deleted_at')
                ->sum('recu_items.marge_totale') : null,
            
            // Nombre de ventes
            'nombre_ventes' => (clone $ventesQuery)->count(),
            
            // Valeur du stock actuel (visible uniquement pour Gérant)
            'valeur_stock' => $isGerant ? DB::table('produits')
                ->whereNull('deleted_at')
                ->selectRaw('SUM(quantite_stock * COALESCE(prix_achat, 0)) as total')
                ->value('total') ?? 0 : null,
            
            // Nombre de produits actifs
            'produits_actifs' => Produit::where('actif', true)->count(),
            
            // Produits en alerte stock
            'produits_alerte' => Produit::whereColumn('quantite_stock', '<=', 'stock_alerte')
                ->where('actif', true)
                ->count(),
            
            // Paiements du jour (filtrés par user si pas Gérant)
            'paiements_jour' => $this->getPaiementsJour($user, $isGerant),
        ];

        // Calculer le taux de marge (visible uniquement pour Gérant)
        $kpis['taux_marge'] = $isGerant && $kpis['ca_total'] > 0 
            ? ($kpis['marge_totale'] / $kpis['ca_total']) * 100 
            : null;

        // ========== GRAPHIQUE: ÉVOLUTION CA vs ACHATS ==========
        $evolutionVentes = $this->getEvolutionVentes($dateDebut, $dateFin, $user, $isGerant);

        // ========== GRAPHIQUE: TOP 10 PRODUITS LES PLUS VENDUS ==========
        $topProduits = $this->getTopProduits($dateDebut, $dateFin, $user, $isGerant);

        // ========== GRAPHIQUE: VENTES PAR CATÉGORIE ==========
        $ventesByCategorie = $this->getVentesByCategorie($dateDebut, $dateFin, $user, $isGerant);

        // ========== GRAPHIQUE: RÉPARTITION MODES DE PAIEMENT ==========
        $paiementsModes = $this->getPaiementsModes($dateDebut, $dateFin, $user, $isGerant);

        // ========== PRODUITS EN RUPTURE DE STOCK ==========
        $produitsRupture = Produit::whereColumn('quantite_stock', '<=', 'stock_alerte')
            ->where('actif', true)
            ->with('category')
            ->orderBy('quantite_stock', 'asc')
            ->take(10)
            ->get();

        // ========== DERNIÈRES VENTES ==========
        $dernieresVentesQuery = RecuUcg::with(['items.produit', 'user'])
            ->whereIn('statut', ['en_cours', 'livre']);
        
        // Si pas Gérant, filtrer par user
        if (!$isGerant) {
            $dernieresVentesQuery->where('user_id', $user->id);
        }
        
        $dernieresVentes = $dernieresVentesQuery->latest()->take(5)->get();

        // ========== COMPARAISON AVEC PÉRIODE PRÉCÉDENTE ==========
        $comparaison = $this->getComparaison($dateDebut, $dateFin, $user, $isGerant);

        // Liste des mois disponibles
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
            'moisDisponibles',
            'isGerant',
            'isVendeur'
        ));
    }

    /**
     * Paiements du jour (filtrés par user si pas Gérant)
     */
    private function getPaiementsJour($user, $isGerant)
    {
        $query = Paiement::whereDate('date_paiement', now());
        
        if (!$isGerant) {
            // Filtrer par les reçus de l'utilisateur
            $query->whereHas('recuUcg', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        return $query->sum('montant');
    }

    /**
     * Top produits (filtrés par user si pas Gérant)
     */
    private function getTopProduits($dateDebut, $dateFin, $user, $isGerant)
    {
        $query = DB::table('produits')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at');
        
        // Si pas Gérant, filtrer par user
        if (!$isGerant) {
            $query->where('recus_ucgs.user_id', $user->id);
        }
        
        return $query->select(
                'produits.nom',
                DB::raw('SUM(recu_items.quantite) as quantite_vendue'),
                DB::raw('SUM(recu_items.sous_total) as ca_total')
            )
            ->groupBy('produits.id', 'produits.nom')
            ->orderByDesc('quantite_vendue')
            ->take(10)
            ->get();
    }

    /**
     * Ventes par catégorie (filtrées par user si pas Gérant)
     */
    private function getVentesByCategorie($dateDebut, $dateFin, $user, $isGerant)
    {
        $query = DB::table('categories')
            ->leftJoin('produits', 'categories.id', '=', 'produits.category_id')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('recus_ucgs.deleted_at');
        
        // Si pas Gérant, filtrer par user
        if (!$isGerant) {
            $query->where('recus_ucgs.user_id', $user->id);
        }
        
        return $query->select(
                'categories.nom as categorie',
                DB::raw('SUM(recu_items.sous_total) as total_ventes'),
                $isGerant ? DB::raw('SUM(recu_items.marge_totale) as marge') : DB::raw('NULL as marge')
            )
            ->groupBy('categories.id', 'categories.nom')
            ->orderByDesc('total_ventes')
            ->get();
    }

    /**
     * Modes de paiement (filtrés par user si pas Gérant)
     */
    private function getPaiementsModes($dateDebut, $dateFin, $user, $isGerant)
    {
        $query = Paiement::whereBetween('date_paiement', [$dateDebut, $dateFin]);
        
        if (!$isGerant) {
            $query->whereHas('recuUcg', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        return $query->select('mode_paiement', DB::raw('SUM(montant) as total'))
            ->groupBy('mode_paiement')
            ->get();
    }

    /**
     * Obtenir la liste des mois disponibles
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
     * Obtenir l'évolution des ventes
     */
    private function getEvolutionVentes($dateDebut, $dateFin, $user, $isGerant)
    {
        $nbJours = $dateDebut->diffInDays($dateFin) + 1;
        
        if ($nbJours > 60) {
            return $this->getEvolutionVentesParMois($dateDebut, $dateFin, $user, $isGerant);
        }
        
        return $this->getEvolutionVentesParJour($dateDebut, $dateFin, $user, $isGerant);
    }

    /**
     * Évolution par jour
     */
    private function getEvolutionVentesParJour($dateDebut, $dateFin, $user, $isGerant)
    {
        $jours = [];
        $ventes = [];
        $achats = [];
        $marges = [];

        $dateActuelle = $dateDebut->copy();
        
        while ($dateActuelle->lte($dateFin)) {
            $jours[] = $dateActuelle->format('d/m');
            
            $ventesQuery = RecuUcg::whereDate('created_at', $dateActuelle)
                ->whereIn('statut', ['en_cours', 'livre']);
            
            if (!$isGerant) {
                $ventesQuery->where('user_id', $user->id);
            }
            
            $caJour = $ventesQuery->sum('total');
            $ventes[] = round($caJour, 2);
            
            if ($isGerant) {
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
            }
            
            $dateActuelle->addDay();
        }

        return [
            'labels' => $jours,
            'ventes' => $ventes,
            'achats' => $isGerant ? $achats : null,
            'marges' => $isGerant ? $marges : null,
        ];
    }

    /**
     * Évolution par mois
     */
    private function getEvolutionVentesParMois($dateDebut, $dateFin, $user, $isGerant)
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
            
            $ventesQuery = RecuUcg::whereBetween('created_at', [$debutMois, $finMois])
                ->whereIn('statut', ['en_cours', 'livre']);
            
            if (!$isGerant) {
                $ventesQuery->where('user_id', $user->id);
            }
            
            $caMois = $ventesQuery->sum('total');
            $ventes[] = round($caMois, 2);
            
            if ($isGerant) {
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
            }
            
            $dateActuelle->addMonth();
        }

        return [
            'labels' => $mois,
            'ventes' => $ventes,
            'achats' => $isGerant ? $achats : null,
            'marges' => $isGerant ? $marges : null,
        ];
    }

    /**
     * Comparaison avec période précédente
     */
    private function getComparaison($dateDebut, $dateFin, $user, $isGerant)
    {
        $nbJours = $dateDebut->diffInDays($dateFin) + 1;
        $dateDebutPrecedent = $dateDebut->copy()->subDays($nbJours);
        $dateFinPrecedent = $dateDebut->copy()->subDay();

        $ventesActuelQuery = RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
            ->whereIn('statut', ['en_cours', 'livre']);
        
        $ventesPrecedentQuery = RecuUcg::whereBetween('created_at', [$dateDebutPrecedent, $dateFinPrecedent])
            ->whereIn('statut', ['en_cours', 'livre']);
        
        if (!$isGerant) {
            $ventesActuelQuery->where('user_id', $user->id);
            $ventesPrecedentQuery->where('user_id', $user->id);
        }

        $caActuel = $ventesActuelQuery->sum('total');
        $caPrecedent = $ventesPrecedentQuery->sum('total');

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
        $user = Auth::user();
        $isGerant = $user->hasRole('Gérant_de_stock');
        
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

        $ventesQuery = RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
            ->whereIn('statut', ['en_cours', 'livre']);
        
        if (!$isGerant) {
            $ventesQuery->where('user_id', $user->id);
        }

        $stats = [
            'ca' => (clone $ventesQuery)->sum('total'),
            'ventes' => (clone $ventesQuery)->count(),
            'marge' => $isGerant ? DB::table('recu_items')
                ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->when(!$isGerant, function($q) use ($user) {
                    $q->where('recus_ucgs.user_id', $user->id);
                })
                ->sum('recu_items.marge_totale') : null,
        ];

        return response()->json($stats);
    }
}