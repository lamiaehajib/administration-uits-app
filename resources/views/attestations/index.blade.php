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
        <!-- Search Bar -->
        <form method="GET" action="{{ route('attestations.index') }}" class="mb-2">
            <div class="input-group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    class="form-control" 
                    placeholder="Rechercher par nom, CIN ou poste"
                >
                <button type="submit" class="btnn">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- Page Title -->
        <h1 class="gradient-text text-center mb-4">Liste des Attestations de Stage</h1>

        <!-- Create Button -->
        <div class="text-end mb-4">
            <a href="{{ route('attestations.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle"></i> Créer une nouvelle attestation
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>CIN</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Poste</th>
                        <th>Créé par</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attestations as $attestation)
                        <tr>
                            <td>{{ $attestation->stagiaire_name }}</td>
                            <td>{{ $attestation->stagiaire_cin }}</td>
                            <td>{{ $attestation->date_debut }}</td>
                            <td>{{ $attestation->date_fin }}</td>
                            <td>{{ $attestation->poste }}</td>
                            <td>{{ $attestation->user->name ?? 'Utilisateur inconnu' }}</td>
                            <td class="text-center">
                                <a href="{{ route('attestations.edit', $attestation->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <form action="{{ route('attestations.destroy', $attestation->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"  title="Supprimer">
                                        <i class="fas fa-trash-alt"></i> 
                                    </button>
                                </form>
                                <a href="{{ route('attestations.pdf', $attestation->id) }}" class="btn btn-info btn-sm" title="Télécharger">
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
         .gradient-text {
        background: linear-gradient(135deg, #ff0404, #000000);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        font-weight: bold;
    }
   
        .table-responsive {
            background: linear-gradient(135deg, #f9f9f9, #ffffff);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            text-transform: uppercase;
            font-weight: bold;
        }

        .table td, .table th {
            vertical-align: middle;
        }

        .btn-sm {
            font-size: 0.9rem;
        }

        h1 {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }
        .text-center{
            display: flex;
            gap: 20px;
        }
    </style>
</x-app-layout>
