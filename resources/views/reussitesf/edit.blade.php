<x-app-layout>
    <style>
        /* Couleurs Thème Principales */
        :root {
            --color-primary-dark: #C2185B; /* Rouge foncé/vin élégant */
            --color-primary-main: #D32F2F; /* Rouge principal vif */
            --color-primary-light: #ef4444; /* Rouge plus clair pour les survols */
            --color-text-dark: #374151; /* Texte foncé */
            --color-text-light: #6b7280; /* Texte secondaire */
            --color-background-page: #f9fafb; /* Fond de page très clair */
            --color-background-card: #ffffff; /* Fond de carte blanc */
            --color-border: #e5e7eb; /* Bordures subtiles */
            --color-error: #dc2626; /* Rouge pour les erreurs */
        }

        body {
            background-color: var(--color-background-page);
            font-family: 'Inter', sans-serif; 
            margin: 0;
            padding: 0;
        }

        h2 {
            color: var(--color-primary-dark);
            text-transform: uppercase;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            letter-spacing: 0.05em;
        }

        .container {
            background: var(--color-background-card);
            padding: 40px; 
            border-radius: 12px; 
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08); 
            border-left: 5px solid var(--color-primary-main); /* Accent de couleur */
        }

        .mb-4 {
            margin-bottom: 24px; 
        }

        label {
            display: block;
            color: var(--color-text-dark);
            margin-bottom: 8px;
            font-weight: 600; 
            font-size: 0.95rem;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="email"],
        select { 
            width: 100%;
            padding: 14px 18px; 
            border: 1px solid var(--color-border);
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            color: var(--color-text-dark);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            -webkit-appearance: none; 
            -moz-appearance: none; 
            appearance: none; 
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        input[type="email"]:focus,
        select:focus {
            border-color: var(--color-primary-main);
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1); 
            outline: none;
        }
        
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%23D32F2F'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.5em;
            padding-right: 3rem; 
        }

        .error {
            color: var(--color-error);
            font-size: 0.875rem;
            margin-top: 4px;
        }

        /* Style pour les deux boutons */
        button[type="submit"],
        .btn-cancel {
            display: inline-block;
            border: none;
            padding: 14px 20px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            margin-top: 10px; 
        }
        
        /* Bouton de soumission (Mettre à jour) */
        button[type="submit"] {
            background-color: var(--color-primary-main); 
            color: white;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }

        button[type="submit"]:hover {
            background-color: var(--color-primary-light); 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
        
        /* Bouton Annuler */
        .btn-cancel {
            background-color: var(--color-border);
            color: var(--color-text-dark);
        }

        .btn-cancel:hover {
            background-color: #d1d5db; 
            transform: translateY(-1px);
        }

        /* Disposition des boutons */
        .button-group {
            display: flex;
            gap: 15px; 
            margin-top: 30px;
        }
        
        .button-group > * {
            flex: 1; 
        }

    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('MODIFIER UN REÇU') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> 
            <div class="container">
                <form action="{{ route('reussitesf.update', $reussite->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"> 

                        {{-- Nom --}}
                        <div class="mb-4">
                            <label for="nom">Nom :</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom', $reussite->nom) }}" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('nom')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Prénom --}}
                        <div class="mb-4">
                            <label for="prenom">Prénom :</label>
                            <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $reussite->prenom) }}" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('prenom')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- CIN --}}
                        <div class="mb-4">
                            <label for="CIN">CIN :</label>
                            <input type="text" id="CIN" name="CIN" value="{{ old('CIN', $reussite->CIN) }}">
                             @error('CIN')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Téléphone --}}
                        <div class="mb-4">
                            <label for="tele">Téléphone :</label>
                            <input type="text" id="tele" name="tele" value="{{ old('tele', $reussite->tele) }}">
                             @error('tele')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Gmail --}}
                        <div class="mb-4 col-span-1 md:col-span-2"> 
                            <label for="gmail">Gmail :</label>
                            <input type="email" id="gmail" name="gmail" value="{{ old('gmail', $reussite->gmail) }}">
                             @error('gmail')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Formation --}}
                        <div class="mb-4 col-span-1 md:col-span-2"> 
                            <label for="formation">Formation :</label>
                            <input type="text" id="formation" name="formation" value="{{ old('formation', $reussite->formation) }}" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('formation')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Montant Payé --}}
                        <div class="mb-4">
                            <label for="montant_paye">Montant Payé :</label>
                            <input type="number" id="montant_paye" name="montant_paye" value="{{ old('montant_paye', $reussite->montant_paye) }}" step="0.01"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('montant_paye')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        {{-- Mode de Paiement --}}
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

                        {{-- Date de Paiement --}}
                        <div class="mb-4">
                            <label for="date_paiement">Date de Paiement :</label>
                            <input type="date" id="date_paiement" name="date_paiement" value="{{ old('date_paiement', $reussite->date_paiement) }}" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('date_paiement')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Prochaine Paiement --}}
                        <div class="mb-4">
                            <label for="prochaine_paiement">Prochain Paiement (Optionnel) :</label>
                            <input type="date" id="prochaine_paiement" name="prochaine_paiement" value="{{ old('prochaine_paiement', $reussite->prochaine_paiement) }}" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        {{-- Reste --}}
                        <div class="mb-4 col-span-1 md:col-span-2"> 
                            <label for="rest">Reste :</label>
                            <input type="number" id="rest" name="rest" value="{{ old('rest', $reussite->rest) }}" step="0.01"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('rest')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        
                    </div>

                    <div class="button-group">
                        <button type="submit">Mettre à jour</button>
                        <a href="{{ route('reussitesf.index') }}" class="btn-cancel">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>