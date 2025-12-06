<?php 

namespace App\Http\Controllers;

use App\Models\Attestationallinone;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttestationAllinoneController extends Controller
{
       public function index(Request $request)
    {
        // Récupérer les paramètres de recherche et filtres
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $afficherCachet = $request->input('afficher_cachet');

        // Statistiques globales
        $stats = [
            'total' => Attestationallinone::count(),
            'today' => Attestationallinone::whereDate('created_at', today())->count(),
            'this_month' => Attestationallinone::whereMonth('created_at', now()->month)
                           ->whereYear('created_at', now()->year)->count(),
            'with_cachet' => Attestationallinone::where('afficher_cachet', true)->count(),
        ];

        // Query builder avec filtres avancés
        $attestationsQuery = Attestationallinone::query()
            ->with('user') // Eager loading pour optimiser
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('personne_name', 'like', "%{$search}%")
                      ->orWhere('numero_de_serie', 'like', "%{$search}%")
                      ->orWhere('cin', 'like', "%{$search}%");
                });
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('created_at', '<=', $dateTo);
            })
            ->when($afficherCachet !== null, function ($query) use ($afficherCachet) {
                return $query->where('afficher_cachet', $afficherCachet);
            })
            ->orderBy($sortBy, $sortOrder);

        // Pagination
        $attestations = $attestationsQuery->paginate($perPage)->withQueryString();

        // Graphique des données (dernier 7 jours)
        $chartData = Attestationallinone::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Retourner la vue avec toutes les données
        return view('attestations_allinone.index', compact(
            'attestations',
            'search',
            'stats',
            'chartData',
            'perPage',
            'sortBy',
            'sortOrder',
            'dateFrom',
            'dateTo',
            'afficherCachet'
        ));
    }

    
    

    public function create()
    {
        return view('attestations_allinone.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'personne_name' => 'required',
            'cin' => 'required',
            'afficher_cachet' => 'boolean', // Validjf  de afficher_cachet
        ]);

        $personneName = $request->input('personne_name');
        $cin = $request->input('cin');
        $afficherCachet = $request->boolean('afficher_cachet', true); // Valeur par défaut true

        // Générer le numéro de série
        $numbers = preg_replace('/[^0-9]/', '', $cin); // Extraire les chiffres
        $letters = preg_replace('/[^A-Za-z]/', '', $cin); // Extraire les lettres
        $date = now()->format('dm'); // Jour et mois
        $year = now()->format('y');  // Année en deux chiffres (24 pour 2024)

        $numeroDeSerie = $numbers . $letters . $date . $year;

        Attestationallinone::create([
            'personne_name' => $personneName,
            'cin' => $cin,
            'numero_de_serie' => $numeroDeSerie, // Sauvegarder le numéro de série
            'user_id' => auth()->id(), // ربط المستخدم الذي قام بإنشاء السجل
            'afficher_cachet' => $afficherCachet,
        ]);

        return redirect()->route('attestations_allinone.index')->with('success', 'Attestation créée avec succès.');
    }

    public function edit(Attestationallinone $attestation)
    {
        return view('attestations_allinone.edit', compact('attestation'));
    }

    public function update(Request $request, Attestationallinone $attestation)
    {
        $request->validate([
            'personne_name' => 'required',
            'cin' => 'required',
            'afficher_cachet' => 'boolean', // Vérification de la valeur
        ]);

        // Mise à jour de l'attestation
        $attestation->update([
            'personne_name' => $request->input('personne_name'),
            'cin' => $request->input('cin'),
            'afficher_cachet' => $request->boolean('afficher_cachet'), // Mise à jour du cachet
        ]);

        return redirect()->route('attestations_allinone.index')->with('success', 'Attestation mise à jour avec succès.');
    }

    public function destroy(Attestationallinone $attestation)
    {
        $attestation->delete();
        return redirect()->route('attestations_allinone.index')->with('success', 'Attestation supprimée avec succès.');
    }

    public function generatePDF(Attestationallinone $attestation)
    {
        // Assurez-vous que DomPDF utilise une configuration plus simple si nécessaire
        $pdf = Pdf::loadView('attestations_allinone.pdf', compact('attestation'))->setPaper('a4', 'landscape');
        return $pdf->download('attestation_allinone.pdf');
    }
}
