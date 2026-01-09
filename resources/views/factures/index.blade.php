<x-app-layout>
    <style>
        :root {
            --primary-pink: #C2185B;
            --primary-red: #D32F2F;
            --accent-red: #ef4444;
            --gradient-primary: linear-gradient(135deg, #C2185B, #D32F2F);
            --gradient-light: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            --gradient-blue: linear-gradient(135deg, #3b82f6, #2563eb);
            --gradient-green: linear-gradient(135deg, #10b981, #059669);
            --gradient-purple: linear-gradient(135deg, #8b5cf6, #7c3aed);
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

        .stat-card.blue { border-left-color: #3b82f6; }
        .stat-card.green { border-left-color: #10b981; }
        .stat-card.purple { border-left-color: #8b5cf6; }
        .stat-card.orange { border-left-color: #f59e0b; }

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

        .type-stats-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .type-card-stats {
            background: var(--gradient-light);
            border-radius: 10px;
            padding: 1.5rem;
            border-left: 4px solid var(--primary-pink);
        }

        .type-card-stats.service {
            border-left-color: #3b82f6;
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1));
        }

        .type-card-stats.produit {
            border-left-color: #10b981;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
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

        .table-container {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
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

        .table tbody tr:hover {
            background: var(--gradient-light);
            transform: scale(1.01);
        }

        .badge-type {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .badge-service {
            background: var(--gradient-blue);
            color: white;
        }

        .badge-produit {
            background: var(--gradient-green);
            color: white;
        }

        .top-produits-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .produit-item {
            padding: 1rem;
            border-left: 3px solid #10b981;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .produit-item:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.15);
        }

        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="page-title">
                <i class="fas fa-file-invoice"></i> Gestion des Factures
            </h1>
            <div class="d-flex gap-2">
                <a href="{{ route('factures.create') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-plus-circle"></i> Nouvelle Facture
                </a>
                <a href="{{ route('factures.corbeille') }}" class="btn btn-danger">
                    <i class="fa fa-trash"></i> Corbeille
                </a>
            </div>
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

        <div class="stat-card blue">
            <div class="stat-label">
                <i class="fas fa-briefcase"></i> Factures Services
            </div>
            <div class="stat-value">{{ number_format($stats['factures_services']) }}</div>
            <small class="text-muted">{{ number_format($stats['montant_services'], 2) }} DH</small>
        </div>

        <div class="stat-card green">
            <div class="stat-label">
                <i class="fas fa-box"></i> Factures Produits
            </div>
            <div class="stat-value">{{ number_format($stats['factures_produits']) }}</div>
            <small class="text-muted">{{ number_format($stats['montant_produits'], 2) }} DH</small>
        </div>

        <div class="stat-card orange">
            <div class="stat-label">
                <i class="fas fa-chart-line"></i> Montant TTC
            </div>
            <div class="stat-value">{{ number_format($stats['total_montant_ttc'], 2) }} DH</div>
        </div>

        @if(isset($statsMarges) && $statsMarges->marge_totale)
        <div class="stat-card purple">
            <div class="stat-label">
                <i class="fas fa-percentage"></i> Marge Produits
            </div>
            <div class="stat-value">{{ number_format($statsMarges->marge_totale, 2) }} DH</div>
            <small class="text-success">Taux: {{ number_format($tauxMarge, 1) }}%</small>
        </div>
        @endif
    </div>

    <!-- Stats par Type -->
    @if($statsByType->count() > 0)
    <div class="type-stats-section">
        <h3 class="filter-title">
            <i class="fas fa-chart-pie"></i> Répartition par Type
        </h3>
        <div class="row g-3">
            @foreach($statsByType as $type => $data)
            <div class="col-md-6">
                <div class="type-card-stats {{ $type }}">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">
                            @if($type === 'service')
                                <i class="fas fa-briefcase text-primary"></i> Services
                            @else
                                <i class="fas fa-box text-success"></i> Produits
                            @endif
                        </h5>
                        <span class="badge badge-type badge-{{ $type }}">
                            {{ $data->count }} facture(s)
                        </span>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <small class="text-muted d-block">Total HT</small>
                            <strong>{{ number_format($data->total_ht, 2) }} DH</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Total TTC</small>
                            <strong>{{ number_format($data->total_ttc, 2) }} DH</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">TVA</small>
                            <strong>{{ number_format($data->total_tva, 2) }} DH</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Moyenne</small>
                            <strong>{{ number_format($data->moyenne_ttc, 2) }} DH</strong>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="filter-section">
        <h3 class="filter-title">
            <i class="fas fa-filter"></i> Filtres de Recherche
        </h3>
        
        <form method="GET" action="{{ route('factures.index') }}">
            <div class="row g-3">
                <!-- Type Filter -->
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>
                            💼 Services
                        </option>
                        <option value="produit" {{ request('type') == 'produit' ? 'selected' : '' }}>
                            📦 Produits
                        </option>
                    </select>
                </div>

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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Devise</label>
                    <select name="currency" class="form-select">
                        <option value="">Toutes</option>
                        <option value="DH" {{ request('currency') == 'DH' ? 'selected' : '' }}>DH</option>
                        <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="CFA" {{ request('currency') == 'CFA' ? 'selected' : '' }}>CFA</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
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
            <a href="{{ route('factures.index', array_merge(request()->all(), ['export' => 'csv'])) }}" 
               class="btn btn-outline-primary btn-sm">
                <i class="fas fa-file-csv"></i> Exporter CSV
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Type</th>
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
                        <td>
                            <span class="badge-type badge-{{ $facture->type }}">
                                @if($facture->type === 'service')
                                    <i class="fas fa-briefcase"></i> Service
                                @else
                                    <i class="fas fa-box"></i> Produit
                                @endif
                            </span>
                        </td>
                        <td><strong class="text-primary">{{ $facture->facture_num }}</strong></td>
                        <td>{{ \Carbon\Carbon::parse($facture->date)->format('d/m/Y') }}</td>
                        <td>{{ $facture->client }}</td>
                        <td>{{ Str::limit($facture->titre, 30) }}</td>
                        <td>{{ number_format($facture->total_ht, 2) }}</td>
                        <td>
                            @if($facture->tva > 0)
                                <span class="badge bg-success">{{ number_format($facture->tva, 2) }}</span>
                            @else
                                <span class="badge bg-warning">0.00</span>
                            @endif
                        </td>
                        <td><strong>{{ number_format($facture->total_ttc, 2) }}</strong></td>
                        <td><span class="badge" style="background: var(--gradient-primary)">{{ $facture->currency }}</span></td>
                        <td>{{ $facture->user->name ?? 'Inconnu' }}</td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('factures.show', $facture->id) }}" 
                                   class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('factures.edit', $facture->id) }}" 
                                   class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('factures.duplicate', $facture->id) }}" 
                                   class="btn btn-sm btn-secondary" title="Dupliquer">
                                    <i class="fas fa-copy"></i>
                                </a>
                                <form action="{{ route('factures.destroy', $facture->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" 
                                            title="Supprimer"
                                            onclick="return confirm('Supprimer cette facture?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucune facture trouvée</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $factures->links() }}
        </div>
    </div>

    <!-- Top Produits (si factures produits) -->
    @if(isset($topProduits) && $topProduits->count() > 0)
    <div class="top-produits-section">
        <h3 class="filter-title">
            <i class="fas fa-trophy"></i> Top 10 Produits Vendus
        </h3>
        <div class="row">
            @foreach($topProduits as $index => $item)
            <div class="col-md-6 mb-3">
                <div class="produit-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1 fw-bold">
                                {{ $index + 1 }}. {{ $item->produit->nom ?? 'Produit supprimé' }}
                            </h6>
                            <small class="text-muted">
                                Quantité: {{ number_format($item->total_quantite) }} | 
                                {{ $item->nb_factures }} facture(s)
                            </small>
                        </div>
                        <div class="text-end">
                            <div class="stat-value" style="font-size: 1.25rem">
                                {{ number_format($item->total_ventes, 2) }} DH
                            </div>
                            <small class="text-success">
                                Marge: {{ number_format($item->total_marge, 2) }} DH
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Top Clients -->
    @if($topClients->count() > 0)
    <div class="top-produits-section">
        <h3 class="filter-title">
            <i class="fas fa-users"></i> Top 10 Clients
        </h3>
        <div class="row">
            @foreach($topClients as $index => $client)
            <div class="col-md-6 mb-3">
                <div class="produit-item" style="border-left-color: var(--primary-pink)">
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