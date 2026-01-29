<?php

namespace App\Http\Controllers;

use App\Models\Facturef;
use App\Models\Facture;
use App\Models\Reussite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BeneficeUitsController extends Controller
{
    public function index(Request $request)
    {
        // 1. Paramètres de filtrage
        $periode = $request->input('periode', 'ce_mois');
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $currency = $request->input('currency', 'DH');
        $view = $request->input('view', 'global'); // global, formations, services, stages, portail

        // 2. Calculer les dates selon la période
        [$from, $to] = $this->getDateRange($periode, $dateDebut, $dateFin);

        // 3. Récupérer les revenus par source
        $revenueFormations = $this->getFormationsRevenue($from, $to, $currency);
        $revenueServices = $this->getServicesRevenue($from, $to, $currency);
        $revenueStages = $this->getStagesRevenue($from, $to);
        $revenuePortail = $this->getPortailRevenue($from, $to);

        // 4. Calcul du total général
        $totalRevenue = $revenueFormations['total'] + 
                       $revenueServices['total'] + 
                       $revenueStages['total'] + 
                       $revenuePortail;

        // 5. Statistiques détaillées
        $stats = [
            'total_general' => $totalRevenue,
            'formations' => $revenueFormations,
            'services' => $revenueServices,
            'stages' => $revenueStages,
            'portail' => $revenuePortail,
            'currency' => $currency,
        ];

        // 6. Évolution mensuelle (12 derniers mois)
        $evolutionMensuelle = $this->getEvolutionMensuelle($currency);

        // 7. Comparaison avec période précédente
        $comparison = $this->getComparison($from, $to, $currency);

        // 8. Top clients/formations
        $topFormations = $this->getTopFormations($from, $to, $currency);
        $topClients = $this->getTopClients($from, $to, $currency);

        // 9. Détails par mois (pour table détaillée)
        $detailsParMois = $this->getDetailsParMois($from, $to, $currency);

        // 10. Données pour charts
        $chartData = $this->prepareChartData($evolutionMensuelle);

        return view('BeneficeUits.index', compact(
            'stats',
            'evolutionMensuelle',
            'comparison',
            'topFormations',
            'topClients',
            'detailsParMois',
            'chartData',
            'from',
            'to',
            'periode',
            'currency',
            'view'
        ));
    }

    // ================== MÉTHODES HELPER ==================

    private function getDateRange($periode, $dateDebut, $dateFin)
    {
        if ($dateDebut && $dateFin) {
            return [Carbon::parse($dateDebut), Carbon::parse($dateFin)];
        }

        switch ($periode) {
            case 'aujourdhui':
                return [Carbon::today(), Carbon::today()];
            case 'cette_semaine':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'ce_mois':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'ce_trimestre':
                return [Carbon::now()->firstOfQuarter(), Carbon::now()->lastOfQuarter()];
            case 'cette_annee':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            case '12_mois':
                return [Carbon::now()->subMonths(12), Carbon::now()];
            default:
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        }
    }

    private function getFormationsRevenue($from, $to, $currency)
    {
        $query = Facturef::whereBetween('date', [$from, $to])
                         ->where('currency', $currency);

        $total = $query->sum('total_ttc');
        $totalHT = $query->sum('total_ht');
        $totalTVA = $query->sum('tva');
        $count = $query->count();

        $clients = Facturef::whereBetween('date', [$from, $to])
                          ->where('currency', $currency)
                          ->distinct('client')
                          ->count('client');

        $moyenne = $count > 0 ? $total / $count : 0;

        return [
            'total' => $total,
            'total_ht' => $totalHT,
            'total_tva' => $totalTVA,
            'count' => $count,
            'clients' => $clients,
            'moyenne' => $moyenne,
        ];
    }

    private function getServicesRevenue($from, $to, $currency)
    {
        $query = Facture::whereBetween('date', [$from, $to])
                       ->where('type', 'service')
                       ->where('currency', $currency);

        $total = $query->sum('total_ttc');
        $totalHT = $query->sum('total_ht');
        $totalTVA = $query->sum('tva');
        $count = $query->count();

        $clients = Facture::whereBetween('date', [$from, $to])
                         ->where('type', 'service')
                         ->where('currency', $currency)
                         ->distinct('client')
                         ->count('client');

        $moyenne = $count > 0 ? $total / $count : 0;

        return [
            'total' => $total,
            'total_ht' => $totalHT,
            'total_tva' => $totalTVA,
            'count' => $count,
            'clients' => $clients,
            'moyenne' => $moyenne,
        ];
    }

    private function getStagesRevenue($from, $to)
    {
        $query = Reussite::whereBetween('date_paiement', [$from, $to]);

        $total = $query->sum('montant_paye');
        $count = $query->count();
        $stagiaires = $query->distinct('CIN')->count('CIN');
        $moyenne = $count > 0 ? $total / $count : 0;

        return [
            'total' => $total,
            'count' => $count,
            'stagiaires' => $stagiaires,
            'moyenne' => $moyenne,
        ];
    }

    private function getPortailRevenue($from, $to)
    {
        try {
            $response = Http::timeout(5)->withHeaders([
                'X-API-KEY' => 'S3CR3T_K3Y'
            ])->get('https://uits-portail.ma/api/monthly-revenue', [
                'date_from' => $from->format('Y-m-d'),
                'date_to' => $to->format('Y-m-d')
            ]);

            if ($response->successful()) {
                return (float) $response->json('total_sum', 0);
            }
        } catch (\Exception $e) {
            \Log::error("Error connecting to Portail API: " . $e->getMessage());
        }
        return 0;
    }

    private function getEvolutionMensuelle($currency)
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $months = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $monthStart = $currentDate->copy()->startOfMonth();
            $monthEnd = $currentDate->copy()->endOfMonth();

            // Formations
            $formations = Facturef::whereBetween('date', [$monthStart, $monthEnd])
                                 ->where('currency', $currency)
                                 ->sum('total_ttc');

            // Services
            $services = Facture::whereBetween('date', [$monthStart, $monthEnd])
                              ->where('type', 'service')
                              ->where('currency', $currency)
                              ->sum('total_ttc');

            // Stages
            $stages = Reussite::whereBetween('date_paiement', [$monthStart, $monthEnd])
                             ->sum('montant_paye');

            // Portail (par mois)
            $portail = $this->getPortailRevenue($monthStart, $monthEnd);

            $months[] = [
                'mois' => $currentDate->format('M Y'),
                'mois_court' => $currentDate->format('M'),
                'annee' => $currentDate->year,
                'formations' => $formations,
                'services' => $services,
                'stages' => $stages,
                'portail' => $portail,
                'total' => $formations + $services + $stages + $portail,
            ];

            $currentDate->addMonth();
        }

        return collect($months);
    }

    private function getComparison($from, $to, $currency)
    {
        // Période actuelle
        $currentFormations = Facturef::whereBetween('date', [$from, $to])
                                    ->where('currency', $currency)
                                    ->sum('total_ttc');
        $currentServices = Facture::whereBetween('date', [$from, $to])
                                  ->where('type', 'service')
                                  ->where('currency', $currency)
                                  ->sum('total_ttc');
        $currentStages = Reussite::whereBetween('date_paiement', [$from, $to])
                                ->sum('montant_paye');
        $currentPortail = $this->getPortailRevenue($from, $to);
        $currentTotal = $currentFormations + $currentServices + $currentStages + $currentPortail;

        // Période précédente
        $diff = $from->diffInDays($to);
        $prevFrom = $from->copy()->subDays($diff + 1);
        $prevTo = $to->copy()->subDays($diff + 1);

        $prevFormations = Facturef::whereBetween('date', [$prevFrom, $prevTo])
                                  ->where('currency', $currency)
                                  ->sum('total_ttc');
        $prevServices = Facture::whereBetween('date', [$prevFrom, $prevTo])
                              ->where('type', 'service')
                              ->where('currency', $currency)
                              ->sum('total_ttc');
        $prevStages = Reussite::whereBetween('date_paiement', [$prevFrom, $prevTo])
                             ->sum('montant_paye');
        $prevPortail = $this->getPortailRevenue($prevFrom, $prevTo);
        $prevTotal = $prevFormations + $prevServices + $prevStages + $prevPortail;

        // Calcul des variations
        $variationFormations = $prevFormations > 0 ? (($currentFormations - $prevFormations) / $prevFormations) * 100 : 0;
        $variationServices = $prevServices > 0 ? (($currentServices - $prevServices) / $prevServices) * 100 : 0;
        $variationStages = $prevStages > 0 ? (($currentStages - $prevStages) / $prevStages) * 100 : 0;
        $variationPortail = $prevPortail > 0 ? (($currentPortail - $prevPortail) / $prevPortail) * 100 : 0;
        $variationTotal = $prevTotal > 0 ? (($currentTotal - $prevTotal) / $prevTotal) * 100 : 0;

        return [
            'current' => $currentTotal,
            'previous' => $prevTotal,
            'variation' => $variationTotal,
            'formations_variation' => $variationFormations,
            'services_variation' => $variationServices,
            'stages_variation' => $variationStages,
            'portail_variation' => $variationPortail,
        ];
    }

    private function getTopFormations($from, $to, $currency)
    {
        return Facturef::whereBetween('date', [$from, $to])
                      ->where('currency', $currency)
                      ->select('titre', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_ttc) as total'))
                      ->groupBy('titre')
                      ->orderBy('total', 'desc')
                      ->limit(10)
                      ->get();
    }

    private function getTopClients($from, $to, $currency)
    {
        // Combiner formations et services
        $formationsClients = Facturef::whereBetween('date', [$from, $to])
                                    ->where('currency', $currency)
                                    ->select('client', DB::raw('SUM(total_ttc) as total'))
                                    ->groupBy('client');

        $servicesClients = Facture::whereBetween('date', [$from, $to])
                                 ->where('type', 'service')
                                 ->where('currency', $currency)
                                 ->select('client', DB::raw('SUM(total_ttc) as total'))
                                 ->groupBy('client');

        $combined = $formationsClients->union($servicesClients)
                                     ->get()
                                     ->groupBy('client')
                                     ->map(function ($items) {
                                         return [
                                             'client' => $items->first()->client,
                                             'total' => $items->sum('total')
                                         ];
                                     })
                                     ->sortByDesc('total')
                                     ->take(10)
                                     ->values();

        return $combined;
    }

    private function getDetailsParMois($from, $to, $currency)
    {
        $startDate = Carbon::parse($from)->startOfMonth();
        $endDate = Carbon::parse($to)->endOfMonth();

        $details = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $monthStart = $currentDate->copy()->startOfMonth();
            $monthEnd = $currentDate->copy()->endOfMonth();

            $formations = Facturef::whereBetween('date', [$monthStart, $monthEnd])
                                 ->where('currency', $currency)
                                 ->sum('total_ttc');
            $formationsCount = Facturef::whereBetween('date', [$monthStart, $monthEnd])
                                      ->where('currency', $currency)
                                      ->count();

            $services = Facture::whereBetween('date', [$monthStart, $monthEnd])
                              ->where('type', 'service')
                              ->where('currency', $currency)
                              ->sum('total_ttc');
            $servicesCount = Facture::whereBetween('date', [$monthStart, $monthEnd])
                                   ->where('type', 'service')
                                   ->where('currency', $currency)
                                   ->count();

            $stages = Reussite::whereBetween('date_paiement', [$monthStart, $monthEnd])
                             ->sum('montant_paye');
            $stagesCount = Reussite::whereBetween('date_paiement', [$monthStart, $monthEnd])
                                  ->count();

            $portail = $this->getPortailRevenue($monthStart, $monthEnd);

            $details[] = [
                'mois' => $currentDate->format('F Y'),
                'formations' => $formations,
                'formations_count' => $formationsCount,
                'services' => $services,
                'services_count' => $servicesCount,
                'stages' => $stages,
                'stages_count' => $stagesCount,
                'portail' => $portail,
                'total' => $formations + $services + $stages + $portail,
            ];

            $currentDate->addMonth();
        }

        return collect($details);
    }

    private function prepareChartData($evolutionMensuelle)
    {
        return [
            'labels' => $evolutionMensuelle->pluck('mois_court')->toArray(),
            'formations' => $evolutionMensuelle->pluck('formations')->toArray(),
            'services' => $evolutionMensuelle->pluck('services')->toArray(),
            'stages' => $evolutionMensuelle->pluck('stages')->toArray(),
            'portail' => $evolutionMensuelle->pluck('portail')->toArray(),
            'total' => $evolutionMensuelle->pluck('total')->toArray(),
        ];
    }

    // Export CSV
    public function exportCSV(Request $request)
    {
        $periode = $request->input('periode', 'ce_mois');
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $currency = $request->input('currency', 'DH');

        [$from, $to] = $this->getDateRange($periode, $dateDebut, $dateFin);
        $details = $this->getDetailsParMois($from, $to, $currency);

        $filename = 'beneficier_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($details, $currency) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'Mois', 
                'Formations (' . $currency . ')', 
                'Nb Formations',
                'Services (' . $currency . ')', 
                'Nb Services',
                'Stages (DH)', 
                'Nb Stages',
                'Portail (DH)',
                'Total (' . $currency . ')'
            ], ';');

            foreach ($details as $detail) {
                fputcsv($file, [
                    $detail['mois'],
                    number_format($detail['formations'], 2, ',', ' '),
                    $detail['formations_count'],
                    number_format($detail['services'], 2, ',', ' '),
                    $detail['services_count'],
                    number_format($detail['stages'], 2, ',', ' '),
                    $detail['stages_count'],
                    number_format($detail['portail'], 2, ',', ' '),
                    number_format($detail['total'], 2, ',', ' '),
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    
}