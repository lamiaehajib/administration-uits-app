<x-app-layout>
    <style>
        :root {
            --primary-pink: #C2185B;
            --primary-red: #D32F2F;
            --accent-red: #ef4444;
            --gradient-primary: linear-gradient(135deg, #C2185B, #D32F2F);
            --gradient-light: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
        }

        .page-header {
            background: var(--gradient-primary);
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.25);
        }

        .page-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-left: 4px solid var(--primary-pink);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: var(--gradient-light);
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(211, 47, 47, 0.2);
        }

        .stat-card.primary {
            border-left-color: var(--primary-red);
        }

        .stat-card.accent {
            border-left-color: var(--accent-red);
        }

        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 1;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            z-index: 1;
        }

        .filter-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .filter-title {
            font-size: 1.25rem;
            font-weight: 700;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.25);
        }

        .btn-primary {
            background: var(--gradient-primary);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-pink);
            color: var(--primary-pink);
            background: transparent;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        .table-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead {
            background: var(--gradient-primary);
        }

        .table thead th {
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 0.5px;
            border: none;
            padding: 1rem;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: var(--gradient-light);
            transform: scale(1.01);
        }

        .table tbody td {
            vertical-align: middle;
            padding: 1rem;
        }

        .badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .badge-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-duplicate {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .pagination {
            margin-top: 2rem;
        }

        .page-link {
            color: var(--primary-pink);
            border: 1px solid #e2e8f0;
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 6px;
        }

        .page-link:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        .page-item.active .page-link {
            background: var(--gradient-primary);
            border-color: transparent;
        }

        .top-clients-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .client-item {
            padding: 1rem;
            border-left: 3px solid var(--primary-pink);
            margin-bottom: 1rem;
            background: var(--gradient-light);
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .client-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(194, 24, 91, 0.15);
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .page-title {
                font-size: 1.5rem;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="fas fa-file-invoice"></i> Gestion des Factures
            </h1>
            <a href="{{ route('factures.create') }}" class="btn btn-light btn-lg">
                <i class="fas fa-plus-circle"></i> Nouvelle Facture
            </a>
                 <a href="{{ route('factures.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-label">
                <i class="fas fa-file-invoice"></i> Total Factures
            </div>
            <div class="stat-value">{{ number_format($stats['total_factures']) }}</div>
        </div>

        <div class="stat-card primary">
            <div class="stat-label">
                <i class="fas fa-money-bill-wave"></i> Montant HT
            </div>
            <div class="stat-value">{{ number_format($stats['total_montant_ht'], 2) }} DH</div>
        </div>

        <div class="stat-card accent">
            <div class="stat-label">
                <i class="fas fa-chart-line"></i> Montant TTC
            </div>
            <div class="stat-value">{{ number_format($stats['total_montant_ttc'], 2) }} DH</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">
                <i class="fas fa-percent"></i> Total TVA
            </div>
            <div class="stat-value">{{ number_format($stats['total_tva'], 2) }} DH</div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-section">
        <h3 class="filter-title">
            <i class="fas fa-filter"></i> Filtres de Recherche
        </h3>
        
        <form method="GET" action="{{ route('factures.index') }}">
            <div class="row g-3">
                <!-- Search -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Recherche</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="N°, Client, Titre, ICE..." 
                           value="{{ request('search') }}">
                </div>

                <!-- Date Debut -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Date Début</label>
                    <input type="date" name="date_debut" class="form-control" 
                           value="{{ request('date_debut') }}">
                </div>

                <!-- Date Fin -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Date Fin</label>
                    <input type="date" name="date_fin" class="form-control" 
                           value="{{ request('date_fin') }}">
                </div>

                <!-- Période -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Période</label>
                    <select name="periode" class="form-select">
                        <option value="">Toutes</option>
                        <option value="aujourdhui" {{ request('periode') == 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="cette_semaine" {{ request('periode') == 'cette_semaine' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="ce_mois" {{ request('periode') == 'ce_mois' ? 'selected' : '' }}>Ce mois</option>
                        <option value="cette_annee" {{ request('periode') == 'cette_annee' ? 'selected' : '' }}>Cette année</option>
                    </select>
                </div>

                <!-- Montant Min -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Montant Min</label>
                    <input type="number" name="montant_min" class="form-control" 
                           placeholder="0.00" value="{{ request('montant_min') }}">
                </div>

                <!-- Montant Max -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Montant Max</label>
                    <input type="number" name="montant_max" class="form-control" 
                           placeholder="0.00" value="{{ request('montant_max') }}">
                </div>

                <!-- Currency -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Devise</label>
                    <select name="currency" class="form-select">
                        <option value="">Toutes</option>
                        <option value="DH" {{ request('currency') == 'DH' ? 'selected' : '' }}>DH</option>
                        <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                    <a href="{{ route('factures.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-redo"></i> Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Container -->
    <div class="table-container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="filter-title mb-0">
                <i class="fas fa-list"></i> Liste des Factures
            </h4>
            <div class="d-flex gap-2">
                
                <a href="{{ route('factures.index', array_merge(request()->all(), ['export' => 'csv'])) }}" 
                   class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-file-csv"></i> CSV
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>N° Facture</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Titre</th>
                        <th>Montant HT</th>
                        <th>TVA</th>
                        <th>Montant TTC</th>
                        <th>Devise</th>
                        <th>Créé par</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($factures as $facture)
                    <tr>
                        <td><strong class="text-primary">{{ $facture->facture_num }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($facture->date)->format('d/m/Y') }}</td>
                        <td>{{ $facture->client }}</td>
                        <td>{{ Str::limit($facture->titre, 30) }}</td>
                        <td>{{ number_format($facture->total_ht, 2) }}</td>
                        
                        <td>
                            @if($facture->tva > 0)
                                <span class="badge badge-success">{{ number_format($facture->tva, 2) }}</span>
                            @else
                                <span class="badge badge-warning">0.00</span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($facture->total_ttc, 2) }}</strong></td>
                        <td><span class="badge" style="background: var(--gradient-primary)">{{ $facture->currency }}</span></td>
                         <td>{{ $facture->user->name ?? 'Utilisateur inconnu' }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('factures.show', $facture->id) }}" 
                                   class="action-btn btn-view" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('factures.edit', $facture->id) }}" 
                                   class="action-btn btn-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('factures.duplicate', $facture->id) }}" 
                                   class="action-btn btn-duplicate" title="Dupliquer">
                                    <i class="fas fa-copy"></i>
                                </a>
                                <form action="{{ route('factures.destroy', $facture->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" 
                                            title="Supprimer"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette facture?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune facture trouvée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $factures->links('pagination.custom') }}
        </div>
    </div>

    <!-- Top Clients Section -->
    @if($topClients->count() > 0)
    <div class="top-clients-section">
        <h3 class="filter-title">
            <i class="fas fa-users"></i> Top 10 Clients
        </h3>
        <div class="row">
            @foreach($topClients as $index => $client)
            <div class="col-md-6 mb-3">
                <div class="client-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $index + 1 }}. {{ $client->client }}</h6>
                            <small class="text-muted">{{ $client->nb_factures }} facture(s)</small>
                        </div>
                        <div class="text-end">
                            <div class="stat-value" style="font-size: 1.25rem">
                                {{ number_format($client->total_ttc, 2) }} DH
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</x-app-layout>