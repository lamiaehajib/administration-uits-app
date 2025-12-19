<x-app-layout>
    <style>
        /* ===== DESIGN MODERNE ===== */
        .products-header {
            background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.3);
            position: relative;
            overflow: hidden;
        }

        .products-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .products-header h1 {
            color: white;
            font-weight: 700;
            font-size: 2.5rem;
            margin: 0 0 10px 0;
            position: relative;
            z-index: 1;
        }

        .products-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid;
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
            background: currentColor;
            opacity: 0.05;
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .stat-card.primary { border-color: #C2185B; color: #C2185B; }
        .stat-card.success { border-color: #4CAF50; color: #4CAF50; }
        .stat-card.warning { border-color: #FF9800; color: #FF9800; }
        .stat-card.info { border-color: #2196F3; color: #2196F3; }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            opacity: 0.8;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0 5px 0;
            color: #333;
        }

        .stat-label {
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Action Bar */
        .action-bar {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            outline: none;
            border-color: #C2185B;
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        }

        .search-box i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .btn-modern {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary-modern {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .btn-primary-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
            color: white;
        }

        .btn-outline-modern {
            background: white;
            color: #C2185B;
            border: 2px solid #C2185B;
        }

        .btn-outline-modern:hover {
            background: #C2185B;
            color: white;
            transform: translateY(-2px);
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .table-responsive {
            border-radius: 15px;
        }

        .modern-table {
            margin: 0;
            width: 100%;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .modern-table thead th {
            color: white;
            font-weight: 600;
            padding: 18px 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            color: #333;
        }

        /* Product Info */
        .product-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .product-avatar {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .product-details h6 {
            margin: 0 0 4px 0;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .product-details small {
            color: #999;
            font-size: 0.85rem;
        }

        /* Badges */
        .badge-modern {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
        }

        .badge-success-modern {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .badge-danger-modern {
            background: #FFEBEE;
            color: #C62828;
        }

        .badge-warning-modern {
            background: #FFF3E0;
            color: #EF6C00;
        }

        /* Stock Progress */
        .stock-progress {
            width: 100%;
            height: 6px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 5px;
        }

        .stock-progress-bar {
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .stock-progress-bar.success { background: #4CAF50; }
        .stock-progress-bar.warning { background: #FF9800; }
        .stock-progress-bar.danger { background: #f44336; }

        /* Action Buttons */
        .action-btns {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-view { background: #E3F2FD; color: #1976D2; }
        .btn-view:hover { background: #1976D2; color: white; }

        .btn-edit { background: #FFF3E0; color: #F57C00; }
        .btn-edit:hover { background: #F57C00; color: white; }

        .btn-delete { background: #FFEBEE; color: #C62828; }
        .btn-delete:hover { background: #C62828; color: white; }

        .btn-stock { 
            background: #F3E5F5; 
            color: #7B1FA2; 
        }
        .btn-stock:hover { 
            background: #7B1FA2; 
            color: white; 
        }

        /* Pagination */
        .pagination-modern {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .pagination-modern .page-link {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 8px 16px;
            color: #666;
            transition: all 0.3s ease;
        }

        .pagination-modern .page-link:hover {
            border-color: #C2185B;
            background: #C2185B;
            color: white;
        }

        .pagination-modern .active .page-link {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: #C2185B;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .products-header h1 { font-size: 1.8rem; }
            .stats-grid { grid-template-columns: 1fr; }
            .action-bar { flex-direction: column; }
            .search-box { width: 100%; }
        }
        .btn-variant { 
            background: #E8EAF6; 
            color: #3F51B5; 
        }
        .btn-variant:hover { 
            background: #3F51B5; 
            color: white; 
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex !important;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            width: 90%;
            max-width: 1000px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 25px 30px;
            border-radius: 20px 20px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 30px;
        }

        .tab-btn {
            background: transparent;
            border: none;
            padding: 12px 24px;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .tab-btn.active {
            color: #C2185B;
            border-bottom-color: #C2185B;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .variant-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            border: 2px solid #e0e0e0;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .variant-card:hover {
            border-color: #C2185B;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(194, 24, 91, 0.2);
        }

        .variant-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .variant-name {
            font-weight: 700;
            font-size: 1.1rem;
            color: #333;
        }

        .variant-specs {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }

        .spec-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }

        .spec-item i {
            color: #C2185B;
        }

        .variant-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #e0e0e0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #C2185B;
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .badge-purple-modern {
            background: #F3E5F5;
            color: #7B1FA2;
        }

        /* Ajout colonne Variants dans le tableau */
        .modern-table thead th {
            white-space: nowrap;
        }
 .pagination {
            margin-top: 20px;
        }

        .page-link {
            border: 1px solid #e5e7eb;
            color: #C2185B;
            border-radius: 8px;
            margin: 0 3px;
            padding: 8px 15px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: #C2185B;
            transform: translateY(-2px);
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: #C2185B;
        }

        
    </style>

    <div class="container-fluid">
        <!-- Header -->
        <div class="products-header">
            <h1><i class="fas fa-boxes"></i> Gestion des Produits</h1>
            <p>Vue d'ensemble complète de votre catalogue produits</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card primary">
                <div class="stat-icon"><i class="fas fa-box"></i></div>
                <div class="stat-value">{{ $produits->total() }}</div>
                <div class="stat-label">Produits Total</div>
            </div>
            
            <div class="stat-card success">
                <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="stat-value">{{ $produits->where('actif', true)->count() }}</div>
                <div class="stat-label">Produits Actifs</div>
            </div>
            
            <div class="stat-card warning">
                <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-value">{{ $produits->filter(function($p) { return $p->quantite_stock <= $p->stock_alerte; })->count() }}</div>
                <div class="stat-label">Alertes Stock</div>
            </div>
            
            @can('produit-rapport')
            <div class="stat-card info">
                <div class="stat-icon"><i class="fas fa-coins"></i></div>
                <div class="stat-value">{{ number_format($produits->sum('marge_totale'), 0) }} DH</div>
                <div class="stat-label">Marge Totale</div>
            </div>
            @endcan
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <form action="{{ route('produits.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="🔍 Rechercher un produit..." 
                       value="{{ request('search') }}">
                <i class="fas fa-search"></i>
            </form>

            @can('produit-create')
            <a href="{{ route('produits.create') }}" class="btn-modern btn-primary-modern">
                <i class="fas fa-plus-circle"></i> Nouveau Produit
            </a>
            @endcan

            @can('produit-rapport')
            <a href="{{ route('produits.rapport') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-chart-line"></i> Rapport
            </a>
            @endcan

            @can('produit-export')
            <a href="{{ route('produits.export_pdf') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            @endcan
            @can('produit-export')
            <a href="{{ route('produits.trash') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-trash"></i> Corbeille
            </a>
            @endcan
        </div>

        <!-- Products Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="modern-table table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Catégorie</th>
                            <th>Prix Achat</th>
                            <th>Prix Vente</th>
                            <th>Stock</th>
                            <th>Variants</th>
                            <th>Ventes</th>
                            @can('produit-rapport')
                            <th>Marge</th>
                            @endcan
                            <th>Statut</th>
                            <th style="min-width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produits as $produit)
                        <tr>
                            <td>
                                <div class="product-info">
                                    <div class="product-avatar">
                                        {{ strtoupper(substr($produit->nom, 0, 2)) }}
                                    </div>
                                    <div class="product-details">
                                        <h6>{{ $produit->nom }}</h6>
                                        <small>{{ $produit->reference }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-modern badge-warning-modern">
                                    {{ $produit->categorie_nom }}
                                </span>
                            </td>
                            <td><strong>{{ number_format($produit->dernier_prix_achat, 2) }} DH</strong></td>
                            <td><strong class="text-success">{{ number_format($produit->prix_vente, 2) }} DH</strong></td>
                            <td>
                                <div>
                                    <strong>{{ $produit->quantite_stock }}</strong> unités
                                </div>
                                @php
                                    $stockPercent = $produit->stock_alerte > 0 
                                        ? ($produit->quantite_stock / $produit->stock_alerte) * 100 
                                        : 100;
                                    $stockClass = $stockPercent > 50 ? 'success' : ($stockPercent > 20 ? 'warning' : 'danger');
                                @endphp
                                <div class="stock-progress">
                                    <div class="stock-progress-bar {{ $stockClass }}" 
                                         style="width: {{ min($stockPercent, 100) }}%"></div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-modern badge-purple-modern">
                                    <i class="fas fa-layer-group"></i> {{ $produit->variants->count() ?? 0 }}
                                </span>
                            </td>
                            <td>
                                <div><strong>{{ $produit->quantite_vendue ?? 0 }}</strong> unités</div>
                                <small class="text-muted">{{ number_format($produit->total_vendu_montant ?? 0, 0) }} DH</small>
                            </td>
                            @can('produit-rapport')
                            <td>
                                <div class="text-success">
                                    <strong>{{ number_format($produit->marge_totale ?? 0, 0) }} DH</strong>
                                </div>
                                <small class="text-muted">({{ number_format($produit->marge_pourcentage ?? 0, 1) }}%)</small>
                            </td>
                            @endcan
                            <td>
                                @if($produit->actif)
                                    <span class="badge-modern badge-success-modern">
                                        <i class="fas fa-check"></i> Actif
                                    </span>
                                @else
                                    <span class="badge-modern badge-danger-modern">
                                        <i class="fas fa-times"></i> Inactif
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-btns" style="flex-wrap: wrap;">
                                    <a href="{{ route('produits.show', $produit->id) }}" 
                                       class="btn-icon btn-view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @can('produit-edit')
                                    <a href="{{ route('produits.edit', $produit->id) }}" 
                                       class="btn-icon btn-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endcan
                                    @can('produit-edit')
                                    <button onclick="openVariantsModal({{ $produit->id }}, '{{ addslashes($produit->nom) }}')" 
                                            class="btn-icon btn-variant" title="Gérer Variants">
                                        <i class="fas fa-layer-group"></i>
                                    </button>
                                    @endcan
                                    @can('produit-delete')
                                    <button onclick="confirmDelete({{ $produit->id }})" 
                                            class="btn-icon btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endcan
                                    @can('stock-view')
                                    <a href="{{ route('stock.movements.produit', $produit->id) }}" 
                                       class="btn-icon btn-stock" 
                                       title="Mouvements de stock">
                                        <i class="fas fa-exchange-alt"></i>
                                    </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="fas fa-box-open" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                                <h5 class="text-muted">Aucun produit trouvé</h5>
                                <p class="text-muted">Commencez par ajouter votre premier produit</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
             
            <div class="d-flex justify-content-between align-items-center p-3">
                
                <div>
                    {{ $produits->links('pagination.custom') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Variants -->
    <div id="variantsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-layer-group"></i> <span id="modalTitle">Variants du Produit</span></h3>
                <button class="modal-close" onclick="closeVariantsModal()">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Tabs Navigation -->
                <div style="display: flex; gap: 10px; margin-bottom: 25px; border-bottom: 2px solid #e0e0e0;">
                    <button class="tab-btn active" onclick="switchTab('list')" id="listTab">
                        <i class="fas fa-list"></i> Liste des Variants
                    </button>
                    <button class="tab-btn" onclick="switchTab('create')" id="createTab">
                        <i class="fas fa-plus"></i> Créer Variant
                    </button>
                </div>

                <!-- Tab Content: Liste -->
                <div id="listContent" class="tab-content active">
                    <div id="variantsListContainer">
                        <div class="text-center py-5">
                            <i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #C2185B;"></i>
                            <p class="mt-3">Chargement des variants...</p>
                        </div>
                    </div>
                </div>

                <!-- Tab Content: Créer -->
                <div id="createContent" class="tab-content">
                    <form id="createVariantForm" onsubmit="submitVariant(event)">
                        @csrf
                        <input type="hidden" id="produit_id" name="produit_id">
                        <input type="hidden" id="variant_id" name="variant_id">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-memory"></i> RAM</label>
                                <input type="text" class="form-control" name="ram" placeholder="Ex: 16GB">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-hdd"></i> SSD</label>
                                <input type="text" class="form-control" name="ssd" placeholder="Ex: 512GB">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-microchip"></i> CPU</label>
                                <input type="text" class="form-control" name="cpu" placeholder="Ex: Intel i7">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-desktop"></i> GPU</label>
                                <input type="text" class="form-control" name="gpu" placeholder="Ex: NVIDIA RTX 3060">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-tv"></i> Écran</label>
                                <input type="text" class="form-control" name="ecran" placeholder="Ex: 15.6'' FHD">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label><i class="fas fa-coins"></i> Prix Supplément (DH)</label>
                                <input type="number" step="0.01" class="form-control" name="prix_supplement" value="0" required>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-box"></i> Quantité Stock</label>
                                <input type="number" class="form-control" name="quantite_stock" value="0" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="actif" value="1" checked style="width: 20px; height: 20px;">
                                <span><i class="fas fa-check-circle"></i> Variant actif</span>
                            </label>
                        </div>

                        <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 25px;">
                            <button type="button" class="btn-modern btn-outline-modern" onclick="resetForm()">
                                <i class="fas fa-times"></i> Annuler
                            </button>
                            <button type="submit" class="btn-modern btn-primary-modern">
                                <i class="fas fa-save"></i> <span id="submitBtnText">Créer Variant</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation & Scripts -->
    <script>
        let currentProduitId = null;

        // Ouvrir le modal
        function openVariantsModal(produitId, produitNom) {
            currentProduitId = produitId;
            document.getElementById('modalTitle').textContent = `Variants - ${produitNom}`;
            document.getElementById('produit_id').value = produitId;
            document.getElementById('variantsModal').classList.add('show');
            
            // Charger la liste des variants
            loadVariants(produitId);
        }

        // Fermer le modal
        function closeVariantsModal() {
            document.getElementById('variantsModal').classList.remove('show');
            resetForm();
        }

        // Changer d'onglet
        function switchTab(tab) {
            // Reset tabs
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Activer le bon onglet
            if (tab === 'list') {
                document.getElementById('listTab').classList.add('active');
                document.getElementById('listContent').classList.add('active');
            } else {
                document.getElementById('createTab').classList.add('active');
                document.getElementById('createContent').classList.add('active');
            }
        }

        // Charger les variants
        async function loadVariants(produitId) {
            const container = document.getElementById('variantsListContainer');
            container.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: #C2185B;"></i></div>';
            
            try {
                const response = await fetch(`/api/variants/produit/${produitId}`);
                const data = await response.json();
                
                if (data.success && data.variants.length > 0) {
                    let html = '';
                    data.variants.forEach(variant => {
                        html += `
                            <div class="variant-card">
                                <div class="variant-header">
                                    <div class="variant-name">
                                        <i class="fas fa-tag"></i> ${variant.variant_name}
                                        <small style="color: #999; font-weight: normal;">(${variant.sku})</small>
                                    </div>
                                    <div>
                                        ${variant.is_alert_stock ? '<span class="badge-modern badge-danger-modern"><i class="fas fa-exclamation-triangle"></i> Stock Bas</span>' : ''}
                                    </div>
                                </div>
                                
                                <div class="variant-specs">
                                    ${variant.specs.ram ? `<div class="spec-item"><i class="fas fa-memory"></i> <strong>RAM:</strong> ${variant.specs.ram}</div>` : ''}
                                    ${variant.specs.ssd ? `<div class="spec-item"><i class="fas fa-hdd"></i> <strong>SSD:</strong> ${variant.specs.ssd}</div>` : ''}
                                    ${variant.specs.cpu ? `<div class="spec-item"><i class="fas fa-microchip"></i> <strong>CPU:</strong> ${variant.specs.cpu}</div>` : ''}
                                    ${variant.specs.gpu ? `<div class="spec-item"><i class="fas fa-desktop"></i> <strong>GPU:</strong> ${variant.specs.gpu}</div>` : ''}
                                    ${variant.specs.ecran ? `<div class="spec-item"><i class="fas fa-tv"></i> <strong>Écran:</strong> ${variant.specs.ecran}</div>` : ''}
                                </div>
                                
                                <div class="variant-footer">
                                    <div>
                                        <strong style="color: #4CAF50; font-size: 1.1rem;">${variant.prix_vente_final.toFixed(2)} DH</strong>
                                        <span style="color: #999; margin-left: 10px;">Stock: <strong>${variant.stock}</strong></span>
                                    </div>
                                    <div class="action-btns">
                                        <button onclick="editVariant(${variant.id})" class="btn-icon btn-edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteVariant(${variant.id}, '${variant.variant_name}')" class="btn-icon btn-delete" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-box-open" style="font-size: 3rem; color: #ccc;"></i>
                            <h5 class="text-muted mt-3">Aucun variant trouvé</h5>
                            <p class="text-muted">Créez votre premier variant pour ce produit</p>
                            <button onclick="switchTab('create')" class="btn-modern btn-primary-modern mt-3">
                                <i class="fas fa-plus"></i> Créer un variant
                            </button>
                        </div>
                    `;
                }
            } catch (error) {
                container.innerHTML = '<div class="alert alert-danger">Erreur de chargement</div>';
            }
        }

        // Soumettre le formulaire
        async function submitVariant(e) {
    e.preventDefault();
    const form = document.getElementById('createVariantForm');
    const formData = new FormData(form);
    const variantId = document.getElementById('variant_id').value;
    
    // ✅ Construire l'objet de données
    const data = {
        ram: formData.get('ram'),
        ssd: formData.get('ssd'),
        cpu: formData.get('cpu'),
        gpu: formData.get('gpu'),
        ecran: formData.get('ecran'),
        prix_supplement: formData.get('prix_supplement'),
        quantite_stock: formData.get('quantite_stock'),
        actif: formData.get('actif') ? 1 : 0
    };
    
    const url = variantId 
        ? `/produits/${currentProduitId}/variants/${variantId}`
        : `/produits/${currentProduitId}/variants`;
    
    const method = variantId ? 'PUT' : 'POST';
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Accept': 'application/json',
                'Content-Type': 'application/json', // ✅ Important pour PUT
            },
            body: JSON.stringify(data) // ✅ Envoyer en JSON au lieu de FormData
        });
        
        const result = await response.json();
        
        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: result.message || 'Variant enregistré avec succès',
                timer: 2000
            });
            
            resetForm();
            switchTab('list');
            loadVariants(currentProduitId);
            
            // Recharger la page pour mettre à jour le compteur
            setTimeout(() => location.reload(), 2000);
        } else {
            // ✅ Afficher les erreurs de validation
            let errorMsg = result.message || 'Une erreur est survenue';
            if (result.errors) {
                errorMsg = Object.values(result.errors).flat().join('\n');
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Erreur de validation',
                text: errorMsg
            });
        }
    } catch (error) {
        console.error('Erreur:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Erreur de connexion au serveur'
        });
    }
}

        // Modifier un variant
        async function editVariant(variantId) {
            try {
                const response = await fetch(`/api/variants/${variantId}`);
                const data = await response.json();
                
                if (data.success) {
                    const variant = data.variant;
                    
                    // Remplir le formulaire
                    document.getElementById('variant_id').value = variant.id;
                    document.querySelector('[name="ram"]').value = variant.specs.ram || '';
                    document.querySelector('[name="ssd"]').value = variant.specs.ssd || '';
                    document.querySelector('[name="cpu"]').value = variant.specs.cpu || '';
                    document.querySelector('[name="gpu"]').value = variant.specs.gpu || '';
                    document.querySelector('[name="ecran"]').value = variant.specs.ecran || '';
                    document.querySelector('[name="prix_supplement"]').value = variant.prix_supplement;
                    document.querySelector('[name="quantite_stock"]').value = variant.quantite_stock;
                    document.querySelector('[name="actif"]').checked = variant.actif;
                    
                    document.getElementById('submitBtnText').textContent = 'Modifier Variant';
                    switchTab('create');
                }
            } catch (error) {
                Swal.fire('Erreur', 'Impossible de charger le variant', 'error');
            }
        }

        // Supprimer un variant
        function deleteVariant(variantId, variantName) {
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: `Supprimer le variant "${variantName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D32F2F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch(`/produits/${currentProduitId}/variants/${variantId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                'Accept': 'application/json',
                            }
                        });
                        
                        if (response.ok) {
                            Swal.fire('Supprimé!', 'Le variant a été supprimé.', 'success');
                            loadVariants(currentProduitId);
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            Swal.fire('Erreur', 'Impossible de supprimer le variant', 'error');
                        }
                    } catch (error) {
                        Swal.fire('Erreur', 'Erreur de connexion', 'error');
                    }
                }
            });
        }

        // Reset form
        function resetForm() {
            document.getElementById('createVariantForm').reset();
            document.getElementById('variant_id').value = '';
            document.getElementById('submitBtnText').textContent = 'Créer Variant';
        }

        // Fonction de suppression de produit (existante)
        function confirmDelete(id) {
            Swal.fire({
                title: 'Êtes-vous sûr?',
                text: "Cette action est irréversible!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D32F2F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/produits/${id}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Fermer le modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('variantsModal');
            if (event.target == modal) {
                closeVariantsModal();
            }
        }

        // Success/Error Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: "{{ session('error') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
</x-app-layout>