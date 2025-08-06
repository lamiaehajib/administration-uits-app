<x-app-layout>
    <div class="container">
        <h1>Créer un Nouveau Devis</h1>
        <form action="{{ route('devis.store') }}" method="POST" id="devis-form">
            @csrf
            <div class="form-group">
                <label for="devis_num">Numéro du devis</label>
                <input type="text" name="devis_num" id="devis_num" class="form-control" value="{{ old('devis_num', 'Généré automatiquement après création') }}" readonly>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
            </div>
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" class="form-control" value="{{ old('titre') }}" required>
            </div>
            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" name="client" class="form-control" value="{{ old('client') }}" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" name="contact" class="form-control" value="{{ old('contact') }}">
            </div>
            <div class="form-group">
                <label for="ref">Référence</label>
                <input type="text" name="ref" class="form-control" value="{{ old('ref') }}">
            </div>
            
            <!-- Dynamically Added Products -->
            <div id="product-container">
                <div class="product-row">
                    <div class="form-group">
                        <label for="libele">Libellé</label>
                        <textarea name="libele[]" class="form-control" rows="3" required>{{ old('libele.0') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="quantite">Quantité</label>
                        <input type="number" name="quantite[]" class="form-control quantity" value="{{ old('quantite.0') }}" oninput="calculatePrixTotal()" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="prix_unitaire">Prix Unitaire</label>
                        <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" value="{{ old('prix_unitaire.0') }}" oninput="calculatePrixTotal()" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="prix_total">Prix Total</label>
                        <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger" onclick="removeProduct(this)">Supprimer</button>
                    </div>
                </div>
            </div>
    
            <button type="button" class="btn btn-info" onclick="addProduct()">Ajouter un produit</button>
    
            <div class="form-group">
                <label for="total_ht">Total HT</label>
                <input type="number" step="0.01" name="total_ht" class="form-control" id="total_ht" value="{{ old('total_ht') }}" readonly>
            </div>
    
     <div class="form-group">
    <label for="tva">TVA (%)</label>
    <select name="tva" class="form-control" onchange="calculateTTC()">
        <option value="20" {{ old('tva', '20') == '20' ? 'selected' : '' }}>TVA 20%</option>
        <option value="0" {{ old('tva') == '0' ? 'selected' : '' }}>Aucune TVA</option>
    </select>
</div>
    
            <div class="form-group">
                <label for="total_ttc">Total TTC</label>
                <input type="number" step="0.01" name="total_ttc" class="form-control" id="total_ttc" value="{{ old('total_ttc') }}" readonly>
            </div>
    
            <div class="form-group">
                <label for="currency">Devise</label>
                <select name="currency" class="form-control">
                    <option value="DH" {{ old('currency') == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                    <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                </select>
            </div>
    
            <div class="form-group">
                <label for="important">Informations importantes</label>
                <div id="important-container">
                    <div class="important-row">
                        <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                        <button type="button" class="btn btn-danger" onclick="removeImportant(this)">Supprimer</button>
                    </div>
                </div>
                <button type="button" class="btn btn-info" onclick="addImportant()">Ajouter une autre information</button>
            </div>
            
            <button type="submit" class="btn btn-success">Créer</button>
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
            
            totalPrice.value = (quantity * unitPrice).toFixed(2);
            totalHT += quantity * unitPrice;
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
        newRow.classList.add('product-row');
        newRow.innerHTML = `
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
        productContainer.appendChild(newRow);
    }
    
    function removeProduct(button) {
        let productRows = document.querySelectorAll('.product-row');
        if (productRows.length > 1) { // Empêche la suppression si c'est la dernière ligne
            button.closest('.product-row').remove();
            calculatePrixTotal(); // Recalcule les totaux après suppression
        } else {
            alert('Vous devez conserver au moins un produit.');
        }
    }
    
    function addImportant() {
        const container = document.getElementById('important-container');
        const newRow = document.createElement('div');
        newRow.className = 'important-row';
        newRow.innerHTML = `
            <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante">
            <button type="button" class="btn btn-danger" onclick="removeImportant(this)">Supprimer</button>
        `;
        container.appendChild(newRow);
    }
    
    function removeImportant(button) {
        let importantRows = document.querySelectorAll('.important-row');
        if (importantRows.length > 1) { // Empêche la suppression si c'est la dernière ligne
            button.closest('.important-row').remove();
        } else {
            alert('Vous devez conserver au moins une information importante.');
        }
    }
    </script>
    </x-app-layout>