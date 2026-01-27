<div class="row g-3">
    <!-- Informations générales -->
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4 class="mb-2">
                            <i class="fas fa-calendar-alt text-success me-2"></i>
                            Import {{ $historique->mois_nom }} {{ $historique->annee }}
                        </h4>
                        <p class="mb-0">
                            {!! $historique->statut_badge !!}
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <small class="text-muted d-block">Importé le</small>
                        <p class="mb-1">
                            <i class="fas fa-clock me-1"></i>
                            {{ $historique->importe_le->format('d/m/Y à H:i') }}
                        </p>
                        <small class="text-muted">
                            par <strong>{{ $historique->importePar->name ?? 'N/A' }}</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="col-md-4">
        <div class="card border-0 bg-light h-100">
            <div class="card-body text-center">
                <div class="avatar-md bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-users text-primary fs-3"></i>
                </div>
                <h6 class="text-muted mb-1">Nombre d'employés</h6>
                <h3 class="mb-0">{{ $historique->nombre_employes }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 bg-light h-100">
            <div class="card-body text-center">
                <div class="avatar-md bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-money-bill-wave text-danger fs-3"></i>
                </div>
                <h6 class="text-muted mb-1">Montant Total</h6>
                <h3 class="mb-0 text-danger">{{ number_format($historique->montant_total, 2) }} DH</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 bg-light h-100">
            <div class="card-body text-center">
                <div class="avatar-md bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                    <i class="fas fa-shield-alt text-warning fs-3"></i>
                </div>
                <h6 class="text-muted mb-1">CNSS (20.48%)</h6>
                @php
                    $cnss = $historique->montant_total * 0.2048;
                @endphp
                <h3 class="mb-0 text-warning">{{ number_format($cnss, 2) }} DH</h3>
            </div>
        </div>
    </div>

    <!-- Détails des salaires -->
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0">
                    <i class="fas fa-list me-2"></i>Détails des Salaires ({{ count($historique->details_salaires ?? []) }})
                </h6>
            </div>
            <div class="card-body">
                @if($historique->details_salaires && count($historique->details_salaires) > 0)
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-white sticky-top">
                            <tr>
                                <th>#</th>
                                <th>Nom</th>
                                <th>Poste</th>
                                <th class="text-end">Salaire</th>
                                <th class="text-end">CNSS Part</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historique->details_salaires as $index => $salaire)
                            @php
                                $cnssPart = floatval($salaire['salaire']) * 0.2048;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $salaire['nom'] }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $salaire['poste'] }}</span>
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format(floatval($salaire['salaire']), 2) }} DH
                                </td>
                                <td class="text-end text-warning">
                                    {{ number_format($cnssPart, 2) }} DH
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-top bg-white sticky-bottom">
                            <tr>
                                <th colspan="3" class="text-end">TOTAL:</th>
                                <th class="text-end text-danger">
                                    {{ number_format($historique->montant_total, 2) }} DH
                                </th>
                                <th class="text-end text-warning">
                                    {{ number_format($cnss, 2) }} DH
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <p class="text-muted text-center mb-0">Aucun détail disponible</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Répartition par poste -->
    @if($historique->details_salaires && count($historique->details_salaires) > 0)
    <div class="col-md-6">
        <div class="card border-0 bg-light h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Répartition par Poste
                </h6>
            </div>
            <div class="card-body">
                @php
                    $parPoste = collect($historique->details_salaires)
                        ->groupBy('poste')
                        ->map(function($groupe) {
                            return [
                                'count' => $groupe->count(),
                                'total' => $groupe->sum('salaire')
                            ];
                        });
                @endphp

                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Poste</th>
                                <th class="text-center">Nombre</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Moyenne</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($parPoste as $poste => $data)
                            <tr>
                                <td>{{ $poste }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $data['count'] }}</span>
                                </td>
                                <td class="text-end fw-bold">{{ number_format($data['total'], 2) }} DH</td>
                                <td class="text-end text-muted">{{ number_format($data['total'] / $data['count'], 2) }} DH</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques supplémentaires -->
    <div class="col-md-6">
        <div class="card border-0 bg-light h-100">
            <div class="card-header bg-transparent border-0">
                <h6 class="mb-0">
                    <i class="fas fa-calculator me-2"></i>Statistiques
                </h6>
            </div>
            <div class="card-body">
                @php
                    $salaires = collect($historique->details_salaires)->pluck('salaire')->map(fn($s) => floatval($s));
                    $moyenne = $salaires->avg();
                    $min = $salaires->min();
                    $max = $salaires->max();
                @endphp

                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" width="50%">Salaire Moyen:</td>
                        <td class="text-end fw-bold">{{ number_format($moyenne, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Salaire Minimum:</td>
                        <td class="text-end">{{ number_format($min, 2) }} DH</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Salaire Maximum:</td>
                        <td class="text-end">{{ number_format($max, 2) }} DH</td>
                    </tr>
                    <tr class="border-top">
                        <td class="text-muted">Écart (Max - Min):</td>
                        <td class="text-end fw-bold">{{ number_format($max - $min, 2) }} DH</td>
                    </tr>
                </table>

                <div class="mt-3 pt-3 border-top">
                    <h6 class="text-muted mb-2 small">Répartition CNSS</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted">Part Patronale (70%):</td>
                            <td class="text-end text-danger fw-bold">{{ number_format($cnss * 0.7, 2) }} DH</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Part Salariale (30%):</td>
                            <td class="text-end text-success fw-bold">{{ number_format($cnss * 0.3, 2) }} DH</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions liées -->
    <div class="col-12">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-link me-2"></i>Dépenses Créées
                </h6>
                <div class="alert alert-info small mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Cet import a automatiquement créé:
                    <ul class="mb-0 mt-2">
                        <li>
                            <strong>Dépense Fixe:</strong> Salaires {{ $historique->mois_nom }} {{ $historique->annee }} 
                            ({{ number_format($historique->montant_total, 2) }} DH)
                        </li>
                        <li>
                            <strong>Dépense Variable:</strong> CNSS {{ $historique->mois_nom }} {{ $historique->annee }} 
                            ({{ number_format($cnss, 2) }} DH)
                        </li>
                    </ul>
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
        <a href="{{ route('depenses.fixes.index') }}" class="btn btn-outline-danger" target="_blank">
            <i class="fas fa-receipt me-2"></i>Voir Dépense Fixe
        </a>
        <a href="{{ route('depenses.variables.index') }}" class="btn btn-outline-warning" target="_blank">
            <i class="fas fa-random me-2"></i>Voir Dépense CNSS
        </a>
    </div>
</div>