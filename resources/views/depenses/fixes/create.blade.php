<!-- Modal Create Dépense Fixe -->
<div class="modal fade" id="createFixeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('depenses.fixes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle"></i> Nouvelle Dépense Fixe
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Type -->
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select name="type" id="typeFixe" class="form-select" required>
                                <option value="">Sélectionner un type</option>
                                <option value="loyer">🏠 Loyer</option>
                                <option value="internet">🌐 Internet</option>
                                <option value="mobile">📱 Mobile</option>
                                <option value="srmc">🏢 SRMC</option>
                                <option value="femme_menage">🧹 Femme de ménage</option>
                                <option value="frais_aups">📋 Frais AUPS</option>
                                <option value="autre">📌 Autre</option>
                            </select>
                        </div>

                        <!-- Libellé (si autre) -->
                        <div class="col-md-6" id="libelleFixeDiv" style="display: none;">
                            <label class="form-label">Libellé <span class="text-danger">*</span></label>
                            <input type="text" name="libelle" class="form-control" placeholder="Nom de la dépense">
                        </div>

                        <!-- Montant Mensuel -->
                        <div class="col-md-6">
                            <label class="form-label">Montant Mensuel (DH) <span class="text-danger">*</span></label>
                            <input type="number" name="montant_mensuel" class="form-control" step="0.01" required>
                        </div>

                        <!-- Date Début -->
                        <div class="col-md-6">
                            <label class="form-label">Date Début <span class="text-danger">*</span></label>
                            <input type="date" name="date_debut" class="form-control" required>
                        </div>

                        <!-- Date Fin -->
                        <div class="col-md-6">
                            <label class="form-label">Date Fin (optionnel)</label>
                            <input type="date" name="date_fin" class="form-control">
                        </div>

                        <!-- Statut -->
                        <div class="col-md-6">
                            <label class="form-label">Statut <span class="text-danger">*</span></label>
                            <select name="statut" class="form-select" required>
                                <option value="actif">✅ Actif</option>
                                <option value="inactif">❌ Inactif</option>
                                <option value="suspendu">⏸️ Suspendu</option>
                            </select>
                        </div>

                        <!-- Jour Paiement -->
                        <div class="col-md-6">
                            <label class="form-label">Jour de Paiement <span class="text-danger">*</span></label>
                            <input type="number" name="jour_paiement" class="form-control" min="1" max="31" required>
                        </div>

                        <!-- Rappel -->
                        <div class="col-md-6">
                            <label class="form-label">Rappel avant (jours) <span class="text-danger">*</span></label>
                            <input type="number" name="rappel_avant_jours" class="form-control" min="1" max="30" value="3" required>
                        </div>

                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" name="rappel_actif" class="form-check-input" id="rappelActifCreate" value="1" checked>
                                <label class="form-check-label" for="rappelActifCreate">
                                    🔔 Activer les rappels de paiement
                                </label>
                            </div>
                        </div>

                        <!-- Référence Contrat -->
                        <div class="col-md-12">
                            <label class="form-label">Référence Contrat</label>
                            <input type="text" name="reference_contrat" class="form-control" placeholder="Ex: CONT-2024-001">
                        </div>

                        <!-- Fichier Contrat -->
                        <div class="col-md-12">
                            <label class="form-label">Fichier Contrat (PDF)</label>
                            <input type="file" name="fichier_contrat" class="form-control" accept=".pdf">
                            <small class="text-muted">Taille max: 5MB</small>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Détails supplémentaires..."></textarea>
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
    document.getElementById('typeFixe')?.addEventListener('change', function() {
        const libelleDiv = document.getElementById('libelleFixeDiv');
        if (this.value === 'autre') {
            libelleDiv.style.display = 'block';
            libelleDiv.querySelector('input').required = true;
        } else {
            libelleDiv.style.display = 'none';
            libelleDiv.querySelector('input').required = false;
        }
    });
</script>