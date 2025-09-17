<x-app-layout>
<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Réussites (F)</h2>

<p class="alert alert-info">Cette liste contient tous les éléments de Réussite qui ont été supprimés. Vous pouvez soit les restaurer, soit les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Nom & Prénom</th>
            <th>Formation</th>
            <th>Tél. / Gmail</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($reussitef as $item)
            <tr>
                <td>{{ $item->nom }} {{ $item->prenom }}</td>
                <td>{{ $item->formation }}</td>
                <td>{{ $item->tele }} / {{ $item->gmail }}</td>
                <td>{{ $item->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                    <form method="POST" action="{{ route('reussitef.restore', $item->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('reussitef.forceDelete', $item->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($reussitef->isEmpty())
            <tr>
                <td colspan="5" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>