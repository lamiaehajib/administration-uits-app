<x-app-layout>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 650px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 26px;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
        }

        .form-label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #34495e;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #dcdde1;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
            background-color: #ffffff;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.4);
        }

        button.btn-primary {
            width: 100%;
            background-color: #3498db;
            color: #fff;
            font-size: 16px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.btn-primary:hover {
            background-color: #2980b9;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 20px;
            }

            h1 {
                font-size: 22px;
            }

            button.btn-primary {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>

    <div class="container">
        
        <h1>Créer une Attestation de Stage</h1>
        <form action="{{ route('attestations.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="afficher_cachet">Afficher le cachet ?</label>
                <select name="afficher_cachet" class="form-control">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="stagiaire_name" class="form-label">Nom du stagiaire</label>
                <input type="text" name="stagiaire_name" id="stagiaire_name" class="form-control" placeholder="Entrez le nom du stagiaire" required>
            </div>
        
            <div class="mb-3">
                <label for="stagiaire_cin" class="form-label">CIN du stagiaire</label>
                <input type="text" name="stagiaire_cin" id="stagiaire_cin" class="form-control" placeholder="Entrez le CIN" required>
            </div>
        
            <div class="mb-3">
                <label for="date_debut" class="form-label">Date de début</label>
                <input type="date" name="date_debut" id="date_debut" class="form-control" required>
            </div>
        
            <div class="mb-3">
                <label for="date_fin" class="form-label">Date de fin</label>
                <input type="date" name="date_fin" id="date_fin" class="form-control" required>
            </div>
        
            <div class="mb-3">
                <label for="poste" class="form-label">Poste occupé</label>
                <input type="text" name="poste" id="poste" class="form-control" placeholder="Entrez le poste occupé" required>
            </div>
        
            <button type="submit" class="btn btn-primary">Créer l'attestation</button>
        </form>
    </div>
</x-app-layout>
