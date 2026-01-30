<x-app-layout>
    <style>
        /* ===== VARIABLES COULEURS ===== */
        :root {
            --primary-gradient: linear-gradient(135deg, #C2185B, #D32F2F);
            --primary-color: #D32F2F;
            --secondary-color: #C2185B;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f8f9fa;
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* ===== MODAL BACKDROP - VERSION CORRIGÉE ===== */
.modal-backdrop-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 1050;
    display: none; /* Caché par défaut */
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal-backdrop-custom.show {
    display: block; /* Afficher quand show */
    opacity: 1;
}

/* ===== MODAL CONTAINER - VERSION CORRIGÉE ===== */
.modal-custom {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1055;
    display: none; /* Caché par défaut */
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal-custom.show {
    display: flex !important; /* Force display */
    opacity: 1;
}

        .modal-dialog-custom {
            background: white;
            border-radius: 24px;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
        }

        /* ===== MODAL HEADER ===== */
        .modal-header-custom {
            background: var(--primary-gradient);
            color: white;
            padding: 28px 32px;
            border-bottom: none;
            position: relative;
        }

        .modal-header-custom h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .modal-close-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 20px;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(90deg);
        }

        /* ===== MODAL BODY ===== */
        .modal-body-custom {
            padding: 32px;
        }

        .filter-group {
            margin-bottom: 28px;
        }

        .filter-group:last-child {
            margin-bottom: 0;
        }

        .filter-label {
            font-weight: 700;
            color: #374151;
            font-size: 0.9375rem;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-label i {
            color: var(--primary-color);
            font-size: 1.125rem;
        }

        .filter-select,
        .filter-input {
            width: 100%;
            border: 2.5px solid #e5e7eb;
            border-radius: 14px;
            padding: 14px 18px;
            font-size: 0.9375rem;
            font-weight: 500;
            color: #1f2937;
            transition: all 0.3s;
            background: white;
        }

        .filter-select:focus,
        .filter-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 5px rgba(211, 47, 47, 0.1);
            outline: none;
        }

        .filter-select:hover,
        .filter-input:hover {
            border-color: #cbd5e1;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* ===== MODAL FOOTER ===== */
        .modal-footer-custom {
            padding: 24px 32px;
            background: #f9fafb;
            border-top: 2px solid #f3f4f6;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-modal-primary {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9375rem;
            transition: all 0.3s;
            box-shadow: 0 4px 14px rgba(211, 47, 47, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .btn-modal-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.45);
        }

        .btn-modal-secondary {
            background: white;
            color: #6b7280;
            border: 2.5px solid #e5e7eb;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9375rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
        }

        .btn-modal-secondary:hover {
            background: #f9fafb;
            border-color: #cbd5e1;
        }

        /* ===== ACTIVE FILTERS BADGE ===== */
        .active-filters-info {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.08), rgba(211, 47, 47, 0.08));
            border: 2px solid rgba(211, 47, 47, 0.2);
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
            animation: slideInUp 0.5s ease-out;
        }

        .filter-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: white;
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 6px;
            border: 2px solid rgba(211, 47, 47, 0.2);
            transition: all 0.3s;
        }

        .filter-badge:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .filter-badge i {
            font-size: 0.75rem;
        }

        /* ===== PAGE HEADER ===== */
        .dashboard-header {
            margin-bottom: 32px;
            animation: slideInUp 0.5s ease-out;
        }

        .dashboard-header h1 {
            font-size: 2.25rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
            display: inline-flex;
            align-items: center;
            gap: 16px;
        }

        .dashboard-header h1 i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .dashboard-header p {
            color: #6b7280;
            font-size: 1rem;
            margin: 0;
        }

        .dashboard-header p i {
            color: var(--primary-color);
        }

        /* ===== BUTTONS PREMIUM ===== */
        .btn-gradient {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 14px rgba(211, 47, 47, 0.35);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-gradient:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(211, 47, 47, 0.5);
            color: white;
        }

        .btn-outline-gradient {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9375rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-outline-gradient:hover {
            background: var(--primary-gradient);
            color: white;
            border-color: transparent;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.3);
        }

        /* ===== STAT CARDS (KPIs) ===== */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(211, 47, 47, 0.08);
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 32px rgba(211, 47, 47, 0.2);
            border-color: rgba(211, 47, 47, 0.25);
        }

        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.12), rgba(211, 47, 47, 0.12));
            color: var(--primary-color);
            transition: all 0.3s;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 16px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .stat-change {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.875rem;
            font-weight: 700;
        }

        .stat-change.positive {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.25));
            color: #059669;
        }

        .stat-change.negative {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.25));
            color: #dc2626;
        }

        .stat-card:nth-child(1) { animation: slideInUp 0.5s ease-out 0.1s both; }
        .stat-card:nth-child(2) { animation: slideInUp 0.5s ease-out 0.2s both; }
        .stat-card:nth-child(3) { animation: slideInUp 0.5s ease-out 0.3s both; }
        .stat-card:nth-child(4) { animation: slideInUp 0.5s ease-out 0.4s both; }

        /* ===== ALERTES PREMIUM ===== */
        .alert-card {
            border-left: 5px solid;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 18px;
            display: flex;
            gap: 20px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            animation: slideInUp 0.5s ease-out;
        }

        .alert-card:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .alert-card.danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.08), rgba(239, 68, 68, 0.15));
            border-color: #ef4444;
        }

        .alert-card.warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.08), rgba(245, 158, 11, 0.15));
            border-color: #f59e0b;
        }

        .alert-card.success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(16, 185, 129, 0.15));
            border-color: #10b981;
        }

        .alert-card.info {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.08), rgba(59, 130, 246, 0.15));
            border-color: #3b82f6;
        }

        .alert-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .alert-card.danger .alert-icon {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.2), rgba(239, 68, 68, 0.3));
            color: #dc2626;
        }

        .alert-card.warning .alert-icon {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(245, 158, 11, 0.3));
            color: #d97706;
        }

        .alert-card.success .alert-icon {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2), rgba(16, 185, 129, 0.3));
            color: #059669;
        }

        .alert-card.info .alert-icon {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(59, 130, 246, 0.3));
            color: #2563eb;
        }

        /* ===== CHART CONTAINERS ===== */
        .chart-container {
            background: white;
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            margin-bottom: 32px;
            border: 2px solid rgba(211, 47, 47, 0.08);
            animation: slideInUp 0.6s ease-out;
            transition: all 0.3s;
        }

        .chart-container:hover {
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.1);
            border-color: rgba(211, 47, 47, 0.15);
        }

        .chart-title {
            font-size: 1.375rem;
            font-weight: 700;
            margin-bottom: 28px;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 14px;
            padding-bottom: 16px;
            border-bottom: 3px solid rgba(211, 47, 47, 0.1);
        }

        .chart-title i {
            font-size: 1.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* ===== SECTION TITLES ===== */
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 24px;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.625rem;
        }

        /* ===== TABLES PREMIUM ===== */
        .table-modern {
            width: 100%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            border: 2px solid rgba(211, 47, 47, 0.08);
        }

        .table-modern thead {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
        }

        .table-modern th {
            padding: 20px 18px;
            text-align: left;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 3px solid rgba(211, 47, 47, 0.25);
        }

        .table-modern td {
            padding: 18px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .table-modern tbody tr {
            transition: all 0.3s;
        }

        .table-modern tbody tr:hover {
            background: linear-gradient(90deg, rgba(211, 47, 47, 0.05), rgba(194, 24, 91, 0.05));
            transform: scale(1.01);
        }

        .table-modern tbody tr:last-child td {
            border-bottom: none;
        }

        /* ===== BADGES ===== */
        .badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.8125rem;
            font-weight: 700;
            line-height: 1;
        }

        .badge-gradient {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .badge-success {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.25));
            color: #059669;
        }

        .badge-danger {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.25));
            color: #dc2626;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .stat-value {
                font-size: 1.875rem;
            }

            .chart-container {
                padding: 24px;
            }

            .dashboard-header h1 {
                font-size: 1.75rem;
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                font-size: 26px;
            }

            .modal-dialog-custom {
                margin: 10px;
                max-width: calc(100% - 20px);
            }

            .filter-row {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            .modal-custom,
            .modal-backdrop-custom,
            .btn-gradient,
            .btn-outline-gradient {
                display: none !important;
            }
        }

        /* ===== SCROLLBAR CUSTOM ===== */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #a0154b, #b71c1c);
        }
    </style>

    <div class="container-fluid px-4 py-4">
        
        {{-- Header --}}
        <div class="dashboard-header d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h1>
                    <i class="fas fa-chart-line"></i>
                    Dashboard Bénéfice & Marge
                </h1>
                <p>
                    <i class="fas fa-calendar-alt me-2"></i>
                    Période: <strong>{{ $from->format('d/m/Y') }}</strong> - <strong>{{ $to->format('d/m/Y') }}</strong>
                </p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button class="btn btn-gradient" onclick="openFilterModal()">
                    <i class="fas fa-filter"></i> Filtres
                </button>
                <a href="{{ route('benefice-marge.export.csv', request()->all()) }}" class="btn btn-outline-gradient">
                    <i class="fas fa-download"></i> CSV
                </a>
                <button class="btn btn-outline-gradient" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>

        {{-- Active Filters Info --}}
        @php
            $hasActiveFilters = request('periode') != 'ce_mois' || request('date_debut') || request('date_fin') || request('comparaison') != 'mois_precedent';
            
            $filterLabels = [
                'periode' => [
                    'aujourdhui' => "Aujourd'hui",
                    'cette_semaine' => 'Cette semaine',
                    'ce_mois' => 'Ce mois',
                    'ce_trimestre' => 'Ce trimestre',
                    'cette_annee' => 'Cette année',
                    '12_mois' => '12 derniers mois',
                    'personnalise' => 'Période personnalisée'
                ],
                'comparaison' => [
                    'mois_precedent' => 'Mois précédent',
                    'annee_precedente' => 'Année précédente',
                    'aucune' => 'Aucune comparaison'
                ]
            ];
        @endphp

        @if($hasActiveFilters)
        <div class="active-filters-info">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <strong style="color: var(--primary-color);">
                        <i class="fas fa-filter me-2"></i>Filtres actifs:
                    </strong>
                    
                    @if(request('periode') && request('periode') != 'ce_mois')
                        <span class="filter-badge">
                            <i class="fas fa-clock"></i>
                            {{ $filterLabels['periode'][request('periode')] ?? request('periode') }}
                        </span>
                    @endif

                    @if(request('date_debut'))
                        <span class="filter-badge">
                            <i class="fas fa-calendar-day"></i>
                            Du {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}
                        </span>
                    @endif

                    @if(request('date_fin'))
                        <span class="filter-badge">
                            <i class="fas fa-calendar-day"></i>
                            Au {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
                        </span>
                    @endif

                    @if(request('comparaison') && request('comparaison') != 'mois_precedent')
                        <span class="filter-badge">
                            <i class="fas fa-exchange-alt"></i>
                            {{ $filterLabels['comparaison'][request('comparaison')] ?? request('comparaison') }}
                        </span>
                    @endif
                </div>

                <a href="{{ route('benefice-marge.dashboard') }}" class="btn-outline-gradient" style="padding: 8px 20px; font-size: 0.875rem;">
                    <i class="fas fa-times-circle"></i>
                    Réinitialiser
                </a>
            </div>
        </div>
        @endif

        {{-- KPIs Principaux --}}
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-label">Revenus Total</div>
                    <div class="stat-value">{{ number_format($kpis['revenus_total'], 0, ',', ' ') }} <small style="font-size: 1.25rem;">DH</small></div>
                    @if($comparaisonData)
                        <span class="stat-change {{ $comparaisonData['variations']['revenus'] >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-{{ $comparaisonData['variations']['revenus'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($comparaisonData['variations']['revenus']) }}%
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-label">Charges Total</div>
                    <div class="stat-value" style="background: linear-gradient(135deg, #ef4444, #dc2626); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ number_format($kpis['charges_total'], 0, ',', ' ') }} <small style="font-size: 1.25rem;">DH</small>
                    </div>
                    @if($comparaisonData)
                        <span class="stat-change {{ $comparaisonData['variations']['charges'] <= 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-{{ $comparaisonData['variations']['charges'] <= 0 ? 'arrow-down' : 'arrow-up' }}"></i>
                            {{ abs($comparaisonData['variations']['charges']) }}%
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-label">Marge Nette</div>
                    <div class="stat-value" style="background: linear-gradient(135deg, {{ $kpis['marge_nette'] >= 0 ? '#10b981, #059669' : '#ef4444, #dc2626' }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ number_format($kpis['marge_nette'], 0, ',', ' ') }} <small style="font-size: 1.25rem;">DH</small>
                    </div>
                    @if($comparaisonData)
                        <span class="stat-change {{ $comparaisonData['variations']['marge'] >= 0 ? 'positive' : 'negative' }}">
                            <i class="fas fa-{{ $comparaisonData['variations']['marge'] >= 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                            {{ abs($comparaisonData['variations']['marge']) }}%
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-percentage"></i>
                    </div>
                    <div class="stat-label">Taux de Marge</div>
                    <div class="stat-value" style="background: linear-gradient(135deg, {{ $kpis['taux_marge'] >= 20 ? '#10b981, #059669' : ($kpis['taux_marge'] >= 10 ? '#f59e0b, #d97706' : '#ef4444, #dc2626') }}); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ number_format($kpis['taux_marge'], 1) }}<small style="font-size: 1.25rem;">%</small>
                    </div>
                    <small class="text-muted d-flex align-items-center gap-1">
                        <i class="fas fa-shopping-cart"></i>
                        {{ $kpis['nombre_transactions'] }} transactions
                    </small>
                </div>
            </div>
        </div>

        {{-- Alertes --}}
        @if($alertes->count() > 0)
        <div class="mb-5">
            <h5 class="section-title">
                <i class="fas fa-bell"></i>
                Alertes & Recommandations
            </h5>
            @foreach($alertes as $alerte)
            <div class="alert-card {{ $alerte['type'] }}">
                <div class="alert-icon">
                    <i class="fas fa-{{ $alerte['type'] == 'danger' ? 'exclamation-triangle' : ($alerte['type'] == 'warning' ? 'exclamation-circle' : ($alerte['type'] == 'success' ? 'check-circle' : 'info-circle')) }}"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-2">{{ $alerte['titre'] }}</h6>
                    <p class="mb-2">{{ $alerte['message'] }}</p>
                    <small class="text-muted">
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Action:</strong> {{ $alerte['action'] }}
                    </small>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Charts Row 1 --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="chart-container">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-area"></i>
                        Évolution Revenus, Charges & Marge (12 mois)
                    </h5>
                    <canvas id="evolutionChart" height="80"></canvas>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Répartition Revenus
                    </h5>
                    <canvas id="revenusChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- Charts Row 2 --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="chart-container">
                    <h5 class="chart-title">
                        <i class="fas fa-layer-group"></i>
                        Évolution par Source de Revenus
                    </h5>
                    <canvas id="sourcesChart" height="80"></canvas>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="chart-container">
                    <h5 class="chart-title">
                        <i class="fas fa-chart-pie"></i>
                        Répartition Charges
                    </h5>
                    <canvas id="chargesChart" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- Tables --}}
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="chart-container">
                    <h5 class="chart-title">
                        <i class="fas fa-trophy"></i>
                        Top 10 Sources de Revenus
                    </h5>
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Source</th>
                                <th>Type</th>
                                <th class="text-end">Montant (DH)</th>
                                <th class="text-center">Nb</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topRevenus as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item['libelle'] }}</td>
                                <td>
                                    <span class="badge-custom badge-gradient">
                                        {{ $item['type'] }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold" style="color: #10b981;">
                                    {{ number_format($item['montant_dh'], 2, ',', ' ') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge-custom" style="background: rgba(194, 24, 91, 0.1); color: var(--primary-color);">
                                        {{ $item['nb'] }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                    <strong>Aucune donnée disponible</strong>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="chart-container">
                    <h5 class="chart-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Top 10 Dépenses
                    </h5>
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Libellé</th>
                                <th>Type</th>
                                <th class="text-end">Montant (DH)</th>
                                <th class="text-center">Nb</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topDepenses as $item)
                            <tr>
                                <td class="fw-semibold">{{ $item['libelle'] }}</td>
                                <td>
                                    <span class="badge-custom badge-danger">
                                        {{ $item['type'] }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold" style="color: #ef4444;">
                                    {{ number_format($item['montant'], 2, ',', ' ') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge-custom" style="background: rgba(239, 68, 68, 0.1); color: #dc2626;">
                                        {{ $item['nb'] ?? $item['nb_mois'] ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                    <strong>Aucune donnée disponible</strong>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Analyse Mensuelle --}}
        <div class="chart-container">
            <h5 class="chart-title">
                <i class="fas fa-calendar-check"></i>
                Analyse Mensuelle Détaillée
            </h5>
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th class="text-end">Revenus (DH)</th>
                            <th class="text-end">Charges (DH)</th>
                            <th class="text-end">Marge Nette (DH)</th>
                            <th class="text-center">Taux Marge</th>
                            <th class="text-center">Transactions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($analyseMensuelle as $mois)
                        <tr>
                            <td class="fw-semibold">
                                <i class="fas fa-calendar-alt me-2" style="color: var(--primary-color);"></i>
                                {{ $mois['mois'] }}
                            </td>
                            <td class="text-end fw-bold" style="color: var(--primary-color);">
                                {{ number_format($mois['revenus']['total'], 2, ',', ' ') }}
                            </td>
                            <td class="text-end fw-bold" style="color: #ef4444;">
                                {{ number_format($mois['charges']['total'], 2, ',', ' ') }}
                            </td>
                            <td class="text-end fw-bold" style="color: {{ $mois['marge']['nette'] >= 0 ? '#10b981' : '#ef4444' }};">
                                {{ number_format($mois['marge']['nette'], 2, ',', ' ') }}
                            </td>
                            <td class="text-center">
                                <span class="badge-custom" style="background: {{ $mois['marge']['taux'] >= 20 ? 'rgba(16, 185, 129, 0.2)' : ($mois['marge']['taux'] >= 10 ? 'rgba(245, 158, 11, 0.2)' : 'rgba(239, 68, 68, 0.2)') }}; color: {{ $mois['marge']['taux'] >= 20 ? '#059669' : ($mois['marge']['taux'] >= 10 ? '#d97706' : '#dc2626') }};">
                                    {{ number_format($mois['marge']['taux'], 1) }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-custom" style="background: rgba(194, 24, 91, 0.1); color: var(--primary-color);">
                                    {{ $mois['revenus']['nb_transactions'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-inbox fa-3x mb-3 d-block" style="opacity: 0.3;"></i>
                                <strong>Aucune donnée disponible</strong>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Budget vs Réalisé --}}
        @if($budgetAnalyse->count() > 0)
        <div class="chart-container mt-4">
            <h5 class="chart-title">
                <i class="fas fa-bullseye"></i>
                Budget vs Réalisé
            </h5>
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th class="text-end">Budget Total</th>
                            <th class="text-end">Réalisé Total</th>
                            <th class="text-end">Écart</th>
                            <th class="text-center">Taux Exec.</th>
                            <th class="text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($budgetAnalyse as $budget)
                        <tr>
                            <td class="fw-semibold">
                                <i class="fas fa-calendar-alt me-2" style="color: var(--primary-color);"></i>
                                {{ $budget['mois'] }}
                            </td>
                            <td class="text-end">{{ number_format($budget['budget']['total'], 2, ',', ' ') }} DH</td>
                            <td class="text-end fw-bold">{{ number_format($budget['realise']['total'], 2, ',', ' ') }} DH</td>
                            <td class="text-end fw-bold" style="color: {{ $budget['ecart']['total'] > 0 ? '#ef4444' : '#10b981' }};">
                                {{ $budget['ecart']['total'] > 0 ? '+' : '' }}{{ number_format($budget['ecart']['total'], 2, ',', ' ') }} DH
                            </td>
                            <td class="text-center">
                                <span class="badge-custom" style="background: {{ $budget['taux_execution'] <= 100 ? 'rgba(16, 185, 129, 0.2)' : 'rgba(239, 68, 68, 0.2)' }}; color: {{ $budget['taux_execution'] <= 100 ? '#059669' : '#dc2626' }};">
                                    {{ number_format($budget['taux_execution'], 1) }}%
                                </span>
                            </td>
                            <td class="text-center">
                                @if($budget['depasse'])
                                    <span class="badge-custom badge-danger">
                                        <i class="fas fa-times-circle"></i> Dépassé
                                    </span>
                                @else
                                    <span class="badge-custom badge-success">
                                        <i class="fas fa-check-circle"></i> OK
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    {{-- MODAL FILTRES - VERSION CORRIGÉE --}}
<div id="filterBackdrop" class="modal-backdrop-custom"></div>
<div id="filterModal" class="modal-custom">
    <div class="modal-dialog-custom">
        <form method="GET" action="{{ route('benefice-marge.dashboard') }}" id="filterForm">
            {{-- Header --}}
            <div class="modal-header-custom">
                <h4>
                    <i class="fas fa-sliders-h"></i>
                    Filtres de Recherche
                </h4>
                <button type="button" class="modal-close-btn" onclick="closeFilterModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body-custom">
                {{-- Période --}}
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-clock"></i>
                        Période
                    </label>
                    <select name="periode" id="periodeSelect" class="filter-select">
                        <option value="aujourdhui" {{ request('periode') == 'aujourdhui' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="cette_semaine" {{ request('periode') == 'cette_semaine' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="ce_mois" {{ request('periode', 'ce_mois') == 'ce_mois' ? 'selected' : '' }}>Ce mois</option>
                        <option value="ce_trimestre" {{ request('periode') == 'ce_trimestre' ? 'selected' : '' }}>Ce trimestre</option>
                        <option value="cette_annee" {{ request('periode') == 'cette_annee' ? 'selected' : '' }}>Cette année</option>
                        <option value="12_mois" {{ request('periode') == '12_mois' ? 'selected' : '' }}>12 derniers mois</option>
                        <option value="personnalise" {{ request('periode') == 'personnalise' ? 'selected' : '' }}>Période personnalisée</option>
                    </select>
                </div>

                {{-- Dates personnalisées --}}
                <div id="customDatesGroup" style="display: {{ request('periode') == 'personnalise' ? 'block' : 'none' }};">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-day"></i>
                                Date début
                            </label>
                            <input type="date" name="date_debut" class="filter-input" value="{{ request('date_debut') }}">
                        </div>
                        
                        <div class="filter-group">
                            <label class="filter-label">
                                <i class="fas fa-calendar-day"></i>
                                Date fin
                            </label>
                            <input type="date" name="date_fin" class="filter-input" value="{{ request('date_fin') }}">
                        </div>
                    </div>
                </div>

                {{-- Comparaison --}}
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-exchange-alt"></i>
                        Comparaison
                    </label>
                    <select name="comparaison" class="filter-select">
                        <option value="mois_precedent" {{ request('comparaison', 'mois_precedent') == 'mois_precedent' ? 'selected' : '' }}>Mois précédent</option>
                        <option value="annee_precedente" {{ request('comparaison') == 'annee_precedente' ? 'selected' : '' }}>Année précédente</option>
                        <option value="aucune" {{ request('comparaison') == 'aucune' ? 'selected' : '' }}>Aucune comparaison</option>
                    </select>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-secondary" onclick="resetFilters()">
                    <i class="fas fa-redo"></i>
                    Réinitialiser
                </button>
                <button type="submit" class="btn-modal-primary">
                    <i class="fas fa-check"></i>
                    Appliquer
                </button>
            </div>
        </form>
    </div>
</div>

   @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ===== MODAL FUNCTIONS - VERSION CORRIGÉE =====
    function openFilterModal() {
        console.log('Opening modal...');
        const backdrop = document.getElementById('filterBackdrop');
        const modal = document.getElementById('filterModal');
        
        if (backdrop && modal) {
            backdrop.classList.add('show');
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            console.log('Modal opened successfully');
        } else {
            console.error('Modal elements not found!');
        }
    }

    function closeFilterModal() {
        console.log('Closing modal...');
        const backdrop = document.getElementById('filterBackdrop');
        const modal = document.getElementById('filterModal');
        
        if (backdrop && modal) {
            backdrop.classList.remove('show');
            modal.classList.remove('show');
            document.body.style.overflow = '';
            console.log('Modal closed successfully');
        }
    }

    function toggleCustomDates() {
        const periode = document.getElementById('periodeSelect').value;
        const customDatesGroup = document.getElementById('customDatesGroup');
        if (customDatesGroup) {
            customDatesGroup.style.display = periode === 'personnalise' ? 'block' : 'none';
        }
    }

    function resetFilters() {
        window.location.href = '{{ route("benefice-marge.dashboard") }}';
    }

    // Event listener pour le changement de période
    document.addEventListener('DOMContentLoaded', function() {
        const periodeSelect = document.getElementById('periodeSelect');
        if (periodeSelect) {
            periodeSelect.addEventListener('change', toggleCustomDates);
        }

        // Fermer modal avec ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeFilterModal();
            }
        });

        // Fermer modal en cliquant sur le backdrop
        const backdrop = document.getElementById('filterBackdrop');
        if (backdrop) {
            backdrop.addEventListener('click', closeFilterModal);
        }

        console.log('Modal scripts loaded successfully');
    });

    // ===== CHARTS CONFIG =====
    Chart.defaults.font.family = 'Ubuntu, system-ui, sans-serif';
    Chart.defaults.color = '#6b7280';

    const primaryColor = '#D32F2F';
    const secondaryColor = '#C2185B';

        // Evolution Chart
        const evolutionCtx = document.getElementById('evolutionChart');
        const grad1 = evolutionCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        grad1.addColorStop(0, 'rgba(194, 24, 91, 0.3)');
        grad1.addColorStop(1, 'rgba(211, 47, 47, 0.05)');

        const grad2 = evolutionCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        grad2.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
        grad2.addColorStop(1, 'rgba(239, 68, 68, 0.05)');

        const grad3 = evolutionCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
        grad3.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
        grad3.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

        new Chart(evolutionCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['evolution']['labels']),
                datasets: [
                    {
                        label: 'Revenus',
                        data: @json($chartData['evolution']['revenus']),
                        borderColor: primaryColor,
                        backgroundColor: grad1,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: primaryColor,
                        pointBorderWidth: 3,
                    },
                    {
                        label: 'Charges',
                        data: @json($chartData['evolution']['charges']),
                        borderColor: '#ef4444',
                        backgroundColor: grad2,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#ef4444',
                        pointBorderWidth: 3,
                    },
                    {
                        label: 'Marge Nette',
                        data: @json($chartData['evolution']['marge']),
                        borderColor: '#10b981',
                        backgroundColor: grad3,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 4,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 3,
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            font: { size: 14, weight: '700', family: 'Ubuntu' },
                            usePointStyle: true,
                            pointStyle: 'circle',
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.95)',
                        padding: 18,
                        cornerRadius: 12,
                        titleFont: { size: 15, weight: 'bold' },
                        bodyFont: { size: 14 },
                        bodySpacing: 8,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.dataset.label + ': ' + 
                                       new Intl.NumberFormat('fr-MA').format(context.parsed.y) + ' DH';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.04)' },
                        ticks: {
                            font: { size: 13, weight: '600' },
                            callback: value => new Intl.NumberFormat('fr-MA', {
                                notation: 'compact'
                            }).format(value) + ' DH'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 13, weight: '600' } }
                    }
                }
            }
        });

        // Répartition Revenus
        new Chart(document.getElementById('revenusChart'), {
            type: 'doughnut',
            data: {
                labels: @json($chartData['repartition_revenus']['labels']),
                datasets: [{
                    data: @json($chartData['repartition_revenus']['data']),
                    backgroundColor: [primaryColor, secondaryColor, '#f59e0b', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 15,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 13, weight: '700', family: 'Ubuntu' },
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.95)',
                        padding: 18,
                        cornerRadius: 12,
                        callbacks: {
                            label: context => {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = ((context.parsed / total) * 100).toFixed(1);
                                return ' ' + context.label + ': ' + 
                                       new Intl.NumberFormat('fr-MA').format(context.parsed) + 
                                       ' DH (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Evolution Sources
        new Chart(document.getElementById('sourcesChart'), {
            type: 'bar',
            data: {
                labels: @json($chartData['evolution_sources']['labels']),
                datasets: [
                    {
                        label: 'Factures Formations',
                        data: @json($chartData['evolution_sources']['formations']),
                        backgroundColor: primaryColor,
                    },
                    {
                        label: 'Factures des Services',
                        data: @json($chartData['evolution_sources']['services']),
                        backgroundColor: secondaryColor,
                    },
                    {
                        label: 'Reçus de Stage',
                        data: @json($chartData['evolution_sources']['stages']),
                        backgroundColor: '#f59e0b',
                    },
                    {
                        label: 'Payments des Étudiants (Portail)',
                        data: @json($chartData['evolution_sources']['portail']),
                        backgroundColor: '#8b5cf6',
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            padding: 20,
                            font: { size: 14, weight: '700', family: 'Ubuntu' },
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.95)',
                        padding: 18,
                        cornerRadius: 12,
                    }
                },
                scales: {
                    x: { 
                        stacked: true, 
                        grid: { display: false },
                        ticks: { font: { size: 13, weight: '600' } }
                    },
                    y: { 
                        stacked: true,
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.04)' },
                        ticks: {
                            font: { size: 13, weight: '600' },
                            callback: value => new Intl.NumberFormat('fr-MA', {
                                notation: 'compact'
                            }).format(value)
                        }
                    }
                }
            }
        });

        // Répartition Charges
        new Chart(document.getElementById('chargesChart'), {
            type: 'pie',
            data: {
                labels: @json($chartData['repartition_charges']['labels']),
                datasets: [{
                    data: @json($chartData['repartition_charges']['data']),
                    backgroundColor: @json($chartData['repartition_charges']['colors']),
                    borderWidth: 0,
                    hoverOffset: 15,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            font: { size: 12, weight: '700', family: 'Ubuntu' },
                            usePointStyle: true,
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.95)',
                        padding: 18,
                        cornerRadius: 12,
                        callbacks: {
                            label: context => {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = ((context.parsed / total) * 100).toFixed(1);
                                return ' ' + context.label + ': ' + 
                                       new Intl.NumberFormat('fr-MA').format(context.parsed) + 
                                       ' DH (' + pct + '%)';
                            }
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>