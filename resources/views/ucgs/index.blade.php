<x-app-layout>
    <style>
        :root {
            --primary: #C2185B;
            --secondary: #D32F2F;
            --accent: #ef4444;
            --dark: #1a1a2e;
            --light: #f8f9fa;
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(211, 47, 47, 0.3);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .page-header h1 {
            color: white;
            font-weight: 800;
            font-size: 2.5rem;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            z-index: 1;
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 0 0;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
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
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0.05;
            border-radius: 0 15px 0 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(211, 47, 47, 0.2);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .stat-icon i {
            color: white;
            font-size: 24px;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Filters Section */
        .filters-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .filters-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .filters-header i {
            color: var(--primary);
            font-size: 1.3rem;
        }

        .filters-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--dark);
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.15);
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .btn-outline-gradient {
            border: 2px solid var(--primary);
            color: var(--primary);
            background: white;
            padding: 11px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-color: transparent;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .table-header h5 {
            margin: 0;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-header h5 i {
            color: var(--primary);
        }

        .table {
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .table thead th {
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            padding: 18px 15px;
            border: none;
            vertical-align: middle;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            transform: scale(1.01);
        }

        .table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            color: #495057;
            font-weight: 500;
        }

        .badge-garantie {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }

        .badge-90 {
            background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
            color: #d63031;
        }

        .badge-180 {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
        }

        .badge-360 {
            background: linear-gradient(135deg, #55efc4, #00b894);
            color: white;
        }

        .montant {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--secondary);
        }

        .action-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            margin: 0 3px;
        }

        .btn-view {
            background: linear-gradient(135deg, #74b9ff, #0984e3);
            color: white;
        }

        .btn-view:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(9, 132, 227, 0.4);
        }

        .btn-edit {
            background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
            color: #d63031;
        }

        .btn-edit:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(253, 203, 110, 0.4);
        }

        .btn-delete {
            background: linear-gradient(135deg, #ff7675, #d63031);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(214, 48, 49, 0.4);
        }

        .alert-expire {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border-left: 4px solid #fdcb6e;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .alert-expire i {
            font-size: 1.5rem;
            color: #d63031;
        }

        .pagination {
            margin-top: 25px;
        }

        .page-link {
            border: 2px solid #e9ecef;
            color: var(--primary);
            margin: 0 3px;
            border-radius: 8px;
            font-weight: 600;
        }

        .page-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-color: transparent;
        }

        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .table-responsive {
                border-radius: 10px;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-file-invoice"></i> Gestion des Reçus UCG</h1>
        <p>Gérez vos reçus de garantie avec facilité et efficacité</p>
    </div>

    <!-- Alert Expire Soon -->
    @if(isset($expireBientot) && $expireBientot > 0)
    <div class="alert-expire">
        <i class="fas fa-exclamation-triangle"></i>
        <div>
            <strong>Attention!</strong> {{ $expireBientot }} garantie(s) expirent dans les 30 prochains jours.
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    @if(isset($stats))
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-receipt"></i>
            </div>
            <div class="stat-label">Total Reçus</div>
            <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-label">Montant Total</div>
            <div class="stat-value">{{ number_format($stats['total_montant'] ?? 0, 2) }} DH</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-label">Ce Mois</div>
            <div class="stat-value">{{ $stats['total_mois'] ?? 0 }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-label">Revenu Mensuel</div>
            <div class="stat-value">{{ number_format($stats['montant_mois'] ?? 0, 2) }} DH</div>
        </div>
    </div>
    @endif

    <!-- Filters Card -->
    <div class="filters-card">
        <div class="filters-header">
            <i class="fas fa-filter"></i>
            <h5>Filtres de Recherche</h5>
        </div>
        <form method="GET" action="{{ route('ucgs.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold"><i class="fas fa-search"></i> Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Nom, prénom, équipement..." value="{{ $search ?? '' }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold"><i class="fas fa-calendar-day"></i> Date Début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ $dateDebut ?? '' }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold"><i class="fas fa-calendar-check"></i> Date Fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ $dateFin ?? '' }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold"><i class="fas fa-shield-alt"></i> Garantie</label>
                    <select name="garantie" class="form-select">
                        <option value="">Toutes</option>
                        <option value="90 jours" {{ ($garantie ?? '') == '90 jours' ? 'selected' : '' }}>90 jours</option>
                        <option value="180 jours" {{ ($garantie ?? '') == '180 jours' ? 'selected' : '' }}>180 jours</option>
                        <option value="360 jours" {{ ($garantie ?? '') == '360 jours' ? 'selected' : '' }}>360 jours</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold"><i class="fas fa-sort"></i> Trier par</label>
                    <select name="sort_by" class="form-select">
                        <option value="created_at" {{ ($sortBy ?? 'created_at') == 'created_at' ? 'selected' : '' }}>Date création</option>
                        <option value="date_paiement" {{ ($sortBy ?? '') == 'date_paiement' ? 'selected' : '' }}>Date paiement</option>
                        <option value="montant_paye" {{ ($sortBy ?? '') == 'montant_paye' ? 'selected' : '' }}>Montant</option>
                        <option value="nom" {{ ($sortBy ?? '') == 'nom' ? 'selected' : '' }}>Nom</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold"><i class="fas fa-money-bill-wave"></i> Montant</label>
                    <div class="row g-2">
                        <div class="col">
                            <input type="number" name="montant_min" class="form-control" placeholder="Min" value="{{ $montantMin ?? '' }}">
                        </div>
                        <div class="col">
                            <input type="number" name="montant_max" class="form-control" placeholder="Max" value="{{ $montantMax ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-gradient flex-fill">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('ucgs.index') }}" class="btn btn-outline-gradient flex-fill">
                        <i class="fas fa-redo"></i> Réinitialiser
                    </a>
                    <a href="{{ route('ucgs.create') }}" class="btn btn-gradient">
                        <i class="fas fa-plus"></i> Nouveau
                    </a>
                     <a href="{{ route('ucg.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        <div class="table-header">
            <h5><i class="fas fa-list"></i> Liste des Reçus ({{ $ucgs->total() }})</h5>
            <div>
                <select name="per_page" class="form-select form-select-sm" onchange="window.location.href='?per_page='+this.value+'&{{ http_build_query(request()->except('per_page')) }}'">
                    <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10 par page</option>
                    <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25 par page</option>
                    <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50 par page</option>
                    <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100 par page</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Complet</th>
                        <th>Équipement</th>
                        <th>Garantie</th>
                        <th>Montant</th>
                        <th>Date Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ucgs as $ucg)
                    <tr>
                        <td><strong>#{{ $ucg->id }}</strong></td>
                        <td><strong>{{ $ucg->nom }} {{ $ucg->prenom }}</strong></td>
                        <td>{{ $ucg->equipemen }}</td>
                        <td>
                            <span class="badge-garantie badge-{{ str_replace(' jours', '', $ucg->recu_garantie) }}">
                                {{ $ucg->recu_garantie }}
                            </span>
                        </td>
                        <td class="montant">{{ number_format($ucg->montant_paye, 2) }} DH</td>
                        <td>{{ \Carbon\Carbon::parse($ucg->date_paiement)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('ucgs.show', $ucg->id) }}" class="action-btn btn-view" title="Voir PDF">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <a href="{{ route('ucgs.edit', $ucg->id) }}" class="action-btn btn-edit" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('ucgs.destroy', $ucg->id) }}" method="POST" class="d-inline delete-form">
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
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun reçu trouvé</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $ucgs->links('pagination.custom') }}
        </div>
    </div>

    <!-- SweetAlert for Delete Confirmation -->
    <script>
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
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
            text: '{{ session("success") }}',
            timer: 3000,
            showConfirmButton: false
        });
        @endif
    </script>
</x-app-layout>