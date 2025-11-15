<x-app-layout>
    <style>
        /* Styles de base */
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
        
        /* Product Number (yemken t7aydha ila mabghitch, mais kat3awen f l'esthétique) */
        .product-row:not(:first-child) { 
            margin-top: 40px; 
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

        .total-row-display { /* Total HT, TVA, TTC pour l'affichage stylisé */
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #d1fae5;
        }
        
        .total-row-display:last-child {
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
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
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

        /* Utilisation pour la grille des champs des produits */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 10px;
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
            .grid-4, .grid-3, .grid-2, .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-card {
                padding: 25px;
            }
        }

        @media (max-width: 576px) {
            .grid-4, .grid-3, .grid-2, .product-grid {
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

    <div class="edit-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-edit"></i> Modifier la Facture N° {{ $facturef->facturef_num }}
                </h2>
                <p class="text-muted mb-0">Mettez à jour les informations de votre Facture de Formation</p>
            </div>
            {{-- Yemken tzid lien Retour bhal:
            <a href="{{ route('facturefs.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Retour aux Factures
            </a>
            --}}
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

        <form action="{{ route('facturefs.update', $facturef) }}" method="POST" id="facture-form">
            @csrf
            @method('PUT')
            
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales de la Facture</h3>
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label for="facturef_num" class="form-label">Numéro de la Facture</label>
                        <input type="text" name="facturef_num" id="facturef_num" class="form-control" value="{{ old('facturef_num', $facturef->facturef_num) }}" required readonly>
                    </div>
                    <div>
                        <label for="date" class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $facturef->date) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Titre de la formation" value="{{ old('titre', $facturef->titre) }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label for="client" class="form-label">Client <span class="required">*</span></label>
                        <input type="text" name="client" class="form-control" placeholder="Nom du client" value="{{ old('client', $facturef->client) }}" required>
                    </div>
                    <div>
                        <label for="tele" class="form-label">Téléphone <span class="required">*</span></label>
                        <input type="text" name="tele" class="form-control" placeholder="Téléphone du client" value="{{ old('tele', $facturef->tele) }}" required>
                    </div>
                    <div>
                        <label for="ref" class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" placeholder="Référence interne ou du client" value="{{ old('ref', $facturef->ref) }}">
                    </div>
                </div>
                
                <div class="grid-2 mb-3">
                    <div>
                        <label for="ice" class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" placeholder="ICE du client" value="{{ old('ice', $facturef->ice) }}">
                    </div>
                    <div>
                        <label for="afficher_cachet" class="form-label">Afficher le cachet ?</label>
                        <select name="afficher_cachet" class="form-select">
                            <option value="1" {{ old('afficher_cachet', $facturef->afficher_cachet) == 1 ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ old('afficher_cachet', $facturef->afficher_cachet) == 0 ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" placeholder="Adresse complète du client">{{ old('adresse', $facturef->adresse) }}</textarea>
                </div>
            </div>

            
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <h3 class="section-title">Détails de la Formation</h3>
                </div>

                <div id="product-container">
                    @foreach ($facturef->items as $index => $item)
                        <div class="product-row">
                            <div class="product-number">{{ $index + 1 }}</div>
                            <div class="mb-3">
                                <label for="libelle" class="form-label">Libellé <span class="required">*</span></label>
                                <textarea name="libelle[]" class="form-control" rows="3" required>{{ old("libelle.$index", $item->libelle) }}</textarea>
                            </div>

                            <div class="product-grid">
                                <div>
                                    <label for="type" class="form-label">Choisir le type</label>
                                    <select name="type[]" class="form-select" onchange="toggleFields(this)">
                                        @php
                                            $current_type = '';
                                            if ($item->duree) $current_type = 'duree';
                                            elseif ($item->nombre_collaborateurs) $current_type = 'nombre_collaborateurs';
                                            elseif ($item->nombre_jours) $current_type = 'nombre_jours';
                                            // Handle case where item has no quantity field set (default to duree for new empty items or old items)
                                            if (!$current_type) $current_type = old("type.$index", 'duree');
                                        @endphp
                                        <option value="duree" {{ $current_type == 'duree' ? 'selected' : '' }}>Durée</option>
                                        <option value="nombre_collaborateurs" {{ $current_type == 'nombre_collaborateurs' ? 'selected' : '' }}>Nombre de collaborateurs</option>
                                        <option value="nombre_jours" {{ $current_type == 'nombre_jours' ? 'selected' : '' }}>Nombre de jours</option>
                                    </select>
                                </div>
                                <div class="duree-field" style="{{ $item->duree ? '' : 'display: none;' }}">
                                    <label for="duree" class="form-label">Durée (en jours ou heures)</label>
                                    <input type="text" name="duree[]" class="form-control" value="{{ old("duree.$index", $item->duree) }}">
                                </div>
                                <div class="nombre_collaborateurs-field" style="{{ $item->nombre_collaborateurs ? '' : 'display: none;' }}">
                                    <label for="nombre_collaborateurs" class="form-label">Nombre de collaborateurs</label>
                                    <input type="number" name="nombre_collaborateurs[]" class="form-control" value="{{ old("nombre_collaborateurs.$index", $item->nombre_collaborateurs) }}" oninput="calculatePrixTotal()" min="0">
                                </div>
                                <div class="nombre_jours-field" style="{{ $item->nombre_jours ? '' : 'display: none;' }}">
                                    <label for="nombre_jours" class="form-label">Nombre de jours</label>
                                    <input type="number" name="nombre_jours[]" class="form-control" value="{{ old("nombre_jours.$index", $item->nombre_jours) }}" oninput="calculatePrixTotal()" min="0">
                                </div>
                                <div>
                                    <label for="prix_ht" class="form-label">Prix Unitaire <span class="required">*</span></label>
                                    <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="{{ old("prix_ht.$index", $item->prix_ht) }}" oninput="calculatePrixTotal()" min="0" required>
                                </div>
                                <div>
                                    <label for="prix_total" class="form-label">Prix Total (HT)</label>
                                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-remove" onclick="removeProduct(this)">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    @endforeach
                    @if ($facturef->items->isEmpty())
                        <div class="product-row">
                            <div class="product-number">1</div>
                            <div class="mb-3">
                                <label for="libelle" class="form-label">Libellé <span class="required">*</span></label>
                                <textarea name="libelle[]" class="form-control" rows="3" required>{{ old('libelle.0') }}</textarea>
                            </div>
                            <div class="product-grid">
                                <div>
                                    <label for="type" class="form-label">Choisir le type</label>
                                    <select name="type[]" class="form-select" onchange="toggleFields(this)">
                                        <option value="duree" {{ old('type.0') == 'duree' || !old('type.0') ? 'selected' : '' }}>Durée</option>
                                        <option value="nombre_collaborateurs" {{ old('type.0') == 'nombre_collaborateurs' ? 'selected' : '' }}>Nombre de collaborateurs</option>
                                        <option value="nombre_jours" {{ old('type.0') == 'nombre_jours' ? 'selected' : '' }}>Nombre de jours</option>
                                    </select>
                                </div>
                                <div class="duree-field" style="{{ (old('type.0') == 'duree' || !old('type.0')) ? '' : 'display: none;' }}">
                                    <label for="duree" class="form-label">Durée (en jours ou heures)</label>
                                    <input type="text" name="duree[]" class="form-control" value="{{ old('duree.0') }}">
                                </div>
                                <div class="nombre_collaborateurs-field" style="{{ old('type.0') == 'nombre_collaborateurs' ? '' : 'display: none;' }}">
                                    <label for="nombre_collaborateurs" class="form-label">Nombre de collaborateurs</label>
                                    <input type="number" name="nombre_collaborateurs[]" class="form-control" value="{{ old('nombre_collaborateurs.0') }}" oninput="calculatePrixTotal()" min="0">
                                </div>
                                <div class="nombre_jours-field" style="{{ old('type.0') == 'nombre_jours' ? '' : 'display: none;' }}">
                                    <label for="nombre_jours" class="form-label">Nombre de jours</label>
                                    <input type="number" name="nombre_jours[]" class="form-control" value="{{ old('nombre_jours.0') }}" oninput="calculatePrixTotal()" min="0">
                                </div>
                                <div>
                                    <label for="prix_ht" class="form-label">Prix Unitaire <span class="required">*</span></label>
                                    <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="{{ old('prix_ht.0') }}" oninput="calculatePrixTotal()" min="0" required>
                                </div>
                                <div>
                                    <label for="prix_total" class="form-label">Prix Total (HT)</label>
                                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-remove" onclick="removeProduct(this)">
                                    <i class="fas fa-trash-alt"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    @endif
                </div>

                <button type="button" class="btn btn-add mt-3" onclick="addProduct()">
                    <i class="fas fa-plus-circle"></i> Ajouter un produit
                </button>
            </div>
            
            <div class="grid-2">
                <div class="form-card">
                    <div class="section-header">
                        <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="section-title">Informations Importantes</h3>
                    </div>

                    <div id="important-container">
                        @foreach ($facturef->importantInfo as $index => $info)
                            <div class="important-row">
                                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old("important.$index", $info->info) }}">
                                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                        @if ($facturef->importantInfo->isEmpty())
                            <div class="important-row">
                                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-important mt-3" onclick="addImportant()">
                        <i class="fas fa-plus-circle"></i> Ajouter une autre information
                    </button>
                </div>

                <div class="total-section">
                    <div class="section-header">
                        <div class="section-icon" style="background: linear-gradient(135deg, #059669, #10b981);">
                            <i class="fas fa-calculator"></i>
                        </div>
                        <h3 class="section-title" style="color: #059669;">Calcul des Totaux</h3>
                    </div>

                    <div class="mb-3">
                        <label for="currency" class="form-label">Devise</label>
                        <select name="currency" class="form-select">
                            <option value="DH" {{ old('currency', $facturef->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency', $facturef->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                              <option value="CFA" {{ old('currency', $facturef->currency) == 'CFA' ? 'selected' : '' }}>CFA</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tva" class="form-label">TVA (%)</label>
                        <select name="tva" class="form-select" onchange="calculateTTC()">
                            @php
                                $tva_percent = $facturef->total_ht > 0 ? round($facturef->tva / $facturef->total_ht * 100) : 0;
                            @endphp
                            <option value="0" {{ old('tva', $tva_percent) == 0 ? 'selected' : '' }}>Aucune TVA (0%)</option>
                            <option value="20" {{ old('tva', $tva_percent) == 20 ? 'selected' : '' }}>TVA 20%</option>
                        </select>
                    </div>
                    
                    <div class="total-row-display">
                        <span class="total-label">Total HT:</span>
                        <input type="number" step="0.01" name="total_ht" class="form-control total-value" id="total_ht" value="{{ old('total_ht', $facturef->total_ht) }}" readonly style="text-align: right; width: 60%; background-color: #f0fdf4; border: none; font-size: 18px;">
                    </div>

                    <div class="total-ttc-row d-flex justify-content-between">
                        <span class="total-label">Total TTC:</span>
                        <input type="number" step="0.01" name="total_ttc" class="form-control total-value" id="total_ttc" value="{{ old('total_ttc', $facturef->total_ttc) }}" readonly style="text-align: right; width: 60%; background-color: transparent; border: none; font-size: 28px; color: white;">
                    </div>
                </div>
            </div>


            <div class="action-buttons">
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-save"></i> Mettre à jour la Facture
                </button>
            </div>
        </form>
    </div>

    <script>
        /** Fonctions de Calcul **/
        function calculatePrixTotal() {
            let totalHT = 0;
            let rows = document.querySelectorAll('.product-row');

            rows.forEach(function(row) {
                let unitPriceInput = row.querySelector('.unit-price');
                let totalPriceInput = row.querySelector('.total-price');
                
                let unitPrice = parseFloat(unitPriceInput.value) || 0;
                let type = row.querySelector('[name="type[]"]').value;

                let quantity = 1;
                
                // Get the correct quantity field value based on the selected type
                if (type === 'nombre_collaborateurs') {
                    const collabInput = row.querySelector('[name="nombre_collaborateurs[]"]');
                    quantity = parseFloat(collabInput.value) || 0;
                } else if (type === 'nombre_jours') {
                    const joursInput = row.querySelector('[name="nombre_jours[]"]');
                    quantity = parseFloat(joursInput.value) || 0;
                }
                
                // If quantity is 0, total price is 0, even if unit price is set
                let rowTotal = unitPrice * quantity;
                
                totalPriceInput.value = rowTotal.toFixed(2);
                totalHT += rowTotal;
            });

            document.getElementById('total_ht').value = totalHT.toFixed(2);
            calculateTTC();
        }

        function calculateTTC() {
            let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
            let tva = parseFloat(document.querySelector('[name="tva"]').value) || 0;
            let totalTTC = totalHT * (1 + tva / 100);
            document.getElementById('total_ttc').value = totalTTC.toFixed(2);
        }

        /** Fonction d'ajout de produit **/
        function addProduct() {
            let productContainer = document.getElementById('product-container');
            let rows = productContainer.querySelectorAll('.product-row');
            let newIndex = rows.length; 

            let newRow = document.createElement('div');
            newRow.classList.add('product-row');
            newRow.innerHTML = `
                <div class="product-number">${newIndex + 1}</div>
                <div class="mb-3">
                    <label for="libelle" class="form-label">Libellé <span class="required">*</span></label>
                    <textarea name="libelle[]" class="form-control" rows="3" required></textarea>
                </div>
                <div class="product-grid">
                    <div>
                        <label for="type" class="form-label">Choisir le type</label>
                        <select name="type[]" class="form-select" onchange="toggleFields(this)">
                            <option value="duree">Durée</option>
                            <option value="nombre_collaborateurs">Nombre de collaborateurs</option>
                            <option value="nombre_jours">Nombre de jours</option>
                        </select>
                    </div>
                    <div class="duree-field">
                        <label for="duree" class="form-label">Durée (en jours ou heures)</label>
                        <input type="text" name="duree[]" class="form-control">
                    </div>
                    <div class="nombre_collaborateurs-field" style="display: none;">
                        <label for="nombre_collaborateurs" class="form-label">Nombre de collaborateurs</label>
                        <input type="number" name="nombre_collaborateurs[]" class="form-control" oninput="calculatePrixTotal()" min="0">
                    </div>
                    <div class="nombre_jours-field" style="display: none;">
                        <label for="nombre_jours" class="form-label">Nombre de jours</label>
                        <input type="number" name="nombre_jours[]" class="form-control" oninput="calculatePrixTotal()" min="0">
                    </div>
                    <div>
                        <label for="prix_ht" class="form-label">Prix Unitaire <span class="required">*</span></label>
                        <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" min="0" oninput="calculatePrixTotal()" required>
                    </div>
                    <div>
                        <label for="prix_total" class="form-label">Prix Total (HT)</label>
                        <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-3">
                    <button type="button" class="btn btn-remove" onclick="removeProduct(this)">
                        <i class="fas fa-trash-alt"></i> Supprimer
                    </button>
                </div>
            `;
            productContainer.appendChild(newRow);
            updateProductNumbers(); // Update all product numbers
            toggleFields(newRow.querySelector('[name="type[]"]')); // Initialize field visibility
        }

        /** Fonction de suppression de produit **/
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
        
        /** Fonction de mise à jour des numéros de produit **/
        function updateProductNumbers() {
            document.querySelectorAll('.product-row').forEach((row, index) => {
                row.querySelector('.product-number').textContent = index + 1;
            });
        }

        /** Fonction pour afficher/masquer les champs de quantité **/
        function toggleFields(select) {
            const type = select.value;
            const row = select.closest('.product-row');
            const dureeField = row.querySelector('.duree-field');
            const nombreCollaborateursField = row.querySelector('.nombre_collaborateurs-field');
            const nombreJoursField = row.querySelector('.nombre_jours-field');
            
            // Set all to display none
            dureeField.style.display = 'none';
            nombreCollaborateursField.style.display = 'none';
            nombreJoursField.style.display = 'none';

            // Reset value of hidden fields to prevent accidental calculation
            const allQuantityInputs = [
                row.querySelector('[name="duree[]"]'),
                row.querySelector('[name="nombre_collaborateurs[]"]'),
                row.querySelector('[name="nombre_jours[]"]')
            ];
            allQuantityInputs.forEach(input => {
                if(input) {
                    input.value = ''; 
                }
            });


            // Display the selected field and reset its required value for calculation
            if (type === 'duree') {
                dureeField.style.display = 'block';
                // La durée n'est pas utilisée pour le calcul dans ce contexte (quantity=1)
            } else if (type === 'nombre_collaborateurs') {
                nombreCollaborateursField.style.display = 'block';
            } else if (type === 'nombre_jours') {
                nombreJoursField.style.display = 'block';
            }
            
            // Recalculate totals after changing type
            calculatePrixTotal();
        }

        /** Fonction d'ajout d'information importante **/
        function addImportant() {
            const container = document.getElementById('important-container');
            const newRow = document.createElement('div');
            newRow.className = 'important-row';
            newRow.innerHTML = `
                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newRow);
        }

        /** Fonction de suppression d'information importante **/
        function removeImportant(button) {
            let importantRows = document.querySelectorAll('.important-row');
            if (importantRows.length > 1) {
                button.closest('.important-row').remove();
            } else {
                alert('Vous devez conserver au moins une information importante.');
            }
        }

        // Initialisation :
        document.addEventListener('DOMContentLoaded', () => {
            // Initialiser la visibilité des champs pour les produits existants
            document.querySelectorAll('.product-row').forEach(row => {
                const select = row.querySelector('[name="type[]"]');
                if (select) {
                    // Pour les éléments existants, il faut forcer l'affichage
                    const type = select.value;
                    const dureeField = row.querySelector('.duree-field');
                    const nombreCollaborateursField = row.querySelector('.nombre_collaborateurs-field');
                    const nombreJoursField = row.querySelector('.nombre_jours-field');

                    dureeField.style.display = 'none';
                    nombreCollaborateursField.style.display = 'none';
                    nombreJoursField.style.display = 'none';

                    if (type === 'duree') dureeField.style.display = 'block';
                    else if (type === 'nombre_collaborateurs') nombreCollaborateursField.style.display = 'block';
                    else if (type === 'nombre_jours') nombreJoursField.style.display = 'block';
                }
            });
            updateProductNumbers();
            calculatePrixTotal();
        });
    </script>
</x-app-layout>