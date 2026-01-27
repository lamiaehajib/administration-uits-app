<?php

namespace App\Services;

use App\Models\DepenseFixe;
use App\Models\DepenseVariable;
use App\Models\HistoriqueSalaireApi;
use App\Models\BudgetMensuel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DepenseService
{
    protected $apiService;

    public function __construct(UitsMgmtApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * ✅ استيراد الرواتب من API + حساب CNSS
     */
    public function importerSalairesMensuel($annee = null, $mois = null)
    {
        $annee = $annee ?? now()->year;
        $mois = $mois ?? now()->month;

        DB::beginTransaction();
        try {
            // 1. التحقق من عدم التكرار
            $existe = HistoriqueSalaireApi::where('annee', $annee)
                ->where('mois', $mois)
                ->exists();

            if ($existe) {
                throw new \Exception("Les salaires de {$mois}/{$annee} sont déjà importés.");
            }

            // 2. استدعاء API
            $data = $this->apiService->getSalairesDetailsMois($annee, $mois);

            if (!$data || !$data['success']) {
                throw new \Exception("Erreur lors de la récupération des salaires depuis l'API.");
            }

            $salaires = $data['salaires'];
            $totalSalaires = $data['total'];
            $nombreEmployes = $data['count'];

            // 3. حفظ في historique_salaires_api
            $historique = HistoriqueSalaireApi::create([
                'annee' => $annee,
                'mois' => $mois,
                'nombre_employes' => $nombreEmployes,
                'montant_total' => $totalSalaires,
                'details_salaires' => $salaires,
                'statut' => 'importe',
                'importe_par' => auth()->id(),
                'importe_le' => now(),
            ]);

            // 4. إنشاء dépense fixe "Salaires"
            $dateDebut = Carbon::create($annee, $mois, 1);
            $dateFin = Carbon::create($annee, $mois, 1)->endOfMonth();

            $depenseSalaire = DepenseFixe::create([
                'type' => 'salaire',
                'libelle' => "Salaires {$dateDebut->format('F Y')}",
                'description' => "Import automatique depuis uits-mgmt.ma ({$nombreEmployes} employés)",
                'montant_mensuel' => $totalSalaires,
                'date_debut' => $dateDebut,
                'date_fin' => $dateFin,
                'statut' => 'actif',
                'rappel_actif' => false,
                'created_by' => auth()->id(),
            ]);

            // 5. Calculer CNSS (20.48% du total brut)
            $tauxCNSS = 20.48;
            $montantCNSS = round($totalSalaires * ($tauxCNSS / 100), 2);
            
            $partPatronale = round($montantCNSS * 0.7, 2); // 70%
            $partSalariale = round($montantCNSS * 0.3, 2); // 30%

            $depenseCNSS = DepenseVariable::create([
                'type' => 'cnss',
                'libelle' => "CNSS {$dateDebut->format('F Y')}",
                'description' => "Cotisation automatique ({$tauxCNSS}% de {$totalSalaires} DH)",
                'montant' => $montantCNSS,
                'date_depense' => Carbon::create($annee, $mois, 15), // CNSS payée le 15
                'annee' => $annee,
                'mois' => $mois,
                'montant_salaire_base' => $totalSalaires,
                'taux_cnss' => $tauxCNSS,
                'repartition_cnss' => [
                    'part_patronale' => $partPatronale,
                    'part_salariale' => $partSalariale,
                ],
                'statut' => 'validee',
                'created_by' => auth()->id(),
            ]);

            // 6. Mettre à jour historique
            $historique->update(['statut' => 'integre']);

            // 7. Mettre à jour le budget mensuel
            $this->updateBudgetMensuel($annee, $mois);

            DB::commit();

            return [
                'success' => true,
                'message' => "Salaires importés avec succès!",
                'data' => [
                    'nombre_employes' => $nombreEmployes,
                    'total_salaires' => $totalSalaires,
                    'montant_cnss' => $montantCNSS,
                    'depense_salaire_id' => $depenseSalaire->id,
                    'depense_cnss_id' => $depenseCNSS->id,
                ]
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur importerSalairesMensuel: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * ✅ حساب مجموع المصاريف لشهر معين
     */
    public function calculerTotauxMois($annee, $mois)
    {
        $debut = Carbon::create($annee, $mois, 1)->startOfMonth();
        $fin = Carbon::create($annee, $mois, 1)->endOfMonth();

        $fixes = DepenseFixe::pourMois($annee, $mois)->sum('montant_mensuel');
        
        $variables = DepenseVariable::pourMois($annee, $mois)
            ->validee()
            ->sum('montant');

        return [
            'fixes' => $fixes,
            'variables' => $variables,
            'total' => $fixes + $variables,
            
            // Détails variables
            'factures' => DepenseVariable::pourMois($annee, $mois)
                ->parType('facture_recue')
                ->validee()
                ->sum('montant'),
                
            'primes' => DepenseVariable::pourMois($annee, $mois)
                ->parType('prime')
                ->validee()
                ->sum('montant'),
                
            'cnss' => DepenseVariable::pourMois($annee, $mois)
                ->parType('cnss')
                ->validee()
                ->sum('montant'),
                
            'transport' => DepenseVariable::pourMois($annee, $mois)
                ->parType('transport')
                ->validee()
                ->sum('montant'),
        ];
    }
    public function updateBudgetMensuel($annee, $mois)
{
    $budget = BudgetMensuel::firstOrCreate(
        ['annee' => $annee, 'mois' => $mois],
        [
            'budget_fixes' => 0,
            'budget_variables' => 0,
            'statut' => 'en_cours',
            'created_by' => auth()->id(),
        ]
    );

    $budget->recalculerDepenses();

    // تنبيه إذا تجاوز 90%
    if ($budget->taux_execution >= 90 && !$budget->alerte_depassement) {
        $budget->update([
            'alerte_depassement' => true,
            'date_alerte' => now(),
        ]);

        // TODO: إرسال إشعار
    }

    return $budget;
}
}