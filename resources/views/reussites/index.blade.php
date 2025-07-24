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
      
   </style>
    <div class="container my-5">
        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('reussites.index') }}" class="mb-4">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    class="form-control" 
                    placeholder="Rechercher par nom, prénom ou CIN">
                <button type="submit" class="btnn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Titre -->
        <h2 class="text-white text-center py-3 mb-4" style="background: linear-gradient(135deg, #f60404, #000000); border-radius: 8px;">
            Liste des Reçus
        </h2>

        <!-- Bouton d'ajout -->
        <div class="text-end mb-4">
            <a href="{{ route('reussites.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Ajouter une Reçu
            </a>
        </div>

        <!-- Tableau -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark" style="background: linear-gradient(135deg, #f60404, #000000);">
                    <tr>
                        
                        <th>Prénom</th>
                        <th>Durée de Stage</th>
                        <th>Montant Payé</th>
                        <th>Restant</th>
                        <th>Date de Paiement</th>
                        <th>Prochaine Paiement</th>
                        <th>CIN</th>
                        <th>Téléphone</th>
                        <th>Gmail</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reussites as $reussite)
                        <tr>
                            
                            <td>{{ $reussite->prenom }}</td>
                            <td>{{ $reussite->duree_stage }}</td>
                            <td>{{ $reussite->montant_paye }} DH</td>
                            <td>{{ $reussite->rest }} DH</td>
                            <td>{{ $reussite->date_paiement }}</td>
                            <td>{{ $reussite->prochaine_paiement ?? 'N/A' }}</td>
                            <td>{{ $reussite->CIN ?? 'N/A' }}</td>
                            <td>{{ $reussite->tele ?? 'N/A' }}</td>
                            <td>{{ $reussite->gmail ?? 'N/A' }}</td>
                            <td>{{ $reussite->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td class="text-center">
                                <div class="d-flex gap-2">
                                   
                                    <a href="{{ route('reussites.pdf', $reussite->id) }}" class="btn btn-primary btn-sm" title="Télécharger">
                                        <i class="fas fa-file-pdf"></i> 
                                    </a>
                                    <a href="{{ route('reussites.edit', $reussite->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('reussites.destroy', $reussite->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i> 
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        

        <div class="d-fl">
            <nav aria-label="Page navigation">
                {{ $reussites->links('pagination.custom') }}
            </nav>
        </div>
    </div>

    <style>
         .text-center{
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .table-bordered th, .table-bordered td {
            vertical-align: middle;
        }

        /* Boutons et actions */
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</x-app-layout>
