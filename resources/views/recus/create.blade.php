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
                            <h5 class="mb-0">
                                <i class="fas fa-user me-2"></i>
                                Informations Client
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="client_nom" 
                                           class="form-control @error('client_nom') is-invalid @enderror"
                                           value="{{ old('client_nom') }}"
                                           required>
                                    @error('client_nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Prénom</label>
                                    <input type="text" 
                                           name="client_prenom" 
                                           class="form-control"
                                           value="{{ old('client_prenom') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" 
                                           name="client_telephone" 
                                           class="form-control"
                                           value="{{ old('client_telephone') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" 
                                           name="client_email" 
                                           class="form-control"
                                           value="{{ old('client_email') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse</label>
                                    <textarea name="client_adresse" 
                                              class="form-control" 
                                              rows="2">{{ old('client_adresse') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Équipement & Garantie -->
                <div class="col-md-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-laptop me-2"></i>
                                Équipement & Garantie
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Équipement</label>
                                    <input type="text" 
                                           name="equipement" 
                                           class="form-control"
                                           placeholder="Ex: PC HP, Imprimante Canon..."
                                           value="{{ old('equipement') }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Détails</label>
                                    <textarea name="details" 
                                              class="form-control" 
                                              rows="2"
                                              placeholder="Description du problème...">{{ old('details') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Type de Garantie <span class="text-danger">*</span></label>
                                    <select name="type_garantie" class="form-select" required>
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

            <!-- Section Produits -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-boxes me-2"></i>
                        Produits
                    </h5>
                </div>
                <div class="card-body">
                    <div id="items-container">
                        <div class="item-row mb-3 p-3 border rounded">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Produit <span class="text-danger">*</span></label>
                                    <select name="items[0][produit_id]" 
                                            class="form-select produit-select" 
                                            required>
                                        <option value="">-- Sélectionner --</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}" 
                                                    data-prix="{{ $produit->prix_vente }}"
                                                    data-stock="{{ $produit->quantite_stock }}">
                                                {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           name="items[0][quantite]" 
                                           class="form-control quantite-input"
                                           min="1"
                                           value="1"
                                           required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Prix Unitaire</label>
                                    <input type="text" 
                                           class="form-control prix-display"
                                           readonly>
                                </div>
                                <div class="col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-item" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success" id="add-item">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter un produit
                    </button>
                </div>
            </div>

            <!-- Section Paiement -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Paiement & Montants
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Remise (DH)</label>
                            <input type="number" 
                                   name="remise" 
                                   id="remise"
                                   class="form-control"
                                   step="0.01"
                                   min="0"
                                   value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">TVA (DH)</label>
                            <input type="number" 
                                   name="tva" 
                                   id="tva"
                                   class="form-control"
                                   step="0.01"
                                   min="0"
                                   value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Total (DH)</label>
                            <input type="text" 
                                   id="total-display"
                                   class="form-control bg-light"
                                   readonly
                                   value="0.00">
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
                            <input type="number" 
                                   name="montant_paye" 
                                   class="form-control"
                                   step="0.01"
                                   min="0"
                                   value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date de Paiement</label>
                            <input type="date" 
                                   name="date_paiement" 
                                   class="form-control"
                                   value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" 
                                      class="form-control" 
                                      rows="2">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons -->
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('recus.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>
                    Créer le Reçu
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            let itemIndex = 1;

            // Template pour nouveaux items
            const produitsOptions = `
                <option value="">-- Sélectionner --</option>
                @foreach($produits as $produit)
                    <option value="{{ $produit->id }}" 
                            data-prix="{{ $produit->prix_vente }}"
                            data-stock="{{ $produit->quantite_stock }}">
                        {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                    </option>
                @endforeach
            `;

            // Ajouter un item
            $('#add-item').click(function() {
                const newItem = `
                    <div class="item-row mb-3 p-3 border rounded">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Produit <span class="text-danger">*</span></label>
                                <select name="items[${itemIndex}][produit_id]" 
                                        class="form-select produit-select" 
                                        required>
                                    ${produitsOptions}
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                <input type="number" 
                                       name="items[${itemIndex}][quantite]" 
                                       class="form-control quantite-input"
                                       min="1"
                                       value="1"
                                       required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Prix Unitaire</label>
                                <input type="text" 
                                       class="form-control prix-display"
                                       readonly>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-item">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $('#items-container').append(newItem);
                itemIndex++;
                updateRemoveButtons();
            });

            // Supprimer un item
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item-row').remove();
                updateRemoveButtons();
                calculateTotal();
            });

            // Mettre à jour prix quand on sélectionne un produit
            $(document).on('change', '.produit-select', function() {
                const prix = $(this).find(':selected').data('prix');
                const stock = $(this).find(':selected').data('stock');
                const row = $(this).closest('.item-row');
                
                row.find('.prix-display').val(prix ? parseFloat(prix).toFixed(2) + ' DH' : '');
                row.find('.quantite-input').attr('max', stock);
                
                calculateTotal();
            });

            // Calculer total
            $(document).on('input', '.quantite-input, #remise, #tva', calculateTotal);

            function calculateTotal() {
                let sousTotal = 0;
                
                $('.item-row').each(function() {
                    const select = $(this).find('.produit-select');
                    const prix = parseFloat(select.find(':selected').data('prix')) || 0;
                    const quantite = parseInt($(this).find('.quantite-input').val()) || 0;
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

            // Validation
            $('#recuForm').on('submit', function(e) {
                let valid = true;
                
                $('.produit-select').each(function() {
                    if (!$(this).val()) {
                        valid = false;
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Veuillez sélectionner tous les produits!'
                    });
                }
            });
        });
    </script>
</x-app-layout>