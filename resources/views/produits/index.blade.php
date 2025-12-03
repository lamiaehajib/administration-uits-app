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
        .btn-stock { 
    background: #F3E5F5; 
    color: #7B1FA2; 
}
.btn-stock:hover { 
    background: #7B1FA2; 
    color: white; 
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
            
            <div class="stat-card info">
                <div class="stat-icon"><i class="fas fa-coins"></i></div>
                <div class="stat-value">{{ number_format($produits->sum('marge_totale'), 0) }} DH</div>
                <div class="stat-label">Marge Totale</div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar">
            <form action="{{ route('produits.index') }}" method="GET" class="search-box">
                <input type="text" name="search" placeholder="🔍 Rechercher un produit..." 
                       value="{{ request('search') }}">
                <i class="fas fa-search"></i>
            </form>

            <a href="{{ route('produits.create') }}" class="btn-modern btn-primary-modern">
                <i class="fas fa-plus-circle"></i> Nouveau Produit
            </a>

            <a href="{{ route('produits.rapport') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-chart-line"></i> Rapport
            </a>

            <a href="{{ route('produits.export_pdf') }}" class="btn-modern btn-outline-modern">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
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
                            <th>Ventes</th>
                            <th>Marge</th>
                            <th>Statut</th>
                            <th>Actions</th>
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
                                <div><strong>{{ $produit->quantite_vendue ?? 0 }}</strong> unités</div>
                                <small class="text-muted">{{ number_format($produit->total_vendu_montant ?? 0, 0) }} DH</small>
                            </td>
                            <td>
                                <div class="text-success">
                                    <strong>{{ number_format($produit->marge_totale ?? 0, 0) }} DH</strong>
                                </div>
                                <small class="text-muted">({{ number_format($produit->marge_pourcentage ?? 0, 1) }}%)</small>
                            </td>
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
                                <div class="action-btns">
                                    <a href="{{ route('produits.show', $produit->id) }}" 
                                       class="btn-icon btn-view" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('produits.edit', $produit->id) }}" 
                                       class="btn-icon btn-edit" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete({{ $produit->id }})" 
                                            class="btn-icon btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <a href="{{ route('stock.movements.produit', $produit->id) }}" 
   class="btn-icon btn-stock" 
   title="Mouvements de stock">
    <i class="fas fa-exchange-alt"></i>
</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
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
            <div class="pagination-modern">
                {{ $produits->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Script -->
    <script>
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