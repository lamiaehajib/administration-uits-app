<?php

namespace App\Http\Controllers;

use App\Models\RecuUcg;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::with(['recuUcg', 'user']);

        if ($request->filled('mode_paiement')) {
            $query->where('mode_paiement', $request->mode_paiement);
        }

        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->whereBetween('date_paiement', [
                $request->date_debut,
                $request->date_fin
            ]);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('recuUcg', function($q) use ($search) {
                $q->where('numero_recu', 'like', "%{$search}%")
                  ->orWhere('client_nom', 'like', "%{$search}%");
            });
        }

        $paiements = $query->latest('date_paiement')->paginate(20);

        $stats = [
            'total_jour' => Paiement::whereDate('date_paiement', now())->sum('montant'),
            'especes_jour' => Paiement::whereDate('date_paiement', now())->especes()->sum('montant'),
            'carte_jour' => Paiement::whereDate('date_paiement', now())->carte()->sum('montant'),
        ];

        return view('paiements.index', compact('paiements', 'stats'));
    }

    /**
     * ✅ MÉTHODE FIXÉE - Ajouter paiement avec calcul correct
     */
    public function store(Request $request, RecuUcg $recu)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric|min:0.01',
            'mode_paiement' => 'required|in:especes,carte,cheque,virement',
            'reference' => 'nullable|string|max:255',
            'date_paiement' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Vérifier que le montant n'excède pas le reste à payer
        if ($validated['montant'] > $recu->reste) {
            return back()->with('error', 'Le montant (' . number_format($validated['montant'], 2) . ' DH) dépasse le reste à payer (' . number_format($recu->reste, 2) . ' DH)');
        }

        DB::beginTransaction();
        try {
            // ✅ UTILISER LA MÉTHODE ajouterPaiement du model
            // Cette méthode gère TOUT automatiquement: création paiement + update statut
            $paiement = $recu->ajouterPaiement(
                $validated['montant'],
                $validated['mode_paiement'],
                $validated['reference'] ?? null
            );

            // Ajouter les notes si présentes
            if (!empty($validated['notes'])) {
                $paiement->update(['notes' => $validated['notes']]);
            }

            // Update date paiement si différente de now()
            if ($validated['date_paiement'] !== date('Y-m-d')) {
                $paiement->update(['date_paiement' => $validated['date_paiement']]);
            }

            DB::commit();

            return back()->with('success', 'Paiement de ' . number_format($validated['montant'], 2) . ' DH enregistré avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['recuUcg.items', 'user']);
        return view('paiements.show', compact('paiement'));
    }

    public function destroy(Paiement $paiement)
    {
        if ($paiement->recuUcg->statut !== 'en_cours') {
            return back()->with('error', 'Impossible de supprimer un paiement d\'un reçu livré/annulé');
        }

        DB::beginTransaction();
        try {
            $montant = $paiement->montant;
            $recu = $paiement->recuUcg;

            // Supprimer le paiement
            $paiement->delete();

            // ✅ Update avec recalcul du statut
            $recu->montant_paye = $recu->montant_paye - $montant;
            $recu->save(); // Le observer updating() va gérer le statut

            DB::commit();

            return back()->with('success', 'Paiement de ' . number_format($montant, 2) . ' DH supprimé avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function rapport(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth());
        $dateFin = $request->input('date_fin', now()->endOfMonth());

        $paiements = Paiement::with(['recuUcg', 'user'])
            ->whereBetween('date_paiement', [$dateDebut, $dateFin])
            ->get();

        $stats = [
            'total' => $paiements->sum('montant'),
            'especes' => $paiements->where('mode_paiement', 'especes')->sum('montant'),
            'carte' => $paiements->where('mode_paiement', 'carte')->sum('montant'),
            'cheque' => $paiements->where('mode_paiement', 'cheque')->sum('montant'),
            'virement' => $paiements->where('mode_paiement', 'virement')->sum('montant'),
            'nombre' => $paiements->count(),
        ];

        return view('paiements.rapport', compact('paiements', 'stats', 'dateDebut', 'dateFin'));
    }
}