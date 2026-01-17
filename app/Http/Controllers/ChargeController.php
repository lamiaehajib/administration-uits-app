<?php

namespace App\Http\Controllers;

use App\Models\Charge;
use App\Models\ChargeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ChargeController extends Controller
{
    // ================================= PAGE PRINCIPALE ==============================
    
    /**
     * Afficher la page principale avec charges et catégories
     */
    public function index(Request $request)
    {
        // Récupérer les filtres
        $search = $request->input('search');
        $type = $request->input('type'); // fixe ou variable
        $categoryId = $request->input('category_id');
        $statut = $request->input('statut'); // paye, impaye, partiel
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');
        
        // Par défaut: mois en cours
        if (!$dateDebut) {
            $dateDebut = now()->startOfMonth()->format('Y-m-d');
        }
        if (!$dateFin) {
            $dateFin = now()->endOfMonth()->format('Y-m-d');
        }

        // Query des charges avec filtres
        $charges = Charge::with(['category', 'user'])
            ->when($search, function($q) use ($search) {
                $q->search($search);
            })
            ->when($type, function($q) use ($type) {
                $q->where('type', $type);
            })
            ->when($categoryId, function($q) use ($categoryId) {
                $q->where('charge_category_id', $categoryId);
            })
            ->when($statut, function($q) use ($statut) {
                $q->where('statut_paiement', $statut);
            })
            ->when($dateDebut && $dateFin, function($q) use ($dateDebut, $dateFin) {
                $q->entreDates($dateDebut, $dateFin);
            })
            ->orderBy('date_charge', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Récupérer toutes les catégories actives

       $categories = ChargeCategory::actif()->orderBy('nom')->get();
    
    // DEBUG - À supprimer après
    \Log::info('Nombre de catégories : ' . $categories->count());
    
    // Statistiques
    $stats = $this->getStatistiques($dateDebut, $dateFin);

        return view('charges.index', compact(
            'charges',
            'categories',
            'stats',
            'search',
            'type',
            'categoryId',
            'statut',
            'dateDebut',
            'dateFin'
        ));
    }

    // ================================= CHARGES - CRUD ==============================
    
    /**
     * Créer une nouvelle charge
     */
   public function store(Request $request)
{
    \Log::info('📥 Données reçues pour créer une charge:', $request->all());
    
    // Validation
    try {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:fixe,variable',
            'charge_category_id' => 'nullable|exists:charge_categories,id',
            'montant' => 'required|numeric|min:0',
            'date_charge' => 'required|date',
            'date_echeance' => 'nullable|date|after_or_equal:date_charge',
            'mode_paiement' => 'required|in:especes,virement,cheque,carte,autre',
            'reference_paiement' => 'nullable|string|max:255',
            'statut_paiement' => 'required|in:paye,impaye,partiel',
            'montant_paye' => 'nullable|numeric|min:0',
            'fournisseur' => 'nullable|string|max:255',
            'fournisseur_telephone' => 'nullable|string|max:20',
            'recurrent' => 'nullable|in:on,1,true',
            'frequence' => 'nullable|in:mensuel,trimestriel,annuel,unique',
            'notes' => 'nullable|string',
            'facture' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        \Log::info('✅ Validation réussie');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('❌ Erreur de validation:', $e->errors());
        return redirect()->back()
            ->withInput()
            ->withErrors($e->errors())
            ->with('error', '❌ Erreur de validation.');
    }

    try {
        // ========== UNE SEULE TRANSACTION ==========
        DB::beginTransaction();

        // Upload facture AVANT la création
        if ($request->hasFile('facture')) {
            \Log::info('📄 Upload facture...');
            $facturePath = $request->file('facture')->store('charges/factures', 'public');
            $validated['facture_path'] = $facturePath;
            \Log::info('✅ Facture uploadée: ' . $facturePath);
        }

        // Données supplémentaires
        $validated['user_id'] = auth()->id();
        $validated['recurrent'] = $request->has('recurrent');
        
        if ($validated['recurrent'] && empty($validated['frequence'])) {
            $validated['frequence'] = 'mensuel';
        }

        // Calculer montant_paye si non fourni
        if (!isset($validated['montant_paye']) || $validated['montant_paye'] === null) {
            $validated['montant_paye'] = match($validated['statut_paiement']) {
                'paye' => $validated['montant'],
                'impaye' => 0,
                'partiel' => $request->input('montant_paye', 0),
                default => $validated['montant']
            };
        }

        \Log::info('🔨 Création de la charge...');
        
        // LE NUMÉRO SERA GÉNÉRÉ DANS LE boot() AVEC VERROU
        $charge = Charge::create($validated);
        
        \Log::info("✅ Charge créée: ID={$charge->id}, Ref={$charge->numero_reference}");

        DB::commit();
        \Log::info('✅ Transaction validée');

        return redirect()->back()->with('success', "✅ Charge '{$charge->libelle}' créée !");
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('❌ Erreur création charge:', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', "❌ Erreur: " . $e->getMessage());
    }
}

    /**
     * Afficher les détails d'une charge
     */
    public function show(Charge $charge)
    {
        $charge->load(['category', 'user']);
        
        return response()->json([
            'success' => true,
            'charge' => $charge
        ]);
    }

    /**
     * Mettre à jour une charge
     */
    public function update(Request $request, Charge $charge)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:fixe,variable',
            'charge_category_id' => 'nullable|exists:charge_categories,id',
            'montant' => 'required|numeric|min:0',
            'date_charge' => 'required|date',
            'date_echeance' => 'nullable|date|after_or_equal:date_charge',
            'mode_paiement' => 'required|in:especes,virement,cheque,carte,autre',
            'reference_paiement' => 'nullable|string|max:255',
            'statut_paiement' => 'required|in:paye,impaye,partiel',
            'montant_paye' => 'nullable|numeric|min:0',
            'fournisseur' => 'nullable|string|max:255',
            'fournisseur_telephone' => 'nullable|string|max:20',
            'recurrent' => 'nullable|boolean',
            'frequence' => 'nullable|in:mensuel,trimestriel,annuel,unique',
            'notes' => 'nullable|string',
            'facture' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        try {
            DB::beginTransaction();

            // Gérer l'upload de la nouvelle facture
            if ($request->hasFile('facture')) {
                // Supprimer l'ancienne facture
                if ($charge->facture_path) {
                    Storage::disk('public')->delete($charge->facture_path);
                }
                
                $facturePath = $request->file('facture')->store('charges/factures', 'public');
                $validated['facture_path'] = $facturePath;
            }

            // Convertir recurrent en boolean
            $validated['recurrent'] = $request->has('recurrent');

            // Mettre à jour
            $charge->update($validated);

            DB::commit();

            return redirect()->back()->with('success', "✅ Charge '{$charge->libelle}' modifiée avec succès!");
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->withInput()
                ->with('error', "❌ Erreur lors de la modification: " . $e->getMessage());
        }
    }

    /**
     * Supprimer une charge (soft delete)
     */
    public function destroy(Charge $charge)
    {
        try {
            $libelle = $charge->libelle;
            $charge->delete();

            return redirect()->back()->with('success', "✅ Charge '{$libelle}' supprimée!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    /**
     * Restaurer une charge supprimée
     */
    public function restore($id)
    {
        try {
            $charge = Charge::withTrashed()->findOrFail($id);
            $charge->restore();

            return redirect()->back()->with('success', "✅ Charge '{$charge->libelle}' restaurée!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    /**
     * Marquer une charge comme payée
     */
    public function marquerPayee(Charge $charge)
    {
        try {
            $charge->marquerPayee();

            return redirect()->back()->with('success', "✅ Charge marquée comme payée!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    /**
     * Ajouter un paiement partiel
     */
    public function ajouterPaiement(Request $request, Charge $charge)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric|min:0.01'
        ]);

        try {
            $charge->ajouterPaiement($validated['montant']);

            return redirect()->back()->with('success', "✅ Paiement de {$validated['montant']} DH ajouté!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    /**
     * Générer la prochaine échéance pour une charge récurrente
     */
    public function genererProchaine(Charge $charge)
    {
        try {
            if (!$charge->recurrent) {
                return redirect()->back()->with('error', "❌ Cette charge n'est pas récurrente!");
            }

            $nouvelleCharge = $charge->genererProchaineEcheance();

            if ($nouvelleCharge) {
                return redirect()->back()->with('success', "✅ Prochaine échéance générée!");
            }

            return redirect()->back()->with('error', "❌ Impossible de générer l'échéance!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    // ================================= CATÉGORIES - CRUD ==============================
    
    /**
     * Créer une nouvelle catégorie
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:charge_categories,nom',
            'description' => 'nullable|string',
            'type_defaut' => 'required|in:fixe,variable',
            'icone' => 'nullable|string|max:50',
            'couleur' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
        ]);

        try {
            $category = ChargeCategory::create($validated);

            return redirect()->back()->with('success', "✅ Catégorie '{$category->nom}' créée!");
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    /**
     * Mettre à jour une catégorie
     */
    public function updateCategory(Request $request, ChargeCategory $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:charge_categories,nom,' . $category->id,
            'description' => 'nullable|string',
            'type_defaut' => 'required|in:fixe,variable',
            'icone' => 'nullable|string|max:50',
            'couleur' => 'nullable|string|max:7|regex:/^#[0-9A-F]{6}$/i',
            'actif' => 'nullable|boolean',
        ]);

        try {
            $validated['actif'] = $request->has('actif');
            $category->update($validated);

            return redirect()->back()->with('success', "✅ Catégorie '{$category->nom}' modifiée!");
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    /**
     * Supprimer une catégorie
     */
    public function destroyCategory(ChargeCategory $category)
    {
        try {
            // Vérifier si la catégorie a des charges
            if ($category->charges()->count() > 0) {
                return redirect()->back()->with('error', "❌ Impossible de supprimer: cette catégorie contient des charges!");
            }

            $nom = $category->nom;
            $category->delete();

            return redirect()->back()->with('success', "✅ Catégorie '{$nom}' supprimée!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    /**
     * Toggle actif/inactif pour une catégorie
     */
    public function toggleCategory(ChargeCategory $category)
    {
        try {
            $category->update(['actif' => !$category->actif]);

            $statut = $category->actif ? 'activée' : 'désactivée';
            return redirect()->back()->with('success', "✅ Catégorie '{$category->nom}' {$statut}!");
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    // ================================= STATISTIQUES ==============================
    
    /**
     * Calculer les statistiques pour une période
     */
    private function getStatistiques($dateDebut, $dateFin)
    {
        return [
            // Totaux généraux
            'total_charges' => Charge::entreDates($dateDebut, $dateFin)->paye()->sum('montant'),
            'total_impaye' => Charge::entreDates($dateDebut, $dateFin)->impaye()->sum('montant'),
            'nombre_charges' => Charge::entreDates($dateDebut, $dateFin)->count(),
            
            // Par type
            'total_fixe' => Charge::entreDates($dateDebut, $dateFin)->fixe()->paye()->sum('montant'),
            'total_variable' => Charge::entreDates($dateDebut, $dateFin)->variable()->paye()->sum('montant'),
            
            // Par statut
            'count_paye' => Charge::entreDates($dateDebut, $dateFin)->paye()->count(),
            'count_impaye' => Charge::entreDates($dateDebut, $dateFin)->impaye()->count(),
            'count_partiel' => Charge::entreDates($dateDebut, $dateFin)->where('statut_paiement', 'partiel')->count(),
            
            // Par catégorie (top 5)
            'par_categorie' => Charge::with('category')
                ->entreDates($dateDebut, $dateFin)
                ->paye()
                ->get()
                ->groupBy('charge_category_id')
                ->map(function($charges) {
                    return [
                        'nom' => $charges->first()->category?->nom ?? 'Sans catégorie',
                        'couleur' => $charges->first()->category?->couleur ?? '#64748B',
                        'total' => $charges->sum('montant'),
                        'count' => $charges->count(),
                    ];
                })
                ->sortByDesc('total')
                ->take(5),
            
            // Charges en retard
            'charges_retard' => Charge::impaye()
                ->where('date_echeance', '<', now())
                ->count(),
            
            // Prochaines échéances (7 jours)
            'prochaines_echeances' => Charge::impaye()
                ->whereBetween('date_echeance', [now(), now()->addDays(7)])
                ->count(),
        ];
    }

    /**
     * Exporter les statistiques détaillées (API)
     */
    public function statistiques(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->endOfMonth()->format('Y-m-d'));

        $stats = $this->getStatistiques($dateDebut, $dateFin);

        return response()->json([
            'success' => true,
            'periode' => [
                'debut' => $dateDebut,
                'fin' => $dateFin
            ],
            'statistiques' => $stats
        ]);
    }

    /**
     * Dashboard des charges (graphiques)
     */
    public function dashboard(Request $request)
{
    $annee = $request->input('annee', now()->year);
    $mois = $request->input('mois'); // null = toute l'année

    // Si un mois est sélectionné
    if ($mois) {
        // Stats pour le mois sélectionné
        $dateDebut = Carbon::create($annee, $mois, 1)->startOfMonth();
        $dateFin = Carbon::create($annee, $mois, 1)->endOfMonth();
        
        $stats = [
            'total_charges' => Charge::entreDates($dateDebut, $dateFin)->paye()->sum('montant'),
            'total_fixe' => Charge::entreDates($dateDebut, $dateFin)->fixe()->paye()->sum('montant'),
            'total_variable' => Charge::entreDates($dateDebut, $dateFin)->variable()->paye()->sum('montant'),
            'nombre_charges' => Charge::entreDates($dateDebut, $dateFin)->count(),
            'par_categorie' => Charge::with('category')
                ->entreDates($dateDebut, $dateFin)
                ->paye()
                ->get()
                ->groupBy('charge_category_id')
                ->map(function($charges) {
                    return [
                        'nom' => $charges->first()->category?->nom ?? 'Sans catégorie',
                        'couleur' => $charges->first()->category?->couleur ?? '#64748B',
                        'total' => $charges->sum('montant'),
                        'count' => $charges->count(),
                    ];
                })
                ->sortByDesc('total')
                ->take(5),
        ];

        // Données par jour du mois pour le graphique
        $chargesParJour = [];
        $nbJours = $dateFin->day;
        
        for ($jour = 1; $jour <= $nbJours; $jour++) {
            $date = Carbon::create($annee, $mois, $jour);
            $chargesParJour[$jour] = [
                'jour' => $jour,
                'fixe' => Charge::fixe()
                    ->whereDate('date_charge', $date)
                    ->paye()
                    ->sum('montant'),
                'variable' => Charge::variable()
                    ->whereDate('date_charge', $date)
                    ->paye()
                    ->sum('montant'),
            ];
        }

        $evolutionMensuelle = collect($chargesParJour)->map(function($item) {
            return [
                'mois' => $item['jour'],
                'total' => $item['fixe'] + $item['variable']
            ];
        });

        return view('charges.dashboard', compact(
            'chargesParJour',
            'evolutionMensuelle',
            'stats',
            'annee',
            'mois'
        ));
    }

    // Vue annuelle (comportement par défaut)
    $chargesParMois = [];
    for ($m = 1; $m <= 12; $m++) {
        $chargesParMois[$m] = [
            'mois' => Carbon::create($annee, $m)->format('M'),
            'fixe' => Charge::fixe()->duMois($m, $annee)->paye()->sum('montant'),
            'variable' => Charge::variable()->duMois($m, $annee)->paye()->sum('montant'),
        ];
    }

    $evolutionMensuelle = collect($chargesParMois)->map(function($item) {
        return [
            'mois' => $item['mois'],
            'total' => $item['fixe'] + $item['variable']
        ];
    });

    $stats = $this->getStatistiques(
        Carbon::create($annee, 1, 1)->startOfYear()->format('Y-m-d'),
        Carbon::create($annee, 12, 31)->endOfYear()->format('Y-m-d')
    );

    return view('charges.dashboard', compact(
        'chargesParMois',
        'evolutionMensuelle',
        'stats',
        'annee',
        'mois'
    ));
}


    /**
     * Export Excel/PDF (à implémenter selon tes besoins)
     */
   public function export(Request $request)
{
    try {
        $format = $request->input('format', 'excel'); // excel ou csv
        
        // Récupérer tous les filtres
        $filters = [
            'annee' => $request->input('annee'),
            'mois' => $request->input('mois'),
            'date_debut' => $request->input('date_debut'),
            'date_fin' => $request->input('date_fin'),
            'type' => $request->input('type'),
            'category_id' => $request->input('category_id'),
            'statut' => $request->input('statut'),
            'search' => $request->input('search'),
        ];

        // Calculer les stats pour la période
        if ($filters['annee'] && $filters['mois']) {
            $dateDebut = Carbon::create($filters['annee'], $filters['mois'], 1)->startOfMonth();
            $dateFin = Carbon::create($filters['annee'], $filters['mois'], 1)->endOfMonth();
        } elseif ($filters['annee']) {
            $dateDebut = Carbon::create($filters['annee'], 1, 1)->startOfYear();
            $dateFin = Carbon::create($filters['annee'], 12, 31)->endOfYear();
        } elseif ($filters['date_debut'] && $filters['date_fin']) {
            $dateDebut = $filters['date_debut'];
            $dateFin = $filters['date_fin'];
        } else {
            $dateDebut = now()->startOfMonth();
            $dateFin = now()->endOfMonth();
        }

        $stats = $this->getStatistiques($dateDebut, $dateFin);

        // Générer le nom du fichier
        $filename = 'Charges_';
        if ($filters['annee'] && $filters['mois']) {
            $filename .= Carbon::create($filters['annee'], $filters['mois'])->format('F_Y');
        } elseif ($filters['annee']) {
            $filename .= $filters['annee'];
        } else {
            $filename .= now()->format('Y-m-d');
        }

        // Export selon format
        if ($format === 'csv') {
            $filename .= '.csv';
            return \Excel::download(
                new \App\Exports\ChargesExport($filters),
                $filename,
                \Maatwebsite\Excel\Excel::CSV
            );
        }

        // Excel par défaut (multi-feuilles)
        $filename .= '.xlsx';
        return \Excel::download(
            new \App\Exports\ChargesMultiSheetExport($filters, $stats),
            $filename
        );

    } catch (\Exception $e) {
        \Log::error('Erreur export: ' . $e->getMessage());
        return redirect()->back()->with('error', '❌ Erreur lors de l\'export: ' . $e->getMessage());
    }
}


    public function getCategoryDetails(ChargeCategory $category)
{
    return response()->json([
        'success' => true,
        'category' => [
            'id' => $category->id,
            'nom' => $category->nom,
            'type_defaut' => $category->type_defaut,
            'couleur' => $category->couleur,
            'icone' => $category->icone,
        ]
    ]);
}

}