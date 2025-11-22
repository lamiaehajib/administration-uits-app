<x-app-layout>
<style>
    :root {
        --primary: #D32F2F;
        --primary-dark: #C2185B;
        --danger: #ef4444;
        --gradient: linear-gradient(135deg, #C2185B, #D32F2F);
    }

    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--gradient);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(211, 47, 47, 0.15);
    }

    .stat-card .icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin-bottom: 15px;
    }

    .stat-card .icon.primary { background: linear-gradient(135deg, #C2185B20, #D32F2F20); color: #D32F2F; }
    .stat-card .icon.warning { background: #fef3c720; color: #f59e0b; }
    .stat-card .icon.success { background: #d1fae520; color: #10b981; }
    .stat-card .icon.info { background: #dbeafe20; color: #3b82f6; }

    .stat-card h3 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 5px 0;
        background: var(--gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .stat-card p {
        color: #6b7280;
        font-size: 14px;
        margin: 0;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f3f4f6;
    }

    .filter-header h5 {
        margin: 0;
        font-weight: 600;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-header h5 i {
        color: var(--primary);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .filter-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 10px 14px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
    }

    /* Alerts Section */
    .alerts-section {
        margin-bottom: 25px;
    }

    .alert-custom {
        border-radius: 12px;
        padding: 16px 20px;
        border: none;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .alert-custom.warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
    }

    .alert-custom.info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #1e40af;
    }

    .alert-custom i {
        font-size: 20px;
    }

    /* Table Section */
    .table-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .table-header {
        background: var(--gradient);
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .table-header h5 {
        margin: 0;
        color: white;
        font-weight: 600;
        font-size: 18px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-custom {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-custom.primary {
        background: white;
        color: var(--primary);
    }

    .btn-custom.primary:hover {
        background: #f3f4f6;
        transform: translateY(-2px);
    }

    .btn-custom.outline {
        background: transparent;
        color: white;
        border: 2px solid white;
    }

    .btn-custom.outline:hover {
        background: white;
        color: var(--primary);
    }

    .btn-custom.success {
        background: #10b981;
        color: white;
    }

    .btn-custom.danger {
        background: var(--danger);
        color: white;
    }

    /* Custom Table */
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }

    .custom-table thead th {
        background: #f8f9fa;
        padding: 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        border-bottom: 2px solid #e5e7eb;
    }

    .custom-table thead th a {
        color: #6b7280;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .custom-table thead th a:hover {
        color: var(--primary);
    }

    .custom-table tbody tr {
        transition: all 0.2s ease;
    }

    .custom-table tbody tr:hover {
        background: linear-gradient(135deg, #fdf2f8, #fff1f2);
    }

    .custom-table tbody td {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-badge.en_attente { background: #fef3c7; color: #d97706; }
    .status-badge.en_cours { background: #dbeafe; color: #2563eb; }
    .status-badge.termine { background: #d1fae5; color: #059669; }
    .status-badge.livre { background: #e0e7ff; color: #4f46e5; }

    /* Money Display */
    .money {
        font-weight: 700;
        font-family: 'Courier New', monospace;
    }

    .money.total { color: #1f2937; }
    .money.avance { color: #10b981; }
    .money.reste { color: var(--danger); }

    /* Client Info */
    .client-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .client-info .name {
        font-weight: 600;
        color: #1f2937;
    }

    .client-info .phone {
        font-size: 12px;
        color: #6b7280;
    }

    /* Device Info */
    .device-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .device-info .type {
        font-weight: 600;
        color: #1f2937;
    }

    .device-info .brand {
        font-size: 12px;
        color: #6b7280;
    }

    /* Action Buttons */
    .action-btns {
        display: flex;
        gap: 8px;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
    }

    .action-btn.view {
        background: #dbeafe;
        color: #2563eb;
    }

    .action-btn.edit {
        background: #d1fae5;
        color: #059669;
    }

    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btn.pdf {
        background: linear-gradient(135deg, #C2185B20, #D32F2F20);
        color: var(--primary);
    }

    .action-btn:hover {
        transform: scale(1.1);
    }

    /* Overdue Row */
    .custom-table tbody tr.overdue {
        background: #fef2f2;
        border-left: 4px solid var(--danger);
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 2px solid #f3f4f6;
        flex-wrap: wrap;
        gap: 15px;
    }

    .pagination-info {
        color: #6b7280;
        font-size: 14px;
    }

    .pagination {
        display: flex;
        gap: 5px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pagination .page-item .page-link {
        padding: 8px 14px;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .pagination .page-item.active .page-link {
        background: var(--gradient);
        border-color: transparent;
        color: white;
    }

    .pagination .page-item .page-link:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    /* Charts Section */
    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }

    .chart-card h6 {
        margin: 0 0 20px 0;
        color: #1f2937;
        font-weight: 600;
    }

    /* Quick Filters */
    .quick-filters {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .quick-filter {
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 500;
        border: 2px solid #e5e7eb;
        background: white;
        color: #6b7280;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .quick-filter:hover,
    .quick-filter.active {
        border-color: var(--primary);
        background: linear-gradient(135deg, #fdf2f8, #fff1f2);
        color: var(--primary);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 60px;
        color: #d1d5db;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        color: #6b7280;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #9ca3af;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .filter-grid {
            grid-template-columns: 1fr;
        }
        
        .table-header {
            flex-direction: column;
            text-align: center;
        }
        
        .custom-table {
            display: block;
            overflow-x: auto;
        }
    }
</style>

<!-- Alerts Section -->
@if(isset($alerts) && count($alerts) > 0)
<div class="alerts-section">
    @foreach($alerts as $alert)
    <div class="alert-custom {{ $alert['type'] }}">
        <i class="fas {{ $alert['type'] === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle' }}"></i>
        <span>{{ $alert['message'] }}</span>
    </div>
    @endforeach
</div>
@endif

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="icon primary"><i class="fas fa-ticket-alt"></i></div>
        <h3>{{ $stats['total_tickets'] ?? 0 }}</h3>
        <p>Total Tickets</p>
    </div>
    <div class="stat-card">
        <div class="icon warning"><i class="fas fa-clock"></i></div>
        <h3>{{ $stats['en_attente'] ?? 0 }}</h3>
        <p>En Attente</p>
    </div>
    <div class="stat-card">
        <div class="icon info"><i class="fas fa-tools"></i></div>
        <h3>{{ $stats['en_cours'] ?? 0 }}</h3>
        <p>En Cours</p>
    </div>
    <div class="stat-card">
        <div class="icon success"><i class="fas fa-check-circle"></i></div>
        <h3>{{ $stats['termine'] ?? 0 }}</h3>
        <p>Terminés</p>
    </div>
    <div class="stat-card">
        <div class="icon primary"><i class="fas fa-money-bill-wave"></i></div>
        <h3>{{ number_format($stats['total_montant'] ?? 0, 2) }} DH</h3>
        <p>Chiffre d'Affaires</p>
    </div>
    <div class="stat-card">
        <div class="icon success"><i class="fas fa-hand-holding-usd"></i></div>
        <h3>{{ number_format($stats['total_avance'] ?? 0, 2) }} DH</h3>
        <p>Total Avances</p>
    </div>
    <div class="stat-card">
        <div class="icon warning"><i class="fas fa-exclamation-circle"></i></div>
        <h3>{{ number_format($stats['total_reste'] ?? 0, 2) }} DH</h3>
        <p>Reste à Payer</p>
    </div>
    <div class="stat-card">
        <div class="icon info"><i class="fas fa-calendar-day"></i></div>
        <h3>{{ $stats['tickets_today'] ?? 0 }}</h3>
        <p>Tickets Aujourd'hui</p>
    </div>
</div>

<!-- Quick Filters -->
<div class="quick-filters">
    <a href="{{ route('repair-tickets.index') }}" class="quick-filter {{ !request()->hasAny(['status', 'unpaid_only', 'overdue_only']) ? 'active' : '' }}">
        <i class="fas fa-layer-group"></i> Tous
    </a>
    <a href="{{ route('repair-tickets.index', ['status' => 'en_attente']) }}" class="quick-filter {{ request('status') === 'en_attente' ? 'active' : '' }}">
        <i class="fas fa-clock"></i> En Attente
    </a>
    <a href="{{ route('repair-tickets.index', ['status' => 'en_cours']) }}" class="quick-filter {{ request('status') === 'en_cours' ? 'active' : '' }}">
        <i class="fas fa-tools"></i> En Cours
    </a>
    <a href="{{ route('repair-tickets.index', ['status' => 'termine']) }}" class="quick-filter {{ request('status') === 'termine' ? 'active' : '' }}">
        <i class="fas fa-check"></i> Terminés
    </a>
    <a href="{{ route('repair-tickets.index', ['unpaid_only' => 1]) }}" class="quick-filter {{ request('unpaid_only') ? 'active' : '' }}">
        <i class="fas fa-exclamation-triangle"></i> Non Payés
    </a>
    <a href="{{ route('repair-tickets.index', ['overdue_only' => 1]) }}" class="quick-filter {{ request('overdue_only') ? 'active' : '' }}">
        <i class="fas fa-calendar-times"></i> En Retard
    </a>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <div class="filter-header">
        <h5><i class="fas fa-filter"></i> Filtres Avancés</h5>
        <a href="{{ route('repair-tickets.index') }}" class="btn-custom outline" style="background: var(--gradient); color: white; border: none;">
            <i class="fas fa-redo"></i> Réinitialiser
        </a>
    </div>
    <form method="GET" action="{{ route('repair-tickets.index') }}">
        <div class="filter-grid">
            <div class="filter-group">
                <label>Recherche</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, téléphone, ID...">
            </div>
            <div class="filter-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Tous les status</option>
                    @foreach($filterData['statuses'] as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Type d'appareil</label>
                <select name="device_type">
                    <option value="">Tous les types</option>
                    @foreach($filterData['device_types'] as $type)
                    <option value="{{ $type }}" {{ request('device_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Marque</label>
                <select name="device_brand">
                    <option value="">Toutes les marques</option>
                    @foreach($filterData['device_brands'] as $brand)
                    <option value="{{ $brand }}" {{ request('device_brand') === $brand ? 'selected' : '' }}>{{ $brand }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-group">
                <label>Date début</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="filter-group">
                <label>Date fin</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="filter-group">
                <label>Montant Min</label>
                <input type="number" name="montant_min" value="{{ request('montant_min') }}" placeholder="0.00">
            </div>
            <div class="filter-group">
                <label>Montant Max</label>
                <input type="number" name="montant_max" value="{{ request('montant_max') }}" placeholder="0.00">
            </div>
        </div>
        <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
            <button type="submit" class="btn-custom primary" style="background: var(--gradient); color: white;">
                <i class="fas fa-search"></i> Appliquer les filtres
            </button>
        </div>
    </form>
</div>

<!-- Table Section -->
<div class="table-section">
    <div class="table-header">
        <h5><i class="fas fa-list"></i> Liste des Tickets ({{ $tickets->total() }})</h5>
        <div class="header-actions">
            <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['export' => 'csv'])) }}" class="btn-custom outline">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['export' => 'pdf'])) }}" class="btn-custom outline">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('repair-tickets.create') }}" class="btn-custom primary">
                <i class="fas fa-plus"></i> Nouveau Ticket
            </a>
        </div>
    </div>

    @if($tickets->count() > 0)
    <table class="custom-table">
        <thead>
            <tr>
                <th>
                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        # <i class="fas fa-sort"></i>
                    </a>
                </th>
                <th>
                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['sort' => 'nom_complet', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Client <i class="fas fa-sort"></i>
                    </a>
                </th>
                <th>Appareil</th>
                <th>
                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['sort' => 'date_depot', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Date Dépôt <i class="fas fa-sort"></i>
                    </a>
                </th>
                <th>Échéance</th>
                <th>Montant</th>
                <th>Avance</th>
                <th>Reste</th>
                <th>
                    <a href="{{ route('repair-tickets.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}">
                        Status <i class="fas fa-sort"></i>
                    </a>
                </th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            @php
                $isOverdue = $ticket->estimated_completion && 
                             \Carbon\Carbon::parse($ticket->estimated_completion)->isPast() && 
                             !in_array($ticket->status, ['termine', 'livre']);
                $reste = $ticket->montant_total - $ticket->avance;
            @endphp
            <tr class="{{ $isOverdue ? 'overdue' : '' }}">
                <td><strong>#{{ $ticket->id }}</strong></td>
                <td>
                    <div class="client-info">
                        <span class="name">{{ $ticket->nom_complet }}</span>
                        <span class="phone"><i class="fas fa-phone"></i> {{ $ticket->phone ?? 'N/A' }}</span>
                    </div>
                </td>
                <td>
                    <div class="device-info">
                        <span class="type">{{ $ticket->device_type }}</span>
                        <span class="brand">{{ $ticket->device_brand ?? 'N/A' }}</span>
                    </div>
                </td>
                <td>{{ \Carbon\Carbon::parse($ticket->date_depot)->format('d/m/Y') }}</td>
                <td>
                    @if($ticket->estimated_completion)
                        <span class="{{ $isOverdue ? 'text-danger fw-bold' : '' }}">
                            {{ \Carbon\Carbon::parse($ticket->estimated_completion)->format('d/m/Y') }}
                            @if($isOverdue)
                                <i class="fas fa-exclamation-circle text-danger"></i>
                            @endif
                        </span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td><span class="money total">{{ number_format($ticket->montant_total, 2) }} DH</span></td>
                <td><span class="money avance">{{ number_format($ticket->avance, 2) }} DH</span></td>
                <td><span class="money reste">{{ number_format($reste, 2) }} DH</span></td>
                <td><span class="status-badge {{ $ticket->status }}">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span></td>
                <td>
                    <div class="action-btns">
                        <a href="{{ route('repair-tickets.show', $ticket) }}" class="action-btn view" title="Voir">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('repair-tickets.edit', $ticket) }}" class="action-btn edit" title="Modifier">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="{{ route('repair-tickets.pdf', $ticket) }}" class="action-btn pdf" title="PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <form action="{{ route('repair-tickets.destroy', $ticket) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete" title="Supprimer">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Affichage de {{ $tickets->firstItem() }} à {{ $tickets->lastItem() }} sur {{ $tickets->total() }} résultats
        </div>
        {{ $tickets->links() }}
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <h5>Aucun ticket trouvé</h5>
        <p>Essayez de modifier vos filtres ou créez un nouveau ticket.</p>
        <a href="{{ route('repair-tickets.create') }}" class="btn-custom primary" style="background: var(--gradient); color: white; margin-top: 15px;">
            <i class="fas fa-plus"></i> Créer un ticket
        </a>
    </div>
    @endif
</div>

<!-- Charts Section -->
@if(isset($chartData))
<div class="charts-grid" style="margin-top: 25px;">
    <!-- Top Appareils -->
    <div class="chart-card">
        <h6><i class="fas fa-mobile-alt" style="color: var(--primary);"></i> Top Appareils</h6>
        @if(isset($stats['top_devices']) && $stats['top_devices']->count() > 0)
        <div class="top-list">
            @foreach($stats['top_devices'] as $device)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="font-weight: 500; color: #374151;">{{ $device->device_type }}</span>
                <span style="background: linear-gradient(135deg, #C2185B20, #D32F2F20); color: var(--primary); padding: 4px 12px; border-radius: 50px; font-weight: 600; font-size: 13px;">
                    {{ $device->count }} tickets
                </span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">Aucune donnée disponible</p>
        @endif
    </div>

    <!-- Top Marques -->
    <div class="chart-card">
        <h6><i class="fas fa-tags" style="color: var(--primary);"></i> Top Marques</h6>
        @if(isset($stats['top_brands']) && $stats['top_brands']->count() > 0)
        <div class="top-list">
            @foreach($stats['top_brands'] as $brand)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid #f3f4f6;">
                <span style="font-weight: 500; color: #374151;">{{ $brand->device_brand }}</span>
                <span style="background: #dbeafe; color: #2563eb; padding: 4px 12px; border-radius: 50px; font-weight: 600; font-size: 13px;">
                    {{ $brand->count }} tickets
                </span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">Aucune donnée disponible</p>
        @endif
    </div>

    <!-- Distribution par Status -->
    <div class="chart-card">
        <h6><i class="fas fa-chart-pie" style="color: var(--primary);"></i> Distribution par Status</h6>
        @if(isset($chartData['status_distribution']) && $chartData['status_distribution']->count() > 0)
        <div class="status-distribution">
            @php
                $colors = [
                    'en_attente' => ['bg' => '#fef3c7', 'color' => '#d97706'],
                    'en_cours' => ['bg' => '#dbeafe', 'color' => '#2563eb'],
                    'termine' => ['bg' => '#d1fae5', 'color' => '#059669'],
                    'livre' => ['bg' => '#e0e7ff', 'color' => '#4f46e5'],
                ];
                $total = $chartData['status_distribution']->sum('count');
            @endphp
            @foreach($chartData['status_distribution'] as $item)
            @php
                $percentage = $total > 0 ? round(($item->count / $total) * 100) : 0;
                $color = $colors[$item->status] ?? ['bg' => '#f3f4f6', 'color' => '#6b7280'];
            @endphp
            <div style="margin-bottom: 15px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <span style="font-weight: 500; color: #374151;">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                    <span style="color: #6b7280; font-size: 13px;">{{ $item->count }} ({{ $percentage }}%)</span>
                </div>
                <div style="height: 8px; background: #f3f4f6; border-radius: 50px; overflow: hidden;">
                    <div style="height: 100%; width: {{ $percentage }}%; background: {{ $color['color'] }}; border-radius: 50px; transition: width 0.5s ease;"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-muted">Aucune donnée disponible</p>
        @endif
    </div>

    <!-- Résumé Financier -->
    <div class="chart-card">
        <h6><i class="fas fa-coins" style="color: var(--primary);"></i> Résumé Financier</h6>
        <div class="financial-summary">
            <div style="display: flex; justify-content: space-between; padding: 15px; background: linear-gradient(135deg, #d1fae520, #d1fae540); border-radius: 12px; margin-bottom: 10px;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">Revenue du Jour</p>
                    <p style="margin: 5px 0 0 0; font-size: 22px; font-weight: 700; color: #059669;">{{ number_format($stats['revenue_today'] ?? 0, 2) }} DH</p>
                </div>
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-arrow-up" style="color: #059669; font-size: 24px;"></i>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 15px; background: linear-gradient(135deg, #dbeafe20, #dbeafe40); border-radius: 12px; margin-bottom: 10px;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">Revenue du Mois</p>
                    <p style="margin: 5px 0 0 0; font-size: 22px; font-weight: 700; color: #2563eb;">{{ number_format($stats['revenue_month'] ?? 0, 2) }} DH</p>
                </div>
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-calendar-alt" style="color: #2563eb; font-size: 24px;"></i>
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 15px; background: linear-gradient(135deg, #fee2e220, #fee2e240); border-radius: 12px;">
                <div>
                    <p style="margin: 0; font-size: 13px; color: #6b7280;">Impayés Total</p>
                    <p style="margin: 5px 0 0 0; font-size: 22px; font-weight: 700; color: #dc2626;">{{ number_format($stats['total_reste'] ?? 0, 2) }} DH</p>
                </div>
                <div style="display: flex; align-items: center;">
                    <i class="fas fa-exclamation-triangle" style="color: #dc2626; font-size: 24px;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Success Message Toast -->
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Succès!',
            text: '{{ session('success') }}',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#10b981',
            color: '#fff',
            iconColor: '#fff'
        });
    });
</script>
@endif

<!-- Delete Confirmation -->
<script>
    function confirmDelete(event) {
        event.preventDefault();
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D32F2F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler',
            borderRadius: '16px'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
        return false;
    }
</script>

</x-app-layout>