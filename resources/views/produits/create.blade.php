<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-lg rounded">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0">Ajouter un Produit</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('produits.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nom du Produit</label>
                        <input type="text" name="nom" class="form-control form-control-lg border-primary" placeholder="Entrez le nom du produit" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catégorie</label>
                        <select name="category_id" class="form-select form-select-lg border-primary" required>
                            <option value="" disabled selected>-- Sélectionnez une catégorie --</option>
                            @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Prix Vente (€)</label>
                        <input type="number" name="prix_vendu" class="form-control form-control-lg border-primary" placeholder="Entrez le prix de vente" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Quantité en Stock</label>
                        <input type="number" name="quantite_stock" class="form-control form-control-lg border-primary" placeholder="Entrez la quantité disponible" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-plus"></i> Ajouter Produit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
