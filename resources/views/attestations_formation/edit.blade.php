<x-app-layout>
    <div class="header text-center text-white mb-4">
        Modifier l'attestation de formation
    </div>

    <div class="container py-5">
        <form action="{{ route('attestations_formation.update', $attestation) }}" method="POST" class="p-4 shadow rounded bg-white">
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
                    class="form-control"
                    value="{{ old('personne_name', $attestation->personne_name) }}" 
                    placeholder="Entrez le nom de la personne"
                    required>
            </div>

            <!-- Formation Name -->
            <div class="mb-4">
                <label for="formation_name" class="form-label">{{ __('Nom de la formation') }}</label>
                <input 
                    type="text" 
                    id="formation_name" 
                    name="formation_name" 
                    class="form-control"
                    value="{{ old('formation_name', $attestation->formation_name) }}" 
                    placeholder="Entrez le nom de la formation"
                    required>
            </div>

            <!-- CIN -->
            <div class="mb-4">
                <label for="cin" class="form-label">{{ __('CIN') }}</label>
                <input 
                    type="text" 
                    id="cin" 
                    name="cin" 
                    class="form-control"
                    value="{{ old('cin', $attestation->cin) }}" 
                    placeholder="Entrez le CIN"
                    required>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="btn btn-gradient text-white">
                    {{ __('Mettre à jour') }}
                </button>
            </div>
        </form>
    </div>

    <style>
         .header {
            background: linear-gradient(135deg, #f60404, #000000);
    font-size: 24px;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    width: 90%;
    margin-left: 60px;
        }
        /* Header gradient background */
        .font-semibold {
            border-radius: 8px;
            text-align: center;
        }

        /* Gradient button */
        .btn-gradient {
            background: linear-gradient(135deg, #f60404, #000000);
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #000000, #f60404);
            color: #fff;
        }

        /* Form styling */
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
