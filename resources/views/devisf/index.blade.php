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
    <div class="container py-5">
       
        
        

        <form method="GET" action="{{ route('devisf.index') }}" class="input-group mb-4">
            <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request()->input('search') }}">
            <button type="submit" class="btnn">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('devisf.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un Devis Formation
            </a>
            @if (session('success'))
                <div class="alert alert-success w-50 text-center">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Liste des Devis</h2>
            </div>
        <table class="table table-striped table-hover">
            <thead class="table-primary">
                <tr>
                    <th>Devis N°</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Total TTC</th>
                    <th>Vide</th>
                    <th>Créé par</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($devisf as $devisItem)
                    <tr>
                        <td>{{ $devisItem->devis_num }}</td>
                        <td>{{ $devisItem->client }}</td>
                        <td>{{ $devisItem->date }}</td>
                        <td>{{ $devisItem->total_ttc }}{{ str_replace('EUR', '€', $devisItem->currency) }}</td>
                        <td>{{ $devisItem->vide }}</td>
                        <td>{{ $devisItem->user->name ?? 'Utilisateur inconnu' }}</td>
                        <td>
                            <a href="{{ route('devisf.show', $devisItem->id) }}" class="btn btn-info btn-sm mx-1" title="Voir">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('devisf.edit', $devisItem->id) }}" class="btn btn-warning btn-sm mx-1" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('devisf.destroy', $devisItem->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mx-1" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            <a href="{{ route('devisf.downloadPDF', ['id' => $devisItem->id]) }}" class="btn btn-primary btn-sm mx-1" title="Télécharger">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                            <a href="{{ route('facturefs.create_from_devisf', $devisItem->id) }}" class="btn btn-primary btn-sm">Ajouter Facture</a>
                            <a href="{{ route('devisf.duplicate', $devisItem->id) }}" class="btn btn-secondary btn-sm" title="Dupliquer">
                                <i class="fas fa-copy"></i> 
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-fl">
            <nav aria-label="Page navigation">
                {{ $devisf->links('pagination.custom') }}
            </nav>
        </div>
    </div>
</x-app-layout>
