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
    <div class="container my-4">


        <form method="GET" action="{{ route('factures.index') }}" class="input-group mb-4">
            <input type="text" name="search" class="form-control" placeholder="Rechercher..."  value="{{ $search ?? '' }}" >
            <button type="submit" class="btnn">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <!-- En-tête -->
     


        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('factures.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un facture
            </a>
            @if (session('success'))
                <div class="alert alert-success w-50 text-center">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Formulaire de recherche -->
    


       
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Liste des Factures</h2>
            </div>
        <!-- Table des factures -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle shadow">
                <thead class="table-primary">
                    <tr>
                        <th>Facture N°</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Total TTC</th>
                        <th>Créé par</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($factures as $factureItem)
                        <tr>
                            <td>{{ $factureItem->facture_num }}</td>
                            <td>{{ $factureItem->client }}</td>
                            <td>{{ $factureItem->date }}</td>
                            <td>{{ number_format($factureItem->total_ttc, 2) }} MAD</td>
                            <td>{{ $factureItem->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td class="text-center">
                                <a href="{{ route('factures.show', $factureItem->id) }}" class="btn btn-info btn-sm" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('factures.edit', $factureItem->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                             

                                <form action="{{ route('factures.destroy', $factureItem->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <a href="{{ route('factures.downloadPDF', ['id' => $factureItem->id]) }}" class="btn btn-primary btn-sm" title="Télécharger PDF">
                                    <i class="fas fa-file-pdf"></i>

                                </a>
                                
                                <a href="{{ route('factures.duplicate', $factureItem->id) }}" class="btn btn-secondary btn-sm" title="Dupliquer">
                                    <i class="fas fa-copy"></i> Dupliquer
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        

        <div class="d-fl">
            <nav aria-label="Page navigation">
                {{ $factures->links('pagination.custom') }}
            </nav>
        </div>
    </div>
</x-app-layout>
