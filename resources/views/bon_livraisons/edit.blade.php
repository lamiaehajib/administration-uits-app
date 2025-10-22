<x-app-layout>
    <style>
        /* Couleurs du Bon de Livraison (Blue/Cyan - pour le différencier du Devis) */
        .gradient-bg-bl {
            /* De #00BCD4 (Cyan) à #03A9F4 (Light Blue) */
            background: linear-gradient(135deg, #00BCD4, #03A9F4);
        }

        .gradient-text-bl {
            background: linear-gradient(135deg, #00BCD4, #03A9F4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        /* Styles de base */
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

        .section-icon-bl {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: linear-gradient(135deg, #00BCD4, #03A9F4); /* Couleur BL */
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

        .form-control, .form-select, textarea.form-control {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 16px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .form-control:focus, .form-select:focus, textarea.form-control:focus {
            border-color: #03A9F4; /* Couleur BL Focus */
            box-shadow: 0 0 0 4px rgba(3, 169, 244, 0.1);
            outline: none;
        }

        .form-control:disabled, .form-control[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
            color: #6b7280;
        }

        textarea.form-control {
            min-height: 80px; /* Taille plus petite pour libellé BL */
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
            border-color: #03A9F4; /* Couleur BL Hover */
            box-shadow: 0 5px 20px rgba(3, 169, 244, 0.1);
        }

        .product-number {
            position: absolute;
            top: -15px;
            left: 20px;
            background: linear-gradient(135deg, #00BCD4, #03A9F4); /* Couleur BL */
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            box-shadow: 0 4px 10px rgba(3, 169, 244, 0.3);
        }
        /* Fin Styles spécifiques aux lignes de produits */

        /* Styles des informations importantes (Couleur jaune/orange pour alerte) */
        .important-item {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 15px !important;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .important-item input {
            flex: 1;
            margin-bottom: 0 !important;
        }
        
        .important-icon {
             color: #f59e0b; 
             font-size: 18px;
        }

        /* Styles des boutons */
        .btn-update {
            background: linear-gradient(135deg, #00BCD4, #03A9F4); /* Couleur BL Principale */
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

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(3, 169, 244, 0.3);
            color: white;
        }

        .btn-add-product {
            background: linear-gradient(135deg, #10b981, #059669); /* Vert pour Ajouter Produit */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-product:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-remove {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            padding: 8px 15px; /* Plus petit pour les items/infos */
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

        .btn-add-important {
            background: linear-gradient(135deg, #f59e0b, #d97706); /* Jaune/Orange pour Ajouter Info Importante */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-important:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.3);
            color: white;
        }
        /* Fin Styles des boutons */

        /* Styles des Totaux */
        .total-section {
            background: linear-gradient(135deg, #eff6ff, #dbeafe); /* Fond bleu clair */
            border: 2px solid #3b82f6;
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
            border-bottom: 1px solid #bfdbfe;
        }

        .total-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .total-label {
            font-size: 16px;
            font-weight: 600;
            color: #1e40af;
        }

        .total-value {
            font-size: 18px;
            font-weight: 700;
            color: #2563eb;
        }

        .total-ttc-row {
            background: linear-gradient(135deg, #00BCD4, #03A9F4); /* Couleur BL Principale */
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
            }
        }
    </style>
    <div class="edit-container px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text-bl mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-truck"></i> Modifier le Bon de Livraison N° {{ $bonLivraison->bon_num }}
                </h2>
                <p class="text-muted mb-0">Mettez à jour les informations du bon de livraison</p>
            </div>
            {{-- <a href="{{ route('bon_livraisons.show', $bonLivraison->id) }}" class="btn btn-cancel">
                <i class="fas fa-arrow-left me-2"></i> Retour au BL
            </a> --}}
        </div>
        
        <form action="{{ route('bon_livraisons.update', $bonLivraison->id) }}" method="POST" id="bon-livraison-form">
            @csrf
            @method('PUT')
            
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon-bl">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="section-title">Informations Générales du Bon</h3>
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label for="bon_num" class="form-label">Bon de Livraison N°</label>
                        <input type="text" name="bon_num" class="form-control" value="{{ old('bon_num', $bonLivraison->bon_num) }}" readonly>
                    </div>
                    <div>
                        <label for="date" class="form-label">Date <span class="required">*</span></label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $bonLivraison->date->format('Y-m-d')) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="titre" class="form-label">Titre <span class="required">*</span></label>
                    <input type="text" name="titre" class="form-control" placeholder="Titre du Bon de Livraison" value="{{ old('titre', $bonLivraison->titre) }}" required>
                </div>

                <div class="grid-3 mb-3">
                    <div>
                        <label for="client" class="form-label">Client <span class="required">*</span></label>
                        <input type="text" name="client" class="form-control" placeholder="Nom du client" value="{{ old('client', $bonLivraison->client) }}" required>
                    </div>
                    <div>
                        <label for="tele" class="form-label">Téléphone</label>
                        <input type="text" name="tele" class="form-control" placeholder="Téléphone" value="{{ old('tele', $bonLivraison->tele) }}">
                    </div>
                    <div>
                        <label for="ice" class="form-label">ICE</label>
                        <input type="text" name="ice" class="form-control" placeholder="ICE" value="{{ old('ice', $bonLivraison->ice) }}">
                    </div>
                </div>
                
                <div class="grid-2 mb-3">
                    <div>
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" name="adresse" class="form-control" placeholder="Adresse de livraison" value="{{ old('adresse', $bonLivraison->adresse) }}">
                    </div>
                    <div>
                        <label for="ref" class="form-label">Référence</label>
                        <input type="text" name="ref" class="form-control" placeholder="Référence interne ou commande" value="{{ old('ref', $bonLivraison->ref) }}">
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon-bl">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="section-title">Produits Livrés</h3>
                </div>

                <div id="product-container">
                    @foreach($bonLivraison->items as $index => $item)
                    <div class="product-row">
                        <div class="product-number">{{ $index + 1 }}</div>
                        
                        <div class="mb-3">
                            <label class="form-label">Libellé <span class="required">*</span></label>
                            <textarea name="libelle[]" class="form-control" required>{{ old("libelle.$index", $item->libelle) }}</textarea>
                        </div>

                        <div class="grid-4">
                            <div>
                                <label class="form-label">Quantité <span class="required">*</span></label>
                                <input type="number" name="quantite[]" class="form-control quantity" value="{{ old("quantite.$index", $item->quantite) }}" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                            </div>
                            <div>
                                <label class="form-label">Prix HT <span class="required">*</span></label>
                                <input type="number" step="0.01" name="prix_ht[]" class="form-control prix_ht" value="{{ old("prix_ht.$index", $item->prix_ht) }}" oninput="calculatePrixTotal()" min="0" required>
                            </div>
                            <div>
                                <label class="form-label">Prix Total</label>
                                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                            </div>
                            <div class="d-flex align-items-end">
                                <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                                    <i class="fas fa-trash me-2"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <button type="button" class="btn btn-add-product mt-3" onclick="addProduct()">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter un Produit
                </button>
            </div>

            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon-bl">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="section-title">Calculs & Totaux</h3>
                </div>

                <div class="grid-2 mb-3">
                    <div>
                        <label for="tva" class="form-label">TVA <span class="required">*</span></label>
                        <select name="tva" class="form-select" onchange="calculateTTC()" required>
                            <option value="0" {{ old('tva', $bonLivraison->tva > 0 ? 20 : 0) == 0 ? 'selected' : '' }}>Aucune TVA (0%)</option>
                            <option value="20" {{ old('tva', $bonLivraison->tva > 0 ? 20 : 0) == 20 ? 'selected' : '' }}>TVA 20%</option>
                        </select>
                    </div>
                    <div></div> 
                </div>

                <div class="total-section">
                    <div class="total-row">
                        <span class="total-label">Total HT (Hors Taxes)</span>
                        <span class="total-value" id="display_total_ht">{{ number_format(old('total_ht', $bonLivraison->total_ht ?? 0), 2, '.', '') }}</span>
                    </div>
                    <div class="total-row">
                        <span class="total-label">Montant TVA</span>
                        <span class="total-value" id="display_tva">
                            @php
                                $tva_rate = old('tva', $bonLivraison->tva ?? 0);
                                $total_ht = old('total_ht', $bonLivraison->total_ht ?? 0);
                                $tva_amount = $total_ht * ($tva_rate / 100);
                                echo number_format($tva_amount, 2, '.', '');
                            @endphp
                        </span>
                    </div>
                    <div class="total-ttc-row">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="total-label" style="font-size: 20px;">
                                <i class="fas fa-coins me-2"></i> Total TTC (Toutes Taxes Comprises)
                            </span>
                            <span class="total-value" id="display_total_ttc">{{ number_format(old('total_ttc', $bonLivraison->total_ttc ?? 0), 2, '.', '') }}</span>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="total_ht" id="total_ht" value="{{ old('total_ht', $bonLivraison->total_ht) }}">
                <input type="hidden" name="total_ttc" id="total_ttc" value="{{ old('total_ttc', $bonLivraison->total_ttc) }}">
            </div>
            
            <div class="form-card">
                <div class="section-header">
                    <div class="section-icon-bl">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h3 class="section-title">Informations Importantes</h3>
                </div>

                <div id="important-container">
                    @foreach($bonLivraison->important ?? [] as $index => $info)
                    <div class="important-item">
                        <i class="fas fa-star important-icon"></i>
                        <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ $info }}">
                        <button type="button" class="btn btn-remove remove-important">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-add-important mt-3" id="add-important">
                    <i class="fas fa-plus-circle me-2"></i> Ajouter une autre information
                </button>
            </div>


            <div class="action-buttons">
                <button type="submit" class="btn btn-update">
                    <i class="fas fa-save me-2"></i> Mettre à jour le Bon de Livraison
                </button>
            </div>

        </form>
    </div>

    <script>
        // Fonction pour mettre à jour les numéros de produit après ajout/suppression
        function updateProductNumbers() {
            document.querySelectorAll('#product-container .product-row').forEach((row, index) => {
                const numberDiv = row.querySelector('.product-number');
                if (numberDiv) {
                    numberDiv.textContent = index + 1;
                }
            });
        }
        
        // Nouvelle fonction pour supprimer un produit
        function removeProduct(button) {
            button.closest('.product-row').remove();
            calculatePrixTotal(); // Recalculer les totaux après suppression
            updateProductNumbers(); // Mettre à jour les numéros
        }

        // Nouvelle fonction pour supprimer une info importante (similaire au Devis)
        function removeImportant(button) {
            button.closest('.important-item').remove();
        }

        // La fonction calculatePrixTotal est modifiée pour inclure l'affichage des totaux
        function calculatePrixTotal() {
            let totalHT = 0;
            let rows = document.querySelectorAll('.product-row');

            rows.forEach(function(row) {
                let quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                let unitPrice = parseFloat(row.querySelector('.prix_ht').value) || 0;
                let totalPriceField = row.querySelector('.total-price');

                // Calculate total price for this product
                let prixTotal = quantity * unitPrice;
                totalPriceField.value = prixTotal.toFixed(2);

                totalHT += prixTotal;
            });

            // Update hidden and display total HT
            document.getElementById('total_ht').value = totalHT.toFixed(2);
            document.getElementById('display_total_ht').textContent = totalHT.toFixed(2);

            // Recalculate total TTC
            calculateTTC();
        }

        function calculateTTC() {
            let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
            let tvaRate = parseFloat(document.querySelector('select[name="tva"]').value) || 0;
            let tvaAmount = totalHT * (tvaRate / 100);
            let totalTTC = totalHT + tvaAmount;

            // Update hidden and display total TTC
            document.getElementById('total_ttc').value = totalTTC.toFixed(2);
            document.getElementById('display_total_ttc').textContent = totalTTC.toFixed(2);

            // Update display TVA amount
            document.getElementById('display_tva').textContent = tvaAmount.toFixed(2);
        }

        function addProduct() {
            let productContainer = document.getElementById('product-container');
            let newProductRow = document.createElement('div');
            newProductRow.classList.add('product-row');
            
            // Get the current number of products for the new product number
            const newIndex = productContainer.children.length + 1;

            newProductRow.innerHTML = `
                <div class="product-number">${newIndex}</div>
                <div class="mb-3">
                    <label class="form-label">Libellé <span class="required">*</span></label>
                    <textarea name="libelle[]" class="form-control" required></textarea>
                </div>
                <div class="grid-4">
                    <div>
                        <label class="form-label">Quantité <span class="required">*</span></label>
                        <input type="number" name="quantite[]" class="form-control quantity" oninput="calculatePrixTotal()" min="0" step="0.01" required>
                    </div>
                    <div>
                        <label class="form-label">Prix HT <span class="required">*</span></label>
                        <input type="number" step="0.01" name="prix_ht[]" class="form-control prix_ht" oninput="calculatePrixTotal()" min="0" required>
                    </div>
                    <div>
                        <label class="form-label">Prix Total</label>
                        <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                    </div>
                    <div class="d-flex align-items-end">
                        <button type="button" class="btn btn-remove w-100" onclick="removeProduct(this)">
                            <i class="fas fa-trash me-2"></i> Supprimer
                        </button>
                    </div>
                </div>
            `;
            productContainer.appendChild(newProductRow);
            // Pas besoin de mettre à jour les numéros ici car on a calculé l'index
        }

        document.getElementById('add-important').addEventListener('click', function() {
            let container = document.getElementById('important-container');
            let newItem = document.createElement('div');
            newItem.classList.add('important-item');
            newItem.innerHTML = `
                <i class="fas fa-star important-icon"></i>
                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-remove remove-important" onclick="removeImportant(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newItem);
        });

        // La logique d'écoute d'événement pour la suppression de produit et d'information a été simplifiée
        // en utilisant les fonctions `removeProduct(this)` et `removeImportant(this)` directement dans le HTML généré.
        
        // Initialize calculations on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Assurez-vous que Font Awesome est inclus pour les icônes (fas fa-...)
            calculatePrixTotal(); 
            updateProductNumbers(); // S'assurer que les numéros sont corrects au chargement
        });
    </script>
</x-app-layout>