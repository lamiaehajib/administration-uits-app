<x-app-layout>
    <style>
        .detail-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
        }

        .detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.15);
        }

        .gradient-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 25px;
        }

        .stat-metric {
            background: linear-gradient(135deg, #fff5f5, #ffffff);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            border: 2px solid #fce4ec;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-metric::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            opacity: 0.1;
            transform: translate(30%, -30%);
        }

        .stat-metric.success::before {
            background: #4CAF50;
        }

        .stat-metric.warning::before {
            background: #FF9800;
        }

        .stat-metric.danger::before {
            background: #D32F2F;
        }

        .stat-metric.info::before {
            background: #2196F3;
        }

        .stat-metric:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(211, 47, 47, 0.1);
            border-color: #D32F2F;
        }

        .stat-icon-lg {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .metric-value {
            font-size: 2.5rem;
            font-weight: 800;
            line-height: 1;
            margin: 10px 0;
        }

        .progress-detail {
            height: 35px;
            border-radius: 20px;
            background: #fce4ec;
            overflow: hidden;
            box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .progress-detail .progress-bar {
            border-radius: 20px;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: width 0.6s ease;
        }

        .depense-card {
            background: white;
            border-radius: 15px;
            border: 2px solid #fce4ec;
            transition: all 0.3s ease;
        }

        .depense-card:hover {
            border-color: #D32F2F;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.1);
        }

        .depense-header {
            padding: 20px;
            border-bottom: 2px solid #fce4ec;
        }

        .info-row {
            padding: 15px 0;
            border-bottom: 1px solid #fce4ec;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .badge-status {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .notes-box {
            background: linear-gradient(135deg, #fff5f5, #ffffff);
            border-left: 5px solid #D32F2F;
            padding: 20px;
            border-radius: 10px;
        }
    </style>

    <div class="container-fluid">
        <!-- 🎯 En-tête Principal -->
        <div class="detail-card shadow-sm mb-4">
            <div class="gradient-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Budget {{ $budget->mois_nom }} {{ $budget->annee }}
                        </h3>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-info-circle me-1"></i>
                            Créé le {{ $budget->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        @if($budget->statut === 'previsionnel')
                            <span class="badge-status bg-info text-white">
                                <i class="fas fa-file-alt me-1"></i> Prévisionnel
                            </span>
                        @elseif($budget->statut === 'en_cours')
                            <span class="badge-status bg-warning text-white">
                                <i class="fas fa-play-circle me-1"></i> En Cours
                            </span>
                        @else
                            <span class="badge-status bg-secondary text-white">
                                <i class="fas fa-lock me-1"></i> Clôturé
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 📊 Métriques Principales -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-metric success">
                    <div class="stat-icon-lg">
                        <i class="fas fa-wallet text-success fs-2"></i>
                    </div>
                    <h6 class="text-muted mb-2">Budget Total</h6>
                    <h3 class="metric-value text-success">{{ number_format($budget->budget_total, 0) }}</h3>
                    <small class="text-muted fw-semibold">DH</small>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-metric {{ $budget->is_depasse ? 'danger' : 'warning' }}">
                    <div class="stat-icon-lg">
                        <i class="fas fa-money-bill-wave {{ $budget->is_depasse ? 'text-danger' : 'text-warning' }} fs-2"></i>
                    </div>
                    <h6 class="text-muted mb-2">Dépensé</h6>
                    <h3 class="metric-value {{ $budget->is_depasse ? 'text-danger' : 'text-warning' }}">
                        {{ number_format($budget->depense_totale_realisee, 0) }}
                    </h3>
                    <small class="text-muted fw-semibold">DH</small>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-metric {{ $budget->ecart_total >= 0 ? 'success' : 'danger' }}">
                    <div class="stat-icon-lg">
                        <i class="fas fa-{{ $budget->ecart_total >= 0 ? 'check-circle' : 'exclamation-triangle' }} 
                           {{ $budget->ecart_total >= 0 ? 'text-success' : 'text-danger' }} fs-2"></i>
                    </div>
                    <h6 class="text-muted mb-2">{{ $budget->is_depasse ? 'Dépassement' : 'Restant' }}</h6>
                    <h3 class="metric-value {{ $budget->ecart_total >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ number_format(abs($budget->ecart_total), 0) }}
                    </h3>
                    <small class="text-muted fw-semibold">DH</small>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-metric info">
                    <div class="stat-icon-lg">
                        <i class="fas fa-percentage text-info fs-2"></i>
                    </div>
                    <h6 class="text-muted mb-2">Taux d'Exécution</h6>
                    <h3 class="metric-value {{ $budget->is_depasse ? 'text-danger' : 'text-info' }}">
                        {{ number_format($budget->taux_execution, 1) }}
                    </h3>
                    <small class="text-muted fw-semibold">%</small>
                </div>
            </div>
        </div>

        <!-- 📈 Progress Bar Globale -->
        <div class="detail-card shadow-sm mb-4">
            <div class="card-body p-4">
                <h6 class="text-muted mb-3">
                    <i class="fas fa-chart-line me-2"></i>
                    Progression Globale
                </h6>
                <div class="progress-detail progress">
                    @php
                        $tauxAffiche = min($budget->taux_execution, 100);
                        $progressColor = $budget->is_depasse ? 'bg-danger' : 'bg-success';
                    @endphp
                    <div class="progress-bar {{ $progressColor }}" 
                         style="width: {{ $tauxAffiche }}%">
                        {{ number_format($budget->taux_execution, 1) }}% 
                        ({{ number_format($budget->depense_totale_realisee, 0) }} / {{ number_format($budget->budget_total, 0) }} DH)
                    </div>
                </div>
                
                @if($budget->is_depasse)
                <div class="alert alert-danger mt-3 mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention!</strong> Le budget est dépassé de <strong>{{ number_format(abs($budget->ecart_total), 2) }} DH</strong>
                </div>
                @endif
            </div>
        </div>

        <!-- 💰 Détails Fixes vs Variables -->
        <div class="row g-4 mb-4">
            <!-- Dépenses Fixes -->
            <div class="col-md-6">
                <div class="depense-card">
                    <div class="depense-header" style="background: linear-gradient(135deg, #e3f2fd, #ffffff);">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check text-primary me-2"></i>
                            Dépenses Fixes
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="info-row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Budget Alloué:</span>
                                <h5 class="mb-0 fw-bold text-primary">{{ number_format($budget->budget_fixes, 2) }} DH</h5>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Réalisé:</span>
                                <h5 class="mb-0 fw-bold text-info">{{ number_format($budget->depense_fixes_realisee, 2) }} DH</h5>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Écart:</span>
                                <h5 class="mb-0 fw-bold {{ $budget->ecart_fixes >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $budget->ecart_fixes >= 0 ? '+' : '' }}{{ number_format($budget->ecart_fixes, 2) }} DH
                                </h5>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Taux d'exécution</small>
                                <small class="fw-bold">{{ number_format($budget->taux_execution_fixes, 1) }}%</small>
                            </div>
                            <div class="progress-detail progress" style="height: 25px;">
                                <div class="progress-bar bg-primary" 
                                     style="width: {{ min($budget->taux_execution_fixes, 100) }}%">
                                    {{ number_format($budget->taux_execution_fixes, 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dépenses Variables -->
            <div class="col-md-6">
                <div class="depense-card">
                    <div class="depense-header" style="background: linear-gradient(135deg, #fff3e0, #ffffff);">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-line text-warning me-2"></i>
                            Dépenses Variables
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="info-row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Budget Alloué:</span>
                                <h5 class="mb-0 fw-bold text-warning">{{ number_format($budget->budget_variables, 2) }} DH</h5>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Réalisé:</span>
                                <h5 class="mb-0 fw-bold" style="color: #FF9800;">{{ number_format($budget->depense_variables_realisee, 2) }} DH</h5>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Écart:</span>
                                <h5 class="mb-0 fw-bold {{ $budget->ecart_variables >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $budget->ecart_variables >= 0 ? '+' : '' }}{{ number_format($budget->ecart_variables, 2) }} DH
                                </h5>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Taux d'exécution</small>
                                <small class="fw-bold">{{ number_format($budget->taux_execution_variables, 1) }}%</small>
                            </div>
                            <div class="progress-detail progress" style="height: 25px;">
                                <div class="progress-bar bg-warning" 
                                     style="width: {{ min($budget->taux_execution_variables, 100) }}%">
                                    {{ number_format($budget->taux_execution_variables, 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📝 Notes -->
        @if($budget->notes)
        <div class="detail-card shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="notes-box">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-sticky-note me-2"></i>
                        Notes & Commentaires
                    </h6>
                    <p class="mb-0 text-muted">{{ $budget->notes }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- 📊 Tableau Récapitulatif -->
        <div class="detail-card shadow-sm">
            <div class="card-body p-4">
                <h5 class="mb-4">
                    <i class="fas fa-table me-2 text-danger"></i>
                    <span class="hight">Récapitulatif Détaillé</span>
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Budget Alloué</th>
                                <th class="text-end">Dépense Réalisée</th>
                                <th class="text-end">Écart</th>
                                <th class="text-end">Taux (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <i class="fas fa-calendar-check text-primary me-2"></i>
                                    <strong>Dépenses Fixes</strong>
                                </td>
                                <td class="text-end">{{ number_format($budget->budget_fixes, 2) }} DH</td>
                                <td class="text-end text-primary fw-bold">{{ number_format($budget->depense_fixes_realisee, 2) }} DH</td>
                                <td class="text-end {{ $budget->ecart_fixes >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $budget->ecart_fixes >= 0 ? '+' : '' }}{{ number_format($budget->ecart_fixes, 2) }} DH
                                </td>
                                <td class="text-end fw-bold">{{ number_format($budget->taux_execution_fixes, 1) }}%</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fas fa-chart-line text-warning me-2"></i>
                                    <strong>Dépenses Variables</strong>
                                </td>
                                <td class="text-end">{{ number_format($budget->budget_variables, 2) }} DH</td>
                                <td class="text-end text-warning fw-bold">{{ number_format($budget->depense_variables_realisee, 2) }} DH</td>
                                <td class="text-end {{ $budget->ecart_variables >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $budget->ecart_variables >= 0 ? '+' : '' }}{{ number_format($budget->ecart_variables, 2) }} DH
                                </td>
                                <td class="text-end fw-bold">{{ number_format($budget->taux_execution_variables, 1) }}%</td>
                            </tr>
                        </tbody>
                        <tfoot style="background: linear-gradient(135deg, #fff5f5, #ffffff); border-top: 3px solid #D32F2F;">
                            <tr>
                                <th>
                                    <i class="fas fa-calculator me-2"></i>
                                    TOTAL
                                </th>
                                <th class="text-end">{{ number_format($budget->budget_total, 2) }} DH</th>
                                <th class="text-end {{ $budget->is_depasse ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($budget->depense_totale_realisee, 2) }} DH
                                </th>
                                <th class="text-end {{ $budget->ecart_total >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $budget->ecart_total >= 0 ? '+' : '' }}{{ number_format($budget->ecart_total, 2) }} DH
                                </th>
                                <th class="text-end {{ $budget->is_depasse ? 'text-danger' : 'text-info' }}">
                                    {{ number_format($budget->taux_execution, 1) }}%
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>