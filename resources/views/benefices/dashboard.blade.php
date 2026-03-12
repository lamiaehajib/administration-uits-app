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

        <!-- ====== CARTES PRINCIPALES ====== -->
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
                        <small class="text-muted">Avant charges — à répartir sur 3 parties</small>
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

            <!-- Bénéfice Net Global -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #D32F2F !important;">
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
                        <small class="text-muted">Taux: {{ number_format($benefice['taux_marge_nette'], 2) }}%</small>
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

        <!-- ====== RÉPARTITION DES BÉNÉFICES (NOUVEAU) ====== -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white d-flex align-items-center justify-content-between"
                         style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); border-radius: 8px 8px 0 0;">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-chart-pie me-2"></i>
                            Répartition des Bénéfices
                        </h5>
                        <span class="badge bg-light text-dark fw-semibold" style="font-size: 0.8rem;">
                            <i class="fas fa-info-circle me-1"></i>
                            Charges déduites sur la part UCGS uniquement
                        </span>
                    </div>
                    <div class="card-body" style="background: #f8f9fc;">

                        {{-- Barre de répartition visuelle --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-bold text-muted text-uppercase" style="letter-spacing: 0.05em;">
                                    Répartition de la Marge Brute — {{ number_format($benefice['marge_brute'], 2) }} DH
                                </small>
                            </div>
                            <div class="progress" style="height: 28px; border-radius: 14px; overflow: hidden; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);">
                                <div class="progress-bar fw-bold"
                                     style="width: {{ $repartition['khalid']['pourcentage'] }}%; background: #2196F3; font-size: 0.85rem;">
                                    Khalid {{ $repartition['khalid']['pourcentage'] }}%
                                </div>
                                <div class="progress-bar fw-bold"
                                     style="width: {{ $repartition['moutalib']['pourcentage'] }}%; background: #9C27B0; font-size: 0.85rem;">
                                    Moutalib {{ $repartition['moutalib']['pourcentage'] }}%
                                </div>
                                <div class="progress-bar fw-bold"
                                     style="width: {{ $repartition['ucgs']['pourcentage'] }}%; background: #FF5722; font-size: 0.85rem;">
                                    UCGS {{ $repartition['ucgs']['pourcentage'] }}%
                                </div>
                            </div>
                        </div>

                        {{-- Cards des 3 parties --}}
                        <div class="row g-3">

                            {{-- KHALID --}}
                            <div class="col-lg-4">
                                <div class="card border-0 h-100 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                    <div class="card-header py-3 text-white fw-bold d-flex align-items-center gap-2"
                                         style="background: linear-gradient(135deg, #1565C0, #2196F3); border: none;">
                                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                                             style="width: 36px; height: 36px; min-width: 36px;">
                                            <i class="fas fa-user-tie" style="color: #2196F3; font-size: 1rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size: 1rem;">{{ $repartition['khalid']['nom'] }}</div>
                                            <div style="font-size: 0.75rem; opacity: 0.85; font-weight: 400;">
                                                Part {{ $repartition['khalid']['pourcentage'] }}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                <td class="ps-3 py-3 text-muted">
                                                    <i class="fas fa-coins me-1" style="color: #2196F3;"></i>
                                                    Marge brute ({{ $repartition['khalid']['pourcentage'] }}%)
                                                </td>
                                                <td class="pe-3 py-3 text-end fw-bold" style="color: #2196F3;">
                                                    {{ number_format($repartition['khalid']['marge_brute'], 2) }} DH
                                                </td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                <td class="ps-3 py-2 text-muted">
                                                    <i class="fas fa-minus-circle me-1 text-secondary"></i>
                                                    Charges
                                                </td>
                                                <td class="pe-3 py-2 text-end text-muted fw-semibold">
                                                    — <span class="badge bg-light text-muted">Non concerné</span>
                                                </td>
                                            </tr>
                                            <tr style="background: #e3f2fd;">
                                                <td class="ps-3 py-3 fw-bold">
                                                    <i class="fas fa-check-circle me-1" style="color: #1565C0;"></i>
                                                    Bénéfice Net
                                                </td>
                                                <td class="pe-3 py-3 text-end fw-bold fs-5" style="color: #1565C0;">
                                                    {{ number_format($repartition['khalid']['benefice_net'], 2) }} DH
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- MOUTALIB --}}
                            <div class="col-lg-4">
                                <div class="card border-0 h-100 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                    <div class="card-header py-3 text-white fw-bold d-flex align-items-center gap-2"
                                         style="background: linear-gradient(135deg, #6A1B9A, #9C27B0); border: none;">
                                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                                             style="width: 36px; height: 36px; min-width: 36px;">
                                            <i class="fas fa-user-tie" style="color: #9C27B0; font-size: 1rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size: 1rem;">{{ $repartition['moutalib']['nom'] }}</div>
                                            <div style="font-size: 0.75rem; opacity: 0.85; font-weight: 400;">
                                                Part {{ $repartition['moutalib']['pourcentage'] }}%
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                <td class="ps-3 py-3 text-muted">
                                                    <i class="fas fa-coins me-1" style="color: #9C27B0;"></i>
                                                    Marge brute ({{ $repartition['moutalib']['pourcentage'] }}%)
                                                </td>
                                                <td class="pe-3 py-3 text-end fw-bold" style="color: #9C27B0;">
                                                    {{ number_format($repartition['moutalib']['marge_brute'], 2) }} DH
                                                </td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                <td class="ps-3 py-2 text-muted">
                                                    <i class="fas fa-minus-circle me-1 text-secondary"></i>
                                                    Charges
                                                </td>
                                                <td class="pe-3 py-2 text-end text-muted fw-semibold">
                                                    — <span class="badge bg-light text-muted">Non concerné</span>
                                                </td>
                                            </tr>
                                            <tr style="background: #f3e5f5;">
                                                <td class="ps-3 py-3 fw-bold">
                                                    <i class="fas fa-check-circle me-1" style="color: #6A1B9A;"></i>
                                                    Bénéfice Net
                                                </td>
                                                <td class="pe-3 py-3 text-end fw-bold fs-5" style="color: #6A1B9A;">
                                                    {{ number_format($repartition['moutalib']['benefice_net'], 2) }} DH
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- UCGS --}}
                            <div class="col-lg-4">
                                <div class="card border-0 h-100 shadow-sm" style="border-radius: 12px; overflow: hidden;">
                                    <div class="card-header py-3 text-white fw-bold d-flex align-items-center gap-2"
                                         style="background: linear-gradient(135deg, #BF360C, #FF5722); border: none;">
                                        <div class="rounded-circle bg-white d-flex align-items-center justify-content-center"
                                             style="width: 36px; height: 36px; min-width: 36px;">
                                            <i class="fas fa-building" style="color: #FF5722; font-size: 1rem;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size: 1rem;">{{ $repartition['ucgs']['nom'] }}</div>
                                            <div style="font-size: 0.75rem; opacity: 0.85; font-weight: 400;">
                                                Part {{ $repartition['ucgs']['pourcentage'] }}% — Supporte les charges
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                <td class="ps-3 py-3 text-muted">
                                                    <i class="fas fa-coins me-1" style="color: #FF5722;"></i>
                                                    Marge brute ({{ $repartition['ucgs']['pourcentage'] }}%)
                                                </td>
                                                <td class="pe-3 py-3 text-end fw-bold" style="color: #FF5722;">
                                                    {{ number_format($repartition['ucgs']['marge_brute'], 2) }} DH
                                                </td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #e9ecef;">
                                                <td class="ps-3 py-2 text-muted">
                                                    <i class="fas fa-minus-circle me-1 text-danger"></i>
                                                    Total Charges (fixe + variable)
                                                </td>
                                                <td class="pe-3 py-2 text-end fw-bold text-danger">
                                                    - {{ number_format($repartition['ucgs']['charges'], 2) }} DH
                                                </td>
                                            </tr>
                                            <tr style="background: {{ $repartition['ucgs']['benefice_net'] >= 0 ? '#fbe9e7' : '#ffcdd2' }};">
                                                <td class="ps-3 py-3 fw-bold">
                                                    <i class="fas fa-{{ $repartition['ucgs']['benefice_net'] >= 0 ? 'check-circle' : 'exclamation-triangle' }} me-1"
                                                       style="color: {{ $repartition['ucgs']['benefice_net'] >= 0 ? '#BF360C' : '#B71C1C' }};"></i>
                                                    Bénéfice Net
                                                </td>
                                                <td class="pe-3 py-3 text-end fw-bold fs-5"
                                                    style="color: {{ $repartition['ucgs']['benefice_net'] >= 0 ? '#BF360C' : '#B71C1C' }};">
                                                    {{ number_format($repartition['ucgs']['benefice_net'], 2) }} DH
                                                    @if($repartition['ucgs']['benefice_net'] < 0)
                                                        <br><small class="text-danger" style="font-size: 0.7rem;">⚠ Déficit</small>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>{{-- fin row 3 cards --}}

                        {{-- Récapitulatif total --}}
                        {{-- <div class="mt-4 p-3 rounded-3"
                             style="background: linear-gradient(135deg, #1a1a2e, #16213e); color: white;">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-center border-end border-secondary">
                                    <div class="text-white-50 small text-uppercase mb-1">Marge Brute Totale</div>
                                    <div class="fw-bold fs-5" style="color: #4CAF50;">
                                        {{ number_format($benefice['marge_brute'], 2) }} DH
                                    </div>
                                </div>
                                <div class="col-md-4 text-center border-end border-secondary">
                                    <div class="text-white-50 small text-uppercase mb-1">
                                        <i class="fas fa-minus me-1 text-warning"></i>
                                        Total Charges (UCGS)
                                    </div>
                                    <div class="fw-bold fs-5 text-warning">
                                        - {{ number_format($benefice['total_charges'], 2) }} DH
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="text-white-50 small text-uppercase mb-1">Bénéfice Net Global</div>
                                    <div class="fw-bold fs-4" style="color: {{ $benefice['benefice_net'] >= 0 ? '#81C784' : '#EF9A9A' }};">
                                        {{ number_format($benefice['benefice_net'], 2) }} DH
                                    </div>
                                    <small style="color: #90CAF9;">Taux: {{ number_format($benefice['taux_marge_nette'], 2) }}%</small>
                                </div>
                            </div>
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
        {{-- ====== FIN RÉPARTITION ====== --}}

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

                                {{-- Répartition rapide --}}
                                <h6 class="fw-bold mb-2 mt-3" style="color: #555;">
                                    <i class="fas fa-percentage me-2"></i>Répartition Marge Brute
                                </h6>
                                <table class="table table-sm table-borderless">
                                    @foreach($repartition as $key => $part)
                                    <tr>
                                        <td class="text-muted">
                                            <span class="badge" style="background: {{ $part['couleur'] }}; font-size: 0.7rem;">{{ $part['pourcentage'] }}%</span>
                                            {{ $part['nom'] }}:
                                        </td>
                                        <td class="text-end fw-bold" style="color: {{ $part['couleur'] }};">
                                            {{ number_format($part['marge_brute'], 2) }} DH
                                        </td>
                                    </tr>
                                    @endforeach
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
                                        <td class="fw-bold">Total Charges (→ UCGS):</td>
                                        <td class="text-end fw-bold" style="color: #FF9800;">{{ number_format($statsCharges['total_charges'], 2) }} DH</td>
                                    </tr>
                                </table>
                                <hr>
                                <h6 class="fw-bold mb-2" style="color: #FF5722;">
                                    <i class="fas fa-building me-2"></i>BÉNÉFICE NET UCGS
                                </h6>
                                <table class="table table-sm table-borderless mb-0">
                                    <tr>
                                        <td class="text-muted">Marge UCGS (30%):</td>
                                        <td class="text-end fw-bold" style="color: #FF5722;">{{ number_format($repartition['ucgs']['marge_brute'], 2) }} DH</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">— Charges:</td>
                                        <td class="text-end fw-bold text-danger">- {{ number_format($repartition['ucgs']['charges'], 2) }} DH</td>
                                    </tr>
                                    <tr class="{{ $repartition['ucgs']['benefice_net'] >= 0 ? 'table-success' : 'table-danger' }}">
                                        <td class="fw-bold fs-6">Bénéfice Net UCGS:</td>
                                        <td class="text-end fw-bold fs-6" style="color: #BF360C;">
                                            {{ number_format($repartition['ucgs']['benefice_net'], 2) }} DH
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
                        label: 'Part Khalid (35%)',
                        data: {!! json_encode(collect($evolutionJournaliere)->pluck('part_khalid')) !!},
                        borderColor: '#2196F3',
                        borderDash: [5, 5],
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Part Moutalib (35%)',
                        data: {!! json_encode(collect($evolutionJournaliere)->pluck('part_moutalib')) !!},
                        borderColor: '#9C27B0',
                        borderDash: [5, 5],
                        backgroundColor: 'transparent',
                        tension: 0.4,
                        fill: false
                    },
                    {
                        label: 'Bénéfice Net UCGS (30%-charges)',
                        data: {!! json_encode(collect($evolutionJournaliere)->pluck('part_ucgs_nette')) !!},
                        borderColor: '#FF5722',
                        backgroundColor: 'rgba(255, 87, 34, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
                    },
                    {
                        label: 'Charges',
                        data: {!! json_encode(collect($evolutionJournaliere)->pluck('charges')) !!},
                        borderColor: '#FF9800',
                        backgroundColor: 'rgba(255, 152, 0, 0.07)',
                        tension: 0.4,
                        fill: true
                    },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: true, position: 'top' },
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
                        ticks: { callback: v => v + ' DH' }
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
                    legend: { position: 'right' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed.toFixed(2) + ' DH (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>