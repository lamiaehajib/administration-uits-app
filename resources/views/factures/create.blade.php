<x-app-layout>
    <style>
        /* Styles identiques à votre code original */
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
        .type-card:hover {
            border-color: #D32F2F;
            box-shadow: 0 5px 20px rgba(211, 47, 47, 0.2);
        }
        .type-card.active {
            border-color: #D32F2F;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
        }
        .type-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .alert-info {
            background: #dbeafe;
            border: 2px solid #3b82f6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
            .type-selector { flex-direction: column; }
        }
    </style>

    <div class="container px-4" style="max-width: 1200px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-file-invoice"></i> 
                    @if(isset($devis))
                        Créer Facture depuis Devis #{{ $devis->devis_num }}
                    @else
                        Créer une Nouvelle Facture
                    @endif
                </h2>
                <p class="text-muted mb-0">
                    @if(isset($devis))
                        Les informations du devis seront pré-remplies
                    @else
                        Choisissez le type de facture et remplissez les informations
                    @endif
                </p>
            </div>
            <a href="{{ route('factures.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Retour
            </a>
        </div>

        @if(isset($devis))
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Devis source :</strong> {{ $devis->devis_num }} - {{ $devis->client }} - {{ $devis->titre }}
        </div>
        @endif

        <form action="{{ route('factures.store') }}" method="POST" id="facture-form">
            @csrf
            
            <!-- Sélection du Type -->
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-layer-group"></i></div>
                    <h3 class="section-title">Type de Facture</h3>
                </div>

                <div class="type-selector">
                    <div class="type-card active" onclick="selectType('service')">
                        <input type="radio" name="type" value="service" id="type_service" checked hidden>
                        <div class="type-icon">💼</div>
                        <h4>Services</h4>
                        <p class="text-muted mb-0">Prestations, consultations, main d'œuvre...</p>
                    </div>
                    <div class="type-card" onclick="selectType('produit')">
                        <input type="radio" name="type" value="produit" id="type_produit" hidden>
                        <div class="type-icon">📦</div>
                        <h4>Produits</h4>
                        <p class="text-muted mb-0">Vente de produits depuis le stock</p>
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
                        <input type="text" class="form-control" value="Généré automatiquement" readonly>
                    </div>
                    <div>
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" 
                               value="{{ isset($devis) ? $devis->date : now()->format('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="form-label">Afficher le cachet ?</label>
                        <select name="afficher_cachet" class="form-select">
                            <option value="1" selected>Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Titre <span class="text-danger">*</span></label>
                    <input type="text" name="titre" class="form-control" 
                           value="{{ isset($devis) ? $devis->titre : '' }}" 
                           placeholder="Titre de la facture" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label class="form-label">Client <span class="text-danger">*</span></label>
                        <input type="text" name="client" class="form-control" 
                               value="{{ isset($devis) ? $devis->client : '' }}" 
                               placeholder="Nom du client" required>
                    </div>
                    <div>
                        <label class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" 
                               value="{{ isset($devis) ? ($devis->ice ?? '') : '' }}" 
                               placeholder="Numéro ICE">
                    </div>
                    <div>
                        <label class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" 
                               value="{{ isset($devis) ? ($devis->ref ?? '') : '' }}" 
                               placeholder="Bon de commande">
                    </div>
                </div>
                
                <div class="mb-3"> 
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" rows="2" 
                              placeholder="Adresse complète">{{ isset($devis) ? ($devis->adresse ?? '') : '' }}</textarea>
                </div>
            </div>
            
            <!-- SECTION SERVICES -->
            <div class="form-card" id="services-section">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-briefcase"></i></div>
                    <h3 class="section-title">Services & Prestations</h3>
                </div>

                <div id="services-container">
                    @if(isset($devis) && $devis->items->count() > 0)
                        @foreach($devis->items as $index => $item)
                        <div class="product-row">
                            <div class="product-number">{{ $index + 1 }}</div>
                            <div class="mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="libele[]" class="form-control" rows="3" required>{{ $item->libele }}</textarea>
                            </div>
                            <div class="grid-4">
                                <div>
                                    <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <input type="number" name="quantite[]" class="form-control quantity" 
                                           value="{{ $item->quantite }}" min="0" step="0.01" 
                                           oninput="calculateTotals()" required>
                                </div>
                                <div>
                                    <label class="form-label">Prix Unitaire HT <span class="text-danger">*</span></label>
                                    <input type="number" name="prix_ht[]" class="form-control prix_ht" 
                                           value="{{ $item->prix_unitaire }}" min="0" step="0.01" 
                                           oninput="calculateTotals()" required>
                                </div>
                                <div>
                                    <label class="form-label">Prix Total HT</label>
                                    <input type="number" class="form-control total-price" 
                                           value="{{ $item->prix_total }}" readonly>
                                </div>
                                <div class="d-flex align-items-end">
                                    @if($index > 0 || $devis->items->count() > 1)
                                    <button type="button" class="btn btn-remove w-100" onclick="removeRow(this)">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="product-row">
                            <div class="product-number">1</div>
                            <div class="mb-3">
                                <label class="form-label">Description <span class="text-danger">*</span></label>
                                <textarea name="libele[]" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="grid-4">
                                <div>
                                    <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                    <input type="number" name="quantite[]" class="form-control quantity" value="1" min="0" step="0.01" oninput="calculateTotals()" required>
                                </div>
                                <div>
                                    <label class="form-label">Prix Unitaire HT <span class="text-danger">*</span></label>
                                    <input type="number" name="prix_ht[]" class="form-control prix_ht" value="0" min="0" step="0.01" oninput="calculateTotals()" required>
                                </div>
                                <div>
                                    <label class="form-label">Prix Total HT</label>
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

            <!-- SECTION PRODUITS (cachée par défaut) -->
            <div class="form-card" id="produits-section" style="display: none;">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-box"></i></div>
                    <h3 class="section-title">Produits</h3>
                </div>

                <div id="produits-container">
                    <div class="product-row">
                        <div class="product-number">1</div>
                        <div class="grid-2 mb-3">
                            <div>
                                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select category-select" onchange="loadProduits(this)" required>
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
                                <label class="form-label">Produit <span class="text-danger">*</span></label>
                                <select name="produit_id[]" class="form-select produit-select" onchange="selectProduit(this)" required disabled>
                                    <option value="">-- Choisir une catégorie d'abord --</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="alert alert-info produit-info" style="display: none;">
                            <strong>Prix d'achat:</strong> <span class="prix-achat-display">0</span> DH | 
                            <strong>Prix de vente suggéré:</strong> <span class="prix-vente-display">0</span> DH | 
                            <strong>Stock:</strong> <span class="stock-display">0</span>
                        </div>

                        <div class="grid-4">
                            <div>
                                <label class="form-label">Quantité <span class="text-danger">*</span></label>
                                <input type="number" name="quantite[]" class="form-control quantity-produit" value="1" min="1" step="1" oninput="calculateTotals()" required>
                            </div>
                            <div>
                                <label class="form-label">Prix Unitaire HT <span class="text-danger">*</span></label>
                                <input type="number" name="prix_ht[]" class="form-control prix-vente-input" value="0" min="0" step="0.01" oninput="calculateTotals()" required>
                            </div>
                            <div>
                                <label class="form-label">Marge Unitaire</label>
                                <input type="text" class="form-control marge-display" readonly>
                            </div>
                            <div class="d-flex align-items-end">
                                <button type="button" class="btn btn-remove w-100" onclick="removeRow(this)">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
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
                        <label class="form-label">Devise <span class="text-danger">*</span></label>
                        <select name="currency" class="form-select" required>
                            <option value="DH" {{ (isset($devis) && $devis->currency == 'DH') ? 'selected' : 'selected' }}>Dirham (DH)</option>
                            <option value="EUR" {{ (isset($devis) && $devis->currency == 'EUR') ? 'selected' : '' }}>Euro (€)</option>
                            <option value="CFA" {{ (isset($devis) && $devis->currency == 'CFA') ? 'selected' : '' }}>CFA</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">TVA (%) <span class="text-danger">*</span></label>
                        <select name="tva" class="form-select" onchange="calculateTotals()" required>
                            <option value="20" {{ (isset($devis) && $devis->tva_rate == 20) ? 'selected' : 'selected' }}>20%</option>
                            <option value="0" {{ (isset($devis) && $devis->tva_rate == 0) ? 'selected' : '' }}>0%</option>
                        </select>
                    </div>
                </div>

                <div style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border: 2px solid #10b981; border-radius: 15px; padding: 25px;">
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total HT:</strong>
                        <span id="display_total_ht">{{ isset($devis) ? number_format($devis->total_ht, 2) : '0.00' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Montant TVA:</strong>
                        <span id="display_tva">{{ isset($devis) ? number_format($devis->tva, 2) : '0.00' }}</span>
                    </div>
                    <div class="d-flex justify-content-between" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white; border-radius: 10px; padding: 15px;">
                        <strong style="font-size: 20px;">Total TTC:</strong>
                        <span id="display_total_ttc" style="font-size: 24px; font-weight: 700;">{{ isset($devis) ? number_format($devis->total_ttc, 2) : '0.00' }}</span>
                    </div>
                </div>
            </div>

            <!-- Informations Importantes -->
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations Importantes</h3>
                </div>

                <div id="important-container">
                    @if (isset($devis) && $devis->importantInfos && $devis->importantInfos->count() > 0)
                        @foreach ($devis->importantInfos as $info)
                            <div class="important-row mb-2">
                                <input type="text" name="important[]" class="form-control" value="{{ $info->info }}" placeholder="Ajouter une information importante">
                                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        @endforeach
                    @else
                        <div class="important-row mb-2">
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
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
                    <i class="fas fa-check-circle me-2"></i> Créer la Facture
                </button>
            </div>
        </form>
    </div>
    
    <script>
let productCount = {{ isset($devis) && $devis->items->count() > 0 ? $devis->items->count() : 1 }};
let produitsData = {};

// Sélection du type
function selectType(type) {
    document.querySelectorAll('.type-card').forEach(card => card.classList.remove('active'));
    event.currentTarget.classList.add('active');
    
    document.getElementById('type_' + type).checked = true;
    
    if (type === 'service') {
        document.getElementById('services-section').style.display = 'block';
        document.getElementById('produits-section').style.display = 'none';
        
        document.querySelectorAll('#services-section input, #services-section textarea, #services-section select').forEach(el => {
            el.disabled = false;
        });
        
        document.querySelectorAll('#produits-section input, #produits-section textarea, #produits-section select').forEach(el => {
            el.disabled = true;
        });
    } else {
        document.getElementById('services-section').style.display = 'none';
        document.getElementById('produits-section').style.display = 'block';
        
        document.querySelectorAll('#services-section input, #services-section textarea, #services-section select').forEach(el => {
            el.disabled = true;
        });
        
        document.querySelectorAll('#produits-container .product-row').forEach(row => {
            row.querySelectorAll('input, textarea').forEach(el => {
                el.disabled = false;
            });
            row.querySelector('.category-select').disabled = false;
        });
    }
    
    calculateTotals();
}

// Charger les produits d'une catégorie
async function loadProduits(selectElement) {
    const categoryId = selectElement.value;
    const row = selectElement.closest('.product-row');
    const produitSelect = row.querySelector('.produit-select');
    
    produitSelect.disabled = true;
    produitSelect.innerHTML = '<option value="">Chargement...</option>';
    
    if (!categoryId) {
        produitSelect.innerHTML = '<option value="">-- Choisir une catégorie d\'abord --</option>';
        return;
    }
    
    try {
        const response = await fetch(`/factures/produits-by-category/${categoryId}`);
        const produits = await response.json();
        
        produitSelect.innerHTML = '<option value="">-- Sélectionner un produit --</option>';
        
        produits.forEach(produit => {
            const option = document.createElement('option');
            option.value = produit.id;
            option.textContent = `${produit.nom} (Stock: ${produit.quantite_stock})`;
            option.dataset.produit = JSON.stringify(produit);
            produitSelect.appendChild(option);
        });
        
        produitSelect.disabled = false;
    } catch (error) {
        console.error('Erreur:', error);
        produitSelect.innerHTML = '<option value="">Erreur de chargement</option>';
    }
}

// Sélectionner un produit
function selectProduit(selectElement) {
    const option = selectElement.options[selectElement.selectedIndex];
    const row = selectElement.closest('.product-row');
    
    if (!option.dataset.produit) return;
    
    const produit = JSON.parse(option.dataset.produit);
    
    row.querySelector('.produit-info').style.display = 'block';
    row.querySelector('.prix-achat-display').textContent = produit.prix_achat.toFixed(2);
    row.querySelector('.prix-vente-display').textContent = produit.prix_vente.toFixed(2);
    row.querySelector('.stock-display').textContent = produit.quantite_stock;
    
    row.querySelector('.prix-vente-input').value = produit.prix_vente;
    row.querySelector('.quantity-produit').max = produit.quantite_stock;
    
    produitsData[row.dataset.rowId || Date.now()] = produit;
    
    calculateTotals();
}

// Calculer les totaux
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
            const prixVente = parseFloat(row.querySelector('.prix-vente-input').value) || 0;
            const prixAchat = parseFloat(row.querySelector('.prix-achat-display').textContent) || 0;
            
            const marge = prixVente - prixAchat;
            row.querySelector('.marge-display').value = marge.toFixed(2) + ' DH';
            
            totalHT += qty * prixVente;
        });
    }
    
    const tvaRate = parseFloat(document.querySelector('[name="tva"]').value) || 0;
    const tva = totalHT * (tvaRate / 100);
    const totalTTC = totalHT + tva;
    
    document.getElementById('display_total_ht').textContent = totalHT.toFixed(2);
    document.getElementById('display_tva').textContent = tva.toFixed(2);
    document.getElementById('display_total_ttc').textContent = totalTTC.toFixed(2);
}

// Ajouter service
function addService() {
    productCount++;
    const container = document.getElementById('services-container');
    const newRow = document.createElement('div');
    newRow.className = 'product-row';
    newRow.innerHTML = `
        <div class="product-number">${productCount}</div>
        <div class="mb-3">
            <label class="form-label">Description <span class="text-danger">*</span></label>
            <textarea name="libele[]" class="form-control" rows="3" required></textarea>
        </div>
        <div class="grid-4">
            <div>
                <label class="form-label">Quantité <span class="text-danger">*</span></label>
                <input type="number" name="quantite[]" class="form-control quantity" value="1" min="0" step="0.01" oninput="calculateTotals()" required>
            </div>
            <div>
                <label class="form-label">Prix Unitaire HT <span class="text-danger">*</span></label>
                <input type="number" name="prix_ht[]" class="form-control prix_ht" value="0" min="0" step="0.01" oninput="calculateTotals()" required>
            </div>
            <div>
                <label class="form-label">Prix Total HT</label>
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

// Ajouter produit
function addProduit() {
    productCount++;
    const container = document.getElementById('produits-container');
    const newRow = document.createElement('div');
    newRow.className = 'product-row';
    newRow.dataset.rowId = Date.now();
    newRow.innerHTML = `
        <div class="product-number">${productCount}</div>
        <div class="grid-2 mb-3">
            <div>
                <label class="form-label">Catégorie <span class="text-danger">*</span></label>
                <select class="form-select category-select" onchange="loadProduits(this)" required>
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
                <label class="form-label">Produit <span class="text-danger">*</span></label>
                <select name="produit_id[]" class="form-select produit-select" onchange="selectProduit(this)" required disabled>
                    <option value="">-- Choisir une catégorie d'abord --</option>
                </select>
            </div>
        </div>
        
        <div class="alert alert-info produit-info" style="display: none;">
            <strong>Prix d'achat:</strong> <span class="prix-achat-display">0</span> DH | 
            <strong>Prix de vente suggéré:</strong> <span class="prix-vente-display">0</span> DH | 
            <strong>Stock:</strong> <span class="stock-display">0</span>
        </div>

        <div class="grid-4">
            <div>
                <label class="form-label">Quantité <span class="text-danger">*</span></label>
                <input type="number" name="quantite[]" class="form-control quantity-produit" value="1" min="1" step="1" oninput="calculateTotals()" required>
            </div>
            <div>
                <label class="form-label">Prix Unitaire HT <span class="text-danger">*</span></label>
                <input type="number" name="prix_ht[]" class="form-control prix-vente-input" value="0" min="0" step="0.01" oninput="calculateTotals()" required>
            </div>
            <div>
                <label class="form-label">Marge Unitaire</label>
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

// Supprimer ligne
function removeRow(button) {
    const container = button.closest('.product-row').parentElement;
    if (container.children.length > 1) {
        button.closest('.product-row').remove();
        updateRowNumbers();
        calculateTotals();
    } else {
        alert('Vous devez avoir au moins une ligne.');
    }
}

// Mettre à jour numéros
function updateRowNumbers() {
    const type = document.querySelector('input[name="type"]:checked').value;
    const container = type === 'service' ? '#services-container' : '#produits-container';
    document.querySelectorAll(container + ' .product-row').forEach((row, index) => {
        row.querySelector('.product-number').textContent = index + 1;
    });
    productCount = document.querySelectorAll(container + ' .product-row').length;
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

// ✅ Validation avant soumission
document.getElementById('facture-form').addEventListener('submit', function(e) {
    const type = document.querySelector('input[name="type"]:checked').value;
    
    if (type === 'produit') {
        const rows = document.querySelectorAll('#produits-container .product-row');
        let hasError = false;
        
        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.quantity-produit').value) || 0;
            const stock = parseFloat(row.querySelector('.stock-display').textContent) || 0;
            
            if (qty > stock) {
                hasError = true;
                alert(`Stock insuffisant! Quantité demandée: ${qty}, Stock disponible: ${stock}`);
            }
        });
        
        if (hasError) {
            e.preventDefault();
            return false;
        }
    }
});

// ✅ INITIALISATION : Désactiver les champs Produits au chargement
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('#produits-section input, #produits-section textarea, #produits-section select').forEach(el => {
        el.disabled = true;
    });
    
    // Calculer totaux initiaux si devis existe
    @if(isset($devis))
    calculateTotals();
    @endif
});
</script>

</x-app-layout>