<x-app-layout>
    <div class="container-fluid py-4">
        
        <h3 class="text-center mb-4">
            <i class="fas fa-chart-bar me-2 hight"></i> Rapport de Performance des Produits
        </h3>
        
        <hr class="mb-4">

        <div class="row mb-5 justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0 p-3">
                    <form action="{{ route('produits.rapport') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="date_debut" class="form-label mb-1 text-muted">Date de Début</label>
                            <input type="date" id="date_debut" name="date_debut" class="form-control" value="{{ \Carbon\Carbon::parse($dateDebut)->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-5">
                            <label for="date_fin" class="form-label mb-1 text-muted">Date de Fin</label>
                            <input type="date" id="date_fin" name="date_fin" class="form-control" value="{{ \Carbon\Carbon::parse($dateFin)->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100 shadow-sm" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border: none;">
                                Filtrer <i class="fas fa-filter ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <h4 class="mb-4 text-center">
            <span class="badge bg-secondary">Période: Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</span>
        </h4>
        
        <div class="row">
            
            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg h-100 border-start border-5 border-info">
                    <div class="card-header bg-info text-white fw-bold">
                        <i class="fas fa-arrow-up me-2"></i> Top 10 des Produits les Plus Vendus (Quantité)
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($topVentes as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-info me-2">{{ $loop->iteration }}</span>
                                        <a href="{{ route('produits.show', $item->id) }}" class="fw-bold text-dark">{{ $item->nom }}</a>
                                        <small class="text-muted ms-2">({{ $item->reference ?? 'N/A' }})</small>
                                    </div>
                                    <span class="badge bg-dark rounded-pill">{{ number_format($item->quantite_vendue, 0) }} Unités</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Aucune vente enregistrée pour cette période.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow-lg h-100 border-start border-5 border-success">
                    <div class="card-header bg-success text-white fw-bold">
                        <i class="fas fa-trophy me-2"></i> Top 10 des Produits les Plus Rentables (Marge)
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($topMarges as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="badge bg-success me-2">{{ $loop->iteration }}</span>
                                        <a href="{{ route('produits.show', $item->id) }}" class="fw-bold text-dark">{{ $item->nom }}</a>
                                        <small class="text-muted ms-2">({{ $item->reference ?? 'N/A' }})</small>
                                    </div>
                                    <span class="badge bg-success rounded-pill">{{ number_format($item->marge_totale, 2, ',', ' ') }} MAD</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">Aucun bénéfice enregistré pour cette période.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            
        </div> <hr class="mt-4 mb-4">

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-start border-5 border-danger">
                    <div class="card-header bg-danger text-white fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i> Produits en Alerte de Stock
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($alerteStock as $produit)
                                <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-danger">
                                    <div>
                                        <a href="{{ route('produits.show', $produit->id) }}" class="fw-bold text-dark">{{ $produit->nom }}</a>
                                        <small class="text-muted ms-2">({{ $produit->reference ?? 'N/A' }})</small>
                                    </div>
                                    <span class="text-danger fw-bold">
                                        <i class="fas fa-warehouse me-1"></i> Stock actuel: {{ $produit->quantite_stock }} (Alerte: {{ $produit->stock_alerte ?? 0 }})
                                    </span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-success fw-bold">
                                    <i class="fas fa-check-circle me-1"></i> Aucun produit en dessous du seuil d'alerte. Le stock est bon.
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>