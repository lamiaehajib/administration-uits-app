<x-app-layout>
    <div class="container-fluid">
        <!-- En-tête Dashboard -->
        <div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="hight mb-0">
                <i class="fas fa-chart-line me-2"></i>
                Dashboard des Charges - 
                @if(isset($mois))
                    {{ \Carbon\Carbon::create($annee, $mois)->format('F Y') }}
                @else
                    {{ $annee }}
                @endif
            </h3>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2" id="filterForm">
                    <!-- Sélecteur d'année -->
                    <select name="annee" class="form-select" onchange="this.form.submit()" style="width: 120px;">
                        @for($y = now()->year; $y >= now()->year - 5; $y--)
                            <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    
                    <!-- Sélecteur de mois -->
                    <select name="mois" class="form-select" onchange="this.form.submit()" style="width: 150px;">
                        <option value="">Toute l'année</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ (isset($mois) && $mois == $m) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m)->format('F') }}
                            </option>
                        @endfor
                    </select>

                    @if(isset($mois))
                        <!-- Bouton reset visible seulement si mois sélectionné -->
                        <a href="{{ route('charges.dashboard', ['annee' => $annee]) }}" 
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i> Réinitialiser
                        </a>
                    @endif
                </form>

                <a href="{{ route('charges.index') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Retour aux Charges
                </a>
                <a href="{{ route('charges.export', [
        'annee' => $annee,
        'mois' => $mois ?? null
    ]) }}" class="btn btn-success btn-sm">
        <i class="fas fa-file-excel me-1"></i> Exporter
    </a>
            </div>
        </div>
    </div>
</div>


        <!-- Statistiques annuelles -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #D32F2F !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.85rem;">Total Année</h6>
                                <h3 class="mb-0 fw-bold" style="color: #D32F2F;">
                                    {{ number_format($stats['total_charges'], 2) }} DH
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas fa-calendar-alt fa-2x opacity-50" style="color: #D32F2F;"></i>
                            </div>
                        </div>
                        <small class="text-muted">{{ $stats['nombre_charges'] }} charge(s)</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #1976D2 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.85rem;">Moyenne Mensuelle</h6>
                                <h3 class="mb-0 fw-bold" style="color: #1976D2;">
                                    {{ number_format($stats['total_charges'] / 12, 2) }} DH
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas fa-chart-bar fa-2x opacity-50" style="color: #1976D2;"></i>
                            </div>
                        </div>
                        <small class="text-muted">Par mois</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #388E3C !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.85rem;">Charges Fixes</h6>
                                <h3 class="mb-0 fw-bold" style="color: #388E3C;">
                                    {{ number_format($stats['total_fixe'], 2) }} DH
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas fa-lock fa-2x opacity-50" style="color: #388E3C;"></i>
                            </div>
                        </div>
                        <small class="text-muted">{{ number_format(($stats['total_fixe'] / $stats['total_charges']) * 100, 1) }}% du total</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #F57C00 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.85rem;">Charges Variables</h6>
                                <h3 class="mb-0 fw-bold" style="color: #F57C00;">
                                    {{ number_format($stats['total_variable'], 2) }} DH
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas fa-chart-line fa-2x opacity-50" style="color: #F57C00;"></i>
                            </div>
                        </div>
                        <small class="text-muted">{{ number_format(($stats['total_variable'] / $stats['total_charges']) * 100, 1) }}% du total</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row mb-4">
            <!-- Évolution mensuelle -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-area me-2"></i>
                            Évolution Mensuelle
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartEvolution" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Répartition Fixe/Variable -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-pie-chart me-2"></i>
                            Répartition
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartRepartition"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques par catégorie -->
        <div class="row mb-4">
            <!-- Top 5 catégories -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tags me-2"></i>
                            Top 5 Catégories
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($stats['par_categorie']->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($stats['par_categorie'] as $cat)
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle me-3" 
                                             style="width: 12px; height: 12px; background-color: {{ $cat['couleur'] }};"></div>
                                        <div>
                                            <strong>{{ $cat['nom'] }}</strong>
                                            <br><small class="text-muted">{{ $cat['count'] }} charge(s)</small>
                                        </div>
                                    </div>
                                    <span class="badge" style="background-color: {{ $cat['couleur'] }}; font-size: 1rem;">
                                        {{ number_format($cat['total'], 2) }} DH
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucune donnée disponible</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Comparaison mensuelle Fixe vs Variable -->
            <div class="col-lg-6 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-balance-scale me-2"></i>
                            Comparaison Fixe/Variable
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartComparaison" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau récapitulatif mensuel -->
       <!-- Tableau récapitulatif -->
<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    @if(isset($mois))
                        Récapitulatif par Jour - {{ \Carbon\Carbon::create($annee, $mois)->format('F Y') }}
                    @else
                        Récapitulatif Mensuel - {{ $annee }}
                    @endif
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ isset($mois) ? 'Jour' : 'Mois' }}</th>
                                <th class="text-center">Charges Fixes</th>
                                <th class="text-center">Charges Variables</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">
                                    @if(isset($mois))
                                        % Mois
                                    @else
                                        % Annuel
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($mois))
                                {{-- Mode MOIS: afficher par jour --}}
                                @foreach($chargesParJour as $jour => $data)
                                <tr>
                                    <td><strong>{{ $jour }} {{ \Carbon\Carbon::create($annee, $mois)->format('M') }}</strong></td>
                                    <td class="text-center">
                                        <span style="color: #388E3C; font-weight: bold;">
                                            {{ number_format($data['fixe'], 2) }} DH
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span style="color: #F57C00; font-weight: bold;">
                                            {{ number_format($data['variable'], 2) }} DH
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <strong style="color: #D32F2F;">
                                            {{ number_format($data['fixe'] + $data['variable'], 2) }} DH
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $total = $data['fixe'] + $data['variable'];
                                            $pourcentage = $stats['total_charges'] > 0 ? ($total / $stats['total_charges']) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $pourcentage }}%; background: linear-gradient(135deg, #C2185B, #D32F2F);"
                                                 aria-valuenow="{{ $pourcentage }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($pourcentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                {{-- Mode ANNÉE: afficher par mois --}}
                                @foreach($chargesParMois as $m => $data)
                                <tr>
                                    <td><strong>{{ $data['mois'] }}</strong></td>
                                    <td class="text-center">
                                        <span style="color: #388E3C; font-weight: bold;">
                                            {{ number_format($data['fixe'], 2) }} DH
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span style="color: #F57C00; font-weight: bold;">
                                            {{ number_format($data['variable'], 2) }} DH
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <strong style="color: #D32F2F;">
                                            {{ number_format($data['fixe'] + $data['variable'], 2) }} DH
                                        </strong>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $total = $data['fixe'] + $data['variable'];
                                            $pourcentage = $stats['total_charges'] > 0 ? ($total / $stats['total_charges']) * 100 : 0;
                                        @endphp
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $pourcentage }}%; background: linear-gradient(135deg, #C2185B, #D32F2F);"
                                                 aria-valuenow="{{ $pourcentage }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($pourcentage, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th>
                                    TOTAL 
                                    @if(isset($mois))
                                        {{ \Carbon\Carbon::create($annee, $mois)->format('F Y') }}
                                    @else
                                        {{ $annee }}
                                    @endif
                                </th>
                                <th class="text-center" style="color: #388E3C;">
                                    {{ number_format($stats['total_fixe'], 2) }} DH
                                </th>
                                <th class="text-center" style="color: #F57C00;">
                                    {{ number_format($stats['total_variable'], 2) }} DH
                                </th>
                                <th class="text-center" style="color: #D32F2F;">
                                    {{ number_format($stats['total_charges'], 2) }} DH
                                </th>
                                <th class="text-center">100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Configuration Chart.js
      // Configuration Chart.js
Chart.defaults.font.family = 'Ubuntu, sans-serif';

// Labels dynamiques selon le filtre
@if(isset($mois))
    // Mode mois: afficher les jours
    const labels = {!! json_encode(collect($chargesParJour)->pluck('jour')) !!};
    const labelFormat = 'Jour ';
@else
    // Mode année: afficher les mois
    const labels = {!! json_encode($evolutionMensuelle->pluck('mois')) !!};
    const labelFormat = '';
@endif

// 1. Graphique Évolution
const ctxEvolution = document.getElementById('chartEvolution').getContext('2d');
new Chart(ctxEvolution, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Total Charges',
            data: {!! json_encode($evolutionMensuelle->pluck('total')) !!},
            borderColor: '#D32F2F',
            backgroundColor: 'rgba(211, 47, 47, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: @if(isset($mois))
                    'Évolution journalière - {{ \Carbon\Carbon::create($annee, $mois)->format("F Y") }}'
                @else
                    'Évolution mensuelle - {{ $annee }}'
                @endif
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y.toFixed(2) + ' DH';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toFixed(0) + ' DH';
                    }
                }
            }
        }
    }
});

// 2. Graphique Répartition (inchangé)
const ctxRepartition = document.getElementById('chartRepartition').getContext('2d');
new Chart(ctxRepartition, {
    type: 'doughnut',
    data: {
        labels: ['Fixes', 'Variables'],
        datasets: [{
            data: [{{ $stats['total_fixe'] }}, {{ $stats['total_variable'] }}],
            backgroundColor: [
                'rgba(56, 142, 60, 0.8)',
                'rgba(245, 124, 0, 0.8)'
            ],
            borderColor: [
                '#388E3C',
                '#F57C00'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const value = context.parsed;
                        const percentage = ((value / total) * 100).toFixed(1);
                        return context.label + ': ' + value.toFixed(2) + ' DH (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// 3. Graphique Comparaison
const ctxComparaison = document.getElementById('chartComparaison').getContext('2d');
new Chart(ctxComparaison, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Fixes',
                data: @if(isset($mois))
                    {!! json_encode(collect($chargesParJour)->pluck('fixe')) !!}
                @else
                    {!! json_encode(collect($chargesParMois)->pluck('fixe')) !!}
                @endif,
                backgroundColor: 'rgba(56, 142, 60, 0.8)',
                borderColor: '#388E3C',
                borderWidth: 1
            },
            {
                label: 'Variables',
                data: @if(isset($mois))
                    {!! json_encode(collect($chargesParJour)->pluck('variable')) !!}
                @else
                    {!! json_encode(collect($chargesParMois)->pluck('variable')) !!}
                @endif,
                backgroundColor: 'rgba(245, 124, 0, 0.8)',
                borderColor: '#F57C00',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: @if(isset($mois))
                    'Comparaison journalière Fixe/Variable'
                @else
                    'Comparaison mensuelle Fixe/Variable'
                @endif
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y.toFixed(2) + ' DH';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                stacked: false,
                ticks: {
                    callback: function(value) {
                        return value.toFixed(0) + ' DH';
                    }
                }
            },
            x: {
                stacked: false
            }
        }
    }
});
    </script>
    @endpush
</x-app-layout>