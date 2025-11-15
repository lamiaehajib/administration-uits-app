<x-app-layout>
    <style>
        /* Les styles du create.blade appliqués pour l'édition */
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
            color: #6b7280; /* Style pour les champs en lecture seule */
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
            /* For the button inside the product-row on mobile */
            flex-shrink: 0;
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

        .total-row-display { /* Nouvelle classe pour le total-row dans la section totale */
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
                padding: 12px 20px; /* Taille plus grande pour mobile */
            }
        }

    </style>

    <div class="edit-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-edit"></i> Modifier le Devis N° {{ $devisf->devis_num }}
                </h2>
                <p class="text-muted mb-0">Mettez à jour les informations de votre devis de formation</p>
            </div>
            <a href="{{ route('devisf.index') }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Annuler
            </a>
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

        <form action="{{ route('devisf.update', $devisf->id) }}" method="POST" id="devis-formation-form">
            @csrf
            @method('PUT')

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales</h3>
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label for="devis_num" class="form-label">Numéro du Devis</label>
                        <input type="text" name="devis_num" class="form-control" value="{{ old('devis_num', $devisf->devis_num) }}" readonly>
                    </div>
                    <div>
                        <label for="date" class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $devisf->date) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Ex: Devis pour la formation en cybersécurité" value="{{ old('titre', $devisf->titre) }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label for="client" class="form-label">Client <span class="required">*</span></label>
                        <input type="text" name="client" class="form-control" placeholder="Nom de l'entreprise/client" value="{{ old('client', $devisf->client) }}" required>
                    </div>
                    <div>
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" name="contact" class="form-control" placeholder="Personne de contact ou email" value="{{ old('contact', $devisf->contact) }}">
                    </div>
                    <div>
                        <label for="ref" class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" placeholder="Votre référence interne" value="{{ old('ref', $devisf->ref) }}">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="vide" class="form-label">Champ "Vide" (Information Supplémentaire)</label>
                    <input type="text" name="vide" class="form-control" value="{{ old('vide', $devisf->vide) }}">
                </div>
            </div>

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <h3 class="section-title">Détails des Formations/Prestations</h3>
                </div>

                <div id="product-container">
                    @foreach($devisf->items as $index => $item)
                        <div class="product-row" data-index="{{ $index }}">
                            <div class="product-number">{{ $index + 1 }}</div>
                            <div class="mb-3">
                                <label for="libele" class="form-label">Libellé de la prestation <span class="required">*</span></label>
                                <textarea name="libele[]" class="form-control" rows="3" required>{{ old("libele.$index", $item->libele) }}</textarea>
                            </div>

                            <div class="grid-4">
                                <div class="col-md-4">
                                    <label for="type" class="form-label">Choisir le type <span class="required">*</span></label>
                                    <select name="type[]" class="form-select type-select" onchange="toggleFields(this); calculatePrixTotal()">
                                        <option value="formation" {{ (old("type.$index") == 'formation' || ($item->nombre == null && $item->nombre_de_jours == null)) ? 'selected' : '' }}>Durée</option>
                                        <option value="nombre" {{ old("type.$index", $item->nombre != null ? 'nombre' : '') == 'nombre' ? 'selected' : '' }}>Nombre de collaborateurs</option>
                                        <option value="nombre_de_jours" {{ old("type.$index", $item->nombre_de_jours != null ? 'nombre_de_jours' : '') == 'nombre_de_jours' ? 'selected' : '' }}>Nombre de jours</option>
                                    </select>
                                </div>
                                <div class="formation-field" style="{{ $item->nombre != null || $item->nombre_de_jours != null ? 'display:none;' : '' }}">
                                    <label for="formation" class="form-label">Durée (en jours ou heures)</label>
                                    <input type="text" name="formation[]" class="form-control" value="{{ old("formation.$index", $item->formation) }}">
                                </div>
                                <div class="nombre-field" style="{{ $item->nombre == null ? 'display:none;' : '' }}">
                                    <label for="nombre" class="form-label">Nombre de collaborateurs</label>
                                    <input type="number" name="nombre[]" class="form-control quantity-field" value="{{ old("nombre.$index", $item->nombre) }}" oninput="calculatePrixTotal()" min="0">
                                </div>
                                <div class="nombre_de_jours-field" style="{{ $item->nombre_de_jours == null ? 'display:none;' : '' }}">
                                    <label for="nombre_de_jours" class="form-label">Nombre de jours</label>
                                    <input type="number" name="nombre_de_jours[]" class="form-control quantity-field" value="{{ old("nombre_de_jours.$index", $item->nombre_de_jours) }}" oninput="calculatePrixTotal()" min="0">
                                </div>
                                <div>
                                    <label for="prix_unitaire" class="form-label">Prix Unitaire <span class="required">*</span></label>
                                    <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" value="{{ old("prix_unitaire.$index", $item->prix_unitaire) }}" oninput="calculatePrixTotal()" min="0" required>
                                </div>
                                <div>
                                    <label for="prix_total" class="form-label">Prix Total</label>
                                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                                </div>
                                <div class="d-flex align-items-end">
                                    <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-add" onclick="addProduct()">
                    <i class="fas fa-plus-circle"></i> Ajouter un produit
                </button>
            </div>

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="section-title">Totaux et Devise</h3>
                </div>

                <div class="grid-2 mb-4">
                    <div>
                        <label for="tva" class="form-label">TVA (%)</label>
                        <select name="tva" class="form-select" onchange="calculateTTC()">
                            <option value="0" {{ old('tva', $devisf->tva == 0 ? '0' : '20') == '0' ? 'selected' : '' }}>Aucune TVA (0%)</option>
                            <option value="20" {{ old('tva', $devisf->tva == 0 ? '0' : '20') == '20' ? 'selected' : '' }}>TVA 20%</option>
                        </select>
                    </div>
                    <div>
                        <label for="currency" class="form-label">Devise</label>
                        <select name="currency" class="form-select">
                            <option value="DH" {{ old('currency', $devisf->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency', $devisf->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                             <option value="CFA" {{ old('currency', $devisf->currency) == 'CFA' ? 'selected' : '' }}>CFA</option>
                        </select>
                    </div>
                </div>

                <div class="total-section">
                    <div class="total-row-display">
                        <span class="total-label">Total Hors Taxes (HT) :</span>
                        <input type="text" name="total_ht" id="total_ht" class="form-control text-end total-value" value="{{ old('total_ht', number_format($devisf->total_ht, 2, '.', '')) }}" readonly>
                    </div>

                    <div class="total-ttc-row d-flex justify-content-between align-items-center">
                        <span class="total-label">Total Toutes Taxes Comprises (TTC) :</span>
                        <input type="text" name="total_ttc" id="total_ttc" class="form-control text-end total-value" value="{{ old('total_ttc', number_format($devisf->total_ttc, 2, '.', '')) }}" readonly style="width: auto;">
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations Importantes</h3>
                </div>

                <div id="important-container">
                    @php $important_infos = old('important', $devisf->ImportantInfof->pluck('info')->toArray() ?: ['']); @endphp
                    @foreach($important_infos as $index => $info)
                        <div class="important-row">
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ $info }}">
                            <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-important mt-2" onclick="addImportant()">
                    <i class="fas fa-plus"></i> Ajouter une autre information
                </button>
            </div>

            <div class="action-buttons">
                <a href="{{ route('devisf.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times-circle"></i> Annuler
                </a>
                <button type="submit" class="btn btn-gradient">
                    <i class="fas fa-save"></i> Mettre à jour le devis
                </button>
            </div>
        </form>
    </div>

    <script>
        let productIndex = {{ $devisf->items->count() }}; // Initialiser l'index pour les nouveaux produits

        function updateProductNumbers() {
            document.querySelectorAll('.product-row').forEach((row, index) => {
                let numberBadge = row.querySelector('.product-number');
                if (numberBadge) {
                    numberBadge.textContent = index + 1;
                    row.setAttribute('data-index', index);
                }
            });
        }

        function calculatePrixTotal() {
            let totalHT = 0;
            let rows = document.querySelectorAll('.product-row');

            rows.forEach(function(row) {
                let unitPriceInput = row.querySelector('.unit-price');
                let totalPriceInput = row.querySelector('.total-price');
                let type = row.querySelector('.type-select').value;

                let unitPrice = parseFloat(unitPriceInput.value) || 0;
                let quantity = 1;

                // Trouver la bonne quantité en fonction du type sélectionné
                if (type === 'nombre') {
                    quantity = parseFloat(row.querySelector('.nombre-field .quantity-field').value) || 0;
                } else if (type === 'nombre_de_jours') {
                    quantity = parseFloat(row.querySelector('.nombre_de_jours-field .quantity-field').value) || 0;
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
            let tva = parseFloat(document.querySelector('select[name="tva"]').value) || 0;
            let totalTTC = totalHT * (1 + tva / 100);
            document.getElementById('total_ttc').value = totalTTC.toFixed(2);
        }

        function addProduct() {
            let productContainer = document.getElementById('product-container');
            let newProductRow = document.createElement('div');
            newProductRow.classList.add('product-row');
            newProductRow.setAttribute('data-index', productIndex++);

            newProductRow.innerHTML = `
                <div class="product-number">${productIndex}</div>
                <div class="mb-3">
                    <label for="libele" class="form-label">Libellé de la prestation <span class="required">*</span></label>
                    <textarea name="libele[]" class="form-control" rows="3" required></textarea>
                </div>
                <div class="grid-4">
                    <div class="col-md-4">
                        <label for="type" class="form-label">Choisir le type <span class="required">*</span></label>
                        <select name="type[]" class="form-select type-select" onchange="toggleFields(this); calculatePrixTotal()">
                            <option value="formation" selected>Durée</option>
                            <option value="nombre">Nombre de collaborateurs</option>
                            <option value="nombre_de_jours">Nombre de jours</option>
                        </select>
                    </div>
                    <div class="formation-field">
                        <label for="formation" class="form-label">Durée (en jours ou heures)</label>
                        <input type="text" name="formation[]" class="form-control">
                    </div>
                    <div class="nombre-field" style="display:none;">
                        <label for="nombre" class="form-label">Nombre de collaborateurs</label>
                        <input type="number" name="nombre[]" class="form-control quantity-field" oninput="calculatePrixTotal()" min="0">
                    </div>
                    <div class="nombre_de_jours-field" style="display:none;">
                        <label for="nombre_de_jours" class="form-label">Nombre de jours</label>
                        <input type="number" name="nombre_de_jours[]" class="form-control quantity-field" oninput="calculatePrixTotal()" min="0">
                    </div>
                    <div>
                        <label for="prix_unitaire" class="form-label">Prix Unitaire <span class="required">*</span></label>
                        <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" min="0" oninput="calculatePrixTotal()" required>
                    </div>
                    <div>
                        <label for="prix_total" class="form-label">Prix Total</label>
                        <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                    </div>
                    <div class="d-flex align-items-end">
                        <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                            <i class="fas fa-trash-alt"></i> Supprimer
                        </button>
                    </div>
                </div>
            `;
            productContainer.appendChild(newProductRow);
            toggleFields(newProductRow.querySelector('.type-select')); // Initialize field visibility
            updateProductNumbers();
        }

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

        function toggleFields(select) {
            const type = select.value;
            const row = select.closest('.product-row');
            const formationField = row.querySelector('.formation-field');
            const nombreField = row.querySelector('.nombre-field');
            const nombreDeJoursField = row.querySelector('.nombre_de_jours-field');

            // Réinitialiser les champs masqués pour qu'ils ne soient pas envoyés
            // On le fait dans la fonction calculate pour gérer la valeur de la quantité, ici on gère l'affichage.

            formationField.style.display = 'none';
            nombreField.style.display = 'none';
            nombreDeJoursField.style.display = 'none';

            // Masquer les inputs des champs non sélectionnés pour ne pas les envoyer
            row.querySelectorAll('.quantity-field, [name="formation[]"]').forEach(input => {
                input.removeAttribute('required');
                // input.value = ''; // Optionnel: pour effacer la valeur quand le champ est masqué
            });


            if (type === 'formation') {
                formationField.style.display = 'block';
                // Assurez-vous que le champ "formation" n'a pas de 'required' car il est souvent optionnel
            } else if (type === 'nombre') {
                nombreField.style.display = 'block';
                nombreField.querySelector('.quantity-field').setAttribute('required', 'required');
            } else if (type === 'nombre_de_jours') {
                nombreDeJoursField.style.display = 'block';
                nombreDeJoursField.querySelector('.quantity-field').setAttribute('required', 'required');
            }
        }

        function addImportant() {
            let container = document.getElementById('important-container');
            let newRow = document.createElement('div');
            newRow.classList.add('important-row');
            newRow.innerHTML = `
                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-remove" onclick="removeImportant(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newRow);
        }

        function removeImportant(button) {
            let importantRows = document.querySelectorAll('.important-row');
            if (importantRows.length > 1) {
                button.closest('.important-row').remove();
            } else {
                // S'il ne reste qu'un seul champ, on ne le supprime pas mais on vide sa valeur
                let input = button.closest('.important-row').querySelector('input[name="important[]"]');
                if (input) {
                    input.value = '';
                }
            }
        }

        // Initialisation à l'ouverture de la page
        document.addEventListener('DOMContentLoaded', function() {
            // Assurer que les styles d'affichage sont corrects au chargement
            document.querySelectorAll('.type-select').forEach(toggleFields);
            // Mettre à jour les numéros (au cas où il y aurait eu des suppressions en cours de session)
            updateProductNumbers();
            // Recalculer les totaux
            calculatePrixTotal();
        });
    </script>
</x-app-layout>