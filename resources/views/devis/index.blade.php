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
           justify-content: center;
       }
       button.btnn {
   text-align: center;
   margin-left: 455px !important;
   height: 47px;
   font-size: 20px;
}
input.form-control {
    max-width: 409px !important;
}

input.form-control {
    max-height: 47px;
}
.text-center{
            display: flex;
            gap: 20px;
             justify-content: center;
        }
      
   </style>


    <div class="container mt-5">
        <form method="GET" action="{{ route('devis.index') }}" class="d-flex justify-content-between mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                <button type="submit" class="btnn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            
        </form>
      

        
        <!-- Success Alert -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
<a href="{{ route('devis.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
        <!-- Search Form -->
        <div class="text-end mb-4">
        <a href="{{ route('devis.create') }}" class="btn btn-primary ms-3"><i class="fas fa-plus-circle"></i> Ajouter un Devis</a>
        </div>


        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Liste des Devis</h2>
            </div>
        <!-- Table of Devis -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Devis N°</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total TTC</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($devis as $devisItem)
                        <tr>
                            <td>{{ $devisItem->devis_num }}</td>
                            <td>{{ $devisItem->client }}</td>
                            <td>{{ $devisItem->date }}</td>
                            <td>{{ $devisItem->total_ttc }} MAD</td>
                            <td>{{ $devisItem->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td  class="text-center">
                               
                                <a href="{{ route('devis.show', $devisItem->id) }}" class="btn btn-info btn-sm" title="Voir">
                                    <i class="fas fa-eye"></i> 
                                </a>
                                <a href="{{ route('devis.edit', $devisItem->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('devis.destroy', $devisItem->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                                <a href="{{ route('devis.downloadPDF', ['id' => $devisItem->id]) }}" class="btn btn-primary btn-sm" title="Télécharger">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                                <a href="{{ route('factures.create_from_devis', $devisItem->id) }}" class="btn btn-primary">Ajouter Facture</a>
                                <a href="{{ route('devis.duplicate', $devisItem->id) }}" class="btn btn-secondary btn-sm" title="Dupliquer">
                                    <i class="fas fa-copy"></i> Dupliquer
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination (if enabled) -->
        {{-- <div class="d-flex justify-content-center">
            {{ $devis->appends(['search' => request('search')])->links() }}
        </div> --}}


        <div class="d-fl">
            <nav aria-label="Page navigation">
                {{ $devis->links('pagination.custom') }}
            </nav>
        </div>
    </div>
</x-app-layout>
