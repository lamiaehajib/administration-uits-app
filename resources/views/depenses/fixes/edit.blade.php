<form action="{{ route('depenses.fixes.update', $depense->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
    @csrf
    @method('PUT')
    
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Type <span class="text-danger">*</span></label>
            <select name="type" id="edit_type" class="form-select" required>
                <option value="">Sélectionner...</option>
                <option value="salaire" {{ $depense->type == 'salaire' ? 'selected' : '' }}>Salaires</option>
                <option value="loyer" {{ $depense->type == 'loyer' ? 'selected' : '' }}>Loyer</option>
                <option value="internet" {{ $depense->type == 'internet' ? 'selected' : '' }}>Internet</option>
                <option value="mobile" {{ $depense->type == 'mobile' ? 'selected' : '' }}>Mobile</option>
                <option value="srmc" {{ $depense->type == 'srmc' ? 'selected' : '' }}>SRMC</option>
                <option value="femme_menage" {{ $depense->type == 'femme_menage' ? 'selected' : '' }}>Femme de ménage</option>
                <option value="frais_aups" {{ $depense->type == 'frais_aups' ? 'selected' : '' }}>Frais AUPS</option>
                <option value="autre" {{ $depense->type == 'autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>
        
        <div class="col-md-6" id="edit_libelle_div" style="{{ $depense->type == 'autre' ? '' : 'display: none;' }}">
            <label class="form-label">Libellé (si Autre) <span class="text-danger">*</span></label>
            <input type="text" name="libelle" class="form-control" value="{{ $depense->libelle }}" placeholder="Ex: Assurance...">
        </div>
        
        <div class="col-12">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="2" placeholder="Détails supplémentaires...">{{ $depense->description }}</textarea>
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Montant Mensuel (DH) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="montant_mensuel" class="form-control" value="{{ $depense->montant_mensuel }}" required placeholder="0.00">
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Référence Contrat</label>
            <input type="text" name="reference_contrat" class="form-control" value="{{ $depense->reference_contrat }}" placeholder="Ex: CONT-2024-001">
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Date Début <span class="text-danger">*</span></label>
            <input type="date" name="date_debut" class="form-control" value="{{ $depense->date_debut->format('Y-m-d') }}" required>
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Date Fin</label>
            <input type="date" name="date_fin" class="form-control" value="{{ $depense->date_fin ? $depense->date_fin->format('Y-m-d') : '' }}">
        </div>
        
        <div class="col-md-4">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select name="statut" class="form-select" required>
                <option value="actif" {{ $depense->statut == 'actif' ? 'selected' : '' }}>Actif</option>
                <option value="inactif" {{ $depense->statut == 'inactif' ? 'selected' : '' }}>Inactif</option>
                <option value="suspendu" {{ $depense->statut == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
            </select>
        </div>
        
        <div class="col-md-4">
            <label class="form-label">Jour de Paiement <span class="text-danger">*</span></label>
            <input type="number" name="jour_paiement" class="form-control" min="1" max="31" value="{{ $depense->jour_paiement }}" required>
        </div>
        
        <div class="col-md-4">
            <label class="form-label">Rappel (jours avant) <span class="text-danger">*</span></label>
            <input type="number" name="rappel_avant_jours" class="form-control" min="1" max="30" value="{{ $depense->rappel_avant_jours }}" required>
        </div>
        
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="rappel_actif" value="1" id="edit_rappel" {{ $depense->rappel_actif ? 'checked' : '' }}>
                <label class="form-check-label" for="edit_rappel">
                    Activer les rappels de paiement
                </label>
            </div>
        </div>
        
        <div class="col-12">
            <label class="form-label">Fichier Contrat (PDF)</label>
            
            @if($depense->fichier_contrat)
            <div class="alert alert-info small mb-2">
                <i class="fas fa-file-pdf me-2"></i>
                Fichier actuel: 
                <a href="{{ Storage::url($depense->fichier_contrat) }}" target="_blank" class="alert-link">
                    Voir le contrat
                </a>
            </div>
            @endif
            
            <input type="file" name="fichier_contrat" class="form-control" accept=".pdf">
            <small class="text-muted">Laissez vide pour conserver le fichier actuel. Max 5 Mo</small>
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
    // Form submit handler
    $('#editForm').on('submit', function(e) {
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