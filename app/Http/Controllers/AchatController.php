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
    $search      = $request->input('search');
    $fournisseur = $request->input('fournisseur');
    $category_id = $request->input('category_id');
    $produit_id  = $request->input('produit_id');
    $mois        = $request->input('mois');
    $annee       = $request->input('annee');

    $achats = Achat::with(['produit.category'])
        ->when($search, fn($q) => $q->whereHas('produit', fn($q2) =>
            $q2->where('nom', 'like', "%$search%")
               ->orWhere('reference', 'like', "%$search%")
        ))
        ->when($fournisseur, fn($q) => $q->where('fournisseur', 'like', "%$fournisseur%"))
        ->when($category_id, fn($q) => $q->whereHas('produit', fn($q2) =>
            $q2->where('category_id', $category_id)
        ))
        ->when($produit_id, fn($q) => $q->where('produit_id', $produit_id))
        ->when($mois, fn($q) => $q->whereRaw('DATE_FORMAT(date_achat, "%Y-%m") = ?', [$mois]))
        ->when($annee, fn($q) => $q->whereYear('date_achat', $annee))
        ->latest('date_achat')
        ->paginate(10)
        ->appends($request->all());

    $categories          = \App\Models\Category::orderBy('nom')->get();
    $fournisseursListe   = Achat::whereNotNull('fournisseur')->distinct()->pluck('fournisseur')->sort()->values();
    $produitsListe       = \App\Models\Produit::orderBy('nom')->get(['id', 'nom']);
    $anneesDisponibles   = Achat::selectRaw('YEAR(date_achat) as annee')->distinct()->orderByDesc('annee')->pluck('annee');


    // ✅ Stats filtrées (basées sur la même query SANS pagination)
$statsQuery = Achat::with(['produit.category'])
    ->when($search, fn($q) => $q->whereHas('produit', fn($q2) =>
        $q2->where('nom', 'like', "%$search%")
           ->orWhere('reference', 'like', "%$search%")
    ))
    ->when($fournisseur, fn($q) => $q->where('fournisseur', 'like', "%$fournisseur%"))
    ->when($category_id, fn($q) => $q->whereHas('produit', fn($q2) =>
        $q2->where('category_id', $category_id)
    ))
    ->when($produit_id, fn($q) => $q->where('produit_id', $produit_id))
    ->when($mois, fn($q) => $q->whereRaw('DATE_FORMAT(date_achat, "%Y-%m") = ?', [$mois]))
    ->when($annee, fn($q) => $q->whereYear('date_achat', $annee));

$statsFilters = [
    'total_achats'     => $statsQuery->count(),
    'total_quantite'   => $statsQuery->sum('quantite'),
    'total_montant'    => $statsQuery->sum('total_achat'),
    'total_restant'    => $statsQuery->sum('quantite_restante'),
    'valeur_restante'  => $statsQuery->get()->sum(fn($a) => $a->quantite_restante * $a->prix_achat),
];

return view('achats.index', compact(
    'achats', 'categories', 'fournisseursListe',
    'produitsListe', 'anneesDisponibles', 'statsFilters',
    'search', 'fournisseur', 'category_id', 'produit_id', 'mois', 'annee'
));
   
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
        'prix_vente_suggere' => 'required|numeric|min:0',
        'date_achat' => 'required|date',
        'notes' => 'nullable|string',
    ]);

    DB::beginTransaction();
    try {
        $produit = Produit::findOrFail($validated['produit_id']);
        $stockAvant = $produit->quantite_stock;

        $prixAchat = $validated['prix_achat'];
        $prixVente = $validated['prix_vente_suggere'];
        
        if ($prixAchat > 0) {
            $margePourcentage = (($prixVente - $prixAchat) / $prixAchat) * 100;
        } else {
            $margePourcentage = 0;
        }

        // Créer l'achat
        $achat = Achat::create([
            'produit_id' => $validated['produit_id'],
            'user_id' => auth()->id(),
            'fournisseur' => $validated['fournisseur'] ?? null,
            'numero_bon' => $validated['numero_bon'] ?? null,
            'quantite' => $validated['quantite'],
            'quantite_restante' => $validated['quantite'],
            'prix_achat' => $prixAchat,
            'prix_vente_suggere' => $prixVente,
            'marge_pourcentage' => round($margePourcentage, 2),
            'total_achat' => $validated['quantite'] * $prixAchat,
            'date_achat' => $validated['date_achat'],
            'notes' => $validated['notes'] ?? null
        ]);

        Log::info("✅ Achat créé #{$achat->id} - PV: {$achat->prix_vente_suggere} DH, Marge: {$achat->marge_pourcentage}%");

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
                'motif' => "Achat FIFO - PV: {$achat->prix_vente_suggere} DH (Marge: {$achat->marge_pourcentage}%)"
            ]);
        }

        // ✅ SUPPRIMÉ: Ne plus mettre à jour prix_achat/prix_vente du produit
        // Les prix restent dans chaque achat individuellement
        
        // ✅ OPTIONNEL: Si tu veux quand même un "prix de référence" dans la table produits,
        // tu peux calculer le CUMP (Coût Unitaire Moyen Pondéré):
        /*
        $totalQuantite = Achat::where('produit_id', $produit->id)
            ->where('quantite_restante', '>', 0)
            ->sum('quantite_restante');
        
        $valeurTotale = Achat::where('produit_id', $produit->id)
            ->where('quantite_restante', '>', 0)
            ->selectRaw('SUM(quantite_restante * prix_achat) as total')
            ->value('total');
        
        if ($totalQuantite > 0) {
            $prixMoyen = $valeurTotale / $totalQuantite;
            $produit->update(['prix_achat' => round($prixMoyen, 2)]);
        }
        */

        DB::commit();
        
        return redirect()->route('achats.index')->with('success', 'Achat ajouté avec succès!');

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
        'prix_vente_suggere' => 'required|numeric|min:0',
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

        $prixAchat = $validated['prix_achat'];
        $prixVente = $validated['prix_vente_suggere'];
        
        if ($prixAchat > 0) {
            $margePourcentage = (($prixVente - $prixAchat) / $prixAchat) * 100;
        } else {
            $margePourcentage = 0;
        }

        $updateStock = $request->has('update_stock');

        if ($updateStock && $difference != 0) {
            $stockAvant = $produit->quantite_stock;
            
            if ($difference > 0) {
                $produit->increment('quantite_stock', $difference);
                $achat->increment('quantite_restante', $difference);
            } elseif ($difference < 0) {
                $produit->decrement('quantite_stock', abs($difference));
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
            'prix_achat' => $prixAchat,
            'prix_vente_suggere' => $prixVente,
            'marge_pourcentage' => round($margePourcentage, 2),
            'total_achat' => $validated['quantite'] * $prixAchat,
            'date_achat' => $validated['date_achat'],
            'notes' => $validated['notes'] ?? null
        ]);

        // ✅ SUPPRIMÉ: Ne plus mettre à jour prix_achat/prix_vente du produit
        
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