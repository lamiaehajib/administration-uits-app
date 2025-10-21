<x-app-layout>
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div>
                    <h1 class="hight display-5 mb-2">
                        <i class="fas fa-certificate me-2"></i>
                        Gestion des Attestations
                    </h1>
                    <p class="text-muted">Gérez et suivez toutes vos attestations de formation</p>
                </div>
                <button onclick="openCreateModal()" class="btn btn-gradient mt-3 mt-md-0" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 600; box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3); transition: all 0.3s;">
                    <i class="fas fa-plus me-2"></i>
                    Nouvelle Attestation
                </button>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-4">
            <form method="GET" action="{{ route('attestations_formation.index') }}">
                <div class="input-group shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <span class="input-group-text bg-white border-0" style="padding-left: 20px;">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control border-0 py-3" placeholder="Rechercher par numéro de série, nom ou CIN..." style="font-size: 15px;">
                    @if($search)
                        <a href="{{ route('attestations_formation.index') }}" class="input-group-text bg-white border-0" style="cursor: pointer;">
                            <i class="fas fa-times text-danger"></i>
                        </a>
                    @endif
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
                <h5 class="mb-0 text-white">
                    <i class="fas fa-list me-2"></i>
                    Liste des Attestations
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="border: none;">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center py-3">N° Série</th>
                                <th class="text-center py-3">Formation</th>
                                <th class="text-center py-3">Bénéficiaire</th>
                                <th class="text-center py-3">CIN</th>
                                <th class="text-center py-3">Date</th>
                                <th class="text-center py-3">Cachet</th>
                                <th class="text-center py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attestations as $attestation)
                                <tr style="transition: all 0.2s; cursor: pointer;" onmouseover="this.style.backgroundColor='#fff5f7'" onmouseout="this.style.backgroundColor=''">
                                    <td class="align-middle">
                                        <span class="badge text-white px-3 py-2" style="background: linear-gradient(135deg, #C2185B, #D32F2F); font-size: 11px; border-radius: 20px;">
                                            {{ $attestation->numero_de_serie }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <strong class="text-dark">{{ $attestation->formation_name }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="rounded-circle d-flex align-items-center justify-center text-white fw-bold me-2" style="width: 36px; height: 36px; background: linear-gradient(135deg, #C2185B, #ef4444); font-size: 14px; display: flex; align-items: center; justify-content: center;">
                                                {{ strtoupper(substr($attestation->personne_name, 0, 1)) }}
                                            </div>
                                            <span>{{ $attestation->personne_name }}</span>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <code style="background: #f8f9fa; padding: 4px 8px; border-radius: 6px; color: #333;">{{ $attestation->cin }}</code>
                                    </td>
                                    <td class="align-middle text-muted">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ $attestation->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="align-middle">
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
                                            <a href="{{ route('attestations_formation.pdf', $attestation) }}" class="btn btn-sm btn-info" style="border-radius: 8px; padding: 6px 12px;" title="Télécharger PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            <button onclick='openEditModal(@json($attestation))' class="btn btn-sm btn-warning" style="border-radius: 8px; padding: 6px 12px;" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('attestations_formation.destroy', $attestation) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette attestation ?')" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 8px; padding: 6px 12px;" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div>
                                            <i class="fas fa-inbox text-muted" style="font-size: 64px; opacity: 0.3;"></i>
                                            <h5 class="text-muted mt-3">Aucune attestation trouvée</h5>
                                            <p class="text-muted">Commencez par créer votre première attestation</p>
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
                        {{ $attestations->links() }}
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
                        Nouvelle Attestation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('attestations_formation.store') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-graduation-cap text-danger me-1"></i>
                                Nom de la Formation *
                            </label>
                            <input type="text" name="formation_name" class="form-control" required style="border-radius: 10px; padding: 12px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user text-danger me-1"></i>
                                Nom du Bénéficiaire *
                            </label>
                            <input type="text" name="personne_name" class="form-control" required style="border-radius: 10px; padding: 12px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-id-card text-danger me-1"></i>
                                CIN *
                            </label>
                            <input type="text" name="cin" class="form-control" required style="border-radius: 10px; padding: 12px;">
                        </div>

                        <div class="alert alert-light border" style="border-radius: 10px; background: linear-gradient(135deg, #fff5f7, #ffe5e9);">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="afficher_cachet" value="1" id="afficher_cachet_create">
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
                        Modifier l'Attestation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-graduation-cap text-danger me-1"></i>
                                Nom de la Formation *
                            </label>
                            <input type="text" name="formation_name" id="edit_formation_name" class="form-control" required style="border-radius: 10px; padding: 12px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-user text-danger me-1"></i>
                                Nom du Bénéficiaire *
                            </label>
                            <input type="text" name="personne_name" id="edit_personne_name" class="form-control" required style="border-radius: 10px; padding: 12px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">
                                <i class="fas fa-id-card text-danger me-1"></i>
                                CIN *
                            </label>
                            <input type="text" name="cin" id="edit_cin" class="form-control" required style="border-radius: 10px; padding: 12px;">
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
            var modal = new bootstrap.Modal(document.getElementById('createModal'));
            modal.show();
        }

        function openEditModal(attestation) {
            document.getElementById('editForm').action = `/attestations_formation/${attestation.id}`;
            document.getElementById('edit_formation_name').value = attestation.formation_name;
            document.getElementById('edit_personne_name').value = attestation.personne_name;
            document.getElementById('edit_cin').value = attestation.cin;
            document.getElementById('edit_afficher_cachet').checked = attestation.afficher_cachet == 1;
            
            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }
    </script>
</x-app-layout>