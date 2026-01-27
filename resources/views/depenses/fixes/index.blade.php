<x-app-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>
                    <span class="hight">Dépenses Fixes</span>
                </h3>
                <p class="text-muted mb-0">Gestion des dépenses récurrentes mensuelles</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-2"></i>Nouvelle Dépense Fixe
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importSalairesModal">
                    <i class="fas fa-download"></i> Importer salaires
                </button>
                <a href="{{ route('depenses.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-3 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-coins text-danger fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small">Total Mensuel</p>
                                <h4 class="mb-0 fw-bold">{{ number_format($stats['total'], 2) }} DH</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small">Actifs</p>
                                <h4 class="mb-0 fw-bold">{{ $stats['actifs'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-pause-circle text-secondary fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small">Inactifs</p>
                                <h4 class="mb-0 fw-bold">{{ $stats['inactifs'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-list text-info fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small">Total</p>
                                <h4 class="mb-0 fw-bold">{{ $stats['count'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('depenses.fixes.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">Tous les types</option>
                                <option value="salaire" {{ request('type') == 'salaire' ? 'selected' : '' }}>Salaires</option>
                                <option value="loyer" {{ request('type') == 'loyer' ? 'selected' : '' }}>Loyer</option>
                                <option value="internet" {{ request('type') == 'internet' ? 'selected' : '' }}>Internet</option>
                                <option value="mobile" {{ request('type') == 'mobile' ? 'selected' : '' }}>Mobile</option>
                                <option value="srmc" {{ request('type') == 'srmc' ? 'selected' : '' }}>SRMC</option>
                                <option value="femme_menage" {{ request('type') == 'femme_menage' ? 'selected' : '' }}>Femme de ménage</option>
                                <option value="frais_aups" {{ request('type') == 'frais_aups' ? 'selected' : '' }}>Frais AUPS</option>
                                <option value="autre" {{ request('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Statut</label>
                            <select name="statut" class="form-select form-select-sm">
                                <option value="">Tous les statuts</option>
                                <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                <option value="suspendu" {{ request('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Recherche</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Libellé, description..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-search me-1"></i>Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Type</th>
                                <th>Libellé</th>
                                <th>Description</th>
                                <th>Montant Mensuel</th>
                                <th>Période</th>
                                
                                <th>Statut</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depenses as $depense)
                            <tr>
                                <td>
                                    <span class="badge bg-primary">{{ $depense->type_libelle }}</span>
                                </td>
                                <td class="fw-bold">{{ $depense->libelle_complet }}</td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($depense->description, 50) }}</small>
                                </td>
                                <td>
                                    <span class="fw-bold text-danger">{{ number_format($depense->montant_mensuel, 2) }} DH</span>
                                </td>
                                <td>
                                    <small>
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                        {{ $depense->date_debut->format('d/m/Y') }}
                                        @if($depense->date_fin)
                                            → {{ $depense->date_fin->format('d/m/Y') }}
                                        @else
                                            → <em class="text-muted">Indéterminé</em>
                                        @endif
                                    </small>
                                </td>
                                <td>{!! $depense->statut_badge !!}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info btn-sm" onclick="showDetails({{ $depense->id }})" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-warning btn-sm" onclick="editDepense({{ $depense->id }})" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteDepense({{ $depense->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox text-muted fs-1 mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Aucune dépense fixe trouvée</p>
                                    <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#createModal">
                                        <i class="fas fa-plus me-2"></i>Créer la première dépense
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($depenses->hasPages())
            <div class="card-footer bg-white">
                {{ $depenses->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Create -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Nouvelle Dépense Fixe
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('depenses.fixes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" id="create_type" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="salaire">Salaires</option>
                                    <option value="loyer">Loyer</option>
                                    <option value="internet">Internet</option>
                                    <option value="mobile">Mobile</option>
                                    <option value="srmc">SRMC</option>
                                    <option value="femme_menage">Femme de ménage</option>
                                    <option value="frais_aups">Frais AUPS</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="create_libelle_div" style="display: none;">
                                <label class="form-label">Libellé (si Autre) <span class="text-danger">*</span></label>
                                <input type="text" name="libelle" class="form-control" placeholder="Ex: Assurance...">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Détails supplémentaires..."></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Montant Mensuel (DH) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="montant_mensuel" class="form-control" required placeholder="0.00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Référence Contrat</label>
                                <input type="text" name="reference_contrat" class="form-control" placeholder="Ex: CONT-2024-001">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date Début <span class="text-danger">*</span></label>
                                <input type="date" name="date_debut" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date Fin</label>
                                <input type="date" name="date_fin" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Statut <span class="text-danger">*</span></label>
                                <select name="statut" class="form-select" required>
                                    <option value="actif">Actif</option>
                                    <option value="inactif">Inactif</option>
                                    <option value="suspendu">Suspendu</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jour de Paiement <span class="text-danger">*</span></label>
                                <input type="number" name="jour_paiement" class="form-control" min="1" max="31" value="5" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Rappel (jours avant) <span class="text-danger">*</span></label>
                                <input type="number" name="rappel_avant_jours" class="form-control" min="1" max="30" value="3" required>
                            </div>
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="rappel_actif" value="1" id="create_rappel">
                                    <label class="form-check-label" for="create_rappel">
                                        Activer les rappels de paiement
                                    </label>
                                </div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Fichier Contrat (PDF)</label>
                                <input type="file" name="fichier_contrat" class="form-control" accept=".pdf">
                                <small class="text-muted">Max 5 Mo</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Show (Details) -->
    <div class="modal fade" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Détails de la Dépense
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="showContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Modifier la Dépense
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     
    <div class="modal fade" id="importSalairesModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('depenses.importer-salaires') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-download"></i> Importer salaires</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Année</label>
                            <select name="annee" class="form-select" required>
                                @for($y = now()->year; $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mois</label>
                            <select name="mois" class="form-select" required>
                                @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $i => $nom)
                                    <option value="{{ $i+1 }}" {{ ($i+1) == now()->month ? 'selected' : '' }}>{{ $nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Cela va importer les salaires depuis <strong>uits-mgmt.ma</strong> et créer automatiquement la CNSS.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download"></i> Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show libellé field if type = autre
        $(document).ready(function() {
            $('#create_type').on('change', function() {
                if ($(this).val() === 'autre') {
                    $('#create_libelle_div').show();
                } else {
                    $('#create_libelle_div').hide();
                }
            });
        });

        // Show details
        function showDetails(id) {
            $('#showModal').modal('show');
            $('#showContent').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
            
            $.get(`/depenses/fixes/${id}`, function(response) {
                $('#showContent').html(response);
            }).fail(function() {
                $('#showContent').html('<div class="alert alert-danger">Erreur lors du chargement</div>');
            });
        }

        // Edit depense
        function editDepense(id) {
            $('#editModal').modal('show');
            $('#editContent').html('<div class="text-center py-5"><div class="spinner-border text-warning"></div></div>');
            
            $.get(`/depenses/fixes/${id}/edit`, function(response) {
                $('#editContent').html(response);
                
                // Re-init select2 if needed
                $('#edit_type').on('change', function() {
                    if ($(this).val() === 'autre') {
                        $('#edit_libelle_div').show();
                    } else {
                        $('#edit_libelle_div').hide();
                    }
                });
            }).fail(function() {
                $('#editContent').html('<div class="alert alert-danger">Erreur lors du chargement</div>');
            });
        }

        // Delete depense
        function deleteDepense(id) {
            Swal.fire({
                title: 'Confirmer la suppression?',
                text: "Cette action est irréversible!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D32F2F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/depenses/fixes/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire('Supprimé!', 'Dépense supprimée avec succès.', 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            Swal.fire('Erreur!', 'Impossible de supprimer.', 'error');
                        }
                    });
                }
            });
        }

        // Show success/error messages
        @if(session('success'))
            Swal.fire('Succès!', '{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            Swal.fire('Erreur!', '{{ session('error') }}', 'error');
        @endif
    </script>
    @endpush
</x-app-layout>