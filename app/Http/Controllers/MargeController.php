<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marge;
use App\Models\Produit;

class MargeController extends Controller
{
    public function calculer()
    {
        $produits = Produit::all();

        foreach ($produits as $produit) {
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

        return redirect()->route('produits.index')->with('success', 'Marge calculée pour tous les produits.');
    }

    
}
