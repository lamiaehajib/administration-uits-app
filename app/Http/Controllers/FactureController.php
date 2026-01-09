<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Facture;
use App\Models\FactureItem;
use App\Models\Produit;
use App\Models\Category;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class FactureController extends Controller
{
    
// ✅ Ajoutez cet import en haut du fichier si ce n'est pas déjà fait:
// use App\Models\FactureItem;

public function index(Request $request)
{
    $query = Facture::with(['items.produit', 'importantInfoo', 'user']);

    // ✅ 1. NOUVEAU: Filtrage par Type (Service/Produit)
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // 2. Recherche Multi-Critères
    if ($search = $request->input('search')) {
        $query->where(function($q) use ($search) {
            $q->where('facture_num', 'like', "%{$search}%")
              ->orWhere('client', 'like', "%{$search}%")
              ->orWhere('titre', 'like', "%{$search}%")
              ->orWhere('ice', 'like', "%{$search}%")
              ->orWhere('ref', 'like', "%{$search}%")
              ->orWhere('adresse', 'like', "%{$search}%");
        });
    }

    // 3. Filtrage par Date
    if ($request->filled('date_debut')) {
        $query->whereDate('date', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->whereDate('date', '<=', $request->date_fin);
    }

    // 4. Filtrage par Période Rapide
    if ($periode = $request->input('periode')) {
        switch ($periode) {
            case 'aujourdhui':
                $query->whereDate('date', today());
                break;
            case 'cette_semaine':
                $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'ce_mois':
                $query->whereMonth('date', now()->month)
                      ->whereYear('date', now()->year);
                break;
            case 'ce_trimestre':
                $query->whereBetween('date', [now()->firstOfQuarter(), now()->lastOfQuarter()]);
                break;
            case 'cette_annee':
                $query->whereYear('date', now()->year);
                break;
        }
    }

    // 5. Filtrage par Montant
    if ($request->filled('montant_min')) {
        $query->where('total_ttc', '>=', $request->montant_min);
    }
    if ($request->filled('montant_max')) {
        $query->where('total_ttc', '<=', $request->montant_max);
    }

    // 6. Filtrage par Devise
    if ($request->filled('currency')) {
        $query->where('currency', $request->currency);
    }

    // 7. Tri Dynamique
    $sortBy = $request->input('sort_by', 'created_at');
    $sortOrder = $request->input('sort_order', 'desc');
    
    $allowedSorts = ['facture_num', 'date', 'client', 'total_ht', 'total_ttc', 'created_at', 'tva'];
    if (in_array($sortBy, $allowedSorts)) {
        $query->orderBy($sortBy, $sortOrder);
    }

    // ✅ 8. STATISTIQUES GLOBALES (avec détails par type)
    $stats = [
        'total_factures' => (clone $query)->count(),
        'total_montant_ht' => (clone $query)->sum('total_ht'),
        'total_montant_ttc' => (clone $query)->sum('total_ttc'),
        'total_tva' => (clone $query)->sum('tva'),
        'montant_moyen' => (clone $query)->avg('total_ttc'),
        'factures_ce_mois' => (clone $query)->whereMonth('date', now()->month)
                                            ->whereYear('date', now()->year)
                                            ->count(),
        
        // ✅ NOUVEAU: Stats par type
        'factures_services' => Facture::where('type', 'service')->count(),
        'factures_produits' => Facture::where('type', 'produit')->count(),
        'montant_services' => Facture::where('type', 'service')->sum('total_ttc'),
        'montant_produits' => Facture::where('type', 'produit')->sum('total_ttc'),
    ];

    // ✅ 9. NOUVEAU: Statistiques détaillées par type
    $statsByType = Facture::selectRaw('
            type,
            COUNT(*) as count,
            SUM(total_ht) as total_ht,
            SUM(total_ttc) as total_ttc,
            SUM(tva) as total_tva,
            AVG(total_ttc) as moyenne_ttc
        ')
        ->groupBy('type')
        ->get()
        ->keyBy('type');

    // 10. Statistiques par Devise
    $statsByDevise = Facture::selectRaw('
            currency,
            COUNT(*) as count,
            SUM(total_ht) as total_ht,
            SUM(total_ttc) as total_ttc,
            SUM(tva) as total_tva
        ')
        ->groupBy('currency')
        ->get()
        ->keyBy('currency');

    // 11. Top 10 Clients
    $topClients = Facture::selectRaw('
            client,
            ice,
            COUNT(*) as nb_factures,
            SUM(total_ht) as total_ht,
            SUM(total_ttc) as total_ttc,
            SUM(tva) as total_tva
        ')
        ->groupBy('client', 'ice')
        ->orderBy('total_ttc', 'desc')
        ->limit(10)
        ->get();

    // ✅ 12. NOUVEAU: Top Produits Vendus (pour factures de type produit)
    $topProduits = FactureItem::whereHas('facture', function($q) {
            $q->where('type', 'produit');
        })
        ->with('produit')
        ->selectRaw('
            produit_id,
            SUM(quantite) as total_quantite,
            SUM(prix_total) as total_ventes,
            SUM(marge_totale) as total_marge,
            COUNT(DISTINCT factures_id) as nb_factures
        ')
        ->whereNotNull('produit_id')
        ->groupBy('produit_id')
        ->orderBy('total_ventes', 'desc')
        ->limit(10)
        ->get();

    // ✅ 13. NOUVEAU: Statistiques de Marges (pour produits uniquement)
    $statsMarges = FactureItem::whereHas('facture', function($q) {
            $q->where('type', 'produit');
        })
        ->selectRaw('
            SUM(marge_totale) as marge_totale,
            AVG(marge_unitaire) as marge_moyenne,
            SUM(prix_total) as ca_total,
            SUM(prix_achat * quantite) as cout_total
        ')
        ->first();

    // Calcul du taux de marge global
    $tauxMarge = $statsMarges && $statsMarges->ca_total > 0
        ? (($statsMarges->marge_totale / $statsMarges->ca_total) * 100)
        : 0;

    // 14. Évolution mensuelle (12 derniers mois)
    $evolutionMensuelle = Facture::selectRaw('
            YEAR(date) as annee,
            MONTH(date) as mois,
            COUNT(*) as nombre,
            SUM(total_ht) as montant_ht,
            SUM(total_ttc) as montant_ttc,
            SUM(tva) as montant_tva
        ')
        ->where('date', '>=', now()->subMonths(12))
        ->groupBy('annee', 'mois')
        ->orderBy('annee', 'asc')
        ->orderBy('mois', 'asc')
        ->get();

    // ✅ 15. NOUVEAU: Évolution par type (Services vs Produits)
    $evolutionParType = Facture::selectRaw('
            YEAR(date) as annee,
            MONTH(date) as mois,
            type,
            COUNT(*) as nombre,
            SUM(total_ttc) as montant_ttc
        ')
        ->where('date', '>=', now()->subMonths(6))
        ->groupBy('annee', 'mois', 'type')
        ->orderBy('annee', 'asc')
        ->orderBy('mois', 'asc')
        ->get()
        ->groupBy(function($item) {
            return $item->annee . '-' . str_pad($item->mois, 2, '0', STR_PAD_LEFT);
        });

    // 16. Liste des Clients Uniques
    $clientsList = Facture::distinct('client')
        ->orderBy('client')
        ->pluck('client');

    // 17. Export
    if ($request->input('export') === 'csv') {
        return $this->exportCSV($query->get());
    }

    // 18. Pagination
    $perPage = $request->input('per_page', 10);
    $perPage = in_array($perPage, [10, 25, 50, 100, 200]) ? $perPage : 10;
    $factures = $query->paginate($perPage)->appends($request->except('page'));

    // 19. Données pour Graphiques
    $chartData = [
        'labels' => $evolutionMensuelle->map(function($item) {
            return Carbon::create($item->annee, $item->mois)->format('M Y');
        }),
        'montants_ht' => $evolutionMensuelle->pluck('montant_ht'),
        'montants_ttc' => $evolutionMensuelle->pluck('montant_ttc'),
        'montants_tva' => $evolutionMensuelle->pluck('montant_tva'),
        'nombres' => $evolutionMensuelle->pluck('nombre'),
    ];

    return view('factures.index', compact(
        'factures',
        'stats',
        'statsByType',
        'statsByDevise',
        'topClients',
        'topProduits',
        'statsMarges',
        'tauxMarge',
        'clientsList',
        'chartData',
        'evolutionMensuelle',
        'evolutionParType'
    ));
}
// Fonction auxiliaire pour Export Excel
private function exportExcel($factures)
{
    // À implémenter avec PhpSpreadsheet ou Laravel Excel
    // return Excel::download(new FactureExport($factures), 'factures_projet.xlsx');
}

// Fonction auxiliaire pour Export CSV
 private function exportCSV($factures)
    {
        $filename = 'factures_' . now()->format('Y-m-d_H-i-s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($factures) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, [
                'N° Facture', 'Type', 'Date', 'Client', 'Titre', 
                'Montant HT', 'TVA', 'Montant TTC', 'Devise'
            ], ';');

            foreach ($factures as $facture) {
                fputcsv($file, [
                    $facture->facture_num,
                    ucfirst($facture->type),
                    $facture->date,
                    $facture->client,
                    $facture->titre,
                    number_format($facture->total_ht, 2, ',', ' '),
                    number_format($facture->tva, 2, ',', ' '),
                    number_format($facture->total_ttc, 2, ',', ' '),
                    $facture->currency,
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    


    public function duplicate(Facture $facture)
    {
        // Clone the existing facture
        $newFacture = $facture->replicate();
        
        $newFacture->facture_num = null; // Reset facture_num to generate a new one
        $newFacture->created_at = now();
        $newFacture->updated_at = now();
        
        // ✨ MODIFICATION CLÉ : Mettre à jour le user_id avec l'ID de l'utilisateur qui duplique
        $newFacture->user_id = Auth::id(); // Assignation de l'ID de l'utilisateur authentifié
        
        $newFacture->save();

        // Generate a new facture_num
        $date = now()->addDays(1)->addMonths(1)->addYears(1)->format('ymd'); // Same logic as in store
        $newFacture->facture_num = "{$newFacture->id}{$date}";
        $newFacture->save();

        // Duplicate related items
        foreach ($facture->items as $item) {
            FactureItem::create([
                'factures_id' => $newFacture->id,
                'libele' => $item->libele,
                'quantite' => $item->quantite,
                'prix_ht' => $item->prix_ht,
                'prix_total' => $item->prix_total,
            ]);
        }

        // Duplicate important infos
        foreach ($facture->importantInfoo as $info) {
            $newFacture->importantInfoo()->create(['info' => $info->info]);
        }

        return redirect()->route('factures.index')->with('success', 'Facture dupliquée avec succès!');
    }

    public function createFromDevis(Devis $devis)
{
    $devis->load(['items', 'importantInfos']);
    return view('factures.create', compact('devis'));
}

    // عرض نموذج لإنشاء عرض جديد
    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('nom')->get();
        return view('factures.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:service,produit',
            'date' => 'required|date',
            'titre' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'ice' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'ref' => 'nullable|string|max:255',
            'currency' => 'required|in:DH,EUR,CFA',
            'tva' => 'required|numeric|in:0,20',
            'afficher_cachet' => 'nullable|boolean',
            'important' => 'nullable|array',
        ]);

        DB::beginTransaction();
        
        try {
            $totalHT = 0;
            $items = [];

            // Type Service
            if ($request->type === 'service') {
                $request->validate([
                    'libele' => 'required|array',
                    'quantite' => 'required|array',
                    'prix_ht' => 'required|array',
                ]);

                foreach ($request->quantite as $key => $quantite) {
                    if (!isset($request->libele[$key]) || !isset($request->prix_ht[$key])) {
                        continue;
                    }
                    
                    $sousTotal = (float) $quantite * (float) $request->prix_ht[$key];
                    $totalHT += $sousTotal;
                    
                    $items[] = [
                        'libele' => $request->libele[$key],
                        'quantite' => (float) $quantite,
                        'prix_ht' => (float) $request->prix_ht[$key],
                        'prix_total' => $sousTotal,
                        'produit_id' => null,
                        'prix_achat' => null,
                        'marge_unitaire' => null,
                        'marge_totale' => null,
                    ];
                }
            } 
            // Type Produit
            else {
                $request->validate([
                    'produit_id' => 'required|array',
                    'produit_id.*' => 'required|exists:produits,id',
                    'quantite' => 'required|array',
                    'prix_ht' => 'required|array',
                ]);

                foreach ($request->produit_id as $key => $produitId) {
                    $produit = Produit::findOrFail($produitId);
                    $quantite = (float) $request->quantite[$key];
                    $prixVente = (float) $request->prix_ht[$key];
                    
                    // Vérifier stock
                    if ($produit->quantite_stock < $quantite) {
                        throw new \Exception("Stock insuffisant pour {$produit->nom}. Stock disponible: {$produit->quantite_stock}");
                    }
                    
                    $sousTotal = $quantite * $prixVente;
                    $margeUnitaire = $prixVente - $produit->prix_achat;
                    $margeTotale = $margeUnitaire * $quantite;
                    
                    $totalHT += $sousTotal;
                    
                    $items[] = [
                        'produit_id' => $produitId,
                        'libele' => $produit->nom,
                        'quantite' => $quantite,
                        'prix_ht' => $prixVente,
                        'prix_achat' => $produit->prix_achat,
                        'marge_unitaire' => $margeUnitaire,
                        'marge_totale' => $margeTotale,
                        'prix_total' => $sousTotal,
                    ];
                    
                    // Déduire du stock
                    $stockAvant = $produit->quantite_stock;
                    $produit->decrement('quantite_stock', $quantite);
                    $produit->increment('total_vendu', $quantite);
                }
            }

            // Calcul TVA et TTC
            $tvaRate = $request->input('tva', 0);
            $tva = $totalHT * ($tvaRate / 100);
            $totalTTC = $totalHT + $tva;

            // Créer la facture
            $facture = Facture::create(array_merge(
                $request->except('libele', 'quantite', 'prix_ht', 'important', 'facture_num', 'tva', 'produit_id'),
                [
                    'user_id' => auth()->id(),
                    'type' => $request->type,
                    'total_ht' => $totalHT,
                    'tva' => $tva,
                    'total_ttc' => $totalTTC,
                    'afficher_cachet' => $request->input('afficher_cachet', 0),
                ]
            ));

            // Générer numéro facture
            $date = now()->format('ymd');
            $facture->facture_num = "{$facture->id}{$date}";
            $facture->save();

            // Enregistrer les items
            foreach ($items as $item) {
                $item['factures_id'] = $facture->id;
                FactureItem::create($item);
                
                // Enregistrer mouvement de stock (seulement pour produits)
                if ($request->type === 'produit' && $item['produit_id']) {
                    $produit = Produit::find($item['produit_id']);
                    StockMovement::create([
                        'produit_id' => $item['produit_id'],
                        'user_id' => auth()->id(),
                        'type' => 'sortie',
                        'quantite' => $item['quantite'],
                        'stock_avant' => $produit->quantite_stock + $item['quantite'],
                        'stock_apres' => $produit->quantite_stock,
                        'reference' => $facture->facture_num,
                        'motif' => "Vente - Facture #{$facture->facture_num}"
                    ]);
                }
            }

            // Infos importantes
            if ($request->has('important') && !empty($request->important)) {
                $facture->importantInfoo()->createMany(array_map(function ($info) {
                    return ['info' => $info];
                }, array_filter($request->important)));
            }

            DB::commit();
            return redirect()->route('factures.index')->with('success', 'Facture créée avec succès!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }


     public function getProduitsByCategory($categoryId)
{
    $produits = Produit::where('category_id', $categoryId)
        ->where('actif', true)
        ->where('quantite_stock', '>', 0)
        ->select('id', 'nom', 'reference', 'prix_vente', 'prix_achat', 'quantite_stock')
        ->get()
        ->map(function($produit) {
            return [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'reference' => $produit->reference,
                'prix_vente' => (float) $produit->prix_vente,
                'prix_achat' => (float) $produit->prix_achat,
                'quantite_stock' => (int) $produit->quantite_stock,
            ];
        });

    return response()->json($produits);
}


    

    // عرض تفاصيل عرض معين
    public function show(Facture $facture)
    {
        $facture->load(['items', 'importantInfoo']);
        $pdf = FacadePdf::loadView('factures.show', compact('facture'))->setPaper('a4', 'portrait');
        return $pdf->stream('factures.pdf'); 
       
    }


  

    // عرض نموذج لتعديل عرض معين
    public function edit(Facture $facture)
{
    // Charger les relations nécessaires
    $facture->load(['items', 'importantInfoo']);
    
    // Charger les catégories (pour produits)
    $categories = Category::with('children')
        ->whereNull('parent_id')
        ->orderBy('nom')
        ->get();
    
    return view('factures.edit', compact('facture', 'categories'));
}

public function update(Request $request, Facture $facture)
{
    $request->validate([
        'type' => 'required|in:service,produit',
        'facture_num' => 'required|string|max:255',
        'date' => 'required|date',
        'titre' => 'required|string|max:255',
        'client' => 'required|string|max:255',
        'ice' => 'nullable|string|max:255',
        'adresse' => 'nullable|string',
        'ref' => 'nullable|string|max:255',
        'currency' => 'required|in:DH,EUR,CFA',
        'tva' => 'required|numeric|in:0,20',
        'afficher_cachet' => 'nullable|boolean',
        'important' => 'nullable|array',
    ]);

    DB::beginTransaction();
    
    try {
        $totalHT = 0;
        $items = [];
        
        // 🔹 ANCIEN TYPE vs NOUVEAU TYPE (pour gérer le stock)
        $ancienType = $facture->type;
        $nouveauType = $request->type;

        // ✅ TYPE SERVICE
        if ($request->type === 'service') {
            $request->validate([
                'libele' => 'required|array',
                'quantite' => 'required|array',
                'prix_ht' => 'required|array',
            ]);

            foreach ($request->quantite as $key => $quantite) {
                if (!isset($request->libele[$key]) || !isset($request->prix_ht[$key])) {
                    continue;
                }
                
                $sousTotal = (float) $quantite * (float) $request->prix_ht[$key];
                $totalHT += $sousTotal;
                
                $items[] = [
                    'libele' => $request->libele[$key],
                    'quantite' => (float) $quantite,
                    'prix_ht' => (float) $request->prix_ht[$key],
                    'prix_total' => $sousTotal,
                    'produit_id' => null,
                    'prix_achat' => null,
                    'marge_unitaire' => null,
                    'marge_totale' => null,
                ];
            }
        } 
        // ✅ TYPE PRODUIT
        else {
            $request->validate([
                'produit_id' => 'required|array',
                'produit_id.*' => 'required|exists:produits,id',
                'quantite' => 'required|array',
                'prix_ht' => 'required|array',
            ]);

            foreach ($request->produit_id as $key => $produitId) {
                $produit = Produit::findOrFail($produitId);
                $quantite = (float) $request->quantite[$key];
                $prixVente = (float) $request->prix_ht[$key];
                
                // 🔹 Récupérer l'ancienne quantité vendue (si même produit)
                $ancienItem = $facture->items->where('produit_id', $produitId)->first();
                $ancienneQuantite = $ancienItem ? $ancienItem->quantite : 0;
                
                // Calculer la différence de stock
                $diffQuantite = $quantite - $ancienneQuantite;
                
                // Vérifier stock disponible
                if ($diffQuantite > 0 && $produit->quantite_stock < $diffQuantite) {
                    throw new \Exception("Stock insuffisant pour {$produit->nom}. Stock disponible: {$produit->quantite_stock}, Besoin: {$diffQuantite}");
                }
                
                $sousTotal = $quantite * $prixVente;
                $margeUnitaire = $prixVente - $produit->prix_achat;
                $margeTotale = $margeUnitaire * $quantite;
                
                $totalHT += $sousTotal;
                
                $items[] = [
                    'produit_id' => $produitId,
                    'libele' => $produit->nom,
                    'quantite' => $quantite,
                    'prix_ht' => $prixVente,
                    'prix_achat' => $produit->prix_achat,
                    'marge_unitaire' => $margeUnitaire,
                    'marge_totale' => $margeTotale,
                    'prix_total' => $sousTotal,
                    'diff_quantite' => $diffQuantite, // Pour le mouvement de stock
                ];
            }
        }

        // ✅ Calcul TVA et TTC
        $tvaRate = $request->input('tva', 0);
        $tva = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $tva;

        // ✅ Mettre à jour la facture
        $facture->update(array_merge(
            $request->except('libele', 'quantite', 'prix_ht', 'important', 'tva', 'produit_id'),
            [
                'type' => $request->type,
                'total_ht' => $totalHT,
                'tva' => $tva,
                'total_ttc' => $totalTTC,
                'afficher_cachet' => $request->input('afficher_cachet', 0),
            ]
        ));

        // ✅ 🔹 GESTION DU CHANGEMENT DE TYPE (Service ↔ Produit)
        if ($ancienType === 'produit' && $nouveauType === 'service') {
            // RESTAURER le stock des anciens produits
            foreach ($facture->items as $oldItem) {
                if ($oldItem->produit_id) {
                    $produit = Produit::find($oldItem->produit_id);
                    if ($produit) {
                        $stockAvant = $produit->quantite_stock;
                        $produit->increment('quantite_stock', $oldItem->quantite);
                        $produit->decrement('total_vendu', $oldItem->quantite);
                        
                        StockMovement::create([
                            'produit_id' => $oldItem->produit_id,
                            'user_id' => auth()->id(),
                            'type' => 'entree',
                            'quantite' => $oldItem->quantite,
                            'stock_avant' => $stockAvant,
                            'stock_apres' => $produit->quantite_stock,
                            'reference' => $facture->facture_num,
                            'motif' => "Annulation vente - Modification facture #{$facture->facture_num}"
                        ]);
                    }
                }
            }
        }

        // ✅ Supprimer les anciens items
        $facture->items()->delete();

        // ✅ Enregistrer les nouveaux items
        foreach ($items as $item) {
            $item['factures_id'] = $facture->id;
            $diffQuantite = $item['diff_quantite'] ?? 0;
            unset($item['diff_quantite']);
            
            FactureItem::create($item);
            
            // ✅ Enregistrer mouvement de stock (seulement pour produits)
            if ($request->type === 'produit' && $item['produit_id']) {
                $produit = Produit::find($item['produit_id']);
                
                if ($diffQuantite != 0) {
                    $stockAvant = $produit->quantite_stock;
                    
                    if ($diffQuantite > 0) {
                        // Augmentation de vente → Retirer du stock
                        $produit->decrement('quantite_stock', $diffQuantite);
                        $produit->increment('total_vendu', $diffQuantite);
                        
                        StockMovement::create([
                            'produit_id' => $item['produit_id'],
                            'user_id' => auth()->id(),
                            'type' => 'sortie',
                            'quantite' => $diffQuantite,
                            'stock_avant' => $stockAvant,
                            'stock_apres' => $produit->quantite_stock,
                            'reference' => $facture->facture_num,
                            'motif' => "Modification vente - Facture #{$facture->facture_num}"
                        ]);
                    } else {
                        // Diminution de vente → Remettre en stock
                        $quantiteAjouter = abs($diffQuantite);
                        $produit->increment('quantite_stock', $quantiteAjouter);
                        $produit->decrement('total_vendu', $quantiteAjouter);
                        
                        StockMovement::create([
                            'produit_id' => $item['produit_id'],
                            'user_id' => auth()->id(),
                            'type' => 'entree',
                            'quantite' => $quantiteAjouter,
                            'stock_avant' => $stockAvant,
                            'stock_apres' => $produit->quantite_stock,
                            'reference' => $facture->facture_num,
                            'motif' => "Retour stock - Modification facture #{$facture->facture_num}"
                        ]);
                    }
                }
            }
        }

        // ✅ Infos importantes
        $facture->importantInfoo()->delete();
        if ($request->has('important') && !empty($request->important)) {
            $facture->importantInfoo()->createMany(array_map(function ($info) {
                return ['info' => $info];
            }, array_filter($request->important)));
        }

        DB::commit();
        return redirect()->route('factures.index')->with('success', 'Facture mise à jour avec succès!');
        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Erreur: ' . $e->getMessage());
    }
}
    

    // حذف عرض معين
    public function destroy(Facture $facture)
    {
        $facture->delete();
        return redirect()->route('factures.index')->with('success', 'facture supprimé avec succès!');
    }

    public function downloadPDF($id)
{
    // استرجاع الـ Devis مع منتجاته
    $facture =Facture::with('items' , 'importantInfoo')->find($id);

    if (!$facture) {
        // إذا لم يتم العثور على الـ Devis
        return redirect()->route('factures.index')->with('error', 'facture non trouvé!');
    }

    // تمرير البيانات إلى ملف PDF
    $pdf = FacadePdf::loadView('factures.pdf', compact('facture'));
    $clientName = $facture->client ?? 'client'; 
    $titre = $facture->titre ?? 'titre';
    $total_ttc = $facture->total_ttc ?? 'total_ttc';
    
    
    return $pdf->download('facture_P_' . $facture->facture_num . '_' . $clientName . '_' . $titre . '_' . $total_ttc . '.pdf');
    
    // تحميل الـ PDF
    
}

public function corbeille()
{
    // Kanst3amlo onlyTrashed() bach njebdo GHI les factures li mamsou7in
    $factures = Facture::onlyTrashed()
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('factures.corbeille', compact('factures'));
}

// N°2. Restauration d'une Facture (I3ada l'Hayat)
public function restore($id)
{
    // Kanjebdo l-facture b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
    $facture = Facture::withTrashed()->findOrFail($id);
    $facture->restore();

    return redirect()->route('factures.corbeille')->with('success', 'Facture restaurée avec succès!');
}

// N°3. Suppression Définitive (Mass7 Nnéha'i)
public function forceDelete($id)
{
    // Kanjebdo l-facture b ID men l'Corbeille w kan3ayto 3la forceDelete()
    $facture = Facture::withTrashed()->findOrFail($id);
    $facture->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

    return redirect()->route('factures.corbeille')->with('success', 'Facture supprimée définitivement!');
}


}
