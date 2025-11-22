<?php

namespace App\Http\Controllers;

use App\Models\RepairTicket;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RepairTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = RepairTicket::query();

        // 1. FILTRAGE AVANCÉ
        // -------------------
        // Filtre par status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par type d'appareil
        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        // Filtre par marque
        if ($request->filled('device_brand')) {
            $query->where('device_brand', $request->device_brand);
        }

        // Filtre par plage de dates
        if ($request->filled('date_from')) {
            $query->whereDate('date_depot', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_depot', '<=', $request->date_to);
        }

        // Filtre par montant (min/max)
        if ($request->filled('montant_min')) {
            $query->where('montant_total', '>=', $request->montant_min);
        }
        if ($request->filled('montant_max')) {
            $query->where('montant_total', '<=', $request->montant_max);
        }

        // Filtre tickets non payés (reste > 0)
        if ($request->boolean('unpaid_only')) {
            $query->whereRaw('montant_total > avance');
        }

        // Filtre tickets en retard
        if ($request->boolean('overdue_only')) {
            $query->where('estimated_completion', '<', now())
                  ->whereNotIn('status', ['termine', 'livre']);
        }

        // 2. RECHERCHE GLOBALE
        // --------------------
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_complet', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%")
                  ->orWhere('device_type', 'LIKE', "%{$search}%")
                  ->orWhere('device_brand', 'LIKE', "%{$search}%")
                  ->orWhere('problem_description', 'LIKE', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // 3. TRI DYNAMIQUE
        // ----------------
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $allowedSorts = ['id', 'nom_complet', 'date_depot', 'estimated_completion', 
                         'montant_total', 'status', 'created_at', 'device_type'];
        
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        // 4. STATISTIQUES DASHBOARD
        // -------------------------
        $stats = [
            'total_tickets' => RepairTicket::count(),
            'en_attente' => RepairTicket::where('status', 'en_attente')->count(),
            'en_cours' => RepairTicket::where('status', 'en_cours')->count(),
            'termine' => RepairTicket::where('status', 'termine')->count(),
            'livre' => RepairTicket::where('status', 'livre')->count(),
            
            // Stats financières
            'total_montant' => RepairTicket::sum('montant_total'),
            'total_avance' => RepairTicket::sum('avance'),
            'total_reste' => RepairTicket::sum(DB::raw('montant_total - avance')),
            
            // Stats du jour
            'tickets_today' => RepairTicket::whereDate('created_at', today())->count(),
            'revenue_today' => RepairTicket::whereDate('created_at', today())->sum('avance'),
            
            // Stats de la semaine
            'tickets_week' => RepairTicket::whereBetween('created_at', [
                now()->startOfWeek(), now()->endOfWeek()
            ])->count(),
            
            // Stats du mois
            'tickets_month' => RepairTicket::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year)->count(),
            'revenue_month' => RepairTicket::whereMonth('created_at', now()->month)
                                           ->whereYear('created_at', now()->year)->sum('avance'),
            
            // Tickets en retard
            'overdue_count' => RepairTicket::where('estimated_completion', '<', now())
                                           ->whereNotIn('status', ['termine', 'livre'])->count(),
            
            // Top appareils
            'top_devices' => RepairTicket::select('device_type', DB::raw('count(*) as count'))
                                         ->groupBy('device_type')
                                         ->orderByDesc('count')
                                         ->limit(5)
                                         ->get(),
            
            // Top marques
            'top_brands' => RepairTicket::select('device_brand', DB::raw('count(*) as count'))
                                        ->whereNotNull('device_brand')
                                        ->groupBy('device_brand')
                                        ->orderByDesc('count')
                                        ->limit(5)
                                        ->get(),
            
            // Moyenne de réparation (jours)
            'avg_repair_time' => RepairTicket::where('status', 'livre')
                                             ->whereNotNull('estimated_completion')
                                             ->avg(DB::raw('DATEDIFF(updated_at, date_depot)')),
        ];

        // 5. DONNÉES POUR LES FILTRES (Dropdowns)
        // ---------------------------------------
        $filterData = [
            'statuses' => ['en_attente', 'en_cours', 'termine', 'livre'],
            'device_types' => RepairTicket::distinct()->pluck('device_type')->filter(),
            'device_brands' => RepairTicket::distinct()->pluck('device_brand')->filter(),
        ];

        // 6. GRAPHIQUES DATA (7 derniers jours)
        // -------------------------------------
        $chartData = [
            'daily_tickets' => RepairTicket::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('count(*) as count')
                )
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
                
            'daily_revenue' => RepairTicket::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(avance) as total')
                )
                ->whereBetween('created_at', [now()->subDays(7), now()])
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
                
            'status_distribution' => RepairTicket::select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get(),
        ];

        // 7. ALERTES & NOTIFICATIONS
        // --------------------------
        $alerts = [];
        
        // Tickets en retard
        $overdueTickets = RepairTicket::where('estimated_completion', '<', now())
                                      ->whereNotIn('status', ['termine', 'livre'])
                                      ->get();
        if ($overdueTickets->count() > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "{$overdueTickets->count()} ticket(s) en retard!",
                'tickets' => $overdueTickets
            ];
        }
        
        // Tickets avec gros montant impayé (> 500)
        $bigUnpaid = RepairTicket::whereRaw('(montant_total - avance) > 500')
                                 ->where('status', '!=', 'livre')
                                 ->get();
        if ($bigUnpaid->count() > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "{$bigUnpaid->count()} ticket(s) avec reste important à payer",
                'tickets' => $bigUnpaid
            ];
        }

        // 8. EXPORT OPTION
        // ----------------
        if ($request->get('export') === 'csv') {
            return $this->exportCsv($query->get());
        }
        
        if ($request->get('export') === 'pdf') {
            return $this->exportPdfList($query->get());
        }

        // 9. PAGINATION AVEC QUERY STRINGS
        // ---------------------------------
        $tickets = $query->paginate($request->get('per_page', 10))
                         ->appends($request->query());

        return view('repair-tickets.index', compact(
            'tickets', 
            'stats', 
            'filterData', 
            'chartData', 
            'alerts'
        ));
    }

    // Helper: Export CSV
    private function exportCsv($tickets)
    {
        $filename = 'tickets-' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($tickets) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'ID', 'Client', 'Téléphone', 'Appareil', 'Marque', 
                'Problème', 'Date Dépôt', 'Montant', 'Avance', 'Reste', 'Status'
            ]);
            
            foreach ($tickets as $t) {
                fputcsv($file, [
                    $t->id, $t->nom_complet, $t->phone, $t->device_type,
                    $t->device_brand, $t->problem_description, $t->date_depot,
                    $t->montant_total, $t->avance, $t->montant_total - $t->avance,
                    $t->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Helper: Export PDF Liste
    private function exportPdfList($tickets)
    {
        $pdf = Pdf::loadView('repair-tickets.pdf-list', compact('tickets'));
        return $pdf->download('tickets-liste-' . now()->format('Y-m-d') . '.pdf');
    }

    public function create()
    {
        return view('repair-tickets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'device_type' => 'required|string|max:100',
            'device_brand' => 'nullable|string|max:100',
            'problem_description' => 'nullable|string',
            'date_depot' => 'required|date',
            'time_depot' => 'required',
            'estimated_completion' => 'nullable|date',
            'montant_total' => 'required|numeric|min:0',
            'avance' => 'required|numeric|min:0',
            'details' => 'nullable|string',
            'status' => 'required|in:en_attente,en_cours,termine,livre',
        ]);

        $validated['user_id'] = auth()->id();
        RepairTicket::create($validated);

        return redirect()->route('repair-tickets.index')
            ->with('success', 'Ticket créé avec succès!');
    }

    public function show(RepairTicket $repairTicket)
    {
        return view('repair-tickets.show', compact('repairTicket'));
    }

    public function edit(RepairTicket $repairTicket)
    {
        return view('repair-tickets.edit', compact('repairTicket'));
    }

    public function update(Request $request, RepairTicket $repairTicket)
    {
        $validated = $request->validate([
            'nom_complet' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'device_type' => 'required|string|max:100',
            'device_brand' => 'nullable|string|max:100',
            'problem_description' => 'nullable|string',
            'date_depot' => 'required|date',
            'time_depot' => 'required',
            'estimated_completion' => 'nullable|date',
            'montant_total' => 'required|numeric|min:0',
            'avance' => 'required|numeric|min:0',
            'details' => 'nullable|string',
            'status' => 'required|in:en_attente,en_cours,termine,livre',
        ]);

        $repairTicket->update($validated);

        return redirect()->route('repair-tickets.index')
            ->with('success', 'Ticket mis à jour!');
    }

    public function destroy(RepairTicket $repairTicket)
    {
        $repairTicket->delete();
        return redirect()->route('repair-tickets.index')
            ->with('success', 'Ticket supprimé!');
    }

    public function downloadPdf(RepairTicket $repairTicket)
    {
        $pdf = Pdf::loadView('repair-tickets.pdf', compact('repairTicket'));
        return $pdf->download('ticket-' . $repairTicket->id . '.pdf');
    }
}