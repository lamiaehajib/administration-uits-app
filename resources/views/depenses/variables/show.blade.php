<div class="row g-3">
    <!-- Informations Générales -->
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
                            <span class="badge bg-warning">{{ $depense->type_libelle }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Libellé:</td>
                        <td class="fw-bold">{{ $depense->libelle }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Description:</td>
                        <td>{{ $depense->description ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Statut:</td>
                        <td>{!! $depense->statut_badge !!}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date Dépense:</td>
                        <td class="fw-bold">{{ $depense->date_depense->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Période:</td>
                        <td>
                            <span class="badge bg-info">{{ $depense->mois_nom }} {{ $depense->annee }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Montant -->
    <div class="col-md-6">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-coins me-2"></i>Montant
                </h6>
                <div class="text-center py-3">
                    <h2 class="fw-bold text-warning mb-0">{{ number_format($depense->montant, 2) }} DH</h2>
                    <small class="text-muted">Montant de la dépense</small>
                </div>
                
                @if($depense->validee_le)
                <div class="border-top pt-3 mt-3">
                    <small class="text-muted d-block">Validée par:</small>
                    <p class="mb-0">
                        <i class="fas fa-user-check text-success me-1"></i>
                        {{ $depense->valideePar->name ?? 'N/A' }}
                        <br>
                        <small class="text-muted">le {{ $depense->validee_le->format('d/m/Y H:i') }}</small>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Détails selon type -->
    @if($depense->type == 'facture_recue' && $depense->factureRecue)
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-primary mb-3">
                    <i class="fas fa-file-invoice me-2"></i>Détails Facture Reçue
                </h6>
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted">Numéro Facture:</small>
                        <p class="fw-bold">{{ $depense->factureRecue->numero_facture }}</p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Fournisseur:</small>
                        <p class="fw-bold">{{ $depense->factureRecue->nom_fournisseur }}</p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Date Facture:</small>
                        <p class="fw-bold">{{ $depense->factureRecue->date_facture->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($depense->type == 'prime')
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-success mb-3">
                    <i class="fas fa-gift me-2"></i>Détails Prime
                </h6>
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted">Employé:</small>
                        <p class="fw-bold">{{ $depense->nom_employe }}</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Poste:</small>
                        <p>{{ $depense->poste_employe }}</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Salaire de base:</small>
                        <p>{{ number_format($depense->montant_salaire, 2) }} DH</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Type Prime:</small>
                        <p class="fw-bold">{{ $depense->type_prime }}</p>
                    </div>
                    @if($depense->motif_prime)
                    <div class="col-12">
                        <small class="text-muted">Motif:</small>
                        <p>{{ $depense->motif_prime }}</p>
                    </div>
                    @endif
                </div>
                
                @if($employee)
                <div class="alert alert-info small mt-3 mb-0">
                    <strong>Informations actuelles depuis uits-mgmt.ma:</strong><br>
                    Email: {{ $employee['email'] ?? '-' }} | Tél: {{ $employee['tele'] ?? '-' }}
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if($depense->type == 'cnss')
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-info mb-3">
                    <i class="fas fa-shield-alt me-2"></i>Détails CNSS
                </h6>
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted">Salaire de base:</small>
                        <p class="fw-bold">{{ number_format($depense->montant_salaire_base, 2) }} DH</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Taux CNSS:</small>
                        <p class="fw-bold">{{ $depense->taux_cnss }}%</p>
                    </div>
                    @if($depense->repartition_cnss)
                    <div class="col-md-3">
                        <small class="text-muted">Part Patronale (70%):</small>
                        <p class="text-danger fw-bold">{{ number_format($depense->repartition_cnss['part_patronale'] ?? 0, 2) }} DH</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Part Salariale (30%):</small>
                        <p class="text-success fw-bold">{{ number_format($depense->repartition_cnss['part_salariale'] ?? 0, 2) }} DH</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($depense->type == 'publication')
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-info mb-3">
                    <i class="fas fa-bullhorn me-2"></i>Détails Publication
                </h6>
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted">Plateforme:</small>
                        <p class="fw-bold">{{ $depense->plateforme }}</p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Campagne:</small>
                        <p class="fw-bold">{{ $depense->campagne }}</p>
                    </div>
                    @if($depense->date_debut_campagne)
                    <div class="col-md-4">
                        <small class="text-muted">Période:</small>
                        <p>
                            {{ $depense->date_debut_campagne->format('d/m/Y') }}
                            @if($depense->date_fin_campagne)
                                → {{ $depense->date_fin_campagne->format('d/m/Y') }}
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($depense->type == 'transport')
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-warning mb-3">
                    <i class="fas fa-car me-2"></i>Détails Transport
                </h6>
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted">Type:</small>
                        <p class="fw-bold">{{ ucfirst($depense->type_transport) }}</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Bénéficiaire:</small>
                        <p class="fw-bold">{{ $depense->beneficiaire }}</p>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Trajet:</small>
                        <p>{{ $depense->trajet }}</p>
                    </div>
                    @if($depense->distance_km)
                    <div class="col-md-3">
                        <small class="text-muted">Distance:</small>
                        <p>{{ $depense->distance_km }} km</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Fichiers justificatifs -->
    @if($depense->fichiers_justificatifs && count($depense->fichiers_justificatifs) > 0)
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-paperclip me-2"></i>Fichiers Justificatifs
                </h6>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($depense->fichiers_justificatifs as $index => $fichier)
                    <a href="{{ Storage::url($fichier) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-file-{{ Str::endsWith($fichier, '.pdf') ? 'pdf' : 'image' }} me-1"></i>
                        Fichier {{ $index + 1 }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Notes internes -->
    @if($depense->notes_internes)
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-sticky-note me-2"></i>Notes Internes
                </h6>
                <p class="mb-0">{{ $depense->notes_internes }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Informations système -->
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
</div>

<div class="mt-3 d-flex justify-content-between">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times me-2"></i>Fermer
    </button>
    <div>
        @if(!in_array($depense->statut, ['payee', 'annulee']))
        <button type="button" class="btn btn-warning" onclick="editDepense({{ $depense->id }}); $('#showModal').modal('hide');">
            <i class="fas fa-edit me-2"></i>Modifier
        </button>
        @endif
        
        @if($depense->statut == 'en_attente')
        <button type="button" class="btn btn-success" onclick="validerDepense({{ $depense->id }}); $('#showModal').modal('hide');">
            <i class="fas fa-check me-2"></i>Valider
        </button>
        @endif
        
        @if($depense->statut != 'payee')
        <button type="button" class="btn btn-danger" onclick="deleteDepense({{ $depense->id }}); $('#showModal').modal('hide');">
            <i class="fas fa-trash me-2"></i>Supprimer
        </button>
        @endif
    </div>
</div>