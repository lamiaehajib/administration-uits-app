<?php

namespace App\Http\Controllers;

use App\Models\Attestation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class AttestationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $attestations = Attestation::when($search, function ($query, $search) {
                return $query->where('stagiaire_name', 'like', "%{$search}%")
                             ->orWhere('stagiaire_cin', 'like', "%{$search}%")
                             ->orWhere('poste', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
    
        return view('attestations.index', compact('attestations', 'search'));
    }

    public function create()
    {
        return view('attestations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'stagiaire_name' => 'required',
            'stagiaire_cin' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'poste' => 'required',
        ]);

        Attestation::create([
            'stagiaire_name' => $request->input('stagiaire_name'),
            'stagiaire_cin' => $request->input('stagiaire_cin'),
            'date_debut' => $request->input('date_debut'),
            'date_fin' => $request->input('date_fin'),
            'poste' => $request->input('poste'),
            'user_id' => auth()->id(),
            'afficher_cachet' => $request->has('afficher_cachet') ? 1 : 0, // ✅ Correction
        ]);

        return redirect()->route('attestations.index')->with('success', 'Attestation créée avec succès.');
    }

    public function edit(Attestation $attestation)
    {
        return view('attestations.edit', compact('attestation'));
    }

    public function update(Request $request, Attestation $attestation)
    {
        $request->validate([
            'stagiaire_name' => 'required',
            'stagiaire_cin' => 'required',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'poste' => 'required',
        ]);

        $attestation->update([
            'stagiaire_name' => $request->input('stagiaire_name'),
            'stagiaire_cin' => $request->input('stagiaire_cin'),
            'date_debut' => $request->input('date_debut'),
            'date_fin' => $request->input('date_fin'),
            'poste' => $request->input('poste'),
            'afficher_cachet' => $request->has('afficher_cachet') ? 1 : 0, // ✅ Correction
        ]);

        return redirect()->route('attestations.index')->with('success', 'Attestation mise à jour avec succès.');
    }

    public function destroy(Attestation $attestation)
    {
        $attestation->delete();
        return redirect()->route('attestations.index')->with('success', 'Attestation supprimée avec succès.');
    }

    public function generatePDF($id)
    {
        $attestation = Attestation::findOrFail($id);
        $pdf = Pdf::loadView('attestations.pdf', compact('attestation'))->setPaper('a4', 'landscape');
        return $pdf->download('attestation_' . $attestation->stagiaire_name . '.pdf');
    }
}