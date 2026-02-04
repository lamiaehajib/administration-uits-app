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

    public function create()
    {
        $categories = Category::with('produits')->get();
        return view('achats.create', compact('categories'));
    }

    public function store(Request $request)
    {
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

            // ✅ Créer l'achat avec quantite_restante
            $achat = Achat::create([
                'produit_id' => $validated['produit_id'],
                'user_id' => auth()->id(),
                'fournisseur' => $validated['fournisseur'] ?? null,
                'numero_bon' => $validated['numero_bon'] ?? null,
                'quantite' => $validated['quantite'],
                'quantite_restante' => $validated['quantite'], // ✅ IMPORTANT
                'prix_achat' => $validated['prix_achat'],
                'total_achat' => $validated['quantite'] * $validated['prix_achat'],
                'date_achat' => $validated['date_achat'],
                'notes' => $validated['notes'] ?? null
            ]);

            Log::info("✅ Achat créé #{$achat->id} - Quantité restante: {$achat->quantite_restante}");

            $updateStock = $request->has('update_stock');
            
            if ($updateStock) {
                $produit->increment('quantite_stock', $validated['quantite']);

                StockMovement::create([
                    'produit_id' => $produit->id,
                    'user_id' => auth()->id(),
                    'type' => 'entree',
                    'quantite' => $validated['quantite'],
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $produit->fresh()->quantite_stock,
                    'reference' => $validated['numero_bon'] ?? "Achat #{$achat->id}",
                    'motif' => "Achat FIFO - " . ($validated['fournisseur'] ?? 'Fournisseur non spécifié')
                ]);

                Log::info("📦 Stock mis à jour: {$stockAvant} → {$produit->fresh()->quantite_stock}");
            }

            $produit->update(['prix_achat' => $validated['prix_achat']]);

            DB::commit();
            
            $message = 'Achat ajouté avec succès!';
            if (!$updateStock) {
                $message .= ' (Stock non modifié)';
            }
            
            return redirect()->route('achats.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Erreur création achat: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $achat = Achat::with('produit')->findOrFail($id);
        $categories = Category::with('produits')->get();
        return view('achats.edit', compact('achat', 'categories'));
    }

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

            $updateStock = $request->has('update_stock');

            if ($updateStock && $difference != 0) {
                $stockAvant = $produit->quantite_stock;
                
                if ($difference > 0) {
                    $produit->increment('quantite_stock', $difference);
                    // ✅ Augmenter quantite_restante aussi
                    $achat->increment('quantite_restante', $difference);
                } elseif ($difference < 0) {
                    $produit->decrement('quantite_stock', abs($difference));
                    // ✅ Diminuer quantite_restante (si possible)
                    $nouvRestante = max(0, $achat->quantite_restante + $difference);
                    $achat->quantite_restante = $nouvRestante;
                }

                StockMovement::create([
                    'produit_id' => $produit->id,
                    'user_id' => auth()->id(),
                    'type' => $difference > 0 ? 'entree' : 'ajustement',
                    'quantite' => abs($difference),
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $produit->fresh()->quantite_stock,
                    'reference' => $validated['numero_bon'] ?? "Achat #{$achat->id}",
                    'motif' => "Modification achat FIFO (différence: " . ($difference > 0 ? '+' : '') . $difference . ")"
                ]);

                Log::info("📦 Stock ajusté: {$stockAvant} → {$produit->fresh()->quantite_stock}");
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

            $produit->update(['prix_achat' => $validated['prix_achat']]);

            DB::commit();
            
            $message = 'Achat mis à jour avec succès!';
            if (!$updateStock && $difference != 0) {
                $message .= ' (Stock non modifié)';
            }
            
            return redirect()->route('achats.index')->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Erreur mise à jour: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $achat = Achat::findOrFail($id);
            $produit = $achat->produit;

            if ($produit) {
                $stockAvant = $produit->quantite_stock;
                
                // Retirer SEULEMENT quantite_restante (pas quantite complète)
                $produit->decrement('quantite_stock', $achat->quantite_restante);

                StockMovement::create([
                    'produit_id' => $produit->id,
                    'user_id' => auth()->id(),
                    'type' => 'ajustement',
                    'quantite' => $achat->quantite_restante,
                    'stock_avant' => $stockAvant,
                    'stock_apres' => $produit->fresh()->quantite_stock,
                    'reference' => $achat->numero_bon ?? "Achat #{$achat->id}",
                    'motif' => "Suppression achat FIFO (restant: {$achat->quantite_restante})"
                ]);
                
                Log::info("🗑️ Achat supprimé - Stock ajusté: -{$achat->quantite_restante}");
            }

            $achat->delete();

            DB::commit();
            return redirect()
                ->route('achats.index')
                ->with('success', 'Achat supprimé avec succès! Le stock a été ajusté.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Erreur suppression: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function getProduits(Request $request)
    {
        $categoryId = $request->input('category_id');
        
        $query = Produit::select('id', 'nom', 'quantite_stock')
            ->orderBy('nom', 'asc');
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $produits = $query->get();
        
        return response()->json([
            'success' => true,
            'produits' => $produits,
            'count' => $produits->count()
        ]);
    }
}