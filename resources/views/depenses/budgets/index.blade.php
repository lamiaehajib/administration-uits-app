<x-app-layout>
    <style>
        .budget-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }

        .budget-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            transition: width 0.3s ease;
        }

        .budget-card:hover::before {
            width: 100%;
            opacity: 0.05;
        }

        .budget-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(211, 47, 47, 0.2) !important;
        }

        .stat-box {
            background: white;
            border-radius: 15px;
            padding: 25px;
            transition: all 0.3s ease;
            border: 2px solid #fce4ec;
            position: relative;
            overflow: hidden;
        }

        .stat-box::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            opacity: 0.05;
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.15);
            border-color: #D32F2F;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
            transition: all 0.3s ease;
        }

        .stat-box:hover .stat-icon {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            transform: rotate(10deg) scale(1.1);
        }

        .stat-box:hover .stat-icon i {
            color: white !important;
        }

        .metric-value {
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .filter-card {
            background: linear-gradient(135deg, #fff5f5, #ffffff);
            border: 2px solid #fce4ec;
            border-radius: 15px;
            padding: 25px;
        }

        .year-section {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .year-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .budget-month-card {
            border: 2px solid #fce4ec;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
            overflow: hidden;
        }

        .budget-month-card:hover {
            border-color: #D32F2F;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(211, 47, 47, 0.15);
        }

        .month-header {
            padding: 15px;
            border-bottom: 2px solid #fce4ec;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .month-header.success {
            background: linear-gradient(135deg, #e8f5e9, #ffffff);
        }

        .month-header.danger {
            background: linear-gradient(135deg, #ffebee, #ffffff);
        }

        .progress-custom {
            height: 25px;
            border-radius: 20px;
            background: #fce4ec;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .progress-custom .progress-bar {
            border-radius: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: width 0.6s ease;
        }

        .badge-custom {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            transition: all 0.3s ease;
            font-weight: 600;
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
            background: linear-gradient(135deg, #fff5f5, #ffffff);
            border-radius: 15px;
        }

        .empty-state i {
            font-size: 5rem;
            color: #fce4ec;
            margin-bottom: 20px;
        }

        .action-btn-group {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.05);
        }

        .info-metric {
            padding: 15px;
            border-radius: 10px;
            background: linear-gradient(135deg, #fff5f5, #ffffff);
            text-align: center;
            transition: all 0.3s ease;
        }

        .info-metric:hover {
            background: linear-gradient(135deg, #fce4ec, #ffffff);
        }

        .alert-custom {
            border-left: 5px solid;
            border-radius: 10px;
            padding: 15px 20px;
        }
    </style>

    <div class="container-fluid">
        <!-- 🎯 Header Section -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <h3 class="mb-2">
                    <i class="fas fa-piggy-bank me-2"></i>
                    <span class="hight">Budgets Mensuels</span>
                </h3>
                <p class="text-muted mb-0">
                    <i class="fas fa-chart-line me-1"></i>
                    Gérez et suivez vos budgets mensuels
                </p>
            </div>
            <div class="col-lg-4 text-lg-end text-start mt-3 mt-lg-0">
                <button class="btn btn-gradient btn-lg me-2" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-2"></i>Nouveau Budget
                </button>
                <a href="{{ route('depenses.dashboard') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- ✅ Alertes -->
        @if(session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Succès!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-custom alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Erreur!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- 📊 Statistiques Globales -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-wallet text-success fs-3"></i>
                    </div>
                    <h6 class="text-muted mb-2">Budget Total {{ now()->year }}</h6>
                    <h3 class="metric-value mb-1">{{ number_format($stats['budget_total_annee'], 0) }}</h3>
                    <small class="text-muted">DH</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave text-danger fs-3"></i>
                    </div>
                    <h6 class="text-muted mb-2">Dépensé {{ now()->year }}</h6>
                    <h3 class="metric-value mb-1">{{ number_format($stats['depense_total_annee'], 0) }}</h3>
                    <small class="text-muted">DH</small>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle text-warning fs-3"></i>
                    </div>
                    <h6 class="text-muted mb-2">Budgets Dépassés</h6>
                    <h3 class="metric-value mb-1">{{ $stats['budgets_depasses'] }}</h3>
                    <small class="text-muted">mois</small>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-box">
                    <div class="stat-icon">
                        <i class="fas fa-percentage text-primary fs-3"></i>
                    </div>
                    <h6 class="text-muted mb-2">Taux Moyen</h6>
                    @php
                        $tauxMoyen = $stats['budget_total_annee'] > 0 
                            ? ($stats['depense_total_annee'] / $stats['budget_total_annee']) * 100 
                            : 0;
                    @endphp
                    <h3 class="metric-value mb-1">{{ number_format($tauxMoyen, 1) }}%</h3>
                    <small class="text-muted">exécution</small>
                </div>
            </div>
        </div>

        <!-- 🔍 Filtres -->
        <div class="filter-card mb-4">
            <form method="GET" action="{{ route('depenses.budgets.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt text-danger me-1"></i>
                            Année
                        </label>
                        <select name="annee" class="form-select form-select-lg">
                            <option value="">Toutes les années</option>
                            @for($y = now()->year + 1; $y >= 2020; $y--)
                                <option value="{{ $y }}" {{ request('annee') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-filter text-danger me-1"></i>
                            Statut
                        </label>
                        <select name="statut" class="form-select form-select-lg">
                            <option value="">Tous les statuts</option>
                            <option value="previsionnel" {{ request('statut') == 'previsionnel' ? 'selected' : '' }}>
                                📋 Prévisionnel
                            </option>
                            <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>
                                ⚡ En cours
                            </option>
                            <option value="cloture" {{ request('statut') == 'cloture' ? 'selected' : '' }}>
                                🔒 Clôturé
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-gradient btn-lg w-100">
                            <i class="fas fa-search me-2"></i>Filtrer
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- 📅 Budgets par Année -->
        @php
            $budgetsParAnnee = $budgets->groupBy('annee');
        @endphp

        @forelse($budgetsParAnnee as $annee => $budgetsAnnee)
        <div class="year-section">
            <div class="year-header">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Année {{ $annee }}
                </h4>
                <span class="badge bg-white text-dark" style="font-size: 1rem; padding: 10px 20px;">
                    {{ $budgetsAnnee->count() }} mois
                </span>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    @foreach($budgetsAnnee as $budget)
                    <div class="col-lg-4 col-md-6">
                        <div class="budget-month-card">
                            <div class="month-header {{ $budget->is_depasse ? 'danger' : 'success' }}">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-calendar text-danger me-2"></i>
                                    {{ $budget->mois_nom }}
                                </h5>
                                @if($budget->statut === 'previsionnel')
                                    <span class="badge-custom bg-info text-white">Prévisionnel</span>
                                @elseif($budget->statut === 'en_cours')
                                    <span class="badge-custom bg-warning text-white">En cours</span>
                                @else
                                    <span class="badge-custom bg-secondary text-white">Clôturé</span>
                                @endif
                            </div>
                            
                            <div class="p-3">
                                <!-- Métriques -->
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="info-metric">
                                            <small class="text-muted d-block">Budget</small>
                                            <h6 class="mb-0 fw-bold text-success">{{ number_format($budget->budget_total, 0) }} DH</h6>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-metric">
                                            <small class="text-muted d-block">Dépensé</small>
                                            <h6 class="mb-0 fw-bold {{ $budget->is_depasse ? 'text-danger' : 'text-warning' }}">
                                                {{ number_format($budget->depense_totale_realisee, 0) }} DH
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                <div class="progress-custom progress mb-3">
                                    @php
                                        $tauxExecution = min($budget->taux_execution, 100);
                                        $progressColor = $budget->is_depasse ? 'bg-danger' : 'bg-success';
                                    @endphp
                                    <div class="progress-bar {{ $progressColor }}" 
                                         style="width: {{ $tauxExecution }}%">
                                        {{ number_format($budget->taux_execution, 1) }}%
                                    </div>
                                </div>

                                <!-- Détails Fixes/Variables -->
                                <div class="row text-center mb-3">
                                    <div class="col-6">
                                        <div class="border-end">
                                            <i class="fas fa-calendar-check text-primary mb-1"></i>
                                            <div class="small text-muted">Fixes</div>
                                            <strong>{{ number_format($budget->depense_fixes_realisee, 0) }} DH</strong>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-chart-line text-warning mb-1"></i>
                                        <div class="small text-muted">Variables</div>
                                        <strong>{{ number_format($budget->depense_variables_realisee, 0) }} DH</strong>
                                    </div>
                                </div>

                                <!-- Restant/Dépassement -->
                                <div class="alert alert-{{ $budget->ecart_total >= 0 ? 'success' : 'danger' }} py-2 mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-{{ $budget->ecart_total >= 0 ? 'check-circle' : 'exclamation-triangle' }} me-1"></i>
                                            {{ $budget->is_depasse ? 'Dépassement' : 'Restant' }}
                                        </span>
                                        <strong>{{ number_format(abs($budget->ecart_total), 0) }} DH</strong>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="action-btn-group">
                                    <button class="btn btn-outline-info action-btn flex-fill" 
                                            onclick="showBudget({{ $budget->id }})" 
                                            title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($budget->statut !== 'cloture')
                                        <button class="btn btn-outline-warning action-btn flex-fill" 
                                                onclick="editBudget({{ $budget->id }})" 
                                                title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-outline-success action-btn flex-fill" 
                                                onclick="cloturerBudget({{ $budget->id }})" 
                                                title="Clôturer">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-piggy-bank"></i>
            <h4 class="text-muted mb-3">Aucun budget créé</h4>
            <p class="text-muted mb-4">Commencez par créer votre premier budget mensuel pour suivre vos dépenses</p>
            <button class="btn btn-gradient btn-lg" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fas fa-plus me-2"></i>Créer le Premier Budget
            </button>
        </div>
        @endforelse

        <!-- Pagination -->
        @if($budgets->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $budgets->links() }}
        </div>
        @endif
    </div>

    <!-- 🎨 Modal Create -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('depenses.budgets.store') }}" method="POST" id="createBudgetForm">
                    @csrf
                    <div class="modal-header" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle me-2"></i>Nouveau Budget Mensuel
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="alert alert-info alert-custom mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Astuce:</strong> Le système calculera automatiquement le budget total
                        </div>

                        <div class="row g-3">
                            <!-- Période -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-danger me-1"></i>
                                    Année <span class="text-danger">*</span>
                                </label>
                                <select name="annee" id="createAnnee" class="form-select form-select-lg" required>
                                    @for($y = now()->year + 1; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar text-danger me-1"></i>
                                    Mois <span class="text-danger">*</span>
                                </label>
                                <select name="mois" id="createMois" class="form-select form-select-lg" required>
                                    @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $i => $nom)
                                        <option value="{{ $i+1 }}" {{ ($i+1) == now()->month ? 'selected' : '' }}>{{ $nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Budgets -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-check text-primary me-1"></i>
                                    Budget Dépenses Fixes <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="budget_fixes" id="createBudgetFixes" 
                                           class="form-control" step="0.01" required>
                                    <span class="input-group-text">DH</span>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Suggestion: <span id="suggestionFixes" class="fw-bold">Calcul...</span>
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-chart-line text-warning me-1"></i>
                                    Budget Dépenses Variables <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="budget_variables" id="createBudgetVariables" 
                                           class="form-control" step="0.01" required>
                                    <span class="input-group-text">DH</span>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Suggestion: <span id="suggestionVariables" class="fw-bold">Calcul...</span>
                                </small>
                            </div>

                            <!-- Total calculé -->
                            <div class="col-12">
                                <div class="p-4 rounded" style="background: linear-gradient(135deg, #e8f5e9, #ffffff); border: 2px dashed #4CAF50;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0">
                                            <i class="fas fa-calculator text-success me-2"></i>
                                            Budget Total
                                        </span>
                                        <h3 class="mb-0 fw-bold text-success" id="budgetTotal">0.00 DH</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-sticky-note text-danger me-1"></i>
                                    Notes
                                </label>
                                <textarea name="notes" class="form-control" rows="3" 
                                          placeholder="Objectifs, remarques, commentaires..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-gradient btn-lg">
                            <i class="fas fa-save me-2"></i>Créer le Budget
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 🎨 Modal Show -->
    <div class="modal fade" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #2196F3, #1976D2); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-chart-pie me-2"></i>Détails du Budget
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="showModalContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                        <p class="text-muted mt-3">Chargement des détails...</p>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 🎨 Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header" style="background: linear-gradient(135deg, #FF9800, #F57C00); color: white;">
                        <h5 class="modal-title">
                            <i class="fas fa-edit me-2"></i>Modifier le Budget
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="editModalContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-warning"></div>
                            <p class="text-muted mt-3">Chargement...</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-warning text-white btn-lg">
                            <i class="fas fa-save me-2"></i>Mettre à Jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Calculer budget total automatiquement
        function calculerTotal() {
            const fixes = parseFloat(document.getElementById('createBudgetFixes').value) || 0;
            const variables = parseFloat(document.getElementById('createBudgetVariables').value) || 0;
            const total = fixes + variables;
            
            document.getElementById('budgetTotal').textContent = total.toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' DH';
        }

        document.getElementById('createBudgetFixes')?.addEventListener('input', calculerTotal);
        document.getElementById('createBudgetVariables')?.addEventListener('input', calculerTotal);

        // Charger suggestions
        document.getElementById('createModal')?.addEventListener('shown.bs.modal', function() {
            fetch('/depenses/api/stats')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('suggestionFixes').textContent = '100,000 DH';
                    document.getElementById('suggestionVariables').textContent = '50,000 DH';
                })
                .catch(error => {
                    console.error('Erreur suggestions:', error);
                });
        });

        // Voir détails
        function showBudget(id) {
            const modal = new bootstrap.Modal(document.getElementById('showModal'));
            const content = document.getElementById('showModalContent');
            
            content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div><p class="text-muted mt-3">Chargement...</p></div>';
            modal.show();
            
            fetch(`/depenses/budgets/${id}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const mainContent = doc.querySelector('.container-fluid') || doc.querySelector('main');
                    content.innerHTML = mainContent ? mainContent.innerHTML : '<div class="alert alert-danger">Erreur de chargement</div>';
                })
                .catch(error => {
                    content.innerHTML = `<div class="alert alert-danger">Erreur: ${error.message}</div>`;
                });
        }

        // Modifier
        function editBudget(id) {
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            const form = document.getElementById('editForm');
            const content = document.getElementById('editModalContent');
            
            form.action = `/depenses/budgets/${id}`;
            content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-warning"></div><p class="text-muted mt-3">Chargement...</p></div>';
            modal.show();
            
            fetch(`/depenses/budgets/${id}/edit`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const formContent = doc.querySelector('form') || doc.querySelector('.container-fluid');
                    
                    if (formContent) {
                        content.innerHTML = formContent.innerHTML;
                        
                        const editFixes = content.querySelector('[name="budget_fixes"]');
                        const editVariables = content.querySelector('[name="budget_variables"]');
                        const editTotal = content.querySelector('#editBudgetTotal');
                        
                        function recalculerEditTotal() {
                            const f = parseFloat(editFixes.value) || 0;
                            const v = parseFloat(editVariables.value) || 0;
                            if(editTotal) {
                                editTotal.textContent = (f + v).toLocaleString('fr-FR', {
                                    minimumFractionDigits: 2
                                }) + ' DH';
                            }
                        }
                        
                        editFixes?.addEventListener('input', recalculerEditTotal);
                        editVariables?.addEventListener('input', recalculerEditTotal);
                    }
                })
                .catch(error => {
                    content.innerHTML = `<div class="alert alert-danger">Erreur: ${error.message}</div>`;
                });
        }

        // Clôturer
        function cloturerBudget(id) {
            Swal.fire({
                title: 'Clôturer ce budget ?',
                html: "Vous ne pourrez <strong>plus le modifier</strong> après cette action<br><small class='text-muted'>Cette action est irréversible</small>",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-lock me-2"></i>Oui, clôturer',
                cancelButtonText: '<i class="fas fa-times me-2"></i>Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/depenses/budgets/${id}/cloturer`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                    
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Form submit
        $('#createBudgetForm').on('submit', function(e) {
            const fixes = parseFloat($('#createBudgetFixes').val()) || 0;
            const variables = parseFloat($('#createBudgetVariables').val()) || 0;
            
            if(fixes === 0 && variables === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Veuillez saisir au moins un montant'
                });
            }
        });
    </script>
    @endpush
</x-app-layout>