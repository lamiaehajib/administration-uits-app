<x-app-layout>
    <div class="container">
        <h1>Modifier le Bon de Commande</h1>
        <form action="{{ route('bon_commande_r.update', $bonCommandeR) }}" method="POST" id="bon-commande-form">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="bon_num">Numéro du bon de commande</label>
                <input type="text" name="bon_num" id="bon_num" class="form-control" value="{{ old('bon_num', $bonCommandeR->bon_num) }}" readonly>
                @error('bon_num')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $bonCommandeR->date ? $bonCommandeR->date->format('Y-m-d') : '') }}">
                @error('date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" class="form-control" value="{{ old('titre', $bonCommandeR->titre) }}">
                @error('titre')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="prestataire">Prestataire</label>
                <input type="text" name="prestataire" class="form-control" value="{{ old('prestataire', $bonCommandeR->prestataire) }}">
                @error('prestataire')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="tele">Téléphone</label>
                <input type="text" name="tele" class="form-control" value="{{ old('tele', $bonCommandeR->tele) }}">
                @error('tele')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="ice">ICE</label>
                <input type="text" name="ice" class="form-control" value="{{ old('ice', $bonCommandeR->ice) }}">
                @error('ice')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" class="form-control" value="{{ old('adresse', $bonCommandeR->adresse) }}">
                @error('adresse')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="ref">Référence</label>
                <input type="text" name="ref" class="form-control" value="{{ old('ref', $bonCommandeR->ref) }}">
                @error('ref')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Dynamically Added Items -->
            <div id="product-container">
                @forelse ($bonCommandeR->items as $index => $item)
                    <div class="product-row">
                        <div class="form-group">
                            <label for="libelle">Libellé</label>
                            <textarea name="libelle[]" class="form-control" rows="3">{{ old("libelle.$index", $item->libelle) }}</textarea>
                            @error("libelle.$index")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="quantite">Quantité</label>
                            <input type="number" name="quantite[]" class="form-control quantity" value="{{ old("quantite.$index", $item->quantite) }}" oninput="calculatePrixTotal()" required>
                            @error("quantite.$index")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="prix_ht">Prix HT</label>
                            <input type="number" name="prix_ht[]" class="form-control unit-price" value="{{ old("prix_ht.$index", $item->prix_ht) }}" oninput="calculatePrixTotal()" required>
                            @error("prix_ht.$index")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="prix_total">Prix Total</label>
                            <input type="number" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeProduct(this)">Supprimer</button>
                        </div>
                    </div>
                @empty
                    <div class="product-row">
                        <div class="form-group">
                            <label for="libelle">Libellé</label>
                            <textarea name="libelle[]" class="form-control" rows="3">{{ old('libelle.0') }}</textarea>
                            @error('libelle.0')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="quantite">Quantité</label>
                            <input type="number" name="quantite[]" class="form-control quantity" value="{{ old('quantite.0') }}" oninput="calculatePrixTotal()" required>
                            @error('quantite.0')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="prix_ht">Prix HT</label>
                            <input type="number" name="prix_ht[]" class="form-control unit-price" value="{{ old('prix_ht.0') }}" oninput="calculatePrixTotal()" required>
                            @error('prix_ht.0')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="prix_total">Prix Total</label>
                            <input type="number" name="prix_total[]" class="form-control total-price" readonly>
                            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeProduct(this)">Supprimer</button>
                        </div>
                    </div>
                @endforelse
            </div>
    
            <button type="button" class="btn btn-info" onclick="addProduct()">Ajouter un article</button>
    
            <div class="form-group">
                <label for="total_ht">Total HT</label>
                <input type="number" name="total_ht" class="form-control" id="total_ht" value="{{ old('total_ht', $bonCommandeR->total_ht) }}" readonly>
                @error('total_ht')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="form-group">
                <label for="tva">TVA (%)</label>
                <select name="tva" class="form-control" onchange="calculateTTC()">
                    <option value="0" {{ old('tva', $bonCommandeR->tva) == 0 ? 'selected' : '' }}>Aucune TVA</option>
                    <option value="20" {{ old('tva', $bonCommandeR->tva) == 20 ? 'selected' : '' }}>TVA 20%</option>
                </select>
                @error('tva')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="form-group">
                <label for="total_ttc">Total TTC</label>
                <input type="number" name="total_ttc" class="form-control" id="total_ttc" value="{{ old('total_ttc', $bonCommandeR->total_ttc) }}" readonly>
                @error('total_ttc')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="form-group">
                <label for="currency">Devise</label>
                <select name="currency" class="form-control" required>
                    <option value="DH" {{ old('currency', $bonCommandeR->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                    <option value="EUR" {{ old('currency', $bonCommandeR->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                </select>
                @error('currency')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
    
            <div class="form-group">
                <label for="important">Informations importantes</label>
                <div id="important-container">
                    @php
                        $importantItems = is_array($bonCommandeR->important) ? $bonCommandeR->important : (json_decode($bonCommandeR->important, true) ?? []);
                    @endphp
                    @forelse ($importantItems as $index => $info)
                        <div class="important-row">
                            <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante" value="{{ old("important.$index", $info) }}">
                            @error("important.$index")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeImportant(this)">Supprimer</button>
                        </div>
                    @empty
                        <div class="important-row">
                            <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                            @error('important.0')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeImportant(this)">Supprimer</button>
                        </div>
                    @endforelse
                </div>
                <button type="button" class="btn btn-info" onclick="addImportant()">Ajouter une autre information</button>
            </div>
            
            <button type="submit" class="btn btn-success">Modifier</button>
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
                    <label for="libelle">Libellé</label>
                    <textarea name="libelle[]" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="quantite">Quantité</label>
                    <input type="number" name="quantite[]" class="form-control quantity" oninput="calculatePrixTotal()" required>
                </div>
                <div class="form-group">
                    <label for="prix_ht">Prix HT</label>
                    <input type="number" name="prix_ht[]" class="form-control unit-price" oninput="calculatePrixTotal()" required>
                </div>
                <div class="form-group">
                    <label for="prix_total">Prix Total</label>
                    <input type="number" name="prix_total[]" class="form-control total-price" readonly>
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeProduct(this)">Supprimer</button>
                </div>
            `;
            productContainer.appendChild(newRow);
            calculatePrixTotal();
        }
    
        function removeProduct(button) {
            button.closest('.product-row').remove();
            calculatePrixTotal();
        }
    
        function addImportant() {
            const container = document.getElementById('important-container');
            const newRow = document.createElement('div');
            newRow.className = 'important-row';
            newRow.innerHTML = `
                <input type="text" name="important[]" class="form-control mb-2" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeImportant(this)">Supprimer</button>
            `;
            container.appendChild(newRow);
        }
    
        function removeImportant(button) {
            button.closest('.important-row').remove();
        }
    
        // Initialize calculations on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculatePrixTotal();
        });
    </script>
    </x-app-layout>