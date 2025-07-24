<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h1 class="mb-0">Ajouter un Achat</h1>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('achats.store') }}" method="POST">
                    @csrf

                    <!-- Sélection de la catégorie -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Catégorie</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sélection du produit -->
                    <div class="mb-3">
                        <label for="produit_id" class="form-label">Produit</label>
                        <select name="produit_id" id="produit_id" class="form-control" disabled>
                            <option value="">Sélectionnez un produit</option>
                        </select>
                        <div id="loading-spinner" class="mt-2 text-primary" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Chargement des produits...
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="quantite" class="form-label">Quantité</label>
                        <input type="number" name="quantite" id="quantite" class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="prix_achat" class="form-label">Prix Achat (DH)</label>
                        <input type="number" name="prix_achat" id="prix_achat" class="form-control" step="0.01" min="0.01" required>
                    </div>

                    <button type="submit" class="btn btn-success" id="submit-btn" disabled>Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <!-- AJAX pour charger les produits -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#category_id').change(function () {
                let categoryId = $(this).val();
                let produitSelect = $('#produit_id');
                let loadingSpinner = $('#loading-spinner');
                let submitBtn = $('#submit-btn');

                produitSelect.prop('disabled', true);
                submitBtn.prop('disabled', true);
                produitSelect.empty().append('<option value="">Sélectionnez un produit</option>');

                if (categoryId) {
                    loadingSpinner.show(); // Afficher le spinner

                    $.ajax({
                        url: "/get-produits-by-category/" + categoryId,
                        type: "GET",
                        success: function (response) {
                            produitSelect.empty().append('<option value="">Sélectionnez un produit</option>');
                            response.forEach(function (produit) {
                                produitSelect.append('<option value="' + produit.id + '">' + produit.nom + '</option>');
                            });
                            produitSelect.prop('disabled', false);
                        },
                        error: function () {
                            alert("Erreur lors du chargement des produits !");
                        },
                        complete: function () {
                            loadingSpinner.hide(); // Cacher le spinner
                        }
                    });
                }
            });

            $('#produit_id').change(function () {
                $('#submit-btn').prop('disabled', !$(this).val()); // Activer le bouton si un produit est sélectionné
            });
        });
    </script>
</x-app-layout>
