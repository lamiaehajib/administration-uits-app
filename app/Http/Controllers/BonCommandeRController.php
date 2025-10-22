<?php

namespace App\Http\Controllers;

use App\Models\BonCommandeR;
use App\Models\BonCommandeItem;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BonCommandeRController extends Controller
{
    // عرض جميع أوامر الشراء
   public function index(Request $request)
{
    $query = BonCommandeR::with(['items', 'user']);

    // 1. RECHERCHE AVANCÉE (Multi-critères)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('prestataire', 'like', "%$search%")
              ->orWhere('bon_num', 'like', "%$search%")
              ->orWhere('titre', 'like', "%$search%")
              ->orWhere('ice', 'like', "%$search%")
              ->orWhere('ref', 'like', "%$search%")
              ->orWhereHas('user', function($q) use ($search) {
                  $q->where('name', 'like', "%$search%");
              });
        });
    }

    // 2. FILTRE PAR DATE (Plage de dates)
    if ($request->filled('date_debut')) {
        $query->whereDate('date', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->whereDate('date', '<=', $request->date_fin);
    }

    // 3. FILTRE PAR MONTANT (Min/Max)
    if ($request->filled('montant_min')) {
        $query->where('total_ttc', '>=', $request->montant_min);
    }
    if ($request->filled('montant_max')) {
        $query->where('total_ttc', '<=', $request->montant_max);
    }

    // 4. FILTRE PAR DEVISE
    if ($request->filled('currency')) {
        $query->where('currency', $request->currency);
    }

    // 5. FILTRE PAR TVA
    if ($request->filled('tva')) {
        $query->where('tva', $request->tva);
    }

    // 6. FILTRE PAR UTILISATEUR (Créateur)
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // 7. FILTRE PAR PÉRIODE (Cette semaine, ce mois, cette année)
    if ($request->filled('periode')) {
        switch ($request->periode) {
            case 'aujourd_hui':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'cette_semaine':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'ce_mois':
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
                break;
            case 'cette_annee':
                $query->whereYear('created_at', Carbon::now()->year);
                break;
            case 'mois_dernier':
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                      ->whereYear('created_at', Carbon::now()->subMonth()->year);
                break;
            case 'annee_derniere':
                $query->whereYear('created_at', Carbon::now()->subYear()->year);
                break;
        }
    }

    // 8. TRI DYNAMIQUE
    $sortField = $request->get('sort_by', 'created_at');
    $sortDirection = $request->get('sort_direction', 'desc');
    
    $allowedSortFields = ['created_at', 'date', 'bon_num', 'prestataire', 'total_ttc', 'titre'];
    if (in_array($sortField, $allowedSortFields)) {
        $query->orderBy($sortField, $sortDirection);
    } else {
        $query->orderBy('created_at', 'desc');
    }

    // 9. NOMBRE D'ÉLÉMENTS PAR PAGE (Personnalisable)
    $perPage = $request->get('per_page', 10);
    if (!in_array($perPage, [10, 25, 50, 100])) {
        $perPage = 10;
    }

    // 10. STATISTIQUES GLOBALES (Pour le dashboard)
    $stats = [
        'total_bons' => BonCommandeR::count(),
        'total_montant_ttc' => BonCommandeR::sum('total_ttc'),
        'total_montant_ht' => BonCommandeR::sum('total_ht'),
        'moyenne_bon' => BonCommandeR::avg('total_ttc'),
        'total_ce_mois' => BonCommandeR::whereMonth('created_at', Carbon::now()->month)
                                       ->whereYear('created_at', Carbon::now()->year)
                                       ->sum('total_ttc'),
        'nombre_ce_mois' => BonCommandeR::whereMonth('created_at', Carbon::now()->month)
                                         ->whereYear('created_at', Carbon::now()->year)
                                         ->count(),
    ];

    // 11. STATISTIQUES PAR DEVISE
   
    // 12. TOP PRESTATAIRES
    $topPrestataires = BonCommandeR::selectRaw('prestataire, COUNT(*) as nombre_bons, SUM(total_ttc) as total_montant')
                                    ->groupBy('prestataire')
                                    ->orderByDesc('total_montant')
                                    ->limit(10)
                                    ->get();

    // 13. ÉVOLUTION MENSUELLE (12 derniers mois)
    $evolutionMensuelle = BonCommandeR::selectRaw('
            YEAR(created_at) as annee,
            MONTH(created_at) as mois,
            COUNT(*) as nombre,
            SUM(total_ttc) as montant
        ')
        ->where('created_at', '>=', Carbon::now()->subMonths(12))
        ->groupBy('annee', 'mois')
        ->orderBy('annee', 'desc')
        ->orderBy('mois', 'desc')
        ->get();

    // 14. RÉCUPÉRATION DES UTILISATEURS (pour filtre)
    $users = User::orderBy('name')->get();

    // Pagination avec conservation des paramètres de recherche
    $bonCommandes = $query->paginate($perPage)->withQueryString();

    return view('bon_commande_r.index', compact(
        'bonCommandes',
        'stats',
       
        'topPrestataires',
        'evolutionMensuelle',
        'users'
    ));
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