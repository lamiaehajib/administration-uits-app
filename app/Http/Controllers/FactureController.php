<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Facture;
use App\Models\FactureItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer le terme de recherche
        $search = $request->input('search');
    
        // Appliquer la recherche et pagination
        $factures = Facture::with('items', 'importantInfoo')
            ->when($search, function ($query, $search) {
                return $query->where('facture_num', 'like', "%{$search}%")
                             ->orWhere('client', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc') 
            ->paginate(10); // Pagination de 10 factures par page
    
        return view('factures.index', compact('factures', 'search'));
    }
    


    public function duplicate(Facture $facture)
    {
        // Clone the existing facture
        $newFacture = $facture->replicate();
        $newFacture->facture_num = null; // Reset facture_num to generate a new one
        $newFacture->created_at = now();
        $newFacture->updated_at = now();
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
        return view('factures.create');
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'titre' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'ice' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'vide' => 'nullable|string',
            'ref' => 'nullable|string|max:255',
            'libele' => 'nullable|array',
            'quantite' => 'required|array',
            'quantite.*' => 'numeric|min:0', // فاليديشن للكميات
            'prix_ht' => 'required|array',
            'prix_ht.*' => 'numeric|min:0', // فاليديشن للأسعار
            'important' => 'nullable|array',
            'important.*' => 'nullable|string|max:255', // عدلت required ل nullable
            'afficher_cachet' => 'nullable|boolean',
            'currency' => 'required|in:DH,EUR',
            'tva' => 'required|numeric|in:0,20', // إضافة فاليديشن لـ tva
        ]);
    
        // حساب إجمالي HT بناءً على الكميات والأسعار
        $totalHT = 0;
        foreach ($request->quantite as $key => $quantite) {
            $totalHT += (float) $quantite * (float) $request->prix_ht[$key];
        }
    
        // حساب TVA و TTC
        $tvaRate = $request->input('tva', 0); // جيب القيمة ديال tva من الفورم
        $tva = $totalHT * ($tvaRate / 100); // احسب الـ TVA حسب القيمة
        $totalTTC = $totalHT + $tva;
    
        // إنشاء السجل الرئيسي في جدول Facture
        $facture = Facture::create(array_merge(
            $request->except('libele', 'quantite', 'prix_ht', 'important', 'facture_num', 'tva'),
            [
                'user_id' => auth()->id(),
                'total_ht' => $totalHT,
                'tva' => $tva,
                'total_ttc' => $totalTTC,
                'afficher_cachet' => $request->input('afficher_cachet', 0),
                // إذا زاديتي tva_rate فالداتابايس، زيدي: 'tva_rate' => $tvaRate
            ]
        ));
    
        // إنشاء رقم الفاتورة
        $date = now()->addDays(1)->addMonths(1)->addYears(1)->format('ymd'); // إضافة يوم، شهر وسنة
        $facture->facture_num = "{$facture->id}{$date}";
        $facture->save();
    
        // تخزين المعلومات الهامة
        if ($request->has(' совершенно новый') && !empty($request->important)) {
            $facture->importantInfoo()->createMany(array_map(function ($info) {
                return ['info' => $info];
            }, array_filter($request->important))); // array_filter باش ما نسجلوش القيم الخاوية
        }
    
        // تخزين العناصر المرتبطة بالفاتورة
        $items = [];
        if ($request->has('libele')) {
            foreach ($request->libele as $key => $libele) {
                if (!isset($request->quantite[$key]) || !isset($request->prix_ht[$key])) {
                    continue;
                }
                $items[] = [
                    'libele' => $libele,
                    'quantite' => (float) $request->quantite[$key],
                    'prix_ht' => (float) $request->prix_ht[$key],
                    'prix_total' => (float) $request->quantite[$key] * (float) $request->prix_ht[$key],
                    'factures_id' => $facture->id,
                ];
            }
        }
    
        // إدخال العناصر إلى جدول FactureItem
        if (!empty($items)) {
            FactureItem::insert($items);
        }
    
        // إعادة التوجيه إلى صفحة الفواتير مع رسالة نجاح
        return redirect()->route('factures.index')->with('success', 'Facture créée avec succès!');
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
         // طباعة الـ devis للتأكد من أنه موجود
        return view('factures.edit', compact('facture'));
    }
    


    // تحديث عرض معين
    public function update(Request $request, Facture $facture)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'facture_num' => 'required|string|max:255',
            'date' => 'required|date',
            'titre' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'ice' => 'nullable|string|max:255',
            'adresse' => 'nullable|string',
            'vide' => 'nullable|string', // تعديل هنا
            'ref' => 'nullable|string|max:255',
            'libele' => 'nullable|array',
            'quantite' => 'required|array',
            'prix_ht' => 'required|array',
            'important' => 'nullable|array',
            'afficher_cachet' => 'nullable|boolean',
            'currency' => 'required|in:DH,EUR',
'tva' => 'required|numeric|in:0,20',
        ]);
        
    
        // إعداد البيانات لتحديث الـ devis
        $data = $request->except('libele', 'quantite', 'prix_ht', 'prix_total', 'important');
    
        // حساب الـ prix_total للـ devis (إذا كانت هناك بيانات)
        $totalHT = 0;
        foreach ($request->quantite as $key => $quantite) {
            $totalHT += $quantite * $request->prix_ht[$key];
        }
    
        // حساب إجمالي الـ devis
      $tvaRate = $request->input('tva', 0); // جيب القيمة ديال tva من الفورم
    $tva = $totalHT * ($tvaRate / 100); // احسب الـ TVA حسب القيمة
    $totalTTC = $totalHT + $tva;
    
        // تحديث البيانات في جدول devis
        $data['total_ht'] = $totalHT;
        $data['tva'] = $tva;
        $data['total_ttc'] = $totalTTC;
    
        $facture->update($data);
    
        // تحديث الـ devis_items (أو إضافتها إذا كانت جديدة)
        $items = [];
        foreach ($request->libele as $key => $libele) {
            $items[] = [
                'libele' => $libele,
                'quantite' => $request->quantite[$key],
                'prix_ht' => $request->prix_ht[$key],
                'prix_total' => $request->quantite[$key] * $request->prix_ht[$key],
                'factures_id' => $facture->id, 
            ];
        }
    
        // حذف المنتجات القديمة إذا كان هناك تحديثات جديدة
        $facture->items()->delete();
    
        // إدراج أو تحديث المنتجات المرتبطة بالـ devis
        FactureItem::insert($items);
       // إذا كانت هناك معلومات مهمة، حفظها في جدول important_info
       if ($request->has('important')) {
        // إزالة البيانات القديمة من جدول importantInfos
        $facture->importantInfoo()->delete();
        
        // importantInfoo
        foreach ($request->important as $info) {
            $facture->importantInfoo()->create(['info' => $info]);
        }
    }
        // إعادة التوجيه إلى صفحة العروض مع رسالة نجاح
        return redirect()->route('factures.index')->with('success', 'facture mis à jour avec succès!');
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
