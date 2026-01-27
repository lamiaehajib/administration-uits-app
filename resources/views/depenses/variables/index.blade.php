<x-app-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="mb-0">
                    <i class="fas fa-random me-2"></i>
                    <span class="hight">Dépenses Variables</span>
                </h3>
                <p class="text-muted mb-0">Gestion des dépenses ponctuelles</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus me-2"></i>Nouvelle Dépense Variable
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
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-coins text-warning fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small">Total</p>
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
                            <div class="avatar-sm bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="fas fa-hourglass-half text-warning fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small">En Attente</p>
                                <h4 class="mb-0 fw-bold">{{ $stats['en_attente'] }}</h4>
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
                                <i class="fas fa-check-circle text-info fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small">Validées</p>
                                <h4 class="mb-0 fw-bold">{{ $stats['validee'] }}</h4>
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
                                <i class="fas fa-money-check-alt text-success fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <p class="text-muted mb-1 small">Payées</p>
                                <h4 class="mb-0 fw-bold">{{ $stats['payee'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('depenses.variables.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label small">Type</label>
                            <select name="type" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                <option value="facture_recue" {{ request('type') == 'facture_recue' ? 'selected' : '' }}>Facture reçue</option>
                                <option value="prime" {{ request('type') == 'prime' ? 'selected' : '' }}>Prime</option>
                                <option value="cnss" {{ request('type') == 'cnss' ? 'selected' : '' }}>CNSS</option>
                                <option value="publication" {{ request('type') == 'publication' ? 'selected' : '' }}>Publication</option>
                                <option value="transport" {{ request('type') == 'transport' ? 'selected' : '' }}>Transport</option>
                                <option value="dgi" {{ request('type') == 'dgi' ? 'selected' : '' }}>DGI</option>
                                <option value="comptabilite" {{ request('type') == 'comptabilite' ? 'selected' : '' }}>Comptabilité</option>
                                <option value="autre" {{ request('type') == 'autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Statut</label>
                            <select name="statut" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                                <option value="validee" {{ request('statut') == 'validee' ? 'selected' : '' }}>Validée</option>
                                <option value="payee" {{ request('statut') == 'payee' ? 'selected' : '' }}>Payée</option>
                                <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Année</label>
                            <select name="annee" class="form-select form-select-sm">
                                <option value="">Toutes</option>
                                @foreach(range(date('Y'), date('Y') - 5) as $y)
                                    <option value="{{ $y }}" {{ request('annee') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Mois</label>
                            <select name="mois" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ request('mois') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Recherche</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Libellé..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label small d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Répartition par type -->
        @if($stats['par_type']->isNotEmpty())
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-pie text-warning me-2"></i>
                            Répartition par Type
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($stats['par_type'] as $type)
                            <div class="col-md-3">
                                <div class="border rounded p-2">
                                    <small class="text-muted d-block">{{ ucfirst($type->type) }}</small>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-warning">{{ $type->count }}</span>
                                        <strong class="text-warning">{{ number_format($type->total, 2) }} DH</strong>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Libellé</th>
                                <th>Bénéficiaire</th>
                                <th>Montant</th>
                                <th>Statut</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($depenses as $depense)
                            <tr>
                                <td>
                                    <small>{{ $depense->date_depense->format('d/m/Y') }}</small><br>
                                    <small class="text-muted">{{ $depense->mois_nom }} {{ $depense->annee }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $depense->type_libelle }}</span>
                                </td>
                                <td class="fw-bold">{{ Str::limit($depense->libelle, 30) }}</td>
                                <td>
                                    @if($depense->type == 'prime')
                                        <small>{{ $depense->nom_employe }}</small>
                                    @elseif($depense->type == 'facture_recue' && $depense->factureRecue)
                                        <small>{{ $depense->factureRecue->nom_fournisseur }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold text-warning">{{ number_format($depense->montant, 2) }} DH</span>
                                </td>
                                <td>{!! $depense->statut_badge !!}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-info btn-sm" onclick="showDetails({{ $depense->id }})" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if(!in_array($depense->statut, ['payee', 'annulee']))
                                        <button class="btn btn-warning btn-sm" onclick="editDepense({{ $depense->id }})" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endif
                                        @if($depense->statut == 'en_attente')
                                        <button class="btn btn-success btn-sm" onclick="validerDepense({{ $depense->id }})" title="Valider">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                        @if($depense->statut != 'payee')
                                        <button class="btn btn-danger btn-sm" onclick="deleteDepense({{ $depense->id }})" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox text-muted fs-1 mb-3 d-block"></i>
                                    <p class="text-muted mb-0">Aucune dépense variable trouvée</p>
                                    <button class="btn btn-sm btn-warning mt-3" data-bs-toggle="modal" data-bs-target="#createModal">
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
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Nouvelle Dépense Variable
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('depenses.variables.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" id="create_type" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <option value="facture_recue">Facture reçue</option>
                                    <option value="prime">Prime</option>
                                    <option value="cnss">CNSS</option>
                                    <option value="publication">Publication</option>
                                    <option value="transport">Transport</option>
                                    <option value="dgi">DGI</option>
                                    <option value="comptabilite">Comptabilité</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date Dépense <span class="text-danger">*</span></label>
                                <input type="date" name="date_depense" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Libellé <span class="text-danger">*</span></label>
                                <input type="text" name="libelle" class="form-control" required placeholder="Description de la dépense">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Montant (DH) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="montant" class="form-control" required placeholder="0.00">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Description</label>
                                <input type="text" name="description" class="form-control" placeholder="Détails...">
                            </div>

                            <!-- Fields conditionnels selon type -->
                            <div id="facture_fields" class="col-12" style="display: none;">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-primary mb-3">
                                            <i class="fas fa-file-invoice me-2"></i>Informations Facture
                                        </h6>
                                        <select name="facture_recue_id" class="form-select">
    <option value="">Sélectionner une facture...</option>
    @if(isset($facturess) && $facturess->count() > 0)
        @foreach($facturess as $facture)
            <option value="{{ $facture->id }}">
                {{ $facture->numero_facture }} - {{ $facture->nom_fournisseur }} ({{ number_format($facture->montant_ttc, 2) }} DH)
            </option>
        @endforeach
    @endif
</select>
                                    </div>
                                </div>
                            </div>

                            <div id="prime_fields" class="col-12" style="display: none;">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-success mb-3">
                                            <i class="fas fa-gift me-2"></i>Informations Prime
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Employé</label>
                                                <select name="user_mgmt_id" id="employee_select" class="form-select select2">
                                                    <option value="">Sélectionner...</option>
                                                    @foreach($employees as $emp)
                                                        <option value="{{ $emp['id'] }}" data-poste="{{ $emp['poste'] }}" data-salaire="{{ $emp['salaire'] }}">
                                                            {{ $emp['name'] }} - {{ $emp['poste'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Type Prime</label>
                                                <input type="text" name="type_prime" class="form-control" placeholder="Ex: Performance, Ancienneté...">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Motif</label>
                                                <textarea name="motif_prime" class="form-control" rows="2" placeholder="Raison de la prime..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="publication_fields" class="col-12" style="display: none;">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-info mb-3">
                                            <i class="fas fa-bullhorn me-2"></i>Informations Publication
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Plateforme</label>
                                                <input type="text" name="plateforme" class="form-control" placeholder="Ex: Facebook, Google Ads...">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Campagne</label>
                                                <input type="text" name="campagne" class="form-control" placeholder="Nom de la campagne">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="transport_fields" class="col-12" style="display: none;">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="text-warning mb-3">
                                            <i class="fas fa-car me-2"></i>Informations Transport
                                        </h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Type</label>
                                                <select name="type_transport" class="form-select">
                                                    <option value="">Sélectionner...</option>
                                                    <option value="taxi">Taxi</option>
                                                    <option value="train">Train</option>
                                                    <option value="avion">Avion</option>
                                                    <option value="voiture">Voiture personnelle</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Bénéficiaire</label>
                                                <input type="text" name="beneficiaire" class="form-control" placeholder="Nom...">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Trajet</label>
                                                <input type="text" name="trajet" class="form-control" placeholder="De... à...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Fichiers Justificatifs</label>
                                <input type="file" name="fichiers_justificatifs[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">Formats: PDF, JPG, PNG. Max 5 Mo par fichier</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Show -->
    <div class="modal fade" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-eye me-2"></i>Détails de la Dépense
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="showContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Modifier la Dépense
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="editContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-warning"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dropdownParent: $('#createModal'),
                width: '100%'
            });

            // Show conditional fields
            $('#create_type').on('change', function() {
                const type = $(this).val();
                
                $('#facture_fields, #prime_fields, #publication_fields, #transport_fields').hide();
                
                if (type === 'facture_recue') $('#facture_fields').show();
                if (type === 'prime') $('#prime_fields').show();
                if (type === 'publication') $('#publication_fields').show();
                if (type === 'transport') $('#transport_fields').show();
            });

            // Auto-fill employee data
            $('#employee_select').on('change', function() {
                const option = $(this).find(':selected');
                const poste = option.data('poste');
                const salaire = option.data('salaire');
                
                $('input[name="poste_employe"]').val(poste);
                $('input[name="montant_salaire"]').val(salaire);
            });
        });

        function showDetails(id) {
            $('#showModal').modal('show');
            $('#showContent').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
            
            $.get(`/depenses/variables/${id}`, function(response) {
                $('#showContent').html(response);
            });
        }

        function editDepense(id) {
            $('#editModal').modal('show');
            $('#editContent').html('<div class="text-center py-5"><div class="spinner-border text-warning"></div></div>');
            
            $.get(`/depenses/variables/${id}/edit`, function(response) {
                $('#editContent').html(response);
            });
        }

        function validerDepense(id) {
            Swal.fire({
                title: 'Valider cette dépense?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, valider',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(`/depenses/variables/${id}/valider`, {
                        _token: '{{ csrf_token() }}'
                    }, function() {
                        Swal.fire('Validée!', 'Dépense validée avec succès.', 'success');
                        location.reload();
                    });
                }
            });
        }

        function deleteDepense(id) {
            Swal.fire({
                title: 'Supprimer cette dépense?',
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
                        url: `/depenses/variables/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function() {
                            Swal.fire('Supprimée!', 'Dépense supprimée.', 'success');
                            location.reload();
                        }
                    });
                }
            });
        }

        @if(session('success'))
            Swal.fire('Succès!', '{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            Swal.fire('Erreur!', '{{ session('error') }}', 'error');
        @endif
    </script>
    @endpush
</x-app-layout>