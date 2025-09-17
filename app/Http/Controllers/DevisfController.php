<?php

namespace App\Http\Controllers;

use App\Models\Devisf;
use App\Models\DevisItemf;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;

class DevisfController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the request
        $searchTerm = $request->input('search', '');

        // Paginate and filter by search term (e.g., search by devis_num or client)
        $devisf = Devisf::with('items', 'ImportantInfof')
            ->where('devis_num', 'like', '%' . $searchTerm . '%')
            ->orWhere('client', 'like', '%' . $searchTerm . '%')
            ->orderBy('created_at', 'desc') 
            ->paginate(10); // You can change the pagination number (10) based on your needs
        
        // Return the view with the devisf data and search term
        return view('devisf.index', compact('devisf', 'searchTerm'));
    }

    // عرض نموذج لإنشاء عرض جديد


    public function duplicate(Devisf $devisf)
{
    // Clone the existing devisf
    $newDevisf = $devisf->replicate();
    $newDevisf->devis_num = null; // Reset devis_num to generate a new one
    $newDevisf->created_at = now();
    $newDevisf->updated_at = now();
    $newDevisf->save();

    // Generate a new devis_num
    $date = now()->format('dmy');
    $newDevisf->devis_num = "{$newDevisf->id}{$date}";
    $newDevisf->save();

    // Duplicate related items
    foreach ($devisf->items as $item) {
        DevisItemf::create([
            'devis_id' => $newDevisf->id,
            'libele' => $item->libele,
            'formation' => $item->formation,
            'nombre' => $item->nombre,
            'nombre_de_jours' => $item->nombre_de_jours,
            'prix_unitaire' => $item->prix_unitaire,
            'prix_total' => $item->prix_total,
        ]);
    }

    // Duplicate important infos
    foreach ($devisf->ImportantInfof as $info) {
        $newDevisf->ImportantInfof()->create(['info' => $info->info]);
    }

    return redirect()->route('devisf.index')->with('success', 'Devis de formation dupliqué avec succès!');
}
    public function create()
    {
        return view('devisf.create');
    }

    // تخزين عرض جديد مع المنتجات المرتبطة
    public function store(Request $request)
{
    $request->validate([
        'date' => 'required|date',
        'titre' => 'required|string|max:255',
        'client' => 'required|string|max:255',
        'contact' => 'nullable|string|max:255',
        'ref' => 'nullable|string|max:255',
        'currency' => 'required|in:DH,EUR',
        'libele' => 'required|array',
        'type' => 'required|array',
        'formation' => 'nullable|array',
        'nombre' => 'nullable|array',
        'nombre_de_jours' => 'nullable|array',  // تعديل هنا
        'prix_unitaire' => 'required|array',
        'important' => 'nullable|array',
        'important.*' => 'nullable|string|max:255',
    ]);

    $totalHT = 0;

    foreach ($request->libele as $key => $libele) {
        $type = $request->type[$key];
        $unitPrice = (float) $request->prix_unitaire[$key];

        if ($type === 'nombre') {
            $qty = (int) ($request->nombre[$key] ?? 0);
            $rowTotal = $unitPrice * $qty;
        } elseif ($type === 'nombre_de_jours') {  // تعديل هنا
            $qty = (int) ($request->nombre_de_jours[$key] ?? 0);  // تعديل هنا
            $rowTotal = $unitPrice * $qty;
        } else {
            $rowTotal = $unitPrice; // بالنسبة للتكوين العادي
        }

        $totalHT += $rowTotal;
    }

    $tvaRate = $request->tva ?? 0;
    $tva = $totalHT * ($tvaRate / 100);
    $totalTTC = $totalHT + $tva;

    $devisf = Devisf::create([
        'date' => $request->date,
        'titre' => $request->titre,
        'client' => $request->client,
        'contact' => $request->contact,
        'ref' => $request->ref,
        'vide' => $request->vide,
        'total_ht' => $totalHT,
        'tva' => $tva,
        'total_ttc' => $totalTTC,
        'user_id' => auth()->id(),
        'currency' => $request->currency,
    ]);

    // توليد رقم العرض
    $devisf->devis_num = $devisf->id . now()->format('dmy');
    $devisf->save();

    // حفظ العناصر
    foreach ($request->libele as $key => $libele) {
        $type = $request->type[$key];
        $unitPrice = (float) $request->prix_unitaire[$key];
        $formation = $request->formation[$key] ?? null;
        $nombre = $request->nombre[$key] ?? null;
        $nombre_de_jours = $request->nombre_de_jours[$key] ?? null;  // تعديل هنا

        $prixTotal = $type === 'nombre'
            ? $unitPrice * (int) $nombre
            : ($type === 'nombre_de_jours'
                ? $unitPrice * (int) $nombre_de_jours  // تعديل هنا
                : $unitPrice);

        DevisItemf::create([
            'devis_id' => $devisf->id,
            'libele' => $libele,
            'formation' => $formation,
            'nombre' => $nombre,
            'nombre_de_jours' => $nombre_de_jours,  // تعديل هنا
            'prix_unitaire' => $unitPrice,
            'prix_total' => $prixTotal,
        ]);
    }

    // حفظ المعلومات المهمة
    if ($request->has('important')) {
        $importantData = collect($request->important)->filter()->map(function ($info) {
            return ['info' => $info];
        });
        $devisf->ImportantInfof()->createMany($importantData);
    }

    return redirect()->route('devisf.index')->with('success', 'Devis créé avec succès!');
}

    
    

    
    


    

    // عرض تفاصيل عرض معين
 
    public function show(Devisf $devisf)
    {
        $devisf->load(['items', 'ImportantInfof']);
        $pdf = FacadePdf::loadView('devisf.show', compact('devisf'))->setPaper('a4', 'portrait');
        return $pdf->stream('devisf.pdf'); 
       
    }



    // عرض نموذج لتعديل عرض معين
    public function edit(Devisf $devisf)
    {
        
        return view('devisf.edit', compact('devisf'));
    }
    
    

    


    // تحديث عرض معين
    public function update(Request $request, Devisf $devisf)
    {
        $request->validate([
            'devis_num' => 'required|string|max:255',
            'date' => 'required|date',
            'titre' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'ref' => 'nullable|string|max:255',
            'currency' => 'required|in:DH,EUR',
            'libele' => 'required|array',
            'type' => 'required|array',
            'formation' => 'nullable|array',
            'nombre' => 'nullable|array',
            'nombre_de_jours' => 'nullable|array',
            'prix_unitaire' => 'required|array',
            'prix_unitaire.*' => 'required|numeric|min:0',
            'important' => 'nullable|array',
            'important.*' => 'nullable|string|max:255',
        ]);

        $totalHT = 0;

        foreach ($request->libele as $key => $libele) {
            $type = $request->type[$key];
            $unitPrice = (float) $request->prix_unitaire[$key];

            if ($type === 'nombre') {
                $qty = (int) ($request->nombre[$key] ?? 0);
                $rowTotal = $unitPrice * $qty;
            } elseif ($type === 'nombre_de_jours') {
                $qty = (int) ($request->nombre_de_jours[$key] ?? 0);
                $rowTotal = $unitPrice * $qty;
            } else {
                $rowTotal = $unitPrice; // Pour formation
            }

            $totalHT += $rowTotal;
        }

        $tvaRate = $request->tva ?? 0;
        $tva = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $tva;

        $devisf->update([
            'devis_num' => $request->devis_num,
            'date' => $request->date,
            'titre' => $request->titre,
            'client' => $request->client,
            'contact' => $request->contact,
            'ref' => $request->ref,
            'vide' => $request->vide,
            'total_ht' => $totalHT,
            'tva' => $tva,
            'total_ttc' => $totalTTC,
            'currency' => $request->currency,
        ]);

        $devisf->items()->delete();

        $items = [];
        foreach ($request->libele as $key => $libele) {
            $type = $request->type[$key];
            $unitPrice = (float) $request->prix_unitaire[$key];
            $formation = $request->formation[$key] ?? null;
            $nombre = $request->nombre[$key] ?? null;
            $nombre_de_jours = $request->nombre_de_jours[$key] ?? null;

            $prixTotal = $type === 'nombre'
                ? $unitPrice * (int) $nombre
                : ($type === 'nombre_de_jours'
                    ? $unitPrice * (int) $nombre_de_jours
                    : $unitPrice);

            $items[] = [
                'devis_id' => $devisf->id,
                'libele' => $libele,
                'formation' => $formation,
                'nombre' => $nombre,
                'nombre_de_jours' => $nombre_de_jours,
                'prix_unitaire' => $unitPrice,
                'prix_total' => $prixTotal,
            ];
        }

        DevisItemf::insert($items);

        $devisf->ImportantInfof()->delete();
        if ($request->has('important')) {
            $importantData = collect($request->important)->filter()->map(function ($info) {
                return ['info' => $info];
            });
            $devisf->ImportantInfof()->createMany($importantData);
        }

        return redirect()->route('devisf.index')->with('success', 'Devis mis à jour avec succès!');
    }



     


    // حذف عرض معين
    public function destroy(Devisf $devisf)
    {
        $devisf->delete();
        return redirect()->route('devisf.index')->with('success', 'Devis supprimé avec succès!');
    }

    public function downloadPDF($id)
    {
        $devisf = Devisf::with('items')->find($id);
    
        if (!$devisf) {
            return redirect()->route('devisf.index')->with('error', 'Devis non trouvé!');
        }
    
        if (!empty($devisf->important)) {
            // تحويل حقل JSON إلى مصفوفة
            $importantData = json_decode($devisf->important, true);
    
            if (is_array($importantData)) {
                // حذف البيانات القديمة
                $devisf->ImportantInfof()->delete();
    
                // إدخال البيانات الجديدة
                foreach ($importantData as $info) {
                    $devisf->ImportantInfof()->create(['info' => $info]);
                }
            }
        }
    
        $pdf = FacadePdf::loadView('devisf.pdf', compact('devisf'));

        $clientName = $devisf->client ?? 'client'; 
    $titre = $devisf->titre ?? 'titre';
    
    return $pdf->download('devis_' . $devisf->devis_num . '-' . $clientName . '-' . $titre . '.pdf');
    
       
    }


    public function corbeille()
{
    // Kanst3amlo onlyTrashed() bach njebdo GHI les devis li mamsou7in
    $devisf = Devisf::onlyTrashed()
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('devisf.corbeille', compact('devisf'));
}

// N°2. Restauration d'un Devis (I3ada l'Hayat)
public function restore($id)
{
    // Kanjebdo Devis b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
    $devisf = Devisf::withTrashed()->findOrFail($id);
    $devisf->restore();

    return redirect()->route('devisf.corbeille')->with('success', 'Devis restauré avec succès!');
}

// N°3. Suppression Définitive (Mass7 Nnéha'i)
public function forceDelete($id)
{
    // Kanjebdo Devis b ID men l'Corbeille w kan3ayto 3la forceDelete()
    $devisf = Devisf::withTrashed()->findOrFail($id);
    $devisf->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

    return redirect()->route('devisf.corbeille')->with('success', 'Devis supprimé définitivement!');
}
    

}


