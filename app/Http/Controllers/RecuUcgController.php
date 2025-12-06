<?php

namespace App\Http\Controllers;

use App\Models\RecuUcg;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
class RecuUcgController extends Controller
{

    public function __construct()
{
    $this->middleware('permission:recu-list|recu-create|recu-edit|recu-delete', ['only' => ['index', 'show']]);
    $this->middleware('permission:recu-create', ['only' => ['create', 'store', 'addItem']]);
    $this->middleware('permission:recu-edit', ['only' => ['edit', 'update']]);
    $this->middleware('permission:recu-delete', ['only' => ['destroy', 'removeItem']]);
    $this->middleware('permission:recu-statut-change', ['only' => ['updateStatut']]);
    $this->middleware('permission:recu-print', ['only' => ['print']]);
    $this->middleware('permission:recu-statistiques', ['only' => ['statistiques']]);
}
    public function index(Request $request)
    {
        $query = RecuUcg::with(['user', 'items.produit']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('statut_paiement')) {
            $query->where('statut_paiement', $request->statut_paiement);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('numero_recu', 'like', "%{$search}%")
                  ->orWhere('client_nom', 'like', "%{$search}%")
                  ->orWhere('client_telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('created_at', [
                $request->date_debut,
                $request->date_fin . ' 23:59:59'
            ]);
        }

        $recus = $query->latest()->paginate(20);

        return view('recus.index', compact('recus'));
    }

    public function create()
    {
        $produits = Produit::where('actif', true)
            ->where('quantite_stock', '>', 0)
            ->orderBy('nom')
            ->get();

        return view('recus.create', compact('produits'));
    }

    /**
     * ✅ VERSION FIXED  - BASITA O KHDAM  100%
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_nom' => 'required|string|max:255',
            'client_prenom' => 'nullable|string|max:255',
            'client_telephone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_adresse' => 'nullable|string',
            'equipement' => 'nullable|string|max:255',
            'details' => 'nullable|string',
'type_garantie' => 'required|in:30_jours,90_jours,180_jours,360_jours,sans_garantie',
            'remise' => 'nullable|numeric|min:0',
            'tva' => 'nullable|numeric|min:0',
            'mode_paiement' => 'required|in:especes,carte,cheque,virement,credit',
            'montant_paye' => 'nullable|numeric|min:0',
            'date_paiement' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.produit_id' => 'required|exists:produits,id',
            'items.*.quantite' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $recu = RecuUcg::create([
                'user_id' => auth()->id(),
                'client_nom' => $validated['client_nom'],
                'client_prenom' => $validated['client_prenom'] ?? null,
                'client_telephone' => $validated['client_telephone'] ?? null,
                'client_email' => $validated['client_email'] ?? null,
                'client_adresse' => $validated['client_adresse'] ?? null,
                'equipement' => $validated['equipement'] ?? null,
                'details' => $validated['details'] ?? null,
                'type_garantie' => $validated['type_garantie'],
                'remise' => $validated['remise'] ?? 0,
                'tva' => $validated['tva'] ?? 0,
                'mode_paiement' => $validated['mode_paiement'],
                'date_paiement' => $validated['date_paiement'] ?? now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            
            foreach ($validated['items'] as $itemData) {
                $produit = Produit::findOrFail($itemData['produit_id']);
                
                // Vérifier stock
                if ($produit->quantite_stock < $itemData['quantite']) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}. Stock disponible: {$produit->quantite_stock}");
                }

                // Créer item (observer ghadi i7asb totaux o inaqs stock)
                $recu->items()->create([
                    'produit_id' => $itemData['produit_id'],
                    'quantite' => $itemData['quantite'],
                ]);
            }
            
            //Refresh reçu bach nakhdo total m7asb
            $recu->refresh();

            // Ajouter paiement (ILA kan chi montant)
            $montantPaye = $validated['montant_paye'] ?? null;
            
            // Ila ma3tach walo, ndiro paiement complet
            if ($montantPaye === null || $montantPaye === '' || $montantPaye == 0) {
                $montantPaye = $recu->total; // Paiement complet
            }

            // Créer paiement o update statut 
            if ($montantPaye > 0) {
                $recu->ajouterPaiement(
                    $montantPaye,
                    $validated['mode_paiement'],
                    null
                );
            }

            DB::commit();

            return redirect()
                ->route('recus.show', $recu)
                ->with('success', "Reçu {$recu->numero_recu} créé avec succès!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', "Erreur lors de la création: " . $e->getMessage());
        }
    }

    public function show(RecuUcg $recu)
    {
        $recu->load([
            'items.produit',
            'paiements.user',
            'stockMovements.produit',
            'user'
        ]);

        return view('recus.show', compact('recu'));
    }

    public function edit(RecuUcg $recu)
    {
        if ($recu->statut !== 'en_cours') {
            return back()->with('error', 'Ce reçu ne peut pas être modifié');
        }

        $produits = Produit::where('actif', true)
            ->orderBy('nom')
            ->get();

        $recu->load('items.produit');

        return view('recus.edit', compact('recu', 'produits'));
    }

    public function update(Request $request, RecuUcg $recu)
    {
        if ($recu->statut !== 'en_cours') {
            return back()->with('error', 'Ce reçu ne peut pas être modifié');
        }

        $validated = $request->validate([
            'client_nom' => 'required|string|max:255',
            'client_prenom' => 'nullable|string|max:255',
            'client_telephone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'client_adresse' => 'nullable|string',
            'equipement' => 'nullable|string|max:255',
            'details' => 'nullable|string',
'type_garantie' => 'required|in:30_jours,90_jours,180_jours,360_jours,sans_garantie',
            'remise' => 'nullable|numeric|min:0',
            'tva' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $recu->update($validated);
            $recu->calculerTotal();

            DB::commit();

            return redirect()
                ->route('recus.show', $recu)
                ->with('success', 'Reçu mis à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function updateStatut(Request $request, RecuUcg $recu)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en_cours,livre,annule,retour'
        ]);

        DB::beginTransaction();
        try {
            if (in_array($validated['statut'], ['annule', 'retour'])) {
                foreach ($recu->items as $item) {
                    $item->delete();
                }
            }

            $recu->update(['statut' => $validated['statut']]);

            DB::commit();

            return back()->with('success', 'Statut mis à jour avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function destroy(RecuUcg $recu)
    {
        DB::beginTransaction();
        try {
            foreach ($recu->items as $item) {
                $item->delete();
            }

            $recu->delete();

            DB::commit();

            return redirect()
                ->route('recus.index')
                ->with('success', 'Reçu supprimé avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function addItem(Request $request, RecuUcg $recu)
    {
        if ($recu->statut !== 'en_cours') {
            return back()->with('error', 'Impossible d\'ajouter des articles à ce reçu');
        }

        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $produit = Produit::find($validated['produit_id']);

            if ($produit->quantite_stock < $validated['quantite']) {
                throw new \Exception("Stock insuffisant pour {$produit->nom}");
            }

            $recu->items()->create($validated);

            DB::commit();

            return back()->with('success', 'Article ajouté avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function removeItem(RecuUcg $recu, $itemId)
    {
        if ($recu->statut !== 'en_cours') {
            return back()->with('error', 'Impossible de supprimer des articles de ce reçu');
        }

        DB::beginTransaction();
        try {
            $item = $recu->items()->findOrFail($itemId);
            $item->delete();

            DB::commit();

            return back()->with('success', 'Article supprimé avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function print(RecuUcg $recu)
    {
        $recu->load(['items.produit', 'paiements', 'user']);
        
        $pdf = Pdf::loadView('recus.print', compact('recu')) ->setPaper('a4', 'portrait');
            return $pdf->stream(); 
    }

    public function statistiques(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth());
        $dateFin = $request->input('date_fin', now()->endOfMonth());

        $stats = [
            'total_recus' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])->count(),
            'total_ventes' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])->sum('total'),
            'total_marges' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->get()
                ->sum(fn($recu) => $recu->margeGlobale()),
            'recus_payes' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->where('statut_paiement', 'paye')
                ->count(),
            'recus_impayes' => RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
                ->where('statut_paiement', 'impaye')
                ->count(),
        ];

        return view('recus.statistiques', compact('stats', 'dateDebut', 'dateFin'));
    }
}