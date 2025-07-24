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

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    margin-top: 30px !important
}

      
   </style> 
    <div class="container mt-5">
        <div class="col-md-4">
            <form method="GET" action="{{ route('achats.index') }}" class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par total achat"  value="{{ request('search') }}" >
                <button type="submit" class="btnn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h1 class="mb-0">Liste des Achats</h1>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="mb-3 text-end">
                    <a href="{{ route('achats.create') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-plus"></i> Ajouter un Achat
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Produit</th>
                                <th>Quantité</th>
                                <th>Prix Achat</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($achats as $achat)
                                <tr>
                                    <td>{{ $achat->produit->nom }}</td>
                                    <td>{{ $achat->quantite }}</td>
                                    <td>{{ number_format($achat->prix_achat, 2) }} DH</td>
                                    <td>{{ number_format($achat->total_achat, 2) }} DH</td>
                                    <td>{{ $achat->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('achats.edit', $achat->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('achats.destroy', $achat->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet achat ?');">
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
        </div>
    </div>
    <div class="d-fl">
        <nav aria-label="Page navigation">
            {{ $achats->links('pagination.custom') }}
        </nav>
    </div>
</x-app-layout>
