<x-app-layout>
    <style>
        .rapport-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.3);
            position: relative;
            overflow: hidden;
        }

        .rapport-header::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .rapport-header::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .rapport-header h3 {
            color: white;
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
            -webkit-text-fill-color: white;
            position: relative;
            z-index: 1;
        }

        .rapport-header .subtitle {
            opacity: 0.9;
            font-size: 1.1rem;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }

        .periode-selector {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 30px;
        }

        .periode-selector h5 {
            color: #D32F2F;
            font-weight: 700;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stats-mega-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .mega-stat-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .mega-stat-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(211, 47, 47, 0.2);
        }

        .mega-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #C2185B, #D32F2F);
        }

        .mega-stat-card .icon-circle {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .mega-stat-card:hover .icon-circle {
            transform: rotate(360deg) scale(1.1);
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }

        .mega-stat-card:hover .icon-circle i {
            color: white !important;
        }

        .mega-stat-card .icon-circle i {
            font-size: 2rem;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 0.3s ease;
        }

        .mega-stat-card .stat-label {
            color: #666;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .mega-stat-card .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .mega-stat-card .stat-subtext {
            color: #999;
            font-size: 0.85rem;
            margin-top: 8px;
        }

        .chart-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .chart-card h5 {
            color: #D32F2F;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, #C2185B, #D32F2F) 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .payment-modes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .mode-card {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .mode-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mode-card:hover {
            border-color: #C2185B;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(211, 47, 47, 0.2);
        }

        .mode-card:hover::before {
            opacity: 0.05;
        }

        .mode-card .mode-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .mode-especes .mode-icon { color: #4CAF50; }
        .mode-carte .mode-icon { color: #2196F3; }
        .mode-cheque .mode-icon { color: #FF9800; }
        .mode-virement .mode-icon { color: #9C27B0; }

        .mode-card .mode-label {
            font-weight: 600;
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .mode-card .mode-amount {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .mode-card .mode-count {
            color: #999;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .table-rapport {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .table-rapport table {
            margin: 0;
        }

        .table-rapport thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .table-rapport thead th {
            border: none;
            padding: 18px 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-rapport tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-rapport tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            transform: scale(1.01);
        }

        .table-rapport tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        .btn-export {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-export:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.3);
            background: linear-gradient(135deg, #45a049, #4CAF50);
            color: white;
        }

        .btn-print {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(33, 150, 243, 0.3);
            background: linear-gradient(135deg, #1976D2, #2196F3);
            color: white;
        }

        .comparison-card {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            padding: 25px;
            border-radius: 15px;
            border: 2px solid rgba(194, 24, 91, 0.2);
            margin-bottom: 25px;
        }

        .comparison-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .comparison-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 10px;
        }

        .comparison-item .label {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .comparison-item .value {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .progress-custom {
            height: 30px;
            border-radius: 15px;
            background: rgba(0,0,0,0.05);
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
        }

        .progress-custom .progress-bar {
            background: linear-gradient(90deg, #C2185B, #D32F2F);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            transition: width 1s ease;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #999;
        }

        .empty-state i {
            font-size: 5rem;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
        }

        .badge-mode {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #C2185B;
            box-shadow: 0 0 0 0.2rem rgba(194, 24, 91, 0.25);
        }

        @media print {
            .no-print {
                display: none !important;
            }
            
            .rapport-header {
                box-shadow: none;
            }
        }

        @media (max-width: 768px) {
            .rapport-header {
                padding: 25px;
            }

            .rapport-header h3 {
                font-size: 1.8rem;
            }

            .stats-mega-grid {
                grid-template-columns: 1fr;
            }

            .mega-stat-card .stat-value {
                font-size: 2rem;
            }

            .payment-modes-grid {
                grid-template-columns: 1fr;
            }

            .comparison-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Header -->
        <div class="rapport-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h3>
                        <i class="fas fa-chart-pie me-3"></i>
                        Rapport des Paiements
                    </h3>
                    <p class="subtitle mb-0">
                        Analyse détaillée et statistiques complètes
                    </p>
                </div>
                <div class="mt-3 mt-md-0 no-print">
                    <button onclick="window.print()" class="btn btn-print">
                        <i class="fas fa-print"></i>
                        Imprimer
                    </button>
                    <button onclick="exportToExcel()" class="btn btn-export ms-2">
                        <i class="fas fa-file-excel"></i>
                        Exporter
                    </button>
                </div>
            </div>
        </div>

        <!-- Sélecteur de période -->
        <div class="periode-selector no-print">
            <h5>
                <i class="fas fa-calendar-alt"></i>
                Sélectionner la Période
            </h5>
            <form method="GET" action="{{ route('paiements.rapport') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Date de début</label>
                      <input type="date" name="date_debut" class="form-control form-control-lg" 
                                       value="{{ request('date_debut', $dateDebut) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Date de fin</label>
                       <input type="date" name="date_fin" class="form-control" 
                               value="{{ $dateFin instanceof \Carbon\Carbon ? $dateFin->format('Y-m-d') : $dateFin }}" required>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border: none; padding: 12px;">
                            <i class="fas fa-search me-2"></i>
                            Générer le Rapport
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Période affichée -->
        <div class="comparison-card">
            <div class="d-flex align-items-center justify-content-center">
                <h4 class="mb-0" style="background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 700;">
                    <i class="fas fa-calendar-check me-2"></i>
                     Période: {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} 
                            au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                </h4>
            </div>
            <div class="comparison-grid">
                <div class="comparison-item">
                    <div class="label">Jours</div>
                    {{-- <div class="value">{{ $dateDebut->diffInDays($dateFin) + 1 }}</div> --}}
                </div>
                <div class="comparison-item">
                    <div class="label">Semaines</div>
                    {{-- <div class="value">{{ ceil($dateDebut->diffInDays($dateFin) / 7) }}</div> --}}
                </div>
                <div class="comparison-item">
                    <div class="label">Paiements</div>
                    <div class="value">{{ $stats['nombre'] }}</div>
                </div>
            </div>
        </div>

        <!-- Statistiques Principales -->
        <div class="stats-mega-grid">
            <div class="mega-stat-card">
                <div class="icon-circle">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stat-label">Total Encaissé</div>
                <div class="stat-value">{{ number_format($stats['total'], 2) }} DH</div>
                <div class="stat-subtext">
                    Moyenne: {{ number_format($stats['nombre'] > 0 ? $stats['total'] / $stats['nombre'] : 0, 2) }} DH/paiement
                </div>
            </div>

            <div class="mega-stat-card">
                <div class="icon-circle">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-label">Espèces</div>
                <div class="stat-value">{{ number_format($stats['especes'], 2) }} DH</div>
                <div class="stat-subtext">
                    {{ $stats['total'] > 0 ? number_format(($stats['especes'] / $stats['total']) * 100, 1) : 0 }}% du total
                </div>
            </div>

            <div class="mega-stat-card">
                <div class="icon-circle">
                    <i class="fas fa-credit-card"></i>
                </div>
                <div class="stat-label">Cartes Bancaires</div>
                <div class="stat-value">{{ number_format($stats['carte'], 2) }} DH</div>
                <div class="stat-subtext">
                    {{ $stats['total'] > 0 ? number_format(($stats['carte'] / $stats['total']) * 100, 1) : 0 }}% du total
                </div>
            </div>

            <div class="mega-stat-card">
                <div class="icon-circle">
                    <i class="fas fa-money-check"></i>
                </div>
                <div class="stat-label">Chèques</div>
                <div class="stat-value">{{ number_format($stats['cheque'], 2) }} DH</div>
                <div class="stat-subtext">
                    {{ $stats['total'] > 0 ? number_format(($stats['cheque'] / $stats['total']) * 100, 1) : 0 }}% du total
                </div>
            </div>

            <div class="mega-stat-card">
                <div class="icon-circle">
                    <i class="fas fa-university"></i>
                </div>
                <div class="stat-label">Virements</div>
                <div class="stat-value">{{ number_format($stats['virement'], 2) }} DH</div>
                <div class="stat-subtext">
                    {{ $stats['total'] > 0 ? number_format(($stats['virement'] / $stats['total']) * 100, 1) : 0 }}% du total
                </div>
            </div>

            <div class="mega-stat-card">
                <div class="icon-circle">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stat-label">Nombre de Paiements</div>
                <div class="stat-value">{{ $stats['nombre'] }}</div>
                <div class="stat-subtext">
                    Transactions enregistrées
                </div>
            </div>
        </div>

        <!-- Répartition par mode de paiement -->
        <div class="chart-card">
            <h5>
                <i class="fas fa-chart-bar"></i>
                Répartition par Mode de Paiement
            </h5>
            
            @if($stats['total'] > 0)
            <div class="payment-modes-grid">
                <div class="mode-card mode-especes">
                    <div class="mode-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="mode-label">Espèces</div>
                    <div class="mode-amount">{{ number_format($stats['especes'], 2) }}</div>
                    <div class="mode-count">DH</div>
                    <div class="progress-custom mt-3">
                        <div class="progress-bar" style="width: {{ ($stats['especes'] / $stats['total']) * 100 }}%; background: linear-gradient(90deg, #4CAF50, #45a049);">
                            {{ number_format(($stats['especes'] / $stats['total']) * 100, 1) }}%
                        </div>
                    </div>
                </div>

                <div class="mode-card mode-carte">
                    <div class="mode-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="mode-label">Cartes</div>
                    <div class="mode-amount">{{ number_format($stats['carte'], 2) }}</div>
                    <div class="mode-count">DH</div>
                    <div class="progress-custom mt-3">
                        <div class="progress-bar" style="width: {{ ($stats['carte'] / $stats['total']) * 100 }}%; background: linear-gradient(90deg, #2196F3, #1976D2);">
                            {{ number_format(($stats['carte'] / $stats['total']) * 100, 1) }}%
                        </div>
                    </div>
                </div>

                <div class="mode-card mode-cheque">
                    <div class="mode-icon">
                        <i class="fas fa-money-check"></i>
                    </div>
                    <div class="mode-label">Chèques</div>
                    <div class="mode-amount">{{ number_format($stats['cheque'], 2) }}</div>
                    <div class="mode-count">DH</div>
                    <div class="progress-custom mt-3">
                        <div class="progress-bar" style="width: {{ ($stats['cheque'] / $stats['total']) * 100 }}%; background: linear-gradient(90deg, #FF9800, #F57C00);">
                            {{ number_format(($stats['cheque'] / $stats['total']) * 100, 1) }}%
                        </div>
                    </div>
                </div>

                <div class="mode-card mode-virement">
                    <div class="mode-icon">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="mode-label">Virements</div>
                    <div class="mode-amount">{{ number_format($stats['virement'], 2) }}</div>
                    <div class="mode-count">DH</div>
                    <div class="progress-custom mt-3">
                        <div class="progress-bar" style="width: {{ ($stats['virement'] / $stats['total']) * 100 }}%; background: linear-gradient(90deg, #9C27B0, #7B1FA2);">
                            {{ number_format(($stats['virement'] / $stats['total']) * 100, 1) }}%
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center text-muted py-5">
                <i class="fas fa-chart-bar fa-3x mb-3" style="opacity: 0.3;"></i>
                <p>Aucune donnée disponible pour cette période</p>
            </div>
            @endif
        </div>

        <!-- Liste détaillée des paiements -->
        <div class="chart-card">
            <h5>
                <i class="fas fa-list-alt"></i>
                Liste Détaillée des Paiements ({{ $paiements->count() }})
            </h5>

            @if($paiements->count() > 0)
            <div class="table-rapport">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>N° Reçu</th>
                                <th>Client</th>
                                <th>Mode</th>
                                <th>Référence</th>
                                <th>Montant</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($paiements as $paiement)
                            <tr>
                                <td>
                                    <i class="fas fa-calendar me-2 text-muted"></i>
                                    {{ $paiement->date_paiement->format('d/m/Y') }}
                                    <br>
                                    <small class="text-muted">{{ $paiement->date_paiement->format('H:i') }}</small>
                                </td>
                                <td>
                                    <strong style="color: #C2185B;">{{ $paiement->recuUcg->numero_recu }}</strong>
                                </td>
                                <td>
                                    <i class="fas fa-user me-2 text-muted"></i>
                                    {{ $paiement->recuUcg->client_nom }}
                                </td>
                                <td>
                                    <span class="badge-mode" style="background: 
                                        @if($paiement->mode_paiement == 'especes') linear-gradient(135deg, #4CAF50, #45a049)
                                        @elseif($paiement->mode_paiement == 'carte') linear-gradient(135deg, #2196F3, #1976D2)
                                        @elseif($paiement->mode_paiement == 'cheque') linear-gradient(135deg, #FF9800, #F57C00)
                                        @else linear-gradient(135deg, #9C27B0, #7B1FA2)
                                        @endif
                                        ; color: white;">
                                        <i class="fas fa-{{ $paiement->mode_paiement == 'especes' ? 'money-bill-wave' : ($paiement->mode_paiement == 'carte' ? 'credit-card' : ($paiement->mode_paiement == 'cheque' ? 'money-check' : 'university')) }}"></i>
                                        {{ ucfirst($paiement->mode_paiement) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $paiement->reference ?? '-' }}</small>
                                </td>
                                <td>
                                    <strong style="color: #D32F2F; font-size: 1.1rem;">
                                        {{ number_format($paiement->montant, 2) }} DH
                                    </strong>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-user-tie me-1"></i>
                                        {{ $paiement->user->name ?? '-' }}
                                    </small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1)); font-weight: 700;">
                            <tr>
                                <td colspan="5" class="text-end">TOTAL GÉNÉRAL:</td>
                                <td colspan="2">
                                    <span style="color: #D32F2F; font-size: 1.3rem;">
                                        {{ number_format($stats['total'], 2) }} DH
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5 class="mt-3">Aucun paiement trouvé</h5>
                <p>Il n'y a aucun paiement pour la période sélectionnée.</p>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Graphique en camembert
        const ctx = document.getElementById('paymentModeChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Espèces', 'Carte', 'Chèque', 'Virement'],
                datasets: [{
                    data: [
                        {{ $stats['especes'] }},
                        {{ $stats['carte'] }},
                        {{ $stats['cheque'] }},
                        {{ $stats['virement'] }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#007bff',
                        '#ffc107',
                        '#17a2b8'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Définir la période du mois actuel
        function setPeriodeMois() {
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            
            document.querySelector('input[name="date_debut"]').value = firstDay.toISOString().split('T')[0];
            document.querySelector('input[name="date_fin"]').value = lastDay.toISOString().split('T')[0];
        }

        // Export Excel (simple)
        function exportToExcel() {
            const table = document.getElementById('paiementsTable');
            const html = table.outerHTML;
            const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
            const link = document.createElement('a');
            link.href = url;
            link.download = 'rapport_paiements_{{ now()->format("Y-m-d") }}.xls';
            link.click();
        }

        // Style pour l'impression
        const printStyles = `
            @media print {
                .btn, nav, .sidebar, .no-print {
                    display: none !important;
                }
                .card {
                    border: 1px solid #ddd;
                    page-break-inside: avoid;
                }
            }
        `;
        const style = document.createElement('style');
        style.textContent = printStyles;
        document.head.appendChild(style);
    </script>
    @endpush
</x-app-layout>