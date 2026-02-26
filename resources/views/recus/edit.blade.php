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

        <!-- Alert info sur remises -->
        @if($recu->items->where('remise_appliquee', true)->count() > 0)
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Attention:</strong> Ce reçu contient {{ $recu->items->where('remise_appliquee', true)->count() }} article(s) avec remise.
            Les remises seront conservées lors de la modification.
        </div>
        @endif

        <form action="{{ route('recus.update', $recu) }}" method="POST" id="recuForm">
            @csrf
            @method('PUT')
            
            <!-- ✅ Champ remise caché (conserve remise actuelle calculée automatiquement) -->
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
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Pour gérer les remises par article, veuillez utiliser la page de détails du reçu après la sauvegarde.
                    </div>
                    
                    <div id="items-container">
                        @foreach($recu->items as $index => $item)
                        <div class="item-row mb-3 p-3 border rounded {{ $item->remise_appliquee ? 'border-warning' : '' }}">
                            @if($item->remise_appliquee)
                            <div class="alert alert-warning py-1 mb-2">
                                <small>
                                    <i class="fas fa-tag me-1"></i>
                                    Remise appliquée: 
                                    @if($item->remise_pourcentage > 0)
                                        {{ $item->remise_pourcentage }}%
                                    @else
                                        {{ number_format($item->remise_montant, 2) }} DH
                                    @endif
                                </small>
                            </div>
                            @endif
                            
                            <div class="row g-3">
                                <!-- Sélection Produit -->
                                <div class="col-md-5">
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
                                <div class="col-md-4 variant-container" style="display: {{ $item->product_variant_id ? 'block' : 'none' }};">
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
                                <div class="col-md-2">
                                    <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <input type="number" name="items[{{ $index }}][quantite]" class="form-control quantite-input"
                                           min="1" value="{{ old('items.'.$index.'.quantite', $item->quantite) }}" required>
                                </div>

                                <!-- Prix -->
                                <div class="col-md-2">
                                    <label class="form-label">Prix Unit.</label>
                                    <input type="text" class="form-control prix-display" 
                                           value="{{ number_format($item->prix_unitaire, 2) }} DH" readonly>
                                </div>

                                <!-- Supprimer -->
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-item" {{ count($recu->items) <= 1 ? 'disabled' : '' }}>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Specs -->
                                <div class="col-12 variant-specs" style="display: {{ $item->variant ? 'block' : 'none' }};">
                                    <div class="alert alert-info mb-0 py-2">
                                        <small class="specs-text">
                                            @if($item->variant)
                                                <strong>Specs:</strong> {{ $item->variant->variant_name }}
                                            @endif
                                        </small>
                                    </div>
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

            <!-- Section Paiement (SANS champ remise) -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i>Paiement & Montants</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">TVA (DH)</label>
                            <input type="number" name="tva" id="tva" class="form-control" 
                                   step="0.01" min="0" value="{{ old('tva', $recu->tva) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total Estimé (DH)</label>
                            <input type="text" id="total-display" class="form-control bg-light" 
                                   readonly value="{{ number_format($recu->total, 2) }}">
                            <small class="text-muted">Le total sera recalculé automatiquement</small>
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
                            <input type="number" name="montant_paye" class="form-control" 
                                   step="0.01" min="0" value="{{ old('montant_paye', $recu->paiements->sum('montant')) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de Paiement</label>
                            <!-- Déjà correct dans ton code, mais vérifie la valeur par défaut -->
<input type="date" name="date_paiement" class="form-control" 
       value="{{ old('date_paiement', $recu->date_paiement 
           ? $recu->date_paiement->format('Y-m-d') 
           : $recu->created_at->format('Y-m-d')) }}">
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

    @push('scripts')
    <script>
        console.log('✅ Script Edit Mode chargé');
        
        let itemIndex = {{ count($recu->items) }};
        
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
            $('#category-filter').on('change', function() {
                const categoryId = $(this).val();
                
                if (!categoryId) {
                    $('.produit-select').each(function() {
                        const currentVal = $(this).val();
                        $(this).html(produitsOptions);
                        $(this).val(currentVal);
                    });
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
                            response.produits.forEach(produit => {
                                options += `<option value="${produit.id}" data-prix="${produit.prix_vente}" data-stock="${produit.quantite_stock}" data-has-variants="${produit.has_variants}">${produit.nom} (Stock: ${produit.quantite_stock})</option>`;
                            });
                            $('.produit-select').each(function() {
                                const currentVal = $(this).val();
                                $(this).html(options);
                                $(this).val(currentVal);
                            });
                            $('#filter-info').html(`<span class="text-success">✅ ${response.produits.length} produit(s)</span>`);
                        } else {
                            $('.produit-select').html('<option value="">Aucun produit</option>');
                            $('#filter-info').html('<span class="text-warning">⚠️ Aucun produit</span>');
                        }
                    }
                });
            });

            $('#reset-filter').on('click', function() {
                $('#category-filter').val('').trigger('change');
            });

            $(document).on('change', '.produit-select', function() {
                const row = $(this).closest('.item-row');
                const hasVariants = $(this).find(':selected').data('has-variants') === true;
                const prix = $(this).find(':selected').data('prix');
                const stock = $(this).find(':selected').data('stock');

                row.find('.variant-container').hide();
                row.find('.prix-display').val(parseFloat(prix).toFixed(2) + ' DH');
                row.find('.quantite-input').attr('max', stock);

                if (hasVariants) {
                    const produitId = $(this).val();
                    $.ajax({
                        url: `/api/variants/produit/${produitId}`,
                        method: 'GET',
                        success: function(response) {
                            if (response.success) {
                                row.find('.variant-container').show();
                                let options = '<option value="">-- Produit de base --</option>';
                                response.variants.forEach(v => {
                                    options += `<option value="${v.id}" data-prix="${v.prix_vente_final}" data-stock="${v.stock}" data-specs="${v.variant_name}">${v.variant_name} - ${v.prix_vente_final} DH</option>`;
                                });
                                row.find('.variant-select').html(options);
                            }
                        }
                    });
                }
                calculateTotal();
            });

            $(document).on('change', '.variant-select', function() {
                const row = $(this).closest('.item-row');
                const variantId = $(this).val();
                if (variantId) {
                    const prix = $(this).find(':selected').data('prix');
                    const stock = $(this).find(':selected').data('stock');
                    row.find('.prix-display').val(parseFloat(prix).toFixed(2) + ' DH');
                    row.find('.quantite-input').attr('max', stock);
                    row.find('.variant-specs').show();
                    row.find('.specs-text').html(`<strong>Specs:</strong> ${$(this).find(':selected').data('specs')}`);
                } else {
                    const produitSelect = row.find('.produit-select');
                    const prix = produitSelect.find(':selected').data('prix');
                    row.find('.prix-display').val(parseFloat(prix).toFixed(2) + ' DH');
                    row.find('.variant-specs').hide();
                }
                calculateTotal();
            });

            $('#add-item').click(function() {
                const newItem = `
                    <div class="item-row mb-3 p-3 border rounded">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Produit *</label>
                                <select name="items[${itemIndex}][produit_id]" class="form-select produit-select" required>${produitsOptions}</select>
                            </div>
                            <div class="col-md-4 variant-container" style="display: none;">
                                <label class="form-label">Variant (optionnel)</label>
                                <select name="items[${itemIndex}][product_variant_id]" class="form-select variant-select"><option value="">-- Produit de base --</option></select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantité *</label>
                                <input type="number" name="items[${itemIndex}][quantite]" class="form-control quantite-input" min="1" value="1" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Prix Unit.</label>
                                <input type="text" class="form-control prix-display" readonly>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-item"><i class="fas fa-trash"></i></button>
                            </div>
                            <div class="col-12 variant-specs" style="display: none;"><div class="alert alert-info mb-0 py-2"><small class="specs-text"></small></div></div>
                        </div>
                    </div>
                `;
                $('#items-container').append(newItem);
                itemIndex++;
                updateRemoveButtons();
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                updateRemoveButtons();
                calculateTotal();
            });

            $(document).on('input', '.quantite-input, #tva', calculateTotal);

            function calculateTotal() {
                let sousTotal = 0;
                $('.item-row').each(function() {
                    const row = $(this);
                    let prix = 0;
                    const variantSelect = row.find('.variant-select');
                    const produitSelect = row.find('.produit-select');
                    if (variantSelect.val()) {
                        prix = parseFloat(variantSelect.find(':selected').data('prix')) || 0;
                    } else {
                        prix = parseFloat(produitSelect.find(':selected').data('prix')) || 0;
                    }
                    const quantite = parseInt(row.find('.quantite-input').val()) || 0;
                    sousTotal += prix * quantite;
                });
                const tva = parseFloat($('#tva').val()) || 0;
                const total = sousTotal + tva; // ✅ Pas de remise
                $('#total-display').val(total.toFixed(2));
            }

            function updateRemoveButtons() {
                const itemCount = $('.item-row').length;
                $('.remove-item').prop('disabled', itemCount <= 1);
            }

            calculateTotal();
        });
    </script>
    @endpush
</x-app-layout>