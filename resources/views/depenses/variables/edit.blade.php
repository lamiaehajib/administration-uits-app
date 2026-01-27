<form action="{{ route('depenses.variables.update', $depense->id) }}" method="POST" enctype="multipart/form-data" id="editVariableForm">
    @csrf
    @method('PUT')
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type" id="edit_type" class="form-select" required>
                <option value="">Sélectionner...</option>
                <option value="facture_recue" {{ $depense->type == 'facture_recue' ? 'selected' : '' }}>Facture reçue</option>
                <option value="prime" {{ $depense->type == 'prime' ? 'selected' : '' }}>Prime</option>
                <option value="cnss" {{ $depense->type == 'cnss' ? 'selected' : '' }}>CNSS</option>
                <option value="publication" {{ $depense->type == 'publication' ? 'selected' : '' }}>Publication</option>
                <option value="transport" {{ $depense->type == 'transport' ? 'selected' : '' }}>Transport</option>
                <option value="dgi" {{ $depense->type == 'dgi' ? 'selected' : '' }}>DGI</option>
                <option value="comptabilite" {{ $depense->type == 'comptabilite' ? 'selected' : '' }}>Comptabilité</option>
                <option value="autre" {{ $depense->type == 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Date Dépense <span class="text-danger">*</span></label>
            <input type="date" name="date_depense" class="form-control" required value="{{ $depense->date_depense->format('Y-m-d') }}">
        </div>
        
        <div class="col-12">
            <label class="form-label">Libellé <span class="text-danger">*</span></label>
            <input type="text" name="libelle" class="form-control" required value="{{ $depense->libelle }}" placeholder="Description de la dépense">
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Montant (DH) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="montant" class="form-control" required value="{{ $depense->montant }}" placeholder="0.00">
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select name="statut" class="form-select" required>
                <option value="en_attente" {{ $depense->statut == 'en_attente' ? 'selected' : '' }}>En attente</option>
                <option value="validee" {{ $depense->statut == 'validee' ? 'selected' : '' }}>Validée</option>
                <option value="payee" {{ $depense->statut == 'payee' ? 'selected' : '' }}>Payée</option>
                <option value="annulee" {{ $depense->statut == 'annulee' ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>
        
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Détails...">{{ $depense->description }}</textarea>
        </div>

        <!-- Fields conditionnels -->
        <div id="edit_facture_fields" class="col-12" style="{{ $depense->type == 'facture_recue' ? '' : 'display: none;' }}">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-file-invoice me-2"></i>Informations Facture
                    </h6>
                    <select name="facture_recue_id" class="form-select">
                        <option value="">Sélectionner une facture...</option>
                        @foreach($factures as $facture)
                            <option value="{{ $facture->id }}" {{ $depense->facture_recue_id == $facture->id ? 'selected' : '' }}>
                                {{ $facture->numero_facture }} - {{ $facture->nom_fournisseur }} ({{ number_format($facture->montant_ttc, 2) }} DH)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div id="edit_prime_fields" class="col-12" style="{{ $depense->type == 'prime' ? '' : 'display: none;' }}">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="text-success mb-3">
                        <i class="fas fa-gift me-2"></i>Informations Prime
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Employé</label>
                            <select name="user_mgmt_id" id="edit_employee_select" class="form-select select2-edit">
                                <option value="">Sélectionner...</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp['id'] }}" 
                                            {{ $depense->user_mgmt_id == $emp['id'] ? 'selected' : '' }}
                                            data-poste="{{ $emp['poste'] }}" 
                                            data-salaire="{{ $emp['salaire'] }}">
                                        {{ $emp['name'] }} - {{ $emp['poste'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type Prime</label>
                            <input type="text" name="type_prime" class="form-control" value="{{ $depense->type_prime }}" placeholder="Ex: Performance...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Motif</label>
                            <textarea name="motif_prime" class="form-control" rows="2" placeholder="Raison...">{{ $depense->motif_prime }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="edit_publication_fields" class="col-12" style="{{ $depense->type == 'publication' ? '' : 'display: none;' }}">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="text-info mb-3">
                        <i class="fas fa-bullhorn me-2"></i>Informations Publication
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Plateforme</label>
                            <input type="text" name="plateforme" class="form-control" value="{{ $depense->plateforme }}" placeholder="Ex: Facebook...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Campagne</label>
                            <input type="text" name="campagne" class="form-control" value="{{ $depense->campagne }}" placeholder="Nom...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="edit_transport_fields" class="col-12" style="{{ $depense->type == 'transport' ? '' : 'display: none;' }}">
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
                                <option value="taxi" {{ $depense->type_transport == 'taxi' ? 'selected' : '' }}>Taxi</option>
                                <option value="train" {{ $depense->type_transport == 'train' ? 'selected' : '' }}>Train</option>
                                <option value="avion" {{ $depense->type_transport == 'avion' ? 'selected' : '' }}>Avion</option>
                                <option value="voiture" {{ $depense->type_transport == 'voiture' ? 'selected' : '' }}>Voiture personnelle</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Bénéficiaire</label>
                            <input type="text" name="beneficiaire" class="form-control" value="{{ $depense->beneficiaire }}" placeholder="Nom...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Trajet</label>
                            <input type="text" name="trajet" class="form-control" value="{{ $depense->trajet }}" placeholder="De... à...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fichiers existants -->
        @if($depense->fichiers_justificatifs && count($depense->fichiers_justificatifs) > 0)
        <div class="col-12">
            <label class="form-label">Fichiers Actuels</label>
            <div class="alert alert-info small">
                <i class="fas fa-paperclip me-2"></i>
                <strong>{{ count($depense->fichiers_justificatifs) }} fichier(s) attaché(s)</strong>
                <div class="mt-2">
                    @foreach($depense->fichiers_justificatifs as $index => $fichier)
                        <a href="{{ Storage::url($fichier) }}" target="_blank" class="badge bg-primary me-1">
                            Fichier {{ $index + 1 }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Nouveaux fichiers -->
        <div class="col-12">
            <label class="form-label">Ajouter des Fichiers Justificatifs</label>
            <input type="file" name="fichiers_justificatifs[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
            <small class="text-muted">Les nouveaux fichiers seront ajoutés aux existants. Max 5 Mo par fichier</small>
        </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Annuler
        </button>
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-save me-2"></i>Mettre à jour
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Init select2
        $('.select2-edit').select2({
            dropdownParent: $('#editModal'),
            width: '100%'
        });

        // Show conditional fields
        $('#edit_type').on('change', function() {
            const type = $(this).val();
            
            $('#edit_facture_fields, #edit_prime_fields, #edit_publication_fields, #edit_transport_fields').hide();
            
            if (type === 'facture_recue') $('#edit_facture_fields').show();
            if (type === 'prime') $('#edit_prime_fields').show();
            if (type === 'publication') $('#edit_publication_fields').show();
            if (type === 'transport') $('#edit_transport_fields').show();
        });

        // Auto-fill employee data
        $('#edit_employee_select').on('change', function() {
            const option = $(this).find(':selected');
            const poste = option.data('poste');
            const salaire = option.data('salaire');
            
            console.log('Selected employee:', poste, salaire);
        });
    });

    // Form submit handler
    $('#editVariableForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editModal').modal('hide');
                Swal.fire('Succès!', 'Dépense mise à jour avec succès.', 'success');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors;
                if (errors) {
                    let errorMsg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Erreur!', errorMsg, 'error');
                } else {
                    Swal.fire('Erreur!', 'Impossible de mettre à jour.', 'error');
                }
            }
        });
    });
</script>