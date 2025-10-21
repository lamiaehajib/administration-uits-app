<x-app-layout>
    <style>
        /* Variables de couleurs */
        :root {
            --primary-pink: #C2185B;
            --primary-red: #D32F2F;
            --accent-red: #ef4444;
            --gradient-primary: linear-gradient(135deg, #C2185B, #D32F2F);
            --gradient-light: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
        }

        /* Container Principal */
        .factures-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header Section */
        .factures-header {
            background: var(--gradient-primary);
            padding: 30px;
            color: white;
        }

        .factures-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 30px;
        }

        .stat-card {
            background: var(--gradient-light);
            border-left: 4px solid var(--primary-pink);
            padding: 20px;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(194, 24, 91, 0.2);
        }

        .stat-label {
            color: #666;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            color: var(--primary-red);
            font-size: 1.8rem;
            font-weight: 700;
        }

        /* Filters Section */
        .filters-section {
            background: #f8f9fa;
            padding: 25px 30px;
            border-top: 1px solid #e0e0e0;
            border-bottom: 1px solid #e0e0e0;
        }

        .filter-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: end;
        }

        .filter-group {
            flex: 1;
            min-width: 180px;
        }

        .filter-label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .filter-input, .filter-select {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        }

        /* Action Buttons */
        .btn-gradient {
            background: var(--gradient-primary);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9rem;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(194, 24, 91, 0.3);
        }

        .btn-outline {
            background: white;
            color: var(--primary-red);
            border: 2px solid var(--primary-red);
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-outline:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        /* Table Styles */
        .table-container {
            padding: 30px;
            overflow-x: auto;
        }

        .factures-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .factures-table thead th {
            background: var(--gradient-primary);
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
        }

        .factures-table thead th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .factures-table thead th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .factures-table tbody tr {
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .factures-table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(194, 24, 91, 0.15);
        }

        .factures-table tbody td {
            padding: 18px 15px;
            text-align: center;
            border: none;
            vertical-align: middle;
        }

        .factures-table tbody tr td:first-child {
            border-radius: 10px 0 0 10px;
        }

        .factures-table tbody tr td:last-child {
            border-radius: 0 10px 10px 0;
        }

        /* Badge Styles */
        .badge-currency {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .badge-dh {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .badge-eur {
            background: linear-gradient(135deg, #1976D2, #2196F3);
            color: white;
        }

        /* Action Icons */
        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 0 3px;
        }

        .action-btn.view {
            background: rgba(33, 150, 243, 0.1);
            color: #2196F3;
        }

        .action-btn.view:hover {
            background: #2196F3;
            color: white;
            transform: scale(1.1);
        }

        .action-btn.edit {
            background: rgba(255, 152, 0, 0.1);
            color: #FF9800;
        }

        .action-btn.edit:hover {
            background: #FF9800;
            color: white;
            transform: scale(1.1);
        }

        .action-btn.duplicate {
            background: rgba(76, 175, 80, 0.1);
            color: #4CAF50;
        }

        .action-btn.duplicate:hover {
            background: #4CAF50;
            color: white;
            transform: scale(1.1);
        }

        .action-btn.delete {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .action-btn.delete:hover {
            background: #ef4444;
            color: white;
            transform: scale(1.1);
        }

        /* Pagination */
        .pagination-container {
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e0e0e0;
        }

        .pagination {
            display: flex;
            gap: 5px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pagination .page-link {
            padding: 8px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            color: var(--primary-red);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        .pagination .active .page-link {
            background: var(--gradient-primary);
            color: white;
            border-color: transparent;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 30px;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .filter-row {
                flex-direction: column;
            }

            .filter-group {
                width: 100%;
            }

            .table-container {
                padding: 15px;
            }

            .factures-table {
                font-size: 0.85rem;
            }

            .factures-table tbody td,
            .factures-table thead th {
                padding: 10px 8px;
            }
        }
    </style>

    <div class="factures-container">
        <!-- Header avec titre -->
        <div class="factures-header">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
                <h1 class="factures-title">
                    <i class="fas fa-file-invoice"></i> Factures de Formation
                </h1>
                <a href="{{ route('facturefs.create') }}" class="btn-outline" style="background: white;">
                    <i class="fas fa-plus"></i> Nouvelle Facture
                </a>
                  <a href="{{ route('facturef.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Factures</div>
                <div class="stat-value">{{ $stats['total_factures'] ?? 0 }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Montant Total (DH)</div>
                <div class="stat-value">{{ number_format($stats['total_amount_dh'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Montant Total (EUR)</div>
                <div class="stat-value">{{ number_format($stats['total_amount_eur'] ?? 0, 2) }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Ce Mois</div>
                <div class="stat-value">{{ $stats['factures_this_month'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filters-section">
            <form method="GET" action="{{ route('facturefs.index') }}">
                <div class="filter-row">
                    <!-- Recherche -->
                    <div class="filter-group" style="flex: 2;">
                        <label class="filter-label">
                            <i class="fas fa-search"></i> Rechercher
                        </label>
                        <input type="text" name="search" class="filter-input" 
                               placeholder="N°, Client, Titre, Tél, ICE..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Date Début -->
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-calendar-alt"></i> Date Début
                        </label>
                        <input type="date" name="date_from" class="filter-input" 
                               value="{{ request('date_from') }}">
                    </div>

                    <!-- Date Fin -->
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-calendar-check"></i> Date Fin
                        </label>
                        <input type="date" name="date_to" class="filter-input" 
                               value="{{ request('date_to') }}">
                    </div>

                    <!-- Devise -->
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-money-bill-wave"></i> Devise
                        </label>
                        <select name="currency" class="filter-select">
                            <option value="">Toutes</option>
                            <option value="DH" {{ request('currency') == 'DH' ? 'selected' : '' }}>DH</option>
                            <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="filter-group" style="display: flex; gap: 10px;">
                        <button type="submit" class="btn-gradient">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('facturefs.index') }}" class="btn-outline">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>

                <!-- Filtres avancés (2ème ligne) -->
                <div class="filter-row" style="margin-top: 15px;">
                    <!-- Montant Min -->
                    <div class="filter-group">
                        <label class="filter-label">Montant Min</label>
                        <input type="number" name="min_amount" class="filter-input" 
                               placeholder="0.00" step="0.01" value="{{ request('min_amount') }}">
                    </div>

                    <!-- Montant Max -->
                    <div class="filter-group">
                        <label class="filter-label">Montant Max</label>
                        <input type="number" name="max_amount" class="filter-input" 
                               placeholder="0.00" step="0.01" value="{{ request('max_amount') }}">
                    </div>

                    <!-- TVA -->
                    <div class="filter-group">
                        <label class="filter-label">TVA</label>
                        <select name="tva_filter" class="filter-select">
                            <option value="">Toutes</option>
                            <option value="0" {{ request('tva_filter') === '0' ? 'selected' : '' }}>0%</option>
                            <option value="20" {{ request('tva_filter') === '20' ? 'selected' : '' }}>20%</option>
                        </select>
                    </div>

                    <!-- Tri -->
                    <div class="filter-group">
                        <label class="filter-label">Trier par</label>
                        <select name="sort_by" class="filter-select">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date Création</option>
                            <option value="date" {{ request('sort_by') == 'date' ? 'selected' : '' }}>Date Facture</option>
                            <option value="client" {{ request('sort_by') == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="total_ttc" {{ request('sort_by') == 'total_ttc' ? 'selected' : '' }}>Montant</option>
                        </select>
                    </div>

                    <!-- Export -->
                    <div class="filter-group" style="display: flex; gap: 10px;">
                        
                        <button type="submit" name="export" value="csv" class="btn-outline" style="white-space: nowrap;">
                            <i class="fas fa-file-csv"></i> CSV
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="table-container">
            @if($facturefs->count() > 0)
                <table class="factures-table">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Titre</th>
                            <th>Total HT</th>
                            <th>TVA</th>
                            <th>Total TTC</th>
                            <th>Devise</th>
                            <th>Créé par</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturefs as $facture)
                        <tr>
                            <td><strong>{{ $facture->facturef_num }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($facture->date)->format('d/m/Y') }}</td>
                            <td>{{ $facture->client }}</td>
                            <td>{{ Str::limit($facture->titre, 40) }}</td>
                            <td>{{ number_format($facture->total_ht, 2) }}</td>
                            <td>{{ number_format($facture->tva, 2) }}</td>
                            <td><strong>{{ number_format($facture->total_ttc, 2) }}</strong></td>
                            <td>
                                <span class="badge-currency {{ $facture->currency == 'DH' ? 'badge-dh' : 'badge-eur' }}">
                                    {{ $facture->currency }}
                                </span>
                            </td>
                            <td>{{ $facture->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td>
                                <div style="display: flex; justify-content: center; gap: 5px;">
                                    <a href="{{ route('facturefs.show', $facture->id) }}" 
                                       class="action-btn view" title="Voir PDF">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('facturefs.edit', $facture->id) }}" 
                                       class="action-btn edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('facturefs.duplicate', $facture->id) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="action-btn duplicate" title="Dupliquer">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('facturefs.destroy', $facture->id) }}" 
                                          method="POST" style="display: inline;" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h3>Aucune facture trouvée</h3>
                    <p>Commencez par créer votre première facture de formation</p>
                    <a href="{{ route('facturefs.create') }}" class="btn-gradient" style="display: inline-block; margin-top: 20px;">
                        <i class="fas fa-plus"></i> Créer une facture
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($facturefs->hasPages())
        <div class="pagination-container">
            <div>
                Affichage de {{ $facturefs->firstItem() }} à {{ $facturefs->lastItem() }} sur {{ $facturefs->total() }} factures
            </div>
            {{ $facturefs->links() }}
        </div>
        @endif
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
</x-app-layout>