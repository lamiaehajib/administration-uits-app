<x-app-layout>
    <style>
        .payments-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.3);
        }

        .payments-header h3 {
            color: white;
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            -webkit-text-fill-color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-left: 4px solid #C2185B;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(211, 47, 47, 0.15);
        }

        .stat-card .stat-label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .filters-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .filters-card h5 {
            color: #D32F2F;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
            background: linear-gradient(135deg, #D32F2F, #C2185B);
        }

        .btn-danger {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .table {
            margin: 0;
        }

        .table thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .table thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
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
            padding: 15px;
            vertical-align: middle;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .badge-especes {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
        }

        .badge-carte {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .badge-cheque {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
        }

        .badge-virement {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
            color: white;
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.25);
        }

        .payment-amount {
            font-size: 1.1rem;
            font-weight: 600;
            color: #D32F2F;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .pagination {
            margin-top: 25px;
        }

        .pagination .page-link {
            color: #C2185B;
            border: 1px solid #e0e0e0;
            margin: 0 3px;
            border-radius: 8px;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: #C2185B;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: #C2185B;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .payments-header {
                padding: 20px;
            }

            .payments-header h3 {
                font-size: 1.5rem;
            }

            .table-responsive {
                border-radius: 12px;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Header -->
        <div class="payments-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="mb-2">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        Gestion des Paiements
                    </h3>
                    <p class="mb-0 opacity-75">Suivi complet des transactions et encaissements</p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('paiements.rapport') }}" class="btn btn-light">
                        <i class="fas fa-chart-line me-2"></i>
                        Rapport Détaillé
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistiques du jour -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">
                    <i class="fas fa-calendar-day me-2"></i>
                    Total Aujourd'hui
                </div>
                <div class="stat-value">
                    {{ number_format($stats['total_jour'], 2) }} DH
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">
                    <i class="fas fa-money-bill-alt me-2"></i>
                    Espèces
                </div>
                <div class="stat-value">
                    {{ number_format($stats['especes_jour'], 2) }} DH
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-label">
                    <i class="fas fa-credit-card me-2"></i>
                    Cartes
                </div>
                <div class="stat-value">
                    {{ number_format($stats['carte_jour'], 2) }} DH
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filters-card">
            <h5><i class="fas fa-filter me-2"></i>Filtres de Recherche</h5>
            <form method="GET" action="{{ route('paiements.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Mode de Paiement</label>
                        <select name="mode_paiement" class="form-select">
                            <option value="">Tous</option>
                            <option value="especes" {{ request('mode_paiement') == 'especes' ? 'selected' : '' }}>Espèces</option>
                            <option value="carte" {{ request('mode_paiement') == 'carte' ? 'selected' : '' }}>Carte</option>
                            <option value="cheque" {{ request('mode_paiement') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                            <option value="virement" {{ request('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date Début</label>
                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date Fin</label>
                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Rechercher</label>
                        <input type="text" name="search" class="form-control" placeholder="N° reçu, client..." value="{{ request('search') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('paiements.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tableau des paiements -->
        <div class="table-card">
            @if($paiements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>N° Reçu</th>
                                <th>Client</th>
                                <th>Montant</th>
                                <th>Mode</th>
                                <th>Référence</th>
                                <th>Utilisateur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paiements as $paiement)
                                <tr>
                                    <td>
                                        <i class="fas fa-calendar-alt me-2 text-muted"></i>
                                        {{ $paiement->date_paiement->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">{{ $paiement->date_paiement->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('recus.show', $paiement->recuUcg) }}" class="text-decoration-none fw-bold" style="color: #C2185B;">
                                            {{ $paiement->recuUcg->numero_recu }}
                                        </a>
                                    </td>
                                    <td>
                                        <i class="fas fa-user me-2 text-muted"></i>
                                        {{ $paiement->recuUcg->client_nom }}
                                    </td>
                                    <td>
                                        <span class="payment-amount">
                                            {{ number_format($paiement->montant, 2) }} DH
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $paiement->mode_paiement }}">
                                            <i class="fas fa-{{ $paiement->mode_paiement == 'especes' ? 'money-bill-wave' : ($paiement->mode_paiement == 'carte' ? 'credit-card' : ($paiement->mode_paiement == 'cheque' ? 'money-check' : 'university')) }} me-1"></i>
                                            {{ ucfirst($paiement->mode_paiement) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $paiement->reference ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-user-tie me-1"></i>
                                            {{ $paiement->user->name ?? '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('paiements.show', $paiement) }}" class="btn btn-sm btn-outline-primary" title="Détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($paiement->recuUcg->statut === 'en_cours')
                                                <form action="{{ route('paiements.destroy', $paiement) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center p-3">
                    {{ $paiements->links() }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-receipt"></i>
                    <h5 class="mt-3">Aucun paiement trouvé</h5>
                    <p class="text-muted">Aucun paiement ne correspond à vos critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#D32F2F'
            });
        </script>
    @endif
</x-app-layout>