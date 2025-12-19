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

            <!-- Section Produits avec Variants -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Produits</h5>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        <div class="item-row mb-3 p-3 border rounded">
                            <div class="row g-3">
                                <!-- Sélection Produit -->
                                <div class="col-md-5">
                                    <label class="form-label">Produit <span class="text-danger">*</span></label>
                                    <select name="items[0][produit_id]" class="form-select produit-select" required>
                                        <option value="">-- Sélectionner un produit --</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}" 
                                                    data-prix="{{ $produit->prix_vente }}"
                                                    data-stock="{{ $produit->quantite_stock }}"
                                                    data-has-variants="{{ $produit->variants->where('actif', true)->where('quantite_stock', '>', 0)->count() > 0 ? 'true' : 'false' }}">
                                                {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sélection Variant -->
                                <div class="col-md-4 variant-container" style="display: none;">
                                    <label class="form-label">Variant <span class="text-danger">*</span></label>
                                    <select name="items[0][product_variant_id]" class="form-select variant-select">
                                        <option value="">-- Choisir un variant --</option>
                                    </select>
                                    <small class="text-muted variant-info"></small>
                                </div>

                                <!-- Quantité -->
                                <div class="col-md-2">
                                    <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <input type="number" name="items[0][quantite]" class="form-control quantite-input"
                                           min="1" value="1" required>
                                </div>

                                <!-- Prix -->
                                <div class="col-md-2">
                                    <label class="form-label">Prix Unit.</label>
                                    <input type="text" class="form-control prix-display" readonly>
                                </div>

                                <!-- Supprimer -->
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Specs -->
                                <div class="col-12 variant-specs" style="display: none;">
                                    <div class="alert alert-info mb-0 py-2">
                                        <small class="specs-text"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                            <label class="form-label">Remise (DH)</label>
                            <input type="number" name="remise" id="remise" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">TVA (DH)</label>
                            <input type="number" name="tva" id="tva" class="form-control" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Total (DH)</label>
                            <input type="text" id="total-display" class="form-control bg-light" readonly value="0.00">
                        </div>
                        <div class="col-md-3">
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
                            <input type="number" name="montant_paye" class="form-control" step="0.01" min="0" value="0">
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

    @push('scripts')
    <script>
        console.log('✅ Script variants chargé');
        console.log('jQuery version:', $.fn.jquery);

       // ✅ SOLUTION : Permettre la création sans variant même si variants disponibles

$(document).ready(function() {
    console.log('✅ Script variants chargé');
    let itemIndex = 1;

    const produitsOptions = `
        <option value="">-- Sélectionner un produit --</option>
        @foreach($produits as $produit)
            <option value="{{ $produit->id }}" 
                    data-prix="{{ $produit->prix_vente }}"
                    data-stock="{{ $produit->quantite_stock }}"
                    data-has-variants="{{ $produit->variants->where('actif', true)->where('quantite_stock', '>', 0)->count() > 0 ? 'true' : 'false' }}">
                {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
            </option>
        @endforeach
    `;

    // Gestion sélection produit
    $(document).on('change', '.produit-select', function() {
        const row = $(this).closest('.item-row');
        const produitId = $(this).val();
        const hasVariants = $(this).find(':selected').data('has-variants') === true;
        const prix = $(this).find(':selected').data('prix');
        const stock = $(this).find(':selected').data('stock');

        // Reset
        row.find('.variant-container').hide();
        row.find('.variant-select').html('<option value="">-- Choisir un variant --</option>');
        row.find('.variant-specs').hide();
        row.find('.prix-display').val('');

        if (!produitId) return;

        // ✅ TOUJOURS afficher le prix du produit de base
        row.find('.prix-display').val(parseFloat(prix).toFixed(2) + ' DH');
        row.find('.quantite-input').attr('max', stock);

        if (hasVariants) {
            console.log('⚡ Chargement variants...');
            $.ajax({
                url: `/api/variants/produit/${produitId}`,
                method: 'GET',
                beforeSend: function() {
                    row.find('.variant-info').text('🔄 Chargement...');
                },
                success: function(response) {
                    if (response.success && response.variants.length > 0) {
                        row.find('.variant-container').show();
                        let variantOptions = '<option value="">-- Produit de base (sans variant) --</option>'; // ✅ Option par défaut
                        response.variants.forEach(variant => {
                            variantOptions += `
                                <option value="${variant.id}" 
                                        data-prix="${variant.prix_vente_final}"
                                        data-stock="${variant.stock}"
                                        data-specs="${variant.variant_name}">
                                    ${variant.variant_name} - ${variant.prix_vente_final} DH (Stock: ${variant.stock})
                                </option>
                            `;
                        });
                        row.find('.variant-select').html(variantOptions);
                        row.find('.variant-info').html('<small class="text-info">💡 Vous pouvez choisir un variant ou laisser vide pour utiliser le produit de base</small>');
                    }
                },
                error: function(xhr) {
                    console.error('❌ Erreur API:', xhr.responseText);
                    row.find('.variant-info').text('❌ Erreur chargement');
                }
            });
        }
        calculateTotal();
    });

    // Gestion sélection variant
    $(document).on('change', '.variant-select', function() {
        const row = $(this).closest('.item-row');
        const variantId = $(this).val();
        
        if (variantId) {
            // Variant sélectionné
            const prix = $(this).find(':selected').data('prix');
            const stock = $(this).find(':selected').data('stock');
            const specs = $(this).find(':selected').data('specs');

            row.find('.prix-display').val(parseFloat(prix).toFixed(2) + ' DH');
            row.find('.quantite-input').attr('max', stock);
            row.find('.variant-specs').show();
            row.find('.specs-text').html(`<strong>Specs:</strong> ${specs}`);
        } else {
            // Retour au produit de base
            const produitSelect = row.find('.produit-select');
            const prix = produitSelect.find(':selected').data('prix');
            const stock = produitSelect.find(':selected').data('stock');
            
            row.find('.prix-display').val(parseFloat(prix).toFixed(2) + ' DH');
            row.find('.quantite-input').attr('max', stock);
            row.find('.variant-specs').hide();
        }
        calculateTotal();
    });

    // Ajouter item
    $('#add-item').click(function() {
        const newItem = `
            <div class="item-row mb-3 p-3 border rounded">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Produit <span class="text-danger">*</span></label>
                        <select name="items[${itemIndex}][produit_id]" class="form-select produit-select" required>
                            ${produitsOptions}
                        </select>
                    </div>
                    <div class="col-md-4 variant-container" style="display: none;">
                        <label class="form-label">Variant (optionnel)</label>
                        <select name="items[${itemIndex}][product_variant_id]" class="form-select variant-select">
                            <option value="">-- Choisir un variant --</option>
                        </select>
                        <small class="text-muted variant-info"></small>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Quantité <span class="text-danger">*</span></label>
                        <input type="number" name="items[${itemIndex}][quantite]" class="form-control quantite-input"
                               min="1" value="1" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Prix Unit.</label>
                        <input type="text" class="form-control prix-display" readonly>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-item">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="col-12 variant-specs" style="display: none;">
                        <div class="alert alert-info mb-0 py-2">
                            <small class="specs-text"></small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('#items-container').append(newItem);
        itemIndex++;
        updateRemoveButtons();
    });

    // Supprimer item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        updateRemoveButtons();
        calculateTotal();
    });

    // Calcul total
    $(document).on('input', '.quantite-input, #remise, #tva', calculateTotal);

    function calculateTotal() {
        let sousTotal = 0;
        $('.item-row').each(function() {
            const row = $(this);
            let prix = 0;
            const variantSelect = row.find('.variant-select');
            const produitSelect = row.find('.produit-select');
            
            // ✅ Si variant sélectionné, utiliser son prix, sinon utiliser le prix du produit
            if (variantSelect.val()) {
                prix = parseFloat(variantSelect.find(':selected').data('prix')) || 0;
            } else {
                prix = parseFloat(produitSelect.find(':selected').data('prix')) || 0;
            }
            
            const quantite = parseInt(row.find('.quantite-input').val()) || 0;
            sousTotal += prix * quantite;
        });

        const remise = parseFloat($('#remise').val()) || 0;
        const tva = parseFloat($('#tva').val()) || 0;
        const total = sousTotal - remise + tva;
        $('#total-display').val(total.toFixed(2));
    }

    function updateRemoveButtons() {
        const itemCount = $('.item-row').length;
        $('.remove-item').prop('disabled', itemCount <= 1);
    }

    // ✅ VALIDATION CORRIGÉE : Accepter les produits SANS variant
    $('#recuForm').on('submit', function(e) {
        let valid = true;
        let errors = [];

        $('.item-row').each(function() {
            const row = $(this);
            const produitId = row.find('.produit-select').val();
            const quantite = parseInt(row.find('.quantite-input').val());
            const maxStock = parseInt(row.find('.quantite-input').attr('max'));

            // Vérifier que le produit est sélectionné
            if (!produitId) {
                valid = false;
                errors.push('Veuillez sélectionner un produit pour chaque ligne');
            }

            // Vérifier le stock
            if (quantite > maxStock) {
                valid = false;
                errors.push(`Stock insuffisant pour "${row.find('.produit-select option:selected').text()}"`);
            }
        });

        if (!valid) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Erreur de validation',
                html: errors.join('<br>'),
                confirmButtonText: 'OK'
            });
        }
    });
});
    </script>
    @endpush
</x-app-layout>