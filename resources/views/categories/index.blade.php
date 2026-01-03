<x-app-layout>
    <style>
        /* Container principal avec animations */
        .categories-container {
            animation: fadeInUp 0.6s ease-out;
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

        /* Header Section avec gradient */
        .page-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.3);
            animation: slideInDown 0.5s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-header h1 {
            color: white;
            margin: 0;
            font-size: 2.5rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 0 0;
            font-size: 1.1rem;
        }

        /* Search Bar Modern */
        .search-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            animation: fadeIn 0.7s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .search-wrapper {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input-group {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-input-group input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input-group input:focus {
            outline: none;
            border-color: #D32F2F;
            box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
        }

        .search-input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #C2185B;
            font-size: 1.1rem;
        }

        /* Boutons modernes */
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .btn-gradient i {
            margin-right: 8px;
        }

        /* Table moderne */
        .table-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            animation: fadeInUp 0.8s ease-out;
        }

        .table-responsive {
            border-radius: 15px;
        }

        .modern-table {
            margin: 0;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .modern-table thead th {
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 18px 15px;
            border: none;
            font-size: 0.9rem;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            transform: scale(1.01);
        }

        .modern-table tbody td {
            padding: 15px;
            vertical-align: middle;
            color: #333;
        }

        /* Badges pour les numéros */
        .badge-number {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 8px 15px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-block;
            min-width: 40px;
        }

        /* Action buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .btn-edit {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.4);
        }

        .btn-delete {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(244, 67, 54, 0.4);
        }

        /* Modal moderne */
        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 25px 30px;
            border: none;
        }

        .modal-header h5 {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            border-top: 1px solid #e0e0e0;
            padding: 20px 30px;
        }

        /* Form inputs modernes */
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 20px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
            outline: none;
        }

        .is-invalid {
            border-color: #f44336 !important;
        }

        .invalid-feedback {
            color: #f44336;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        /* Pagination moderne */
        .pagination {
            margin-top: 25px;
            justify-content: center;
        }

        .pagination .page-link {
            border: 2px solid #e0e0e0;
            color: #D32F2F;
            margin: 0 5px;
            border-radius: 10px;
            padding: 10px 18px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: #D32F2F;
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: #D32F2F;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 4rem;
            color: #e0e0e0;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #666;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.8rem;
            }

            .search-wrapper {
                flex-direction: column;
            }

            .search-input-group {
                width: 100%;
            }

            .btn-gradient {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
            }
        }
        





                .categories-container {
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

        /* ✅ Nouvelles cartes de statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(211, 47, 47, 0.2);
        }

        .stat-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }

        .stat-card-icon.purple {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
            color: white;
        }

        .stat-card-icon.blue {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        .stat-card-icon.green {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: white;
        }

        .stat-card-icon.orange {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
        }

        .stat-card-title {
            font-size: 0.9rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        /* ✅ Cartes de catégories avec mini-stats */
        .category-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(211, 47, 47, 0.15);
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .category-name {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
        }

        .category-stats-mini {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin: 15px 0;
        }

        .mini-stat {
            text-align: center;
            padding: 12px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
        }

        .mini-stat-value {
            font-size: 1.4rem;
            font-weight: bold;
            color: #C2185B;
        }

        .mini-stat-label {
            font-size: 0.75rem;
            color: #666;
            text-transform: uppercase;
            margin-top: 5px;
        }

        /* ✅ Barre de progression */
        .progress-bar-custom {
            height: 8px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #C2185B, #D32F2F);
            transition: width 0.5s ease;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .category-stats-mini {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .btn-action {
    padding: 8px 15px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    font-weight: 500;
    color: white !important;
}

    </style>

    <div class="categories-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-layer-group"></i> Gestion des Catégories</h1>
            <p>Organisez et gérez vos catégories de produits</p>
        </div>

        <!-- ✅ Statistiques Globales -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-icon purple">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-card-title">Total Catégories</div>
                <div class="stat-card-value">{{ $stats['total_categories'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon blue">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-card-title">Total Produits</div>
                <div class="stat-card-value">{{ $stats['total_produits'] }}</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon green">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-card-title">Valeur Stock</div>
                <div class="stat-card-value">{{ number_format($stats['valeur_stock_total'], 2) }} DH</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-icon orange">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-card-title">CA Total</div>
                <div class="stat-card-value">{{ number_format($stats['ca_total'], 2) }} DH</div>
            </div>
        </div>

        <!-- Search & Add Section -->
        <div class="search-section">
            <div class="search-wrapper">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <form method="GET" action="{{ route('categories.index') }}" style="flex: 1;">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Rechercher une catégorie..." 
                               value="{{ request('search') }}">
                    </form>
                </div>
                <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus-circle"></i> Nouvelle Catégorie
                </button>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- ✅ Liste des catégories avec stats -->
        @forelse($categories as $category)
            <div class="category-card">
                <div class="category-header">
                    <div class="category-name">
                        <i class="fas fa-folder"></i> {{ $category->nom }}
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('categories.show', $category->id) }}" class="btn-action" style="background: linear-gradient(135deg, #4CAF50, #388E3C);">
                            <i class="fas fa-chart-bar"></i> Détails
                        </a>
                        <button type="button" 
                                class="btn-action btn-edit" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal{{ $category->id }}">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <form method="POST" 
                              action="{{ route('categories.destroy', $category->id) }}" 
                              style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="btn-action btn-delete" 
                                    onclick="return confirm('Êtes-vous sûr ?')">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ✅ Mini statistiques -->
                <div class="category-stats-mini">
                    <div class="mini-stat">
                        <div class="mini-stat-value">{{ $category->total_produits }}</div>
                        <div class="mini-stat-label">Produits</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-value">{{ number_format($category->ca_total, 0) }} DH</div>
                        <div class="mini-stat-label">CA Total</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-value">{{ number_format($category->marge_totale, 0) }} DH</div>
                        <div class="mini-stat-label">Marge</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-value">{{ number_format($category->quantite_vendue, 0) }}</div>
                        <div class="mini-stat-label">Quantité vendue</div>
                    </div>
                </div>

                <!-- ✅ Barre de progression du stock -->
                <div style="margin-top: 15px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #666; margin-bottom: 5px;">
                        <span><i class="fas fa-box"></i> Stock: {{ number_format($category->valeur_stock, 2) }} DH</span>
                        <span><i class="fas fa-exclamation-triangle"></i> Rupture: {{ $category->produits_rupture }}</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: {{ $category->total_produits > 0 ? (($category->produits_actifs / $category->total_produits) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-edit"></i> Modifier la Catégorie</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('categories.update', $category->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-tag"></i> Nom de la catégorie</label>
                                    <input type="text" class="form-control" name="nom" value="{{ $category->nom }}" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <button type="submit" class="btn btn-gradient">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h4>Aucune catégorie trouvée</h4>
                <p>Commencez par créer votre première catégorie</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($categories->hasPages())
            
            <div class="d-flex justify-content-between align-items-center p-3">
                
                <div>
                    {{ $categories->links('pagination.custom') }}
                </div>
            </div>
        @endif
        
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Nouvelle Catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-tag"></i> Nom de la catégorie</label>
                            <input type="text" class="form-control" name="nom" required autofocus>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-gradient">Créer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Scripts pour animations -->
    <script>
        // Animation au scroll
        document.addEventListener('DOMContentLoaded', function() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            });

            document.querySelectorAll('.modern-table tbody tr').forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';
                row.style.transition = `all 0.5s ease ${index * 0.05}s`;
                observer.observe(row);
            });

            // Auto-dismiss alerts
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Confirmation de suppression avec SweetAlert2
        document.querySelectorAll('form[action*="destroy"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler',
                    background: '#fff',
                    customClass: {
                        popup: 'animated fadeInDown'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>