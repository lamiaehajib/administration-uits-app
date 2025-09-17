<x-app-layout>
<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Devis</h2>

<p class="alert alert-info">Cette liste contient tous les devis qui ont été supprimés (Suppression logique). Vous pouvez soit les restaurer, soit les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>N° Devis</th>
            <th>Client</th>
            <th>Titre</th>
            <th>Total TTC</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($devis as $devisItem)
            <tr>
                <td>{{ $devisItem->devis_num }}</td>
                <td>{{ $devisItem->client }}</td>
                <td>{{ $devisItem->titre }}</td>
                <td>{{ number_format($devisItem->total_ttc, 2) }} {{ $devisItem->currency }}</td>
                <td>{{ $devisItem->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('devis.show', $devisItem->id) }}" class="btn btn-info btn-sm" title="Voir" style="display:inline-block;">
                        <i class="fas fa-eye"></i> Voir
                    </a>
                    
                    <form method="POST" action="{{ route('devis.restore', $devisItem->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('devis.forceDelete', $devisItem->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Devis DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($devis->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>