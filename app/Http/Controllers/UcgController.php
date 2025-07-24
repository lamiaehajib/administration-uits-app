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
}
