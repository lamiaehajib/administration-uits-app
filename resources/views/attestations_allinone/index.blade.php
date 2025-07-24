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
    <div class="container py-4">
        <form method="GET" action="{{ route('attestations_allinone.index') }}" class="mb-4">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    class="form-control" 
                    placeholder="Rechercher par nom ou CIN"
                >
                <button type="submit" class="btnn"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <h1 class="gradient-text text-center mb-4">Liste des Attestations de Formation ALL IN ONE</h1>

        <div class="text-end mb-3">
            <a class="btn btn-danger btn-gradient" href="{{ route('attestations_allinone.create') }}">
                Créer une nouvelle attestation de ALL IN ONE
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle shadow">
                <thead class="table-dark">
                    <tr>
                        <th>Nom de la Personne</th>
                        <th>Numéro de Série</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attestation as $attestatio)
                        <tr>
                            <td>{{ $attestatio->personne_name }}</td>
                            <td>{{ $attestatio->numero_de_serie }}</td>
                            <td>{{ $attestatio->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td class="text-center">
                                <a href="{{ route('attestations_allinone.edit', $attestatio->id) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <form action="{{ route('attestations_allinone.destroy', $attestatio->id) }}" method="POST" class="d-inline" title="Supprimer">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i> </button>
                                </form>
                                <a href="{{ route('attestations_allinone.pdf', $attestatio->id) }}" class="btn btn-sm btn-secondary" title="Télécharger">
                                    <i class="fas fa-file-pdf"></i> 
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

     

        <div class="d-fl">
            <nav aria-label="Page navigation">
                {{ $attestation->links('pagination.custom') }}
            </nav>
        </div>
    </div>

    <style>
  .text-center{
            display: flex;
            gap: 20px;
            justify-content: center;
        }
        .gradient-text {
            background: linear-gradient(135deg, #f60404, #000000);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn-gradient {
            background: linear-gradient(135deg, #f60404, #000000);
            border: none;
            color: #fff;
            transition: all 0.3s ease-in-out;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #000000, #f60404);
            color: #fff;
        }

        .table-hover tbody tr:hover {
            background: rgba(0, 0, 0, 0.05);
        }
    </style>
</x-app-layout>
