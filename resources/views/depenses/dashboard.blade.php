<x-app-layout>
    <div class="container-fluid">
        <!-- Header avec filtres -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h3 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    <span class="hight">Tableau de Bord des Dépenses</span>
                </h3>
                <p class="text-muted mb-0">Gestion complète de vos dépenses fixes et variables</p>
            </div>
            <div class="col-md-4 text-end">
                <form method="GET" action="{{ route('depenses.dashboard') }}" class="d-flex gap-2 justify-content-end">
                    <select name="mois" class="form-select form-select-sm" style="width: 120px;">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="annee" class="form-select form-select-sm" style="width: 100px;">
                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                            <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                </form>
            </div>
        </div>

        <!-- Cards statistiques -->
        <div class="row g-3 mb-4">
            <!-- Dépenses Fixes -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-receipt text-danger fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 small">Dépenses Fixes</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($totaux['fixes'], 2) }} DH</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dépenses Variables -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-random text-warning fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 small">Dépenses Variables</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($totaux['variables'], 2) }} DH</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Dépenses -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                    <div class="card-body text-white">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-white bg-opacity-25 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-wallet text-white fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="mb-1 small opacity-75">Total Dépenses</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($totaux['total'], 2) }} DH</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="avatar-sm rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="fas fa-chart-pie text-success fs-4"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <p class="text-muted mb-1 small">Budget Restant</p>
                                @if($budget)
                                    <h4 class="mb-0 fw-bold {{ $budget->is_depasse ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($budget->budget_total - $budget->depense_totale_realisee, 2) }} DH
                                    </h4>
                                    <small class="text-muted">{{ $budget->taux_execution }}% utilisé</small>
                                @else
                                    <p class="mb-0 text-muted small">Aucun budget</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes -->
        @if($alertes['budget_depasse'] || $alertes['factures_en_attente'] > 0 || $alertes['rappels_du_jour'] > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle fs-4 me-3"></i>
                    <div>
                        <strong>Alertes importantes:</strong>
                        <ul class="mb-0 mt-2">
                            @if($alertes['budget_depasse'])
                                <li>⚠️ Budget mensuel dépassé!</li>
                            @endif
                            @if($alertes['factures_en_attente'] > 0)
                                <li>📋 {{ $alertes['factures_en_attente'] }} facture(s) en attente de validation</li>
                            @endif
                            @if($alertes['rappels_du_jour'] > 0)
                                <li>🔔 {{ $alertes['rappels_du_jour'] }} rappel(s) de paiement aujourd'hui</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="row g-4">
            <!-- Graphique évolution -->
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            Évolution des Dépenses (12 derniers mois)
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="evolutionChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Répartition par type -->
            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-pie text-success me-2"></i>
                            Répartition Variables
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="repartitionChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top dépenses -->
        <div class="row g-4 mt-1">
            <!-- Top Fixes -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">
                            <i class="fas fa-star text-danger me-2"></i>
                            Top 5 Dépenses Fixes
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Libellé</th>
                                        <th class="text-end">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topFixes as $depense)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $depense->type_libelle }}</span>
                                        </td>
                                        <td>{{ $depense->libelle_complet }}</td>
                                        <td class="text-end fw-bold">{{ number_format($depense->montant_mensuel, 2) }} DH</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fs-3 mb-2 d-block"></i>
                                            Aucune dépense fixe
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Variables -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">
                            <i class="fas fa-star text-warning me-2"></i>
                            Top 5 Dépenses Variables du Mois
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Libellé</th>
                                        <th class="text-end">Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topVariables as $depense)
                                    <tr>
                                        <td>
                                            <span class="badge bg-warning">{{ $depense->type_libelle }}</span>
                                        </td>
                                        <td>{{ Str::limit($depense->libelle, 30) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($depense->montant, 2) }} DH</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fas fa-inbox fs-3 mb-2 d-block"></i>
                                            Aucune dépense variable ce mois
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">
                            <i class="fas fa-bolt text-warning me-2"></i>
                            Actions Rapides
                        </h5>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('depenses.fixes.create') }}" class="btn btn-danger">
                                <i class="fas fa-plus me-2"></i>Nouvelle Dépense Fixe
                            </a>
                            <a href="{{ route('depenses.variables.create') }}" class="btn btn-warning">
                                <i class="fas fa-plus me-2"></i>Nouvelle Dépense Variable
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importSalaireModal">
                                <i class="fas fa-download me-2"></i>Importer Salaires
                            </button>
                            <a href="{{ route('depenses.budgets.create') }}" class="btn btn-info">
                                <i class="fas fa-calculator me-2"></i>Créer Budget
                            </a>
                            <a href="{{ route('depenses.export') }}" class="btn btn-secondary">
                                <i class="fas fa-file-export me-2"></i>Exporter
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Import Salaires -->
    <div class="modal fade" id="importSalaireModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-download me-2"></i>Importer Salaires depuis uits-mgmt.ma
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('depenses.importer-salaires') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Mois</label>
                            <select name="mois" class="form-select" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Année</label>
                            <select name="annee" class="form-select" required>
                                @foreach(range(date('Y'), date('Y') - 2) as $y)
                                    <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-info small mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Ceci va créer automatiquement les dépenses fixes pour les salaires et calculer la CNSS.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique évolution
        const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($evolution->pluck('mois')) !!},
                datasets: [
                    {
                        label: 'Fixes',
                        data: {!! json_encode($evolution->pluck('fixes')) !!},
                        borderColor: '#D32F2F',
                        backgroundColor: 'rgba(211, 47, 47, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Variables',
                        data: {!! json_encode($evolution->pluck('variables')) !!},
                        borderColor: '#FFC107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.4
                    },
                    {
                        label: 'Total',
                        data: {!! json_encode($evolution->pluck('total')) !!},
                        borderColor: '#C2185B',
                        backgroundColor: 'rgba(194, 24, 91, 0.1)',
                        borderWidth: 2,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                }
            }
        });

        // Graphique répartition
        const repartitionCtx = document.getElementById('repartitionChart').getContext('2d');
        new Chart(repartitionCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($repartitionTypes->pluck('type')) !!},
                datasets: [{
                    data: {!! json_encode($repartitionTypes->pluck('montant')) !!},
                    backgroundColor: [
                        '#D32F2F', '#C2185B', '#FFC107', '#4CAF50', 
                        '#2196F3', '#9C27B0', '#FF5722', '#607D8B'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>