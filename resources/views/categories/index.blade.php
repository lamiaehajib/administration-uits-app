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
    </style>

    <div class="categories-container">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-layer-group"></i> Gestion des Catégories</h1>
            <p>Organisez et gérez vos catégories de produits</p>
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
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 4px solid #4caf50;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table modern-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom de la Catégorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <span class="badge-number">{{ $loop->iteration }}</span>
                                </td>
                                <td style="font-weight: 600; color: #333; font-size: 1.05rem;">
                                    {{ $category->nom }}
                                </td>
                                <td>
                                    <div class="action-buttons">
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
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                                <i class="fas fa-trash-alt"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal for each category -->
                            <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="fas fa-edit"></i> Modifier la Catégorie
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="{{ route('categories.update', $category->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="nom{{ $category->id }}" class="form-label">
                                                        <i class="fas fa-tag"></i> Nom de la catégorie
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control @error('nom') is-invalid @enderror" 
                                                           id="nom{{ $category->id }}" 
                                                           name="nom" 
                                                           value="{{ old('nom', $category->nom) }}" 
                                                           required 
                                                           placeholder="Entrez le nom de la catégorie">
                                                    @error('nom')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">
                                                    <i class="fas fa-times"></i> Annuler
                                                </button>
                                                <button type="submit" class="btn btn-gradient">
                                                    <i class="fas fa-save"></i> Enregistrer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h4>Aucune catégorie trouvée</h4>
                                        <p>Commencez par créer votre première catégorie</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
                <div class="d-flex justify-content-center" style="padding: 20px;">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Nouvelle Catégorie
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nom" class="form-label">
                                <i class="fas fa-tag"></i> Nom de la catégorie
                            </label>
                            <input type="text" 
                                   class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" 
                                   name="nom" 
                                   value="{{ old('nom') }}" 
                                   required 
                                   placeholder="Entrez le nom de la catégorie"
                                   autofocus>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px;">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-save"></i> Créer
                        </button>
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