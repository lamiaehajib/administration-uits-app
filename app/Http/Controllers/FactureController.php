<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\Facture;
use App\Models\FactureItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class FactureController extends Controller
{
    
public function index(Request $request)
{
    $query = Facture::with(['items', 'importantInfoo', 'user']);

    // 1. Recherche Multi-Critères Avancée
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

    // 2. Filtrage par Date (Plage de dates)
    if ($request->filled('date_debut')) {
        $query->whereDate('date', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->whereDate('date', '<=', $request->date_fin);
    }

    // 3. Filtrage par Période Rapide
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
            case 'mois_dernier':
                $query->whereMonth('date', now()->subMonth()->month)
                      ->whereYear('date', now()->subMonth()->year);
                break;
            case 'trimestre_dernier':
                $startOfLastQuarter = now()->subQuarter()->firstOfQuarter();
                $endOfLastQuarter = now()->subQuarter()->lastOfQuarter();
                $query->whereBetween('date', [$startOfLastQuarter, $endOfLastQuarter]);
                break;
        }
    }

    // 4. Filtrage par Montant (Min/Max)
    if ($request->filled('montant_min')) {
        $query->where('total_ttc', '>=', $request->montant_min);
    }
    if ($request->filled('montant_max')) {
        $query->where('total_ttc', '<=', $request->montant_max);
    }

    // 5. Filtrage par Devise
    if ($request->filled('currency')) {
        $query->where('currency', $request->currency);
    }

    // 6. Filtrage par Client
    if ($request->filled('client_filter')) {
        $query->where('client', $request->client_filter);
    }

    // 7. Filtrage par TVA
    if ($request->filled('tva_filter')) {
        $query->where('tva', '>', 0);
    }

    // 8. Filtrage par ICE (avoir ICE ou non)
    if ($request->filled('has_ice')) {
        if ($request->has_ice == 'oui') {
            $query->whereNotNull('ice')->where('ice', '!=', '');
        } else {
            $query->where(function($q) {
                $q->whereNull('ice')->orWhere('ice', '');
            });
        }
    }

    // 9. Filtrage par Cachet
    if ($request->filled('afficher_cachet')) {
        $query->where('afficher_cachet', $request->afficher_cachet);
    }

    // 10. Tri Dynamique
    $sortBy = $request->input('sort_by', 'created_at');
    $sortOrder = $request->input('sort_order', 'desc');
    
    $allowedSorts = ['facture_num', 'date', 'client', 'total_ht', 'total_ttc', 'created_at', 'tva'];
    if (in_array($sortBy, $allowedSorts)) {
        $query->orderBy($sortBy, $sortOrder);
    }

    // 11. Statistiques Globales (avant pagination)
    $stats = [
        'total_factures' => (clone $query)->count(),
        'total_montant_ht' => (clone $query)->sum('total_ht'),
        'total_montant_ttc' => (clone $query)->sum('total_ttc'),
        'total_tva' => (clone $query)->sum('tva'),
        'montant_moyen' => (clone $query)->avg('total_ttc'),
        'factures_ce_mois' => (clone $query)->whereMonth('date', now()->month)
                                            ->whereYear('date', now()->year)
                                            ->count(),
        'factures_avec_tva' => Facture::where('tva', '>', 0)->count(),
        'factures_sans_tva' => Facture::where('tva', 0)->count(),
    ];

    // 12. Statistiques par Devise
    $statsByDevise = Facture::selectRaw('currency, COUNT(*) as count, SUM(total_ht) as total_ht, SUM(total_ttc) as total_ttc, SUM(tva) as total_tva')
        ->groupBy('currency')
        ->get()
        ->keyBy('currency');

    // 13. Top 10 Clients (par montant total)
    $topClients = Facture::selectRaw('client, ice, COUNT(*) as nb_factures, SUM(total_ht) as total_ht, SUM(total_ttc) as total_ttc, SUM(tva) as total_tva')
        ->groupBy('client', 'ice')
        ->orderBy('total_ttc', 'desc')
        ->limit(10)
        ->get();

    // 14. Évolution mensuelle (12 derniers mois)
    $evolutionMensuelle = Facture::selectRaw('YEAR(date) as annee, MONTH(date) as mois, COUNT(*) as nombre, SUM(total_ht) as montant_ht, SUM(total_ttc) as montant_ttc, SUM(tva) as montant_tva')
        ->where('date', '>=', now()->subMonths(12))
        ->groupBy('annee', 'mois')
        ->orderBy('annee', 'asc')
        ->orderBy('mois', 'asc')
        ->get();

    // 15. Statistiques par Trimestre (année en cours)
    $statsTrimestre = Facture::selectRaw('QUARTER(date) as trimestre, COUNT(*) as nombre, SUM(total_ttc) as montant')
        ->whereYear('date', now()->year)
        ->groupBy('trimestre')
        ->orderBy('trimestre', 'asc')
        ->get()
        ->keyBy('trimestre');

    // 16. Liste des Clients Uniques (pour filtres)
    $clientsList = Facture::distinct('client')
        ->orderBy('client')
        ->pluck('client');

    // 17. Comparaison avec période précédente
    $currentMonthTotal = Facture::whereMonth('date', now()->month)
                                 ->whereYear('date', now()->year)
                                 ->sum('total_ttc');
    
    $lastMonthTotal = Facture::whereMonth('date', now()->subMonth()->month)
                              ->whereYear('date', now()->subMonth()->year)
                              ->sum('total_ttc');
    
    $evolutionPourcentage = $lastMonthTotal > 0 
        ? (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100 
        : 0;

    // 18. Factures par statut TVA
    $facturesAvecTVA = Facture::where('tva', '>', 0)->sum('total_ttc');
    $facturesSansTVA = Facture::where('tva', 0)->sum('total_ttc');

    // 19. Top 5 Titres de projets les plus fréquents
    $topTitres = Facture::selectRaw('titre, COUNT(*) as count, SUM(total_ttc) as total')
        ->groupBy('titre')
        ->orderBy('count', 'desc')
        ->limit(5)
        ->get();

    // 20. Export Excel/CSV (si demandé)
    if ($request->input('export') === 'excel') {
        return $this->exportExcel($query->get());
    }
    if ($request->input('export') === 'csv') {
        return $this->exportCSV($query->get());
    }
    if ($request->input('export') === 'pdf_liste') {
        return $this->exportPDFListe($query->get());
    }

    // 21. Pagination avec Nombre Variable
    $perPage = $request->input('per_page', 10);
    $perPage = in_array($perPage, [10, 25, 50, 100, 200]) ? $perPage : 10;
    $factures = $query->paginate($perPage)->appends($request->except('page'));

    // 22. Données pour Graphiques
    $chartData = [
        'labels' => $evolutionMensuelle->map(function($item) {
            return Carbon::create($item->annee, $item->mois)->format('M Y');
        }),
        'montants_ht' => $evolutionMensuelle->pluck('montant_ht'),
        'montants_ttc' => $evolutionMensuelle->pluck('montant_ttc'),
        'montants_tva' => $evolutionMensuelle->pluck('montant_tva'),
        'nombres' => $evolutionMensuelle->pluck('nombre'),
    ];

    // 23. Répartition TVA (pour graphique circulaire)
    $repartitionTVA = [
        'avec_tva' => $stats['factures_avec_tva'],
        'sans_tva' => $stats['factures_sans_tva'],
    ];

    return view('factures.index', compact(
        'factures',
        'stats',
        'statsByDevise',
        'topClients',
        'clientsList',
        'chartData',
        'evolutionMensuelle',
        'statsTrimestre',
        'evolutionPourcentage',
        'facturesAvecTVA',
        'facturesSansTVA',
        'topTitres',
        'repartitionTVA',
        'search'
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
    $filename = 'factures_projet_' . now()->format('Y-m-d_H-i-s') . '.csv';
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
        'Pragma' => 'no-cache',
        'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
        'Expires' => '0',
    ];

    $callback = function() use ($factures) {
        $file = fopen('php://output', 'w');
        
        // BOM UTF-8 pour Excel
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // En-têtes
        fputcsv($file, [
            'N° Facture', 
            'Date', 
            'Client', 
            'ICE',
            'Titre', 
            'Montant HT', 
            'TVA (%)',
            'Montant TVA',
            'Montant TTC', 
            'Devise',
            'Référence',
            'Adresse'
        ], ';');

        foreach ($factures as $facture) {
            fputcsv($file, [
                $facture->facture_num,
                $facture->date,
                $facture->client,
                $facture->ice ?? 'N/A',
                $facture->titre,
                number_format($facture->total_ht, 2, ',', ' '),
                $facture->tva > 0 ? '20%' : '0%',
                number_format($facture->tva, 2, ',', ' '),
                number_format($facture->total_ttc, 2, ',', ' '),
                $facture->currency,
                $facture->ref ?? 'N/A',
                $facture->adresse ?? 'N/A',
            ], ';');
        }
        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

// Fonction auxiliaire pour Export PDF Liste
private function exportPDFListe($factures)
{
    $stats = [
        'total' => $factures->count(),
        'total_ht' => $factures->sum('total_ht'),
        'total_tva' => $factures->sum('tva'),
        'total_ttc' => $factures->sum('total_ttc'),
    ];
    
    $pdf = FacadePdf::loadView('factures.export_liste', compact('factures', 'stats'))
                    ->setPaper('a4', 'landscape');
    
    return $pdf->download('liste_factures_' . now()->format('Y-m-d') . '.pdf');
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
