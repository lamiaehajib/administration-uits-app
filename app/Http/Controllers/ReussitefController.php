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
    
        // Ajout de recherche et pagination
        $fomationre = Reussitef::when($search, function ($query, $search) {
                return $query->where('nom', 'like', "%{$search}%")
                             ->orWhere('prenom', 'like', "%{$search}%")
                             ->orWhere('CIN', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc') 
            ->paginate(10); // Pagination à 10 éléments par page
    
        return view('reussitesf.index', compact('fomationre', 'search'));
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
    
        return redirect()->route('reussitesf.index')->with('success', 'Réussite ajoutée avec succès.');
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

        return redirect()->route('reussitesf.index')->with('success', 'Réussite mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $reussite = Reussitef::findOrFail($id);
        $reussite->delete();

        return redirect()->route('reussitesf.index')->with('success', 'Réussite supprimée avec succès.');
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