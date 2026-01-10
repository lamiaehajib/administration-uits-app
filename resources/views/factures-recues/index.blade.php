<x-app-layout>
    <div class="container-fluid">
        <!-- 🎨 Header avec gradient -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border-radius: 15px;">
                    <div class="card-body py-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h2 class="text-white mb-2 fw-bold">
                                    <i class="fas fa-file-invoice"></i> Factures Reçues
                                </h2>
                                <p class="text-white-50 mb-0">Gestion complète des factures consultants et fournisseurs</p>
                            </div>
                            <div class="mt-3 mt-md-0">
                                <a href="{{ route('factures-recues.create') }}" class="btn btn-light btn-lg px-4 shadow-sm">
                                    <i class="fas fa-plus-circle"></i> Nouvelle Facture
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 Statistiques Cards -->
        <div class="row mb-4 g-3">
            <!-- Total Factures -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #D32F2F !important; border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 fw-semibold">TOTAL FACTURES</p>
                                <h3 class="mb-0 fw-bold hight">{{ $stats['total_factures'] }}</h3>
                                <small class="text-success">{{ number_format($stats['montant_total'], 2) }} DH</small>
                            </div>
                            <div class="text-end">
                                <div class="rounded-circle p-3" style="background: linear-gradient(135deg, #C2185B15, #D32F2F15);">
                                    <i class="fas fa-file-invoice fa-2x hight"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- En Attente -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #FF9800 !important; border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 fw-semibold">EN ATTENTE</p>
                                <h3 class="mb-0 fw-bold text-warning">{{ $stats['en_attente']['count'] }}</h3>
                                <small class="text-warning">{{ number_format($stats['en_attente']['montant'], 2) }} DH</small>
                            </div>
                            <div class="text-end">
                                <div class="rounded-circle p-3" style="background: #FF980015;">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payées -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4CAF50 !important; border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 fw-semibold">PAYÉES</p>
                                <h3 class="mb-0 fw-bold text-success">{{ $stats['payee']['count'] }}</h3>
                                <small class="text-success">{{ number_format($stats['payee']['montant'], 2) }} DH</small>
                            </div>
                            <div class="text-end">
                                <div class="rounded-circle p-3" style="background: #4CAF5015;">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Échues -->
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #F44336 !important; border-radius: 10px;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 fw-semibold">ÉCHUES</p>
                                <h3 class="mb-0 fw-bold text-danger">{{ $stats['echues']['count'] }}</h3>
                                <small class="text-danger">{{ number_format($stats['echues']['montant'], 2) }} DH</small>
                            </div>
                            <div class="text-end">
                                <div class="rounded-circle p-3" style="background: #F4433615;">
                                    <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 🔍 Filtres Avancés -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold hight">
                        <i class="fas fa-filter"></i> Filtres & Recherche
                    </h5>
                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#filtresCollapse">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
            <div class="collapse show" id="filtresCollapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('factures-recues.index') }}" id="filterForm">
                        <div class="row g-3">
                            <!-- Recherche -->
                            <div class="col-md-4">
                                <label class="form-label fw-semibold text-muted small">RECHERCHE</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Numéro, fournisseur, description..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Statut -->
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-muted small">STATUT</label>
                                <select name="statut" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                                    <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                                    <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </div>

                            <!-- Type -->
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-muted small">TYPE</label>
                                <select name="type" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="consultant" {{ request('type') == 'consultant' ? 'selected' : '' }}>Consultant</option>
                                    <option value="fournisseur" {{ request('type') == 'fournisseur' ? 'selected' : '' }}>Fournisseur</option>
                                </select>
                            </div>

                            <!-- Date Début -->
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-muted small">DATE DÉBUT</label>
                                <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                            </div>

                            <!-- Date Fin -->
                            <div class="col-md-2">
                                <label class="form-label fw-semibold text-muted small">DATE FIN</label>
                                <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                            </div>

                            <!-- Filtres avancés -->
                            <div class="col-12">
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-search"></i> Filtrer
                                    </button>
                                    <a href="{{ route('factures-recues.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo"></i> Réinitialiser
                                    </a>
                                    <a href="{{ route('factures-recues.index', ['export' => 'csv'] + request()->all()) }}" class="btn btn-outline-success">
                                        <i class="fas fa-file-excel"></i> Export CSV
                                    </a>
                                    <a href="{{ route('factures-recues.trash') }}" class="btn btn-outline-warning">
                                        <i class="fas fa-trash"></i> Corbeille
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 📋 Table des Factures -->
        <div class="card border-0 shadow-sm" style="border-radius: 10px;">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0 fw-bold hight">
                        <i class="fas fa-list"></i> Liste des Factures ({{ $factures->total() }})
                    </h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href='?per_page='+this.value+'{{ request()->has('search') ? '&search='.request('search') : '' }}'">
                            <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15 / page</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 / page</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 / page</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 / page</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                            <tr>
                                <th class="text-white text-center">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th class="text-white">N° FACTURE</th>
                                <th class="text-white">FOURNISSEUR</th>
                                <th class="text-white">TYPE</th>
                                <th class="text-white">DATE</th>
                                <th class="text-white">ÉCHÉANCE</th>
                                <th class="text-white">MONTANT</th>
                                <th class="text-white">STATUT</th>
                                <th class="text-white text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($factures as $facture)
                            <tr class="border-bottom">
                                <td class="text-center">
                                    <input type="checkbox" class="form-check-input facture-checkbox" value="{{ $facture->id }}">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $facture->numero_facture }}</div>
                                    @if($facture->fichier_pdf)
                                    <small class="text-muted">
                                        <i class="fas fa-paperclip"></i> PDF joint
                                    </small>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="width: 35px; height: 35px; background: linear-gradient(135deg, #C2185B15, #D32F2F15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas {{ $facture->fournisseur_type === 'App\Models\Consultant' ? 'fa-user-tie' : 'fa-building' }} text-danger"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $facture->nom_fournisseur }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($facture->fournisseur_type === 'App\Models\Consultant')
                                    <span class="badge bg-info">
                                        <i class="fas fa-user-tie"></i> Consultant
                                    </span>
                                    @else
                                    <span class="badge bg-primary">
                                        <i class="fas fa-building"></i> Fournisseur
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $facture->date_facture->format('d/m/Y') }}</small>
                                </td>
                                <td>
                                    @if($facture->date_echeance)
                                        @php
                                            $isEchue = $facture->date_echeance->isPast() && $facture->statut !== 'payee';
                                            $isProche = $facture->date_echeance->between(now(), now()->addDays(7)) && $facture->statut !== 'payee';
                                        @endphp
                                        <small class="{{ $isEchue ? 'text-danger fw-bold' : ($isProche ? 'text-warning fw-bold' : 'text-muted') }}">
                                            {{ $facture->date_echeance->format('d/m/Y') }}
                                            @if($isEchue)
                                            <i class="fas fa-exclamation-circle"></i>
                                            @elseif($isProche)
                                            <i class="fas fa-clock"></i>
                                            @endif
                                        </small>
                                    @else
                                    <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold hight">{{ number_format($facture->montant_ttc, 2) }} DH</span>
                                </td>
                                <td>
                                    @if($facture->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock"></i> En Attente
                                    </span>
                                    @elseif($facture->statut === 'payee')
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Payée
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-ban"></i> Annulée
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('factures-recues.show', $facture->id) }}" 
                                           class="btn btn-outline-info" 
                                           data-bs-toggle="tooltip" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('factures-recues.edit', $facture->id) }}" 
                                           class="btn btn-outline-primary" 
                                           data-bs-toggle="tooltip" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-success quick-edit-btn" 
                                                data-id="{{ $facture->id }}"
                                                data-bs-toggle="tooltip" 
                                                title="Édition rapide">
                                            <i class="fas fa-bolt"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-warning duplicate-btn" 
                                                data-id="{{ $facture->id }}"
                                                data-bs-toggle="tooltip" 
                                                title="Dupliquer">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-danger delete-btn" 
                                                data-id="{{ $facture->id }}"
                                                data-bs-toggle="tooltip" 
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        <h5>Aucune facture trouvée</h5>
                                        <p>Essayez de modifier vos critères de recherche</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($factures->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Affichage de {{ $factures->firstItem() }} à {{ $factures->lastItem() }} sur {{ $factures->total() }} résultats
                    </div>
                    <div>
                        {{ $factures->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Actions en masse -->
        <div class="mt-3" id="bulkActionsBar" style="display: none;">
            <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold"><span id="selectedCount">0</span> facture(s) sélectionnée(s)</span>
                        </div>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-success" id="bulkPayBtn">
                                <i class="fas fa-check"></i> Marquer Payée
                            </button>
                            <button type="button" class="btn btn-danger" id="bulkDeleteBtn">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Édition Rapide -->
    <div class="modal fade" id="quickEditModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-bolt"></i> Édition Rapide
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quickEditForm">
                        <input type="hidden" id="quick_edit_id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Statut</label>
                            <select class="form-select" id="quick_statut" required>
                                <option value="en_attente">En Attente</option>
                                <option value="payee">Payée</option>
                                <option value="annulee">Annulée</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Date d'échéance</label>
                            <input type="date" class="form-control" id="quick_date_echeance">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="saveQuickEdit">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Select All Checkboxes
            $('#selectAll').on('change', function() {
                $('.facture-checkbox').prop('checked', this.checked);
                updateBulkActionsBar();
            });

            $('.facture-checkbox').on('change', function() {
                updateBulkActionsBar();
            });

            function updateBulkActionsBar() {
                let count = $('.facture-checkbox:checked').length;
                $('#selectedCount').text(count);
                $('#bulkActionsBar').toggle(count > 0);
            }

            // Édition Rapide
            $('.quick-edit-btn').on('click', function() {
                let id = $(this).data('id');
                $('#quick_edit_id').val(id);
                $('#quickEditModal').modal('show');
            });

            $('#saveQuickEdit').on('click', function() {
                let id = $('#quick_edit_id').val();
                let statut = $('#quick_statut').val();
                let date_echeance = $('#quick_date_echeance').val();

                $.ajax({
                    url: `/factures-recues/${id}/quick-edit`,
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        statut: statut,
                        date_echeance: date_echeance
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            text: response.message,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur!',
                            text: xhr.responseJSON.message || 'Une erreur est survenue'
                        });
                    }
                });
            });

            // Dupliquer
            $('.duplicate-btn').on('click', function() {
                let id = $(this).data('id');
                
                Swal.fire({
                    title: 'Dupliquer cette facture?',
                    text: "Une copie sera créée que vous pourrez modifier",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, dupliquer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/factures-recues/${id}/duplicate`,
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dupliquée!',
                                    text: 'Facture dupliquée avec succès',
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            });

            // Supprimer
            $('.delete-btn').on('click', function() {
                let id = $(this).data('id');
                
                Swal.fire({
                    title: 'Supprimer cette facture?',
                    text: "Vous pourrez la restaurer depuis la corbeille",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/factures-recues/${id}`,
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supprimée!',
                                    text: 'Facture déplacée dans la corbeille',
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            });

            // Bulk Payer
            $('#bulkPayBtn').on('click', function() {
                let ids = $('.facture-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                $.ajax({
                    url: '/factures-recues/bulk/update-status',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids,
                        statut: 'payee'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès!',
                            text: response.message,
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                });
            });

            // Bulk Delete
            $('#bulkDeleteBtn').on('click', function() {
                let ids = $('.facture-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                Swal.fire({
                    title: 'Supprimer les factures sélectionnées?',
                    text: `${ids.length} facture(s) seront supprimée(s)`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/factures-recues/bulk/delete',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: ids
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supprimées!',
                                    text: response.message,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            });
        });
</script>
@endpush
</x-app-layout>