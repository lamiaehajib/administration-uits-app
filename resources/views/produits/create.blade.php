<x-app-layout>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <!-- Header Card -->
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-header bg-gradient-primary text-white py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="mb-1 text-white">
                                    <i class="fas fa-plus-circle me-2"></i>Ajouter un nouveau produit
                                </h3>
                                <p class="mb-0 opacity-75">Remplissez les informations du produit</p>
                            </div>
                            <a href="{{ route('produits.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Messages d'erreur -->
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Erreurs de validation :</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Formulaire -->
                        <form action="{{ route('produits.store') }}" method="POST" id="productForm">
                            @csrf

                            <!-- Section Informations de base -->
                            <div class="form-section mb-4">
                                <h5 class="section-title mb-3">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    Informations de base
                                </h5>
                                <div class="row g-3">
                                    <!-- Nom -->
                                    <div class="col-md-6">
                                        <label for="nom" class="form-label required">
                                            <i class="fas fa-tag me-1"></i>Nom du produit
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg @error('nom') is-invalid @enderror" 
                                               id="nom" 
                                               name="nom" 
                                               value="{{ old('nom') }}" 
                                               placeholder="Ex: Produit ABC"
                                               required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Référence (Auto-générée) -->
                                    <div class="col-md-6">
                                        <label for="reference" class="form-label">
                                            <i class="fas fa-barcode me-1"></i>Référence
                                            <span class="badge bg-info ms-2">Auto-générée</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-hashtag"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control form-control-lg bg-light" 
                                                   id="reference" 
                                                   name="reference" 
                                                   value="{{ old('reference') }}" 
                                                   placeholder="Sera générée automatiquement"
                                                   readonly>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            La référence sera générée automatiquement
                                        </small>
                                    </div>

                                    <!-- Catégorie -->
                                    <div class="col-md-12">
                                        <label for="category_id" class="form-label required">
                                            <i class="fas fa-folder-open me-1"></i>Catégorie
                                        </label>
                                        <select class="form-select form-select-lg select2 @error('category_id') is-invalid @enderror" 
                                                id="category_id" 
                                                name="category_id" 
                                                required>
                                            <option value="">-- Sélectionnez une catégorie --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->nom }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="col-12">
                                        <label for="description" class="form-label">
                                            <i class="fas fa-align-left me-1"></i>Description
                                        </label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="4" 
                                                  placeholder="Décrivez le produit...">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Section Prix -->
                            <div class="form-section mb-4">
                                <h5 class="section-title mb-3">
                                    <i class="fas fa-dollar-sign text-success me-2"></i>
                                    Informations de prix
                                </h5>
                                <div class="row g-3">
                                    <!-- Prix d'achat -->
                                    <div class="col-md-6">
                                        <label for="prix_achat" class="form-label">
                                            <i class="fas fa-shopping-cart me-1"></i>Prix d'achat (DH)
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" 
                                                   class="form-control @error('prix_achat') is-invalid @enderror" 
                                                   id="prix_achat" 
                                                   name="prix_achat" 
                                                   value="{{ old('prix_achat', 0) }}" 
                                                   step="0.01" 
                                                   min="0"
                                                   placeholder="0.00">
                                            <span class="input-group-text">DH</span>
                                        </div>
                                        @error('prix_achat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Prix de vente -->
                                    <div class="col-md-6">
                                        <label for="prix_vente" class="form-label required">
                                            <i class="fas fa-money-bill-wave me-1"></i>Prix de vente (DH)
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <input type="number" 
                                                   class="form-control @error('prix_vente') is-invalid @enderror" 
                                                   id="prix_vente" 
                                                   name="prix_vente" 
                                                   value="{{ old('prix_vente') }}" 
                                                   step="0.01" 
                                                   min="0"
                                                   placeholder="0.00"
                                                   required>
                                            <span class="input-group-text">DH</span>
                                        </div>
                                        @error('prix_vente')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Affichage de la marge -->
                                    <div class="col-12">
                                        <div class="alert alert-info d-flex align-items-center" id="margeInfo" style="display: none !important;">
                                            <i class="fas fa-chart-line fa-2x me-3"></i>
                                            <div>
                                                <strong>Marge calculée :</strong>
                                                <span id="margeMontant" class="fs-5 ms-2">0.00 DH</span>
                                                <span class="badge bg-primary ms-2" id="margePourcentage">0%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Section Stock -->
                            <div class="form-section mb-4">
                                <h5 class="section-title mb-3">
                                    <i class="fas fa-boxes text-warning me-2"></i>
                                    Gestion du stock
                                </h5>
                                <div class="row g-3">
                                    <!-- Quantité en stock -->
                                    <div class="col-md-6">
                                        <label for="quantite_stock" class="form-label required">
                                            <i class="fas fa-cubes me-1"></i>Quantité en stock
                                        </label>
                                        <input type="number" 
                                               class="form-control form-control-lg @error('quantite_stock') is-invalid @enderror" 
                                               id="quantite_stock" 
                                               name="quantite_stock" 
                                               value="{{ old('quantite_stock', 0) }}" 
                                               min="0"
                                               placeholder="0"
                                               required>
                                        @error('quantite_stock')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Stock d'alerte -->
                                    <div class="col-md-6">
                                        <label for="stock_alerte" class="form-label">
                                            <i class="fas fa-bell me-1"></i>Seuil d'alerte
                                        </label>
                                        <input type="number" 
                                               class="form-control form-control-lg @error('stock_alerte') is-invalid @enderror" 
                                               id="stock_alerte" 
                                               name="stock_alerte" 
                                               value="{{ old('stock_alerte', 10) }}" 
                                               min="0"
                                               placeholder="10">
                                        @error('stock_alerte')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Vous serez alerté quand le stock atteint ce seuil
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Section Statut -->
                            <div class="form-section mb-4">
                                <h5 class="section-title mb-3">
                                    <i class="fas fa-toggle-on text-info me-2"></i>
                                    Statut
                                </h5>
                                <div class="form-check form-switch form-check-lg">
    <input class="form-check-input" 
           type="checkbox" 
           role="switch" 
           id="actif" 
           name="actif" 
           value="1"
           checked> <!-- ✅ checked by default -->
    <label class="form-check-label" for="actif">
        <i class="fas fa-check-circle text-success me-1"></i>
        Produit actif (visible dans les ventes)
    </label>
</div>
                            </div>

                            <!-- Boutons d'action -->
                            <div class="row mt-5">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between gap-2">
                                        <a href="{{ route('produits.index') }}" class="btn btn-secondary btn-lg px-4">
                                            <i class="fas fa-times me-2"></i>Annuler
                                        </a>
                                        <button type="submit" class="btn btn-primary btn-lg px-5">
                                            <i class="fas fa-save me-2"></i>Enregistrer le produit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #C2185B, #D32F2F) !important;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card-header {
            border: none;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-label.required::after {
            content: " *";
            color: #dc3545;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.15);
        }

        .form-control-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .input-group-text {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .section-title {
            font-weight: 700;
            color: #2c3e50;
            padding-bottom: 10px;
            border-bottom: 3px solid #f0f0f0;
        }

        .form-section {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #f0f0f0;
        }

        .btn-lg {
            padding: 12px 30px;
            font-size: 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #a01750, #b71c1c);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(194, 24, 91, 0.3);
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .form-check-input {
            width: 3rem;
            height: 1.5rem;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        hr {
            border-top: 2px solid #f0f0f0;
            opacity: 1;
        }

        /* Select2 custom styling */
        .select2-container--default .select2-selection--single {
            height: calc(3rem + 2px);
            border: 2px solid #e0e0e0;
            border-radius: 8px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 3rem;
            padding-left: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 3rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }

            .btn-lg {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            // Initialiser Select2
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Sélectionnez une catégorie --'
            });

            // Calcul automatique de la marge
            function calculateMarge() {
                const prixAchat = parseFloat($('#prix_achat').val()) || 0;
                const prixVente = parseFloat($('#prix_vente').val()) || 0;

                if (prixAchat > 0 && prixVente > 0) {
                    const marge = prixVente - prixAchat;
                    const margePourcentage = ((marge / prixAchat) * 100).toFixed(2);

                    $('#margeMontant').text(marge.toFixed(2) + ' DH');
                    $('#margePourcentage').text(margePourcentage + '%');
                    
                    // Changer la couleur selon la marge
                    if (margePourcentage < 10) {
                        $('#margePourcentage').removeClass('bg-primary bg-success').addClass('bg-danger');
                    } else if (margePourcentage < 30) {
                        $('#margePourcentage').removeClass('bg-danger bg-success').addClass('bg-primary');
                    } else {
                        $('#margePourcentage').removeClass('bg-danger bg-primary').addClass('bg-success');
                    }

                    $('#margeInfo').show();
                } else {
                    $('#margeInfo').hide();
                }
            }

            // Événements pour calculer la marge
            $('#prix_achat, #prix_vente').on('input', calculateMarge);

            // Validation du formulaire
            $('#productForm').on('submit', function(e) {
                const prixVente = parseFloat($('#prix_vente').val()) || 0;
                
                if (prixVente <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Le prix de vente doit être supérieur à 0',
                        confirmButtonColor: '#D32F2F'
                    });
                    return false;
                }
            });

            // Animation au chargement
            $('.form-section').hide().fadeIn(800);
        });
    </script>
</x-app-layout>