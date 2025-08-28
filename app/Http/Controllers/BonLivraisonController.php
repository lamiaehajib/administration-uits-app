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
        // Get the search term
        $search = $request->input('search');

        // Apply search and pagination
        $bonLivraisons = BonLivraison::with('items', 'user')
            ->when($search, function ($query, $search) {
                return $query->where('bon_num', 'like', "%{$search}%")
                             ->orWhere('client', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Paginate 10 delivery notes per page

        return view('bon_livraisons.index', compact('bonLivraisons', 'search'));
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

    // Show form to edit a specific delivery note
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