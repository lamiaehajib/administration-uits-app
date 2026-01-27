<?php

namespace App\Http\Controllers;

use App\Models\FactureRecue;
use App\Models\Consultant;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class FactureRecueController extends Controller
{
    /**
     * Générer automatiquement un numéro de facture
     * Format: CY{ID}{JJ}{MM}{AA} pour Consultant
     * Format: FY{ID}{JJ}{MM}{AA} pour Fournisseur
     */
    private function genererNumeroFacture($type, $fournisseurId, $date = null)
    {
        // Si pas de date, utiliser aujourd'hui
        $date = $date ? \Carbon\Carbon::parse($date) : now();
        
        // Déterminer le préfixe selon le type
        if ($type === 'consultant') {
            $consultant = Consultant::findOrFail($fournisseurId);
            // Première lettre du nom (en majuscule)
            $premiereLettre = strtoupper(substr($consultant->nom, 0, 1));
            $prefix = 'C' . $premiereLettre;
        } else {
            $fournisseur = Fournisseur::findOrFail($fournisseurId);
            // Première lettre du nom entreprise (en majuscule)
            $premiereLettre = strtoupper(substr($fournisseur->nom_entreprise, 0, 1));
            $prefix = 'F' . $premiereLettre;
        }
        
        // Obtenir le prochain ID de facture
        $derniereFacutre = FactureRecue::latest('id')->first();
        $nextId = $derniereFacutre ? ($derniereFacutre->id + 1) : 1;
        
        // Formater la date: JJ MM AA
        $jour = $date->format('d');
        $mois = $date->format('m');
        $annee = $date->format('y'); // 2 derniers chiffres de l'année
        
        // Construire le numéro: CY3100126 ou FY3100126
        $numeroFacture = $prefix . $nextId . $jour . $mois . $annee;
        
        return $numeroFacture;
    }

    /**
     * API pour générer le numéro de facture (AJAX)
     */
    public function generateNumeroFacture(Request $request)
    {
        try {
            $validated = $request->validate([
                'type_fournisseur' => 'required|in:consultant,fournisseur',
                'fournisseur_id' => 'required|integer',
                'date_facture' => 'required|date',
            ]);

            $numeroFacture = $this->genererNumeroFacture(
                $validated['type_fournisseur'],
                $validated['fournisseur_id'],
                $validated['date_facture']
            );

            return response()->json([
                'success' => true,
                'numero_facture' => $numeroFacture
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Afficher la page de création
     */
    public function create()
    {
        $consultants = Consultant::actif()->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('nom_entreprise')->get();
        
        return view('factures-recues.create', compact('consultants', 'fournisseurs'));
    }

    /**
     * Créer nouveau Consultant via AJAX
     */
    public function storeConsultant(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:consultants,email',
                'telephone' => 'nullable|string|max:20',
                'specialite' => 'nullable|string|max:255',
                'cin' => 'nullable|string|max:20',
                'tarif_heure' => 'nullable|numeric|min:0',
            ]);

            $consultant = Consultant::create($validated);

            return response()->json([
                'success' => true,
                'consultant' => $consultant,
                'message' => 'Consultant créé avec succès!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Créer nouveau Fournisseur via AJAX
     */
    public function storeFournisseur(Request $request)
    {
        try {
            $validated = $request->validate([
                'nom_entreprise' => 'required|string|max:255',
                'contact_nom' => 'nullable|string|max:255',
                'email' => 'nullable|email',
                'telephone' => 'nullable|string|max:20',
                'ice' => 'nullable|string|max:20',
                'if' => 'nullable|string|max:20',
                'type_materiel' => 'nullable|string|max:255',
            ]);

            $fournisseur = Fournisseur::create($validated);

            return response()->json([
                'success' => true,
                'fournisseur' => $fournisseur,
                'message' => 'Fournisseur créé avec succès!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Enregistrer la facture reçue
     */
   public function store(Request $request)
{
    // 1. VALIDATION AMÉLIORÉE
    $validated = $request->validate([
        'type_fournisseur' => 'required|in:consultant,fournisseur',
        'fournisseur_id' => 'required|integer',
        'numero_facture' => 'nullable|string|unique:factures_recues,numero_facture',
        'date_facture' => 'required|date',
        'date_echeance' => 'nullable|date|after_or_equal:date_facture',
        'montant_ttc' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'fichier_pdf' => 'nullable|file|mimes:pdf|max:5120',
    ]);

    DB::beginTransaction();
    try {
        // 2. DÉTERMINER LE TYPE DE FOURNISSEUR (NAMESPACE COMPLET)
        $fournisseurType = $validated['type_fournisseur'] === 'consultant' 
            ? 'App\Models\Consultant'
            : 'App\Models\Fournisseur';

        // 3. VÉRIFIER QUE LE FOURNISSEUR EXISTE
        $fournisseurModel = $fournisseurType === 'App\Models\Consultant' 
            ? Consultant::class 
            : Fournisseur::class;
        
        $fournisseur = $fournisseurModel::find($validated['fournisseur_id']);
        
        if (!$fournisseur) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Fournisseur introuvable. Veuillez actualiser la page.');
        }

        // 4. GÉNÉRER LE NUMÉRO SI ABSENT
        if (empty($validated['numero_facture'])) {
            $validated['numero_facture'] = $this->genererNumeroFacture(
                $validated['type_fournisseur'],
                $validated['fournisseur_id'],
                $validated['date_facture']
            );
        }

        // 5. GÉRER LE FICHIER PDF
        $fichierPath = null;
        if ($request->hasFile('fichier_pdf')) {
            try {
                $fichierPath = $request->file('fichier_pdf')->store('factures-recues', 'public');
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur upload PDF: ' . $e->getMessage());
                return back()->withInput()
                    ->with('error', 'Erreur lors du téléchargement du PDF: ' . $e->getMessage());
            }
        }

        // 6. CRÉER LA FACTURE
        $facture = FactureRecue::create([
            'numero_facture' => $validated['numero_facture'],
            'date_facture' => $validated['date_facture'],
            'date_echeance' => $validated['date_echeance'],
            'fournisseur_type' => $fournisseurType,
            'fournisseur_id' => $validated['fournisseur_id'],
            'montant_ttc' => $validated['montant_ttc'],
            'description' => $validated['description'],
            'fichier_pdf' => $fichierPath,
            'statut' => 'en_attente',
            'created_by' => auth()->id(),
        ]);

        DB::commit();

        // 7. LOG SUCCESS
        Log::info('Facture créée avec succès', [
            'id' => $facture->id,
            'numero' => $facture->numero_facture,
            'user' => auth()->id()
        ]);

        return redirect()->route('factures-recues.index')
            ->with('success', 'Facture reçue créée avec succès! Numéro: ' . $facture->numero_facture);

    } catch (\Illuminate\Database\QueryException $e) {
        DB::rollBack();
        
        // Supprimer le fichier si uploadé
        if ($fichierPath && Storage::disk('public')->exists($fichierPath)) {
            Storage::disk('public')->delete($fichierPath);
        }

        // Erreur de contrainte d'unicité
        if ($e->getCode() == 23000) {
            Log::error('Erreur unicité numéro facture: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Ce numéro de facture existe déjà. Veuillez réessayer.');
        }

        Log::error('Erreur DB création facture: ' . $e->getMessage());
        return back()->withInput()
            ->with('error', 'Erreur de base de données: ' . $e->getMessage());

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Supprimer le fichier si uploadé
        if (isset($fichierPath) && $fichierPath && Storage::disk('public')->exists($fichierPath)) {
            Storage::disk('public')->delete($fichierPath);
        }

        Log::error('Erreur création facture: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return back()->withInput()
            ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
    }
}

    /**
     * Liste des factures reçues avec fonctionnalités avancées
     */
    public function index(Request $request)
    {
        $query = FactureRecue::with(['fournisseur', 'createdBy', 'updatedBy']);

        // Filtre par type de fournisseur
        if ($request->filled('type')) {
            $type = $request->type === 'consultant' ? Consultant::class : Fournisseur::class;
            $query->where('fournisseur_type', $type);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par fournisseur spécifique
        if ($request->filled('fournisseur_id') && $request->filled('fournisseur_type')) {
            $type = $request->fournisseur_type === 'consultant' ? Consultant::class : Fournisseur::class;
            $query->where('fournisseur_type', $type)
                  ->where('fournisseur_id', $request->fournisseur_id);
        }

        // Recherche multi-champs
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par plage de dates
        if ($request->filled('date_debut')) {
            $query->where('date_facture', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->where('date_facture', '<=', $request->date_fin);
        }

        // Filtre par plage de montants
        if ($request->filled('montant_min')) {
            $query->where('montant_ttc', '>=', $request->montant_min);
        }
        if ($request->filled('montant_max')) {
            $query->where('montant_ttc', '<=', $request->montant_max);
        }

        // Filtre factures échues
        if ($request->filled('echues') && $request->echues == '1') {
            $query->where('statut', '!=', 'payee')
                  ->where('date_echeance', '<', now());
        }

        // Filtre factures à échéance proche (7 jours)
        if ($request->filled('echeance_proche') && $request->echeance_proche == '1') {
            $query->where('statut', '!=', 'payee')
                  ->whereBetween('date_echeance', [now(), now()->addDays(7)]);
        }

        // Tri dynamique
        $sortField = $request->get('sort', 'date_facture');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSorts = ['date_facture', 'date_echeance', 'montant_ttc', 'numero_facture', 'statut', 'created_at'];
        
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection);
        } else {
            $query->orderBy('date_facture', 'desc');
        }

        // Statistiques
        $statsQuery = clone $query;
        
        $stats = [
            'total_factures' => $statsQuery->count(),
            'montant_total' => $statsQuery->sum('montant_ttc'),
            
            'en_attente' => [
                'count' => (clone $statsQuery)->where('statut', 'en_attente')->count(),
                'montant' => (clone $statsQuery)->where('statut', 'en_attente')->sum('montant_ttc'),
            ],
            'payee' => [
                'count' => (clone $statsQuery)->where('statut', 'payee')->count(),
                'montant' => (clone $statsQuery)->where('statut', 'payee')->sum('montant_ttc'),
            ],
            'annulee' => [
                'count' => (clone $statsQuery)->where('statut', 'annulee')->count(),
                'montant' => (clone $statsQuery)->where('statut', 'annulee')->sum('montant_ttc'),
            ],
            
            'echues' => [
                'count' => (clone $statsQuery)
                    ->where('statut', '!=', 'payee')
                    ->where('date_echeance', '<', now())
                    ->count(),
                'montant' => (clone $statsQuery)
                    ->where('statut', '!=', 'payee')
                    ->where('date_echeance', '<', now())
                    ->sum('montant_ttc'),
            ],
            
            'echeance_proche' => [
                'count' => (clone $statsQuery)
                    ->where('statut', '!=', 'payee')
                    ->whereBetween('date_echeance', [now(), now()->addDays(7)])
                    ->count(),
                'montant' => (clone $statsQuery)
                    ->where('statut', '!=', 'payee')
                    ->whereBetween('date_echeance', [now(), now()->addDays(7)])
                    ->sum('montant_ttc'),
            ],
            
            'consultants' => [
                'count' => (clone $statsQuery)->where('fournisseur_type', Consultant::class)->count(),
                'montant' => (clone $statsQuery)->where('fournisseur_type', Consultant::class)->sum('montant_ttc'),
            ],
            'fournisseurs' => [
                'count' => (clone $statsQuery)->where('fournisseur_type', Fournisseur::class)->count(),
                'montant' => (clone $statsQuery)->where('fournisseur_type', Fournisseur::class)->sum('montant_ttc'),
            ],
        ];

        // Top fournisseurs
        $topFournisseurs = FactureRecue::select(
                'fournisseur_type',
                'fournisseur_id',
                DB::raw('COUNT(*) as total_factures'),
                DB::raw('SUM(montant_ttc) as total_montant')
            )
            ->groupBy('fournisseur_type', 'fournisseur_id')
            ->orderBy('total_montant', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                $fournisseur = $item->fournisseur_type::find($item->fournisseur_id);
                
                return [
                    'nom' => $fournisseur ? 
                        ($item->fournisseur_type === Consultant::class ? 
                            $fournisseur->nom_complet : 
                            $fournisseur->nom_entreprise) 
                        : 'N/A',
                    'type' => $item->fournisseur_type === Consultant::class ? 'Consultant' : 'Fournisseur',
                    'total_factures' => $item->total_factures,
                    'total_montant' => $item->total_montant,
                ];
            });

        // Évolution mensuelle
        $evolutionMensuelle = FactureRecue::select(
                DB::raw('DATE_FORMAT(date_facture, "%Y-%m") as mois'),
                DB::raw('COUNT(*) as nombre'),
                DB::raw('SUM(montant_ttc) as montant')
            )
            ->where('date_facture', '>=', now()->subMonths(12))
            ->groupBy('mois')
            ->orderBy('mois', 'asc')
            ->get();

        // Pagination
        $perPage = $request->get('per_page', 15);
        $factures = $query->paginate($perPage)->appends($request->except('page'));

        // Données pour les filtres
        $consultants = Consultant::actif()->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('nom_entreprise')->get();

        // Export
        if ($request->has('export')) {
            return $this->exportFactures($query->get(), $request->export);
        }

        return view('factures-recues.index', compact(
            'factures',
            'stats',
            'topFournisseurs',
            'evolutionMensuelle',
            'consultants',
            'fournisseurs'
        ));
    }

    /**
     * 📄 Afficher les détails d'une facture
     */
    public function show($id)
    {
        $facture = FactureRecue::with(['fournisseur', 'createdBy', 'updatedBy'])
            ->findOrFail($id);

        // Historique des modifications (si tu as un système d'audit)
        $historique = [];

        // Factures similaires du même fournisseur
        $facturesSimilaires = FactureRecue::where('fournisseur_type', $facture->fournisseur_type)
            ->where('fournisseur_id', $facture->fournisseur_id)
            ->where('id', '!=', $facture->id)
            ->orderBy('date_facture', 'desc')
            ->limit(5)
            ->get();

        // Statistiques du fournisseur
        $statsFournisseur = [
            'total_factures' => FactureRecue::where('fournisseur_type', $facture->fournisseur_type)
                ->where('fournisseur_id', $facture->fournisseur_id)
                ->count(),
            'total_montant' => FactureRecue::where('fournisseur_type', $facture->fournisseur_type)
                ->where('fournisseur_id', $facture->fournisseur_id)
                ->sum('montant_ttc'),
            'factures_payees' => FactureRecue::where('fournisseur_type', $facture->fournisseur_type)
                ->where('fournisseur_id', $facture->fournisseur_id)
                ->where('statut', 'payee')
                ->count(),
        ];

        return view('factures-recues.show', compact(
            'facture',
            'historique',
            'facturesSimilaires',
            'statsFournisseur'
        ));
    }

    /**
     * ✏️ Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $facture = FactureRecue::with('fournisseur')->findOrFail($id);
        
        // Vérifier si la facture peut être modifiée
        if ($facture->statut === 'payee') {
            return redirect()->route('factures-recues.show', $facture->id)
                ->with('warning', 'Une facture payée ne peut pas être modifiée. Vous pouvez la dupliquer si nécessaire.');
        }

        $consultants = Consultant::actif()->orderBy('nom')->get();
        $fournisseurs = Fournisseur::actif()->orderBy('nom_entreprise')->get();

        return view('factures-recues.edit', compact('facture', 'consultants', 'fournisseurs'));
    }

    /**
     * 💾 Mettre à jour une facture
     */
   public function update(Request $request, $id)
{
    $facture = FactureRecue::findOrFail($id);

    // Vérifier si la facture peut être modifiée
    if ($facture->statut === 'payee') {
        return back()->with('error', 'Une facture payée ne peut pas être modifiée.');
    }

    // VALIDATION - SANS LE NUMÉRO DE FACTURE
    $validated = $request->validate([
        'type_fournisseur' => 'required|in:consultant,fournisseur',
        'fournisseur_id' => 'required|integer',
        // ❌ SUPPRIMÉ: 'numero_facture' => 'required|string|unique:factures_recues,numero_facture,' . $id,
        'date_facture' => 'required|date',
        'date_echeance' => 'nullable|date|after_or_equal:date_facture',
        'montant_ttc' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'statut' => 'required|in:en_attente,payee,annulee',
        'fichier_pdf' => 'nullable|file|mimes:pdf|max:5120',
    ]);

    DB::beginTransaction();
    try {
        $fournisseurType = $validated['type_fournisseur'] === 'consultant' 
            ? 'App\Models\Consultant'
            : 'App\Models\Fournisseur';

        $fournisseurModel = $fournisseurType === 'App\Models\Consultant' 
            ? Consultant::class 
            : Fournisseur::class;
        
        $fournisseur = $fournisseurModel::find($validated['fournisseur_id']);
        
        if (!$fournisseur) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Fournisseur introuvable. Veuillez sélectionner un fournisseur valide.');
        }

        // Gérer le fichier PDF
        $fichierPath = $facture->fichier_pdf;
        
        if ($request->hasFile('fichier_pdf')) {
            try {
                if ($fichierPath && Storage::disk('public')->exists($fichierPath)) {
                    Storage::disk('public')->delete($fichierPath);
                }
                
                $fichierPath = $request->file('fichier_pdf')->store('factures-recues', 'public');
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Erreur upload PDF lors de la mise à jour: ' . $e->getMessage());
                
                return back()->withInput()
                    ->with('error', 'Erreur lors du téléchargement du PDF: ' . $e->getMessage());
            }
        }

        // ✅ MISE À JOUR - SANS MODIFIER LE NUMÉRO
        $facture->update([
            // ❌ SUPPRIMÉ: 'numero_facture' => $validated['numero_facture'],
            'date_facture' => $validated['date_facture'],
            'date_echeance' => $validated['date_echeance'],
            'fournisseur_type' => $fournisseurType,
            'fournisseur_id' => $validated['fournisseur_id'],
            'montant_ttc' => $validated['montant_ttc'],
            'description' => $validated['description'],
            'statut' => $validated['statut'],
            'fichier_pdf' => $fichierPath,
            'updated_by' => auth()->id(),
        ]);

        DB::commit();

        Log::info('Facture mise à jour avec succès', [
            'id' => $facture->id,
            'numero' => $facture->numero_facture,
            'user' => auth()->id()
        ]);

        return redirect()->route('factures-recues.show', $facture->id)
            ->with('success', 'Facture mise à jour avec succès!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('Erreur mise à jour facture: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString()
        ]);

        return back()->withInput()
            ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
}
    /**
     * ⚡ Édition rapide (AJAX) - Changement de statut uniquement
     */
    public function quickEdit(Request $request, $id)
    {
        try {
            $facture = FactureRecue::findOrFail($id);

            $validated = $request->validate([
                'statut' => 'required|in:en_attente,payee,annulee',
                'date_echeance' => 'nullable|date',
            ]);

            $facture->update([
                'statut' => $validated['statut'],
                'date_echeance' => $validated['date_echeance'] ?? $facture->date_echeance,
                'updated_by' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Statut mis à jour avec succès!',
                'facture' => $facture->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * 📋 Dupliquer une facture
     */
    public function duplicate($id)
    {
        $factureOriginal = FactureRecue::findOrFail($id);

        DB::beginTransaction();
        try {
            // Générer un nouveau numéro de facture automatique
            $type = $factureOriginal->fournisseur_type === Consultant::class ? 'consultant' : 'fournisseur';
            $nouveauNumero = $this->genererNumeroFacture($type, $factureOriginal->fournisseur_id);

            // Copier le fichier PDF si existe
            $nouveauFichier = null;
            if ($factureOriginal->fichier_pdf) {
                $extension = pathinfo($factureOriginal->fichier_pdf, PATHINFO_EXTENSION);
                $nouveauNom = 'factures-recues/' . uniqid() . '_copy.' . $extension;
                
                if (Storage::disk('public')->exists($factureOriginal->fichier_pdf)) {
                    Storage::disk('public')->copy(
                        $factureOriginal->fichier_pdf,
                        $nouveauNom
                    );
                    $nouveauFichier = $nouveauNom;
                }
            }

            // Créer la nouvelle facture
            $nouvelleFacture = FactureRecue::create([
                'numero_facture' => $nouveauNumero,
                'date_facture' => now(),
                'date_echeance' => now()->addDays(30),
                'fournisseur_type' => $factureOriginal->fournisseur_type,
                'fournisseur_id' => $factureOriginal->fournisseur_id,
                'montant_ttc' => $factureOriginal->montant_ttc,
                'description' => $factureOriginal->description . ' (Copie)',
                'fichier_pdf' => $nouveauFichier,
                'statut' => 'en_attente',
                'created_by' => auth()->id(),
            ]);

            DB::commit();

            return redirect()->route('factures-recues.edit', $nouvelleFacture->id)
                ->with('success', 'Facture dupliquée avec succès! Numéro généré: ' . $nouveauNumero);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Erreur lors de la duplication: ' . $e->getMessage());
        }
    }

    /**
     * 🗑️ Supprimer une facture (Soft Delete)
     */
    public function destroy($id)
    {
        try {
            $facture = FactureRecue::findOrFail($id);

            // Vérifier si la facture peut être supprimée
            if ($facture->statut === 'payee') {
                return back()->with('error', 'Une facture payée ne peut pas être supprimée.');
            }

            // Soft delete
            $facture->delete();

            return redirect()->route('factures-recues.index')
                ->with('success', 'Facture supprimée avec succès!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }

    /**
     * 🗑️ Suppression permanente (Force Delete)
     */
    public function forceDestroy($id)
    {
        try {
            $facture = FactureRecue::withTrashed()->findOrFail($id);

            // Supprimer le fichier PDF
            if ($facture->fichier_pdf) {
                Storage::disk('public')->delete($facture->fichier_pdf);
            }

            // Suppression définitive
            $facture->forceDelete();

            return redirect()->route('factures-recues.index')
                ->with('success', 'Facture supprimée définitivement!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * 🔄 Restaurer une facture supprimée
     */
    public function restore($id)
    {
        try {
            $facture = FactureRecue::withTrashed()->findOrFail($id);
            $facture->restore();

            return redirect()->route('factures-recues.index')
                ->with('success', 'Facture restaurée avec succès!');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * 📊 API pour obtenir les statistiques en temps réel
     */
    public function getStats(Request $request)
    {
        $query = FactureRecue::query();
        
        if ($request->filled('type')) {
            $type = $request->type === 'consultant' ? Consultant::class : Fournisseur::class;
            $query->where('fournisseur_type', $type);
        }
        
        if ($request->filled('date_debut')) {
            $query->where('date_facture', '>=', $request->date_debut);
        }
        
        if ($request->filled('date_fin')) {
            $query->where('date_facture', '<=', $request->date_fin);
        }

        $stats = [
            'total' => $query->sum('montant_ttc'),
            'count' => $query->count(),
            'moyenne' => $query->avg('montant_ttc'),
            'en_attente' => (clone $query)->where('statut', 'en_attente')->sum('montant_ttc'),
            'payee' => (clone $query)->where('statut', 'payee')->sum('montant_ttc'),
        ];

        return response()->json($stats);
    }

    /**
     * Obtenir les détails d'un consultant
     */
    public function getConsultant($id)
    {
        $consultant = Consultant::findOrFail($id);
        return response()->json($consultant);
    }

    /**
     * Obtenir les détails d'un fournisseur
     */
    public function getFournisseur($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return response()->json($fournisseur);
    }


    /**
     * 📥 Export des factures
     */
    private function exportFactures($factures, $format = 'csv')
    {
        $filename = 'factures_recues_' . now()->format('Y-m-d_His');
        
        if ($format === 'csv') {
            return $this->exportCSV($factures, $filename);
        }
        
        // Pour Excel et PDF, tu peux ajouter les libraries nécessaires
        return back()->with('info', 'Format d\'export non disponible pour le moment.');
    }

    /**
     * 📄 Export CSV
     */
    private function exportCSV($factures, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($factures) {
            $file = fopen('php://output', 'w');
            
            // BOM UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'Numéro',
                'Date Facture',
                'Date Échéance',
                'Fournisseur',
                'Type',
                'Montant TTC',
                'Statut',
                'Description',
                'Créé le',
                'Créé par'
            ]);

            // Données
            foreach ($factures as $facture) {
                fputcsv($file, [
                    $facture->numero_facture,
                    $facture->date_facture->format('d/m/Y'),
                    $facture->date_echeance?->format('d/m/Y') ?? 'N/A',
                    $facture->nom_fournisseur,
                    $facture->fournisseur_type === Consultant::class ? 'Consultant' : 'Fournisseur',
                    number_format($facture->montant_ttc, 2, ',', ' ') . ' DH',
                    ucfirst($facture->statut),
                    $facture->description ?? '',
                    $facture->created_at->format('d/m/Y H:i'),
                    $facture->createdBy->name ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * 🔢 Générer un nouveau numéro de facture pour duplication
     */
    private function genererNouveauNumero($ancienNumero)
    {
        // Extraire le numéro et ajouter un suffixe
        $parts = explode('-', $ancienNumero);
        $dernierPart = end($parts);
        
        if (is_numeric($dernierPart)) {
            $parts[count($parts) - 1] = str_pad((int)$dernierPart + 1, strlen($dernierPart), '0', STR_PAD_LEFT);
            return implode('-', $parts);
        }
        
        // Si pas de numéro détecté, ajouter un timestamp
        return $ancienNumero . '-' . now()->format('Ymd');
    }

    /**
     * 📋 Afficher les factures supprimées (Corbeille)
     */
    public function trash(Request $request)
    {
        $query = FactureRecue::onlyTrashed()
            ->with(['fournisseur', 'createdBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_facture', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $factures = $query->orderBy('deleted_at', 'desc')->paginate(15);

        return view('factures-recues.trash', compact('factures'));
    }

    /**
     * 📥 Télécharger le fichier PDF
     */
    public function downloadPDF($id)
    {
        $facture = FactureRecue::findOrFail($id);

        if (!$facture->fichier_pdf || !Storage::disk('public')->exists($facture->fichier_pdf)) {
            return back()->with('error', 'Fichier PDF introuvable.');
        }

        return Storage::disk('public')->download(
            $facture->fichier_pdf,
            'Facture_' . $facture->numero_facture . '.pdf'
        );
    }

    /**
     * 🔄 Changer le statut en masse (AJAX)
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:factures_recues,id',
                'statut' => 'required|in:en_attente,payee,annulee',
            ]);

            $updated = FactureRecue::whereIn('id', $validated['ids'])
                ->update([
                    'statut' => $validated['statut'],
                    'updated_by' => auth()->id(),
                ]);

            return response()->json([
                'success' => true,
                'message' => "{$updated} facture(s) mise(s) à jour avec succès!",
                'count' => $updated
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * 🗑️ Suppression en masse
     */
    public function bulkDelete(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'exists:factures_recues,id',
            ]);

            $deleted = FactureRecue::whereIn('id', $validated['ids'])
                ->where('statut', '!=', 'payee')
                ->delete();

            return response()->json([
                'success' => true,
                'message' => "{$deleted} facture(s) supprimée(s) avec succès!",
                'count' => $deleted
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ], 422);
        }
    }


    public function updateConsultant(Request $request, $id)
{
    try {
        $consultant = Consultant::findOrFail($id);
        
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:consultants,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:255',
            'cin' => 'nullable|string|max:20',
            'tarif_heure' => 'nullable|numeric|min:0',
        ]);

        $consultant->update($validated);

        return response()->json([
            'success' => true,
            'consultant' => $consultant,
            'message' => 'Consultant mis à jour avec succès!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 422);
    }
}

/**
 * Mettre à jour un fournisseur via AJAX
 */
public function updateFournisseur(Request $request, $id)
{
    try {
        $fournisseur = Fournisseur::findOrFail($id);
        
        $validated = $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'contact_nom' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:fournisseurs,email,' . $id,
            'telephone' => 'nullable|string|max:20',
            'ice' => 'nullable|string|max:20',
            'if' => 'nullable|string|max:20',
            'type_materiel' => 'nullable|string|max:255',
        ]);

        $fournisseur->update($validated);

        return response()->json([
            'success' => true,
            'fournisseur' => $fournisseur,
            'message' => 'Fournisseur mis à jour avec succès!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur: ' . $e->getMessage()
        ], 422);
    }
}
}


