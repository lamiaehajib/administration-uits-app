<?php

namespace App\Http\Controllers;

use App\Models\DepenseFixe;
use App\Models\DepenseVariable;
use App\Models\BudgetMensuel;
use App\Models\HistoriqueSalaireApi;
use App\Models\FactureRecue;
use App\Services\UitsMgmtApiService;
use App\Services\DepenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DepenseController extends Controller
{
    protected $depenseService;
    protected $apiService;

    public function __construct(DepenseService $depenseService, UitsMgmtApiService $apiService)
    {
        $this->depenseService = $depenseService;
        $this->apiService = $apiService;
    }

    // ========================================
    // 🏠 DASHBOARD PRINCIPAL
    // ========================================
    
    public function dashboard(Request $request)
    {
        $annee = $request->get('annee', now()->year);
        $mois = $request->get('mois', now()->month);

        // 📊 Totaux du mois
        $totaux = $this->depenseService->calculerTotauxMois($annee, $mois);

        // 📅 Budget mensuel
        $budget = BudgetMensuel::pourMois($annee, $mois)->first();

        // 📈 Évolution 12 derniers mois
        $evolution = collect(range(11, 0))->map(function($i) {
            $date = now()->subMonths($i);
            $totaux = $this->depenseService->calculerTotauxMois($date->year, $date->month);
            
            return [
                'mois' => $date->format('M Y'),
                'mois_num' => $date->month,
                'annee' => $date->year,
                'fixes' => $totaux['fixes'],
                'variables' => $totaux['variables'],
                'total' => $totaux['total'],
            ];
        });

        // 🔝 Top 5 dépenses fixes
        $topFixes = DepenseFixe::actif()
            ->orderBy('montant_mensuel', 'desc')
            ->limit(5)
            ->get();

        // 🔝 Top 5 dépenses variables du mois
        $topVariables = DepenseVariable::pourMois($annee, $mois)
            ->validee()
            ->orderBy('montant', 'desc')
            ->limit(5)
            ->get();

        // ⚠️ Alertes
        $alertes = [
            'budget_depasse' => $budget && $budget->is_depasse,
            'factures_en_attente' => DepenseVariable::enAttente()->count(),
            'rappels_du_jour' => $this->getRappelsDuJour(),
        ];

        // 📊 Répartition par type (variables)
        $repartitionTypes = DepenseVariable::pourMois($annee, $mois)
            ->validee()
            ->select('type', DB::raw('SUM(montant) as total'))
            ->groupBy('type')
            ->get()
            ->map(function($item) {
                return [
                    'type' => $item->type_libelle,
                    'montant' => $item->total,
                ];
            });

        return view('depenses.dashboard', compact(
            'annee',
            'mois',
            'totaux',
            'budget',
            'evolution',
            'topFixes',
            'topVariables',
            'alertes',
            'repartitionTypes'
        ));
    }

    // ========================================
    // 💰 DÉPENSES FIXES
    // ========================================
    
    /**
     * Liste dépenses fixes
     */
    public function indexFixes(Request $request)
    {
        $query = DepenseFixe::with(['createdBy', 'updatedBy']);

        // Filtres
        if ($request->filled('type')) {
            $query->parType($request->type);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('libelle', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortField = $request->get('sort', 'date_debut');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Stats
        $stats = [
            'total' => DepenseFixe::actif()->sum('montant_mensuel'),
            'count' => DepenseFixe::actif()->count(),
            'actifs' => DepenseFixe::where('statut', 'actif')->count(),
            'inactifs' => DepenseFixe::where('statut', 'inactif')->count(),
            'par_type' => DepenseFixe::actif()
                ->select('type', DB::raw('COUNT(*) as count, SUM(montant_mensuel) as total'))
                ->groupBy('type')
                ->get(),
        ];

        $depenses = $query->paginate(15);

        return view('depenses.fixes.index', compact('depenses', 'stats'));
    }

    /**
     * Créer dépense fixe
     */
    public function createFixe()
    {
        return view('depenses.fixes.create');
    }

    /**
     * Enregistrer dépense fixe
     */
    public function storeFixe(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:salaire,loyer,internet,mobile,srmc,femme_menage,frais_aups,autre',
            'libelle' => 'required_if:type,autre|nullable|string|max:255',
            'description' => 'nullable|string',
            'montant_mensuel' => 'required|numeric|min:0',
            'reference_contrat' => 'nullable|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'statut' => 'required|in:actif,inactif,suspendu',
            'rappel_actif' => 'boolean',
            'jour_paiement' => 'required|integer|min:1|max:31',
            'rappel_avant_jours' => 'required|integer|min:1|max:30',
            'fichier_contrat' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Upload fichier
            if ($request->hasFile('fichier_contrat')) {
                $validated['fichier_contrat'] = $request->file('fichier_contrat')
                    ->store('contrats', 'public');
            }

            $validated['created_by'] = auth()->id();

            $depense = DepenseFixe::create($validated);

            // Mettre à jour budget du mois
            $this->depenseService->updateBudgetMensuel(
                $depense->date_debut->year,
                $depense->date_debut->month
            );

            DB::commit();

            return redirect()->route('depenses.fixes.index')
                ->with('success', 'Dépense fixe créée avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création dépense fixe: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher dépense fixe
     */
    public function showFixe($id)
    {
        $depense = DepenseFixe::with(['createdBy', 'updatedBy'])->findOrFail($id);
        
        // Historique paiements (si tu as un système de paiements)
        $historiquePaiements = [];
        
        // Montant total sur la période
        $montantTotal = $depense->montantPourPeriode(
            $depense->date_debut,
            $depense->date_fin ?? now()
        );

        return view('depenses.fixes.show', compact('depense', 'montantTotal', 'historiquePaiements'));
    }

    /**
     * Éditer dépense fixe
     */
    public function editFixe($id)
    {
        $depense = DepenseFixe::findOrFail($id);
        return view('depenses.fixes.edit', compact('depense'));
    }

    /**
     * Mettre à jour dépense fixe
     */
    public function updateFixe(Request $request, $id)
    {
        $depense = DepenseFixe::findOrFail($id);

        $validated = $request->validate([
            'type' => 'required|in:salaire,loyer,internet,mobile,srmc,femme_menage,frais_aups,autre',
            'libelle' => 'required_if:type,autre|nullable|string|max:255',
            'description' => 'nullable|string',
            'montant_mensuel' => 'required|numeric|min:0',
            'reference_contrat' => 'nullable|string|max:255',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
            'statut' => 'required|in:actif,inactif,suspendu',
            'rappel_actif' => 'boolean',
            'jour_paiement' => 'required|integer|min:1|max:31',
            'rappel_avant_jours' => 'required|integer|min:1|max:30',
            'fichier_contrat' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Upload nouveau fichier
            if ($request->hasFile('fichier_contrat')) {
                if ($depense->fichier_contrat) {
                    Storage::disk('public')->delete($depense->fichier_contrat);
                }
                
                $validated['fichier_contrat'] = $request->file('fichier_contrat')
                    ->store('contrats', 'public');
            }

            $validated['updated_by'] = auth()->id();
            $depense->update($validated);

            // Mettre à jour budget
            $this->depenseService->updateBudgetMensuel(
                $depense->date_debut->year,
                $depense->date_debut->month
            );

            DB::commit();

            return redirect()->route('depenses.fixes.show', $depense->id)
                ->with('success', 'Dépense mise à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer dépense fixe
     */
    public function destroyFixe($id)
    {
        DB::beginTransaction();
        try {
            $depense = DepenseFixe::findOrFail($id);
            
            $annee = $depense->date_debut->year;
            $mois = $depense->date_debut->month;
            
            $depense->delete();

            // Mettre à jour budget
            $this->depenseService->updateBudgetMensuel($annee, $mois);

            DB::commit();

            return redirect()->route('depenses.fixes.index')
                ->with('success', 'Dépense supprimée avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    // ========================================
    // 📊 DÉPENSES VARIABLES
    // ========================================
    
    /**
     * Liste dépenses variables
     */
    public function indexVariables(Request $request)
    {
        $query = DepenseVariable::with(['factureRecue', 'createdBy', 'updatedBy', 'valideePar']);

        // Filtres
        if ($request->filled('type')) {
            $query->parType($request->type);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        if ($request->filled('mois')) {
            $query->where('mois', $request->mois);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('libelle', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('nom_employe', 'like', "%{$search}%");
            });
        }

        // Tri
        $sortField = $request->get('sort', 'date_depense');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Stats
        $statsQuery = clone $query;
        $stats = [
            'total' => $statsQuery->sum('montant'),
            'count' => $statsQuery->count(),
            'en_attente' => (clone $statsQuery)->where('statut', 'en_attente')->count(),
            'validee' => (clone $statsQuery)->where('statut', 'validee')->count(),
            'payee' => (clone $statsQuery)->where('statut', 'payee')->count(),
'par_type' => (clone $statsQuery)
    ->reorder() // <--- Supprime le "ORDER BY date_depense" hérité de la requête principale
    ->select('type', DB::raw('COUNT(*) as count, SUM(montant) as total'))
    ->groupBy('type')
    ->get(),
        ];

        $depenses = $query->paginate(15);

        // Liste employés pour filtres
        $employees = $this->apiService->getEmployees();

        return view('depenses.variables.index', compact('depenses', 'stats', 'employees'));
    }

    /**
     * Créer dépense variable
     */
    public function createVariable()
    {
        // Récupérer les employés pour les primes
        $employees = $this->apiService->getEmployees();
        
        // Factures reçues non associées
        $factures = FactureRecue::whereDoesntHave('depenseVariable')
            ->where('statut', '!=', 'annulee')
            ->orderBy('date_facture', 'desc')
            ->get();

        return view('depenses.variables.create', compact('employees', 'factures'));
    }

    /**
     * Enregistrer dépense variable
     */
    public function storeVariable(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:facture_recue,prime,cnss,publication,transport,dgi,comptabilite,autre',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            
            // Facture
            'facture_recue_id' => 'nullable|exists:factures_recues,id',
            
            // Prime
            'user_mgmt_id' => 'nullable|integer',
            'type_prime' => 'nullable|string',
            'motif_prime' => 'nullable|string',
            
            // Publication
            'plateforme' => 'nullable|string',
            'campagne' => 'nullable|string',
            
            // Transport
            'type_transport' => 'nullable|string',
            'beneficiaire' => 'nullable|string',
            'trajet' => 'nullable|string',
            
            'fichiers_justificatifs' => 'nullable|array',
            'fichiers_justificatifs.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $date = Carbon::parse($validated['date_depense']);
            $validated['annee'] = $date->year;
            $validated['mois'] = $date->month;

            // Upload fichiers
            if ($request->hasFile('fichiers_justificatifs')) {
                $fichiers = [];
                foreach ($request->file('fichiers_justificatifs') as $file) {
                    $fichiers[] = $file->store('justificatifs', 'public');
                }
                $validated['fichiers_justificatifs'] = $fichiers;
            }

            // Si c'est une prime, récupérer infos employé
            if ($validated['type'] === 'prime' && !empty($validated['user_mgmt_id'])) {
                $employee = $this->apiService->getEmployee($validated['user_mgmt_id']);
                
                if ($employee) {
                    $validated['nom_employe'] = $employee['name'];
                    $validated['poste_employe'] = $employee['poste'];
                    $validated['montant_salaire'] = $employee['salaire'];
                }
            }

            $validated['created_by'] = auth()->id();
            $validated['statut'] = 'en_attente';

            $depense = DepenseVariable::create($validated);

            DB::commit();

            return redirect()->route('depenses.variables.index')
                ->with('success', 'Dépense variable créée avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création dépense variable: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher dépense variable
     */
    public function showVariable($id)
    {
        $depense = DepenseVariable::with([
            'factureRecue',
            'createdBy',
            'updatedBy',
            'valideePar'
        ])->findOrFail($id);

        // Si c'est une prime, récupérer infos complètes employé
        $employee = null;
        if ($depense->type === 'prime' && $depense->user_mgmt_id) {
            $employee = $this->apiService->getEmployee($depense->user_mgmt_id);
        }

        return view('depenses.variables.show', compact('depense', 'employee'));
    }

    /**
     * Éditer dépense variable
     */
    public function editVariable($id)
    {
        $depense = DepenseVariable::findOrFail($id);
        
        // Vérifier si modifiable
        if (in_array($depense->statut, ['payee', 'annulee'])) {
            return redirect()->route('depenses.variables.show', $id)
                ->with('warning', 'Cette dépense ne peut plus être modifiée.');
        }

        $employees = $this->apiService->getEmployees();
        $factures = FactureRecue::where('statut', '!=', 'annulee')
            ->orderBy('date_facture', 'desc')
            ->get();

        return view('depenses.variables.edit', compact('depense', 'employees', 'factures'));
    }

    /**
     * Mettre à jour dépense variable
     */
    public function updateVariable(Request $request, $id)
    {
        $depense = DepenseVariable::findOrFail($id);

        if (in_array($depense->statut, ['payee', 'annulee'])) {
            return back()->with('error', 'Cette dépense ne peut plus être modifiée.');
        }

        $validated = $request->validate([
            'type' => 'required|in:facture_recue,prime,cnss,publication,transport,dgi,comptabilite,autre',
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'montant' => 'required|numeric|min:0',
            'date_depense' => 'required|date',
            'statut' => 'required|in:en_attente,validee,payee,annulee',
            
            'facture_recue_id' => 'nullable|exists:factures_recues,id',
            'user_mgmt_id' => 'nullable|integer',
            'type_prime' => 'nullable|string',
            'motif_prime' => 'nullable|string',
            'plateforme' => 'nullable|string',
            'campagne' => 'nullable|string',
            'type_transport' => 'nullable|string',
            'beneficiaire' => 'nullable|string',
            'trajet' => 'nullable|string',
            
            'fichiers_justificatifs' => 'nullable|array',
            'fichiers_justificatifs.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $date = Carbon::parse($validated['date_depense']);
            $validated['annee'] = $date->year;
            $validated['mois'] = $date->month;

            // Upload nouveaux fichiers
            if ($request->hasFile('fichiers_justificatifs')) {
                $fichiers = $depense->fichiers_justificatifs ?? [];
                
                foreach ($request->file('fichiers_justificatifs') as $file) {
                    $fichiers[] = $file->store('justificatifs', 'public');
                }
                
                $validated['fichiers_justificatifs'] = $fichiers;
            }

            // Si changement de statut vers validée
            if ($validated['statut'] === 'validee' && $depense->statut !== 'validee') {
                $validated['validee_par'] = auth()->id();
                $validated['validee_le'] = now();
            }

            $validated['updated_by'] = auth()->id();
            $depense->update($validated);

            // Mettre à jour budget si validée
            if (in_array($depense->statut, ['validee', 'payee'])) {
                $this->depenseService->updateBudgetMensuel($depense->annee, $depense->mois);
            }

            DB::commit();

            return redirect()->route('depenses.variables.show', $depense->id)
                ->with('success', 'Dépense mise à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer dépense variable
     */
    public function destroyVariable($id)
    {
        DB::beginTransaction();
        try {
            $depense = DepenseVariable::findOrFail($id);
            
            if ($depense->statut === 'payee') {
                return back()->with('error', 'Une dépense payée ne peut pas être supprimée.');
            }

            $annee = $depense->annee;
            $mois = $depense->mois;
            
            $depense->delete();

            // Mettre à jour budget
            $this->depenseService->updateBudgetMensuel($annee, $mois);

            DB::commit();

            return redirect()->route('depenses.variables.index')
                ->with('success', 'Dépense supprimée avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Valider une dépense variable (AJAX)
     */
    public function validerVariable($id)
    {
        DB::beginTransaction();
        try {
            $depense = DepenseVariable::findOrFail($id);
            
            $depense->update([
                'statut' => 'validee',
                'validee_par' => auth()->id(),
                'validee_le' => now(),
            ]);

            // Mettre à jour budget
            $this->depenseService->updateBudgetMensuel($depense->annee, $depense->mois);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dépense validée avec succès!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 422);
        }
    }

    // ========================================
    // 💼 BUDGETS MENSUELS
    // ========================================
    
    /**
     * Liste budgets
     */
    public function indexBudgets(Request $request)
    {
        $query = BudgetMensuel::with(['createdBy', 'updatedBy']);

        if ($request->filled('annee')) {
            $query->where('annee', $request->annee);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $query->orderBy('annee', 'desc')->orderBy('mois', 'desc');

        $budgets = $query->paginate(12);

        // Stats globales
        $stats = [
            'budget_total_annee' => BudgetMensuel::where('annee', now()->year)->sum('budget_total'),
            'depense_total_annee' => BudgetMensuel::where('annee', now()->year)->sum('depense_totale_realisee'),
            'budgets_depasses' => BudgetMensuel::whereRaw('depense_totale_realisee > budget_total')->count(),
        ];

        return view('depenses.budgets.index', compact('budgets', 'stats'));
    }

    /**
     * Créer budget
     */
    public function createBudget()
    {
        // Suggérer budget basé sur moyenne
        $moyenneFixes = DepenseFixe::actif()->avg('montant_mensuel') * DepenseFixe::actif()->count();
        $moyenneVariables = DepenseVariable::where('annee', now()->year)
            ->validee()
            ->avg(DB::raw('montant'));

        $suggestion = [
            'fixes' => round($moyenneFixes, 2),
            'variables' => round($moyenneVariables * 30, 2), // Estimation mensuelle
        ];

        return view('depenses.budgets.create', compact('suggestion'));
    }

    /**
     * Enregistrer budget
     */
    public function storeBudget(Request $request)
    {
        $validated = $request->validate([
            'annee' => 'required|integer|min:2020|max:2099',
            'mois' => 'required|integer|min:1|max:12',
            'budget_fixes' => 'required|numeric|min:0',
            'budget_variables' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            // Vérifier unicité
            $existe = BudgetMensuel::where('annee', $validated['annee'])
                ->where('mois', $validated['mois'])
                ->exists();

            if ($existe) {
                return back()->withInput()
                    ->with('error', 'Un budget existe déjà pour ce mois.');
            }

            $validated['created_by'] = auth()->id();
            $validated['statut'] = 'previsionnel';

            $budget = BudgetMensuel::create($validated);

            // Calculer dépenses réelles si c'est un mois passé
            if ($validated['annee'] < now()->year || 
                ($validated['annee'] == now()->year && $validated['mois'] <= now()->month)) {
                $budget->recalculerDepenses();
            }

            return redirect()->route('depenses.budgets.index')
                ->with('success', 'Budget créé avec succès!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher budget
     */
    public function showBudget($id)
    {
        $budget = BudgetMensuel::with(['createdBy', 'updatedBy'])->findOrFail($id);

        // Détails dépenses du mois
        $depensesFixes = DepenseFixe::pourMois($budget->annee, $budget->mois)->get();
        $depensesVariables = DepenseVariable::pourMois($budget->annee, $budget->mois)->validee()->get();

        // Comparaison avec mois précédent
        $moisPrecedent = Carbon::create($budget->annee, $budget->mois, 1)->subMonth();
        $budgetPrecedent = BudgetMensuel::pourMois($moisPrecedent->year, $moisPrecedent->month)->first();

        return view('depenses.budgets.show', compact('budget', 'depensesFixes', 'depensesVariables', 'budgetPrecedent'));
    }

    /**
     * Éditer budget
     */
    public function editBudget($id)
    {
        $budget = BudgetMensuel::findOrFail($id);
        
        if ($budget->statut === 'cloture') {
            return redirect()->route('depenses.budgets.show', $id)
                ->with('warning', 'Un budget clôturé ne peut plus être modifié.');
        }

        return view('depenses.budgets.edit', compact('budget'));
    }

    /**
     * Mettre à jour budget
     */
    public function updateBudget(Request $request, $id)
    {
        $budget = BudgetMensuel::findOrFail($id);

        if ($budget->statut === 'cloture') {
            return back()->with('error', 'Un budget clôturé ne peut plus être modifié.');
        }

        $validated = $request->validate([
            'budget_fixes' => 'required|numeric|min:0',
            'budget_variables' => 'required|numeric|min:0',
            'statut' => 'required|in:previsionnel,en_cours,cloture',
            'notes' => 'nullable|string',
]);
try {
        $validated['updated_by'] = auth()->id();
        $budget->update($validated);

        // Recalculer dépenses
        $budget->recalculerDepenses();

        return redirect()->route('depenses.budgets.show', $budget->id)
            ->with('success', 'Budget mis à jour avec succès!');

    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', 'Erreur: ' . $e->getMessage());
    }
}

/**
 * Clôturer budget
 */
public function cloturerBudget($id)
{
    try {
        $budget = BudgetMensuel::findOrFail($id);
        
        $budget->update([
            'statut' => 'cloture',
            'updated_by' => auth()->id(),
        ]);

        // Recalcul final
        $budget->recalculerDepenses();

        return redirect()->route('depenses.budgets.show', $id)
            ->with('success', 'Budget clôturé avec succès!');

    } catch (\Exception $e) {
        return back()->with('error', 'Erreur: ' . $e->getMessage());
    }
}

// ========================================
// 📥 IMPORT SALAIRES
// ========================================

/**
 * Importer salaires depuis API
 */
public function importerSalaires(Request $request)
{
    $validated = $request->validate([
        'annee' => 'required|integer|min:2020|max:2099',
        'mois' => 'required|integer|min:1|max:12',
    ]);

    $result = $this->depenseService->importerSalairesMensuel(
        $validated['annee'],
        $validated['mois']
    );

    if ($result['success']) {
        return redirect()->route('depenses.fixes.index')
            ->with('success', $result['message'] . ' | Total: ' . number_format($result['data']['total_salaires'], 2) . ' DH + CNSS: ' . number_format($result['data']['montant_cnss'], 2) . ' DH');
    }

    return back()->with('error', $result['message']);
}

/**
 * Historique imports salaires
 */
public function historiqueSalaires()
{
    $historiques = HistoriqueSalaireApi::with('importePar')
        ->orderBy('annee', 'desc')
        ->orderBy('mois', 'desc')
        ->paginate(12);

    return view('depenses.salaires.historique', compact('historiques'));
}

/**
 * Détails import salaire
 */
public function showHistoriqueSalaire($id)
{
    $historique = HistoriqueSalaireApi::with('importePar')->findOrFail($id);
    
    return view('depenses.salaires.show', compact('historique'));
}

// ========================================
// 🔧 UTILITAIRES
// ========================================

/**
 * Test connexion API
 */
public function testApiConnection()
{
    $isConnected = $this->apiService->testConnection();

    return response()->json([
        'success' => $isConnected,
        'message' => $isConnected 
            ? 'Connexion réussie avec uits-mgmt.ma' 
            : 'Impossible de se connecter à uits-mgmt.ma',
        'url' => config('services.uits_mgmt.api_url'),
    ]);
}

/**
 * Récupérer employés (AJAX)
 */
public function getEmployees()
{
    $employees = $this->apiService->getEmployees();

    return response()->json([
        'success' => true,
        'employees' => $employees
    ]);
}

/**
 * Récupérer détails employé (AJAX)
 */
public function getEmployee($id)
{
    $employee = $this->apiService->getEmployee($id);

    if ($employee) {
        return response()->json([
            'success' => true,
            'employee' => $employee
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Employé introuvable'
    ], 404);
}

/**
 * Statistiques globales (AJAX)
 */
public function getStats(Request $request)
{
    $annee = $request->get('annee', now()->year);
    $mois = $request->get('mois', now()->month);

    $totaux = $this->depenseService->calculerTotauxMois($annee, $mois);
    $budget = BudgetMensuel::pourMois($annee, $mois)->first();

    return response()->json([
        'success' => true,
        'totaux' => $totaux,
        'budget' => $budget,
    ]);
}

/**
 * Export Excel/CSV
 */
public function export(Request $request)
{
    $type = $request->get('type', 'fixes'); // fixes, variables, budgets
    $format = $request->get('format', 'csv'); // csv, excel, pdf

    // TODO: Implémenter export
    return back()->with('info', 'Export en cours de développement...');
}

// ========================================
// 🔔 RAPPELS
// ========================================

/**
 * Rappels du jour
 */
private function getRappelsDuJour()
{
    $today = now()->format('Y-m-d');
    
    $rappelsFixes = DepenseFixe::actif()
        ->where('rappel_actif', true)
        ->whereDay('date_debut', now()->day)
        ->get();

    return $rappelsFixes->count();
}
}
