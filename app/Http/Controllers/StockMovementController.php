<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    /**
     * Afficher la liste des mouvements de stock
     */
    public function index(Request $request)
    {
        $query = StockMovement::with(['produit', 'user', 'recuUcg']);

        // Filtres
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('produit_id')) {
            $query->where('produit_id', $request->produit_id);
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [
                $request->date_debut,
                $request->date_fin . ' 23:59:59'
            ]);
        }

        $movements = $query->latest()->paginate(50);

        $produits = Produit::orderBy('nom')->get();

        return view('stock.movements.index', compact('movements', 'produits'));
    }

    /**
     * Afficher les mouvements d'un produit spécifique
     */
    public function produit(Produit $produit)
    {
        $movements = $produit->stockMovements()
            ->with(['user', 'recuUcg'])
            ->latest()
            ->paginate(50);

        return view('stock.movements.produit', compact('produit', 'movements'));
    }

    /**
     * Ajouter un ajustement manuel de stock
     */
    public function ajustement(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|not_in:0',
            'motif' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $produit = Produit::find($validated['produit_id']);
            $stockAvant = $produit->quantite_stock;
            $quantite = $validated['quantite'];

            // Déterminer le type
            // Si la quantité est positive, c'est une 'entree'
            // Si la quantité est négative, c'est un 'ajustement' (sortie)
            $type = $quantite > 0 ? 'entree' : 'ajustement';
            $quantite = abs($quantite); // Utiliser la valeur absolue pour le mouvement

            // Mettre à jour le stock
            if ($type === 'entree') {
                $produit->quantite_stock += $quantite;
            } else {
                if ($produit->quantite_stock < $quantite) {
                    // Lever une exception si l'on tente de retirer plus que le stock disponible
                    throw new \Exception('Stock insuffisant pour cet ajustement');
                }
                $produit->quantite_stock -= $quantite;
            }

            $produit->save();

            // Créer le mouvement
            StockMovement::create([
                'produit_id' => $produit->id,
                'user_id' => auth()->id(),
                'type' => $type,
                'quantite' => $quantite,
                'stock_avant' => $stockAvant,
                'stock_apres' => $produit->quantite_stock,
                'motif' => $validated['motif'],
            ]);

            DB::commit();

            return back()->with('success', 'Ajustement effectué avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Statistiques du stock
     */
    public function statistiques()
    {
        $stats = [
            'valeur_stock_total' => Produit::selectRaw('SUM(quantite_stock * prix_achat) as total')->value('total'),
            'produits_en_stock' => Produit::where('quantite_stock', '>', 0)->count(),
            // Produits dont la quantité est inférieure ou égale au stock d'alerte
            'produits_rupture' => Produit::where('quantite_stock', '<=', DB::raw('stock_alerte'))->count(),
            'produits_inactifs' => Produit::where('actif', false)->count(),
        ];

        // Liste des produits en alerte (actif et sous le seuil d'alerte)
        $alertes = Produit::where('quantite_stock', '<=', DB::raw('stock_alerte'))
            ->where('actif', true)
            ->orderBy('quantite_stock')
            ->get();

        // 10 derniers mouvements de stock
        $mouvements_recents = StockMovement::with(['produit', 'user'])
            ->latest()
            ->take(10)
            ->get();

        return view('stock.statistiques', compact('stats', 'alertes', 'mouvements_recents'));
    }
}