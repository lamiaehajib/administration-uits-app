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

      
   </style> 
    <div class="container mt-5">
        <div class="col-md-4">
            <form method="GET" action="{{ route('categories.index') }}" class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher par nom"  value="{{ request('search') }}" >
                <button type="submit" class="btnn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="card shadow-lg rounded">

            
           
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Liste des Catégories</h2>
                <a href="{{ route('categories.create') }}" class="btn btn-light">
                    <i class="fas fa-plus"></i> Ajouter une catégorie
                </a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            
                            <th>Nom</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                
                                <td>{{ $category->nom }}</td>
                                <td>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Confirmer la suppression ?')">
                                            <i class="fas fa-trash-alt"></i> 
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
    <div class="d-fl">
        <nav aria-label="Page navigation">
            {{ $categories->links('pagination.custom') }}
        </nav>
    </div>
</x-app-layout>
