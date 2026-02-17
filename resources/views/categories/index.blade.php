<x-app-layout>
    <style>
        /* Container principal */
        .categories-container {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Page Header */
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

        .page-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 0 0;
        }

        /* Stats Cards */
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
        }

        .stat-card.purple::before { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
        .stat-card.blue::before { background: linear-gradient(135deg, #2196F3, #1976D2); }
        .stat-card.green::before { background: linear-gradient(135deg, #4CAF50, #388E3C); }
        .stat-card.orange::before { background: linear-gradient(135deg, #FF9800, #F57C00); }

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
            color: white;
        }

        .stat-card.purple .stat-card-icon { background: linear-gradient(135deg, #9C27B0, #7B1FA2); }
        .stat-card.blue .stat-card-icon { background: linear-gradient(135deg, #2196F3, #1976D2); }
        .stat-card.green .stat-card-icon { background: linear-gradient(135deg, #4CAF50, #388E3C); }
        .stat-card.orange .stat-card-icon { background: linear-gradient(135deg, #FF9800, #F57C00); }

        .stat-card-title {
            font-size: 0.9rem;
            color: #666;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
        }

        /* Search Section */
        .search-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
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
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(211, 47, 47, 0.4);
            color: white;
        }

        /* ✅ Carte Catégorie Parent */
        .parent-category-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border-left: 5px solid #C2185B;
        }

        .parent-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .parent-name {
            font-size: 1.5rem;
            font-weight: bold;
            color: #C2185B;
        }

        /* ✅ Bouton Toggle Sous-catégories */
        .btn-toggle-children {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(156, 39, 176, 0.3);
        }

        .btn-toggle-children:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(156, 39, 176, 0.45);
        }

        .btn-toggle-children .arrow-icon {
            transition: transform 0.3s ease;
            display: inline-block;
            font-style: normal;
            font-size: 0.8rem;
        }

        .btn-toggle-children.open .arrow-icon {
            transform: rotate(90deg);
        }

        /* ✅ Sous-catégories container */
        .children-container {
            margin-top: 15px;
            padding-left: 30px;
            border-left: 3px dashed #e0e0e0;
            display: none; /* Cachées par défaut */
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .child-category-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
            transition: all 0.3s ease;
        }

        .child-category-card:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(33, 150, 243, 0.2);
        }

        .child-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2196F3;
            margin-bottom: 10px;
        }

        /* Stats Mini */
        .category-stats-mini {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin: 15px 0;
        }

        .mini-stat {
            text-align: center;
            padding: 12px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
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

        .btn-details { background: linear-gradient(135deg, #4CAF50, #388E3C); }
        .btn-edit { background: linear-gradient(135deg, #2196F3, #1976D2); }
        .btn-delete { background: linear-gradient(135deg, #f44336, #d32f2f); }
        .btn-move { background: linear-gradient(135deg, #FF9800, #F57C00); }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 25px 30px;
            border: none;
            border-radius: 20px 20px 0 0;
        }

        .modal-header h5 {
            font-weight: bold;
            margin: 0;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 0.85rem;
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
            outline: none;
        }

        /* Progress Bar */
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

        /* Badge hiérarchique */
        .hierarchy-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-parent {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .badge-child {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .category-stats-mini {
                grid-template-columns: repeat(2, 1fr);
            }
            .action-buttons {
                flex-direction: column;
            }
            .btn-action {
                width: 100%;
            }
        }
    </style>

    <div class="categories-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-layer-group"></i> Gestion des Catégories</h1>
            <p>Organisez vos catégories de manière hiérarchique</p>
        </div>

        <!-- ✅ Statistiques Globales -->
        <div class="stats-grid">
            <div class="stat-card purple">
                <div class="stat-card-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-card-title">Catégories Parent</div>
                <div class="stat-card-value">{{ $stats['total_categories_parent'] }}</div>
            </div>

            <div class="stat-card blue">
                <div class="stat-card-icon">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div class="stat-card-title">Total Catégories</div>
                <div class="stat-card-value">{{ $stats['total_categories'] }}</div>
            </div>

            <div class="stat-card green">
                <div class="stat-card-icon">
                    <i class="fas fa-warehouse"></i>
                </div>
                <div class="stat-card-title">Valeur Stock</div>
                <div class="stat-card-value">{{ number_format($stats['valeur_stock_total'], 0) }} DH</div>
            </div>

            <div class="stat-card orange">
                <div class="stat-card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-card-title">CA Total</div>
                <div class="stat-card-value">{{ number_format($stats['ca_total'], 0) }} DH</div>
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
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- ✅ Liste des Catégories Parent avec Enfants -->
        @forelse($categories as $parent)
            <div class="parent-category-card">

                <!-- Header Parent -->
                <div class="parent-header">
                    <div>
                        <span class="hierarchy-badge badge-parent">Parent</span>
                        <div class="parent-name">
                            <i class="fas fa-folder-open"></i> {{ $parent->nom }}
                        </div>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('categories.show', $parent->id) }}" class="btn-action btn-details">
                            <i class="fas fa-chart-bar"></i> Détails
                        </a>
                        <button type="button" 
                                class="btn-action btn-edit" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal{{ $parent->id }}">
                            <i class="fas fa-edit"></i> Modifier
                        </button>
                        <form method="POST" action="{{ route('categories.destroy', $parent->id) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Êtes-vous sûr ?')">
                                <i class="fas fa-trash-alt"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Stats Parent -->
                <div class="category-stats-mini">
                    <div class="mini-stat">
                        <div class="mini-stat-value">{{ $parent->total_produits }}</div>
                        <div class="mini-stat-label">Produits</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-value">{{ number_format($parent->ca_total, 0) }} DH</div>
                        <div class="mini-stat-label">CA Total</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-value">{{ number_format($parent->marge_totale, 0) }} DH</div>
                        <div class="mini-stat-label">Marge</div>
                    </div>
                    <div class="mini-stat">
                        <div class="mini-stat-value">{{ number_format($parent->quantite_vendue, 0) }}</div>
                        <div class="mini-stat-label">Qté vendue</div>
                    </div>
                </div>

                <!-- ✅ Bouton Toggle + Sous-catégories -->
                @if($parent->children->count() > 0)

                    <button 
                        class="btn-toggle-children" 
                        onclick="toggleChildren(this, 'children-{{ $parent->id }}')"
                    >
                        <span class="arrow-icon">▶</span>
                        <i class="fas fa-sitemap"></i>
                        <span class="toggle-label">Voir sous-catégories ({{ $parent->children->count() }})</span>
                    </button>

                    <div class="children-container" id="children-{{ $parent->id }}">
                        <h6 style="color: #666; font-weight: 600; margin-bottom: 15px;">
                            <i class="fas fa-sitemap"></i> Sous-catégories ({{ $parent->children->count() }})
                        </h6>
                        
                        @foreach($parent->children as $child)
                            <div class="child-category-card">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                                    <div>
                                        <span class="hierarchy-badge badge-child">Sous-catégorie</span>
                                        <div class="child-name">
                                            <i class="fas fa-folder"></i> {{ $child->nom }}
                                        </div>
                                    </div>
                                    <div class="action-buttons">
                                        <a href="{{ route('categories.show', $child->id) }}" class="btn-action btn-details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn-action btn-edit" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $child->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('categories.destroy', $child->id) }}" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Êtes-vous sûr ?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Mini Stats Enfant -->
                                <div class="category-stats-mini">
                                    <div class="mini-stat">
                                        <div class="mini-stat-value">{{ $child->total_produits }}</div>
                                        <div class="mini-stat-label">Produits</div>
                                    </div>
                                    <div class="mini-stat">
                                        <div class="mini-stat-value">{{ number_format($child->ca_total, 0) }} DH</div>
                                        <div class="mini-stat-label">CA</div>
                                    </div>
                                    <div class="mini-stat">
                                        <div class="mini-stat-value">{{ number_format($child->marge_totale, 0) }} DH</div>
                                        <div class="mini-stat-label">Marge</div>
                                    </div>
                                    <div class="mini-stat">
                                        <div class="mini-stat-value">{{ $child->produits_rupture }}</div>
                                        <div class="mini-stat-label">Rupture</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Modal Child -->
                            <div class="modal fade" id="editModal{{ $child->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5><i class="fas fa-edit"></i> Modifier: {{ $child->nom }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('categories.update', $child->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label"><i class="fas fa-tag"></i> Nom</label>
                                                    <input type="text" class="form-control" name="nom" value="{{ $child->nom }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><i class="fas fa-sitemap"></i> Catégorie Parent</label>
                                                    <select class="form-select" name="parent_id">
                                                        <option value="">Aucun (Devenir parent)</option>
                                                        @foreach($categoriesForSelect as $cat)
                                                            @if($cat->id != $child->id)
                                                                <option value="{{ $cat->id }}" {{ $child->parent_id == $cat->id ? 'selected' : '' }}>
                                                                    {{ $cat->nom }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
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
                        @endforeach
                    </div>

                @else
                    <div style="text-align: center; color: #999; padding: 20px; background: #f8f9fa; border-radius: 10px; margin-top: 15px;">
                        <i class="fas fa-info-circle"></i> Aucune sous-catégorie
                    </div>
                @endif

                <!-- Progress Bar -->
                <div style="margin-top: 15px;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.85rem; color: #666; margin-bottom: 5px;">
                        <span><i class="fas fa-box"></i> Valeur Stock: {{ number_format($parent->valeur_stock, 2) }} DH</span>
                        <span><i class="fas fa-exclamation-triangle"></i> Rupture: {{ $parent->produits_rupture }}</span>
                    </div>
                    <div class="progress-bar-custom">
                        <div class="progress-fill" style="width: {{ $parent->total_produits > 0 ? (($parent->produits_actifs / $parent->total_produits) * 100) : 0 }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal Parent -->
            <div class="modal fade" id="editModal{{ $parent->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><i class="fas fa-edit"></i> Modifier: {{ $parent->nom }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('categories.update', $parent->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-tag"></i> Nom de la catégorie</label>
                                    <input type="text" class="form-control" name="nom" value="{{ $parent->nom }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-sitemap"></i> Catégorie Parent (optionnel)</label>
                                    <select class="form-select" name="parent_id">
                                        <option value="">Aucun (Rester parent)</option>
                                        @foreach($categoriesForSelect as $cat)
                                            @if($cat->id != $parent->id)
                                                <option value="{{ $cat->id }}">
                                                    {{ $cat->nom }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Sélectionnez un parent pour transformer cette catégorie en sous-catégorie
                                    </small>
                                    @if($parent->children->count() > 0)
                                        <div class="alert alert-warning mt-2 mb-0" style="font-size: 0.85rem; padding: 10px;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <strong>Attention:</strong> Cette catégorie contient {{ $parent->children->count() }} sous-catégorie(s).
                                            Si vous lui assignez un parent, ses sous-catégories deviendront orphelines.
                                        </div>
                                    @endif
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
            <div style="text-align: center; padding: 60px; background: white; border-radius: 15px;">
                <i class="fas fa-inbox" style="font-size: 4rem; color: #e0e0e0;"></i>
                <h4 style="color: #666; margin-top: 20px;">Aucune catégorie trouvée</h4>
                <p style="color: #999;">Commencez par créer votre première catégorie</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($categories->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- ✅ Modal Création -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5><i class="fas fa-plus-circle"></i> Nouvelle Catégorie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-tag"></i> Nom de la catégorie</label>
                            <input type="text" class="form-control" name="nom" required autofocus>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-sitemap"></i> Catégorie Parent (optionnel)</label>
                            <select class="form-select" name="parent_id">
                                <option value="">Aucun (Créer comme parent)</option>
                                @foreach($categoriesForSelect as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Laissez vide pour créer une catégorie parent</small>
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

    <script>
        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // ✅ Toggle sous-catégories
        function toggleChildren(btn, containerId) {
            const container = document.getElementById(containerId);
            const label = btn.querySelector('.toggle-label');
            const arrow = btn.querySelector('.arrow-icon');
            const isOpen = btn.classList.contains('open');
            const count = label.textContent.match(/\d+/)[0];

            if (isOpen) {
                // Fermer
                container.style.display = 'none';
                btn.classList.remove('open');
                arrow.textContent = '▶';
                label.textContent = 'Voir sous-catégories (' + count + ')';
            } else {
                // Ouvrir
                container.style.display = 'block';
                btn.classList.add('open');
                arrow.textContent = '▼';
                label.textContent = 'Cacher sous-catégories (' + count + ')';
            }
        }
    </script>
</x-app-layout>