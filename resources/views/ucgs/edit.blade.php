<x-app-layout>
    <style>
        body {
            background-color: #f7f8fc;
            font-family: 'Inter', sans-serif;
            color: #374151;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.75rem;
            font-weight: bold;
            color: #1f2937;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 8px;
        }

        input[type="text"], 
        input[type="date"], 
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: #f9fafb;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus, textarea:focus {
            border-color: #2563eb;
            background-color: #ffffff;
            outline: none;
            box-shadow: 0 0 3px rgba(37, 99, 235, 0.4);
        }

        .error {
            font-size: 12px;
            color: #dc2626;
            margin-top: 5px;
        }

        button {
            background-color: #2563eb;
            color: #ffffff;
            font-weight: 600;
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background-color: #1e40af;
            transform: scale(1.02);
        }

        .btn-cancel {
            background-color: #6b7280;
            color: #ffffff;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background-color: #4b5563;
        }

        .form-actions {
            text-align: right;
        }

        .mb-4 {
            margin-bottom: 16px;
        }
    </style>

    <div class="py-12">
        <div class="container">
            <div class="header">
                <h2>{{ __('Modifier un UCG') }}</h2>
            </div>

            <form action="{{ route('ucgs.update', $ucg->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $ucg->nom) }}" required>
                    @error('nom') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $ucg->prenom) }}" required>
                    @error('prenom') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="equipemen">Équipement :</label>
                    <input type="text" id="equipemen" name="equipemen" value="{{ old('equipemen', $ucg->equipemen) }}" required>
                    @error('equipemen') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="recu_garantie" class="form-label">Reçu de Garantie :</label>
                    <select id="recu_garantie" name="recu_garantie" class="form-control" required>
                        <option value="180 jours" {{ old('recu_garantie', $ucg->recu_garantie) == '180 jours' ? 'selected' : '' }}>180 jours</option>
                        <option value="90 jours" {{ old('recu_garantie', $ucg->recu_garantie) == '90 jours' ? 'selected' : '' }}>90 jours</option>
                        <option value="360 jours" {{ old('recu_garantie', $ucg->recu_garantie) == '360 jours' ? 'selected' : '' }}>360 jours</option>
                    </select>
                    @error('recu_garantie') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="montant_paye">Montant Payé :</label>
                    <input type="number" id="montant_paye" name="montant_paye" value="{{ old('montant_paye', $ucg->montant_paye) }}" step="0.01" required>
                    @error('montant_paye') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="date_paiement">Date de Paiement :</label>
                    <input type="date" id="date_paiement" name="date_paiement" value="{{ old('date_paiement', $ucg->date_paiement) }}" required>
                    @error('date_paiement') <p class="error">{{ $message }}</p> @enderror
                </div>

                <div class="mb-4">
                    <label for="details">Détails (optionnel) :</label>
                    <textarea id="details" name="details">{{ old('details', $ucg->details) }}</textarea>
                </div>

                <div class="form-actions">
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('ucgs.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
