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

        /* Container pour navigation et contenu */
        .content-wrapper {
            display: flex;
            flex: 1;
            width: 100%;
            gap: 30px; /* GRAND ESPACE entre navigation et contenu */
            padding: 0;
        }

        /* Navigation styles */
        .navigation {
            width: 280px;
            background-color: #ffffff;
            color: #fff;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            border-right: 3px solid #C2185B;
            flex-shrink: 0; /* Empêche la navigation de rétrécir */
        }

        /* Content styles */
        .main-content {
            flex: 1;
            padding: 0;
            overflow-y: auto;
            background: #f8f9fa;
            min-width: 0; /* Permet au contenu de rétrécir correctement */
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
        @media (max-width: 1200px) {
            .content-wrapper {
                gap: 20px;
            }
            
            .navigation {
                width: 240px;
            }
        }

        @media (max-width: 992px) {
            .content-wrapper {
                gap: 15px;
            }
            
            .navigation {
                width: 70px;
            }
        }

        @media (max-width: 768px) {
            .content-wrapper {
                flex-direction: column;
                gap: 0;
            }

            .navigation {
                width: 100%;
                position: fixed;
                bottom: 0;
                left: 0;
                z-index: 999;
                height: auto;
                border-right: none;
                border-top: 3px solid #C2185B;
            }

            .main-content {
                padding: 15px;
                padding-bottom: 80px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <div class="app-layout">
        <!-- Header en haut -->
        @include('layouts.header')

        <!-- Container pour navigation et contenu -->
        <div class="content-wrapper">
            <!-- Navigation à gauche -->
            <div class="navigation">
                @include('layouts.navigation')
            </div>

            <!-- Contenu principal à droite avec GRAND ESPACE -->
            <div class="main-content">
                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>
    </div>
</body>
</html>