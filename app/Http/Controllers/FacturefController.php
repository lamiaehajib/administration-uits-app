<?php

namespace App\Http\Controllers;

use App\Models\Devisf;
use App\Models\Facturef;
use App\Models\FacturefItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;

class FacturefController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer le terme de recherche
        $search = $request->input('search');
    
        // Appliquer la recherche et pagination
        $facturefs = Facturef::with('items', 'importantInfo')
            ->when($search, function ($query, $search) {
                return $query->where('facturef_num', 'like', "%{$search}%")
                             ->orWhere('client', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc') 
            ->paginate(10); // Pagination de 10 factures par page
    
        return view('facturefs.index', compact('facturefs', 'search'));
    }


    public function duplicate(Facturef $facturef)
    {
        // Clone the existing facturef
        $newFacturef = $facturef->replicate();
        $newFacturef->facturef_num = null; // Reset facturef_num to generate a new one
        $newFacturef->created_at = now();
        $newFacturef->updated_at = now();
        $newFacturef->save();

        // Generate a new facturef_num
        $date = Carbon::now()->format('dmy'); // Same logic as in store
        $newFacturef->facturef_num = "{$newFacturef->id}{$date}";
        $newFacturef->save();

        // Duplicate related items
        foreach ($facturef->items as $item) {
            FacturefItem::create([
                'facturefs_id' => $newFacturef->id,
                'libelle' => $item->libelle,
                'duree' => $item->duree,
                'nombre_collaborateurs' => $item->nombre_collaborateurs,
                'nombre_jours' => $item->nombre_jours,
                'prix_ht' => $item->prix_ht,
                'prix_total' => $item->prix_total,
            ]);
        }

        // Duplicate important infos
        foreach ($facturef->importantInfo as $info) {
            $newFacturef->importantInfo()->create(['info' => $info->info]);
        }

        return redirect()->route('facturefs.index')->with('success', 'Facture de formation dupliquée avec succès!');
    }
    public function createFromDevisf(Devisf $devisf)
{
    $devisf->load(['items', 'ImportantInfof']);
    return view('facturefs.create', compact('devisf'));
}

    public function create()
    {
        return view('facturefs.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'facturef_num' => 'required|string|max:255',
            'date' => 'required|date',
            'titre' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'tele' => 'required|string|max:255',
            'ice' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'vide' => 'nullable|string',
            'ref' => 'nullable|string|max:255',
            'libelle' => 'required|array',
            'libelle.*' => 'required|string|max:255',
            'type' => 'required|array',
            'type.*' => 'required|in:duree,nombre_collaborateurs,nombre_jours',
            'duree' => 'nullable|array',
            'duree.*' => 'nullable|string|max:255',
            'nombre_collaborateurs' => 'nullable|array',
            'nombre_collaborateurs.*' => 'nullable|integer|min:0',
            'nombre_jours' => 'nullable|array',
            'nombre_jours.*' => 'nullable|integer|min:0',
            'prix_ht' => 'required|array',
            'prix_ht.*' => 'required|numeric|min:0',
            'important' => 'nullable|array',
            'important.*' => 'nullable|string|max:255',
            'afficher_cachet' => 'nullable|boolean',
            'currency' => 'required|in:DH,EUR',
            'tva' => 'required|numeric|in:0,20',
        ]);

        // Calculate total HT
        $totalHT = 0;
        foreach ($request->libelle as $key => $libelle) {
            if (!isset($request->type[$key], $request->prix_ht[$key])) {
                continue; // Skip if required fields are missing
            }

            $type = $request->type[$key];
            $unitPrice = (float) $request->prix_ht[$key];

            if ($type === 'nombre_collaborateurs') {
                $qty = (int) ($request->nombre_collaborateurs[$key] ?? 0);
                $rowTotal = $unitPrice * $qty;
            } elseif ($type === 'nombre_jours') {
                $qty = (int) ($request->nombre_jours[$key] ?? 0);
                $rowTotal = $unitPrice * $qty;
            } else { // duree
                $rowTotal = $unitPrice;
            }

            $totalHT += $rowTotal;
        }

        // Calculate TVA and total TTC
        $tvaRate = $request->input('tva', 0);
        $tva = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $tva;

        // Create Facturef
        $facturef = Facturef::create([
            'date' => $request->date,
            'titre' => $request->titre,
            'client' => $request->client,
            'tele' => $request->tele,
            'ice' => $request->ice,
            'adresse' => $request->adresse,
            'vide' => $request->vide,
            'ref' => $request->ref,
            'total_ht' => $totalHT,
            'tva' => $tva,
            'total_ttc' => $totalTTC,
            'user_id' => auth()->id(),
            'currency' => $request->currency,
            'afficher_cachet' => $request->input('afficher_cachet', 0),
        ]);

        // Generate facture number
        $facturef->facturef_num = $facturef->id . Carbon::now()->format('dmy');
        $facturef->save();

        // Store items
        $items = [];
        foreach ($request->libelle as $key => $libelle) {
            if (!isset($request->type[$key], $request->prix_ht[$key])) {
                continue; // Skip if required fields are missing
            }

            $type = $request->type[$key];
            $unitPrice = (float) $request->prix_ht[$key];
            $duree = $request->duree[$key] ?? null;
            $nombre_collaborateurs = $request->nombre_collaborateurs[$key] ?? null;
            $nombre_jours = $request->nombre_jours[$key] ?? null;

            $prixTotal = $type === 'nombre_collaborateurs'
                ? $unitPrice * (int) ($nombre_collaborateurs ?? 0)
                : ($type === 'nombre_jours'
                    ? $unitPrice * (int) ($nombre_jours ?? 0)
                    : $unitPrice);

            $items[] = [
                'facturefs_id' => $facturef->id,
                'libelle' => $libelle,
                'duree' => $duree,
                'nombre_collaborateurs' => $nombre_collaborateurs,
                'nombre_jours' => $nombre_jours,
                'prix_ht' => $unitPrice,
                'prix_total' => $prixTotal,
            ];
        }

        if (!empty($items)) {
            FacturefItem::insert($items);
        }

        // Store important information
        if ($request->has('important') && !empty($request->important)) {
            $facturef->importantInfo()->createMany(array_map(function ($info) {
                return ['info' => $info];
            }, array_filter($request->important)));
        }

        return redirect()->route('facturefs.index')->with('success', 'Facture créée avec succès!');
    }



    public function show(Facturef $facturef)
    {
        $facturef->load(['items', 'importantInfo']);
        $pdf = FacadePdf::loadView('facturefs.show', compact('facturef'))->setPaper('a4', 'portrait');
        return $pdf->stream('facturefs.pdf'); 
       
    }



    public function edit(Facturef $facturef)
    {
         // طباعة الـ devis للتأكد من أنه موجود
        return view('facturefs.edit', compact('facturef'));
    }
    


    // تحديث عرض معين
    public function update(Request $request, Facturef $facturef)
    {
        $request->validate([
            'facturef_num' => 'required|string|max:255',
            'date' => 'required|date',
            'titre' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'tele' => 'required|string|max:255',
            'ice' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'vide' => 'nullable|string',
            'ref' => 'nullable|string|max:255',
            'libelle' => 'required|array',
            'libelle.*' => 'required|string|max:255',
            'type' => 'required|array',
            'type.*' => 'required|in:duree,nombre_collaborateurs,nombre_jours',
            'duree' => 'nullable|array',
            'duree.*' => 'nullable|string|max:255',
            'nombre_collaborateurs' => 'nullable|array',
            'nombre_collaborateurs.*' => 'nullable|integer|min:0',
            'nombre_jours' => 'nullable|array',
            'nombre_jours.*' => 'nullable|integer|min:0',
            'prix_ht' => 'required|array',
            'prix_ht.*' => 'required|numeric|min:0',
            'important' => 'nullable|array',
            'important.*' => 'nullable|string|max:255',
            'afficher_cachet' => 'nullable|boolean',
            'currency' => 'required|in:DH,EUR',
            'tva' => 'required|numeric|in:0,20',
        ]);

        // Calculate total HT
        $totalHT = 0;
        $items = [];
        foreach ($request->libelle as $key => $libelle) {
            if (!isset($request->type[$key], $request->prix_ht[$key])) {
                continue; // Skip if required fields are missing
            }

            $type = $request->type[$key];
            $unitPrice = (float) $request->prix_ht[$key];
            $duree = $request->duree[$key] ?? null;
            $nombre_collaborateurs = $request->nombre_collaborateurs[$key] ?? null;
            $nombre_jours = $request->nombre_jours[$key] ?? null;

            $prixTotal = $type === 'nombre_collaborateurs'
                ? $unitPrice * (int) ($nombre_collaborateurs ?? 0)
                : ($type === 'nombre_jours'
                    ? $unitPrice * (int) ($nombre_jours ?? 0)
                    : $unitPrice);

            $totalHT += $prixTotal;

            $items[] = [
                'facturefs_id' => $facturef->id,
                'libelle' => $libelle,
                'duree' => $duree,
                'nombre_collaborateurs' => $nombre_collaborateurs,
                'nombre_jours' => $nombre_jours,
                'prix_ht' => $unitPrice,
                'prix_total' => $prixTotal,
            ];
        }

        // Calculate TVA and total TTC
        $tvaRate = $request->input('tva', 0);
        $tva = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $tva;

        // Update Facturef
        $facturef->update([
            'facturef_num' => $request->facturef_num,
            'date' => $request->date,
            'titre' => $request->titre,
            'client' => $request->client,
            'tele' => $request->tele,
            'ice' => $request->ice,
            'adresse' => $request->adresse,
            'vide' => $request->vide,
            'ref' => $request->ref,
            'total_ht' => $totalHT,
            'tva' => $tva,
            'total_ttc' => $totalTTC,
            'user_id' => auth()->id(),
            'currency' => $request->currency,
            'afficher_cachet' => $request->input('afficher_cachet', 0),
        ]);

        // Delete old items and insert new ones
        $facturef->items()->delete();
        if (!empty($items)) {
            FacturefItem::insert($items);
        }

        // Update important information
        $facturef->importantInfo()->delete();
        if ($request->has('important') && !empty($request->important)) {
            $facturef->importantInfo()->createMany(array_map(function ($info) {
                return ['info' => $info];
            }, array_filter($request->important)));
        }

        return redirect()->route('facturefs.index')->with('success', 'Facture mise à jour avec succès!');
    }


    
    

    // حذف عرض معين
    public function destroy(Facturef $facturef)
    {
        $facturef->delete();
        return redirect()->route('facturefs.index')->with('success', 'facture supprimé avec succès!');
    }

    public function downloadPDF($id)
{
    // استرجاع الـ Devis مع منتجاته
    $facturef =Facturef::with('items' , 'importantInfo')->find($id);

    if (!$facturef) {
        // إذا لم يتم العثور على الـ Devis
        return redirect()->route('facturefs.index')->with('error', 'facture non trouvé!');
    }

    // تمرير البيانات إلى ملف PDF
    $pdf = FacadePdf::loadView('facturefs.pdf', compact('facturef'));
    
    
    $clientName = $facturef->client ?? 'client'; 
    $titre = $facturef->titre ?? 'titre';
    $total_ttc = $facturef->total_ttc ?? 'total_ttc';
    
    
    return $pdf->download('facture_F_' . $facturef->facturef_num . '_' . $clientName . '_' . $titre . '_' . $total_ttc . '.pdf');

}


    
}
