<x-app-layout>
    <style>
        /* Couleurs et Thème */
        .gradient-bg {
            background: linear-gradient(135deg, #007bff, #0056b3); /* Bleu pour la formation */
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #007bff, #0056b3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Conteneur Général */
        .create-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Cartes de Formulaire */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        /* En-tête de Section */
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
            background: linear-gradient(135deg, #007bff, #0056b3); /* Bleu */
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

        /* Labels et Contrôles de Formulaire */
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
            border-color: #007bff;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
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

        /* Ligne de Produit/Formation */
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
            border-color: #007bff;
            box-shadow: 0 5px 20px rgba(0, 123, 255, 0.1);
        }
        
        .product-number {
            position: absolute;
            top: -15px;
            left: 20px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }

        /* Lignes d'Informations Importantes */
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

        /* Boutons */
        .btn-gradient {
            background: linear-gradient(135deg, #007bff, #0056b3);
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
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);
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

        /* Section Totaux */
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
            background: linear-gradient(135deg, #007bff, #0056b3); /* Bleu pour TTC */
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

        /* Dispositions en Grille */
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
            grid-template-columns: 1fr 1fr 1fr 150px; /* Adapting for formation fields and price */
            gap: 15px;
            align-items: end;
        }
        
        .grid-product-line {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 150px; /* Libellé | Type | Champ Variable | Prix Unitaire | Prix Total | Supprimer */
            gap: 15px;
            align-items: start;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .grid-4, .grid-3, .grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }
            .grid-product-line {
                grid-template-columns: 1fr; /* Stack on smaller screens */
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
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e5e7eb;
        }

        /* Messages d'alerte */
        .alert-danger {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            border: 2px solid #ef4444;
            color: #991b1b;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
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

        /* Animations */
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
                    <i class="fas fa-chalkboard-teacher me-2"></i> Créer un Nouveau Devis Formation
                </h2>
                <p class="text-muted mb-0">Remplissez les informations pour générer votre devis de formation</p>
            </div>
            <a href="#" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger fade-in">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Veuillez corriger les erreurs suivantes :
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('devisf.store') }}" method="POST" id="devis-form">
            @csrf

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales du Devis</h3>
                </div>

                <div class="alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    Le numéro de devis sera généré automatiquement après la création.
                </div>

                <div class="grid-2 mb-4">
                    <div>
                        <label class="form-label" for="devis_num">Numéro du Devis</label>
                        <input type="text" name="devis_num" id="devis_num" class="form-control" value="Généré automatiquement" readonly>
                    </div>
                    <div>
                        <label class="form-label" for="date">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="titre">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" id="titre" class="form-control" placeholder="Ex: Formation en Sécurité Informatique" value="{{ old('titre') }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label" for="client">Client <span class="required">*</span></label>
                        <input type="text" name="client" id="client" class="form-control" placeholder="Nom de la société/client" value="{{ old('client') }}" required>
                    </div>
                    <div>
                        <label class="form-label" for="contact">Contact</label>
                        <input type="text" name="contact" id="contact" class="form-control" placeholder="Email ou téléphone" value="{{ old('contact') }}">
                    </div>
                    <div>
                        <label class="form-label" for="ref">Référence</label>
                        <input type="text" name="ref" id="ref" class="form-control" placeholder="Référence interne/client" value="{{ old('ref') }}">
                    </div>
                </div>
            </div>

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="section-title">Détails de la Formation</h3>
                </div>

                <div id="product-container">
                    <div class="product-row fade-in">
                        <div class="product-number">1</div>
                        <div class="grid-product-line">
                            <div class="form-group">
                                <label class="form-label" for="libele">Libellé / Description <span class="required">*</span></label>
                                <textarea name="libele[]" class="form-control" rows="3" placeholder="Description de la formation/module..." required>{{ old('libele.0') }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="type">Choisir le Type <span class="required">*</span></label>
                                <select name="type[]" class="form-select type-select" onchange="toggleFields(this)">
                                    <option value="formation" {{ old('type.0') == 'formation' ? 'selected' : '' }}>Durée (Jours/Heures)</option>
                                    <option value="nombre" {{ old('type.0') == 'nombre' ? 'selected' : '' }}>Nb. Collaborateurs</option>
                                    <option value="nombre_de_jours" {{ old('type.0') == 'nombre_de_jours' ? 'selected' : '' }}>Nb. de Jours</option>
                                </select>
                            </div>

                            <div class="form-group formation-field">
                                <label class="form-label" for="formation">Durée (Jours/Heures)</label>
                                <input type="text" name="formation[]" class="form-control quantity-variable" value="{{ old('formation.0') }}" placeholder="Ex: 3 jours, 24h">
                            </div>
                            <div class="form-group nombre-field" style="display: none;">
                                <label class="form-label" for="nombre">Nombre de collaborateurs</label>
                                <input type="number" name="nombre[]" class="form-control quantity-variable" value="{{ old('nombre.0') }}" oninput="calculatePrixTotal()" min="0" placeholder="0">
                            </div>
                            <div class="form-group nombre_de_jours-field" style="display: none;">
                                <label class="form-label" for="nombre_de_jours">Nombre de jours</label>
                                <input type="number" name="nombre_de_jours[]" class="form-control quantity-variable" value="{{ old('nombre_de_jours.0') }}" oninput="calculatePrixTotal()" min="0" placeholder="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="prix_unitaire">Prix Unitaire <span class="required">*</span></label>
                                <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" value="{{ old('prix_unitaire.0', 0) }}" oninput="calculatePrixTotal()" min="0" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="prix_total">Prix Total</label>
                                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                            </div>
                            
                            <div class="form-group d-flex align-items-end">
                                <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                                    <i class="fas fa-trash me-2"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        
                <button type="button" class="btn btn-add mt-3" onclick="addProduct()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un Module de Formation
                </button>
            </div>
            
            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="section-title">Calculs & Totaux</h3>
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label class="form-label" for="currency">Devise <span class="required">*</span></label>
                        <select name="currency" id="currency" class="form-select" required>
                            <option value="DH" {{ old('currency', 'DH') == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="tva">TVA <span class="required">*</span></label>
                        <select name="tva" id="tva" class="form-select" onchange="calculateTTC()" required>
                            <option value="20" {{ old('tva', '20') == '20' ? 'selected' : '' }}>TVA 20%</option>
                            <option value="0" {{ old('tva') == '0' ? 'selected' : '' }}>Aucune TVA (0%)</option>
                        </select>
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
                    @php $important_data = old('important', ['']); @endphp
                    @foreach($important_data as $index => $value)
                    <div class="important-row fade-in">
                        <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                        <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante (Ex: Validité du devis: 30 jours)" value="{{ $value }}">
                        <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-important mt-3" onclick="addImportant()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter une Information
                </button>
            </div>
            
            <div class="action-buttons">
                <a href="#" class="btn btn-cancel">
                    <i class="fas fa-times-circle me-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-check-circle me-2"></i> Créer le Devis
                </button>
            </div>
        </form>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser l'affichage des champs dynamiques et les calculs
        document.querySelectorAll('.type-select').forEach(toggleFields);
        calculatePrixTotal();
    });

    /**
     * Calcule le prix total de chaque ligne et met à jour le Total HT et TTC.
     */
    function calculatePrixTotal() {
        let totalHT = 0;
        let rows = document.querySelectorAll('.product-row');

        rows.forEach(function(row) {
            let unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            let totalPriceInput = row.querySelector('.total-price');
            let typeSelect = row.querySelector('.type-select');
            let type = typeSelect ? typeSelect.value : 'formation'; // Fallback
            let quantity = 1;

            if (type === 'nombre') {
                quantity = parseFloat(row.querySelector('[name="nombre[]"]').value) || 0;
            } else if (type === 'nombre_de_jours') {
                quantity = parseFloat(row.querySelector('[name="nombre_de_jours[]"]').value) || 0;
            }
            
            // Pour 'formation' (Durée), on considère généralement la quantité comme 1, 
            // le prix unitaire étant le prix de la formation.
            // La quantité est utilisée uniquement pour 'nombre' ou 'nombre_de_jours'.

            let rowTotal = unitPrice * quantity;
            totalPriceInput.value = rowTotal.toFixed(2);
            totalHT += rowTotal;
        });

        document.getElementById('total_ht').value = totalHT.toFixed(2);
        document.getElementById('display_total_ht').textContent = totalHT.toFixed(2);
        calculateTTC();
    }

    /**
     * Calcule le Total TTC et la TVA.
     */
    function calculateTTC() {
        let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
        let tvaRate = parseFloat(document.querySelector('[name="tva"]').value) || 0;
        
        let montantTVA = totalHT * (tvaRate / 100);
        let totalTTC = totalHT + montantTVA;

        document.getElementById('total_ttc').value = totalTTC.toFixed(2);
        
        // Affichage
        document.getElementById('display_tva').textContent = montantTVA.toFixed(2);
        document.getElementById('display_total_ttc').textContent = totalTTC.toFixed(2);
    }

    /**
     * Ajoute une nouvelle ligne de module de formation.
     */
    function addProduct() {
        let productContainer = document.getElementById('product-container');
        let productRows = document.querySelectorAll('.product-row').length;
        let newIndex = productRows; // Index pour les inputs
        
        let newRow = document.createElement('div');
        newRow.classList.add('product-row', 'fade-in');
        newRow.innerHTML = `
            <div class="product-number">${newIndex + 1}</div>
            <div class="grid-product-line">
                <div class="form-group">
                    <label class="form-label" for="libele">Libellé / Description <span class="required">*</span></label>
                    <textarea name="libele[]" class="form-control" rows="3" placeholder="Description de la formation/module..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="type">Choisir le Type <span class="required">*</span></label>
                    <select name="type[]" class="form-select type-select" onchange="toggleFields(this)">
                        <option value="formation" selected>Durée (Jours/Heures)</option>
                        <option value="nombre">Nb. Collaborateurs</option>
                        <option value="nombre_de_jours">Nb. de Jours</option>
                    </select>
                </div>

                <div class="form-group formation-field">
                    <label class="form-label" for="formation">Durée (Jours/Heures)</label>
                    <input type="text" name="formation[]" class="form-control quantity-variable" placeholder="Ex: 3 jours, 24h">
                </div>
                <div class="form-group nombre-field" style="display: none;">
                    <label class="form-label" for="nombre">Nombre de collaborateurs</label>
                    <input type="number" name="nombre[]" class="form-control quantity-variable" oninput="calculatePrixTotal()" min="0" placeholder="0">
                </div>
                <div class="form-group nombre_de_jours-field" style="display: none;">
                    <label class="form-label" for="nombre_de_jours">Nombre de jours</label>
                    <input type="number" name="nombre_de_jours[]" class="form-control quantity-variable" oninput="calculatePrixTotal()" min="0" placeholder="0">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="prix_unitaire">Prix Unitaire <span class="required">*</span></label>
                    <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" min="0" oninput="calculatePrixTotal()" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="prix_total">Prix Total</label>
                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                </div>
                
                <div class="form-group d-flex align-items-end">
                    <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                        <i class="fas fa-trash me-2"></i> Supprimer
                    </button>
                </div>
            </div>
        `;
        productContainer.appendChild(newRow);
        toggleFields(newRow.querySelector('.type-select')); // Initialisation
        updateProductNumbers();
    }

    /**
     * Supprime une ligne de produit/formation.
     */
    function removeProduct(button) {
        let productRows = document.querySelectorAll('.product-row');
        if (productRows.length > 1) {
            button.closest('.product-row').remove();
            calculatePrixTotal();
            updateProductNumbers();
        } else {
            alert('Vous devez conserver au moins un module de formation.');
        }
    }
    
    /**
     * Met à jour les numéros affichés sur chaque ligne de produit.
     */
    function updateProductNumbers() {
        document.querySelectorAll('.product-row').forEach((row, index) => {
            row.querySelector('.product-number').textContent = index + 1;
        });
    }

    /**
     * Gère l'affichage des champs de quantité dynamiques.
     */
    function toggleFields(select) {
        const type = select.value;
        const row = select.closest('.product-row');
        
        // Tous les champs variables
        const variableFields = [
            row.querySelector('.formation-field'),
            row.querySelector('.nombre-field'),
            row.querySelector('.nombre_de_jours-field')
        ].filter(el => el != null); // Filtrer les éléments qui pourraient ne pas exister (même si l'HTML les contient)

        variableFields.forEach(field => {
            field.style.display = 'none';
            // Réinitialiser les valeurs des champs cachés
            let input = field.querySelector('input, textarea');
            if (input && input.name.includes('[]')) {
                 // Ne pas effacer la valeur, mais le retirer du calcul si le type change
                 // Pour la formation, le champ est textuel, pas un nombre pour le calcul
                if (field.classList.contains('nombre-field') || field.classList.contains('nombre_de_jours-field')) {
                    // C'est un champ de quantité utilisé pour le calcul
                    input.value = ''; 
                }
            }
        });

        let fieldToShow;
        if (type === 'formation') {
            fieldToShow = row.querySelector('.formation-field');
        } else if (type === 'nombre') {
            fieldToShow = row.querySelector('.nombre-field');
        } else if (type === 'nombre_de_jours') {
            fieldToShow = row.querySelector('.nombre_de_jours-field');
        }
        
        if (fieldToShow) {
            fieldToShow.style.display = 'block';
            // Assurez-vous que la quantité est réinitialisée à 1 pour le calcul si le type est 'formation'
            if (type === 'formation') {
                // Le calcul se base sur quantity=1 pour ce type, pas besoin de réinitialiser le champ textuel
            }
        }
        
        // Déclencher un nouveau calcul après le changement de type
        calculatePrixTotal();
    }
    
    /**
     * Ajoute une nouvelle information importante.
     */
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

    </script>
</x-app-layout>