<?php

namespace App\Http\Controllers;

use App\Models\Reussite;
use App\Models\Reussitef;
use App\Models\RecuUcg;
use App\Models\Devis;
use App\Models\Devisf;
use App\Models\Facture;
use App\Models\Facturef;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Taux de conversion vers DH
    private const EXCHANGE_RATES = [
        'DH'  => 1,
        'EUR' => 10.74,
        'CFA' => 0.0162,
    ];

    /**
     * Check ila user 3ando role Admin (ichuf kolchi)
     */
    private function isAdmin()
    {
        return Auth::user()->hasRole('Admin');
    }

    /**
     * Apply filter: Admin ichuf kolchi, Admin2 ghir dyalo
     */
    private function applyUserFilter($query)
    {
        if (!$this->isAdmin()) {
            $query->where('user_id', Auth::id());
        }
        return $query;
    }

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
        
        // Apply user filter
        $query = $this->applyUserFilter($query);
        
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

    /**
     * ✅ NOUVELLE MÉTHODE - Calculer la MARGE totale des Reçus UCG (rba7)
     */
    private function getRecuUcgMargeTotal($dateFilter = null)
    {
        $query = RecuUcg::with('items');
        
        // Apply user filter
        $query = $this->applyUserFilter($query);
        
        if ($dateFilter) {
            $query->whereMonth('created_at', $dateFilter['month'])
                  ->whereYear('created_at', $dateFilter['year']);
        }

        $recus = $query->get();
        
        // Somme de toutes les marges
        return $recus->sum(fn($recu) => $recu->margeGlobale());
    }

    public function index()
    {
        // ==================== STATISTIQUES GLOBALES ====================
        
        // Reçus Stage (Reussite)
        $queryReussites = Reussite::query();
        $queryReussites = $this->applyUserFilter($queryReussites);
        $totalReussites = $queryReussites->count();
        $revenusReussites = $queryReussites->sum('montant_paye');
        $reussitesCurrentMonth = $this->applyUserFilter(Reussite::query())
                                         ->whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        
        // Reçus Formation (Reussitef)
        $queryReussitesf = Reussitef::query();
        $queryReussitesf = $this->applyUserFilter($queryReussitesf);
        $totalReussitesf = $queryReussitesf->count();
        $revenusReussitesf = $queryReussitesf->sum('montant_paye');
        $reussitesfCurrentMonth = $this->applyUserFilter(Reussitef::query())
                                           ->whereMonth('created_at', Carbon::now()->month)
                                           ->whereYear('created_at', Carbon::now()->year)
                                           ->count();
        
        // ✅ FIX: Reçus UCG - MARGE UNIQUEMENT (rba7 machi total)
        $queryRecuUcgs = RecuUcg::query();
        $queryRecuUcgs = $this->applyUserFilter($queryRecuUcgs);
        $totalRecuUcgs = $queryRecuUcgs->count();
        $revenusRecuUcgs = $this->getRecuUcgMargeTotal(); // ✅ Marge au lieu de montant_paye
        $recuUcgsCurrentMonth = $this->applyUserFilter(RecuUcg::query())
                               ->whereMonth('created_at', Carbon::now()->month)
                               ->whereYear('created_at', Carbon::now()->year)
                               ->count();
        
        // Devis Projet
        $queryDevis = Devis::query();
        $queryDevis = $this->applyUserFilter($queryDevis);
        $totalDevis = $queryDevis->count();
        $devisValeurTotal = $queryDevis->sum('total_ttc');
        $devisCurrentMonth = $this->applyUserFilter(Devis::query())
                                  ->whereMonth('created_at', Carbon::now()->month)
                                  ->whereYear('created_at', Carbon::now()->year)
                                  ->count();
        
        // Devis Formation
        $queryDevisf = Devisf::query();
        $queryDevisf = $this->applyUserFilter($queryDevisf);
        $totalDevisf = $queryDevisf->count();
        $devisfValeurTotal = $queryDevisf->sum('total_ttc');
        $devisfCurrentMonth = $this->applyUserFilter(Devisf::query())
                                    ->whereMonth('created_at', Carbon::now()->month)
                                    ->whereYear('created_at', Carbon::now()->year)
                                    ->count();
        
        // ========== FACTURES AVEC CONVERSION DEVISES ==========
        
        // Factures Projet
        $queryFactures = Facture::query();
        $queryFactures = $this->applyUserFilter($queryFactures);
        $totalFactures = $queryFactures->count();
        $facturesRevenu = $this->getFacturesRevenueInDH(Facture::class);
        $facturesCurrentMonth = $this->applyUserFilter(Facture::query())
                                       ->whereMonth('created_at', Carbon::now()->month)
                                       ->whereYear('created_at', Carbon::now()->year)
                                       ->count();
        
        // Factures Formation
        $queryFacturesf = Facturef::query();
        $queryFacturesf = $this->applyUserFilter($queryFacturesf);
        $totalFacturesf = $queryFacturesf->count();
        $facturesfRevenu = $this->getFacturesRevenueInDH(Facturef::class);
        $facturesfCurrentMonth = $this->applyUserFilter(Facturef::query())
                                         ->whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count();
        
        // ==================== REVENUS TOTAUX ====================
        // ✅ revenusRecuUcgs howa daba marge (rba7) machi CA
        $revenuTotal = $revenusReussites + $revenusReussitesf + $revenusRecuUcgs + 
                       $facturesRevenu + $facturesfRevenu;
        
        $valeurDevisTotal = $devisValeurTotal + $devisfValeurTotal;
        
        // ==================== DONNÉES POUR GRAPHIQUES ====================
        
        // Graphique : Évolution mensuelle des revenus
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M Y');
            
            // Reçus
            $revenueMonth = $this->applyUserFilter(Reussite::query())
                                    ->whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->sum('montant_paye') +
                           $this->applyUserFilter(Reussitef::query())
                                    ->whereMonth('created_at', $date->month)
                                    ->whereYear('created_at', $date->year)
                                    ->sum('montant_paye');
            
            // ✅ Reçus UCG - MARGE uniquement
            $revenueMonth += $this->getRecuUcgMargeTotal([
                'month' => $date->month,
                'year' => $date->year
            ]);
            
            // Factures Projet avec conversion
            $facturesProjet = $this->applyUserFilter(Facture::query())
                                     ->whereMonth('created_at', $date->month)
                                     ->whereYear('created_at', $date->year)
                                     ->select('total_ttc', 'currency')
                                     ->get();
            foreach ($facturesProjet as $f) {
                $revenueMonth += $this->convertToDH($f->total_ttc, $f->currency);
            }
            
            // Factures Formation avec conversion
            $facturesFormation = $this->applyUserFilter(Facturef::query())
                                         ->whereMonth('created_at', $date->month)
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
        
        // Graphique : Répartition des revenus par type
        $revenueByType = [
            ['type' => 'Reçus Stage', 'amount' => round($revenusReussites, 2)],
            ['type' => 'Reçus Formation', 'amount' => round($revenusReussitesf, 2)],
            ['type' => 'Reçus UCG (Marge)', 'amount' => round($revenusRecuUcgs, 2)], // ✅ Préciser "Marge"
            ['type' => 'Factures Projet', 'amount' => round($facturesRevenu, 2)],
            ['type' => 'Factures Formation', 'amount' => round($facturesfRevenu, 2)],
        ];
        
        // Graphique : Nombre de documents par catégorie
        $documentCounts = [
            ['category' => 'Reçus Stage', 'count' => $totalReussites],
            ['category' => 'Reçus Formation', 'count' => $totalReussitesf],
            ['category' => 'Reçus UCG', 'count' => $totalRecuUcgs],
            ['category' => 'Devis Projet', 'count' => $totalDevis],
            ['category' => 'Devis Formation', 'count' => $totalDevisf],
            ['category' => 'Factures Projet', 'count' => $totalFactures],
            ['category' => 'Factures Formation', 'count' => $totalFacturesf],
        ];
        
        // Graphique : Top 5 clients
        $topClients = $this->getTopClientsWithConversion();
        
        // Activités récentes
        $recentActivities = collect()
            ->merge($this->applyUserFilter(Reussite::query())->latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Reçu Stage',
                    'description' => $item->nom . ' ' . $item->prenom,
                    'amount' => $item->montant_paye,
                    'currency' => 'DH',
                    'date' => $item->created_at
                ];
            }))
            ->merge($this->applyUserFilter(RecuUcg::query())->with('items')->latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Reçu UCG',
                    'description' => $item->client_nom . ' - ' . $item->equipement,
                    'amount' => $item->margeGlobale(), // ✅ Marge au lieu de total
                    'label' => 'Marge', // ✅ Préciser que c'est la marge
                    'currency' => 'DH',
                    'date' => $item->created_at
                ];
            }))
            ->merge($this->applyUserFilter(Facture::query())->latest()->take(3)->get()->map(function($item) {
                return [
                    'type' => 'Facture Projet',
                    'description' => $item->client . ' - ' . $item->titre,
                    'amount' => $this->convertToDH($item->total_ttc, $item->currency),
                    'original_amount' => $item->total_ttc,
                    'original_currency' => $item->currency,
                    'date' => $item->created_at
                ];
            }))
            ->merge($this->applyUserFilter(Facturef::query())->latest()->take(3)->get()->map(function($item) {
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
            'totalRecuUcgs', 'revenusRecuUcgs', 'recuUcgsCurrentMonth',
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
        // Récupérer factures (filtré selon role)
        $facturesProjet = $this->applyUserFilter(Facture::query())
                              ->select('client', 'total_ttc', 'currency')
                              ->get();
        $facturesFormation = $this->applyUserFilter(Facturef::query())
                                 ->select('client', 'total_ttc', 'currency')
                                 ->get();
        
        $clientTotals = [];
        
        foreach ($facturesProjet as $f) {
            $amountDH = $this->convertToDH($f->total_ttc, $f->currency);
            if (!isset($clientTotals[$f->client])) {
                $clientTotals[$f->client] = 0;
            }
            $clientTotals[$f->client] += $amountDH;
        }
        
        foreach ($facturesFormation as $f) {
            $amountDH = $this->convertToDH($f->total_ttc, $f->currency);
            if (!isset($clientTotals[$f->client])) {
                $clientTotals[$f->client] = 0;
            }
            $clientTotals[$f->client] += $amountDH;
        }
        
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