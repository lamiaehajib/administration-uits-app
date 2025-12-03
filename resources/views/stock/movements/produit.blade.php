<x-app-layout>
    <div class="container-fluid">
        <!-- En-tête avec informations produit -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h3><i class="fas fa-box"></i> Mouvements de Stock - {{ $produit->nom }}</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('stock.movements.index') }}">Mouvements</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $produit->nom }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('stock.movements.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <!-- Carte d'informations du produit -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Stock Actuel</h6>
                        <h3 class="mb-0">
                            <span class="hight">{{ $produit->quantite_stock }}</span>
                            <small class="text-muted">{{ $produit->unite }}</small>
                        </h3>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Stock d'Alerte</h6>
                        <h4 class="mb-0">
                            @if($produit->quantite_stock <= $produit->stock_alerte)
                                <span class="text-danger">
                                    <i class="fas fa-exclamation-triangle"></i> {{ $produit->stock_alerte }}
                                </span>
                            @else
                                <span class="text-success">{{ $produit->stock_alerte }}</span>
                            @endif
                        </h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Prix d'Achat</h6>
                        <h4 class="mb-0">{{ number_format($produit->prix_achat, 2) }} DH</h4>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted mb-1">Valeur Stock</h6>
                        <h4 class="mb-0 text-success">
                            {{ number_format($produit->quantite_stock * $produit->prix_achat, 2) }} DH
                        </h4>
                    </div>
                </div>

                @if($produit->quantite_stock <= $produit->stock_alerte)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Alerte stock:</strong> Le stock de ce produit est inférieur ou égal au seuil d'alerte.
                    </div>
                @endif
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-arrow-down fa-2x text-success mb-2"></i>
                        <h5>Entrées</h5>
                        <h3 class="text-success">
                            {{ $movements->where('type', 'entree')->sum('quantite') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-arrow-up fa-2x text-danger mb-2"></i>
                        <h5>Sorties</h5>
                        <h3 class="text-danger">
                            {{ $movements->where('type', 'sortie')->sum('quantite') }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-wrench fa-2x text-warning mb-2"></i>
                        <h5>Ajustements</h5>
                        <h3 class="text-warning">
                            {{ $movements->where('type', 'ajustement')->count() }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-undo fa-2x text-info mb-2"></i>
                        <h5>Retours</h5>
                        <h3 class="text-info">
                            {{ $movements->where('type', 'retour')->sum('quantite') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des mouvements -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-history"></i> Historique des Mouvements
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date & Heure</th>
                                <th>Type</th>
                                <th>Quantité</th>
                                <th>Stock Avant</th>
                                <th>Stock Après</th>
                                <th>Référence</th>
                                <th>Utilisateur</th>
                                <th>Motif</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $movement)
                                <tr>
                                    <td>
                                        <strong>{{ $movement->created_at->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $movement->created_at->format('H:i:s') }}</small>
                                    </td>
                                    <td>
                                        @if($movement->type == 'entree')
                                            <span class="badge bg-success">
                                                <i class="fas fa-arrow-down"></i> Entrée
                                            </span>
                                        @elseif($movement->type == 'sortie')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-arrow-up"></i> Sortie
                                            </span>
                                        @elseif($movement->type == 'ajustement')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-wrench"></i> Ajustement
                                            </span>
                                        @else
                                            <span class="badge bg-info">
                                                <i class="fas fa-undo"></i> Retour
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>
                                            @if($movement->type == 'entree' || $movement->type == 'retour')
                                                <span class="text-success">+{{ $movement->quantite }}</span>
                                            @else
                                                <span class="text-danger">-{{ $movement->quantite }}</span>
                                            @endif
                                        </strong>
                                    </td>
                                    <td>{{ $movement->stock_avant }}</td>
                                    <td>
                                        <strong>{{ $movement->stock_apres }}</strong>
                                    </td>
                                    <td>
                                        @if($movement->reference)
                                            <code>{{ $movement->reference }}</code>
                                        @elseif($movement->recuUcg)
                                            <a href="{{ route('recus.show', $movement->recuUcg->id) }}" 
                                               class="text-decoration-none" title="Voir le reçu UCG">
                                                <i class="fas fa-file-invoice"></i>
                                                {{ $movement->recuUcg->numero_recu }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <i class="fas fa-user"></i>
                                        <small>{{ $movement->user->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        @if($movement->motif)
                                            <span data-bs-toggle="tooltip" title="{{ $movement->motif }}">
                                                {{ Str::limit($movement->motif, 40) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Aucun mouvement enregistré pour ce produit</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($movements->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialiser les tooltips Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    @endpush
</x-app-layout>