<x-app-layout>
    <style>
        .btnn {
           background-color: #C2185B;
           color: #fff;
           border: none;
           padding: 10px 15px;
           border-radius: 0 20px 20px 0;
           cursor: pointer;
           transition: background-color 0.3s ease;
           justify-content: center !important;
   display: flex;
   position: absolute;
       }
       .form-control {
           width: 100px !important;
           padding: 10px;
           border: 1px solid #ddd;
           border-radius: 20px 0 0 20px;
           font-size: 16px;
           outline: none;
           position: relative;
          
           
       }

       .form-control:focus {
           border-color: #D32F2F;
       }
       .input-group {
    display: flex;
    justify-content: center !important;
    margin-left: 359px;
}
       button.btnn {
   text-align: center;
   margin-left: 455px !important;
   height: 47px;
   font-size: 20px;
}
.text-center{
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .rounded {
    border-radius: var(--bs-border-radius) !important;
    margin-top: 33px;
}

      
   </style>
    <div class="container mt-5">
        <div class="col-md-4">
            <form method="GET" action="{{ route('produits.index') }}" class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom"  value="{{ request('search') }}" >
                <button type="submit" class="btnn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
       
        <div class="card shadow-lg rounded">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0">Liste des Produits</h2>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ route('produits.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Ajouter Produit
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Nom Produit</th>
                                <th>Catégorie</th>
                                <th>Quantité</th>
                                <th>Prix Achat</th>
                                <th>Total Achat</th>
                                <th>Prix Vente</th>
                                <th>Quantité Vendue</th>
                                <th>Prix finale de vente</th>
                                <th>Total Vente</th>
                                <th>En Stock</th>
                               
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($produits as $produit)
                            <tr>
                                <td>{{ $produit->produit_nom }}</td>
                                <td>{{ $produit->categorie_nom }}</td>
                                <td>{{ $produit->quantite }}</td>
                                <td>{{ $produit->prix_achat }}</td>
                                <td>{{ $produit->total_achat }}</td>
                                <td>{{ $produit->prix_vente_unitaire }}</td>
                                <td>{{ $produit->quantite_vendue }}</td>
                                <td>{{ $produit->prix_vendu }}</td>
                                <td>{{ $produit->total_vendu }}</td>
                                <td>{{ $produit->en_stock }}</td>
                                
                                
                                <td>
                                    <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal de confirmation de suppression -->
                            

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="d-fl">
        <nav aria-label="Page navigation">
            {{ $produits->links('pagination.custom') }}
        </nav>
    </div>
</x-app-layout>
