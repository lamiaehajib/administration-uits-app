<x-app-layout>
    <div class="container py-4">
        <div class="header text-center text-white mb-4">
            Modifier l'attestation de formation ALL IN ONE
        </div>

        <div class="form-section p-4 shadow rounded bg-white">
            <form action="{{ route('attestations_allinone.update', $attestation) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Afficher le cachet -->
                <div class="mb-4">
                    <label for="afficher_cachet" class="form-label">Afficher le cachet ?</label>
                    <select name="afficher_cachet" id="afficher_cachet" class="form-control">
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
            background: linear-gradient(135deg, #C2185B, #D32F2F, #ef4444);
            font-size: 24px;
            font-weight: bold;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(194, 24, 91, 0.3);
        }

        /* Form section styling */
        .form-section {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Gradient button */
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: #fff;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
            box-shadow: 0 4px 8px rgba(194, 24, 91, 0.3);
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #ef4444, #C2185B);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.4);
        }

        /* Input fields */
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.25);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        /* Container styling */
        .container {
            max-width: 700px;
        }
    </style>
</x-app-layout>