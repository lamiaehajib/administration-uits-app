<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-info-circle me-2"></i>Informations Générales
                </h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Type:</td>
                        <td>
                            <span class="badge bg-primary">{{ $depense->type_libelle }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Libellé:</td>
                        <td class="fw-bold">{{ $depense->libelle_complet }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Description:</td>
                        <td>{{ $depense->description ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Statut:</td>
                        <td>{!! $depense->statut_badge !!}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-coins me-2"></i>Montants et Période
                </h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Montant Mensuel:</td>
                        <td>
                            <span class="fw-bold text-danger fs-5">{{ number_format($depense->montant_mensuel, 2) }} DH</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date Début:</td>
                        <td class="fw-bold">{{ $depense->date_debut->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date Fin:</td>
                        <td class="fw-bold">{{ $depense->date_fin ? $depense->date_fin->format('d/m/Y') : 'Indéterminée' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total Période:</td>
                        <td class="fw-bold text-success">{{ number_format($montantTotal, 2) }} DH</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-bell me-2"></i>Rappels de Paiement
                </h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Rappel Actif:</td>
                        <td>
                            @if($depense->rappel_actif)
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Oui
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-times"></i> Non
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jour de Paiement:</td>
                        <td class="fw-bold">{{ $depense->jour_paiement }} du mois</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Rappel avant:</td>
                        <td>{{ $depense->rappel_avant_jours }} jour(s)</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Prochain paiement:</td>
                        <td class="fw-bold text-warning">
                            {{ now()->day($depense->jour_paiement)->format('d/m/Y') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-file-contract me-2"></i>Contrat
                </h6>
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="40%">Référence:</td>
                        <td class="fw-bold">{{ $depense->reference_contrat ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fichier:</td>
                        <td>
                            @if($depense->fichier_contrat)
                                <a href="{{ Storage::url($depense->fichier_contrat) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf me-1"></i>Télécharger PDF
                                </a>
                            @else
                                <span class="text-muted">Aucun fichier</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-history me-2"></i>Informations Système
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Créé par:</small>
                        <p class="mb-2">
                            <i class="fas fa-user me-1"></i>
                            {{ $depense->createdBy->name ?? 'N/A' }}
                            <small class="text-muted">le {{ $depense->created_at->format('d/m/Y H:i') }}</small>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Modifié par:</small>
                        <p class="mb-0">
                            <i class="fas fa-user-edit me-1"></i>
                            {{ $depense->updatedBy->name ?? 'N/A' }}
                            <small class="text-muted">le {{ $depense->updated_at->format('d/m/Y H:i') }}</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($historiquePaiements && count($historiquePaiements) > 0)
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-calendar-check me-2"></i>Historique des Paiements
                </h6>
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Mode</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historiquePaiements as $paiement)
                            <tr>
                                <td>{{ $paiement->date }}</td>
                                <td>{{ number_format($paiement->montant, 2) }} DH</td>
                                <td>{{ $paiement->mode }}</td>
                                <td><span class="badge bg-success">Payé</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="mt-3 d-flex justify-content-between">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times me-2"></i>Fermer
    </button>
    <div>
        <button type="button" class="btn btn-warning" onclick="editDepense({{ $depense->id }}); $('#showModal').modal('hide');">
            <i class="fas fa-edit me-2"></i>Modifier
        </button>
        <button type="button" class="btn btn-danger" onclick="deleteDepense({{ $depense->id }}); $('#showModal').modal('hide');">
            <i class="fas fa-trash me-2"></i>Supprimer
        </button>
    </div>
</div>