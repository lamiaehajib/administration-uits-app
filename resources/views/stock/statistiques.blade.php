<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-red: #D32F2F;
            --primary-pink: #C2185B;
            --gradient: linear-gradient(135deg, #C2185B, #D32F2F);
            --gradient-reverse: linear-gradient(135deg, #D32F2F, #C2185B);
        }

        body {
            background: linear-gradient(to bottom, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Ubuntu', sans-serif;
            min-height: 100vh;
        }

        .page-header {
            background: var(--gradient);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(211, 47, 47, 0.3);
            border-radius: 0 0 30px 30px;
        }

        .page-header h1 {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient);
            transform: scaleX(0);
            transition: transform 0.4s ease;
        }

        .stats-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(211, 47, 47, 0.2);
        }

        .stats-card:hover::before {
            transform: scaleX(1);
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
            position: relative;
            overflow: hidden;
        }

        .stats-icon::before {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--gradient);
            opacity: 0.1;
            transition: opacity 0.3s;
        }

        .stats-card:hover .stats-icon::before {
            opacity: 0.2;
        }

        .stats-icon-1 { background: linear-gradient(135deg, rgba(211, 47, 47, 0.1), rgba(194, 24, 91, 0.1)); color: var(--primary-red); }
        .stats-icon-2 { background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1)); color: var(--primary-pink); }
        .stats-icon-3 { background: linear-gradient(135deg, rgba(211, 47, 47, 0.15), rgba(194, 24, 91, 0.15)); color: var(--primary-red); }
        .stats-icon-4 { background: linear-gradient(135deg, rgba(194, 24, 91, 0.15), rgba(211, 47, 47, 0.15)); color: var(--primary-pink); }

        .stats-value {
            font-size: 2.5rem;
            font-weight: bold;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 0.5rem 0;
        }

        .stats-label {
            color: #6c757d;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .section-title {
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 3rem 0 1.5rem;
            font-size: 1.8rem;
            position: relative;
            padding-bottom: 1rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--gradient);
            border-radius: 2px;
        }

        .alert-table {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .alert-table table {
            margin: 0;
        }

        .alert-table thead {
            background: var(--gradient);
            color: white;
        }

        .alert-table thead th {
            padding: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            font-size: 0.9rem;
        }

        .alert-table tbody td {
            padding: 1rem 1.2rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .alert-table tbody tr {
            transition: all 0.3s ease;
        }

        .alert-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(211, 47, 47, 0.03), rgba(194, 24, 91, 0.03));
            transform: scale(1.01);
        }

        .badge-danger {
            background: var(--gradient);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .badge-warning {
            background: linear-gradient(135deg, #ff9800, #ff5722);
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .movement-card {
            background: white;
            border-radius: 15px;
            padding: 1.2rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .movement-card:hover {
            box-shadow: 0 8px 25px rgba(211, 47, 47, 0.15);
            transform: translateX(5px);
            border-left-color: var(--primary-red);
        }

        .movement-type {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .movement-type-entree {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.2), rgba(76, 175, 80, 0.1));
            color: #2e7d32;
        }

        .movement-type-sortie {
            background: linear-gradient(135deg, rgba(211, 47, 47, 0.2), rgba(194, 24, 91, 0.1));
            color: var(--primary-red);
        }

        .movement-type-ajustement {
            background: linear-gradient(135deg, rgba(255, 152, 0, 0.2), rgba(255, 152, 0, 0.1));
            color: #f57c00;
        }

        .product-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 1.05rem;
        }

        .movement-date {
            color: #95a5a6;
            font-size: 0.9rem;
        }

        .stock-info {
            background: linear-gradient(135deg, rgba(211, 47, 47, 0.05), rgba(194, 24, 91, 0.05));
            padding: 0.8rem;
            border-radius: 10px;
            display: inline-block;
        }

        .stock-arrow {
            color: var(--primary-red);
            margin: 0 0.5rem;
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #95a5a6;
        }

        .empty-state i {
            font-size: 5rem;
            margin-bottom: 1.5rem;
            opacity: 0.3;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: fadeInUp 0.6s ease forwards;
        }

        .animate-in:nth-child(1) { animation-delay: 0.1s; }
        .animate-in:nth-child(2) { animation-delay: 0.2s; }
        .animate-in:nth-child(3) { animation-delay: 0.3s; }
        .animate-in:nth-child(4) { animation-delay: 0.4s; }

        @media (max-width: 768px) {
            .stats-value {
                font-size: 2rem;
            }
            
            .section-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <x-app-layout>
        <div class="page-header">
            <div class="container">
                <h1><i class="fas fa-chart-line me-3"></i>Statistiques de Stock</h1>
            </div>
        </div>

        <div class="container">
            <!-- Statistiques principales -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="stats-card">
                        <div class="stats-icon stats-icon-1">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="stats-value">{{ number_format($stats['valeur_stock_total'], 2) }} DH</div>
                        <div class="stats-label">Valeur Stock Total</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="stats-card">
                        <div class="stats-icon stats-icon-2">
                            <i class="fas fa-cubes"></i>
                        </div>
                        <div class="stats-value">{{ $stats['produits_en_stock'] }}</div>
                        <div class="stats-label">Produits en Stock</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="stats-card">
                        <div class="stats-icon stats-icon-3">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stats-value">{{ $stats['produits_rupture'] }}</div>
                        <div class="stats-label">Produits en Alerte</div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 animate-in">
                    <div class="stats-card">
                        <div class="stats-icon stats-icon-4">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="stats-value">{{ $stats['produits_inactifs'] }}</div>
                        <div class="stats-label">Produits Inactifs</div>
                    </div>
                </div>
            </div>

            <!-- Alertes de stock -->
            <h2 class="section-title">
                <i class="fas fa-bell me-2"></i>Alertes de Stock
            </h2>

            @if($alertes->count() > 0)
                <div class="alert-table mb-5">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Référence</th>
                                <th>Stock Actuel</th>
                                <th>Stock d'Alerte</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alertes as $produit)
                                <tr>
                                    <td class="product-name">
                                        <i class="fas fa-box me-2"></i>{{ $produit->nom }}
                                    </td>
                                    <td><code>{{ $produit->reference ?? 'N/A' }}</code></td>
                                    <td>
                                        <strong style="color: {{ $produit->quantite_stock == 0 ? 'var(--primary-red)' : '#f57c00' }}">
                                            {{ $produit->quantite_stock }}
                                        </strong>
                                    </td>
                                    <td>{{ $produit->stock_alerte }}</td>
                                    <td>
                                        @if($produit->quantite_stock == 0)
                                            <span class="badge badge-danger">
                                                <i class="fas fa-times-circle me-1"></i>Rupture
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-exclamation-circle me-1"></i>Critique
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert-table">
                    <div class="empty-state">
                        <i class="fas fa-check-circle"></i>
                        <h4>Aucune alerte de stock</h4>
                        <p>Tous les produits ont un stock suffisant</p>
                    </div>
                </div>
            @endif

            <!-- Derniers mouvements -->
            <h2 class="section-title">
                <i class="fas fa-history me-2"></i>Derniers Mouvements
            </h2>

            @if($mouvements_recents->count() > 0)
                <div class="row">
                    <div class="col-12">
                        @foreach($mouvements_recents as $mouvement)
                            <div class="movement-card">
                                <div class="row align-items-center">
                                    <div class="col-md-4">
                                        <div class="product-name">
                                            <i class="fas fa-cube me-2"></i>{{ $mouvement->produit->nom }}
                                        </div>
                                        <div class="movement-date">
                                            <i class="far fa-clock me-1"></i>{{ $mouvement->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="movement-type movement-type-{{ $mouvement->type }}">
                                            @if($mouvement->type == 'entree')
                                                <i class="fas fa-plus-circle me-1"></i>Entrée
                                            @elseif($mouvement->type == 'sortie')
                                                <i class="fas fa-minus-circle me-1"></i>Sortie
                                            @else
                                                <i class="fas fa-sync-alt me-1"></i>Ajustement
                                            @endif
                                        </span>
                                    </div>
                                    <div class="col-md-2 text-center">
                                        <strong style="font-size: 1.3rem; color: var(--primary-red)">
                                            {{ $mouvement->type == 'entree' ? '+' : '-' }}{{ $mouvement->quantite }}
                                        </strong>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <div class="stock-info">
                                            <span class="badge bg-secondary">{{ $mouvement->stock_avant }}</span>
                                            <span class="stock-arrow"><i class="fas fa-long-arrow-alt-right"></i></span>
                                            <span class="badge" style="background: var(--gradient)">{{ $mouvement->stock_apres }}</span>
                                        </div>
                                    </div>
                                </div>
                                @if($mouvement->motif)
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-muted">
                                                <i class="fas fa-comment-alt me-1"></i>
                                                <strong>Motif:</strong> {{ $mouvement->motif }}
                                            </small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert-table">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h4>Aucun mouvement récent</h4>
                        <p>Les mouvements de stock apparaîtront ici</p>
                    </div>
                </div>
            @endif
        </div>
    </x-app-layout>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>