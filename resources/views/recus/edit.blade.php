<x-app-layout>
    <x-slot name="header">
        <h2 class="hight text-xl text-gray-800 leading-tight">
            {{ __('Modification du Reçu #') . $recu->numero_recu }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    
                    <h3 class="text-2xl font-bold mb-6 text-center" style="color: #D32F2F;">
                        <i class="fas fa-edit me-2"></i> Modifier le Reçu
                    </h3>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('recus.update', $recu) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="border p-4 rounded-lg shadow-sm" style="border-color: #C2185B !important;">
                            <h4 class="hight text-xl font-semibold mb-4 border-b pb-2" style="border-color: #D32F2F !important;">
                                Informations Client & Garantie
                            </h4>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="client_nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="client_nom" name="client_nom" value="{{ old('client_nom', $recu->client_nom) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="client_prenom" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="client_prenom" name="client_prenom" value="{{ old('client_prenom', $recu->client_prenom) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="client_telephone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" id="client_telephone" name="client_telephone" value="{{ old('client_telephone', $recu->client_telephone) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="client_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="client_email" name="client_email" value="{{ old('client_email', $recu->client_email) }}">
                                </div>
                                <div class="col-12">
                                    <label for="client_adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control" id="client_adresse" name="client_adresse" rows="2">{{ old('client_adresse', $recu->client_adresse) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="equipement" class="form-label">Équipement</label>
                                    <input type="text" class="form-control" id="equipement" name="equipement" value="{{ old('equipement', $recu->equipement) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="type_garantie" class="form-label">Type de Garantie <span class="text-danger">*</span></label>
                                    <select class="form-select" id="type_garantie" name="type_garantie" required>
                                        <option value="30_jours" {{ old('type_garantie', $recu->type_garantie) == '30_jours' ? 'selected' : '' }}>30 Jours</option>
                                        <option value="90_jours" {{ old('type_garantie', $recu->type_garantie) == '90_jours' ? 'selected' : '' }}>90 Jours</option>
                                        <option value="180_jours" {{ old('type_garantie', $recu->type_garantie) == '180_jours' ? 'selected' : '' }}>180 Jours</option>
                                        <option value="360_jours" {{ old('type_garantie', $recu->type_garantie) == '360_jours' ? 'selected' : '' }}>360 Jours</option>
                                        <option value="sans_garantie" {{ old('type_garantie', $recu->type_garantie) == 'sans_garantie' ? 'selected' : '' }}>Sans Garantie</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="details" class="form-label">Détails de la réparation / demande</label>
                                    <textarea class="form-control" id="details" name="details" rows="3">{{ old('details', $recu->details) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="border p-4 rounded-lg shadow-sm" style="border-color: #C2185B !important;">
                            <h4 class="hight text-xl font-semibold mb-4 border-b pb-2" style="border-color: #D32F2F !important;">
                                Paramètres du Reçu
                            </h4>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="remise" class="form-label">Remise (DH)</label>
                                    <input type="number" step="0.01" class="form-control" id="remise" name="remise" value="{{ old('remise', $recu->remise) }}" min="0">
                                </div>
                                <div class="col-md-6">
                                    <label for="tva" class="form-label">TVA (%)</label>
                                    <input type="number" step="0.01" class="form-control" id="tva" name="tva" value="{{ old('tva', $recu->tva) }}" min="0">
                                </div>
                                <div class="col-12">
                                    <label for="notes" class="form-label">Notes Internes</label>
                                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $recu->notes) }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end space-x-2">
                            <a href="{{ route('recus.show', $recu) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn text-white" style="background-color: #D32F2F;">
                                <i class="fas fa-save me-1"></i> Mettre à Jour le Reçu
                            </button>
                        </div>
                    </form>

                    <hr class="my-6" style="border-top: 1px solid #C2185B;">

                    <h3 class="text-2xl font-bold mb-4 mt-6 text-center" style="color: #C2185B;">
                        <i class="fas fa-list me-2"></i> Articles du Reçu
                    </h3>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped">
                            <thead class="text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                                <tr>
                                    <th>Article</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Total</th>
                                    <th style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recu->items as $item)
                                    <tr>
                                        <td class="text-start">{{ $item->produit->nom }}</td>
                                        <td>{{ $item->quantite }}</td>
                                        <td>{{ number_format($item->prix_unitaire, 2, ',', ' ') }} DH</td>
                                        <td>{{ number_format($item->sous_total, 2, ',', ' ') }} DH</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger delete-item-btn" data-item-id="{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Aucun article n'a été ajouté à ce reçu.</td>
                                    </tr>
                                @endforelse
                                <tr class="font-weight-bold">
                                    <td colspan="3" class="text-end">**Sous-Total Articles:**</td>
                                    <td colspan="2">{{ number_format($recu->total_items_brut, 2, ',', ' ') }} DH</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td colspan="3" class="text-end">**Remise ({{ number_format($recu->remise, 2) }} DH):**</td>
                                    <td colspan="2" class="text-danger">- {{ number_format($recu->remise, 2, ',', ' ') }} DH</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td colspan="3" class="text-end">**TVA ({{ number_format($recu->tva, 2) }}%):**</td>
                                    <td colspan="2" class="text-success">+ {{ number_format($recu->montant_tva, 2, ',', ' ') }} DH</td>
                                </tr>
                                <tr class="font-weight-bold">
                                    <td colspan="3" class="text-end" style="color: #D32F2F;">**TOTAL NET:**</td>
                                    <td colspan="2" style="color: #C2185B; font-size: 1.1em;">
                                        **{{ number_format($recu->total, 2, ',', ' ') }} DH**
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="border p-4 rounded-lg shadow-sm mt-5" style="border-color: #C2185B !important;">
                        <h4 class="hight text-xl font-semibold mb-4 border-b pb-2" style="border-color: #D32F2F !important;">
                            Ajouter un Article
                        </h4>
                        <form id="addItemForm" method="POST" action="{{ route('recus.items.add', $recu) }}" class="row g-3">
                            @csrf
                            <div class="col-md-6">
                                <label for="produit_id" class="form-label">Produit <span class="text-danger">*</span></label>
                                <select class="form-select select2-produit" id="produit_id" name="produit_id" required>
                                    <option value="">Sélectionner un produit</option>
                                    @foreach ($produits as $produit)
                                        <option value="{{ $produit->id }}" data-stock="{{ $produit->quantite_stock }}" data-prix="{{ $produit->prix_vente }}">{{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="quantite" class="form-label">Quantité <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantite" name="quantite" value="1" min="1" required>
                                <small id="stockInfo" class="form-text text-muted"></small>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn text-white w-100" style="background-color: #C2185B;">
                                    <i class="fas fa-plus me-1"></i> Ajouter
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            // Initialiser Select2
            $('.select2-produit').select2({
                placeholder: "Rechercher ou sélectionner un produit",
                allowClear: true,
                width: '100%'
            });
            
            // =======================================
            // GESTION DU STOCK ET AFFICHAGE DU PRIX
            // =======================================
            $('#produit_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const stock = selectedOption.data('stock');
                const prix = selectedOption.data('prix');

                if (stock !== undefined) {
                    $('#stockInfo').text(`Stock disponible: ${stock}. Prix: ${prix} DH.`);
                    $('#quantite').attr('max', stock); // Limiter la quantité au stock
                } else {
                    $('#stockInfo').text('');
                    $('#quantite').removeAttr('max');
                }
            });
            
            // =======================================
            // CONFIRMATION DE SUPPRESSION D'ARTICLE
            // =======================================
            $('.delete-item-btn').on('click', function() {
                const itemId = $(this).data('item-id');
                
                Swal.fire({
                    title: 'Êtes-vous sûr ?',
                    text: "Vous allez supprimer cet article du reçu et remettre le stock en place!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#C2185B',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Créer un formulaire temporaire pour la requête DELETE
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ route('recus.items.remove', ['recu' => $recu->id, 'item' => '__itemId__']) }}`.replace('__itemId__', itemId);
                        
                        const csrfField = document.createElement('input');
                        csrfField.type = 'hidden';
                        csrfField.name = '_token';
                        csrfField.value = '{{ csrf_token() }}';
                        
                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';

                        form.appendChild(csrfField);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
            
            // =======================================
            // VERIFICATION STOCK AVANT AJOUT
            // =======================================
            $('#addItemForm').on('submit', function(e) {
                const selectedOption = $('#produit_id').find('option:selected');
                const stock = selectedOption.data('stock');
                const quantite = parseInt($('#quantite').val());

                if (stock !== undefined && quantite > stock) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Stock Insuffisant',
                        text: `La quantité demandée (${quantite}) dépasse le stock disponible (${stock}).`,
                        confirmButtonColor: '#D32F2F'
                    });
                }
            });
        });
    </script>
</x-app-layout>