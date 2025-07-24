<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card shadow-lg p-5" style="margin-left:20px; border-radius: 12px;">
                <h1 class="text-black text-center mb-4">Ajouter un Reçu</h1>
                <form action="{{ route('ucgs.store') }}" method="POST" class="bg-white p-6 rounded shadow-sm">
                    @csrf
                    <div class="mb-4">
                        <label for="nom" class="form-label">Nom :</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" class="form-control" required>
                        @error('nom')
                            <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="prenom" class="form-label">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" class="form-control" required>
                        @error('prenom')
                            <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="equipemen" class="form-label">equipement :</label>
                        <input type="text" id="equipemen" name="equipemen" value="{{ old('equipemen') }}" class="form-control" required>
                        @error('equipemen')
                            <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="recu_garantie" class="form-label">Reçu de Garantie :</label>
                        <select id="recu_garantie" name="recu_garantie" class="form-control" required>
                            <option value="180 jours" {{ old('recu_garantie') == '180 jours' ? 'selected' : '' }}>180 jours</option>
                            <option value="90 jours" {{ old('recu_garantie') == '90 jours' ? 'selected' : '' }}>90 jours</option>
                            <option value="360 jours" {{ old('recu_garantie') == '360 jours' ? 'selected' : '' }}>360 jours</option>
                        </select>
                        @error('recu_garantie')
                            <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="montant_paye" class="form-label">Montant Payé :</label>
                        <input type="number" id="montant_paye" name="montant_paye" value="{{ old('montant_paye') }}" step="0.01" class="form-control" required>
                        @error('montant_paye')
                            <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="date_paiement" class="form-label">Date de Paiement :</label>
                        <input type="date" id="date_paiement" name="date_paiement" value="{{ old('date_paiement') }}" class="form-control" required>
                        @error('date_paiement')
                            <p class="text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="details" class="form-label">Détails (optionnel) :</label>
                        <textarea id="details" name="details" class="form-control">{{ old('details') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end space-x-4">
                        <button type="submit" class="btn btn-danger">Ajouter</button>
                        <a href="{{ route('ucgs.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
