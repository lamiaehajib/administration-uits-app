<x-app-layout>
    <style>
        .payment-detail-header {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 40px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(211, 47, 47, 0.3);
            position: relative;
            overflow: hidden;
        }

        .payment-detail-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .payment-detail-header h3 {
            color: white;
            margin: 0;
            font-size: 2.2rem;
            font-weight: 700;
            -webkit-text-fill-color: white;
            position: relative;
            z-index: 1;
        }

        .payment-detail-header .subtitle {
            opacity: 0.9;
            font-size: 1rem;
            margin-top: 10px;
            position: relative;
            z-index: 1;
        }

        .detail-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 30px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .detail-card:hover {
            box-shadow: 0 8px 30px rgba(211, 47, 47, 0.15);
            transform: translateY(-3px);
        }

        .detail-card h5 {
            color: #D32F2F;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid;
            border-image: linear-gradient(90deg, #C2185B, #D32F2F) 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-card h5 i {
            font-size: 1.3rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        .info-row:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.03), rgba(211, 47, 47, 0.03));
            padding-left: 10px;
            margin: 0 -10px;
            padding-right: 10px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-label i {
            color: #C2185B;
            font-size: 0.9rem;
        }

        .info-value {
            font-weight: 500;
            color: #333;
            text-align: right;
        }

        .amount-highlight {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            border: 2px solid rgba(194, 24, 91, 0.2);
            margin: 20px 0;
        }

        .amount-highlight .label {
            color: #666;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .amount-highlight .value {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .badge-custom {
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .badge-especes {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            box-shadow: 0 3px 10px rgba(76, 175, 80, 0.3);
        }

        .badge-carte {
            background: linear-gradient(135deg, #2196F3, #1976D2);
            color: white;
            box-shadow: 0 3px 10px rgba(33, 150, 243, 0.3);
        }

        .badge-cheque {
            background: linear-gradient(135deg, #FF9800, #F57C00);
            color: white;
            box-shadow: 0 3px 10px rgba(255, 152, 0, 0.3);
        }

        .badge-virement {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
            color: white;
            box-shadow: 0 3px 10px rgba(156, 39, 176, 0.3);
        }

        .btn-action {
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
            background: linear-gradient(135deg, #D32F2F, #C2185B);
        }

        .btn-outline-custom {
            background: white;
            border: 2px solid #C2185B;
            color: #C2185B;
        }

        .btn-outline-custom:hover {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            border-color: transparent;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.2);
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, #f44336, #d32f2f);
            color: white;
        }

        .btn-danger-custom:hover {
            background: linear-gradient(135deg, #d32f2f, #f44336);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(244, 67, 54, 0.3);
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #C2185B, #D32F2F);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -25px;
            top: 5px;
            width: 15px;
            height: 15px;
            border-radius: 50%;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: 3px solid white;
            box-shadow: 0 0 0 3px rgba(194, 24, 91, 0.2);
        }

        .timeline-content {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            padding: 15px;
            border-radius: 10px;
            border-left: 3px solid #C2185B;
        }

        .note-box {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 152, 0, 0.1));
            border-left: 4px solid #FFC107;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }

        .note-box i {
            color: #FFC107;
            font-size: 1.5rem;
        }

        .table-custom {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }

        .table-custom thead {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
        }

        .table-custom thead th {
            border: none;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-custom tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .table-custom tbody tr:hover {
            background: linear-gradient(90deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            transform: scale(1.01);
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 25px;
        }

        .icon-box {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.1), rgba(211, 47, 47, 0.1));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .icon-box i {
            font-size: 1.5rem;
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media (max-width: 768px) {
            .payment-detail-header {
                padding: 25px;
            }

            .payment-detail-header h3 {
                font-size: 1.6rem;
            }

            .detail-card {
                padding: 20px;
            }

            .amount-highlight .value {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            .info-row {
                flex-direction: column;
                gap: 8px;
            }

            .info-value {
                text-align: left;
            }
        }
    </style>

    <div class="container-fluid">
        <!-- Header -->
        <div class="payment-detail-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h3>
                        <i class="fas fa-receipt me-2"></i>
                        Détails du Paiement
                    </h3>
                    <p class="subtitle mb-0">
                        Paiement effectué le {{ $paiement->date_paiement->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <a href="{{ route('paiements.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche - Informations principales -->
            <div class="col-lg-8">
                <!-- Montant en évidence -->
                <div class="detail-card">
                    <div class="amount-highlight">
                        <div class="label">Montant du Paiement</div>
                        <div class="value">{{ number_format($paiement->montant, 2) }} DH</div>
                    </div>
                </div>

                <!-- Informations du paiement -->
                <div class="detail-card">
                    <h5>
                        <i class="fas fa-money-bill-wave"></i>
                        Informations du Paiement
                    </h5>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar-alt"></i>
                            Date de paiement
                        </span>
                        <span class="info-value">
                            {{ $paiement->date_paiement->format('d/m/Y à H:i') }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-credit-card"></i>
                            Mode de paiement
                        </span>
                        <span class="info-value">
                            <span class="badge-custom badge-{{ $paiement->mode_paiement }}">
                                <i class="fas fa-{{ $paiement->mode_paiement == 'especes' ? 'money-bill-wave' : ($paiement->mode_paiement == 'carte' ? 'credit-card' : ($paiement->mode_paiement == 'cheque' ? 'money-check' : 'university')) }}"></i>
                                {{ ucfirst($paiement->mode_paiement) }}
                            </span>
                        </span>
                    </div>

                    @if($paiement->reference)
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-hashtag"></i>
                            Référence
                        </span>
                        <span class="info-value">
                            <code style="background: #f5f5f5; padding: 5px 10px; border-radius: 5px;">
                                {{ $paiement->reference }}
                            </code>
                        </span>
                    </div>
                    @endif

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-user-tie"></i>
                            Enregistré par
                        </span>
                        <span class="info-value">
                            {{ $paiement->user->name ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-clock"></i>
                            Date de création
                        </span>
                        <span class="info-value">
                            {{ $paiement->created_at->format('d/m/Y à H:i') }}
                        </span>
                    </div>

                    @if($paiement->notes)
                    <div class="note-box">
                        <div class="d-flex gap-3">
                            <i class="fas fa-sticky-note"></i>
                            <div>
                                <strong class="d-block mb-2">Notes:</strong>
                                {{ $paiement->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Informations du reçu associé -->
                <div class="detail-card">
                    <h5>
                        <i class="fas fa-file-invoice"></i>
                        Reçu Associé
                    </h5>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-barcode"></i>
                            Numéro de reçu
                        </span>
                        <span class="info-value">
                            <a href="{{ route('recus.show', $paiement->recuUcg) }}" class="fw-bold" style="color: #C2185B; text-decoration: none;">
                                {{ $paiement->recuUcg->numero_recu }}
                                <i class="fas fa-external-link-alt ms-1" style="font-size: 0.8rem;"></i>
                            </a>
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-user"></i>
                            Client
                        </span>
                        <span class="info-value">
                            {{ $paiement->recuUcg->client_nom }}
                            @if($paiement->recuUcg->client_prenom)
                                {{ $paiement->recuUcg->client_prenom }}
                            @endif
                        </span>
                    </div>

                    @if($paiement->recuUcg->client_telephone)
                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-phone"></i>
                            Téléphone
                        </span>
                        <span class="info-value">
                            <a href="tel:{{ $paiement->recuUcg->client_telephone }}" style="color: #C2185B; text-decoration: none;">
                                {{ $paiement->recuUcg->client_telephone }}
                            </a>
                        </span>
                    </div>
                    @endif

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-calendar"></i>
                            Date du reçu
                        </span>
                        <span class="info-value">
                            {{ $paiement->recuUcg->created_at->format('d/m/Y à H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Colonne droite - Résumé et actions -->
            <div class="col-lg-4">
                <!-- Résumé financier -->
                <div class="detail-card">
                    <h5>
                        <i class="fas fa-calculator"></i>
                        Résumé Financier
                    </h5>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-shopping-cart"></i>
                            Total reçu
                        </span>
                        <span class="info-value fw-bold">
                            {{ number_format($paiement->recuUcg->total, 2) }} DH
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-check-circle"></i>
                            Total payé
                        </span>
                        <span class="info-value fw-bold text-success">
                            {{ number_format($paiement->recuUcg->montant_paye, 2) }} DH
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-hourglass-half"></i>
                            Reste à payer
                        </span>
                        <span class="info-value fw-bold" style="color: {{ $paiement->recuUcg->reste > 0 ? '#D32F2F' : '#4CAF50' }};">
                            {{ number_format($paiement->recuUcg->reste, 2) }} DH
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            <i class="fas fa-info-circle"></i>
                            Statut paiement
                        </span>
                        <span class="info-value">
                            @if($paiement->recuUcg->statut_paiement === 'paye')
                                <span class="badge bg-success">Payé</span>
                            @elseif($paiement->recuUcg->statut_paiement === 'partiel')
                                <span class="badge bg-warning">Partiel</span>
                            @else
                                <span class="badge bg-danger">Impayé</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Articles du reçu -->
                <div class="detail-card">
                    <h5>
                        <i class="fas fa-box-open"></i>
                        Articles ({{ $paiement->recuUcg->items->count() }})
                    </h5>

                    <div class="timeline">
                        @foreach($paiement->recuUcg->items as $item)
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong>{{ $item->produit->nom }}</strong>
                                    <span class="badge bg-secondary">x{{ $item->quantite }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">{{ $item->produit->reference }}</small>
                                    <strong style="color: #D32F2F;">{{ number_format($item->sous_total, 2) }} DH</strong>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Actions -->
                <div class="detail-card">
                    <h5>
                        <i class="fas fa-tasks"></i>
                        Actions
                    </h5>

                    <div class="action-buttons">
                        <a href="{{ route('recus.show', $paiement->recuUcg) }}" class="btn btn-action btn-primary-custom w-100">
                            <i class="fas fa-file-invoice"></i>
                            Voir le Reçu Complet
                        </a>

                        <a href="{{ route('recus.print', $paiement->recuUcg) }}" class="btn btn-action btn-outline-custom w-100" target="_blank">
                            <i class="fas fa-print"></i>
                            Imprimer le Reçu
                        </a>

                        @if($paiement->recuUcg->statut === 'en_cours')
                        <form action="{{ route('paiements.destroy', $paiement) }}" method="POST" class="w-100" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ? Cette action est irréversible.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-action btn-danger-custom w-100">
                                <i class="fas fa-trash"></i>
                                Supprimer le Paiement
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Succès!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#D32F2F'
            });
        </script>
    @endif
</x-app-layout>