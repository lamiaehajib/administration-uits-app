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
            padding: 15px;
        }

        .dashboard-header {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            padding: 25px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(194, 24, 91, 0.3);
        }

        .dashboard-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
        }

        .dashboard-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
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
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            border-radius: 0 12px 0 100%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stat-card.pink { border-left-color: var(--color-primary); }
        .stat-card.red { border-left-color: var(--color-secondary); }
        .stat-card.accent { border-left-color: var(--color-accent); }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 12px;
            color: white;
        }

        .stat-card.pink .icon { background: linear-gradient(135deg, var(--color-primary), #E91E63); }
        .stat-card.red .icon { background: linear-gradient(135deg, var(--color-secondary), #F44336); }
        .stat-card.accent .icon { background: linear-gradient(135deg, var(--color-accent), #dc2626); }

        .stat-card h3 {
            font-size: 0.85rem;
            color: #6c757d;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .stat-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: #212529;
            margin: 0 0 5px 0;
        }

        .stat-card .subtitle {
            font-size: 0.8rem;
            color: #28a745;
            font-weight: 500;
        }

        .stat-card small {
            color: #6c757d;
            display: block;
            margin-top: 8px;
            font-size: 0.8rem;
        }

        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            height: 380px;
        }

        .chart-container.full-width {
            height: 320px;
        }

        .chart-container h2 {
            font-size: 1.1rem;
            color: var(--color-secondary);
            margin: 0 0 15px 0;
            font-weight: 700;
            text-transform: uppercase;
            border-bottom: 3px solid var(--color-primary);
            padding-bottom: 8px;
        }

        .chart-wrapper {
            position: relative;
            height: calc(100% - 50px);
            width: 100%;
        }

        .charts-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .charts-row-full {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 300px;
            overflow-y: auto;
        }

        .activity-list::-webkit-scrollbar {
            width: 5px;
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
            padding: 12px;
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
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: white;
            font-size: 1rem;
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
            font-size: 0.85rem;
        }

        .activity-description {
            color: #6c757d;
            font-size: 0.8rem;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .activity-amount {
            font-weight: 700;
            color: #212529;
            font-size: 1rem;
            margin-left: 10px;
            white-space: nowrap;
        }

        .activity-date {
            color: #adb5bd;
            font-size: 0.7rem;
            margin-top: 2px;
        }

        /* Responsive pour tablettes */
        @media (max-width: 1024px) {
            .charts-row {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }
        }

        /* Responsive pour mobiles */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 10px;
            }

            .dashboard-header {
                padding: 20px 15px;
                border-radius: 10px;
                margin-bottom: 15px;
            }

            .dashboard-header h1 {
                font-size: 1.4rem;
            }

            .dashboard-header h1 i {
                display: none;
            }

            .dashboard-header p {
                font-size: 0.85rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 12px;
                margin-bottom: 15px;
            }

            .stat-card {
                padding: 15px;
                border-radius: 10px;
            }

            .stat-card .icon {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
                margin-bottom: 10px;
            }

            .stat-card h3 {
                font-size: 0.8rem;
            }

            .stat-card .value {
                font-size: 1.6rem;
            }

            .stat-card .subtitle {
                font-size: 0.75rem;
            }

            .stat-card small {
                font-size: 0.75rem;
                margin-top: 6px;
            }

            .chart-container {
                padding: 15px;
                border-radius: 10px;
                margin-bottom: 15px;
                height: 300px;
            }

            .chart-container.full-width {
                height: 280px;
            }

            .chart-container h2 {
                font-size: 0.95rem;
                margin-bottom: 12px;
                padding-bottom: 6px;
            }

            .chart-container h2 i {
                font-size: 0.9rem;
            }

            .chart-wrapper {
                height: calc(100% - 45px);
            }

            .charts-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .activity-list {
                max-height: 250px;
            }

            .activity-item {
                padding: 10px 8px;
            }

            .activity-icon {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
                margin-right: 10px;
            }

            .activity-type {
                font-size: 0.8rem;
            }

            .activity-description {
                font-size: 0.75rem;
            }

            .activity-amount {
                font-size: 0.9rem;
                margin-left: 8px;
            }

            .activity-date {
                font-size: 0.65rem;
            }
        }

        /* Responsive pour très petits écrans */
        @media (max-width: 480px) {
            .dashboard-container {
                padding: 8px;
            }

            .dashboard-header {
                padding: 15px 12px;
            }

            .dashboard-header h1 {
                font-size: 1.2rem;
            }

            .dashboard-header p {
                font-size: 0.8rem;
            }

            .stat-card {
                padding: 12px;
            }

            .stat-card .icon {
                width: 40px;
                height: 40px;
                font-size: 1.1rem;
            }

            .stat-card .value {
                font-size: 1.4rem;
            }

            .chart-container {
                padding: 12px;
                height: 280px;
            }

            .chart-container.full-width {
                height: 260px;
            }

            .chart-container h2 {
                font-size: 0.9rem;
                margin-bottom: 10px;
            }

            .activity-item {
                flex-wrap: wrap;
                padding: 8px;
            }

            .activity-amount {
                flex-basis: 100%;
                margin-left: 47px;
                margin-top: 5px;
                text-align: left;
            }
        }

        /* Optimisation du scroll sur mobile */
        @media (max-width: 768px) {
            .activity-list::-webkit-scrollbar {
                width: 3px;
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
                <small>
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
                <small>
                    <i class="fas fa-coins"></i> {{ number_format($revenusReussitesf, 2) }} DH
                </small>
            </div>

            <!-- Reçus UCG -->
            <div class="stat-card accent">
                <div class="icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Reçus Garantie</h3>
                <div class="value">{{ $totalRecuUcgs }}</div>
                <div class="subtitle">
                    <i class="fas fa-arrow-up"></i> {{ $recuUcgsCurrentMonth }} ce mois
                </div>
                <small>
                    <i class="fas fa-coins"></i> {{ number_format($revenusRecuUcgs, 2) }} DH
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
                <small>
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
                <small>
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
                <small>
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
                <small>
                    <i class="fas fa-coins"></i> {{ number_format($facturesfRevenu, 2) }} DH
                </small>
            </div>

            <!-- Revenu Total -->
            <div class="stat-card red">
                <div class="icon" style="width: 60px; height: 60px; font-size: 2rem;">
                    <i class="fas fa-wallet"></i>
                </div>
                <h3>Revenu Total</h3>
                <div class="value" style="font-size: 2rem;">{{ number_format($revenuTotal, 2) }} DH</div>
                <div class="subtitle">
                    <i class="fas fa-chart-line"></i> Performance globale
                </div>
            </div>
        </div>

        <!-- Graphique Évolution mensuelle (pleine largeur) -->
        <div class="charts-row-full">
            <div class="chart-container full-width">
                <h2><i class="fas fa-chart-area"></i> Évolution des Revenus</h2>
                <div class="chart-wrapper">
                    <canvas id="monthlyRevenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphiques en 2 colonnes -->
        <div class="charts-row">
            <!-- Répartition des revenus -->
            <div class="chart-container">
                <h2><i class="fas fa-chart-pie"></i> Répartition</h2>
                <div class="chart-wrapper">
                    <canvas id="revenueTypeChart"></canvas>
                </div>
            </div>

            <!-- Documents par catégorie -->
            <div class="chart-container">
                <h2><i class="fas fa-chart-bar"></i> Documents</h2>
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
                <h2><i class="fas fa-history"></i> Activités</h2>
                <ul class="activity-list">
                    @forelse($recentActivities as $index => $activity)
                        <li class="activity-item">
                            <div class="activity-icon {{ $index % 3 == 0 ? 'pink' : ($index % 3 == 1 ? 'red' : 'accent') }}">
                                <i class="fas fa-{{ $activity['type'] == 'Reçu Stage' ? 'receipt' : ($activity['type'] == 'Facture Projet' ? 'file-invoice-dollar' : 'money-check-alt') }}"></i>
                            </div>
                            <div class="activity-details">
                                <div class="activity-type">{{ $activity['type'] }}</div>
                                <div class="activity-description">{{ Str::limit($activity['description'], 35) }}</div>
                                <div class="activity-date">{{ $activity['date']->diffForHumans() }}</div>
                            </div>
                            <div class="activity-amount">
                                {{ number_format($activity['amount'], 2) }} DH
                            </div>
                        </li>
                    @empty
                        <li class="activity-item">
                            <p class="text-muted text-center w-100">Aucune activité</p>
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
            accent: '#ef4444'
        };

        // Configuration responsive pour les graphiques
        const isMobile = window.innerWidth < 768;
        const chartFontSize = isMobile ? 9 : 11;
        const legendFontSize = isMobile ? 10 : 13;

        // Graphique : Évolution mensuelle des revenus
        const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        const monthlyData = @json($monthlyRevenue);
        
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: monthlyData.map(item => isMobile ? item.month.substring(0, 3) : item.month),
                datasets: [{
                    label: 'Revenus (DH)',
                    data: monthlyData.map(item => item.revenue),
                    borderColor: colors.primary,
                    backgroundColor: 'rgba(194, 24, 91, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: isMobile ? 4 : 6,
                    pointHoverRadius: isMobile ? 6 : 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: !isMobile,
                        position: 'top',
                        labels: {
                            font: { size: legendFontSize, weight: 'bold' },
                            color: '#212529',
                            padding: isMobile ? 8 : 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: isMobile ? 8 : 12,
                        titleFont: { size: isMobile ? 11 : 13 },
                        bodyFont: { size: isMobile ? 10 : 12 },
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
                                return isMobile ? value.toLocaleString() : value.toLocaleString() + ' DH';
                            },
                            font: { size: chartFontSize }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: chartFontSize },
                            maxRotation: isMobile ? 45 : 0
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
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: isMobile ? 8 : 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: isMobile ? 8 : 12,
                            font: { size: isMobile ? 9 : 11 },
                            boxWidth: isMobile ? 10 : 12,
                            boxHeight: isMobile ? 10 : 12
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: isMobile ? 8 : 12,
                        bodyFont: { size: isMobile ? 10 : 12 },
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
                    label: 'Documents',
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
                    borderRadius: isMobile ? 5 : 8,
                    maxBarThickness: isMobile ? 35 : 50
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: isMobile ? 8 : 10,
                        bodyFont: { size: isMobile ? 10 : 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1,
                            font: { size: chartFontSize }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: isMobile ? 8 : 10 },
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
                    label: 'CA (DH)',
                    data: topClientsData.map(item => item.revenue),
                    backgroundColor: colors.secondary,
                    borderRadius: isMobile ? 5 : 8,
                    maxBarThickness: isMobile ? 30 : 40
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
                        padding: isMobile ? 8 : 10,
                        bodyFont: { size: isMobile ? 10 : 12 },
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
                                return isMobile ? value.toLocaleString() : value.toLocaleString() + ' DH';
                            },
                            font: { size: isMobile ? 8 : 10 }
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