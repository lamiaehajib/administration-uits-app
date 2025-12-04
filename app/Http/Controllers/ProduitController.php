<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produit;
use App\Models\Category;
use App\Models\Achat;
use App\Models\RecuItem;
use App\Models\RecuUcg;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:produit-list|produit-create|produit-edit|produit-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:produit-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:produit-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:produit-delete', ['only' => ['destroy']]);
        $this->middleware('permission:produit-rapport', ['only' => ['rapport', 'getTotals']]);
        $this->middleware('permission:produit-export', ['only' => ['exportPDF']]);
    }

    /**
     * عرض جميع المنتجات مع معلومات المبيعات والمارج من RecuItem
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $produits = Produit::with(['category'])
            ->when($search, function ($query) use ($search) {
                return $query->where('nom', 'like', '%' . $search . '%')
                    ->orWhere('reference', 'like', '%' . $search . '%');
            })
            ->paginate(10);

        // إضافة حسابات إضافية لكل منتج
        foreach ($produits as $produit) {
            // حساب إجمالي الكمية المباعة
            $produit->quantite_vendue = RecuItem::where('produit_id', $produit->id)
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('quantite');

            // حساب إجمالي المبيعات
            $produit->total_vendu_montant = RecuItem::where('produit_id', $produit->id)
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('sous_total');

            // ✅ حساب إجمالي المارج (مع الremise)
            $produit->marge_totale = RecuItem::where('produit_id', $produit->id)
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('marge_totale');

            // آخر سعر شراء
            $dernierAchat = Achat::where('produit_id', $produit->id)
                ->latest('date_achat')
                ->first();
            $produit->dernier_prix_achat = $dernierAchat ? $dernierAchat->prix_achat : ($produit->prix_achat ?? 0);

            // الكاتيغوري
            $produit->categorie_nom = $produit->category ? $produit->category->nom : 'N/A';

            // المارج بالنسبة المئوية
            if ($produit->prix_achat > 0 && $produit->prix_vente > 0) {
                $produit->marge_pourcentage = (($produit->prix_vente - $produit->prix_achat) / $produit->prix_achat) * 100;
            } else {
                $produit->marge_pourcentage = 0;
            }
        }

        return view('produits.index', compact('produits'));
    }

    /**
     * إحصائيات المنتجات (المبيعات، المشتريات، المخزون)
     */
    public function getTotals(Request $request)
    {
        // التاريخ المحدد أو الشهر الحالي
        $date = $request->input('date', now()->format('Y-m'));
        
        $dateDebut = Carbon::parse($date . '-01')->startOfMonth();
        $dateFin = Carbon::parse($date . '-01')->endOfMonth();

        // إجمالي المشتريات
        $totalAchat = Achat::whereBetween('date_achat', [$dateDebut, $dateFin])
            ->sum('total_achat');

        // إجمالي المبيعات من RecuUcg (colonne total qui tient compte de remise)
        $totalVente = RecuUcg::whereBetween('created_at', [$dateDebut, $dateFin])
            ->whereIn('statut', ['en_cours', 'livre'])
            ->sum('total');

        // إجمالي المخزون الحالي
        $totalStock = Produit::sum('quantite_stock');

        // إجمالي قيمة المخزون (كمية × سعر الشراء)
        $valeurStock = DB::table('produits')
            ->whereNull('deleted_at')
            ->selectRaw('SUM(quantite_stock * COALESCE(prix_achat, 0)) as total')
            ->value('total') ?? 0;

        // ✅ إجمالي المارج الصحيح (مع الremise)
        $totalMarge = DB::table('recu_items')
            ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('recus_ucgs.deleted_at')
            ->sum('recu_items.marge_totale');

        // الربح = المارج
        $benefice = $totalMarge;

        return view('produits.totals', compact(
            'totalAchat',
            'totalVente',
            'totalStock',
            'valeurStock',
            'benefice',
            'totalMarge',
            'date'
        ));
    }

    /**
     * ✅ تصدير تقرير المبيعات PDF - CORRIGÉ
     * Affiche TOUS les produits actifs avec leurs ventes (même si 0)
     */
/**
 * ✅ MÉTHODE CORRIGÉE - Export PDF avec TOUS les produits vendus
 */
public function exportPDF(Request $request)
{
    $dateFin = $request->input('date') ? Carbon::parse($request->input('date'))->endOfMonth() : Carbon::now()->endOfMonth();
    $dateDebut = $dateFin->copy()->startOfMonth();

    // ✅ FIX: Jib TOUS les produits vendus directement avec leurs stats
    $produits = DB::table('produits')
        ->leftJoin('categories', 'produits.category_id', '=', 'categories.id')
        ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
        ->leftJoin('recus_ucgs', function($join) use ($dateDebut, $dateFin) {
            $join->on('recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
                ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
                ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
                ->whereNull('recus_ucgs.deleted_at');
        })
        ->whereNull('produits.deleted_at')
        ->whereNotNull('recus_ucgs.id') // ✅ Ghir produits li 3andhom ventes
        ->select(
            'produits.id',
            'produits.nom',
            'produits.reference',
            'produits.prix_achat',
            'categories.nom as categorie_nom',
            DB::raw('SUM(recu_items.quantite) as quantite_vendue'),
            DB::raw('SUM(recu_items.sous_total) as total_vendu_montant'),
            DB::raw('SUM(recu_items.marge_totale) as marge_totale')
        )
        ->groupBy('produits.id', 'produits.nom', 'produits.reference', 'produits.prix_achat', 'categories.nom')
        ->havingRaw('SUM(recu_items.quantite) > 0') // ✅ Ghir li ba3 
        ->orderByDesc('quantite_vendue')
        ->get();

    // Ajouter prix d'achat moyen pour chaque produit
    foreach ($produits as $produit) {
        $dernierAchat = Achat::where('produit_id', $produit->id)
            ->latest('date_achat')
            ->first();
        $produit->prix_achat_moyen = $dernierAchat ? $dernierAchat->prix_achat : ($produit->prix_achat ?? 0);
        $produit->categorie_nom = $produit->categorie_nom ?? 'N/A';
    }

    $pdf = Pdf::loadView('produits.rapport_ventes', compact('produits', 'dateDebut', 'dateFin'));
    return $pdf->download('rapport_ventes_' . $dateFin->format('Y-m-d') . '.pdf');
}

    /**
     * عرض نموذج إنشاء منتج جديد
     */
    public function create()
    {
        $categories = Category::all();
        return view('produits.create', compact('categories'));
    }

    /**
     * حفظ منتج جديد
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'nom' => 'required|string|max:255|unique:produits',
        'reference' => 'nullable|string|max:255|unique:produits',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'prix_achat' => 'nullable|numeric|min:0',
        'prix_vente' => 'required|numeric|min:0',
        'quantite_stock' => 'required|integer|min:0',
        'stock_alerte' => 'nullable|integer|min:0',
        'actif' => 'sometimes|boolean', // ✅ sometimes = اختياري
    ]);

    // ✅ معالجة actif
    $validatedData['actif'] = $request->boolean('actif', true); // default true
    
    // Générer référence si vide
    if (empty($request->reference)) {
        $lastId = Produit::max('id') ?? 0;
        $validatedData['reference'] = 'PROD-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT);
    }

    Produit::create($validatedData);

    return redirect()->route('produits.index')->with('success', 'Produit ajouté avec succès.');
}

    /**
     * عرض منتج محدد مع تفاصيل المبيعات والمشتريات
     */
    public function show($id)
    {
        $produit = Produit::with(['category', 'achats', 'stockMovements'])
            ->findOrFail($id);

        // إحصائيات المنتج
        $stats = [
            'total_achats' => Achat::where('produit_id', $id)->sum('quantite'),
            'total_ventes' => RecuItem::where('produit_id', $id)
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('quantite'),
            'valeur_stock' => $produit->quantite_stock * ($produit->prix_achat ?? 0),
            'ca_total' => RecuItem::where('produit_id', $id)
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('sous_total'),
            'marge_totale' => RecuItem::where('produit_id', $id)
                ->whereHas('recuUcg', function($q) {
                    $q->whereIn('statut', ['en_cours', 'livre']);
                })
                ->sum('marge_totale'),
        ];

        // جلب آخر الحركات
        $derniersAchats = Achat::where('produit_id', $id)
            ->latest('date_achat')
            ->take(5)
            ->get();

        $dernieresVentes = RecuItem::where('produit_id', $id)
            ->with('recuUcg')
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('produits.show', compact('produit', 'stats', 'derniersAchats', 'dernieresVentes'));
    }

    /**
     * عرض نموذج تعديل منتج
     */
    public function edit($id)
    {
        $produit = Produit::findOrFail($id);
        $categories = Category::all();
        return view('produits.edit', compact('produit', 'categories'));
    }

    /**
     * تحديث منتج معين
     */
    public function update(Request $request, Produit $produit)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255|unique:produits,nom,' . $produit->id,
            'reference' => 'nullable|string|max:255|unique:produits,reference,' . $produit->id,
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'prix_achat' => 'nullable|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'quantite_stock' => 'required|integer|min:0',
            'stock_alerte' => 'nullable|integer|min:0',
            'actif' => 'nullable|boolean',
        ]);

        $validatedData['actif'] = $request->has('actif') ? true : false;

        $produit->update($validatedData);

        return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * حذف منتج معين (SoftDelete)
     */
    public function destroy($id)
    {
        $produit = Produit::findOrFail($id);

        // التحقق من وجود مبيعات مرتبطة
        $hasVentes = RecuItem::where('produit_id', $id)->exists();
        
        if ($hasVentes) {
            return back()->with('error', 'Impossible de supprimer ce produit car il a des ventes associées.');
        }

        $produit->delete();

        return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * جلب المنتجات حسب الفئة (للاستخدام في AJAX)
     */
    public function getProduitsByCategory($category_id)
    {
        $produits = Produit::where('category_id', $category_id)
            ->where('actif', true)
            ->where('quantite_stock', '>', 0)
            ->select('id', 'nom', 'reference', 'prix_vente', 'quantite_stock', 'prix_achat')
            ->get();

        return response()->json($produits);
    }

    /**
     * تقرير أداء المنتجات (الأكثر مبيعاً، الأكثر ربحاً...)
     */
    public function rapport(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth());
        $dateFin = $request->input('date_fin', now()->endOfMonth());

        // ✅ المنتجات الأكثر مبيعاً (avec marge corrigée)
        $topVentes = DB::table('produits')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at')
            ->select(
                'produits.id',
                'produits.nom',
                'produits.reference',
                DB::raw('SUM(recu_items.quantite) as quantite_vendue'),
                DB::raw('SUM(recu_items.sous_total) as total_ventes'),
                DB::raw('SUM(recu_items.marge_totale) as marge_totale')
            )
            ->groupBy('produits.id', 'produits.nom', 'produits.reference')
            ->orderByDesc('quantite_vendue')
            ->take(10)
            ->get();

        // ✅ المنتجات الأكثر ربحاً (avec marge corrigée)
        $topMarges = DB::table('produits')
            ->leftJoin('recu_items', 'produits.id', '=', 'recu_items.produit_id')
            ->leftJoin('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
            ->whereBetween('recus_ucgs.created_at', [$dateDebut, $dateFin])
            ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
            ->whereNull('produits.deleted_at')
            ->whereNull('recus_ucgs.deleted_at')
            ->select(
                'produits.id',
                'produits.nom',
                'produits.reference',
                DB::raw('SUM(recu_items.marge_totale) as marge_totale'),
                DB::raw('SUM(recu_items.quantite) as quantite_vendue'),
                DB::raw('SUM(recu_items.sous_total) as total_ventes')
            )
            ->groupBy('produits.id', 'produits.nom', 'produits.reference')
            ->orderByDesc('marge_totale')
            ->take(10)
            ->get();

        // المنتجات في حالة تنبيه المخزون
        $alerteStock = Produit::whereColumn('quantite_stock', '<=', 'stock_alerte')
            ->where('actif', true)
            ->orderBy('quantite_stock')
            ->get();

        return view('produits.rapport', compact(
            'topVentes',
            'topMarges',
            'alerteStock',
            'dateDebut',
            'dateFin'
        ));
    }
}