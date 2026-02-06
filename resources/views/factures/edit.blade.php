<x-app-layout>
    <style>
        .gradient-bg { background: linear-gradient(135deg, #C2185B, #D32F2F); }
        .gradient-text {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
        }
        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
            outline: none;
        }
        .product-row {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            position: relative;
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
        }
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-add {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
        }
        .btn-remove {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
        }
        .btn-important {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
        }
        .important-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .grid-2 { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; }
        .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
        
        .type-selector {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        .type-card {
            flex: 1;
            padding: 30px;
            border: 3px solid #e5e7eb;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        .type-card.active {
            border-color: #D32F2F;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
        }
        .type-icon { font-size: 48px; margin-bottom: 15px; }
        
        @media (max-width: 768px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .type-selector { flex-direction: column; }
        }
    </style>

    <div class="container px-4" style="max-width: 1200px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-edit"></i> Modifier Facture #{{ $facture->facture_num }}
                </h2>
                <p class="text-muted mb-0">Mettez à jour les informations de la facture</p>
            </div>
            <a href="{{ route('factures.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>

        <form action="{{ route('factures.update', $facture->id) }}" method="POST" id="facture-form">
            @csrf
            @method('PUT')
            
            <!-- Sélection du Type -->
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-layer-group"></i></div>
                    <h3 class="section-title">Type de Facture</h3>
                </div>

                <div class="type-selector">
                    <div class="type-card {{ $facture->type === 'service' ? 'active' : '' }}" onclick="selectType('service')">
                        <input type="radio" name="type" value="service" id="type_service" {{ $facture->type === 'service' ? 'checked' : '' }} hidden>
                        <div class="type-icon">💼</div>
                        <h4>Services</h4>
                        <p class="text-muted mb-0">Prestations, consultations...</p>
                    </div>
                    <div class="type-card {{ $facture->type === 'produit' ? 'active' : '' }}" onclick="selectType('produit')">
                        <input type="radio" name="type" value="produit" id="type_produit" {{ $facture->type === 'produit' ? 'checked' : '' }} hidden>
                        <div class="type-icon">📦</div>
                        <h4>Produits</h4>
                        <p class="text-muted mb-0">Vente de produits</p>
                    </div>
                </div>
            </div>

            <!-- Informations Générales -->
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-info-circle"></i></div>
                    <h3 class="section-title">Informations Générales</h3>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label">Numéro Facture</label>
                        <input type="text" name="facture_num" class="form-control" value="{{ $facture->facture_num }}" required>
                    </div>
                    <div>
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ $facture->date }}" required>
                    </div>
                    <div>
                        <label class="form-label">Afficher le cachet ?</label>
                        <select name="afficher_cachet" class="form-select">
                            <option value="1" {{ $facture->afficher_cachet ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ !$facture->afficher_cachet ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" name="titre" class="form-control" value="{{ $facture->titre }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <input type="text" name="client" class="form-control" value="{{ $facture->client }}" required>
                    </div>
                    <div>
                        <label class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" value="{{ $facture->ice }}">
                    </div>
                    <div>
                        <label class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" value="{{ $facture->ref }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" rows="2">{{ $facture->adresse }}</textarea>
                </div>
            </div>
            
            <!-- SECTION SERVICES -->
            <div class="form-card" id="services-section" style="display: {{ $facture->type === 'service' ? 'block' : 'none' }}">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-briefcase"></i></div>
                    <h3 class="section-title">Services & Prestations</h3>
                </div>

                <div id="services-container">
                    @if($facture->type === 'service')
                        @foreach($facture->items as $index => $item)
                        <div class="product-row">
                            <div class="product-number">{{ $index + 1 }}</div>
                            <div class="mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="libele[]" class="form-control" rows="3">{{ $item->libele }}</textarea>
                            </div>
                            <div class="grid-4">
                                <div>
                                    <label class="form-label">Quantité</label>
                                    <input type="number" name="quantite[]" class="form-control quantity" value="{{ $item->quantite }}" min="0" step="0.01" oninput="calculateTotals()">
                                </div>
                                <div>
                                    <label class="form-label">Prix HT</label>
                                    <input type="number" name="prix_ht[]" class="form-control prix_ht" value="{{ $item->prix_ht }}" min="0" step="0.01" oninput="calculateTotals()">
                                </div>
                                <div>
                                    <label class="form-label">Total HT</label>
                                    <input type="number" class="form-control total-price" value="{{ $item->prix_total }}" readonly>
                                </div>
                                <div class="d-flex align-items-end">
                                    <button type="button" class="btn btn-remove w-100" onclick="removeRow(this)">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="product-row">
                            <div class="product-number">1</div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="libele[]" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="grid-4">
                                <div>
                                    <label class="form-label">Quantité</label>
                                    <input type="number" name="quantite[]" class="form-control quantity" value="1" min="0" step="0.01" oninput="calculateTotals()">
                                </div>
                                <div>
                                    <label class="form-label">Prix HT</label>
                                    <input type="number" name="prix_ht[]" class="form-control prix_ht" value="0" min="0" step="0.01" oninput="calculateTotals()">
                                </div>
                                <div>
                                    <label class="form-label">Total HT</label>
                                    <input type="number" class="form-control total-price" readonly>
                                </div>
                                <div class="d-flex align-items-end">
                                    <button type="button" class="btn btn-remove w-100" onclick="removeRow(this)">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <button type="button" class="btn btn-add mt-3" onclick="addService()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un Service
                </button>
            </div>

            <!-- SECTION PRODUITS -->
            <div class="form-card" id="produits-section" style="display: {{ $facture->type === 'produit' ? 'block' : 'none' }}">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-box"></i></div>
                    <h3 class="section-title">Produits</h3>
                </div>

                <div id="produits-container">
                    @if($facture->type === 'produit')
                        @foreach($facture->items as $index => $item)
                        <div class="product-row" data-produit-id="{{ $item->produit_id }}">
                            <div class="product-number">{{ $index + 1 }}</div>
                            <div class="grid-2 mb-3">
                                <div>
                                    <label class="form-label">Catégorie</label>
                                    <select class="form-select category-select" onchange="loadProduits(this)">
                                        <option value="">-- Sélectionner --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->nom }}</option>
                                            @foreach($category->children as $child)
                                                <option value="{{ $child->id }}">-- {{ $child->nom }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Produit</label>
                                    <select name="produit_id[]" class="form-select produit-select" onchange="selectProduit(this)">
                                        <option value="{{ $item->produit_id }}" selected>{{ $item->produit->nom ?? $item->libele }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="grid-4">
                                <div>
                                    <label class="form-label">Quantité</label>
                                    <input type="number" name="quantite[]" class="form-control quantity-produit" value="{{ $item->quantite }}" min="1" oninput="calculateTotals()">
                                </div>
                                <div>
                                    <label class="form-label">Prix HT</label>
                                    <input type="number" name="prix_ht[]" class="form-control prix-vente-input" value="{{ $item->prix_ht }}" min="0" step="0.01" oninput="calculateTotals()">
                                </div>
                                <div>
                                    <label class="form-label">Marge</label>
                                    <input type="text" class="form-control marge-display" value="{{ $item->marge_unitaire }} DH" readonly>
                                </div>
                                <div class="d-flex align-items-end">
                                    <button type="button" class="btn btn-remove w-100" onclick="removeRow(this)">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" class="btn btn-add mt-3" onclick="addProduit()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un Produit
                </button>
            </div>

            <!-- Calculs & Totaux -->
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-calculator"></i></div>
                    <h3 class="section-title">Calculs & Totaux</h3>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label">Devise</label>
                        <select name="currency" class="form-select">
                            <option value="DH" {{ $facture->currency === 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ $facture->currency === 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="CFA" {{ $facture->currency === 'CFA' ? 'selected' : '' }}>CFA</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">TVA (%)</label>
                        <select name="tva" class="form-select" onchange="calculateTotals()">
                            <option value="20" {{ $facture->tva == ($facture->total_ht * 0.2) ? 'selected' : '' }}>20%</option>
                            <option value="0" {{ $facture->tva == 0 ? 'selected' : '' }}>0%</option>
                        </select>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 2px solid #10b981; border-radius: 15px; padding: 25px;">
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total HT:</strong>
                        <span id="display_total_ht">{{ number_format($facture->total_ht, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Montant TVA:</strong>
                        <span id="display_tva">{{ number_format($facture->tva, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white; border-radius: 10px; padding: 15px;">
                        <strong style="font-size: 20px;">Total TTC:</strong>
                        <span id="display_total_ttc" style="font-size: 24px; font-weight: 700;">{{ number_format($facture->total_ttc, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- ✅ SECTION INFORMATIONS IMPORTANTES -->
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations Importantes</h3>
                </div>

                <div id="important-container">
                    @if($facture->importantInfoo && $facture->importantInfoo->count() > 0)
                        @foreach($facture->importantInfoo as $info)
                            <div class="important-row mb-2">
                                <input type="text" name="important[]" class="form-control" value="{{ $info->info }}" placeholder="Ajouter une information importante">
                                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="important-row mb-2">
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                            <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-important mt-3" onclick="addImportant()">
                    <i class="fas fa-plus me-2"></i> Ajouter une information
                </button>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('factures.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-check-circle me-2"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
    
    <script>
    let productCount = {{ $facture->items->count() }};

    function selectType(type) {
        document.querySelectorAll('.type-card').forEach(card => card.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('type_' + type).checked = true;
        
        if (type === 'service') {
            document.getElementById('services-section').style.display = 'block';
            document.getElementById('produits-section').style.display = 'none';
            document.querySelectorAll('#produits-section input, #produits-section select, #produits-section textarea').forEach(el => el.disabled = true);
            document.querySelectorAll('#services-section input, #services-section select, #services-section textarea').forEach(el => el.disabled = false);
        } else {
            document.getElementById('services-section').style.display = 'none';
            document.getElementById('produits-section').style.display = 'block';
            document.querySelectorAll('#services-section input, #services-section select, #services-section textarea').forEach(el => el.disabled = true);
            document.querySelectorAll('#produits-section input, #produits-section select, #produits-section textarea').forEach(el => el.disabled = false);
        }
        calculateTotals();
    }

    async function loadProduits(selectElement) {
        const categoryId = selectElement.value;
        const row = selectElement.closest('.product-row');
        const produitSelect = row.querySelector('.produit-select');
        
        if (!categoryId) return;
        
        const response = await fetch(`/factures/produits-by-category/${categoryId}`);
        const produits = await response.json();
        
        produitSelect.innerHTML = '<option value="">-- Sélectionner --</option>';
        produits.forEach(p => {
            const option = document.createElement('option');
            option.value = p.id;
            option.textContent = `${p.nom} (Stock: ${p.quantite_stock})`;
            option.dataset.produit = JSON.stringify(p);
            produitSelect.appendChild(option);
        });
        produitSelect.disabled = false;
    }

    function selectProduit(selectElement) {
        const option = selectElement.options[selectElement.selectedIndex];
        if (!option.dataset.produit) return;
        
        const produit = JSON.parse(option.dataset.produit);
        const row = selectElement.closest('.product-row');
        
        row.querySelector('.prix-vente-input').value = produit.prix_vente;
        row.querySelector('.quantity-produit').max = produit.quantite_stock;
        calculateTotals();
    }

    function calculateTotals() {
        let totalHT = 0;
        const type = document.querySelector('input[name="type"]:checked').value;
        
        if (type === 'service') {
            document.querySelectorAll('#services-container .product-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.quantity').value) || 0;
                const price = parseFloat(row.querySelector('.prix_ht').value) || 0;
                const total = qty * price;
                row.querySelector('.total-price').value = total.toFixed(2);
                totalHT += total;
            });
        } else {
            document.querySelectorAll('#produits-container .product-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.quantity-produit').value) || 0;
                const price = parseFloat(row.querySelector('.prix-vente-input').value) || 0;
                totalHT += qty * price;
            });
        }
        
        const tvaRate = parseFloat(document.querySelector('[name="tva"]').value) || 0;
        const tva = totalHT * (tvaRate / 100);
        const totalTTC = totalHT + tva;
        
        document.getElementById('display_total_ht').textContent = totalHT.toFixed(2);
        document.getElementById('display_tva').textContent = tva.toFixed(2);
        document.getElementById('display_total_ttc').textContent = totalTTC.toFixed(2);
    }

    function addService() {
        productCount++;
        const container = document.getElementById('services-container');
        const newRow = document.createElement('div');
        newRow.className = 'product-row';
        newRow.innerHTML = `
            <div class="product-number">${productCount}</div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="libele[]" class="form-control" rows="3"></textarea>
            </div>
            <div class="grid-4">
                <div>
                    <label class="form-label">Quantité</label>
                    <input type="number" name="quantite[]" class="form-control quantity" value="1" min="0" step="0.01" oninput="calculateTotals()">
                </div>
                <div>
                    <label class="form-label">Prix HT</label>
                    <input type="number" name="prix_ht[]" class="form-control prix_ht" value="0" min="0" step="0.01" oninput="calculateTotals()">
                </div>
                <div>
                    <label class="form-label">Total HT</label>
                    <input type="number" class="form-control total-price" readonly>
                </div>
                <div class="d-flex align-items-end">
                    <button type="button" class="btn btn-remove w-100" onclick="removeRow(this)">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
    }

    function addProduit() {
        productCount++;
        const container = document.getElementById('produits-container');
        const newRow = document.createElement('div');
        newRow.className = 'product-row';
        newRow.innerHTML = `
            <div class="product-number">${productCount}</div>
            <div class="grid-2 mb-3">
                <div>
                    <label class="form-label">Catégorie</label>
                    <select class="form-select category-select" onchange="loadProduits(this)">
                        <option value="">-- Sélectionner --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nom }}</option>
                            @foreach($category->children as $child)
                                <option value="{{ $child->id }}">-- {{ $child->nom }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Produit</label>
                    <select name="produit_id[]" class="form-select produit-select" onchange="selectProduit(this)" disabled>
                        <option value="">-- Choisir catégorie d'abord --</option>
                    </select>
                </div>
            </div>
            <div class="grid-4">
                <div>
                    <label class="form-label">Quantité</label>
                    <input type="number" name="quantite[]" class="form-control quantity-produit" value="1" min="1" oninput="calculateTotals()">
                </div>
                <div>
                    <label class="form-label">Prix HT</label>
                    <input type="number" name="prix_ht[]" class="form-control prix-vente-input" value="0" min="0" step="0.01" oninput="calculateTotals()">
                </div>
                <div>
                    <label class="form-label">Marge</label>
                    <input type="text" class="form-control marge-display" readonly>
                </div>
                <div class="d-flex align-items-end">
                    <button type="button" class="btn btn-remove w-100" onclick="removeRow(this)">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newRow);
    }

    function removeRow(button) {
        const container = button.closest('.product-row').parentElement;
        if (container.children.length > 1) {
            button.closest('.product-row').remove();
            updateRowNumbers();
            calculateTotals();
        }
    }

    function updateRowNumbers() {
        const type = document.querySelector('input[name="type"]:checked').value;
        const container = type === 'service' ? '#services-container' : '#produits-container';
        document.querySelectorAll(container + ' .product-row').forEach((row, index) => {
            row.querySelector('.product-number').textContent = index + 1;
        });
    }

    // ✅ FONCTION: Ajouter Information Importante
    function addImportant() {
        const container = document.getElementById('important-container');
        const newRow = document.createElement('div');
        newRow.className = 'important-row mb-2';
        newRow.innerHTML = `
            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
            <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(newRow);
    }

    // ✅ FONCTION: Supprimer Information Importante
    function removeImportant(button) {
        const container = document.getElementById('important-container');
        if (container.children.length > 1) {
            button.closest('.important-row').remove();
        } else {
            alert('Vous devez avoir au moins une ligne d\'information importante.');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const type = document.querySelector('input[name="type"]:checked').value;
        if (type === 'service') {
            document.querySelectorAll('#produits-section input, #produits-section select, #produits-section textarea').forEach(el => el.disabled = true);
        } else {
            document.querySelectorAll('#services-section input, #services-section select, #services-section textarea').forEach(el => el.disabled = true);
        }
        calculateTotals();
    });
    </script>
</x-app-layout>