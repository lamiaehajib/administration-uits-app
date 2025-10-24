<?php

namespace App\Http\Controllers;

use App\Models\AttestationFormation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AttestationFormationController extends Controller
{
    public function index(Request $request)
{
    // Récupérer le terme de recherche depuis la requête
    $search = $request->input('search');

    // Requête avec recherche conditionnelle et pagination
    $attestations = AttestationFormation::when($search, function ($query, $search) {
            return $query->where('numero_de_serie', 'like', "%{$search}%")
                         ->orWhere('personne_name', 'like', "%{$search}%")
                         ->orWhere('cin', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc') 
        ->paginate(10); // Afficher 10 enregistrements par page

    return view('attestations_formation.index', compact('attestations', 'search'));
}


    public function create()
    {
        return view('attestations_formation.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'formation_name' => 'required',
        'personne_name' => 'required',
        'cin' => 'required',
    ]);

    // Récupérer les valeurs
    $formationName = $request->input('formation_name');
    $personneName = $request->input('personne_name');
    $cin = $request->input('cin');
    $afficher_cachet = $request->has('afficher_cachet') ? 1 : 0; // ✅ Correction ici

    // Générer le numéro de série
    $numbers = preg_replace('/[^0-9]/', '', $cin);
    $letters = preg_replace('/[^A-Za-z]/', '', $cin);
    $date = now()->format('dm');
    $year = now()->format('y');

    $numeroDeSerie = $numbers . $letters . $date . $year;

    // Créer l'attestation
    AttestationFormation::create([
        'formation_name' => $formationName,
        'personne_name' => $personneName,
        'cin' => $cin,
        'numero_de_serie' => $numeroDeSerie,
        'afficher_cachet' => $afficher_cachet,
        'user_id' => auth()->id(),
    ]);

    return redirect()->route('attestations_formation.index')
        ->with('success', 'Attestation de formation créée avec succès.');
}
    


    public function edit(AttestationFormation $attestation)
    {
        return view('attestations_formation.edit', compact('attestation'));
    }

    public function update(Request $request, AttestationFormation $attestation)
{
    $request->validate([
        'formation_name' => 'required',
        'personne_name' => 'required',
        'cin' => 'required',
    ]);

    // Mettre à jour avec gestion du checkbox
    $attestation->update([
        'formation_name' => $request->input('formation_name'),
        'personne_name' => $request->input('personne_name'),
        'cin' => $request->input('cin'),
        'afficher_cachet' => $request->has('afficher_cachet') ? 1 : 0, // ✅ Correction
    ]);

    return redirect()->route('attestations_formation.index')
        ->with('success', 'Attestation de formation mise à jour avec succès.');
}


    public function destroy(AttestationFormation $attestation)
    {
        $attestation->delete();
        return redirect()->route('attestations_formation.index')->with('success', 'Attestation de formation supprimée avec succès.');
    }

    public function generatePDF(AttestationFormation $attestation)
    {
        // Assurez-vous que DomPDF utilise une configuration plus simple si nécessaire
        $pdf = Pdf::loadView('attestations_formation.pdf', compact('attestation'))->setPaper('a4', 'landscape');
        return $pdf->download('attestation_formation.pdf');
    }
    
}
