<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Category;
use App\Models\Achat;
use App\Models\Vente;
use App\Models\Marge;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ProduitController extends Controller
{
    // عرض جميع المنتجات
    public function index(Request $request)
    {
        $search = $request->input('search'); // Récupérer le terme de recherche
    
        // Recherche des produits par nom et pagination
        $produits = Produit::leftJoin('categories', 'produits.category_id', '=', 'categories.id')
            ->leftJoin('achats', 'produits.id', '=', 'achats.produit_id')
            ->leftJoin('ventes', 'produits.id', '=', 'ventes.produit_id')
            ->leftJoin('marges', 'produits.id', '=', 'marges.produit_id')
            ->select(
                'produits.id',
                'produits.nom as produit_nom',
                'categories.nom as categorie_nom',
                'achats.quantite',
                'achats.prix_achat',
                'achats.total_achat',
                'produits.prix_vendu as prix_vente_unitaire',
                'ventes.quantite_vendue',
                'ventes.prix_vendu', 
                'ventes.total_vendu',
                'produits.quantite_stock as en_stock',
                'marges.marge'
            )
            ->when($search, function ($query) use ($search) {
                return $query->where('produits.nom', 'like', '%' . $search . '%');
            })
            ->paginate(10); // Pagination de 10 produits par page
    
        // Assurer que chaque produit a une marge, même si elle est nulle
        foreach ($produits as $produit) {
            if ($produit->marge === null) {
                $produit->marge = 'N/A';
            }
        }
    
        return view('produits.index', compact('produits'));
    }
    

    public function getTotals(Request $request)
    {
        // Get the selected date or use the current month if none is selected
        $date = $request->input('date', now()->format('Y-m')); // Default to current month
    
        // Get the totals for the selected month
        $totalAchat = \App\Models\Achat::where('created_at', 'like', "$date%")->sum('total_achat');
        $totalVente = \App\Models\Vente::where('created_at', 'like', "$date%")->sum('total_vendu');
        $totalStock = \App\Models\Produit::sum('quantite_stock'); // Keep this for overall stock
        $bénéfice = $totalVente - $totalAchat; // Calculate the profit
    
        return view('produits.totals', compact('totalAchat', 'totalVente', 'totalStock', 'bénéfice', 'date'));
    }
    


public function exportPDF(Request $request)
{
    // Ensure that the date format is valid for Carbon parsing
    $dateFin = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
    $dateDebut = $dateFin->copy()->startOfMonth();

    // Ensure the 'marges' table is joined
    $ventes = Produit::leftJoin('ventes', 'ventes.produit_id', '=', 'produits.id')
    ->leftJoin('achats', 'produits.id', '=', 'achats.produit_id')
    ->leftJoin('marges', 'produits.id', '=', 'marges.produit_id')
    ->select(
        'produits.nom as produit_nom',
        'achats.prix_achat',
        'ventes.quantite_vendue',
        'ventes.prix_vendu',
        'ventes.total_vendu',
        'ventes.marge',
        'produits.prix_vendu as prix_vente_unitaire',
        'produits.quantite_stock'
    )
    ->paginate(10);
    
   


    // Generate the PDF using the view
    $pdf = Pdf::loadView('produits.rapport_ventes', compact('ventes', 'dateDebut', 'dateFin'));
    return $pdf->download('rapport_ventes_' . $dateFin->format('Y-m-d') . '.pdf');
}






    // عرض نموذج إنشاء منتج جديد
    public function create()
    {
        $categories = Category::all();
        return view('produits.create', compact('categories'));
    }

    // حفظ منتج جديد
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'nom' => 'required|string|unique:produits',
        'category_id' => 'required|exists:categories,id',
        'prix_vendu' => 'nullable|numeric',
        'quantite_stock' => 'required|integer',
    ]);

    $produit = Produit::create($validatedData);

    // حساب `marge` بعد إنشاء المنتج
    $this->calculerMarge($produit);

    return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
}

public function update(Request $request, Produit $produit)
{
    $validatedData = $request->validate([
        'nom' => 'required|string|unique:produits,nom,' . $produit->id,
        'category_id' => 'required|exists:categories,id',
        'prix_vendu' => 'nullable|numeric',
        'quantite_stock' => 'required|integer',
    ]);

    $produit->update($validatedData);

    // حساب `marge` بعد التحديث
    $this->calculerMarge($produit);

    return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès.');
}

private function calculerMarge($produit)
{
    $achat = $produit->achats()->latest()->first();
    $vente = $produit->ventes()->latest()->first();

    if ($achat && $vente) {
        $marge = $vente->prix_vendu - $achat->prix_achat;
        Marge::updateOrCreate(
            ['produit_id' => $produit->id],
            ['marge' => $marge]
        );
    }
}

public function getProduitsByCategory($category_id)
{
    $produits = Produit::where('category_id', $category_id)->get();
    return response()->json($produits);
}



    // عرض منتج محدد
    public function show($id)
    {
        $produit = Produit::with('category', 'achats', 'ventes', 'marge')->findOrFail($id);
        return view('produits.show', compact('produit'));
    }

    // عرض نموذج تعديل منتج
    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        $categories = Category::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    // تعديل منتج معين
    

    // حذف منتج معين
    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);
        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }
}
