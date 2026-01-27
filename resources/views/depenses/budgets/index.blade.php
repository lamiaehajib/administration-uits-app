<x-app-layout>
    <div class="container-fluid">
        
        {{-- ✅ Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="hight">
                <i class="fas fa-piggy-bank"></i> Budgets Mensuels
            </h3>
            <div class="btn-group">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus"></i> Nouveau budget
                </button>
                <a href="{{ route('depenses.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Dashboard
                </a>
            </div>
        </div>

        {{-- ✅ Alertes --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ✅ Statistiques globales --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h6 class="text-white-50">Budget Total {{ now()->year }}</h6>
                        <h3 class="mb-0">{{ number_format($stats['budget_total_annee'], 2) }}</h3>
                        <small>DH</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                    <div class="card-body text-center">
                        <h6 class="text-white-50">Dépensé {{ now()->year }}</h6>
                        <h3 class="mb-0">{{ number_format($stats['depense_total_annee'], 2) }}</h3>
                        <small>DH</small>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h6 class="text-white-50">Budgets dépassés</h6>
                        <h3 class="mb-0">{{ $stats['budgets_depasses'] }}</h3>
                        <small>mois</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Filtres --}}
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-filter"></i> Filtres</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('depenses.budgets.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Année</label>
                            <select name="annee" class="form-select">
                                <option value="">Toutes</option>
                                @for($y = now()->year + 1; $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ request('annee') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="">Tous</option>
                                <option value="previsionnel" {{ request('statut') == 'previsionnel' ? 'selected' : '' }}>Prévisionnel</option>
                                <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                <option value="cloture" {{ request('statut') == 'cloture' ? 'selected' : '' }}>Clôturé</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-search"></i> Filtrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ✅ Grille des budgets (par année) --}}
        @php
            $budgetsParAnnee = $budgets->groupBy('annee');
        @endphp

        @foreach($budgetsParAnnee as $annee => $budgetsAnnee)
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt"></i> Année {{ $annee }}
                    <span class="badge bg-white text-dark float-end">{{ $budgetsAnnee->count() }} mois</span>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($budgetsAnnee as $budget)
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100 {{ $budget->is_depasse ? 'border-danger' : 'border-success' }}" style="border-width: 2px;">
                            <div class="card-header {{ $budget->is_depasse ? 'bg-danger' : 'bg-success' }} text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-calendar"></i> {{ $budget->mois_nom }}
                                    </h6>
                                    @if($budget->statut === 'previsionnel')
                                        <span class="badge bg-light text-dark">Prévisionnel</span>
                                    @elseif($budget->statut === 'en_cours')
                                        <span class="badge bg-warning">En cours</span>
                                    @else
                                        <span class="badge bg-secondary">Clôturé</span>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                {{-- Budget vs Réalisé --}}
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Budget</small>
                                        <strong>{{ number_format($budget->budget_total, 2) }} DH</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <small class="text-muted">Dépensé</small>
                                        <strong class="{{ $budget->is_depasse ? 'text-danger' : 'text-success' }}">
                                            {{ number_format($budget->depense_totale_realisee, 2) }} DH
                                        </strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">{{ $budget->is_depasse ? 'Dépassement' : 'Restant' }}</small>
                                        <strong class="{{ $budget->ecart_total >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format(abs($budget->ecart_total), 2) }} DH
                                        </strong>
                                    </div>
                                </div>

                                {{-- Progress Bar --}}
                                <div class="progress mb-3" style="height: 25px;">
                                    <div class="progress-bar {{ $budget->is_depasse ? 'bg-danger' : 'bg-success' }}" 
                                         style="width: {{ min($budget->taux_execution, 100) }}%">
                                        {{ number_format($budget->taux_execution, 1) }}%
                                    </div>
                                </div>

                                {{-- Détails --}}
                                <div class="row text-center small">
                                    <div class="col-6">
                                        <div class="text-muted">Fixes</div>
                                        <strong>{{ number_format($budget->depense_fixes_realisee, 0) }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <div class="text-muted">Variables</div>
                                        <strong>{{ number_format($budget->depense_variables_realisee, 0) }}</strong>
                                    </div>
                                </div>

                                {{-- Alerte dépassement --}}
                                @if($budget->alerte_depassement)
                                <div class="alert alert-danger alert-sm mt-3 mb-0 py-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <small>Budget dépassé!</small>
                                </div>
                                @endif
                            </div>
                            <div class="card-footer bg-light">
                                <div class="btn-group btn-group-sm w-100">
                                    <button class="btn btn-info" onclick="showBudget({{ $budget->id }})" title="Voir">
                                        <i class="fas fa-eye"></i> Voir
                                    </button>
                                    @if($budget->statut !== 'cloture')
                                        <button class="btn btn-warning text-white" onclick="editBudget({{ $budget->id }})" title="Modifier">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                        <button class="btn btn-success" onclick="cloturerBudget({{ $budget->id }})" title="Clôturer">
                                            <i class="fas fa-lock"></i> Clôturer
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        {{-- ✅ Pagination --}}
        @if($budgets->hasPages())
        <div class="d-flex justify-content-center">
            {{ $budgets->links() }}
        </div>
        @endif

        {{-- ✅ Message si vide --}}
        @if($budgets->isEmpty())
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="fas fa-piggy-bank fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun budget créé</h5>
                <p class="text-muted">Commencez par créer un budget mensuel</p>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="fas fa-plus"></i> Créer le premier budget
                </button>
            </div>
        </div>
        @endif

    </div>

    {{-- ========================================
         ✅ MODAL CREATE
        ======================================== --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('depenses.budgets.store') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Nouveau budget mensuel</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            {{-- Période --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Année <span class="text-danger">*</span></label>
                                <select name="annee" id="createAnnee" class="form-select" required>
                                    @for($y = now()->year + 1; $y >= 2020; $y--)
                                        <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mois <span class="text-danger">*</span></label>
                                <select name="mois" id="createMois" class="form-select" required>
                                    @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $i => $nom)
                                        <option value="{{ $i+1 }}" {{ ($i+1) == now()->month ? 'selected' : '' }}>{{ $nom }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Budgets --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Budget Dépenses Fixes <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="budget_fixes" id="createBudgetFixes" class="form-control" step="0.01" required>
                                    <span class="input-group-text">DH</span>
                                </div>
                                <small class="text-muted">Suggestion: <span id="suggestionFixes">Calcul...</span></small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Budget Dépenses Variables <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" name="budget_variables" id="createBudgetVariables" class="form-control" step="0.01" required>
                                    <span class="input-group-text">DH</span>
                                </div>
                                <small class="text-muted">Suggestion: <span id="suggestionVariables">Calcul...</span></small>
                            </div>

                            {{-- Total calculé --}}
                            <div class="col-md-12 mb-3">
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-calculator"></i> Budget Total:</span>
                                        <strong class="h5 mb-0" id="budgetTotal">0.00 DH</strong>
                                    </div>
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Objectifs, remarques..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Créer le budget
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ========================================
         ✅ MODAL SHOW
        ======================================== --}}
    <div class="modal fade" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-chart-pie"></i> Détails du budget</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="showModalContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================
         ✅ MODAL EDIT
        ======================================== --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Modifier le budget</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="editModalContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Annuler
                        </button>
                        <button type="submit" class="btn btn-warning text-white">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // ✅ Calculer budget total automatiquement
        function calculerTotal() {
            const fixes = parseFloat(document.getElementById('createBudgetFixes').value) || 0;
            const variables = parseFloat(document.getElementById('createBudgetVariables').value) || 0;
            const total = fixes + variables;
            
            document.getElementById('budgetTotal').textContent = total.toLocaleString('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' DH';
        }

        document.getElementById('createBudgetFixes')?.addEventListener('input', calculerTotal);
        document.getElementById('createBudgetVariables')?.addEventListener('input', calculerTotal);

        // ✅ Charger suggestions au chargement du modal
        document.getElementById('createModal')?.addEventListener('shown.bs.modal', function() {
            // Appel AJAX pour récupérer moyennes
            fetch('/depenses/api/stats')
                .then(response => response.json())
                .then(data => {
                    // Mettre suggestions (exemple simplifié)
                    document.getElementById('suggestionFixes').textContent = '100,000 DH';
                    document.getElementById('suggestionVariables').textContent = '50,000 DH';
                })
                .catch(error => {
                    console.error('Erreur suggestions:', error);
                });
        });

        // ✅ Voir détails
        function showBudget(id) {
            const modal = new bootstrap.Modal(document.getElementById('showModal'));
            const content = document.getElementById('showModalContent');
            
            content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-info"></div></div>';
            modal.show();
            
            fetch(`/depenses/budgets/${id}`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const mainContent = doc.querySelector('.container-fluid') || doc.querySelector('main');
                    content.innerHTML = mainContent ? mainContent.innerHTML : '<div class="alert alert-danger">Erreur</div>';
                })
                .catch(error => {
                    content.innerHTML = `<div class="alert alert-danger">Erreur: ${error.message}</div>`;
                });
        }

        // ✅ Modifier
        function editBudget(id) {
            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            const form = document.getElementById('editForm');
            const content = document.getElementById('editModalContent');
            
            form.action = `/depenses/budgets/${id}`;
            content.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-warning"></div></div>';
            modal.show();
            
            fetch(`/depenses/budgets/${id}/edit`)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const formContent = doc.querySelector('form') || doc.querySelector('.container-fluid');
                    
                    if (formContent) {
                        content.innerHTML = formContent.innerHTML;
                        
                        // Recalcul total si changement
                        const editFixes = content.querySelector('[name="budget_fixes"]');
                        const editVariables = content.querySelector('[name="budget_variables"]');
                        const editTotal = content.querySelector('#editBudgetTotal');
                        
                        function recalculerEditTotal() {
                            const f = parseFloat(editFixes.value) || 0;
                            const v = parseFloat(editVariables.value) || 0;
                            editTotal.textContent = (f + v).toLocaleString('fr-FR', {
                                minimumFractionDigits: 2
                            }) + ' DH';
                        }
                        
                        editFixes?.addEventListener('input', recalculerEditTotal);
                        editVariables?.addEventListener('input', recalculerEditTotal);
                    }
                })
                .catch(error => {
                    content.innerHTML = `<div class="alert alert-danger">Erreur: ${error.message}</div>`;
                });
        }

        // ✅ Clôturer
        function cloturerBudget(id) {
            Swal.fire({
                title: 'Clôturer ce budget?',
                text: "Vous ne pourrez plus le modifier après",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, clôturer!',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/depenses/budgets/${id}/cloturer`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                    
                    form.appendChild(csrfInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>