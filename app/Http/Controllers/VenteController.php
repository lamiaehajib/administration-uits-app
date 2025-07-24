<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Vente;
use App\Models\Produit;

class VenteController extends Controller
{
    public function index()
    {
        $ventes = Vente::with('produit')->get();
        return view('ventes.index', compact('ventes'));
    }

    public function create()
    {
        $categories = Category::all(); // جلب جميع التصنيفات
        return view('ventes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite_vendue' => 'required|integer|min:1',
            'prix_vendu' => 'required|numeric|min:0'
        ]);

        $produit = Produit::findOrFail($request->produit_id);

        // التحقق من توفر الكمية في المخزون
        if ($produit->quantite_stock < $request->quantite_vendue) {
            return redirect()->back()->with('error', 'Quantité insuffisante en stock.');
        }

        $dernierAchat = $produit->achats()->latest()->first();

        if ($dernierAchat && $request->prix_vendu < $dernierAchat->prix_achat) {
            return redirect()->back()->with('error', 
                'Le prix de vente doit être supérieur au dernier prix d\'achat. Prix d\'achat: ' . number_format($dernierAchat->prix_achat, 2, ',', ' ') . ' DH'
            );
        }

        // إنشاء عملية البيع
        Vente::create([
            'produit_id' => $request->produit_id,
            'quantite_vendue' => $request->quantite_vendue,
            'prix_vendu' => $request->prix_vendu,
            'total_vendu' => $request->quantite_vendue * $request->prix_vendu,
        ]);

        // تحديث المخزون
        $produit->update(['quantite_stock' => $produit->quantite_stock - $request->quantite_vendue]);

        return redirect()->route('ventes.index')->with('success', 'Vente enregistrée avec marge.');
    }

    public function edit($id)
    {
        $vente = Vente::findOrFail($id);
        $categories = Category::all(); // جلب جميع التصنيفات
        return view('ventes.edit', compact('vente', 'categories'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'produit_id' => 'required|exists:produits,id',
        'quantite_vendue' => 'required|integer|min:1',
        'prix_vendu' => 'required|numeric|min:0'
    ]);

    $vente = Vente::findOrFail($id);
    $produit = Produit::findOrFail($request->produit_id);

    // التحقق من توفر الكمية في المخزون
    if ($produit->quantite_stock < $request->quantite_vendue) {
        return redirect()->back()->with('error', 'Quantité insuffisante en stock.');
    }

    $dernierAchat = $produit->achats()->latest()->first();

    if ($dernierAchat && $request->prix_vendu < $dernierAchat->prix_achat) {
        return redirect()->back()->with('error', 
            'Le prix de vente doit être supérieur au dernier prix d\'achat. Prix d\'achat: ' . number_format($dernierAchat->prix_achat, 2, ',', ' ') . ' DH'
        );
    }

    // تحديث عملية البيع
    $vente->update([
        'produit_id' => $request->produit_id,
        'quantite_vendue' => $request->quantite_vendue,
        'prix_vendu' => $request->prix_vendu,
        'total_vendu' => $request->quantite_vendue * $request->prix_vendu,
    ]);

    // تحديث المخزون
    $produit->update(['quantite_stock' => $produit->quantite_stock - $request->quantite_vendue]);

    return redirect()->route('ventes.index')->with('success', 'Vente mise à jour.');
}


    public function destroy($id)
    {
        $vente = Vente::findOrFail($id);
        $produit = $vente->produit;

        // تحديث المخزون
        $produit->update(['quantite_stock' => $produit->quantite_stock + $vente->quantite_vendue]);

        // حذف عملية البيع
        $vente->delete();

        return redirect()->route('ventes.index')->with('success', 'Vente supprimée.');
    }
}
