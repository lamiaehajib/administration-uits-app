<?php

namespace App\Http\Controllers;

use App\Models\Devisf;
use App\Models\Facturef;
use App\Models\FacturefItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FacturefController extends Controller
{
  public function index(Request $request)
{
    // 1. Récupérer tous les paramètres de recherche et filtres
    $search = $request->input('search');
    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');
    $minAmount = $request->input('min_amount');
    $maxAmount = $request->input('max_amount');
    $currency = $request->input('currency');
    $sortBy = $request->input('sort_by', 'created_at');
    $sortOrder = $request->input('sort_order', 'desc');
    $perPage = $request->input('per_page', 10);
    $tvaFilter = $request->input('tva_filter');

    // 2. Query de base avec relations
    $query = Facturef::with(['items', 'importantInfo', 'user']);

    // 3. Recherche globale (numéro facture, client, titre, téléphone, ICE)
    if ($search) {
        $query->where(function($q) use ($search) {
            $q->where('facturef_num', 'like', "%{$search}%")
              ->orWhere('client', 'like', "%{$search}%")
              ->orWhere('titre', 'like', "%{$search}%")
              ->orWhere('tele', 'like', "%{$search}%")
              ->orWhere('ice', 'like', "%{$search}%");
        });
    }

    // 4. Filtre par période (date)
    if ($dateFrom) {
        $query->whereDate('date', '>=', $dateFrom);
    }
    if ($dateTo) {
        $query->whereDate('date', '<=', $dateTo);
    }

    // 5. Filtre par montant (total TTC)
    if ($minAmount) {
        $query->where('total_ttc', '>=', $minAmount);
    }
    if ($maxAmount) {
        $query->where('total_ttc', '<=', $maxAmount);
    }

    // 6. Filtre par devise (DH ou EUR)
    if ($currency) {
        $query->where('currency', $currency);
    }

    // 7. Filtre par TVA (0% ou 20%)
    if ($tvaFilter !== null && $tvaFilter !== '') {
        $query->where('tva', '>', 0)->when($tvaFilter == '0', function($q) {
            return $q->orWhere('tva', 0);
        });
    }

    // 8. Tri dynamique
    $allowedSorts = ['created_at', 'date', 'facturef_num', 'client', 'total_ht', 'total_ttc'];
    if (in_array($sortBy, $allowedSorts)) {
        $query->orderBy($sortBy, $sortOrder);
    }

    // 9. Pagination avec conservation des paramètres
    $facturefs = $query->paginate($perPage)->appends($request->all());

    // 10. Statistiques avancées pour l'affichage
    $stats = [
        'total_factures' => Facturef::count(),
        'total_amount_dh' => Facturef::where('currency', 'DH')->sum('total_ttc'),
        'total_amount_eur' => Facturef::where('currency', 'EUR')->sum('total_ttc'),
        'factures_this_month' => Facturef::whereMonth('date', Carbon::now()->month)
                                         ->whereYear('date', Carbon::now()->year)
                                         ->count(),
        'amount_this_month' => Facturef::whereMonth('date', Carbon::now()->month)
                                       ->whereYear('date', Carbon::now()->year)
                                       ->sum('total_ttc'),
        'avg_amount' => Facturef::avg('total_ttc'),
        'top_clients' => Facturef::select('client', \DB::raw('COUNT(*) as count'), \DB::raw('SUM(total_ttc) as total'))
                                 ->groupBy('client')
                                 ->orderBy('total', 'desc')
                                 ->limit(5)
                                 ->get(),
    ];

    // 11. Export Excel si demandé
    if ($request->has('export') && $request->export === 'excel') {
        return $this->exportToExcel($query->get());
    }

    // 12. Export CSV si demandé
    if ($request->has('export') && $request->export === 'csv') {
        return $this->exportToCsv($query->get());
    }

    return view('facturefs.index', compact('facturefs', 'search', 'stats', 
        'dateFrom', 'dateTo', 'minAmount', 'maxAmount', 'currency', 
        'sortBy', 'sortOrder', 'perPage', 'tvaFilter'));
}

// Fonction helper pour export Excel
private function exportToExcel($factures)
{
    $filename = 'factures_formation_' . Carbon::now()->format('Y-m-d_His') . '.xlsx';
    
    // Tu peux utiliser Laravel Excel package ici
    // return Excel::download(new FacturefsExport($factures), $filename);
}

// Fonction helper pour export CSV
private function exportToCsv($factures)
{
    $filename = 'factures_formation_' . Carbon::now()->format('Y-m-d_His') . '.csv';
    
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($factures) {
        $file = fopen('php://output', 'w');
        
        // En-têtes CSV
        fputcsv($file, ['N° Facture', 'Date', 'Client', 'Titre', 'Total HT', 'TVA', 'Total TTC', 'Devise']);
        
        // Données
        foreach ($factures as $facture) {
            fputcsv($file, [
                $facture->facturef_num,
                $facture->date,
                $facture->client,
                $facture->titre,
                $facture->total_ht,
                $facture->tva,
                $facture->total_ttc,
                $facture->currency,
            ]);
        }
        
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


    public function duplicate(Facturef $facturef)
    {
        // 1. Clone the existing facturef
        $newFacturef = $facturef->replicate();
        
        // 2. Mettre à jour les champs spécifiques pour la duplication
        $newFacturef->facturef_num = null; // Reset facturef_num pour générer un nouveau
        $newFacturef->created_at = now();
        $newFacturef->updated_at = now();
        
        // ✨ MODIFICATION CLÉ : Assigner l'ID de l'utilisateur authentifié
        $newFacturef->user_id = Auth::id(); // Récupère l'ID de l'utilisateur connecté
        // Si vous utilisez une autre méthode que Auth::id(), remplacez-la ici (ex: auth()->id())

        $newFacturef->save();

        // 3. Generate a new facturef_num (comme dans votre logique existante)
        $date = Carbon::now()->format('dmy'); 
        $newFacturef->facturef_num = "{$newFacturef->id}{$date}";
        $newFacturef->save(); // Sauvegarde à nouveau pour le numéro de facture

        // 4. Duplicate related items
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

        // 5. Duplicate important infos
        foreach ($facturef->importantInfo as $info) {
            $newFacturef->importantInfo()->create(['info' => $info->info]);
        }

        return redirect()->route('facturefs.index')->with('success', 'Facture de formation dupliquée avec succès!');
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
            'libelle.*' => 'required|string|max:2055',
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
            'libelle.*' => 'required|string|max:2055',
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



public function corbeille()
{
    // Kanst3amlo onlyTrashed() bach njebdo GHI les factures li mamsou7in
    $factures = Facturef::onlyTrashed()
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('facturefs.corbeille', compact('factures'));
}

// N°2. Restauration d'une Facture (I3ada l'Hayat)
public function restore($id)
{
    // Kanjebdo l-facture b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
    $facture = Facturef::withTrashed()->findOrFail($id);
    $facture->restore();

    return redirect()->route('facturef.corbeille')->with('success', 'Facture restaurée avec succès!');
}

// N°3. Suppression Définitive (Mass7 Nnéha'i)
public function forceDelete($id)
{
    // Kanjebdo l-facture b ID men l'Corbeille w kan3ayto 3la forceDelete()
    $facture = Facturef::withTrashed()->findOrFail($id);
    $facture->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

    return redirect()->route('facturef.corbeille')->with('success', 'Facture supprimée définitivement!');
}
    
}
