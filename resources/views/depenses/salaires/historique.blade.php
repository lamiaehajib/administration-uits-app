<x-app-layout>
    <style>
        .salary-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            background: white;
        }
        
        .salary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.15) !important;
            border-left-color: #D32F2F;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            transition: all 0.3s ease;
        }

        .salary-card:hover .stat-icon {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            transform: rotate(10deg) scale(1.1);
        }

        .salary-card:hover .stat-icon i {
            color: white !important;
        }

        .timeline-badge {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            font-weight: bold;
            margin: 0 auto;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }

        .month-display {
            font-size: 1.1rem;
            font-weight: 600;
            color: #D32F2F;
            margin-top: 10px;
        }

        .year-display {
            font-size: 0.9rem;
            color: #666;
        }

        .info-box {
            background: linear-gradient(135deg, #fff5f5, #ffffff);
            border-left: 4px solid #D32F2F;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
            color: white;
        }

        .empty-state {
            padding: 80px 20px;
            text-align: center;
        }

        .empty-state i {
            font-size: 5rem;
            color: #fce4ec;
            margin-bottom: 20px;
        }

        .badge-custom {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .metric-value {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .action-btn {
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            transform: scale(1.05);
        }

        .modal-header.gradient-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .preview-card {
            background: linear-gradient(135deg, #fff5f5, #ffffff);
            border: 2px dashed #D32F2F;
            border-radius: 10px;
            padding: 20px;
        }
    </style>

    <div class="container-fluid">
        <!-- 🎯 Header Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <h3 class="mb-2">
                    <i class="fas fa-history me-2"></i>
                    <span class="hight">Historique Imports Salaires</span>
                </h3>
                <p class="text-muted mb-0">
                    <i class="fas fa-link me-1"></i>
                    Synchronisation avec <strong>uits-mgmt.ma</strong>
                </p>
            </div>
            <div class="col-lg-4 text-lg-end text-start mt-3 mt-lg-0">
                <button class="btn btn-gradient me-2" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-cloud-download-alt me-2"></i>Nouvel Import
                </button>
                <a href="{{ route('depenses.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- 📊 Statistiques Rapides -->
        @if($historiques->count() > 0)
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-file-import text-danger fs-3"></i>
                        </div>
                        <h6 class="text-muted mb-2">Total Imports</h6>
                        <h2 class="metric-value mb-0">{{ $historiques->total() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-users text-danger fs-3"></i>
                        </div>
                        <h6 class="text-muted mb-2">Total Employés</h6>
                        <h2 class="metric-value mb-0">{{ $historiques->sum('nombre_employes') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="stat-icon mb-3">
                            <i class="fas fa-money-bill-wave text-danger fs-3"></i>
                        </div>
                        <h6 class="text-muted mb-2">Montant Total</h6>
                        <h2 class="metric-value mb-0">{{ number_format($historiques->sum('montant_total'), 0) }} DH</h2>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- 📅 Timeline des Imports -->
        <div class="row">
            <div class="col-12">
                @forelse($historiques as $historique)
                <div class="salary-card card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Date Badge -->
                            <div class="col-lg-2 col-md-3 text-center mb-3 mb-md-0">
                                <div class="timeline-badge">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="month-display mt-2">{{ $historique->mois_nom }}</div>
                                <div class="year-display">{{ $historique->annee }}</div>
                            </div>

                            <!-- Informations -->
                            <div class="col-lg-7 col-md-6">
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-users text-primary fs-4"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Employés</small>
                                                <h5 class="mb-0 fw-bold">{{ $historique->nombre_employes }}</h5>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-money-bill-wave text-danger fs-4"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Montant Total</small>
                                                <h5 class="mb-0 fw-bold text-danger">{{ number_format($historique->montant_total, 2) }} DH</h5>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <i class="fas fa-shield-alt text-warning fs-4"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">CNSS</small>
                                                <h5 class="mb-0 fw-bold text-warning">{{ number_format($historique->montant_total * 0.2048, 2) }} DH</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-user-circle text-muted me-2"></i>
                                        <small class="text-muted">
                                            Importé par <strong>{{ $historique->importePar->name ?? 'N/A' }}</strong>
                                            le {{ $historique->importe_le->format('d/m/Y à H:i') }}
                                        </small>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    {!! $historique->statut_badge !!}
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-lg-3 col-md-3 text-md-end text-center mt-3 mt-md-0">
                                <button class="btn btn-outline-danger action-btn" onclick="showHistorique({{ $historique->id }})">
                                    <i class="fas fa-eye me-2"></i>Détails
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body empty-state">
                        <i class="fas fa-inbox"></i>
                        <h4 class="text-muted mb-3">Aucun import trouvé</h4>
                        <p class="text-muted mb-4">Commencez par importer les salaires depuis uits-mgmt.ma</p>
                        <button class="btn btn-gradient btn-lg" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-cloud-download-alt me-2"></i>Premier Import
                        </button>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($historiques->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $historiques->links() }}
            </div>
        </div>
        @endif
    </div>

    <!-- 🎨 Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header gradient-header text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-cloud-download-alt me-2"></i>Importer les Salaires
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('depenses.importer-salaires') }}" method="POST" id="importForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="info-box">
                            <div class="d-flex">
                                <i class="fas fa-info-circle text-danger me-3 mt-1"></i>
                                <div>
                                    <h6 class="fw-bold mb-2">Synchronisation automatique</h6>
                                    <p class="mb-2 small">Cette action va récupérer les données depuis <strong>uits-mgmt.ma</strong> et créer automatiquement :</p>
                                    <ul class="mb-0 small">
                                        <li>Une dépense fixe pour les salaires du mois</li>
                                        <li>Une dépense variable pour la CNSS (20.48% du total)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar me-1 text-danger"></i>
                                    Mois <span class="text-danger">*</span>
                                </label>
                                <select name="mois" class="form-select form-select-lg" required>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                            {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt me-1 text-danger"></i>
                                    Année <span class="text-danger">*</span>
                                </label>
                                <select name="annee" class="form-select form-select-lg" required>
                                    @foreach(range(date('Y'), date('Y') - 3) as $y)
                                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="importPreview" class="mt-4" style="display: none;">
                            <div class="preview-card">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-eye me-2 text-danger"></i>Aperçu des données
                                </h6>
                                <div id="previewContent">
                                    <div class="text-center py-3">
                                        <div class="spinner-border text-danger"></div>
                                        <p class="mb-0 small mt-2">Chargement des données...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="btnPreview">
                            <i class="fas fa-eye me-2"></i>Aperçu
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-cloud-download-alt me-2"></i>Lancer l'Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 🎨 Modal Détails -->
    <div class="modal fade" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header gradient-header text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-file-invoice me-2"></i>Détails de l'Import
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="showContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-danger"></div>
                        <p class="text-muted mt-3">Chargement des détails...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Preview avant import
        $('#btnPreview').on('click', function() {
            const mois = $('select[name="mois"]').val();
            const annee = $('select[name="annee"]').val();

            $('#importPreview').slideDown();
            $('#previewContent').html('<div class="text-center py-3"><div class="spinner-border text-danger"></div><p class="small mt-2">Récupération des données...</p></div>');

            $.get('/depenses/api/employees', function(response) {
                if (response.success && response.employees.length > 0) {
                    const total = response.employees.reduce((sum, emp) => sum + parseFloat(emp.salaire || 0), 0);
                    const cnss = total * 0.2048;

                    let html = `
                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded">
                                    <i class="fas fa-users text-primary fs-3 mb-2 d-block"></i>
                                    <small class="text-muted d-block">Employés</small>
                                    <h4 class="mb-0 fw-bold">${response.employees.length}</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded">
                                    <i class="fas fa-money-bill-wave text-danger fs-3 mb-2 d-block"></i>
                                    <small class="text-muted d-block">Total Salaires</small>
                                    <h4 class="mb-0 fw-bold text-danger">${total.toLocaleString('fr-FR', {minimumFractionDigits: 2})} DH</h4>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-white rounded">
                                    <i class="fas fa-shield-alt text-warning fs-3 mb-2 d-block"></i>
                                    <small class="text-muted d-block">CNSS (20.48%)</small>
                                    <h4 class="mb-0 fw-bold text-warning">${cnss.toLocaleString('fr-FR', {minimumFractionDigits: 2})} DH</h4>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-hover">
                                <thead class="sticky-top" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                                    <tr>
                                        <th>#</th>
                                        <th>Employé</th>
                                        <th>Poste</th>
                                        <th class="text-end">Salaire</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    response.employees.forEach((emp, index) => {
                        html += `
                            <tr>
                                <td>${index + 1}</td>
                                <td class="fw-semibold">${emp.name}</td>
                                <td><span class="badge bg-primary">${emp.poste}</span></td>
                                <td class="text-end fw-bold">${parseFloat(emp.salaire).toLocaleString('fr-FR', {minimumFractionDigits: 2})} DH</td>
                            </tr>
                        `;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;

                    $('#previewContent').html(html);
                } else {
                    $('#previewContent').html('<div class="alert alert-warning mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Aucun employé trouvé</div>');
                }
            }).fail(function() {
                $('#previewContent').html('<div class="alert alert-danger mb-0"><i class="fas fa-times-circle me-2"></i>Erreur de connexion à uits-mgmt.ma</div>');
            });
        });

        // Show historique details
        function showHistorique(id) {
            $('#showModal').modal('show');
            $('#showContent').html('<div class="text-center py-5"><div class="spinner-border text-danger"></div><p class="text-muted mt-3">Chargement...</p></div>');
            
            $.get(`/depenses/salaires/${id}`, function(response) {
                $('#showContent').html(response);
            });
        }

        // Form submit
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Confirmer l\'import ?',
                html: "Les salaires et la CNSS seront créés automatiquement<br><small class='text-muted'>Cette action est irréversible</small>",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#D32F2F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check me-2"></i>Oui, importer',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès !',
                text: '{{ session('success') }}',
                confirmButtonColor: '#D32F2F'
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur !',
                text: '{{ session('error') }}',
                confirmButtonColor: '#D32F2F'
            });
        @endif
    </script>
    @endpush
</x-app-layout>