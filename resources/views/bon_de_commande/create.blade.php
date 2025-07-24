
<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Ajouter un Bon de Commande</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('bon_de_commande.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control" value="{{ old('titre') }}" required>
                        @error('titre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fichier" class="form-label">Fichier (PDF, CSV, Excel)</label>
                        <input type="file" name="fichier" class="form-control" accept=".pdf,.csv,.xls,.xlsx" required>
                        @error('fichier')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="date_commande" class="form-label">Date de commande</label>
                        <input type="date" name="date_commande" class="form-control" value="{{ old('date_commande') }}">
                        @error('date_commande')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-success">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>