<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" type="image/png">

    <title>Navigation Moderne - GESTION</title>
    <style>
        :root {
            --color-primary: #C2185B;
            --color-secondary: #D32F2F;
            --color-accent: #ef4444;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Ubuntu', sans-serif;
            background-color: #f8f9fa;
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
            padding: 0;
            overflow-y: auto;
            overflow-x: hidden;
            border-right: 3px solid var(--color-primary);
            z-index: 1000;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #f1f3f5;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--color-primary), var(--color-secondary));
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, var(--color-secondary), var(--color-accent));
        }

        /* Logo section dans le sidebar */
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 25px 20px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            border-bottom: 3px solid rgba(255,255,255,0.2);
            margin-bottom: 20px;
            position: sticky;
            top: 0;
            z-index: 10;
            backdrop-filter: blur(10px);
        }

        .sidebar-logo img {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border: 2px solid white;
            object-fit: cover;
        }

        .sidebar-logo-text h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 800;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .sidebar-logo-text p {
            margin: 3px 0 0 0;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.9);
            font-weight: 500;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .sidebar-menu button::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 5px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar-menu button::after {
            content: '';
            position: absolute;
            right: -50px;
            top: 50%;
            transform: translateY(-50%);
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            border-radius: 50%;
            transition: all 0.4s ease;
            opacity: 0;
        }

        .sidebar-menu button:hover {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            transform: translateX(8px) scale(1.02);
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
        }

        .sidebar-menu button:hover::before {
            transform: scaleY(1);
        }

        .sidebar-menu button:hover::after {
            right: -10px;
            opacity: 1;
        }

        .sidebar-menu button:hover #i-fetch {
            transform: rotate(180deg);
        }

        .sidebar-menu button.active {
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            color: white;
            box-shadow: 0 6px 20px rgba(194, 24, 91, 0.4);
            transform: translateX(5px);
        }

        .sidebar-menu button.active::before {
            transform: scaleY(1);
        }

        .sidebar-menu button i {
            font-size: 1.3rem;
            width: 28px;
            text-align: center;
            flex-shrink: 0;
            transition: transform 0.3s ease;
        }

        .sidebar-menu button:hover i {
            transform: scale(1.1);
        }

        .sidebar-menu span {
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            flex: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-menu button #i-fetch {
            font-size: 0.75rem;
            margin-left: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-menu a {
            text-decoration: none;
            color: inherit;
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
        }

        /* Section dividers */
        .sidebar-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(194, 24, 91, 0.3), transparent);
            margin: 20px 10px;
            position: relative;
        }

        .sidebar-divider::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 8px;
            height: 8px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(194, 24, 91, 0.5);
        }

        .sidebar-section-title {
            padding: 15px 20px 10px 20px;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--color-secondary);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-section-title::before {
            content: '';
            width: 4px;
            height: 16px;
            background: linear-gradient(180deg, var(--color-primary), var(--color-secondary));
            border-radius: 2px;
        }

        /* Dropdown container */
        .dropdown-container {
            width: 100%;
            padding: 0 15px 15px 15px;
            margin-top: auto;
        }

        .dropdown-container button {
            background: linear-gradient(135deg, var(--color-secondary), var(--color-accent));
            color: white;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.4);
        }

        .dropdown-container button:hover {
            background: linear-gradient(135deg, var(--color-accent), var(--color-secondary));
            transform: translateX(8px) scale(1.02);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.5);
        }

        .dropdown-container .p-fetch {
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            :root {
                --sidebar-width: 240px;
            }
            
            .sidebar-menu button {
                padding: 13px 15px;
            }
            
            .sidebar-menu span {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 992px) {
            :root {
                --sidebar-width: 70px;
            }
            
            .sidebar-logo-text,
            .sidebar-menu span,
            .sidebar-section-title,
            .p-fetch {
                display: none;
            }
            
            .sidebar-menu button,
            .dropdown-container button {
                justify-content: center;
                padding: 14px;
            }
            
            .sidebar-menu button i {
                margin: 0;
                font-size: 1.5rem;
            }
            
            .sidebar-menu button #i-fetch {
                display: none;
            }

            .sidebar-logo {
                justify-content: center;
                padding: 20px 10px;
            }

            .sidebar-divider {
                margin: 15px 5px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                z-index: 9999;
                transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .sidebar.active {
                left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 9998;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.active {
                display: block;
                opacity: 1;
            }

            .mobile-menu-toggle {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 10000;
                background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
                color: white;
                border: none;
                width: 50px;
                height: 50px;
                border-radius: 12px;
                font-size: 1.5rem;
                cursor: pointer;
                box-shadow: 0 4px 15px rgba(194, 24, 91, 0.4);
                transition: all 0.3s ease;
            }

            .mobile-menu-toggle:hover {
                transform: scale(1.1);
                box-shadow: 0 6px 20px rgba(194, 24, 91, 0.5);
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-toggle {
                display: none;
            }
        }

        /* Animation d'entrée */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .sidebar-menu button {
            animation: slideIn 0.3s ease forwards;
        }

        .sidebar-menu button:nth-child(1) { animation-delay: 0.05s; }
        .sidebar-menu button:nth-child(2) { animation-delay: 0.1s; }
        .sidebar-menu button:nth-child(3) { animation-delay: 0.15s; }
        .sidebar-menu button:nth-child(4) { animation-delay: 0.2s; }
        .sidebar-menu button:nth-child(5) { animation-delay: 0.25s; }
        .sidebar-menu button:nth-child(6) { animation-delay: 0.3s; }
        .sidebar-menu button:nth-child(7) { animation-delay: 0.35s; }
        .sidebar-menu button:nth-child(8) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <nav class="sidebar" id="sidebar">
        <!-- Logo Section -->
        <div class="sidebar-logo">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" alt="Logo">
            <div class="sidebar-logo-text">
                <h3>GESTION</h3>
                <p>Système complet</p>
            </div>
        </div>

        <div class="sidebar-menu">
            <!-- Accueil -->
            <button type="button" class="active">
                <a href="{{ route('dashboard') }}">
                    <i class='bx bx-home'></i>
                    <span>Accueil</span>
                </a>
            </button>

            <!-- Responsable Admin -->
            <button type="button">
                <i class='bx bx-group'></i>
                <span><a href="{{ route('users.index') }}">Responsable Admin</a></span>
            </button>

            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Documents</div>

            <!-- Attestations -->
            <button type="button" id="attestationsButton">
                <i class="fas fa-certificate"></i>
                <span>Attestations <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>
            
            <!-- Reçus -->
            <button type="button" id="recuButton">
                <i class="fas fa-receipt"></i>
                <span>Les Reçus <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <!-- Devis -->
            <button type="button" id="devisButton">
                <i class="fas fa-file-invoice"></i>
                <span>Les Devis <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <!-- Bon de Commande -->
            <button type="button" id="bonDeCommandeButton">
                <i class="fas fa-file-contract"></i>
                <span>Bon.Commande <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <!-- Factures -->
            <button type="button" id="facturButto">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Les Factures <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <!-- Bon de Livraisons -->
            <button type="button">
                <i class="fas fa-shipping-fast"></i>
                <span><a href="{{ route('bon_livraisons.index') }}">Bon de Livraisons</a></span>
            </button>
            
            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Produits & Stocks</div>

            <!-- Produits UCGS -->
            <button type="button" id="produitButto">
                <i class="fas fa-box"></i>
                <span>Produit UCGS <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Administration</div>

            <!-- Rôles -->
            @can('role-list')
            <button>
                <i class="fas fa-user-shield"></i>
                <span><a href="{{ route('roles.index') }}">Rôles</a></span>
            </button>
            @endcan

            <!-- Backup -->
            @can('role-list')
            <button>
                <i class="fas fa-download"></i>
                <span><a href="{{ route('download.backup') }}">Backup</a></span>
            </button>
            @endcan
        </div>

        {{-- <!-- Profile Button -->
        <div class="dropdown-container">
            <button class="icon" id="profileButton">
                <i class='bx bx-user'></i>
                <p class="p-fetch">Profile <i id="i-fetch" class="fa fa-chevron-down"></i></p>
            </button>
            <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
                @csrf
            </form>
        </div> --}}
    </nav>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Configuration des boutons et leurs liens
        const buttons = [
            { 
                id: 'attestationsButton', 
                title: 'Liste des Attestations', 
                links: [
                    { href: '{{ route('attestations.index') }}', label: 'Attestations de Stage', icon: 'fas fa-file-alt', color: '#C2185B' },
                    { href: '{{ route('attestations_formation.index') }}', label: 'Attestations de Formation', icon: 'fas fa-graduation-cap', color: '#35c218' },
                    { href: '{{ route('attestations_allinone.index') }}', label: 'Attestations ALL IN ONE', icon: 'fas fa-certificate', color: '#5683cd' }
                ]
            },
            { 
                id: 'recuButton', 
                title: 'Liste des Reçus', 
                links: [
                    { href: '{{ route('reussites.index') }}', label: 'Reçu de Stage', icon: 'fas fa-receipt', color: '#C2185B' },
                    { href: '{{ route('reussitesf.index') }}', label: 'Reçu de Formation', icon: 'fas fa-graduation-cap', color: '#4CAF50' },
                    { href: '{{ route('ucgs.index') }}', label: 'Reçu de UCGS', icon: 'fas fa-shield-alt', color: '#ef4444' }
                ]
            },
            { 
                id: 'devisButton', 
                title: 'Liste des Devis', 
                links: [
                    { href: '{{ route('devis.index') }}', label: 'Devis de Projet', icon: 'fas fa-file-invoice', color: '#C2185B' },
                    { href: '{{ route('devisf.index') }}', label: 'Devis de Formation', icon: 'fas fa-file-contract', color: '#4CAF50' }
                ]
            },
            {
                id: 'bonDeCommandeButton',
                title: 'Bon de Commande',
                links: [
                    { href: '{{ route('bon_de_commande.index') }}', label: 'Bon de Commande Reçus', icon: 'fas fa-file-contract', color: '#FF9800' },
                    { href: '{{ route('bon_commande_r.index') }}', label: 'Bon de Commande Envoyés', icon: 'fas fa-paper-plane', color: '#C2185B' },
                ]
            },
            { 
                id: 'facturButto', 
                title: 'Liste des Factures', 
                links: [
                    { href: '{{ route('factures.index') }}', label: 'Factures de Projet', icon: 'fas fa-file-invoice-dollar', color: '#C2185B' },
                    { href: '{{ route('facturefs.index') }}', label: 'Factures de Formation', icon: 'fas fa-money-check-alt', color: '#4CAF50' }
                ]
            },
            { 
                id: 'produitButto', 
                title: 'Liste des Produits', 
                links: [
                    { href: '{{ route('categories.index') }}', label: 'Catégories de Produits', icon: 'fas fa-boxes-stacked', color: '#C2185B' },
                    { href: '{{ route('produits.index') }}', label: 'Les Produits', icon: 'fas fa-box', color: '#ffc107' },
                    { href: '{{ route('achats.index') }}', label: 'Les Achats', icon: 'fas fa-cart-arrow-down', color: '#0a58ca' },
                    { href: '{{ route('ventes.index') }}', label: 'Les Ventes', icon: 'fas fa-cash-register', color: '#62fd0d' },
                    { href: '{{ route('produits.totals') }}', label: 'Totaux', icon: 'fas fa-calculator', color: '#754E1A' },
                ]
            },
            { 
                id: 'profileButton', 
                title: 'Options du Profile', 
                links: [
                    { href: '{{ route('profile.edit') }}', label: 'Mon Profile', icon: 'bx bx-user-circle', color: '#C2185B' },
                    { 
                        href: '#', 
                        label: 'Déconnexion', 
                        icon: 'bx bx-log-out', 
                        color: '#ef4444', 
                        onclick: 'showLogoutConfirmation()'
                    }
                ]
            },
        ];

        // Ajouter les événements click sur les boutons
        buttons.forEach(button => {
            const btn = document.getElementById(button.id);
            if (btn) {
                btn.addEventListener('click', () => {
                    Swal.fire({
                        title: `<strong style="background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">${button.title}</strong>`,
                        html: button.links.map(link => `
                            <a href="${link.href}" ${link.onclick ? `onclick="${link.onclick}"` : ''} 
                               style="display:flex; align-items:center; gap:15px; margin-bottom: 12px; color: ${link.color}; 
                                      font-size: 16px; text-decoration: none; background: linear-gradient(135deg, ${link.color}15, ${link.color}25); 
                                      padding: 14px 18px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); 
                                      transition: all 0.3s ease; border-left: 4px solid ${link.color};"
                               onmouseover="this.style.transform='translateX(8px)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.2)';"
                               onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">
                                <i class="${link.icon}" style="color: ${link.color}; font-size: 20px; width: 24px;"></i> 
                                <span style="flex:1; text-align:left; font-weight:600;">${link.label}</span>
                                <i class="fas fa-chevron-right" style="font-size: 14px; opacity: 0.6;"></i>
                            </a>
                        `).join(''),
                        showCloseButton: true,
                        showConfirmButton: false,
                        background: '#ffffff',
                        width: '500px',
                        padding: '30px',
                        customClass: {
                            popup: 'animated-popup',
                            closeButton: 'custom-close-btn'
                        },
                        didOpen: () => {
                            const popup = document.querySelector('.swal2-popup');
                            popup.style.borderRadius = '20px';
                            popup.style.boxShadow = '0 10px 40px rgba(0, 0, 0, 0.15)';
                            popup.style.border = '2px solid #C2185B';
                        }
                    });
                });
            }
        });

        // Fonction de confirmation de déconnexion
        function showLogoutConfirmation() {
            Swal.fire({
                title: '<strong>Êtes-vous sûr ?</strong>',
                html: '<p style="color: #6c757d; margin-top: 10px;">Vous allez vous déconnecter du système !</p>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D32F2F',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-out-alt"></i> Oui, déconnexion !',
                cancelButtonText: '<i class="fas fa-times"></i> Annuler',
                customClass: {
                    confirmButton: 'custom-confirm-btn',
                    cancelButton: 'custom-cancel-btn'
                },
                buttonsStyling: false,
                didOpen: () => {
                    const style = document.createElement('style');
                    style.textContent = `
                        .custom-confirm-btn, .custom-cancel-btn {
                            padding: 12px 30px;
                            border-radius: 10px;
                            font-weight: 600;
                            font-size: 14px;
                            margin: 5px;
                            transition: all 0.3s ease;
                            border: none;
                            cursor: pointer;
                        }
                        .custom-confirm-btn {
                            background: linear-gradient(135deg, #D32F2F, #ef4444);
                            color: white;
                        }
                        .custom-confirm-btn:hover {
                            transform: translateY(-2px);
                            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.4);
                        }
                        .custom-cancel-btn {
                            background: #e9ecef;
                            color: #495057;
                        }
                        .custom-cancel-btn:hover {
                            background: #dee2e6;
                            transform: translateY(-2px);
                        }
                    `;
                    document.head.appendChild(style);
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }

        // Mobile menu toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }

        // Highlight active page
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.closest('button').classList.add('active');
            }
        });
    </script>
</body>
</html>