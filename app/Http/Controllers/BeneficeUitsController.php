<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Facturef;
use App\Models\Reussite;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BeneficeUitsController extends Controller
{
    public function index(Request $request)
    {
        // 📅 Récupération des filtres
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $currency = $request->input('currency', 'DH');

        // 💰 REVENUS (Entrées d'argent)
        
        // 1. Factures Services (type = 'service')
        $revenusServices = Facture::where('type', 'service')
            ->where('currency', $currency)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->sum('total_ttc');

        // 2. Factures Formations
        $revenusFormations = Facturef::where('currency', $currency)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->sum('total_ttc');

        // 3. Reçus Stages (montant_paye)
        $revenusStages = Reussite::whereBetween('date_paiement', [$dateFrom, $dateTo])
            ->sum('montant_paye');

        // Total Revenus
        $totalRevenus = $revenusServices + $revenusFormations + $revenusStages;

        // 📉 COÛTS (Sorties d'argent)
        
        // Factures Produits - on prend le coût d'achat total
        $coutsProduits = DB::table('factures')
            ->join('factures_items', 'factures.id', '=', 'factures_items.factures_id')
            ->where('factures.type', 'produit')
            ->where('factures.currency', $currency)
            ->whereBetween('factures.date', [$dateFrom, $dateTo])
            ->sum(DB::raw('factures_items.quantite * factures_items.prix_achat'));

        // Total Coûts
        $totalCouts = $coutsProduits;

        // 🎯 BÉNÉFICE NET
        $beneficeNet = $totalRevenus - $totalCouts;

        // 📊 Détails par catégorie
        $details = [
            'revenus' => [
                'services' => $revenusServices,
                'formations' => $revenusFormations,
                'stages' => $revenusStages,
                'total' => $totalRevenus,
            ],
            'couts' => [
                'produits' => $coutsProduits,
                'total' => $totalCouts,
            ],
            'benefice_net' => $beneficeNet,
            'marge_benefice' => $totalRevenus > 0 
                ? round(($beneficeNet / $totalRevenus) * 100, 2) 
                : 0,
        ];

        // 📈 Évolution mensuelle (6 derniers mois)
        $evolutionMensuelle = $this->getEvolutionMensuelle($currency);

        // 🏆 Top 5 Clients par revenu
        $topClients = $this->getTopClients($dateFrom, $dateTo, $currency);

        // 📊 Statistiques supplémentaires
        $stats = [
            'total_factures_services' => Facture::where('type', 'service')
                ->where('currency', $currency)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->count(),
            'total_factures_formations' => Facturef::where('currency', $currency)
                ->whereBetween('date', [$dateFrom, $dateTo])
                ->count(),
            'total_stages' => Reussite::whereBetween('date_paiement', [$dateFrom, $dateTo])
                ->count(),
            'moyenne_facture' => $totalRevenus > 0 
                ? round($totalRevenus / (
                    Facture::where('type', 'service')->whereBetween('date', [$dateFrom, $dateTo])->count() +
                    Facturef::whereBetween('date', [$dateFrom, $dateTo])->count() +
                    Reussite::whereBetween('date_paiement', [$dateFrom, $dateTo])->count()
                ), 2)
                : 0,
        ];

        return view('BeneficeUits.index', compact(
            'details',
            'evolutionMensuelle',
            'topClients',
            'stats',
            'dateFrom',
            'dateTo',
            'currency'
        ));
    }

    // 📈 Évolution mensuelle des 6 derniers mois
    private function getEvolutionMensuelle($currency)
    {
        $evolution = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth()->format('Y-m-d');
            $end = $date->copy()->endOfMonth()->format('Y-m-d');

            $revenus = Facture::where('type', 'service')
                    ->where('currency', $currency)
                    ->whereBetween('date', [$start, $end])
                    ->sum('total_ttc')
                + Facturef::where('currency', $currency)
                    ->whereBetween('date', [$start, $end])
                    ->sum('total_ttc')
                + Reussite::whereBetween('date_paiement', [$start, $end])
                    ->sum('montant_paye');

            $couts = DB::table('factures')
                ->join('factures_items', 'factures.id', '=', 'factures_items.factures_id')
                ->where('factures.type', 'produit')
                ->where('factures.currency', $currency)
                ->whereBetween('factures.date', [$start, $end])
                ->sum(DB::raw('factures_items.quantite * factures_items.prix_achat'));

            $evolution[] = [
                'mois' => $date->locale('fr')->isoFormat('MMM YYYY'),
                'revenus' => round($revenus, 2),
                'couts' => round($couts, 2),
                'benefice' => round($revenus - $couts, 2),
            ];
        }

        return $evolution;
    }

    // 🏆 Top 5 Clients
    private function getTopClients($dateFrom, $dateTo, $currency)
    {
        // Clients des services
        $clientsServices = Facture::where('type', 'service')
            ->where('currency', $currency)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->select('client', DB::raw('SUM(total_ttc) as total'))
            ->groupBy('client')
            ->get();

        // Clients des formations
        $clientsFormations = Facturef::where('currency', $currency)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->select('client', DB::raw('SUM(total_ttc) as total'))
            ->groupBy('client')
            ->get();

        // Fusion et tri
        $allClients = $clientsServices->merge($clientsFormations)
            ->groupBy('client')
            ->map(function ($items) {
                return [
                    'client' => $items->first()->client,
                    'total' => $items->sum('total'),
                ];
            })
            ->sortByDesc('total')
            ->take(5)
            ->values();

        return $allClients;
    }

    // 📥 Export Excel
    public function exportExcel(Request $request)
    {
        $dateFrom = $request->input('date_from', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $currency = $request->input('currency', 'DH');

        // Récupérer toutes les données
        $services = Facture::where('type', 'service')
            ->where('currency', $currency)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();

        $formations = Facturef::where('currency', $currency)
            ->whereBetween('date', [$dateFrom, $dateTo])
            ->get();

        $stages = Reussite::whereBetween('date_paiement', [$dateFrom, $dateTo])
            ->get();

        $filename = 'benefice_' . $dateFrom . '_to_' . $dateTo . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($services, $formations, $stages, $currency) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // En-têtes
            fputcsv($file, ['Type', 'Date', 'Client', 'Référence', 'Montant', 'Devise']);
            
            // Services
            foreach ($services as $item) {
                fputcsv($file, [
                    'Service',
                    $item->date,
                    $item->client,
                    $item->facture_num,
                    $item->total_ttc,
                    $currency,
                ]);
            }
            
            // Formations
            foreach ($formations as $item) {
                fputcsv($file, [
                    'Formation',
                    $item->date,
                    $item->client,
                    $item->facturef_num,
                    $item->total_ttc,
                    $currency,
                ]);
            }
            
            // Stages
            foreach ($stages as $item) {
                fputcsv($file, [
                    'Stage',
                    $item->date_paiement,
                    $item->nom . ' ' . $item->prenom,
                    'Stage',
                    $item->montant_paye,
                    'DH',
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}