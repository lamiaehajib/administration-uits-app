<x-app-layout>
<div class="container">
    <h2>Modifier l'achat</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('achats.update', $achat->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="produit_id" class="form-label">Produit</label>
            <select name="produit_id" id="produit_id" class="form-control">
                @foreach($produits as $produit)
                    <option value="{{ $produit->id }}" {{ $achat->produit_id == $produit->id ? 'selected' : '' }}>
                        {{ $produit->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="quantite" class="form-label">Quantité</label>
            <input type="number" name="quantite" id="quantite" class="form-control" value="{{ $achat->quantite }}" required>
        </div>

        <div class="mb-3">
            <label for="prix_achat" class="form-label">Prix d'achat</label>
            <input type="text" name="prix_achat" id="prix_achat" class="form-control" value="{{ $achat->prix_achat }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
</x-app-layout>