<x-app-layout>
    <style>
        .stats-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            border-left: 4px solid;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .stats-card-1 { border-left-color: #C2185B; }
        .stats-card-2 { border-left-color: #D32F2F; }
        .stats-card-3 { border-left-color: #ef4444; }
        .stats-card-4 { border-left-color: #C2185B; }
        
        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
        }
        .icon-gradient-1 { background: linear-gradient(135deg, #C2185B, #D32F2F); }
        .icon-gradient-2 { background: linear-gradient(135deg, #D32F2F, #ef4444); }
        .icon-gradient-3 { background: linear-gradient(135deg, #ef4444, #C2185B); }
        .icon-gradient-4 { background: linear-gradient(135deg, #D32F2F, #C2185B); }
        
        .gradient-bg {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05), rgba(239, 68, 68, 0.05));
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #D32F2F, #ef4444);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.4);
            color: white;
        }
        
        .filter-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .table-gradient thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F, #ef4444);
        }
        
        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
        }
        
        .badge-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .alert-success-custom {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border-left: 4px solid #10b981;
            border-radius: 12px;
        }
        
        .empty-state {
            padding: 60px 20px;
            text-align: center;
        }
        .empty-state i {
            font-size: 80px;
            color: #e5e7eb;
            margin-bottom: 20px;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>

    <div class="gradient-bg min-vh-100 py-4">
        <!-- En-tête -->
        <div class="container-fluid mb-4">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h1 class="hight mb-2" style="font-size: 2.5rem;">
                        <i class="fas fa-file-certificate"></i> Gestion des Attestations
                    </h1>
                    <p class="text-muted">Gérez et suivez toutes vos attestations</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('attestations_allinone.create') }}" class="btn btn-gradient btn-lg rounded-pill shadow">
                        <i class="fas fa-plus me-2"></i>Nouvelle Attestation
                    </a>
                </div>
            </div>
        </div>

        <!-- Cartes de statistiques -->
        <div class="container-fluid mb-4">
            <div class="row g-3">
                <!-- Total -->
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card stats-card-1 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Total</p>
                                <h2 class="mb-0 fw-bold">{{ $stats['total'] }}</h2>
                            </div>
                            <div class="stats-icon icon-gradient-1">
                                <i class="fas fa-file-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Aujourd'hui -->
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card stats-card-2 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Aujourd'hui</p>
                                <h2 class="mb-0 fw-bold">{{ $stats['today'] }}</h2>
                            </div>
                            <div class="stats-icon icon-gradient-2">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ce mois -->
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card stats-card-3 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Ce mois</p>
                                <h2 class="mb-0 fw-bold">{{ $stats['this_month'] }}</h2>
                            </div>
                            <div class="stats-icon icon-gradient-3">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avec Cachet -->
                <div class="col-lg-3 col-md-6">
                    <div class="stats-card stats-card-4 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">Avec Cachet</p>
                                <h2 class="mb-0 fw-bold">{{ $stats['with_cachet'] }}</h2>
                            </div>
                            <div class="stats-icon icon-gradient-4">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="container-fluid mb-4">
            <div class="filter-card p-4">
                <form method="GET" action="{{ route('attestations_allinone.index') }}">
                    <div class="row g-3 mb-3">
                        <!-- Recherche -->
                        <div class="col-lg-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-search text-danger"></i> Recherche
                            </label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control form-control-lg" 
                                   placeholder="Nom, CIN, N° série..." 
                                   value="{{ $search }}">
                        </div>

                        <!-- Date début -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="far fa-calendar-alt text-danger"></i> Date début
                            </label>
                            <input type="date" 
                                   name="date_from" 
                                   class="form-control form-control-lg" 
                                   value="{{ $dateFrom }}">
                        </div>

                        <!-- Date fin -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="far fa-calendar-alt text-danger"></i> Date fin
                            </label>
                            <input type="date" 
                                   name="date_to" 
                                   class="form-control form-control-lg" 
                                   value="{{ $dateTo }}">
                        </div>
                    </div>

                    <div class="row g-3 align-items-end">
                        <!-- Cachet -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-stamp text-danger"></i> Cachet
                            </label>
                            <select name="afficher_cachet" class="form-select form-select-lg">
                                <option value="">Tous</option>
                                <option value="1" {{ $afficherCachet == '1' ? 'selected' : '' }}>Avec cachet</option>
                                <option value="0" {{ $afficherCachet == '0' ? 'selected' : '' }}>Sans cachet</option>
                            </select>
                        </div>

                        <!-- Par page -->
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-bold">
                                <i class="fas fa-list-ol text-danger"></i> Par page
                            </label>
                            <select name="per_page" class="form-select form-select-lg">
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <!-- Boutons -->
                        <div class="col-lg-6 col-md-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-gradient btn-lg flex-fill">
                                    <i class="fas fa-filter me-2"></i>Filtrer
                                </button>
                                <a href="{{ route('attestations_allinone.index') }}" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-redo me-2"></i>Réinitialiser
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Message de succès -->
        @if(session('success'))
        <div class="container-fluid mb-4">
            <div class="alert alert-success-custom fade-in" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success fs-4 me-3"></i>
                    <strong>{{ session('success') }}</strong>
                </div>
            </div>
        </div>
        @endif

        <!-- Table -->
        <div class="container-fluid">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover table-gradient mb-0">
                        <thead>
                            <tr class="text-white">
                                <th class="py-3"><i class="fas fa-hashtag me-2"></i>ID</th>
                                <th class="py-3"><i class="fas fa-user me-2"></i>Nom</th>
                                <th class="py-3"><i class="fas fa-id-card me-2"></i>CIN</th>
                                <th class="py-3"><i class="fas fa-barcode me-2"></i>N° Série</th>
                                <th class="py-3"><i class="fas fa-stamp me-2"></i>Cachet</th>
                                <th class="py-3"><i class="far fa-clock me-2"></i>Date</th>
                                <th class="py-3"><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attestations as $attestation)
                            <tr>
                                <td class="align-middle fw-bold">#{{ $attestation->id }}</td>
                                <td class="align-middle">
                                    <i class="fas fa-user-circle me-2" style="color: #C2185B;"></i>
                                    <strong>{{ $attestation->personne_name }}</strong>
                                </td>
                                <td class="align-middle text-muted">{{ $attestation->cin }}</td>
                                <td class="align-middle">
                                    <span class="badge badge-gradient rounded-pill px-3 py-2">
                                        {{ $attestation->numero_de_serie }}
                                    </span>
                                </td>
                                <td class="align-middle">
                                    @if($attestation->afficher_cachet)
                                        <span class="badge bg-success rounded-pill">
                                            <i class="fas fa-check-circle me-1"></i>Oui
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">
                                            <i class="fas fa-times-circle me-1"></i>Non
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle text-muted">
                                    <i class="far fa-calendar me-1" style="color: #D32F2F;"></i>
                                    {{ $attestation->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex gap-1 justify-content-center">
                                        <!-- PDF -->
                                        <a href="{{ route('attestations_allinone.pdf', $attestation) }}" 
                                           class="action-btn bg-primary text-white" 
                                           title="Télécharger PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        
                                        <!-- Modifier -->
                                        <a href="{{ route('attestations_allinone.edit', $attestation) }}" 
                                           class="action-btn text-white"
                                           style="background: linear-gradient(135deg, #D32F2F, #ef4444);"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Supprimer -->
                                        <form action="{{ route('attestations_allinone.destroy', $attestation) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette attestation ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="action-btn bg-danger text-white border-0" 
                                                    title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="border-0">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h4 class="text-muted mb-2">Aucune attestation trouvée</h4>
                                        <p class="text-muted mb-4">Commencez par créer votre première attestation</p>
                                        <a href="{{ route('attestations_allinone.create') }}" class="btn btn-gradient btn-lg rounded-pill">
                                            <i class="fas fa-plus me-2"></i>Créer une attestation
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                  @if($attestations->hasPages())
                <div class="bg-gradient-to-r from-pink-50 to-red-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            <i class="fas fa-info-circle mr-1"></i>
                            Affichage de <span class="font-semibold text-[#C2185B]">{{ $attestations->firstItem() }}</span> 
                            à <span class="font-semibold text-[#D32F2F]">{{ $attestations->lastItem() }}</span> 
                            sur <span class="font-semibold text-[#ef4444]">{{ $attestations->total() }}</span> résultats
                        </div>
                        <div>
                            {{ $attestations->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>