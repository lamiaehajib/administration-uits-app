<x-app-layout>
    <style>
        body {
            background-color: #f9fafa;
            font-family: Arial, sans-serif;
            color: #334155;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 1.75rem;
            font-weight: bold;
            color: #1e293b;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #475569;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            color: #334155;
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 4px rgba(79, 70, 229, 0.5);
            background-color: #ffffff;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            font-weight: bold;
            padding: 12px 16px;
            font-size: 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: scale(1.02);
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 6px rgba(67, 56, 202, 0.7);
        }

        .mb-3 {
            margin-bottom: 20px;
        }

        .mb-3:last-of-type {
            margin-bottom: 30px;
        }
    </style>

    <div class="container">
        <h1>Créer une Attestation de Formation</h1>
        <form action="{{ route('attestations_formation.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="afficher_cachet">Afficher le cachet ?</label>
                <select name="afficher_cachet" class="form-control">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="formation_name" class="form-label">Nom de la formation</label>
                <input type="text" name="formation_name" id="formation_name" class="form-control" placeholder="Entrez le nom de la formation" required>
            </div>

            <div class="mb-3">
                <label for="personne_name" class="form-label">Nom de la personne</label>
                <input type="text" name="personne_name" id="personne_name" class="form-control" placeholder="Entrez le nom de la personne" required>
            </div>
            <div class="mb-3">
                <label for="cin" class="form-label">CIN </label>
                <input type="text" name="cin" id="cin" class="form-control" placeholder="Entrez le CIN" required>
            </div>
            <div class="form-group">
                <label for="numero_de_serie">Numéro de Série</label>
                <input 
                    type="text" 
                    name="numero_de_serie" 
                    id="numero_de_serie" 
                    class="form-control" 
                    value="{{ old('numero_de_serie') }}" 
                    readonly>
            </div>

            <button type="submit" class="btn btn-primary">Créer l'attestation</button>
        </form>
    </div>
</x-app-layout>
