<x-app-layout>
    <style>
        /* Les styles généraux et thématiques */
        .gradient-bg {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .gradient-text {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .edit-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px; /* Ajout d'un padding pour un meilleur espace */
        }

        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #f3f4f6;
        }

        .section-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-right: 15px;
        }

        .section-title {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
            display: block;
        }

        .form-label .required {
            color: #ef4444;
            margin-left: 3px;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
            outline: none;
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
            color: #6b7280;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Styles spécifiques aux lignes de produits */
        .product-row {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            position: relative;
            transition: all 0.3s ease;
        }

        .product-row:hover {
            border-color: #D32F2F;
            box-shadow: 0 5px 20px rgba(211, 47, 47, 0.1);
        }
        
        /* Product Number (new addition to align with devis style) */
        .product-number {
            position: absolute;
            top: -15px;
            left: 20px;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(211, 47, 47, 0.3);
        }
        
        /* Styles des informations importantes */
        .important-item { /* Changé .important-row à .important-item pour le sélecteur d'origine */
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px !important; /* Pour override mb-3 de Bootstrap */
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .important-item input {
            flex: 1;
            margin-bottom: 0 !important;
        }
        /* Fin Styles des informations importantes */


        /* Styles des boutons */
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
            color: white;
        }

        .btn-add {
            background: linear-gradient(135deg, #10b981, #059669); /* Vert pour Ajouter Produit */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
            color: white;
        }
        
        .btn-remove {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
            color: white;
        }

        .btn-important {
            background: linear-gradient(135deg, #f59e0b, #d97706); /* Jaune/Orange pour Ajouter Info Importante */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-important:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.3);
            color: white;
        }
        /* Fin Styles des boutons */

        /* Styles des Totaux */
        .total-section {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            border: 2px solid #10b981;
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #d1fae5;
        }

        .total-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .total-label {
            font-size: 16px;
            font-weight: 600;
            color: #065f46;
        }

        .total-value {
            font-size: 18px;
            font-weight: 700;
            color: #059669;
        }

        .total-ttc-row {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 15px;
        }

        .total-ttc-row .total-label,
        .total-ttc-row .total-value {
            color: white;
        }

        .total-ttc-row .total-value {
            font-size: 28px;
        }
        /* Fin Styles des Totaux */

        /* Mise en page en Grille */
        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        
        .grid-4 {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }
        /* Fin Mise en page en Grille */

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 2px solid #ef4444;
            border-radius: 12px;
            padding: 15px 20px;
            color: #b91c1c;
            font-weight: 500;
            margin-bottom: 25px;
        }
        
        .alert-danger ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        
        /* Media Queries pour la réactivité */
        @media (max-width: 992px) {
            .grid-4, .grid-3, .grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-card {
                padding: 25px;
            }
        }

        @media (max-width: 576px) {
            .grid-4, .grid-3, .grid-2 {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 20px;
            }

            .product-row {
                padding: 20px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons button,
            .action-buttons a {
                width: 100%;
            }
            .btn-remove {
                padding: 12px 20px;
            }
        }
    </style>

    <div class="edit-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-file-invoice"></i> Modifier la Facture N° {{ $facture->facture_num }}
                </h2>
                <p class="text-muted mb-0">Mettez à jour les informations de votre facture</p>
            </div>
            {{-- Option pour un bouton de retour --}}
            {{-- <a href="{{ route('factures.show', $facture->id) }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Retour à la Facture
            </a> --}}
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('factures.update', $facture->id) }}" method="POST" id="facture-form">
            @csrf
            @method('PUT')

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales</h3>
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label for="facture_num" class="form-label">Numéro de la Facture</label>
                        <input type="text" name="facture_num" class="form-control" value="{{ old('facture_num', $facture->facture_num) }}" required>
                    </div>
                    <div>
                        <label for="date" class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $facture->date) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Titre de la facture" value="{{ old('titre', $facture->titre) }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label for="client" class="form-label">Client <span class="required">*</span></label>
                        <input type="text" name="client" class="form-control" placeholder="Nom du client" value="{{ old('client', $facture->client) }}" required>
                    </div>
                    <div>
                        <label for="ice" class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" placeholder="Numéro ICE" value="{{ old('ice', $facture->ice) }}">
                    </div>
                    <div>
                        <label for="ref" class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" placeholder="Référence interne" value="{{ old('ref', $facture->ref) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" placeholder="Adresse du client" value="{{ old('adresse', $facture->adresse) }}">
                </div>
            </div>

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="section-title">Produits & Services</h3>
                </div>

                <div id="product-container">
                    @foreach($facture->items as $index => $item)
                    <div class="product-row">
                        <div class="product-number">{{ $index + 1 }}</div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description / Libellé <span class="required">*</span></label>
                            <textarea name="libele[]" class="form-control" rows="3" placeholder="Description détaillée du produit ou service..." required>{{ old("libele.$index", $item->libele) }}</textarea>
                        </div>

                        <div class="row align-items-end">
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="form-label">Quantité <span class="required">*</span></label>
                                <input type="number" name="quantite[]" class="form-control quantity" value="{{ old("quantite.$index", $item->quantite) }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="form-label">Prix HT <span class="required">*</span></label>
                                <input type="number" step="0.01" name="prix_ht[]" class="form-control prix_ht" value="{{ old("prix_ht.$index", $item->prix_ht) }}" oninput="calculatePrixTotal()" min="0" required>
                            </div>
                            <div class="col-md-3 mb-3 mb-md-0">
                                <label class="form-label">Prix Total</label>
                                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                                    <i class="fas fa-trash me-2"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-add mt-3" onclick="addProduct()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un Produit
                </button>
            </div>
            
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="section-title">Calculs & Totaux</h3>
                </div>
                
                <div class="grid-2 mb-3">
                    <div>
                        <label for="currency" class="form-label">Devise <span class="required">*</span></label>
                        <select name="currency" class="form-select" required>
                            <option value="DH" {{ old('currency', $facture->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency', $facture->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="CFA" {{ old('currency', $facture->currency) == 'CFA' ? 'selected' : '' }}>CFA</option>
                        </select>
                    </div>
                    <div>
                        <label for="tva" class="form-label">TVA <span class="required">*</span></label>
                        <select name="tva" class="form-select" onchange="calculateTTC()" required>
                            <option value="20" {{ old('tva', $facture->tva) == '20' ? 'selected' : '' }}>TVA 20%</option>
                            <option value="0" {{ old('tva', $facture->tva) == '0' ? 'selected' : '' }}>Aucune TVA (0%)</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="afficher_cachet" class="form-label">Afficher le cachet sur la facture ? <span class="required">*</span></label>
                    <select name="afficher_cachet" class="form-select">
                        <option value="1" {{ old('afficher_cachet', $facture->afficher_cachet) == 1 ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('afficher_cachet', $facture->afficher_cachet) == 0 ? 'selected' : '' }}>Non</option>
                    </select>
                </div>

                <div class="total-section">
                    <div class="total-row">
                        <span class="total-label">Total HT (Hors Taxes)</span>
                        <span class="total-value" id="display_total_ht">{{ number_format(old('total_ht', $facture->total_ht ?? 0), 2, '.', '') }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Montant TVA</span>
                        <span class="total-value" id="display_tva">
                            @php
                                $tva_rate = old('tva', $facture->tva ?? '20');
                                $total_ht = old('total_ht', $facture->total_ht ?? 0);
                                $tva_amount = $total_ht * ($tva_rate / 100);
                                echo number_format($tva_amount, 2, '.', '');
                            @endphp
                        </span>
                    </div>
                    <div class="total-ttc-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="total-label" style="font-size: 20px;">
                                <i class="fas fa-coins me-2"></i> Total TTC (Toutes Taxes Comprises)
                            </span>
                            <span class="total-value" id="display_total_ttc">{{ number_format(old('total_ttc', $facture->total_ttc ?? 0), 2, '.', '') }}</span>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="total_ht" id="total_ht" value="{{ old('total_ht', $facture->total_ht ?? 0) }}">
                <input type="hidden" name="total_ttc" id="total_ttc" value="{{ old('total_ttc', $facture->total_ttc ?? 0) }}">
            </div>

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Notes et Informations Importantes</h3>
                </div>

                <div id="important-container">
                    @foreach($facture->importantInfoo as $important)
                    <div class="important-item">
                        <input type="text" name="important[]" class="form-control me-2" value="{{ old("important.$index", $important->info) }}" placeholder="Ajouter une information importante">
                        <button type="button" class="btn btn-remove btn-sm remove-important" style="padding: 10px 15px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            
                <button type="button" class="btn btn-important mt-3" id="add-important">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter une autre information
                </button>
            </div>
            
            <div class="action-buttons">
                {{-- <a href="{{ route('factures.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times-circle me-2"></i> Annuler
                </a> --}}
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-save me-2"></i> Mettre à jour la Facture
                </button>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les totaux au chargement
        calculatePrixTotal();
        
        // Déléguer l'écoute d'événement pour la suppression de produits
        document.getElementById('product-container').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-remove')) {
                removeProduct(e.target);
            }
        });

        // Ajouter un écouteur pour l'ajout d'information importante
        document.getElementById('add-important').addEventListener('click', function() {
            addImportantInfo();
        });

        // Déléguer l'écoute d'événement pour la suppression d'info importante
        document.getElementById('important-container').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-important')) {
                e.target.closest('.important-item').remove();
            }
        });

        // Mettre à jour le numéro des produits après le chargement/suppression
        updateProductNumbers();
    });

    function calculatePrixTotal() {
        let totalHT = 0;
        let rows = document.querySelectorAll('.product-row');
        
        rows.forEach(function(row) {
            let quantityInput = row.querySelector('.quantity');
            let unitPriceInput = row.querySelector('.prix_ht');
            let totalPriceInput = row.querySelector('.total-price');

            let quantity = parseFloat(quantityInput ? quantityInput.value : 0) || 0;
            let unitPrice = parseFloat(unitPriceInput ? unitPriceInput.value : 0) || 0;
            
            let prixTotal = quantity * unitPrice;
            if (totalPriceInput) {
                totalPriceInput.value = prixTotal.toFixed(2);
            }
            totalHT += prixTotal;
        });
        
        let totalHTInput = document.getElementById('total_ht');
        let displayTotalHT = document.getElementById('display_total_ht');
        if (totalHTInput) totalHTInput.value = totalHT.toFixed(2);
        if (displayTotalHT) displayTotalHT.textContent = totalHT.toFixed(2);
        
        calculateTTC();
    }

    function calculateTTC() {
        let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
        let tvaSelect = document.querySelector('select[name="tva"]');
        let tvaRate = parseFloat(tvaSelect ? tvaSelect.value : 0) || 0;
        
        let tvaAmount = totalHT * (tvaRate / 100);
        let totalTTC = totalHT + tvaAmount;
        
        let totalTTCInput = document.getElementById('total_ttc');
        let displayTotalTTC = document.getElementById('display_total_ttc');
        let displayTVA = document.getElementById('display_tva');
        
        if (totalTTCInput) totalTTCInput.value = totalTTC.toFixed(2);
        if (displayTotalTTC) displayTotalTTC.textContent = totalTTC.toFixed(2);
        if (displayTVA) displayTVA.textContent = tvaAmount.toFixed(2);
    }

    function updateProductNumbers() {
        let rows = document.querySelectorAll('#product-container .product-row');
        rows.forEach((row, index) => {
            let numberElement = row.querySelector('.product-number');
            if (numberElement) {
                numberElement.textContent = index + 1;
            }
        });
    }

    function addProduct() {
        let productContainer = document.getElementById('product-container');
        let newProductRow = document.createElement('div');
        newProductRow.classList.add('product-row');
        newProductRow.innerHTML = `
            <div class="product-number"></div> <div class="mb-3">
                <label class="form-label">Description / Libellé <span class="required">*</span></label>
                <textarea name="libele[]" class="form-control" rows="3" placeholder="Description détaillée du produit ou service..." required></textarea>
            </div>

            <div class="row align-items-end">
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label">Quantité <span class="required">*</span></label>
                    <input type="number" name="quantite[]" class="form-control quantity" min="0" step="0.01" oninput="calculatePrixTotal()" required>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label">Prix HT <span class="required">*</span></label>
                    <input type="number" name="prix_ht[]" class="form-control prix_ht" min="0" step="0.01" oninput="calculatePrixTotal()" required>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="form-label">Prix Total</label>
                    <input type="number" name="prix_total[]" class="form-control total-price" step="0.01" readonly>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-remove w-100">
                        <i class="fas fa-trash me-2"></i> Supprimer
                    </button>
                </div>
            </div>
        `;
        productContainer.appendChild(newProductRow);
        updateProductNumbers(); // Mettre à jour les numéros après l'ajout
    }

    function removeProduct(button) {
        let productRows = document.querySelectorAll('.product-row');
        if (productRows.length > 1) {
            button.closest('.product-row').remove();
            calculatePrixTotal();
            updateProductNumbers(); // Mettre à jour les numéros après la suppression
        } else {
            alert('Vous devez conserver au moins un produit.');
        }
    }
    
    function addImportantInfo() {
        let container = document.getElementById('important-container');
        let newItem = document.createElement('div');
        newItem.classList.add('important-item');
        newItem.innerHTML = `
            <input type="text" name="important[]" class="form-control me-2" placeholder="Ajouter une information importante">
            <button type="button" class="btn btn-remove btn-sm remove-important" style="padding: 10px 15px;">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newItem);
    }

    </script>
</x-app-layout>