<x-app-layout>
    <div class="container">
            <h1>Modifier la Catégorie</h1>
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom de la catégorie</label>
                    <input type="text" name="nom" id="nom" class="form-control" value="{{ $category->nom }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Retour</a>
            </form>
        </div>
    </x-app-layout>