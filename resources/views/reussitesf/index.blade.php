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

      
   </style>    
 



    <div class="col-md-4">
        <form method="GET" action="{{ route('reussitesf.index') }}" class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Rechercher..."  value="{{ $search ?? '' }}" >
            <button type="submit" class="btnn">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
    
    <div class="py-4">
        <div class="container">
            <a href="{{ route('reussitef.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
            <a href="{{ route('reussitesf.create') }}" class="btn btn-success mb-4">
                <i class="fas fa-plus-circle me-2"></i> Ajouter un Reçu de formation
            </a>
            

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Liste des Reçus de formation</h2>
                </div>
                
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                       
                        <th>Prénom</th>
                        <th>Formation</th>
                        <th>Montant Payé</th>
                        <th>Mode de Paiement </th>
                        <th>Date de Paiement</th>
                        <th>Prochaine Paiement</th>
                        <th>Rest</th>
                        <th>CIN</th>
                        <th>Téléphone</th>
                        <th>Gmail</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fomationre as $fomationr)
                        <tr>
                           
                            <td>{{ $fomationr->prenom }}</td>
                            <td>{{ $fomationr->formation }}</td>
                            <td>{{ $fomationr->montant_paye }}</td>
                             <td>{{ $fomationr->mode_paiement }}</td>
                            <td>{{ $fomationr->date_paiement }}</td>
                            <td>{{ $fomationr->prochaine_paiement ?? 'N/A' }}</td>
                            <td>{{ $fomationr->rest }} DH</td>
                            <td>{{ $fomationr->CIN ?? 'N/A' }}</td>
                            <td>{{ $fomationr->tele ?? 'N/A' }}</td>
                            <td>{{ $fomationr->gmail ?? 'N/A' }}</td>
                            <td>{{ $fomationr->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td class="text-center">
                                <a href="{{ route('reussitesf.pdf', $fomationr->id) }}" class="btn btn-info btn-sm me-1">
                                    <i class="fas fa-download"></i>
                                </a>
                                <a href="{{ route('reussitesf.edit', $fomationr->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('reussitesf.destroy', $fomationr->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
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

    

    <div class="d-fl">
        <nav aria-label="Page navigation">
            {{ $fomationre->links('pagination.custom') }}
        </nav>
    </div>
</x-app-layout>
