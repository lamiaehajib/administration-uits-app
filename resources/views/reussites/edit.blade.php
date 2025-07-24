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
        input[type="number"] {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: #f9fafb;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input:focus {
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
                <h2>{{ __('Modifier un Reçus') }}</h2>
            </div>

            <form action="{{ route('reussites.update', $reussite->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $reussite->nom) }}">
                    @error('nom')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $reussite->prenom) }}">
                    @error('prenom')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="duree_stage">Durée de Stage :</label>
                    <input type="text" id="duree_stage" name="duree_stage" value="{{ old('duree_stage', $reussite->duree_stage) }}">
                    @error('duree_stage')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="montant_paye">Montant Payé :</label>
                    <input type="number" id="montant_paye" name="montant_paye" step="0.01" value="{{ old('montant_paye', $reussite->montant_paye) }}">
                    @error('montant_paye')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="rest" class="block text-gray-700">rest:</label>
                    <input type="number" id="rest" name="rest" value="{{ old('rest', $reussite->rest) }}" step="0.01"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    @error('rest')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="date_paiement">Date de Paiement :</label>
                    <input type="date" id="date_paiement" name="date_paiement" value="{{ old('date_paiement', $reussite->date_paiement) }}">
                    @error('date_paiement')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="prochaine_paiement">Prochaine Paiement (optionnel) :</label>
                    <input type="date" id="prochaine_paiement" name="prochaine_paiement" value="{{ old('prochaine_paiement', $reussite->prochaine_paiement) }}">
                </div>

                <div class="mb-4">
                    <label for="CIN">CIN :</label>
                    <input type="text" id="CIN" name="CIN" value="{{ old('CIN', $reussite->CIN) }}">
                </div>
            
                <div class="mb-4">
                    <label for="tele">Téléphone :</label>
                    <input type="text" id="tele" name="tele" value="{{ old('tele', $reussite->tele) }}">
                </div>
            
                <div class="mb-4">
                    <label for="gmail">Gmail :</label>
                    <input type="email" id="gmail" name="gmail" value="{{ old('gmail', $reussite->gmail) }}">
                </div>

                <div class="form-actions">
                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('reussites.index') }}" class="btn-cancel">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
