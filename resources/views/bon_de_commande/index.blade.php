
<x-app-layout>
    <style>
        /* Custom styles for buttons, dropdowns, table, and icons */
    .btn-primary, .btn-outline-primary, .btn-outline-danger {
        transition: all 0.3s ease;
        border-radius: 10px; /* Slightly rounder edges */
        font-size: 0.9rem;
        padding: 8px 16px; /* Slightly larger padding for better click area */
        font-weight: 500; /* Medium font weight for better readability */
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #0056b3); /* Gradient for primary button */
        border: none;
    }
    
    .btn-primary:hover, .btn-outline-primary:hover, .btn-outline-danger:hover {
        transform: translateY(-2px); /* Subtle lift effect */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15); /* Stronger shadow on hover */
    }
    
    .btn-outline-primary, .btn-outline-danger {
        border-width: 2px; /* Thicker border for outline buttons */
    }
    
    .dropdown-menu {
        border-radius: 10px;
        border: none;
        min-width: 200px; /* Slightly wider dropdown */
        background-color: #ffffff; /* Clean white background */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Softer shadow */
        padding: 10px 0; /* More padding for spacing */
    }
    
    .dropdown-item {
        font-size: 0.9rem;
        color: #333;
        padding: 8px 20px; /* More padding for better clickability */
        transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
        background-color: #f1f3f5; /* Softer hover background */
        color: #007bff;
    }
    
    .dropdown-item i {
        color: #6c757d;
        margin-right: 8px; /* Consistent spacing for icons */
        width: 20px; /* Fixed width for icon alignment */
        text-align: center; /* Center icons */
    }
    
    .btn-sm i {
        font-size: 0.9rem; /* Slightly larger icons for buttons */
    }
    
    .card {
        border-radius: 12px; /* Rounder card corners */
        border: none; /* Remove default border */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); /* Softer, modern shadow */
    }
    
    .card-header {
        background: linear-gradient(135deg, #007bff, #0056b3); /* Gradient for header */
        border-radius: 12px 12px 0 0; /* Rounded top corners */
        padding: 1.5rem; /* More padding for header */
    }
    
    .table {
        border-radius: 8px;
        overflow: hidden; /* Ensure rounded corners for table */
    }
    
    .table th {
        background-color: #f8f9fa; /* Light gray header background */
        font-weight: 600; /* Bolder headers */
        color: #333;
        padding: 12px; /* More padding for headers */
    }
    
    .table td {
        padding: 12px; /* More padding for cells */
        vertical-align: middle; /* Center content vertically */
    }
    
    .table tr:hover {
        background-color: #f1f3f5; /* Subtle hover effect for rows */
    }
    
    .alert {
        border-radius: 8px; /* Rounded alerts */
        margin-bottom: 1.5rem; /* More spacing */
    }
    
    .text-center {
        text-align: center !important;
    }
    
    .align-middle {
        vertical-align: middle !important;
    }
    
    /* Icon-specific styles */
    .fas, .far {
        transition: color 0.2s ease; /* Smooth icon color transition */
    }
    
    .btn:hover .fas, .btn:hover .far {
        color: #fff; /* White icons on button hover */
    }
    </style>
    
        <div class="container mt-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h2 class="mb-0"><i class="fas fa-file-invoice me-2"></i> Liste des Bons de Commande</h2>
                    <a href="{{ route('bon_de_commande.create') }}" class="btn btn-light shadow-sm">
                        <i class="fas fa-plus-circle me-1"></i> Ajouter un Bon de Commande
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><i class="fas fa-tag me-2"></i> Titre</th>
                                <th><i class="fas fa-calendar-alt me-2"></i> Date</th>
                                <th><i class="fas fa-file-download me-2"></i> Fichier</th>
                                <th><i class="fas fa-cog me-2"></i> Actions</th>
                            </tr>
    
                        </thead>
                        <tbody>
                            @forelse($bons as $bon)
                            <tr>
                                <td>{{ $bon->titre }}</td>
                                <td>
                                    @if($bon->date_commande)
                                        {{ \Carbon\Carbon::parse($bon->date_commande)->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted"><i class="fas fa-minus-circle me-1"></i> -</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bon->fichier_path)
                                        <a href="{{ route('bon_de_commande.download', $bon->id) }}" class="text-primary">
                                            <i class="fas fa-download me-1"></i> Télécharger
                                        </a>
                                    @else
                                        <span class="text-muted"><i class="fas fa-file me-1"></i> Aucun fichier</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
         
                                       
                                                <a class="dropdown-item py-2" href="{{ route('factures.create') }}">
                                                    <i class="fas fa-project-diagram me-2"></i> facture de Projet
                                                </a>
                                            
                                   
                                    <a href="{{ route('bon_de_commande.edit', $bon->id) }}" class="btn btn-sm btn-outline-primary shadow-sm ms-2">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <form action="{{ route('bon_de_commande.destroy', $bon->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm ms-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bon de commande ?')">
                                            <i class="fas fa-trash-alt"></i> 
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    <i class="fas fa-folder-open me-2"></i> Aucun bon de commande trouvé.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-app-layout>