<x-app-layout>
    <style>
        /* Variables de couleurs */
        :root {
            --primary-pink: #C2185B;
            --primary-red: #D32F2F;
            --accent-red: #ef4444;
            --gradient-primary: linear-gradient(135deg, #C2185B, #D32F2F);
            --gradient-accent: linear-gradient(135deg, #ef4444, #D32F2F);
        }

        /* Container Principal */
        .page-container {
            background: #f8f9fa;
            padding: 20px;
        }

        /* Header Section */
        .page-header {
            background: var(--gradient-primary);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.3);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Cards Statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 5px solid var(--primary-pink);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .stat-card.red { border-left-color: var(--primary-red); }
        .stat-card.accent { border-left-color: var(--accent-red); }

        .stat-icon {
            font-size: 2.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin: 10px 0;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Filters Section */
        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .filters-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-red);
            margin-bottom: 20px;
            cursor: pointer;
        }

        .filters-title i {
            transition: transform 0.3s ease;
        }

        .filters-title.collapsed i {
            transform: rotate(-90deg);
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        }

        /* Buttons */
        .btn-gradient {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .btn-accent {
            background: var(--gradient-accent);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-accent:hover {
            transform: translateY(-2px);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-pink);
            color: var(--primary-pink);
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            background: white;
            transition: all 0.3s ease;
        }

        .btn-outline-custom:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        /* Table */
        .table-container {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead {
            background: var(--gradient-primary);
            color: white;
        }

        .table-custom thead th {
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            white-space: nowrap;
        }

        .table-custom thead th:first-child {
            border-top-left-radius: 10px;
        }

        .table-custom thead th:last-child {
            border-top-right-radius: 10px;
        }

        .table-custom tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-custom tbody tr:hover {
            background: #fff5f8;
            transform: scale(1.01);
        }

        .table-custom tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        /* Badges */
        .badge-custom {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-currency-dh {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .badge-currency-eur {
            background: linear-gradient(135deg, #ef4444, #f97316);
            color: white;
        }

        /* Action Buttons */
        .action-btn {
            padding: 8px 12px;
            border-radius: 8px;
            border: none;
            margin: 0 3px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: #2196F3;
            color: white;
        }

        .btn-edit {
            background: #FF9800;
            color: white;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-download {
            background: #4CAF50;
            color: white;
        }

        /* Pagination */
        .pagination {
            margin-top: 25px;
            justify-content: center;
            gap: 5px;
        }

        .pagination .page-link {
            border: 2px solid #e0e0e0;
            color: var(--primary-red);
            border-radius: 8px;
            padding: 10px 15px;
            margin: 0 3px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background: var(--gradient-primary);
            border-color: transparent;
        }

        /* Top Prestataires */
        .top-prestataires {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .prestataire-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid var(--primary-pink);
            transition: all 0.3s ease;
        }

        .prestataire-item:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .prestataire-rank {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-red);
            margin-right: 15px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .table-container {
                overflow-x: scroll;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-red);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <div class="page-container">
        <!-- Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <h1 class="page-title">
                    <i class="fas fa-file-invoice"></i> Gestion des Bons de Commande
                </h1>
                <div class="d-flex gap-2 mt-3 mt-md-0">
                    <a href="{{ route('bon_commande_r.create') }}" class="btn btn-gradient">
                        <i class="fas fa-plus-circle"></i> Nouveau Bon
                    </a>
                    <a href="{{ route('boncommandes.corbeille') }}" class="btn btn-outline-custom">
                        <i class="fas fa-trash"></i> Corbeille
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-file-invoice stat-icon"></i>
                <div class="stat-value">{{ $stats['total_bons'] }}</div>
                <div class="stat-label">Total Bons</div>
            </div>
            <div class="stat-card red">
                <i class="fas fa-coins stat-icon"></i>
                <div class="stat-value">{{ number_format($stats['total_montant_ttc'], 2) }}</div>
                <div class="stat-label">Montant Total TTC</div>
            </div>
            <div class="stat-card accent">
                <i class="fas fa-calendar-month stat-icon"></i>
                <div class="stat-value">{{ number_format($stats['total_ce_mois'], 2) }}</div>
                <div class="stat-label">Ce Mois ({{ $stats['nombre_ce_mois'] }} bons)</div>
            </div>
            <div class="stat-card">
                <i class="fas fa-chart-line stat-icon"></i>
                <div class="stat-value">{{ number_format($stats['moyenne_bon'], 2) }}</div>
                <div class="stat-label">Moyenne par Bon</div>
            </div>
        </div>

        <!-- Statistiques par Devise -->
        {{-- @if($statsCurrency->isNotEmpty())
        <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
            @foreach($statsCurrency as $currency)
            <div class="stat-card {{ $currency->currency == 'DH' ? 'red' : 'accent' }}">
                <i class="fas fa-money-bill-wave stat-icon"></i>
                <div class="stat-value">{{ number_format($currency->total, 2) }} {{ $currency->currency }}</div>
                <div class="stat-label">{{ $currency->count }} Bons en {{ $currency->currency }}</div>
            </div>
            @endforeach
        </div>
        @endif --}}

        <!-- Top Prestataires -->
        @if($topPrestataires->isNotEmpty())
        <div class="top-prestataires">
            <h3 class="filters-title mb-3">
                <i class="fas fa-trophy"></i> Top 10 Prestataires
            </h3>
            @foreach($topPrestataires as $index => $prestataire)
            <div class="prestataire-item">
                <div class="d-flex align-items-center">
                    <span class="prestataire-rank">#{{ $index + 1 }}</span>
                    <div>
                        <strong>{{ $prestataire->prestataire ?? 'Non défini' }}</strong>
                        <div class="text-muted small">{{ $prestataire->nombre_bons }} bon(s)</div>
                    </div>
                </div>
                <div class="text-end">
                    <strong class="text-success">{{ number_format($prestataire->total_montant, 2) }}</strong>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Filtres Avancés -->
        <div class="filters-section">
            <h3 class="filters-title" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                <i class="fas fa-chevron-down"></i> Filtres Avancés
            </h3>
            
            <div class="collapse show" id="filtersCollapse">
                <form method="GET" action="{{ route('bon_commande_r.index') }}">
                    <div class="filter-grid mb-3">
                        <!-- Recherche -->
                        <div>
                            <label class="form-label"><i class="fas fa-search"></i> Recherche</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Prestataire, N°, Titre..." 
                                   value="{{ request('search') }}">
                        </div>

                        <!-- Date Début -->
                        <div>
                            <label class="form-label"><i class="fas fa-calendar-alt"></i> Date Début</label>
                            <input type="date" name="date_debut" class="form-control" 
                                   value="{{ request('date_debut') }}">
                        </div>

                        <!-- Date Fin -->
                        <div>
                            <label class="form-label"><i class="fas fa-calendar-check"></i> Date Fin</label>
                            <input type="date" name="date_fin" class="form-control" 
                                   value="{{ request('date_fin') }}">
                        </div>

                        <!-- Période -->
                        <div>
                            <label class="form-label"><i class="fas fa-clock"></i> Période</label>
                            <select name="periode" class="form-select">
                                <option value="">Toutes</option>
                                <option value="aujourd_hui" {{ request('periode') == 'aujourd_hui' ? 'selected' : '' }}>Aujourd'hui</option>
                                <option value="cette_semaine" {{ request('periode') == 'cette_semaine' ? 'selected' : '' }}>Cette Semaine</option>
                                <option value="ce_mois" {{ request('periode') == 'ce_mois' ? 'selected' : '' }}>Ce Mois</option>
                                <option value="cette_annee" {{ request('periode') == 'cette_annee' ? 'selected' : '' }}>Cette Année</option>
                                <option value="mois_dernier" {{ request('periode') == 'mois_dernier' ? 'selected' : '' }}>Mois Dernier</option>
                                <option value="annee_derniere" {{ request('periode') == 'annee_derniere' ? 'selected' : '' }}>Année Dernière</option>
                            </select>
                        </div>

                        <!-- Montant Min -->
                        <div>
                            <label class="form-label"><i class="fas fa-dollar-sign"></i> Montant Min</label>
                            <input type="number" name="montant_min" class="form-control" 
                                   placeholder="0.00" step="0.01" 
                                   value="{{ request('montant_min') }}">
                        </div>

                        <!-- Montant Max -->
                        <div>
                            <label class="form-label"><i class="fas fa-dollar-sign"></i> Montant Max</label>
                            <input type="number" name="montant_max" class="form-control" 
                                   placeholder="0.00" step="0.01" 
                                   value="{{ request('montant_max') }}">
                        </div>

                        {{-- <!-- Devise -->
                        <div>
                            <label class="form-label"><i class="fas fa-money-bill"></i> Devise</label>
                            <select name="currency" class="form-select">
                                <option value="">Toutes</option>
                                <option value="DH" {{ request('currency') == 'DH' ? 'selected' : '' }}>DH</option>
                                <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                            </select>
                        </div> --}}

                        <!-- TVA -->
                        <div>
                            <label class="form-label"><i class="fas fa-percent"></i> TVA</label>
                            <select name="tva" class="form-select">
                                <option value="">Toutes</option>
                                <option value="0" {{ request('tva') == '0' ? 'selected' : '' }}>0%</option>
                                <option value="10" {{ request('tva') == '10' ? 'selected' : '' }}>10%</option>
                                <option value="20" {{ request('tva') == '20' ? 'selected' : '' }}>20%</option>
                            </select>
                        </div>

                        <!-- Utilisateur -->
                        <div>
                            <label class="form-label"><i class="fas fa-user"></i> Créé par</label>
                            <select name="user_id" class="form-select">
                                <option value="">Tous</option>
                                @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tri -->
                        <div>
                            <label class="form-label"><i class="fas fa-sort"></i> Trier par</label>
                            <select name="sort_by" class="form-select">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Création</option>
                                <option value="date" {{ request('sort_by') == 'date' ? 'selected' : '' }}>Date Bon</option>
                                <option value="bon_num" {{ request('sort_by') == 'bon_num' ? 'selected' : '' }}>N° Bon</option>
                                <option value="prestataire" {{ request('sort_by') == 'prestataire' ? 'selected' : '' }}>Prestataire</option>
                                <option value="total_ttc" {{ request('sort_by') == 'total_ttc' ? 'selected' : '' }}>Montant</option>
                            </select>
                        </div>

                        <!-- Direction Tri -->
                        <div>
                            <label class="form-label"><i class="fas fa-arrows-alt-v"></i> Direction</label>
                            <select name="sort_direction" class="form-select">
                                <option value="desc" {{ request('sort_direction') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                <option value="asc" {{ request('sort_direction') == 'asc' ? 'selected' : '' }}>Croissant</option>
                            </select>
                        </div>

                        <!-- Par Page -->
                        <div>
                            <label class="form-label"><i class="fas fa-list"></i> Par Page</label>
                            <select name="per_page" class="form-select">
                                <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('bon_commande_r.index') }}" class="btn btn-outline-custom">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table des Bons -->
        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">
                    <i class="fas fa-list"></i> Liste des Bons ({{ $bonCommandes->total() }})
                </h3>
            </div>

            @if($bonCommandes->count() > 0)
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>N° Bon</th>
                            <th>Date</th>
                            <th>Prestataire</th>
                            <th>Titre</th>
                            <th>Montant HT</th>
                            <th>TVA</th>
                            <th>Montant TTC</th>
                          
                            <th>Créé par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bonCommandes as $bon)
                        <tr>
                            <td><strong>{{ $bon->bon_num }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($bon->date)->format('d/m/Y') }}</td>
                            <td>{{ $bon->prestataire ?? 'N/A' }}</td>
                            <td>{{ Str::limit($bon->titre, 30) }}</td>
                            <td>{{ number_format($bon->total_ht, 2) }}</td>
                            <td><span class="badge bg-info">{{ $bon->tva }}%</span></td>
                            <td><strong>{{ number_format($bon->total_ttc, 2) }}</strong></td>
                            
                            <td>{{ $bon->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <a href="{{ route('bon_commande_r.show', $bon->id) }}" 
                                       class="action-btn btn-view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bon_commande_r.edit', $bon->id) }}" 
                                       class="action-btn btn-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('bon_commande_r.pdf', $bon->id) }}" 
                                       class="action-btn btn-download" title="Télécharger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('bon_commande_r.destroy', $bon->id) }}" 
                                          method="POST" style="display:inline;" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bon?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $bonCommandes->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">Aucun bon de commande trouvé</h4>
                <p class="text-muted">Essayez de modifier vos critères de recherche</p>
            </div>
            @endif
        </div>
    </div>

    <!-- SweetAlert pour les messages -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#C2185B',
            timer: 3000
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erreur!',
            text: '{{ session("error") }}',
            confirmButtonColor: '#D32F2F'
        });
    </script>
    @endif
</x-app-layout>