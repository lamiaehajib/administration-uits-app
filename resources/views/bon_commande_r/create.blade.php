<x-app-layout>
    <style>
        /* Styles de base importés du devis de projet pour la cohérence */
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

        /* Spécifique aux lignes d'articles */
        .product-row {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            position: relative;
            transition: all 0.3s ease;
            display: grid; /* Utilisation de grid pour l'alignement des champs */
            grid-template-columns: 1fr 1fr 1fr 120px; /* Libellé, Quantité, Prix HT, Prix Total */
            gap: 15px;
            align-items: end;
        }

        .product-row:hover {
            border-color: #D32F2F;
            box-shadow: 0 5px 20px rgba(211, 47, 47, 0.1);
        }

        .product-row .form-group:first-child { /* Le libellé prend plus d'espace */
            grid-column: 1 / 5;
        }

        .product-row .form-group:nth-child(2) {
            grid-column: 1 / 2;
        }

        .product-row .form-group:nth-child(3) {
            grid-column: 2 / 3;
        }

        .product-row .form-group:nth-child(4) {
            grid-column: 3 / 4;
        }

        .product-row .form-group:nth-child(5) {
            grid-column: 4 / 5;
            display: flex;
            flex-direction: column;
            align-items: center;
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
        
        /* Styles pour les informations importantes */
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
            background: linear-gradient(135deg, #10b981, #059669);
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
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;
            height: 45px; /* Harmonisation de la hauteur */
        }

        .btn-remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
            color: white;
        }

        .btn-important {
            background: linear-gradient(135deg, #f59e0b, #d97706);
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

        /* Totaux section */
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

        /* Grids */
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

        /* Alerts */
        .alert-info {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 15px 20px;
            color: #1e40af;
            font-weight: 500;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
        }

        /* Animation */
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

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .grid-3, .grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }

            .product-row {
                grid-template-columns: 1fr 1fr; /* 2 colonnes par ligne sur tablettes */
            }

            .product-row .form-group:first-child {
                grid-column: 1 / 3; /* Libellé prend toute la largeur */
            }
        }

        @media (max-width: 576px) {
            .grid-3, .grid-2 {
                grid-template-columns: 1fr;
            }

            .product-row {
                grid-template-columns: 1fr; /* 1 colonne par ligne sur mobiles */
            }

            .product-row .form-group:first-child {
                grid-column: 1 / 2;
            }

            .product-row .form-group {
                grid-column: 1 / 2 !important; /* Tous les éléments prennent toute la largeur */
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons button,
            .action-buttons a {
                width: 100%;
            }
        }
    </style>

    <div class="create-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-file-contract"></i> Créer un Nouveau Bon de Commande
                </h2>
                <p class="text-muted mb-0">Remplissez les informations pour générer votre bon de commande</p>
            </div>
            {{-- Remplacez par votre route d'index des bons de commande --}}
            <a href="#" class="btn btn-cancel"> 
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>

        <form action="{{ route('bon_commande_r.store') }}" method="POST" id="bon-commande-form">
            @csrf

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3 class="section-title">Informations Générales</h3>
                </div>

                <div class="alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    Le numéro du bon de commande sera généré automatiquement après la création.
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label class="form-label" for="bon_num">Numéro du bon de commande</label>
                        <input type="text" name="bon_num" id="bon_num" class="form-control" value="{{ old('bon_num', 'Généré automatiquement après création') }}" readonly>
                        @error('bon_num')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="date">Date <span class="required">*</span></label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}">
                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="titre">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" id="titre" class="form-control" placeholder="Ex: Achat de fournitures de bureau" value="{{ old('titre') }}">
                    @error('titre')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-truck-moving"></i>
                    </div>
                    <h3 class="section-title">Informations Prestataire</h3>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label" for="prestataire">Prestataire <span class="required">*</span></label>
                        <input type="text" name="prestataire" id="prestataire" class="form-control" placeholder="Nom du fournisseur" value="{{ old('prestataire') }}">
                        @error('prestataire')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="tele">Téléphone</label>
                        <input type="text" name="tele" id="tele" class="form-control" placeholder="Téléphone du prestataire" value="{{ old('tele') }}">
                        @error('tele')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="ice">ICE</label>
                        <input type="text" name="ice" id="ice" class="form-control" placeholder="Numéro ICE (Maroc)" value="{{ old('ice') }}">
                        @error('ice')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="adresse">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="form-control" placeholder="Adresse complète du prestataire" value="{{ old('adresse') }}">
                    @error('adresse')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label" for="ref">Référence</label>
                    <input type="text" name="ref" id="ref" class="form-control" placeholder="Référence ou N° Proforma" value="{{ old('ref') }}">
                    @error('ref')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="section-title">Articles Commandés</h3>
                </div>

                <div id="product-container">
                    {{-- Ligne d'article initiale --}}
                    <div class="product-row fade-in">
                        <div class="product-number">1</div>
                        
                        <div class="form-group mb-0">
                            <label class="form-label" for="libelle">Libellé / Description <span class="required">*</span></label>
                            <textarea name="libelle[]" class="form-control" rows="3" placeholder="Description détaillée de l'article ou service" required>{{ old('libelle.0') }}</textarea>
                            @error('libelle.0')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-0">
                            <label class="form-label" for="quantite">Quantité <span class="required">*</span></label>
                            <input type="number" name="quantite[]" class="form-control quantity" value="{{ old('quantite.0', 1) }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                            @error('quantite.0')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-0">
                            <label class="form-label" for="prix_ht">Prix HT Unitaire <span class="required">*</span></label>
                            <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="{{ old('prix_ht.0', 0) }}" oninput="calculatePrixTotal()" min="0" required>
                            @error('prix_ht.0')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-0">
                            <label class="form-label" for="prix_total">Prix Total</label>
                            <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ old('prix_total.0', 0) }}" readonly>
                            <button type="button" class="btn btn-remove btn-sm mt-2" onclick="removeProduct(this)">
                                <i class="fas fa-trash me-1"></i> Supprimer
                            </button>
                        </div>
                    </div>
                </div>
    
                <button type="button" class="btn btn-add mt-3" onclick="addProduct()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un Article
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
                        <label class="form-label" for="currency">Devise <span class="required">*</span></label>
                        <select name="currency" id="currency" class="form-select" required>
                            <option value="DH" {{ old('currency', 'DH') == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                        </select>
                        @error('currency')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="tva">TVA <span class="required">*</span></label>
                        <select name="tva" id="tva" class="form-select" onchange="calculateTTC()" required>
                            <option value="20" {{ old('tva', '20') == '20' ? 'selected' : '' }}>TVA 20%</option>
                            <option value="0" {{ old('tva') == '0' ? 'selected' : '' }}>Aucune TVA (0%)</option>
                        </select>
                        @error('tva')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
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

                <input type="hidden" name="total_ht" id="total_ht" value="{{ old('total_ht', '0.00') }}">
                <input type="hidden" name="total_ttc" id="total_ttc" value="{{ old('total_ttc', '0.00') }}">
            </div>

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations Importantes</h3>
                </div>

                <div id="important-container">
                    {{-- Ligne d'information importante initiale --}}
                    <div class="important-row fade-in">
                        <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                        <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                        <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                            <i class="fas fa-times"></i>
                        </button>
                        @error('important.0')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="button" class="btn btn-important mt-3" onclick="addImportant()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter une Information
                </button>
            </div>

            <div class="action-buttons">
                <a href="#" class="btn btn-cancel"> {{-- Remplacez par votre route d'index des bons de commande --}}
                    <i class="fas fa-times-circle me-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-check-circle me-2"></i> Créer le Bon de Commande
                </button>
            </div>
        </form>
    </div>

    <script>
        let productCount = 1;

        // Fonction pour calculer le Prix Total de chaque ligne et le Total HT
        function calculatePrixTotal() {
            let totalHT = 0;
            let rows = document.querySelectorAll('.product-row');
            productCount = 0; // Réinitialiser le compteur pour la numérotation

            rows.forEach(function(row) {
                productCount++;
                // Mise à jour du numéro de ligne
                row.querySelector('.product-number').textContent = productCount;
                
                let quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                let unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
                let totalPriceInput = row.querySelector('.total-price');
                
                let total = quantity * unitPrice;
                totalPriceInput.value = total.toFixed(2);
                totalHT += total;
            });

            // Mise à jour des champs et affichage du Total HT
            document.getElementById('total_ht').value = totalHT.toFixed(2);
            document.getElementById('display_total_ht').textContent = totalHT.toFixed(2);
            
            calculateTTC(); // Appel du calcul TTC après la mise à jour du HT
        }

        // Fonction pour calculer le Total TTC
        function calculateTTC() {
            let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
            let tvaRate = parseFloat(document.querySelector('[name="tva"]').value) || 0;
            let tvaAmount = totalHT * (tvaRate / 100);
            let totalTTC = totalHT + tvaAmount;
            
            // Mise à jour des champs et affichage des totaux TTC et TVA
            document.getElementById('total_ttc').value = totalTTC.toFixed(2);
            document.getElementById('display_total_ttc').textContent = totalTTC.toFixed(2);
            document.getElementById('display_tva').textContent = tvaAmount.toFixed(2);
        }

        // Fonction pour ajouter un nouvel article
        function addProduct() {
            productCount++;
            let productContainer = document.getElementById('product-container');
            let newRow = document.createElement('div');
            newRow.classList.add('product-row', 'fade-in');
            newRow.innerHTML = `
                <div class="product-number">${productCount}</div>
                
                <div class="form-group mb-0">
                    <label class="form-label" for="libelle">Libellé / Description <span class="required">*</span></label>
                    <textarea name="libelle[]" class="form-control" rows="3" placeholder="Description détaillée de l'article ou service" required></textarea>
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label" for="quantite">Quantité <span class="required">*</span></label>
                    <input type="number" name="quantite[]" class="form-control quantity" value="1" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label" for="prix_ht">Prix HT Unitaire <span class="required">*</span></label>
                    <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="0" oninput="calculatePrixTotal()" min="0" required>
                </div>
                
                <div class="form-group mb-0">
                    <label class="form-label" for="prix_total">Prix Total</label>
                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                    <button type="button" class="btn btn-remove btn-sm mt-2" onclick="removeProduct(this)">
                        <i class="fas fa-trash me-1"></i> Supprimer
                    </button>
                </div>
            `;
            productContainer.appendChild(newRow);
            calculatePrixTotal();
        }

        // Fonction pour supprimer un article
        function removeProduct(button) {
            button.closest('.product-row').remove();
            calculatePrixTotal(); // Recalculer et re-numéroter
        }

        // Fonction pour ajouter une information importante
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

        // Fonction pour supprimer une information importante
        function removeImportant(button) {
            button.closest('.important-row').remove();
        }

        // Exécuter les calculs au chargement de la page pour les valeurs old()
        document.addEventListener('DOMContentLoaded', (event) => {
            calculatePrixTotal();
        });
    </script>
    </x-app-layout>