<x-app-layout>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        :root {
            --color-primary: #C2185B;
            --color-secondary: #D32F2F;
            --color-accent: #ef4444;
        }

        .dashboard-container {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 8px 20px rgba(194, 24, 91, 0.3);
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .dashboard-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border-left: 5px solid;
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
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            border-radius: 0 15px 0 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.pink { border-left-color: var(--color-primary); }
        .stat-card.red { border-left-color: var(--color-secondary); }
        .stat-card.accent { border-left-color: var(--color-accent); }

        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: white;
        }

        .stat-card.pink .icon { background: linear-gradient(135deg, var(--color-primary), #E91E63); }
        .stat-card.red .icon { background: linear-gradient(135deg, var(--color-secondary), #F44336); }
        .stat-card.accent .icon { background: linear-gradient(135deg, var(--color-accent), #dc2626); }

        .stat-card h3 {
            font-size: 0.9rem;
            color: #6c757d;
            margin: 0 0 10px 0;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-card .value {
            font-size: 2rem;
            font-weight: 700;
            color: #212529;
            margin: 0 0 5px 0;
        }

        .stat-card .subtitle {
            font-size: 0.85rem;
            color: #28a745;
            font-weight: 500;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            height: 400px; /* Hauteur fixe pour les graphiques */
        }

        .chart-container.full-width {
            height: 350px; /* Plus petit pour le graphique ligne */
        }

        .chart-container h2 {
            font-size: 1.3rem;
            color: var(--color-secondary);
            margin: 0 0 20px 0;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 3px solid var(--color-primary);
            padding-bottom: 10px;
        }

        .chart-wrapper {
            position: relative;
            height: calc(100% - 60px); /* Hauteur moins le titre */
            width: 100%;
        }

        .charts-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .charts-row-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 320px;
            overflow-y: auto;
        }

        .activity-list::-webkit-scrollbar {
            width: 6px;
        }

        .activity-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .activity-list::-webkit-scrollbar-thumb {
            background: var(--color-primary);
            border-radius: 10px;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            transition: background 0.3s ease;
        }

        .activity-item:hover {
            background: #f8f9fa;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .activity-icon.pink { background: linear-gradient(135deg, var(--color-primary), #E91E63); }
        .activity-icon.red { background: linear-gradient(135deg, var(--color-secondary), #F44336); }
        .activity-icon.accent { background: linear-gradient(135deg, var(--color-accent), #dc2626); }

        .activity-details {
            flex: 1;
            min-width: 0;
        }

        .activity-type {
            font-weight: 600;
            color: var(--color-secondary);
            font-size: 0.9rem;
        }

        .activity-description {
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .activity-amount {
            font-weight: 700;
            color: #212529;
            font-size: 1.1rem;
            margin-left: 15px;
            white-space: nowrap;
        }

        .activity-date {
            color: #adb5bd;
            font-size: 0.75rem;
            margin-top: 3px;
        }

        @media (max-width: 1200px) {
            .charts-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .dashboard-header h1 {
                font-size: 1.8rem;
            }
            .chart-container {
                height: 350px;
            }
        }
    </style>

    <div class="dashboard-container">
        <!-- Header -->
        <div class="dashboard-header">
            <h1><i class="fas fa-chart-line"></i> Tableau de Bord</h1>
            <p><i class="fas fa-calendar-alt"></i> {{ now()->format('d/m/Y') }} - Vue d'ensemble de votre activité</p>
        </div>

        <!-- Cards statistiques principales -->
        <div class="stats-grid">
            <!-- Reçus Stage -->
            <div class="stat-card pink">
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <h3>Reçus Stage</h3>
                <div class="value">{{ $totalReussites }}</div>
                <div class="subtitle">
                    <i class="fas fa-arrow-up"></i> {{ $reussitesCurrentMonth }} ce mois
                </div>
                <small style="color: #6c757d; display: block; margin-top: 10px;">
                    <i class="fas fa-coins"></i> {{ number_format($revenusReussites, 2) }} DH
                </small>
            </div>

            <!-- Reçus Formation -->
            <div class="stat-card red">
                <div class="icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3>Reçus Formation</h3>
                <div class="value">{{ $totalReussitesf }}</div>
                <div class="subtitle">
                    <i class="fas fa-arrow-up"></i> {{ $reussitesfCurrentMonth }} ce mois
                </div>
                <small style="color: #6c757d; display: block; margin-top: 10px;">
                    <i class="fas fa-coins"></i> {{ number_format($revenusReussitesf, 2) }} DH
                </small>
            </div>

            <!-- Reçus UCG -->
            <div class="stat-card accent">
                <div class="icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Reçus Garantie</h3>
                <div class="value">{{ $totalUcgs }}</div>
                <div class="subtitle">
                    <i class="fas fa-arrow-up"></i> {{ $ucgsCurrentMonth }} ce mois
                </div>
                <small style="color: #6c757d; display: block; margin-top: 10px;">
                    <i class="fas fa-coins"></i> {{ number_format($revenusUcgs, 2) }} DH
                </small>
            </div>

            <!-- Devis Projet -->
            <div class="stat-card pink">
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h3>Devis Projet</h3>
                <div class="value">{{ $totalDevis }}</div>
                <div class="subtitle">
                    <i class="fas fa-arrow-up"></i> {{ $devisCurrentMonth }} ce mois
                </div>
                <small style="color: #6c757d; display: block; margin-top: 10px;">
                    <i class="fas fa-coins"></i> {{ number_format($devisValeurTotal, 2) }} DH
                </small>
            </div>

            <!-- Devis Formation -->
            <div class="stat-card red">
                <div class="icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h3>Devis Formation</h3>
                <div class="value">{{ $totalDevisf }}</div>
                <div class="subtitle">
                    <i class="fas fa-arrow-up"></i> {{ $devisfCurrentMonth }} ce mois
                </div>
                <small style="color: #6c757d; display: block; margin-top: 10px;">
                    <i class="fas fa-coins"></i> {{ number_format($devisfValeurTotal, 2) }} DH
                </small>
            </div>

            <!-- Factures Projet -->
            <div class="stat-card accent">
                <div class="icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3>Factures Projet</h3>
                <div class="value">{{ $totalFactures }}</div>
                <div class="subtitle">
                    <i class="fas fa-arrow-up"></i> {{ $facturesCurrentMonth }} ce mois
                </div>
                <small style="color: #6c757d; display: block; margin-top: 10px;">
                    <i class="fas fa-coins"></i> {{ number_format($facturesRevenu, 2) }} DH
                </small>
            </div>

            <!-- Factures Formation -->
            <div class="stat-card pink">
                <div class="icon">
                    <i class="fas fa-money-check-alt"></i>
                </div>
                <h3>Factures Formation</h3>
                <div class="value">{{ $totalFacturesf }}</div>
                <div class="subtitle">
                    <i class="fas fa-arrow-up"></i> {{ $facturesfCurrentMonth }} ce mois
                </div>
                <small style="color: #6c757d; display: block; margin-top: 10px;">
                    <i class="fas fa-coins"></i> {{ number_format($facturesfRevenu, 2) }} DH
                </small>
            </div>

            <!-- Revenu Total -->
            <div class="stat-card red">
                <div class="icon" style="width: 80px; height: 80px; font-size: 2.5rem;">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Revenu Total</h3>
                <div class="value" style="font-size: 2.5rem;">{{ number_format($revenuTotal, 2) }} DH</div>
                <div class="subtitle">
                    <i class="fas fa-chart-line"></i> Performance globale
                </div>
            </div>
        </div>

        <!-- Graphique Évolution mensuelle (pleine largeur) -->
        <div class="charts-row-full">
            <div class="chart-container full-width">
                <h2><i class="fas fa-chart-area"></i> Évolution des Revenus (6 derniers mois)</h2>
                <div class="chart-wrapper">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphiques en 2 colonnes -->
        <div class="charts-row">
            <!-- Répartition des revenus -->
            <div class="chart-container">
                <h2><i class="fas fa-chart-pie"></i> Répartition des Revenus</h2>
                <div class="chart-wrapper">
                    <canvas id="revenueTypeChart"></canvas>
                </div>
            </div>

            <!-- Documents par catégorie -->
            <div class="chart-container">
                <h2><i class="fas fa-chart-bar"></i> Documents par Catégorie</h2>
                <div class="chart-wrapper">
                    <canvas id="documentCountChart"></canvas>
                </div>
            </div>
        </div>

        <div class="charts-row">
            <!-- Top clients -->
            <div class="chart-container">
                <h2><i class="fas fa-users"></i> Top 5 Clients</h2>
                <div class="chart-wrapper">
                    <canvas id="topClientsChart"></canvas>
                </div>
            </div>

            <!-- Activités récentes -->
            <div class="chart-container">
                <h2><i class="fas fa-history"></i> Activités Récentes</h2>
                <ul class="activity-list">
                    @forelse($recentActivities as $index => $activity)
                        <li class="activity-item">
                            <div class="activity-icon {{ $index % 3 == 0 ? 'pink' : ($index % 3 == 1 ? 'red' : 'accent') }}">
                                <i class="fas fa-{{ $activity['type'] == 'Reçu Stage' ? 'receipt' : ($activity['type'] == 'Facture Projet' ? 'file-invoice-dollar' : 'money-check-alt') }}"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-type">{{ $activity['type'] }}</div>
                                <div class="activity-description">{{ Str::limit($activity['description'], 40) }}</div>
                                <div class="activity-date">{{ $activity['date']->diffForHumans() }}</div>
                            </div>
                            <div class="activity-amount">
                                {{ number_format($activity['amount'], 2) }} DH
                            </div>
                        </li>
                    @empty
                        <li class="activity-item">
                            <p class="text-muted text-center w-100">Aucune activité récente</p>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <script>
        // Configuration des couleurs
        const colors = {
            primary: '#C2185B',
            secondary: '#D32F2F',
            accent: '#ef4444',
            gradients: {
                pink: ['#C2185B', '#E91E63'],
                red: ['#D32F2F', '#F44336'],
                accent: ['#ef4444', '#dc2626']
            }
        };

        // Graphique : Évolution mensuelle des revenus
        const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        const monthlyData = @json($monthlyRevenue);
        
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => item.month),
                datasets: [{
                    label: 'Revenus (DH)',
                    data: monthlyData.map(item => item.revenue),
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(194, 24, 91, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 13, weight: 'bold' },
                            color: '#212529',
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                return 'Revenus: ' + context.parsed.y.toLocaleString() + ' DH';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' DH';
                            },
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 11 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Graphique : Répartition des revenus
        const revenueTypeCtx = document.getElementById('revenueTypeChart').getContext('2d');
        const revenueTypeData = @json($revenueByType);
        
        new Chart(revenueTypeCtx, {
            type: 'doughnut',
            data: {
                labels: revenueTypeData.map(item => item.type),
                datasets: [{
                    data: revenueTypeData.map(item => item.amount),
                    backgroundColor: [
                        colors.primary,
                        colors.secondary,
                        colors.accent,
                        '#E91E63',
                        '#F44336'
                    ],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 12,
                            font: { size: 11 },
                            boxWidth: 12,
                            boxHeight: 12
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value.toLocaleString() + ' DH (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Graphique : Documents par catégorie
        const documentCountCtx = document.getElementById('documentCountChart').getContext('2d');
        const documentCountData = @json($documentCounts);
        
        new Chart(documentCountCtx, {
            type: 'bar',
            data: {
                labels: documentCountData.map(item => item.category),
                datasets: [{
                    label: 'Nombre de documents',
                    data: documentCountData.map(item => item.count),
                    backgroundColor: [
                        colors.primary,
                        colors.secondary,
                        colors.accent,
                        '#C2185B',
                        '#D32F2F',
                        '#ef4444',
                        '#E91E63'
                    ],
                    borderWidth: 0,
                    borderRadius: 8,
                    maxBarThickness: 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 10,
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1,
                            font: { size: 11 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 10 },
                            maxRotation: 45,
                            minRotation: 45
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Graphique : Top clients
        const topClientsCtx = document.getElementById('topClientsChart').getContext('2d');
        const topClientsData = @json($topClients);
        
        new Chart(topClientsCtx, {
            type: 'bar',
            data: {
                labels: topClientsData.map(item => item.client),
                datasets: [{
                    label: 'Chiffre d\'affaires (DH)',
                    data: topClientsData.map(item => item.revenue),
                    backgroundColor: colors.secondary,
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 10,
                        bodyFont: { size: 12 },
                        callbacks: {
                            label: function(context) {
                                return 'CA: ' + context.parsed.x.toLocaleString() + ' DH';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' DH';
                            },
                            font: { size: 10 }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    y: {
                        ticks: {
                            font: { size: 11 }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>