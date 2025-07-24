<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="fw-bold text-primary mb-4">
                <i class="fas fa-cart-plus"></i> Ajouter une Vente
            </h2>

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('ventes.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <!-- Sélection de la catégorie -->
                <div class="mb-3">
                    <label for="category_id" class="form-label fw-bold">
                        <i class="fas fa-tags"></i> Catégorie
                    </label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Sélectionnez une catégorie</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Sélection du produit -->
                <div class="mb-3">
                    <label for="produit_id" class="form-label fw-bold">
                        <i class="fas fa-box"></i> Produit
                    </label>
                    <select name="produit_id" id="produit_id" class="form-select" disabled required>
                        <option value="">Sélectionnez un produit</option>
                    </select>
                </div>

                <!-- Quantité vendue -->
                <div class="mb-3">
                    <label for="quantite_vendue" class="form-label fw-bold">
                        <i class="fas fa-sort-numeric-up"></i> Quantité Vendue
                    </label>
                    <input type="number" name="quantite_vendue" id="quantite_vendue" class="form-control" min="1" required>
                </div>

                <!-- Prix unitaire -->
                <div class="mb-3">
                    <label for="prix_vendu" class="form-label fw-bold">
                        <i class="fas fa-dollar-sign"></i> Prix Unitaire
                    </label>
                    <input type="number" name="prix_vendu" id="prix_vendu" class="form-control" step="0.01" min="0" required>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- AJAX pour charger les produits selon la catégorie sélectionnée -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#category_id').change(function () {
                let categoryId = $(this).val();
                let produitSelect = $('#produit_id');

                if (categoryId) {
                    $.ajax({
                        url: "/get-produits-by-category/" + categoryId,
                        type: "GET",
                        success: function (response) {
                            produitSelect.empty().append('<option value="">Sélectionnez un produit</option>');
                            response.forEach(function (produit) {
                                produitSelect.append('<option value="' + produit.id + '">' + produit.nom + 
                    ' (Stock: ' + produit.quantite_stock + ', prix vente: ' + produit.prix_vendu + ')</option>');
            
                            });
                            produitSelect.prop('disabled', false);
                        }
                    });
                } else {
                    produitSelect.empty().append('<option value="">Sélectionnez un produit</option>').prop('disabled', true);
                }
            });
        });
    </script>

    <!-- FontAwesome pour les icônes -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</x-app-layout>
