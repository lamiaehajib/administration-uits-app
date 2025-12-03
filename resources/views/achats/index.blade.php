<x-app-layout>
    <style>
        /* ===== PAGE ACHATS - DESIGN MODERNE ===== */
        
        /* Header Achats */
        .achats-header {
            background: linear-gradient(135deg, #D32F2F 0%, #C2185B 100%);
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(13, 71, 161, 0.3);
            position: relative;
            overflow: hidden;
        }

        .achats-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .achats-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
        }

        .achats-header h1 {
            color: white;
            font-weight: 700;
            font-size: 2.5rem;
            margin: 0 0 10px 0;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .achats-header p {
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
            font-size: 1.1rem;
            position: relative;
            z-index: 1;
        }

        /* Stats Cards Achats */
        .achats-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .achat-stat-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .achat-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: currentColor;
            opacity: 0.05;
            border-radius: 50%;
            transform: translate(30%, -30%);
        }

        .achat-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .achat-stat-card.blue { 
            border-left: 4px solid #2196F3; 
            color: #2196F3; 
        }

        .achat-stat-card.green { 
            border-left: 4px solid #4CAF50; 
            color: #4CAF50; 
        }

        .achat-stat-card.orange { 
            border-left: 4px solid #FF9800; 
            color: #FF9800; 
        }

        .achat-stat-card.purple { 
            border-left: 4px solid #9C27B0; 
            color: #9C27B0; 
        }

        .stat-achat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            opacity: 0.8;
        }

        .stat-achat-value {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0 5px 0;
            color: #333;
        }

        .stat-achat-label {
            font-size: 0.9rem;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Action Bar */
        .action-bar-achats {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box-achats {
            flex: 1;
            min-width: 250px;
            position: relative;
        }

        .search-box-achats input {
            width: 100%;
            padding: 12px 45px 12px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-box-achats input:focus {
            outline: none;
            border-color: #D32F2F;
            box-shadow: 0 0 0 3px rgba(21, 101, 192, 0.1);
        }

        .search-box-achats i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .btn-achat {
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-achat-primary {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            color: white;
        }

        .btn-achat-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 71, 161, 0.3);
            color: white;
        }

        .btn-achat-outline {
            background: white;
            color: #D32F2F;
            border: 2px solid #D32F2F;
        }

        .btn-achat-outline:hover {
            background: #D32F2F;
            color: white;
            transform: translateY(-2px);
        }

        /* Table Card */
        .achats-table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .achats-table {
            margin: 0;
            width: 100%;
        }

        .achats-table thead {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
        }

        .achats-table thead th {
            color: white;
            font-weight: 600;
            padding: 18px 15px;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border: none;
            text-align: center;
        }

        .achats-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .achats-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
        }

        .achats-table tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            color: #333;
            text-align: center;
        }

        /* Product Info in Table */
        .product-table-info {
            display: flex;
            align-items: center;
            gap: 12px;
            text-align: left;
        }

        .product-table-avatar {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .product-table-details h6 {
            margin: 0 0 4px 0;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }

        .product-table-details small {
            color: #999;
            font-size: 0.85rem;
        }

        /* Badges */
        .badge-achat {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
        }

        .badge-achat-info {
            background: #E3F2FD;
            color: #D32F2F;
        }

        .badge-achat-success {
            background: #E8F5E9;
            color: #2E7D32;
        }

        /* Fournisseur Display */
        .fournisseur-badge {
            background: linear-gradient(135deg, #FFF3E0, #FFE0B2);
            padding: 8px 16px;
            border-radius: 10px;
            color: #E65100;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Quantité Display */
        .quantite-box {
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            padding: 10px 18px;
            border-radius: 10px;
            color: #2E7D32;
            font-weight: 700;
            font-size: 1.1rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Prix Display */
        .prix-display {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .prix-unitaire {
            color: #D32F2F;
        }

        .prix-total {
            color: #4CAF50;
            font-size: 1.2rem;
        }

        /* Action Buttons */
        .action-btns-achats {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-icon-achat {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-icon-achat:hover {
            transform: translateY(-2px);
        }

        .btn-edit-achat { 
            background: #FFF3E0; 
            color: #F57C00; 
        }

        .btn-edit-achat:hover { 
            background: #F57C00; 
            color: white; 
        }

        .btn-delete-achat { 
            background: #FFEBEE; 
            color: #C62828; 
        }

        .btn-delete-achat:hover { 
            background: #C62828; 
            color: white; 
        }

        /* Date Badge */
        .date-badge {
            background: #F5F5F5;
            padding: 6px 12px;
            border-radius: 8px;
            color: #666;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* Empty State */
        .empty-achats {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-achats i {
            font-size: 4rem;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-achats h4 {
            color: #999;
            margin-bottom: 10px;
        }

        .empty-achats p {
            color: #bbb;
        }

        /* Pagination */
        .pagination-achats {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .pagination-achats .page-link {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 8px 16px;
            color: #666;
            transition: all 0.3s ease;
        }

        .pagination-achats .page-link:hover {
            border-color: #D32F2F;
            background: #D32F2F;
            color: white;
        }

        .pagination-achats .active .page-link {
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            border-color: #D32F2F;
            color: white;
        }

        /* Tooltip Info */
        .info-tooltip {
            position: relative;
            display: inline-block;
            cursor: help;
        }

        .info-tooltip:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.8rem;
            white-space: nowrap;
            z-index: 1000;
            margin-bottom: 5px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .achats-header h1 { 
                font-size: 1.8rem; 
            }
            
            .achats-stats { 
                grid-template-columns: 1fr; 
            }
            
            .action-bar-achats { 
                flex-direction: column; 
            }
            
            .search-box-achats { 
                width: 100%; 
            }

            .product-table-info {
                flex-direction: column;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .achats-header {
                padding: 30px 20px;
            }

            .stat-achat-value {
                font-size: 1.5rem;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Header -->
        <div class="achats-header">
            <h1>
                <i class="fas fa-shopping-cart"></i>
                Gestion des Achats
            </h1>
            <p>Suivez et gérez tous vos achats et approvisionnements</p>
        </div>

        <!-- Statistics Cards -->
        <div class="achats-stats">
            <div class="achat-stat-card blue">
                <div class="stat-achat-icon"><i class="fas fa-receipt"></i></div>
                <div class="stat-achat-value">{{ $achats->total() }}</div>
                <div class="stat-achat-label">Total Achats</div>
            </div>
            
            <div class="achat-stat-card green">
                <div class="stat-achat-icon"><i class="fas fa-boxes"></i></div>
                <div class="stat-achat-value">{{ number_format($achats->sum('quantite')) }}</div>
                <div class="stat-achat-label">Unités Achetées</div>
            </div>
            
            <div class="achat-stat-card orange">
                <div class="stat-achat-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="stat-achat-value">{{ number_format($achats->sum('total_achat'), 0) }} DH</div>
                <div class="stat-achat-label">Montant Total</div>
            </div>
            
            <div class="achat-stat-card purple">
                <div class="stat-achat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-achat-value">{{ $achats->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
                <div class="stat-achat-label">Achats ce Mois</div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="action-bar-achats">
            <form action="{{ route('achats.index') }}" method="GET" class="search-box-achats">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="🔍 Rechercher par produit..." 
                    value="{{ request('search') }}"
                >
                <i class="fas fa-search"></i>
            </form>

            <a href="{{ route('achats.create') }}" class="btn-achat btn-achat-primary">
                <i class="fas fa-plus-circle"></i> Nouvel Achat
            </a>

            <a href="{{ route('produits.index') }}" class="btn-achat btn-achat-outline">
                <i class="fas fa-box"></i> Produits
            </a>
        </div>

        <!-- Achats Table -->
        <div class="achats-table-card">
            <div class="table-responsive">
                <table class="achats-table table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Fournisseur</th>
                            <th>N° Bon</th>
                            <th>Date</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($achats as $achat)
                        <tr>
                            <td>
                                <div class="product-table-info">
                                    <div class="product-table-avatar">
                                        {{ strtoupper(substr($achat->produit->nom, 0, 2)) }}
                                    </div>
                                    <div class="product-table-details">
                                        <h6>{{ $achat->produit->nom }}</h6>
                                        <small>{{ $achat->produit->reference }}</small>
                                    </div>
                                </div>
                            </td>
                            
                            <td>
                                @if($achat->fournisseur)
                                    <span class="fournisseur-badge">
                                        <i class="fas fa-truck"></i>
                                        {{ $achat->fournisseur }}
                                    </span>
                                @else
                                    <span class="text-muted">
                                        <i class="fas fa-minus"></i> Non spécifié
                                    </span>
                                @endif
                            </td>
                            
                            <td>
                                @if($achat->numero_bon)
                                    <span class="badge-achat badge-achat-info">
                                        <i class="fas fa-hashtag"></i> {{ $achat->numero_bon }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            
                            <td>
                                <span class="date-badge">
                                    <i class="fas fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($achat->date_achat)->format('d/m/Y') }}
                                </span>
                            </td>
                            
                            <td>
                                <span class="quantite-box">
                                    <i class="fas fa-cube"></i>
                                    {{ number_format($achat->quantite) }}
                                </span>
                            </td>
                            
                            <td>
                                <span class="prix-display prix-unitaire">
                                    {{ number_format($achat->prix_achat, 2) }} DH
                                </span>
                            </td>
                            
                            <td>
                                <span class="prix-display prix-total">
                                    {{ number_format($achat->total_achat, 2) }} DH
                                </span>
                            </td>
                            
                            <td>
                                <div class="action-btns-achats">
                                    <a 
                                        href="{{ route('achats.edit', $achat->id) }}" 
                                        class="btn-icon-achat btn-edit-achat"
                                        data-tooltip="Modifier"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <button 
                                        onclick="confirmDeleteAchat({{ $achat->id }})" 
                                        class="btn-icon-achat btn-delete-achat"
                                        data-tooltip="Supprimer"
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-achats">
                                    <i class="fas fa-shopping-cart"></i>
                                    <h4>Aucun achat trouvé</h4>
                                    <p>Commencez par enregistrer votre premier achat</p>
                                    <a href="{{ route('achats.create') }}" class="btn-achat btn-achat-primary" style="margin-top: 15px;">
                                        <i class="fas fa-plus"></i> Créer un Achat
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-achats">
                {{ $achats->links() }}
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Script -->
    <script>
        function confirmDeleteAchat(id) {
            Swal.fire({
                title: 'Supprimer cet achat?',
                html: '<strong style="color: #d32f2f;">⚠️ Attention:</strong><br>Le stock sera automatiquement ajusté lors de la suppression.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C62828',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Oui, supprimer',
                cancelButtonText: '<i class="fas fa-times"></i> Annuler',
                reverseButtons: true,
                customClass: {
                    confirmButton: 'btn-achat btn-achat-primary',
                    cancelButton: 'btn-achat btn-achat-outline'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/achats/${id}`;
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Success/Error Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: "{{ session('error') }}",
                timer: 4000,
                showConfirmButton: true
            });
        @endif
    </script>
</x-app-layout>