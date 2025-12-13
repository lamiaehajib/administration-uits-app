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
                                        <th>Produit</th>
                                        <th>Référence</th>
                                        <th>Qté</th>
                                        <th>Prix Unit.</th>
                                        <th>Sous-total</th>
                                        @can('produit-rapport')
                                            <th>Marge</th>
                                        @endcan
                                        @if($recu->statut == 'en_cours')
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recu->items as $item)
                                        <tr>
                                            <td>{{ $item->produit_nom }}</td>
                                            <td class="text-muted">{{ $item->produit_reference }}</td>
                                            <td><span class="badge bg-secondary">{{ $item->quantite }}</span></td>
                                            <td>{{ number_format($item->prix_unitaire, 2) }} DH</td>
                                            <td class="fw-bold">{{ number_format($item->sous_total, 2) }} DH</td>
                                            @can('produit-rapport')
                                                <td>
                                                    <span class="text-success">
                                                        {{ number_format($item->marge_totale, 2) }} DH
                                                    </span>
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
                                            <td colspan="{{ auth()->user()->can('produit-rapport') ? '7' : '6' }}" class="text-center py-3 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p>Aucun article</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
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
                                            <td colspan="4" class="text-center py-3 text-muted">
                                                <i class="fas fa-wallet fa-2x mb-2"></i>
                                                <p>Aucun paiement enregistré</p>
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
                        <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Résumé</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total:</span>
                            <strong>{{ number_format($recu->sous_total, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Remise:</span>
                            <strong class="text-danger">-{{ number_format($recu->remise, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>TVA:</span>
                            <strong>{{ number_format($recu->tva, 2) }} DH</strong>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="h5">Total:</span>
                            <strong class="h5 hight">{{ number_format($recu->total, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Montant Payé:</span>
                            <strong class="text-success">{{ number_format($recu->montant_paye, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="h6">Reste à payer:</span>
                            <strong class="h6 text-danger">{{ number_format($recu->reste, 2) }} DH</strong>
                        </div>
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
                                        <option value="en_cours" {{ $recu->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="livre" {{ $recu->statut == 'livre' ? 'selected' : '' }}>Livré</option>
                                        <option value="annule" {{ $recu->statut == 'annule' ? 'selected' : '' }}>Annulé</option>
                                        <option value="retour" {{ $recu->statut == 'retour' ? 'selected' : '' }}>Retour</option>
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
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
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
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
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
        // Initialiser Select2
        $(document).ready(function() {
            $('.select2').select2({
                dropdownParent: $('#addItemModal'),
                placeholder: '-- Choisir un produit --',
                width: '100%'
            });
        });

        // Confirmation suppression item
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
    </script>
    @endpush
</x-app-layout>