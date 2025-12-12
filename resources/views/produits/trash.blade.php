<x-app-layout>
    <x-slot name="header">
        <h2 class="hight text-center">
            {{ __('Corbeille : Produits Supprimés') }}
        </h2>
    </x-slot>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                {{-- Messages d'alerte --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-times-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                {{-- Barre de recherche et contrôle --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            
                            {{-- Champ de recherche --}}
                            <form action="{{ route('produits.trash') }}" method="GET" class="d-flex" style="max-width: 300px;">
                                <input type="text" name="search" class="form-control me-2" placeholder="Rechercher par nom ou référence..." value="{{ request('search') }}">
                                <button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-search"></i></button>
                            </form>
                            
                            {{-- Lien de retour à la liste des produits --}}
                            <a href="{{ route('produits.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-box-open me-1"></i> Liste des Produits
                            </a>
                        </div>
                    </div>
                </div>

                @if ($produits->isEmpty())
                    <div class="alert alert-info text-center">
                        <i class="fas fa-trash-restore-alt me-2"></i> Aucun produit supprimé actuellement dans la corbeille.
                    </div>
                @else
                    {{-- Tableau des produits supprimés --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover bg-white">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Référence</th>
                                    <th>Catégorie</th>
                                    <th>Date de Suppression</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produits as $produit)
                                    <tr>
                                        <td>{{ $produit->id }}</td>
                                        <td class="text-start">{{ $produit->nom }}</td>
                                        <td>{{ $produit->reference ?? 'N/A' }}</td>
                                        <td>{{ $produit->category->nom ?? 'Non défini' }}</td>
                                        <td>{{ $produit->deleted_at ? $produit->deleted_at->format('Y-m-d H:i') : 'N/A' }}</td>
                                        <td>
                                            {{-- Bouton Restaurer --}}
                                            @can('produit-trash')
                                                <button class="btn btn-success btn-sm me-1 btn-restore"
                                                        data-id="{{ $produit->id }}" 
                                                        title="Restaurer le produit">
                                                    <i class="fas fa-trash-restore"></i> Restaurer
                                                </button>
                                            @endcan

                                            {{-- Bouton Suppression Définitive --}}
                                            @can('produit-trash')
                                                <button class="btn btn-danger btn-sm btn-force-delete"
                                                        data-id="{{ $produit->id }}" 
                                                        title="Supprimer définitivement de la base de données">
                                                    <i class="fas fa-bomb"></i> Supprimer Déf.
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Liens de Pagination --}}
                    <div class="d-flex justify-content-center">
                        {{ $produits->links('pagination::bootstrap-5') }}
                    </div>

                @endif
            </div>
        </div>
    </div>

    {{-- SweetAlert2 Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ======================================= 
            // Confirmation de Restauration (Restore)
            // =======================================
            $('.btn-restore').on('click', function(e) {
                e.preventDefault();
                const produitId = $(this).data('id');

                Swal.fire({
                    title: 'Êtes-vous sûr de vouloir restaurer ?',
                    text: "Ce produit sera restauré et réapparaîtra dans la liste des produits.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, Restaurer !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Création du formulaire pour l'envoi de la requête POST
                        const form = document.createElement('form');
                        form.action = '{{ url("produits") }}/' + produitId + '/restore';
                        form.method = 'POST';
                        form.style.display = 'none';

                        // Ajout du CSRF Token
                        const token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = '{{ csrf_token() }}';
                        form.appendChild(token);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });

            // ======================================= 
            // Confirmation de Suppression Définitive (Force Delete)
            // =======================================
            $('.btn-force-delete').on('click', function(e) {
                e.preventDefault();
                const produitId = $(this).data('id');

                Swal.fire({
                    title: 'Êtes-vous sûr de la suppression définitive ?',
                    html: "Vous ne pourrez **PAS** récupérer ce produit après la suppression. **Il sera retiré définitivement de la base de données.**",
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, Supprimer Déf. !',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Création du formulaire pour l'envoi de la requête DELETE
                        const form = document.createElement('form');
                        form.action = '{{ url("produits") }}/' + produitId + '/force-delete';
                        form.method = 'POST'; 
                        form.style.display = 'none';

                        // Ajout du CSRF Token et du Method Spoofing
                        const token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = '{{ csrf_token() }}';
                        form.appendChild(token);

                        const method = document.createElement('input');
                        method.type = 'hidden';
                        method.name = '_method';
                        method.value = 'DELETE';
                        form.appendChild(method);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>