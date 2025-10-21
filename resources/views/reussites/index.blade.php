<x-app-layout>
<div class="container-fluid py-4">
<h3 class="text-center hight mb-4 shadow-sm p-3 rounded-lg bg-white">
<i class="fas fa-receipt mr-2" style="color: #D32F2F;"></i> Gestion des Reçus de Stage
</h3>
    <!-- Messages de session (Success/Error) -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulaire de Filtrage et de Recherche Avancée -->
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-body bg-light rounded-3">
            <form action="{{ route('reussites.index') }}" method="GET" class="row g-3 align-items-end">
                
                <!-- Recherche (Texte) -->
                <div class="col-md-4 col-sm-6">
                    <label for="search" class="form-label text-secondary">Recherche Rapide (Nom, CIN...)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search" style="color: #C2185B;"></i></span>
                        <input type="text" name="search" id="search" class="form-control border-start-0" placeholder="Nom, Prénom, CIN..." value="{{ $search ?? '' }}">
                    </div>
                </div>

                <!-- Filtre Statut Financier -->
                <div class="col-md-2 col-sm-6">
                    <label for="status" class="form-label text-secondary">Statut Paiement</label>
                    <select name="status" id="status" class="form-select">
                        <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>Tous les statuts</option>
                        <option value="paid" {{ ($status ?? '') == 'paid' ? 'selected' : '' }}>Payé intégralement</option>
                        <option value="remaining" {{ ($status ?? '') == 'remaining' ? 'selected' : '' }}>Reste à payer</option>
                    </select>
                </div>

                <!-- Date Début -->
                <div class="col-md-2 col-sm-6">
                    <label for="start_date" class="form-label text-secondary">Date Paiement (Début)</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $startDate ?? '' }}">
                </div>

                <!-- Date Fin -->
                <div class="col-md-2 col-sm-6">
                    <label for="end_date" class="form-label text-secondary">Date Paiement (Fin)</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $endDate ?? '' }}">
                </div>

                <!-- Bouton de Filtrage -->
                <div class="col-md-2 col-sm-12 d-grid">
                    <button type="submit" class="btn btn-primary rounded-pill" style="background-color: #D32F2F; border-color: #D32F2F;">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                </div>

                <!-- Champs cachés pour le tri actuel (pour persister l'état) -->
                <input type="hidden" name="sort_by" value="{{ $sortBy ?? 'created_at' }}">
                <input type="hidden" name="sort_direction" value="{{ $sortDirection ?? 'desc' }}">

            </form>
        </div>
    </div>
    
    <!-- Bouton d'ajout et Statistiques (simples) -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('reussites.create') }}" class="btn btn-success rounded-pill" style="background-color: #C2185B; border-color: #C2185B;">
            <i class="fas fa-plus me-1"></i> Nouveau Reçu
        </a>
        <p class="mb-0 text-muted">
            Affichage de {{ $reussites->firstItem() }} à {{ $reussites->lastItem() }} sur {{ $reussites->total() }} reçus.
        </p>
    </div>

    <!-- Table des Résultats -->
    <div class="table-responsive bg-white rounded-3 shadow-lg">
        <table class="table table-hover align-middle mb-0">
            <thead style="background-color: #D32F2F; color: white;">
                <tr>
                    <th scope="col" class="text-center">#</th>
                    <th scope="col" class="sortable-header" data-sort-by="nom">Stagiaire</th>
                    <th scope="col" class="sortable-header" data-sort-by="duree_stage">Durée</th>
                    <th scope="col" class="sortable-header" data-sort-by="montant_paye">Montant Payé</th>
                    <th scope="col">Reste</th>
                    <th scope="col" class="sortable-header" data-sort-by="date_paiement">Date Paiement</th>
                    <th scope="col" class="sortable-header" data-sort-by="created_at">Créé le</th>
                    <th scope="col" class="sortable-header" data-sort-by="created_at">Créé par</th>
                    <th scope="col" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reussites as $reussite)
                    <tr>
                        <td class="text-center">{{ $loop->iteration + $reussites->firstItem() - 1 }}</td>
                        <td class="text-start">
                            <strong>{{ $reussite->prenom }} {{ $reussite->nom }}</strong>
                            <small class="d-block text-muted">{{ $reussite->CIN ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $reussite->duree_stage }}</td>
                        <td><span class="badge bg-success py-2 px-3">{{ number_format($reussite->montant_paye, 2) }} DH</span></td>
                        <td>
                            @if(is_null($reussite->rest) || $reussite->rest <= 0)
                                <span class="badge bg-primary py-2 px-3" style="background-color: #C2185B !important;">Payé</span>
                            @else
                                <span class="badge py-2 px-3" style="background-color: #ef4444;">Reste: {{ number_format($reussite->rest, 2) }} DH</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($reussite->date_paiement)->format('Y-m-d') }}</td>
                        <td>{{ $reussite->created_at->diffForHumans() }}</td>
                         <td>{{ $reussite->user->name ?? 'Utilisateur inconnu' }}</td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="{{ route('reussites.pdf', $reussite) }}" class="btn btn-sm btn-info text-white" title="Télécharger PDF">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="{{ route('reussites.edit', $reussite) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $reussite->id }}" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <form id="delete-form-{{ $reussite->id }}" action="{{ route('reussites.destroy', $reussite) }}" method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i> Aucun reçu trouvé avec les critères de recherche ou de filtre.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Liens de Pagination -->
    <div class="mt-4 d-flex justify-content-center">
        {{ $reussites->links('pagination.custom') }}
    </div>

</div>

<!-- Script de Tri Dynamique et de Confirmation de Suppression -->
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // ======================================
        // Tri Dynamique (Ajout des icônes et des liens)
        // ======================================
        const sortableHeaders = document.querySelectorAll('.sortable-header');
        const currentSortBy = '{{ $sortBy ?? 'created_at' }}';
        const currentDirection = '{{ $sortDirection ?? 'desc' }}';
        const currentQuery = @json(request()->except(['sort_by', 'sort_direction', 'page']));

        sortableHeaders.forEach(header => {
            const column = header.dataset.sortBy;
            let newDirection = 'asc';
            let icon = '<i class="fas fa-sort ml-1"></i>';

            if (currentSortBy === column) {
                newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
                icon = currentDirection === 'asc' 
                    ? '<i class="fas fa-sort-up ml-1 text-primary" style="color: #C2185B !important;"></i>' 
                    : '<i class="fas fa-sort-down ml-1 text-primary" style="color: #C2185B !important;"></i>';
            }

            // Construction de l'URL avec les paramètres existants
            const newQuery = { ...currentQuery, 'sort_by': column, 'sort_direction': newDirection };
            const url = '{{ route('reussites.index') }}?' + new URLSearchParams(newQuery).toString();
            
            // Création du lien
            const link = document.createElement('a');
            link.setAttribute('href', url);
            link.style.color = 'inherit'; // Hérite de la couleur du thead
            link.style.textDecoration = 'none';
            link.style.display = 'flex';
            link.style.alignItems = 'center';
            link.style.justifyContent = 'center';
            
            // Déplacer le texte existant dans le lien
            const text = header.textContent;
            header.innerHTML = '';
            link.textContent = text;
            
            // Ajouter l'icône de tri
            link.innerHTML += icon;
            
            // Remplacer le contenu de l'entête par le lien
            header.appendChild(link);
        });

        // ======================================
        // SweetAlert2 pour Confirmation de Suppression
        // ======================================
        const deleteButtons = document.querySelectorAll('.delete-btn');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reussiteId = this.dataset.id;
                Swal.fire({
                    title: 'Êtes-vous sûr(e) ?',
                    text: "Vous ne pourrez pas revenir en arrière après la suppression de ce reçu !",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F', // Couleur de confirmation
                    cancelButtonColor: '#C2185B', // Couleur d'annulation
                    confirmButtonText: 'Oui, Supprimer !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${reussiteId}`).submit();
                    }
                })
            });
        });
    });
</script>
@endpush


</x-app-layout>