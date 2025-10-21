<?php

namespace App\Http\Controllers;

use App\Models\Ucg;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class UcgController extends Controller
{
   public function index(Request $request)
{
    // Recherche avancée
    $search = $request->input('search');
    $dateDebut = $request->input('date_debut');
    $dateFin = $request->input('date_fin');
    $garantie = $request->input('garantie');
    $montantMin = $request->input('montant_min');
    $montantMax = $request->input('montant_max');
    $sortBy = $request->input('sort_by', 'created_at');
    $sortOrder = $request->input('sort_order', 'desc');
    $perPage = $request->input('per_page', 10);

    $ucgs = Ucg::query()
        // Recherche par nom, prénom ou équipement
        ->when($search, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('equipemen', 'like', "%{$search}%")
                  ->orWhere('details', 'like', "%{$search}%");
            });
        })
        // Filtrage par période
        ->when($dateDebut, function($query, $dateDebut) {
            return $query->whereDate('date_paiement', '>=', $dateDebut);
        })
        ->when($dateFin, function($query, $dateFin) {
            return $query->whereDate('date_paiement', '<=', $dateFin);
        })
        // Filtrage par type de garantie
        ->when($garantie, function($query, $garantie) {
            return $query->where('recu_garantie', $garantie);
        })
        // Filtrage par montant
        ->when($montantMin, function($query, $montantMin) {
            return $query->where('montant_paye', '>=', $montantMin);
        })
        ->when($montantMax, function($query, $montantMax) {
            return $query->where('montant_paye', '<=', $montantMax);
        })
        // Tri dynamique
        ->orderBy($sortBy, $sortOrder)
        ->paginate($perPage)
        ->withQueryString(); // Garde les paramètres dans la pagination

    // Statistiques
    $stats = [
        'total' => Ucg::count(),
        'total_montant' => Ucg::sum('montant_paye'),
        'montant_mois' => Ucg::whereMonth('date_paiement', now()->month)
                             ->whereYear('date_paiement', now()->year)
                             ->sum('montant_paye'),
        'total_mois' => Ucg::whereMonth('date_paiement', now()->month)
                           ->whereYear('date_paiement', now()->year)
                           ->count(),
        'par_garantie' => Ucg::selectRaw('recu_garantie, COUNT(*) as count, SUM(montant_paye) as total')
                             ->groupBy('recu_garantie')
                             ->get(),
    ];

    // Reçus expirant bientôt (dans les 30 prochains jours)
    $expireBientot = Ucg::whereRaw('DATE_ADD(date_paiement, INTERVAL 
        CASE 
            WHEN recu_garantie = "90 jours" THEN 90
            WHEN recu_garantie = "180 jours" THEN 180
            WHEN recu_garantie = "360 jours" THEN 360
        END DAY) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY)')
        ->count();

    return view('ucgs.index', compact(
        'ucgs', 
        'search', 
        'dateDebut', 
        'dateFin', 
        'garantie', 
        'montantMin', 
        'montantMax',
        'sortBy',
        'sortOrder',
        'perPage',
        'stats',
        'expireBientot'
    ));
}
    

    public function create()
    {
        return view('ucgs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
           'equipemen' => 'required|string|max:255',
            'recu_garantie' => 'required|in:180 jours,90 jours,360 jours',
            'montant_paye' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'details' => 'nullable|string',
        ]);

        Ucg::create([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'recu_garantie' => $request->input('recu_garantie'),
            'montant_paye' => $request->input('montant_paye'),
            'date_paiement' => $request->input('date_paiement'),
            'details' => $request->input('details'),
            'equipemen' => $request->input('equipemen'),
            'user_id' => auth()->id(), // إضافة معرف المستخدم
        ]);

        return redirect()->route('ucgs.index')->with('success', 'Reçu ajouté avec succès.');
    }

    public function show(Ucg $ucg)
{
    $pdf = Pdf::loadView('ucgs.show', compact('ucg'))->setPaper('a5', 'portrait');
    return $pdf->stream(); // ou ->download('document.pdf');
}


    public function edit(Ucg $ucg)
    {
        return view('ucgs.edit', compact('ucg'));
    }

    public function update(Request $request, Ucg $ucg)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'equipemen' => 'required|string|max:255',
            'recu_garantie' => 'required|in:180 jours,90 jours,360 jours',
            'montant_paye' => 'required|numeric|min:0',
            'date_paiement' => 'required|date',
            'details' => 'nullable|string',
        ]);

        $ucg->update($request->all());

        return redirect()->route('ucgs.index')->with('success', 'Reçu mis à jour avec succès.');
    }

    public function destroy(Ucg $ucg)
    {
        $ucg->delete();
        return redirect()->route('ucgs.index')->with('success', 'Reçu supprimé avec succès.');
    }

    public function generatePDF(Ucg $ucg)
    {
        set_time_limit(300);
        ini_set('memory_limit', '256M');

        // توليد PDF بحجم A5
        $pdf = Pdf::loadView('ucgs.pdf', compact('ucg'))
                 ->setPaper('a5', 'portrait');

        return $pdf->download('reçu_garantie.pdf');
    }


    public function corbeille()
{
    // Kanst3amlo onlyTrashed() bach njebdo GHI les éléments li mamsou7in
    $ucg = Ucg::onlyTrashed()
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('ucgs.corbeille', compact('ucg'));
}

// N°2. Restauration d'un Élément (I3ada l'Hayat)
public function restore($id)
{
    // Kanjebdo l-élément b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
    $ucg = Ucg::withTrashed()->findOrFail($id);
    $ucg->restore();

    return redirect()->route('ucg.corbeille')->with('success', 'Élément restauré avec succès!');
}

// N°3. Suppression Définitive (Mass7 Nnéha'i)
public function forceDelete($id)
{
    // Kanjebdo l-élément b ID men l'Corbeille w kan3ayto 3la forceDelete()
    $ucg = Ucg::withTrashed()->findOrFail($id);
    $ucg->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

    return redirect()->route('ucg.corbeille')->with('success', 'Élément supprimé définitivement!');
}
}
