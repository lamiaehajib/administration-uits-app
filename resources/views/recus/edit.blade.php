<x-app-layout>
    <div class="container-fluid">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="hight">
                    <i class="fas fa-edit me-2"></i>
                    Modifier le Reçu {{ $recu->numero_recu }}
                </h3>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('recus.show', $recu) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>
        </div>

        <form action="{{ route('recus.update', $recu) }}" method="POST" id="recuForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="remise" value="{{ $recu->remise }}">

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
                                           value="{{ old('client_nom', $recu->client_nom) }}" required>
                                    @error('client_nom') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" name="client_prenom" class="form-control"
                                           value="{{ old('client_prenom', $recu->client_prenom) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="client_telephone" class="form-control"
                                           value="{{ old('client_telephone', $recu->client_telephone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="client_email" class="form-control"
                                           value="{{ old('client_email', $recu->client_email) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse</label>
                                    <textarea name="client_adresse" class="form-control" rows="2">{{ old('client_adresse', $recu->client_adresse) }}</textarea>
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
                                           placeholder="Ex: PC HP, Imprimante Canon..."
                                           value="{{ old('equipement', $recu->equipement) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Détails</label>
                                    <textarea name="details" class="form-control" rows="2"
                                              placeholder="Description du problème...">{{ old('details', $recu->details) }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Type de Garantie <span class="text-danger">*</span></label>
                                    <select name="type_garantie" class="form-select" required>
                                        <option value="30_jours" {{ old('type_garantie', $recu->type_garantie) == '30_jours' ? 'selected' : '' }}>30 jours</option>
                                        <option value="90_jours" {{ old('type_garantie', $recu->type_garantie) == '90_jours' ? 'selected' : '' }}>90 jours</option>
                                        <option value="180_jours" {{ old('type_garantie', $recu->type_garantie) == '180_jours' ? 'selected' : '' }}>180 jours (6 mois)</option>
                                        <option value="360_jours" {{ old('type_garantie', $recu->type_garantie) == '360_jours' ? 'selected' : '' }}>360 jours (1 an)</option>
                                        <option value="sans_garantie" {{ old('type_garantie', $recu->type_garantie) == 'sans_garantie' ? 'selected' : '' }}>Sans garantie</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtre par Catégorie -->
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
                        @foreach($recu->items as $index => $item)
                        <div class="item-row mb-3 p-3 border rounded {{ $item->remise_appliquee ? 'border-warning' : '' }}" data-index="{{ $index }}">
                            <div class="row g-3">
                                <!-- Sélection Produit -->
                                <div class="col-md-4">
                                    <label class="form-label">Produit <span class="text-danger">*</span></label>
                                    <select name="items[{{ $index }}][produit_id]" class="form-select produit-select" required>
                                        <option value="">-- Sélectionner un produit --</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}"
                                                    data-prix="{{ $produit->prix_vente }}"
                                                    data-stock="{{ $produit->quantite_stock }}"
                                                    data-category="{{ $produit->category_id }}"
                                                    data-has-variants="{{ $produit->variants->count() > 0 ? 'true' : 'false' }}"
                                                    {{ $item->produit_id == $produit->id ? 'selected' : '' }}>
                                                {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sélection Variant -->
                                <div class="col-md-3 variant-container" style="display: {{ $item->product_variant_id ? 'block' : 'none' }};">
                                    <label class="form-label">Variant (optionnel)</label>
                                    <select name="items[{{ $index }}][product_variant_id]" class="form-select variant-select">
                                        <option value="">-- Produit de base (sans variant) --</option>
                                        @if($item->variant)
                                            <option value="{{ $item->variant->id }}"
                                                    data-prix="{{ $item->variant->prix_vente_final }}"
                                                    data-stock="{{ $item->variant->quantite_stock }}"
                                                    data-specs="{{ $item->variant->variant_name }}"
                                                    selected>
                                                {{ $item->variant->variant_name }} - {{ $item->variant->prix_vente_final }} DH
                                            </option>
                                        @endif
                                    </select>
                                    <small class="text-muted variant-info"></small>
                                </div>

                                <!-- Quantité -->
                                <div class="col-md-1">
                                    <label class="form-label">Qté <span class="text-danger">*</span></label>
                                    <input type="number" name="items[{{ $index }}][quantite]" class="form-control quantite-input"
                                           min="1" value="{{ old('items.'.$index.'.quantite', $item->quantite) }}" required>
                                </div>

                                <!-- Prix unitaire -->
                                <div class="col-md-2">
                                    <label class="form-label">Prix Unit.</label>
                                    <input type="text" class="form-control prix-display"
                                           value="{{ number_format($item->prix_unitaire, 2) }} DH" readonly>
                                </div>

                                <!-- Remise -->
                                <div class="col-md-2">
                                    <label class="form-label">Remise</label>
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="items[{{ $index }}][remise_appliquee]" class="remise-appliquee-input" value="{{ $item->remise_appliquee ? '1' : '0' }}">
                                        <input type="hidden" name="items[{{ $index }}][remise_pourcentage]" class="remise-pourcentage-input" value="{{ $item->remise_pourcentage ?? 0 }}">
                                        <input type="hidden" name="items[{{ $index }}][remise_montant]" class="remise-montant-input" value="{{ $item->attributes['remise_montant'] ?? 0 }}">
                                        <input type="text" class="form-control remise-display bg-light" readonly
                                               value="{{ $item->remise_appliquee ? ($item->remise_pourcentage > 0 ? number_format($item->remise_pourcentage, 2).'%' : '-'.number_format($item->attributes['remise_montant'] ?? 0, 2).' DH') : '' }}"
                                               placeholder="Aucune">
                                        <button type="button" class="btn {{ $item->remise_appliquee ? 'btn-info' : 'btn-warning' }} btn-remise" title="Appliquer remise">
                                            <i class="fas fa-tag"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Total ligne -->
                                <div class="col-md-2">
                                    <label class="form-label">Total ligne</label>
                                    <input type="text" class="form-control total-ligne-display bg-light fw-bold {{ $item->remise_appliquee ? 'text-success' : '' }}" readonly
                                           value="{{ number_format($item->remise_appliquee ? $item->total_apres_remise : $item->sous_total, 2) }} DH">
                                </div>

                                <!-- Supprimer -->
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-item" {{ count($recu->items) <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Specs variant -->
                                <div class="col-12 variant-specs" style="display: {{ $item->variant ? 'block' : 'none' }};">
                                    <div class="alert alert-info mb-0 py-2">
                                        <small class="specs-text">
                                            @if($item->variant)
                                                <strong>Specs:</strong> {{ $item->variant->variant_name }}
                                            @endif
                                        </small>
                                    </div>
                                </div>

                                <!-- Badge remise -->
                                <div class="col-12 remise-badge-container" style="display: {{ $item->remise_appliquee ? 'block' : 'none' }};">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-tag me-1"></i>
                                        <span class="remise-badge-text">
                                            @if($item->remise_appliquee)
                                                @if($item->remise_pourcentage > 0)
                                                    Remise: {{ number_format($item->remise_pourcentage, 2) }}% (-{{ number_format(($item->sous_total * $item->remise_pourcentage) / 100, 2) }} DH)
                                                @else
                                                    Remise: -{{ number_format($item->attributes['remise_montant'] ?? 0, 2) }} DH
                                                @endif
                                            @endif
                                        </span>
                                        <button type="button" class="btn-close btn-close-sm ms-2 btn-supprimer-remise"
                                                style="font-size: 0.6rem;" title="Supprimer remise"></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" class="btn btn-success" id="add-item">
                        <i class="fas fa-plus me-2"></i>Ajouter un produit
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
                            <input type="text" id="sous-total-display" class="form-control bg-light" readonly value="{{ number_format($recu->sous_total, 2) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Total Remises (DH)</label>
                            @php $totalRemisesActuel = $recu->items->sum('montant_remise'); @endphp
                            <input type="text" id="remises-total-display" class="form-control bg-light text-danger" readonly
                                   value="{{ $totalRemisesActuel > 0 ? '-'.number_format($totalRemisesActuel, 2) : '0.00' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">TVA (DH)</label>
                            <input type="number" name="tva" id="tva" class="form-control"
                                   step="0.01" min="0" value="{{ old('tva', $recu->tva) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Total (DH)</label>
                            <input type="text" id="total-display" class="form-control bg-light fw-bold text-primary"
                                   readonly value="{{ number_format($recu->total, 2) }}">
                            <small class="text-muted">Recalculé automatiquement</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Mode de Paiement <span class="text-danger">*</span></label>
                            <select name="mode_paiement" class="form-select" required>
                                <option value="especes" {{ old('mode_paiement', $recu->mode_paiement) == 'especes' ? 'selected' : '' }}>Espèces</option>
                                <option value="carte" {{ old('mode_paiement', $recu->mode_paiement) == 'carte' ? 'selected' : '' }}>Carte</option>
                                <option value="cheque" {{ old('mode_paiement', $recu->mode_paiement) == 'cheque' ? 'selected' : '' }}>Chèque</option>
                                <option value="virement" {{ old('mode_paiement', $recu->mode_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                                <option value="credit" {{ old('mode_paiement', $recu->mode_paiement) == 'credit' ? 'selected' : '' }}>Crédit</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Montant Payé (DH)</label>
                            <input type="number" name="montant_paye" id="montant_paye" class="form-control"
                                   step="0.01" min="0" value="{{ old('montant_paye', $recu->paiements->sum('montant')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de Paiement</label>
                            <input type="date" name="date_paiement" class="form-control"
                                   value="{{ old('date_paiement', $recu->date_paiement ? $recu->date_paiement->format('Y-m-d') : $recu->created_at->format('Y-m-d')) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $recu->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('recus.show', $recu) }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Mettre à jour
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

    @push('scripts')
    <script>
        let itemIndex = {{ count($recu->items) }};
        let currentRemiseRow = null;
        let currentRemiseSousTotal = 0;

        const produitsOptions = `
            <option value="">-- Sélectionner un produit --</option>
            @foreach($produits as $produit)
                <option value="{{ $produit->id }}"
                        data-prix="{{ $produit->prix_vente }}"
                        data-stock="{{ $produit->quantite_stock }}"
                        data-category="{{ $produit->category_id }}"
                        data-has-variants="{{ $produit->variants->count() > 0 ? 'true' : 'false' }}">
                    {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                </option>
            @endforeach
        `;

        $(document).ready(function() {

            // ===== FILTRE CATÉGORIE =====
            $('#category-filter').on('change', function() {
                const categoryId = $(this).val();
                if (!categoryId) {
                    $('.produit-select').each(function() {
                        const v = $(this).val();
                        $(this).html(produitsOptions);
                        $(this).val(v);
                    });
                    $('#filter-info').text('');
                    return;
                }
                $.ajax({
                    url: `/api/produits/category/${categoryId}`,
                    method: 'GET',
                    beforeSend: function() { $('#filter-info').html('<span class="text-info">🔄 Chargement...</span>'); },
                    success: function(response) {
                        if (response.success && response.produits.length > 0) {
                            let options = '<option value="">-- Sélectionner un produit --</option>';
                            response.produits.forEach(p => {
                                options += `<option value="${p.id}" data-prix="${p.prix_vente}" data-stock="${p.quantite_stock}" data-has-variants="${p.has_variants}">${p.nom} (Stock: ${p.quantite_stock})</option>`;
                            });
                            $('.produit-select').each(function() {
                                const v = $(this).val();
                                $(this).html(options);
                                $(this).val(v);
                            });
                            $('#filter-info').html(`<span class="text-success">✅ ${response.produits.length} produit(s)</span>`);
                        } else {
                            $('.produit-select').html('<option value="">Aucun produit dans cette catégorie</option>');
                            $('#filter-info').html('<span class="text-warning">⚠️ Aucun produit</span>');
                        }
                    }
                });
            });

            $('#reset-filter').on('click', function() {
                $('#category-filter').val('').trigger('change');
            });

            // ===== CHANGEMENT PRODUIT =====
            $(document).on('change', '.produit-select', function() {
                const row = $(this).closest('.item-row');
                const hasVariants = $(this).find(':selected').data('has-variants') === true;
                const prix = $(this).find(':selected').data('prix');
                const stock = $(this).find(':selected').data('stock');
                const produitId = $(this).val();

                row.find('.variant-container').hide();
                row.find('.variant-select').html('<option value="">-- Produit de base (sans variant) --</option>');
                row.find('.variant-specs').hide();
                row.find('.prix-display').val('');
                resetRemiseRow(row);

                if (!produitId) { calculateTotal(); return; }

                row.find('.prix-display').val(parseFloat(prix || 0).toFixed(2) + ' DH');
                row.find('.quantite-input').attr('max', stock);

                if (hasVariants) {
                    $.ajax({
                        url: `/api/variants/produit/${produitId}`,
                        method: 'GET',
                        success: function(response) {
                            if (response.success && response.variants.length > 0) {
                                row.find('.variant-container').show();
                                let variantOptions = '<option value="">-- Produit de base (sans variant) --</option>';
                                response.variants.forEach(v => {
                                    variantOptions += `<option value="${v.id}" data-prix="${v.prix_vente_final}" data-stock="${v.stock}" data-specs="${v.variant_name}">${v.variant_name} - ${v.prix_vente_final} DH (Stock: ${v.stock})</option>`;
                                });
                                row.find('.variant-select').html(variantOptions);
                            }
                        }
                    });
                }
                calculateTotal();
            });

            // ===== CHANGEMENT VARIANT =====
            $(document).on('change', '.variant-select', function() {
                const row = $(this).closest('.item-row');
                const variantId = $(this).val();
                resetRemiseRow(row);

                if (variantId) {
                    const prix = $(this).find(':selected').data('prix');
                    const stock = $(this).find(':selected').data('stock');
                    const specs = $(this).find(':selected').data('specs');
                    row.find('.prix-display').val(parseFloat(prix).toFixed(2) + ' DH');
                    row.find('.quantite-input').attr('max', stock);
                    row.find('.variant-specs').show();
                    row.find('.specs-text').html(`<strong>Specs:</strong> ${specs}`);
                } else {
                    const prix = row.find('.produit-select option:selected').data('prix');
                    const stock = row.find('.produit-select option:selected').data('stock');
                    row.find('.prix-display').val(parseFloat(prix || 0).toFixed(2) + ' DH');
                    row.find('.quantite-input').attr('max', stock);
                    row.find('.variant-specs').hide();
                }
                calculateTotal();
            });

            // ===== AJOUTER ITEM =====
            $('#add-item').click(function() {
                const idx = itemIndex;
                const newItem = `
                    <div class="item-row mb-3 p-3 border rounded" data-index="${idx}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Produit <span class="text-danger">*</span></label>
                                <select name="items[${idx}][produit_id]" class="form-select produit-select" required>${produitsOptions}</select>
                            </div>
                            <div class="col-md-3 variant-container" style="display: none;">
                                <label class="form-label">Variant (optionnel)</label>
                                <select name="items[${idx}][product_variant_id]" class="form-select variant-select">
                                    <option value="">-- Produit de base (sans variant) --</option>
                                </select>
                                <small class="text-muted variant-info"></small>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Qté <span class="text-danger">*</span></label>
                                <input type="number" name="items[${idx}][quantite]" class="form-control quantite-input" min="1" value="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Prix Unit.</label>
                                <input type="text" class="form-control prix-display" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Remise</label>
                                <div class="input-group input-group-sm">
                                    <input type="hidden" name="items[${idx}][remise_appliquee]" class="remise-appliquee-input" value="0">
                                    <input type="hidden" name="items[${idx}][remise_pourcentage]" class="remise-pourcentage-input" value="0">
                                    <input type="hidden" name="items[${idx}][remise_montant]" class="remise-montant-input" value="0">
                                    <input type="text" class="form-control remise-display bg-light" readonly placeholder="Aucune">
                                    <button type="button" class="btn btn-warning btn-remise" title="Appliquer remise">
                                        <i class="fas fa-tag"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Total ligne</label>
                                <input type="text" class="form-control total-ligne-display bg-light fw-bold" readonly value="0.00 DH">
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="col-12 variant-specs" style="display: none;">
                                <div class="alert alert-info mb-0 py-2"><small class="specs-text"></small></div>
                            </div>
                            <div class="col-12 remise-badge-container" style="display: none;">
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-tag me-1"></i>
                                    <span class="remise-badge-text"></span>
                                    <button type="button" class="btn-close btn-close-sm ms-2 btn-supprimer-remise" style="font-size: 0.6rem;"></button>
                                </span>
                            </div>
                        </div>
                    </div>
                `;
                $('#items-container').append(newItem);
                itemIndex++;
                updateRemoveButtons();
            });

            // ===== SUPPRIMER ITEM =====
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                updateRemoveButtons();
                calculateTotal();
            });

            // ===== BOUTON REMISE =====
            $(document).on('click', '.btn-remise', function() {
                currentRemiseRow = $(this).closest('.item-row');
                const produitNom = currentRemiseRow.find('.produit-select option:selected').text().trim();
                const prix = getPrixUnitaire(currentRemiseRow);
                const quantite = parseInt(currentRemiseRow.find('.quantite-input').val()) || 0;
                currentRemiseSousTotal = prix * quantite;

                if (!currentRemiseRow.find('.produit-select').val() || currentRemiseSousTotal <= 0) {
                    Swal.fire('Attention', 'Veuillez sélectionner un produit et une quantité valide', 'warning');
                    return;
                }

                // Pré-remplir si remise déjà appliquée
                const pourcentage = parseFloat(currentRemiseRow.find('.remise-pourcentage-input').val()) || 0;
                const montant = parseFloat(currentRemiseRow.find('.remise-montant-input').val()) || 0;

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

            // ===== SUPPRIMER REMISE =====
            $(document).on('click', '.btn-supprimer-remise', function() {
                const row = $(this).closest('.item-row');
                resetRemiseRow(row);
                calculateTotal();
            });

            // ===== RECALCUL =====
            $(document).on('input', '.quantite-input, #tva', function() {
                if ($(this).hasClass('quantite-input')) {
                    const row = $(this).closest('.item-row');
                    const remiseAppliquee = row.find('.remise-appliquee-input').val() === '1';
                    const remisePourcentage = parseFloat(row.find('.remise-pourcentage-input').val()) || 0;

                    if (remiseAppliquee && remisePourcentage > 0) {
                        const prix = getPrixUnitaire(row);
                        const quantite = parseInt($(this).val()) || 0;
                        const sousTotal = prix * quantite;
                        const montantRemise = (sousTotal * remisePourcentage) / 100;
                        row.find('.remise-montant-input').val(montantRemise.toFixed(2));
                    }
                }
                calculateTotal();
            });

            // ===== SUBMIT VALIDATION =====
            $('#recuForm').on('submit', function(e) {
                let valid = true;
                let errors = [];
                $('.item-row').each(function() {
                    const row = $(this);
                    const produitId = row.find('.produit-select').val();
                    const quantite = parseInt(row.find('.quantite-input').val());
                    const maxStock = parseInt(row.find('.quantite-input').attr('max'));
                    if (!produitId) { valid = false; errors.push('Veuillez sélectionner un produit pour chaque ligne'); }
                    if (maxStock && quantite > maxStock) { valid = false; errors.push(`Stock insuffisant pour "${row.find('.produit-select option:selected').text().trim()}"`); }
                });
                if (!valid) {
                    e.preventDefault();
                    Swal.fire({ icon: 'error', title: 'Erreur de validation', html: errors.join('<br>'), confirmButtonText: 'OK' });
                }
            });

            calculateTotal();
        });

        // ===== HELPERS =====
        function getPrixUnitaire(row) {
            const variantVal = row.find('.variant-select').val();
            if (variantVal) return parseFloat(row.find('.variant-select option:selected').data('prix')) || 0;
            return parseFloat(row.find('.produit-select option:selected').data('prix')) || 0;
        }

        function resetRemiseRow(row) {
            row.find('.remise-appliquee-input').val('0');
            row.find('.remise-pourcentage-input').val('0');
            row.find('.remise-montant-input').val('0');
            row.find('.remise-display').val('').attr('placeholder', 'Aucune');
            row.find('.btn-remise').removeClass('btn-info').addClass('btn-warning');
            row.find('.remise-badge-container').hide();
            row.removeClass('border-warning');
            calculateTotal();
        }

        function toggleRemiseType() {
            const type = $('#modal-type-remise').val();
            const unite = document.getElementById('remise-unite');
            const hint = document.getElementById('remise-max-hint');
            const input = document.getElementById('modal-valeur-remise');
            if (type === 'pourcentage') {
                unite.textContent = '%';
                input.max = 100;
                hint.textContent = 'Maximum: 100%';
            } else {
                unite.textContent = 'DH';
                input.max = currentRemiseSousTotal;
                hint.textContent = `Maximum: ${currentRemiseSousTotal.toFixed(2)} DH`;
            }
        }

        function confirmerRemise() {
            const type = $('#modal-type-remise').val();
            const valeur = parseFloat($('#modal-valeur-remise').val());
            if (!valeur || valeur <= 0) { Swal.fire('Erreur', 'Veuillez entrer une valeur valide', 'error'); return; }
            if (type === 'pourcentage' && valeur > 100) { Swal.fire('Erreur', 'Pourcentage maximum: 100%', 'error'); return; }
            if (type === 'montant' && valeur > currentRemiseSousTotal) { Swal.fire('Erreur', 'Remise ne peut pas dépasser le sous-total', 'error'); return; }

            const row = currentRemiseRow;
            row.find('.remise-appliquee-input').val('1');
            row.addClass('border-warning');

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
            let sousTotal = 0;
            let totalRemises = 0;

            $('.item-row').each(function() {
                const row = $(this);
                const prix = getPrixUnitaire(row);
                const quantite = parseInt(row.find('.quantite-input').val()) || 0;
                const ligneSousTotal = prix * quantite;

                const remiseAppliquee = row.find('.remise-appliquee-input').val() === '1';
                const remisePourcentage = parseFloat(row.find('.remise-pourcentage-input').val()) || 0;
                const remiseMontant = parseFloat(row.find('.remise-montant-input').val()) || 0;

                let montantRemise = 0;
                if (remiseAppliquee) {
                    montantRemise = remisePourcentage > 0 ? (ligneSousTotal * remisePourcentage) / 100 : remiseMontant;
                }

                const ligneTotal = Math.max(0, ligneSousTotal - montantRemise);
                sousTotal += ligneSousTotal;
                totalRemises += montantRemise;

                row.find('.total-ligne-display').val(ligneTotal.toFixed(2) + ' DH');
                if (montantRemise > 0) {
                    row.find('.total-ligne-display').addClass('text-success').removeClass('text-dark');
                } else {
                    row.find('.total-ligne-display').removeClass('text-success').addClass('text-dark');
                }
            });

            const tva = parseFloat($('#tva').val()) || 0;
            const total = sousTotal - totalRemises + tva;

            $('#sous-total-display').val(sousTotal.toFixed(2));
            $('#remises-total-display').val(totalRemises > 0 ? '-' + totalRemises.toFixed(2) : '0.00');
            $('#total-display').val(total.toFixed(2));
        }

        function updateRemoveButtons() {
            const count = $('.item-row').length;
            $('.remove-item').prop('disabled', count <= 1);
        }
    </script>
    @endpush
</x-app-layout>