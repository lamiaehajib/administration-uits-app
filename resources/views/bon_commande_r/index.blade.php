<x-app-layout>
    <style>
        /* General Container Styling */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 15px;
        }

        /* Search Bar Styling */
        .input-group {
            max-width: 600px;
            margin: 0 auto 2rem;
            position: relative;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 50px 0 0 50px;
            padding: 12px 20px;
            font-size: 1.1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            height: 50px;
        }

        .form-control:focus {
            border-color:rgb(194, 24, 24);
            box-shadow: 0 0 8px rgba(194, 24, 91, 0.3);
            outline: none;
        }

        .btn-search {
            background: linear-gradient(45deg,rgb(194, 24, 24), #D81B60);
            color: white;
            border: none;
            border-radius: 0 50px 50px 0;
            padding: 0 20px;
            font-size: 1.2rem;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(194, 24, 91, 0.4);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(45deg,rgb(179, 23, 23),rgb(15, 0, 6));
            color: white;
            padding: 1.5rem;
            border-radius: 15px 15px 0 0;
        }

        .card-header h2 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 600;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
            border-radius: 0 0 15px 15px;
            overflow: hidden;
        }

        .table th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 1rem;
            text-transform: uppercase;
            font-size: 0.95rem;
        }

        .table td {
            padding: 1rem;
            vertical-align: middle;
            font-size: 1rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
            transition: background-color 0.2s ease;
        }

        /* Action Buttons */
        .btn-action {
            padding: 8px 12px;
            margin: 0 4px;
            font-size: 0.9rem;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .btn-create {
            background: linear-gradient(45deg,rgb(194, 24, 24), #D81B60);
            border: none;
            padding: 10px 20px;
            font-size: 1.1rem;
            border-radius: 10px;
            color: white;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(194, 24, 91, 0.4);
        }

        /* Success Message */
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-radius: 10px;
            padding: 1rem;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
            margin-top: 2rem;
        }

        .page-link {
            border-radius: 8px;
            margin: 0 5px;
            color: #C2185B;
            border: 1px solid #e0e0e0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .page-link:hover {
            background-color: #C2185B;
            color: white;
            border-color: #C2185B;
        }

        .page-item.active .page-link {
            background-color: #C2185B;
            border-color: #C2185B;
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .input-group {
                max-width: 100%;
            }

            .btn-action {
                margin: 5px 2px;
                padding: 6px 10px;
            }

            .table td, .table th {
                font-size: 0.9rem;
                padding: 0.8rem;
            }

            .card-header h2 {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="container my-4">
        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Search Form -->
        <form method="GET" action="{{ route('bon_commande_r.index') }}" class="input-group mb-4">
            <input type="text" name="search" class="form-control" placeholder="Rechercher par numéro, prestataire..." value="{{ request('search') ?? '' }}">
            <button type="submit" class="btn-search">
                <i class="fas fa-search"></i>
            </button>
        </form>

        <!-- Header with Create Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('bon_commande_r.create') }}" class="btn btn-create">
                <i class="fas fa-plus"></i> Créer un Bon de Commande
            </a>
        </div>

        <!-- Bon Commande Table -->
        <div class="card">
            <div class="card-header">
                <h2>Liste des Bons de Commande</h2>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Bon N°</th>
                            <th>Prestataire</th>
                            <th>Date</th>
                            <th>Total TTC</th>
                            <th>Créé par</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bonCommandes as $bonCommande)
                            <tr>
                                <td>{{ $bonCommande->bon_num ?? '-' }}</td>
                                <td>{{ $bonCommande->prestataire ?? '-' }}</td>
                                <td>{{ $bonCommande->date ? \Carbon\Carbon::parse($bonCommande->date)->format('d-m-Y') : '-' }}</td>
                                <td>{{ $bonCommande->total_ttc ? number_format($bonCommande->total_ttc, 2) : '0.00' }} {{ $bonCommande->currency == 'EUR' ? '€' : 'MAD' }}</td>
                                <td>{{ $bonCommande->user->name ?? 'Utilisateur inconnu' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('bon_commande_r.show', $bonCommande->id) }}" class="btn btn-info btn-action btn-sm" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bon_commande_r.edit', $bonCommande->id) }}" class="btn btn-warning btn-action btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('bon_commande_r.destroy', $bonCommande->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-action btn-sm" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce bon de commande ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('bon_commande_r.pdf', $bonCommande->id) }}" class="btn btn-primary btn-action btn-sm" title="Télécharger PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucun bon de commande trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $bonCommandes->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    </div>
</x-app-layout>