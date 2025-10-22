<?php

namespace App\Http\Controllers;

use App\Models\BonLivraison;
use App\Models\BonLivraisonItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;

class BonLivraisonController extends Controller
{
   public function index(Request $request)
{
    // Récupérer les paramètres de recherche et filtrage
    $search = $request->input('search');
    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');
    $clientFilter = $request->input('client_filter');
    $minAmount = $request->input('min_amount');
    $maxAmount = $request->input('max_amount');
    $sortBy = $request->input('sort_by', 'created_at');
    $sortOrder = $request->input('sort_order', 'desc');
    $perPage = $request->input('per_page', 10);
    $tvaFilter = $request->input('tva_filter');
    $userFilter = $request->input('user_filter');

    // Construction de la requête avec eager loading optimisé
    $query = BonLivraison::with(['items', 'user:id,name,email'])
        ->withCount('items') // Compter le nombre d'items
        ->select('bon_livraison.*');

    // Recherche globale (bon_num, client, titre, ref)
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('bon_num', 'like', "%{$search}%")
              ->orWhere('client', 'like', "%{$search}%")
              ->orWhere('titre', 'like', "%{$search}%")
              ->orWhere('ref', 'like', "%{$search}%")
              ->orWhere('tele', 'like', "%{$search}%")
              ->orWhere('ice', 'like', "%{$search}%");
        });
    }

    // Filtre par période (date)
    if ($dateFrom) {
        $query->whereDate('date', '>=', $dateFrom);
    }
    if ($dateTo) {
        $query->whereDate('date', '<=', $dateTo);
    }

    // Filtre par client spécifique
    if ($clientFilter) {
        $query->where('client', $clientFilter);
    }

    // Filtre par montant (total TTC)
    if ($minAmount) {
        $query->where('total_ttc', '>=', $minAmount);
    }
    if ($maxAmount) {
        $query->where('total_ttc', '<=', $maxAmount);
    }

    // Filtre par taux de TVA
    if ($tvaFilter !== null && $tvaFilter !== '') {
        $query->whereRaw('(tva / total_ht * 100) = ?', [$tvaFilter]);
    }

    // Filtre par utilisateur (créateur)
    if ($userFilter) {
        $query->where('user_id', $userFilter);
    }

    // Tri dynamique avec validation
    $allowedSortColumns = ['bon_num', 'date', 'client', 'total_ht', 'total_ttc', 'created_at'];
    if (in_array($sortBy, $allowedSortColumns)) {
        $query->orderBy($sortBy, $sortOrder === 'asc' ? 'asc' : 'desc');
    } else {
        $query->orderBy('created_at', 'desc');
    }

    // Pagination avec validation
    $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
    $bonLivraisons = $query->paginate($perPage)->appends($request->except('page'));

    // Calculs statistiques pour le tableau de bord
    $stats = [
        'total_count' => BonLivraison::count(),
        'total_amount' => BonLivraison::sum('total_ttc'),
        'monthly_count' => BonLivraison::whereMonth('created_at', now()->month)
                                       ->whereYear('created_at', now()->year)
                                       ->count(),
        'monthly_amount' => BonLivraison::whereMonth('created_at', now()->month)
                                         ->whereYear('created_at', now()->year)
                                         ->sum('total_ttc'),
    ];

    // Statistiques filtrées (selon les critères appliqués)
    $filteredStats = [
        'filtered_count' => $bonLivraisons->total(),
        'filtered_amount' => BonLivraison::when($search, function ($q) use ($search) {
                $q->where(function ($subQ) use ($search) {
                    $subQ->where('bon_num', 'like', "%{$search}%")
                         ->orWhere('client', 'like', "%{$search}%")
                         ->orWhere('titre', 'like', "%{$search}%")
                         ->orWhere('ref', 'like', "%{$search}%");
                });
            })
            ->when($dateFrom, fn($q) => $q->whereDate('date', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('date', '<=', $dateTo))
            ->when($clientFilter, fn($q) => $q->where('client', $clientFilter))
            ->when($minAmount, fn($q) => $q->where('total_ttc', '>=', $minAmount))
            ->when($maxAmount, fn($q) => $q->where('total_ttc', '<=', $maxAmount))
            ->when($tvaFilter !== null, fn($q) => $q->whereRaw('(tva / total_ht * 100) = ?', [$tvaFilter]))
            ->when($userFilter, fn($q) => $q->where('user_id', $userFilter))
            ->sum('total_ttc'),
    ];

    // Liste des clients uniques pour le filtre
    $clients = BonLivraison::distinct()
                           ->pluck('client')
                           ->filter()
                           ->sort()
                           ->values();

    // Liste des utilisateurs pour le filtre
    $users = \App\Models\User::select('id', 'name')
                              ->orderBy('name')
                              ->get();

    // Graphique des ventes par mois (6 derniers mois)
    $salesChart = BonLivraison::selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(total_ttc) as total')
                               ->where('date', '>=', now()->subMonths(6))
                               ->groupBy('month')
                               ->orderBy('month')
                               ->get();

    return view('bon_livraisons.index', compact(
        'bonLivraisons',
        'search',
        'dateFrom',
        'dateTo',
        'clientFilter',
        'minAmount',
        'maxAmount',
        'sortBy',
        'sortOrder',
        'perPage',
        'tvaFilter',
        'userFilter',
        'stats',
        'filteredStats',
        'clients',
        'users',
        'salesChart'
    ));
}

    // Show form to create a new delivery note
    public function create()
    {
        return view('bon_livraisons.create');
    }
public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'titre' => 'required|string|max:255',
        'client' => 'required|string|max:255',
        'tele' => 'nullable|string|max:255',
        'ice' => 'nullable|string|max:255',
        'adresse' => 'nullable|string',
        'ref' => 'nullable|string|max:255',
        'libelle' => 'nullable|array',
        'quantite' => 'required|array',
        'quantite.*' => 'numeric|min:0',
        'prix_ht' => 'required|array',
        'prix_ht.*' => 'numeric|min:0',
        'important' => 'nullable|array',
        'important.*' => 'nullable|string|max:255',
        'tva' => 'required|numeric|in:0,20',
    ]);

    // Calculate total HT based on quantities and prices
    $totalHT = 0;
    foreach ($request->quantite as $key => $quantite) {
        $totalHT += (float) $quantite * (float) $request->prix_ht[$key];
    }

    // Calculate TVA and TTC
    $tvaRate = $request->input('tva', 0);
    $tva = $totalHT * ($tvaRate / 100);
    $totalTTC = $totalHT + $tva;

    // Create the main record in the BonLivraison table
    $bonLivraison = BonLivraison::create(array_merge(
        $request->except('libelle', 'quantite', 'prix_ht', 'important', 'bon_num', 'tva'),
        [
            'user_id' => auth()->id(),
            'total_ht' => $totalHT,
            'tva' => $tva,
            'total_ttc' => $totalTTC,
        ]
    ));

    // Generate delivery note number
    $date = now()->addDays(1)->addMonths(1)->addYears(1)->format('ymd');
    $bonLivraison->bon_num = "{$bonLivraison->id}{$date}";
    $bonLivraison->save();

    // Store important information
    if ($request->has('important') && !empty($request->important)) {
        $bonLivraison->important = array_filter($request->important);
        $bonLivraison->save();
    }

    // Store related items using saveMany()
    $items = [];
    if ($request->has('libelle')) {
        foreach ($request->libelle as $key => $libelle) {
            if (!isset($request->quantite[$key]) || !isset($request->prix_ht[$key])) {
                continue;
            }
            $items[] = new BonLivraisonItem([
                'libelle' => $libelle,
                'quantite' => (float) $request->quantite[$key],
                'prix_ht' => (float) $request->prix_ht[$key],
                'prix_total' => (float) $request->quantite[$key] * (float) $request->prix_ht[$key],
            ]);
        }
    }
    
    // Check if there are items to save before calling saveMany()
    if (!empty($items)) {
        $bonLivraison->items()->saveMany($items);
    }
    

    // Redirect to delivery notes index with success message
    return redirect()->route('bon_livraisons.index')->with('success', 'Bon de livraison créé avec succès!');
}

    // Show details of a specific delivery note
    public function show(BonLivraison $bonLivraison)
    {
        $bonLivraison->load(['items', 'user']);
        $pdf = FacadePdf::loadView('bon_livraisons.show', compact('bonLivraison'))->setPaper('a4', 'portrait');
        return $pdf->stream('bon_livraison.pdf');
    }

    
    public function edit(BonLivraison $bonLivraison)
    {
        return view('bon_livraisons.edit', compact('bonLivraison'));
    }

    // Update a specific delivery note
    public function update(Request $request, BonLivraison $bonLivraison)
{
    // Validate input data
    $request->validate([
        'bon_num' => 'required|string|max:255',
        'date' => 'required|date',
        'titre' => 'required|string|max:255',
        'client' => 'required|string|max:255',
        'tele' => 'nullable|string|max:255',
        'ice' => 'nullable|string|max:255',
        'adresse' => 'nullable|string',
        'ref' => 'nullable|string|max:255',
        'libelle' => 'nullable|array',
        'quantite' => 'required|array',
        'prix_ht' => 'required|array',
        'important' => 'nullable|array',
        'tva' => 'required|numeric|in:0,20',
    ]);

    // Prepare data for updating
    $data = $request->except('libelle', 'quantite', 'prix_ht', 'prix_total', 'important');

    // Calculate total HT
    $totalHT = 0;
    foreach ($request->quantite as $key => $quantite) {
        $totalHT += $quantite * $request->prix_ht[$key];
    }

    // Calculate TVA and TTC
    $tvaRate = $request->input('tva', 0);
    $tva = $totalHT * ($tvaRate / 100);
    $totalTTC = $totalHT + $tva;

    // Update data in BonLivraison table
    $data['total_ht'] = $totalHT;
    $data['tva'] = $tva;
    $data['total_ttc'] = $totalTTC;
    $data['important'] = $request->has('important') ? array_filter($request->important) : [];

    $bonLivraison->update($data);

    // Update or add items using the relationship
    // First, delete all existing items for this delivery note
    $bonLivraison->items()->delete();

    // Then, create and save the new/updated items
    if (!empty($request->libelle)) {
        $items = [];
        foreach ($request->libelle as $key => $libelle) {
            $items[] = new BonLivraisonItem([
                'libelle' => $libelle,
                'quantite' => $request->quantite[$key],
                'prix_ht' => $request->prix_ht[$key],
                'prix_total' => $request->quantite[$key] * $request->prix_ht[$key],
                'bon_livraison_id' => $bonLivraison->id,
            ]);
        }
        $bonLivraison->items()->saveMany($items);
    }

    // Redirect to delivery notes index with success message
    return redirect()->route('bon_livraisons.index')->with('success', 'Bon de livraison mis à jour avec succès!');
}

    // Delete a specific delivery note
    public function destroy(BonLivraison $bonLivraison)
    {
        $bonLivraison->delete();
        return redirect()->route('bon_livraisons.index')->with('success', 'Bon de livraison supprimé avec succès!');
    }

    public function downloadPDF($id)
    {
        // Retrieve the delivery note with its items
        $bonLivraison = BonLivraison::with('items', 'user')->find($id);

        if (!$bonLivraison) {
            return redirect()->route('bon_livraisons.index')->with('error', 'Bon de livraison non trouvé!');
        }

        // Pass data to PDF view
        $pdf = FacadePdf::loadView('bon_livraisons.pdf', compact('bonLivraison'));
        $clientName = $bonLivraison->client ?? 'client';
        $titre = $bonLivraison->titre ?? 'titre';
        $total_ttc = $bonLivraison->total_ttc ?? 'total_ttc';

        // Download the PDF
        return $pdf->download('bon_livraison_' . $bonLivraison->bon_num . '_' . $clientName . '_' . $titre . '_' . $total_ttc . '.pdf');
    }
}