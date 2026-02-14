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

        .form-label.fw-bold {
             font-weight: 600 !important;
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
        
        .page-header h3 i {
             background: transparent;
             color: #fff;
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
            padding: 12px 18px;
        }

        #total_display:focus {
            box-shadow: var(--shadow-lg);
        }

        /* Champ Marge calculée automatiquement */
        #marge_display {
            background: linear-gradient(135deg, #4CAF50, #388E3C);
            color: #fff;
            font-size: 18px;
            font-weight: 700;
            text-align: center;
            border: none;
            box-shadow: var(--shadow-md);
            letter-spacing: 1px;
        }

        #marge_display:focus {
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
        .alert-info {
             background: linear-gradient(135deg, rgba(3, 169, 244, 0.05), rgba(0, 188, 212, 0.05)) !important;
             border-left: 4px solid #03A9F4 !important;
             padding: 20px;
             border-radius: 10px;
             margin-bottom: 25px !important;
             box-shadow: var(--shadow-sm);
             animation: fadeInUp 0.7s ease 0.3s backwards;
        }

        .alert-info .alert-heading {
            color: #03A9F4;
            font-weight: 700;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-info strong {
            color: #2c3e50;
        }
        
        .alert-warning {
             background: linear-gradient(135deg, rgba(255, 193, 7, 0.05), rgba(255, 152, 0, 0.05)) !important;
             border-left: 4px solid #FFC107 !important;
             padding: 20px;
             border-radius: 10px;
             box-shadow: var(--shadow-sm);
        }
        
        .alert-warning i {
            color: #FFC107;
            margin-right: 5px;
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

            .d-flex.justify-content-between.mt-4.pt-3.border-top {
                flex-direction: column;
                gap: 15px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>

    <div class="container-fluid">
        <div class="page-header">
            <h3>
                <i class="fas fa-edit"></i> Modifier l'Achat
            </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('achats.index') }}">Achats</a></li>
                    <li class="breadcrumb-item active">Modifier</li>
                </ol>
            </nav>
        </div>

        <div class="alert alert-info mb-4">
            <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Informations Actuelles</h5>
            <div class="row">
                <div class="col-md-6">
                    <strong>Produit:</strong> {{ $achat->produit->nom }}<br>
                    <strong>Quantité actuelle:</strong> {{ $achat->quantite }}<br>
                    <strong>Stock produit:</strong> {{ $achat->produit->quantite_stock }}
                </div>
                <div class="col-md-6">
                    <strong>Prix d'achat:</strong> {{ number_format($achat->prix_achat, 2) }} DH<br>
                    <strong>Prix de vente:</strong> {{ number_format($achat->prix_vente_suggere, 2) }} DH<br>
                    <strong>Marge:</strong> {{ number_format($achat->marge_pourcentage, 2) }}%
                </div>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('achats.update', $achat) }}" method="POST" id="achatForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-folder"></i> Catégorie
                            </label>
                            <select id="category_id" class="form-select select2">
                                <option value="">-- Sélectionner une catégorie --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ $achat->produit->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->nom }} ({{ $category->produits->count() }} produits)
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Filtrer les produits par catégorie</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-box"></i> Produit <span class="text-danger">*</span>
                            </label>
                            <select name="produit_id" id="produit_id" class="form-select select2 @error('produit_id') is-invalid @enderror" required>
                                <option value="{{ $achat->produit->id }}" selected data-category="{{ $achat->produit->category_id }}">
                                    {{ $achat->produit->nom }} (Stock: {{ $achat->produit->quantite_stock }})
                                </option>
                                @foreach($categories as $category)
                                    @foreach($category->produits as $produit)
                                        @if($produit->id != $achat->produit->id)
                                            <option value="{{ $produit->id }}" 
                                                    data-category="{{ $category->id }}"
                                                    {{ old('produit_id', $achat->produit_id) == $produit->id ? 'selected' : '' }}>
                                                {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                                            </option>
                                        @endif
                                    @endforeach
                                @endforeach
                            </select>
                            @error('produit_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-truck"></i> Fournisseur
                            </label>
                            <input type="text" name="fournisseur" id="fournisseur" 
                                    class="form-control @error('fournisseur') is-invalid @enderror" 
                                    value="{{ old('fournisseur', $achat->fournisseur) }}"
                                    placeholder="Nom du fournisseur">
                            @error('fournisseur')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-file-invoice"></i> N° Bon de commande
                            </label>
                            <input type="text" name="numero_bon" id="numero_bon" 
                                    class="form-control @error('numero_bon') is-invalid @enderror" 
                                    value="{{ old('numero_bon', $achat->numero_bon) }}"
                                    placeholder="Ex: BC-2024-001">
                            @error('numero_bon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-cubes"></i> Quantité <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="quantite" id="quantite" 
                                    class="form-control @error('quantite') is-invalid @enderror" 
                                    value="{{ old('quantite', $achat->quantite) }}" 
                                    min="1" required>
                            @error('quantite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Quantité précédente: {{ $achat->quantite }}</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-dollar-sign"></i> Prix d'achat (DH) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="prix_achat" id="prix_achat" 
                                    class="form-control @error('prix_achat') is-invalid @enderror" 
                                    value="{{ old('prix_achat', $achat->prix_achat) }}" 
                                    min="0" required>
                            @error('prix_achat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ✅ NOUVEAU: Prix de Vente (ENTRÉE MANUELLE) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-tag"></i> Prix de vente (DH) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" name="prix_vente_suggere" id="prix_vente_suggere" 
                                   class="form-control @error('prix_vente_suggere') is-invalid @enderror" 
                                   value="{{ old('prix_vente_suggere', $achat->prix_vente_suggere) }}" min="0" required>
                            @error('prix_vente_suggere')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Entrez le prix de vente souhaité</small>
                        </div>

                        <!-- ✅ NOUVEAU: Marge % (AUTO-CALCULÉE) -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-percentage"></i> Marge (%)
                            </label>
                            <input type="text" id="marge_display" class="form-control" readonly value="0.00%">
                            <small class="text-muted">Calculée automatiquement</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-money-bill-wave"></i> Total (DH)
                            </label>
                            <input type="text" id="total_display" class="form-control fw-bold" readonly value="0.00 DH">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar"></i> Date d'achat <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="date_achat" id="date_achat" 
                                    class="form-control @error('date_achat') is-invalid @enderror" 
                                    value="{{ old('date_achat', $achat->date_achat?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                            @error('date_achat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-warehouse"></i> Gestion du Stock
                            </label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="update_stock" 
                                        id="update_stock" value="1" {{ old('update_stock', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="update_stock">
                                    Mettre à jour le stock automatiquement
                                </label>
                            </div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Si la quantité change et que cette option est cochée, le stock sera ajusté
                            </small>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">
                                <i class="fas fa-sticky-note"></i> Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3" 
                                        class="form-control @error('notes') is-invalid @enderror"
                                        placeholder="Notes supplémentaires...">{{ old('notes', $achat->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-warning mt-4" id="warningChange" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Attention:</strong> La modification de la quantité ajustera le stock du produit si l'option est cochée.
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top action-buttons">
                        <a href="{{ route('achats.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Mettre à Jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const quantiteOriginale = {{ $achat->quantite }};
            const produitIdOriginal = '{{ $achat->produit->id }}';

            // ====================================
            // ✅ NOUVEAU: Calculer la marge
            // ====================================
            function calculerMarge() {
                const prixAchat = parseFloat($('#prix_achat').val()) || 0;
                const prixVente = parseFloat($('#prix_vente_suggere').val()) || 0;
                
                if (prixAchat > 0) {
                    const margePct = ((prixVente - prixAchat) / prixAchat) * 100;
                    const margeDh = prixVente - prixAchat;
                    
                    $('#marge_display').val(margePct.toFixed(2) + '%');
                    $('#marge_display').attr('title', 'Marge: ' + margeDh.toFixed(2) + ' DH');
                } else {
                    $('#marge_display').val('0.00%');
                }
            }
            
            // Calcul initial
            calculerMarge();
            
            // Déclencher le calcul quand les prix changent
            $('#prix_achat, #prix_vente_suggere').on('input', function() {
                calculerMarge();
                calculerTotal();
            });

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
                const selectedProduitId = $produitSelect.val();

                $produitSelect.find('option').each(function() {
                    const $option = $(this);
                    const optionCategoryId = $option.data('category');
                    const optionId = $option.val();

                    if (optionId === '' || optionId === produitIdOriginal || optionCategoryId == categoryId) {
                        $option.show();
                    } else {
                        $option.hide();
                    }
                });
                
                $produitSelect.trigger('change');
            });

            // ====================================
            // CALCUL DU TOTAL et Avertissement
            // ====================================
            function calculerTotal() {
                const quantite = parseFloat($('#quantite').val()) || 0;
                const prixAchat = parseFloat($('#prix_achat').val()) || 0;
                const total = quantite * prixAchat;
                
                $('#total_display').val(total.toFixed(2) + ' DH');
                
                if (quantite !== quantiteOriginale) {
                    $('#warningChange').slideDown();
                } else {
                    $('#warningChange').slideUp();
                }
            }

            $('#quantite').on('input', calculerTotal);

            // Calcul initial
            calculerTotal();
            
            // Appliquer le filtre initial
            const initialCategoryId = $('#category_id').val();
            if(initialCategoryId) {
                 $('#category_id').trigger('change');
            }
        });
    </script>
</x-app-layout>