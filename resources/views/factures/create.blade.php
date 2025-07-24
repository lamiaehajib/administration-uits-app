<x-app-layout>
    <div class="container">
        <h1>Créer un Nouveau Facture</h1>
        <form action="{{ route('factures.store') }}" method="POST" id="facture-form">
            @csrf
            <!-- إضافة devis_id مخفي لو جاي من Devis -->
            @if (isset($devis))
                <input type="hidden" name="devis_id" value="{{ $devis->id }}">
            @endif
    
            <div class="form-group">
                <label for="facture_num">Numéro du Facture</label>
                <input type="text" name="facture_num" id="facture_num" class="form-control" value="{{ old('facture_num', 'Généré automatiquement après création') }}" readonly>
            </div>
            <div class="form-group">
                <label for="afficher_cachet">Afficher le cachet ?</label>
                <select name="afficher_cachet" class="form-control">
                    <option value="1" {{ old('afficher_cachet', isset($devis) ? ($devis->afficher_cachet ?? 1) : 1) == 1 ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('afficher_cachet', isset($devis) ? ($devis->afficher_cachet ?? 0) : 0) == 0 ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" class="form-control" value="{{ old('titre', isset($devis) ? $devis->titre : '') }}" required>
            </div>
            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" name="client" class="form-control" value="{{ old('client', isset($devis) ? $devis->client : '') }}" required>
            </div>
            <div class="form-group">
                <label for="ice">ICE</label>
                <input type="text" name="ice" class="form-control" value="{{ old('ice', isset($devis) ? ($devis->ice ?? '') : '') }}">
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea name="adresse" class="form-control">{{ old('adresse', isset($devis) ? ($devis->adresse ?? '') : '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="ref">Référence</label>
                <input type="text" name="ref" class="form-control" value="{{ old('ref', isset($devis) ? ($devis->ref ?? '') : '') }}">
            </div>
    
            <!-- Dynamically Added Products -->
            <div id="product-container">
                @if (isset($devis) && $devis->items)
                    @foreach ($devis->items as $index => $item)
                        <div class="product-row mb-3">
                            <div class="form-group">
                                <label for="libele">Libellé</label>
                                <textarea name="libele[]" class="form-control" required>{{ $item->libele }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="quantite">Quantité</label>
                                <input type="number" name="quantite[]" class="form-control quantity" value="{{ $item->quantite }}" oninput="calculatePrixTotal()" required>
                            </div>
                            <div class="form-group">
                                <label for="prix_ht">Prix HT</label>
                                <input type="number" name="prix_ht[]" class="form-control prix_ht" value="{{ $item->prix_unitaire }}" oninput="calculatePrixTotal()" required>
                            </div>
                            <div class="form-group">
                                <label for="prix_total">Prix Total</label>
                                <input type="number" name="prix_total[]" class="form-control total-price" value="{{ $item->prix_total }}" readonly>
                            </div>
                            <button type="button" class="btn btn-danger remove-product">Supprimer</button>
                        </div>
                    @endforeach
                @else
                    <div class="product-row mb-3">
                        <div class="form-group">
                            <label for="libele">Libellé</label>
                            <textarea name="libele[]" class="form-control" required></textarea>
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
                        <button type="button" class="btn btn-danger remove-product">Supprimer</button>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-info" onclick="addProduct()">Ajouter un produit</button>
    
            <div class="form-group">
                <label for="total_ht">Total HT</label>
                <input type="number" name="total_ht" class="form-control" id="total_ht" value="{{ old('total_ht', isset($devis) ? $devis->total_ht : 0) }}" readonly>
            </div>
            <div class="form-group">
                <label for="tva">TVA (%)</label>
                <select name="tva" class="form-control" onchange="calculateTTC()">
                    <option value="0" {{ old('tva', isset($devis) && $devis->total_ht > 0 ? ($devis->tva / $devis->total_ht * 100) : 0) == 0 ? 'selected' : '' }}>Aucune TVA</option>
                    <option value="20" {{ old('tva', isset($devis) && $devis->total_ht > 0 ? ($devis->tva / $devis->total_ht * 100) : 20) == 20 ? 'selected' : '' }}>TVA 20%</option>
                </select>
            </div>
            <div class="form-group">
                <label for="total_ttc">Total TTC</label>
                <input type="number" name="total_ttc" class="form-control" id="total_ttc" value="{{ old('total_ttc', isset($devis) ? $devis->total_ttc : 0) }}" readonly>
            </div>
            <div class="form-group">
                <label for="currency">Devise</label>
                <select name="currency" class="form-control">
                    <option value="DH" {{ old('currency', isset($devis) ? $devis->currency : 'DH') == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                    <option value="EUR" {{ old('currency', isset($devis) ? $devis->currency : 'EUR') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="important">Informations importantes</label>
                <div id="important-container">
                    @if (isset($devis) && $devis->importantInfos)
                        @foreach ($devis->importantInfos as $info)
                            <div class="important-row mb-2">
                                <input type="text" name="important[]" class="form-control" value="{{ $info->info }}" placeholder="Ajouter une information importante">
                                <button type="button" class="btn btn-danger remove-important">Supprimer</button>
                            </div>
                        @endforeach
                    @else
                        <div class="important-row mb-2">
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                            <button type="button" class="btn btn-danger remove-important">Supprimer</button>
                        </div>
                    @endif
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
                let unitPrice = parseFloat(row.querySelector('.prix_ht').value) || 0;
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
            newRow.classList.add('product-row', 'mb-3');
            newRow.innerHTML = `
                <div class="form-group">
                    <label for="libele">Libellé</label>
                    <textarea name="libele[]" class="form-control" required></textarea>
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
                <button type="button" class="btn btn-danger remove-product">Supprimer</button>
            `;
            productContainer.appendChild(newRow);
        }
    
        function addImportant() {
            const container = document.getElementById('important-container');
            const newRow = document.createElement('div');
            newRow.className = 'important-row mb-2';
            newRow.innerHTML = `
                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
                <button type="button" class="btn btn-danger remove-important">Supprimer</button>
            `;
            container.appendChild(newRow);
        }
    
        // إضافة حذف ديناميكي
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                if (document.querySelectorAll('.product-row').length > 1) {
                    e.target.closest('.product-row').remove();
                    calculatePrixTotal();
                }
            }
            if (e.target.classList.contains('remove-important')) {
                if (document.querySelectorAll('.important-row').length > 1) {
                    e.target.closest('.important-row').remove();
                }
            }
        });
    
        // تشغيل الحسابات عند تحميل الصفحة إذا كان هناك بيانات من Devis
        window.onload = function() {
            calculatePrixTotal();
        };
    </script>
    </x-app-layout>