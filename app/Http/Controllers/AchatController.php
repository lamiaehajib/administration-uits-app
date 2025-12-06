<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Achat;
use App\Models\Produit;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AchatController extends Controller
{
    public function __construct()
{
    $this->middleware('permission:achat-list|achat-create|achat-edit|achat-delete', ['only' => ['index']]);
    $this->middleware('permission:achat-create', ['only' => ['create', 'store']]);
    $this->middleware('permission:achat-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:achat-delete', ['only' => ['destroy']]);
}
    /**
     * Afficher la liste des achats avec recherche
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $achats = Achat::with('produit')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('produit', function ($q) use ($search) {
                    $q->where('nom', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10);
    
        return view('achats.index', compact('achats'));
    }
    
    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        // Charger toutes les catégories avec leurs produits
        $categories = Category::with('produits')->get();
        return view('achats.create', compact('categories'));
    }

    /**
     * Enregistrer un nouvel achat
     */
    public function store(Request $request)
    {
        // Log pour déboguer
        Log::info('Achat Store Request:', $request->all());

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'fournisseur' => 'nullable|string|max:255',
            'numero_bon' => 'nullable|string|max:255',
            'quantite' => 'required|integer|min:1',
            'prix_achat' => 'required|numeric|min:0',
            'date_achat' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $produit = Produit::findOrFail($validated['produit_id']);
            $stockAvant = $produit->quantite_stock;

            // 1️⃣ Créer l'achat
            $achat = Achat::create([
                'produit_id' => $validated['produit_id'],
                'user_id' => auth()->id(),
                'fournisseur' => $validated['fournisseur'] ?? null,
                'numero_bon' => $validated['numero_bon'] ?? null,
                'quantite' => $validated['quantite'],
                'prix_achat' => $validated['prix_achat'],
                'total_achat' => $validated['quantite'] * $validated['prix_achat'],
                'date_achat' => $validated['date_achat'],
                'notes' => $validated['notes'] ?? null
            ]);

            Log::info('Achat créé avec ID: ' . $achat->id);

            // 2️⃣ Vérifier si la checkbox est cochée
            // Si cochée, $request->has('update_stock') retourne true
            // Si décochée, $request->has('update_stock') retourne false
            $updateStock = $request->has('update_stock');
            
            Log::info('Update Stock: ' . ($updateStock ? 'OUI' : 'NON'));

            if ($updateStock) {
                // Mettre à jour le stock
                $produit->increment('quantite_stock', $validated['quantite']);

                // Enregistrer le mouvement de stock
                StockMovement::create([
                    'produit_id' => $produit->id,
                    'user_id' => auth()->id(),
                    'type' => 'entree',
                    'quantite' => $validated['quantite'],
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $produit->fresh()->quantite_stock,
                    'reference' => $validated['numero_bon'] ?? "Achat #{$achat->id}",
                    'motif' => "Achat - " . ($validated['fournisseur'] ?? 'Fournisseur non spécifié')
                ]);

                Log::info('Stock mis à jour: ' . $stockAvant . ' → ' . $produit->fresh()->quantite_stock);
            }

            // 3️⃣ Mettre à jour le prix d'achat du produit
            $produit->update(['prix_achat' => $validated['prix_achat']]);

            DB::commit();
            
            $message = 'Achat ajouté avec succès!';
            if (!$updateStock) {
                $message .= ' (Stock non modifié)';
            }
            
            Log::info('Achat enregistré avec succès');
            
            return redirect()->route('achats.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de l\'achat: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit($id)
    {
        $achat = Achat::with('produit')->findOrFail($id);
        $categories = Category::with('produits')->get();
        return view('achats.edit', compact('achat', 'categories'));
    }

    /**
     * Mettre à jour un achat
     */
    public function update(Request $request, $id)
    {
        Log::info('Achat Update Request:', $request->all());

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'fournisseur' => 'nullable|string|max:255',
            'numero_bon' => 'nullable|string|max:255',
            'quantite' => 'required|integer|min:1',
            'prix_achat' => 'required|numeric|min:0',
            'date_achat' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $achat = Achat::findOrFail($id);
            $produit = Produit::findOrFail($validated['produit_id']);

            $ancienneQuantite = $achat->quantite;
            $nouvelleQuantite = $validated['quantite'];
            $difference = $nouvelleQuantite - $ancienneQuantite;

            // Vérifier la checkbox
            $updateStock = $request->has('update_stock');
            
            Log::info("Différence quantité: $difference, Update stock: " . ($updateStock ? 'OUI' : 'NON'));

            if ($updateStock && $difference != 0) {
                $stockAvant = $produit->quantite_stock;
                
                if ($difference > 0) {
                    $produit->increment('quantite_stock', $difference);
                } elseif ($difference < 0) {
                    $produit->decrement('quantite_stock', abs($difference));
                }

                // Enregistrer le mouvement
                StockMovement::create([
                    'produit_id' => $produit->id,
                    'user_id' => auth()->id(),
                    'type' => $difference > 0 ? 'entree' : 'ajustement',
                    'quantite' => abs($difference),
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $produit->fresh()->quantite_stock,
                    'reference' => $validated['numero_bon'] ?? "Achat #{$achat->id}",
                    'motif' => "Modification achat (différence: " . ($difference > 0 ? '+' : '') . $difference . ")"
                ]);

                Log::info('Stock ajusté: ' . $stockAvant . ' → ' . $produit->fresh()->quantite_stock);
            }

            // Mettre à jour l'achat
            $achat->update([
                'produit_id' => $validated['produit_id'],
                'fournisseur' => $validated['fournisseur'] ?? null,
                'numero_bon' => $validated['numero_bon'] ?? null,
                'quantite' => $validated['quantite'],
                'prix_achat' => $validated['prix_achat'],
                'total_achat' => $validated['quantite'] * $validated['prix_achat'],
                'date_achat' => $validated['date_achat'],
                'notes' => $validated['notes'] ?? null
            ]);

            // Mettre à jour le prix d'achat du produit
            $produit->update(['prix_achat' => $validated['prix_achat']]);

            DB::commit();
            
            $message = 'Achat mis à jour avec succès!';
            if (!$updateStock && $difference != 0) {
                $message .= ' (Stock non modifié)';
            }
            
            return redirect()->route('achats.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Supprimer un achat
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $achat = Achat::findOrFail($id);
            $produit = $achat->produit;

            if ($produit) {
                $stockAvant = $produit->quantite_stock;
                
                // Retirer la quantité du stock
                $produit->decrement('quantite_stock', $achat->quantite);

                // Enregistrer le mouvement
                StockMovement::create([
                    'produit_id' => $produit->id,
                    'user_id' => auth()->id(),
                    'type' => 'ajustement',
                    'quantite' => $achat->quantite,
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $produit->fresh()->quantite_stock,
                    'reference' => $achat->numero_bon ?? "Achat #{$achat->id}",
                    'motif' => "Suppression achat #{$achat->id}"
                ]);
            }

            $achat->delete();

            DB::commit();
            return redirect()
                ->route('achats.index')
                ->with('success', 'Achat supprimé avec succès! Le stock a été ajusté.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la suppression: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}