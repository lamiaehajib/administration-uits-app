<?php

namespace App\Http\Controllers;

use App\Models\Reussite;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReussiteController extends Controller
{
    public function index(Request $request)
    {
        // Recherche
        $search = $request->input('search');
        
        $reussites = Reussite::when($search, function($query, $search) {
                return $query->where('nom', 'like', "%{$search}%")
                             ->orWhere('prenom', 'like', "%{$search}%")
                             ->orWhere('CIN', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc') 
            ->paginate(10); // Pagination à 10 éléments par page
    
        return view('reussites.index', compact('reussites', 'search'));
    }
    

    public function create()
    {
        return view('reussites.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'nom' => 'required',
        'prenom' => 'required',
        'duree_stage' => 'required',
        'montant_paye' => 'required|numeric',
        'rest' => 'nullable|numeric',
        'date_paiement' => 'required|date',
        'CIN' => 'nullable|string',
        'tele' => 'nullable|string',
        'gmail' => 'nullable|email',
    ]);

    Reussite::create([
        'nom' => $request->input('nom'),
        'prenom' => $request->input('prenom'),
        'duree_stage' => $request->input('duree_stage'),
        'montant_paye' => $request->input('montant_paye'),
        'rest' => $request->input('rest'),
        'date_paiement' => $request->input('date_paiement'),
        'CIN' => $request->input('CIN'),
        'tele' => $request->input('tele'),
        'gmail' => $request->input('gmail'),
        'user_id' => auth()->id(), // إضافة معرف المستخدم
    ]);

    return redirect()->route('reussites.index')->with('success', 'Réussite ajoutée avec succès.');
}


    public function show(Reussite $reussite)
    {
        return view('reussites.show', compact('reussite'));
    }

    public function edit(Reussite $reussite)
    {
        return view('reussites.edit', compact('reussite'));
    }

    public function update(Request $request, Reussite $reussite)
    {
        $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'duree_stage' => 'required',
            'montant_paye' => 'required|numeric',
            'rest' => 'nullable|numeric',
            'date_paiement' => 'required|date',
            'CIN' => 'nullable|string',   // Validation for CIN (optional, as it's nullable)
            'tele' => 'nullable|string',  // Validation for telephone number (optional, as it's nullable)
            'gmail' => 'nullable|email', 
        ]);

        $reussite->update($request->all());
        return redirect()->route('reussites.index')->with('success', 'Réussite mise à jour avec succès.');
    }

    public function destroy(Reussite $reussite)
    {
        $reussite->delete();
        return redirect()->route('reussites.index')->with('success', 'Réussite supprimée avec succès.');
    }


    public function duplicate(Reussite $reussite)
    {
        // استخدام replicate() لإنشاء نسخة جديدة من الموديل
        $newReussite = $reussite->replicate();

        // يمكن تغيير بعض الحقول هنا إذا لزم الأمر قبل الحفظ، مثل مسح 'date_paiement'
        // $newReussite->date_paiement = null;

        // ✨ Ajouté : Remplacer l'ID de l'utilisateur par l'ID de l'utilisateur actuel
        $newReussite->user_id = auth()->id();

        // حفظ النسخة الجديدة في قاعدة البيانات
        $newReussite->save();

        return redirect()->route('reussites.index')->with('success', 'Réussite dupliquée avec succès.');
    }


    public function generatePDF(Reussite $reussite)
{
    set_time_limit(300);
    ini_set('memory_limit', '256M');

    // Spécifie le format PDF en A5
    $pdf = Pdf::loadView('reussites.pdf', compact('reussite'))
             ->setPaper('a5', 'portrait'); // Utilisation du format A5 en mode portrait

    return $pdf->download('reçu_stage.pdf');
}

}
