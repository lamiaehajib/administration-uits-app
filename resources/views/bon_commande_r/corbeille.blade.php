<x-app-layout>
<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Bons de Commande</h2>

<p class="alert alert-info">Cette liste contient tous les Bons de Commande qui ont été supprimés. Vous pouvez les visualiser, les restaurer, ou les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>N° Bon</th>
            <th>Prestataire</th>
            <th>Total TTC</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bons as $bon)
            <tr>
                <td>{{ $bon->bon_num }}</td>
                <td>{{ $bon->prestataire }}</td>
                <td>{{ number_format($bon->total_ttc, 2) }} DH</td>
                <td>{{ $bon->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('bon_commande_r.show', $bon->id) }}" class="btn btn-info btn-sm" title="Voir Bon" style="display:inline-block;">
                        <i class="fas fa-eye"></i> Voir
                    </a>

                    <form method="POST" action="{{ route('boncommandes.restore', $bon->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('boncommandes.forceDelete', $bon->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce Bon de Commande DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($bons->isEmpty())
            <tr>
                <td colspan="5" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>