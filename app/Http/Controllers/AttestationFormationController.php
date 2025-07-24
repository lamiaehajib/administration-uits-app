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
            'afficher_cachet' => 'nullable|boolean',
        ]);
    
        // Récupérer les valeurs
        $formationName = $request->input('formation_name');
        $personneName = $request->input('personne_name');
        $cin = $request->input('cin');
        $afficher_cachet = $request->input('afficher_cachet');
    
    
        // Générer le numéro de série
        $numbers = preg_replace('/[^0-9]/', '', $cin); // Extraire les chiffres
        $letters = preg_replace('/[^A-Za-z]/', '', $cin); // Extraire les lettres
        $date = now()->format('dm'); // Jour et mois
        $year = now()->format('y');  // Année en deux chiffres (24 pour 2024)
    
        $numeroDeSerie = $numbers . $letters . $date . $year;
    
        // Créer l'attestation
        AttestationFormation::create([
            'formation_name' => $formationName,
            'personne_name' => $personneName,
            'cin' => $cin,
            'numero_de_serie' => $numeroDeSerie, // Sauvegarder le numéro de série
            'afficher_cachet' => $afficher_cachet, // Sauvegarder le numéro de série

            'user_id' => auth()->id(),
        ]);
    
        return redirect()->route('attestations_formation.index')->with('success', 'Attestation de formation créée avec succès.');
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
        'cin'=> 'required',
    ]);

    // Mettre à jour l'attestation
    $attestation->update($request->all());

    return redirect()->route('attestations_formation.index')->with('success', 'Attestation de formation mise à jour avec succès.');
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
