<x-app-layout>
    <style>
        .details-container {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .page-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.3);
        }

        .page-header h1 {
            color: white;
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .stat-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        .chart-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .table-modern {
            width: 100%;
            border-collapse: collapse;
        }

        .table-modern thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .table-modern thead th {
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }

        .table-modern tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-modern tbody tr:hover {
            background: rgba(194, 24, 91, 0.05);
        }
    </style>

    <div class="details-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-chart-pie"></i> Détails: {{ $category->nom }}</h1>
            <p>Analyse complète de la catégorie</p>
        </div>

        <!-- Statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #2196F3, #1976D2);">
                    <i class="fas fa-box"></i>
                </div>
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">PRODUITS TOTAL</div>
                <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ $stats['total_produits'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #4CAF50, #388E3C);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">PRODUITS ACTIFS</div>
                <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ $stats['produits_actifs'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #f44336, #d32f2f);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">RUPTURE STOCK</div>
                <div style="font-size: 2rem; font-weight: bold; color: #333;">{{ $stats['produits_rupture'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #FF9800, #F57C00);">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">VALEUR STOCK</div>
                <div style="font-size: 1.5rem; font-weight: bold; color: #333;">{{ number_format($stats['valeur_stock'], 2) }} DH</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #9C27B0, #7B1FA2);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">CA TOTAL</div>
                <div style="font-size: 1.5rem; font-weight: bold; color: #333;">{{ number_format($stats['ca_total'], 2) }} DH</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon" style="background: linear-gradient(135deg, #00BCD4, #0097A7);">
                    <i class="fas fa-coins"></i>
                </div>
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 8px;">MARGE TOTALE</div>
                <div style="font-size: 1.5rem; font-weight: bold; color: #333;">{{ number_format($stats['marge_totale'], 2) }} DH</div>
            </div>
        </div>

        <!-- Graphique: Évolution des ventes -->
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-chart-area"></i> Évolution des Ventes (6 derniers mois)</div>
            <canvas id="ventesChart" height="80"></canvas>
        </div>

        <!-- Graphique: Top 10 Produits -->
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-trophy"></i> Top 10 Produits les Plus Vendus</div>
            <canvas id="topProduitsChart" height="80"></canvas>
        </div>

        <!-- Table des produits -->
        <div class="chart-card">
            <div class="chart-title"><i class="fas fa-table"></i> Détails des Produits</div>
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Référence</th>
                        <th>Quantité Vendue</th>
                        <th>CA</th>
                        <th>Marge</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProduits as $produit)
                        <tr>
                            <td><strong>{{ $produit->nom }}</strong></td>
                            <td>{{ $produit->reference }}</td>
                            <td>{{ number_format($produit->quantite_vendue) }}</td>
                            <td>{{ number_format($produit->ca_total, 2) }} DH</td>
                            <td style="color: #4CAF50; font-weight: bold;">{{ number_format($produit->marge_totale, 2) }} DH</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: #999;">
                                <i class="fas fa-inbox" style="font-size: 3rem; display: block; margin-bottom: 15px;"></i>
                                Aucune vente enregistrée
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bouton Retour -->
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('categories.index') }}" class="btn" style="
                background: linear-gradient(135deg, #C2185B, #D32F2F);
                color: white;
                padding: 12px 30px;
                border-radius: 12px;
                text-decoration: none;
                font-weight: 600;
            ">
                <i class="fas fa-arrow-left"></i> Retour aux Catégories
            </a>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique Évolution des Ventes
        const ventesData = {!! json_encode($ventesParMois) !!};
        const ventesLabels = ventesData.map(v => v.mois);
        const ventesCA = ventesData.map(v => parseFloat(v.ca));
        const ventesMarges = ventesData.map(v => parseFloat(v.marge));

        new Chart(document.getElementById('ventesChart'), {
            type: 'line',
            data: {
                labels: ventesLabels,
                datasets: [
                    {
                        label: 'Chiffre d\'Affaires (DH)',
                        data: ventesCA,
                        borderColor: '#C2185B',
                        backgroundColor: 'rgba(194, 24, 91, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Marge (DH)',
                        data: ventesMarges,
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true, position: 'top' }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Graphique Top Produits
        const topData = {!! json_encode($topProduits) !!};
        const topLabels = topData.map(p => p.nom);
        const topValues = topData.map(p => parseFloat(p.quantite_vendue));

        new Chart(document.getElementById('topProduitsChart'), {
            type: 'bar',
            data: {
                labels: topLabels,
                datasets: [{
                    label: 'Quantité Vendue',
                    data: topValues,
                    backgroundColor: 'rgba(194, 24, 91, 0.7)',
                    borderColor: '#C2185B',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });
    </script>
</x-app-layout>