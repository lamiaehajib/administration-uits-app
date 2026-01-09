<x-app-layout>
    <div class="container-fluid">
        <!-- En-tête -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3><i class="fas fa-receipt me-2"></i>Détails du Reçu</h3>
                <p class="text-muted mb-0">{{ $recu->numero_recu }}</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('recus.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                @if($recu->statut == 'en_cours')
                    <a href="{{ route('recus.edit', $recu) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Modifier
                    </a>
                @endif
                <a href="{{ route('recus.print', $recu) }}" class="btn btn-info" target="_blank">
                    <i class="fas fa-print me-2"></i>Imprimer
                </a>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Colonne gauche: Info client + Items -->
            <div class="col-lg-8">
                <!-- Informations Client -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Informations Client</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-user me-2 text-muted"></i>Nom:</strong> {{ $recu->client_nom }} {{ $recu->client_prenom }}</p>
                                <p><strong><i class="fas fa-phone me-2 text-muted"></i>Téléphone:</strong> {{ $recu->client_telephone ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="fas fa-envelope me-2 text-muted"></i>Email:</strong> {{ $recu->client_email ?? '-' }}</p>
                                <p><strong><i class="fas fa-map-marker-alt me-2 text-muted"></i>Adresse:</strong> {{ $recu->client_adresse ?? '-' }}</p>
                            </div>
                        </div>
                        @if($recu->equipement)
                            <p class="mb-0"><strong><i class="fas fa-laptop me-2 text-muted"></i>Équipement:</strong> {{ $recu->equipement }}</p>
                        @endif
                    </div>
                </div>

                <!-- Articles -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-box me-2"></i>Articles</h5>
                        @if($recu->statut == 'en_cours')
                            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="fas fa-plus me-1"></i>Ajouter
                            </button>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light">
            <tr>
                @if($recu->remise > 0 && $recu->statut == 'en_cours')
                    <th width="50">
                        <i class="fas fa-tag text-warning" 
                           title="Appliquer la remise"></i>
                    </th>
                @endif
                <th>#</th>
                <th>Produit</th>
                <th>Qté</th>
                <th>Prix Unit.</th>
                <th>Sous-total</th>
                @can('produit-rapport')
                    <th>Marge Totale</th>
                @endcan
                @if($recu->statut == 'en_cours')
                    <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($recu->items as $index => $item)
                <tr class="{{ $item->remise_appliquee && $recu->remise > 0 ? 'table-warning' : '' }}">
                    @if($recu->remise > 0 && $recu->statut == 'en_cours')
                        <td class="text-center">
                            <input type="radio" 
                                   name="remise_item" 
                                   value="{{ $item->id }}"
                                   {{ $item->remise_appliquee ? 'checked' : '' }}
                                   onchange="appliquerRemiseSurItem({{ $recu->id }}, {{ $item->id }})"
                                   class="form-check-input"
                                   title="Appliquer la remise sur cet article">
                        </td>
                    @endif
                    <td>
                        {{ $index + 1 }}
                        @if($item->remise_appliquee && $recu->remise > 0)
                            <span class="badge bg-warning text-dark ms-1" 
                                  title="Remise appliquée">
                                <i class="fas fa-tag"></i>
                            </span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $item->produit_nom }}</strong>
                        @if($item->designation)
                            <br><small class="text-muted">{{ $item->designation }}</small>
                        @endif
                        @if($item->produit_reference)
                            <br><small class="text-muted">Réf: {{ $item->produit_reference }}</small>
                        @endif
                    </td>
                    <td><span class="badge bg-secondary">{{ $item->quantite }}</span></td>
                    <td>{{ number_format($item->prix_unitaire, 2) }} DH</td>
                    <td>
                        <strong>{{ number_format($item->sous_total, 2) }} DH</strong>
                        @if($item->remise_appliquee && $recu->remise > 0)
                            <br>
                            <small class="text-danger">
                                - {{ number_format($recu->remise, 2) }} DH (remise)
                            </small>
                        @endif
                    </td>
                    @can('produit-rapport')
                        <td>
                            <div class="text-success">
                                <strong>{{ number_format($item->marge_totale, 2) }} DH</strong>
                                <br>
                                <small class="text-muted">
                                    ({{ number_format($item->marge_unitaire, 2) }} DH/u)
                                </small>
                                @if($item->remise_appliquee && $recu->remise > 0)
                                    <br>
                                    <small class="text-danger">
                                        Après remise: {{ number_format($item->margeApresRemise(), 2) }} DH
                                    </small>
                                @endif
                            </div>
                        </td>
                    @endcan
                    @if($recu->statut == 'en_cours')
                        <td>
                            <button class="btn btn-sm btn-danger" 
                                    onclick="confirmDeleteItem({{ $recu->id }}, {{ $item->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-item-form-{{ $item->id }}" 
                                  action="{{ route('recus.items.remove', [$recu, $item]) }}" 
                                  method="POST" 
                                  style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <p class="mb-0">Aucun article dans ce reçu</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
        @can('produit-rapport')
        <tfoot class="table-light">
            <tr>
                <td colspan="{{ $recu->remise > 0 && $recu->statut == 'en_cours' ? 6 : 5 }}" 
                    class="text-end fw-bold">
                    <i class="fas fa-chart-line me-2"></i>Total Marges:
                </td>
                <td colspan="2">
                    <div class="text-success fw-bold">
                        {{ number_format($recu->margeGlobale(), 2) }} DH
                        @if($recu->remise > 0)
                            <br>
                            <small class="text-danger">
                                Après remise: {{ number_format($recu->margeApresRemise(), 2) }} DH
                            </small>
                        @endif
                    </div>
                </td>
            </tr>
        </tfoot>
        @endcan
    </table>
</div>
                    </div>
                </div>

                <!-- Paiements -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Historique des Paiements</h5>
                        @if($recu->reste > 0 && $recu->statut != 'annule')
                            <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addPaiementModal">
                                <i class="fas fa-plus me-1"></i>Nouveau Paiement
                            </button>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Mode</th>
                                        <th>Utilisateur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recu->paiements as $paiement)
                                        <tr>
                                            <td>{{ $paiement->date_paiement->format('d/m/Y H:i') }}</td>
                                            <td class="fw-bold text-success">{{ number_format($paiement->montant, 2) }} DH</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst($paiement->mode_paiement) }}
                                                </span>
                                            </td>
                                            <td>{{ $paiement->user->name ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="fas fa-wallet fa-3x mb-3 d-block"></i>
                                                <p class="mb-0">Aucun paiement enregistré</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite: Résumé + Actions -->
            <div class="col-lg-4">
                <!-- Résumé Financier -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Résumé Financier</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total:</span>
                            <strong>{{ number_format($recu->sous_total, 2) }} DH</strong>
                        </div>
                        @if($recu->remise > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                <i class="fas fa-tag text-warning me-1"></i>Remise:
                                <small class="text-muted d-block">Sur 1er article</small>
                            </span>
                            <strong class="text-danger">-{{ number_format($recu->remise, 2) }} DH</strong>
                        </div>
                        @endif
                        @if($recu->tva > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>TVA:</span>
                            <strong>+{{ number_format($recu->tva, 2) }} DH</strong>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5 mb-0">Total:</span>
                            <strong class="h5 text-primary mb-0">{{ number_format($recu->total, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Montant Payé:</span>
                            <strong class="text-success">{{ number_format($recu->montant_paye, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h6 mb-0">Reste à payer:</span>
                            <strong class="h6 {{ $recu->reste > 0 ? 'text-danger' : 'text-success' }} mb-0">
                                {{ number_format($recu->reste, 2) }} DH
                            </strong>
                        </div>
                        
                        @can('produit-rapport')
                        <hr>
                        <div class="bg-light p-3 rounded">
                            <h6 class="mb-3">
                                <i class="fas fa-chart-line me-2 text-success"></i>Analyse de Marge
                            </h6>
                            
                            <!-- Marge Brute (avant remise) -->
                            <div class="d-flex justify-content-between mb-2">
                                <span>
                                    <i class="fas fa-coins me-1 text-muted"></i>Marge Brute:
                                    <small class="text-muted d-block">Prix vente - Prix achat</small>
                                </span>
                                <strong class="text-success">
                                    {{ number_format($recu->margeGlobale(), 2) }} DH
                                </strong>
                            </div>
                            
                            @if($recu->remise > 0)
                            <!-- Impact de la remise -->
                            <div class="d-flex justify-content-between mb-2">
                                <span>
                                    <i class="fas fa-tag me-1 text-warning"></i>Impact Remise:
                                </span>
                                <strong class="text-danger">
                                    -{{ number_format($recu->remise, 2) }} DH
                                </strong>
                            </div>
                            
                            <!-- Marge Nette (après remise) -->
                            <div class="d-flex justify-content-between mb-2 border-top pt-2">
                                <span>
                                    <i class="fas fa-hand-holding-usd me-1 text-success"></i>Marge Nette:
                                    <small class="text-muted d-block">Après remise</small>
                                </span>
                                <strong class="text-success fw-bold">
                                    {{ number_format($recu->margeApresRemise(), 2) }} DH
                                </strong>
                            </div>
                            @endif
                            
                            <!-- Taux de marge -->
                            <div class="d-flex justify-content-between border-top pt-2">
                                <span>
                                    <i class="fas fa-percent me-1 text-info"></i>Taux de Marge:
                                </span>
                                <strong class="text-info">
                                    @if($recu->remise > 0)
                                        {{ number_format($recu->tauxMargeReel(), 2) }}%
                                    @else
                                        {{ number_format($recu->tauxMarge(), 2) }}%
                                    @endif
                                </strong>
                            </div>
                            
                            <!-- Note explicative -->
                            <div class="alert alert-info mt-3 mb-0 py-2">
                                <small>
                                    <i class="fas fa-info-circle me-1"></i>
                                    @if($recu->remise > 0)
                                        La remise réduit le prix de vente, donc impacte la marge du 1er article uniquement.
                                    @else
                                        Marges calculées sur (Prix Vente - Prix Achat) × Quantité
                                    @endif
                                </small>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>

                <!-- Statuts et Garantie -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations</h5>
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Statut:</strong>
                            @if($recu->statut == 'en_cours')
                                <span class="badge bg-info">En cours</span>
                            @elseif($recu->statut == 'livre')
                                <span class="badge bg-success">Livré</span>
                            @elseif($recu->statut == 'annule')
                                <span class="badge bg-secondary">Annulé</span>
                            @else
                                <span class="badge bg-warning">Retour</span>
                            @endif
                        </p>
                        <p>
                            <strong>Paiement:</strong>
                            @if($recu->statut_paiement == 'paye')
                                <span class="badge bg-success">Payé</span>
                            @elseif($recu->statut_paiement == 'partiel')
                                <span class="badge bg-warning">Partiel</span>
                            @else
                                <span class="badge bg-danger">Impayé</span>
                            @endif
                        </p>
                        <p>
                            <strong>Garantie:</strong>
                            <span class="badge {{ $recu->isGarantieValide() ? 'bg-success' : 'bg-secondary' }}">
                                {{ $recu->date_garantie_fin ? $recu->date_garantie_fin->format('d/m/Y') : 'Sans garantie' }}
                            </span>
                        </p>
                        <p>
                            <strong>Mode paiement:</strong>
                            <span class="badge bg-info">{{ ucfirst($recu->mode_paiement) }}</span>
                        </p>
                        <p class="mb-0">
                            <strong>Créé par:</strong> {{ $recu->user->name ?? '-' }}<br>
                            <small class="text-muted">Le {{ $recu->created_at->format('d/m/Y à H:i') }}</small>
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                @if($recu->statut == 'en_cours')
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Changer le Statut</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('recus.statut', $recu) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <select name="statut" class="form-select" required>
                                        <option value="en_cours" selected>En cours</option>
                                        <option value="livre">Livré</option>
                                        <option value="annule">Annulé</option>
                                        <option value="retour">Retour</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check me-2"></i>Mettre à jour
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal: Ajouter un article -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('recus.items.add', $recu) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Ajouter un Article</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Produit *</label>
                            <select name="produit_id" class="form-select select2" required>
                                <option value="">-- Choisir un produit --</option>
                                @foreach(\App\Models\Produit::where('actif', true)->where('quantite_stock', '>', 0)->get() as $produit)
                                    <option value="{{ $produit->id }}">
                                        {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }}) - {{ number_format($produit->prix_vente, 2) }} DH
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantité *</label>
                            <input type="number" name="quantite" class="form-control" min="1" value="1" required>
                        </div>
                        @if($recu->remise > 0 && $recu->items->count() === 0)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <small>La remise de {{ number_format($recu->remise, 2) }} DH sera appliquée sur ce premier article</small>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Ajouter un paiement -->
    <div class="modal fade" id="addPaiementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('paiements.store', $recu) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-money-bill-wave me-2"></i>Nouveau Paiement</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Reste à payer: <strong>{{ number_format($recu->reste, 2) }} DH</strong>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Montant *</label>
                            <input type="number" name="montant" class="form-control" 
                                   step="0.01" min="0.01" max="{{ $recu->reste }}" 
                                   value="{{ $recu->reste }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mode de paiement *</label>
                            <select name="mode_paiement" class="form-select" required>
                                <option value="especes">Espèces</option>
                                <option value="carte">Carte bancaire</option>
                                <option value="cheque">Chèque</option>
                                <option value="virement">Virement</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date de paiement *</label>
                            <input type="date" name="date_paiement" class="form-control" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Référence</label>
                            <input type="text" name="reference" class="form-control" 
                                   placeholder="N° chèque, référence...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dropdownParent: $('#addItemModal'),
                placeholder: '-- Choisir un produit --',
                width: '100%'
            });
        });

        function confirmDeleteItem(recuId, itemId) {
            Swal.fire({
                title: 'Supprimer cet article?',
                text: "Le stock sera automatiquement retourné",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Oui, supprimer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-item-form-' + itemId).submit();
                }
            });
        }

        function appliquerRemiseSurItem(recuId, itemId) {
    // Afficher un loader
    Swal.fire({
        title: 'Application de la remise...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/recus/${recuId}/items/${itemId}/appliquer-remise`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Remise appliquée!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // Recharger la page pour mettre à jour l'affichage
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: data.message
            });
            // Remettre l'ancien radio checked
            location.reload();
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue'
        });
        location.reload();
    });
}
    </script>
    @endpush
</x-app-layout>