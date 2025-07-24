<?php

namespace App\Http\Controllers;

use App\Models\BonDeCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BonDeCommandeController extends Controller
{
    public function index()
    {
        $bons = BonDeCommande::latest()->get();
        return view('bon_de_commande.index', compact('bons'));
    }

    public function create()
    {
        return view('bon_de_commande.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'fichier' => 'required|file|mimes:pdf,csv,xls,xlsx|max:10240', // PDF أو Excel، 10MB max
            'date_commande' => 'nullable|date',
        ]);

        $data = $request->only(['titre', 'date_commande']);

        if ($request->hasFile('fichier')) {
            $file = $request->file('fichier');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bon_de_commande', $fileName, 'public');
            $data['fichier_path'] = $filePath;
        }

        BonDeCommande::create($data);

        return redirect()->route('bon_de_commande.index')->with('success', 'Bon de commande ajouté avec succès!');
    }

    public function edit(BonDeCommande $bonDeCommande)
    {
        return view('bon_de_commande.edit', compact('bonDeCommande'));
    }

    public function update(Request $request, BonDeCommande $bonDeCommande)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'fichier' => 'nullable|file|mimes:pdf,csv,xls,xlsx|max:10240',
            'date_commande' => 'nullable|date',
        ]);

        $data = $request->only(['titre', 'date_commande']);

        if ($request->hasFile('fichier')) {
            // حذف الملف القديم إذا كان موجود
            if ($bonDeCommande->fichier_path) {
                Storage::disk('public')->delete($bonDeCommande->fichier_path);
            }
            $file = $request->file('fichier');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('bon_de_commande', $fileName, 'public');
            $data['fichier_path'] = $filePath;
        }

        $bonDeCommande->update($data);

        return redirect()->route('bon_de_commande.index')->with('success', 'Bon de commande mis à jour avec succès!');
    }

    public function destroy(BonDeCommande $bonDeCommande)
    {
        if ($bonDeCommande->fichier_path) {
            Storage::disk('public')->delete($bonDeCommande->fichier_path);
        }
        $bonDeCommande->delete();
        return redirect()->route('bon_de_commande.index')->with('success', 'Bon de commande supprimé avec succès!');
    }

    public function download(BonDeCommande $bonDeCommande)
    {
        if ($bonDeCommande->fichier_path && Storage::disk('public')->exists($bonDeCommande->fichier_path)) {
            return Storage::disk('public')->download($bonDeCommande->fichier_path, basename($bonDeCommande->fichier_path));
        }
        return redirect()->back()->with('error', 'Fichier introuvable.');
    }
}