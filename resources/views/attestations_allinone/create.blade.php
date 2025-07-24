<x-app-layout>
    <div class="container">
        <h1 class="mb-4">Créer une Attestation de Formation all ine one</h1>
        <form action="{{ route('attestations_allinone.store') }}" method="POST">
            @csrf
           
            <div class="form-group">
                <label for="afficher_cachet">Afficher le cachet ?</label>
                <select name="afficher_cachet" class="form-control">
                    <option value="1">Oui</option>
                    <option value="0">Non</option>
                </select>
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
