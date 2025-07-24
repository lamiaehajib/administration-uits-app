<x-app-layout>
    <div class="container">
        <h1>Créer un Nouveau Bon de Livraison</h1>
        <form action="{{ route('bon_livraisons.store') }}" method="POST" id="bon-livraison-form">
            @csrf
            <div class="form-group">
                <label for="bon_num">Numéro du Bon de Livraison</label>
                <input type="text" name="bon_num" id="bon_num" class="form-control" value="{{ old('bon_num', 'Généré automatiquement après création') }}" readonly>
            </div>
            
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" name="client" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tele">Téléphone</label>
                <input type="text" name="tele" class="form-control">
            </div>
            <div class="form-group">
                <label for="ice">ICE</label>
                <input type="text" name="ice" class="form-control">
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea name="adresse" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label for="ref">Référence</label>
                <input type="text" name="ref" class="form-control">
            </div>
            
            <!-- Dynamically Added Products -->
            <div id="product-container">
                <div class="product-row">
                    <div class="form-group">
                        <label for="libelle">Libellé</label>
                        <textarea name="libelle[]" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="quantite">Quantité</label>
                        <input type="number" name="quantite[]" class="form-control quantity" oninput="calculatePrixTotal()" required>
                    </div>
                    <div class="form-group">
                        <label for="prix_ht">Prix HT</label>
                        <input type="number" name="prix_ht[]" class="form-control prix_ht" oninput="calculatePrixTotal()" required>
                    </div>
                    <div class="form-group">
                        <label for="prix_total">Prix Total</label>
                        <input type="number" name="prix_total[]" class="form-control total-price" readonly>
                    </div>
                </div>
            </div>
    
            <button type="button" class="btn btn-info" onclick="addProduct()">Ajouter un produit</button>
    
            <div class="form-group">
                <label for="total_ht">Total HT</label>
                <input type="number" name="total_ht" class="form-control" id="total_ht" readonly>
            </div>
    
            <div class="form-group">
                <label for="tva">TVA (%)</label>
                <select name="tva" class="form-control" onchange="calculateTTC()">
                    <option value="0">Aucune TVA</option>
                    <option value="20">TVA 20%</option>
                </select>
            </div>
    
            <div class="form-group">
                <label for="total_ttc">Total TTC</label>
                <input type="number" name="total_ttc" class="form-control" id="total_ttc" readonly>
            </div>
    
            <div class="form-group">
                <label for="important">Informations importantes</label>
                <div id="important-container">
                    <div class="important-row">
                        <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante">
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
                let quantity = row.querySelector('.quantity').value;
                let unitPrice = row.querySelector('.prix_ht').value;
                let totalPrice = row.querySelector('.total-price');
                
                totalPrice.value = quantity * unitPrice;
                totalHT += quantity * unitPrice;
            });
    
            document.getElementById('total_ht').value = totalHT;
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
                    <label for="libelle">Libellé</label>
                    <textarea name="libelle[]" class="form-control" required></textarea>
                </div>
                <div class="form-group">
                    <label for="quantite">Quantité</label>
                    <input type="number" name="quantite[]" class="form-control quantity" oninput="calculatePrixTotal()" required>
                </div>
                <div class="form-group">
                    <label for="prix_ht">Prix HT</label>
                    <input type="number" name="prix_ht[]" class="form-control prix_ht" oninput="calculatePrixTotal()" required>
                </div>
                <div class="form-group">
                    <label for="prix_total">Prix Total</label>
                    <input type="number" name="prix_total[]" class="form-control total-price" readonly>
                </div>
            `;
            productContainer.appendChild(newRow);
        }
    
        function addImportant() {
            const container = document.getElementById('important-container');
            const newRow = document.createElement('div');
            newRow.className = 'important-row';
            newRow.innerHTML = `
                <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante">
            `;
            container.appendChild(newRow);
        }
    </script>
    </x-app-layout>