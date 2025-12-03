<x-app-layout>
    <style>
        /* ===============================================
           VARIABLES ET CONFIGURATION GLOBALE
        =============================================== */
        :root {
            --primary-red: #D32F2F;
            --primary-pink: #C2185B;
            --gradient-primary: linear-gradient(135deg, #C2185B, #D32F2F);
            --gradient-primary-hover: linear-gradient(135deg, #AD1457, #B71C1C);
            --shadow-sm: 0 2px 8px rgba(211, 47, 47, 0.1);
            --shadow-md: 0 4px 16px rgba(211, 47, 47, 0.15);
            --shadow-lg: 0 8px 24px rgba(211, 47, 47, 0.2);
            --shadow-xl: 0 12px 32px rgba(211, 47, 47, 0.25);
        }

        /* ===============================================
           CONTENEUR PRINCIPAL
        =============================================== */
        .container-fluid {
            background: linear-gradient(to bottom, #ffffff, #fef5f7);
            min-height: 100vh;
            padding-bottom: 60px;
        }

        /* ===============================================
           TITRE PRINCIPAL
        =============================================== */
        .container-fluid > h3 {
            position: relative;
            padding: 25px 0;
            margin-bottom: 30px;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .container-fluid > h3 .hight {
            animation: gradientShift 3s ease infinite;
        }

        @keyframes gradientShift {
            0%, 100% { 
                background: linear-gradient(135deg, #C2185B, #D32F2F);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
            50% { 
                background: linear-gradient(135deg, #D32F2F, #C2185B);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
        }

        /* ===============================================
           LIGNE DE SÉPARATION
        =============================================== */
        .container-fluid > hr {
            height: 3px;
            background: var(--gradient-primary);
            border: none;
            opacity: 1;
            margin: 30px auto;
            max-width: 200px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(211, 47, 47, 0.3);
        }

        /* ===============================================
           FORMULAIRE DE SÉLECTION DE DATE
        =============================================== */
        .card.shadow-sm {
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            border: 2px solid transparent;
        }

        .card.shadow-sm:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg) !important;
            border-color: rgba(211, 47, 47, 0.2);
        }

        .card.shadow-sm form {
            gap: 15px;
        }

        .card.shadow-sm label {
            color: var(--primary-red);
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .card.shadow-sm input[type="month"] {
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .card.shadow-sm input[type="month"]:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.1);
            outline: none;
        }

        .card.shadow-sm button {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .card.shadow-sm button:hover {
            background: var(--gradient-primary-hover) !important;
            transform: scale(1.05);
            box-shadow: var(--shadow-md);
        }

        .card.shadow-sm button:active {
            transform: scale(0.98);
        }

        /* ===============================================
           BADGE DU MOIS
        =============================================== */
        h4 .badge {
            background: var(--gradient-primary) !important;
            padding: 12px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            letter-spacing: 0.5px;
            box-shadow: var(--shadow-md);
            animation: pulse 2s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { 
                box-shadow: 0 4px 16px rgba(211, 47, 47, 0.3);
            }
            50% { 
                box-shadow: 0 6px 24px rgba(211, 47, 47, 0.5);
            }
        }

        /* ===============================================
           CARTES STATISTIQUES PRINCIPALES
        =============================================== */
        .row .col-md-4 .card {
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        .row .col-md-4 .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1));
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .row .col-md-4 .card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-xl) !important;
        }

        .row .col-md-4 .card:hover::before {
            opacity: 1;
        }

        .row .col-md-4 .card-body {
            padding: 30px;
            position: relative;
            z-index: 1;
        }

        .row .col-md-4 .card-body h6 {
            font-size: 0.85rem;
            letter-spacing: 1px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .row .col-md-4 .card-body h2 {
            font-size: 1.8rem;
            font-weight: 900;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            animation: countUp 0.8s ease;
        }

        @keyframes countUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .row .col-md-4 .card-body i {
            transition: all 0.4s ease;
        }

        .row .col-md-4 .card:hover .card-body i {
            transform: rotate(15deg) scale(1.1);
        }

        /* Gradients spécifiques pour chaque carte */
        .bg-danger-gradient {
            background: linear-gradient(135deg, #FF6B6B 0%, #E63946 50%, #D32F2F 100%) !important;
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #66BB6A 0%, #4CAF50 50%, #2E7D32 100%) !important;
        }

        .bg-info-gradient {
            background: linear-gradient(135deg, #42A5F5 0%, #2196F3 50%, #1565C0 100%) !important;
        }

        /* ===============================================
           CARTES SECONDAIRES (STOCK)
        =============================================== */
        .row.mt-3 .card {
            border-radius: 15px;
            transition: all 0.3s ease;
            background: white;
            border-left-width: 5px !important;
        }

        .row.mt-3 .card:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-lg) !important;
        }

        .row.mt-3 .card-body {
            padding: 25px;
        }

        .row.mt-3 .card-body h6 {
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .row.mt-3 .card-body h3 {
            font-size: 1.6rem;
            font-weight: 800;
            margin: 0;
            color: #333;
        }

        .row.mt-3 .card-body i {
            transition: all 0.3s ease;
        }

        .row.mt-3 .card:hover .card-body i {
            transform: scale(1.2);
        }

        /* ===============================================
           BOUTON TÉLÉCHARGER PDF
        =============================================== */
        .text-center.mt-5 a {
            background: white;
            border: 3px solid;
            border-image: var(--gradient-primary) 1;
            color: var(--primary-red);
            border-radius: 50px;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            transition: all 0.4s ease;
            box-shadow: var(--shadow-sm);
            position: relative;
            overflow: hidden;
        }

        .text-center.mt-5 a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-primary);
            transition: left 0.4s ease;
            z-index: -1;
        }

        .text-center.mt-5 a:hover {
            color: white;
            border-color: transparent;
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .text-center.mt-5 a:hover::before {
            left: 0;
        }

        .text-center.mt-5 a i {
            font-size: 1.3rem;
            transition: transform 0.3s ease;
        }

        .text-center.mt-5 a:hover i {
            transform: scale(1.2) rotate(10deg);
        }

        /* ===============================================
           RESPONSIVE DESIGN
        =============================================== */
        @media (max-width: 768px) {
            .container-fluid > h3 {
                font-size: 1.5rem;
                padding: 20px 0;
            }

            .card.shadow-sm form {
                flex-direction: column;
                align-items: stretch !important;
            }

            .card.shadow-sm label {
                margin-bottom: 8px;
            }

            .card.shadow-sm button {
                margin-top: 10px;
                margin-left: 0 !important;
            }

            h4 .badge {
                font-size: 0.9rem;
                padding: 10px 20px;
            }

            .row .col-md-4 .card-body h2 {
                font-size: 1.5rem;
            }

            .row .col-md-4 .card-body i {
                font-size: 2rem !important;
            }

            .text-center.mt-5 a {
                padding: 12px 25px;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }

            .row .col-md-4 .card-body {
                padding: 20px;
            }

            .row.mt-3 .card-body {
                padding: 20px;
            }

            .row .col-md-4 .card-body h2 {
                font-size: 1.3rem;
            }

            .row.mt-3 .card-body h3 {
                font-size: 1.3rem;
            }
        }

        /* ===============================================
           ANIMATIONS D'ENTRÉE
        =============================================== */
        .col-md-4, .col-md-6 {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
        }

        .col-md-4:nth-child(1) { animation-delay: 0.1s; }
        .col-md-4:nth-child(2) { animation-delay: 0.2s; }
        .col-md-4:nth-child(3) { animation-delay: 0.3s; }
        .col-md-6:nth-child(1) { animation-delay: 0.4s; }
        .col-md-6:nth-child(2) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===============================================
           EFFETS DE BRILLANCE
        =============================================== */
        .row .col-md-4 .card::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.1),
                transparent
            );
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
    </style>

    <div class="container-fluid py-4">
        
        <h3 class="text-center mb-4">
            <i class="fas fa-chart-area me-2 hight"></i> Tableau de Bord des Statistiques Produits
        </h3>
        
        <hr class="mb-4">

        <div class="row mb-5 justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0 p-3">
                    <form action="{{ route('produits.totals') }}" method="GET" class="d-flex align-items-center">
                        <label for="date-select" class="form-label me-3 mb-0 fw-bold text-muted">Sélectionner le Mois:</label>
                        <input type="month" id="date-select" name="date" class="form-control" value="{{ $date ?? \Carbon\Carbon::now()->format('Y-m') }}" required>
                        <button type="submit" class="btn btn-primary ms-2 shadow-sm" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border: none;">
                            <i class="fas fa-filter"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <h4 class="mb-4 text-center">
            <span class="badge bg-secondary">Statistiques pour le mois de {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('MMMM YYYY') }}</span>
        </h4>
        
        <div class="row">
            
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-danger-gradient shadow-lg h-100 border-0" style="background: linear-gradient(135deg, #FF6B6B, #E63946);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold opacity-75">Chiffre d'Affaires (Ventes)</h6>
                                <h2 class="mb-0 fw-bolder">{{ number_format($totalVente, 2, ',', ' ') }} MAD</h2>
                            </div>
                            <i class="fas fa-hand-holding-usd fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card text-white bg-success-gradient shadow-lg h-100 border-0" style="background: linear-gradient(135deg, #4CAF50, #2E7D32);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold opacity-75">Marge Brute / Bénéfice</h6>
                                <h2 class="mb-0 fw-bolder">{{ number_format($benefice, 2, ',', ' ') }} MAD</h2>
                            </div>
                            <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-info-gradient shadow-lg h-100 border-0" style="background: linear-gradient(135deg, #2196F3, #1565C0);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold opacity-75">Coût des Achats</h6>
                                <h2 class="mb-0 fw-bolder">{{ number_format($totalAchat, 2, ',', ' ') }} MAD</h2>
                            </div>
                            <i class="fas fa-shopping-basket fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-3">
            
            <div class="col-md-6 mb-4">
                <div class="card bg-light shadow-sm h-100 border-start border-5 border-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold text-info">Total Articles en Stock</h6>
                                <h3 class="mb-0 fw-bolder">{{ number_format($totalStock, 0, ',', ' ') }} Unités</h3>
                            </div>
                            <i class="fas fa-cubes fa-2x text-info opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card bg-light shadow-sm h-100 border-start border-5 border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-uppercase fw-bold text-warning">Valeur Actuelle du Stock (Coût d'Achat)</h6>
                                <h3 class="mb-0 fw-bolder">{{ number_format($valeurStock, 2, ',', ' ') }} MAD</h3>
                            </div>
                            <i class="fas fa-warehouse fa-2x text-warning opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('produits.export_pdf', ['date' => $date]) }}" class="btn btn-outline-danger btn-lg shadow-sm">
                <i class="fas fa-file-pdf me-2"></i> Télécharger le Rapport de Ventes du Mois (PDF)
            </a>
        </div>
        
    </div>
</x-app-layout>