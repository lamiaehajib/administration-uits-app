<x-app-layout>
    <div class="container mt-5 d-flex justify-content-center">
        <div class="card shadow-lg rounded p-4" style="max-width: 500px; width: 100%;">
            <div class="card-header bg-primary text-white text-center">
                <h2 class="mb-0">Ajouter une Catégorie</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nom" class="form-label fw-bold">Nom de la catégorie</label>
                        <input type="text" name="nom" id="nom" class="form-control" placeholder="Entrez le nom" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
