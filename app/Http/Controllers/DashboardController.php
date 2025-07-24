<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\Devis;
use App\Models\Facture;
use App\Models\Reussite;
use App\Models\Reussitef;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $filter = $request->get('filter', '');

        // الحصول على تاريخ اليوم الحالي
        $today = Carbon::today();

        // حساب العدد اليومي
        $dailyReussitesCount = Reussite::whereDate('created_at', $today)->count();
        $dailyfomationreCount = Reussitef::whereDate('created_at', $today)->count();
        $dailyDevisCount = Devis::whereDate('created_at', $today)->count();
        $dailyFacturesCount = Facture::whereDate('created_at', $today)->count();

        // البحث حسب الفلتر
        if ($filter === 'reussites') {
            $reussites = Reussite::where('CIN', 'like', "%{$search}%")->paginate(2);
        } else {
            $reussites = Reussite::paginate(2);
        }

        if ($filter === 'fomationre') {
            $fomationre = Reussitef::where('CIN', 'like', "%{$search}%")->paginate(2);
        } else {
            $fomationre = Reussitef::paginate(2);
        }

        if ($filter === 'devis') {
            $devis = Devis::where('devis_num', 'like', "%{$search}%")->paginate(2);
        } else {
            $devis = Devis::paginate(2);
        }

        if ($filter === 'factures') {
            $factures = Facture::where('ref', 'like', "%{$search}%")->paginate(2);
        } else {
            $factures = Facture::paginate(2);
        }

        return view('dashboard', compact(
            'reussites',
            'fomationre',
            'devis',
            'factures',
            'search',
            'filter',
            'dailyReussitesCount',  // العدد اليومي للنجاحات
            'dailyfomationreCount', // العدد اليومي للنجاحات في التكوين
            'dailyDevisCount',      // العدد اليومي لـ devis
            'dailyFacturesCount'    // العدد اليومي لـ factures
        ));
    }
}