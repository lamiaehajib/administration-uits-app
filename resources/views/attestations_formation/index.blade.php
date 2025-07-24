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
        <!-- Search Form -->
        <form method="GET" action="{{ route('attestations_formation.index') }}" class="mb-4">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    class="form-control" 
                    placeholder="Rechercher par formation, nom ou CIN"
                >
                <button type="submit" class="btnn">
                    <i class="fas fa-search"></i> 
                </button>
            </div>
        </form>

        <!-- Page Title -->
        <h1 class="gradient-text text-center mb-4">Liste des Attestations de Formation</h1>

        <!-- Create Button -->
        <div class="text-end mb-4">
            <a href="{{ route('attestations_formation.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Créer une nouvelle attestation de formation
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive bg-light p-4 rounded">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nom de la Formation</th>
                        <th>Nom de la Personne</th>
                        <th>CIN</th>
                        <th>Numéro de Série</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attestations as $attestation)
                        <tr>
                            <td>{{ $attestation->formation_name }}</td>
                            <td>{{ $attestation->personne_name }}</td>
                            <td>{{ $attestation->cin }}</td>
                            <td>{{ $attestation->numero_de_serie }}</td>
                            <td>{{ $attestation->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td class="text-center">
                                <a href="{{ route('attestations_formation.edit', $attestation->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <form action="{{ route('attestations_formation.destroy', $attestation->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                        <i class="fas fa-trash-alt"></i> 
                                    </button>
                                </form>
                                <a href="{{ route('attestations_formation.pdf', $attestation->id) }}" class="btn btn-info btn-sm" title="Télécharger">
                                    <i class="fas fa-file-pdf"></i> 
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
                {{ $attestations->links('pagination.custom') }}
            </nav>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
          .text-center{
            display: flex;
            gap: 20px;
        }
        .gradient-text {
        background: linear-gradient(135deg, #ff0404, #000000);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        font-weight: bold;
    }

        .table-responsive {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        h1 {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(211, 47, 47, 0.1);
        }

        .btn-sm {
            font-size: 0.9rem;
        }
    </style>
</x-app-layout>
