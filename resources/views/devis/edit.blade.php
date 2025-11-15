<x-app-layout>
    <style>
        /* Les styles du create.blade appliqués pour l'édition */
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
            color: #6b7280; /* Style pour les champs en lecture seule */
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
        /* Fin Styles spécifiques aux lignes de produits */

        /* Styles des informations importantes */
        .important-row {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .important-row input {
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

        .btn-cancel {
            background: #6b7280;
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

        .btn-cancel:hover {
            background: #4b5563;
            transform: translateY(-2px);
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
                padding: 12px 20px; /* Taille plus grande pour mobile */
            }
        }
    </style>

    <div class="edit-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-edit"></i> Modifier le Devis N° {{ $devis->devis_num }}
                </h2>
                <p class="text-muted mb-0">Mettez à jour les informations de votre devis</p>
            </div>
            {{-- <a href="{{ route('devis.show', $devis->id) }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Retour au Devis
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
    
        <form action="{{ route('devis.update', $devis->id) }}" method="POST" id="devis-form">
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
                        <label for="devis_num" class="form-label">Numéro du Devis</label>
                        <input type="text" name="devis_num" class="form-control" value="{{ old('devis_num', $devis->devis_num) }}" readonly>
                    </div>
                    <div>
                        <label for="date" class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $devis->date) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre du Devis <span class="required">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Ex: Développement site web e-commerce" value="{{ old('titre', $devis->titre) }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label for="client" class="form-label">Client <span class="required">*</span></label>
                        <input type="text" name="client" class="form-control" placeholder="Nom du client" value="{{ old('client', $devis->client) }}" required>
                    </div>
                    <div>
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" name="contact" class="form-control" placeholder="Email ou téléphone" value="{{ old('contact', $devis->contact) }}">
                    </div>
                    <div>
                        <label for="ref" class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" placeholder="Référence interne" value="{{ old('ref', $devis->ref) }}">
                    </div>
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
                    @foreach($devis->items as $index => $item)
                        <div class="product-row">
                            <div class="product-number">{{ $index + 1 }}</div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description / Libellé <span class="required">*</span></label>
                                <textarea name="libele[]" class="form-control" rows="3" placeholder="Description détaillée du produit ou service..." required>{{ old("libele.$index", $item->libele) }}</textarea>
                            </div>

                            <div class="grid-4">
                                <div>
                                    <label class="form-label">Quantité <span class="required">*</span></label>
                                    <input type="number" name="quantite[]" class="form-control quantity" value="{{ old("quantite.$index", $item->quantite) }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                                </div>
                                <div>
                                    <label class="form-label">Prix Unitaire <span class="required">*</span></label>
                                    <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" value="{{ old("prix_unitaire.$index", $item->prix_unitaire) }}" oninput="calculatePrixTotal()" min="0" required>
                                </div>
                                <div>
                                    <label class="form-label">Prix Total</label>
                                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                                </div>
                                <div class="d-flex align-items-end">
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
                            <option value="DH" {{ old('currency', $devis->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency', $devis->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="CFA" {{ old('currency', $devis->currency) == 'CFA' ? 'selected' : '' }}>CFA</option>
                        </select>
                    </div>
                    <div>
                        <label for="tva" class="form-label">TVA <span class="required">*</span></label>
                        <select name="tva" class="form-select" onchange="calculateTTC()" required>
                            <option value="20" {{ old('tva', $devis->tva_rate ?? '20') == '20' ? 'selected' : '' }}>TVA 20%</option>
                            <option value="0" {{ old('tva', $devis->tva_rate ?? '20') == '0' ? 'selected' : '' }}>Aucune TVA (0%)</option>
                        </select>
                    </div>
                </div>

                <div class="total-section">
                    <div class="total-row">
                        <span class="total-label">Total HT (Hors Taxes)</span>
                        <span class="total-value" id="display_total_ht">{{ number_format(old('total_ht', $devis->total_ht ?? 0), 2, '.', '') }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Montant TVA</span>
                        <span class="total-value" id="display_tva">
                            @php
                                $tva_rate = old('tva', $devis->tva_rate ?? '20');
                                $total_ht = old('total_ht', $devis->total_ht ?? 0);
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
                            <span class="total-value" id="display_total_ttc">{{ number_format(old('total_ttc', $devis->total_ttc ?? 0), 2, '.', '') }}</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="total_ht" id="total_ht" value="{{ old('total_ht', $devis->total_ht) }}">
                <input type="hidden" name="total_ttc" id="total_ttc" value="{{ old('total_ttc', $devis->total_ttc) }}">
            </div>

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations Importantes</h3>
                </div>

                <div id="important-container">
                    @if($devis->importantInfos->count())
                        @foreach($devis->importantInfos as $index => $important)
                            <div class="important-row">
                                <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old("important.$index", $important->info) }}">
                                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="important-row">
                            <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                            <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <button type="button" class="btn btn-important mt-3" onclick="addImportant()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter une Information
                </button>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('devis.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times-circle me-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-check-circle me-2"></i> Mettre à jour le Devis
                </button>
            </div>
        </form>
    </div>
    
    <script>
    // Initialiser le compteur de produits basé sur les éléments existants
    let productCount = document.querySelectorAll('.product-row').length;

    /**
     * Recalcule le Prix Total pour chaque ligne de produit et le Total HT global.
     */
    function calculatePrixTotal() {
        let totalHT = 0;
        let rows = document.querySelectorAll('.product-row');
    
        rows.forEach(function(row) {
            let quantityInput = row.querySelector('.quantity');
            let unitPriceInput = row.querySelector('.unit-price');
            let totalPriceInput = row.querySelector('.total-price');

            // S'assurer que les valeurs sont des nombres
            let quantity = parseFloat(quantityInput.value) || 0;
            let unitPrice = parseFloat(unitPriceInput.value) || 0;
            
            let prixTotal = quantity * unitPrice;

            totalPriceInput.value = prixTotal.toFixed(2);
            totalHT += prixTotal;
        });
    
        // Mettre à jour le champ caché et l'affichage du Total HT
        document.getElementById('total_ht').value = totalHT.toFixed(2);
        document.getElementById('display_total_ht').textContent = totalHT.toFixed(2);
        calculateTTC(); // Recalculer le TTC après la mise à jour du HT
    }
    
    /**
     * Calcule le Total TTC et la Montant TVA.
     */
    function calculateTTC() {
        let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
        let tvaRate = parseFloat(document.querySelector('[name="tva"]').value) || 0;
        
        let tvaAmount = totalHT * (tvaRate / 100);
        let totalTTC = totalHT + tvaAmount;
    
        // Mettre à jour les champs cachés et l'affichage des totaux
        document.getElementById('total_ttc').value = totalTTC.toFixed(2);
        document.getElementById('display_total_ttc').textContent = totalTTC.toFixed(2);
        document.getElementById('display_tva').textContent = tvaAmount.toFixed(2);
    }
    
    /**
     * Ajoute une nouvelle ligne de produit.
     */
    function addProduct() {
        productCount++;
        let productContainer = document.getElementById('product-container');
        let newRow = document.createElement('div');
        newRow.classList.add('product-row', 'fade-in');
        newRow.innerHTML = `
            <div class="product-number">${productCount}</div>
            
            <div class="mb-3">
                <label class="form-label">Description / Libellé <span class="required">*</span></label>
                <textarea name="libele[]" class="form-control" rows="3" placeholder="Description détaillée du produit ou service..." required></textarea>
            </div>

            <div class="grid-4">
                <div>
                    <label class="form-label">Quantité <span class="required">*</span></label>
                    <input type="number" name="quantite[]" class="form-control quantity" value="1" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                </div>
                <div>
                    <label class="form-label">Prix Unitaire <span class="required">*</span></label>
                    <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" value="0.00" oninput="calculatePrixTotal()" min="0" required>
                </div>
                <div>
                    <label class="form-label">Prix Total</label>
                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="0.00" readonly>
                </div>
                <div class="d-flex align-items-end">
                    <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                        <i class="fas fa-trash me-2"></i> Supprimer
                    </button>
                </div>
            </div>
        `;
        productContainer.appendChild(newRow);
        // Assurez-vous que l'index dans le DOM est mis à jour pour les produits existants
        updateProductNumbers();
        calculatePrixTotal();
    }
    
    /**
     * Supprime une ligne de produit.
     */
    function removeProduct(button) {
        let productRows = document.querySelectorAll('.product-row');
        if (productRows.length > 1) {
            button.closest('.product-row').remove();
            updateProductNumbers();
            calculatePrixTotal();
        } else {
            alert('Vous devez conserver au moins un produit.');
        }
    }

    /**
     * Met à jour les numéros affichés des produits après ajout/suppression.
     */
    function updateProductNumbers() {
        let rows = document.querySelectorAll('#product-container .product-row');
        productCount = rows.length;
        rows.forEach((row, index) => {
            let numberElement = row.querySelector('.product-number');
            if (numberElement) {
                numberElement.textContent = index + 1;
            }
        });
    }

    /**
     * Ajoute une nouvelle information importante.
     */
    function addImportant() {
        let container = document.getElementById('important-container');
        let newRow = document.createElement('div');
        newRow.classList.add('important-row', 'fade-in');
        newRow.innerHTML = `
            <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
            <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(newRow);
    }
    
    /**
     * Supprime une information importante.
     */
    function removeImportant(button) {
        let importantRows = document.querySelectorAll('.important-row');
        if (importantRows.length > 1) {
            button.closest('.important-row').remove();
        } else {
            alert('Vous devez conserver au moins une information importante.');
        }
    }

    // Calcul initial au chargement de la page pour les totaux affichés
    document.addEventListener('DOMContentLoaded', () => {
        calculatePrixTotal();
    });
    </script>
</x-app-layout>