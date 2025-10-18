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

        <!-- Centre - Barre de recherche -->
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
            <!-- Date et heure -->
            <div class="datetime-display">
                <div class="time" id="current-time"></div>
                <div class="date" id="current-date"></div>
            </div>

            <!-- Notifications -->
            {{-- <div class="header-item notification-wrapper">
                <button class="icon-btn" id="notification-btn">
                    <i class="fas fa-bell"></i>
                    <span class="badge">3</span>
                </button>
                <div class="dropdown-menu notification-dropdown" id="notification-dropdown">
                    <div class="dropdown-header">
                        <h4>Notifications</h4>
                        <span class="badge-count">3 nouvelles</span>
                    </div>
                    <div class="notification-list">
                        <div class="notification-item unread">
                            <div class="notif-icon pink">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div class="notif-content">
                                <p class="notif-title">Nouvelle facture créée</p>
                                <p class="notif-text">Facture #12345 - Client ABC</p>
                                <span class="notif-time">Il y a 5 min</span>
                            </div>
                        </div>
                        <div class="notification-item unread">
                            <div class="notif-icon red">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="notif-content">
                                <p class="notif-title">Nouveau reçu stage</p>
                                <p class="notif-text">Mohammed ALAMI - Stage informatique</p>
                                <span class="notif-time">Il y a 1h</span>
                            </div>
                        </div>
                        <div class="notification-item">
                            <div class="notif-icon accent">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="notif-content">
                                <p class="notif-title">Rapport mensuel</p>
                                <p class="notif-text">Rapport du mois disponible</p>
                                <span class="notif-time">Il y a 3h</span>
                            </div>
                        </div>
                    </div>
                    <div class="dropdown-footer">
                        <a href="#" class="view-all">Voir toutes les notifications</a>
                    </div>
                </div>
            </div> --}}

            <!-- Messages -->
            {{-- <div class="header-item">
                <button class="icon-btn">
                    <i class="fas fa-envelope"></i>
                    <span class="badge">5</span>
                </button>
            </div> --}}

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
</header>

<style>
    :root {
        --header-height: 80px;
        --color-primary: #C2185B;
        --color-secondary: #D32F2F;
        --color-accent: #ef4444;
    }

    .modern-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
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

    /* Logo Section */
    .header-left {
        flex: 0 0 auto;
    }

    .logo-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo-img {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(194, 24, 91, 0.2);
    }

    .logo-text {
        display: flex;
        flex-direction: column;
    }

    .app-name {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .app-subtitle {
        font-size: 0.7rem;
        color: #6c757d;
        margin: 0;
        font-weight: 500;
    }

    /* Search Section */
    .header-center {
        flex: 1;
        max-width: 600px;
        padding: 0 30px;
    }

    .search-box {
        position: relative;
        display: flex;
        align-items: center;
        background: white;
        border-radius: 50px;
        padding: 5px 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
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
    }

    .search-input {
        flex: 1;
        border: none;
        outline: none;
        padding: 10px;
        font-size: 0.95rem;
        background: transparent;
    }

    .search-btn {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        border: none;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .search-btn:hover {
        transform: scale(1.1);
    }

    /* Header Right Section */
    .header-right {
        display: flex;
        align-items: center;
        gap: 15px;
        flex: 0 0 auto;
    }

    /* DateTime Display */
    .datetime-display {
        text-align: right;
        margin-right: 10px;
        padding-right: 15px;
        border-right: 2px solid #e9ecef;
    }

    .time {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--color-secondary);
        font-family: 'Courier New', monospace;
    }

    .date {
        font-size: 0.75rem;
        color: #6c757d;
        margin-top: 2px;
    }

    /* Icon Buttons */
    .header-item {
        position: relative;
    }

    .icon-btn {
        position: relative;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        border: none;
        background: white;
        color: #495057;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        font-size: 1.2rem;
    }

    .icon-btn:hover {
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(194, 24, 91, 0.3);
    }

    .icon-btn .badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: var(--color-accent);
        color: white;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 3px 6px;
        border-radius: 10px;
        min-width: 20px;
        text-align: center;
    }

    /* Profile Button */
    .profile-btn {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 15px;
        border-radius: 50px;
        border: 2px solid transparent;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .profile-btn:hover {
        border-color: var(--color-primary);
        box-shadow: 0 4px 15px rgba(194, 24, 91, 0.2);
    }

    .profile-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--color-primary);
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .profile-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #212529;
    }

    .profile-role {
        font-size: 0.7rem;
        color: #6c757d;
    }

    .profile-arrow {
        font-size: 0.8rem;
        color: #6c757d;
        transition: transform 0.3s ease;
    }

    .profile-btn:hover .profile-arrow {
        transform: rotate(180deg);
    }

    /* Dropdown Menus */
    .dropdown-menu {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        min-width: 300px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-header {
        padding: 20px;
        border-bottom: 2px solid #f1f3f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dropdown-header h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--color-secondary);
    }

    .badge-count {
        background: var(--color-accent);
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    /* Notifications */
    .notification-list {
        max-height: 350px;
        overflow-y: auto;
    }

    .notification-item {
        display: flex;
        gap: 15px;
        padding: 15px 20px;
        border-bottom: 1px solid #f1f3f5;
        transition: background 0.3s ease;
        cursor: pointer;
    }

    .notification-item:hover {
        background: #f8f9fa;
    }

    .notification-item.unread {
        background: #fff5f7;
    }

    .notif-icon {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .notif-icon.pink { background: linear-gradient(135deg, var(--color-primary), #E91E63); }
    .notif-icon.red { background: linear-gradient(135deg, var(--color-secondary), #F44336); }
    .notif-icon.accent { background: linear-gradient(135deg, var(--color-accent), #dc2626); }

    .notif-content {
        flex: 1;
    }

    .notif-title {
        font-weight: 700;
        font-size: 0.9rem;
        color: #212529;
        margin: 0 0 5px 0;
    }

    .notif-text {
        font-size: 0.8rem;
        color: #6c757d;
        margin: 0 0 5px 0;
    }

    .notif-time {
        font-size: 0.7rem;
        color: #adb5bd;
    }

    /* Quick Actions */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        padding: 15px;
    }

    .quick-action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border-radius: 12px;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease;
        font-size: 0.85rem;
        font-weight: 600;
        gap: 10px;
    }

    .quick-action-item.pink { background: linear-gradient(135deg, var(--color-primary), #E91E63); }
    .quick-action-item.red { background: linear-gradient(135deg, var(--color-secondary), #F44336); }
    .quick-action-item.accent { background: linear-gradient(135deg, var(--color-accent), #dc2626); }

    .quick-action-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    }

    .quick-action-item i {
        font-size: 1.5rem;
    }

    /* Profile Dropdown */
    .profile-header {
        text-align: center;
        padding: 25px;
        background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
        color: white;
        border-radius: 15px 15px 0 0;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid white;
        margin-bottom: 10px;
    }

    .profile-header h4 {
        margin: 10px 0 5px 0;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .profile-header p {
        margin: 0;
        font-size: 0.85rem;
        opacity: 0.9;
    }

    .profile-menu {
        padding: 10px;
    }

    .profile-menu-item {
        display: flex;
        align-items: center;
        gap: 15px;
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
    }

    .profile-menu-item:hover {
        background: #f8f9fa;
        color: var(--color-primary);
    }

    .profile-menu-item.logout {
        color: var(--color-accent);
    }

    .profile-menu-item.logout:hover {
        background: #fff5f5;
    }

    .divider {
        height: 1px;
        background: #e9ecef;
        margin: 10px 0;
    }

    .dropdown-footer {
        padding: 15px;
        border-top: 2px solid #f1f3f5;
        text-align: center;
    }

    .view-all {
        color: var(--color-primary);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .view-all:hover {
        color: var(--color-secondary);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .header-center {
            display: none;
        }
        
        .datetime-display {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .header-container {
            padding: 0 15px;
        }

        .app-name {
            font-size: 1.2rem;
        }

        .profile-info {
            display: none;
        }

        .dropdown-menu {
            min-width: 280px;
        }
    }
</style>

<script>
    // Horloge en temps réel
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

    // Mettre à jour l'horloge chaque seconde
    updateDateTime();
    setInterval(updateDateTime, 1000);

    // Gestion des dropdowns
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

    // Initialiser les dropdowns
    setupDropdown('notification-btn', 'notification-dropdown');
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
</script>