<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --color-primary: #C2185B;
            --color-secondary: #D32F2F;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, #ffffff 0%, #f8f9fa 100%);
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            border-right: 3px solid var(--color-primary);
            z-index: 999;
            margin-right: 30px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--color-primary), var(--color-secondary));
            border-radius: 10px;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 25px 20px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            border-bottom: 3px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
        }

        .sidebar-logo img {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border: 2px solid white;
        }

        .sidebar-logo-text h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .sidebar-logo-text p {
            margin: 3px 0 0 0;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.9);
        }

        .sidebar-menu {
            padding: 0 15px 20px 15px;
            flex: 1;
        }

        .sidebar-menu button {
            width: 100%;
            background: white;
            color: #495057;
            border: none;
            padding: 15px 18px;
            text-align: left;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .sidebar-menu button:hover {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            transform: translateX(8px);
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
        }

        .sidebar-menu button.active {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
        }

        .sidebar-menu button i {
            font-size: 1.3rem;
            width: 28px;
            text-align: center;
        }

        .sidebar-menu span {
            font-size: 0.9rem;
            font-weight: 600;
            flex: 1;
        }

        .sidebar-menu a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        .sidebar-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(194, 24, 91, 0.3), transparent);
            margin: 20px 10px;
        }

        .sidebar-section-title {
            padding: 15px 20px 10px 20px;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--color-secondary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                transform: translateX(-100%);
                margin-right: 0;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" alt="Logo">
            <div class="sidebar-logo-text">
                <h3>GESTION STOCK</h3>
                <p>Système de gestion</p>
            </div>
        </div>

        <div class="sidebar-menu">
            {{-- ✅ Dashboard - Tout le monde --}}
            @can('dashboard-view')
            <button type="button">
                <a href="{{ route('dashboardstock') }}">
                    <i class='bx bx-home'></i>
                    <span>Tableau de Bord</span>
                </a>
            </button>
            @endcan

            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">📦 Gestion Stock</div>

            {{-- ✅ Catégories - Gérant, Magasinier --}}
            @can('category-list')
            <button type="button">
                <a href="{{ route('categories.index') }}">
                    <i class="fas fa-boxes-stacked"></i>
                    <span>Catégories</span>
                </a>
            </button>
            @endcan

            {{-- ✅ Produits - Tous sauf rôle sans permissions --}}
            @can('produit-list')
            <button type="button">
                <a href="{{ route('produits.index') }}">
                    <i class="fas fa-box"></i>
                    <span>Produits</span>
                </a>
            </button>
            @endcan

            {{-- ✅ Achats - Gérant, Magasinier --}}
            @can('achat-list')
            <button type="button">
                <a href="{{ route('achats.index') }}">
                    <i class="fas fa-cart-shopping"></i>
                    <span>Achats</span>
                </a>
            </button>
            @endcan

            {{-- ✅ Stock Movements - Gérant, Magasinier --}}
            @can('stock-movement-list')
            <button type="button">
                <a href="{{ route('stock.movements.index') }}">
                    <i class="fas fa-truck-ramp-box"></i>
                    <span>Mouvements Stock</span>
                </a>
            </button>
            @endcan

            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">💰 Ventes & Paiements</div>

            {{-- ✅ Reçus - Gérant, Vendeur --}}
            @can('recu-list')
            <button type="button">
                <a href="{{ route('recus.index') }}">
                    <i class="fas fa-receipt"></i>
                    <span>Reçus de Vente</span>
                </a>
            </button>
            @endcan

            {{-- ✅ Paiements - Gérant, Comptable --}}
            @can('paiement-list')
            <button type="button">
                <a href="{{ route('paiements.index') }}">
                    <i class="fas fa-credit-card"></i>
                    <span>Paiements</span>
                </a>
            </button>
            @endcan

            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">📊 Rapports</div>

            {{-- ✅ Statistiques Produits - Gérant, Comptable --}}
            @can('produit-rapport')
            <button type="button">
                <a href="{{ route('produits.totals') }}">
                    <i class="fas fa-chart-pie"></i>
                    <span>Statistiques</span>
                </a>
            </button>
            @endcan

            {{-- ✅ Rapport Ventes - Gérant, Comptable --}}
            {{-- @can('rapport-ventes')
            <button type="button">
                <a href="{{ route('recus.statistiques') }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Rapport Ventes</span>
                </a>
            </button>
            @endcan --}}

            {{-- ✅ Rapport Paiements - Gérant, Comptable --}}
            @can('paiement-rapport')
            <button type="button">
                <a href="{{ route('paiements.rapport') }}">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Rapport Paiements</span>
                </a>
            </button>
            @endcan

            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">⚙️ Administration</div>

            {{-- ✅ Utilisateurs - Admin uniquement --}}
            @can('user-list')
            <button type="button">
                <a href="{{ route('users.index') }}">
                    <i class='bx bx-group'></i>
                    <span>Utilisateurs</span>
                </a>
            </button>
            @endcan

            {{-- ✅ Rôles - Admin uniquement --}}
            @can('role-list')
            <button type="button">
                <a href="{{ route('roles.index') }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Rôles & Permissions</span>
                </a>
            </button>
            @endcan

            {{-- ✅ Backup - Admin uniquement --}}
            @can('role-list')
            <button type="button">
                <a href="{{ route('download.backup') }}">
                    <i class="fas fa-download"></i>
                    <span>Sauvegarde</span>
                </a>
            </button>
            @endcan
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Highlight active page
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.closest('button').classList.add('active');
            }
        });
        
        // Mobile sidebar toggle
        document.getElementById('sidebarOverlay')?.addEventListener('click', () => {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        });
    </script>
</body>
</html>