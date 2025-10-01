<x-app-layout>
    <x-slot name="header">
        <h2 class="text-center text-white py-3" style="background-color: #1976D2;">
            Ajouter une Reçu
        </h2>
    </x-slot>

    <div class="container py-5">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center">
                <h3>Ajouter une Reçu</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('reussitesf.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom :</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom') }}" 
                               class="form-control @error('nom') is-invalid @enderror">
                        @error('nom')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" 
                               class="form-control @error('prenom') is-invalid @enderror">
                        @error('prenom')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="formation" class="form-label">Formation :</label>
                        <input type="text" id="formation" name="formation" value="{{ old('formation') }}" 
                               class="form-control @error('formation') is-invalid @enderror">
                        @error('formation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="montant_paye" class="form-label">Montant Payé :</label>
                        <input type="number" id="montant_paye" name="montant_paye" value="{{ old('montant_paye') }}" step="0.01"
                               class="form-control @error('montant_paye') is-invalid @enderror">
                        @error('montant_paye')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    
                    {{-- 🟢 NOUVEAU CHAMP: Mode de Paiement (Select) --}}
                <div class="mb-4">
                    <label for="mode_paiement" class="form-label">Mode de Paiement :</label>
                    <select id="mode_paiement" name="mode_paiement" class="form-control" required>
                        <option value="" disabled {{ old('mode_paiement') == '' ? 'selected' : '' }}>Choisir le mode</option>
                        <option value="espèce" {{ old('mode_paiement') == 'espèce' ? 'selected' : '' }}>Espèce</option>
                        <option value="virement" {{ old('mode_paiement') == 'virement' ? 'selected' : '' }}>Virement</option>
                        <option value="chèque" {{ old('mode_paiement') == 'chèque' ? 'selected' : '' }}>Chèque</option>
                    </select>
                    @error('mode_paiement')
                        <p class="text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
                {{-- 🟢 Fin du nouveau champ --}}


                    <div class="mb-3">
                        <label for="date_paiement" class="form-label">Date de Paiement :</label>
                        <input type="date" id="date_paiement" name="date_paiement" value="{{ old('date_paiement') }}" 
                               class="form-control @error('date_paiement') is-invalid @enderror">
                        @error('date_paiement')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="prochaine_paiement" class="form-label">Prochaine Paiement (optionnel) :</label>
                        <input type="date" id="prochaine_paiement" name="prochaine_paiement" value="{{ old('prochaine_paiement') }}" 
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="rest" class="form-label">Rest :</label>
                        <input type="number" id="rest" name="rest" value="{{ old('rest') }}" step="0.01"
                               class="form-control @error('rest') is-invalid @enderror">
                        @error('rest')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="CIN" class="form-label">CIN :</label>
                        <input type="text" id="CIN" name="CIN" value="{{ old('CIN') }}" 
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="tele" class="form-label">Téléphone :</label>
                        <input type="text" id="tele" name="tele" value="{{ old('tele') }}" 
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label for="gmail" class="form-label">Gmail :</label>
                        <input type="email" id="gmail" name="gmail" value="{{ old('gmail') }}" 
                               class="form-control">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success">Ajouter</button>
                        <a href="{{ route('reussites.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
