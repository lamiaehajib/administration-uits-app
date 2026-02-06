<x-app-layout>
    <style>
        /* ===== PAGE DÉTAILS PRODUIT - DESIGN MODERNE ===== */
        
        /* Header Product */
        .product-detail-header {
            background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);
            padding: 50px 40px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 15px 40px rgba(211, 47, 47, 0.3);
            position: relative;
            overflow: hidden;
        }

        .product-detail-header::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .product-detail-header::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .header-content {
            position: relative;
            z-index: 1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .product-main-info {
            flex: 1;
            min-width: 300px;
        }

        .product-avatar-large {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            border: 4px solid rgba(255, 255, 255, 0.3);
        }

        .product-title {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .product-reference {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .product-category-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 25px;
            color: white;
            font-weight: 600;
            display: inline-block;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .header-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-action-header {
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            font-size: 1rem;
        }

        .btn-white {
            background: white;
            color: #C2185B;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            color: #C2185B;
        }

        .btn-outline-white {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-outline-white:hover {
            background: white;
            color: #C2185B;
            transform: translateY(-3px);
        }

        /* Stats Overview Cards */
        .stats-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-overview-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-overview-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: currentColor;
        }

        .stat-overview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-overview-card.blue { color: #2196F3; }
        .stat-overview-card.green { color: #4CAF50; }
        .stat-overview-card.orange { color: #FF9800; }
        .stat-overview-card.purple { color: #9C27B0; }
        .stat-overview-card.red { color: #f44336; }

        .stat-overview-icon {
            font-size: 2.5rem;
            opacity: 0.2;
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .stat-overview-value {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin: 10px 0 5px 0;
        }

        .stat-overview-label {
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        /* Info Card */
        .info-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #f0f0f0;
        }

        .info-card-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .info-card-icon.primary { background: linear-gradient(135deg, #C2185B, #D32F2F); }
        .info-card-icon.success { background: linear-gradient(135deg, #66BB6A, #4CAF50); }
        .info-card-icon.warning { background: linear-gradient(135deg, #FFA726, #FF9800); }

        .info-card-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            font-size: 0.95rem;
        }

        .info-value {
            font-weight: 700;
            color: #333;
            font-size: 1rem;
            text-align: right;
        }

        .info-value.success { color: #4CAF50; }
        .info-value.danger { color: #f44336; }
        .info-value.primary { color: #C2185B; }

        /* Price Display */
        .price-display {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            padding: 20px;
            border-radius: 12px;
            margin: 15px 0;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }

        .price-label {
            font-size: 0.95rem;
            color: #666;
            font-weight: 600;
        }

        .price-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        /* Recent Activity Cards */
        .activity-section {
            margin-top: 30px;
        }

        .activity-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 25px;
        }

        .activity-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .activity-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            padding: 20px;
            color: white;
        }

        .activity-header h4 {
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .activity-body {
            padding: 20px;
        }

        .activity-item {
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .activity-item:last-child {
            margin-bottom: 0;
        }

        .activity-date {
            font-size: 0.85rem;
            color: #999;
            margin-bottom: 8px;
        }

        .activity-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .activity-quantity {
            font-weight: 700;
            color: #333;
            font-size: 1.1rem;
        }

        .activity-price {
            font-weight: 700;
            color: #4CAF50;
            font-size: 1.1rem;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.3;
        }

        /* Status Badge */
        .status-badge-large {
            padding: 10px 24px;
            border-radius: 25px;
            font-weight: 700;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-badge-large.active {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .status-badge-large.inactive {
            background: #FFEBEE;
            color: #C62828;
        }

        /* Stock Alert */
        .stock-alert-box {
            background: #FFF3E0;
            border-left: 5px solid #FF9800;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .stock-alert-box.danger {
            background: #FFEBEE;
            border-left-color: #f44336;
        }

        .stock-alert-box i {
            font-size: 1.5rem;
            color: #FF9800;
            margin-right: 10px;
        }

        .stock-alert-box.danger i {
            color: #f44336;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 1.8rem;
            }

            .stats-overview {
                grid-template-columns: repeat(2, 1fr);
            }

            .activity-grid {
                grid-template-columns: 1fr;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .product-avatar-large {
                margin: 0 auto 20px;
            }
        }

        @media (max-width: 576px) {
            .stats-overview {
                grid-template-columns: 1fr;
            }

            .product-detail-header {
                padding: 30px 20px;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Product Header -->
        <div class="product-detail-header">
            <div class="header-content">
                <div class="product-main-info">
                    <div class="product-avatar-large">
                        {{ strtoupper(substr($produit->nom, 0, 2)) }}
                    </div>
                    <h1 class="product-title">{{ $produit->nom }}</h1>
                    <p class="product-reference">
                        <i class="fas fa-barcode"></i> {{ $produit->reference }}
                    </p>
                    <span class="product-category-badge">
                        <i class="fas fa-tag"></i> {{ $produit->category->nom ?? 'N/A' }}
                    </span>
                </div>

                <div class="header-actions">
                    @can('produit-edit')
                    <a href="{{ route('produits.edit', $produit->id) }}" class="btn-action-header btn-white">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    @endcan
                    <a href="{{ route('produits.index') }}" class="btn-action-header btn-outline-white">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="stats-overview">
            <div class="stat-overview-card blue">
                <i class="fas fa-shopping-cart stat-overview-icon"></i>
                <div class="stat-overview-label">Total Ventes</div>
                <div class="stat-overview-value">{{ number_format($stats['total_ventes']) }}</div>
            </div>

            <div class="stat-overview-card green">
                <i class="fas fa-money-bill-wave stat-overview-icon"></i>
                <div class="stat-overview-label">Chiffre d'Affaires</div>
                <div class="stat-overview-value">{{ number_format($stats['ca_total'], 0) }} DH</div>
            </div>

            @can('produit-rapport')
            <div class="stat-overview-card purple">
                <i class="fas fa-chart-line stat-overview-icon"></i>
                <div class="stat-overview-label">Marge Totale</div>
                <div class="stat-overview-value">{{ number_format($stats['marge_totale'], 0) }} DH</div>
            </div>
            @endcan

            <div class="stat-overview-card orange">
                <i class="fas fa-boxes stat-overview-icon"></i>
                <div class="stat-overview-label">Stock Actuel</div>
                <div class="stat-overview-value">{{ number_format($produit->quantite_stock) }}</div>
            </div>

            @can('produit-rapport')
            <div class="stat-overview-card red">
                <i class="fas fa-warehouse stat-overview-icon"></i>
                <div class="stat-overview-label">Valeur Stock</div>
                <div class="stat-overview-value">{{ number_format($stats['valeur_stock'], 0) }} DH</div>
            </div>
            @endcan
        </div>

        <!-- Stock Alert -->
        @if($produit->quantite_stock <= $produit->stock_alerte)
            <div class="stock-alert-box {{ $produit->quantite_stock == 0 ? 'danger' : '' }}">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>
                    @if($produit->quantite_stock == 0)
                        Rupture de stock ! Le produit n'est plus disponible.
                    @else
                        Alerte stock bas ! Quantité actuelle: {{ $produit->quantite_stock }} (Seuil: {{ $produit->stock_alerte }})
                    @endif
                </strong>
            </div>
        @endif

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Left Column: Product Info -->
            <div>
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon primary">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h3 class="info-card-title">Informations Produit</h3>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Référence</span>
                        <span class="info-value">{{ $produit->reference }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Catégorie</span>
                        <span class="info-value">{{ $produit->category->nom ?? 'N/A' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Description</span>
                        <span class="info-value">{!! nl2br(e( $produit->description)) !!}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Statut</span>
                        <span class="info-value">
                            @if($produit->actif)
                                <span class="status-badge-large active">
                                    <i class="fas fa-check-circle"></i> Actif
                                </span>
                            @else
                                <span class="status-badge-large inactive">
                                    <i class="fas fa-times-circle"></i> Inactif
                                </span>
                            @endif
                        </span>
                    </div>

                    <div class="price-display">
                        @can('produit-rapport')
                        <div class="price-row">
                            <span class="price-label"><i class="fas fa-tag"></i> Prix d'Achat</span>
                            <span class="price-value" style="color: #f44336;">
                                {{ number_format($produit->prix_achat, 2) }} DH
                            </span>
                        </div>
                        @endcan
                        
                        <div class="price-row">
                            <span class="price-label"><i class="fas fa-dollar-sign"></i> Prix de Vente</span>
                            <span class="price-value" style="color: #4CAF50;">
                                {{ number_format($produit->prix_vente, 2) }} DH
                            </span>
                        </div>
                        
                        @can('produit-rapport')
                        <div class="price-row" style="border-top: 2px dashed #ccc; padding-top: 15px; margin-top: 10px;">
                            <span class="price-label"><i class="fas fa-percentage"></i> Marge Unitaire</span>
                            <span class="price-value" style="color: #9C27B0;">
                                {{ number_format($produit->prix_vente - $produit->prix_achat, 2) }} DH
                                ({{ number_format((($produit->prix_vente - $produit->prix_achat) / $produit->prix_achat) * 100, 1) }}%)
                            </span>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Right Column: Stock Info -->
            <div>
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon success">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <h3 class="info-card-title">Stock & Inventaire</h3>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Quantité en Stock</span>
                        <span class="info-value primary">{{ number_format($produit->quantite_stock) }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Seuil d'Alerte</span>
                        <span class="info-value">{{ number_format($produit->stock_alerte) }}</span>
                    </div>

                    @can('produit-rapport')
                    <div class="info-row">
                        <span class="info-label">Total Achats</span>
                        <span class="info-value">{{ number_format($stats['total_achats']) }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Valeur Stock</span>
                        <span class="info-value success">{{ number_format($stats['valeur_stock'], 2) }} DH</span>
                    </div>
                    @endcan
                </div>

                <div class="info-card" style="margin-top: 25px;">
                    <div class="info-card-header">
                        <div class="info-card-icon warning">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <h3 class="info-card-title">Performance</h3>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Unités Vendues</span>
                        <span class="info-value primary">{{ number_format($stats['total_ventes']) }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">CA Total</span>
                        <span class="info-value success">{{ number_format($stats['ca_total'], 2) }} DH</span>
                    </div>

                    @can('produit-rapport')
                    <div class="info-row">
                        <span class="info-label">Marge Totale</span>
                        <span class="info-value success">{{ number_format($stats['marge_totale'], 2) }} DH</span>
                    </div>
                    @endcan
                </div>
            </div>
        </div>
        


        <div class="info-card" style="margin-top: 25px;" id="stock-fifo">
    <div class="info-card-header">
        <div class="info-card-icon warning">
            <i class="fas fa-layer-group"></i>
        </div>
        <h3 class="info-card-title">Stock FIFO - Prix par Batch</h3>
    </div>
    <div class="activity-body">
        @php
            $achatsAvecStock = \App\Models\Achat::where('produit_id', $produit->id)
                ->where('quantite_restante', '>', 0)
                ->orderBy('date_achat', 'asc')
                ->get();
        @endphp

        <div class="table-responsive">
            <table class="table table-hover">
                <thead style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                    <tr>
                        <th>Date Achat</th>
                        <th>Stock Restant</th>
                        <th>Prix Achat</th>
                        <th>Prix Vente</th>
                        <th>Marge Unit.</th>
                        <th>Valeur Stock</th>
                        <th>CA Potentiel</th>
                        <th>Utilisation</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($achatsAvecStock as $achat)
                    @php
                        $prixVente = $achat->prix_vente_suggere ?? $produit->prix_vente;
                        $margeUnit = $prixVente - $achat->prix_achat;
                        $margePct = $achat->prix_achat > 0 ? ($margeUnit / $achat->prix_achat * 100) : 0;
                        $valeurStock = $achat->quantite_restante * $achat->prix_achat;
                        $caPotentiel = $achat->quantite_restante * $prixVente;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ \Carbon\Carbon::parse($achat->date_achat)->format('d/m/Y') }}</strong>
                            <br>
                            <small class="text-muted">{{ $achat->fournisseur ?? 'N/A' }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background: #E3F2FD; color: #1976D2; font-size: 1rem;">
                                {{ $achat->quantite_restante }} / {{ $achat->quantite }}
                            </span>
                        </td>
                        <td>
                            <strong style="color: #f44336;">
                                {{ number_format($achat->prix_achat, 2) }} DH
                            </strong>
                        </td>
                        <td>
                            <strong style="color: #4CAF50; font-size: 1.1rem;">
                                {{ number_format($prixVente, 2) }} DH
                            </strong>
                        </td>
                        <td>
                            <div>
                                <strong style="color: {{ $margeUnit >= 0 ? '#9C27B0' : '#f44336' }};">
                                    {{ number_format($margeUnit, 2) }} DH
                                </strong>
                            </div>
                            <small class="badge" style="background: {{ $margePct >= 15 ? '#E8F5E9' : '#FFEBEE' }}; color: {{ $margePct >= 15 ? '#2E7D32' : '#C62828' }};">
                                {{ number_format($margePct, 1) }}%
                            </small>
                        </td>
                        <td>
                            <strong style="color: #666;">
                                {{ number_format($valeurStock, 2) }} DH
                            </strong>
                        </td>
                        <td>
                            <strong style="color: #4CAF50; font-size: 1.1rem;">
                                {{ number_format($caPotentiel, 2) }} DH
                            </strong>
                            <br>
                            <small class="text-muted">
                                Marge: {{ number_format($caPotentiel - $valeurStock, 2) }} DH
                            </small>
                        </td>
                        <td>
                            <div style="width: 100px;">
                                <div style="font-size: 0.85rem; font-weight: 600;">
                                    {{ $achat->taux_utilisation }}%
                                </div>
                                <div class="stock-progress">
                                    <div class="stock-progress-bar {{ $achat->taux_utilisation >= 80 ? 'danger' : 'success' }}" 
                                         style="width: {{ $achat->taux_utilisation }}%;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Aucun stock disponible</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

        <!-- Recent Activity -->
        <div class="activity-section">
            <div class="activity-grid">
                <!-- Recent Purchases -->
                @can('achat-list')
                <div class="activity-card">
                    <div class="activity-header">
                        <h4><i class="fas fa-shopping-bag"></i> Derniers Achats</h4>
                    </div>
                    <div class="activity-body">
                        @forelse($derniersAchats as $achat)
                            <div class="activity-item">
                                <div class="activity-date">
                                    <i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($achat->date_achat)->format('d/m/Y') }}
                                </div>
                                <div class="activity-details">
                                    <div>
                                        <span class="activity-quantity">{{ number_format($achat->quantite) }} unités</span>
                                        <br>
                                        <small class="text-muted">Fournisseur: {{ $achat->fournisseur->nom ?? 'N/A' }}</small>
                                    </div>
                                    @can('produit-rapport')
                                    <div class="activity-price">
                                        {{ number_format($achat->total_achat, 2) }} DH
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Aucun achat enregistré</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                @endcan

                <!-- Recent Sales -->
                <div class="activity-card">
                    <div class="activity-header">
                        <h4><i class="fas fa-cash-register"></i> Dernières Ventes</h4>
                    </div>
                    <div class="activity-body">
                        @forelse($dernieresVentes as $vente)
                            <div class="activity-item">
                                <div class="activity-date">
                                    <i class="fas fa-calendar"></i> {{ $vente->created_at->format('d/m/Y H:i') }}
                                </div>
                                <div class="activity-details">
                                    <div>
                                        <span class="activity-quantity">{{ number_format($vente->quantite) }} unités</span>
                                        <br>
                                        <small class="text-muted">Reçu: {{ $vente->recuUcg->numero_recu ?? 'N/A' }}</small>
                                    </div>
                                    <div>
                                        <div class="activity-price">{{ number_format($vente->sous_total, 2) }} DH</div>
                                        @can('produit-rapport')
                                        <small class="text-muted">Marge: {{ number_format($vente->marge_totale, 2) }} DH</small>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>Aucune vente enregistrée</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>