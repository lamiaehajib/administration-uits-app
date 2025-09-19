<?php

namespace App\Http\Controllers;

use App\Models\BonCommandeR;
use App\Models\BonCommandeItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BonCommandeRController extends Controller
{
    // عرض جميع أوامر الشراء
    public function index(Request $request)
    {
        // البحث عن طريق الكلمات المفتاحية (مثلاً: prestataire، bon_num، أو titre)
        $query = BonCommandeR::with(['items', 'user']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('prestataire', 'like', "%$search%")
                  ->orWhere('bon_num', 'like', "%$search%")
                  ->orWhere('titre', 'like', "%$search%");
            });
        }

        // جلب أوامر الشراء مع التصفية والصفحة
        $bonCommandes = $query
            ->orderBy('created_at', 'desc')
            ->paginate(10); // يمكنك تعديل الرقم حسب العدد المطلوب لكل صفحة

        return view('bon_commande_r.index', compact('bonCommandes'));
    }

    // عرض نموذج لإنشاء أمر شراء جديد
    public function create()
    {
        return view('bon_commande_r.create');
    }

    // تخزين أمر شراء جديد مع العناصر المرتبطة
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
            'titre' => 'nullable|string|max:255',
            'prestataire' => 'nullable|string|max:255',
            'tele' => 'nullable|string|max:255',
            'ice' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'ref' => 'nullable|string|max:255',
            'important' => 'nullable|array',
            'important.*' => 'nullable|string|max:255',
            'total_ht' => 'nullable|numeric',
            'total_ttc' => 'nullable|numeric',
            'tva' => 'nullable|numeric|min:0|max:100',
            'libelle' => 'nullable|array',
            'quantite' => 'required|array',
            'prix_ht' => 'required|array',
            'currency' => 'required|in:DH,EUR',
        ]);

        // حساب المجموع بدون ضريبة القيمة المضافة (HT)
        $totalHT = 0;
        foreach ($request->quantite as $key => $quantite) {
            $totalHT += $quantite * $request->prix_ht[$key];
        }

        // حساب ضريبة القيمة المضافة وإجمالي الفاتورة
        $tva = $totalHT * ($request->tva ? $request->tva / 100 : 0.2); // 20% TVA إذا لم يتم إدخال tva
        $totalTTC = $totalHT + $tva;

        // إنشاء السجل في جدول BonCommandeR
        $bonCommande = BonCommandeR::create(array_merge(
            $request->except('bon_num', 'libelle', 'quantite', 'prix_ht', 'important'),
            [
                'user_id' => auth()->id(),
                'total_ht' => $totalHT,
                'tva' => $request->tva ?? 20, // Default to 20% if not provided
                'total_ttc' => $totalTTC,
                'important' => $request->important ? json_encode($request->important) : null,
            ]
        ));

        // إنشاء رقم أمر الشراء
        $date = now()->format('dmy'); // السنة-الشهر-اليوم
        $bonCommande->bon_num = "{$bonCommande->id}{$date}";
        $bonCommande->save();

        // تسجيل العناصر المرتبطة بأمر الشراء
        $items = [];
        foreach ($request->libelle as $key => $libelle) {
            $items[] = [
                'libelle' => Str::limit($libelle, 255),
                'quantite' => $request->quantite[$key],
                'prix_ht' => $request->prix_ht[$key],
                'prix_total' => $request->quantite[$key] * $request->prix_ht[$key],
                'bon_commande_r_id' => $bonCommande->id,
            ];
        }

        // إدخال العناصر في جدول BonCommandeItem
        BonCommandeItem::insert($items);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('bon_commande_r.index')->with('success', 'Bon de commande créé avec succès!');
    }

    // عرض تفاصيل أمر شراء معين
    public function show(BonCommandeR $bonCommandeR)
{
    // L-Bon ghadi yji wakha ykoun mamsouh, 7it l-Route m3eddla
    $bonCommandeR->load(['items', 'user']);
    
    $pdf = FacadePdf::loadView('bon_commande_r.show', compact('bonCommandeR'))->setPaper('a4', 'portrait');
    return $pdf->stream('bon_commande_r.pdf');
}
    // عرض نموذج لتعديل أمر شراء معين
    public function edit(BonCommandeR $bonCommandeR)
    {
        $bonCommandeR->load(['items']);
        return view('bon_commande_r.edit', compact('bonCommandeR'));
    }

    // تحديث أمر شراء معين
    public function update(Request $request, BonCommandeR $bonCommandeR)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'bon_num' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'titre' => 'nullable|string|max:255',
            'prestataire' => 'nullable|string|max:255',
            'tele' => 'nullable|string|max:255',
            'ice' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'ref' => 'nullable|string|max:255',
            'important' => 'nullable|array',
            'important.*' => 'nullable|string|max:255',
            'total_ht' => 'nullable|numeric',
            'total_ttc' => 'nullable|numeric',
            'tva' => 'nullable|numeric|min:0|max:100',
            'libelle' => 'nullable|array',
            'quantite' => 'required|array',
            'prix_ht' => 'required|array',
            'currency' => 'required|in:DH,EUR',
        ]);

        // إعداد البيانات لتحديث أمر الشراء
        $data = $request->except('libelle', 'quantite', 'prix_ht', 'important');

        // حساب المجموع بدون ضريبة القيمة المضافة (HT)
        $totalHT = 0;
        foreach ($request->quantite as $key => $quantite) {
            $totalHT += $quantite * $request->prix_ht[$key];
        }

        // حساب إجمالي أمر الشراء
        $tva = $totalHT * ($request->tva ? $request->tva / 100 : 0.2); // 20% TVA إذا لم يتم إدخال tva
        $totalTTC = $totalHT + $tva;

        // تحديث البيانات في جدول bon_commande_r
        $data['total_ht'] = $totalHT;
        $data['tva'] = $request->tva ?? 20; // Default to 20% if not provided
        $data['total_ttc'] = $totalTTC;
        $data['important'] = $request->important ? json_encode($request->important) : null;

        $bonCommandeR->update($data);

        // حذف العناصر القديمة إذا كان هناك تحديثات جديدة
        $bonCommandeR->items()->delete();

        // إدراج العناصر المرتبطة بأمر الشراء
        $items = [];
        foreach ($request->libelle as $key => $libelle) {
            $items[] = [
                'libelle' => Str::limit($libelle, 255),
                'quantite' => $request->quantite[$key],
                'prix_ht' => $request->prix_ht[$key],
                'prix_total' => $request->quantite[$key] * $request->prix_ht[$key],
                'bon_commande_r_id' => $bonCommandeR->id,
            ];
        }

        // إدخال العناصر في جدول BonCommandeItem
        BonCommandeItem::insert($items);

        // إعادة التوجيه إلى صفحة أوامر الشراء مع رسالة نجاح
        return redirect()->route('bon_commande_r.index')->with('success', 'Bon de commande mis à jour avec succès!');
    }

    // حذف أمر شراء معين
    public function destroy(BonCommandeR $bonCommandeR)
    {
        $bonCommandeR->delete();
        return redirect()->route('bon_commande_r.index')->with('success', 'Bon de commande supprimé avec succès!');
    }

    // تحميل أمر شراء كملف PDF
    public function downloadPDF($id)
    {
        // استرجاع أمر الشراء مع العناصر المرتبطة
        $bonCommandeR = BonCommandeR::with(['items', 'user'])->find($id);

        if (!$bonCommandeR) {
            // إذا لم يتم العثور على أمر الشراء
            return redirect()->route('bon_commande_r.index')->with('error', 'Bon de commande non trouvé!');
        }

        set_time_limit(300);
        ini_set('memory_limit', '256M');

        // تمرير البيانات إلى ملف PDF
        $pdf = FacadePdf::loadView('bon_commande_r.pdf', compact('bonCommandeR'))
            ->setPaper('a4', 'portrait');

        // تحميل الـ PDF مع تنسيق الاسم المطلوب
        $prestataire = $bonCommandeR->prestataire ?? 'prestataire';
        $titre = $bonCommandeR->titre ?? 'titre';

        return $pdf->download('bon_commande_' . $bonCommandeR->bon_num . '-' . $prestataire . '-' . $titre . '.pdf');
    }

    public function corbeille()
{
    // Kanst3amlo onlyTrashed() bach njebdo GHI les bons de commande li mamsou7in
    $bons = BonCommandeR::onlyTrashed()
                  ->orderBy('deleted_at', 'desc')
                  ->get();

    return view('bon_commande_r.corbeille', compact('bons'));
}

// N°2. Restauration d'un Bon de Commande (I3ada l'Hayat)
public function restore($id)
{
    // Kanjebdo l-Bon b ID men l'Corbeille (withTrashed) w kan3ayto 3la restore()
    $bon = BonCommandeR::withTrashed()->findOrFail($id);
    $bon->restore();

    return redirect()->route('boncommandes.corbeille')->with('success', 'Bon de commande restauré avec succès!');
}

// N°3. Suppression Définitive (Mass7 Nnéha'i)
public function forceDelete($id)
{
    // Kanjebdo l-Bon b ID men l'Corbeille w kan3ayto 3la forceDelete()
    $bon = BonCommandeR::withTrashed()->findOrFail($id);
    $bon->forceDelete(); // Hadchi kaymassah men la base de données b neha'i!

    return redirect()->route('boncommandes.corbeille')->with('success', 'Bon de commande supprimé définitivement!');
}

}