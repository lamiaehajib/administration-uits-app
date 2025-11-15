<x-app-layout>
    <style>
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
            width: 100%; /* Ajouté pour garantir l'occupation complète de l'espace */
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
            text-decoration: none; /* Pour les liens */
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cancel:hover {
            background: #4b5563;
            transform: translateY(-2px);
            color: white;
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
        
        /* Réduire le grid en mobile */
        @media (max-width: 992px) {
            .grid-4, .grid-3, .grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .grid-4, .grid-3, .grid-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="create-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-file-invoice"></i> Créer une Nouvelle Facture
                </h2>
                <p class="text-muted mb-0">Remplissez les informations pour générer votre facture</p>
            </div>
            <a href="{{ route('factures.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>

        <form action="{{ route('factures.store') }}" method="POST" id="facture-form">
            @csrf
            
            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales</h3>
                </div>

                <div class="alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    Le numéro de facture sera **généré automatiquement** après la création.
                </div>

                @if (isset($devis))
                    <div class="alert-info" style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-color: #059669; color: #065f46;">
                        <i class="fas fa-link me-2"></i>
                        Création d'une facture à partir du **Devis #{{ $devis->id }}** (Informations pré-remplies).
                    </div>
                    <input type="hidden" name="devis_id" value="{{ $devis->id }}">
                @endif
        
                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label">Numéro du Facture</label>
                        <input type="text" name="facture_num" id="facture_num" class="form-control" value="{{ old('facture_num', 'Généré automatiquement') }}" readonly>
                    </div>
                    <div>
                        <label class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label class="form-label">Afficher le cachet ? <span class="required">*</span></label>
                        <select name="afficher_cachet" class="form-select">
                            <option value="1" {{ old('afficher_cachet', isset($devis) ? ($devis->afficher_cachet ?? 1) : 1) == 1 ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ old('afficher_cachet', isset($devis) ? ($devis->afficher_cachet ?? 0) : 0) == 0 ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Titre de la facture" value="{{ old('titre', isset($devis) ? $devis->titre : '') }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label">Client <span class="required">*</span></label>
                        <input type="text" name="client" class="form-control" placeholder="Nom du client" value="{{ old('client', isset($devis) ? $devis->client : '') }}" required>
                    </div>
                    <div>
                        <label class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" placeholder="Numéro ICE du client" value="{{ old('ice', isset($devis) ? ($devis->ice ?? '') : '') }}">
                    </div>
                    <div>
                        <label class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" placeholder="Référence ou Bon de Commande" value="{{ old('ref', isset($devis) ? ($devis->ref ?? '') : '') }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" placeholder="Adresse complète du client">{{ old('adresse', isset($devis) ? ($devis->adresse ?? '') : '') }}</textarea>
                </div>
            </div>
            
            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="section-title">Produits & Services</h3>
                </div>

                <div id="product-container">
                    @php $i = 1; @endphp
                    @if (isset($devis) && $devis->items)
                        @foreach ($devis->items as $index => $item)
                            <div class="product-row fade-in">
                                <div class="product-number">{{ $i++ }}</div>
                                <div class="mb-3">
                                    <label class="form-label">Description / Libellé <span class="required">*</span></label>
                                    <textarea name="libele[]" class="form-control" rows="3" required>{{ $item->libele }}</textarea>
                                </div>

                                <div class="grid-4">
                                    <div>
                                        <label class="form-label">Quantité <span class="required">*</span></label>
                                        <input type="number" name="quantite[]" class="form-control quantity" value="{{ $item->quantite }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Prix Unitaire HT <span class="required">*</span></label>
                                        <input type="number" step="0.01" name="prix_ht[]" class="form-control prix_ht" value="{{ $item->prix_unitaire }}" oninput="calculatePrixTotal()" min="0" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Prix Total HT</label>
                                        <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ $item->prix_total }}" readonly>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <button type="button" class="btn btn-remove w-100 remove-product">
                                            <i class="fas fa-trash me-2"></i> Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="product-row fade-in">
                            <div class="product-number">1</div>
                            <div class="mb-3">
                                <label class="form-label">Description / Libellé <span class="required">*</span></label>
                                <textarea name="libele[]" class="form-control" rows="3" placeholder="Description détaillée du produit ou service..." required></textarea>
                            </div>

                            <div class="grid-4">
                                <div>
                                    <label class="form-label">Quantité <span class="required">*</span></label>
                                    <input type="number" name="quantite[]" class="form-control quantity" oninput="calculatePrixTotal()" min="0" step="0.01" required value="{{ old('quantite.0', 1) }}">
                                </div>
                                <div>
                                    <label class="form-label">Prix Unitaire HT <span class="required">*</span></label>
                                    <input type="number" step="0.01" name="prix_ht[]" class="form-control prix_ht" oninput="calculatePrixTotal()" min="0" required value="{{ old('prix_ht.0', 0) }}">
                                </div>
                                <div>
                                    <label class="form-label">Prix Total HT</label>
                                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                                </div>
                                <div class="d-flex align-items-end">
                                    <button type="button" class="btn btn-remove w-100 remove-product">
                                        <i class="fas fa-trash me-2"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
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
                            <option value="DH" {{ old('currency', isset($devis) ? $devis->currency : 'DH') == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency', isset($devis) ? $devis->currency : 'DH') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="CFA" {{ old('currency', isset($devis) ? $devis->currency : 'DH') == 'CFA' ? 'selected' : '' }}>CFA</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">TVA (%) <span class="required">*</span></label>
                        <select name="tva" class="form-select" onchange="calculateTTC()" required>
                            @php
                                // Calculer la TVA du devis s'il existe
                                $default_tva_percent = 20;
                                if (isset($devis) && $devis->total_ht > 0) {
                                    $default_tva_percent = round(($devis->tva / $devis->total_ht) * 100);
                                } else {
                                    $default_tva_percent = old('tva', 20);
                                }
                            @endphp
                            <option value="20" {{ $default_tva_percent == 20 ? 'selected' : '' }}>TVA 20%</option>
                            <option value="0" {{ $default_tva_percent == 0 ? 'selected' : '' }}>Aucune TVA (0%)</option>
                        </select>
                    </div>
                </div>

                <div class="total-section">
                    <div class="total-row">
                        <span class="total-label">Total HT (Hors Taxes)</span>
                        <span class="total-value" id="display_total_ht">{{ old('total_ht', isset($devis) ? number_format($devis->total_ht, 2) : '0.00') }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Montant TVA</span>
                        <span class="total-value" id="display_tva">{{ old('tva_amount', isset($devis) ? number_format($devis->tva, 2) : '0.00') }}</span>
                    </div>
                    <div class="total-ttc-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="total-label" style="font-size: 20px;">
                                <i class="fas fa-coins me-2"></i> Total TTC (Toutes Taxes Comprises)
                            </span>
                            <span class="total-value" id="display_total_ttc">{{ old('total_ttc', isset($devis) ? number_format($devis->total_ttc, 2) : '0.00') }}</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="total_ht" id="total_ht" value="{{ old('total_ht', isset($devis) ? $devis->total_ht : 0) }}">
                <input type="hidden" name="tva_amount" id="tva_amount" value="{{ old('tva_amount', isset($devis) ? $devis->tva : 0) }}">
                <input type="hidden" name="total_ttc" id="total_ttc" value="{{ old('total_ttc', isset($devis) ? $devis->total_ttc : 0) }}">
            </div>


            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations Importantes</h3>
                </div>

                <div id="important-container">
                    @php $j = 0; @endphp
                    @if (isset($devis) && $devis->importantInfos)
                        @foreach ($devis->importantInfos as $info)
                            <div class="important-row fade-in">
                                <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ $info->info }}">
                                <button type="button" class="btn btn-remove remove-important" onclick="removeImportant(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @php $j++; @endphp
                        @endforeach
                    @endif
                    @if (!isset($devis) || (isset($devis) && count($devis->importantInfos) == 0))
                        <div class="important-row fade-in">
                            <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                            <button type="button" class="btn btn-remove remove-important" onclick="removeImportant(this)">
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
                <a href="{{ route('factures.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times-circle me-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-check-circle me-2"></i> Créer la Facture
                </button>
            </div>
        </form>
    </div>
    
    <script>
        let productCount = document.querySelectorAll('.product-row').length || 1;

        // Fonction pour mettre à jour le numéro des lignes de produits
        function updateProductNumbers() {
            const rows = document.querySelectorAll('#product-container .product-row');
            rows.forEach((row, index) => {
                let numberDiv = row.querySelector('.product-number');
                if (!numberDiv) {
                    numberDiv = document.createElement('div');
                    numberDiv.classList.add('product-number');
                    row.prepend(numberDiv);
                }
                numberDiv.textContent = index + 1;
            });
            productCount = rows.length;
        }

        function calculatePrixTotal() {
            let totalHT = 0;
            let rows = document.querySelectorAll('.product-row');
        
            rows.forEach(function(row) {
                let quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                let unitPrice = parseFloat(row.querySelector('.prix_ht').value) || 0;
                let totalPriceInput = row.querySelector('.total-price');
                
                let productTotal = quantity * unitPrice;
                totalPriceInput.value = productTotal.toFixed(2);
                totalHT += productTotal;
            });
        
            document.getElementById('total_ht').value = totalHT.toFixed(2);
            document.getElementById('display_total_ht').textContent = totalHT.toFixed(2);
            calculateTTC();
        }
        
        function calculateTTC() {
            let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
            let tva_percent = parseFloat(document.querySelector('[name="tva"]').value) || 0;
            
            let tvaAmount = totalHT * (tva_percent / 100);
            let totalTTC = totalHT + tvaAmount;
            
            document.getElementById('tva_amount').value = tvaAmount.toFixed(2);
            document.getElementById('display_tva').textContent = tvaAmount.toFixed(2);
            document.getElementById('total_ttc').value = totalTTC.toFixed(2);
            document.getElementById('display_total_ttc').textContent = totalTTC.toFixed(2);
        }
        
        function addProduct() {
            let productContainer = document.getElementById('product-container');
            productCount++;
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
                        <label class="form-label">Prix Unitaire HT <span class="required">*</span></label>
                        <input type="number" step="0.01" name="prix_ht[]" class="form-control prix_ht" value="0" oninput="calculatePrixTotal()" min="0" required>
                    </div>
                    <div>
                        <label class="form-label">Prix Total HT</label>
                        <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                    </div>
                    <div class="d-flex align-items-end">
                        <button type="button" class="btn btn-remove w-100 remove-product">
                            <i class="fas fa-trash me-2"></i> Supprimer
                        </button>
                    </div>
                </div>
            `;
            productContainer.appendChild(newRow);
            // Ajouter les écouteurs d'événements pour les nouveaux inputs
            newRow.querySelectorAll('.quantity, .prix_ht').forEach(input => {
                input.addEventListener('input', calculatePrixTotal);
            });
            updateProductNumbers();
            calculatePrixTotal(); // Recalculer après ajout
        }

        // Utilisation de la délégation d'événements pour supprimer les lignes
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product')) {
                const row = e.target.closest('.product-row');
                if (document.querySelectorAll('.product-row').length > 1) {
                    row.remove();
                    updateProductNumbers(); // Mise à jour des numéros après suppression
                    calculatePrixTotal(); // Recalculer après suppression
                } else {
                    alert('Vous devez avoir au moins un produit ou service.');
                }
            }
        });
        
        // Les fonctions d'ajout/suppression d'informations importantes
        function addImportant() {
            const container = document.getElementById('important-container');
            const newRow = document.createElement('div');
            newRow.className = 'important-row fade-in';
            newRow.innerHTML = `
                <i class="fas fa-star" style="color: #f59e0b; font-size: 18px;"></i>
                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-remove remove-important" onclick="removeImportant(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newRow);
        }

        function removeImportant(button) {
            const row = button.closest('.important-row');
            // Laisser au moins une ligne d'information importante
            if (document.querySelectorAll('.important-row').length > 1) {
                row.remove();
            } else {
                alert('Vous devez avoir au moins une information importante.');
            }
        }

        // Lancement des calculs au chargement de la page
        window.onload = function() {
            updateProductNumbers();
            calculatePrixTotal();
        };

        // Ajout des écouteurs d'événements pour les inputs existants (si chargés via old() ou devis)
        document.querySelectorAll('.quantity, .prix_ht').forEach(input => {
            input.addEventListener('input', calculatePrixTotal);
        });
    </script>
</x-app-layout>