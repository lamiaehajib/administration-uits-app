<x-app-layout>
    <div class="container-fluid">
        <!-- 🔙 Bouton Retour -->
        <div class="mb-3">
            <a href="{{ route('factures-recues.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <!-- 🎨 Header Facture -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border-radius: 15px;">
                    <div class="card-body py-4">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="text-white mb-2 fw-bold">
                                    <i class="fas fa-file-invoice"></i> Facture {{ $facture->numero_facture }}
                                </h2>
                                <div class="d-flex gap-3 flex-wrap">
                                    @if($facture->statut === 'en_attente')
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="fas fa-clock"></i> En Attente
                                    </span>
                                    @elseif($facture->statut === 'payee')
                                    <span class="badge bg-success fs-6">
                                        <i class="fas fa-check-circle"></i> Payée
                                    </span>
                                    @else
                                    <span class="badge bg-secondary fs-6">
                                        <i class="fas fa-ban"></i> Annulée
                                    </span>
                                    @endif

                                    <span class="text-white-50">
                                        <i class="fas fa-calendar"></i> {{ $facture->date_facture->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <div class="btn-group" role="group">
                                    @if($facture->statut !== 'payee')
                                    <a href="{{ route('factures-recues.edit', $facture->id) }}" class="btn btn-light">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    @endif
                                    
                                    <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#" id="printBtn">
                                                <i class="fas fa-print text-primary"></i> Imprimer
                                            </a>
                                        </li>
                                        @if($facture->fichier_pdf)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('factures-recues.download', $facture->id) }}">
                                                <i class="fas fa-download text-success"></i> Télécharger PDF
                                            </a>
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="#" id="duplicateBtn">
                                                <i class="fas fa-copy text-warning"></i> Dupliquer
                                            </a>
                                        </li>
                                        @if($facture->statut !== 'payee')
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" id="deleteBtn">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- 📋 Détails Facture -->
            <div class="col-lg-8">
                <!-- Informations Principales -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold hight">
                            <i class="fas fa-info-circle"></i> Informations de la Facture
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small fw-semibold mb-1">NUMÉRO DE FACTURE</label>
                                    <div class="fs-5 fw-bold hight">{{ $facture->numero_facture }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small fw-semibold mb-1">MONTANT TTC</label>
                                    <div class="fs-4 fw-bold text-success">{{ number_format($facture->montant_ttc, 2) }} DH</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small fw-semibold mb-1">DATE DE FACTURE</label>
                                    <div class="fs-6">
                                        <i class="fas fa-calendar text-primary"></i> 
                                        {{ $facture->date_facture->format('d/m/Y') }}
                                        <small class="text-muted">({{ $facture->date_facture->diffForHumans() }})</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small fw-semibold mb-1">DATE D'ÉCHÉANCE</label>
                                    <div class="fs-6">
                                        @if($facture->date_echeance)
                                            @php
                                                $isEchue = $facture->date_echeance->isPast() && $facture->statut !== 'payee';
                                                $isProche = $facture->date_echeance->between(now(), now()->addDays(7)) && $facture->statut !== 'payee';
                                            @endphp
                                            <i class="fas fa-calendar {{ $isEchue ? 'text-danger' : ($isProche ? 'text-warning' : 'text-primary') }}"></i>
                                            <span class="{{ $isEchue ? 'text-danger fw-bold' : ($isProche ? 'text-warning fw-bold' : '') }}">
                                                {{ $facture->date_echeance->format('d/m/Y') }}
                                            </span>
                                            @if($isEchue)
                                                <span class="badge bg-danger ms-2">Échue!</span>
                                            @elseif($isProche)
                                                <span class="badge bg-warning text-dark ms-2">Bientôt échue</span>
                                            @endif
                                        @else
                                            <span class="text-muted">Non définie</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($facture->description)
                            <div class="col-12">
                                <div class="info-item">
                                    <label class="text-muted small fw-semibold mb-1">DESCRIPTION</label>
                                    <div class="fs-6 text-dark">{{ $facture->description }}</div>
                                </div>
                            </div>
                            @endif
                            @if($facture->fichier_pdf)
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-paperclip"></i> 
                                    <strong>Fichier joint:</strong> 
                                    <a href="{{ route('factures-recues.download', $facture->id) }}" class="alert-link">
                                        Télécharger le PDF <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informations Fournisseur -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold hight">
                            <i class="fas {{ $facture->fournisseur_type === 'App\Models\Consultant' ? 'fa-user-tie' : 'fa-building' }}"></i> 
                            Informations {{ $facture->fournisseur_type === 'App\Models\Consultant' ? 'Consultant' : 'Fournisseur' }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-circle me-3" style="width: 60px; height: 60px; background: linear-gradient(135deg, #C2185B15, #D32F2F15); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas {{ $facture->fournisseur_type === 'App\Models\Consultant' ? 'fa-user-tie' : 'fa-building' }} fa-2x hight"></i>
                                    </div>
                                    <div>
                                        <h4 class="mb-0 fw-bold">{{ $facture->nom_fournisseur }}</h4>
                                        @if($facture->fournisseur_type === 'App\Models\Consultant')
                                        <span class="badge bg-info">Consultant</span>
                                        @else
                                        <span class="badge bg-primary">Fournisseur</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($facture->fournisseur_type === 'App\Models\Consultant')
                                <!-- Détails Consultant -->
                                @if($facture->fournisseur->email)
                                <div class="col-md-6">
                                    <small class="text-muted">Email</small>
                                    <div><i class="fas fa-envelope text-primary"></i> {{ $facture->fournisseur->email }}</div>
                                </div>
                                @endif
                                @if($facture->fournisseur->telephone)
                                <div class="col-md-6">
                                    <small class="text-muted">Téléphone</small>
                                    <div><i class="fas fa-phone text-success"></i> {{ $facture->fournisseur->telephone }}</div>
                                </div>
                                @endif
                                @if($facture->fournisseur->specialite)
                                <div class="col-md-6">
                                    <small class="text-muted">Spécialité</small>
                                    <div><i class="fas fa-briefcase text-warning"></i> {{ $facture->fournisseur->specialite }}</div>
                                </div>
                                @endif
                                @if($facture->fournisseur->tarif_heure)
                                <div class="col-md-6">
                                    <small class="text-muted">Tarif Horaire</small>
                                    <div><i class="fas fa-money-bill-wave text-success"></i> {{ number_format($facture->fournisseur->tarif_heure, 2) }} DH/h</div>
                                </div>
                                @endif
                            @else
                                <!-- Détails Fournisseur -->
                                @if($facture->fournisseur->email)
                                <div class="col-md-6">
                                    <small class="text-muted">Email</small>
                                    <div><i class="fas fa-envelope text-primary"></i> {{ $facture->fournisseur->email }}</div>
                                </div>
                                @endif
                                @if($facture->fournisseur->telephone)
                                <div class="col-md-6">
                                    <small class="text-muted">Téléphone</small>
                                    <div><i class="fas fa-phone text-success"></i> {{ $facture->fournisseur->telephone }}</div>
                                </div>
                                @endif
                                @if($facture->fournisseur->ice)
                                <div class="col-md-6">
                                    <small class="text-muted">ICE</small>
                                    <div><i class="fas fa-id-card text-info"></i> {{ $facture->fournisseur->ice }}</div>
                                </div>
                                @endif
                                @if($facture->fournisseur->if)
                                <div class="col-md-6">
                                    <small class="text-muted">IF</small>
                                    <div><i class="fas fa-id-badge text-warning"></i> {{ $facture->fournisseur->if }}</div>
                                </div>
                                @endif
                            @endif
                        </div>

                        <!-- Statistiques Fournisseur -->
                        <div class="row g-3 mt-3 pt-3 border-top">
                            <div class="col-md-4 text-center">
                                <div class="stat-box p-3 rounded" style="background: #f8f9fa;">
                                    <div class="fs-3 fw-bold hight">{{ $statsFournisseur['total_factures'] }}</div>
                                    <small class="text-muted">Total Factures</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="stat-box p-3 rounded" style="background: #f8f9fa;">
                                    <div class="fs-5 fw-bold text-success">{{ number_format($statsFournisseur['total_montant'], 2) }} DH</div>
                                    <small class="text-muted">Montant Total</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="stat-box p-3 rounded" style="background: #f8f9fa;">
                                    <div class="fs-3 fw-bold text-info">{{ $statsFournisseur['factures_payees'] }}</div>
                                    <small class="text-muted">Factures Payées</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Factures Similaires -->
                @if($facturesSimilaires->count() > 0)
                <div class="card border-0 shadow-sm" style="border-radius: 10px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0 fw-bold hight">
                            <i class="fas fa-history"></i> Autres Factures de ce Fournisseur
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Numéro</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($facturesSimilaires as $factureSimilaire)
                                    <tr>
                                        <td class="fw-semibold">{{ $factureSimilaire->numero_facture }}</td>
                                        <td><small>{{ $factureSimilaire->date_facture->format('d/m/Y') }}</small></td>
                                        <td class="fw-bold hight">{{ number_format($factureSimilaire->montant_ttc, 2) }} DH</td>
                                        <td>
                                            @if($factureSimilaire->statut === 'payee')
                                            <span class="badge bg-success">Payée</span>
                                            @elseif($factureSimilaire->statut === 'en_attente')
                                            <span class="badge bg-warning text-dark">En Attente</span>
                                            @else
                                            <span class="badge bg-secondary">Annulée</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('factures-recues.show', $factureSimilaire->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- 📊 Sidebar Infos -->
            <div class="col-lg-4">
                <!-- Actions Rapides -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold hight">
                            <i class="fas fa-bolt"></i> Actions Rapides
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if($facture->statut === 'en_attente')
                            <button type="button" class="btn btn-success" id="markAsPaidBtn">
                                <i class="fas fa-check-circle"></i> Marquer comme Payée
                            </button>
                            @endif
                            
                            @if($facture->fichier_pdf)
                            <a href="{{ route('factures-recues.download', $facture->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-download"></i> Télécharger PDF
                            </a>
                            @endif
                            
                            <button type="button" class="btn btn-outline-warning" id="duplicateBtn2">
                                <i class="fas fa-copy"></i> Dupliquer cette Facture
                            </button>
                            
                            @if($facture->statut !== 'payee')
                            <a href="{{ route('factures-recues.edit', $facture->id) }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Informations Système -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-bold hight">
                            <i class="fas fa-info-circle"></i> Informations Système
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-3">
                                <small class="text-muted d-block">Créée le</small>
                                <div>
                                    <i class="fas fa-calendar-plus text-primary"></i>
                                    {{ $facture->created_at->format('d/m/Y H:i') }}
                                </div>
                            </li>
                            <li class="mb-3">
                                <small class="text-muted d-block">Créée par</small>
                                <div>
                                    <i class="fas fa-user text-success"></i>
                                    {{ $facture->createdBy->name ?? 'N/A' }}
                                </div>
                            </li>
                            @if($facture->updated_at != $facture->created_at)
                            <li class="mb-3">
                                <small class="text-muted d-block">Dernière modification</small>
                                <div>
                                    <i class="fas fa-clock text-warning"></i>
                                    {{ $facture->updated_at->format('d/m/Y H:i') }}
                                </div>
                            </li>
                            @endif
                            @if($facture->updatedBy)
                            <li class="mb-0">
                                <small class="text-muted d-block">Modifiée par</small>
                                <div>
                                    <i class="fas fa-user-edit text-info"></i>
                                    {{ $facture->updatedBy->name }}
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Alertes -->
                @if($facture->date_echeance && $facture->date_echeance->isPast() && $facture->statut !== 'payee')
                <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 10px;">
                    <h6 class="alert-heading mb-2">
                        <i class="fas fa-exclamation-triangle"></i> Facture Échue!
                    </h6>
                    <p class="mb-0 small">
                        Cette facture a dépassé sa date d'échéance de <strong>{{ $facture->date_echeance->diffForHumans() }}</strong>.
                    </p>
                </div>
                @elseif($facture->date_echeance && $facture->date_echeance->between(now(), now()->addDays(7)) && $facture->statut !== 'payee')
                <div class="alert alert-warning border-0 shadow-sm" style="border-radius: 10px;">
                    <h6 class="alert-heading mb-2">
                        <i class="fas fa-clock"></i> Échéance Proche!
                    </h6>
                    <p class="mb-0 small">
                        Cette facture arrive à échéance <strong>{{ $facture->date_echeance->diffForHumans() }}</strong>.
                    </p>
                </div>
                @endif

                @if($facture->statut === 'payee')
                <div class="alert alert-success border-0 shadow-sm" style="border-radius: 10px;">
                    <h6 class="alert-heading mb-2">
                        <i class="fas fa-check-circle"></i> Facture Payée
                    </h6>
                    <p class="mb-0 small">
                        Cette facture a été marquée comme payée.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Marquer comme payée
            $('#markAsPaidBtn').on('click', function() {
                Swal.fire({
                    title: 'Marquer comme payée?',
                    text: "Cette facture sera marquée comme payée",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4CAF50',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, marquer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/factures-recues/{{ $facture->id }}/quick-edit',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                statut: 'payee'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Succès!',
                                    text: 'Facture marquée comme payée',
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    }
                });
            });

            // Dupliquer
            $('#duplicateBtn, #duplicateBtn2').on('click', function() {
                Swal.fire({
                    title: 'Dupliquer cette facture?',
                    text: "Une copie sera créée que vous pourrez modifier",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, dupliquer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/factures-recues/{{ $facture->id }}/duplicate',
                            method: 'POST',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Dupliquée!',
                                    text: 'Redirection vers l\'édition...',
                                    timer: 1500
                                }).then(() => {
                                    window.location.href = response.redirect || '{{ route("factures-recues.index") }}';
                                });
                            }
                        });
                    }
                });
            });

            // Supprimer
            $('#deleteBtn').on('click', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Supprimer cette facture?',
                    text: "Vous pourrez la restaurer depuis la corbeille",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/factures-recues/{{ $facture->id }}',
                            method: 'DELETE',
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Supprimée!',
                                    text: 'Facture déplacée dans la corbeille',
                                    timer: 2000
                                }).then(() => {
                                    window.location.href = '{{ route("factures-recues.index") }}';
                                });
                            }
                        });
                    }
                });
            });

            // Imprimer
            $('#printBtn').on('click', function(e) {
                e.preventDefault();
                window.print();
            });
        });
    </script>

    <style>
        @media print {
            .btn, .card-header, nav, .sidebar-container, .main-container > .sidebar-container {
                display: none !important;
            }
            .card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
        }
    </style>
    @endpush
</x-app-layout>