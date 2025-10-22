<x-app-layout>
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="page-title">
                        <i class="fas fa-file-invoice me-2"></i>
                        Gestion des <span class="hight">Bons de Commande</span>
                    </h3>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-gradient" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-plus-circle me-2"></i>Nouveau Bon
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card stats-card-primary">
                    <div class="stats-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stats-content">
                        <h4>{{ $stats['total'] }}</h4>
                        <p>Total Bons</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card stats-card-success">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stats-content">
                        <h4>{{ $stats['ce_mois'] }}</h4>
                        <p>Ce Mois</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card stats-card-danger">
                    <div class="stats-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="stats-content">
                        <h4>{{ $stats['pdf_count'] }}</h4>
                        <p>Fichiers PDF</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card stats-card-warning">
                    <div class="stats-icon">
                        <i class="fas fa-file-excel"></i>
                    </div>
                    <div class="stats-content">
                        <h4>{{ $stats['excel_count'] }}</h4>
                        <p>Fichiers Excel</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('bon_de_commande.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="fas fa-search me-1"></i>Rechercher
                            </label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Titre, date..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>Date Début
                            </label>
                            <input type="date" name="date_debut" class="form-control" 
                                   value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-calendar-alt me-1"></i>Date Fin
                            </label>
                            <input type="date" name="date_fin" class="form-control" 
                                   value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">
                                <i class="fas fa-file me-1"></i>Type Fichier
                            </label>
                            <select name="type_fichier" class="form-select">
                                <option value="">Tous les types</option>
                                <option value="pdf" {{ request('type_fichier') == 'pdf' ? 'selected' : '' }}>PDF</option>
                                <option value="xlsx" {{ request('type_fichier') == 'xlsx' ? 'selected' : '' }}>Excel</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-filter me-1"></i>Filtrer
                            </button>
                            <a href="{{ route('bon_de_commande.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo me-1"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>
                                    <a href="{{ route('bon_de_commande.index', array_merge(request()->all(), ['sort' => 'titre', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                       class="text-white text-decoration-none">
                                        Titre <i class="fas fa-sort"></i>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ route('bon_de_commande.index', array_merge(request()->all(), ['sort' => 'date_commande', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}" 
                                       class="text-white text-decoration-none">
                                        Date Commande <i class="fas fa-sort"></i>
                                    </a>
                                </th>
                                <th>Fichier</th>
                                <th>Type</th>
                                <th>Date Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bons as $bon)
                                <tr>
                                    <td>
                                        <strong>{{ $bon->titre }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ $bon->date_commande ? \Carbon\Carbon::parse($bon->date_commande)->format('d/m/Y') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($bon->fichier_path)
                                            <i class="fas fa-file text-success me-1"></i>
                                            {{ basename($bon->fichier_path) }}
                                        @else
                                            <span class="text-muted">Aucun fichier</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $extension = pathinfo($bon->fichier_path, PATHINFO_EXTENSION);
                                        @endphp
                                        @if($extension == 'pdf')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-file-pdf me-1"></i>PDF
                                            </span>
                                        @elseif(in_array($extension, ['xls', 'xlsx', 'csv']))
                                            <span class="badge bg-success">
                                                <i class="fas fa-file-excel me-1"></i>Excel
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Autre</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $bon->created_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('bon_de_commande.download', $bon) }}" 
                                               class="btn btn-sm btn-outline-success" 
                                               title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary edit-btn" 
                                                    data-id="{{ $bon->id }}"
                                                    data-titre="{{ $bon->titre }}"
                                                    data-date="{{ $bon->date_commande }}"
                                                    title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-btn" 
                                                    data-id="{{ $bon->id }}"
                                                    data-titre="{{ $bon->titre }}"
                                                    title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Aucun bon de commande trouvé</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        Affichage de {{ $bons->firstItem() ?? 0 }} à {{ $bons->lastItem() ?? 0 }} sur {{ $bons->total() }} résultats
                    </div>
                    <div>
                        {{ $bons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary">
                    <h5 class="modal-title text-white" id="createModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Nouveau Bon de Commande
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('bon_de_commande.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titre" class="form-label">
                                <i class="fas fa-heading me-1 text-danger"></i>Titre *
                            </label>
                            <input type="text" class="form-control" id="titre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="date_commande" class="form-label">
                                <i class="fas fa-calendar me-1 text-danger"></i>Date de Commande
                            </label>
                            <input type="date" class="form-control" id="date_commande" name="date_commande">
                        </div>
                        <div class="mb-3">
                            <label for="fichier" class="form-label">
                                <i class="fas fa-file-upload me-1 text-danger"></i>Fichier (PDF, Excel) *
                            </label>
                            <input type="file" class="form-control" id="fichier" name="fichier" 
                                   accept=".pdf,.csv,.xls,.xlsx" required>
                            <small class="text-muted">Max: 10MB - Formats: PDF, CSV, XLS, XLSX</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-save me-1"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary">
                    <h5 class="modal-title text-white" id="editModalLabel">
                        <i class="fas fa-edit me-2"></i>Modifier Bon de Commande
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_titre" class="form-label">
                                <i class="fas fa-heading me-1 text-danger"></i>Titre *
                            </label>
                            <input type="text" class="form-control" id="edit_titre" name="titre" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_date_commande" class="form-label">
                                <i class="fas fa-calendar me-1 text-danger"></i>Date de Commande
                            </label>
                            <input type="date" class="form-control" id="edit_date_commande" name="date_commande">
                        </div>
                        <div class="mb-3">
                            <label for="edit_fichier" class="form-label">
                                <i class="fas fa-file-upload me-1 text-danger"></i>Nouveau Fichier (optionnel)
                            </label>
                            <input type="file" class="form-control" id="edit_fichier" name="fichier" 
                                   accept=".pdf,.csv,.xls,.xlsx">
                            <small class="text-muted">Laissez vide pour conserver le fichier actuel</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Annuler
                        </button>
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-save me-1"></i>Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Styles -->
    <style>
        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            padding: 25px;
            border-radius: 16px;
            border-left: 5px solid #C2185B;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .page-title {
            font-size: 2rem;
            color: #2c3e50;
            font-weight: 800;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
        }

        .page-title i {
            color: #C2185B;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* Gradient Button */
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F, #ef4444);
            background-size: 200% 200%;
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px rgba(194, 24, 91, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.2);
            transition: all 0.4s ease;
        }

        .btn-gradient:hover::before {
            left: 100%;
        }

        .btn-gradient:hover {
            background-position: 100% 0;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(211, 47, 47, 0.4);
        }

        /* Stats Cards Enhanced */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(194, 24, 91, 0.05) 0%, transparent 70%);
            transition: all 0.6s ease;
            opacity: 0;
        }

        .stats-card:hover::before {
            opacity: 1;
            top: -25%;
            right: -25%;
        }

        .stats-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 12px 35px rgba(0,0,0,0.15);
            border-color: rgba(194, 24, 91, 0.3);
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .stats-card:hover .stats-icon {
            transform: rotate(10deg) scale(1.1);
        }

        .stats-card-primary .stats-icon {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .stats-card-success .stats-icon {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .stats-card-danger .stats-icon {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .stats-card-warning .stats-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stats-content {
            position: relative;
            z-index: 1;
        }

        .stats-content h4 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-content p {
            margin: 5px 0 0;
            color: #6c757d;
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .card-body {
            padding: 30px;
        }

        /* Form Styles */
        .form-label {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        .form-label i {
            color: #C2185B;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 18px;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 0.25rem rgba(194, 24, 91, 0.15);
            transform: translateY(-2px);
        }

        /* Table Enhanced */
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            padding: 18px 15px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table thead th a {
            transition: all 0.2s ease;
        }

        .table thead th a:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.03), rgba(211, 47, 47, 0.03));
            transform: scale(1.005);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        /* Button Group */
        .btn-group .btn {
            border-radius: 8px;
            margin: 0 3px;
            padding: 8px 12px;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .btn-group .btn:hover {
            transform: translateY(-3px) scale(1.1);
        }

        .btn-outline-success {
            border: 2px solid #22c55e;
            color: #22c55e;
        }

        .btn-outline-success:hover {
            background: linear-gradient(135deg, #22c55e, #16a34a);
            border-color: #22c55e;
            color: white;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid #C2185B;
            color: #C2185B;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: #C2185B;
            color: white;
            box-shadow: 0 4px 12px rgba(194, 24, 91, 0.3);
        }

        .btn-outline-danger {
            border: 2px solid #ef4444;
            color: #ef4444;
        }

        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-color: #ef4444;
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }

        .modal-header.bg-gradient-primary {
            background: linear-gradient(135deg, #C2185B, #D32F2F, #ef4444);
            padding: 25px 30px;
            border: none;
        }

        .modal-title {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .modal-body {
            padding: 30px;
        }

        .modal-footer {
            padding: 20px 30px;
            border-top: 2px solid #f0f0f0;
        }

        /* Badge Styles */
        .badge {
            padding: 8px 14px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .badge i {
            margin-right: 4px;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #D32F2F, #ef4444);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 700;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }

        /* Empty State */
        .table tbody td.text-center i.fa-inbox {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        /* Loading Animation */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }

        /* Pagination */
        .pagination .page-link {
            color: #C2185B;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            margin: 0 3px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: #C2185B;
            transform: translateY(-2px);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: #C2185B;
        }
    </style>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Edit Button Click
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const titre = $(this).data('titre');
                const date = $(this).data('date');
                
                $('#edit_titre').val(titre);
                $('#edit_date_commande').val(date);
                $('#editForm').attr('action', `/bon_de_commande/${id}`);
                
                $('#editModal').modal('show');
            });

            // Delete Button Click
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');
                const titre = $(this).data('titre');
                
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: `Voulez-vous vraiment supprimer "${titre}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = $('<form>', {
                            'method': 'POST',
                            'action': `/bon_de_commande/${id}`
                        });
                        
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_token',
                            'value': '{{ csrf_token() }}'
                        }));
                        
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': '_method',
                            'value': 'DELETE'
                        }));
                        
                        $('body').append(form);
                        form.submit();
                    }
                });
            });

            // Success/Error Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
</x-app-layout>