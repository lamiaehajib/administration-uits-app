<x-app-layout>
    <div class="container-fluid">
        {{-- En-tête --}}
        <div class="card mb-3">
            <div class="card-header" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt"></i> Budget {{ $budget->mois_nom }} {{ $budget->annee }}
                    </h5>
                    @if($budget->statut === 'cloture')
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-lock"></i> Clôturé
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Métriques principales --}}
                    <div class="col-md-3 text-center mb-3">
                        <h6 class="text-muted">Budget Total</h6>
                        <h3 class="text-success">{{ number_format($budget->budget_total, 2) }}</h3>
                        <small>DH</small>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h6 class="text-muted">Dépensé</h6>
                        <h3 class="{{ $budget->is_depasse ? 'text-danger' : 'text-warning' }}">
                            {{ number_format($budget->depense_totale_realisee, 2) }}
                        </h3>
                        <small>DH</small>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h6 class="text-muted">{{ $budget->is_depasse ? 'Dépassement' : 'Restant' }}</h6>
                        <h3 class="{{ $budget->ecart_total >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format(abs($budget->ecart_total), 2) }}
                        </h3>
                        <small>DH</small>
                    </div>
                    <div class="col-md-3 text-center mb-3">
                        <h6 class="text-muted">Taux d'exécution</h6>
                        <h3 class="{{ $budget->is_depasse ? 'text-danger' : 'text-info' }}">
                            {{ number_format($budget->taux_execution, 1) }}%
                        </h3>
                    </div>

                    {{-- Progress --}}
                    <div class="col-md-12">
                        <div class="progress" style="height: 30px;">
                            <div class="progress-bar {{ $budget->is_depasse ? 'bg-danger' : 'bg-success' }}" 
                                 style="width: {{ min($budget->taux_execution, 100) }}%">
                                {{ number_format($budget->taux_execution, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Détails Fixes vs Variables --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="fas fa-calendar-check"></i> Dépenses Fixes</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Budget:</span>
                            <strong>{{ number_format($budget->budget_fixes, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Réalisé:</span>
                            <strong class="text-primary">{{ number_format($budget->depense_fixes_realisee, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Écart:</span>
                            <strong class="{{ $budget->ecart_fixes >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($budget->ecart_fixes, 2) }} DH
                            </strong>
                        </div>
                        <div class="progress mt-3" style="height: 20px;">
                            <div class="progress-bar bg-primary" 
                                 style="width: {{ $budget->taux_execution_fixes }}%">
                                {{ number_format($budget->taux_execution_fixes, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="fas fa-chart-line"></i> Dépenses Variables</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Budget:</span>
                            <strong>{{ number_format($budget->budget_variables, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Réalisé:</span>
                            <strong class="text-warning">{{ number_format($budget->depense_variables_realisee, 2) }} DH</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Écart:</span>
                            <strong class="{{ $budget->ecart_variables >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($budget->ecart_variables, 2) }} DH
                            </strong>
                        </div>
                        <div class="progress mt-3" style="height: 20px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: {{ $budget->taux_execution_variables }}%">
                                {{ number_format($budget->taux_execution_variables, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($budget->notes)
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-sticky-note"></i> Notes</h6>
            </div>
            <div class="card-body">
                {{ $budget->notes }}
            </div>
        </div>
        @endif
    </div>
</x-app-layout>