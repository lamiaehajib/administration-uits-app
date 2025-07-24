<x-app-layout>
    <div class="container py-4">
        <div class="header text-center text-white mb-4">
            Modifier l'attestation de formation ALL IN ONE
        </div>

        <div class="form-section p-4 shadow rounded bg-white">
            <form action="{{ route('attestations_allinone.update', $attestation) }}" method="POST">
                @csrf
                @method('PUT')


                <div class="form-group">
                    <label for="afficher_cachet">Afficher le cachet ?</label>
                    <select name="afficher_cachet" class="form-control">
                        <option value="1" {{ old('afficher_cachet', $attestation->afficher_cachet ?? 0) == 1 ? 'selected' : '' }}>Oui</option>
                        <option value="0" {{ old('afficher_cachet', $attestation->afficher_cachet ?? 0) == 0 ? 'selected' : '' }}>Non</option>
                    </select>
                </div>
                <!-- Personne Name -->
                <div class="mb-4">
                    <label for="personne_name" class="form-label">{{ __('Nom de la personne') }}</label>
                    <input 
                        type="text" 
                        id="personne_name" 
                        name="personne_name" 
                        value="{{ old('personne_name', $attestation->personne_name) }}" 
                        class="form-control"
                        placeholder="Entrez le nom de la personne"
                        required
                    >
                </div>

                <!-- CIN -->
                <div class="mb-4">
                    <label for="cin" class="form-label">{{ __('CIN') }}</label>
                    <input 
                        type="text" 
                        id="cin" 
                        name="cin" 
                        value="{{ old('cin', $attestation->cin) }}" 
                        class="form-control"
                        placeholder="Entrez le CIN"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-gradient">
                        {{ __('Mettre à jour') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Header with gradient */
        .header {
            background: linear-gradient(135deg, #f60404, #000000);
            font-size: 24px;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Form section styling */
        .form-section {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Gradient button */
        .btn-gradient {
            background: linear-gradient(135deg, #f60404, #000000);
            color: #fff;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #000000, #f60404);
            color: #fff;
        }

        /* Input fields */
        .form-control {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }
    </style>
</x-app-layout>
