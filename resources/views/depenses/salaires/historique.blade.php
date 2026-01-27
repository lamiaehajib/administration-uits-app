<x-app-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h3 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    <span class="hight">Historique Imports Salaires</span>
                </h3>
                <p class="text-muted mb-0">Imports depuis uits-mgmt.ma</p>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-download me-2"></i>Nouvel Import
                </button>
                <a href="{{ route('depenses.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <!-- Timeline des imports -->
        <div class="row">
            <div class="col-12">
                @forelse($historiques as $historique)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="text-center">
                                    <div class="avatar-lg bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                        <i class="fas fa-calendar-check text-success fs-3"></i>
                                    </div>
                                    <h5 class="mt-2 mb-0">{{ $historique->mois_nom }}</h5>
                                    <p class="text-muted mb-0">{{ $historique->annee }}</p>
                                </div>
                            </div>

                            <div class="col-md-7">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Nombre d'employés</small>
                                        <h5 class="mb-0">
                                            <i class="fas fa-users text-primary me-1"></i>
                                            {{ $historique->nombre_employes }}
                                        </h5>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Montant Total</small>
                                        <h5 class="mb-0 text-danger">
                                            {{ number_format($historique->montant_total, 2) }} DH
                                        </h5>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block">Importé par</small>
                                        <p class="mb-0">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $historique->importePar->name ?? 'N/A' }}
                                        </p>
                                        <small class="text-muted">{{ $historique->importe_le->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>

                                <div class="mt-2">
                                    {!! $historique->statut_badge !!}
                                </div>
                            </div>

                            <div class="col-md-3 text-end">
                                <button class="btn btn-info btn-sm" onclick="showHistorique({{ $historique->id }})">
                                    <i class="fas fa-eye me-2"></i>Détails
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox text-muted fs-1 mb-3 d-block"></i>
                        <h5 class="text-muted mb-3">Aucun import trouvé</h5>
                        <p class="text-muted mb-4">Commencez par importer les salaires depuis uits-mgmt.ma</p>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
                            <i class="fas fa-download me-2"></i>Premier Import
                        </button>
                    </div>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($historiques->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                {{ $historiques->links() }}
            </div>
        </div>
        @endif
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-download me-2"></i>Importer Salaires
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('depenses.importer-salaires') }}" method="POST" id="importForm">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <i class="fas fa-info-circle me-2"></i>
                            Cette action va récupérer les salaires depuis <strong>uits-mgmt.ma</strong> et créer automatiquement:
                            <ul class="mb-0 mt-2">
                                <li>Une dépense fixe pour les salaires</li>
                                <li>Une dépense variable pour la CNSS (20.48%)</li>
                            </ul>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mois <span class="text-danger">*</span></label>
                            <select name="mois" class="form-select" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == date('n') ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Année <span class="text-danger">*</span></label>
                            <select name="annee" class="form-select" required>
                                @foreach(range(date('Y'), date('Y') - 3) as $y)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="importPreview" style="display: none;">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="mb-3">
                                        <i class="fas fa-eye me-2"></i>Aperçu
                                    </h6>
                                    <div id="previewContent">
                                        <div class="text-center py-3">
                                            <div class="spinner-border spinner-border-sm text-primary"></div>
                                            <p class="mb-0 small mt-2">Chargement...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-info" id="btnPreview">
                            <i class="fas fa-eye me-2"></i>Aperçu
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Show Details -->
    <div class="modal fade" id="showModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-file-invoice me-2"></i>Détails Import Salaire
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="showContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Preview avant import
        $('#btnPreview').on('click', function() {
            const mois = $('select[name="mois"]').val();
            const annee = $('select[name="annee"]').val();

            $('#importPreview').show();
            $('#previewContent').html('<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>');

            $.get('/depenses/api/employees', function(response) {
                if (response.success && response.employees.length > 0) {
                    const total = response.employees.reduce((sum, emp) => sum + parseFloat(emp.salaire || 0), 0);
                    const cnss = total * 0.2048;

                    let html = `
                        <div class="row text-center mb-3">
                            <div class="col-md-4">
                                <small class="text-muted d-block">Employés</small>
                                <h5 class="mb-0">${response.employees.length}</h5>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">Total Salaires</small>
                                <h5 class="mb-0 text-danger">${total.toFixed(2)} DH</h5>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted d-block">CNSS (20.48%)</small>
                                <h5 class="mb-0 text-warning">${cnss.toFixed(2)} DH</h5>
                            </div>
                        </div>
                        <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                            <table class="table table-sm table-striped">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th>Employé</th>
                                        <th>Poste</th>
                                        <th class="text-end">Salaire</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                    response.employees.forEach(emp => {
                        html += `
                            <tr>
                                <td>${emp.name}</td>
                                <td><small>${emp.poste}</small></td>
                                <td class="text-end">${parseFloat(emp.salaire).toFixed(2)} DH</td>
                            </tr>
                        `;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;

                    $('#previewContent').html(html);
                } else {
                    $('#previewContent').html('<p class="text-muted text-center mb-0">Aucun employé trouvé</p>');
                }
            }).fail(function() {
                $('#previewContent').html('<div class="alert alert-danger small mb-0">Erreur de connexion à uits-mgmt.ma</div>');
            });
        });

        // Show historique details
        function showHistorique(id) {
            $('#showModal').modal('show');
            $('#showContent').html('<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>');
            
            $.get(`/depenses/salaires/${id}`, function(response) {
                $('#showContent').html(response);
            });
        }

        // Form submit
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Confirmer l\'import?',
                text: "Les salaires et la CNSS seront créés automatiquement",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui, importer',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        @if(session('success'))
            Swal.fire('Succès!', '{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            Swal.fire('Erreur!', '{{ session('error') }}', 'error');
        @endif
    </script>
    @endpush
</x-app-layout>