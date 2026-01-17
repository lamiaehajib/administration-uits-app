<x-app-layout>
    <div class="container-fluid">
        <!-- En-tête -->
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="hight mb-3">
                    <i class="fas fa-chart-line me-2"></i>
                    Dashboard Bénéfices - {{ \Carbon\Carbon::create($annee, $mois)->locale('fr')->isoFormat('MMMM YYYY') }}
                </h3>
                
                <!-- Filtres -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="{{ route('benefices.dashboard') }}" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1"></i> Mois
                                </label>
                                <select name="mois" class="form-select">
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create(null, $m)->locale('fr')->isoFormat('MMMM') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt me-1"></i> Année
                                </label>
                                <select name="annee" class="form-select">
                                    @foreach(range(date('Y'), date('Y') - 5) as $y)
                                        <option value="{{ $y }}" {{ $annee == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Afficher
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cartes principales -->
        <div class="row mb-4">
            <!-- Total Ventes -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #2196F3 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.85rem;">Total Ventes</h6>
                                <h3 class="mb-0 fw-bold" style="color: #2196F3;">
                                    {{ number_format($statsVentes['total_ventes'], 2) }} DH
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas fa-shopping-cart fa-2x opacity-50" style="color: #2196F3;"></i>
                            </div>
                        </div>
                        <small class="text-muted">{{ $statsVentes['nombre_recus'] }} reçu(s)</small>
                        @if($comparaison['ventes_evolution'] != 0)
                            <br>
                            <span class="badge {{ $comparaison['ventes_evolution'] > 0 ? 'bg-success' : 'bg-danger' }} mt-1">
                                <i class="fas fa-arrow-{{ $comparaison['ventes_evolution'] > 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($comparaison['ventes_evolution']), 1) }}% vs mois précédent
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Marge Brute -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4CAF50 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.85rem;">Marge Brute</h6>
                                <h3 class="mb-0 fw-bold" style="color: #4CAF50;">
                                    {{ number_format($benefice['marge_brute'], 2) }} DH
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas fa-coins fa-2x opacity-50" style="color: #4CAF50;"></i>
                            </div>
                        </div>
                        <small class="text-muted">Avant charges</small>
                    </div>
                </div>
            </div>

            <!-- Total Charges -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #FF9800 !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.85rem;">Total Charges</h6>
                                <h3 class="mb-0 fw-bold" style="color: #FF9800;">
                                    {{ number_format($statsCharges['total_charges'], 2) }} DH
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas fa-file-invoice-dollar fa-2x opacity-50" style="color: #FF9800;"></i>
                            </div>
                        </div>
                        <small class="text-muted">
                            Fixe: {{ number_format($statsCharges['total_fixe'], 2) }} DH | 
                            Variable: {{ number_format($statsCharges['total_variable'], 2) }} DH
                        </small>
                        @if($comparaison['charges_evolution'] != 0)
                            <br>
                            <span class="badge {{ $comparaison['charges_evolution'] > 0 ? 'bg-danger' : 'bg-success' }} mt-1">
                                <i class="fas fa-arrow-{{ $comparaison['charges_evolution'] > 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($comparaison['charges_evolution']), 1) }}% vs mois précédent
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bénéfice Net -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid {{ $benefice['benefice_net'] >= 0 ? '#D32F2F' : '#F44336' }} !important;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted text-uppercase mb-1" style="font-size: 0.85rem;">Bénéfice Net</h6>
                                <h3 class="mb-0 fw-bold" style="color: {{ $benefice['benefice_net'] >= 0 ? '#D32F2F' : '#F44336' }};">
                                    {{ number_format($benefice['benefice_net'], 2) }} DH
                                </h3>
                            </div>
                            <div class="text-end">
                                <i class="fas fa-{{ $benefice['benefice_net'] >= 0 ? 'hand-holding-usd' : 'exclamation-triangle' }} fa-2x opacity-50" 
                                   style="color: {{ $benefice['benefice_net'] >= 0 ? '#D32F2F' : '#F44336' }};"></i>
                            </div>
                        </div>
                        <small class="text-muted">
                            Taux: {{ number_format($benefice['taux_marge_nette'], 2) }}%
                        </small>
                        @if($comparaison['benefice_evolution'] != 0)
                            <br>
                            <span class="badge {{ $comparaison['benefice_evolution'] > 0 ? 'bg-success' : 'bg-danger' }} mt-1">
                                <i class="fas fa-arrow-{{ $comparaison['benefice_evolution'] > 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($comparaison['benefice_evolution']), 1) }}% vs mois précédent
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique Évolution Journalière -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-area me-2"></i>
                            Évolution Journalière
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartEvolution" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Produits + Charges par Catégorie -->
        <div class="row mb-4">
            <!-- Top Produits -->
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-trophy me-2"></i>
                            Top 10 Produits Vendus
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Qté</th>
                                        <th class="text-end">Ventes</th>
                                        <th class="text-end">Marge</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProduits as $produit)
                                    <tr>
                                        <td>
                                            <strong>{{ $produit->nom }}</strong>
                                            <br><small class="text-muted">{{ $produit->reference }}</small>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-info">{{ $produit->total_quantite }}</span>
                                        </td>
                                        <td class="text-end">
                                            <strong style="color: #2196F3;">{{ number_format($produit->total_ventes, 2) }} DH</strong>
                                        </td>
                                        <td class="text-end">
                                            <strong style="color: #4CAF50;">{{ number_format($produit->total_marge, 2) }} DH</strong>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Aucune vente</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charges par Catégorie -->
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-tags me-2"></i>
                            Charges par Catégorie
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartCharges" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Résumé Détaillé -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2"></i>
                            Résumé Détaillé
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3" style="color: #2196F3;">
                                    <i class="fas fa-shopping-bag me-2"></i>VENTES
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted">Nombre de reçus:</td>
                                        <td class="text-end fw-bold">{{ $statsVentes['nombre_recus'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Total ventes:</td>
                                        <td class="text-end fw-bold">{{ number_format($statsVentes['total_ventes'], 2) }} DH</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Total encaissé:</td>
                                        <td class="text-end fw-bold text-success">{{ number_format($statsVentes['total_encaisse'], 2) }} DH</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Reste à encaisser:</td>
                                        <td class="text-end fw-bold text-danger">{{ number_format($statsVentes['total_reste'], 2) }} DH</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td class="fw-bold">Marge Brute:</td>
                                        <td class="text-end fw-bold" style="color: #4CAF50;">{{ number_format($statsVentes['marge_brute'], 2) }} DH</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-3" style="color: #FF9800;">
                                    <i class="fas fa-receipt me-2"></i>CHARGES
                                </h6>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted">Nombre de charges:</td>
                                        <td class="text-end fw-bold">{{ $statsCharges['nombre_charges'] }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Charges fixes:</td>
                                        <td class="text-end fw-bold">{{ number_format($statsCharges['total_fixe'], 2) }} DH</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Charges variables:</td>
                                        <td class="text-end fw-bold">{{ number_format($statsCharges['total_variable'], 2) }} DH</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td class="fw-bold">Total Charges:</td>
                                        <td class="text-end fw-bold" style="color: #FF9800;">{{ number_format($statsCharges['total_charges'], 2) }} DH</td>
                                    </tr>
                                </table>
                                <hr>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr class="table-success">
                                        <td class="fw-bold fs-5">BÉNÉFICE NET:</td>
                                        <td class="text-end fw-bold fs-5" style="color: #D32F2F;">
                                            {{ number_format($benefice['benefice_net'], 2) }} DH
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Taux de marge nette:</td>
                                        <td class="text-end fw-bold">{{ number_format($benefice['taux_marge_nette'], 2) }}%</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Graphique Évolution
        const ctxEvolution = document.getElementById('chartEvolution').getContext('2d');
        new Chart(ctxEvolution, {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($evolutionJournaliere)->pluck('date')) !!},
                datasets: [
                    {
                        label: 'Ventes',
                        data: {!! json_encode(collect($evolutionJournaliere)->pluck('ventes')) !!},
                        borderColor: '#2196F3',
                        backgroundColor: 'rgba(33, 150, 243, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Marge Brute',
                        data: {!! json_encode(collect($evolutionJournaliere)->pluck('marge_brute')) !!},
                        borderColor: '#4CAF50',
                        backgroundColor: 'rgba(76, 175, 80, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Charges',
                        data: {!! json_encode(collect($evolutionJournaliere)->pluck('charges')) !!},
                        borderColor: '#FF9800',
                        backgroundColor: 'rgba(255, 152, 0, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Bénéfice Net',
                        data: {!! json_encode(collect($evolutionJournaliere)->pluck('benefice_net')) !!},
                        borderColor: '#D32F2F',
                        backgroundColor: 'rgba(211, 47, 47, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
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
                        ticks: {
                            callback: function(value) {
                                return value + ' DH';
                            }
                        }
                    }
                }
            }
        });

        // Graphique Charges (Doughnut)
        const ctxCharges = document.getElementById('chartCharges').getContext('2d');
        new Chart(ctxCharges, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($chargesParCategorie->pluck('nom')) !!},
                datasets: [{
                    data: {!! json_encode($chargesParCategorie->pluck('total')) !!},
                    backgroundColor: {!! json_encode($chargesParCategorie->pluck('couleur')) !!},
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed.toFixed(2) + ' DH (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>