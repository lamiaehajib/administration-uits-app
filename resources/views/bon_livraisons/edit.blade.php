<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Modifier le Bon de Livraison N° {{ $bonLivraison->bon_num }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('bon_livraisons.update', $bonLivraison->id) }}" method="POST" id="bon-livraison-form">
                    @csrf
                    @method('PUT')

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="bon_num" class="form-label">Bon de Livraison N°</label>
                            <input type="text" name="bon_num" class="form-control" value="{{ old('bon_num', $bonLivraison->bon_num) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', $bonLivraison->date->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" name="titre" class="form-control" value="{{ old('titre', $bonLivraison->titre) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="client" class="form-label">Client</label>
                            <input type="text" name="client" class="form-control" value="{{ old('client', $bonLivraison->client) }}" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="tele" class="form-label">Téléphone</label>
                            <input type="text" name="tele" class="form-control" value="{{ old('tele', $bonLivraison->tele) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="ice" class="form-label">ICE</label>
                            <input type="text" name="ice" class="form-control" value="{{ old('ice', $bonLivraison->ice) }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $bonLivraison->adresse) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="ref" class="form-label">Référence</label>
                            <input type="text" name="ref" class="form-control" value="{{ old('ref', $bonLivraison->ref) }}">
                        </div>
                    </div>

                    <!-- Section Produits -->
                    <h4 class="mt-4">Produits</h4>
                    <div id="product-container">
                        @foreach($bonLivraison->items as $item)
                        <div class="row align-items-end mb-3 product-row">
                            <div class="col-md-4">
                                <label for="libelle" class="form-label">Libellé</label>
                                <textarea name="libelle[]" class="form-control" required>{{ old('libelle.', $item->libelle) }}</textarea>
                            </div>
                            <div class="col-md-2">
                                <label for="quantite" class="form-label">Quantité</label>
                                <input type="number" name="quantite[]" class="form-control quantity" value="{{ old('quantite.', $item->quantite) }}" oninput="calculatePrixTotal()" required>
                            </div>
                            <div class="col-md-3">
                                <label for="prix_ht" class="form-label">Prix HT</label>
                                <input type="number" name="prix_ht[]" class="form-control prix_ht" value="{{ old('prix_ht.', $item->prix_ht) }}" oninput="calculatePrixTotal()" required>
                            </div>
                            <div class="col-md-3">
                                <label for="prix_total" class="form-label">Prix Total</label>
                                <input type="number" name="prix_total[]" class="form-control total-price" value="{{ old('prix_total.', $item->prix_total) }}" readonly>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-info btn-sm mb-3" onclick="addProduct()">+ Ajouter un produit</button>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_ht" class="form-label">Total HT</label>
                            <input type="number" name="total_ht" class="form-control" id="total_ht" value="{{ old('total_ht', $bonLivraison->total_ht) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="tva" class="form-label">TVA (%)</label>
                            <select name="tva" class="form-control" onchange="calculateTTC()">
                                <option value="0" {{ old('tva', $bonLivraison->tva > 0 ? 20 : 0) == 0 ? 'selected' : '' }}>Aucune TVA</option>
                                <option value="20" {{ old('tva', $bonLivraison->tva > 0 ? 20 : 0) == 20 ? 'selected' : '' }}>TVA 20%</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="total_ttc" class="form-label">Total TTC</label>
                        <input type="number" name="total_ttc" class="form-control" id="total_ttc" value="{{ old('total_ttc', $bonLivraison->total_ttc) }}" readonly>
                    </div>

                    <!-- Informations Importantes -->
                    <h4>Informations Importantes</h4>
                    <div id="important-container">
                        @foreach($bonLivraison->important ?? [] as $info)
                        <div class="mb-3 d-flex align-items-center important-item">
                            <input type="text" name="important[]" class="form-control me-2" value="{{ $info }}">
                            <button type="button" class="btn btn-danger btn-sm remove-important">Supprimer</button>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-primary btn-sm mb-4" id="add-important">Ajouter une autre information</button>

                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function calculatePrixTotal() {
            let totalHT = 0;
            let rows = document.querySelectorAll('.product-row');

            rows.forEach(function(row) {
                let quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                let unitPrice = parseFloat(row.querySelector('.prix_ht').value) || 0;
                let totalPrice = row.querySelector('.total-price');

                // Calculate total price for this product
                let prixTotal = quantity * unitPrice;
                totalPrice.value = prixTotal.toFixed(2);

                totalHT += prixTotal;
            });

            // Update total HT
            document.getElementById('total_ht').value = totalHT.toFixed(2);

            // Recalculate total TTC
            calculateTTC();
        }

        function calculateTTC() {
            let totalHT = parseFloat(document.getElementById('total_ht').value) || 0;
            let tva = parseFloat(document.querySelector('select[name="tva"]').value) || 0;
            let totalTTC = totalHT * (1 + tva / 100);

            // Update total TTC field
            document.getElementById('total_ttc').value = totalTTC.toFixed(2);
        }

        function addProduct() {
            let productContainer = document.getElementById('product-container');
            let newProductRow = document.createElement('div');
            newProductRow.classList.add('product-row', 'row', 'align-items-end', 'mb-3');
            newProductRow.innerHTML = `
                <div class="col-md-4">
                    <label for="libelle" class="form-label">Libellé</label>
                    <textarea name="libelle[]" class="form-control" required></textarea>
                </div>
                <div class="col-md-2">
                    <label for="quantite" class="form-label">Quantité</label>
                    <input type="number" name="quantite[]" class="form-control quantity" oninput="calculatePrixTotal()" required>
                </div>
                <div class="col-md-3">
                    <label for="prix_ht" class="form-label">Prix HT</label>
                    <input type="number" name="prix_ht[]" class="form-control prix_ht" oninput="calculatePrixTotal()" required>
                </div>
                <div class="col-md-3">
                    <label for="prix_total" class="form-label">Prix Total</label>
                    <input type="number" name="prix_total[]" class="form-control total-price" readonly>
                </div>
            `;
            productContainer.appendChild(newProductRow);
        }

        document.getElementById('add-important').addEventListener('click', function() {
            let container = document.getElementById('important-container');
            let newItem = document.createElement('div');
            newItem.classList.add('important-item', 'mb-3', 'd-flex', 'align-items-center');
            newItem.innerHTML = `
                <input type="text" name="important[]" class="form-control me-2" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-danger btn-sm remove-important">Supprimer</button>
            `;
            container.appendChild(newItem);
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-important')) {
                e.target.closest('.important-item').remove();
            }
        });

        // Initialize calculations on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculatePrixTotal();
        });
    </script>
</x-app-layout>