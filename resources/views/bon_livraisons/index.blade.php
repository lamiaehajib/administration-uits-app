<x-app-layout>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .stats-card {
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stats-card.primary {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .stats-card.secondary {
            background: linear-gradient(135deg, #ef4444, #C2185B);
            color: white;
        }

        .stats-card.light {
            background: white;
            border-left: 4px solid #D32F2F;
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .table-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .btn-outline-gradient {
            border: 2px solid #D32F2F;
            color: #D32F2F;
            background: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: transparent;
        }

        .custom-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .custom-table thead th {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            padding: 15px 10px;
            border: none;
        }

        .custom-table thead th:first-child {
            border-top-left-radius: 10px;
        }

        .custom-table thead th:last-child {
            border-top-right-radius: 10px;
        }

        .custom-table tbody tr {
            transition: all 0.3s ease;
        }

        .custom-table tbody tr:hover {
            background: #fff5f7;
            transform: scale(1.01);
        }

        .custom-table tbody td {
            padding: 15px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge-tva-20 {
            background: linear-gradient(135deg, #ef4444, #C2185B);
            color: white;
        }

        .badge-tva-0 {
            background: #6c757d;
            color: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
        }

        .action-btn {
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
            margin: 0 2px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: #17a2b8;
            color: white;
        }

        .btn-edit {
            background: #ffc107;
            color: white;
        }

        .btn-download {
            background: #28a745;
            color: white;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #7f8c8d;
            font-size: 1rem;
        }

        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.9;
        }

        .filter-badge {
            background: #fff3f3;
            color: #D32F2F;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            margin: 5px;
            display: inline-block;
        }

        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 15px;
            }
            
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .action-btn {
                padding: 6px 10px;
                font-size: 0.85rem;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="page-title gradient-text">
                        <i class="fas fa-file-invoice"></i> Gestion des Bons de Livraison
                    </h1>
                    <p class="page-subtitle">Gérez et suivez tous vos bons de livraison</p>
                </div>
                <div>
                    <a href="{{ route('bon_livraisons.create') }}" class="btn btn-gradient">
                        <i class="fas fa-plus-circle"></i> Nouveau Bon
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Bons</h6>
                            <h2 class="mb-0">{{ number_format($stats['total_count']) }}</h2>
                        </div>
                        <i class="fas fa-file-alt stats-icon"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card secondary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Montant Total</h6>
                            <h2 class="mb-0">{{ number_format($stats['total_amount'], 2) }} DH</h2>
                        </div>
                        <i class="fas fa-coins stats-icon"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Ce Mois</h6>
                            <h2 class="mb-0 gradient-text">{{ number_format($stats['monthly_count']) }}</h2>
                        </div>
                        <i class="fas fa-calendar-alt stats-icon" style="color: #D32F2F;"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card light">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Mois (DH)</h6>
                            <h2 class="mb-0 gradient-text">{{ number_format($stats['monthly_amount'], 2) }}</h2>
                        </div>
                        <i class="fas fa-chart-line stats-icon" style="color: #ef4444;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtered Stats (if filters applied) -->
        @if($search || $dateFrom || $dateTo || $clientFilter || $minAmount || $maxAmount || $tvaFilter !== null || $userFilter)
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-card">
                    <h5 class="gradient-text mb-3"><i class="fas fa-filter"></i> Résultats Filtrés</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Nombre de bons :</strong> <span class="text-primary">{{ number_format($filteredStats['filtered_count']) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Montant total :</strong> <span class="text-success">{{ number_format($filteredStats['filtered_amount'], 2) }} DH</span></p>
                        </div>
                    </div>
                    
                    <!-- Active Filters Display -->
                    <div class="mt-3">
                        <strong>Filtres actifs :</strong>
                        @if($search)
                            <span class="filter-badge"><i class="fas fa-search"></i> {{ $search }}</span>
                        @endif
                        @if($dateFrom)
                            <span class="filter-badge"><i class="fas fa-calendar"></i> Du: {{ $dateFrom }}</span>
                        @endif
                        @if($dateTo)
                            <span class="filter-badge"><i class="fas fa-calendar"></i> Au: {{ $dateTo }}</span>
                        @endif
                        @if($clientFilter)
                            <span class="filter-badge"><i class="fas fa-user"></i> {{ $clientFilter }}</span>
                        @endif
                        @if($minAmount)
                            <span class="filter-badge"><i class="fas fa-money-bill"></i> Min: {{ $minAmount }} DH</span>
                        @endif
                        @if($maxAmount)
                            <span class="filter-badge"><i class="fas fa-money-bill"></i> Max: {{ $maxAmount }} DH</span>
                        @endif
                        @if($tvaFilter !== null)
                            <span class="filter-badge"><i class="fas fa-percent"></i> TVA: {{ $tvaFilter }}%</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filters Section -->
        <div class="filter-card">
            <form method="GET" action="{{ route('bon_livraisons.index') }}" id="filterForm">
                <div class="row g-3">
                    <!-- Search -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold"><i class="fas fa-search"></i> Recherche</label>
                        <input type="text" name="search" class="form-control" placeholder="N° Bon, Client, Titre..." value="{{ $search }}">
                    </div>

                    <!-- Date From -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold"><i class="fas fa-calendar-alt"></i> Date Début</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                    </div>

                    <!-- Date To -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold"><i class="fas fa-calendar-check"></i> Date Fin</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                    </div>

                    <!-- Client Filter -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold"><i class="fas fa-user"></i> Client</label>
                        <select name="client_filter" class="form-select">
                            <option value="">Tous les clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client }}" {{ $clientFilter == $client ? 'selected' : '' }}>
                                    {{ $client }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Min Amount -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold"><i class="fas fa-money-bill-wave"></i> Montant Min</label>
                        <input type="number" name="min_amount" class="form-control" placeholder="0.00" step="0.01" value="{{ $minAmount }}">
                    </div>

                    <!-- Max Amount -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold"><i class="fas fa-money-bill-wave"></i> Montant Max</label>
                        <input type="number" name="max_amount" class="form-control" placeholder="0.00" step="0.01" value="{{ $maxAmount }}">
                    </div>

                    <!-- TVA Filter -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold"><i class="fas fa-percent"></i> TVA</label>
                        <select name="tva_filter" class="form-select">
                            <option value="">Tous</option>
                            <option value="0" {{ $tvaFilter === '0' ? 'selected' : '' }}>0%</option>
                            <option value="20" {{ $tvaFilter === '20' ? 'selected' : '' }}>20%</option>
                        </select>
                    </div>

                    <!-- User Filter -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-user-tie"></i> Créé par</label>
                        <select name="user_filter" class="form-select">
                            <option value="">Tous les utilisateurs</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $userFilter == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-list"></i> Par Page</label>
                        <select name="per_page" class="form-select" onchange="this.form.submit()">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('bon_livraisons.index') }}" class="btn btn-outline-gradient">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="table-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="gradient-text mb-0"><i class="fas fa-table"></i> Liste des Bons de Livraison</h5>
                <div>
                    <span class="badge bg-secondary">{{ $bonLivraisons->total() }} résultat(s)</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>
                                <a href="?sort_by=bon_num&sort_order={{ $sortBy == 'bon_num' && $sortOrder == 'asc' ? 'desc' : 'asc' }}&{{ http_build_query(request()->except(['sort_by', 'sort_order'])) }}" class="text-white text-decoration-none">
                                    N° Bon <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=date&sort_order={{ $sortBy == 'date' && $sortOrder == 'asc' ? 'desc' : 'asc' }}&{{ http_build_query(request()->except(['sort_by', 'sort_order'])) }}" class="text-white text-decoration-none">
                                    Date <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=client&sort_order={{ $sortBy == 'client' && $sortOrder == 'asc' ? 'desc' : 'asc' }}&{{ http_build_query(request()->except(['sort_by', 'sort_order'])) }}" class="text-white text-decoration-none">
                                    Client <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>Titre</th>
                            <th>Articles</th>
                            <th>TVA</th>
                            <th>
                                <a href="?sort_by=total_ht&sort_order={{ $sortBy == 'total_ht' && $sortOrder == 'asc' ? 'desc' : 'asc' }}&{{ http_build_query(request()->except(['sort_by', 'sort_order'])) }}" class="text-white text-decoration-none">
                                    Total HT <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>
                                <a href="?sort_by=total_ttc&sort_order={{ $sortBy == 'total_ttc' && $sortOrder == 'asc' ? 'desc' : 'asc' }}&{{ http_build_query(request()->except(['sort_by', 'sort_order'])) }}" class="text-white text-decoration-none">
                                    Total TTC <i class="fas fa-sort"></i>
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bonLivraisons as $bon)
                        <tr>
                            <td><strong class="text-primary">{{ $bon->bon_num }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($bon->date)->format('d/m/Y') }}</td>
                            <td><i class="fas fa-user-circle text-muted"></i> {{ $bon->client }}</td>
                            <td>{{ Str::limit($bon->titre, 30) }}</td>
                            <td>
                                <span class="badge bg-info">{{ $bon->items_count }} article(s)</span>
                            </td>
                            <td>
                                @php
                                    $tvaRate = $bon->total_ht > 0 ? ($bon->tva / $bon->total_ht * 100) : 0;
                                @endphp
                                <span class="badge badge-custom {{ $tvaRate > 0 ? 'badge-tva-20' : 'badge-tva-0' }}">
                                    {{ number_format($tvaRate, 0) }}%
                                </span>
                            </td>
                            <td><strong>{{ number_format($bon->total_ht, 2) }} DH</strong></td>
                            <td><strong class="text-success">{{ number_format($bon->total_ttc, 2) }} DH</strong></td>
                            <td>
                                <a href="{{ route('bon_livraisons.show', $bon->id) }}" class="btn btn-sm action-btn btn-view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('bon_livraisons.edit', $bon->id) }}" class="btn btn-sm action-btn btn-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('bon_livraisons.download', $bon->id) }}" class="btn btn-sm action-btn btn-download" title="Télécharger">
                                    <i class="fas fa-download"></i>
                                </a>
                                <form action="{{ route('bon_livraisons.destroy', $bon->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm action-btn btn-delete delete-btn" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun bon de livraison trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    <p class="text-muted mb-0">
                        Affichage de {{ $bonLivraisons->firstItem() ?? 0 }} à {{ $bonLivraisons->lastItem() ?? 0 }} 
                        sur {{ $bonLivraisons->total() }} résultats
                    </p>
                </div>
                <div>
                    {{ $bonLivraisons->links('pagination.custom') }}
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert for Delete Confirmation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirmation
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');
                    
                    Swal.fire({
                        title: 'Êtes-vous sûr?',
                        text: "Cette action est irréversible!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#D32F2F',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Oui, supprimer!',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Success message
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#D32F2F',
                    timer: 3000
                });
            @endif

            // Error message
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#D32F2F'
                });
            @endif
        });
    </script>
</x-app-layout>