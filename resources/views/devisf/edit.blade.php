<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Modifier le Devis N° {{ $devisf->devis_num }}</h3>
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

                        <form action="{{ route('devisf.update', $devisf->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Section: Information de Base -->
                            <div class="mb-4">
                                <label for="devis_num" class="form-label">Numéro du devis</label>
                                <input type="text" name="devis_num" class="form-control" value="{{ old('devis_num', $devisf->devis_num) }}" readonly>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', $devisf->date) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="titre" class="form-label">Titre</label>
                                    <input type="text" name="titre" class="form-control" value="{{ old('titre', $devisf->titre) }}" required>
                                </div>
                            </div>

                            <!-- Section: Client Information -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="client" class="form-label">Client</label>
                                    <input type="text" name="client" class="form-control" value="{{ old('client', $devisf->client) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" name="contact" class="form-control" value="{{ old('contact', $devisf->contact) }}">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="ref" class="form-label">Référence</label>
                                <input type="text" name="ref" class="form-control" value="{{ old('ref', $devisf->ref) }}">
                            </div>

                            <div class="mb-4">
                                <label for="vide" class="form-label">Vide</label>
                                <input type="text" name="vide" class="form-control" value="{{ old('vide', $devisf->vide) }}">
                            </div>

                            <!-- Products Section -->
                            <div id="product-container">
                                <h5 class="mb-3">Produits</h5>
                                @foreach($devisf->items as $index => $item)
                                    <div class="product-row border p-3 mb-3 rounded">
                                        <div class="mb-3">
                                            <label for="libele" class="form-label">Libellé</label>
                                            <textarea name="libele[]" class="form-control" rows="3" required>{{ old("libele.$index", $item->libele) }}</textarea>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label for="type" class="form-label">Choisir le type</label>
                                                <select name="type[]" class="form-control type-select" onchange="toggleFields(this)">
                                                    <option value="formation" {{ old("type.$index", $item->nombre_de_jours == null && $item->nombre == null ? 'formation' : '') == 'formation' ? 'selected' : '' }}>Durée</option>
                                                    <option value="nombre" {{ old("type.$index", $item->nombre != null ? 'nombre' : '') == 'nombre' ? 'selected' : '' }}>Nombre de collaborateurs</option>
                                                    <option value="nombre_de_jours" {{ old("type.$index", $item->nombre_de_jours != null ? 'nombre_de_jours' : '') == 'nombre_de_jours' ? 'selected' : '' }}>Nombre de jours</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 formation-field" style="{{ $item->nombre != null || $item->nombre_de_jours != null ? 'display:none;' : '' }}">
                                                <label for="formation" class="form-label">Durée (en jours ou heures)</label>
                                                <input type="text" name="formation[]" class="form-control" value="{{ old("formation.$index", $item->formation) }}">
                                            </div>
                                            <div class="col-md-4 nombre-field" style="{{ $item->nombre == null ? 'display:none;' : '' }}">
                                                <label for="nombre" class="form-label">Nombre de collaborateurs</label>
                                                <input type="number" name="nombre[]" class="form-control" value="{{ old("nombre.$index", $item->nombre) }}" oninput="calculatePrixTotal()">
                                            </div>
                                            <div class="col-md-4 nombre_de_jours-field" style="{{ $item->nombre_de_jours == null ? 'display:none;' : '' }}">
                                                <label for="nombre_de_jours" class="form-label">Nombre de jours</label>
                                                <input type="number" name="nombre_de_jours[]" class="form-control" value="{{ old("nombre_de_jours.$index", $item->nombre_de_jours) }}" oninput="calculatePrixTotal()">
                                            </div>
                                            <div class="col-md-4">
                                                <label for="prix_unitaire" class="form-label">Prix Unitaire</label>
                                                <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" value="{{ old("prix_unitaire.$index", $item->prix_unitaire) }}" oninput="calculatePrixTotal()" min="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label for="prix_total" class="form-label">Prix Total</label>
                                                <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" value="{{ old("prix_total.$index", $item->prix_total) }}" readonly>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-danger" onclick="removeProduct(this)">Supprimer</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary mb-4" onclick="addProduct()">+ Ajouter un produit</button>

                            <!-- Totals -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="total_ht" class="form-label">Total HT</label>
                                    <input type="number" step="0.01" name="total_ht" id="total_ht" class="form-control" value="{{ old('total_ht', $devisf->total_ht) }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="tva" class="form-label">TVA (%)</label>
                                    <select name="tva" class="form-control" onchange="calculateTTC()">
                                        <option value="0" {{ old('tva', $devisf->tva == 0 ? '0' : '20') == '0' ? 'selected' : '' }}>Aucune TVA</option>
                                        <option value="20" {{ old('tva', $devisf->tva == 0 ? '0' : '20') == '20' ? 'selected' : '' }}>TVA 20%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="total_ttc" class="form-label">Total TTC</label>
                                <input type="number" step="0.01" name="total_ttc" id="total_ttc" class="form-control" value="{{ old('total_ttc', $devisf->total_ttc) }}" readonly>
                            </div>

                            <div class="mb-4">
                                <label for="currency" class="form-label">Devise</label>
                                <select name="currency" class="form-control">
                                    <option value="DH" {{ old('currency', $devisf->currency) == 'DH' ? 'selected' : '' }}>Dirham (DH)</option>
                                    <option value="EUR" {{ old('currency', $devisf->currency) == 'EUR' ? 'selected' : '' }}>Euro (€)</option>
                                </select>
                            </div>

                            <!-- Informations Importantes -->
                            <div class="mb-4">
                                <label for="important" class="form-label">Informations importantes</label>
                                <div id="important-container">
                                    @if($devisf->ImportantInfof->count())
                                        @foreach($devisf->ImportantInfof as $index => $important)
                                            <div class="important-row mb-2">
                                                <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old("important.$index", $important->info) }}">
                                                <button type="button" class="btn btn-danger mt-2" onclick="removeImportant(this)">Supprimer</button>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="important-row mb-2">
                                            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante" value="{{ old('important.0') }}">
                                            <button type="button" class="btn btn-danger mt-2" onclick="removeImportant(this)">Supprimer</button>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn btn-outline-primary mt-2" onclick="addImportant()">Ajouter une autre information</button>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-success me-2">Mettre à jour</button>
                                <a href="{{ route('devisf.index') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function calculatePrixTotal() {
        let totalHT = 0;
        let rows = document.querySelectorAll('.product-row');

        rows.forEach(function(row) {
            let unitPrice = parseFloat(row.querySelector('.unit-price').value) || 0;
            let totalPrice = row.querySelector('.total-price');
            let type = row.querySelector('.type-select').value;

            let quantity = 1;
            if (type === 'nombre') {
                quantity = parseFloat(row.querySelector('[name="nombre[]"]').value) || 0;
            } else if (type === 'nombre_de_jours') {
                quantity = parseFloat(row.querySelector('[name="nombre_de_jours[]"]').value) || 0;
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
        let tva = parseFloat(document.querySelector('select[name="tva"]').value) || 0;
        let totalTTC = totalHT * (1 + tva / 100);
        document.getElementById('total_ttc').value = totalTTC.toFixed(2);
    }

    function addProduct() {
        let productContainer = document.getElementById('product-container');
        let newProductRow = document.createElement('div');
        newProductRow.classList.add('product-row', 'border', 'p-3', 'mb-3', 'rounded');
        newProductRow.innerHTML = `
            <div class="mb-3">
                <label for="libele" class="form-label">Libellé</label>
                <textarea name="libele[]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="type" class="form-label">Choisir le type</label>
                    <select name="type[]" class="form-control type-select" onchange="toggleFields(this)">
                        <option value="formation">Durée</option>
                        <option value="nombre">Nombre de collaborateurs</option>
                        <option value="nombre_de_jours">Nombre de jours</option>
                    </select>
                </div>
                <div class="col-md-4 formation-field">
                    <label for="formation" class="form-label">Durée (en jours ou heures)</label>
                    <input type="text" name="formation[]" class="form-control">
                </div>
                <div class="col-md-4 nombre-field" style="display:none;">
                    <label for="nombre" class="form-label">Nombre de collaborateurs</label>
                    <input type="number" name="nombre[]" class="form-control" oninput="calculatePrixTotal()">
                </div>
                <div class="col-md-4 nombre_de_jours-field" style="display:none;">
                    <label for="nombre_de_jours" class="form-label">Nombre de jours</label>
                    <input type="number" name="nombre_de_jours[]" class="form-control" oninput="calculatePrixTotal()">
                </div>
                <div class="col-md-4">
                    <label for="prix_unitaire" class="form-label">Prix Unitaire</label>
                    <input type="number" step="0.01" name="prix_unitaire[]" class="form-control unit-price" min="0" oninput="calculatePrixTotal()" required>
                </div>
                <div class="col-md-4">
                    <label for="prix_total" class="form-label">Prix Total</label>
                    <input type="number" step="0.01" name="prix_total[]" class="form-control total-price" readonly>
                </div>
                <div class="col-md-4">
                    <button type="button" class="btn btn-danger" onclick="removeProduct(this)">Supprimer</button>
                </div>
            </div>
        `;
        productContainer.appendChild(newProductRow);
        toggleFields(newProductRow.querySelector('.type-select')); // Initialize field visibility
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
        const formationField = row.querySelector('.formation-field');
        const nombreField = row.querySelector('.nombre-field');
        const nombreDeJoursField = row.querySelector('.nombre_de_jours-field');

        formationField.style.display = 'none';
        nombreField.style.display = 'none';
        nombreDeJoursField.style.display = 'none';

        if (type === 'formation') {
            formationField.style.display = 'block';
        } else if (type === 'nombre') {
            nombreField.style.display = 'block';
        } else if (type === 'nombre_de_jours') {
            nombreDeJoursField.style.display = 'block';
        }
    }

    function addImportant() {
        let container = document.getElementById('important-container');
        let newRow = document.createElement('div');
        newRow.classList.add('important-row', 'mb-2');
        newRow.innerHTML = `
            <input type="text" name="important[]" class="form-control" placeholder="Ajouter une information importante">
            <button type="button" class="btn btn-danger mt-2" onclick="removeImportant(this)">Supprimer</button>
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

    // Initialize field visibility for existing rows
    document.querySelectorAll('.type-select').forEach(toggleFields);
    calculatePrixTotal(); // Initialize totals
    </script>
</x-app-layout>