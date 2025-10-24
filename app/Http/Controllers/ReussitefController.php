<?php

namespace App\Http\Controllers;

use App\Models\Reussitef;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class ReussitefController extends Controller
{
   public function index(Request $request)
{
    $search = $request->input('search');
    $formation = $request->input('formation');
    $dateDebut = $request->input('date_debut');
    $dateFin = $request->input('date_fin');
    $modePaiement = $request->input('mode_paiement');
    $statutPaiement = $request->input('statut_paiement'); // 'payé', 'reste', 'tout'
    $sortBy = $request->input('sort_by', 'created_at'); // Colonne de tri
    $sortOrder = $request->input('sort_order', 'desc'); // Ordre: asc ou desc
    $perPage = $request->input('per_page', 10); // Nombre par page

    $query = Reussitef::query();

    // 🔍 Recherche multi-critères avancée
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('prenom', 'like', "%{$search}%")
              ->orWhere('CIN', 'like', "%{$search}%")
              ->orWhere('tele', 'like', "%{$search}%")
              ->orWhere('gmail', 'like', "%{$search}%");
        });
    }

    // 🎓 Filtrage par formation
    if ($formation) {
        $query->where('formation', $formation);
    }

    // 📅 Filtrage par période de paiement
    if ($dateDebut) {
        $query->whereDate('date_paiement', '>=', $dateDebut);
    }
    if ($dateFin) {
        $query->whereDate('date_paiement', '<=', $dateFin);
    }

    // 💳 Filtrage par mode de paiement
    if ($modePaiement) {
        $query->where('mode_paiement', $modePaiement);
    }

    // 💰 Filtrage par statut de paiement (avec reste ou payé complètement)
    if ($statutPaiement === 'reste') {
        $query->where('rest', '>', 0);
    } elseif ($statutPaiement === 'paye') {
        $query->where(function($q) {
            $q->where('rest', 0)
              ->orWhereNull('rest');
        });
    }

    // 📊 Statistiques avant pagination
    $stats = [
        'total_reussites' => (clone $query)->count(),
        'total_montant_paye' => (clone $query)->sum('montant_paye'),
        'total_reste' => (clone $query)->sum('rest'),
        'avec_reste' => (clone $query)->where('rest', '>', 0)->count(),
        'completement_payes' => (clone $query)->where(function($q) {
            $q->where('rest', 0)->orWhereNull('rest');
        })->count(),
    ];

    // 📈 Statistiques par mode de paiement
    $statsPaiement = (clone $query)
        ->selectRaw('mode_paiement, COUNT(*) as count, SUM(montant_paye) as total')
        ->groupBy('mode_paiement')
        ->get()
        ->keyBy('mode_paiement');

    // 🎓 Statistiques par formation
    $statsFormations = (clone $query)
        ->selectRaw('formation, COUNT(*) as count, SUM(montant_paye) as total')
        ->groupBy('formation')
        ->orderByDesc('count')
        ->limit(5)
        ->get();

    // 🔄 Tri dynamique
    $allowedSorts = ['created_at', 'nom', 'prenom', 'date_paiement', 'montant_paye', 'rest', 'formation'];
    if (in_array($sortBy, $allowedSorts)) {
        $query->orderBy($sortBy, $sortOrder);
    } else {
        $query->orderBy('created_at', 'desc');
    }

    // 📄 Pagination avec conservation des filtres
    $fomationre = $query->paginate($perPage)->withQueryString();

    // 📋 Liste des formations disponibles (pour le filtre dropdown)
    $formationsDisponibles = Reussitef::select('formation')
        ->distinct()
        ->orderBy('formation')
        ->pluck('formation');

    // 🎯 Alertes intelligentes
    $alertes = [];
    
    // Alerte pour les paiements en retard (plus de 30 jours avec reste)
    $reusitesEnRetard = Reussitef::where('rest', '>', 0)
        ->whereDate('date_paiement', '<=', now()->subDays(30))
        ->count();
    
    if ($reusitesEnRetard > 0) {
        $alertes[] = [
            'type' => 'warning',
            'message' => "{$reusitesEnRetard} paiement(s) avec reste depuis plus de 30 jours"
        ];
    }

    // Alerte pour les nouveaux reçus du jour
    $nouveauxAujourdhui = Reussitef::whereDate('created_at', today())->count();
    if ($nouveauxAujourdhui > 0) {
        $alertes[] = [
            'type' => 'info',
            'message' => "{$nouveauxAujourdhui} nouveau(x) reçu(s) ajouté(s) aujourd'hui"
        ];
    }

    return view('reussitesf.index', compact(
        'fomationre',
        'search',
        'formation',
        'dateDebut',
        'dateFin',
        'modePaiement',
        'statutPaiement',
        'sortBy',
        'sortOrder',
        'perPage',
        'stats',
        'statsPaiement',
        'statsFormations',
        'formationsDisponibles',
        'alertes'
    ));
}
    

    public function create()
    {
        return view('reussitesf.create');
    }

    public function store(Request $request)
    {
        // 1. 👈 Ajout de la validation pour 'mode_paiement'
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'formation' => 'required|string|max:255',
            'montant_paye' => 'required|numeric',
            'rest' => 'nullable|numeric',
            'date_paiement' => 'nullable|date',
            'CIN' => 'nullable|string',
            'tele' => 'nullable|string',
            'gmail' => 'nullable|email',
            'mode_paiement' => 'required|in:espèce,virement,chèque', // Doit être obligatoire et parmi les valeurs définies
        ]);
    
        Reussitef::create([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'formation' => $request->input('formation'),
            'montant_paye' => $request->input('montant_paye'),
            'rest' => $request->input('rest'),
            'date_paiement' => $request->input('date_paiement'),
            'CIN' => $request->input('CIN'),
            'tele' => $request->input('tele'),
            'gmail' => $request->input('gmail'),
            // 2. 👈 Ajout du champ dans la création
            'mode_paiement' => $request->input('mode_paiement'), 
            'user_id' => auth()->id(), // إضافة user_id المرتبط بالمستخدم الحالي
        ]);
    
        return redirect()->route('reussitesf.index')->with('success', 'reçu ajoutée avec succès.');
    }

    public function edit($id)
    {
        
        $reussite = Reussitef::findOrFail($id);

        
        return view('reussitesf.edit', compact('reussite'));
    }

    

    public function update(Request $request, $id)
    {
        // 1. 👈 Ajout de la validation pour 'mode_paiement'
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'formation' => 'required|string|max:255',
            'montant_paye' => 'required|numeric',
            'rest' => 'nullable|numeric',
            'date_paiement' => 'required|date',
            'CIN' => 'nullable|string', 
            'tele' => 'nullable|string', 
            'gmail' => 'nullable|email',
            'mode_paiement' => 'required|in:espèce,virement,chèque', // Doit être obligatoire et parmi les valeurs définies
        ]);

        $reussite = Reussitef::findOrFail($id);
        
        // 2. 👈 Changement pour passer explicitement les données validées
        // Pour être plus sûr et gérer les champs individuellement :
        $reussite->update([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'formation' => $request->input('formation'),
            'montant_paye' => $request->input('montant_paye'),
            'rest' => $request->input('rest'),
            'date_paiement' => $request->input('date_paiement'),
            'CIN' => $request->input('CIN'),
            'tele' => $request->input('tele'),
            'gmail' => $request->input('gmail'),
            'mode_paiement' => $request->input('mode_paiement'), // Ajout de mode_paiement ici
            // 'user_id' n'a pas besoin d'être mis à jour ici
        ]);

        // Alternative : Utiliser $request->all() s'il n'y a pas d'autres champs non fillable que user_id et que user_id n'est pas dans le formulaire.
        // $reussite->update($request->all());

        return redirect()->route('reussitesf.index')->with('success', 'reçu mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $reussite = Reussitef::findOrFail($id);
        $reussite->delete();

        return redirect()->route('reussitesf.index')->with('success', 'reçu supprimée avec succès.');
    }

     public function duplicate(Reussitef $reussitef)
{
    // استخدام replicate() لإنشاء نسخة جديدة من الموديل
    $newReussitef = $reussitef->replicate();
    
    // يمكن تغيير بعض الحقول هنا قبل الحفظ، مثل مسح 'date_paiement' أو 'rest'
    // $newReussitef->date_paiement = now();
    // $newReussitef->rest = null;

    // ✨ إضافة معرف المستخدم الحالي لربط النسخة الجديدة به
    $newReussitef->user_id = auth()->id(); 

    // حفظ النسخة الجديدة في قاعدة البيانات
    $newReussitef->save();

    return redirect()->route('reussitesf.index')->with('success', 'reçu (Formation) dupliquée avec succès.');
}

    public function downloadPDF($id)
    {
        set_time_limit(300);
        ini_set('memory_limit', '256M');

        // ⬅️ Khassna nzidou withTrashed() bach ychouf hta li mamsou7in!
        $reussite = Reussitef::withTrashed()->findOrFail($id);
        // Hadchi kayضمن ann l-élément ytle3 wakha ykoun f Corbeille

        $pdf = pdf::loadView('reussitesf.pdf', compact('reussite'))
            ->setPaper('a5', 'portrait');

        return $pdf->download('reçu_formation.pdf');
    }

    
    public function corbeille()
    {
        // Kanst3amlo onlyTrashed() bach njebdo GHI les éléments li mamsou7in
        $reussitef = Reussitef::onlyTrashed()
                      ->orderBy('deleted_at', 'desc')
                      ->get();

        return view('reussitesf.corbeille', compact('reussitef'));
    }

    // N°2. Restauration d'un Élément (I3ada l'Hayat)
    public function restore($id)
    {
        // Kanjebdo l-élément b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
        $reussitef = Reussitef::withTrashed()->findOrFail($id);
        $reussitef->restore();

        return redirect()->route('reussitef.corbeille')->with('success', 'Élément restauré avec succès!');
    }

    // N°3. Suppression Définitive (Mass7 Nnéha'i)
    public function forceDelete($id)
    {
        // Kanjebdo l-élément b ID men l'Corbeille w kan3ayto 3la forceDelete()
        $reussitef = Reussitef::withTrashed()->findOrFail($id);
        $reussitef->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

        return redirect()->route('reussitef.corbeille')->with('success', 'Élément supprimé définitivement!');
    }
    

}