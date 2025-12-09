<x-app-layout>
    {{-- Assurez-vous que vous avez l'entête de la page si vous utilisez un composant header/slot spécifique --}}
    {{-- Par exemple, si vous avez un <x-slot name="header"> --}}
    
    <div class="container-fluid">
        
        {{-- Titre et Bouton de Retour --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="hight mb-0"><i class="fas fa-trash-alt me-2"></i> Corbeille des Reçus</h2>
            <a href="{{ route('recus.index') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border: none;">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
        
        {{-- Affichage des messages flash --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                Reçus Supprimés (Soft Deleted) - ({{ $recus->total() }})
            </div>
            <div class="card-body">
                
                {{-- Formulaire de Recherche --}}
                <form action="{{ route('recus.trash') }}" method="GET" class="row g-3 align-items-center mb-4">
                    <div class="col-md-6 col-lg-8">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par N° Reçu, Client, Tél..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-search"></i> Rechercher</button>
                    </div>
                    @if(request()->filled('search'))
                    <div class="col-md-3 col-lg-2">
                        <a href="{{ route('recus.trash') }}" class="btn btn-secondary w-100">Annuler</a>
                    </div>
                    @endif
                </form>
                
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>N° Reçu</th>
                                <th>Client</th>
                                <th>Total</th>
                                <th>Statut Paiement</th>
                                <th>Date Suppression</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recus as $recu)
                            <tr>
                                <td>{{ $recu->numero_recu }}</td>
                                <td>{{ $recu->client_nom }} {{ $recu->client_prenom }}</td>
                                <td><span class="badge bg-warning text-dark">{{ number_format($recu->total, 2) }} DH</span></td>
                                <td>
                                    @php
                                        $badgeClass = match($recu->statut_paiement) {
                                            'paye' => 'success',
                                            'partiel' => 'info',
                                            default => 'danger',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($recu->statut_paiement) }}</span>
                                </td>
                                <td>{{ $recu->deleted_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    {{-- RESTAURER --}}
                                    <form action="{{ route('recus.restore', $recu->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir restaurer ce reçu ? Le stock sera mis à jour en conséquence.');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer"><i class="fas fa-undo"></i> Restaurer</button>
                                    </form>

                                    {{-- SUPPRIMER DÉFINITIVEMENT --}}
                                    <form action="{{ route('recus.forceDelete', $recu->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('ATTENTION : Voulez-vous vraiment supprimer DÉFINITIVEMENT ce reçu ? Cette action est IRREVERSIBLE et les données seront perdues.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer définitivement"><i class="fas fa-times-circle"></i> Suppr. Déf.</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-box-open fa-2x text-muted"></i>
                                    <p class="mt-2">Aucun reçu trouvé dans la corbeille.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $recus->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>