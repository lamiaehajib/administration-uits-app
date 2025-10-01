<x-app-layout>
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }

        h2 {
            color: #4b4b4b;
            text-transform: uppercase;
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }

        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #4b4b4b;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus {
            border-color: #4caf50;
            outline: none;
        }

        .error {
            color: #e74c3c;
            font-size: 0.875rem;
        }

        button[type="submit"] {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 12px 16px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        /* a {
            display: inline-block;
            margin-top: 10px;
            background-color: #888;
            color: white;
            padding: 10px 15px;
            font-size: 1rem;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
        } */

        a:hover {
            background-color: #666;
        }

    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Modifier une Reçu') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="container">
                <form action="{{ route('reussitesf.update', $reussite->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nom" class="block text-gray-700">Nom :</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom', $reussite->nom) }}" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('nom')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="prenom" class="block text-gray-700">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $reussite->prenom) }}" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('prenom')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="formation" class="block text-gray-700">Formation :</label>
                        <input type="text" id="formation" name="formation" value="{{ old('formation', $reussite->formation) }}" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('formation')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="montant_paye" class="block text-gray-700">Montant Payé :</label>
                        <input type="number" id="montant_paye" name="montant_paye" value="{{ old('montant_paye', $reussite->montant_paye) }}" step="0.01"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('montant_paye')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>


                     {{-- 🟢 NOUVEAU CHAMP: Mode de Paiement (Select) avec valeur pré-sélectionnée --}}
            <div class="mb-4">
                <label for="mode_paiement">Mode de Paiement :</label>
                <select id="mode_paiement" name="mode_paiement" required>
                    <option value="" disabled {{ old('mode_paiement', $reussite->mode_paiement) == '' ? 'selected' : '' }}>Choisir le mode</option>
                    <option value="espèce" {{ old('mode_paiement', $reussite->mode_paiement) == 'espèce' ? 'selected' : '' }}>Espèce</option>
                    <option value="virement" {{ old('mode_paiement', $reussite->mode_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="chèque" {{ old('mode_paiement', $reussite->mode_paiement) == 'chèque' ? 'selected' : '' }}>Chèque</option>
                </select>
                @error('mode_paiement')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            {{-- 🟢 Fin du nouveau champ --}}

                    <div class="mb-4">
                        <label for="date_paiement" class="block text-gray-700">Date de Paiement :</label>
                        <input type="date" id="date_paiement" name="date_paiement" value="{{ old('date_paiement', $reussite->date_paiement) }}" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('date_paiement')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="prochaine_paiement" class="block text-gray-700">Prochaine Paiement (optionnel) :</label>
                        <input type="date" id="prochaine_paiement" name="prochaine_paiement" value="{{ old('prochaine_paiement', $reussite->prochaine_paiement) }}" 
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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

                    <button type="submit">Mettre à jour</button>
                    <a href="{{ route('reussites.index') }}">Annuler</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
