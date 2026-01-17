<!-- resources/views/charges/modals/details.blade.php -->
<div class="modal fade" id="modalDetailsCharge" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- En-tête Premium avec Gradient -->
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%); padding: 25px 30px;">
                <div class="d-flex align-items-center w-100">
                    <div class="rounded-circle p-3 bg-white bg-opacity-25 me-3">
                        <i class="fas fa-file-invoice fa-2x text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="modal-title text-white fw-bold mb-1">
                            Détails de la Charge
                        </h4>
                        <p class="text-white-50 mb-0 small">
                            <i class="fas fa-hashtag me-1"></i>
                            <span id="detailReference">CHG-2025-0001</span>
                        </p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body p-0" style="background: #f8f9fa;">
                <div class="container-fluid p-4">
                    
                    <!-- Badges de Statut en Haut -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                <div class="badge-stat" id="detailStatutBadge">
                                    <i class="fas fa-circle-notch fa-spin me-2"></i>
                                    <span id="detailStatut">Chargement...</span>
                                </div>
                                <div class="badge-stat" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);" id="detailTypeBadge">
                                    <i class="fas fa-tag me-2"></i>
                                    <span id="detailType">-</span>
                                </div>
                                <div class="badge-stat" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);" id="detailCategorieBadge">
                                    <i class="fas fa-folder me-2"></i>
                                    <span id="detailCategorie">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Colonne Gauche - Informations Principales -->
                        <div class="col-lg-6">
                            <!-- Card Informations Générales -->
                            <div class="card border-0 shadow-sm h-100 card-hover">
                                <div class="card-header bg-white border-0 pb-0">
                                    <h6 class="fw-bold mb-0" style="color: #C2185B;">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Informations Générales
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Libellé -->
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                        <div class="detail-content">
                                            <label class="detail-label">Libellé</label>
                                            <p class="detail-value" id="detailLibelle">-</p>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-align-left"></i>
                                        </div>
                                        <div class="detail-content">
                                            <label class="detail-label">Description</label>
                                            <p class="detail-value" id="detailDescription">-</p>
                                        </div>
                                    </div>

                                    <!-- Fournisseur -->
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-user-tie"></i>
                                        </div>
                                        <div class="detail-content">
                                            <label class="detail-label">Fournisseur</label>
                                            <p class="detail-value" id="detailFournisseur">-</p>
                                        </div>
                                    </div>

                                    <!-- Téléphone -->
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="detail-content">
                                            <label class="detail-label">Téléphone</label>
                                            <p class="detail-value" id="detailTelephone">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Colonne Droite - Informations Financières -->
                        <div class="col-lg-6">
                            <!-- Card Montant -->
                            <div class="card border-0 shadow-sm mb-3 card-hover" style="background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);">
                                <div class="card-body text-white text-center py-4">
                                    <i class="fas fa-money-bill-wave fa-3x mb-3 opacity-75"></i>
                                    <h6 class="text-white-50 text-uppercase mb-2">Montant Total</h6>
                                    <h2 class="fw-bold mb-0" id="detailMontant">0.00 DH</h2>
                                </div>
                            </div>

                            <!-- Card Paiement -->
                            <div class="card border-0 shadow-sm card-hover">
                                <div class="card-header bg-white border-0 pb-0">
                                    <h6 class="fw-bold mb-0 text-success">
                                        <i class="fas fa-credit-card me-2"></i>
                                        Informations de Paiement
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <!-- Montant Payé -->
                                    <div class="detail-item">
                                        <div class="detail-icon bg-success">
                                            <i class="fas fa-check-circle text-white"></i>
                                        </div>
                                        <div class="detail-content">
                                            <label class="detail-label">Montant Payé</label>
                                            <p class="detail-value text-success" id="detailMontantPaye">-</p>
                                        </div>
                                    </div>

                                    <!-- Reste à Payer -->
                                    <div class="detail-item" id="detailResteSection">
                                        <div class="detail-icon bg-danger">
                                            <i class="fas fa-exclamation-circle text-white"></i>
                                        </div>
                                        <div class="detail-content">
                                            <label class="detail-label">Reste à Payer</label>
                                            <p class="detail-value text-danger" id="detailResteAPayer">-</p>
                                        </div>
                                    </div>

                                    <!-- Mode de Paiement -->
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <div class="detail-content">
                                            <label class="detail-label">Mode de Paiement</label>
                                            <p class="detail-value" id="detailModePaiement">-</p>
                                        </div>
                                    </div>

                                    <!-- Référence Paiement -->
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-hashtag"></i>
                                        </div>
                                        <div class="detail-content">
                                            <label class="detail-label">Référence</label>
                                            <p class="detail-value" id="detailReferencePaiement">-</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Dates (Pleine Largeur) -->
                        <div class="col-12">
                            <div class="card border-0 shadow-sm card-hover">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <div class="date-box">
                                                <div class="date-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                    <i class="fas fa-calendar-day"></i>
                                                </div>
                                                <label class="date-label">Date de Charge</label>
                                                <p class="date-value" id="detailDateCharge">-</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="date-box">
                                                <div class="date-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                                <label class="date-label">Date d'Échéance</label>
                                                <p class="date-value" id="detailDateEcheance">-</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="date-box">
                                                <div class="date-icon" style="background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <label class="date-label">Date de Création</label>
                                                <p class="date-value" id="detailDateCreation">-</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Récurrence (si applicable) -->
                        <div class="col-12" id="detailRecurrenceSection" style="display: none;">
                            <div class="card border-0 shadow-sm card-hover" style="border-left: 4px solid #F57C00 !important;">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle p-3 me-3" style="background: rgba(245, 124, 0, 0.1);">
                                            <i class="fas fa-sync-alt fa-2x" style="color: #F57C00;"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1" style="color: #F57C00;">
                                                <i class="fas fa-infinity me-2"></i>Charge Récurrente
                                            </h6>
                                            <p class="mb-0 text-muted">
                                                Fréquence: <strong id="detailFrequence">-</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section Notes (si présentes) -->
                        <div class="col-12" id="detailNotesSection" style="display: none;">
                            <div class="card border-0 shadow-sm card-hover" style="background: #FFF3E0;">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3" style="color: #F57C00;">
                                        <i class="fas fa-sticky-note me-2"></i>Notes Internes
                                    </h6>
                                    <p class="mb-0" id="detailNotes" style="color: #666;"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Section Facture (si présente) -->
                        <div class="col-12" id="detailFactureSection" style="display: none;">
                            <div class="card border-0 shadow-sm card-hover">
                                <div class="card-body text-center py-4">
                                    <i class="fas fa-file-pdf fa-3x mb-3" style="color: #C2185B;"></i>
                                    <h6 class="fw-bold mb-3">Facture Attachée</h6>
                                    <a href="#" id="detailFactureLien" class="btn btn-lg text-white" style="background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);" target="_blank">
                                        <i class="fas fa-download me-2"></i>Télécharger la Facture
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Créateur (Nouvelle section à ajouter) -->
<div class="col-12" id="detailUserSection" >
    <div class="card border-0 shadow-sm card-hover" style="border-left: 4px solid #667eea !important;">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="rounded-circle p-3 me-3" style="background: rgba(102, 126, 234, 0.1);">
                    <i class="fas fa-user fa-2x" style="color: #667eea;"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1" style="color: #667eea;">
                        <i class="fas fa-user-circle me-2"></i>Créé par
                    </h6>
                    <p class="mb-0">
                        <strong id="detailUserName">-</strong>
                        <br>
                        <small class="text-muted">
                            <i class="fas fa-envelope me-1"></i>
                            <span id="detailUserEmail">-</span>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

            <!-- Footer Premium -->
            <div class="modal-footer bg-white border-0" style="padding: 20px 30px;">
                <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Fermer
                </button>
                <button type="button" class="btn btn-lg px-4 text-white" style="background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);" onclick="imprimerCharge()">
                    <i class="fas fa-print me-2"></i>Imprimer
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Badges de Statut Premium */
.badge-stat {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);
    color: white;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.95rem;
    box-shadow: 0 4px 15px rgba(194, 24, 91, 0.3);
    transition: all 0.3s ease;
}

.badge-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
}

/* Cards avec Hover Effect */
.card-hover {
    transition: all 0.3s ease;
    border-radius: 12px !important;
    overflow: hidden;
}

.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

/* Detail Items */
.detail-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
}

.detail-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.detail-icon {
    width: 45px;
    height: 45px;
    background: linear-gradient(135deg, #C2185B 0%, #D32F2F 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
    box-shadow: 0 4px 10px rgba(194, 24, 91, 0.2);
}

.detail-icon i {
    color: white;
    font-size: 1.2rem;
}

.detail-content {
    flex-grow: 1;
}

.detail-label {
    font-size: 0.8rem;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 5px;
    display: block;
    font-weight: 600;
}

.detail-value {
    font-size: 1.05rem;
    color: #333;
    margin: 0;
    font-weight: 500;
    line-height: 1.5;
}

/* Date Boxes */
.date-box {
    padding: 20px;
    border-radius: 12px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.date-box:hover {
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.date-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.date-icon i {
    color: white;
    font-size: 1.5rem;
}

.date-label {
    font-size: 0.85rem;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
    display: block;
    font-weight: 600;
}

.date-value {
    font-size: 1.15rem;
    color: #333;
    margin: 0;
    font-weight: 700;
}

/* Animation d'entrée */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal.show .modal-dialog {
    animation: fadeInUp 0.4s ease;
}

/* Responsive */
@media (max-width: 768px) {
    .badge-stat {
        font-size: 0.85rem;
        padding: 10px 18px;
    }
    
    .detail-icon {
        width: 40px;
        height: 40px;
    }
    
    .date-icon {
        width: 50px;
        height: 50px;
    }
}
</style>

@push('scripts')
<script>
function voirCharge(id) {
    console.log('🔍 Chargement des détails de la charge ID:', id);
    
    fetch(`/charges/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('✅ Données reçues:', data);
            
            if(data.success && data.charge) {
                const charge = data.charge;
                
                // ========== FONCTIONS UTILITAIRES ==========
                const formatDate = (dateString) => {
                    if (!dateString) return 'Non définie';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('fr-FR', { 
                        day: '2-digit', 
                        month: 'long', 
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                };
                
                const formatDateSimple = (dateString) => {
                    if (!dateString) return 'Non définie';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('fr-FR', { 
                        day: '2-digit', 
                        month: 'long', 
                        year: 'numeric'
                    });
                };
                
                const formatMontant = (montant) => {
                    return new Intl.NumberFormat('fr-FR', { 
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2 
                    }).format(montant || 0) + ' DH';
                };
                
                // ========== INFORMATIONS PRINCIPALES ==========
                document.getElementById('detailReference').textContent = charge.numero_reference || '-';
                document.getElementById('detailLibelle').textContent = charge.libelle || '-';
                document.getElementById('detailDescription').textContent = charge.description || 'Aucune description';
                document.getElementById('detailFournisseur').textContent = charge.fournisseur || 'Non spécifié';
                document.getElementById('detailTelephone').textContent = charge.fournisseur_telephone || 'Non spécifié';
                
                // ========== MONTANTS ==========
                document.getElementById('detailMontant').textContent = formatMontant(charge.montant);
                document.getElementById('detailMontantPaye').textContent = formatMontant(charge.montant_paye);
                
                // Reste à payer
                const reste = parseFloat(charge.montant) - parseFloat(charge.montant_paye || 0);
                document.getElementById('detailResteAPayer').textContent = formatMontant(reste);
                document.getElementById('detailResteSection').style.display = reste > 0 ? 'flex' : 'none';
                
                // ========== MODE DE PAIEMENT ==========
                const modesPaiement = {
                    'especes': '💵 Espèces',
                    'virement': '🏦 Virement',
                    'cheque': '📝 Chèque',
                    'carte': '💳 Carte',
                    'autre': '🔄 Autre'
                };
                document.getElementById('detailModePaiement').textContent = 
                    modesPaiement[charge.mode_paiement] || charge.mode_paiement || 'Non spécifié';
                document.getElementById('detailReferencePaiement').textContent = 
                    charge.reference_paiement || 'Non spécifiée';
                
                // ========== DATES ==========
                document.getElementById('detailDateCharge').textContent = formatDateSimple(charge.date_charge);
                document.getElementById('detailDateEcheance').textContent = formatDateSimple(charge.date_echeance);
                document.getElementById('detailDateCreation').textContent = formatDate(charge.created_at);
                
                // ========== BADGE TYPE ==========
                const typeElement = document.getElementById('detailType');
                typeElement.textContent = charge.type === 'fixe' ? '🔒 Fixe' : '📊 Variable';
                
                // ========== BADGE CATÉGORIE ==========
                const categorieElement = document.getElementById('detailCategorie');
                if (charge.category) {
                    categorieElement.textContent = charge.category.nom;
                    // Optionnel: changer la couleur du badge
                    const categorieBadge = document.getElementById('detailCategorieBadge');
                    if (charge.category.couleur) {
                        categorieBadge.style.background = charge.category.couleur;
                    }
                } else {
                    categorieElement.textContent = '📁 Sans catégorie';
                }
                
                // ========== BADGE STATUT ==========
                const statutBadge = document.getElementById('detailStatutBadge');
                const statutElement = document.getElementById('detailStatut');
                
                if (charge.statut_paiement === 'paye') {
                    statutBadge.style.background = 'linear-gradient(135deg, #28a745 0%, #20c997 100%)';
                    statutElement.innerHTML = '<i class="fas fa-check-circle me-2"></i>Payée';
                } else if (charge.statut_paiement === 'partiel') {
                    statutBadge.style.background = 'linear-gradient(135deg, #ffc107 0%, #ff9800 100%)';
                    statutElement.innerHTML = '<i class="fas fa-clock me-2"></i>Paiement Partiel';
                } else {
                    statutBadge.style.background = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                    statutElement.innerHTML = '<i class="fas fa-times-circle me-2"></i>Impayée';
                }
                
                // ========== RÉCURRENCE ==========
                const recurrenceSection = document.getElementById('detailRecurrenceSection');
                if (charge.recurrent) {
                    recurrenceSection.style.display = 'block';
                    const frequences = {
                        'mensuel': '📅 Mensuelle',
                        'trimestriel': '📆 Trimestrielle',
                        'annuel': '🗓️ Annuelle',
                        'unique': '🔹 Unique'
                    };
                    document.getElementById('detailFrequence').textContent = 
                        frequences[charge.frequence] || charge.frequence;
                } else {
                    recurrenceSection.style.display = 'none';
                }
                
                // ========== NOTES ==========
                const notesSection = document.getElementById('detailNotesSection');
                if (charge.notes && charge.notes.trim() !== '') {
                    notesSection.style.display = 'block';
                    document.getElementById('detailNotes').textContent = charge.notes;
                } else {
                    notesSection.style.display = 'none';
                }
                
                // ========== FACTURE ==========
                const factureSection = document.getElementById('detailFactureSection');
                if (charge.facture_path) {
                    factureSection.style.display = 'block';
                    document.getElementById('detailFactureLien').href = `/storage/${charge.facture_path}`;
                } else {
                    factureSection.style.display = 'none';
                }
                
                // ========== INFORMATIONS UTILISATEUR (CRÉATEUR) ==========
                const userSection = document.getElementById('detailUserSection');
                if (charge.user) {
                    userSection.style.display = 'block';
                    document.getElementById('detailUserName').textContent = charge.user.name || 'Utilisateur inconnu';
                    document.getElementById('detailUserEmail').textContent = charge.user.email || '-';
                } else {
                    userSection.style.display = 'none';
                }
                
                // ========== OUVRIR LE MODAL ==========
                const modal = new bootstrap.Modal(document.getElementById('modalDetailsCharge'));
                modal.show();
            } else {
                throw new Error('Données invalides reçues du serveur');
            }
        })
        .catch(error => {
            console.error('❌ Erreur:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: 'Impossible de charger les détails de la charge',
                confirmButtonColor: '#D32F2F'
            });
        });
}

function imprimerCharge() {
    window.print();
}

// Rendre les fonctions globales
window.voirCharge = voirCharge;
window.imprimerCharge = imprimerCharge;
// Rendre la fonction globale
window.voirCharge = voirCharge;
</script>
@endpush