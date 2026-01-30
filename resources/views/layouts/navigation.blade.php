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

        /* ======================================= */
        /* SIDEBAR OVERLAY (pour mobile)           */
        /* ======================================= */
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

        /* ======================================= */
        /* SIDEBAR                                 */
        /* ======================================= */
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
            z-index: 999;
            margin-right: 30px;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

        /* ======================================= */
        /* NOUVEAU RESPONSIVE : ENTRE MOBILE & DESKTOP (769px - 992px) */
        /* Garde l'icône et le texte, mais réduit la taille du sidebar */
        /* ======================================= */
        @media (min-width: 769px) and (max-width: 992px) {
            :root {
                --sidebar-width: 200px;
            }
            
            .sidebar {
                width: var(--sidebar-width);
                margin-right: 15px;
            }

            .sidebar-logo-text {
                display: none;
            }
            
            .sidebar-menu span {
                display: block;
                margin-left: 0;
                flex: 1;
                text-align: left;
            }

            .sidebar-section-title,
            .p-fetch {
                display: none;
            }
            
            .sidebar-menu button {
                justify-content: flex-start;
                gap: 10px;
                padding: 15px 12px;
                transform: translateX(0);
            }

            .sidebar-menu button:hover {
                transform: translateX(5px) scale(1.02);
            }
            
            .sidebar-menu button i {
                margin: 0;
                font-size: 1.3rem;
                width: 28px;
            }
            
            .sidebar-menu button #i-fetch {
                display: inline-block;
                margin-left: auto;
                font-size: 0.75rem;
            }

            .sidebar-logo {
                justify-content: flex-start;
                padding: 25px 15px;
            }
        }

        /* ======================================= */
        /* RESPONSIVE : MOBILE (max 768px)         */
        /* ======================================= */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 999;
                transform: translateX(-100%);
                width: 280px;
                margin-right: 0;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-logo-text,
            .sidebar-menu span,
            .sidebar-section-title {
                display: block;
            }
            
            .sidebar-menu button {
                justify-content: flex-start;
                gap: 15px;
                padding: 15px 18px;
                transform: translateX(0);
            }
            
            .sidebar-menu button:hover {
                transform: translateX(8px) scale(1.02);
            }

            .sidebar-menu button i {
                width: 28px;
            }

            .sidebar-logo {
                justify-content: flex-start;
                padding: 25px 20px;
            }

            .sidebar-divider {
                margin: 20px 10px;
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
                <h3>GESTION</h3>
                <p>Système complet</p>
            </div>
        </div>

        <div class="sidebar-menu">
           @if(auth()->user()->role !== 'Admin' && auth()->user()->role !== 'Admin2')
    <button type="button" class="active">
        <a href="{{ route('dashboard') }}">
            <i class='bx bx-home'></i>
            <span>Accueil</span>
        </a>
    </button>
@endif

            <button type="button">
                <a href="{{ route('users.index') }}">
                    <i class='bx bx-group'></i>
                    <span>Responsable Admin</span>
                </a>
            </button>

            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Documents</div>

            <button type="button" id="attestationsButton">
                <i class="fas fa-certificate"></i>
                <span>Attestations <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>
            
            <button type="button" id="recuButton">
                <i class="fas fa-receipt"></i>
                <span>Les Reçus <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <button type="button" id="devisButton">
                <i class="fas fa-file-invoice"></i>
                <span>Les Devis <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <button type="button" id="bonDeCommandeButton">
                <i class="fas fa-file-contract"></i>
                <span>Bon.Commande <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <button type="button" id="facturButto">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Les Factures <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <button type="button">
                <a href="{{ route('bon_livraisons.index') }}">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Bon de Livraisons</span>
                </a>
            </button>
             @can('role-list')
             <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Gestion Financière</div>
            <button type="button" id="depensesButton">
    <i class="fas fa-wallet"></i>
    <span>Dépenses UITS <i id="i-fetch" class="fa fa-chevron-down"></i></span>
</button>
@endcan
            @can('produit-list')
            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Produits & Stocks</div>

            <button type="button" id="produitButto">
                <i class="fas fa-box"></i>
                <span>Produit UCGS <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>
            @endcan

          

            @can('role-list')
            <div class="sidebar-divider"></div>
            <div class="sidebar-section-title">Administration</div>

            <button>
                <a href="{{ route('roles.index') }}">
                    <i class="fas fa-user-shield"></i>
                    <span>Rôles</span>
                </a>
            </button>
            @endcan

            @can('role-list')
            <button>
                <a href="{{ route('download.backup') }}">
                    <i class="fas fa-download"></i>
                    <span>Backup</span>
                </a>
            </button>
            @endcan
        </div>
    </nav>

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
                    { href: '{{ route('reussitesf.index') }}', label: 'Reçu de Formation', icon: 'fas fa-graduation-cap', color: '#4CAF50' }
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
                    { href: '{{ route('facturefs.index') }}', label: 'Factures de Formation', icon: 'fas fa-money-check-alt', color: '#4CAF50' },
                    { href: '{{ route('factures-recues.index') }}', label: 'Factures Reçues', icon: 'fas fa-file-download', color: '#3F51B5' }
                ]
            },
            { 
                id: 'produitButto', 
                title: 'Liste des Produits', 
                links: [
                    { href: '{{ route('repair-tickets.index') }}', label: 'SERVICE DE RÉPARATION', icon: 'fas fa-shield-alt', color: '#4455efff' },
                    { 
                        href: '{{ route('categories.index') }}', 
                        label: 'Catégories de Produits', 
                        icon: 'fas fa-boxes-stacked', 
                        color: '#C2185B' 
                    },
                    { 
                        href: '{{ route('produits.index') }}', 
                        label: 'Gestion des Produits', 
                        icon: 'fas fa-box', 
                        color: '#FF9800' 
                    },
                    @can('achat-list')
                    { 
                        href: '{{ route('achats.index') }}', 
                        label: 'Historique des Achats', 
                        icon: 'fas fa-cart-shopping', 
                        color: '#2196F3' 
                    },
                    @endcan
                    @can('produit-rapport')
                    { 
                        href: '{{ route('charges.index') }}', 
                        label: 'Gestion des Charges', 
                        icon: 'fas fa-file-invoice-dollar', 
                        color: '#D32F2F' 
                    },
                    @endcan
                    { 
                        href: '{{ route('recus.index') }}', 
                        label: 'Reçus de Paiement', 
                        icon: 'fas fa-receipt', 
                        color: '#4CAF50' 
                    },
                    @can('paiement-list')
                    { 
                        href: '{{ route('paiements.index') }}', 
                        label: 'Gestion des Paiements', 
                        icon: 'fas fa-credit-card', 
                        color: '#9C27B0' 
                    },
                    @endcan
                    { 
                        href: '{{ route('stock.movements.index') }}', 
                        label: 'Mouvements de Stock', 
                        icon: 'fas fa-truck-ramp-box', 
                        color: '#00BCD4' 
                    },
                    @can('produit-rapport')
                    { 
                        href: '{{ route('produits.totals') }}', 
                        label: 'Totaux et Statistiques', 
                        icon: 'fas fa-chart-pie', 
                        color: '#F44336' 
                    },
                    @endcan
                    @can('produit-rapport')
                    { 
                        href: '{{ route('benefices.dashboard') }}', 
                        label: 'Tableau de Bord Bénéfices', 
                        icon: 'fas fa-hand-holding-dollar', 
                        color: '#2E7D32' 
                    }
                    @endcan



                    
                ]
            },

            {
    id: 'depensesButton',
    title: 'Dépenses UITS',
    links: [
        { 
            href: '{{ route('depenses.dashboard') }}',
            label: 'Tableau de Bord UITS',
            icon: 'fas fa-chart-line',
            color: '#2196F3'
        },
        { 
            href: '{{ route('depenses.fixes.index') }}', 
            label: 'Dépenses Fixes', 
            icon: 'fas fa-building', 
            color: '#C2185B' 
        },
        { 
            href: '{{ route('depenses.variables.index') }}', 
            label: 'Dépenses Variables', 
            icon: 'fas fa-random', 
            color: '#FF9800' 
        },
        { 
            href: '{{ route('depenses.budgets.index') }}', 
            label: 'Budgets Mensuels', 
            icon: 'fas fa-chart-pie', 
            color: '#4CAF50' 
        },
        { 
            href: '{{ route('depenses.salaires.historique') }}', 
            label: 'Historique des Salaires', 
            icon: 'fas fa-money-check-alt', 
            color: '#3F51B5' 
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
                            <a href="${link.href}" 
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

        // Highlight active page
        const currentPath = window.location.pathname;
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.closest('button').classList.add('active');
            }
        });
        
        // Fonctionnalité pour la sidebar mobile/overlay
        document.getElementById('sidebarOverlay').addEventListener('click', () => {
            document.getElementById('sidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        });

        const accueilButton = document.querySelector('.sidebar-menu button.active');
if (accueilButton) {
    accueilButton.addEventListener('click', (e) => {
        e.preventDefault();
        
        Swal.fire({
            title: `<strong style="background: linear-gradient(135deg, #C2185B, #D32F2F); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Choisir le Tableau de Bord</strong>`,
            html: `
                <a  
   id="uitsDashboardBtn"
   style="display:flex; align-items:center; gap:15px; margin-bottom: 12px; color: #2196F3; 
          font-size: 16px; text-decoration: none; background: linear-gradient(135deg, #2196F315, #2196F325); 
          padding: 14px 18px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); 
          transition: all 0.3s ease; border-left: 4px solid #2196F3; cursor:pointer;"
   onmouseover="this.style.transform='translateX(8px)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.2)';"
   onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">

                    <i class="fas fa-chart-line" style="color: #2196F3; font-size: 20px; width: 24px;"></i> 
                    <span style="flex:1; text-align:left; font-weight:600;">UITS - Union IT Services</span>
                    <i class="fas fa-chevron-right" style="font-size: 14px; opacity: 0.6;"></i>
                </a>
                
                <a href="{{ route('benefices.dashboard') }}" 
                   style="display:flex; align-items:center; gap:15px; margin-bottom: 12px; color: #4CAF50; 
                          font-size: 16px; text-decoration: none; background: linear-gradient(135deg, #4CAF5015, #4CAF5025); 
                          padding: 14px 18px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); 
                          transition: all 0.3s ease; border-left: 4px solid #4CAF50;"
                   onmouseover="this.style.transform='translateX(8px)'; this.style.boxShadow='0 4px 15px rgba(0, 0, 0, 0.2)';"
                   onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='0 2px 8px rgba(0, 0, 0, 0.1)';">
                    <i class="fas fa-gamepad" style="color: #4CAF50; font-size: 20px; width: 24px;"></i> 
                    <span style="flex:1; text-align:left; font-weight:600;">UCGS - Union Computers Gaming Services</span>
                    <i class="fas fa-chevron-right" style="font-size: 14px; opacity: 0.6;"></i>
                </a>
            `,
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
                const uitsBtn = document.getElementById('uitsDashboardBtn');

if (uitsBtn) {
    uitsBtn.addEventListener('click', () => {
        Swal.fire({
            title: `<strong style="background: linear-gradient(135deg, #2196F3, #1976D2);
                    -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    Tableau de Bord UITS
                    </strong>`,
            html: `
                <a href="{{ route('beneficier.index') }}"
                   style="display:flex; align-items:center; gap:15px; margin-bottom: 12px; color: #4CAF50;
                          font-size:16px; text-decoration:none;
                          background:linear-gradient(135deg,#4CAF5015,#4CAF5025);
                          padding:14px 18px; border-radius:12px;
                          border-left:4px solid #4CAF50;">
                    <i class="fas fa-coins" style="font-size:20px;"></i>
                    <span style="flex:1;font-weight:600;">Revenu total (Bénéfice global)</span>
                    <i class="fas fa-chevron-right"></i>
                </a>

                <a href="{{ route('benefice-marge.dashboard') }}"
                   style="display:flex; align-items:center; gap:15px; color:#FF9800;
                          font-size:16px; text-decoration:none;
                          background:linear-gradient(135deg,#FF980015,#FF980025);
                          padding:14px 18px; border-radius:12px;
                          border-left:4px solid #FF9800;">
                    <i class="fas fa-chart-pie" style="font-size:20px;"></i>
                    <span style="flex:1;font-weight:600;">Bénéfice & Marge nette</span>
                    <i class="fas fa-chevron-right"></i>
                </a>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: 480,
            didOpen: () => {
                const popup = document.querySelector('.swal2-popup');
                popup.style.borderRadius = '20px';
                popup.style.border = '2px solid #2196F3';
            }
        });
    });
}

                const popup = document.querySelector('.swal2-popup');
                popup.style.borderRadius = '20px';
                popup.style.boxShadow = '0 10px 40px rgba(0, 0, 0, 0.15)';
                popup.style.border = '2px solid #C2185B';
            }
        });
    });
}
    </script>
</body>
</html>