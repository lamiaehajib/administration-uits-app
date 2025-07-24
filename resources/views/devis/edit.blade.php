<x-app-layout>
    <div class="container">
        <h1>Modifier le Devis N° {{ $devis->devis_num }}</h1>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <form action="{{ route('devis.update', $devis->id) }}" method="POST" id="devis-form">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="devis_num">Numéro du devis</label>
                <input type="text" name="devis_num" class="form-control" value="{{ old('devis_num', $devis->devis_num) }}" readonly>
            </div>
            
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $devis->date) }}" required>
            </div>
            
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" class="form-control" value="{{ old('titre', $devis->titre) }}" required>
            </div>
            
            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" name="client" class="form-control" value="{{ old('client', $devis->client) }}" required>
            </div>
            
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact', $devis->contact) }}">
            </div>
            
            <div class="form-group">
                <label for="ref">Référence</label>
                <input type="text" name="ref" class="form-control" value="{{ old('ref', $devis->ref) }}">
            </div>
    
            <!-- Products Section -->
            <div id="product-container">
                @foreach($devis->items as $index => $item)
                    <div class="product-row">
                        <div class="form-group">
                            <label for="libele">Libellé</label>
                            <textarea name="libele[]" class="form-control" rows="3" required>{{ old("libele.$index", $item->libele) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="quantite">Quantité</label>
                            <input type="number" name="quantite[]" class="form-control quantity" value="{{ old("quantite.$index", $item->quantite) }}" oninput="calculatePrixTotal()" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="prix_unitaire">Prix Unitaire</label>
                            <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" value="{{ old("prix_unitaire.$index", $item->prix_unitaire) }}" oninput="calculatePrixTotal()" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="prix_total">Prix Total</label>
                            <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-danger" onclick="removeProduct(this)">Supprimer</button>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <button type="button" class="btn btn-info" onclick="addProduct()">Ajouter un produit</button>
    
            <div class="form-group">
                <label for="total_ht">Total HT</label>
                <input type="number" step="0.01" name="total_ht" class="form-control" id="total_ht" value="{{ old('total_ht', $devis->total_ht) }}" readonly>
            </div>
    
            <div class="form-group">
                <label for="tva">TVA (%)</label>
                <select name="tva" class="form-control" onchange="calculateTTC()">
                    <option value="0" {{ old('tva', $devis->tva_rate) == '0' ? 'selected' : '' }}>Aucune TVA</option>
                    <option value="20" {{ old('tva', $devis->tva_rate) == '20' ? 'selected' : '' }}>TVA 20%</option>
                </select>
            </div>
    
            <div class="form-group">
                <label for="total_ttc">Total TTC</label>
                <input type="number" step="0.01" name="total_ttc" class="form-control" id="total_ttc" value="{{ old('total_ttc', $devis->total_ttc) }}" readonly>
            </div>
    
            <div class="form-group">
                <label for="currency">Devise</label>
                <select name="currency" class="form-control">
                    <option value="DH" {{ old('currency', $devis->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                    <option value="EUR" {{ old('currency', $devis->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                </select>
            </div>
    
            <div class="form-group">
                <label for="important">Informations importantes</label>
                <div id="important-container">
                    @if($devis->importantInfos->count())
                        @foreach($devis->importantInfos as $index => $important)
                            <div class="important-row">
                                <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante" value="{{ old("important.$index", $important->info) }}">
                                <button type="button" class="btn btn-danger" onclick="removeImportant(this)">Supprimer</button>
                            </div>
                        @endforeach
                    @else
                        <div class="important-row">
                            <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                            <button type="button" class="btn btn-danger" onclick="removeImportant(this)">Supprimer</button>
                        </div>
                    @endif
                </div>
                <button type="button" class="btn btn-info" onclick="addImportant()">Ajouter une autre information</button>
            </div>
            
            <button type="submit" class="btn btn-success">Mettre à jour</button>
        </form>
    </div>
    
    <script>
    function calculatePrixTotal() {
        let totalHT = 0;
        let rows = document.querySelectorAll('.product-row');
    
        rows.forEach(function(row) {
            let quantity = parseFloat(row.querySelector('.quantity').value) || 0;
            let unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
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
        newProductRow.classList.add('product-row');
        newProductRow.innerHTML = `
            <div class="form-group">
                <label for="libele">Libellé</label>
                <textarea name="libele[]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="quantite">Quantité</label>
                <input type="number" name="quantite[]" class="form-control quantity" min="0" oninput="calculatePrixTotal()" required>
            </div>
            <div class="form-group">
                <label for="prix_unitaire">Prix Unitaire</label>
                <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" min="0" oninput="calculatePrixTotal()" required>
            </div>
            <div class="form-group">
                <label for="prix_total">Prix Total</label>
                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
            </div>
            <div class="form-group">
                <button type="button" class="btn btn-danger" onclick="removeProduct(this)">Supprimer</button>
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
    
    function addImportant() {
        let container = document.getElementById('important-container');
        let newRow = document.createElement('div');
        newRow.classList.add('important-row');
        newRow.innerHTML = `
            <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante">
            <button type="button" class="btn btn-danger" onclick="removeImportant(this)">Supprimer</button>
        `;
        container.appendChild(newRow);
    }
    
    function removeImportant(button) {
        let importantRows = document.querySelectorAll('.important-row');
        if (importantRows.length > 1) {
            button.closest('.important-row').remove();
        } else {
            alert('Vous devez conserver au moins une information importante.');
        }
    }
    </script>
    </x-app-layout>