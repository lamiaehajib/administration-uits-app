<x-app-layout>
    <div class="container">
            <h1>Liste des Marges</h1>
    
            @if (session('success'))
                <div class="alert alert-success mt-2">{{ session('success') }}</div>
            @endif
    
            @if (session('error'))
                <div class="alert alert-danger mt-2">{{ session('error') }}</div>
            @endif
    
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix Achat</th>
                        <th>Prix Vente</th>
                        <th>Marge</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produits as $produit)
                        @php
                            $achat = $produit->achats()->latest()->first();
                            $vente = $produit->ventes()->latest()->first();
                            $marge = $achat && $vente ? $vente->prix_vendu - $achat->prix_achat : 'N/A';
                        @endphp
                        <tr>
                            <td>{{ $produit->nom }}</td>
                            <td>{{ $achat ? number_format($achat->prix_achat, 2) . ' DH' : 'N/A' }}</td>
                            <td>{{ $vente ? number_format($vente->prix_vendu, 2) . ' DH' : 'N/A' }}</td>
                            <td>{{ is_numeric($marge) ? number_format($marge, 2) . ' DH' : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('marges.calculer', $produit->id) }}" class="btn btn-primary">Calculer</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-app-layout>