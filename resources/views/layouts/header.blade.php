<header class="modern-header">
    <div class="header-container">
        <!-- Logo et titre -->
        <div class="header-left">
            <div class="logo-wrapper">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" 
                     alt="Logo" class="logo-img">
                <div class="logo-text">
                    <h1 class="app-name">{{ config('app.name', 'GESTION') }}</h1>
                    <p class="app-subtitle">Système de Gestion</p>
                </div>
            </div>
        </div>

        <!-- Centre - Barre de recherche (masquée sur mobile) -->
        <div class="header-center">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Rechercher un document, client...">
                <button class="search-btn">
                    <i class="fas fa-sliders-h"></i>
                </button>
            </div>
        </div>

        <!-- Droite - Notifications et profil -->
        <div class="header-right">
            <!-- Date et heure (masquée sur mobile) -->
            <div class="datetime-display">
                <div class="time" id="current-time"></div>
                <div class="date" id="current-date"></div>
            </div>

            <!-- Bouton de recherche mobile -->
            <div class="header-item mobile-search-btn-wrapper">
                <button class="icon-btn" id="mobile-search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- Raccourcis rapides -->
            <div class="header-item quick-actions-wrapper">
                <button class="icon-btn" id="quick-actions-btn">
                    <i class="fas fa-plus-circle"></i>
                </button>
                <div class="dropdown-menu quick-actions-dropdown" id="quick-actions-dropdown">
                    <div class="dropdown-header">
                        <h4>Actions Rapides</h4>
                    </div>
                    <div class="quick-actions-grid">
                        <a href="{{ route('reussites.create') }}" class="quick-action-item pink">
                            <i class="fas fa-receipt"></i>
                            <span>Reçu Stage</span>
                        </a>
                        <a href="{{ route('reussitesf.create') }}" class="quick-action-item red">
                            <i class="fas fa-graduation-cap"></i>
                            <span>Reçu Formation</span>
                        </a>
                        <a href="{{ route('devis.create') }}" class="quick-action-item accent">
                            <i class="fas fa-file-invoice"></i>
                            <span>Devis Projet</span>
                        </a>
                        <a href="{{ route('devisf.create') }}" class="quick-action-item pink">
                            <i class="fas fa-file-contract"></i>
                            <span>Devis Formation</span>
                        </a>
                        <a href="{{ route('factures.create') }}" class="quick-action-item red">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <span>Facture Projet</span>
                        </a>
                        <a href="{{ route('facturefs.create') }}" class="quick-action-item accent">
                            <i class="fas fa-money-check-alt"></i>
                            <span>Facture Formation</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profil utilisateur -->
            <div class="header-item profile-wrapper">
                <button class="profile-btn" id="profile-btn">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=C2185B&color=fff&bold=true" 
                         alt="Profile" class="profile-img">
                    <div class="profile-info">
                        <span class="profile-name">{{ Auth::user()->name }}</span>
                        <span class="profile-role">Administrateur</span>
                    </div>
                    <i class="fas fa-chevron-down profile-arrow"></i>
                </button>
                <div class="dropdown-menu profile-dropdown" id="profile-dropdown">
                    <div class="profile-header">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=C2185B&color=fff&bold=true&size=80" 
                             alt="Profile" class="profile-avatar">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                    <div class="profile-menu">
                        <a href="{{ route('profile.edit') }}" class="profile-menu-item">
                            <i class="fas fa-user"></i>
                            <span>Mon Profil</span>
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-cog"></i>
                            <span>Paramètres</span>
                        </a>
                        <a href="#" class="profile-menu-item">
                            <i class="fas fa-question-circle"></i>
                            <span>Aide & Support</span>
                        </a>
                        <div class="divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="profile-menu-item logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche mobile (overlay) -->
    <div class="mobile-search-overlay" id="mobile-search-overlay">
        <div class="mobile-search-container">
            <button class="mobile-search-close" id="mobile-search-close">
                <i class="fas fa-times"></i>
            </button>
            <div class="mobile-search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="mobile-search-input" placeholder="Rechercher..." autofocus>
            </div>
        </div>
    </div>
</header>

<style>
    :root {
        --header-height: 75px;
        --header-height-mobile: 60px;
        --color-primary: #C2185B;
        --color-secondary: #D32F2F;
        --color-accent: #ef4444;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.05);
        --shadow-md: 0 4px 15px rgba(0,0,0,0.1);
        --shadow-lg: 0 8px 30px rgba(0,0,0,0.15);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* ======================================= */
    /* HEADER GÉNÉRAL                          */
    /* ======================================= */
    .modern-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: var(--shadow-md);
        position: sticky;
        top: 0;
        z-index: 1000;
        border-bottom: 3px solid var(--color-primary);
    }

    .header-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        height: var(--header-height);
        max-width: 100%;
    }

    /* ======================================= */
    /* LOGO ET TITRE                           */
    /* ======================================= */
    .header-left {
        flex: 0 0 auto;
        min-width: 0;
    }

    .logo-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-img {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(194, 24, 91, 0.2);
        flex-shrink: 0;
    }

    .logo-text {
        display: flex;
        flex-direction: column;
        min-width: 0;
    }

    .app-name {
        font-size: 1.4rem;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .app-subtitle {
        font-size: 0.7rem;
        color: #6c757d;
        margin: 0;
        font-weight: 500;
        white-space: nowrap;
    }

    /* ======================================= */
    /* BARRE DE RECHERCHE DESKTOP              */
    /* ======================================= */
    .header-center {
        flex: 1;
        max-width: 600px;
        padding: 0 20px;
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
        background: white;
        border-radius: 50px;
        padding: 5px 10px;
        box-shadow: var(--shadow-sm);
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .search-box:focus-within {
        border-color: var(--color-primary);
        box-shadow: 0 4px 15px rgba(194, 24, 91, 0.2);
    }

    .search-icon {
        color: var(--color-primary);
        font-size: 1.1rem;
        margin: 0 10px;
        flex-shrink: 0;
    }

    .search-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 10px;
        font-size: 0.95rem;
        background: transparent;
        min-width: 0;
    }

    .search-btn {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        border: none;
        color: white;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }

    .search-btn:hover {
        transform: scale(1.1);
    }

    /* ======================================= */
    /* SECTION DROITE                          */
    /* ======================================= */
    .header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex: 0 0 auto;
    }

    .datetime-display {
        text-align: right;
        margin-right: 8px;
        padding-right: 12px;
        border-right: 2px solid #e9ecef;
    }

    .time {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--color-secondary);
        font-family: 'Courier New', monospace;
        line-height: 1.3;
    }

    .date {
        font-size: 0.7rem;
        color: #6c757d;
        margin-top: 2px;
    }

    .header-item {
        position: relative;
    }

    .mobile-search-btn-wrapper {
        display: none;
    }

    .icon-btn {
        position: relative;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        border: none;
        background: white;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
        font-size: 1.15rem;
    }

    .icon-btn:hover {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(194, 24, 91, 0.3);
    }

    /* ======================================= */
    /* PROFIL                                  */
    /* ======================================= */
    .profile-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px 14px;
        border-radius: 50px;
        border: 2px solid transparent;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .profile-btn:hover {
        border-color: var(--color-primary);
        box-shadow: 0 4px 15px rgba(194, 24, 91, 0.2);
    }

    .profile-img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--color-primary);
        flex-shrink: 0;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        text-align: left;
        min-width: 0;
    }

    .profile-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: #212529;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .profile-role {
        font-size: 0.7rem;
        color: #6c757d;
        white-space: nowrap;
    }

    .profile-arrow {
        font-size: 0.75rem;
        color: #6c757d;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }

    .profile-btn:hover .profile-arrow {
        transform: rotate(180deg);
    }

    /* ======================================= */
    /* DROPDOWNS                               */
    /* ======================================= */
    .dropdown-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: white;
        border-radius: 15px;
        box-shadow: var(--shadow-lg);
        min-width: 320px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1100;
        max-height: 85vh;
        overflow-y: auto;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        padding: 18px 20px;
        border-bottom: 2px solid #f1f3f5;
    }

    .dropdown-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #212529;
    }

    /* Quick Actions */
    .quick-actions-dropdown {
        min-width: 360px;
    }

    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        padding: 15px;
    }

    .quick-action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 15px;
        border-radius: 12px;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        font-size: 0.82rem;
        font-weight: 600;
        gap: 10px;
        text-align: center;
    }

    .quick-action-item i {
        font-size: 1.8rem;
    }

    .quick-action-item.pink { 
        background: linear-gradient(135deg, var(--color-primary), #E91E63); 
    }
    .quick-action-item.red { 
        background: linear-gradient(135deg, var(--color-secondary), #F44336); 
    }
    .quick-action-item.accent { 
        background: linear-gradient(135deg, var(--color-accent), #dc2626); 
    }

    .quick-action-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    }

    /* Profile Dropdown */
    .profile-dropdown {
        min-width: 280px;
    }

    .profile-header {
        text-align: center;
        padding: 25px 20px;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .profile-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        margin-bottom: 12px;
        border: 3px solid white;
    }

    .profile-header h4 {
        margin: 0 0 5px 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
    }

    .profile-header p {
        margin: 0;
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .profile-menu {
        padding: 10px;
    }

    .profile-menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        border-radius: 10px;
        text-decoration: none;
        color: #495057;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        border: none;
        background: none;
        width: 100%;
        cursor: pointer;
        text-align: left;
    }

    .profile-menu-item:hover {
        background: #f8f9fa;
        color: var(--color-primary);
    }

    .profile-menu-item i {
        width: 20px;
        text-align: center;
    }

    .profile-menu-item.logout {
        color: #dc3545;
    }

    .profile-menu-item.logout:hover {
        background: #fff5f5;
    }

    .divider {
        height: 1px;
        background: #e9ecef;
        margin: 8px 0;
    }

    /* ======================================= */
    /* RECHERCHE MOBILE (OVERLAY)              */
    /* ======================================= */
    .mobile-search-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(5px);
        z-index: 2000;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .mobile-search-overlay.show {
        display: flex;
        opacity: 1;
        align-items: flex-start;
        padding-top: 20px;
    }

    .mobile-search-container {
        width: 100%;
        padding: 20px;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-30px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .mobile-search-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.2rem;
        color: #495057;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
    }

    .mobile-search-close:hover {
        background: #f8f9fa;
        transform: rotate(90deg);
    }

    .mobile-search-box {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 50px;
        padding: 12px 20px;
        box-shadow: var(--shadow-lg);
    }

    .mobile-search-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1rem;
        padding: 0 10px;
    }

    /* ======================================= */
    /* RESPONSIVE : TABLETTES (768px - 992px)  */
    /* ======================================= */
    @media (max-width: 992px) {
        .header-container {
            padding: 0 20px;
            height: var(--header-height-mobile);
        }

        .header-center {
            display: none;
        }

        .datetime-display {
            display: none;
        }

        .mobile-search-btn-wrapper {
            display: block;
        }

        .logo-img {
            width: 42px;
            height: 42px;
        }

        .app-name {
            font-size: 1.2rem;
        }

        .app-subtitle {
            font-size: 0.65rem;
        }

        .quick-actions-dropdown {
            min-width: 340px;
        }
    }

    /* ======================================= */
    /* RESPONSIVE : MOBILE (max 768px)         */
    /* ======================================= */
    @media (max-width: 768px) {
        .header-container {
            padding: 0 15px;
            gap: 8px;
        }

        .header-right {
            gap: 8px;
        }

        .logo-img {
            width: 38px;
            height: 38px;
        }

        .logo-wrapper {
            gap: 10px;
        }

        .app-name {
            font-size: 1.1rem;
        }

        .app-subtitle {
            font-size: 0.6rem;
        }

        .icon-btn {
            width: 38px;
            height: 38px;
            font-size: 1.05rem;
        }

        /* Profil compact */
        .profile-info {
            display: none;
        }

        .profile-btn {
            padding: 4px;
            border-radius: 50%;
            gap: 0;
        }

        .profile-arrow {
            display: none;
        }

        .profile-img {
            width: 36px;
            height: 36px;
        }

        /* Dropdowns adaptés */
        .dropdown-menu {
            min-width: 280px;
            max-width: calc(100vw - 20px);
            right: -10px;
        }

        .quick-actions-dropdown {
            min-width: 300px;
            right: -10px;
        }

        .quick-actions-grid {
            gap: 10px;
            padding: 12px;
        }

        .quick-action-item {
            padding: 18px 12px;
            font-size: 0.78rem;
        }

        .quick-action-item i {
            font-size: 1.6rem;
        }
    }

    /* ======================================= */
    /* RESPONSIVE : PETITS MOBILES (max 576px) */
    /* ======================================= */
    @media (max-width: 576px) {
        .header-container {
            padding: 0 12px;
        }

        .logo-img {
            width: 35px;
            height: 35px;
        }

        .app-name {
            font-size: 1rem;
        }

        .app-subtitle {
            display: none;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .profile-img {
            width: 34px;
            height: 34px;
        }

        .header-right {
            gap: 6px;
        }

        .dropdown-menu {
            min-width: 260px;
            right: -15px;
        }

        .quick-actions-dropdown {
            min-width: 280px;
            right: -15px;
        }

        .quick-actions-grid {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .quick-action-item {
            flex-direction: row;
            justify-content: flex-start;
            padding: 15px;
            gap: 15px;
            text-align: left;
        }

        .quick-action-item i {
            font-size: 1.5rem;
        }
    }

    /* ======================================= */
    /* RESPONSIVE : TRÈS PETITS (max 400px)    */
    /* ======================================= */
    @media (max-width: 400px) {
        .header-container {
            padding: 0 10px;
        }

        .logo-wrapper {
            gap: 8px;
        }

        .logo-img {
            width: 32px;
            height: 32px;
        }

        .app-name {
            font-size: 0.95rem;
        }

        .icon-btn {
            width: 34px;
            height: 34px;
        }

        .profile-img {
            width: 32px;
            height: 32px;
        }

        .dropdown-menu {
            right: -20px;
        }

        .quick-actions-dropdown {
            right: -20px;
        }
    }
</style>

<script>
    // ======================================= 
    // HORLOGE EN TEMPS RÉEL
    // ======================================= 
    function updateDateTime() {
        const now = new Date();
        const timeElement = document.getElementById('current-time');
        const dateElement = document.getElementById('current-date');
        
        if (timeElement) {
            timeElement.textContent = now.toLocaleTimeString('fr-FR', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
        }
        
        if (dateElement) {
            dateElement.textContent = now.toLocaleDateString('fr-FR', { 
                weekday: 'short',
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
        }
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);

    // ======================================= 
    // GESTION DES DROPDOWNS
    // ======================================= 
    function setupDropdown(btnId, dropdownId) {
        const btn = document.getElementById(btnId);
        const dropdown = document.getElementById(dropdownId);
        
        if (btn && dropdown) {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Fermer tous les autres dropdowns
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.remove('show');
                    }
                });
                
                dropdown.classList.toggle('show');
            });
        }
    }

    setupDropdown('profile-btn', 'profile-dropdown');
    setupDropdown('quick-actions-btn', 'quick-actions-dropdown');

    // Fermer les dropdowns en cliquant ailleurs
    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('show');
        });
    });

    // Empêcher la fermeture lors du clic à l'intérieur du dropdown
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });

    // ======================================= 
    // RECHERCHE MOBILE (OVERLAY)
    // ======================================= 
    const mobileSearchBtn = document.getElementById('mobile-search-btn');
    const mobileSearchOverlay = document.getElementById('mobile-search-overlay');
    const mobileSearchClose = document.getElementById('mobile-search-close');

    if (mobileSearchBtn && mobileSearchOverlay && mobileSearchClose) {
        // Ouvrir l'overlay
        mobileSearchBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileSearchOverlay.classList.add('show');
            // Focus automatique sur l'input
            setTimeout(() => {
                const input = document.querySelector('.mobile-search-input');
                if (input) input.focus();
            }, 300);
        });

        // Fermer l'overlay
        mobileSearchClose.addEventListener('click', function() {
            mobileSearchOverlay.classList.remove('show');
        });

        // Fermer en cliquant en dehors
        mobileSearchOverlay.addEventListener('click', function(e) {
            if (e.target === mobileSearchOverlay) {
                mobileSearchOverlay.classList.remove('show');
            }
        });

        // Fermer avec Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && mobileSearchOverlay.classList.contains('show')) {
                mobileSearchOverlay.classList.remove('show');
            }
        });
    }
</script>