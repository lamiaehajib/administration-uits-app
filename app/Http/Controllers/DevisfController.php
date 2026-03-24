<?php
 
namespace App\Http\Controllers;
 
use App\Models\Devisf;
use App\Models\DevisItemf;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
 
class DevisfController extends Controller
{
    public function index(Request $request)
    {
        $query = Devisf::with(['items', 'ImportantInfof', 'user']);
 
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('devis_num', 'like', "%{$search}%")
                  ->orWhere('client', 'like', "%{$search}%")
                  ->orWhere('titre', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('ref', 'like', "%{$search}%");
            });
        }
 
        if ($request->filled('date_debut')) {
            $query->whereDate('date', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date', '<=', $request->date_fin);
        }
 
        if ($periode = $request->input('periode')) {
            switch ($periode) {
                case 'aujourdhui':
                    $query->whereDate('date', today());
                    break;
                case 'cette_semaine':
                    $query->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'ce_mois':
                    $query->whereMonth('date', now()->month)->whereYear('date', now()->year);
                    break;
                case 'ce_trimestre':
                    $query->whereBetween('date', [now()->firstOfQuarter(), now()->lastOfQuarter()]);
                    break;
                case 'cette_annee':
                    $query->whereYear('date', now()->year);
                    break;
                case 'mois_dernier':
                    $query->whereMonth('date', now()->subMonth()->month)->whereYear('date', now()->subMonth()->year);
                    break;
            }
        }
 
        if ($request->filled('montant_min')) {
            $query->where('total_ttc', '>=', $request->montant_min);
        }
        if ($request->filled('montant_max')) {
            $query->where('total_ttc', '<=', $request->montant_max);
        }
        if ($request->filled('currency')) {
            $query->where('currency', $request->currency);
        }
        if ($request->filled('client_filter')) {
            $query->where('client', $request->client_filter);
        }
 
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $allowedSorts = ['devis_num', 'date', 'client', 'total_ht', 'total_ttc', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }
 
        $stats = [
            'total_devis'       => (clone $query)->count(),
            'total_montant_ht'  => (clone $query)->sum('total_ht'),
            'total_montant_ttc' => (clone $query)->sum('total_ttc'),
            'montant_moyen'     => (clone $query)->avg('total_ttc'),
            'devis_ce_mois'     => (clone $query)->whereMonth('date', now()->month)->whereYear('date', now()->year)->count(),
        ];
 
        $statsByDevise = Devisf::selectRaw('currency, COUNT(*) as count, SUM(total_ttc) as total')
            ->groupBy('currency')->get()->keyBy('currency');
 
        $topClients = Devisf::selectRaw('client, COUNT(*) as nb_devis, SUM(total_ttc) as total_montant')
            ->groupBy('client')->orderBy('total_montant', 'desc')->limit(5)->get();
 
        $evolutionMensuelle = Devisf::selectRaw('YEAR(date) as annee, MONTH(date) as mois, COUNT(*) as nombre, SUM(total_ttc) as montant')
            ->where('date', '>=', now()->subMonths(12))
            ->groupBy('annee', 'mois')->orderBy('annee', 'asc')->orderBy('mois', 'asc')->get();
 
        $clientsList = Devisf::distinct('client')->orderBy('client')->pluck('client');
 
        if ($request->input('export') === 'csv') {
            return $this->exportCSV($query->get());
        }
 
        $perPage = $request->input('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? $perPage : 10;
        $devisf = $query->paginate($perPage)->appends($request->except('page'));
 
        $chartData = [
            'labels'   => $evolutionMensuelle->map(fn($item) => Carbon::create($item->annee, $item->mois)->format('M Y')),
            'montants' => $evolutionMensuelle->pluck('montant'),
            'nombres'  => $evolutionMensuelle->pluck('nombre'),
        ];
 
        return view('devisf.index', compact('devisf', 'stats', 'statsByDevise', 'topClients', 'clientsList', 'chartData', 'evolutionMensuelle'));
    }
 
    private function exportCSV($devisf)
    {
        $filename = 'devis_formation_' . now()->format('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
 
        $callback = function () use ($devisf) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['N° Devis', 'Date', 'Client', 'Titre', 'Montant HT', 'TVA', 'Montant TTC', 'Devise']);
            foreach ($devisf as $devis) {
                fputcsv($file, [$devis->devis_num, $devis->date, $devis->client, $devis->titre, $devis->total_ht, $devis->tva, $devis->total_ttc, $devis->currency]);
            }
            fclose($file);
        };
 
        return response()->stream($callback, 200, $headers);
    }
 
    public function duplicate(Devisf $devisf)
    {
        $newDevisf             = $devisf->replicate();
        $newDevisf->devis_num  = null;
        $newDevisf->created_at = now();
        $newDevisf->updated_at = now();
        $newDevisf->user_id    = Auth::id();
        $newDevisf->save();
 
        $date                  = now()->format('dmy');
        $newDevisf->devis_num  = "{$newDevisf->id}{$date}";
        $newDevisf->save();
 
        foreach ($devisf->items as $item) {
            DevisItemf::create([
                'devis_id'          => $newDevisf->id,
                'libele'            => $item->libele,
                'formation'         => $item->formation,
                'nombre'            => $item->nombre,
                'nombre_de_jours'   => $item->nombre_de_jours,
                'prix_unitaire'     => $item->prix_unitaire,
                'remise'            => $item->remise, // montant fixe
                'prix_apres_remise' => $item->prix_apres_remise,
                'prix_total'        => $item->prix_total,
            ]);
        }
 
        foreach ($devisf->ImportantInfof as $info) {
            $newDevisf->ImportantInfof()->create(['info' => $info->info]);
        }
 
        return redirect()->route('devisf.index')->with('success', 'Devis de formation dupliqué avec succès!');
    }
 
    public function create()
    {
        return view('devisf.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'date'             => 'required|date',
            'titre'            => 'required|string|max:255',
            'client'           => 'required|string|max:255',
            'contact'          => 'nullable|string|max:255',
            'ref'              => 'nullable|string|max:255',
            'currency'         => 'required|in:DH,EUR,CFA',
            'libele'           => 'required|array',
            'type'             => 'required|array',
            'formation'        => 'nullable|array',
            'nombre'           => 'nullable|array',
            'nombre_de_jours'  => 'nullable|array',
            'prix_unitaire'    => 'required|array',
            // remise = montant fixe (pas de max:100)
            'remise'           => 'nullable|array',
            'remise.*'         => 'nullable|numeric|min:0',
            'important'        => 'nullable|array',
            'important.*'      => 'nullable|string|max:255',
        ]);
 
        $totalHT = 0;
 
        foreach ($request->libele as $key => $libele) {
            $type      = $request->type[$key];
            $unitPrice = (float) $request->prix_unitaire[$key];
            // remise = montant fixe en devise
            $remise    = (float) ($request->remise[$key] ?? 0);
            // Prix après remise = unitPrice - remise (montant fixe)
            $prixApresRemise = $unitPrice - $remise;
            if ($prixApresRemise < 0) $prixApresRemise = 0;
 
            if ($type === 'nombre') {
                $qty      = (int) ($request->nombre[$key] ?? 0);
                $rowTotal = $prixApresRemise * $qty;
            } elseif ($type === 'nombre_de_jours') {
                $qty      = (int) ($request->nombre_de_jours[$key] ?? 0);
                $rowTotal = $prixApresRemise * $qty;
            } else {
                $rowTotal = $prixApresRemise;
            }
 
            $totalHT += $rowTotal;
        }
 
        $tvaRate  = $request->tva ?? 0;
        $tva      = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $tva;
 
        $devisf = Devisf::create([
            'date'      => $request->date,
            'titre'     => $request->titre,
            'client'    => $request->client,
            'contact'   => $request->contact,
            'ref'       => $request->ref,
            'vide'      => $request->vide,
            'total_ht'  => $totalHT,
            'tva'       => $tva,
            'total_ttc' => $totalTTC,
            'user_id'   => auth()->id(),
            'currency'  => $request->currency,
        ]);
 
        $devisf->devis_num = $devisf->id . now()->format('dmy');
        $devisf->save();
 
        foreach ($request->libele as $key => $libele) {
            $type            = $request->type[$key];
            $unitPrice       = (float) $request->prix_unitaire[$key];
            $remise          = (float) ($request->remise[$key] ?? 0);
            $prixApresRemise = max(0, $unitPrice - $remise);
            $formation       = $request->formation[$key] ?? null;
            $nombre          = $request->nombre[$key] ?? null;
            $nombre_de_jours = $request->nombre_de_jours[$key] ?? null;
 
            if ($type === 'nombre') {
                $prixTotal = $prixApresRemise * (int) $nombre;
            } elseif ($type === 'nombre_de_jours') {
                $prixTotal = $prixApresRemise * (int) $nombre_de_jours;
            } else {
                $prixTotal = $prixApresRemise;
            }
 
            DevisItemf::create([
                'devis_id'          => $devisf->id,
                'libele'            => $libele,
                'formation'         => $formation,
                'nombre'            => $nombre,
                'nombre_de_jours'   => $nombre_de_jours,
                'prix_unitaire'     => $unitPrice,
                'remise'            => $remise,
                'prix_apres_remise' => $prixApresRemise,
                'prix_total'        => $prixTotal,
            ]);
        }
 
        if ($request->has('important')) {
            $importantData = collect($request->important)->filter()->map(fn($info) => ['info' => $info]);
            $devisf->ImportantInfof()->createMany($importantData);
        }
 
        return redirect()->route('devisf.index')->with('success', 'Devis créé avec succès!');
    }
 
    public function show(Devisf $devisf)
    {
        $devisf->load(['items', 'ImportantInfof']);
        $pdf = FacadePdf::loadView('devisf.show', compact('devisf'))->setPaper('a4', 'portrait');
        return $pdf->stream('devisf.pdf');
    }
 
    public function edit(Devisf $devisf)
    {
        return view('devisf.edit', compact('devisf'));
    }
 
    public function update(Request $request, Devisf $devisf)
    {
        $request->validate([
            'devis_num'        => 'required|string|max:255',
            'date'             => 'required|date',
            'titre'            => 'required|string|max:255',
            'client'           => 'required|string|max:255',
            'contact'          => 'nullable|string|max:255',
            'ref'              => 'nullable|string|max:255',
            'currency'         => 'required|in:DH,EUR,CFA',
            'libele'           => 'required|array',
            'type'             => 'required|array',
            'formation'        => 'nullable|array',
            'nombre'           => 'nullable|array',
            'nombre_de_jours'  => 'nullable|array',
            'prix_unitaire'    => 'required|array',
            'prix_unitaire.*'  => 'required|numeric|min:0',
            // remise = montant fixe
            'remise'           => 'nullable|array',
            'remise.*'         => 'nullable|numeric|min:0',
            'important'        => 'nullable|array',
            'important.*'      => 'nullable|string|max:255',
        ]);
 
        $totalHT = 0;
 
        foreach ($request->libele as $key => $libele) {
            $type            = $request->type[$key];
            $unitPrice       = (float) $request->prix_unitaire[$key];
            $remise          = (float) ($request->remise[$key] ?? 0);
            $prixApresRemise = max(0, $unitPrice - $remise);
 
            if ($type === 'nombre') {
                $qty      = (int) ($request->nombre[$key] ?? 0);
                $rowTotal = $prixApresRemise * $qty;
            } elseif ($type === 'nombre_de_jours') {
                $qty      = (int) ($request->nombre_de_jours[$key] ?? 0);
                $rowTotal = $prixApresRemise * $qty;
            } else {
                $rowTotal = $prixApresRemise;
            }
 
            $totalHT += $rowTotal;
        }
 
        $tvaRate  = $request->tva ?? 0;
        $tva      = $totalHT * ($tvaRate / 100);
        $totalTTC = $totalHT + $tva;
 
        $devisf->update([
            'devis_num' => $request->devis_num,
            'date'      => $request->date,
            'titre'     => $request->titre,
            'client'    => $request->client,
            'contact'   => $request->contact,
            'ref'       => $request->ref,
            'vide'      => $request->vide,
            'total_ht'  => $totalHT,
            'tva'       => $tva,
            'total_ttc' => $totalTTC,
            'currency'  => $request->currency,
        ]);
 
        $devisf->items()->delete();
 
        $items = [];
        foreach ($request->libele as $key => $libele) {
            $type            = $request->type[$key];
            $unitPrice       = (float) $request->prix_unitaire[$key];
            $remise          = (float) ($request->remise[$key] ?? 0);
            $prixApresRemise = max(0, $unitPrice - $remise);
            $formation       = $request->formation[$key] ?? null;
            $nombre          = $request->nombre[$key] ?? null;
            $nombre_de_jours = $request->nombre_de_jours[$key] ?? null;
 
            if ($type === 'nombre') {
                $prixTotal = $prixApresRemise * (int) $nombre;
            } elseif ($type === 'nombre_de_jours') {
                $prixTotal = $prixApresRemise * (int) $nombre_de_jours;
            } else {
                $prixTotal = $prixApresRemise;
            }
 
            $items[] = [
                'devis_id'          => $devisf->id,
                'libele'            => $libele,
                'formation'         => $formation,
                'nombre'            => $nombre,
                'nombre_de_jours'   => $nombre_de_jours,
                'prix_unitaire'     => $unitPrice,
                'remise'            => $remise,
                'prix_apres_remise' => $prixApresRemise,
                'prix_total'        => $prixTotal,
            ];
        }
 
        DevisItemf::insert($items);
 
        $devisf->ImportantInfof()->delete();
        if ($request->has('important')) {
            $importantData = collect($request->important)->filter()->map(fn($info) => ['info' => $info]);
            $devisf->ImportantInfof()->createMany($importantData);
        }
 
        return redirect()->route('devisf.index')->with('success', 'Devis mis à jour avec succès!');
    }
 
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
            $importantData = json_decode($devisf->important, true);
            if (is_array($importantData)) {
                $devisf->ImportantInfof()->delete();
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


