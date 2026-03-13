<x-app-layout>
    <div class="container-fluid">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="hight">
                    <i class="fas fa-plus-circle me-2"></i>
                    Nouveau Reçu UCG
                </h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('recus.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <form action="{{ route('recus.store') }}" method="POST" id="recuForm">
            @csrf

            <!-- ✅ Champ remise global caché (valeur 0 par défaut, sera calculé automatiquement) -->
            <input type="hidden" name="remise" value="0">

            <div class="row">
                <!-- Section Client -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations Client</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="client_nom" class="form-control @error('client_nom') is-invalid @enderror"
                                           value="{{ old('client_nom') }}" required>
                                    @error('client_nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" name="client_prenom" class="form-control" value="{{ old('client_prenom') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="client_telephone" class="form-control" value="{{ old('client_telephone') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="client_email" class="form-control" value="{{ old('client_email') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse</label>
                                    <textarea name="client_adresse" class="form-control" rows="2">{{ old('client_adresse') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Équipement & Garantie -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-laptop me-2"></i>Équipement & Garantie</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Équipement</label>
                                    <input type="text" name="equipement" class="form-control"
                                           placeholder="Ex: PC HP, Imprimante Canon..." value="{{ old('equipement') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Détails</label>
                                    <textarea name="details" class="form-control" rows="2"
                                              placeholder="Description du problème...">{{ old('details') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Type de Garantie <span class="text-danger">*</span></label>
                                    <select name="type_garantie" class="form-select" required>
                                        <option value="30_jours">30 jours</option>
                                        <option value="90_jours">90 jours</option>
                                        <option value="180_jours">180 jours (6 mois)</option>
                                        <option value="360_jours">360 jours (1 an)</option>
                                        <option value="sans_garantie">Sans garantie</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ✅ Filtre par Catégorie -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtre par Catégorie</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <select id="category-filter" class="form-select">
                                <option value="">-- Toutes les catégories --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <button type="button" id="reset-filter" class="btn btn-secondary">
                                <i class="fas fa-redo me-2"></i>Réinitialiser
                            </button>
                            <small class="text-muted ms-3" id="filter-info"></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Produits -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Produits</h5>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        <div class="item-row mb-3 p-3 border rounded" data-index="0">
                            <div class="row g-3">
                                <!-- Sélection Produit -->
                               <!-- Sélection Produit -->
<div class="col-md-4">
    <label class="form-label">Produit <span class="text-danger">*</span></label>
    <select name="items[0][produit_id]" class="form-select produit-select" required>
        <option value="">-- Sélectionner un produit --</option>
        @foreach($produits as $produit)
            <option value="{{ $produit->id }}"
                    data-lots="{{ json_encode($produit->lots_disponibles) }}"
                    data-stock="{{ $produit->quantite_stock }}"
                    data-category="{{ $produit->category_id }}"
                    data-has-variants="{{ $produit->variants->count() > 0 ? 'true' : 'false' }}">
                {{ $produit->nom }} (Stock total: {{ $produit->quantite_stock }})
            </option>
        @endforeach
    </select>
</div>

<!-- ✅ Lots disponibles -->
<div class="col-12 lots-container" style="display:none;">
    <label class="form-label fw-bold text-primary">
        <i class="fas fa-boxes me-1"></i>
        Choisir le lot à vendre
    </label>
    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover lots-table">
            <thead class="table-light">
                <tr>
                    <th>Choisir</th>
                    <th>Date Achat</th>
                    <th>Fournisseur</th>
                    <th>Stock Dispo</th>
                    <th>Prix Achat</th>
                    <th>Prix Vente</th>
                    <th>Marge</th>
                </tr>
            </thead>
            <tbody class="lots-tbody"></tbody>
        </table>
    </div>
    <!-- Champs cachés pour le lot sélectionné -->
    <input type="hidden" name="items[0][achat_id]"         class="lot-achat-id">
    <input type="hidden" name="items[0][prix_achat]"       class="lot-prix-achat">
    <input type="hidden" name="items[0][prix_unitaire]"    class="lot-prix-vente">
</div>

                                <!-- Sélection Variant -->
                                <div class="col-md-3 variant-container" style="display: none;">
                                    <label class="form-label">Variant (optionnel)</label>
                                    <select name="items[0][product_variant_id]" class="form-select variant-select">
                                        <option value="">-- Produit de base (sans variant) --</option>
                                    </select>
                                    <small class="text-muted variant-info"></small>
                                </div>

                                <!-- Quantité -->
                                <div class="col-md-1">
                                    <label class="form-label">Qté <span class="text-danger">*</span></label>
                                    <input type="number" name="items[0][quantite]" class="form-control quantite-input"
                                           min="1" value="1" required>
                                </div>

                                <!-- Prix unitaire -->
                                <div class="col-md-2">
                                    <label class="form-label">Prix Unit.</label>
                                    <input type="text" class="form-control prix-display" readonly>
                                </div>

                                <!-- Remise -->
                                <div class="col-md-2">
                                    <label class="form-label">Remise</label>
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="items[0][remise_appliquee]" class="remise-appliquee-input" value="0">
                                        <input type="hidden" name="items[0][remise_pourcentage]" class="remise-pourcentage-input" value="0">
                                        <input type="hidden" name="items[0][remise_montant]" class="remise-montant-input" value="0">
                                        <input type="text" class="form-control remise-display bg-light" readonly placeholder="Aucune">
                                        <button type="button" class="btn btn-warning btn-remise" title="Appliquer remise">
                                            <i class="fas fa-tag"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Sous-total après remise -->
                                <div class="col-md-2">
                                    <label class="form-label">Total ligne</label>
                                    <input type="text" class="form-control total-ligne-display bg-light fw-bold" readonly value="0.00 DH">
                                </div>

                                <!-- Supprimer -->
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Specs variant -->
                                <div class="col-12 variant-specs" style="display: none;">
                                    <div class="alert alert-info mb-0 py-2">
                                        <small class="specs-text"></small>
                                    </div>
                                </div>

                                <!-- Badge remise appliquée -->
                                <div class="col-12 remise-badge-container" style="display: none;">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-tag me-1"></i>
                                        <span class="remise-badge-text"></span>
                                        <button type="button" class="btn-close btn-close-sm ms-2 btn-supprimer-remise"
                                                style="font-size: 0.6rem;" title="Supprimer remise"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success" id="add-item">
                        <i class="fas fa-plus me-2"></i>Ajouter un produit
                    </button>

                    <button type="button" class="btn btn-success ms-2" id="btn-open-gift-modal">
    <i class="fas fa-gift me-2"></i>Ajouter un Gift
</button>
                </div>
            </div>

            <!-- Section Paiement -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Paiement & Montants</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Sous-total (DH)</label>
                            <input type="text" id="sous-total-display" class="form-control bg-light" readonly value="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Total Remises (DH)</label>
                            <input type="text" id="remises-total-display" class="form-control bg-light text-danger" readonly value="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">TVA (DH)</label>
                            <input type="number" name="tva" id="tva" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Total (DH)</label>
                            <input type="text" id="total-display" class="form-control bg-light fw-bold text-primary" readonly value="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                            <select name="mode_paiement" class="form-select" required>
                                <option value="especes">Espèces</option>
                                <option value="carte">Carte</option>
                                <option value="cheque">Chèque</option>
                                <option value="virement">Virement</option>
                                <option value="credit">Crédit</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Montant Payé (DH)</label>
                            <input type="number" name="montant_paye" id="montant_paye" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de Paiement</label>
                            <input type="date" name="date_paiement" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('recus.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Créer le Reçu
                </button>
            </div>
        </form>
    </div>

    <!-- ===== MODAL REMISE ===== -->
    <div class="modal fade" id="remiseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-tag me-2"></i>Appliquer Remise
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong id="modal-produit-nom"></strong><br>
                        Sous-total: <strong id="modal-sous-total"></strong> DH
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type de remise *</label>
                        <select id="modal-type-remise" class="form-select" onchange="toggleRemiseType()">
                            <option value="montant">Montant fixe (DH)</option>
                            <option value="pourcentage">Pourcentage (%)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valeur de la remise *</label>
                        <div class="input-group">
                            <input type="number" id="modal-valeur-remise" class="form-control"
                                   step="0.01" min="0" placeholder="0.00" required>
                            <span class="input-group-text" id="remise-unite">DH</span>
                        </div>
                        <small class="text-muted" id="remise-max-hint"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-warning" onclick="confirmerRemise()">
                        <i class="fas fa-check me-2"></i>Appliquer
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="giftModal" tabindex="-1" aria-labelledby="giftModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="giftModalLabel">
                    <i class="fas fa-gift me-2"></i>Choisir un Accessoire à Offrir
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
 
            <div class="modal-body">
                {{-- Loading --}}
                <div id="gift-loading" class="text-center py-4">
                    <div class="spinner-border text-success" role="status"></div>
                    <p class="mt-2 text-muted">Chargement des accessoires...</p>
                </div>
 
                {{-- Liste produits accessoires --}}
                <div id="gift-produits-list" style="display:none;">
                    <div class="alert alert-info py-2 mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Sélectionnez l'accessoire à offrir gratuitement au client.
                        Le coût sera enregistré comme <strong>charge variable</strong>.
                    </div>
 
                    {{-- Recherche --}}
                    <div class="mb-3">
                        <input type="text" id="gift-search" class="form-control"
                               placeholder="🔍 Rechercher un accessoire...">
                    </div>
 
                    {{-- Grille produits --}}
                    <div class="row g-3" id="gift-produits-grid">
                        {{-- Rempli dynamiquement par JS --}}
                    </div>
                </div>
 
                {{-- Empty state --}}
                <div id="gift-empty" style="display:none;" class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun accessoire disponible en stock</p>
                </div>
            </div>
 
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="btn-confirm-gift" disabled>
                    <i class="fas fa-check me-2"></i>
                    Confirmer le Gift
                    <span id="gift-confirm-info" class="ms-1"></span>
                </button>
            </div>
        </div>
    </div>
</div>

   @push('scripts')
<script>

let itemIndex = 1;
let currentRemiseRow = null;
let currentRemiseSousTotal = 0;

// ================================================================
// GIFT SYSTEM
// ================================================================
let selectedGiftProduit = null;
let giftModal = null;

// ----------------------------------------------------------------
// Ouvrir le modal Gift → charger les accessoires
// ----------------------------------------------------------------
$('#btn-open-gift-modal').on('click', function () {
    giftModal = new bootstrap.Modal(document.getElementById('giftModal'));

    selectedGiftProduit = null;
    $('#btn-confirm-gift').prop('disabled', true);
    $('#gift-confirm-info').text('');
    $('#gift-search').val('');
    $('#gift-produits-list').hide();
    $('#gift-empty').hide();
    $('#gift-loading').show();
    $('#gift-produits-grid').empty();

    giftModal.show();

    $.ajax({
        url: '/api/produits/accessoires',
        method: 'GET',
        success: function (response) {
            $('#gift-loading').hide();
            if (response.success && response.produits.length > 0) {
                renderGiftProduits(response.produits);
                $('#gift-produits-list').show();
            } else {
                $('#gift-empty').show();
            }
        },
        error: function () {
            $('#gift-loading').hide();
            $('#gift-produits-grid').html(`
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erreur lors du chargement des accessoires.
                    </div>
                </div>
            `);
            $('#gift-produits-list').show();
        }
    });
});

// ----------------------------------------------------------------
// Afficher les produits accessoires en grille
// ----------------------------------------------------------------
function renderGiftProduits(produits) {
    const grid = $('#gift-produits-grid');
    grid.empty();

    produits.forEach(function (p) {
        const stockBadge = p.quantite_stock > 5
            ? `<span class="badge bg-success">Stock: ${p.quantite_stock}</span>`
            : p.quantite_stock > 0
                ? `<span class="badge bg-warning text-dark">Stock: ${p.quantite_stock}</span>`
                : `<span class="badge bg-danger">Rupture</span>`;

        const disabled = p.quantite_stock <= 0 ? 'opacity-50 pe-none' : '';

        grid.append(`
            <div class="col-md-4 col-sm-6 gift-produit-card-wrapper" data-nom="${p.nom.toLowerCase()}">
                <div class="card h-100 border-2 gift-produit-card ${disabled}"
                     data-id="${p.id}"
                     data-nom="${p.nom}"
                     data-prix-achat="${p.prix_achat}"
                     data-prix-vente="${p.prix_vente}"
                     data-stock="${p.quantite_stock}"
                     data-lots='${JSON.stringify(p.lots || [])}'
                     style="cursor:${p.quantite_stock > 0 ? 'pointer' : 'not-allowed'}; transition: all 0.2s; position:relative;">
                    <div class="card-body p-3">
                        <h6 class="card-title mb-1 fw-bold">${p.nom}</h6>
                        <p class="text-muted small mb-2">${p.reference || ''}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            ${stockBadge}
                            <span class="text-primary fw-bold">${parseFloat(p.prix_vente).toFixed(2)} DH</span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-tag me-1"></i>
                            Coût: <strong>${parseFloat(p.prix_achat).toFixed(2)} DH</strong>
                        </small>
                    </div>
                    <div class="card-footer bg-light p-2 gift-qty-container" style="display:none;">
                        <label class="form-label small mb-1 fw-bold text-success">
                            <i class="fas fa-sort-numeric-up me-1"></i>Quantité
                        </label>
                        <div class="input-group input-group-sm">
                            <button type="button" class="btn btn-outline-secondary btn-qty-minus">−</button>
                            <input type="number" class="form-control text-center gift-qty-input"
                                   value="1" min="1" max="${p.quantite_stock}">
                            <button type="button" class="btn btn-outline-secondary btn-qty-plus">+</button>
                        </div>
                    </div>
                    <div class="position-absolute top-0 end-0 p-2 gift-check" style="display:none;">
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
        `);
    });

    window._allGiftProduits = produits;
}

// ----------------------------------------------------------------
// Cliquer sur une carte produit
// ----------------------------------------------------------------
$(document).on('click', '.gift-produit-card', function () {
    if ($(this).hasClass('pe-none')) return;

    $('.gift-produit-card').removeClass('border-success bg-success bg-opacity-10');
    $('.gift-produit-card .gift-check').hide();
    $('.gift-produit-card .gift-qty-container').hide();

    $(this).addClass('border-success bg-success bg-opacity-10');
    $(this).find('.gift-check').show();
    $(this).find('.gift-qty-container').show();

    selectedGiftProduit = {
        id        : $(this).data('id'),
        nom       : $(this).data('nom'),
        prix_achat: parseFloat($(this).data('prix-achat')),
        prix_vente: parseFloat($(this).data('prix-vente')),
        stock     : parseInt($(this).data('stock')),
        lots      : $(this).data('lots') || [],
    };

    updateGiftConfirmButton();
});

// ----------------------------------------------------------------
// Quantité ± dans la carte
// ----------------------------------------------------------------
$(document).on('click', '.btn-qty-minus', function (e) {
    e.stopPropagation();
    const input = $(this).closest('.card-footer').find('.gift-qty-input');
    input.val(Math.max(1, parseInt(input.val()) - 1));
    updateGiftConfirmButton();
});

$(document).on('click', '.btn-qty-plus', function (e) {
    e.stopPropagation();
    const input = $(this).closest('.card-footer').find('.gift-qty-input');
    input.val(Math.min(parseInt(input.attr('max')), parseInt(input.val()) + 1));
    updateGiftConfirmButton();
});

$(document).on('input', '.gift-qty-input', function (e) {
    e.stopPropagation();
    updateGiftConfirmButton();
});

// ----------------------------------------------------------------
// Mettre à jour le bouton Confirmer
// ----------------------------------------------------------------
function updateGiftConfirmButton() {
    if (!selectedGiftProduit) {
        $('#btn-confirm-gift').prop('disabled', true);
        $('#gift-confirm-info').text('');
        return;
    }
    const qty  = getSelectedGiftQty();
    const cout = (selectedGiftProduit.prix_achat * qty).toFixed(2);
    $('#btn-confirm-gift').prop('disabled', false);
    $('#gift-confirm-info').html(`— ${selectedGiftProduit.nom} x${qty} (coût: ${cout} DH)`);
}

function getSelectedGiftQty() {
    const activeCard = $('.gift-produit-card.border-success');
    if (!activeCard.length) return 1;
    return parseInt(activeCard.find('.gift-qty-input').val()) || 1;
}

// ----------------------------------------------------------------
// Confirmer et ajouter la ligne Gift dans le form
// ----------------------------------------------------------------
$('#btn-confirm-gift').on('click', function () {
    if (!selectedGiftProduit) return;

    const qty      = getSelectedGiftQty();
    const cout     = (selectedGiftProduit.prix_achat * qty).toFixed(2);
    const lots     = selectedGiftProduit.lots || [];
    const firstLot = lots.length > 0 ? lots[0] : null;

    $('#items-container').append(buildGiftRow(itemIndex, selectedGiftProduit, qty, firstLot));
    itemIndex++;

    updateRemoveButtons();
    calculateTotal();

    bootstrap.Modal.getInstance(document.getElementById('giftModal')).hide();

    Swal.fire({
        icon : 'success',
        title: '🎁 Gift ajouté !',
        html : `<strong>${selectedGiftProduit.nom}</strong> x${qty}<br>
                Coût enregistré: <strong>${cout} DH</strong> comme charge variable.`,
        timer: 2500,
        showConfirmButton: false,
    });
});

// ----------------------------------------------------------------
// Construire la ligne Gift HTML
// ----------------------------------------------------------------
function buildGiftRow(idx, produit, qty, firstLot) {
    const prixAchat  = firstLot ? firstLot.prix_achat : produit.prix_achat;
    const stockDispo = firstLot ? firstLot.quantite_restante : produit.stock;
    const achatId    = firstLot ? firstLot.id : '';
    const cout       = (prixAchat * qty).toFixed(2);

    return `
        <div class="item-row gift-row mb-3 p-3 border border-success rounded bg-success bg-opacity-10"
             data-index="${idx}" data-is-gift="1">
            <div class="d-flex align-items-center mb-2">
                <span class="badge bg-success fs-6 me-2">
                    <i class="fas fa-gift me-1"></i> GIFT
                </span>
                <strong>${produit.nom}</strong>
                <span class="ms-auto text-muted small">
                    Coût: ${cout} DH → charge variable automatique
                </span>
            </div>
            <div class="row g-2">
                <input type="hidden" name="items[${idx}][produit_id]"        value="${produit.id}">
                <input type="hidden" name="items[${idx}][is_gift]"            value="1">
                <input type="hidden" name="items[${idx}][prix_unitaire]"      value="0">
                <input type="hidden" name="items[${idx}][prix_achat]"         value="${prixAchat}">
                <input type="hidden" name="items[${idx}][achat_id]"           value="${achatId}">
                <input type="hidden" name="items[${idx}][remise_appliquee]"   value="0">
                <input type="hidden" name="items[${idx}][remise_pourcentage]" value="0">
                <input type="hidden" name="items[${idx}][remise_montant]"     value="0">
                <input type="hidden" class="is-gift-input"                    value="1">
                <input type="hidden" class="lot-prix-achat"                   value="${prixAchat}">

                <div class="col-md-4">
                    <label class="form-label small text-success fw-bold">Accessoire offert</label>
                    <input type="text" class="form-control form-control-sm bg-white"
                           value="${produit.nom}" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-success fw-bold">Quantité</label>
                    <input type="number" name="items[${idx}][quantite]"
                           class="form-control form-control-sm quantite-input"
                           value="${qty}" min="1" max="${stockDispo}" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-success fw-bold">Prix client</label>
                    <input type="text" class="form-control form-control-sm bg-white text-success fw-bold"
                           value="0.00 DH (GIFT)" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Coût interne</label>
                    <input type="text" class="form-control form-control-sm bg-light gift-cout-display"
                           value="${cout} DH" readonly>
                </div>
                <div class="col-md-1">
                    <label class="form-label small text-success fw-bold">Total</label>
                    <input type="text" class="form-control form-control-sm bg-white text-success fw-bold total-ligne-display"
                           value="0.00 DH" readonly>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <small class="text-muted mt-1 d-block">
                <i class="fas fa-info-circle me-1 text-warning"></i>
                Une charge variable de <strong>${cout} DH</strong> sera créée automatiquement.
            </small>
        </div>
    `;
}

// ----------------------------------------------------------------
// Recherche dans le modal Gift
// ----------------------------------------------------------------
$('#gift-search').on('input', function () {
    const q = $(this).val().toLowerCase().trim();
    $('.gift-produit-card-wrapper').each(function () {
        $(this).toggle($(this).data('nom').includes(q));
    });
});

// ================================================================
// TEMPLATE OPTIONS PRODUITS
// ================================================================
const produitsOptions = `
    <option value="">-- Sélectionner un produit --</option>
    @foreach($produits as $produit)
        <option value="{{ $produit->id }}"
                data-lots="{{ json_encode($produit->lots_disponibles) }}"
                data-stock="{{ $produit->quantite_stock }}"
                data-category="{{ $produit->category_id }}"
                data-has-variants="{{ $produit->variants->count() > 0 ? 'true' : 'false' }}">
            {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
        </option>
    @endforeach
`;

// ================================================================
// TEMPLATE ITEM ROW (produit normal)
// ================================================================
function buildItemRow(idx) {
    return `
        <div class="item-row mb-3 p-3 border rounded" data-index="${idx}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Produit <span class="text-danger">*</span></label>
                    <select name="items[${idx}][produit_id]" class="form-select produit-select" required>
                        ${produitsOptions}
                    </select>
                </div>

                <div class="col-12 lots-container" style="display:none;">
                    <label class="form-label fw-bold text-primary">
                        <i class="fas fa-boxes me-1"></i> Choisir le lot à vendre
                    </label>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-hover lots-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Choisir</th>
                                    <th>Date Achat</th>
                                    <th>Fournisseur</th>
                                    <th>Stock Dispo</th>
                                    <th>Prix Achat</th>
                                    <th>Prix Vente</th>
                                    <th>Marge</th>
                                </tr>
                            </thead>
                            <tbody class="lots-tbody"></tbody>
                        </table>
                    </div>
                    <input type="hidden" name="items[${idx}][achat_id]"      class="lot-achat-id">
                    <input type="hidden" name="items[${idx}][prix_achat]"    class="lot-prix-achat">
                    <input type="hidden" name="items[${idx}][prix_unitaire]" class="lot-prix-vente">
                </div>

                <div class="col-md-3 variant-container" style="display:none;">
                    <label class="form-label">Variant (optionnel)</label>
                    <select name="items[${idx}][product_variant_id]" class="form-select variant-select">
                        <option value="">-- Produit de base --</option>
                    </select>
                    <small class="text-muted variant-info"></small>
                </div>

                <div class="col-md-1">
                    <label class="form-label">Qté <span class="text-danger">*</span></label>
                    <input type="number" name="items[${idx}][quantite]"
                           class="form-control quantite-input" min="1" value="1" required>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Prix Unit.</label>
                    <input type="text" class="form-control prix-display" readonly>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Remise</label>
                    <div class="input-group input-group-sm">
                        <input type="hidden" name="items[${idx}][remise_appliquee]"   class="remise-appliquee-input" value="0">
                        <input type="hidden" name="items[${idx}][remise_pourcentage]" class="remise-pourcentage-input" value="0">
                        <input type="hidden" name="items[${idx}][remise_montant]"     class="remise-montant-input" value="0">
                        <input type="text" class="form-control remise-display bg-light" readonly placeholder="Aucune">
                        <button type="button" class="btn btn-warning btn-remise" title="Appliquer remise">
                            <i class="fas fa-tag"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Total ligne</label>
                    <input type="text" class="form-control total-ligne-display bg-light fw-bold"
                           readonly value="0.00 DH">
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-item">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <div class="col-12 variant-specs" style="display:none;">
                    <div class="alert alert-info mb-0 py-2">
                        <small class="specs-text"></small>
                    </div>
                </div>

                <div class="col-12 remise-badge-container" style="display:none;">
                    <span class="badge bg-warning text-dark">
                        <i class="fas fa-tag me-1"></i>
                        <span class="remise-badge-text"></span>
                        <button type="button" class="btn-close btn-close-sm ms-2 btn-supprimer-remise"
                                style="font-size:0.6rem;" title="Supprimer remise"></button>
                    </span>
                </div>
            </div>
        </div>
    `;
}

// ================================================================
// DOCUMENT READY
// ================================================================
$(document).ready(function() {

    // FILTRE CATÉGORIE
    $('#category-filter').on('change', function() {
        const categoryId = $(this).val();
        if (!categoryId) {
            $('.produit-select').each(function() { $(this).html(produitsOptions); });
            $('#filter-info').text('');
            return;
        }
        $.ajax({
            url: `/api/produits/category/${categoryId}`,
            method: 'GET',
            beforeSend: function() {
                $('#filter-info').html('<span class="text-info">🔄 Chargement...</span>');
            },
            success: function(response) {
                if (response.success && response.produits.length > 0) {
                    let options = '<option value="">-- Sélectionner un produit --</option>';
                    response.produits.forEach(p => {
                        options += `<option value="${p.id}"
                            data-lots='${JSON.stringify(p.lots)}'
                            data-stock="${p.quantite_stock}"
                            data-has-variants="${p.has_variants}">
                            ${p.nom} (Stock: ${p.quantite_stock})
                        </option>`;
                    });
                    $('.produit-select').each(function() {
                        const v = $(this).val();
                        $(this).html(options);
                        $(this).val(v);
                    });
                    $('#filter-info').html(`<span class="text-success">✅ ${response.produits.length} produit(s)</span>`);
                } else {
                    $('.produit-select').html('<option value="">Aucun produit dans cette catégorie</option>');
                    $('#filter-info').html('<span class="text-warning">⚠️ Aucun produit disponible</span>');
                }
            },
            error: function() {
                $('#filter-info').html('<span class="text-danger">❌ Erreur</span>');
            }
        });
    });

    $('#reset-filter').on('click', function() {
        $('#category-filter').val('').trigger('change');
    });

    // CHANGEMENT PRODUIT
    $(document).on('change', '.produit-select', function() {
        const row            = $(this).closest('.item-row');
        const selectedOption = $(this).find(':selected');
        const produitId      = $(this).val();
        const lots           = selectedOption.data('lots') || [];
        const hasVariants    = selectedOption.data('has-variants') === true
                            || selectedOption.data('has-variants') === 'true';

        // Reset
        row.find('.lots-container').hide();
        row.find('.lots-tbody').empty();
        row.find('.lot-achat-id').val('');
        row.find('.lot-prix-achat').val('');
        row.find('.lot-prix-vente').val('');
        row.find('.prix-display').val('');
        row.find('.variant-container').hide();
        row.find('.variant-select').html('<option value="">-- Produit de base --</option>');
        row.find('.variant-specs').hide();
        resetRemiseRow(row);

        if (!produitId) { calculateTotal(); return; }

        // Lots disponibles
        if (lots.length > 0) {
            let tbodyHtml = '';
            lots.forEach((lot, idx) => {
                const margeColor = lot.marge_pourcentage >= 20
                    ? 'text-success fw-bold'
                    : lot.marge_pourcentage >= 10 ? 'text-warning' : 'text-danger';

                tbodyHtml += `
                    <tr class="lot-row ${idx === 0 ? 'table-primary' : ''}" style="cursor:pointer;"
                        data-achat-id="${lot.id}"
                        data-prix-achat="${lot.prix_achat}"
                        data-prix-vente="${lot.prix_vente_suggere}"
                        data-stock="${lot.quantite_restante}"
                        data-marge="${lot.marge_pourcentage}">
                        <td class="text-center">
                            <input type="radio" name="lot_select_${row.data('index')}"
                                   class="form-check-input lot-radio" value="${lot.id}"
                                   ${idx === 0 ? 'checked' : ''}>
                        </td>
                        <td>${lot.date_achat}</td>
                        <td>${lot.fournisseur}</td>
                        <td><span class="badge bg-info">${lot.quantite_restante}</span></td>
                        <td>${parseFloat(lot.prix_achat).toFixed(2)} DH</td>
                        <td class="fw-bold">${parseFloat(lot.prix_vente_suggere).toFixed(2)} DH</td>
                        <td class="${margeColor}">${parseFloat(lot.marge_pourcentage).toFixed(1)}%</td>
                    </tr>
                `;
            });

            row.find('.lots-tbody').html(tbodyHtml);
            row.find('.lots-container').show();

            const first = lots[0];
            selectLot(row, first.id, first.prix_achat, first.prix_vente_suggere, first.quantite_restante);
        }

        // Variants
        if (hasVariants) {
            $.ajax({
                url: `/api/variants/produit/${produitId}`,
                method: 'GET',
                success: function(response) {
                    if (response.success && response.variants.length > 0) {
                        row.find('.variant-container').show();
                        let variantOptions = '<option value="">-- Produit de base --</option>';
                        response.variants.forEach(v => {
                            variantOptions += `<option value="${v.id}"
                                data-prix="${v.prix_vente_final}"
                                data-stock="${v.stock}"
                                data-specs="${v.variant_name}">
                                ${v.variant_name} - ${v.prix_vente_final} DH (Stock: ${v.stock})
                            </option>`;
                        });
                        row.find('.variant-select').html(variantOptions);
                    }
                }
            });
        }

        calculateTotal();
    });

    // CLIC SUR LIGNE LOT
    $(document).on('click', '.lot-row', function() {
        const row = $(this).closest('.item-row');
        row.find('.lot-row').removeClass('table-primary');
        $(this).addClass('table-primary');
        $(this).find('.lot-radio').prop('checked', true);
        selectLot(
            row,
            $(this).data('achat-id'),
            $(this).data('prix-achat'),
            $(this).data('prix-vente'),
            $(this).data('stock')
        );
        calculateTotal();
    });

    // CHANGEMENT VARIANT
    $(document).on('change', '.variant-select', function() {
        const row       = $(this).closest('.item-row');
        const variantId = $(this).val();
        resetRemiseRow(row);

        if (variantId) {
            const prix  = $(this).find(':selected').data('prix');
            const stock = $(this).find(':selected').data('stock');
            const specs = $(this).find(':selected').data('specs');
            row.find('.prix-display').val(parseFloat(prix).toFixed(2) + ' DH');
            row.find('.quantite-input').attr('max', stock);
            row.find('.variant-specs').show();
            row.find('.specs-text').html(`<strong>Specs:</strong> ${specs}`);
            row.find('.lot-achat-id').val('');
            row.find('.lot-prix-achat').val('');
            row.find('.lot-prix-vente').val('');
        } else {
            const lotPrix = parseFloat(row.find('.lot-prix-vente').val()) || 0;
            if (lotPrix > 0) {
                row.find('.prix-display').val(lotPrix.toFixed(2) + ' DH');
            }
            row.find('.variant-specs').hide();
        }
        calculateTotal();
    });

    // AJOUTER ITEM
    $('#add-item').click(function() {
        $('#items-container').append(buildItemRow(itemIndex));
        itemIndex++;
        updateRemoveButtons();
    });

    // SUPPRIMER ITEM
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        updateRemoveButtons();
        calculateTotal();
    });

    // BOUTON REMISE
    $(document).on('click', '.btn-remise', function() {
        currentRemiseRow      = $(this).closest('.item-row');
        const produitNom      = currentRemiseRow.find('.produit-select option:selected').text().trim();
        const prix            = getPrixUnitaire(currentRemiseRow);
        const quantite        = parseInt(currentRemiseRow.find('.quantite-input').val()) || 0;
        currentRemiseSousTotal = prix * quantite;

        if (!currentRemiseRow.find('.produit-select').val() || currentRemiseSousTotal <= 0) {
            Swal.fire('Attention', 'Sélectionnez un produit et une quantité valide', 'warning');
            return;
        }

        const pourcentage = parseFloat(currentRemiseRow.find('.remise-pourcentage-input').val()) || 0;
        const montant     = parseFloat(currentRemiseRow.find('.remise-montant-input').val()) || 0;

        if (pourcentage > 0) {
            $('#modal-type-remise').val('pourcentage');
            $('#modal-valeur-remise').val(pourcentage);
        } else if (montant > 0) {
            $('#modal-type-remise').val('montant');
            $('#modal-valeur-remise').val(montant);
        } else {
            $('#modal-type-remise').val('montant');
            $('#modal-valeur-remise').val('');
        }

        $('#modal-produit-nom').text(produitNom);
        $('#modal-sous-total').text(currentRemiseSousTotal.toFixed(2));
        toggleRemiseType();
        new bootstrap.Modal(document.getElementById('remiseModal')).show();
    });

    // SUPPRIMER REMISE
    $(document).on('click', '.btn-supprimer-remise', function() {
        resetRemiseRow($(this).closest('.item-row'));
        calculateTotal();
    });

    // RECALCUL QUANTITÉ / TVA
    $(document).on('input', '.quantite-input, #tva', function() {
        if ($(this).hasClass('quantite-input')) {
            const row               = $(this).closest('.item-row');
            const remiseAppliquee   = row.find('.remise-appliquee-input').val() === '1';
            const remisePourcentage = parseFloat(row.find('.remise-pourcentage-input').val()) || 0;

            if (remiseAppliquee && remisePourcentage > 0) {
                const prix      = getPrixUnitaire(row);
                const quantite  = parseInt($(this).val()) || 0;
                const sousTotal = prix * quantite;
                row.find('.remise-montant-input').val(((sousTotal * remisePourcentage) / 100).toFixed(2));
            }
        }
        calculateTotal();
    });

    // SUBMIT VALIDATION
    $('#recuForm').on('submit', function(e) {
        let valid  = true;
        let errors = [];

        $('.item-row').each(function() {
            const row       = $(this);
            const isGift    = row.data('is-gift') == '1';
            const produitId = isGift ? row.find('input[name*="produit_id"]').val()
                                     : row.find('.produit-select').val();
            const quantite  = parseInt(row.find('.quantite-input').val());
            const maxStock  = parseInt(row.find('.quantite-input').attr('max'));

            if (!produitId) {
                valid = false;
                errors.push('Veuillez sélectionner un produit pour chaque ligne');
            }
            if (maxStock && quantite > maxStock) {
                valid = false;
                const nom = isGift
                    ? row.find('input[type="text"]').first().val()
                    : row.find('.produit-select option:selected').text().trim();
                errors.push(`Stock insuffisant pour "${nom}"`);
            }
        });

        if (!valid) {
            e.preventDefault();
            Swal.fire({ icon: 'error', title: 'Erreur de validation', html: errors.join('<br>') });
        }
    });
});

// ================================================================
// HELPERS
// ================================================================

function selectLot(row, achatId, prixAchat, prixVente, stock) {
    row.find('.lot-achat-id').val(achatId);
    row.find('.lot-prix-achat').val(prixAchat);
    row.find('.lot-prix-vente').val(prixVente);
    row.find('.prix-display').val(parseFloat(prixVente).toFixed(2) + ' DH');
    row.find('.quantite-input').attr('max', stock);
}

function getPrixUnitaire(row) {
    const variantVal = row.find('.variant-select').val();
    if (variantVal) {
        return parseFloat(row.find('.variant-select option:selected').data('prix')) || 0;
    }
    const lotPrix = parseFloat(row.find('.lot-prix-vente').val());
    if (lotPrix > 0) return lotPrix;
    return parseFloat(row.find('.produit-select option:selected').data('prix')) || 0;
}

function resetRemiseRow(row) {
    row.find('.remise-appliquee-input').val('0');
    row.find('.remise-pourcentage-input').val('0');
    row.find('.remise-montant-input').val('0');
    row.find('.remise-display').val('').attr('placeholder', 'Aucune');
    row.find('.btn-remise').removeClass('btn-info').addClass('btn-warning');
    row.find('.remise-badge-container').hide();
    calculateTotal();
}

function toggleRemiseType() {
    const type  = $('#modal-type-remise').val();
    const unite = document.getElementById('remise-unite');
    const hint  = document.getElementById('remise-max-hint');
    const input = document.getElementById('modal-valeur-remise');

    if (type === 'pourcentage') {
        unite.textContent = '%';
        input.max         = 100;
        hint.textContent  = 'Maximum: 100%';
    } else {
        unite.textContent = 'DH';
        input.max         = currentRemiseSousTotal;
        hint.textContent  = `Maximum: ${currentRemiseSousTotal.toFixed(2)} DH`;
    }
}

function confirmerRemise() {
    const type   = $('#modal-type-remise').val();
    const valeur = parseFloat($('#modal-valeur-remise').val());

    if (!valeur || valeur <= 0) { Swal.fire('Erreur', 'Valeur invalide', 'error'); return; }
    if (type === 'pourcentage' && valeur > 100) { Swal.fire('Erreur', 'Max 100%', 'error'); return; }
    if (type === 'montant' && valeur > currentRemiseSousTotal) { Swal.fire('Erreur', 'Remise > sous-total', 'error'); return; }

    const row = currentRemiseRow;
    row.find('.remise-appliquee-input').val('1');

    if (type === 'pourcentage') {
        const montantCalcule = (currentRemiseSousTotal * valeur) / 100;
        row.find('.remise-pourcentage-input').val(valeur);
        row.find('.remise-montant-input').val('0');
        row.find('.remise-display').val(`${valeur}%`);
        row.find('.remise-badge-text').text(`Remise: ${valeur}% (-${montantCalcule.toFixed(2)} DH)`);
    } else {
        row.find('.remise-pourcentage-input').val('0');
        row.find('.remise-montant-input').val(valeur);
        row.find('.remise-display').val(`-${valeur.toFixed(2)} DH`);
        row.find('.remise-badge-text').text(`Remise: -${valeur.toFixed(2)} DH`);
    }

    row.find('.btn-remise').removeClass('btn-warning').addClass('btn-info');
    row.find('.remise-badge-container').show();
    bootstrap.Modal.getInstance(document.getElementById('remiseModal')).hide();
    calculateTotal();
}

function calculateTotal() {
    let sousTotal    = 0;
    let totalRemises = 0;

    $('.item-row').each(function() {
        const row    = $(this);
        const isGift = row.find('.is-gift-input').val() === '1'
                    || row.data('is-gift') == '1';
        const quantite = parseInt(row.find('.quantite-input').val()) || 0;

        if (isGift) {
            row.find('.total-ligne-display').val('0.00 DH');
            const prixAchat = parseFloat(row.find('.lot-prix-achat').val()) || 0;
            row.find('.gift-cout-display').val((prixAchat * quantite).toFixed(2) + ' DH');
            return; // continue .each()
        }

        const prix           = getPrixUnitaire(row);
        const ligneSousTotal = prix * quantite;

        const remiseAppliquee   = row.find('.remise-appliquee-input').val() === '1';
        const remisePourcentage = parseFloat(row.find('.remise-pourcentage-input').val()) || 0;
        const remiseMontant     = parseFloat(row.find('.remise-montant-input').val()) || 0;

        let montantRemise = 0;
        if (remiseAppliquee) {
            montantRemise = remisePourcentage > 0
                ? (ligneSousTotal * remisePourcentage) / 100
                : remiseMontant;
        }

        const ligneTotal = Math.max(0, ligneSousTotal - montantRemise);
        sousTotal    += ligneSousTotal;
        totalRemises += montantRemise;

        row.find('.total-ligne-display')
            .val(ligneTotal.toFixed(2) + ' DH')
            .toggleClass('text-success', montantRemise > 0)
            .toggleClass('text-dark',    montantRemise === 0);
    });

    const tva   = parseFloat($('#tva').val()) || 0;
    const total = sousTotal - totalRemises + tva;

    $('#sous-total-display').val(sousTotal.toFixed(2));
    $('#remises-total-display').val(totalRemises > 0 ? '-' + totalRemises.toFixed(2) : '0.00');
    $('#total-display').val(total.toFixed(2));
    document.getElementById('montant_paye').value = total.toFixed(2);
}

function updateRemoveButtons() {
    // Gift rows + normal rows — désactiver supprimer si 1 seule ligne normale reste
    const normalRows = $('.item-row:not(.gift-row)').length;
    $('.item-row:not(.gift-row) .remove-item').prop('disabled', normalRows <= 1);
    $('.gift-row .remove-item').prop('disabled', false); // Gift toujours supprimable
}

</script>
@endpush
</x-app-layout>