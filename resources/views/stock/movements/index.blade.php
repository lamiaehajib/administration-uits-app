<x-app-layout>
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>
                        <span class="hight">Mouvements de Stock</span>
                    </h3>
                    <p class="text-muted mb-0 mt-2">Gérez et suivez tous les mouvements de stock</p>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#ajustementModal">
                        <i class="fas fa-plus-circle me-2"></i>Nouvel Ajustement
                    </button>
                    <a href="{{ route('stock.statistiques') }}" class="btn btn-outline-secondary">
                         
                            statistiques
                        </a>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('stock.movements.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-filter me-1"></i>Type de mouvement
                        </label>
                        <select name="type" class="form-select">
                            <option value="">Tous les types</option>
                            <option value="entree" {{ request('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                            <option value="sortie" {{ request('type') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                            <option value="ajustement" {{ request('type') == 'ajustement' ? 'selected' : '' }}>Ajustement</option>
                            <option value="vente" {{ request('type') == 'vente' ? 'selected' : '' }}>Vente</option>
                            <option value="retour" {{ request('type') == 'retour' ? 'selected' : '' }}>Retour</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-box me-1"></i>Produit
                        </label>
                        <select name="produit_id" class="form-select select2">
                            <option value="">Tous les produits</option>
                            @foreach($produits as $produit)
                                <option value="{{ $produit->id }}" {{ request('produit_id') == $produit->id ? 'selected' : '' }}>
                                    {{ $produit->nom }} ({{ $produit->reference }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar me-1"></i>Date début
                        </label>
                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-check me-1"></i>Date fin
                        </label>
                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-gradient flex-fill">
                            <i class="fas fa-search me-1"></i>Filtrer
                        </button>
                        <a href="{{ route('stock.movements.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Movements Table -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th><i class="fas fa-box me-2"></i>Produit</th>
                                <th><i class="fas fa-tag me-2"></i>Type</th>
                                <th><i class="fas fa-sort-numeric-up me-2"></i>Quantité</th>
                                <th><i class="fas fa-arrow-left me-2"></i>Stock Avant</th>
                                <th><i class="fas fa-arrow-right me-2"></i>Stock Après</th>
                                <th><i class="fas fa-comment me-2"></i>Motif</th>
                                <th><i class="fas fa-user me-2"></i>Utilisateur</th>
                                <th><i class="fas fa-clock me-2"></i>Date</th>
                                <th><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $movement)
                                <tr>
                                    <td class="fw-semibold">#{{ $movement->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="product-icon me-2">
                                                <i class="fas fa-cube text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $movement->produit->nom }}</div>
                                                <small class="text-muted">{{ $movement->produit->reference }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $badges = [
                                                'entree' => ['class' => 'bg-success', 'icon' => 'arrow-down'],
                                                'sortie' => ['class' => 'bg-danger', 'icon' => 'arrow-up'],
                                                'ajustement' => ['class' => 'bg-warning', 'icon' => 'tools'],
                                                'vente' => ['class' => 'bg-info', 'icon' => 'shopping-cart'],
                                                'retour' => ['class' => 'bg-secondary', 'icon' => 'undo']
                                            ];
                                            $badge = $badges[$movement->type] ?? ['class' => 'bg-secondary', 'icon' => 'question'];
                                        @endphp
                                        <span class="badge {{ $badge['class'] }} badge-custom">
                                            <i class="fas fa-{{ $badge['icon'] }} me-1"></i>
                                            {{ ucfirst($movement->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-custom fs-6">
                                            {{ $movement->quantite }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $movement->stock_avant }}</td>
                                    <td class="fw-bold text-primary">{{ $movement->stock_apres }}</td>
                                    <td>
                                        @if($movement->motif)
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $movement->motif }}">
                                                {{ $movement->motif }}
                                            </span>
                                        @else
                                            <span class="text-muted fst-italic">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-user-circle text-primary me-1"></i>
                                        {{ $movement->user->name ?? 'Système' }}
                                    </td>
                                    <td>
                                        <div class="text-nowrap">
                                            <div>{{ $movement->created_at->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ $movement->created_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($movement->recu_ucg_id)
                                            <a href="{{ route('recus.show', $movement->recu_ucg_id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Voir le reçu">
                                                <i class="fas fa-receipt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Aucun mouvement de stock trouvé</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($movements->hasPages())
                <div class="card-footer bg-light">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Ajustement -->
    <div class="modal fade" id="ajustementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-gradient-custom text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-tools me-2"></i>Nouvel Ajustement de Stock
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('stock.movements.ajustement') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-box text-primary me-1"></i>Produit <span class="text-danger">*</span>
                            </label>
                            <select name="produit_id" class="form-select select2-modal" required>
                                <option value="">Sélectionner un produit</option>
                                @foreach($produits as $produit)
                                    <option value="{{ $produit->id }}">
                                        {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-sort-numeric-up text-primary me-1"></i>Quantité <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="quantite" class="form-control" required 
                                   placeholder="Positif pour entrée, négatif pour sortie">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Entrez un nombre positif pour ajouter, négatif pour retirer
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-comment text-primary me-1"></i>Motif <span class="text-danger">*</span>
                            </label>
                            <textarea name="motif" class="form-control" rows="3" required 
                                      placeholder="Expliquez la raison de cet ajustement..."></textarea>
                        </div>

                        <div class="alert alert-info border-0 mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Astuce:</strong> Utilisez cette fonction pour corriger les erreurs de stock ou enregistrer des pertes/gains.
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-check me-1"></i>Confirmer l'ajustement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Styles personnalisés -->
    <style>
        .page-header {
            padding: 20px 0;
            border-bottom: 3px solid;
            border-image: linear-gradient(135deg, #C2185B, #D32F2F) 1;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .bg-gradient-custom {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .badge-custom {
            padding: 6px 12px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .product-icon {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            border-radius: 8px;
        }

        .table thead th {
            font-weight: 600;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: rgba(194, 24, 91, 0.05);
            transform: scale(1.01);
        }

        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .modal-content {
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .select2-container--default .select2-selection--single {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            height: 38px;
            padding: 4px 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.25);
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            color: #D32F2F;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.85rem;
            }
            
            .btn {
                font-size: 0.875rem;
            }
        }
    </style>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: 'Sélectionner...',
                allowClear: true
            });

            // Select2 pour le modal
            $('#ajustementModal').on('shown.bs.modal', function () {
                $('.select2-modal').select2({
                    theme: 'bootstrap-5',
                    dropdownParent: $('#ajustementModal'),
                    placeholder: 'Sélectionner un produit...'
                });
            });

            // Messages de succès/erreur
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#D32F2F'
                });
            @endif
        });
    </script>
</x-app-layout>