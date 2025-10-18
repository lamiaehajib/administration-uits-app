<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-bold fs-4 text-dark">
            <i class="fas fa-users-cog me-2"></i> {{ __('Gestion des Utilisateurs') }}
        </h2>
    </x-slot>

    <div class="container-fluid py-4">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-lg mb-4 animate__animated animate__fadeIn" role="alert" style="background: linear-gradient(135deg, #28a745, #20c997); border: none; border-radius: 15px; color: white;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fs-3 me-3"></i>
                    <strong class="fs-5">{{ $message }}</strong>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Statistiques Cards -->
        <div class="row g-4 mb-5">
            <!-- Total Users -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border-radius: 20px; height: 180px;">
                    <div class="position-absolute top-0 end-0 opacity-25 me-n3 mt-n3">
                        <i class="fas fa-users" style="font-size: 130px;"></i>
                    </div>
                    <div class="card-body position-relative text-white p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-white-50 fw-bold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 2px;">Total Utilisateurs</p>
                                <h2 class="fw-bold mb-0" style="font-size: 3.5rem;">{{ $totalUsers }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-4 p-3 shadow">
                                <i class="fas fa-users fs-2"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line me-2"></i>
                            <small>Base d'utilisateurs</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #D32F2F, #B71C1C); border-radius: 20px; height: 180px;">
                    <div class="position-absolute top-0 end-0 opacity-25 me-n3 mt-n3">
                        <i class="fas fa-user-check" style="font-size: 130px;"></i>
                    </div>
                    <div class="card-body position-relative text-white p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-white-50 fw-bold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 2px;">Utilisateurs Actifs</p>
                                <h2 class="fw-bold mb-0" style="font-size: 3.5rem;">{{ $activeUsers }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-4 p-3 shadow">
                                <i class="fas fa-user-check fs-2"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heartbeat me-2"></i>
                            <small>Comptes vérifiés</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Users -->
            <div class="col-md-4">
                <div class="card border-0 shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #ef4444, #C2185B); border-radius: 20px; height: 180px;">
                    <div class="position-absolute top-0 end-0 opacity-25 me-n3 mt-n3">
                        <i class="fas fa-user-plus" style="font-size: 130px;"></i>
                    </div>
                    <div class="card-body position-relative text-white p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <p class="text-white-50 fw-bold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 2px;">Nouveaux (30j)</p>
                                <h2 class="fw-bold mb-0" style="font-size: 3.5rem;">{{ $recentUsers }}</h2>
                            </div>
                            <div class="bg-white bg-opacity-25 rounded-4 p-3 shadow">
                                <i class="fas fa-user-plus fs-2"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <small>Derniers 30 jours</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres et Recherche -->
        <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <i class="fas fa-filter fs-3 me-3" style="color: #C2185B;"></i>
                    <h3 class="fw-bold mb-0" style="color: #D32F2F;">Filtres de Recherche</h3>
                </div>
                
                <form method="GET" action="{{ route('users.index') }}">
                    <div class="row g-4 mb-4">
                        <!-- Recherche -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-search me-1"></i> Rechercher
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-2" style="border-color: #dee2e6; border-radius: 12px 0 0 12px;">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       class="form-control form-control-lg border-2 border-start-0" 
                                       placeholder="Nom, email, ID..."
                                       style="border-color: #dee2e6; border-radius: 0 12px 12px 0;">
                            </div>
                        </div>

                        <!-- Filtre Rôle -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-user-tag me-1"></i> Rôle
                            </label>
                            <select name="role" class="form-select form-select-lg border-2" style="border-radius: 12px;">
                                <option value="">Tous les rôles</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Filtre Statut -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-toggle-on me-1"></i> Statut
                            </label>
                            <select name="status" class="form-select form-select-lg border-2" style="border-radius: 12px;">
                                <option value="">Tous les statuts</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                            </select>
                        </div>

                        <!-- Nombre par page -->
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-list-ol me-1"></i> Affichage
                            </label>
                            <select name="per_page" class="form-select form-select-lg border-2" style="border-radius: 12px;">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 par page</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 par page</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 par page</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 par page</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-lg fw-bold text-white shadow-lg" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border-radius: 12px; padding: 12px 30px;">
                                <i class="fas fa-search me-2"></i>
                                Filtrer
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-lg btn-secondary fw-bold shadow" style="border-radius: 12px; padding: 12px 30px;">
                                <i class="fas fa-redo me-2"></i>
                                Réinitialiser
                            </a>
                        </div>
                        <a href="{{ route('users.create') }}" class="btn btn-lg fw-bold text-white shadow-lg" style="background: linear-gradient(135deg, #D32F2F, #ef4444); border-radius: 12px; padding: 12px 30px;">
                            <i class="fas fa-user-plus me-2"></i>
                            Nouveau Utilisateur
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau des utilisateurs -->
        <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="text-white" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                        <tr>
                            <th class="py-4 fw-bold text-uppercase" style="font-size: 13px;">
                                <i class="fas fa-hashtag me-2"></i>ID
                            </th>
                            <th class="py-4 fw-bold text-uppercase" style="font-size: 13px;">
                                <i class="fas fa-user me-2"></i>Nom
                            </th>
                            <th class="py-4 fw-bold text-uppercase" style="font-size: 13px;">
                                <i class="fas fa-envelope me-2"></i>Email
                            </th>
                            <th class="py-4 fw-bold text-uppercase" style="font-size: 13px;">
                                <i class="fas fa-shield-alt me-2"></i>Rôles
                            </th>
                            <th class="py-4 fw-bold text-uppercase" style="font-size: 13px;">
                                <i class="fas fa-signal me-2"></i>Statut
                            </th>
                            <th class="py-4 fw-bold text-uppercase" style="font-size: 13px;">
                                <i class="fas fa-calendar me-2"></i>Date Création
                            </th>
                            <th class="py-4 fw-bold text-uppercase text-center" style="font-size: 13px;">
                                <i class="fas fa-cogs me-2"></i>Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $key => $user)
                            <tr style="transition: all 0.3s;">
                                <td class="py-4">
                                    <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold">#{{ $user->id }}</span>
                                </td>
                                <td class="py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-3 d-flex align-items-center justify-center text-white fw-bold shadow-sm me-3" 
                                             style="width: 50px; height: 50px; background: linear-gradient(135deg, #C2185B, #ef4444); font-size: 20px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4">
                                    <i class="fas fa-envelope me-2" style="color: #C2185B;"></i>
                                    <span>{{ $user->email }}</span>
                                </td>
                                <td class="py-4">
                                    @if(!empty($user->getRoleNames()))
                                        @foreach($user->getRoleNames() as $roleName)
                                            <span class="badge text-white rounded-pill px-3 py-2 me-1 fw-bold shadow-sm" style="background: linear-gradient(135deg, #D32F2F, #C2185B);">
                                                <i class="fas fa-shield-alt me-1"></i>
                                                {{ $roleName }}
                                            </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="py-4">
                                    @if($user->email_verified_at)
                                        <span class="badge rounded-pill px-3 py-2 fw-bold shadow-sm" style="background: linear-gradient(135deg, #28a745, #20c997); color: white;">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Actif
                                        </span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold shadow-sm">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Inactif
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4">
                                    <div class="fw-semibold">
                                        <i class="far fa-calendar-alt me-2" style="color: #C2185B;"></i>
                                        {{ $user->created_at->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>
                                        {{ $user->created_at->diffForHumans() }}
                                    </small>
                                </td>
                                <td class="py-4 text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('users.show', $user->id) }}" 
                                           class="btn btn-sm text-white fw-bold shadow" 
                                           style="background: linear-gradient(135deg, #0d6efd, #0a58ca); border-radius: 10px; padding: 10px 15px;"
                                           title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->id) }}" 
                                           class="btn btn-sm text-white fw-bold shadow" 
                                           style="background: linear-gradient(135deg, #C2185B, #D32F2F); border-radius: 10px; padding: 10px 15px;"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm text-white fw-bold shadow" 
                                                    style="background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 10px; padding: 10px 15px;"
                                                    title="Supprimer">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-5 text-center">
                                    <div class="d-flex flex-column align-items-center">
                                        <div class="bg-light rounded-circle p-5 mb-4 shadow-sm">
                                            <i class="fas fa-users-slash text-muted" style="font-size: 70px;"></i>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-2">Aucun utilisateur trouvé</h5>
                                        <p class="text-muted">Essayez de modifier vos critères de recherche ou ajoutez un nouveau utilisateur</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($data->hasPages())
                <div class="card-footer bg-light border-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-2" style="color: #C2185B;"></i>
                            <span class="fw-semibold">
                                Affichage de 
                                <span class="badge bg-primary mx-1">{{ $data->firstItem() }}</span> à 
                                <span class="badge bg-primary mx-1">{{ $data->lastItem() }}</span> sur 
                                <span class="badge mx-1" style="background: #D32F2F;">{{ $data->total() }}</span> résultats
                            </span>
                        </div>
                        <div>
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate__animated {
            animation-duration: 0.6s;
        }

        .animate__fadeIn {
            animation-name: fadeIn;
        }

        /* Hover effects */
        .card {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .table tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.05), rgba(239, 68, 68, 0.05));
        }

        .btn {
            transition: all 0.3s;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #C2185B, #D32F2F);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #D32F2F, #ef4444);
        }
    </style>
</x-app-layout>