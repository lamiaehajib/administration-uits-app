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
        <a href="{{ route('ucg.corbeille') }}" class="btn btn-danger">
    <i class="fa fa-trash"></i> Corbeille
</a>
        <form method="GET" action="{{ route('ucgs.index') }}" class="mb-4">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    class="form-control" 
                    placeholder="Rechercher par nom ou prénom">
                <button type="submit" class="btnn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <h2 class="text-white text-center py-3 mb-4" style="background: linear-gradient(135deg, #f60404, #000000); border-radius: 8px;">
            Liste des Reçus UCGS
        </h2>

        <div class="text-end mb-4">
            <a href="{{ route('ucgs.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Ajouter un Reçus UCGS 
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark" style="background: linear-gradient(135deg, #f60404, #000000);">
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Reçu Garantie</th>
                        <th>Montant Payé</th>
                        <th>Date de Paiement</th>
                        <th>equipement</th>
                        <th>Détails</th>
                       
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ucgs as $ucg)
                        <tr>
                            <td>{{ $ucg->nom }}</td>
                            <td>{{ $ucg->prenom }}</td>
                            <td>{{ $ucg->recu_garantie }}</td>
                            <td>{{ $ucg->montant_paye }} DH</td>
                            <td>{{ $ucg->date_paiement }}</td>
                            <td>{{ $ucg->equipemen ?? '-' }}</td>
                            <td>{{ $ucg->details ?? '-' }}</td>
                           
                            <td class="text-center">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('ucg.pdf', $ucg->id) }}" class="btn btn-primary btn-sm" title="Télécharger">
                                        <i class="fas fa-file-pdf"></i> 
                                    </a>
                                    <a href="{{ route('ucgs.edit', $ucg->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('ucgs.show', $ucg->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('ucgs.destroy', $ucg->id) }}" method="POST" style="display:inline;">
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

        <div class="d-fl">
            <nav aria-label="Page navigation">
                {{ $ucgs->links('pagination.custom') }}
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
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</x-app-layout>
