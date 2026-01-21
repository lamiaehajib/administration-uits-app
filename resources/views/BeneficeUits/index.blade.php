<x-app-layout>
<div class="container-fluid py-4">
    <!-- 🎯 En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="mb-2 hight">
                        <i class="fas fa-chart-line"></i>
                        Tableau de Bénéfice
                    </h3>
                    <p class="text-muted mb-0">Analyse complète des revenus et bénéfices</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 🔍 Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('benefice.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Date Début</label>
                            <input type="date" name="date_from" class="form-control" 
                                   value="{{ $dateFrom }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Date Fin</label>
                            <input type="date" name="date_to" class="form-control" 
                                   value="{{ $dateTo }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Devise</label>
                            <select name="currency" class="form-select">
                                <option value="DH" {{ $currency == 'DH' ? 'selected' : '' }}>DH</option>
                                <option value="EUR" {{ $currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="CFA" {{ $currency == 'CFA' ? 'selected' : '' }}>CFA</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary text-white">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                            <a href="{{ route('benefice.export', request()->all()) }}" 
                               class="btn btn-success text-white">
                                <i class="fas fa-file-excel"></i> Export
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 📊 Statistiques Principales -->
    <div class="row mb-4">
        <!-- Total Revenus -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-success border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Revenus</h6>
                            <h3 class="mb-0 text-success fw-bold">
                                {{ number_format($details['revenus']['total'], 2) }}
                            </h3>
                            <small class="text-muted">{{ $currency }}</small>
                        </div>
                        <div class="icon-box bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-arrow-up fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Coûts -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-danger border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Coûts</h6>
                            <h3 class="mb-0 text-danger fw-bold">
                                {{ number_format($details['couts']['total'], 2) }}
                            </h3>
                            <small class="text-muted">{{ $currency }}</small>
                        </div>
                        <div class="icon-box bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-arrow-down fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bénéfice Net -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-4 h-100" style="border-color: #C2185B !important;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Bénéfice Net</h6>
                            <h3 class="mb-0 fw-bold hight">
                                {{ number_format($details['benefice_net'], 2) }}
                            </h3>
                            <small class="text-muted">{{ $currency }}</small>
                        </div>
                        <div class="icon-box p-3 rounded-circle" style="background: linear-gradient(135deg, #C2185B15, #D32F2F15);">
                            <i class="fas fa-coins fa-2x" style="color: #C2185B;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marge Bénéficiaire -->
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-start border-info border-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Marge Bénéfice</h6>
                            <h3 class="mb-0 text-info fw-bold">
                                {{ $details['marge_benefice'] }}%
                            </h3>
                            <small class="text-muted">Taux de rentabilité</small>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-percent fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 💰 Détails Revenus & Coûts -->
   <div class="row mb-4">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Détails Revenus</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover mb-0">
                    <tbody>
                        <tr>
                            <td>
                                <i class="fas fa-cogs" style="color: #C2185B;"></i> 
                                <strong>Factures Services</strong>
                            </td>
                            <td class="text-end fw-bold">
                                {{ number_format($details['revenus']['services'], 2) }} <small>{{ $currency }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-graduation-cap text-warning"></i> 
                                <strong>Factures Formations</strong>
                            </td>
                            <td class="text-end fw-bold">
                                {{ number_format($details['revenus']['formations'], 2) }} <small>{{ $currency }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <i class="fas fa-user-tie text-info"></i> 
                                <strong>Reçus Stages</strong>
                            </td>
                            <td class="text-end fw-bold">
                                {{ number_format($details['revenus']['stages'], 2) }} <small>{{ $currency }}</small>
                            </td>
                        </tr>
                        
                        <tr class="table-light">
                            <td>
                                <i class="fas fa-globe text-primary"></i> 
                                <strong>Portail (uits-portail.ma)</strong>
                                <span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">API</span>
                            </td>
                            <td class="text-end fw-bold text-primary">
                                {{ number_format($details['revenus']['portail'] ?? 0, 2) }} <small>{{ $currency }}</small>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <td class="fw-bold text-uppercase">TOTAL REVENUS</td>
                            <td class="text-end fw-bold fs-5">
                                {{ number_format($details['revenus']['total'], 2) }} <small>{{ $currency }}</small>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

        <!-- Détails Coûts & Stats -->
        <div class="col-lg-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Détails Coûts & Statistiques</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover mb-3">
                        <tbody>
                            <tr>
                                <td>
                                    <i class="fas fa-box text-secondary"></i> 
                                    <strong>Coût Produits</strong>
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format($details['couts']['produits'], 2) }} <small>{{ $currency }}</small>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr class="table-danger">
                                <td class="fw-bold text-uppercase">TOTAL COÛTS</td>
                                <td class="text-end fw-bold fs-5">
                                    {{ number_format($details['couts']['total'], 2) }} <small>{{ $currency }}</small>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Statistiques -->
                    <hr>
                    <h6 class="text-muted mb-3"><i class="fas fa-chart-bar"></i> Statistiques Période</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="stat-box p-2 rounded bg-light">
                                <small class="text-muted d-block">Factures Services</small>
                                <span class="badge bg-primary">{{ $stats['total_factures_services'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-2 rounded bg-light">
                                <small class="text-muted d-block">Factures Formations</small>
                                <span class="badge bg-warning">{{ $stats['total_factures_formations'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-2 rounded bg-light">
                                <small class="text-muted d-block">Stages</small>
                                <span class="badge bg-info">{{ $stats['total_stages'] }}</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-box p-2 rounded bg-light">
                                <small class="text-muted d-block">Moyenne Facture</small>
                                <span class="badge bg-secondary">{{ number_format($stats['moyenne_facture'], 2) }} {{ $currency }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 📈 Évolution Mensuelle -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-area"></i> Évolution sur 6 Derniers Mois</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mois</th>
                                    <th class="text-end">Revenus</th>
                                    <th class="text-end">Coûts</th>
                                    <th class="text-end">Bénéfice</th>
                                    <th class="text-center">Tendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($evolutionMensuelle as $mois)
                                <tr>
                                    <td class="fw-bold">{{ $mois['mois'] }}</td>
                                    <td class="text-end text-success">
                                        {{ number_format($mois['revenus'], 2) }} <small>{{ $currency }}</small>
                                    </td>
                                    <td class="text-end text-danger">
                                        {{ number_format($mois['couts'], 2) }} <small>{{ $currency }}</small>
                                    </td>
                                    <td class="text-end fw-bold {{ $mois['benefice'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format($mois['benefice'], 2) }} <small>{{ $currency }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($mois['benefice'] >= 0)
                                            <i class="fas fa-arrow-up text-success fa-lg"></i>
                                        @else
                                            <i class="fas fa-arrow-down text-danger fa-lg"></i>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 🏆 Top Clients -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                    <h5 class="mb-0"><i class="fas fa-trophy"></i> Top 5 Meilleurs Clients</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 80px;">Rang</th>
                                    <th>Client</th>
                                    <th class="text-end">Total Dépensé</th>
                                    <th class="text-center" style="width: 120px;">Badge</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topClients as $index => $client)
                                <tr>
                                    <td class="text-center fw-bold fs-4">
                                        @if($index == 0)
                                            <span style="color: #FFD700;">{{ $index + 1 }}</span>
                                        @elseif($index == 1)
                                            <span style="color: #C0C0C0;">{{ $index + 1 }}</span>
                                        @elseif($index == 2)
                                            <span style="color: #CD7F32;">{{ $index + 1 }}</span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $client['client'] }}</td>
                                    <td class="text-end fw-bold">
                                        {{ number_format($client['total'], 2) }} <small>{{ $currency }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <span class="badge" style="background: linear-gradient(135deg, #FFD700, #FFA500);">
                                                🥇 Champion
                                            </span>
                                        @elseif($index == 1)
                                            <span class="badge" style="background: linear-gradient(135deg, #C0C0C0, #808080);">
                                                🥈 2ème Place
                                            </span>
                                        @elseif($index == 2)
                                            <span class="badge" style="background: linear-gradient(135deg, #CD7F32, #8B4513);">
                                                🥉 3ème Place
                                            </span>
                                        @else
                                            <span class="badge bg-info">Top {{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                        Aucun client trouvé pour cette période
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 10px;
    transition: transform 0.2s, box-shadow 0.2s;
    border: none;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.bg-opacity-10 {
    background-color: rgba(var(--bs-success-rgb), 0.1) !important;
}
.stat-box {
    border: 1px solid #dee2e6;
    transition: all 0.3s;
}
.stat-box:hover {
    border-color: #C2185B;
    box-shadow: 0 2px 8px rgba(194, 24, 91, 0.1);
}
.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}
.btn-primary {
    background: linear-gradient(135deg, #C2185B, #D32F2F) !important;
    border: none;
}
.btn-primary:hover {
    background: linear-gradient(135deg, #D32F2F, #C2185B) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(194, 24, 91, 0.3);
}
.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation des cartes au chargement
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.5s ease';
            
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 50);
    });
});
</script>
@endpush
</x-app-layout>