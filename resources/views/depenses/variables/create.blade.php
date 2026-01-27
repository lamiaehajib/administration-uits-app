<!-- Modal Create Dépense Variable -->
<div class="modal fade" id="createVariableModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form action="{{ route('depenses.variables.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Nouvelle Dépense Variable
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Type -->
                        <div class="col-md-4">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="typeVariable" class="form-select" required>
                                <option value="">Sélectionner un type</option>
                                <option value="facture_recue">📄 Facture Reçue</option>
                                <option value="prime">💎 Prime Employé</option>
                                <option value="cnss">🏥 CNSS</option>
                                <option value="publication">📢 Publication</option>
                                <option value="transport">🚗 Transport</option>
                                <option value="dgi">🏛️ DGI</option>
                                <option value="comptabilite">📊 Comptabilité</option>
                                <option value="autre">📌 Autre</option>
                            </select>
                        </div>

                        <!-- Libellé -->
                        <div class="col-md-4">
                            <label class="form-label">Libellé <span class="text-danger">*</span></label>
                            <input type="text" name="libelle" class="form-control" required>
                        </div>

                        <!-- Montant -->
                        <div class="col-md-4">
                            <label class="form-label">Montant (DH) <span class="text-danger">*</span></label>
                            <input type="number" name="montant" class="form-control" step="0.01" required>
                        </div>

                        <!-- Date Dépense -->
                        <div class="col-md-4">
                            <label class="form-label">Date Dépense <span class="text-danger">*</span></label>
                            <input type="date" name="date_depense" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                        </div>

                        <!-- Facture Reçue (si type = facture_recue) -->
                        <div class="col-md-8" id="factureRecueDiv" style="display: none;">
                            <label class="form-label">Facture Reçue</label>
                            <select name="facture_recue_id" class="form-select select2">
                                <option value="">Sélectionner une facture</option>
                                @foreach($facturess as $facturee)
                                    <option value="{{ $facture->id }}">
                                        {{ $facturee->numero_facture }} - {{ $facturee->fournisseur }} ({{ number_format($facture->montant_total, 2) }} DH)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Prime (si type = prime) -->
                        <div id="primeFields" style="display: none;" class="col-md-12">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Employé <span class="text-danger">*</span></label>
                                    <select name="user_mgmt_id" id="employeePrime" class="form-select select2">
                                        <option value="">Sélectionner un employé</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp['id'] }}">{{ $emp['name'] }} - {{ $emp['poste'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Type Prime</label>
                                    <select name="type_prime" class="form-select">
                                        <option value="">Type</option>
                                        <option value="performance">Performance</option>
                                        <option value="anciennete">Ancienneté</option>
                                        <option value="exceptionnelle">Exceptionnelle</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Motif</label>
                                    <input type="text" name="motif_prime" class="form-control" placeholder="Motif de la prime">
                                </div>
                            </div>
                        </div>

                        <!-- Publication (si type = publication) -->
                        <div id="publicationFields" style="display: none;" class="col-md-12">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Plateforme</label>
                                    <input type="text" name="plateforme" class="form-control" placeholder="Ex: Facebook Ads, Google Ads">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Campagne</label>
                                    <input type="text" name="campagne" class="form-control" placeholder="Nom de la campagne">
                                </div>
                            </div>
                        </div>

                        <!-- Transport (si type = transport) -->
                        <div id="transportFields" style="display: none;" class="col-md-12">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Type Transport</label>
                                    <select name="type_transport" class="form-select">
                                        <option value="">Type</option>
                                        <option value="taxi">Taxi</option>
                                        <option value="carburant">Carburant</option>
                                        <option value="location">Location véhicule</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Bénéficiaire</label>
                                    <input type="text" name="beneficiaire" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Trajet</label>
                                    <input type="text" name="trajet" class="form-control" placeholder="De... à...">
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Détails supplémentaires..."></textarea>
                        </div>

                        <!-- Fichiers Justificatifs -->
                        <div class="col-md-12">
                            <label class="form-label">Fichiers Justificatifs (PDF, JPG, PNG)</label>
                            <input type="file" name="fichiers_justificatifs[]" class="form-control" multiple accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">Taille max: 5MB par fichier</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('typeVariable')?.addEventListener('change', function() {
        // Masquer tous les champs spécifiques
        document.getElementById('factureRecueDiv').style.display = 'none';
        document.getElementById('primeFields').style.display = 'none';
        document.getElementById('publicationFields').style.display = 'none';
        document.getElementById('transportFields').style.display = 'none';

        // Afficher selon le type
        switch(this.value) {
            case 'facture_recue':
                document.getElementById('factureRecueDiv').style.display = 'block';
                break;
            case 'prime':
                document.getElementById('primeFields').style.display = 'block';
                break;
            case 'publication':
                document.getElementById('publicationFields').style.display = 'block';
                break;
            case 'transport':
                document.getElementById('transportFields').style.display = 'block';
                break;
        }
    });

    // Select2 init
    $(document).ready(function() {
        $('.select2').select2({
            dropdownParent: $('#createVariableModal'),
            width: '100%'
        });
    });
</script>