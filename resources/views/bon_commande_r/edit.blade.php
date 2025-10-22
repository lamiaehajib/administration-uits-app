<x-app-layout>
    <style>
        /* Styles de base importés du Devis de Projet */
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
        
        .important-row .btn-remove {
            margin-top: 0 !important;
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
            display: inline-flex; /* Assurez-vous que l'icône est alignée */
            align-items: center;
            gap: 8px;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
            align-items: end; /* Alignement pour les éléments de produit */
        }
        
        .grid-4 .form-group {
            margin-bottom: 0; /* Supprimer la marge par défaut des form-group dans la grille */
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
        
        /* Les messages d'erreur (text-danger dans le HTML initial) */
        .text-danger {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
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
                width: 100%;
            }
            
            .important-row {
                flex-direction: column;
                align-items: stretch;
            }
            .important-row .btn-remove {
                width: 100%;
            }
        }
    </style>

    <div class="edit-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-file-invoice"></i> Modifier le Bon de Commande
                </h2>
                <p class="text-muted mb-0">Mettez à jour les informations du bon de commande N° <span class="fw-bold">{{ $bonCommandeR->bon_num }}</span></p>
            </div>
            {{-- Optionnel: Bouton de retour --}}
            {{-- <a href="{{ route('bon_commande_r.show', $bonCommandeR->id) }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Retour au Bon
            </a> --}}
        </div>

        @if ($errors->any())
            <div class="alert-danger">
                <p class="fw-bold">🚨 Attention: Erreurs de validation !</p>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('bon_commande_r.update', $bonCommandeR) }}" method="POST" id="bon-commande-form">
            @csrf
            @method('PUT')

            {{-- Section Informations Générales --}}
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales</h3>
                </div>

                <div class="grid-2 mb-4">
                    <div class="form-group">
                        <label for="bon_num" class="form-label">Numéro du bon de commande</label>
                        <input type="text" name="bon_num" id="bon_num" class="form-control" value="{{ old('bon_num', $bonCommandeR->bon_num) }}" readonly>
                        @error('bon_num')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="date" class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', $bonCommandeR->date ? $bonCommandeR->date->format('Y-m-d') : '') }}" required>
                        @error('date')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="titre" class="form-label">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre', $bonCommandeR->titre) }}" required>
                    @error('titre')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid-2 mb-4">
                    <div class="form-group">
                        <label for="prestataire" class="form-label">Prestataire <span class="required">*</span></label>
                        <input type="text" name="prestataire" id="prestataire" class="form-control" value="{{ old('prestataire', $bonCommandeR->prestataire) }}" required>
                        @error('prestataire')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tele" class="form-label">Téléphone</label>
                        <input type="text" name="tele" id="tele" class="form-control" value="{{ old('tele', $bonCommandeR->tele) }}">
                        @error('tele')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="grid-3">
                    <div class="form-group">
                        <label for="ice" class="form-label">ICE</label>
                        <input type="text" name="ice" id="ice" class="form-control" value="{{ old('ice', $bonCommandeR->ice) }}">
                        @error('ice')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="ref" class="form-label">Référence</label>
                        <input type="text" name="ref" id="ref" class="form-control" value="{{ old('ref', $bonCommandeR->ref) }}">
                        @error('ref')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="currency" class="form-label">Devise <span class="required">*</span></label>
                        <select name="currency" id="currency" class="form-select" required>
                            <option value="DH" {{ old('currency', $bonCommandeR->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency', $bonCommandeR->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            <option value="USD" {{ old('currency', $bonCommandeR->currency) == 'USD' ? 'selected' : '' }}>Dollar ($)</option>
                        </select>
                        @error('currency')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group mt-4">
                    <label for="adresse" class="form-label">Adresse</label>
                    <input type="text" name="adresse" id="adresse" class="form-control" value="{{ old('adresse', $bonCommandeR->adresse) }}">
                    @error('adresse')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Section Produits & Services --}}
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="section-title">Articles Commandés</h3>
                </div>

                <div id="product-container">
                    @forelse ($bonCommandeR->items as $index => $item)
                        <div class="product-row">
                            <div class="product-number">{{ $index + 1 }}</div>

                            <div class="form-group mb-3">
                                <label class="form-label">Libellé <span class="required">*</span></label>
                                <textarea name="libelle[]" class="form-control" rows="3" placeholder="Description de l'article commandé..." required>{{ old("libelle.$index", $item->libelle) }}</textarea>
                                @error("libelle.$index")
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid-4">
                                <div class="form-group">
                                    <label class="form-label">Quantité <span class="required">*</span></label>
                                    <input type="number" name="quantite[]" class="form-control quantity" value="{{ old("quantite.$index", $item->quantite) }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                                    @error("quantite.$index")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Prix HT Unitaire <span class="required">*</span></label>
                                    <input type="number" name="prix_ht[]" class="form-control unit-price" value="{{ old("prix_ht.$index", $item->prix_ht) }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                                    @error("prix_ht.$index")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Prix Total HT</label>
                                    <input type="number" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                                    @error("prix_total.$index")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                                        <i class="fas fa-trash me-2"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        {{-- Ligne vide par défaut --}}
                        <div class="product-row">
                            <div class="product-number">1</div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label">Libellé <span class="required">*</span></label>
                                <textarea name="libelle[]" class="form-control" rows="3" placeholder="Description de l'article commandé..." required>{{ old('libelle.0') }}</textarea>
                                @error('libelle.0')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid-4">
                                <div class="form-group">
                                    <label class="form-label">Quantité <span class="required">*</span></label>
                                    <input type="number" name="quantite[]" class="form-control quantity" value="{{ old('quantite.0') }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                                    @error('quantite.0')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Prix HT Unitaire <span class="required">*</span></label>
                                    <input type="number" name="prix_ht[]" class="form-control unit-price" value="{{ old('prix_ht.0') }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                                    @error('prix_ht.0')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Prix Total HT</label>
                                    <input type="number" name="prix_total[]" class="form-control total-price" readonly value="{{ old('prix_total.0') }}">
                                    @error('prix_total.0')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                                        <i class="fas fa-trash me-2"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>

                <button type="button" class="btn btn-add mt-3" onclick="addProduct()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un article
                </button>
            </div>
            
            {{-- Section Totaux --}}
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="section-title">Calculs & Totaux</h3>
                </div>
                
                <div class="grid-2">
                    <div>
                        <div class="form-group mb-3">
                            <label for="tva" class="form-label">TVA (%)</label>
                            <select name="tva" id="tva" class="form-select" onchange="calculateTTC()">
                                <option value="0" {{ old('tva', $bonCommandeR->tva) == 0 ? 'selected' : '' }}>Aucune TVA (0%)</option>
                                <option value="20" {{ old('tva', $bonCommandeR->tva) == 20 ? 'selected' : '' }}>TVA 20%</option>
                                <option value="10" {{ old('tva', $bonCommandeR->tva) == 10 ? 'selected' : '' }}>TVA 10%</option>
                                <option value="14" {{ old('tva', $bonCommandeR->tva) == 14 ? 'selected' : '' }}>TVA 14%</option>
                            </select>
                            @error('tva')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="total-section">
                        <div class="total-row">
                            <div class="total-label">Total HT</div>
                            <div class="total-value">
                                <input type="text" name="total_ht" class="form-control text-end total-value-input" id="total_ht" value="{{ old('total_ht', number_format($bonCommandeR->total_ht, 2, '.', '')) }}" readonly>
                                <span class="currency-display">{{ $bonCommandeR->currency ?? 'DH' }}</span>
                            </div>
                            @error('total_ht')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="total-ttc-row d-flex justify-content-between align-items-center">
                            <div class="total-label">Total TTC</div>
                            <div class="total-value">
                                <input type="text" name="total_ttc" class="form-control text-end total-value-input" id="total_ttc" value="{{ old('total_ttc', number_format($bonCommandeR->total_ttc, 2, '.', '')) }}" readonly>
                                <span class="currency-display">{{ $bonCommandeR->currency ?? 'DH' }}</span>
                            </div>
                            @error('total_ttc')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section Informations Importantes --}}
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations importantes (Notes)</h3>
                </div>
                
                <div id="important-container">
                    @php
                        // Assurez-vous que c'est un tableau, même si c'est null, une chaîne vide ou JSON mal formaté
                        $importantItems = is_array($bonCommandeR->important) ? $bonCommandeR->important : (json_decode($bonCommandeR->important, true) ?? []);
                    @endphp
                    @forelse ($importantItems as $index => $info)
                        <div class="important-row">
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old("important.$index", $info) }}">
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeImportant(this)">
                                <i class="fas fa-minus-circle"></i>
                            </button>
                        </div>
                    @empty
                        <div class="important-row">
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante (Ex: Délai de livraison 15 jours)" value="{{ old('important.0') }}">
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeImportant(this)">
                                <i class="fas fa-minus-circle"></i>
                            </button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-important mt-3" onclick="addImportant()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter une autre information
                </button>
            </div>

            <div class="action-buttons">
                <a href="{{ url()->previous() }}" class="btn btn-cancel">
                    <i class="fas fa-arrow-left me-2"></i> Annuler
                </a>
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-save me-2"></i> Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>

    <script>
        function calculatePrixTotal() {
            let totalHT = 0;
            let rows = document.querySelectorAll('#product-container .product-row');
            
            // 1. Calculer le total HT et le prix total par ligne
            rows.forEach(function(row) {
                let quantityInput = row.querySelector('.quantity');
                let unitPriceInput = row.querySelector('.unit-price');
                let totalPriceInput = row.querySelector('.total-price');

                let quantity = parseFloat(quantityInput ? quantityInput.value : 0) || 0;
                let unitPrice = parseFloat(unitPriceInput ? unitPriceInput.value : 0) || 0;
                
                let totalPrice = (quantity * unitPrice);

                if (totalPriceInput) {
                    totalPriceInput.value = totalPrice.toFixed(2);
                }
                
                totalHT += totalPrice;
            });

            // 2. Mettre à jour le champ Total HT et recalculer le TTC
            let totalHTInput = document.getElementById('total_ht');
            if(totalHTInput) {
                totalHTInput.value = totalHT.toFixed(2);
            }
            
            calculateTTC();
            updateProductNumbers(); // Mise à jour des numéros de ligne
        }

        function calculateTTC() {
            let totalHTInput = document.getElementById('total_ht');
            let tvaSelect = document.querySelector('[name="tva"]');
            let totalTTCInput = document.getElementById('total_ttc');

            let totalHT = parseFloat(totalHTInput ? totalHTInput.value : 0) || 0;
            let tva = parseFloat(tvaSelect ? tvaSelect.value : 0) || 0;
            let totalTTC = totalHT * (1 + tva / 100);
            
            if(totalTTCInput) {
                totalTTCInput.value = totalTTC.toFixed(2);
            }
        }
        
        function updateProductNumbers() {
            const productNumbers = document.querySelectorAll('.product-number');
            productNumbers.forEach((el, index) => {
                el.textContent = index + 1;
            });
        }

        function addProduct() {
            let productContainer = document.getElementById('product-container');
            let newRow = document.createElement('div');
            newRow.classList.add('product-row');
            newRow.innerHTML = `
                <div class="product-number"></div> <div class="form-group mb-3">
                    <label class="form-label">Libellé <span class="required">*</span></label>
                    <textarea name="libelle[]" class="form-control" rows="3" placeholder="Description de l'article commandé..." required></textarea>
                </div>
                <div class="grid-4">
                    <div class="form-group">
                        <label class="form-label">Quantité <span class="required">*</span></label>
                        <input type="number" name="quantite[]" class="form-control quantity" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix HT Unitaire <span class="required">*</span></label>
                        <input type="number" name="prix_ht[]" class="form-control unit-price" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prix Total HT</label>
                        <input type="number" name="prix_total[]" class="form-control total-price" readonly>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                            <i class="fas fa-trash me-2"></i> Supprimer
                        </button>
                    </div>
                </div>
            `;
            productContainer.appendChild(newRow);
            calculatePrixTotal(); // Recalculer et mettre à jour les numéros
        }

        function removeProduct(button) {
            button.closest('.product-row').remove();
            calculatePrixTotal(); // Recalculer et mettre à jour les numéros
        }

        function addImportant() {
            const container = document.getElementById('important-container');
            const newRow = document.createElement('div');
            newRow.className = 'important-row';
            newRow.innerHTML = `
                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-remove btn-sm" onclick="removeImportant(this)">
                    <i class="fas fa-minus-circle"></i>
                </button>
            `;
            container.appendChild(newRow);
        }

        function removeImportant(button) {
            button.closest('.important-row').remove();
        }

        // Initialize calculations on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculatePrixTotal();
        });
    </script>
</x-app-layout>