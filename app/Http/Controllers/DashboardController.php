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
    // Taux de conversion vers DH (à mettre à jour régulièrement)
    private const EXCHANGE_RATES = [
        'DH'  => 1,
        'EUR' => 10.74,  // 1 EUR = 10.74 DH
        'CFA' => 0.0162, // 1 CFA = 0.0162 DH
    ];

    /**
     * Convertir un montant vers DH
     */
    private function convertToDH($amount, $currency)
    {
        $rate = self::EXCHANGE_RATES[$currency] ?? 1;
        return $amount * $rate;
    }

    /**
     * Calculer le revenu total des factures avec conversion
     */
    private function getFacturesRevenueInDH($model, $dateFilter = null)
    {
        $query = $model::query();
        
        if ($dateFilter) {
            $query->whereMonth('created_at', $dateFilter['month'])
                  ->whereYear('created_at', $dateFilter['year']);
        }

        $factures = $query->select('total_ttc', 'currency')->get();
        
        $total = 0;
        foreach ($factures as $facture) {
            $total += $this->convertToDH($facture->total_ttc, $facture->currency);
        }
        
        return $total;
    }

    public function index()
    {
        // ==================== STATISTIQUES GLOBALES ====================
        
        // Reçus Stage (Reussite) - déjà en DH
        $totalReussites = Reussite::count();
        $revenusReussites = Reussite::sum('montant_paye');
        $reussitesCurrentMonth = Reussite::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        
        // Reçus Formation (Reussitef) - déjà en DH
        $totalReussitesf = Reussitef::count();
        $revenusReussitesf = Reussitef::sum('montant_paye');
        $reussitesfCurrentMonth = Reussitef::whereMonth('created_at', Carbon::now()->month)
                                           ->whereYear('created_at', Carbon::now()->year)
                                           ->count();
        
        // Reçus UCG - déjà en DH
        $totalUcgs = Ucg::count();
        $revenusUcgs = Ucg::sum('montant_paye');
        $ucgsCurrentMonth = Ucg::whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->count();
        
        // Devis Projet (Stats séparées - pas dans revenus)
        $totalDevis = Devis::count();
        $devisValeurTotal = Devis::sum('total_ttc');
        $devisCurrentMonth = Devis::whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->count();
        
        // Devis Formation (Stats séparées - pas dans revenus)
        $totalDevisf = Devisf::count();
        $devisfValeurTotal = Devisf::sum('total_ttc');
        $devisfCurrentMonth = Devisf::whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count();
        
        // ========== FACTURES AVEC CONVERSION DEVISES ==========
        
        // Factures Projet - avec conversion
        $totalFactures = Facture::count();
        $facturesRevenu = $this->getFacturesRevenueInDH(Facture::class);
        $facturesCurrentMonth = Facture::whereMonth('created_at', Carbon::now()->month)
                                       ->whereYear('created_at', Carbon::now()->year)
                                       ->count();
        
        // Factures Formation - avec conversion
        $totalFacturesf = Facturef::count();
        $facturesfRevenu = $this->getFacturesRevenueInDH(Facturef::class);
        $facturesfCurrentMonth = Facturef::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        
        // ==================== REVENUS TOTAUX (SANS DEVIS) ====================
        $revenuTotal = $revenusReussites + $revenusReussitesf + $revenusUcgs + 
                       $facturesRevenu + $facturesfRevenu;
        
        // Valeur totale des devis (séparée, pour information uniquement)
        $valeurDevisTotal = $devisValeurTotal + $devisfValeurTotal;
        
        // ==================== DONNÉES POUR GRAPHIQUES ====================
        
        // Graphique : Évolution mensuelle des revenus RÉELS (6 derniers mois)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            
            // Reçus (déjà en DH)
            $revenueMonth = Reussite::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->sum('montant_paye') +
                           Reussitef::whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->sum('montant_paye') +
                           Ucg::whereMonth('created_at', $date->month)
                              ->whereYear('created_at', $date->year)
                              ->sum('montant_paye');
            
            // Factures Projet avec conversion
            $facturesProjet = Facture::whereMonth('created_at', $date->month)
                                     ->whereYear('created_at', $date->year)
                                     ->select('total_ttc', 'currency')
                                     ->get();
            foreach ($facturesProjet as $f) {
                $revenueMonth += $this->convertToDH($f->total_ttc, $f->currency);
            }
            
            // Factures Formation avec conversion
            $facturesFormation = Facturef::whereMonth('created_at', $date->month)
                                         ->whereYear('created_at', $date->year)
                                         ->select('total_ttc', 'currency')
                                         ->get();
            foreach ($facturesFormation as $f) {
                $revenueMonth += $this->convertToDH($f->total_ttc, $f->currency);
            }
            
            $monthlyRevenue[] = [
                'month' => $month,
                'revenue' => round($revenueMonth, 2)
            ];
        }
        
        // Graphique : Répartition des revenus par type (SANS DEVIS)
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
        
        // Graphique : Top 5 clients (avec conversion devises)
        $topClients = $this->getTopClientsWithConversion();
        
        // Activités récentes
        $recentActivities = collect()
            ->merge(Reussite::latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Reçu Stage',
                    'description' => $item->nom . ' ' . $item->prenom,
                    'amount' => $item->montant_paye,
                    'currency' => 'DH',
                    'date' => $item->created_at
                ];
            }))
            ->merge(Facture::latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Facture Projet',
                    'description' => $item->client . ' - ' . $item->titre,
                    'amount' => $this->convertToDH($item->total_ttc, $item->currency),
                    'original_amount' => $item->total_ttc,
                    'original_currency' => $item->currency,
                    'date' => $item->created_at
                ];
            }))
            ->merge(Facturef::latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Facture Formation',
                    'description' => $item->client . ' - ' . $item->titre,
                    'amount' => $this->convertToDH($item->total_ttc, $item->currency),
                    'original_amount' => $item->total_ttc,
                    'original_currency' => $item->currency,
                    'date' => $item->created_at
                ];
            }))
            ->sortByDesc('date')
            ->take(10);
        
        return view('dashboard', compact(
            'totalReussites', 'revenusReussites', 'reussitesCurrentMonth',
            'totalReussitesf', 'revenusReussitesf', 'reussitesfCurrentMonth',
            'totalUcgs', 'revenusUcgs', 'ucgsCurrentMonth',
            'totalDevis', 'devisValeurTotal', 'devisCurrentMonth',
            'totalDevisf', 'devisfValeurTotal', 'devisfCurrentMonth',
            'totalFactures', 'facturesRevenu', 'facturesCurrentMonth',
            'totalFacturesf', 'facturesfRevenu', 'facturesfCurrentMonth',
            'revenuTotal', 'valeurDevisTotal',
            'monthlyRevenue', 'revenueByType', 'documentCounts', 
            'topClients', 'recentActivities'
        ));
    }

    /**
     * Top 5 clients avec conversion des devises
     */
    private function getTopClientsWithConversion()
    {
        // Récupérer toutes les factures avec leur devise
        $facturesProjet = Facture::select('client', 'total_ttc', 'currency')->get();
        $facturesFormation = Facturef::select('client', 'total_ttc', 'currency')->get();
        
        $clientTotals = [];
        
        // Calculer les totaux par client (Factures Projet)
        foreach ($facturesProjet as $f) {
            $amountDH = $this->convertToDH($f->total_ttc, $f->currency);
            if (!isset($clientTotals[$f->client])) {
                $clientTotals[$f->client] = 0;
            }
            $clientTotals[$f->client] += $amountDH;
        }
        
        // Ajouter Factures Formation
        foreach ($facturesFormation as $f) {
            $amountDH = $this->convertToDH($f->total_ttc, $f->currency);
            if (!isset($clientTotals[$f->client])) {
                $clientTotals[$f->client] = 0;
            }
            $clientTotals[$f->client] += $amountDH;
        }
        
        // Trier et prendre top 5
        arsort($clientTotals);
        $topClients = array_slice($clientTotals, 0, 5, true);
        
        return collect($topClients)->map(function($total, $client) {
            return [
                'client' => $client,
                'revenue' => round($total, 2)
            ];
        })->values();
    }
}