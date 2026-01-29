<style>
    .detail-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .detail-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(211, 47, 47, 0.15);
    }

    .stat-box {
        background: linear-gradient(135deg, #fff5f5, #ffffff);
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        border: 2px solid #fce4ec;
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        border-color: #D32F2F;
        box-shadow: 0 5px 20px rgba(211, 47, 47, 0.1);
    }

    .stat-box .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        background: white;
        box-shadow: 0 4px 15px rgba(211, 47, 47, 0.1);
    }

    .stat-box h3 {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        margin: 10px 0 5px;
    }

    .employee-table {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .employee-table thead {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        color: white;
    }

    .employee-table tbody tr {
        transition: all 0.2s ease;
    }

    .employee-table tbody tr:hover {
        background-color: #fff5f5;
        transform: scale(1.01);
    }

    .progress-custom {
        height: 8px;
        border-radius: 10px;
        background-color: #fce4ec;
    }

    .progress-custom .progress-bar {
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        border-radius: 10px;
    }

    .info-badge {
        padding: 12px 20px;
        border-radius: 10px;
        background: linear-gradient(135deg, #fff5f5, #ffffff);
        border-left: 4px solid #D32F2F;
    }

    .poste-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        background: linear-gradient(135deg, #C2185B, #D32F2F);
        color: white;
    }

    .chart-container {
        position: relative;
        padding: 20px;
        background: white;
        border-radius: 15px;
        border: 2px solid #fce4ec;
    }
</style>

<div class="row g-4">
    <!-- 🎯 En-tête -->
    <div class="col-12">
        <div class="detail-card card shadow-sm">
            <div class="card-body p-4" style="background: linear-gradient(135deg, #fff5f5, #ffffff);">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h3 class="mb-2">
                            <i class="fas fa-calendar-check text-danger me-2"></i>
                            <span class="hight">{{ $historique->mois_nom }} {{ $historique->annee }}</span>
                        </h3>
                        <p class="mb-2">{!! $historique->statut_badge !!}</p>
                        <small class="text-muted">
                            <i class="fas fa-file-import me-1"></i>
                            Import ID: <strong>#{{ str_pad($historique->id, 5, '0', STR_PAD_LEFT) }}</strong>
                        </small>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <div class="info-badge d-inline-block">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-danger me-2"></i>
                                <div class="text-start">
                                    <small class="text-muted d-block">Importé le</small>
                                    <strong>{{ $historique->importe_le->format('d/m/Y à H:i') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        par <strong>{{ $historique->importePar->name ?? 'N/A' }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 📊 Statistiques Principales -->
    <div class="col-lg-4 col-md-6">
        <div class="stat-box">
            <div class="icon-wrapper">
                <i class="fas fa-users text-primary fs-2"></i>
            </div>
            <h6 class="text-muted mb-1">Nombre d'Employés</h6>
            <h3>{{ $historique->nombre_employes }}</h3>
            <small class="text-muted">Actifs ce mois</small>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="stat-box">
            <div class="icon-wrapper">
                <i class="fas fa-money-bill-wave text-danger fs-2"></i>
            </div>
            <h6 class="text-muted mb-1">Montant Total Salaires</h6>
            <h3>{{ number_format($historique->montant_total, 2) }}</h3>
            <small class="text-muted">DH</small>
        </div>
    </div>

    <div class="col-lg-4 col-md-6">
        <div class="stat-box">
            <div class="icon-wrapper">
                <i class="fas fa-shield-alt text-warning fs-2"></i>
            </div>
            <h6 class="text-muted mb-1">CNSS (20.48%)</h6>
            @php
                $cnss = $historique->montant_total * 0.2048;
            @endphp
            <h3>{{ number_format($cnss, 2) }}</h3>
            <small class="text-muted">DH</small>
        </div>
    </div>

    <!-- 📋 Liste des Salaires -->
    <div class="col-12">
        <div class="detail-card card shadow-sm">
            <div class="card-header" style="background: linear-gradient(135deg, #fff5f5, #ffffff); border-bottom: 2px solid #fce4ec;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list-ul me-2 text-danger"></i>
                        <span class="hight">Détails des Salaires</span>
                    </h5>
                    <span class="badge" style="background: linear-gradient(135deg, #C2185B, #D32F2F); font-size: 1rem; padding: 8px 15px;">
                        {{ count($historique->details_salaires ?? []) }} Employés
                    </span>
                </div>
            </div>
            <div class="card-body p-0">
                @if($historique->details_salaires && count($historique->details_salaires) > 0)
                <div class="table-responsive">
                    <table class="table employee-table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Nom Complet</th>
                                <th>Poste</th>
                                <th class="text-end">Salaire</th>
                                <th class="text-end">CNSS Part</th>
                                <th class="text-end">Net Estimé</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historique->details_salaires as $index => $salaire)
                            @php
                                $montantSalaire = floatval($salaire['salaire']);
                                $cnssPart = $montantSalaire * 0.2048;
                                $netEstime = $montantSalaire - ($cnssPart * 0.3); // Part salariale 30%
                            @endphp
                            <tr>
                                <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-danger bg-opacity-10 rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="fas fa-user text-danger"></i>
                                        </div>
                                        <strong>{{ $salaire['nom'] }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <span class="poste-badge">{{ $salaire['poste'] }}</span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-danger">{{ number_format($montantSalaire, 2) }} DH</strong>
                                </td>
                                <td class="text-end">
                                    <span class="text-warning fw-semibold">{{ number_format($cnssPart, 2) }} DH</span>
                                </td>
                                <td class="text-end">
                                    <span class="text-success fw-semibold">{{ number_format($netEstime, 2) }} DH</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: linear-gradient(135deg, #fff5f5, #ffffff); border-top: 2px solid #D32F2F;">
                            <tr>
                                <th colspan="3" class="text-end py-3">TOTAUX:</th>
                                <th class="text-end py-3">
                                    <span class="text-danger fs-5">{{ number_format($historique->montant_total, 2) }} DH</span>
                                </th>
                                <th class="text-end py-3">
                                    <span class="text-warning fs-5">{{ number_format($cnss, 2) }} DH</span>
                                </th>
                                <th class="text-end py-3">
                                    <span class="text-success fs-5">{{ number_format($historique->montant_total - ($cnss * 0.3), 2) }} DH</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3 mb-0">Aucun détail disponible</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($historique->details_salaires && count($historique->details_salaires) > 0)
    <!-- 📊 Analyses -->
    <div class="col-md-6">
        <div class="detail-card card shadow-sm h-100">
            <div class="card-header" style="background: linear-gradient(135deg, #fff5f5, #ffffff); border-bottom: 2px solid #fce4ec;">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2 text-danger"></i>
                    <span class="hight">Répartition par Poste</span>
                </h5>
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
                        })
                        ->sortByDesc('total');
                @endphp

                @foreach($parPoste as $poste => $data)
                @php
                    $pourcentage = ($data['total'] / $historique->montant_total) * 100;
                @endphp
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="poste-badge">{{ $poste }}</span>
                            <span class="badge bg-primary ms-2">{{ $data['count'] }} pers.</span>
                        </div>
                        <div class="text-end">
                            <strong class="text-danger">{{ number_format($data['total'], 2) }} DH</strong>
                            <br>
                            <small class="text-muted">{{ number_format($pourcentage, 1) }}%</small>
                        </div>
                    </div>
                    <div class="progress-custom progress">
                        <div class="progress-bar" style="width: {{ $pourcentage }}%"></div>
                    </div>
                    <small class="text-muted">
                        Moyenne: {{ number_format($data['total'] / $data['count'], 2) }} DH/personne
                    </small>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 🔢 Statistiques Avancées -->
    <div class="col-md-6">
        <div class="detail-card card shadow-sm h-100">
            <div class="card-header" style="background: linear-gradient(135deg, #fff5f5, #ffffff); border-bottom: 2px solid #fce4ec;">
                <h5 class="mb-0">
                    <i class="fas fa-calculator me-2 text-danger"></i>
                    <span class="hight">Analyses Statistiques</span>
                </h5>
            </div>
            <div class="card-body">
                @php
                    $salaires = collect($historique->details_salaires)->pluck('salaire')->map(fn($s) => floatval($s));
                    $moyenne = $salaires->avg();
                    $min = $salaires->min();
                    $max = $salaires->max();
                    $mediane = $salaires->median();
                @endphp

                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, #e3f2fd, #ffffff); border-left: 3px solid #2196F3;">
                            <small class="text-muted d-block mb-1">Salaire Moyen</small>
                            <h5 class="mb-0 fw-bold text-primary">{{ number_format($moyenne, 2) }} DH</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, #f3e5f5, #ffffff); border-left: 3px solid #9C27B0;">
                            <small class="text-muted d-block mb-1">Salaire Médian</small>
                            <h5 class="mb-0 fw-bold" style="color: #9C27B0;">{{ number_format($mediane, 2) }} DH</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, #e8f5e9, #ffffff); border-left: 3px solid #4CAF50;">
                            <small class="text-muted d-block mb-1">Salaire Minimum</small>
                            <h5 class="mb-0 fw-bold text-success">{{ number_format($min, 2) }} DH</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background: linear-gradient(135deg, #fff3e0, #ffffff); border-left: 3px solid #FF9800;">
                            <small class="text-muted d-block mb-1">Salaire Maximum</small>
                            <h5 class="mb-0 fw-bold" style="color: #FF9800;">{{ number_format($max, 2) }} DH</h5>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded mb-3" style="background: linear-gradient(135deg, #fff5f5, #ffffff); border-left: 3px solid #D32F2F;">
                    <small class="text-muted d-block mb-1">Écart Salarial</small>
                    <h5 class="mb-0 fw-bold text-danger">{{ number_format($max - $min, 2) }} DH</h5>
                </div>

                <hr>

                <h6 class="fw-bold mb-3">
                    <i class="fas fa-shield-alt text-warning me-2"></i>
                    Répartition CNSS
                </h6>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded text-center" style="background: linear-gradient(135deg, #fff5f5, #ffffff); border: 2px dashed #D32F2F;">
                            <small class="text-muted d-block mb-1">Part Patronale (70%)</small>
                            <h5 class="mb-0 fw-bold text-danger">{{ number_format($cnss * 0.7, 2) }} DH</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded text-center" style="background: linear-gradient(135deg, #e8f5e9, #ffffff); border: 2px dashed #4CAF50;">
                            <small class="text-muted d-block mb-1">Part Salariale (30%)</small>
                            <h5 class="mb-0 fw-bold text-success">{{ number_format($cnss * 0.3, 2) }} DH</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- ℹ️ Informations Actions -->
    <div class="col-12">
        <div class="detail-card card shadow-sm">
            <div class="card-body p-4" style="background: linear-gradient(135deg, #e3f2fd, #ffffff);">
                <h6 class="fw-bold mb-3">
                    <i class="fas fa-link me-2 text-primary"></i>
                    Dépenses Créées Automatiquement
                </h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white border border-danger">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-receipt text-danger fs-3 me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Dépense Fixe - Salaires</h6>
                                    <p class="mb-1 small text-muted">{{ $historique->mois_nom }} {{ $historique->annee }}</p>
                                    <h5 class="mb-0 text-danger fw-bold">{{ number_format($historique->montant_total, 2) }} DH</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded bg-white border border-warning">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-random text-warning fs-3 me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Dépense Variable - CNSS</h6>
                                    <p class="mb-1 small text-muted">{{ $historique->mois_nom }} {{ $historique->annee }}</p>
                                    <h5 class="mb-0 text-warning fw-bold">{{ number_format($cnss, 2) }} DH</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions Footer -->
<div class="mt-4 d-flex justify-content-between align-items-center">
    <button type="button" class="btn btn-outline-secondary btn-lg" data-bs-dismiss="modal">
        <i class="fas fa-times me-2"></i>Fermer
    </button>
    <div>
        <a href="{{ route('depenses.fixes.index') }}" class="btn btn-outline-danger btn-lg me-2" target="_blank">
            <i class="fas fa-receipt me-2"></i>Voir Dépense Fixe
        </a>
        <a href="{{ route('depenses.variables.index') }}" class="btn btn-outline-warning btn-lg" target="_blank">
            <i class="fas fa-random me-2"></i>Voir Dépense CNSS
        </a>
    </div>
</div>