<x-app-layout>
    <div class="container">
        <h1>Créer une Nouvelle Facture de Formation</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('facturefs.store') }}" method="POST" id="facture-form">
            @csrf
            @if (isset($devisf))
                <input type="hidden" name="devisf_id" value="{{ $devisf->id }}">
            @endif

            <div class="form-group">
                <label for="facturef_num">Numéro de la Facture</label>
                <input type="text" name="facturef_num" id="facturef_num" class="form-control" value="{{ old('facturef_num', 'Généré automatiquement après création') }}" readonly>
            </div>
            <div class="form-group">
                <label for="afficher_cachet">Afficher le cachet ?</label>
                <select name="afficher_cachet" class="form-control">
                    <option value="1" {{ old('afficher_cachet', isset($devisf) ? ($devisf->afficher_cachet ?? 1) : 1) == 1 ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('afficher_cachet', isset($devisf) ? ($devisf->afficher_cachet ?? 0) : 0) == 0 ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" class="form-control" value="{{ old('titre', isset($devisf) ? $devisf->titre : '') }}" required>
            </div>
            <div class="form-group">
                <label for="client">Client</label>
                <input type="text" name="client" class="form-control" value="{{ old('client', isset($devisf) ? $devisf->client : '') }}" required>
            </div>
            <div class="form-group">
                <label for="tele">Téléphone</label>
                <input type="text" name="tele" class="form-control" value="{{ old('tele', isset($devisf) ? ($devisf->contact ?? '') : '') }}" required>
            </div>
            <div class="form-group">
                <label for="ice">ICE</label>
                <input type="text" name="ice" class="form-control" value="{{ old('ice', isset($devisf) ? ($devisf->ice ?? '') : '') }}">
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea name="adresse" class="form-control">{{ old('adresse', isset($devisf) ? ($devisf->adresse ?? '') : '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="ref">Référence</label>
                <input type="text" name="ref" class="form-control" value="{{ old('ref', isset($devisf) ? ($devisf->ref ?? '') : '') }}">
            </div>

            <!-- Dynamically Added Products -->
            <div id="product-container">
                @if (isset($devisf) && $devisf->items)
                    @foreach ($devisf->items as $index => $item)
                        <div class="product-row mb-3">
                            <div class="form-group">
                                <label for="libelle">Libellé</label>
                                <textarea name="libelle[]" class="form-control" rows="3" required>{{ $item->libele }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="type">Choisir le type</label>
                                <select name="type[]" class="form-control" onchange="toggleFields(this)">
                                    <option value="duree" {{ $item->type == 'formation' ? 'selected' : '' }}>Durée</option>
                                    <option value="nombre_collaborateurs" {{ $item->type == 'nombre' ? 'selected' : '' }}>Nombre de collaborateurs</option>
                                    <option value="nombre_jours" {{ $item->type == 'nombre_de_jours' ? 'selected' : '' }}>Nombre de jours</option>
                                </select>
                            </div>
                            <div class="form-group duree-field" style="{{ $item->type == 'formation' ? 'display: block' : 'display: none' }}">
                                <label for="duree">Durée (en jours ou heures)</label>
                                <input type="text" name="duree[]" class="form-control" value="{{ $item->formation ?? '' }}">
                            </div>
                            <div class="form-group nombre_collaborateurs-field" style="{{ $item->type == 'nombre' ? 'display: block' : 'display: none' }}">
                                <label for="nombre_collaborateurs">Nombre de collaborateurs</label>
                                <input type="number" name="nombre_collaborateurs[]" class="form-control" value="{{ $item->nombre ?? '' }}" oninput="calculatePrixTotal()">
                            </div>
                            <div class="form-group nombre_jours-field" style="{{ $item->type == 'nombre_de_jours' ? 'display: block' : 'display: none' }}">
                                <label for="nombre_jours">Nombre de jours</label>
                                <input type="number" name="nombre_jours[]" class="form-control" value="{{ $item->nombre_de_jours ?? '' }}" oninput="calculatePrixTotal()">
                            </div>
                            <div class="form-group">
                                <label for="prix_ht">Prix Unitaire</label>
                                <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="{{ $item->prix_unitaire }}" oninput="calculatePrixTotal()" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="prix_total">Prix Total</label>
                                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ $item->prix_total }}" readonly>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-danger" onclick="removeProduct(this)">Supprimer</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="product-row mb-3">
                        <div class="form-group">
                            <label for="libelle">Libellé</label>
                            <textarea name="libelle[]" class="form-control" rows="3" required>{{ old('libelle.0') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="type">Choisir le type</label>
                            <select name="type[]" class="form-control" onchange="toggleFields(this)">
                                <option value="duree" {{ old('type.0') == 'duree' ? 'selected' : '' }}>Durée</option>
                                <option value="nombre_collaborateurs" {{ old('type.0') == 'nombre_collaborateurs' ? 'selected' : '' }}>Nombre de collaborateurs</option>
                                <option value="nombre_jours" {{ old('type.0') == 'nombre_jours' ? 'selected' : '' }}>Nombre de jours</option>
                            </select>
                        </div>
                        <div class="form-group duree-field">
                            <label for="duree">Durée (en jours ou heures)</label>
                            <input type="text" name="duree[]" class="form-control" value="{{ old('duree.0') }}">
                        </div>
                        <div class="form-group nombre_collaborateurs-field" style="display: none;">
                            <label for="nombre_collaborateurs">Nombre de collaborateurs</label>
                            <input type="number" name="nombre_collaborateurs[]" class="form-control" value="{{ old('nombre_collaborateurs.0') }}" oninput="calculatePrixTotal()">
                        </div>
                        <div class="form-group nombre_jours-field" style="display: none;">
                            <label for="nombre_jours">Nombre de jours</label>
                            <input type="number" name="nombre_jours[]" class="form-control" value="{{ old('nombre_jours.0') }}" oninput="calculatePrixTotal()">
                        </div>
                        <div class="form-group">
                            <label for="prix_ht">Prix Unitaire</label>
                            <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" value="{{ old('prix_ht.0') }}" oninput="calculatePrixTotal()" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="prix_total">Prix Total</label>
                            <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-danger" onclick="removeProduct(this)">Supprimer</button>
                        </div>
                    </div>
                @endif
            </div>

            <button type="button" class="btn btn-info" onclick="addProduct()">Ajouter un produit</button>

            <div class="form-group">
                <label for="total_ht">Total HT</label>
                <input type="number" step="0.01" name="total_ht" class="form-control" id="total_ht" value="{{ old('total_ht', isset($devisf) ? $devisf->total_ht : '') }}" readonly>
            </div>

            <div class="form-group">
                <label for="tva">TVA (%)</label>
                <select name="tva" class="form-control" onchange="calculateTTC()">
                    <option value="0" {{ old('tva', isset($devisf) && $devisf->total_ht > 0 ? ($devisf->tva / $devisf->total_ht * 100) : 0) == 0 ? 'selected' : '' }}>Aucune TVA</option>
                    <option value="20" {{ old('tva', isset($devisf) && $devisf->total_ht > 0 ? ($devisf->tva / $devisf->total_ht * 100) : 20) == 20 ? 'selected' : '' }}>TVA 20%</option>
                </select>
            </div>

            <div class="form-group">
                <label for="total_ttc">Total TTC</label>
                <input type="number" step="0.01" name="total_ttc" class="form-control" id="total_ttc" value="{{ old('total_ttc', isset($devisf) ? $devisf->total_ttc : '') }}" readonly>
            </div>

            <div class="form-group">
                <label for="currency">Devise</label>
                <select name="currency" class="form-control">
                    <option value="DH" {{ old('currency', isset($devisf) ? $devisf->currency : 'DH') == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                    <option value="EUR" {{ old('currency', isset($devisf) ? $devisf->currency : 'EUR') == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="important">Informations importantes</label>
                <div id="important-container">
                    @if (isset($devisf) && $devisf->importantInfos)
                        @foreach ($devisf->importantInfos as $info)
                            <div class="important-row mb-2">
                                <input type="text" name="important[]" class="form-control" value="{{ $info->info }}" placeholder="Ajouter une information importante">
                                <button type="button" class="btn btn-danger" onclick="removeImportant(this)">Supprimer</button>
                            </div>
                        @endforeach
                    @else
                        <div class="important-row mb-2">
                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                            <button type="button" class="btn btn-danger" onclick="removeImportant(this)">Supprimer</button>
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
            let unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            let totalPrice = row.querySelector('.total-price');
            let type = row.querySelector('[name="type[]"]').value;

            let quantity = 1;
            if (type === 'nombre_collaborateurs') {
                quantity = parseFloat(row.querySelector('[name="nombre_collaborateurs[]"]').value) || 0;
            } else if (type === 'nombre_jours') {
                quantity = parseFloat(row.querySelector('[name="nombre_jours[]"]').value) || 0;
            }

            let rowTotal = unitPrice * quantity;
            totalPrice.value = rowTotal.toFixed(2);
            totalHT += rowTotal;
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
                <label for="libelle">Libellé</label>
                <textarea name="libelle[]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="type">Choisir le type</label>
                <select name="type[]" class="form-control" onchange="toggleFields(this)">
                    <option value="duree">Durée</option>
                    <option value="nombre_collaborateurs">Nombre de collaborateurs</option>
                    <option value="nombre_jours">Nombre de jours</option>
                </select>
            </div>
            <div class="form-group duree-field">
                <label for="duree">Durée (en jours ou heures)</label>
                <input type="text" name="duree[]" class="form-control">
            </div>
            <div class="form-group nombre_collaborateurs-field" style="display: none;">
                <label for="nombre_collaborateurs">Nombre de collaborateurs</label>
                <input type="number" name="nombre_collaborateurs[]" class="form-control" oninput="calculatePrixTotal()">
            </div>
            <div class="form-group nombre_jours-field" style="display: none;">
                <label for="nombre_jours">Nombre de jours</label>
                <input type="number" name="nombre_jours[]" class="form-control" oninput="calculatePrixTotal()">
            </div>
            <div class="form-group">
                <label for="prix_ht">Prix Unitaire</label>
                <input type="number" step="0.01" name="prix_ht[]" class="form-control unit-price" min="0" oninput="calculatePrixTotal()" required>
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
        toggleFields(newRow.querySelector('[name="type[]"]')); // Initialize field visibility
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

    function toggleFields(select) {
        const type = select.value;
        const row = select.closest('.product-row');
        const dureeField = row.querySelector('.duree-field');
        const nombreCollaborateursField = row.querySelector('.nombre_collaborateurs-field');
        const nombreJoursField = row.querySelector('.nombre_jours-field');

        dureeField.style.display = 'none';
        nombreCollaborateursField.style.display = 'none';
        nombreJoursField.style.display = 'none';

        if (type === 'duree') {
            dureeField.style.display = 'block';
        } else if (type === 'nombre_collaborateurs') {
            nombreCollaborateursField.style.display = 'block';
        } else if (type === 'nombre_jours') {
            nombreJoursField.style.display = 'block';
        }
    }

    function addImportant() {
        const container = document.getElementById('important-container');
        const newRow = document.createElement('div');
        newRow.className = 'important-row mb-2';
        newRow.innerHTML = `
            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
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

    // Initialize field visibility and calculations for existing rows
    document.querySelectorAll('[name="type[]"]').forEach(toggleFields);
    window.onload = function() {
        calculatePrixTotal();
    };
    </script>
</x-app-layout>
