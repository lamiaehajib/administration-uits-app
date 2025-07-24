<x-app-layout>
    

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card shadow-lg p-5" style="margin-left:20px; border-radius: 12px;">
                <h1 class="text-black text-center mb-4">Ajouter une Reçu</h1>
                <form action="{{ route('reussites.store') }}" method="POST" class="bg-white p-6 rounded shadow-sm">
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
                        <label for="duree_stage" class="form-label">Durée de Stage :</label>
                        <input type="text" id="duree_stage" name="duree_stage" value="{{ old('duree_stage') }}" class="form-control" required>
                        @error('duree_stage')
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
                        <label for="rest" class="form-label">Rest Payé :</label>
                        <input type="number" id="rest" name="rest" value="{{ old('rest') }}" step="0.01" class="form-control" required>
                        @error('rest')
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
                        <label for="prochaine_paiement" class="form-label">Prochaine Paiement (optionnel) :</label>
                        <input type="date" id="prochaine_paiement" name="prochaine_paiement" value="{{ old('prochaine_paiement') }}" class="form-control">
                    </div>

                    <div class="mb-4">
                        <label for="CIN" class="form-label">CIN :</label>
                        <input type="text" id="CIN" name="CIN" value="{{ old('CIN') }}" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label for="tele" class="form-label">Téléphone :</label>
                        <input type="text" id="tele" name="tele" value="{{ old('tele') }}" class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label for="gmail" class="form-label">Gmail :</label>
                        <input type="email" id="gmail" name="gmail" value="{{ old('gmail') }}" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-end space-x-4">
                        <button type="submit" class="btn btn-danger">Ajouter</button>
                        <a href="{{ route('reussites.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
