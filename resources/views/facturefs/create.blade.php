<x-app-layout>
    <style>
        /* Styles Globaux et Couleurs du Thème (Rouge/Rose) */
        .gradient-bg {
            /* Arrière-plan dégradé pour les éléments principaux (Bouton Créer) */
            background: linear-gradient(135deg, #059669, #10b981); /* Vert pour Facture (Succès) */
        }
        
        .gradient-text {
            /* Texte dégradé pour le titre principal */
            background: linear-gradient(135deg, #059669, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .create-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Carte de Formulaire */
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
            /* Icône de section avec dégradé */
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #059669, #10b981);
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
            width: 100%; /* S'assurer qu'ils prennent toute la largeur dans la grille */
        }

        .form-control:focus, .form-select:focus {
            border-color: #10b981; /* Couleur de focus verte */
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
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

        /* Ligne de Produit (Article de Formation) */
        .product-row {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            position: relative;
            transition: all 0.3s ease;
            /* Utilisation de grid pour la mise en page interne des produits */
            display: grid;
            grid-template-columns: repeat(4, 1fr) 100px; /* 4 colonnes pour les champs + bouton Supprimer */
            gap: 15px;
            align-items: end;
        }

        .product-row > .form-group {
            margin-bottom: 0; /* Supprimer la marge par défaut des form-group dans la grille */
        }

        .product-row .form-group:nth-child(1) { /* Libellé */
            grid-column: span 5;
        }

        .product-row:hover {
            border-color: #10b981;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.1);
        }

        .product-number {
            position: absolute;
            top: -15px;
            left: 20px;
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(5, 150, 105, 0.3);
            z-index: 10;
        }

        /* Informations Importantes */
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
        .btn {
             /* Base pour tous les boutons */
             border: none;
             padding: 12px 25px;
             border-radius: 10px;
             font-weight: 600;
             transition: all 0.3s ease;
             display: inline-flex;
             align-items: center;
             justify-content: center;
             gap: 8px;
        }

        .btn-gradient {
            /* Bouton Créer (Success/Primary) */
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 15px 35px; /* Plus grand pour l'action principale */
            font-size: 18px;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-add {
            /* Bouton Ajouter Produit (Info/Secondaire) */
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 10px 25px;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
            color: white;
        }
        
        .btn-remove {
            /* Bouton Supprimer (Danger) */
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 8px 15px;
            border-radius: 8px;
            font-weight: 600;
            white-space: nowrap;
        }

        .btn-remove:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.3);
            color: white;
        }

        .btn-important {
            /* Bouton Ajouter Info Importante (Warning) */
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 10px 25px;
        }

        .btn-important:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.3);
            color: white;
        }

        /* Section Totaux */
        .total-section {
            background: #f0fdf4; /* Tonalité de vert clair */
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
            background: linear-gradient(135deg, #059669, #10b981);
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

        /* Responsive */
        @media (max-width: 992px) {
            .grid-4, .grid-3, .grid-2 {
                grid-template-columns: repeat(2, 1fr);
            }
            .form-card {
                padding: 25px;
            }
            .product-row {
                 grid-template-columns: 1fr 1fr 1fr; /* 3 colonnes sur tablette */
            }
            .product-row .form-group:nth-child(1) {
                 grid-column: span 3;
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
                 grid-template-columns: 1fr; /* 1 colonne sur mobile */
                 padding: 20px;
            }
            .product-row .form-group:nth-child(1) {
                 grid-column: span 1;
            }
            .product-row .form-group:last-child {
                text-align: right; /* Aligner le bouton supprimer à droite */
            }
            .important-row {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Alertes et Animations */
        .alert-danger {
            background: #fee2e2;
            border: 2px solid #ef4444;
            border-radius: 12px;
            padding: 15px 20px;
            color: #b91c1c;
            font-weight: 500;
            margin-bottom: 25px;
        }
        .alert-danger ul {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
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
        
        /* Utilitaires pour espacement (Simuler les classes Bootstrap/Tailwind manquantes) */
        .mb-3 { margin-bottom: 1rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        .mb-2 { margin-bottom: 0.5rem !important; }
        .mt-4 { margin-top: 1.5rem !important; }
        .d-flex { display: flex !important; }
        .justify-content-between { justify-content: space-between !important; }
        .align-items-center { align-items: center !important; }
        .me-2 { margin-right: 0.5rem !important; }
        .px-4 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
    </style>

    <div class="create-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-file-invoice me-2"></i> Créer une Nouvelle Facture de Formation
                </h2>
                <p class="text-muted mb-0">Remplissez les informations pour générer votre Facture</p>
            </div>
            </div>

        @if ($errors->any())
            <div class="alert alert-danger fade-in">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-triangle me-2"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('facturefs.store') }}" method="POST" id="facture-form">
            @csrf
            @if (isset($devisf))
                <input type="hidden" name="devisf_id" value="{{ $devisf->id }}">
            @endif

            <div class="form-card fade-in">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales & Client</h3>
                </div>

                <div class="alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    Le numéro de Facture sera généré automatiquement après la création.
                </div>

                <div class="grid-2 mb-3">
                    <div class="form-group">
                        <label class="form-label">Numéro de la Facture</label>
                        <input type="text" name="facturef_num" id="facturef_num" class="form-control" value="{{ old('facturef_num', 'Généré automatiquement après création') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Ex: Formation en Sécurité Informatique" value="{{ old('titre', isset($devisf) ? $devisf->titre : '') }}" required>
                </div>

                <div class="grid-4 mb-3">
                    <div class="form-group">
                        <label class="form-label">Client <span class="required">*</span></label>
                        <input type="text" name="client" class="form-control" placeholder="Nom de l'entreprise/client" value="{{ old('client', isset($devisf) ? $devisf->client : '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Téléphone <span class="required">*</span></label>
                        <input type="text" name="tele" class="form-control" placeholder="Numéro de contact" value="{{ old('tele', isset($devisf) ? ($devisf->contact ?? '') : '') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" placeholder="Identifiant ICE" value="{{ old('ice', isset($devisf) ? ($devisf->ice ?? '') : '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" placeholder="Référence interne" value="{{ old('ref', isset($devisf) ? ($devisf->ref ?? '') : '') }}">
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" placeholder="Adresse complète du client">{{ old('adresse', isset($devisf) ? ($devisf->adresse ?? '') : '') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Afficher le cachet ?</label>
                    <select name="afficher_cachet" class="form-select">
                        <option value="1" {{ old('afficher_cachet', isset($devisf) ? ($devisf->afficher_cachet ?? 1) : 1) == 1 ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('afficher_cachet', isset($devisf) ? ($devisf->afficher_cachet ?? 0) : 0) == 0 ? 'selected' : '' }}>Non</option>
                    </select>
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
                    @if (isset($devisf) && $devisf->items)
                        @foreach ($devisf->items as $index => $item)
                            <div class="product-row fade-in">
                                <div class="product-number">{{ $index + 1 }}</div>
                                
                                <div class="form-group">
                                    <label class="form-label">Libellé <span class="required">*</span></label>
                                    <textarea name="libelle[]" class="form-control" rows="3" required>{{ $item->libele }}</textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Type de Calcul</label>
                                    <select name="type[]" class="form-select" onchange="toggleFields(this)">
                                        <option value="duree" {{ $item->type == 'formation' ? 'selected' : '' }}>Durée</option>
                                        <option value="nombre_collaborateurs" {{ $item->type == 'nombre' ? 'selected' : '' }}>Nombre de collaborateurs</option>
                                        <option value="nombre_jours" {{ $item->type == 'nombre_de_jours' ? 'selected' : '' }}>Nombre de jours</option>
                                    </select>
                                </div>
                                
                                <div class="form-group duree-field" style="{{ $item->type == 'formation' ? 'display: block' : 'display: none' }}">
                                    <label class="form-label">Durée (Jours/Heures)</label>
                                    <input type="text" name="duree[]" class="form-control" value="{{ $item->formation ?? '' }}">
                                </div>
                                
                                <div class="form-group nombre_collaborateurs-field" style="{{ $item->type == 'nombre' ? 'display: block' : 'display: none' }}">
                                    <label class="form-label">Nombre de collaborateurs</label>
                                    <input type="number" name="nombre_collaborateurs[]" class="form-control quantity-input" value="{{ $item->nombre ?? '' }}" oninput="calculatePrixTotal()">
                                </div>
                                
                                <div class="form-group nombre_jours-field" style="{{ $item->type == 'nombre_de_jours' ? 'display: block' : 'display: none' }}">
                                    <label class="form-label">Nombre de jours</label>
                                    <input type="number" name="nombre_jours[]" class="form-control quantity-input" value="{{ $item->nombre_de_jours ?? '' }}" oninput="calculatePrixTotal()">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Prix Unitaire <span class="required">*</span></label>
                                    <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="{{ $item->prix_unitaire }}" oninput="calculatePrixTotal()" min="0" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Prix Total HT</label>
                                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ $item->prix_total }}" readonly>
                                </div>
                                
                                <div class="form-group">
                                    <button type="button" class="btn btn-remove" onclick="removeProduct(this)"><i class="fas fa-trash-alt me-1"></i> Supprimer</button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="product-row fade-in">
                            <div class="product-number">1</div>
                            
                            <div class="form-group">
                                <label class="form-label">Libellé <span class="required">*</span></label>
                                <textarea name="libelle[]" class="form-control" rows="3" required>{{ old('libelle.0') }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Type de Calcul</label>
                                <select name="type[]" class="form-select" onchange="toggleFields(this)">
                                    <option value="duree" {{ old('type.0') == 'duree' ? 'selected' : '' }}>Durée</option>
                                    <option value="nombre_collaborateurs" {{ old('type.0') == 'nombre_collaborateurs' ? 'selected' : '' }}>Nombre de collaborateurs</option>
                                    <option value="nombre_jours" {{ old('type.0') == 'nombre_jours' ? 'selected' : '' }}>Nombre de jours</option>
                                </select>
                            </div>
                            
                            <div class="form-group duree-field" style="{{ old('type.0', 'duree') == 'duree' ? 'display: block' : 'display: none' }}">
                                <label class="form-label">Durée (Jours/Heures)</label>
                                <input type="text" name="duree[]" class="form-control" value="{{ old('duree.0') }}">
                            </div>
                            
                            <div class="form-group nombre_collaborateurs-field" style="{{ old('type.0') == 'nombre_collaborateurs' ? 'display: block' : 'display: none' }}">
                                <label class="form-label">Nombre de collaborateurs</label>
                                <input type="number" name="nombre_collaborateurs[]" class="form-control quantity-input" value="{{ old('nombre_collaborateurs.0') }}" oninput="calculatePrixTotal()">
                            </div>
                            
                            <div class="form-group nombre_jours-field" style="{{ old('type.0') == 'nombre_jours' ? 'display: block' : 'display: none' }}">
                                <label class="form-label">Nombre de jours</label>
                                <input type="number" name="nombre_jours[]" class="form-control quantity-input" value="{{ old('nombre_jours.0') }}" oninput="calculatePrixTotal()">
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Prix Unitaire <span class="required">*</span></label>
                                <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="{{ old('prix_ht.0') }}" oninput="calculatePrixTotal()" min="0" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Prix Total HT</label>
                                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                            </div>
                            
                            <div class="form-group">
                                <button type="button" class="btn btn-remove" onclick="removeProduct(this)"><i class="fas fa-trash-alt me-1"></i> Supprimer</button>
                            </div>
                        </div>
                    @endif
                </div>

                <button type="button" class="btn btn-add mt-4" onclick="addProduct()">
                    <i class="fas fa-plus me-2"></i> Ajouter un article de formation
                </button>
            </div>
            
            <div class="grid-2">
                <div class="form-card fade-in">
                    <div class="section-header">
                        <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3 class="section-title">Informations Importantes</h3>
                    </div>

                    <div id="important-container">
                        @if (isset($devisf) && $devisf->importantInfos)
                            @foreach ($devisf->importantInfos as $info)
                                <div class="important-row mb-2 fade-in">
                                    <input type="text" name="important[]" class="form-control" value="{{ $info->info }}" placeholder="Ajouter une information importante">
                                    <button type="button" class="btn btn-remove" onclick="removeImportant(this)">Supprimer</button>
                                </div>
                            @endforeach
                        @else
                            <div class="important-row mb-2 fade-in">
                                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">Supprimer</button>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-important mt-3" onclick="addImportant()">
                        <i class="fas fa-plus me-2"></i> Ajouter une information
                    </button>
                </div>

                <div class="total-section fade-in">
                    <div class="total-row">
                        <div class="form-group mb-0" style="width: 100%;">
                            <label class="form-label">TVA (%)</label>
                            <select name="tva" class="form-select" onchange="calculateTTC()">
                                <option value="0" {{ old('tva', isset($devisf) && $devisf->total_ht > 0 ? ($devisf->tva / $devisf->total_ht * 100) : 0) == 0 ? 'selected' : '' }}>Aucune TVA (0%)</option>
                                <option value="20" {{ old('tva', isset($devisf) && $devisf->total_ht > 0 ? ($devisf->tva / $devisf->total_ht * 100) : 20) == 20 ? 'selected' : '' }}>TVA 20%</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="total-row">
                         <div class="form-group mb-0" style="width: 100%;">
                            <label class="form-label">Devise</label>
                            <select name="currency" class="form-select">
                                <option value="DH" {{ old('currency', isset($devisf) ? $devisf->currency : 'DH') == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                                <option value="EUR" {{ old('currency', isset($devisf) ? $devisf->currency : 'EUR') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                            </select>
                        </div>
                    </div>

                    <div class="total-row">
                        <span class="total-label">Total Hors Taxe (HT) :</span>
                        <input type="number" step="0.01" name="total_ht" class="form-control total-value" id="total_ht" value="{{ old('total_ht', isset($devisf) ? $devisf->total_ht : '') }}" readonly style="width: 150px; text-align: right; background: none; border: none;">
                    </div>

                    <div class="total-ttc-row d-flex justify-content-between align-items-center">
                        <span class="total-label" style="font-size: 24px;">Total Toutes Taxes Comprises (TTC) :</span>
                        <input type="number" step="0.01" name="total_ttc" class="form-control total-value" id="total_ttc" value="{{ old('total_ttc', isset($devisf) ? $devisf->total_ttc : '') }}" readonly style="width: 180px; text-align: right; background: none; border: none; color: white;">
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-check-circle me-2"></i> Créer la Facture
                </button>
            </div>
        </form>
    </div>

    <script>
    let productIndex = {{ isset($devisf) && $devisf->items ? count($devisf->items) : (old('libelle') ? count(old('libelle')) : 1) }};

    function updateProductNumbers() {
        document.querySelectorAll('.product-row').forEach((row, index) => {
            let numberDiv = row.querySelector('.product-number');
            if (numberDiv) {
                numberDiv.textContent = index + 1;
            }
        });
    }

    function calculatePrixTotal() {
        let totalHT = 0;
        let rows = document.querySelectorAll('.product-row');

        rows.forEach(function(row) {
            let unitPriceInput = row.querySelector('.unit-price');
            let totalPriceInput = row.querySelector('.total-price');
            let type = row.querySelector('[name="type[]"]').value;

            let unitPrice = parseFloat(unitPriceInput.value) || 0;
            let quantity = 1;
            
            // Récupérer la quantité correcte en fonction du type sélectionné
            if (type === 'nombre_collaborateurs') {
                const quantityInput = row.querySelector('[name="nombre_collaborateurs[]"]');
                quantity = parseFloat(quantityInput ? quantityInput.value : 0) || 0;
            } else if (type === 'nombre_jours') {
                const quantityInput = row.querySelector('[name="nombre_jours[]"]');
                quantity = parseFloat(quantityInput ? quantityInput.value : 0) || 0;
            }
            
            // Pour 'duree', la quantité reste 1 et le prix unitaire est le prix total, la durée est juste informative
            if (type === 'duree' || quantity === 0) {
                 quantity = 1; 
            }
            
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

    function addProduct() {
        let productContainer = document.getElementById('product-container');
        let newRow = document.createElement('div');
        newRow.classList.add('product-row', 'fade-in');
        productIndex++; // Incrémenter l'index pour le numéro d'affichage
        
        // Trouver le prix unitaire initial s'il y a des valeurs old
        const lastIndex = document.querySelectorAll('.product-row').length -1;
        const initialLibelle = '{{ old('libelle.' . (isset($devisf) && $devisf->items ? $devisf->items->count() : 0)) }}';
        
        newRow.innerHTML = `
            <div class="product-number">${productIndex}</div>
            
            <div class="form-group">
                <label class="form-label">Libellé <span class="required">*</span></label>
                <textarea name="libelle[]" class="form-control" rows="3" required>${initialLibelle}</textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">Type de Calcul</label>
                <select name="type[]" class="form-select" onchange="toggleFields(this)">
                    <option value="duree">Durée</option>
                    <option value="nombre_collaborateurs">Nombre de collaborateurs</option>
                    <option value="nombre_jours">Nombre de jours</option>
                </select>
            </div>
            
            <div class="form-group duree-field">
                <label class="form-label">Durée (Jours/Heures)</label>
                <input type="text" name="duree[]" class="form-control">
            </div>
            
            <div class="form-group nombre_collaborateurs-field" style="display: none;">
                <label class="form-label">Nombre de collaborateurs</label>
                <input type="number" name="nombre_collaborateurs[]" class="form-control quantity-input" oninput="calculatePrixTotal()">
            </div>
            
            <div class="form-group nombre_jours-field" style="display: none;">
                <label class="form-label">Nombre de jours</label>
                <input type="number" name="nombre_jours[]" class="form-control quantity-input" oninput="calculatePrixTotal()">
            </div>
            
            <div class="form-group">
                <label class="form-label">Prix Unitaire <span class="required">*</span></label>
                <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" min="0" oninput="calculatePrixTotal()" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Prix Total HT</label>
                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
            </div>
            
            <div class="form-group">
                <button type="button" class="btn btn-remove" onclick="removeProduct(this)"><i class="fas fa-trash-alt me-1"></i> Supprimer</button>
            </div>
        `;
        productContainer.appendChild(newRow);
        toggleFields(newRow.querySelector('[name="type[]"]')); // Initialiser la visibilité
        updateProductNumbers(); // Mettre à jour les numéros
    }

    function removeProduct(button) {
        let productRows = document.querySelectorAll('.product-row');
        if (productRows.length > 1) {
            button.closest('.product-row').remove();
            calculatePrixTotal();
            updateProductNumbers(); // Mettre à jour les numéros
        } else {
            alert('Vous devez conserver au moins un article de formation.');
        }
    }

    function toggleFields(select) {
        const type = select.value;
        // Trouver l'élément .product-row le plus proche
        const row = select.closest('.product-row'); 
        
        // Sélectionner les champs dans la LIGNE actuelle uniquement
        const dureeField = row.querySelector('.duree-field');
        const nombreCollaborateursField = row.querySelector('.nombre_collaborateurs-field');
        const nombreJoursField = row.querySelector('.nombre_jours-field');
        
        // Cacher tous les champs de quantité/durée
        dureeField.style.display = 'none';
        nombreCollaborateursField.style.display = 'none';
        nombreJoursField.style.display = 'none';
        
        // Afficher le champ correspondant au type sélectionné
        if (type === 'duree') {
            dureeField.style.display = 'block';
        } else if (type === 'nombre_collaborateurs') {
            nombreCollaborateursField.style.display = 'block';
        } else if (type === 'nombre_jours') {
            nombreJoursField.style.display = 'block';
        }
        
        // Recalculer le total
        calculatePrixTotal();
    }

    function addImportant() {
        const container = document.getElementById('important-container');
        const newRow = document.createElement('div');
        newRow.className = 'important-row mb-2 fade-in';
        newRow.innerHTML = `
            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
            <button type="button" class="btn btn-remove" onclick="removeImportant(this)"><i class="fas fa-trash-alt me-1"></i> Supprimer</button>
        `;
        container.appendChild(newRow);
    }

    function removeImportant(button) {
        let importantRows = document.querySelectorAll('.important-row');
        if (importantRows.length > 1) {
            button.closest('.important-row').remove();
        } else {
             // Au lieu de supprimer le dernier, on vide son contenu
             const input = button.closest('.important-row').querySelector('input');
             if (input) {
                 input.value = '';
             }
        }
    }

    // Initialisation au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[name="type[]"]').forEach(toggleFields);
        updateProductNumbers();
        calculatePrixTotal(); // Calculer les totaux au chargement
    });
    </script>
</x-app-layout>