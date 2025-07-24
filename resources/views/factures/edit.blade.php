<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Modifier la Facture N° {{ $facture->facture_num }}</h2>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
    
                <form action="{{ route('factures.update', $facture->id) }}" method="POST" id="facture-form">
                    @csrf
                    @method('PUT')
    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="facture_num" class="form-label">Numéro de la Facture</label>
                            <input type="text" name="facture_num" class="form-control" value="{{ old('facture_num', $facture->facture_num) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="afficher_cachet" class="form-label">Afficher le cachet ?</label>
                            <select name="afficher_cachet" class="form-control">
                                <option value="1" {{ old('afficher_cachet', $facture->afficher_cachet) == 1 ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ old('afficher_cachet', $facture->afficher_cachet) == 0 ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                    </div>
    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', $facture->date) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" name="titre" class="form-control" value="{{ old('titre', $facture->titre) }}" required>
                        </div>
                    </div>
    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="client" class="form-label">Client</label>
                            <input type="text" name="client" class="form-control" value="{{ old('client', $facture->client) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="ice" class="form-label">ICE</label>
                            <input type="text" name="ice" class="form-control" value="{{ old('ice', $facture->ice) }}">
                        </div>
                    </div>
    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $facture->adresse) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="ref" class="form-label">Référence</label>
                            <input type="text" name="ref" class="form-control" value="{{ old('ref', $facture->ref) }}">
                        </div>
                    </div>
    
                    <!-- Section Produits -->
                    <h4 class="mt-4">Produits</h4>
                    <div id="product-container">
                        @foreach($facture->items as $index => $item)
                        <div class="row align-items-end mb-3 product-row">
                            <div class="col-md-3">
                                <label for="libele" class="form-label">Libellé</label>
                                <textarea name="libele[]" class="form-control" rows="3" required>{{ old("libele.$index", $item->libele) }}</textarea>
                            </div>
                            <div class="col-md-2">
                                <label for="quantite" class="form-label">Quantité</label>
                                <input type="number" name="quantite[]" class="form-control quantity" min="0" step="0.01" value="{{ old("quantite.$index", $item->quantite) }}" oninput="calculatePrixTotal()" required>
                            </div>
                            <div class="col-md-2">
                                <label for="prix_ht" class="form-label">Prix HT</label>
                                <input type="number" name="prix_ht[]" class="form-control prix_ht" min="0" step="0.01" value="{{ old("prix_ht.$index", $item->prix_ht) }}" oninput="calculatePrixTotal()" required>
                            </div>
                            <div class="col-md-2">
                                <label for="prix_total" class="form-label">Prix Total</label>
                                <input type="number" name="prix_total[]" class="form-control total-price" step="0.01" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-danger btn-sm mt-4 remove-product">Supprimer</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-info btn-sm mb-3" onclick="addProduct()">+ Ajouter un produit</button>
    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="total_ht" class="form-label">Total HT</label>
                            <input type="number" name="total_ht" class="form-control" id="total_ht" step="0.01" value="{{ old('total_ht', $facture->total_ht) }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="tva" class="form-label">TVA (%)</label>
                            <select name="tva" class="form-control" onchange="calculateTTC()">
                                <option value="0" {{ old('tva', $facture->tva > 0 ? 20 : 0) == 0 ? 'selected' : '' }}>Aucune TVA</option>
                                <option value="20" {{ old('tva', $facture->tva > 0 ? 20 : 0) == 20 ? 'selected' : '' }}>TVA 20%</option>
                            </select>
                        </div>
                    </div>
    
                    <div class="mb-3">
                        <label for="total_ttc" class="form-label">Total TTC</label>
                        <input type="number" name="total_ttc" class="form-control" id="total_ttc" step="0.01" value="{{ old('total_ttc', $facture->total_ttc) }}" readonly>
                    </div>
    
                    <div class="mb-3">
                        <label for="currency" class="form-label">Devise</label>
                        <select name="currency" class="form-control">
                            <option value="DH" {{ old('currency', $facture->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                            <option value="EUR" {{ old('currency', $facture->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                        </select>
                    </div>
    
                    <!-- Informations Importantes -->
                    <h4 class="mt-4">Informations Importantes</h4>
                    <div id="important-container">
                        @foreach($facture->importantInfoo as $important )
                        <div class="mb-3 d-flex align-items-center important-item">
                            <input type="text" name="important[]" class="form-control me-2" value="{{ old("important.$index", $important->info) }}" placeholder="Ajouter une information importante">
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
    
            let prixTotal = quantity * unitPrice;
            totalPrice.value = prixTotal.toFixed(2);
            totalHT += prixTotal;
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
        newProductRow.classList.add('row', 'align-items-end', 'mb-3', 'product-row');
        newProductRow.innerHTML = `
            <div class="col-md-3">
                <label for="libele" class="form-label">Libellé</label>
                <textarea name="libele[]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="col-md-2">
                <label for="quantite" class="form-label">Quantité</label>
                <input type="number" name="quantite[]" class="form-control quantity" min="0" step="0.01" oninput="calculatePrixTotal()" required>
            </div>
            <div class="col-md-2">
                <label for="prix_ht" class="form-label">Prix HT</label>
                <input type="number" name="prix_ht[]" class="form-control prix_ht" min="0" step="0.01" oninput="calculatePrixTotal()" required>
            </div>
            <div class="col-md-2">
                <label for="prix_total" class="form-label">Prix Total</label>
                <input type="number" name="prix_total[]" class="form-control total-price" step="0.01" readonly>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-danger btn-sm mt-4 remove-product">Supprimer</button>
            </div>
        `;
        productContainer.appendChild(newProductRow);
    }
    
    function removeProduct(button) {
        let productRows = document.querySelectorAll('.product-row');
        if (productRows.length > 1) {
            button.closest('.product-row').remove();
            calculatePrixTotal();
        } else {
            alert('Vous devez conserver au moins un produit.');
        }
    }
    
    document.getElementById('add-important').addEventListener('click', function() {
        let container = document.getElementById('important-container');
        let newItem = document.createElement('div');
        newItem.classList.add('mb-3', 'd-flex', 'align-items-center', 'important-item');
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
        if (e.target && e.target.classList.contains('remove-product')) {
            removeProduct(e.target);
        }
    });
    </script>
    </x-app-layout>




