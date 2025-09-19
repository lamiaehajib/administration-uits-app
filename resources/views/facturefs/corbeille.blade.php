<x-app-layout>

<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille des Factures (F)</h2>

<p class="alert alert-info">Cette liste contient toutes les factures qui ont été supprimées. Vous pouvez les visualiser (PDF), les restaurer, ou les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>N° Facture</th>
            <th>Client</th>
            <th>Téléphone</th>
            <th>Total TTC</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($factures as $facture)
            <tr>
                <td>{{ $facture->facturef_num }}</td>
                <td>{{ $facture->client }}</td>
                <td>{{ $facture->tele }}</td>
                <td>{{ number_format($facture->total_ttc, 2) }} {{ $facture->currency }}</td>
                <td>{{ $facture->deleted_at->format('Y-m-d H:i') }}</td>
                <td>
                    <a href="{{ route('facturefs.show', $facture->id) }}" class="btn btn-info btn-sm" title="Voir Facture" style="display:inline-block;">
                        <i class="fas fa-eye"></i> Voir
                    </a>

                    <form method="POST" action="{{ route('facturef.restore', $facture->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('facturef.forceDelete', $facture->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette Facture DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($factures->isEmpty())
            <tr>
                <td colspan="6" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>