<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Styles Globaux (récupérés du devis) */
        .gradient-bg {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .create-container {
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
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Styles spécifiques aux lignes de produit et d'info (ajustés) */
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

        /* Styles des boutons (récupérés du devis) */
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
            background: linear-gradient(135deg, #10b981, #059669); /* Vert pour ajouter produit */
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
            background: linear-gradient(135deg, #f59e0b, #d97706); /* Jaune/Orange pour les infos */
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
        }

        .btn-cancel:hover {
            background: #4b5563;
            transform: translateY(-2px);
            color: white;
        }

        /* Styles Totaux (réutilisés et ajustés) */
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

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
        }

        /* Layout Grids */
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

        /* Responsive Design */
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
        }

        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 15px 20px;
            color: #1e40af;
            font-weight: 500;
            margin-bottom: 25px;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="create-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-truck"></i> Créer un Nouveau Bon de Livraison
                </h2>
                <p class="text-muted mb-0">Remplissez les informations pour générer votre bon de livraison</p>
            </div>
            <a href="{{ route('bon_livraisons.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>

        <form action="{{ route('bon_livraisons.store') }}" method="POST" id="bon-livraison-form">
            @csrf

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales & Client</h3>
                </div>

                <div class="alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    Le numéro du bon de livraison sera généré automatiquement après la création.
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label class="form-label">Numéro du Bon de Livraison</label>
                        <input type="text" name="bon_num" class="form-control" value="{{ old('bon_num', 'Généré automatiquement après création') }}" readonly>
                    </div>
                    <div>
                        <label class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Titre du bon de livraison (Ex: Livraison du matériel)" value="{{ old('titre') }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label">Client <span class="required">*</span></label>
                        <input type="text" name="client" class="form-control" placeholder="Nom du client" value="{{ old('client') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="tele" class="form-control" placeholder="Téléphone du client" value="{{ old('tele') }}">
                    </div>
                    <div>
                        <label class="form-label">Référence (Optionnel)</label>
                        <input type="text" name="ref" class="form-control" placeholder="Référence ou N° de commande client" value="{{ old('ref') }}">
                    </div>
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" placeholder="Identifiant Commun de l'Entreprise (ICE)" value="{{ old('ice') }}">
                    </div>
                    <div>
                        <label class="form-label">Adresse</label>
                        <textarea name="adresse" class="form-control" rows="3" placeholder="Adresse complète du client">{{ old('adresse') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="section-title">Produits Livrés</h3>
                </div>

                <div id="product-container">
                    <div class="product-row fade-in">
                        <div class="product-number">1</div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description / Libellé <span class="required">*</span></label>
                            <textarea name="libelle[]" class="form-control" rows="3" placeholder="Description détaillée du produit ou matériel livré..." required>{{ old('libelle.0') }}</textarea>
                        </div>

                        <div class="grid-4">
                            <div>
                                <label class="form-label">Quantité <span class="required">*</span></label>
                                <input type="number" name="quantite[]" class="form-control quantity" value="{{ old('quantite.0', 1) }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                            </div>
                            <div>
                                <label class="form-label">Prix HT (Unitaire) <span class="required">*</span></label>
                                <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="{{ old('prix_ht.0', 0) }}" oninput="calculatePrixTotal()" min="0" required>
                            </div>
                            <div>
                                <label class="form-label">Prix Total</label>
                                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                            </div>
                            <div class="d-flex align-items-end">
                                <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                                    <i class="fas fa-trash me-2"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-add mt-3" onclick="addProduct()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un Produit
                </button>
            </div>

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="section-title">Calculs & Totaux</h3>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label">Devise <span class="required">*</span></label>
                        <select name="currency" class="form-select" required>
                             <option value="DH">Dirham (DH)</option>
                             <option value="EUR">Euro (€)</option>
                         </select>
                    </div>
                    <div>
                        <label class="form-label">TVA <span class="required">*</span></label>
                        <select name="tva" class="form-select" onchange="calculateTTC()" required>
                            <option value="0" {{ old('tva', '0') == '0' ? 'selected' : '' }}>Aucune TVA (0%)</option>
                            <option value="20" {{ old('tva') == '20' ? 'selected' : '' }}>TVA 20%</option>
                        </select>
                    </div>
                    <div>
                         <label class="form-label">Total HT</label>
                         <input type="number" name="total_ht" class="form-control" id="total_ht" readonly value="{{ old('total_ht', 0) }}">
                    </div>
                </div>

                <div class="total-section">
                    <div class="total-row">
                        <span class="total-label">Total HT (Hors Taxes)</span>
                        <span class="total-value" id="display_total_ht">0.00</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Montant TVA</span>
                        <span class="total-value" id="display_tva">0.00</span>
                    </div>
                    <div class="total-ttc-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="total-label" style="font-size: 20px;">
                                <i class="fas fa-coins me-2"></i> Total TTC (Toutes Taxes Comprises)
                            </span>
                            <span class="total-value" id="display_total_ttc">0.00</span>
                        </div>
                    </div>
                </div>
                 <input type="hidden" name="total_ttc" id="total_ttc" value="{{ old('total_ttc', 0) }}">
            </div>

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations Importantes</h3>
                </div>

                <div id="important-container">
                    <div class="important-row fade-in">
                        <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                        <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                        <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <button type="button" class="btn btn-important mt-3" onclick="addImportant()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter une Information
                </button>
            </div>

            <div class="action-buttons">
                <a href="{{ route('bon_livraisons.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times-circle me-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-check-circle me-2"></i> Créer le Bon de Livraison
                </button>
            </div>
        </form>
    </div>

    <script>
        let productCount = 1;

        function updateProductNumbers() {
            let productRows = document.querySelectorAll('.product-row');
            productCount = productRows.length; // Met à jour le compteur global
            productRows.forEach((row, index) => {
                let productNumberElement = row.querySelector('.product-number');
                if (productNumberElement) {
                     productNumberElement.textContent = index + 1;
                }
            });
        }

        function calculatePrixTotal() {
            let totalHT = 0;
            let rows = document.querySelectorAll('.product-row');
        
            rows.forEach(function(row) {
                let quantityInput = row.querySelector('.quantity');
                let unitPriceInput = row.querySelector('.unit-price');
                let totalPriceInput = row.querySelector('.total-price');

                // Utiliser la classe .unit-price qui correspond à l'ancien prix_ht
                let quantity = parseFloat(quantityInput ? quantityInput.value : 0) || 0;
                let unitPrice = parseFloat(unitPriceInput ? unitPriceInput.value : 0) || 0;
                
                let total = quantity * unitPrice;
                
                if (totalPriceInput) {
                    totalPriceInput.value = total.toFixed(2);
                }
                totalHT += total;
            });
        
            document.getElementById('total_ht').value = totalHT.toFixed(2);
            document.getElementById('display_total_ht').textContent = totalHT.toFixed(2);
            calculateTTC();
        }
        
        function calculateTTC() {
            let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
            // Utilisation de querySelector car le select n'a pas d'ID
            let tvaRateElement = document.querySelector('[name="tva"]');
            let tvaRate = parseFloat(tvaRateElement ? tvaRateElement.value : 0) || 0;
            
            let tvaAmount = totalHT * (tvaRate / 100);
            let totalTTC = totalHT + tvaAmount;
            
            document.getElementById('total_ttc').value = totalTTC.toFixed(2);
            document.getElementById('display_total_ttc').textContent = totalTTC.toFixed(2);
            document.getElementById('display_tva').textContent = tvaAmount.toFixed(2);
        }
        
        function addProduct() {
            let productContainer = document.getElementById('product-container');
            let newRow = document.createElement('div');
            newRow.classList.add('product-row', 'fade-in');
            newRow.innerHTML = `
                <div class="product-number">${productCount + 1}</div>
                
                <div class="mb-3">
                    <label class="form-label">Description / Libellé <span class="required">*</span></label>
                    <textarea name="libelle[]" class="form-control" rows="3" placeholder="Description détaillée du produit ou matériel livré..." required></textarea>
                </div>

                <div class="grid-4">
                    <div>
                        <label class="form-label">Quantité <span class="required">*</span></label>
                        <input type="number" name="quantite[]" class="form-control quantity" value="1" min="0" step="0.01" oninput="calculatePrixTotal()" required>
                    </div>
                    <div>
                        <label class="form-label">Prix HT (Unitaire) <span class="required">*</span></label>
                        <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="0" min="0" oninput="calculatePrixTotal()" required>
                    </div>
                    <div>
                        <label class="form-label">Prix Total</label>
                        <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                    </div>
                    <div class="d-flex align-items-end">
                        <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                            <i class="fas fa-trash me-2"></i> Supprimer
                        </button>
                    </div>
                </div>
            `;
            productContainer.appendChild(newRow);
            updateProductNumbers();
        }
        
        function removeProduct(button) {
            let productRows = document.querySelectorAll('.product-row');
            if (productRows.length > 1) {
                button.closest('.product-row').remove();
                updateProductNumbers();
                calculatePrixTotal();
            } else {
                 // Utilisation de SweetAlert2 si disponible, sinon un simple alert
                 if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Attention!',
                        text: 'Vous devez conserver au moins un produit.',
                        confirmButtonColor: '#D32F2F'
                    });
                 } else {
                    alert('Vous devez conserver au moins un produit.');
                 }
            }
        }

        function addImportant() {
            const container = document.getElementById('important-container');
            const newRow = document.createElement('div');
            newRow.className = 'important-row fade-in';
            newRow.innerHTML = `
                <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newRow);
        }
        
        function removeImportant(button) {
            let importantRows = document.querySelectorAll('.important-row');
            if (importantRows.length > 1) {
                button.closest('.important-row').remove();
            } else {
                // Utilisation de SweetAlert2 si disponible, sinon un simple alert
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Information',
                        text: 'Vous devez conserver au moins une information importante.',
                        confirmButtonColor: '#D32F2F'
                    });
                } else {
                    alert('Vous devez conserver au moins une information importante.');
                }
            }
        }

        // Calcul initial au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            updateProductNumbers();
            calculatePrixTotal(); // Exécute le calcul initial
        });
    </script>
</x-app-layout>