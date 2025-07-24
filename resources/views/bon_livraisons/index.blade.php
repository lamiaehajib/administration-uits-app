<x-app-layout>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Custom Styles */
        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 10px 10px 0 0;
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }

        .table td {
            vertical-align: middle;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.875rem;
            border-radius: 5px;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .input-group input {
            border-radius: 5px 0 0 5px;
        }

        .input-group button {
            border-radius: 0 5px 5px 0;
        }

        .pagination .page-link {
            border-radius: 5px;
            margin: 0 3px;
            color: #007bff;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
        }
    </style>

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Liste des Bons de Livraison</h2>
                <a href="{{ route('bon_livraisons.create') }}" class="btn btn-light"><i class="fas fa-plus me-2"></i>Créer un Nouveau Bon</a>
            </div>
            <div class="card-body">
                <!-- Search Form -->
                <form method="GET" action="{{ route('bon_livraisons.index') }}" class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Rechercher par numéro ou client" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-2"></i></button>
                    </div>
                </form>

                <!-- Delivery Notes Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-1"></i>N° Bon</th>
                                <th><i class="fas fa-tag me-1"></i>Titre</th>
                                <th><i class="fas fa-user me-1"></i>Client</th>
                                <th><i class="fas fa-calendar-alt me-1"></i>Date</th>
                                <th><i class="fas fa-money-bill me-1"></i>Total HT</th>
                                <th><i class="fas fa-money-bill-wave me-1"></i>Total TTC</th>
                                <th><i class="fas fa-cog me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bonLivraisons as $bonLivraison)
                                <tr>
                                    <td>{{ $bonLivraison->bon_num }}</td>
                                    <td>{{ $bonLivraison->titre }}</td>
                                    <td>{{ $bonLivraison->client }}</td>
                                    <td>{{ $bonLivraison->date->format('d/m/Y') }}</td>
                                    <td>{{ number_format($bonLivraison->total_ht, 2) }}</td>
                                    <td>{{ number_format($bonLivraison->total_ttc, 2) }}</td>
                                    <td>
                                        <a href="{{ route('bon_livraisons.show', $bonLivraison->id) }}" class="btn btn-sm btn-info" title="Voir"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('bon_livraisons.edit', $bonLivraison->id) }}" class="btn btn-sm btn-primary" title="Modifier"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('bon_livraisons.destroy', $bonLivraison->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce bon de livraison ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer"><i class="fas fa-trash"></i></button>
                                        </form>
                                        <a href="{{ route('bon_livraisons.download', $bonLivraison->id) }}" class="btn btn-sm btn-success" title="Télécharger PDF"><i class="fas fa-file-pdf"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center"><i class="fas fa-exclamation-circle me-2"></i>Aucun bon de livraison trouvé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                
            </div>
        </div>
    </div>
</x-app-layout>