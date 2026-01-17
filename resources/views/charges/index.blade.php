<x-app-layout>
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #D32F2F 0%, #C2185B 100%);
            --secondary-gradient: linear-gradient(135deg, #c73b4dff 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #cc4848ff 0%, #ff5757ff 100%);
            --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            --card-shadow: 0 10px 40px rgba(0,0,0,0.08);
            --card-hover-shadow: 0 15px 50px rgba(0,0,0,0.12);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* En-tête moderne */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
        }

        .page-title {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin: 0;
        }

        /* Cartes statistiques redesignées */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 1.75rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--card-hover-shadow);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            color: #667eea;
        }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a202c;
            margin: 0.5rem 0;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Filtres modernes */
        .filters-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.925rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        /* Boutons modernes */
        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.925rem;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary-modern {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-success-modern {
            background: var(--success-gradient);
            color: white;
        }

        .btn-success-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.3);
        }

        /* Alertes modernes */
        .modern-alert {
            border-radius: 16px;
            border: none;
            padding: 1.25rem 1.5rem;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .modern-alert-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .alert-danger .modern-alert-icon {
            background: linear-gradient(135deg, rgba(250, 112, 154, 0.15) 0%, rgba(254, 225, 64, 0.15) 100%);
            color: #f56565;
        }

        .alert-warning .modern-alert-icon {
            background: linear-gradient(135deg, rgba(251, 211, 141, 0.15) 0%, rgba(255, 154, 158, 0.15) 100%);
            color: #ed8936;
        }

        /* Table moderne */
        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table-header {
            background: var(--primary-gradient);
            color: white;
            padding: 1.5rem 2rem;
        }

        .table-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
        }

        .modern-table {
            margin: 0;
        }

        .modern-table thead th {
            background: #f7fafc;
            color: #4a5568;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1.25rem 1rem;
            border: none;
        }

        .modern-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: #f7fafc;
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 1.25rem 1rem;
            vertical-align: middle;
            border: none;
        }

        /* Badges modernes */
        .badge-modern {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
        }

        .badge-gradient-primary {
            background: var(--primary-gradient);
            color: white;
        }

        .badge-gradient-success {
            background: var(--success-gradient);
            color: white;
        }

        .badge-gradient-danger {
            background: var(--danger-gradient);
            color: white;
        }

        .badge-gradient-warning {
            background: linear-gradient(135deg, #fbd3e9 0%, #bb377d 100%);
            color: white;
        }

        /* Groupes de boutons d'action */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-action-info {
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.15) 0%, rgba(0, 242, 254, 0.15) 100%);
            color: #4facfe;
        }

        .btn-action-warning {
            background: linear-gradient(135deg, rgba(251, 211, 141, 0.15) 0%, rgba(255, 154, 158, 0.15) 100%);
            color: #ed8936;
        }

        .btn-action-success {
            background: linear-gradient(135deg, rgba(52, 211, 153, 0.15) 0%, rgba(16, 185, 129, 0.15) 100%);
            color: #10b981;
        }

        .btn-action-danger {
            background: linear-gradient(135deg, rgba(250, 112, 154, 0.15) 0%, rgba(254, 225, 64, 0.15) 100%);
            color: #f56565;
        }

        /* État vide */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #cbd5e0;
            margin-bottom: 1rem;
        }

        .empty-state-text {
            color: #a0aec0;
            font-size: 1.125rem;
            font-weight: 500;
        }

        /* Pagination moderne */
        .pagination {
            gap: 0.5rem;
        }

        .page-link {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            color: #4a5568;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .page-link:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
        }

        .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: transparent;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stat-card {
                margin-bottom: 1rem;
            }

            .filters-card {
                padding: 1.5rem;
            }

            .table-responsive {
                border-radius: 16px;
            }
        }
    </style>

    <div class="container-fluid px-4">
        <!-- En-tête -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-chart-line me-2"></i>
                        Gestion des Charges
                    </h1>
                    <p class="text-muted mb-0 mt-2">Suivez et gérez toutes vos dépenses en temps réel</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('charges.dashboard') }}" class="btn btn-modern btn-primary-modern">
                        <i class="fas fa-chart-pie"></i> Dashboard
                    </a>
                    <button type="button" class="btn btn-modern btn-success-modern" data-bs-toggle="modal" data-bs-target="#modalAjouterCharge">
                        <i class="fas fa-plus"></i> Nouvelle Charge
                    </button>
                    <button type="button" class="btn btn-modern btn-primary-modern" data-bs-toggle="modal" data-bs-target="#modalCategories">
                        <i class="fas fa-tags"></i> Catégories
                    </button>
                    <a href="{{ route('charges.export', [
        'date_debut' => $dateDebut,
        'date_fin' => $dateFin,
        'type' => $type,
        'category_id' => $categoryId,
        'statut' => $statut,
        'search' => $search
    ]) }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel me-1"></i> Exporter
    </a>

    {{-- <div class="btn-group">
    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown">
        <i class="fas fa-download me-1"></i> Exporter
    </button>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="{{ route('charges.export', array_merge(request()->all(), ['format' => 'excel'])) }}">
                <i class="fas fa-file-excel text-success me-2"></i> Excel (.xlsx)
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('charges.export', array_merge(request()->all(), ['format' => 'csv'])) }}">
                <i class="fas fa-file-csv text-info me-2"></i> CSV
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <span class="dropdown-item-text text-muted small">
                <i class="fas fa-info-circle me-1"></i> Export selon filtres actifs
            </span>
        </li>
    </ul>
</div> --}}
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Total Charges</div>
                            <div class="stat-value">{{ number_format($stats['total_charges'], 2) }} DH</div>
                            <small class="text-muted">{{ $stats['nombre_charges'] }} charge(s)</small>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Charges Fixes</div>
                            <div class="stat-value">{{ number_format($stats['total_fixe'], 2) }} DH</div>
                            <small class="text-muted">Récurrentes</small>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(118, 75, 162, 0.1) 0%, rgba(102, 126, 234, 0.1) 100%); color: #764ba2;">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Charges Variables</div>
                            <div class="stat-value">{{ number_format($stats['total_variable'], 2) }} DH</div>
                            <small class="text-muted">Ponctuelles</small>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%); color: #4facfe;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-label">Impayées</div>
                            <div class="stat-value">{{ number_format($stats['total_impaye'], 2) }} DH</div>
                            <small class="text-muted">{{ $stats['count_impaye'] }} charge(s)</small>
                        </div>
                        <div class="stat-icon" style="background: linear-gradient(135deg, rgba(250, 112, 154, 0.1) 0%, rgba(254, 225, 64, 0.1) 100%); color: #fa709a;">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filters-card">
            <form method="GET" action="{{ route('charges.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="fas fa-search me-1"></i> Recherche
                    </label>
                    <input type="text" name="search" class="form-control" placeholder="Libellé, référence..." value="{{ $search }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i> Date Début
                    </label>
                    <input type="date" name="date_debut" class="form-control" value="{{ $dateDebut }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt me-1"></i> Date Fin
                    </label>
                    <input type="date" name="date_fin" class="form-control" value="{{ $dateFin }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-filter me-1"></i> Type
                    </label>
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        <option value="fixe" {{ $type == 'fixe' ? 'selected' : '' }}>Fixe</option>
                        <option value="variable" {{ $type == 'variable' ? 'selected' : '' }}>Variable</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">
                        <i class="fas fa-tag me-1"></i> Catégorie
                    </label>
                    <select name="category_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-modern btn-primary-modern w-100">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Alertes -->
        @if($stats['charges_retard'] > 0)
        <div class="alert alert-danger modern-alert mb-4" role="alert">
            <div class="modern-alert-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div>
                <strong>Attention!</strong> Vous avez {{ $stats['charges_retard'] }} charge(s) en retard de paiement.
            </div>
        </div>
        @endif

        @if($stats['prochaines_echeances'] > 0)
        <div class="alert alert-warning modern-alert mb-4" role="alert">
            <div class="modern-alert-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <strong>Rappel:</strong> {{ $stats['prochaines_echeances'] }} échéance(s) à venir dans les 7 prochains jours.
            </div>
        </div>
        @endif

        <!-- Table -->
        <div class="table-card">
            <div class="table-header">
                <h5>
                    <i class="fas fa-list me-2"></i>
                    Liste des Charges ({{ $charges->total() }})
                </h5>
            </div>
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th>N° Réf</th>
                            <th>Libellé</th>
                            <th>Type</th>
                            <th>Catégorie</th>
                            <th>Date</th>
                            <th>Montant</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($charges as $charge)
                        <tr>
                            <td>
                                <span class="badge badge-modern badge-gradient-primary">{{ $charge->numero_reference }}</span>
                            </td>
                            <td class="text-start">
                                <strong>{{ $charge->libelle }}</strong>
                                @if($charge->fournisseur)
                                    <br><small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $charge->fournisseur }}
                                    </small>
                                @endif
                                @if($charge->recurrent)
                                    <span class="badge badge-modern badge-gradient-success ms-2">
                                        <i class="fas fa-sync-alt me-1"></i>Récurrent
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($charge->type == 'fixe')
                                    <span class="badge badge-modern badge-gradient-primary">
                                        <i class="fas fa-lock me-1"></i>Fixe
                                    </span>
                                @else
                                    <span class="badge badge-modern badge-gradient-warning">
                                        <i class="fas fa-chart-line me-1"></i>Variable
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($charge->category)
                                    <span class="badge badge-modern" style="background-color: {{ $charge->category->couleur }};">
                                        {{ $charge->category->nom }}
                                    </span>
                                @else
                                    <span class="badge badge-modern" style="background: #94a3b8; color: white;">Sans catégorie</span>
                                @endif
                            </td>
                            <td>
                                {{ $charge->date_charge->format('d/m/Y') }}
                                @if($charge->date_echeance)
                                    <br><small class="text-muted">
                                        Échéance: {{ $charge->date_echeance->format('d/m/Y') }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <strong style="color: #667eea;">{{ number_format($charge->montant, 2) }} DH</strong>
                                @if($charge->statut_paiement != 'paye')
                                    <br><small class="text-muted">
                                        Payé: {{ number_format($charge->montant_paye, 2) }} DH
                                    </small>
                                @endif
                            </td>
                            <td>
                                @if($charge->statut_paiement == 'paye')
                                    <span class="badge badge-modern badge-gradient-success">
                                        <i class="fas fa-check-circle me-1"></i>Payée
                                    </span>
                                @elseif($charge->statut_paiement == 'partiel')
                                    <span class="badge badge-modern badge-gradient-warning">
                                        <i class="fas fa-clock me-1"></i>Partiel
                                    </span>
                                @else
                                    <span class="badge badge-modern badge-gradient-danger">
                                        <i class="fas fa-times-circle me-1"></i>Impayée
                                    </span>
                                @endif
                                
                                @if($charge->is_en_retard)
                                    <br><span class="badge badge-modern badge-gradient-danger mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i>En retard
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button type="button" class="btn-action btn-action-info" onclick="voirCharge({{ $charge->id }})" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn-action btn-action-warning" onclick="modifierCharge({{ $charge->id }})" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($charge->statut_paiement != 'paye')
                                        <button type="button" class="btn-action btn-action-success" onclick="marquerPayee({{ $charge->id }})" title="Marquer payée">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button type="button" class="btn-action btn-action-danger" onclick="supprimerCharge({{ $charge->id }})" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <p class="empty-state-text">Aucune charge trouvée</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($charges->hasPages())
            <div class="p-4">
                {{ $charges->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modals -->
    @include('charges.modals.ajouter')
    @include('charges.modals.modifier')
    @include('charges.modals.details')
    @include('charges.modals.categories')

    @push('scripts')
    <script>
        // Messages de succès/erreur
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                background: 'white',
                customClass: {
                    popup: 'animated fadeInDown'
                }
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#fa709a'
            });
        @endif

        // Voir détails charge
        function voirCharge(id) {
            fetch(`/charges/${id}`)
                .then(response => response.ok ? response.json() : Promise.reject('Erreur réseau'))
                .then(data => {
                    if(data.success) {
                        const elements = {
                            'detailLibelle': data.charge.libelle,
                            'detailReference': data.charge.numero_reference,
                            'detailType': data.charge.type,
                            'detailMontant': data.charge.montant + ' DH',
                            'detailFournisseur': data.charge.fournisseur || 'N/A',
                            'detailDateCharge': data.charge.date_charge,
                            'detailStatut': data.charge.statut_paiement,
                            'detailCategorie': data.charge.category ? data.charge.category.nom : 'Sans catégorie'
                        };

                        Object.keys(elements).forEach(elementId => {
                            const element = document.getElementById(elementId);
                            if (element) element.textContent = elements[elementId];
                        });
                        
                        new bootstrap.Modal(document.getElementById('modalDetailsCharge')).show();
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur!',
                        text: 'Impossible de charger les détails',
                        confirmButtonColor: '#fa709a'
                    });
                });
        }

        // Modifier charge
        function modifierCharge(id) {
            fetch(`/charges/${id}`)
                .then(response => response.ok ? response.json() : Promise.reject('Erreur réseau'))
                .then(data => {
                    if(data.success) {
                        const charge = data.charge;
                        const formatDate = (dateString) => dateString ? dateString.split('T')[0] : '';
                        
                        document.getElementById('editChargeId').value = charge.id;
                        document.getElementById('editLibelle').value = charge.libelle || '';
                        document.getElementById('editMontant').value = charge.montant || 0;
                        document.getElementById('editType').value = charge.type || 'variable';
                        document.getElementById('editCategoryId').value = charge.charge_category_id || '';
                        document.getElementById('editDateCharge').value = formatDate(charge.date_charge);
                        document.getElementById('editDateEcheance').value = formatDate(charge.date_echeance);
                        document.getElementById('editFournisseur').value = charge.fournisseur || '';
                        document.getElementById('editFournisseurTelephone').value = charge.fournisseur_telephone || '';
                        document.getElementById('editDescription').value = charge.description || '';
                        document.getElementById('editStatutPaiement').value = charge.statut_paiement || 'paye';
                        document.getElementById('editModePaiement').value = charge.mode_paiement || 'especes';
                        document.getElementById('editMontantPaye').value = charge.montant_paye || 0;
                        document.getElementById('editReferencePaiement').value = charge.reference_paiement || '';
                        document.getElementById('editNotes').value = charge.notes || '';
                        
                        const editRecurrent = document.getElementById('editRecurrent');
                        editRecurrent.checked = charge.recurrent || false;
                        
                        const editFrequence = document.getElementById('editFrequence');
                        if (editFrequence) {
                            editFrequence.value = charge.frequence || 'mensuel';
                        }
                        
                        const form = document.getElementById('formModifierCharge');
                        if (form) {
                            form.action = `/charges/${id}`;
                        }
                        
                        const modal = new bootstrap.Modal(document.getElementById('modalModifierCharge'));
                        modal.show();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur!',
                        text: 'Impossible de charger les données de la charge',
                        confirmButtonColor: '#ef4444'
                    });
                });
        }

        function marquerPayee(id) {
            Swal.fire({
                title: 'Marquer comme payée?',
                text: "Cette charge sera marquée comme entièrement payée",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, marquer payée',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/charges/${id}/marquer-payee`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Supprimer charge
        function supprimerCharge(id) {
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Cette charge sera supprimée (récupérable)",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D32F2F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/charges/${id}`;
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';
                    form.appendChild(methodField);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Initialiser Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });
    </script>
    @endpush
</x-app-layout>