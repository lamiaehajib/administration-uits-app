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

        .card-modern {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card.primary {
            border-color: #C2185B;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
        }

        .stat-card.success {
            border-color: #10b981;
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.05), rgba(5, 150, 105, 0.05));
        }

        .stat-card.warning {
            border-color: #f59e0b;
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.05), rgba(217, 119, 6, 0.05));
        }

        .stat-card.danger {
            border-color: #ef4444;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.05), rgba(220, 38, 38, 0.05));
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
            color: white;
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .form-control, .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        }

        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table-modern {
            margin: 0;
        }

        .table-modern thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .table-modern thead th {
            border: none;
            padding: 18px 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f3f4f6;
        }

        .table-modern tbody tr:hover {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.03), rgba(211, 47, 47, 0.03));
            transform: scale(1.01);
        }

        .table-modern tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 12px;
        }

        .badge-espece {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-virement {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .badge-cheque {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .badge-reste {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .badge-paye {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            margin: 0 3px;
        }

        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
        }

        .action-btn.btn-info {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .action-btn.btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .action-btn.btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .alert-custom {
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 15px;
            border-left: 4px solid;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.1), rgba(217, 119, 6, 0.1));
            border-left-color: #f59e0b;
            color: #92400e;
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1));
            border-left-color: #3b82f6;
            color: #1e40af;
        }

        .pagination {
            margin-top: 20px;
        }

        .page-link {
            border: 1px solid #e5e7eb;
            color: #C2185B;
            border-radius: 8px;
            margin: 0 3px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: #C2185B;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: #C2185B;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 15px;
            }
            
            .filter-card {
                padding: 15px;
            }
            
            .table-modern {
                font-size: 13px;
            }
            
            .action-btn {
                width: 30px;
                height: 30px;
                font-size: 12px;
            }
        }
    </style>

    <div class="container-fluid px-4">
        <!-- En-tête avec titre et bouton -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="gradient-text mb-0">
                <i class="fas fa-graduation-cap me-2"></i>
                Gestion des Reçus de Formation
            </h2>
            <a href="{{ route('reussitesf.create') }}" class="btn btn-gradient">
                <i class="fas fa-plus me-2"></i>Nouveau Reçu
            </a>
        </div>

        <!-- Alertes -->
        @if(isset($alertes) && count($alertes) > 0)
            @foreach($alertes as $alerte)
                <div class="alert alert-{{ $alerte['type'] }} alert-custom">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $alerte['message'] }}
                </div>
            @endforeach
        @endif

        <!-- Messages de succès -->
        @if(session('success'))
            <div class="alert alert-success alert-custom border-left-success">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Statistiques -->
        @if(isset($stats))
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Reçus</p>
                            <h3 class="mb-0 gradient-text">{{ $stats['total_reussites'] }}</h3>
                        </div>
                        <div class="stat-icon gradient-bg text-white">
                            <i class="fas fa-receipt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Montant Total</p>
                            <h3 class="mb-0 text-success">{{ number_format($stats['total_montant_paye'], 2) }} DH</h3>
                        </div>
                        <div class="stat-icon bg-success text-white">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Reste à Payer</p>
                            <h3 class="mb-0 text-warning">{{ number_format($stats['total_reste'], 2) }} DH</h3>
                        </div>
                        <div class="stat-icon bg-warning text-white">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card danger">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Avec Reste</p>
                            <h3 class="mb-0" style="color: #ef4444;">{{ $stats['avec_reste'] }}</h3>
                        </div>
                        <div class="stat-icon text-white" style="background: #ef4444;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Filtres Avancés -->
        <div class="filter-card">
            <h5 class="gradient-text mb-3">
                <i class="fas fa-filter me-2"></i>Filtres de Recherche
            </h5>
            
            <form method="GET" action="{{ route('reussitesf.index') }}">
                <div class="row g-3">
                    <!-- Recherche générale -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Recherche</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Nom, Prénom, CIN, Tél..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Formation -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Formation</label>
                        <select name="formation" class="form-select">
                            <option value="">Toutes les formations</option>
                            @if(isset($formationsDisponibles))
                                @foreach($formationsDisponibles as $form)
                                    <option value="{{ $form }}" {{ request('formation') == $form ? 'selected' : '' }}>
                                        {{ $form }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- Mode de paiement -->
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted">Mode de Paiement</label>
                        <select name="mode_paiement" class="form-select">
                            <option value="">Tous les modes</option>
                            <option value="espèce" {{ request('mode_paiement') == 'espèce' ? 'selected' : '' }}>Espèce</option>
                            <option value="virement" {{ request('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                            <option value="chèque" {{ request('mode_paiement') == 'chèque' ? 'selected' : '' }}>Chèque</option>
                        </select>
                    </div>

                    <!-- Date début -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Date Début</label>
                        <input type="date" name="date_debut" class="form-control" 
                               value="{{ request('date_debut') }}">
                    </div>

                    <!-- Date fin -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Date Fin</label>
                        <input type="date" name="date_fin" class="form-control" 
                               value="{{ request('date_fin') }}">
                    </div>

                    <!-- Statut paiement -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Statut Paiement</label>
                        <select name="statut_paiement" class="form-select">
                            <option value="">Tous</option>
                            <option value="paye" {{ request('statut_paiement') == 'paye' ? 'selected' : '' }}>Payé Complètement</option>
                            <option value="reste" {{ request('statut_paiement') == 'reste' ? 'selected' : '' }}>Avec Reste</option>
                        </select>
                    </div>

                    <!-- Par page -->
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted">Afficher</label>
                        <select name="per_page" class="form-select">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 par page</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 par page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 par page</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 par page</option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-gradient me-2">
                            <i class="fas fa-search me-2"></i>Rechercher
                        </button>
                        <a href="{{ route('reussitesf.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tableau des reçus -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-modern table-hover">
                    <thead>
                        <tr>
                            <th>
                                <a href="{{ route('reussitesf.index', array_merge(request()->all(), ['sort_by' => 'nom', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-white text-decoration-none">
                                    Nom & Prénom
                                    <i class="fas fa-sort ms-1"></i>
                                </a>
                            </th>
                            <th>CIN</th>
                            <th>Contact</th>
                            <th>
                                <a href="{{ route('reussitesf.index', array_merge(request()->all(), ['sort_by' => 'formation', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-white text-decoration-none">
                                    Formation
                                    <i class="fas fa-sort ms-1"></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{ route('reussitesf.index', array_merge(request()->all(), ['sort_by' => 'montant_paye', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-white text-decoration-none">
                                    Montant Payé
                                    <i class="fas fa-sort ms-1"></i>
                                </a>
                            </th>
                            <th>Reste</th>
                            <th>Mode Paiement</th>
                            <th>
                                <a href="{{ route('reussitesf.index', array_merge(request()->all(), ['sort_by' => 'date_paiement', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-white text-decoration-none">
                                    Date
                                    <i class="fas fa-sort ms-1"></i>
                                </a>
                            </th>
                              <th>
                                <a href="{{ route('reussitesf.index', array_merge(request()->all(), ['sort_by' => 'date_paiement', 'sort_order' => request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}" 
                                   class="text-white text-decoration-none">
                              Créé par
                                    <i class="fas fa-sort ms-1"></i>
                                </a>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fomationre as $reussite)
                        <tr>
                            <td class="fw-bold">{{ $reussite->nom }} {{ $reussite->prenom }}</td>
                            <td>{{ $reussite->CIN ?? 'N/A' }}</td>
                            <td>
                                <small class="d-block">
                                    <i class="fas fa-phone text-muted me-1"></i>{{ $reussite->tele ?? 'N/A' }}
                                </small>
                                <small class="d-block text-muted">
                                    <i class="fas fa-envelope me-1"></i>{{ $reussite->gmail ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $reussite->formation }}</span>
                            </td>
                            <td class="fw-bold text-success">{{ number_format($reussite->montant_paye, 2) }} DH</td>
                            <td>
                                @if($reussite->rest > 0)
                                    <span class="badge badge-reste">{{ number_format($reussite->rest, 2) }} DH</span>
                                @else
                                    <span class="badge badge-paye">Payé</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge badge-{{ $reussite->mode_paiement }}">
                                    {{ ucfirst($reussite->mode_paiement) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($reussite->date_paiement)->format('d/m/Y') }}</td>
                             <td>{{ $reussite->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td>
                                <a href="{{ route('reussitesf.pdf', $reussite->id) }}" 
                                   class="action-btn btn-info" 
                                   title="Télécharger PDF">
                                    <i class="fas fa-file-pdf text-white"></i>
                                </a>
                                <a href="{{ route('reussitesf.edit', $reussite->id) }}" 
                                   class="action-btn btn-warning" 
                                   title="Modifier">
                                    <i class="fas fa-edit text-white"></i>
                                </a>
                                <form action="{{ route('reussitesf.destroy', $reussite->id) }}" 
                                      method="POST" 
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="action-btn btn-danger" 
                                            title="Supprimer">
                                        <i class="fas fa-trash text-white"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun reçu trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center p-3">
                <div class="text-muted small">
                    {{ $fomationre->total() }} résultats
                </div>
                <div>
                    {{ $fomationre->links('pagination.custom') }}
                </div>
            </div>
        </div>

        <!-- Statistiques par formation -->
        @if(isset($statsFormations) && count($statsFormations) > 0)
        <div class="chart-card mt-4">
            <h5 class="gradient-text mb-3">
                <i class="fas fa-chart-bar me-2"></i>Top 5 Formations
            </h5>
            <div class="row">
                @foreach($statsFormations as $stat)
                <div class="col-md-4 mb-3">
                    <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                        <div>
                            <p class="mb-1 fw-bold">{{ $stat->formation }}</p>
                            <small class="text-muted">{{ $stat->count }} reçus</small>
                        </div>
                        <div class="text-end">
                            <p class="mb-0 fw-bold text-success">{{ number_format($stat->total, 2) }} DH</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <script>
        // Confirmation de suppression avec SweetAlert2
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#C2185B',
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

        // Auto-hide alerts après 5 secondes
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
</x-app-layout>