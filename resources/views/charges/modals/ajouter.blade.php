<!-- resources/views/charges/modals/ajouter.blade.php -->
<div class="modal fade" id="modalAjouterCharge" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('charges.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>
                        Nouvelle Charge
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Libellé -->
                        <div class="col-md-8">
                            <label class="form-label fw-bold">
                                Libellé <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="libelle" class="form-control" required placeholder="Ex: Loyer Janvier 2026">
                        </div>

                        <!-- Type - Auto depuis catégorie -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                Type <span class="text-danger">*</span>
                                <small class="text-muted">(Auto)</small>
                            </label>
                            <select name="type" id="chargeType" class="form-select" required>
                                <option value="fixe">Fixe</option>
                                <option value="variable" selected>Variable</option>
                            </select>
                        </div>

                        <!-- Catégorie -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Catégorie
                            </label>
                            <select name="charge_category_id" id="categorySelect" class="form-select">
                                <option value="">Aucune catégorie</option>
                                @php
                                    $categoriesModal = $categories ?? \App\Models\ChargeCategory::actif()->orderBy('nom')->get();
                                @endphp
                                
                                @forelse($categoriesModal as $cat)
                                    <option value="{{ $cat->id }}" data-type="{{ $cat->type_defaut }}">
                                        {{ $cat->nom }} ({{ ucfirst($cat->type_defaut) }})
                                    </option>
                                @empty
                                    <option disabled>Aucune catégorie disponible</option>
                                @endforelse
                            </select>
                            @if(!isset($categories) || $categories->isEmpty())
                                <small class="text-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Aucune catégorie active. Crée-en via le bouton "Catégories"
                                </small>
                            @endif
                        </div>

                        <!-- Montant -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Montant (DH) <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="montant" class="form-control" step="0.01" required placeholder="0.00">
                        </div>

                        <!-- Date charge -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Date Charge <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="date_charge" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Date échéance -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Date Échéance
                            </label>
                            <input type="date" name="date_echeance" class="form-control">
                        </div>

                        <!-- Fournisseur -->
                        <div class="col-md-8">
                            <label class="form-label fw-bold">
                                Fournisseur
                            </label>
                            <input type="text" name="fournisseur" class="form-control" placeholder="Nom du fournisseur">
                        </div>

                        <!-- Téléphone -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                Téléphone
                            </label>
                            <input type="text" name="fournisseur_telephone" class="form-control" placeholder="0600000000">
                        </div>

                        <!-- Mode paiement -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Mode Paiement <span class="text-danger">*</span>
                            </label>
                            <select name="mode_paiement" class="form-select" required>
                                <option value="especes" selected>Espèces</option>
                                <option value="virement">Virement</option>
                                <option value="cheque">Chèque</option>
                                <option value="carte">Carte</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <!-- Référence paiement -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Référence Paiement
                            </label>
                            <input type="text" name="reference_paiement" class="form-control" placeholder="N° chèque, virement...">
                        </div>

                        <!-- Statut paiement -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Statut Paiement <span class="text-danger">*</span>
                            </label>
                            <select name="statut_paiement" class="form-select" id="addStatutPaiement" required>
                                <option value="paye" selected>Payée</option>
                                <option value="impaye">Impayée</option>
                                <option value="partiel">Paiement Partiel</option>
                            </select>
                        </div>

                        <!-- Montant payé -->
                        <div class="col-md-6" id="addDivMontantPaye" style="display: none;">
                            <label class="form-label fw-bold">
                                Montant Payé (DH)
                            </label>
                            <input type="number" name="montant_paye" class="form-control" step="0.01" placeholder="0.00">
                        </div>

                        <!-- Récurrent - CORRIGÉ -->
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="addRecurrent" name="recurrent">
                                <label class="form-check-label fw-bold" for="addRecurrent">
                                    <i class="fas fa-sync-alt me-1"></i>
                                    Charge Récurrente
                                </label>
                            </div>
                        </div>

                        <!-- Fréquence - CORRIGÉ -->
                        <div class="col-md-12" id="addDivFrequence" style="display: none;">
                            <label class="form-label fw-bold">
                                Fréquence
                            </label>
                            <select name="frequence" class="form-select">
                                <option value="mensuel" selected>Mensuel</option>
                                <option value="trimestriel">Trimestriel</option>
                                <option value="annuel">Annuel</option>
                                <option value="unique">Unique</option>
                            </select>
                        </div>

                        <!-- Facture -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-file-pdf me-1"></i>
                                Facture / Justificatif
                            </label>
                            <input type="file" name="facture" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                            <small class="text-muted">PDF ou Image (Max 5MB)</small>
                        </div>

                        <!-- Description -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                Description
                            </label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Détails supplémentaires..."></textarea>
                        </div>

                        <!-- Notes -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">
                                Notes
                            </label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Notes internes..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Script modal ajouter charge chargé');
    
    // ========== AUTO-REMPLISSAGE TYPE DEPUIS CATÉGORIE ==========
    const categorySelect = document.getElementById('categorySelect');
    const chargeType = document.getElementById('chargeType');
    
    if (categorySelect && chargeType) {
        categorySelect.addEventListener('change', async function() {
            const categoryId = this.value;
            
            if (!categoryId) {
                chargeType.value = 'variable';
                return;
            }
            
            try {
                const response = await fetch(`/charges/categories/${categoryId}/details`);
                const data = await response.json();
                
                if (data.success && data.category) {
                    chargeType.value = data.category.type_defaut;
                    
                    // Animation
                    chargeType.classList.add('border-success', 'border-2');
                    setTimeout(() => {
                        chargeType.classList.remove('border-success', 'border-2');
                    }, 1000);
                    
                    console.log(`✅ Type mis à jour: ${data.category.type_defaut}`);
                }
            } catch (error) {
                console.error('❌ Erreur récupération catégorie:', error);
            }
        });
    }
    
    // ========== AFFICHER MONTANT PAYÉ SI PAIEMENT PARTIEL ==========
    const statutPaiement = document.getElementById('addStatutPaiement');
    const divMontantPaye = document.getElementById('addDivMontantPaye');
    
    if (statutPaiement && divMontantPaye) {
        statutPaiement.addEventListener('change', function() {
            console.log('Statut changé:', this.value);
            divMontantPaye.style.display = this.value === 'partiel' ? 'block' : 'none';
        });
    }
    
    // ========== AFFICHER FRÉQUENCE SI RÉCURRENT - CORRIGÉ ==========
    const recurrent = document.getElementById('addRecurrent');
    const divFrequence = document.getElementById('addDivFrequence');
    
    if (recurrent && divFrequence) {
        recurrent.addEventListener('change', function() {
            const isChecked = this.checked;
            console.log('🔄 Récurrent:', isChecked);
            
            if (isChecked) {
                divFrequence.style.display = 'block';
                divFrequence.classList.add('animate__animated', 'animate__fadeIn');
            } else {
                divFrequence.style.display = 'none';
            }
        });
        
        // Debug initial
        console.log('✅ Event listener récurrent attaché');
    } else {
        console.error('❌ Éléments recurrent ou divFrequence introuvables');
    }
});
</script>
@endpush