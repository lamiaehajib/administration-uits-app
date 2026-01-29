<?php

namespace App\Http\Controllers;

use App\Models\Facturef;
use App\Models\Facture;
use App\Models\Reussite;
use App\Models\DepenseFixe;
use App\Models\DepenseVariable;
use App\Models\BudgetMensuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BeneficeMargeController extends Controller
{
    // Taux de conversion (tu peux les mettre en config ou base de données)
    private $tauxConversion = [
        'DH' => 1,
        'EUR' => 11.0,  // 1 EUR = 11 DH (ajuste selon taux réel)
        'USD' => 10.0,  // 1 USD = 10 DH
        'GBP' => 13.0,  // 1 GBP = 13 DH
    ];

    /**
     * Dashboard Principal - Vue Complète
     */
    public function dashboard(Request $request)
    {
        // 1. Paramètres de filtrage
        $periode = $request->input('periode', 'ce_mois');
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        $comparaison = $request->input('comparaison', 'mois_precedent'); // mois_precedent, annee_precedente, aucune

        // 2. Calculer les dates
        [$from, $to] = $this->getDateRange($periode, $dateDebut, $dateFin);

        // 3. REVENUS (Tout converti en DH)
        $revenus = $this->getRevenusTotalEnDH($from, $to);

        // 4. CHARGES (Dépenses Fixes + Variables)
        $charges = $this->getChargesTotales($from, $to);

        // 5. MARGE NETTE
        $margeNette = $revenus['total'] - $charges['total'];
        $tauxMarge = $revenus['total'] > 0 ? ($margeNette / $revenus['total']) * 100 : 0;

        // 6. KPIs Principaux
        $kpis = [
            'revenus_total' => $revenus['total'],
            'charges_total' => $charges['total'],
            'marge_nette' => $margeNette,
            'taux_marge' => round($tauxMarge, 2),
            'nombre_transactions' => $revenus['nb_transactions'],
            'panier_moyen' => $revenus['panier_moyen'],
        ];

        // 7. Évolution sur 12 mois
        $evolution = $this->getEvolution12Mois();

        // 8. Comparaison avec période précédente
        $comparaisonData = null;
        if ($comparaison !== 'aucune') {
            $comparaisonData = $this->getComparaison($from, $to, $comparaison);
        }

        // 9. Répartition des revenus par source
        $repartitionRevenus = $this->getRepartitionRevenus($from, $to);

        // 10. Répartition des charges par type
        $repartitionCharges = $this->getRepartitionCharges($from, $to);

        // 11. Top 10 sources de revenus
        $topRevenus = $this->getTopRevenus($from, $to);

        // 12. Top 10 dépenses
        $topDepenses = $this->getTopDepenses($from, $to);

        // 13. Analyse mensuelle détaillée
        $analyseMensuelle = $this->getAnalyseMensuelle($from, $to);

        // 14. Prévisions budget vs réalisé
        $budgetAnalyse = $this->getBudgetAnalyse($from, $to);

        // 15. Données pour charts
        $chartData = $this->prepareChartData($evolution, $repartitionRevenus, $repartitionCharges);

        // 16. Alertes et recommandations
        $alertes = $this->getAlertes($kpis, $budgetAnalyse, $comparaisonData);

        return view('benefice-marge.dashboard', compact(
            'kpis',
            'revenus',
            'charges',
            'evolution',
            'comparaisonData',
            'repartitionRevenus',
            'repartitionCharges',
            'topRevenus',
            'topDepenses',
            'analyseMensuelle',
            'budgetAnalyse',
            'chartData',
            'alertes',
            'from',
            'to',
            'periode',
            'comparaison'
        ));
    }

    // ================== MÉTHODES REVENUS ==================

    /**
     * Récupérer tous les revenus convertis en DH
     */
    private function getRevenusTotalEnDH($from, $to)
    {
        $total = 0;
        $details = [];
        $nbTransactions = 0;

        // 1. Formations (toutes devises → DH)
        $formations = Facturef::whereBetween('date', [$from, $to])
            ->select('currency', DB::raw('SUM(total_ttc) as total'), DB::raw('COUNT(*) as nb'))
            ->groupBy('currency')
            ->get();

        foreach ($formations as $f) {
            $montantDH = $f->total * $this->getTauxConversion($f->currency);
            $total += $montantDH;
            $nbTransactions += $f->nb;
            $details['formations'][$f->currency] = [
                'montant_origine' => $f->total,
                'montant_dh' => $montantDH,
                'nb' => $f->nb,
            ];
        }

        // 2. Services (toutes devises → DH)
        $services = Facture::whereBetween('date', [$from, $to])
            ->where('type', 'service')
            ->select('currency', DB::raw('SUM(total_ttc) as total'), DB::raw('COUNT(*) as nb'))
            ->groupBy('currency')
            ->get();

        foreach ($services as $s) {
            $montantDH = $s->total * $this->getTauxConversion($s->currency);
            $total += $montantDH;
            $nbTransactions += $s->nb;
            $details['services'][$s->currency] = [
                'montant_origine' => $s->total,
                'montant_dh' => $montantDH,
                'nb' => $s->nb,
            ];
        }

        // 3. Stages (déjà en DH)
        $stages = Reussite::whereBetween('date_paiement', [$from, $to])
            ->selectRaw('SUM(montant_paye) as total, COUNT(*) as nb')
            ->first();

        if ($stages && $stages->total) {
            $total += $stages->total;
            $nbTransactions += $stages->nb;
            $details['stages'] = [
                'montant_dh' => $stages->total,
                'nb' => $stages->nb,
            ];
        }

        // 4. Portail (déjà en DH)
        $portail = $this->getPortailRevenue($from, $to);
        if ($portail > 0) {
            $total += $portail;
            $details['portail'] = [
                'montant_dh' => $portail,
            ];
        }

        // Calculs globaux
        $totalFormations = collect($details['formations'] ?? [])->sum('montant_dh');
        $totalServices = collect($details['services'] ?? [])->sum('montant_dh');
        $totalStages = $details['stages']['montant_dh'] ?? 0;
        $totalPortail = $details['portail']['montant_dh'] ?? 0;

        return [
            'total' => $total,
            'formations' => $totalFormations,
            'services' => $totalServices,
            'stages' => $totalStages,
            'portail' => $totalPortail,
            'nb_transactions' => $nbTransactions,
            'panier_moyen' => $nbTransactions > 0 ? $total / $nbTransactions : 0,
            'details' => $details,
        ];
    }

    /**
     * Récupérer revenus Portail
     */
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

    // ================== MÉTHODES CHARGES ==================

    /**
     * Récupérer toutes les charges (Fixes + Variables)
     */
    private function getChargesTotales($from, $to)
    {
        $fromStart = Carbon::parse($from)->startOfMonth();
        $toEnd = Carbon::parse($to)->endOfMonth();

        // 1. Dépenses Fixes
        $chargesFixes = 0;
        $detailsFixes = [];

        $currentDate = $fromStart->copy();
        while ($currentDate <= $toEnd) {
            $depensesFixes = DepenseFixe::pourMois($currentDate->year, $currentDate->month)->get();
            
            foreach ($depensesFixes as $df) {
                $chargesFixes += $df->montant_mensuel;
                
                $type = $df->type;
                if (!isset($detailsFixes[$type])) {
                    $detailsFixes[$type] = [
                        'montant' => 0,
                        'nb' => 0,
                        'libelle' => $df->type_libelle,
                    ];
                }
                $detailsFixes[$type]['montant'] += $df->montant_mensuel;
                $detailsFixes[$type]['nb']++;
            }

            $currentDate->addMonth();
        }

        // 2. Dépenses Variables
        $chargesVariables = 0;
        $detailsVariables = [];

        $currentDate = $fromStart->copy();
        while ($currentDate <= $toEnd) {
            $depensesVariables = DepenseVariable::pourMois($currentDate->year, $currentDate->month)
                ->validee()
                ->get();
            
            foreach ($depensesVariables as $dv) {
                $chargesVariables += $dv->montant;
                
                $type = $dv->type;
                if (!isset($detailsVariables[$type])) {
                    $detailsVariables[$type] = [
                        'montant' => 0,
                        'nb' => 0,
                        'libelle' => $dv->type_libelle,
                    ];
                }
                $detailsVariables[$type]['montant'] += $dv->montant;
                $detailsVariables[$type]['nb']++;
            }

            $currentDate->addMonth();
        }

        return [
            'total' => $chargesFixes + $chargesVariables,
            'fixes' => $chargesFixes,
            'variables' => $chargesVariables,
            'details_fixes' => $detailsFixes,
            'details_variables' => $detailsVariables,
            'nb_fixes' => collect($detailsFixes)->sum('nb'),
            'nb_variables' => collect($detailsVariables)->sum('nb'),
        ];
    }

    // ================== MÉTHODES ANALYSE ==================

    /**
     * Évolution sur 12 derniers mois
     */
    private function getEvolution12Mois()
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $months = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $monthStart = $currentDate->copy()->startOfMonth();
            $monthEnd = $currentDate->copy()->endOfMonth();

            // Revenus
            $revenus = $this->getRevenusTotalEnDH($monthStart, $monthEnd);
            
            // Charges
            $charges = $this->getChargesTotales($monthStart, $monthEnd);
            
            // Marge
            $marge = $revenus['total'] - $charges['total'];
            $tauxMarge = $revenus['total'] > 0 ? ($marge / $revenus['total']) * 100 : 0;

            $months[] = [
                'mois' => $currentDate->format('M Y'),
                'mois_court' => $currentDate->format('M'),
                'annee' => $currentDate->year,
                'mois_num' => $currentDate->month,
                'revenus' => round($revenus['total'], 2),
                'charges' => round($charges['total'], 2),
                'marge_nette' => round($marge, 2),
                'taux_marge' => round($tauxMarge, 2),
                'revenus_formations' => round($revenus['formations'], 2),
                'revenus_services' => round($revenus['services'], 2),
                'revenus_stages' => round($revenus['stages'], 2),
                'revenus_portail' => round($revenus['portail'], 2),
                'charges_fixes' => round($charges['fixes'], 2),
                'charges_variables' => round($charges['variables'], 2),
            ];

            $currentDate->addMonth();
        }

        return collect($months);
    }

    /**
     * Comparaison avec période précédente
     */
    private function getComparaison($from, $to, $type)
    {
        $diff = $from->diffInDays($to);

        if ($type === 'mois_precedent') {
            $prevFrom = $from->copy()->subMonth()->startOfMonth();
            $prevTo = $from->copy()->subMonth()->endOfMonth();
        } elseif ($type === 'annee_precedente') {
            $prevFrom = $from->copy()->subYear();
            $prevTo = $to->copy()->subYear();
        } else {
            $prevFrom = $from->copy()->subDays($diff + 1);
            $prevTo = $to->copy()->subDays($diff + 1);
        }

        // Période actuelle
        $currentRevenus = $this->getRevenusTotalEnDH($from, $to);
        $currentCharges = $this->getChargesTotales($from, $to);
        $currentMarge = $currentRevenus['total'] - $currentCharges['total'];

        // Période précédente
        $prevRevenus = $this->getRevenusTotalEnDH($prevFrom, $prevTo);
        $prevCharges = $this->getChargesTotales($prevFrom, $prevTo);
        $prevMarge = $prevRevenus['total'] - $prevCharges['total'];

        // Calcul variations
        $variationRevenus = $prevRevenus['total'] > 0 
            ? (($currentRevenus['total'] - $prevRevenus['total']) / $prevRevenus['total']) * 100 
            : 0;
        
        $variationCharges = $prevCharges['total'] > 0 
            ? (($currentCharges['total'] - $prevCharges['total']) / $prevCharges['total']) * 100 
            : 0;
        
        $variationMarge = $prevMarge > 0 
            ? (($currentMarge - $prevMarge) / abs($prevMarge)) * 100 
            : 0;

        return [
            'periode_precedente' => [
                'from' => $prevFrom,
                'to' => $prevTo,
                'revenus' => $prevRevenus['total'],
                'charges' => $prevCharges['total'],
                'marge' => $prevMarge,
            ],
            'periode_actuelle' => [
                'from' => $from,
                'to' => $to,
                'revenus' => $currentRevenus['total'],
                'charges' => $currentCharges['total'],
                'marge' => $currentMarge,
            ],
            'variations' => [
                'revenus' => round($variationRevenus, 2),
                'charges' => round($variationCharges, 2),
                'marge' => round($variationMarge, 2),
            ],
        ];
    }

    /**
     * Répartition revenus par source
     */
    private function getRepartitionRevenus($from, $to)
    {
        $revenus = $this->getRevenusTotalEnDH($from, $to);

        if ($revenus['total'] == 0) {
            return [];
        }

        return [
            [
                'source' => 'Formations',
                'montant' => $revenus['formations'],
                'pourcentage' => round(($revenus['formations'] / $revenus['total']) * 100, 2),
                'color' => '#3B82F6',
            ],
            [
                'source' => 'Services',
                'montant' => $revenus['services'],
                'pourcentage' => round(($revenus['services'] / $revenus['total']) * 100, 2),
                'color' => '#10B981',
            ],
            [
                'source' => 'Stages',
                'montant' => $revenus['stages'],
                'pourcentage' => round(($revenus['stages'] / $revenus['total']) * 100, 2),
                'color' => '#F59E0B',
            ],
            [
                'source' => 'Portail',
                'montant' => $revenus['portail'],
                'pourcentage' => round(($revenus['portail'] / $revenus['total']) * 100, 2),
                'color' => '#8B5CF6',
            ],
        ];
    }

    /**
     * Répartition charges par type
     */
    private function getRepartitionCharges($from, $to)
    {
        $charges = $this->getChargesTotales($from, $to);

        if ($charges['total'] == 0) {
            return [];
        }

        $repartition = [];

        // Charges fixes
        foreach ($charges['details_fixes'] as $type => $data) {
            $repartition[] = [
                'type' => $data['libelle'],
                'categorie' => 'Fixe',
                'montant' => $data['montant'],
                'pourcentage' => round(($data['montant'] / $charges['total']) * 100, 2),
                'nb' => $data['nb'],
                'color' => $this->getColorForType($type),
            ];
        }

        // Charges variables
        foreach ($charges['details_variables'] as $type => $data) {
            $repartition[] = [
                'type' => $data['libelle'],
                'categorie' => 'Variable',
                'montant' => $data['montant'],
                'pourcentage' => round(($data['montant'] / $charges['total']) * 100, 2),
                'nb' => $data['nb'],
                'color' => $this->getColorForType($type),
            ];
        }

        // Trier par montant décroissant
        usort($repartition, function($a, $b) {
            return $b['montant'] <=> $a['montant'];
        });

        return $repartition;
    }

    /**
     * Top 10 sources de revenus
     */
    private function getTopRevenus($from, $to)
    {
        $top = [];

        // Formations
        $topFormations = Facturef::whereBetween('date', [$from, $to])
            ->select('titre', 'currency', DB::raw('COUNT(*) as nb'), DB::raw('SUM(total_ttc) as total'))
            ->groupBy('titre', 'currency')
            ->orderByRaw('SUM(total_ttc) DESC')
            ->limit(10)
            ->get();

        foreach ($topFormations as $f) {
            $montantDH = $f->total * $this->getTauxConversion($f->currency);
            $top[] = [
                'libelle' => $f->titre,
                'type' => 'Formation',
                'montant_origine' => $f->total,
                'currency' => $f->currency,
                'montant_dh' => $montantDH,
                'nb' => $f->nb,
            ];
        }

        // Trier par montant DH
        usort($top, function($a, $b) {
            return $b['montant_dh'] <=> $a['montant_dh'];
        });

        return collect($top)->take(10);
    }

    /**
     * Top 10 dépenses
     */
    private function getTopDepenses($from, $to)
    {
        $top = [];

        $fromStart = Carbon::parse($from)->startOfMonth();
        $toEnd = Carbon::parse($to)->endOfMonth();

        // Dépenses fixes
        $currentDate = $fromStart->copy();
        while ($currentDate <= $toEnd) {
            $depensesFixes = DepenseFixe::pourMois($currentDate->year, $currentDate->month)->get();
            
            foreach ($depensesFixes as $df) {
                $key = $df->type . '_' . ($df->libelle ?? '');
                if (!isset($top[$key])) {
                    $top[$key] = [
                        'libelle' => $df->libelle_complet,
                        'type' => 'Fixe - ' . $df->type_libelle,
                        'montant' => 0,
                        'nb_mois' => 0,
                    ];
                }
                $top[$key]['montant'] += $df->montant_mensuel;
                $top[$key]['nb_mois']++;
            }

            $currentDate->addMonth();
        }

        // Dépenses variables (groupées par type)
        $currentDate = $fromStart->copy();
        while ($currentDate <= $toEnd) {
            $depensesVariables = DepenseVariable::pourMois($currentDate->year, $currentDate->month)
                ->validee()
                ->select('type', 'libelle', DB::raw('SUM(montant) as total'), DB::raw('COUNT(*) as nb'))
                ->groupBy('type', 'libelle')
                ->get();
            
            foreach ($depensesVariables as $dv) {
                $key = 'var_' . $dv->type . '_' . ($dv->libelle ?? '');
                if (!isset($top[$key])) {
                    $top[$key] = [
                        'libelle' => $dv->libelle ?? $dv->type_libelle,
                        'type' => 'Variable - ' . $dv->type_libelle,
                        'montant' => 0,
                        'nb' => 0,
                    ];
                }
                $top[$key]['montant'] += $dv->total;
                $top[$key]['nb'] += $dv->nb;
            }

            $currentDate->addMonth();
        }

        // Trier et retourner top 10
        $top = collect($top)->sortByDesc('montant')->take(10)->values();

        return $top;
    }

    /**
     * Analyse mensuelle détaillée
     */
    private function getAnalyseMensuelle($from, $to)
    {
        $startDate = Carbon::parse($from)->startOfMonth();
        $endDate = Carbon::parse($to)->endOfMonth();

        $analyse = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $monthStart = $currentDate->copy()->startOfMonth();
            $monthEnd = $currentDate->copy()->endOfMonth();

            $revenus = $this->getRevenusTotalEnDH($monthStart, $monthEnd);
            $charges = $this->getChargesTotales($monthStart, $monthEnd);
            $marge = $revenus['total'] - $charges['total'];
            $tauxMarge = $revenus['total'] > 0 ? ($marge / $revenus['total']) * 100 : 0;

            $analyse[] = [
                'mois' => $currentDate->format('F Y'),
                'mois_court' => $currentDate->format('M Y'),
                'revenus' => [
                    'total' => $revenus['total'],
                    'formations' => $revenus['formations'],
                    'services' => $revenus['services'],
                    'stages' => $revenus['stages'],
                    'portail' => $revenus['portail'],
                    'nb_transactions' => $revenus['nb_transactions'],
                ],
                'charges' => [
                    'total' => $charges['total'],
                    'fixes' => $charges['fixes'],
                    'variables' => $charges['variables'],
                    'nb_fixes' => $charges['nb_fixes'],
                    'nb_variables' => $charges['nb_variables'],
                ],
                'marge' => [
                    'nette' => $marge,
                    'taux' => round($tauxMarge, 2),
                ],
            ];

            $currentDate->addMonth();
        }

        return collect($analyse);
    }

    /**
     * Analyse Budget vs Réalisé
     */
    private function getBudgetAnalyse($from, $to)
    {
        $startDate = Carbon::parse($from)->startOfMonth();
        $endDate = Carbon::parse($to)->endOfMonth();

        $analyse = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $budget = BudgetMensuel::pourMois($currentDate->year, $currentDate->month)->first();

            if ($budget) {
                $budget->recalculerDepenses();
                
                $ecartFixes = $budget->depense_fixes_realisee - $budget->budget_fixes;
                $ecartVariables = $budget->depense_variables_realisee - $budget->budget_variables;
                $budgetTotal = $budget->budget_fixes + $budget->budget_variables;
                $realisationTotal = $budget->depense_fixes_realisee + $budget->depense_variables_realisee;
                $ecartTotal = $realisationTotal - $budgetTotal;

                $analyse[] = [
                    'mois' => $currentDate->format('F Y'),
                    'budget' => [
                        'fixes' => $budget->budget_fixes,
                        'variables' => $budget->budget_variables,
                        'total' => $budgetTotal,
                    ],
                    'realise' => [
                        'fixes' => $budget->depense_fixes_realisee,
                        'variables' => $budget->depense_variables_realisee,
                        'total' => $realisationTotal,
                    ],
                    'ecart' => [
                        'fixes' => $ecartFixes,
                        'variables' => $ecartVariables,
                        'total' => $ecartTotal,
                    ],
                    'taux_execution' => $budgetTotal > 0 ? round(($realisationTotal / $budgetTotal) * 100, 2) : 0,
                    'depasse' => $ecartTotal > 0,
                    'statut' => $budget->statut,
                ];
            }

            $currentDate->addMonth();
        }

        return collect($analyse);
    }

    /**
     * Générer alertes et recommandations
     */
    private function getAlertes($kpis, $budgetAnalyse, $comparaison)
    {
        $alertes = [];

        // Alerte marge négative
        if ($kpis['marge_nette'] < 0) {
            $alertes[] = [
                'type' => 'danger',
                'titre' => 'Marge négative',
                'message' => 'Votre marge est négative de ' . number_format(abs($kpis['marge_nette']), 2) . ' DH. Les charges dépassent les revenus.',
                'action' => 'Réduire les dépenses ou augmenter les revenus',
            ];
        }

        // Alerte taux de marge faible
        if ($kpis['taux_marge'] > 0 && $kpis['taux_marge'] < 15) {
            $alertes[] = [
                'type' => 'warning',
                'titre' => 'Taux de marge faible',
                'message' => 'Votre taux de marge est de ' . $kpis['taux_marge'] . '%, ce qui est relativement faible.',
                'action' => 'Optimiser les coûts ou revoir la stratégie tarifaire',
            ];
        }

        // Alerte budget dépassé
        $budgetsDepasses = $budgetAnalyse->where('depasse', true);
        if ($budgetsDepasses->count() > 0) {
            $montantDepasse = $budgetsDepasses->sum('ecart.total');
            $alertes[] = [
                'type' => 'warning',
                'titre' => 'Budgets dépassés',
                'message' => $budgetsDepasses->count() . ' mois avec budget dépassé (total: ' . number_format($montantDepasse, 2) . ' DH)',
                'action' => 'Revoir les budgets prévisionnels',
            ];
        }

        // Alerte baisse revenus
        if ($comparaison && $comparaison['variations']['revenus'] < -10) {
            $alertes[] = [
                'type' => 'danger',
                'titre' => 'Baisse des revenus',
                'message' => 'Les revenus ont baissé de ' . abs($comparaison['variations']['revenus']) . '% par rapport à la période précédente.',
                'action' => 'Analyser les causes et mettre en place un plan d\'action',
            ];
        }

        // Recommandation augmentation charges
        if ($comparaison && $comparaison['variations']['charges'] > 20) {
            $alertes[] = [
                'type' => 'info',
                'titre' => 'Augmentation des charges',
                'message' => 'Les charges ont augmenté de ' . $comparaison['variations']['charges'] . '%.',
                'action' => 'Identifier les postes en hausse et optimiser',
            ];
        }

        // Recommandation positive
        if ($kpis['taux_marge'] > 30) {
            $alertes[] = [
                'type' => 'success',
                'titre' => 'Excellente rentabilité',
                'message' => 'Votre taux de marge est excellent (' . $kpis['taux_marge'] . '%).',
                'action' => 'Maintenir cette dynamique',
            ];
        }

        return collect($alertes);
    }

    /**
     * Préparer données pour charts
     */
    private function prepareChartData($evolution, $repartitionRevenus, $repartitionCharges)
    {
        return [
            // Evolution temporelle
            'evolution' => [
                'labels' => $evolution->pluck('mois_court')->toArray(),
                'revenus' => $evolution->pluck('revenus')->toArray(),
                'charges' => $evolution->pluck('charges')->toArray(),
                'marge' => $evolution->pluck('marge_nette')->toArray(),
            ],
            
            // Répartition revenus (Pie)
            'repartition_revenus' => [
                'labels' => collect($repartitionRevenus)->pluck('source')->toArray(),
                'data' => collect($repartitionRevenus)->pluck('montant')->toArray(),
                'colors' => collect($repartitionRevenus)->pluck('color')->toArray(),
            ],
            
            // Répartition charges (Pie)
            'repartition_charges' => [
                'labels' => collect($repartitionCharges)->pluck('type')->toArray(),
                'data' => collect($repartitionCharges)->pluck('montant')->toArray(),
                'colors' => collect($repartitionCharges)->pluck('color')->toArray(),
            ],
            
            // Evolution par source
            'evolution_sources' => [
                'labels' => $evolution->pluck('mois_court')->toArray(),
                'formations' => $evolution->pluck('revenus_formations')->toArray(),
                'services' => $evolution->pluck('revenus_services')->toArray(),
                'stages' => $evolution->pluck('revenus_stages')->toArray(),
                'portail' => $evolution->pluck('revenus_portail')->toArray(),
            ],
            
            // Evolution charges fixes vs variables
            'evolution_charges' => [
                'labels' => $evolution->pluck('mois_court')->toArray(),
                'fixes' => $evolution->pluck('charges_fixes')->toArray(),
                'variables' => $evolution->pluck('charges_variables')->toArray(),
            ],
        ];
    }

    // ================== MÉTHODES UTILITAIRES ==================

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

    private function getTauxConversion($currency)
    {
        return $this->tauxConversion[$currency] ?? 1;
    }

    private function getColorForType($type)
    {
        $colors = [
            'salaire' => '#EF4444',
            'loyer' => '#F59E0B',
            'internet' => '#3B82F6',
            'mobile' => '#8B5CF6',
            'srmc' => '#10B981',
            'femme_menage' => '#EC4899',
            'frais_aups' => '#14B8A6',
            'prime' => '#F97316',
            'cnss' => '#6366F1',
            'publication' => '#A855F7',
            'transport' => '#84CC16',
            'dgi' => '#F43F5E',
            'comptabilite' => '#06B6D4',
            'autre' => '#64748B',
        ];

        return $colors[$type] ?? '#94A3B8';
    }

    // ================== EXPORT ==================

    /**
     * Export complet en Excel
     */
    public function exportExcel(Request $request)
    {
        // À implémenter avec Laravel Excel
        // Exporter toutes les données: KPIs, évolution, détails, etc.
    }

    /**
     * Export CSV simplifié
     */
    public function exportCSV(Request $request)
    {
        $periode = $request->input('periode', 'ce_mois');
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        [$from, $to] = $this->getDateRange($periode, $dateDebut, $dateFin);
        $analyse = $this->getAnalyseMensuelle($from, $to);

        $filename = 'marge_nette_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($analyse) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'Mois',
                'Revenus Total (DH)',
                'Formations (DH)',
                'Services (DH)',
                'Stages (DH)',
                'Portail (DH)',
                'Charges Total (DH)',
                'Charges Fixes (DH)',
                'Charges Variables (DH)',
                'Marge Nette (DH)',
                'Taux Marge (%)',
            ], ';');

            foreach ($analyse as $mois) {
                fputcsv($file, [
                    $mois['mois'],
                    number_format($mois['revenus']['total'], 2, ',', ' '),
                    number_format($mois['revenus']['formations'], 2, ',', ' '),
                    number_format($mois['revenus']['services'], 2, ',', ' '),
                    number_format($mois['revenus']['stages'], 2, ',', ' '),
                    number_format($mois['revenus']['portail'], 2, ',', ' '),
                    number_format($mois['charges']['total'], 2, ',', ' '),
                    number_format($mois['charges']['fixes'], 2, ',', ' '),
                    number_format($mois['charges']['variables'], 2, ',', ' '),
                    number_format($mois['marge']['nette'], 2, ',', ' '),
                    number_format($mois['marge']['taux'], 2, ',', ' '),
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}