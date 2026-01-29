<x-app-layout>
    <div class="dashboard-container">
        <!-- 🎯 HEADER AVEC FILTRES AVANCÉS -->
        <div class="dashboard-header">
            <div class="row align-items-center mb-4">
                <div class="col-lg-4">
                    <h1 class="dashboard-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        Tableau de Bord
                    </h1>
                    <p class="text-muted mb-0">Vue d'ensemble de vos dépenses</p>
                </div>
                
                <div class="col-lg-8">
                    <form method="GET" action="{{ route('depenses.dashboard') }}" class="filter-form">
                        <div class="row g-2">
                            <!-- Sélecteur de période -->
                            <div class="col-md-3">
                                <select name="periode" class="form-select form-select-modern" id="periodeSelect" onchange="this.form.submit()">
                                    <option value="6" {{ $periode == 6 ? 'selected' : '' }}>6 derniers mois</option>
                                    <option value="12" {{ $periode == 12 ? 'selected' : '' }}>12 derniers mois</option>
                                    <option value="24" {{ $periode == 24 ? 'selected' : '' }}>24 derniers mois</option>
                                </select>
                            </div>
                            
                            <!-- Sélecteur d'année -->
                            <div class="col-md-3">
                                <select name="annee" class="form-select form-select-modern" onchange="this.form.submit()">
                                    @for($y = now()->year; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                            <!-- Sélecteur de mois -->
                            <div class="col-md-3">
                                <select name="mois" class="form-select form-select-modern" onchange="this.form.submit()">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->locale('fr')->isoFormat('MMMM') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Bouton reset -->
                            <div class="col-md-3">
                                <a href="{{ route('depenses.dashboard') }}" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-redo me-1"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ⚠️ ALERTES -->
        @if($alertes['budget_depasse'] || $alertes['factures_en_attente'] > 0 || $alertes['rappels_du_jour'] > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert-container">
                    @if($alertes['budget_depasse'])
                    <div class="alert alert-danger alert-modern">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Budget dépassé!</strong> Vous avez dépassé le budget du mois.
                    </div>
                    @endif
                    
                    @if($alertes['factures_en_attente'] > 0)
                    <div class="alert alert-warning alert-modern">
                        <i class="fas fa-clock me-2"></i>
                        <strong>{{ $alertes['factures_en_attente'] }} facture(s)</strong> en attente de validation.
                        <a href="{{ route('depenses.variables.index', ['statut' => 'en_attente']) }}" class="alert-link">Voir</a>
                    </div>
                    @endif
                    
                    @if($alertes['rappels_du_jour'] > 0)
                    <div class="alert alert-info alert-modern">
                        <i class="fas fa-bell me-2"></i>
                        <strong>{{ $alertes['rappels_du_jour'] }} rappel(s)</strong> pour aujourd'hui.
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- 📊 CARTES STATISTIQUES PRINCIPALES -->
        <div class="row g-4 mb-4">
            <!-- Total Dépenses -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card stat-card-primary">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Total Dépenses</h6>
                        <h3 class="stat-value">{{ number_format($totaux['total'], 2) }} DH</h3>
                        <div class="stat-trend {{ $comparaison['total_variation'] >= 0 ? 'trend-up' : 'trend-down' }}">
                            <i class="fas fa-arrow-{{ $comparaison['total_variation'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($comparaison['total_variation_pct']) }}% vs mois précédent
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dépenses Fixes -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card stat-card-success">
                    <div class="stat-icon">
                        <i class="fas fa-sync"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Dépenses Fixes</h6>
                        <h3 class="stat-value">{{ number_format($totaux['fixes'], 2) }} DH</h3>
                        <div class="stat-trend {{ $comparaison['fixes_variation'] >= 0 ? 'trend-up' : 'trend-down' }}">
                            <i class="fas fa-arrow-{{ $comparaison['fixes_variation'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($comparaison['fixes_variation_pct']) }}% vs mois précédent
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dépenses Variables -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card stat-card-warning">
                    <div class="stat-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Dépenses Variables</h6>
                        <h3 class="stat-value">{{ number_format($totaux['variables'], 2) }} DH</h3>
                        <div class="stat-trend {{ $comparaison['variables_variation'] >= 0 ? 'trend-up' : 'trend-down' }}">
                            <i class="fas fa-arrow-{{ $comparaison['variables_variation'] >= 0 ? 'up' : 'down' }}"></i>
                            {{ abs($comparaison['variables_variation_pct']) }}% vs mois précédent
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Restant -->
            <div class="col-lg-3 col-md-6">
                <div class="stat-card stat-card-info">
                    <div class="stat-icon">
                        <i class="fas fa-piggy-bank"></i>
                    </div>
                    <div class="stat-content">
                        <h6 class="stat-label">Budget Restant</h6>
                        <h3 class="stat-value">
                            @if($budget)
                                {{ number_format($budget->budget_total - $budget->depense_totale_realisee, 2) }} DH
                            @else
                                N/A
                            @endif
                        </h3>
                        <div class="stat-progress">
                            @if($budget)
                                <div class="progress">
                                    <div class="progress-bar {{ $budget->taux_utilisation > 100 ? 'bg-danger' : ($budget->taux_utilisation > 80 ? 'bg-warning' : 'bg-success') }}" 
                                         style="width: {{ min($budget->taux_utilisation, 100) }}%">
                                        {{ $budget->taux_execution }}%%
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📈 GRAPHIQUES PRINCIPAUX -->
        <div class="row g-4 mb-4">
            <!-- Évolution des dépenses -->
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5 class="chart-title">
                            <i class="fas fa-chart-line me-2"></i>
                            Évolution des Dépenses
                        </h5>
                        <div class="chart-legend">
                            <span class="legend-item legend-fixes">
                                <span class="legend-dot"></span> Fixes
                            </span>
                            <span class="legend-item legend-variables">
                                <span class="legend-dot"></span> Variables
                            </span>
                            <span class="legend-item legend-total">
                                <span class="legend-dot"></span> Total
                            </span>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="evolutionChart" height="80"></canvas>
                    </div>
                </div>
            </div>

            <!-- Répartition par type -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5 class="chart-title">
                            <i class="fas fa-chart-pie me-2"></i>
                            Répartition Variables
                        </h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="repartitionChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 DEUXIÈME LIGNE DE GRAPHIQUES -->
        <div class="row g-4 mb-4">
            <!-- Répartition Fixes -->
            <div class="col-lg-4">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5 class="chart-title">
                            <i class="fas fa-chart-pie me-2"></i>
                            Répartition Fixes
                        </h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="repartitionFixesChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Budget vs Réalisé -->
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5 class="chart-title">
                            <i class="fas fa-balance-scale me-2"></i>
                            Budget vs Réalisé ({{ $periode }} mois)
                        </h5>
                    </div>
                    <div class="chart-body">
                        <canvas id="budgetChart" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🏆 TOP DÉPENSES -->
        <div class="row g-4 mb-4">
            <!-- Top Dépenses Fixes -->
            <div class="col-lg-6">
                <div class="top-card">
                    <div class="top-header">
                        <h5 class="top-title">
                            <i class="fas fa-trophy me-2"></i>
                            Top 10 Dépenses Fixes
                        </h5>
                        <a href="{{ route('depenses.fixes.index') }}" class="btn btn-sm btn-outline-danger">
                            Voir tout <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="top-body">
                        @forelse($topFixes as $index => $depense)
                        <div class="top-item">
                            <div class="top-rank">{{ $index + 1 }}</div>
                            <div class="top-content">
                                <div class="top-name">{{ $depense->type_libelle }}</div>
                                <div class="top-desc">{{ $depense->libelle }}</div>
                            </div>
                            <div class="top-amount">{{ number_format($depense->montant_mensuel, 2) }} DH</div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                            <p>Aucune dépense fixe</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Top Dépenses Variables -->
            <div class="col-lg-6">
                <div class="top-card">
                    <div class="top-header">
                        <h5 class="top-title">
                            <i class="fas fa-trophy me-2"></i>
                            Top 10 Dépenses Variables
                        </h5>
                        <a href="{{ route('depenses.variables.index') }}" class="btn btn-sm btn-outline-danger">
                            Voir tout <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="top-body">
                        @forelse($topVariables as $index => $depense)
                        <div class="top-item">
                            <div class="top-rank">{{ $index + 1 }}</div>
                            <div class="top-content">
                                <div class="top-name">{{ $depense->type_libelle }}</div>
                                <div class="top-desc">{{ $depense->libelle }}</div>
                            </div>
                            <div class="top-amount">{{ number_format($depense->montant, 2) }} DH</div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                            <p>Aucune dépense variable pour ce mois</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 STATISTIQUES SUPPLÉMENTAIRES -->
        <div class="row g-4">
            <!-- Activité Récente -->
            <div class="col-lg-4">
                <div class="activity-card">
                    <div class="activity-header">
                        <h5 class="activity-title">
                            <i class="fas fa-clock me-2"></i>
                            Activité (7 derniers jours)
                        </h5>
                    </div>
                    <div class="activity-body">
                        <div class="activity-item">
                            <i class="fas fa-plus-circle activity-icon text-success"></i>
                            <div class="activity-content">
                                <div class="activity-label">Nouvelles dépenses</div>
                                <div class="activity-value">{{ $activiteRecente['nouvelles_depenses'] }}</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-check-circle activity-icon text-primary"></i>
                            <div class="activity-content">
                                <div class="activity-label">Validations</div>
                                <div class="activity-value">{{ $activiteRecente['validations'] }}</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-money-bill-wave activity-icon text-warning"></i>
                            <div class="activity-content">
                                <div class="activity-label">Montant ajouté</div>
                                <div class="activity-value">{{ number_format($activiteRecente['montant_ajoute'], 2) }} DH</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Moyennes -->
            <div class="col-lg-4">
                <div class="activity-card">
                    <div class="activity-header">
                        <h5 class="activity-title">
                            <i class="fas fa-calculator me-2"></i>
                            Moyennes
                        </h5>
                    </div>
                    <div class="activity-body">
                        <div class="activity-item">
                            <i class="fas fa-calendar-day activity-icon text-info"></i>
                            <div class="activity-content">
                                <div class="activity-label">Dépense quotidienne</div>
                                <div class="activity-value">{{ number_format($moyennes['depense_quotidienne'], 2) }} DH</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-sync activity-icon text-success"></i>
                            <div class="activity-content">
                                <div class="activity-label">Dépense fixe moyenne</div>
                                <div class="activity-value">{{ number_format($moyennes['depense_fixe_moyenne'], 2) }} DH</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-chart-bar activity-icon text-warning"></i>
                            <div class="activity-content">
                                <div class="activity-label">Dépense variable moyenne</div>
                                <div class="activity-value">{{ number_format($moyennes['depense_variable_moyenne'], 2) }} DH</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Annuelles -->
            <div class="col-lg-4">
                <div class="activity-card">
                    <div class="activity-header">
                        <h5 class="activity-title">
                            <i class="fas fa-chart-line me-2"></i>
                            Année {{ $annee }}
                        </h5>
                    </div>
                    <div class="activity-body">
                        <div class="activity-item">
                            <i class="fas fa-wallet activity-icon text-danger"></i>
                            <div class="activity-content">
                                <div class="activity-label">Total dépenses</div>
                                <div class="activity-value">{{ number_format($statsAnnuelles['total_depenses'], 2) }} DH</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-piggy-bank activity-icon text-primary"></i>
                            <div class="activity-content">
                                <div class="activity-label">Total budget</div>
                                <div class="activity-value">{{ number_format($statsAnnuelles['total_budget'], 2) }} DH</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <i class="fas fa-exclamation-triangle activity-icon text-warning"></i>
                            <div class="activity-content">
                                <div class="activity-label">Budgets dépassés</div>
                                <div class="activity-value">{{ $statsAnnuelles['budgets_depasses'] }} / {{ $statsAnnuelles['nombre_budgets'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <script>
        // 🎨 Configuration des couleurs (gradient rouge/rose)
        const chartColors = {
            primary: 'rgba(211, 47, 47, 0.8)',      // Rouge principal
            primaryLight: 'rgba(211, 47, 47, 0.4)',
            secondary: 'rgba(194, 24, 91, 0.8)',    // Rose
            secondaryLight: 'rgba(194, 24, 91, 0.4)',
            success: 'rgba(76, 175, 80, 0.8)',
            warning: 'rgba(255, 152, 0, 0.8)',
            info: 'rgba(33, 150, 243, 0.8)',
            gradient: null
        };

        // Gradient pour graphiques
        function createGradient(ctx, color1, color2) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, color1);
            gradient.addColorStop(1, color2);
            return gradient;
        }

        // 📈 Graphique Évolution
        const evolutionCtx = document.getElementById('evolutionChart').getContext('2d');
        const gradientFixes = createGradient(evolutionCtx, 'rgba(76, 175, 80, 0.4)', 'rgba(76, 175, 80, 0.05)');
        const gradientVariables = createGradient(evolutionCtx, 'rgba(255, 152, 0, 0.4)', 'rgba(255, 152, 0, 0.05)');
        const gradientTotal = createGradient(evolutionCtx, 'rgba(211, 47, 47, 0.4)', 'rgba(211, 47, 47, 0.05)');

        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($evolution->pluck('mois_short')) !!},
                datasets: [
                    {
                        label: 'Dépenses Fixes',
                        data: {!! json_encode($evolution->pluck('fixes')) !!},
                        borderColor: chartColors.success,
                        backgroundColor: gradientFixes,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 2,
                        pointBorderColor: chartColors.success
                    },
                    {
                        label: 'Dépenses Variables',
                        data: {!! json_encode($evolution->pluck('variables')) !!},
                        borderColor: chartColors.warning,
                        backgroundColor: gradientVariables,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 2,
                        pointBorderColor: chartColors.warning
                    },
                    {
                        label: 'Total',
                        data: {!! json_encode($evolution->pluck('total')) !!},
                        borderColor: chartColors.primary,
                        backgroundColor: gradientTotal,
                        borderWidth: 4,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 3,
                        pointBorderColor: chartColors.primary
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + 
                                       new Intl.NumberFormat('fr-FR', { 
                                           style: 'currency', 
                                           currency: 'MAD' 
                                       }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR', { 
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value) + ' DH';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });

        // 🥧 Graphique Répartition Variables
        const repartitionData = {!! json_encode($repartitionTypes) !!};
        const repartitionLabels = repartitionData.map(item => item.type);
        const repartitionValues = repartitionData.map(item => item.montant);
        
        const repartitionColors = [
            'rgba(211, 47, 47, 0.8)',
            'rgba(194, 24, 91, 0.8)',
            'rgba(255, 152, 0, 0.8)',
            'rgba(76, 175, 80, 0.8)',
            'rgba(33, 150, 243, 0.8)',
            'rgba(156, 39, 176, 0.8)',
            'rgba(255, 193, 7, 0.8)',
            'rgba(96, 125, 139, 0.8)'
        ];

        new Chart(document.getElementById('repartitionChart'), {
            type: 'doughnut',
            data: {
                labels: repartitionLabels,
                datasets: [{
                    data: repartitionValues,
                    backgroundColor: repartitionColors,
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12 },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + 
                                       new Intl.NumberFormat('fr-FR', { 
                                           style: 'currency', 
                                           currency: 'MAD' 
                                       }).format(context.parsed) + 
                                       ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // 🥧 Graphique Répartition Fixes
        const repartitionFixesData = {!! json_encode($repartitionTypesFixes) !!};
        const repartitionFixesLabels = repartitionFixesData.map(item => item.type);
        const repartitionFixesValues = repartitionFixesData.map(item => item.montant);

        new Chart(document.getElementById('repartitionFixesChart'), {
            type: 'doughnut',
            data: {
                labels: repartitionFixesLabels,
                datasets: [{
                    data: repartitionFixesValues,
                    backgroundColor: repartitionColors,
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12 },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + 
                                       new Intl.NumberFormat('fr-FR', { 
                                           style: 'currency', 
                                           currency: 'MAD' 
                                       }).format(context.parsed) + 
                                       ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // 📊 Graphique Budget vs Réalisé
        new Chart(document.getElementById('budgetChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($evolution->pluck('mois_short')) !!},
                datasets: [
                    {
                        label: 'Budget',
                        data: {!! json_encode($evolution->pluck('budget_total')) !!},
                        backgroundColor: 'rgba(33, 150, 243, 0.6)',
                        borderColor: 'rgba(33, 150, 243, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    },
                    {
                        label: 'Réalisé',
                        data: {!! json_encode($evolution->pluck('total')) !!},
                        backgroundColor: 'rgba(211, 47, 47, 0.6)',
                        borderColor: 'rgba(211, 47, 47, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 15,
                            font: { size: 13 },
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + 
                                       new Intl.NumberFormat('fr-FR', { 
                                           style: 'currency', 
                                           currency: 'MAD' 
                                       }).format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)',
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('fr-FR', { 
                                    notation: 'compact',
                                    compactDisplay: 'short'
                                }).format(value) + ' DH';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        }
                    }
                }
            }
        });
    </script>

    <style>
        /* 🎨 STYLES DASHBOARD MODERNE */
        .dashboard-container {
            padding: 0;
        }

        .dashboard-title {
            color: #D32F2F;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Formulaire de filtres */
        .filter-form .form-select-modern {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 10px 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-form .form-select-modern:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.15);
        }

        /* Alertes modernes */
        .alert-modern {
            border: none;
            border-left: 4px solid;
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 10px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Cartes statistiques */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #C2185B, #D32F2F);
        }

        .stat-card-primary::before { background: linear-gradient(90deg, #C2185B, #D32F2F); }
        .stat-card-success::before { background: linear-gradient(90deg, #4CAF50, #66BB6A); }
        .stat-card-warning::before { background: linear-gradient(90deg, #FF9800, #FFB74D); }
        .stat-card-info::before { background: linear-gradient(90deg, #2196F3, #42A5F5); }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .stat-card-primary .stat-icon {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            color: #D32F2F;
        }

        .stat-card-success .stat-icon {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(102, 187, 106, 0.1));
            color: #4CAF50;
        }

        .stat-card-warning .stat-icon {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.1), rgba(255, 183, 77, 0.1));
            color: #FF9800;
        }

        .stat-card-info .stat-icon {
            background: linear-gradient(135deg, rgba(33, 150, 243, 0.1), rgba(66, 165, 245, 0.1));
            color: #2196F3;
        }

        .stat-label {
            color: #757575;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-value {
            color: #212121;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .stat-trend {
            font-size: 0.875rem;
            font-weight: 600;
        }

        .stat-trend.trend-up {
            color: #f44336;
        }

        .stat-trend.trend-down {
            color: #4CAF50;
        }

        .stat-progress {
            margin-top: 10px;
        }

        .stat-progress .progress {
            height: 8px;
            border-radius: 10px;
            background-color: #f5f5f5;
        }

        .stat-progress .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* Cartes graphiques */
        .chart-card {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f5f5f5;
        }

        .chart-title {
            color: #212121;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .chart-legend {
            display: flex;
            gap: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .legend-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .legend-fixes .legend-dot { background: rgba(76, 175, 80, 0.8); }
        .legend-variables .legend-dot { background: rgba(255, 152, 0, 0.8); }
        .legend-total .legend-dot { background: rgba(211, 47, 47, 0.8); }

        .chart-body {
            position: relative;
        }

        /* Cartes Top */
        .top-card {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            height: 100%;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f5f5f5;
        }

        .top-title {
            color: #212121;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .top-body {
            max-height: 500px;
            overflow-y: auto;
        }

        .top-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            margin-bottom: 10px;
            background: #f9f9f9;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .top-item:hover {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            transform: translateX(5px);
        }

        .top-rank {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .top-content {
            flex: 1;
        }

        .top-name {
            font-weight: 600;
            color: #212121;
            margin-bottom: 4px;
        }

        .top-desc {
            font-size: 0.875rem;
            color: #757575;
        }

        .top-amount {
            font-weight: 700;
            color: #D32F2F;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* Cartes Activité */
        .activity-card {
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            height: 100%;
        }

        .activity-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f5f5f5;
        }

        .activity-title {
            color: #212121;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .activity-body {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 12px;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-label {
            font-size: 0.875rem;
            color: #757575;
            margin-bottom: 4px;
        }

        .activity-value {
            font-size: 1.25rem;
            font-weight: 700;
            color: #212121;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .dashboard-title {
                font-size: 1.5rem;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .chart-legend {
                flex-direction: column;
                gap: 10px;
            }
        }

        @media (max-width: 768px) {
            .filter-form .row {
                gap: 10px;
            }

            .stat-card {
                margin-bottom: 15px;
            }

            .top-rank {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }

            .top-amount {
                font-size: 1rem;
            }
        }
    </style>
    @endpush
</x-app-layout>