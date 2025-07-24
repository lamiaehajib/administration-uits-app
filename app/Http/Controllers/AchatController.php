<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Achat;
use App\Models\Produit;

class AchatController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer le terme de recherche
        $search = $request->input('search');
    
        // Recherche des achats et pagination
        $achats = Achat::with('produit')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('produit', function ($q) use ($search) {
                    $q->where('total_achat', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10); // Pagination de 10 achats par page
    
        return view('achats.index', compact('achats'));
    }
    
    public function create()
{
    $categories = Category::all(); // جلب جميع التصنيفات
    return view('achats.create', compact('categories'));
}


    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'prix_achat' => 'required|numeric|min:0'
        ]);

        Achat::create([
            'produit_id' => $request->produit_id,
            'quantite' => $request->quantite,
            'prix_achat' => $request->prix_achat,
            'total_achat' => $request->quantite * $request->prix_achat
        ]);

        return redirect()->route('achats.index')->with('success', 'Achat ajouté.');
    }

    public function edit($id)
{
    $achat = Achat::findOrFail($id);
    $produits = Produit::all(); // Récupérer tous les produits
    return view('achats.edit', compact('achat', 'produits'));
}
public function update(Request $request, $id)
{
    $request->validate([
        'produit_id' => 'required|exists:produits,id',
        'quantite' => 'required|integer|min:1',
        'prix_achat' => 'required|numeric|min:0'
    ]);

    $achat = Achat::findOrFail($id);
    $achat->update([
        'produit_id' => $request->produit_id,
        'quantite' => $request->quantite,
        'prix_achat' => $request->prix_achat,
        'total_achat' => $request->quantite * $request->prix_achat
    ]);

    return redirect()->route('achats.index')->with('success', 'Achat mis à jour.');
}



public function destroy($id)
{
    $achat = Achat::findOrFail($id);

    // Optionally, you can update stock or handle related operations here
    $produit = $achat->produit;
    if ($produit) {
        // If you are tracking stock, you could update it when an achat is deleted
        $produit->update(['quantite_stock' => $produit->quantite_stock - $achat->quantite]);
    }

    // Delete the achat
    $achat->delete();

    return redirect()->route('achats.index')->with('success', 'Achat supprimé.');
}


}
