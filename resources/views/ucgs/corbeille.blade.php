<x-app-layout>
<h2 class="text-danger"><i class="fa fa-trash"></i> Corbeille UCG</h2>

<p class="alert alert-info">Cette liste contient tous les éléments UCG qui ont été supprimés. Vous pouvez les restaurer, ou les supprimer définitivement.</p>

<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th>Nom & Prénom</th>
            <th>Réf. Garantie</th>
            <th>Montant Payé</th>
            <th>Date d'Effacement</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ucg as $item)
            <tr>
                <td>{{ $item->nom }} {{ $item->prenom }}</td>
                <td>{{ $item->recu_garantie }}</td>
                <td>{{ number_format($item->montant_paye, 2) }} DH</td>
                <td>{{ $item->deleted_at->format('Y-m-d H:i') }}</td>
                <td>

    <a href="{{ route('ucg.pdf', $item->id) }}" class="btn btn-warning btn-sm" title="Télécharger Reçu" style="display:inline-block;">
        <i class="fa fa-file-pdf"></i> PDF
    </a>
    
   

                    <form method="POST" action="{{ route('ucg.restore', $item->id) }}" style="display:inline-block;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-sm btn-success" title="Restaurer">
                            <i class="fa fa-undo"></i> Restaurer
                        </button>
                    </form>

                    <form method="POST" action="{{ route('ucg.forceDelete', $item->id) }}" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet élément UCG DÉFINITIVEMENT ? Cette action est irréversible.');" 
                                title="Supprimer Définitivement">
                            <i class="fa fa-times"></i> Supprimer Déf.
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        
        @if($ucg->isEmpty())
            <tr>
                <td colspan="5" class="text-center text-muted">La corbeille est vide pour le moment.</td>
            </tr>
        @endif
    </tbody>
</table>
</x-app-layout>