<x-app-layout>
    <style>
        /* ============= STYLES GÉNÉRAUX ============= */
        .benefice-container {
            background: #f8f9fa;
            padding: 0;
        }

        /* Header avec gradient */
        .page-header {
            background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
        }

        .page-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .page-header p {
            margin: 10px 0 0 0;
            opacity: 0.95;
            font-size: 1.1rem;
        }

        /* ============= CARDS STATISTIQUES ============= */
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .stats-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .stats-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }

        .icon-formations {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .icon-services {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .icon-stages {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .icon-portail {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .icon-total {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .stats-card-title {
            font-size: 0.9rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stats-card-value {
            font-size: 2rem;
            font-weight: bold;
            color: #2d3748;
            margin: 10px 0;
        }

        .stats-card-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .variation-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .variation-positive {
            background: #d4edda;
            color: #155724;
        }

        .variation-negative {
            background: #f8d7da;
            color: #721c24;
        }

        /* ============= CHART SECTION ============= */
        .chart-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f1f3f5;
        }

        .chart-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d3748;
        }

        /* ============= TABLE DETAILS ============= */
        .table-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .custom-table {
            width: 100%;
            margin-top: 20px;
        }

        .custom-table thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .custom-table thead th {
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
        }

        .custom-table tbody tr {
            transition: all 0.2s ease;
        }

        .custom-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .custom-table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        /* ============= BUTTONS ============= */
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .btn-outline-gradient {
            background: white;
            color: #D32F2F;
            border: 2px solid #D32F2F;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: transparent;
        }

        /* ============= MODAL STYLES ============= */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px 30px;
            border: none;
        }

        .modal-header h5 {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.15);
        }

        /* ============= RESPONSIVE ============= */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }
            
            .stats-card-value {
                font-size: 1.5rem;
            }
            
            .chart-header {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>

    <div class="benefice-container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-chart-line me-3"></i>Tableau de Bord des Bénéfices</h1>
                    <p class="mb-0">Période : {{ $from->format('d/m/Y') }} - {{ $to->format('d/m/Y') }}</p>
                </div>
                <div>
                    <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter me-2"></i>Filtrer
                    </button>
                    <a href="{{ route('beneficier.export.csv', request()->all()) }}" class="btn btn-light btn-lg ms-2">
                        <i class="fas fa-file-export me-2"></i>Exporter CSV
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Card Total -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-card-header">
                        <div>
                            <div class="stats-card-title">Revenu Total</div>
                            <div class="stats-card-value">{{ number_format($stats['total_general'], 2) }} {{ $currency }}</div>
                        </div>
                        <div class="stats-card-icon icon-total">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                    <div class="stats-card-info">
                        <span>Période sélectionnée</span>
                        <span class="variation-badge {{ $comparison['variation'] >= 0 ? 'variation-positive' : 'variation-negative' }}">
                            <i class="fas fa-{{ $comparison['variation'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ number_format(abs($comparison['variation']), 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Formations -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-card-header">
                        <div>
                            <div class="stats-card-title">Factures Formations</div>
                            <div class="stats-card-value">{{ number_format($stats['formations']['total'], 2) }} {{ $currency }}</div>
                        </div>
                        <div class="stats-card-icon icon-formations">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                    </div>
                    <div class="stats-card-info">
                        <span>{{ $stats['formations']['count'] }} factures • {{ $stats['formations']['clients'] }} clients</span>
                        <span class="variation-badge {{ $comparison['formations_variation'] >= 0 ? 'variation-positive' : 'variation-negative' }}">
                            <i class="fas fa-{{ $comparison['formations_variation'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ number_format(abs($comparison['formations_variation']), 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Services -->
            <div class="col-xl-4 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-card-header">
                        <div>
                            <div class="stats-card-title">Factures des Services</div>
                            <div class="stats-card-value">{{ number_format($stats['services']['total'], 2) }} {{ $currency }}</div>
                        </div>
                        <div class="stats-card-icon icon-services">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="stats-card-info">
                        <span>{{ $stats['services']['count'] }} factures • {{ $stats['services']['clients'] }} clients</span>
                        <span class="variation-badge {{ $comparison['services_variation'] >= 0 ? 'variation-positive' : 'variation-negative' }}">
                            <i class="fas fa-{{ $comparison['services_variation'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ number_format(abs($comparison['services_variation']), 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Stages -->
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-card-header">
                        <div>
                            <div class="stats-card-title">Reçus de Stage</div>
                            <div class="stats-card-value">{{ number_format($stats['stages']['total'], 2) }} DH</div>
                        </div>
                        <div class="stats-card-icon icon-stages">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stats-card-info">
                        <span>{{ $stats['stages']['count'] }} paiements • {{ $stats['stages']['stagiaires'] }} stagiaires</span>
                        <span class="variation-badge {{ $comparison['stages_variation'] >= 0 ? 'variation-positive' : 'variation-negative' }}">
                            <i class="fas fa-{{ $comparison['stages_variation'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ number_format(abs($comparison['stages_variation']), 1) }}%
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card Portail -->
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="stats-card">
                    <div class="stats-card-header">
                        <div>
                            <div class="stats-card-title">Payments des Étudiants (Portail)</div>
                            <div class="stats-card-value">{{ number_format($stats['portail'], 2) }} DH</div>
                        </div>
                        <div class="stats-card-icon icon-portail">
                            <i class="fas fa-globe"></i>
                        </div>
                    </div>
                    <div class="stats-card-info">
                        <span>Via portail UITS</span>
                        <span class="variation-badge {{ $comparison['portail_variation'] >= 0 ? 'variation-positive' : 'variation-negative' }}">
                            <i class="fas fa-{{ $comparison['portail_variation'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ number_format(abs($comparison['portail_variation']), 1) }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-section">
            <div class="chart-header">
                <h3 class="chart-title"><i class="fas fa-chart-area me-2"></i>Évolution Mensuelle (12 derniers mois)</h3>
            </div>
            <canvas id="revenueChart" height="80"></canvas>
        </div>

        <!-- Detailed Table -->
        <div class="table-section">
            <div class="chart-header">
                <h3 class="chart-title"><i class="fas fa-table me-2"></i>Détails par Mois</h3>
            </div>
            <div class="table-responsive">
                <table class="custom-table table">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th>Formations</th>
                            <th>Services</th>
                            <th>Stages</th>
                            <th>Portail</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailsParMois as $detail)
                        <tr>
                            <td><strong>{{ $detail['mois'] }}</strong></td>
                            <td>{{ number_format($detail['formations'], 2) }} {{ $currency }}<br>
                                <small class="text-muted">({{ $detail['formations_count'] }} factures)</small>
                            </td>
                            <td>{{ number_format($detail['services'], 2) }} {{ $currency }}<br>
                                <small class="text-muted">({{ $detail['services_count'] }} factures)</small>
                            </td>
                            <td>{{ number_format($detail['stages'], 2) }} DH<br>
                                <small class="text-muted">({{ $detail['stages_count'] }} reçus)</small>
                            </td>
                            <td>{{ number_format($detail['portail'], 2) }} DH</td>
                            <td><strong>{{ number_format($detail['total'], 2) }} {{ $currency }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fas fa-filter me-2"></i>Filtrer les Données
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="GET" action="{{ route('beneficier.index') }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Période</label>
                                <select name="periode" id="periodeSelect" class="form-select">
                                    <option value="aujourdhui" {{ $periode == 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                                    <option value="cette_semaine" {{ $periode == 'cette_semaine' ? 'selected' : '' }}>Cette semaine</option>
                                    <option value="ce_mois" {{ $periode == 'ce_mois' ? 'selected' : '' }}>Ce mois</option>
                                    <option value="ce_trimestre" {{ $periode == 'ce_trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                                    <option value="cette_annee" {{ $periode == 'cette_annee' ? 'selected' : '' }}>Cette année</option>
                                    <option value="12_mois" {{ $periode == '12_mois' ? 'selected' : '' }}>12 derniers mois</option>
                                    <option value="personnalise" {{ $periode == 'personnalise' ? 'selected' : '' }}>Période personnalisée</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Devise</label>
                                <select name="currency" class="form-select">
                                    <option value="DH" {{ $currency == 'DH' ? 'selected' : '' }}>DH (Dirham)</option>
                                    <option value="EUR" {{ $currency == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                                    <option value="USD" {{ $currency == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3" id="dateDebutGroup" style="display: none;">
                                <label class="form-label">Date de début</label>
                                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                            </div>

                            <div class="col-md-6 mb-3" id="dateFinGroup" style="display: none;">
                                <label class="form-label">Date de fin</label>
                                <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-gradient" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-check me-2"></i>Appliquer les filtres
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Configuration
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData['labels']) !!},
                datasets: [
                    {
                        label: 'Formations',
                        data: {!! json_encode($chartData['formations']) !!},
                        borderColor: 'rgb(102, 126, 234)',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Services',
                        data: {!! json_encode($chartData['services']) !!},
                        borderColor: 'rgb(245, 87, 108)',
                        backgroundColor: 'rgba(245, 87, 108, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Stages',
                        data: {!! json_encode($chartData['stages']) !!},
                        borderColor: 'rgb(79, 172, 254)',
                        backgroundColor: 'rgba(79, 172, 254, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Portail',
                        data: {!! json_encode($chartData['portail']) !!},
                        borderColor: 'rgb(67, 233, 123)',
                        backgroundColor: 'rgba(67, 233, 123, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 13,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' {{ $currency }}';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });

        // Toggle custom date inputs
        document.getElementById('periodeSelect').addEventListener('change', function() {
            const isCustom = this.value === 'personnalise';
            document.getElementById('dateDebutGroup').style.display = isCustom ? 'block' : 'none';
            document.getElementById('dateFinGroup').style.display = isCustom ? 'block' : 'none';
        });

        // Initialize on page load
        if (document.getElementById('periodeSelect').value === 'personnalise') {
            document.getElementById('dateDebutGroup').style.display = 'block';
            document.getElementById('dateFinGroup').style.display = 'block';
        }
    </script>
    @endpush
</x-app-layout>