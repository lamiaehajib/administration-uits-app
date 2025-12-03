<x-app-layout>
    <style>
        /* Variables de couleurs */
        :root {
            --primary-color: #D32F2F;
            --secondary-color: #C2185B;
            --gradient-primary: linear-gradient(135deg, #C2185B, #D32F2F);
            --gradient-hover: linear-gradient(135deg, #AD1457, #B71C1C);
            --shadow-sm: 0 2px 8px rgba(211, 47, 47, 0.1);
            --shadow-md: 0 4px 16px rgba(211, 47, 47, 0.15);
            --shadow-lg: 0 8px 32px rgba(211, 47, 47, 0.2);
        }

        /* Animation pour le chargement de la page */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Header avec gradient */
        .page-header {
            background: var(--gradient-primary);
            padding: 25px 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-lg);
            animation: fadeInUp 0.6s ease;
        }

        .page-header h3 {
            color: #fff;
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-header h3 i {
            font-size: 32px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 50px;
            margin-top: 15px;
        }

        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .breadcrumb-item a:hover {
            color: #fff;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .breadcrumb-item.active {
            color: #fff;
            font-weight: 600;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Card principal avec animation */
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            animation: fadeInUp 0.8s ease;
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-5px);
        }

        .card-body {
            padding: 40px;
            background: #fff;
        }

        /* Labels avec style moderne */
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-label i {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-primary);
            color: #fff;
            border-radius: 6px;
            font-size: 12px;
        }

        /* Inputs avec effet moderne */
        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 15px;
            transition: all 0.3s;
            background: #f8f9fa;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.15);
            background: #fff;
            transform: translateY(-2px);
        }

        .form-control:hover, .form-select:hover {
            border-color: var(--secondary-color);
            background: #fff;
        }

        /* Select2 custom style */
        .select2-container--bootstrap-5 .select2-selection {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            min-height: 48px;
            background: #f8f9fa;
            transition: all 0.3s;
        }

        .select2-container--bootstrap-5 .select2-selection:hover {
            border-color: var(--secondary-color);
            background: #fff;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--primary-color) !important;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.15);
            background: #fff;
        }

        /* Input groups avec animation */
        .col-md-6, .col-md-4, .col-12 {
            animation: slideInRight 0.6s ease;
        }

        .col-md-6:nth-child(1) { animation-delay: 0.1s; }
        .col-md-6:nth-child(2) { animation-delay: 0.2s; }
        .col-md-6:nth-child(3) { animation-delay: 0.3s; }
        .col-md-6:nth-child(4) { animation-delay: 0.4s; }
        .col-md-4:nth-child(5) { animation-delay: 0.5s; }
        .col-md-4:nth-child(6) { animation-delay: 0.6s; }
        .col-md-4:nth-child(7) { animation-delay: 0.7s; }

        /* Total display avec effet spécial */
        #total_display {
            background: var(--gradient-primary);
            color: #fff;
            font-size: 20px;
            font-weight: 700;
            text-align: center;
            border: none;
            box-shadow: var(--shadow-md);
            letter-spacing: 1px;
        }

        #total_display:focus {
            box-shadow: var(--shadow-lg);
        }

        /* Switch personnalisé */
        .form-check-input {
            width: 55px;
            height: 28px;
            cursor: pointer;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
        }

        .form-check-input:checked {
            background: var(--gradient-primary);
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(211, 47, 47, 0.3);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.15);
        }

        .form-check-label {
            font-weight: 600;
            color: #2c3e50;
            margin-left: 10px;
            cursor: pointer;
        }

        /* Box d'information */
        .info-box {
            background: linear-gradient(135deg, rgba(211, 47, 47, 0.05), rgba(194, 24, 91, 0.05));
            border-left: 4px solid var(--primary-color);
            padding: 15px 20px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .info-box i {
            color: var(--primary-color);
            margin-right: 8px;
        }

        .info-box .text-muted {
            color: #5a6c7d !important;
            font-size: 14px;
        }

        /* Textarea avec style */
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Boutons avec gradient et animations */
        .btn {
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn i {
            font-size: 16px;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: #fff;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            background: var(--gradient-hover);
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
        }

        .btn-primary:active {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
            box-shadow: var(--shadow-sm);
        }

        .btn-secondary:hover {
            background: #5a6268;
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }

        .btn-lg {
            padding: 15px 40px;
            font-size: 16px;
        }

        /* Footer des boutons */
        .action-buttons {
            border-top: 2px solid #f0f0f0;
            padding-top: 25px;
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: fadeInUp 1s ease;
        }

        /* Badge de required */
        .text-danger {
            color: var(--primary-color) !important;
            font-weight: 700;
        }

        /* Small text styling */
        small.text-muted {
            color: #7f8c8d !important;
            font-size: 13px;
            display: block;
            margin-top: 5px;
        }

        /* Invalid feedback */
        .invalid-feedback {
            color: var(--primary-color);
            font-weight: 500;
            font-size: 13px;
        }

        .is-invalid {
            border-color: var(--primary-color) !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-body {
                padding: 25px;
            }

            .page-header {
                padding: 20px;
            }

            .page-header h3 {
                font-size: 22px;
            }

            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* Effet de survol sur les champs */
        .form-floating {
            position: relative;
        }

        .form-control::placeholder {
            color: #bdc3c7;
        }

        /* Animation de chargement */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.6s linear infinite;
        }
    </style>

    <div class="container-fluid">
        <!-- En-tête avec gradient -->
        <div class="page-header">
            <h3>
                <i class="fas fa-plus-circle"></i> Nouvel Achat
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('achats.index') }}">Achats</a></li>
                    <li class="breadcrumb-item active">Nouveau</li>
                </ol>
            </nav>
        </div>

        <!-- Formulaire -->
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('achats.store') }}" method="POST" id="achatForm">
                    @csrf
                    
                    <div class="row g-4">
                        <!-- Sélection Catégorie -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-folder"></i> Catégorie
                            </label>
                            <select id="category_id" class="form-select select2">
                                <option value="">-- Sélectionner une catégorie --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->nom }} ({{ $category->produits->count() }} produits)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Filtrer les produits par catégorie</small>
                        </div>

                        <!-- Sélection Produit -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-box"></i> Produit <span class="text-danger">*</span>
                            </label>
                            <select name="produit_id" id="produit_id" class="form-select select2 @error('produit_id') is-invalid @enderror" required>
                                <option value="">-- Sélectionner un produit --</option>
                                @foreach($categories as $category)
                                    @foreach($category->produits as $produit)
                                        <option value="{{ $produit->id }}" 
                                                data-category="{{ $category->id }}"
                                                {{ old('produit_id') == $produit->id ? 'selected' : '' }}>
                                            {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            @error('produit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Fournisseur -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-truck"></i> Fournisseur
                            </label>
                            <input type="text" name="fournisseur" id="fournisseur" 
                                   class="form-control @error('fournisseur') is-invalid @enderror" 
                                   value="{{ old('fournisseur') }}"
                                   placeholder="Nom du fournisseur">
                            @error('fournisseur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Numéro de bon -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-file-invoice"></i> N° Bon de commande
                            </label>
                            <input type="text" name="numero_bon" id="numero_bon" 
                                   class="form-control @error('numero_bon') is-invalid @enderror" 
                                   value="{{ old('numero_bon') }}"
                                   placeholder="Ex: BC-2024-001">
                            @error('numero_bon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quantité -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-cubes"></i> Quantité <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="quantite" id="quantite" 
                                   class="form-control @error('quantite') is-invalid @enderror" 
                                   value="{{ old('quantite', 1) }}" 
                                   min="1" required>
                            @error('quantite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Prix d'achat unitaire -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign"></i> Prix unitaire (DH) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="prix_achat" id="prix_achat" 
                                   class="form-control @error('prix_achat') is-invalid @enderror" 
                                   value="{{ old('prix_achat') }}" 
                                   min="0" required>
                            @error('prix_achat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total (calculé automatiquement) -->
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="fas fa-money-bill-wave"></i> Total (DH)
                            </label>
                            <input type="text" id="total_display" class="form-control" readonly value="0.00 DH">
                        </div>

                        <!-- Date d'achat -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-calendar"></i> Date d'achat <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="date_achat" id="date_achat" 
                                   class="form-control @error('date_achat') is-invalid @enderror" 
                                   value="{{ old('date_achat', now()->format('Y-m-d')) }}" required>
                            @error('date_achat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Checkbox Mettre à jour le stock -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-warehouse"></i> Gestion du Stock
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="update_stock" 
                                       id="update_stock" value="1" checked>
                                <label class="form-check-label" for="update_stock">
                                    Mettre à jour le stock automatiquement
                                </label>
                            </div>
                            <div class="info-box">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i> Si coché, le stock du produit sera augmenté automatiquement
                                </small>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-sticky-note"></i> Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Notes supplémentaires...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="action-buttons">
                        <a href="{{ route('achats.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Enregistrer l'Achat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // ====================================
            // INITIALISATION SELECT2
            // ====================================
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // ====================================
            // FILTRAGE DES PRODUITS PAR CATÉGORIE
            // ====================================
            $('#category_id').on('change', function() {
                const categoryId = $(this).val();
                const $produitSelect = $('#produit_id');
                
                if (categoryId) {
                    // Masquer tous les produits
                    $produitSelect.find('option').each(function() {
                        const $option = $(this);
                        if ($option.val() === '') {
                            $option.show();
                        } else if ($option.data('category') == categoryId) {
                            $option.show();
                        } else {
                            $option.hide();
                        }
                    });
                } else {
                    // Afficher tous les produits
                    $produitSelect.find('option').show();
                }
                
                // Réinitialiser la sélection
                $produitSelect.val('').trigger('change');
            });

            // ====================================
            // CALCUL DU TOTAL AVEC ANIMATION
            // ====================================
            function calculerTotal() {
                const quantite = parseFloat($('#quantite').val()) || 0;
                const prixAchat = parseFloat($('#prix_achat').val()) || 0;
                const total = quantite * prixAchat;
                
                // Animation du total
                $('#total_display').addClass('animate__animated animate__pulse');
                $('#total_display').val(total.toFixed(2) + ' DH');
                
                setTimeout(function() {
                    $('#total_display').removeClass('animate__animated animate__pulse');
                }, 600);
            }

            $('#quantite, #prix_achat').on('input', calculerTotal);

            // Calcul initial
            calculerTotal();

            // ====================================
            // VALIDATION DYNAMIQUE
            // ====================================
            $('#achatForm').on('submit', function(e) {
                const produitId = $('#produit_id').val();
                const quantite = $('#quantite').val();
                const prixAchat = $('#prix_achat').val();

                if (!produitId || !quantite || !prixAchat) {
                    e.preventDefault();
                    alert('Veuillez remplir tous les champs obligatoires');
                    return false;
                }

                // Animation du bouton submit
                $(this).find('button[type="submit"]').html('<span class="loading-spinner"></span> Enregistrement...');
            });

            // ====================================
            // EFFETS VISUELS
            // ====================================
            $('.form-control, .form-select').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                $(this).parent().removeClass('focused');
            });
        });
    </script>
</x-app-layout>