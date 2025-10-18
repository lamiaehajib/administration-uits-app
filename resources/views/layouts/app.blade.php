<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (only one version needed) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 CSS and JS (only one version needed) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- SweetAlert2 CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" type="image/png">

    <style>
        body {
            font-family: 'Ubuntu', sans-serif;
            margin: 0;
            background: #f8f9fa;
        }

        h3 {
            color: #D32F2F;
            font-family: 'Ubuntu', sans-serif;
            font-weight: bold;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .hight {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: bold;
        }

        path {
            display: none;
        }

        /* Layout principal */
        .app-layout {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Container pour header et contenu avec sidebar */
        .main-container {
            display: flex;
            flex: 1;
            width: 100%;
            overflow: hidden;
        }

        /* La sidebar (navigation) est maintenant incluse via header */
        .sidebar-container {
            flex-shrink: 0;
        }

        /* Content styles */
        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            background: #f8f9fa;
            min-width: 0;
            margin-left: 0; /* L'espace est géré par le sidebar */
        }

        .bg-primary {
            --bs-bg-opacity: 1;
            background: linear-gradient(135deg, #C2185B, #D32F2F) !important;
            text-align: center;
            text-transform: uppercase;
        }

        tbody, td, tfoot, th, thead, tr {
            border-color: inherit;
            border-style: solid;
            border-width: 0;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .main-content {
                padding: 20px;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .main-content {
                padding: 15px;
                padding-bottom: 20px;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 12px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <div class="app-layout">
        <!-- Header en haut (contient le menu burger pour ouvrir la sidebar) -->
        @include('layouts.header')

        <!-- Container principal avec sidebar et contenu -->
        <div class="main-container">
            <!-- Sidebar Navigation (incluse séparément) -->
            <div class="sidebar-container">
                @include('layouts.navigation')
            </div>

            <!-- Contenu principal -->
            <div class="main-content">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>

    <!-- Script pour connecter le burger du header avec la sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ======================================= 
            // TOGGLE MENU BURGER POUR SIDEBAR
            // ======================================= 
            const menuBurgerBtn = document.getElementById('menuBurgerBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (menuBurgerBtn && sidebar) {
                // Ouvrir/Fermer la sidebar
                menuBurgerBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menuBurgerBtn.classList.toggle('active');
                    sidebar.classList.toggle('active');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.toggle('active');
                    }
                });
            }

            // Fermer la sidebar en cliquant sur l'overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    if (menuBurgerBtn) {
                        menuBurgerBtn.classList.remove('active');
                    }
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }

            // Fermer la sidebar avec touche Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar && sidebar.classList.contains('active')) {
                    if (menuBurgerBtn) {
                        menuBurgerBtn.classList.remove('active');
                    }
                    sidebar.classList.remove('active');
                    if (sidebarOverlay) {
                        sidebarOverlay.classList.remove('active');
                    }
                }
            });
        });
    </script>
</body>
</html>