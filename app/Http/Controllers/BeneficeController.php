<?php

namespace App\Http\Controllers;

use App\Models\RecuUcg;
use App\Models\Charge;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BeneficeController extends Controller
{
    public function dashboard(Request $request)
    {
        // Récupérer mois et année (par défaut = mois en cours)
        $mois = $request->input('mois', now()->month);
        $annee = $request->input('annee', now()->year);
        
        // Calculer les dates de début et fin
        $dateDebut = Carbon::create($annee, $mois, 1)->startOfMonth();
        $dateFin = Carbon::create($annee, $mois, 1)->endOfMonth();
        
        // ================== VENTES ==================
        $ventes = RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
            ->where('statut', '!=', 'annule')
            ->get();
        
        $statsVentes = [
            'nombre_recus' => $ventes->count(),
            'total_ventes' => $ventes->sum('total'),
            'total_encaisse' => $ventes->sum('montant_paye'),
            'total_reste' => $ventes->sum('reste'),
            'marge_brute' => $ventes->sum(fn($recu) => $recu->margeGlobale()),
        ];
        
        // ================== CHARGES ==================
        $charges = Charge::entreDates($dateDebut, $dateFin)
            ->paye()
            ->get();
        
        $statsCharges = [
            'nombre_charges' => $charges->count(),
            'total_fixe' => $charges->where('type', 'fixe')->sum('montant'),
            'total_variable' => $charges->where('type', 'variable')->sum('montant'),
            'total_charges' => $charges->sum('montant'),
        ];
        
        // ================== BÉNÉFICE NET ==================
        $benefice = [
            'marge_brute' => $statsVentes['marge_brute'],
            'total_charges' => $statsCharges['total_charges'],
            'benefice_net' => $statsVentes['marge_brute'] - $statsCharges['total_charges'],
            'taux_marge_nette' => $statsVentes['total_ventes'] > 0 
                ? (($statsVentes['marge_brute'] - $statsCharges['total_charges']) / $statsVentes['total_ventes']) * 100 
                : 0,
        ];
        
        // ================== TOP PRODUITS VENDUS ==================
        $topProduits = DB::table('recu_items')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->join('produits', 'recu_items.produit_id', '=', 'produits.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->where('recus_ucgs.statut', '!=', 'annule')
            ->select(
                'produits.nom',
                'produits.reference',
                DB::raw('SUM(recu_items.quantite) as total_quantite'),
                DB::raw('SUM(recu_items.sous_total) as total_ventes'),
                DB::raw('SUM(recu_items.marge_totale) as total_marge')
            )
            ->groupBy('produits.id', 'produits.nom', 'produits.reference')
            ->orderByDesc('total_ventes')
            ->limit(10)
            ->get();
        
        // ================== CHARGES PAR CATÉGORIE ==================
        $chargesParCategorie = Charge::with('category')
            ->entreDates($dateDebut, $dateFin)
            ->paye()
            ->get()
            ->groupBy('charge_category_id')
            ->map(function($charges) {
                return [
                    'nom' => $charges->first()->category?->nom ?? 'Sans catégorie',
                    'couleur' => $charges->first()->category?->couleur ?? '#64748B',
                    'total' => $charges->sum('montant'),
                    'count' => $charges->count(),
                ];
            })
            ->sortByDesc('total');
        
        // ================== ÉVOLUTION JOURNALIÈRE ==================
        $evolutionJournaliere = [];
        $nbJours = $dateFin->day;
        
        for ($jour = 1; $jour <= $nbJours; $jour++) {
            $date = Carbon::create($annee, $mois, $jour);
            
            // Ventes du jour
            $ventesJour = RecuUcg::whereDate('created_at', $date)
                ->where('statut', '!=', 'annule')
                ->get();
            
            // Charges du jour
            $chargesJour = Charge::whereDate('date_charge', $date)
                ->paye()
                ->sum('montant');
            
            $margeJour = $ventesJour->sum(fn($r) => $r->margeGlobale());
            
            $evolutionJournaliere[] = [
                'jour' => $jour,
                'date' => $date->format('d/m'),
                'ventes' => $ventesJour->sum('total'),
                'marge_brute' => $margeJour,
                'charges' => $chargesJour,
                'benefice_net' => $margeJour - $chargesJour,
            ];
        }
        
        // ================== COMPARAISON MOIS PRÉCÉDENT ==================
        $moisPrecedent = Carbon::create($annee, $mois, 1)->subMonth();
        $dateDebutPrecedent = $moisPrecedent->copy()->startOfMonth();
        $dateFinPrecedent = $moisPrecedent->copy()->endOfMonth();
        
        $ventesPrecedent = RecuUcg::whereBetween('created_at', [$dateDebutPrecedent, $dateFinPrecedent])
            ->where('statut', '!=', 'annule')
            ->get();
        
        $chargesPrecedent = Charge::entreDates($dateDebutPrecedent, $dateFinPrecedent)
            ->paye()
            ->sum('montant');
        
        $margePrecedent = $ventesPrecedent->sum(fn($r) => $r->margeGlobale());
        
        $comparaison = [
            'ventes_evolution' => $statsVentes['total_ventes'] > 0 && $ventesPrecedent->sum('total') > 0
                ? (($statsVentes['total_ventes'] - $ventesPrecedent->sum('total')) / $ventesPrecedent->sum('total')) * 100
                : 0,
            'charges_evolution' => $statsCharges['total_charges'] > 0 && $chargesPrecedent > 0
                ? (($statsCharges['total_charges'] - $chargesPrecedent) / $chargesPrecedent) * 100
                : 0,
            'benefice_evolution' => $benefice['benefice_net'] > 0 && ($margePrecedent - $chargesPrecedent) > 0
                ? (($benefice['benefice_net'] - ($margePrecedent - $chargesPrecedent)) / ($margePrecedent - $chargesPrecedent)) * 100
                : 0,
        ];
        
        return view('benefices.dashboard', compact(
            'mois',
            'annee',
            'dateDebut',
            'dateFin',
            'statsVentes',
            'statsCharges',
            'benefice',
            'topProduits',
            'chargesParCategorie',
            'evolutionJournaliere',
            'comparaison'
        ));
    }
}