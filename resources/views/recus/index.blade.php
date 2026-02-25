<x-app-layout>
    <style>
        /* ===================================
           DESIGN MODERNE & ÉLÉGANT
        =================================== */
        .page-header {
            background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 8px 32px rgba(211, 47, 47, 0.2);
        }

        .page-header h1 {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .page-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 0 0;
            font-size: 1rem;
        }

        /* Filtres Section */
        .filters-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .filters-card h5 {
            color: #D32F2F;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.15);
        }

        /* Buttons */
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4);
            color: white;
        }

        .btn-outline-gradient {
            border: 2px solid #D32F2F;
            color: #D32F2F;
            background: white;
            border-radius: 10px;
            padding: 11px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-gradient:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: transparent;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .table-responsive {
            border-radius: 16px;
        }

        /* Table Styles */
        .modern-table {
            margin: 0;
        }

        .modern-table thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .modern-table thead th {
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 18px 15px;
            border: none;
            font-size: 0.85rem;
        }

        .modern-table tbody td {
            padding: 16px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
            color: #495057;
        }

        .modern-table tbody tr {
            transition: all 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Badges */
        .badge {
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #FF9800, #FFA726);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, #F44336, #EF5350);
            color: white;
        }

        .badge-info {
            background: linear-gradient(135deg, #2196F3, #42A5F5);
            color: white;
        }

        .badge-secondary {
            background: linear-gradient(135deg, #757575, #9E9E9E);
            color: white;
        }

        /* Action Buttons */
        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            margin: 0 3px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-action.btn-info {
            background: linear-gradient(135deg, #2196F3, #42A5F5);
            color: white;
        }

        .btn-action.btn-warning {
            background: linear-gradient(135deg, #FF9800, #FFA726);
            color: white;
        }

        .btn-action.btn-danger {
            background: linear-gradient(135deg, #F44336, #EF5350);
            color: white;
        }

        /* Stats Cards */
        .stats-row {
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid #D32F2F;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-card .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-card h6 {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .stat-card h3 {
            color: #D32F2F;
            font-weight: 700;
            margin: 0;
        }

        /* Pagination */
        .pagination {
            margin-top: 25px;
            gap: 5px;
        }

        .page-link {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            color: #D32F2F;
            font-weight: 600;
            padding: 8px 16px;
            margin: 0 3px;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: transparent;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: transparent;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .empty-state h4 {
            color: #6c757d;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #adb5bd;
        }

        /* Filter produit disabled state */
        #filter-produit option[style*="display: none"] {
            display: none !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .filters-card {
                padding: 20px;
            }

            .btn-gradient, .btn-outline-gradient {
                padding: 10px 20px;
                font-size: 0.9rem;
            }
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1><i class="fas fa-receipt"></i> Gestion des Reçus UCG</h1>
                <p>Gérez et suivez tous vos reçus de vente</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('recus.trash') }}" class="btn btn-outline-light btn-lg border-2" style="color: white; border-color: white;">
                    <i class="fas fa-trash-alt"></i> Corbeille
                </a>
                <a href="{{ route('recus.create') }}" class="btn btn-light btn-lg">
                    <i class="fas fa-plus-circle"></i> Nouveau Reçu
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row stats-row">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <h6>Total Reçus</h6>
                <h3>{{ $recus->total() }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h6>Payés</h6>
                <h3 class="text-success">{{ $recus->where('statut_paiement', 'paye')->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h6>Partiels</h6>
                <h3 class="text-warning">{{ $recus->where('statut_paiement', 'partiel')->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h6>Impayés</h6>
                <h3 class="text-danger">{{ $recus->where('statut_paiement', 'impaye')->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="filters-card">
        <h5><i class="fas fa-filter"></i> Filtres de Recherche</h5>
        <form method="GET" action="{{ route('recus.index') }}">
            {{-- Ligne 1: Recherche, Statut, Paiement, Date Début, Date Fin, Bouton Chercher --}}
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Recherche</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="N° reçu, client, téléphone..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="livre"    {{ request('statut') == 'livre'    ? 'selected' : '' }}>Livré</option>
                        <option value="annule"   {{ request('statut') == 'annule'   ? 'selected' : '' }}>Annulé</option>
                        <option value="retour"   {{ request('statut') == 'retour'   ? 'selected' : '' }}>Retour</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Paiement</label>
                    <select name="statut_paiement" class="form-select">
                        <option value="">Tous</option>
                        <option value="paye"    {{ request('statut_paiement') == 'paye'    ? 'selected' : '' }}>Payé</option>
                        <option value="partiel" {{ request('statut_paiement') == 'partiel' ? 'selected' : '' }}>Partiel</option>
                        <option value="impaye"  {{ request('statut_paiement') == 'impaye'  ? 'selected' : '' }}>Impayé</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Date Début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold">Date Fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-gradient w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            {{-- Ligne 2: Catégorie + Produit --}}
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-tags me-1" style="color:#D32F2F;"></i> Catégorie
                    </label>
                    <select name="category_id" class="form-select" id="filter-category">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-box me-1" style="color:#D32F2F;"></i> Produit
                    </label>
                    <select name="produit_id" class="form-select" id="filter-produit">
                        <option value="">Tous les produits</option>
                        @foreach($produits as $prod)
                            <option value="{{ $prod->id }}"
                                {{ request('produit_id') == $prod->id ? 'selected' : '' }}
                                data-category="{{ $prod->category_id }}">
                                {{ $prod->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    @if(request()->hasAny(['search', 'statut', 'statut_paiement', 'date_debut', 'date_fin', 'category_id', 'produit_id']))
                        <a href="{{ route('recus.index') }}" class="btn btn-outline-gradient">
                            <i class="fas fa-times"></i> Réinitialiser les filtres
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="table-card">
        @if($recus->count() > 0)
            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>N° Reçu</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Total</th>
                            <th>Payé</th>
                            <th>Reste</th>
                            <th>Statut</th>
                            <th>Paiement</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recus as $recu)
                        <tr>
                            <td>
                                <strong class="text-primary">{{ $recu->numero_recu }}</strong>
                            </td>
                            <td>{{ $recu->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div>
                                    <strong>{{ $recu->client_nom }} {{ $recu->client_prenom }}</strong>
                                    @if($recu->client_telephone)
                                        <br><small class="text-muted"><i class="fas fa-phone"></i> {{ $recu->client_telephone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <strong class="text-dark">{{ number_format($recu->total, 2) }} DH</strong>
                            </td>
                            <td>
                                <span class="text-success fw-bold">{{ number_format($recu->montant_paye, 2) }} DH</span>
                            </td>
                            <td>
                                <span class="text-danger fw-bold">{{ number_format($recu->reste, 2) }} DH</span>
                            </td>
                            <td>
                                @if($recu->statut == 'en_cours')
                                    <span class="badge badge-info">En cours</span>
                                @elseif($recu->statut == 'livre')
                                    <span class="badge badge-success">Livré</span>
                                @elseif($recu->statut == 'annule')
                                    <span class="badge badge-danger">Annulé</span>
                                @else
                                    <span class="badge badge-warning">Retour</span>
                                @endif
                            </td>
                            <td>
                                @if($recu->statut_paiement == 'paye')
                                    <span class="badge badge-success">Payé</span>
                                @elseif($recu->statut_paiement == 'partiel')
                                    <span class="badge badge-warning">Partiel</span>
                                @else
                                    <span class="badge badge-danger">Impayé</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('recus.show', $recu) }}" class="btn btn-action btn-info" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('recus.edit', $recu) }}" class="btn btn-action btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('recus.destroy', $recu) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-danger"
                                                onclick="return confirm('Confirmer la suppression ?')"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center p-4">
                {{ $recus->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h4>Aucun reçu trouvé</h4>
                <p>Commencez par créer votre premier reçu</p>
                <a href="{{ route('recus.create') }}" class="btn btn-gradient mt-3">
                    <i class="fas fa-plus-circle"></i> Créer un reçu
                </a>
            </div>
        @endif
    </div>

    <!-- SweetAlert for Success/Error Messages -->
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Succès!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Erreur!',
            text: '{{ session('error') }}',
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    {{-- ✅ JS: Filtrage dynamique des produits par catégorie --}}
    <script>
        const filterCategory = document.getElementById('filter-category');
        const filterProduit  = document.getElementById('filter-produit');
        const allOptions     = Array.from(filterProduit.querySelectorAll('option[data-category]'));

        function filterProduits() {
            const selectedCat = filterCategory.value;

            allOptions.forEach(option => {
                if (!selectedCat || option.dataset.category == selectedCat) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });

            // Si le produit sélectionné n'appartient plus à la catégorie → reset
            const selectedProd = filterProduit.querySelector('option[value="' + filterProduit.value + '"]');
            if (selectedProd && selectedProd.style.display === 'none') {
                filterProduit.value = '';
            }
        }

        filterCategory.addEventListener('change', filterProduits);

        // Init au chargement (utile si filtres déjà appliqués via URL)
        document.addEventListener('DOMContentLoaded', filterProduits);
    </script>

</x-app-layout>