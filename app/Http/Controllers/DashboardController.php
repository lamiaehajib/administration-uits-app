<?php

namespace App\Http\Controllers;

use App\Models\Reussite;
use App\Models\Reussitef;
use App\Models\Ucg;
use App\Models\Devis;
use App\Models\Devisf;
use App\Models\Facture;
use App\Models\Facturef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ==================== STATISTIQUES GLOBALES ====================
        
        // Reçus Stage (Reussite)
        $totalReussites = Reussite::count();
        $revenusReussites = Reussite::sum('montant_paye');
        $reussitesCurrentMonth = Reussite::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        
        // Reçus Formation (Reussitef)
        $totalReussitesf = Reussitef::count();
        $revenusReussitesf = Reussitef::sum('montant_paye');
        $reussitesfCurrentMonth = Reussitef::whereMonth('created_at', Carbon::now()->month)
                                           ->whereYear('created_at', Carbon::now()->year)
                                           ->count();
        
        // Reçus UCG
        $totalUcgs = Ucg::count();
        $revenusUcgs = Ucg::sum('montant_paye');
        $ucgsCurrentMonth = Ucg::whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->count();
        
        // Devis Projet
        $totalDevis = Devis::count();
        $devisValeurTotal = Devis::sum('total_ttc');
        $devisCurrentMonth = Devis::whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->count();
        
        // Devis Formation
        $totalDevisf = Devisf::count();
        $devisfValeurTotal = Devisf::sum('total_ttc');
        $devisfCurrentMonth = Devisf::whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count();
        
        // Factures Projet
        $totalFactures = Facture::count();
        $facturesRevenu = Facture::sum('total_ttc');
        $facturesCurrentMonth = Facture::whereMonth('created_at', Carbon::now()->month)
                                       ->whereYear('created_at', Carbon::now()->year)
                                       ->count();
        
        // Factures Formation
        $totalFacturesf = Facturef::count();
        $facturesfRevenu = Facturef::sum('total_ttc');
        $facturesfCurrentMonth = Facturef::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        
        // ==================== REVENUS TOTAUX ====================
        $revenuTotal = $revenusReussites + $revenusReussitesf + $revenusUcgs + 
                       $facturesRevenu + $facturesfRevenu;
        
        $valeurDevisTotal = $devisValeurTotal + $devisfValeurTotal;
        
        // ==================== DONNÉES POUR GRAPHIQUES ====================
        
        // Graphique : Évolution mensuelle des revenus (6 derniers mois)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            
            $revenueMonth = Reussite::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->sum('montant_paye') +
                           Reussitef::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->sum('montant_paye') +
                           Ucg::whereMonth('created_at', $date->month)
                              ->whereYear('created_at', $date->year)
                              ->sum('montant_paye') +
                           Facture::whereMonth('created_at', $date->month)
                                  ->whereYear('created_at', $date->year)
                                  ->sum('total_ttc') +
                           Facturef::whereMonth('created_at', $date->month)
                                   ->whereYear('created_at', $date->year)
                                   ->sum('total_ttc');
            
            $monthlyRevenue[] = [
                'month' => $month,
                'revenue' => round($revenueMonth, 2)
            ];
        }
        
        // Graphique : Répartition des revenus par type
        $revenueByType = [
            ['type' => 'Reçus Stage', 'amount' => round($revenusReussites, 2)],
            ['type' => 'Reçus Formation', 'amount' => round($revenusReussitesf, 2)],
            ['type' => 'Reçus UCG', 'amount' => round($revenusUcgs, 2)],
            ['type' => 'Factures Projet', 'amount' => round($facturesRevenu, 2)],
            ['type' => 'Factures Formation', 'amount' => round($facturesfRevenu, 2)],
        ];
        
        // Graphique : Nombre de documents par catégorie
        $documentCounts = [
            ['category' => 'Reçus Stage', 'count' => $totalReussites],
            ['category' => 'Reçus Formation', 'count' => $totalReussitesf],
            ['category' => 'Reçus UCG', 'count' => $totalUcgs],
            ['category' => 'Devis Projet', 'count' => $totalDevis],
            ['category' => 'Devis Formation', 'count' => $totalDevisf],
            ['category' => 'Factures Projet', 'count' => $totalFactures],
            ['category' => 'Factures Formation', 'count' => $totalFacturesf],
        ];
        
        // Graphique : Top 5 clients (basé sur les factures)
        $topClients = DB::table(DB::raw('(
            SELECT client, SUM(total_ttc) as total FROM factures GROUP BY client
            UNION ALL
            SELECT client, SUM(total_ttc) as total FROM facturefs GROUP BY client
        ) as combined'))
        ->select('client', DB::raw('SUM(total) as total_revenue'))
        ->groupBy('client')
        ->orderByDesc('total_revenue')
        ->limit(5)
        ->get()
        ->map(function($item) {
            return [
                'client' => $item->client,
                'revenue' => round($item->total_revenue, 2)
            ];
        });
        
        // Activités récentes
        $recentActivities = collect()
            ->merge(Reussite::latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Reçu Stage',
                    'description' => $item->nom . ' ' . $item->prenom,
                    'amount' => $item->montant_paye,
                    'date' => $item->created_at
                ];
            }))
            ->merge(Facture::latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Facture Projet',
                    'description' => $item->client . ' - ' . $item->titre,
                    'amount' => $item->total_ttc,
                    'date' => $item->created_at
                ];
            }))
            ->merge(Facturef::latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Facture Formation',
                    'description' => $item->client . ' - ' . $item->titre,
                    'amount' => $item->total_ttc,
                    'date' => $item->created_at
                ];
            }))
            ->sortByDesc('date')
            ->take(10);
        
        return view('dashboard', compact(
            // Statistiques Reçus Stage
            'totalReussites', 'revenusReussites', 'reussitesCurrentMonth',
            // Statistiques Reçus Formation
            'totalReussitesf', 'revenusReussitesf', 'reussitesfCurrentMonth',
            // Statistiques Reçus UCG
            'totalUcgs', 'revenusUcgs', 'ucgsCurrentMonth',
            // Statistiques Devis Projet
            'totalDevis', 'devisValeurTotal', 'devisCurrentMonth',
            // Statistiques Devis Formation
            'totalDevisf', 'devisfValeurTotal', 'devisfCurrentMonth',
            // Statistiques Factures Projet
            'totalFactures', 'facturesRevenu', 'facturesCurrentMonth',
            // Statistiques Factures Formation
            'totalFacturesf', 'facturesfRevenu', 'facturesfCurrentMonth',
            // Totaux globaux
            'revenuTotal', 'valeurDevisTotal',
            // Données graphiques
            'monthlyRevenue', 'revenueByType', 'documentCounts', 
            'topClients', 'recentActivities'
        ));
    }
}