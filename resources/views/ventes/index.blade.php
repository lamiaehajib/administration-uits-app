<x-app-layout>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-primary">📊 Liste des Ventes</h1>
            <a href="{{ route('ventes.create') }}" class="btn btn-success shadow">
                <i class="fas fa-plus"></i> Ajouter une vente
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive shadow-lg p-3 bg-white rounded">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                       
                        <th>Produit</th>
                        <th>Quantité Vendue</th>
                        <th>Prix Unitaire</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Bénéfice</th>
                        <th>action</th>
                    </tr>
                </thead>
               
                <tbody>
                    @foreach ($ventes as $vente)
                        <tr>
                            
                            <td class="fw-bold text-primary">{{ $vente->produit->nom }}</td>
                            <td>{{ $vente->quantite_vendue }}</td>
                            <td><span class="badge bg-info">{{ number_format($vente->prix_vendu, 2) }} DH</span></td>
                            <td><span class="badge bg-success">{{ number_format($vente->total_vendu, 2) }} DH</span></td>
                            <td class="text-muted">{{ $vente->created_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-warning">{{ number_format($vente->marge, 2) }} DH</span></td>
                            <td>
                                <a href="{{ route('ventes.edit', $vente->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ventes.destroy', $vente->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet achat ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> 
                                    </button>
                                </form>
                               
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</x-app-layout>
