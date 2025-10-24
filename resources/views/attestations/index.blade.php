<x-app-layout>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div>
                    <h1 class="hight display-5 mb-2">
                        <i class="fas fa-file-contract me-2"></i>
                        Gestion des Attestations de Stage
                    </h1>
                    <p class="text-muted">Gérez et suivez toutes les attestations de stage de vos stagiaires</p>
                </div>
                <button onclick="openCreateModal()" class="btn btn-gradient mt-3 mt-md-0" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3); transition: all 0.3s;">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nouvelle Attestation
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card stat-card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2">Total Attestations</h6>
                                <h2 class="mb-0 fw-bold">{{ $attestations->total() }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-file-alt fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2">Stagiaires</h6>
                                <h2 class="mb-0 fw-bold">{{ $attestations->unique('stagiaire_cin')->count() }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-users fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card stat-card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #D32F2F, #C2185B); color: white;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-white-50 mb-2">Ce Mois</h6>
                                <h2 class="mb-0 fw-bold">{{ $attestations->where('created_at', '>=', now()->startOfMonth())->count() }}</h2>
                            </div>
                            <div class="stat-icon">
                                <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <form method="GET" action="{{ route('attestations.index') }}">
                <div class="input-group shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <span class="input-group-text bg-white border-0" style="padding-left: 20px;">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control border-0 py-3" placeholder="Rechercher par nom, CIN ou poste..." style="font-size: 15px;">
                    @if($search)
                        <a href="{{ route('attestations.index') }}" class="input-group-text bg-white border-0" style="cursor: pointer;">
                            <i class="fas fa-times text-danger"></i>
                        </a>
                    @endif
                    <button type="submit" class="btn text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border: none; padding: 0 25px;">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-left: 4px solid #28a745; border-radius: 10px;">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Succès!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table Card -->
        <div class="card shadow-sm" style="border-radius: 15px; border: none; overflow: hidden;">
            <div class="card-header bg-primary text-white py-3" style="background: linear-gradient(135deg, #C2185B, #D32F2F) !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-table me-2"></i>
                        Liste des Attestations
                    </h5>
                    <span class="badge bg-white text-danger px-3 py-2">{{ $attestations->total() }} attestation(s)</span>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border: none;">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3">#</th>
                                <th class="text-center py-3">Stagiaire</th>
                                <th class="text-center py-3">CIN</th>
                                <th class="text-center py-3">Poste</th>
                                <th class="text-center py-3">Période</th>
                                <th class="text-center py-3">Durée</th>
                                <th class="text-center py-3">Cachet</th>
                                <th class="text-center py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attestations as $index => $attestation)
                                <tr style="transition: all 0.2s;" onmouseover="this.style.backgroundColor='#fff5f7'" onmouseout="this.style.backgroundColor=''">
                                    <td class="align-middle text-center">
                                        <span class="badge bg-light text-dark fw-bold" style="font-size: 13px;">
                                            {{ $attestations->firstItem() + $index }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="rounded-circle d-flex align-items-center justify-center text-white fw-bold me-2" style="width: 40px; height: 40px; background: linear-gradient(135deg, #C2185B, #ef4444); font-size: 16px; display: flex; align-items: center; justify-content: center;">
                                                {{ strtoupper(substr($attestation->stagiaire_name, 0, 1)) }}
                                            </div>
                                            <div class="text-start">
                                                <strong class="d-block text-dark">{{ $attestation->stagiaire_name }}</strong>
                                                <small class="text-muted">
                                                    <i class="far fa-calendar-alt me-1"></i>
                                                    {{ $attestation->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        <code style="background: #f8f9fa; padding: 6px 12px; border-radius: 8px; color: #333; font-size: 13px;">
                                            {{ $attestation->stagiaire_cin }}
                                        </code>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-info text-white px-3 py-2" style="font-size: 12px; border-radius: 20px;">
                                            <i class="fas fa-briefcase me-1"></i>
                                            {{ $attestation->poste }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="d-flex flex-column">
                                            <small class="text-success fw-bold">
                                                <i class="fas fa-play-circle me-1"></i>
                                                {{ \Carbon\Carbon::parse($attestation->date_debut)->format('d/m/Y') }}
                                            </small>
                                            <small class="text-danger fw-bold mt-1">
                                                <i class="fas fa-stop-circle me-1"></i>
                                                {{ \Carbon\Carbon::parse($attestation->date_fin)->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">
                                        @php
                                            $debut = \Carbon\Carbon::parse($attestation->date_debut);
                                            $fin = \Carbon\Carbon::parse($attestation->date_fin);
                                            $duree = $debut->diffInDays($fin);
                                            $mois = floor($duree / 30);
                                            $jours = $duree % 30;
                                        @endphp
                                        <span class="badge text-white px-3 py-2" style="background: linear-gradient(135deg, #C2185B, #D32F2F); font-size: 12px; border-radius: 20px;">
                                            <i class="far fa-clock me-1"></i>
                                            @if($mois > 0)
                                                {{ $mois }} mois
                                            @endif
                                            @if($jours > 0)
                                                {{ $jours }} jour(s)
                                            @endif
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        @if($attestation->afficher_cachet)
                                            <span class="badge bg-success" style="font-size: 11px; padding: 6px 12px; border-radius: 20px;">
                                                <i class="fas fa-check-circle me-1"></i>Oui
                                            </span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 11px; padding: 6px 12px; border-radius: 20px;">
                                                <i class="fas fa-times-circle me-1"></i>Non
                                            </span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('attestations.pdf', $attestation->id) }}" class="btn btn-sm btn-info" style="border-radius: 8px; padding: 6px 12px;" title="Télécharger PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <button onclick='openEditModal(@json($attestation))' class="btn btn-sm btn-warning" style="border-radius: 8px; padding: 6px 12px;" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('attestations.destroy', $attestation) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette attestation ?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px; padding: 6px 12px;" title="Supprimer">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div>
                                            <i class="fas fa-folder-open text-muted" style="font-size: 64px; opacity: 0.3;"></i>
                                            <h5 class="text-muted mt-3">Aucune attestation trouvée</h5>
                                            <p class="text-muted">Commencez par créer votre première attestation de stage</p>
                                            <button onclick="openCreateModal()" class="btn btn-sm mt-2 text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border-radius: 8px;">
                                                <i class="fas fa-plus me-1"></i>
                                                Créer une attestation
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($attestations->hasPages())
                    <div class="p-3 bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                <strong>{{ $attestations->total() }}</strong> attestations
                            </div>
                            <div>
                                {{ $attestations->links('pagination.custom') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden;">
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                    <h5 class="modal-title text-white" id="createModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>
                        Nouvelle Attestation de Stage
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('attestations.store') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user text-danger me-1"></i>
                                    Nom du Stagiaire *
                                </label>
                                <input type="text" name="stagiaire_name" class="form-control" required style="border-radius: 10px; padding: 12px;" placeholder="Ex: Ahmed BENNANI">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-id-card text-danger me-1"></i>
                                    CIN *
                                </label>
                                <input type="text" name="stagiaire_cin" class="form-control" required style="border-radius: 10px; padding: 12px;" placeholder="Ex: AB123456">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-briefcase text-danger me-1"></i>
                                Poste de Stage *
                            </label>
                            <input type="text" name="poste" class="form-control" required style="border-radius: 10px; padding: 12px;" placeholder="Ex: Développeur Web">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt text-success me-1"></i>
                                    Date Début *
                                </label>
                                <input type="date" name="date_debut" class="form-control" required style="border-radius: 10px; padding: 12px;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-check text-danger me-1"></i>
                                    Date Fin *
                                </label>
                                <input type="date" name="date_fin" class="form-control" required style="border-radius: 10px; padding: 12px;">
                            </div>
                        </div>

                        <div class="alert alert-light border" style="border-radius: 10px; background: linear-gradient(135deg, #fff5f7, #ffe5e9);">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="afficher_cachet" value="1" id="afficher_cachet_create" checked>
                                <label class="form-check-label fw-bold" for="afficher_cachet_create">
                                    <i class="fas fa-stamp text-danger me-1"></i>
                                    Afficher le cachet sur l'attestation
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 10px 20px;">
                            <i class="fas fa-times me-1"></i>
                            Annuler
                        </button>
                        <button type="submit" class="btn text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border: none; border-radius: 10px; padding: 10px 24px;">
                            <i class="fas fa-check me-1"></i>
                            Créer l'Attestation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none; overflow: hidden;">
                <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                    <h5 class="modal-title text-white" id="editModalLabel">
                        <i class="fas fa-edit me-2"></i>
                        Modifier l'Attestation de Stage
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-user text-danger me-1"></i>
                                    Nom du Stagiaire *
                                </label>
                                <input type="text" name="stagiaire_name" id="edit_stagiaire_name" class="form-control" required style="border-radius: 10px; padding: 12px;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-id-card text-danger me-1"></i>
                                    CIN *
                                </label>
                                <input type="text" name="stagiaire_cin" id="edit_stagiaire_cin" class="form-control" required style="border-radius: 10px; padding: 12px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-briefcase text-danger me-1"></i>
                                Poste de Stage *
                            </label>
                            <input type="text" name="poste" id="edit_poste" class="form-control" required style="border-radius: 10px; padding: 12px;">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt text-success me-1"></i>
                                    Date Début *
                                </label>
                                <input type="date" name="date_debut" id="edit_date_debut" class="form-control" required style="border-radius: 10px; padding: 12px;">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-check text-danger me-1"></i>
                                    Date Fin *
                                </label>
                                <input type="date" name="date_fin" id="edit_date_fin" class="form-control" required style="border-radius: 10px; padding: 12px;">
                            </div>
                        </div>

                        <div class="alert alert-light border" style="border-radius: 10px; background: linear-gradient(135deg, #fff5f7, #ffe5e9);">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="afficher_cachet" value="1" id="edit_afficher_cachet">
                                <label class="form-check-label fw-bold" for="edit_afficher_cachet">
                                    <i class="fas fa-stamp text-danger me-1"></i>
                                    Afficher le cachet sur l'attestation
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 10px; padding: 10px 20px;">
                            <i class="fas fa-times me-1"></i>
                            Annuler
                        </button>
                        <button type="submit" class="btn text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border: none; border-radius: 10px; padding: 10px 24px;">
                            <i class="fas fa-save me-1"></i>
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.4) !important;
        }

        .stat-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .table-hover tbody tr:hover {
            background-color: #fff5f7 !important;
        }

        .gap-2 {
            gap: 8px;
        }

        .form-control:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
        }

        .form-check-input:checked {
            background-color: #D32F2F;
            border-color: #D32F2F;
        }

        @media (max-width: 768px) {
            .d-flex.gap-2 {
                flex-direction: column;
                gap: 4px !important;
            }
        }
    </style>

    <script>
    function openCreateModal() {
        // Réinitialiser le formulaire de création
        document.querySelector('#createModal form').reset();
        // Cocher la checkbox par défaut
        document.getElementById('afficher_cachet_create').checked = true;
        
        var modal = new bootstrap.Modal(document.getElementById('createModal'));
        modal.show();
    }

    function openEditModal(attestation) {
        console.log('Attestation reçue:', attestation); // Debug
        
        // Définir l'action du formulaire
        document.getElementById('editForm').action = `/attestations/${attestation.id}`;
        
        // Remplir les champs texte et date
        document.getElementById('edit_stagiaire_name').value = attestation.stagiaire_name || '';
        document.getElementById('edit_stagiaire_cin').value = attestation.stagiaire_cin || '';
        document.getElementById('edit_poste').value = attestation.poste || '';
        document.getElementById('edit_date_debut').value = attestation.date_debut || '';
        document.getElementById('edit_date_fin').value = attestation.date_fin || '';
        
        // Gérer la checkbox afficher_cachet
        const checkbox = document.getElementById('edit_afficher_cachet');
        if (checkbox) {
            checkbox.checked = Boolean(attestation.afficher_cachet == 1 || attestation.afficher_cachet === true);
            console.log('État checkbox:', checkbox.checked); // Debug
        } else {
            console.error('Checkbox edit_afficher_cachet non trouvée!');
        }
        
        // Ouvrir le modal
        var modal = new bootstrap.Modal(document.getElementById('editModal'));
        modal.show();
    }

    // Auto-dismiss des alertes de succès après 5 secondes
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert-success');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
</x-app-layout>