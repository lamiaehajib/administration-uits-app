<?php

// app/Http/Controllers/DevisController.php
namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\DevisItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Carbon\Carbon;
use Illuminate\Support\Str;
class DevisController extends Controller
{
    // عرض جميع العروض
    public function index(Request $request)
{
    // البحث عن طريق الكلمات المفتاحية (مثلاً: العميل، الرقم، أو العنوان)
    $query = Devis::with(['items', 'importantInfos']);

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('client', 'like', "%$search%")
              ->orWhere('devis_num', 'like', "%$search%")
              ->orWhere('titre', 'like', "%$search%");
        });
    }

    // جلب العروض مع التصفية والصفحة
    $devis = $query
    ->orderBy('created_at', 'desc') 
    ->paginate(10); // يمكنك تعديل الرقم حسب العدد المطلوب لكل صفحة

    return view('devis.index', compact('devis'));
}
public function duplicate(Devis $devis)
{
    // Clone the existing devis
    $newDevis = $devis->replicate();
    $newDevis->devis_num = null; // Reset devis_num to generate a new one
    $newDevis->created_at = now();
    $newDevis->updated_at = now();
    $newDevis->save();

    // Generate a new devis_num
    $date = now()->format('dmy');
    $newDevis->devis_num = "{$newDevis->id}{$date}";
    $newDevis->save();

    // Duplicate related items
    foreach ($devis->items as $item) {
        DevisItem::create([
            'devis_id' => $newDevis->id,
            'libele' => $item->libele,
            'quantite' => $item->quantite,
            'prix_unitaire' => $item->prix_unitaire,
            'prix_total' => $item->prix_total,
        ]);
    }

    // Duplicate important infos
    foreach ($devis->importantInfos as $info) {
        $newDevis->importantInfos()->create(['info' => $info->info]);
    }

    return redirect()->route('devis.index')->with('success', 'Devis dupliqué avec succès!');
}

    // عرض نموذج لإنشاء عرض جديد
    public function create()
    {
        return view('devis.create');
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
        'libele' => 'nullable|array',
        'quantite' => 'required|array',
        'quantite.*' => 'required|numeric|min:0',
        'prix_unitaire' => 'required|array',
        'prix_unitaire.*' => 'required|numeric|min:0',
        'important' => 'nullable|array',
        'important.*' => 'nullable|string|max:255',
        'currency' => 'required|in:DH,EUR',
        'tva' => 'required|in:0,20', // نتأكدو أن TVA إما 0 وإما 20
    ]);

    // حساب المجموع بدون الضريبة (HT)
    $totalHT = 0;
    foreach ($request->quantite as $key => $quantite) {
        $totalHT += $quantite * $request->prix_unitaire[$key];
    }

    // حساب الضريبة (TVA) والمجموع الكلي (TTC)
    $tvaRate = $request->tva / 100; // نحولو النسبة (0 أو 20) لعشري (0 أو 0.2)
    $tva = $totalHT * $tvaRate;
    $totalTTC = $totalHT + $tva;

    // إنشاء السجل فجدول Devis
    $devis = Devis::create(array_merge(
        $request->except('devis_num', 'libele', 'quantite', 'prix_unitaire', 'important', 'tva'),
        [
            'user_id' => auth()->id(),
            'total_ht' => $totalHT,
            'tva' => $tva,
            'total_ttc' => $totalTTC,
            'tva_rate' => $request->tva, // نزيدو حقل باش نحتفظو بنسبة الضريبة
        ]
    ));

    // إنشاء رقم الفاتورة
    $date = now()->format('dmy');
    $devis->devis_num = "{$devis->id}{$date}";
    $devis->save();

    // تسجيل المعلومات المهمة
    if ($request->has('important') && !empty($request->important)) {
        $devis->importantInfos()->createMany(array_map(function ($info) {
            return ['info' => $info];
        }, array_filter($request->important)));
    }

    // تسجيل العناصر ديال الفاتورة
    $items = [];
    foreach ($request->libele as $key => $libele) {
        if (!empty($libele)) {
            $items[] = [
               'libele' => $libele,
                'quantite' => $request->quantite[$key],
                'prix_unitaire' => $request->prix_unitaire[$key],
                'prix_total' => $request->quantite[$key] * $request->prix_unitaire[$key],
                'devis_id' => $devis->id,
            ];
        }
    }

    // إدخال العناصر فجدول DevisItem
    DevisItem::insert($items);

    return redirect()->route('devis.index')->with('success', 'Devis créé avec succès!');
}


    


    

    // عرض تفاصيل عرض معين
    public function show(Devis $devis)
    {
        $devis->load(['items', 'importantInfos']);
        $pdf = FacadePdf::loadView('devis.show', compact('devis'))->setPaper('a4', 'portrait');
        return $pdf->stream('devis.pdf'); // Or `download('devis.pdf')` to download directly.
    }
    

    // عرض نموذج لتعديل عرض معين
    public function edit(Devis $devis)
    {
         // طباعة الـ devis للتأكد من أنه موجود
        return view('devis.edit', compact('devis'));
    }
    


    // تحديث عرض معين
    public function update(Request $request, Devis $devis)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'devis_num' => 'required|string|max:255',
            'date' => 'required|date',
            'titre' => 'required|string|max:255',
            'client' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'ref' => 'nullable|string|max:255',
            'libele' => 'nullable|array',
            'quantite' => 'required|array',
            'quantite.*' => 'required|numeric|min:0',
            'prix_unitaire' => 'required|array',
            'prix_unitaire.*' => 'required|numeric|min:0',
            'important' => 'nullable|array',
            'important.*' => 'nullable|string|max:255', // خليناه nullable باش يقبل القيم الفارغة
            'currency' => 'required|in:DH,EUR',
            'tva' => 'required|in:0,20', // زدناه باش نتأكدو من TVA
        ]);
    
        // إعداد البيانات لتحديث الـ devis
        $data = $request->except('libele', 'quantite', 'prix_unitaire', 'prix_total', 'important', 'tva');
    
        // حساب المجموع بدون الضريبة (HT)
        $totalHT = 0;
        foreach ($request->quantite as $key => $quantite) {
            $totalHT += $quantite * $request->prix_unitaire[$key];
        }
    
        // حساب الضريبة (TVA) والمجموع الكلي (TTC)
        $tvaRate = $request->tva / 100; // نحولو النسبة (0 أو 20) لعشري (0 أو 0.2)
        $tva = $totalHT * $tvaRate;
        $totalTTC = $totalHT + $tva;
    
        // تحديث البيانات في جدول devis
        $data['total_ht'] = $totalHT;
        $data['tva'] = $tva;
        $data['total_ttc'] = $totalTTC;
        $data['tva_rate'] = $request->tva; // نزيدو نسبة الضريبة
    
        $devis->update($data);
    
        // حذف المنتجات القديمة
        $devis->items()->delete();
    
        // تحديث أو إضافة المنتجات الجديدة
        $items = [];
        foreach ($request->libele as $key => $libele) {
            if (!empty($libele)) { // نتأكدو أن الوصف مشي خاوي
                $items[] = [
                   'libele' => $libele,
                    'quantite' => $request->quantite[$key],
                    'prix_unitaire' => $request->prix_unitaire[$key],
                    'prix_total' => $request->quantite[$key] * $request->prix_unitaire[$key],
                    'devis_id' => $devis->id,
                ];
            }
        }
    
        // إدراج المنتجات فجدول DevisItem
        DevisItem::insert($items);
    
        // تحديث المعلومات المهمة
        if ($request->has('important') && !empty($request->important)) {
            // حذف المعلومات القديمة
            $devis->importantInfos()->delete();
            
            // إضافة المعلومات الجديدة
            $devis->importantInfos()->createMany(array_map(function ($info) {
                return ['info' => $info];
            }, array_filter($request->important))); // فلترنا القيم الفارغة
        }
    
        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('devis.index')->with('success', 'Devis mis à jour avec succès!');
    }
    

    // حذف عرض معين
    public function destroy(Devis $devis)
    {
        $devis->delete();
        return redirect()->route('devis.index')->with('success', 'Devis supprimé avec succès!');
    }

    public function downloadPDF($id)
{
    // استرجاع الـ Devis مع منتجاته
    $devis = Devis::with(['items', 'importantInfos'])->find($id);

    if (!$devis) {
        // إذا لم يتم العثور على الـ Devis
        return redirect()->route('devis.index')->with('error', 'Devis non trouvé!');
    }
    set_time_limit(300);
    ini_set('memory_limit', '256M');
    
    // تمرير البيانات إلى ملف PDF
    $pdf = FacadePdf::loadView('devis.pdf', compact('devis'))
    ->setPaper('a4', 'portrait');
    
    // تحميل الـ PDF مع تنسيق الاسم المطلوب
    $clientName = $devis->client ?? 'client'; 
    $titre = $devis->titre ?? 'titre';
    
    return $pdf->download('devis_' . $devis->devis_num . '-' . $clientName . '-' . $titre . '.pdf');
}

    public function restore($id)
    {
        // Kanjebdo Devis men Corbeille w kan3ayto 3la restore()
        $devis = Devis::withTrashed()->findOrFail($id);
        $devis->restore();

        return redirect()->route('devis.corbeille')->with('success', 'Devis tte-rja3 men Corbeille b naja7!');
    }

    public function forceDelete($id)
    {
        // Kanjebdo Devis men Corbeille w kan3ayto 3la forceDelete()
        $devis = Devis::withTrashed()->findOrFail($id);
        $devis->forceDelete(); // Hadchi kaymassah men la base de données!

        return redirect()->route('devis.corbeille')->with('success', 'Devis tte-mssa7 b chkel néha\'i!');
    }


    public function corbeille()
    {
        // Kanst3amlo onlyTrashed() bach njebdo GHI les devis li fihom deleted_at m3emmer
        $devis = Devis::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        // Khassna ndirou wa7ed View jdida bach n'affichiw had les devis
        return view('devis.corbeille', compact('devis'));
    }

}
