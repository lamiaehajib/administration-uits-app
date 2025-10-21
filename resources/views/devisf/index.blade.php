<x-app-layout>
    <style>
        :root {
            --primary: #C2185B;
            --secondary: #D32F2F;
            --danger: #ef4444;
        }

        .gradient-bg {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        .stats-card {
            border-radius: 15px;
            padding: 25px;
            color: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            transition: all 0.5s;
        }

        .stats-card:hover::before {
            right: -60%;
        }

        .stats-card-1 { background: linear-gradient(135deg, #C2185B, #D32F2F); }
        .stats-card-2 { background: linear-gradient(135deg, #D32F2F, #ef4444); }
        .stats-card-3 { background: linear-gradient(135deg, #ef4444, #ff6b6b); }
        .stats-card-4 { background: linear-gradient(135deg, #8E24AA, #C2185B); }

        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
        }

        .table-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .badge-custom {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-dh {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-eur {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .action-btn {
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s;
            border: none;
            margin: 0 3px;
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

        .btn-duplicate {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.25);
        }

        .top-clients-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-top: 30px;
        }

        .client-item {
            padding: 15px;
            border-left: 4px solid var(--primary);
            background: #f8f9fa;
            margin-bottom: 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .client-item:hover {
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .stats-icon {
            font-size: 2.5rem;
            opacity: 0.3;
            position: absolute;
            right: 20px;
            bottom: 20px;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .stats-card {
                margin-bottom: 15px;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title gradient-text">
                <i class="fas fa-graduation-cap"></i> Gestion des Devis de Formation
            </h1>
            <p class="text-muted">Tableau de bord complet et statistiques avancées</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card stats-card-1">
                    <i class="fas fa-file-invoice stats-icon"></i>
                    <h6 class="mb-2 text-uppercase" style="font-size: 0.85rem; opacity: 0.9;">Total Devis</h6>
                    <h2 class="mb-0" style="font-weight: bold;">{{ $stats['total_devis'] ?? 0 }}</h2>
                    <small style="opacity: 0.8;">
                        <i class="fas fa-calendar-alt"></i> Ce mois: {{ $stats['devis_ce_mois'] ?? 0 }}
                    </small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card stats-card-2">
                    <i class="fas fa-coins stats-icon"></i>
                    <h6 class="mb-2 text-uppercase" style="font-size: 0.85rem; opacity: 0.9;">Montant Total HT</h6>
                    <h2 class="mb-0" style="font-weight: bold;">{{ number_format($stats['total_montant_ht'] ?? 0, 2) }}</h2>
                    <small style="opacity: 0.8;"><i class="fas fa-chart-line"></i> Chiffre d'affaires</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card stats-card-3">
                    <i class="fas fa-money-bill-wave stats-icon"></i>
                    <h6 class="mb-2 text-uppercase" style="font-size: 0.85rem; opacity: 0.9;">Montant Total TTC</h6>
                    <h2 class="mb-0" style="font-weight: bold;">{{ number_format($stats['total_montant_ttc'] ?? 0, 2) }}</h2>
                    <small style="opacity: 0.8;"><i class="fas fa-receipt"></i> Toutes taxes comprises</small>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="stats-card stats-card-4">
                    <i class="fas fa-chart-pie stats-icon"></i>
                    <h6 class="mb-2 text-uppercase" style="font-size: 0.85rem; opacity: 0.9;">Montant Moyen</h6>
                    <h2 class="mb-0" style="font-weight: bold;">{{ number_format($stats['montant_moyen'] ?? 0, 2) }}</h2>
                    <small style="opacity: 0.8;"><i class="fas fa-calculator"></i> Par devis</small>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('devisf.index') }}" id="filterForm">
                <div class="row g-3">
                    <!-- Recherche -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-search text-danger"></i> Recherche
                        </label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="N°, Client, Titre..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Période Rapide -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar text-danger"></i> Période Rapide
                        </label>
                        <select name="periode" class="form-select">
                            <option value="">Toutes les périodes</option>
                            <option value="aujourdhui" {{ request('periode') == 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                            <option value="cette_semaine" {{ request('periode') == 'cette_semaine' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="ce_mois" {{ request('periode') == 'ce_mois' ? 'selected' : '' }}>Ce mois</option>
                            <option value="ce_trimestre" {{ request('periode') == 'ce_trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                            <option value="cette_annee" {{ request('periode') == 'cette_annee' ? 'selected' : '' }}>Cette année</option>
                            <option value="mois_dernier" {{ request('periode') == 'mois_dernier' ? 'selected' : '' }}>Mois dernier</option>
                        </select>
                    </div>

                    <!-- Date Début -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar-day text-danger"></i> Date Début
                        </label>
                        <input type="date" name="date_debut" class="form-control" 
                               value="{{ request('date_debut') }}">
                    </div>

                    <!-- Date Fin -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar-check text-danger"></i> Date Fin
                        </label>
                        <input type="date" name="date_fin" class="form-control" 
                               value="{{ request('date_fin') }}">
                    </div>

                    <!-- Client -->
                    <div class="col-md-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-user-tie text-danger"></i> Client
                        </label>
                        <select name="client_filter" class="form-select">
                            <option value="">Tous les clients</option>
                            @foreach($clientsList ?? [] as $client)
                                <option value="{{ $client }}" {{ request('client_filter') == $client ? 'selected' : '' }}>
                                    {{ $client }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Devise -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">
                            <i class="fas fa-money-bill text-danger"></i> Devise
                        </label>
                        <select name="currency" class="form-select">
                            <option value="">Toutes</option>
                            <option value="DH" {{ request('currency') == 'DH' ? 'selected' : '' }}>DH</option>
                            <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>

                    <!-- Montant Min -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">
                            <i class="fas fa-arrow-down text-danger"></i> Montant Min
                        </label>
                        <input type="number" name="montant_min" class="form-control" 
                               placeholder="0" value="{{ request('montant_min') }}">
                    </div>

                    <!-- Montant Max -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">
                            <i class="fas fa-arrow-up text-danger"></i> Montant Max
                        </label>
                        <input type="number" name="montant_max" class="form-control" 
                               placeholder="999999" value="{{ request('montant_max') }}">
                    </div>

                    <!-- Tri -->
                    <div class="col-md-2">
                        <label class="form-label fw-bold">
                            <i class="fas fa-sort text-danger"></i> Trier par
                        </label>
                        <select name="sort_by" class="form-select">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date création</option>
                            <option value="date" {{ request('sort_by') == 'date' ? 'selected' : '' }}>Date devis</option>
                            <option value="devis_num" {{ request('sort_by') == 'devis_num' ? 'selected' : '' }}>N° Devis</option>
                            <option value="client" {{ request('sort_by') == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="total_ttc" {{ request('sort_by') == 'total_ttc' ? 'selected' : '' }}>Montant TTC</option>
                        </select>
                    </div>

                    <!-- Ordre -->
                    <div class="col-md-1">
                        <label class="form-label fw-bold">Ordre</label>
                        <select name="sort_order" class="form-select">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>↓</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>↑</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-gradient me-2">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('devisf.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
                        <a href="{{ route('devisf.create') }}" class="btn btn-gradient float-end">
                            <i class="fas fa-plus-circle"></i> Nouveau Devis
                        </a>
                         <a href="{{ route('devisf.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="gradient-text mb-0">
                    <i class="fas fa-list"></i> Liste des Devis ({{ $devisf->total() }})
                </h4>
                <div>
                    <button onclick="window.location.href='{{ route('devisf.index', array_merge(request()->all(), ['export' => 'csv'])) }}'" 
                            class="btn btn-sm btn-outline-success">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="gradient-bg text-white">
                        <tr>
                            <th><i class="fas fa-hashtag"></i> N° Devis</th>
                            <th><i class="fas fa-calendar"></i> Date</th>
                            <th><i class="fas fa-user"></i> Client</th>
                            <th><i class="fas fa-book"></i> Titre</th>
                            <th><i class="fas fa-coins"></i> Montant HT</th>
                            <th><i class="fas fa-percent"></i> TVA</th>
                            <th><i class="fas fa-money-bill-wave"></i> Montant TTC</th>
                            <th><i class="fas fa-money-check-alt"></i> Devise</th>
                            <th>Créé par</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($devisf as $devis)
                        <tr>
                            <td><strong class="text-primary">{{ $devis->devis_num }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($devis->date)->format('d/m/Y') }}</td>
                            <td><strong>{{ $devis->client }}</strong></td>
                            <td>{{ Str::limit($devis->titre, 30) }}</td>
                            <td>{{ number_format($devis->total_ht, 2) }}</td>
                            <td>{{ number_format($devis->tva, 2) }}</td>
                            <td><strong>{{ number_format($devis->total_ttc, 2) }}</strong></td>
                            <td>
                                <span class="badge badge-custom {{ $devis->currency == 'DH' ? 'badge-dh' : 'badge-eur' }}">
                                    {{ $devis->currency }}
                                </span>
                            </td>
                            <td>{{ $devis->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td>
                                <a href="{{ route('devisf.show', $devis) }}" class="action-btn btn-view" title="Voir PDF">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('devisf.edit', $devis) }}" class="action-btn btn-edit" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('devisf.duplicate', $devis) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="action-btn btn-duplicate" title="Dupliquer">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </form>
                                <a href="{{ route('facturefs.create_from_devisf', $devis->id) }}" class="btn btn-primary btn-sm">Ajouter Facture</a>
                                <form action="{{ route('devisf.destroy', $devis) }}" method="POST" style="display: inline;" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce devis ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun devis trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Affichage de {{ $devisf->firstItem() ?? 0 }} à {{ $devisf->lastItem() ?? 0 }} 
                    sur {{ $devisf->total() }} résultats
                </div>
                <div>
                    {{ $devisf->links('pagination.custom') }}
                </div>
            </div>
        </div>

        <!-- Top 5 Clients -->
        @if(isset($topClients) && $topClients->count() > 0)
        <div class="top-clients-card">
            <h4 class="gradient-text mb-4">
                <i class="fas fa-trophy"></i> Top 5 Clients (par CA)
            </h4>
            @foreach($topClients as $index => $client)
            <div class="client-item">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">
                            <span class="badge bg-danger me-2">{{ $index + 1 }}</span>
                            <strong>{{ $client->client }}</strong>
                        </h6>
                        <small class="text-muted">
                            <i class="fas fa-file-invoice"></i> {{ $client->nb_devis }} devis
                        </small>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-0 gradient-text">{{ number_format($client->total_montant, 2) }} DH</h5>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#C2185B',
            timer: 3000
        });
    </script>
    @endif
</x-app-layout>