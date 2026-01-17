<!-- resources/views/charges/modals/modifier.blade.php -->
<div class="modal fade" id="modalModifierCharge" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" id="formModifierCharge" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="editChargeId" name="charge_id">
                
                <!-- En-tête avec couleurs app -->
                <div class="modal-header" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border: none;">
                    <h5 class="modal-title text-white fw-bold">
                        <i class="fas fa-edit me-2"></i>
                        Modifier la Charge
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="background: #f8f9fa;">
                    <!-- Tabs de Navigation -->
                    <ul class="nav nav-pills nav-fill mb-4" id="editTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="edit-info-tab" data-bs-toggle="pill" data-bs-target="#edit-info" type="button">
                                <i class="fas fa-info-circle me-2"></i>Informations
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-paiement-tab" data-bs-toggle="pill" data-bs-target="#edit-paiement" type="button">
                                <i class="fas fa-credit-card me-2"></i>Paiement
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-avance-tab" data-bs-toggle="pill" data-bs-target="#edit-avance" type="button">
                                <i class="fas fa-cog me-2"></i>Options Avancées
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="editTabsContent">
                        <!-- TAB 1: INFORMATIONS GÉNÉRALES -->
                        <div class="tab-pane fade show active" id="edit-info" role="tabpanel">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3" style="color: #C2185B;">
                                        <i class="fas fa-file-alt me-2"></i>Détails de la Charge
                                    </h6>
                                    
                                    <div class="row g-3">
                                        <!-- Libellé -->
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold">
                                                Libellé <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="libelle" id="editLibelle" class="form-control form-control-lg" required>
                                        </div>

                                        <!-- Type -->
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">
                                                Type <span class="text-danger">*</span>
                                            </label>
                                            <select name="type" id="editType" class="form-select form-select-lg" required>
                                                <option value="fixe">🔒 Fixe</option>
                                                <option value="variable">📊 Variable</option>
                                            </select>
                                        </div>

                                        <!-- Catégorie -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-tag me-1"></i>Catégorie
                                            </label>
                                            <select name="charge_category_id" id="editCategoryId" class="form-select form-select-lg">
                                                <option value="">Sans catégorie</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Montant -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                Montant (DH) <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group input-group-lg">
                                                <input type="number" name="montant" id="editMontant" class="form-control" step="0.01" required>
                                                <span class="input-group-text text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">DH</span>
                                            </div>
                                        </div>

                                        <!-- Dates -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-calendar-day me-1"></i>Date Charge <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="date_charge" id="editDateCharge" class="form-control form-control-lg" required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-calendar-check me-1"></i>Date Échéance
                                            </label>
                                            <input type="date" name="date_echeance" id="editDateEcheance" class="form-control form-control-lg">
                                        </div>

                                        <!-- Fournisseur -->
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-user-tie me-1"></i>Fournisseur
                                            </label>
                                            <input type="text" name="fournisseur" id="editFournisseur" class="form-control form-control-lg" placeholder="Nom du fournisseur">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-phone me-1"></i>Téléphone
                                            </label>
                                            <input type="text" name="fournisseur_telephone" id="editFournisseurTelephone" class="form-control form-control-lg" placeholder="06xxxxxxxx">
                                        </div>

                                        <!-- Description -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-align-left me-1"></i>Description
                                            </label>
                                            <textarea name="description" id="editDescription" class="form-control" rows="3" placeholder="Détails supplémentaires..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: PAIEMENT -->
                        <div class="tab-pane fade" id="edit-paiement" role="tabpanel">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="text-success fw-bold mb-3">
                                        <i class="fas fa-money-bill-wave me-2"></i>Informations de Paiement
                                    </h6>
                                    
                                    <div class="row g-3">
                                        <!-- Statut Paiement -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                Statut <span class="text-danger">*</span>
                                            </label>
                                            <select name="statut_paiement" id="editStatutPaiement" class="form-select form-select-lg" required>
                                                <option value="paye">✅ Payée</option>
                                                <option value="impaye">❌ Impayée</option>
                                                <option value="partiel">⏳ Paiement Partiel</option>
                                            </select>
                                        </div>

                                        <!-- Mode Paiement -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                Mode de Paiement <span class="text-danger">*</span>
                                            </label>
                                            <select name="mode_paiement" id="editModePaiement" class="form-select form-select-lg" required>
                                                <option value="especes">💵 Espèces</option>
                                                <option value="virement">🏦 Virement</option>
                                                <option value="cheque">📝 Chèque</option>
                                                <option value="carte">💳 Carte</option>
                                                <option value="autre">🔄 Autre</option>
                                            </select>
                                        </div>

                                        <!-- Montant Payé (conditionnel) -->
                                        <div class="col-md-6" id="editDivMontantPaye" style="display: none;">
                                            <label class="form-label fw-bold">
                                                Montant Payé (DH)
                                            </label>
                                            <div class="input-group input-group-lg">
                                                <input type="number" name="montant_paye" id="editMontantPaye" class="form-control" step="0.01" placeholder="0.00">
                                                <span class="input-group-text bg-success text-white">DH</span>
                                            </div>
                                        </div>

                                        <!-- Référence Paiement -->
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-hashtag me-1"></i>Référence Paiement
                                            </label>
                                            <input type="text" name="reference_paiement" id="editReferencePaiement" class="form-control form-control-lg" placeholder="N° chèque, virement...">
                                        </div>

                                        <!-- Facture -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-file-pdf me-1"></i>Modifier la Facture
                                            </label>
                                            <input type="file" name="facture" class="form-control form-control-lg" accept=".pdf,.jpg,.jpeg,.png">
                                            <small class="text-muted">PDF ou Image (Max 5MB)</small>
                                        </div>

                                        <!-- Section Ajouter Paiement Partiel -->
                                        <div class="col-12">
                                            <div class="card bg-light border-0">
                                                <div class="card-body">
                                                    <h6 class="text-info fw-bold mb-3">
                                                        <i class="fas fa-plus-circle me-2"></i>Ajouter un Paiement Partiel
                                                    </h6>
                                                    <div class="row g-3">
                                                        <div class="col-md-8">
                                                            <input type="number" id="nouveauPaiement" class="form-control form-control-lg" step="0.01" placeholder="Montant à ajouter (DH)">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button type="button" class="btn btn-info btn-lg w-100" onclick="ajouterPaiementPartiel()">
                                                                <i class="fas fa-plus me-2"></i>Ajouter
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: OPTIONS AVANCÉES -->
                        <div class="tab-pane fade" id="edit-avance" role="tabpanel">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="text-warning fw-bold mb-3">
                                        <i class="fas fa-cogs me-2"></i>Paramètres Avancés
                                    </h6>
                                    
                                    <div class="row g-3">
                                        <!-- Récurrence -->
                                        <div class="col-12">
                                            <div class="card text-white border-0" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                                                <div class="card-body">
                                                    <div class="form-check form-switch form-switch-lg">
                                                        <input class="form-check-input" type="checkbox" id="editRecurrent" name="recurrent" style="cursor: pointer;">
                                                        <label class="form-check-label fw-bold ms-2" for="editRecurrent" style="cursor: pointer;">
                                                            <i class="fas fa-sync-alt me-2"></i>Charge Récurrente
                                                        </label>
                                                    </div>
                                                    <small class="d-block mt-2 opacity-75">
                                                        Activer pour générer automatiquement les prochaines échéances
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fréquence (conditionnel) -->
                                        <div class="col-12" id="editDivFrequence" style="display: none;">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-calendar-alt me-1"></i>Fréquence de Récurrence
                                            </label>
                                            <select name="frequence" id="editFrequence" class="form-select form-select-lg">
                                                <option value="mensuel">📅 Mensuel</option>
                                                <option value="trimestriel">📆 Trimestriel</option>
                                                <option value="annuel">🗓️ Annuel</option>
                                                <option value="unique">🔹 Unique</option>
                                            </select>
                                        </div>

                                        <!-- Notes -->
                                        <div class="col-12">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-sticky-note me-1"></i>Notes Internes
                                            </label>
                                            <textarea name="notes" id="editNotes" class="form-control" rows="4" placeholder="Notes privées, commentaires..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer avec couleurs app -->
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                        <i class="fas fa-save me-2"></i>Enregistrer les Modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Styles pour les tabs avec couleurs app */
.nav-pills .nav-link {
    color: #C2185B;
    font-weight: 600;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.nav-pills .nav-link:hover {
    background: rgba(194, 24, 91, 0.1);
    transform: translateY(-2px);
}

.nav-pills .nav-link.active {
    background: linear-gradient(135deg, #C2185B, #D32F2F);
    box-shadow: 0 4px 15px rgba(194, 24, 91, 0.4);
}

/* Switch personnalisé */
.form-switch .form-check-input {
    width: 3em;
    height: 1.5em;
}

.form-switch .form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
}

/* Cards avec effets */
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

/* Animation d'entrée */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.tab-pane.active {
    animation: slideIn 0.4s ease;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎨 Modal Modifier chargé');
    
    // ========== AFFICHER MONTANT PAYÉ SI PARTIEL ==========
    const editStatut = document.getElementById('editStatutPaiement');
    const editDivMontantPaye = document.getElementById('editDivMontantPaye');
    
    if (editStatut && editDivMontantPaye) {
        editStatut.addEventListener('change', function() {
            editDivMontantPaye.style.display = this.value === 'partiel' ? 'block' : 'none';
        });
    }
    
    // ========== AFFICHER FRÉQUENCE SI RÉCURRENT ==========
    const editRecurrent = document.getElementById('editRecurrent');
    const editDivFrequence = document.getElementById('editDivFrequence');
    
    if (editRecurrent && editDivFrequence) {
        editRecurrent.addEventListener('change', function() {
            editDivFrequence.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // ========== INITIALISER VISIBILITÉ AU CHARGEMENT ==========
    if (editStatut && editDivMontantPaye) {
        editDivMontantPaye.style.display = editStatut.value === 'partiel' ? 'block' : 'none';
    }
    
    if (editRecurrent && editDivFrequence) {
        editDivFrequence.style.display = editRecurrent.checked ? 'block' : 'none';
    }
});

// ========== FONCTION POUR CHARGER LES DONNÉES DANS LE MODAL ==========
function chargerDonneesCharge(chargeId) {
    console.log('📥 Chargement charge ID:', chargeId);
    
    fetch(`/charges/${chargeId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const charge = data.charge;
                console.log('✅ Données reçues:', charge);
                
                // Remplir les champs
                document.getElementById('editChargeId').value = charge.id;
                document.getElementById('editLibelle').value = charge.libelle || '';
                document.getElementById('editType').value = charge.type || 'fixe';
                document.getElementById('editCategoryId').value = charge.charge_category_id || '';
                document.getElementById('editMontant').value = charge.montant || '';
                
                // ✅ CORRECTION DES DATES - Format YYYY-MM-DD
                if (charge.date_charge) {
                    // Si la date est au format ISO (avec heure)
                    const dateCharge = charge.date_charge.split('T')[0];
                    document.getElementById('editDateCharge').value = dateCharge;
                    console.log('📅 Date charge définie:', dateCharge);
                }
                
                if (charge.date_echeance) {
                    const dateEcheance = charge.date_echeance.split('T')[0];
                    document.getElementById('editDateEcheance').value = dateEcheance;
                    console.log('📅 Date échéance définie:', dateEcheance);
                }
                
                document.getElementById('editFournisseur').value = charge.fournisseur || '';
                document.getElementById('editFournisseurTelephone').value = charge.fournisseur_telephone || '';
                document.getElementById('editDescription').value = charge.description || '';
                document.getElementById('editStatutPaiement').value = charge.statut_paiement || 'impaye';
                document.getElementById('editModePaiement').value = charge.mode_paiement || 'especes';
                document.getElementById('editMontantPaye').value = charge.montant_paye || '';
                document.getElementById('editReferencePaiement').value = charge.reference_paiement || '';
                document.getElementById('editRecurrent').checked = charge.recurrent || false;
                document.getElementById('editFrequence').value = charge.frequence || 'mensuel';
                document.getElementById('editNotes').value = charge.notes || '';
                
                // Afficher/masquer champs conditionnels
                document.getElementById('editDivMontantPaye').style.display = 
                    charge.statut_paiement === 'partiel' ? 'block' : 'none';
                document.getElementById('editDivFrequence').style.display = 
                    charge.recurrent ? 'block' : 'none';
                
                // Définir l'action du formulaire
                document.getElementById('formModifierCharge').action = `/charges/${charge.id}`;
                
                console.log('✅ Modal rempli avec succès');
            }
        })
        .catch(error => {
            console.error('❌ Erreur chargement:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Impossible de charger les données de la charge'
            });
        });
}

// ========== FONCTION AJOUTER PAIEMENT PARTIEL ==========
function ajouterPaiementPartiel() {
    const chargeId = document.getElementById('editChargeId').value;
    const montant = document.getElementById('nouveauPaiement').value;
    
    if (!montant || montant <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur!',
            text: 'Veuillez entrer un montant valide',
            confirmButtonColor: '#D32F2F'
        });
        return;
    }
    
    Swal.fire({
        title: 'Confirmer le paiement?',
        text: `Ajouter ${montant} DH à cette charge?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, ajouter',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/charges/${chargeId}/ajouter-paiement`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const montantInput = document.createElement('input');
            montantInput.type = 'hidden';
            montantInput.name = 'montant';
            montantInput.value = montant;
            form.appendChild(montantInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// ========== RENDRE LA FONCTION GLOBALE ==========
window.chargerDonneesCharge = chargerDonneesCharge;
</script>
@endpush