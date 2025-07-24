<x-app-layout>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #e74c3c;
            text-align: center;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f7f7f7;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #e74c3c;
            outline: none;
            background-color: #ffffff;
            box-shadow: 0 0 5px rgba(231, 76, 60, 0.5);
        }

        button.btn-primary {
            background-color: #e74c3c;
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button.btn-primary:hover {
            background-color: #c0392b;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 15px;
            }

            h1 {
                font-size: 20px;
            }

            .form-control {
                font-size: 13px;
            }

            button.btn-primary {
                font-size: 14px;
                padding: 8px 15px;
            }
        }
    </style>

    <div class="container">
        <h1>Modifier l'Attestation</h1>
        <form action="{{ route('attestations.update', $attestation->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="afficher_cachet">Afficher le cachet ?</label>
                <select name="afficher_cachet" class="form-control">
                    <option value="1" {{ old('afficher_cachet', $attestation->afficher_cachet ?? 0) == 1 ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ old('afficher_cachet', $attestation->afficher_cachet ?? 0) == 0 ? 'selected' : '' }}>Non</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="stagiaire_name" class="form-label">Nom du stagiaire</label>
                <input type="text" name="stagiaire_name" id="stagiaire_name" class="form-control" value="{{ $attestation->stagiaire_name }}" required>
            </div>

            <div class="mb-3">
                <label for="stagiaire_cin" class="form-label">CIN du stagiaire</label>
                <input type="text" name="stagiaire_cin" id="stagiaire_cin" class="form-control" value="{{ $attestation->stagiaire_cin }}" required>
            </div>

            <div class="mb-3">
                <label for="date_debut" class="form-label">Date de début</label>
                <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ $attestation->date_debut }}" required>
            </div>

            <div class="mb-3">
                <label for="date_fin" class="form-label">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ $attestation->date_fin }}" required>
            </div>

            <div class="mb-3">
                <label for="poste" class="form-label">Poste occupé</label>
                <input type="text" name="poste" id="poste" class="form-control" value="{{ $attestation->poste }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
</x-app-layout>
