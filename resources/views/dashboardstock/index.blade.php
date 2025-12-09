<x-app-layout>
<div class="dashboard-wrapper">
    <!-- En-tête avec filtre de dates -->
    <div class="dashboard-header">
        <div class="header-card">
            <div class="header-content">
                <div class="header-title">
                    <div class="icon-wrapper">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <h3 class="title-gradient">Tableau de Bord</h3>
                </div>
                
                <form method="GET" action="{{ route('dashboardstock') }}" class="filter-form">
                    <div class="filter-group">
                        <select name="mois" id="mois-filter" class="form-select-modern">
                            <option value="">🔍 Toute la période</option>
                            @foreach($moisDisponibles as $mois)
                                <option value="{{ $mois['value'] }}" 
                                        data-debut="{{ $mois['date_debut'] }}" 
                                        data-fin="{{ $mois['date_fin'] }}">
                                    {{ $mois['label'] }}
                                </option>
                            @endforeach
                        </select>

                        <span class="separator">OU</span>
                        
                        <input type="date" name="date_debut" id="date-debut" 
                               class="date-input" value="{{ $dateDebut->format('Y-m-d') }}">
                        <input type="date" name="date_fin" id="date-fin" 
                               class="date-input" value="{{ $dateFin->format('Y-m-d') }}">
                        
                        <button type="submit" class="btn-primary-gradient">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ route('dashboardstock') }}" class="btn-secondary-outline">
                            <i class="fas fa-redo"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
            
            <div class="period-info">
                <i class="fas fa-calendar-alt"></i> Période affichée: 
                <strong>{{ $dateDebut->format('d/m/Y') }} - {{ $dateFin->format('d/m/Y') }}</strong>
                <span class="badge-days">{{ $dateDebut->diffInDays($dateFin) + 1 }} jours</span>
            </div>
        </div>
    </div>

    <!-- KPIs Cards -->
    <div class="kpi-grid">
        <!-- CA Total -->
        <div class="kpi-card gradient-purple">
            <div class="kpi-content">
                <div class="kpi-header">
                    <div class="kpi-info">
                        <h6 class="kpi-label">Chiffre d'Affaires</h6>
                        <h3 class="kpi-value">{{ number_format($kpis['ca_total'], 2) }} <span>DH</span></h3>
                        <div class="kpi-variation {{ $comparaison['positif'] ? 'positive' : 'negative' }}">
                            <i class="fas fa-arrow-{{ $comparaison['positif'] ? 'up' : 'down' }}"></i>
                            {{ $comparaison['positif'] ? '+' : '' }}{{ number_format($comparaison['variation'], 1) }}%
                            <span class="variation-label">vs période précédente</span>
                        </div>
                    </div>
                    <div class="kpi-icon">
                        <i class="fas fa-coins"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Marge (Masqué pour Vendeur) -->
        @if(!$isVendeur)
        <div class="kpi-card gradient-green">
            <div class="kpi-content">
                <div class="kpi-header">
                    <div class="kpi-info">
                        <h6 class="kpi-label">Marge Totale</h6>
                        <h3 class="kpi-value">{{ number_format($kpis['marge_totale'], 2) }} <span>DH</span></h3>
                        <div class="kpi-variation positive">
                            <i class="fas fa-percentage"></i>
                            Taux: {{ number_format($kpis['taux_marge'], 1) }}%
                        </div>
                    </div>
                    <div class="kpi-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Nombre de Ventes -->
        <div class="kpi-card gradient-pink">
            <div class="kpi-content">
                <div class="kpi-header">
                    <div class="kpi-info">
                        <h6 class="kpi-label">Nombre de Ventes</h6>
                        <h3 class="kpi-value">{{ $kpis['nombre_ventes'] }} <span>ventes</span></h3>
                        <div class="kpi-variation positive">
                            <i class="fas fa-shopping-basket"></i>
                            Panier moyen: {{ $kpis['nombre_ventes'] > 0 ? number_format($kpis['ca_total'] / $kpis['nombre_ventes'], 2) : 0 }} DH
                        </div>
                    </div>
                    <div class="kpi-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Valeur Stock (Masqué pour Vendeur) -->
        @if(!$isVendeur)
        <div class="kpi-card gradient-orange">
            <div class="kpi-content">
                <div class="kpi-header">
                    <div class="kpi-info">
                        <h6 class="kpi-label">Valeur Stock</h6>
                        <h3 class="kpi-value">{{ number_format($kpis['valeur_stock'], 2) }} <span>DH</span></h3>
                        <div class="kpi-variation {{ $kpis['produits_alerte'] > 0 ? 'negative' : 'positive' }}">
                            <i class="fas fa-{{ $kpis['produits_alerte'] > 0 ? 'exclamation-triangle' : 'check-circle' }}"></i>
                            @if($kpis['produits_alerte'] > 0)
                                {{ $kpis['produits_alerte'] }} alertes stock
                            @else
                                Aucune alerte
                            @endif
                        </div>
                    </div>
                    <div class="kpi-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Mini Stats -->
    <div class="mini-stats-grid">
        <div class="mini-stat-card">
            <div class="mini-stat-icon blue">
                <i class="fas fa-cube"></i>
            </div>
            <div class="mini-stat-info">
                <h5>{{ $kpis['produits_actifs'] }}</h5>
                <p>Produits Actifs</p>
            </div>
        </div>

        <div class="mini-stat-card">
            <div class="mini-stat-icon green">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="mini-stat-info">
                <h5>{{ number_format($kpis['paiements_jour'], 2) }} DH</h5>
                <p>Paiements Aujourd'hui</p>
            </div>
        </div>

        <!-- Total Achats (Masqué pour Vendeur) -->
        @if(!$isVendeur)
        <div class="mini-stat-card">
            <div class="mini-stat-icon cyan">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="mini-stat-info">
                <h5>{{ number_format($kpis['total_achats'], 2) }} DH</h5>
                <p>Total Achats</p>
            </div>
        </div>
        @endif

        <div class="mini-stat-card">
            <div class="mini-stat-icon red">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="mini-stat-info">
                <h5>{{ $kpis['produits_alerte'] }}</h5>
                <p>Produits en Alerte</p>
            </div>
        </div>
    </div>

    <!-- Graphiques Principaux -->
    <div class="charts-row">
        <!-- Évolution -->
        <div class="chart-card large">
            <div class="card-header-modern">
                <div class="header-icon">
                    <i class="fas fa-chart-area"></i>
                </div>
                <div class="header-text">
                    <h5>
                        @if($isVendeur)
                            Évolution du Chiffre d'Affaires
                        @else
                            Évolution CA, Achats & Marge
                        @endif
                    </h5>
                    <p>Suivi sur la période sélectionnée</p>
                </div>
            </div>
            <div class="card-body-modern">
                <canvas id="evolutionChart" height="80"></canvas>
            </div>
        </div>

        <!-- Modes de Paiement -->
        <div class="chart-card small">
            <div class="card-header-modern">
                <div class="header-icon">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="header-text">
                    <h5>Modes de Paiement</h5>
                    <p>Répartition des encaissements</p>
                </div>
            </div>
            <div class="card-body-modern center">
                @if($paiementsModes->count() > 0)
                    <canvas id="paiementsChart"></canvas>
                @else
                    <div class="empty-state">
                        <i class="fas fa-info-circle"></i>
                        <p>Aucun paiement sur cette période</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Produits & Catégories -->
    <div class="charts-row">
        <div class="chart-card medium">
            <div class="card-header-modern">
                <div class="header-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="header-text">
                    <h5>Top 10 Produits Vendus</h5>
                    <p>Classement par quantité vendue</p>
                </div>
            </div>
            <div class="card-body-modern">
                @if($topProduits->count() > 0)
                    <canvas id="topProduitsChart" height="100"></canvas>
                @else
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <p>Aucune vente sur cette période</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="chart-card medium">
            <div class="card-header-modern">
                <div class="header-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="header-text">
                    <h5>Ventes par Catégorie</h5>
                    <p>Chiffre d'affaires par catégorie</p>
                </div>
            </div>
            <div class="card-body-modern">
                @if($ventesByCategorie->count() > 0)
                    <canvas id="categoriesChart" height="100"></canvas>
                @else
                    <div class="empty-state">
                        <i class="fas fa-tags"></i>
                        <p>Aucune catégorie vendue</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alertes & Dernières Ventes -->
    <div class="tables-row">
        <!-- Produits en Alerte -->
        <div class="table-card">
            <div class="card-header-modern">
                <div class="header-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="header-text">
                    <h5>Alertes Stock</h5>
                    <p>Produits nécessitant un réapprovisionnement</p>
                </div>
                @if($produitsRupture->count() > 0)
                    <span class="badge-alert">{{ $produitsRupture->count() }}</span>
                @endif
            </div>
            <div class="card-body-modern no-padding">
                @if($produitsRupture->count() > 0)
                    <div class="table-wrapper">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Catégorie</th>
                                    <th>Stock</th>
                                    <th>Alerte</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produitsRupture as $produit)
                                <tr>
                                    <td>
                                        <div class="product-cell">
                                            <strong>{{ $produit->nom }}</strong>
                                            <small>{{ $produit->reference }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-category">{{ $produit->category->nom ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-stock {{ $produit->quantite_stock == 0 ? 'danger' : 'warning' }}">
                                            {{ $produit->quantite_stock }}
                                        </span>
                                    </td>
                                    <td>{{ $produit->stock_alerte }}</td>
                                    <td>
                                        @if($produit->quantite_stock == 0)
                                            <span class="status-badge danger">
                                                <i class="fas fa-times-circle"></i> Rupture
                                            </span>
                                        @else
                                            <span class="status-badge warning">
                                                <i class="fas fa-exclamation-triangle"></i> Faible
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state success">
                        <i class="fas fa-check-circle"></i>
                        <h5>Aucune alerte stock</h5>
                        <p>Tous les produits ont un niveau de stock suffisant</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Dernières Ventes -->
        <div class="table-card">
            <div class="card-header-modern">
                <div class="header-icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="header-text">
                    <h5>Dernières Ventes</h5>
                    <p>5 dernières transactions enregistrées</p>
                </div>
            </div>
            <div class="card-body-modern no-padding">
                @if($dernieresVentes->count() > 0)
                    <div class="table-wrapper">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>N° Reçu</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dernieresVentes as $vente)
                                <tr>
                                    <td>
                                        <a href="{{ route('recus.show', $vente) }}" class="link-modern">
                                            {{ $vente->numero_recu }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="client-cell">
                                            <strong>{{ $vente->client_nom }}</strong>
                                            @if($vente->client_telephone)
                                                <small>{{ $vente->client_telephone }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td><strong class="amount">{{ number_format($vente->total, 2) }} DH</strong></td>
                                    <td>
                                        @if($vente->statut === 'livre')
                                            <span class="status-badge success">
                                                <i class="fas fa-check"></i> Livré
                                            </span>
                                        @elseif($vente->statut === 'en_cours')
                                            <span class="status-badge warning">
                                                <i class="fas fa-clock"></i> En cours
                                            </span>
                                        @else
                                            <span class="status-badge secondary">{{ $vente->statut }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="date-text">{{ $vente->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h5>Aucune vente récente</h5>
                        <p>Les ventes apparaîtront ici dès leur création</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* ==================== VARIABLES ==================== */
:root {
    --gradient-purple: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-green: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    --gradient-pink: linear-gradient(135deg, #667eea 0%, #f093fb 100%);
    --gradient-orange: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%);
    --gradient-primary: linear-gradient(135deg, #C2185B, #D32F2F);
    
    --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
    --shadow-md: 0 4px 16px rgba(0,0,0,0.1);
    --shadow-lg: 0 8px 32px rgba(0,0,0,0.12);
    --shadow-xl: 0 12px 48px rgba(0,0,0,0.15);
    
    --radius: 16px;
    --radius-sm: 12px;
    --radius-lg: 20px;
    
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* ==================== DASHBOARD WRAPPER ==================== */
.dashboard-wrapper {
    padding: 24px;
    max-width: 1800px;
    margin: 0 auto;
    animation: fadeIn 0.6s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ==================== HEADER ==================== */
.dashboard-header {
    margin-bottom: 32px;
}

.header-card {
    background: white;
    border-radius: var(--radius);
    padding: 24px;
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(0,0,0,0.05);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}

.header-title {
    display: flex;
    align-items: center;
    gap: 16px;
}

.icon-wrapper {
    width: 56px;
    height: 56px;
    background: var(--gradient-primary);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 16px rgba(194, 24, 91, 0.3);
}

.title-gradient {
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 28px;
    font-weight: 700;
    margin: 0;
}

.filter-form {
    flex: 1;
    max-width: 900px;
}

.filter-group {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

.form-select-modern,
.date-input {
    height: 42px;
    border: 2px solid #e9ecef;
    border-radius: var(--radius-sm);
    padding: 0 16px;
    font-size: 14px;
    transition: var(--transition);
    background: white;
}

.form-select-modern {
    min-width: 200px;
}

.date-input {
    width: 160px;
}

.form-select-modern:focus,
.date-input:focus {
    border-color: #C2185B;
    outline: none;
    box-shadow: 0 0 0 4px rgba(194, 24, 91, 0.1);
}

.separator {
    color: #6c757d;
    font-weight: 500;
    padding: 0 8px;
}

.btn-primary-gradient,
.btn-secondary-outline {
    height: 42px;
    padding: 0 24px;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 14px;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: none;
    cursor: pointer;
}

.btn-primary-gradient {
    background: var(--gradient-primary);
    color: white;
    box-shadow: 0 4px 12px rgba(194, 24, 91, 0.3);
}

.btn-primary-gradient:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
}

.btn-secondary-outline {
    background: white;
    color: #6c757d;
    border: 2px solid #e9ecef;
}

.btn-secondary-outline:hover {
    border-color: #C2185B;
    color: #C2185B;
    background: rgba(194, 24, 91, 0.05);
}

.period-info {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid #f0f0f0;
    color: #6c757d;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.period-info strong {
    color: #2c3e50;
}

.badge-days {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    margin-left: 8px;
}

/* ==================== KPI CARDS ==================== */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.kpi-card {
    border-radius: var(--radius);
    padding: 28px;
    color: white;
    box-shadow: var(--shadow-lg);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    animation: slideUp 0.6s ease backwards;
}

.kpi-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    transition: var(--transition);
    opacity: 0;
}

.kpi-card:hover::before {
    opacity: 1;
    animation: pulse 2s ease infinite;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1) rotate(0deg); }
    50% { transform: scale(1.1) rotate(180deg); }
}

.kpi-card:nth-child(1) { animation-delay: 0.1s; }
.kpi-card:nth-child(2) { animation-delay: 0.2s; }
.kpi-card:nth-child(3) { animation-delay: 0.3s; }
.kpi-card:nth-child(4) { animation-delay: 0.4s; }

.kpi-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

.gradient-purple { background: var(--gradient-purple); }
.gradient-green { background: var(--gradient-green); }
.gradient-pink { background: var(--gradient-pink); }
.gradient-orange { background: var(--gradient-orange); }

.kpi-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.kpi-label {
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    opacity: 0.85;
    margin-bottom: 12px;
    font-weight: 600;
}

.kpi-value {
    font-size: 32px;
    font-weight: 700;
    margin: 0;
    line-height: 1.2;
    color: white;
}

.kpi-value span {
    font-size: 16px;
    font-weight: 500;
    opacity: 0.9;
}

.kpi-variation {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
    font-size: 13px;
    opacity: 0.9;
}

.kpi-variation i {
    font-size: 14px;
}

.kpi-icon {
    font-size: 48px;
    opacity: 0.2;
    transition: var(--transition);
}

.kpi-card:hover .kpi-icon {
    opacity: 0.3;
    transform: scale(1.1) rotate(5deg);
}

/* ==================== MINI STATS ==================== */
.mini-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.mini-stat-card {
    background: white;
    border-radius: var(--radius-sm);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    border: 1px solid rgba(0,0,0,0.05);
}

.mini-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
}

.mini-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    flex-shrink: 0;
}

.mini-stat-icon.blue { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
.mini-stat-icon.green { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.mini-stat-icon.cyan { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.mini-stat-icon.red { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }

.mini-stat-info h5 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #2c3e50;
}

.mini-stat-info p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #6c757d;
}

/* ==================== CHARTS ==================== */
.charts-row {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

.charts-row:last-of-type {
    grid-template-columns: 1fr 1fr;
}

    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
    transition: var(--transition);
}

.chart-card:hover {
    box-shadow: var(--shadow-lg);
}

.card-header-modern {
    padding: 24px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 16px;
}

.header-icon {
    width: 48px;
    height: 48px;
    background: var(--gradient-primary);
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
}

.header-text h5 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
}

.header-text p {
    margin: 4px 0 0;
    font-size: 13px;
    color: #6c757d;
}

.badge-alert {
    background: linear-gradient(135deg, #f5576c, #f093fb);
    color: white;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    margin-left: auto;
}

.card-body-modern {
    padding: 24px;
}

.card-body-modern.center {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
}

.card-body-modern.no-padding {
    padding: 0;
}

.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: #6c757d;
}

.empty-state i {
    font-size: 64px;
    margin-bottom: 16px;
    opacity: 0.3;
}

.empty-state.success i {
    color: #38ef7d;
    opacity: 0.8;
}

.empty-state h5 {
    font-size: 20px;
    font-weight: 600;
    color: #2c3e50;
    margin: 16px 0 8px;
}

.empty-state p {
    font-size: 14px;
    margin: 0;
}

/* ==================== TABLES ==================== */
.tables-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.table-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
}

.table-wrapper {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table thead {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.modern-table th {
    padding: 16px 20px;
    text-align: left;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: #6c757d;
    letter-spacing: 0.5px;
}

.modern-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: var(--transition);
}

.modern-table tbody tr:hover {
    background: #f8f9fa;
}

.modern-table td {
    padding: 16px 20px;
    font-size: 14px;
    color: #2c3e50;
}

.product-cell strong,
.client-cell strong {
    display: block;
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 4px;
}

.product-cell small,
.client-cell small {
    display: block;
    color: #6c757d;
    font-size: 12px;
}

.badge-category {
    background: #e9ecef;
    color: #495057;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}

.badge-stock {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 700;
    display: inline-block;
}

.badge-stock.danger {
    background: linear-gradient(135deg, #f5576c, #f093fb);
    color: white;
}

.badge-stock.warning {
    background: linear-gradient(135deg, #f2994a, #f2c94c);
    color: white;
}

.status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.status-badge.success {
    background: linear-gradient(135deg, #11998e, #38ef7d);
    color: white;
}

.status-badge.warning {
    background: linear-gradient(135deg, #f2994a, #f2c94c);
    color: white;
}

.status-badge.danger {
    background: linear-gradient(135deg, #f5576c, #f093fb);
    color: white;
}

.status-badge.secondary {
    background: #e9ecef;
    color: #495057;
}

.link-modern {
    color: #C2185B;
    font-weight: 700;
    text-decoration: none;
    transition: var(--transition);
}

.link-modern:hover {
    color: #D32F2F;
    text-decoration: underline;
}

.amount {
    color: #11998e;
    font-size: 15px;
}

.date-text {
    color: #6c757d;
}

/* ==================== RESPONSIVE ==================== */
@media (max-width: 1200px) {
    .charts-row {
        grid-template-columns: 1fr;
    }
    
    .tables-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dashboard-wrapper {
        padding: 16px;
    }
    
    .header-content {
        flex-direction: column;
        align-items: stretch;
    }
    
    .filter-group {
        flex-direction: column;
        align-items: stretch;
    }
    
    .form-select-modern,
    .date-input,
    .btn-primary-gradient,
    .btn-secondary-outline {
        width: 100%;
    }
    
    .kpi-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    
    .kpi-value {
        font-size: 24px;
    }
    
    .mini-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .modern-table {
        font-size: 12px;
    }
    
    .modern-table th,
    .modern-table td {
        padding: 12px;
    }
}

@media (max-width: 576px) {
    .title-gradient {
        font-size: 22px;
    }
    
    .icon-wrapper {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }
    
    .kpi-card {
        padding: 20px;
    }
    
    .kpi-icon {
        font-size: 36px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Auto-remplir les dates
document.getElementById('mois-filter')?.addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const debut = opt.getAttribute('data-debut');
    const fin = opt.getAttribute('data-fin');
    if (debut && fin) {
        document.getElementById('date-debut').value = debut;
        document.getElementById('date-fin').value = fin;
    }
});

// Configuration Chart.js
Chart.defaults.font.family = "'Ubuntu', sans-serif";
Chart.defaults.color = '#6c757d';

const colors = {
    primary: '#C2185B',
    secondary: '#D32F2F',
    success: '#38ef7d',
    info: '#667eea',
    warning: '#f2994a'
};

const isVendeur = @json($isVendeur);

// Évolution Chart
@if($evolutionVentes && count($evolutionVentes['labels']) > 0)
const evolutionDatasets = [{
    label: 'Ventes',
    data: @json($evolutionVentes['ventes']),
    borderColor: colors.primary,
    backgroundColor: 'rgba(194, 24, 91, 0.1)',
    fill: true,
    tension: 0.4,
    borderWidth: 3
}];

@if(!$isVendeur && $evolutionVentes['achats'])
evolutionDatasets.push({
    label: 'Achats',
    data: @json($evolutionVentes['achats']),
    borderColor: colors.secondary,
    backgroundColor: 'rgba(211, 47, 47, 0.1)',
    fill: true,
    tension: 0.4,
    borderWidth: 3
});
@endif

@if(!$isVendeur && $evolutionVentes['marges'])
evolutionDatasets.push({
    label: 'Marge',
    data: @json($evolutionVentes['marges']),
    borderColor: colors.success,
    backgroundColor: 'rgba(56, 239, 125, 0.1)',
    fill: true,
    tension: 0.4,
    borderWidth: 3
});
@endif

new Chart(document.getElementById('evolutionChart'), {
    type: 'line',
    data: {
        labels: @json($evolutionVentes['labels']),
        datasets: evolutionDatasets
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { usePointStyle: true, padding: 15 } },
            tooltip: {
                callbacks: {
                    label: ctx => ctx.dataset.label + ': ' + ctx.parsed.y.toFixed(2) + ' DH'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: val => val.toLocaleString() + ' DH' }
            }
        }
    }
});
@endif

// Paiements Chart
@if($paiementsModes->count() > 0)
new Chart(document.getElementById('paiementsChart'), {
    type: 'doughnut',
    data: {
        labels: @json($paiementsModes->pluck('mode_paiement')->map(fn($m) => ucfirst($m))),
        datasets: [{
            data: @json($paiementsModes->pluck('total')),
            backgroundColor: [colors.primary, colors.success, colors.warning, colors.secondary, colors.info],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } },
            tooltip: {
                callbacks: {
                    label: ctx => {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const pct = ((ctx.parsed / total) * 100).toFixed(1);
                        return ctx.label + ': ' + ctx.parsed.toFixed(2) + ' DH (' + pct + '%)';
                    }
                }
            }
        }
    }
});
@endif

// Top Produits Chart
@if($topProduits->count() > 0)
new Chart(document.getElementById('topProduitsChart'), {
    type: 'bar',
    data: {
        labels: @json($topProduits->pluck('nom')),
        datasets: [{
            label: 'Quantité Vendue',
            data: @json($topProduits->pluck('quantite_vendue')),
            backgroundColor: 'rgba(194, 24, 91, 0.8)',
            borderColor: colors.primary,
            borderWidth: 2
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
@endif

// Catégories Chart
@if($ventesByCategorie->count() > 0)
new Chart(document.getElementById('categoriesChart'), {
    type: 'bar',
    data: {
        labels: @json($ventesByCategorie->pluck('categorie')),
        datasets: [{
            label: 'CA (DH)',
            data: @json($ventesByCategorie->pluck('total_ventes')),
            backgroundColor: 'rgba(56, 239, 125, 0.8)',
            borderColor: colors.success,
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { callback: val => val.toLocaleString() + ' DH' }
            }
        }
    }
});
@endif
</script>
</x-app-layout>