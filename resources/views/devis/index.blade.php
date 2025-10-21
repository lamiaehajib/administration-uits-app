<x-app-layout>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .stats-card {
            border-radius: 15px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
            color: white;
        }

        .table-custom {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .table-custom thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .table-custom tbody tr {
            transition: all 0.2s ease;
        }

        .table-custom tbody tr:hover {
            background-color: #fff5f5;
            transform: scale(1.01);
        }

        .badge-custom {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-dh {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-eur {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: none;
            margin: 0 3px;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
        }

        .btn-edit {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .btn-duplicate {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .search-box {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }

        .search-box:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
            outline: none;
        }

        .period-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
            background: white;
            color: #6b7280;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .period-btn:hover {
            border-color: #D32F2F;
            color: #D32F2F;
        }

        .period-btn.active {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: transparent;
        }

        .filter-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .pagination {
            margin-top: 25px;
        }

        .page-link {
            color: #D32F2F;
            border: 1px solid #e5e7eb;
            margin: 0 3px;
            border-radius: 8px;
        }

        .page-link:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: transparent;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border-color: transparent;
        }

        @media (max-width: 768px) {
            .stats-card {
                margin-bottom: 15px;
            }
            
            .filter-card {
                padding: 15px;
            }
        }
    </style>

    <div class="container-fluid px-4">
        <!-- En-tête avec titre et bouton d'ajout -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gradient-text mb-1" style="font-size: 32px; font-weight: 700;">
                    <i class="fas fa-file-invoice"></i> Gestion des Devis
                </h2>
                <p class="text-muted mb-0">Gérez vos devis et suivez vos statistiques</p>
            </div>
            <a href="{{ route('devis.create') }}" class="btn btn-gradient">
                <i class="fas fa-plus-circle me-2"></i> Nouveau Devis
            </a>
        </div>

        <!-- Statistiques -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1" style="font-size: 13px; font-weight: 600;">TOTAL DEVIS</p>
                            <h3 class="mb-0" style="font-weight: 700; color: #1f2937;">{{ $stats['total_devis'] }}</h3>
                        </div>
                        <div class="stats-icon" style="background: linear-gradient(135deg, #C2185B, #D32F2F); color: white;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1" style="font-size: 13px; font-weight: 600;">CE MOIS</p>
                            <h3 class="mb-0" style="font-weight: 700; color: #1f2937;">{{ $stats['devis_ce_mois'] }}</h3>
                        </div>
                        <div class="stats-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1" style="font-size: 13px; font-weight: 600;">MONTANT TOTAL</p>
                            <h3 class="mb-0" style="font-weight: 700; color: #1f2937;">
                                {{ number_format($stats['montant_total_ttc'], 2, ',', ' ') }}
                            </h3>
                        </div>
                        <div class="stats-icon" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stats-card p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1" style="font-size: 13px; font-weight: 600;">MOYENNE</p>
                            <h3 class="mb-0" style="font-weight: 700; color: #1f2937;">
                                {{ number_format($stats['moyenne_devis'], 2, ',', ' ') }}
                            </h3>
                        </div>
                        <div class="stats-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres avancés -->
        <div class="filter-card">
            <form action="{{ route('devis.index') }}" method="GET" id="filterForm">
                <div class="row g-3">
                    <!-- Recherche -->
                    <div class="col-md-4">
                        <label class="filter-label">Recherche</label>
                        <input type="text" name="search" class="form-control search-box" 
                               placeholder="Client, N° Devis, Titre..." 
                               value="{{ request('search') }}">
                    </div>

                    <!-- Période prédéfinie -->
                    <div class="col-md-8">
                        <label class="filter-label">Période</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="period-btn {{ request('period') == 'today' ? 'active' : '' }}" 
                                    onclick="setPeriod('today')">Aujourd'hui</button>
                            <button type="button" class="period-btn {{ request('period') == 'this_week' ? 'active' : '' }}" 
                                    onclick="setPeriod('this_week')">Cette semaine</button>
                            <button type="button" class="period-btn {{ request('period') == 'this_month' ? 'active' : '' }}" 
                                    onclick="setPeriod('this_month')">Ce mois</button>
                            <button type="button" class="period-btn {{ request('period') == 'last_month' ? 'active' : '' }}" 
                                    onclick="setPeriod('last_month')">Mois dernier</button>
                            <button type="button" class="period-btn {{ request('period') == 'this_year' ? 'active' : '' }}" 
                                    onclick="setPeriod('this_year')">Cette année</button>
                        </div>
                        <input type="hidden" name="period" id="periodInput" value="{{ request('period') }}">
                    </div>

                    <!-- Date personnalisée -->
                    <div class="col-md-3">
                        <label class="filter-label">Date début</label>
                        <input type="date" name="date_from" class="form-control search-box" 
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="filter-label">Date fin</label>
                        <input type="date" name="date_to" class="form-control search-box" 
                               value="{{ request('date_to') }}">
                    </div>

                    <!-- Montant -->
                    <div class="col-md-3">
                        <label class="filter-label">Montant Min</label>
                        <input type="number" name="montant_min" class="form-control search-box" 
                               placeholder="0.00" step="0.01" value="{{ request('montant_min') }}">
                    </div>

                    <div class="col-md-3">
                        <label class="filter-label">Montant Max</label>
                        <input type="number" name="montant_max" class="form-control search-box" 
                               placeholder="0.00" step="0.01" value="{{ request('montant_max') }}">
                    </div>

                    <!-- Devise -->
                    <div class="col-md-2">
                        <label class="filter-label">Devise</label>
                        <select name="currency" class="form-select search-box">
                            <option value="">Tous</option>
                            <option value="DH" {{ request('currency') == 'DH' ? 'selected' : '' }}>DH</option>
                            <option value="EUR" {{ request('currency') == 'EUR' ? 'selected' : '' }}>EUR</option>
                        </select>
                    </div>

                    
                    <!-- Utilisateur -->
                    <div class="col-md-3">
                        <label class="filter-label">Créé par</label>
                        <select name="user_id" class="form-select search-box">
                            <option value="">Tous</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tri -->
                    <div class="col-md-3">
                        <label class="filter-label">Trier par</label>
                        <select name="sort" class="form-select search-box" onchange="this.form.submit()">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date création</option>
                            <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>Date devis</option>
                            <option value="devis_num" {{ request('sort') == 'devis_num' ? 'selected' : '' }}>Numéro</option>
                            <option value="client" {{ request('sort') == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="total_ttc" {{ request('sort') == 'total_ttc' ? 'selected' : '' }}>Montant TTC</option>
                        </select>
                    </div>

                    <!-- Direction -->
                    <div class="col-md-2">
                        <label class="filter-label">Ordre</label>
                        <select name="direction" class="form-select search-box" onchange="this.form.submit()">
                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Croissant</option>
                        </select>
                    </div>

                    <!-- Boutons -->
                    <div class="col-12 d-flex gap-2 justify-content-end">
                        <button type="submit" class="btn btn-gradient">
                            <i class="fas fa-filter me-2"></i> Filtrer
                        </button>
                        <a href="{{ route('devis.index') }}" class="btn btn-outline-secondary" 
                           style="border-radius: 8px; padding: 10px 25px;">
                            <i class="fas fa-redo me-2"></i> Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Résultats et Pagination -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="text-muted">
                Affichage de <strong>{{ $devis->firstItem() ?? 0 }}</strong> à <strong>{{ $devis->lastItem() ?? 0 }}</strong> 
                sur <strong>{{ $devis->total() }}</strong> devis
            </div>
            <div>
                <select name="per_page" class="form-select form-select-sm" style="width: auto; display: inline-block;" 
                        onchange="window.location.href='{{ route('devis.index') }}?per_page=' + this.value + '&{{ http_build_query(request()->except('per_page')) }}'">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 par page</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 par page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 par page</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 par page</option>
                </select>
            </div>
        </div>

        <!-- Tableau des devis -->
        <div class="table-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="padding: 18px;">N° DEVIS</th>
                        <th>DATE</th>
                        <th>CLIENT</th>
                        <th>TITRE</th>
                        <th>MONTANT HT</th>
                        
                        <th>MONTANT TTC</th>
                        <th>DEVISE</th>
                        <th>CRE PAR</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($devis as $d)
                        <tr>
                            <td style="padding: 15px;">
                                <span class="badge bg-dark" style="font-size: 13px; padding: 8px 12px;">
                                    #{{ $d->devis_num }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($d->date)->format('d/m/Y') }}</small>
                            </td>
                            <td style="font-weight: 600; color: #374151;">{{ $d->client }}</td>
                            <td>{{ Str::limit($d->titre, 30) }}</td>
                            <td style="font-weight: 700; color: #059669;">
                                {{ number_format($d->total_ht, 2, ',', ' ') }}
                            </td>
                            
                            <td style="font-weight: 700; color: #D32F2F; font-size: 15px;">
                                {{ number_format($d->total_ttc, 2, ',', ' ') }}
                            </td>
                            <td>
                                <span class="badge-custom {{ $d->currency == 'DH' ? 'badge-dh' : 'badge-eur' }}">
                                    {{ $d->currency }}
                                </span>
                                <td>{{ $d->user->name ?? 'Utilisateur inconnu' }}</td>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('devis.show', $d->id) }}" class="action-btn btn-view" 
                                       title="Voir PDF">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('devis.edit', $d->id) }}" class="action-btn btn-edit" 
                                       title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('devis.duplicate', $d->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn btn-duplicate" title="Dupliquer">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('factures.create_from_devis', $d->id) }}" class="btn btn-primary">Ajouter Facture</a>
                                    <form action="{{ route('devis.destroy', $d->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-btn btn-delete delete-btn" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div style="color: #9ca3af;">
                                    <i class="fas fa-inbox" style="font-size: 48px; margin-bottom: 15px;"></i>
                                    <p class="mb-0">Aucun devis trouvé</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $devis->links() }}
        </div>
    </div>

    <script>
        // Fonction pour les boutons de période
        function setPeriod(period) {
            document.getElementById('periodInput').value = period;
            document.querySelectorAll('.period-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById('filterForm').submit();
        }

        // SweetAlert pour la suppression
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');
                
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Cette action déplacera le devis vers la corbeille!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Messages de succès
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#D32F2F',
                timer: 3000
            });
        @endif
    </script>
</x-app-layout>