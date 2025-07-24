<?php 

namespace App\Http\Controllers;

use App\Models\Attestationallinone;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AttestationAllinoneController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer le terme de recherche
        $search = $request->input('search');
    
        // Appliquer la recherche et pagination
        $attestation = Attestationallinone::when($search, function ($query, $search) {
                return $query->where('personne_name', 'like', "%{$search}%")
                             ->orWhere('numero_de_serie', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc') 
            ->paginate(10); // Pagination, 10 résultats par page
    
        return view('attestations_allinone.index', compact('attestation', 'search'));
    }
    

    public function create()
    {
        return view('attestations_allinone.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'personne_name' => 'required',
            'cin' => 'required',
            'afficher_cachet' => 'boolean', // Validation de afficher_cachet
        ]);

        $personneName = $request->input('personne_name');
        $cin = $request->input('cin');
        $afficherCachet = $request->boolean('afficher_cachet', true); // Valeur par défaut true

        // Générer le numéro de série
        $numbers = preg_replace('/[^0-9]/', '', $cin); // Extraire les chiffres
        $letters = preg_replace('/[^A-Za-z]/', '', $cin); // Extraire les lettres
        $date = now()->format('dm'); // Jour et mois
        $year = now()->format('y');  // Année en deux chiffres (24 pour 2024)

        $numeroDeSerie = $numbers . $letters . $date . $year;

        Attestationallinone::create([
            'personne_name' => $personneName,
            'cin' => $cin,
            'numero_de_serie' => $numeroDeSerie, // Sauvegarder le numéro de série
            'user_id' => auth()->id(), // ربط المستخدم الذي قام بإنشاء السجل
            'afficher_cachet' => $afficherCachet,
        ]);

        return redirect()->route('attestations_allinone.index')->with('success', 'Attestation créée avec succès.');
    }

    public function edit(Attestationallinone $attestation)
    {
        return view('attestations_allinone.edit', compact('attestation'));
    }

    public function update(Request $request, Attestationallinone $attestation)
    {
        $request->validate([
            'personne_name' => 'required',
            'cin' => 'required',
            'afficher_cachet' => 'boolean', // Vérification de la valeur
        ]);

        // Mise à jour de l'attestation
        $attestation->update([
            'personne_name' => $request->input('personne_name'),
            'cin' => $request->input('cin'),
            'afficher_cachet' => $request->boolean('afficher_cachet'), // Mise à jour du cachet
        ]);

        return redirect()->route('attestations_allinone.index')->with('success', 'Attestation mise à jour avec succès.');
    }

    public function destroy(Attestationallinone $attestation)
    {
        $attestation->delete();
        return redirect()->route('attestations_allinone.index')->with('success', 'Attestation supprimée avec succès.');
    }

    public function generatePDF(Attestationallinone $attestation)
    {
        // Assurez-vous que DomPDF utilise une configuration plus simple si nécessaire
        $pdf = Pdf::loadView('attestations_allinone.pdf', compact('attestation'))->setPaper('a4', 'landscape');
        return $pdf->download('attestation_allinone.pdf');
    }
}
