<?php

namespace App\Http\Controllers;

use App\Models\Ucg;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class UcgController extends Controller
{
    public function index(Request $request)
    {
        // البحث
        $search = $request->input('search');
        
        $ucgs = Ucg::when($search, function($query, $search) {
                return $query->where('nom', 'like', "%{$search}%")
                             ->orWhere('prenom', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc') 
            ->paginate(10); // 4 عناصر في كل صفحة
    
        return view('ucgs.index', compact('ucgs', 'search'));
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
