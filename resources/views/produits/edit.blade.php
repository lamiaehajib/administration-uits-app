<x-app-layout>
    <div class="container">
        <h1>Modifier le produit : {{ $produit->nom }}</h1>
    
        <!-- Affichage des erreurs de validation -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <!-- Formulaire pour modifier un produit -->
        <form action="{{ route('produits.update', $produit->id) }}" method="POST">
            @csrf
            @method('PUT')
    
            <!-- Champ pour le nom du produit -->
            <div class="form-group">
                <label for="nom">Nom du produit</label>
                <input type="text" name="nom" id="nom" class="form-control" value="{{ old('nom', $produit->nom) }}" required>
            </div>
    
            <!-- Champ pour la catégorie -->
            <div class="form-group">
                <label for="category_id">Catégorie</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            @if($category->id == old('category_id', $produit->category_id)) selected @endif>
                            {{ $category->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
    
            <!-- Champ pour le prix de vente -->
            <div class="form-group">
                <label for="prix_vendu">Prix de vente</label>
                <input type="number" name="prix_vendu" id="prix_vendu" class="form-control" value="{{ old('prix_vendu', $produit->prix_vendu) }}">
            </div>
    
            <!-- Champ pour la quantité en stock -->
            <div class="form-group">
                <label for="quantite_stock">Quantité en stock</label>
                <input type="number" name="quantite_stock" id="quantite_stock" class="form-control" value="{{ old('quantite_stock', $produit->quantite_stock) }}" required>
            </div>
    
            <!-- Bouton de soumission -->
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Mettre à jour le produit</button>
            </div>
        </form>
    
        <!-- Lien pour revenir à la liste des produits -->
        <a href="{{ route('produits.index') }}" class="btn btn-secondary">Retour à la liste des produits</a>
    </div>
</x-app-layout>