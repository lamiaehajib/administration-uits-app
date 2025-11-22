<x-app-layout>
    <div class="container-fluid">
        <!-- Header avec bouton retour -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="hight mb-1">
                    <i class="fas fa-ticket-alt"></i> Détails du Ticket #{{ $repairTicket->id }}
                </h3>
                <p class="text-muted mb-0">Informations complètes de réparation</p>
            </div>
            <a href="{{ route('repair-tickets.index') }}" class="btn btn-gradient-back">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <!-- Badges de statut en haut -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="status-badges">
                    <span class="badge badge-{{ $repairTicket->status }}">
                        <i class="fas fa-{{ $repairTicket->status === 'livre' ? 'check-circle' : ($repairTicket->status === 'en_cours' ? 'cog' : ($repairTicket->status === 'termine' ? 'check-double' : 'clock')) }}"></i>
                        {{ ucfirst(str_replace('_', ' ', $repairTicket->status)) }}
                    </span>
                    @if($repairTicket->montant_total - $repairTicket->avance > 0)
                        <span class="badge badge-warning">
                            <i class="fas fa-exclamation-triangle"></i> Paiement incomplet
                        </span>
                    @else
                        <span class="badge badge-success">
                            <i class="fas fa-money-check-alt"></i> Payé intégralement
                        </span>
                    @endif
                    @if($repairTicket->estimated_completion && $repairTicket->estimated_completion < now() && !in_array($repairTicket->status, ['termine', 'livre']))
                        <span class="badge badge-danger">
                            <i class="fas fa-exclamation-circle"></i> En retard
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Colonne gauche -->
            <div class="col-lg-8">
                <!-- Informations Client -->
                <div class="card card-modern mb-4">
                    <div class="card-header-gradient">
                        <i class="fas fa-user-circle"></i> Informations Client
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <i class="fas fa-user icon-gradient"></i>
                                    <div>
                                        <small class="text-muted">Nom Complet</small>
                                        <p class="mb-0 fw-bold">{{ $repairTicket->nom_complet }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <i class="fas fa-phone icon-gradient"></i>
                                    <div>
                                        <small class="text-muted">Téléphone</small>
                                        <p class="mb-0 fw-bold">{{ $repairTicket->phone ?? 'Non renseigné' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations Appareil -->
                <div class="card card-modern mb-4">
                    <div class="card-header-gradient">
                        <i class="fas fa-mobile-alt"></i> Informations Appareil
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <i class="fas fa-tablet-alt icon-gradient"></i>
                                    <div>
                                        <small class="text-muted">Type d'Appareil</small>
                                        <p class="mb-0 fw-bold">{{ $repairTicket->device_type }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-item">
                                    <i class="fas fa-tag icon-gradient"></i>
                                    <div>
                                        <small class="text-muted">Marque</small>
                                        <p class="mb-0 fw-bold">{{ $repairTicket->device_brand ?? 'Non spécifiée' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="info-item">
                                    <i class="fas fa-tools icon-gradient"></i>
                                    <div>
                                        <small class="text-muted">Description du Problème</small>
                                        <p class="mb-0">{{ $repairTicket->problem_description ?? 'Aucune description' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Détails de Réparation -->
                @if($repairTicket->details)
                <div class="card card-modern mb-4">
                    <div class="card-header-gradient">
                        <i class="fas fa-clipboard-list"></i> Détails de Réparation
                    </div>
                    <div class="card-body">
                        <div class="details-content">
                            {{ $repairTicket->details }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Colonne droite -->
            <div class="col-lg-4">
                <!-- Informations Financières -->
                <div class="card card-modern card-gradient mb-4">
                    <div class="card-body text-center text-white">
                        <i class="fas fa-dollar-sign financial-icon"></i>
                        <h5 class="text-white mb-3">Informations Financières</h5>
                        
                        <div class="financial-item">
                            <small>Montant Total</small>
                            <h4 class="text-white fw-bold">{{ number_format($repairTicket->montant_total, 2) }} DH</h4>
                        </div>
                        
                        <div class="financial-item">
                            <small>Avance Payée</small>
                            <h5 class="text-white">{{ number_format($repairTicket->avance, 2) }} DH</h5>
                        </div>
                        
                        <hr class="bg-white opacity-25">
                        
                        <div class="financial-item">
                            <small>Reste à Payer</small>
                            <h4 class="text-white fw-bold">
                                {{ number_format($repairTicket->montant_total - $repairTicket->avance, 2) }} DH
                            </h4>
                        </div>
                    </div>
                </div>

                <!-- Dates importantes -->
                <div class="card card-modern mb-4">
                    <div class="card-header-gradient">
                        <i class="fas fa-calendar-alt"></i> Dates Importantes
                    </div>
                    <div class="card-body">
                        <div class="date-item">
                            <i class="fas fa-calendar-plus icon-gradient"></i>
                            <div>
                                <small class="text-muted">Date de Dépôt</small>
                                <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($repairTicket->date_depot)->format('d/m/Y') }}</p>
                                <small class="text-muted">{{ $repairTicket->time_depot ?? '--:--' }}</small>
                            </div>
                        </div>
                        
                        @if($repairTicket->estimated_completion)
                        <div class="date-item">
                            <i class="fas fa-calendar-check icon-gradient"></i>
                            <div>
                                <small class="text-muted">Date Prévue</small>
                                <p class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($repairTicket->estimated_completion)->format('d/m/Y') }}</p>
                                @php
                                    $days = \Carbon\Carbon::parse($repairTicket->date_depot)->diffInDays(\Carbon\Carbon::parse($repairTicket->estimated_completion));
                                @endphp
                                <small class="text-muted">({{ $days }} jours)</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Actions -->
                <div class="card card-modern">
                    <div class="card-header-gradient">
                        <i class="fas fa-cogs"></i> Actions
                    </div>
                    <div class="card-body">
                        <a href="{{ route('repair-tickets.edit', $repairTicket) }}" class="btn btn-gradient-primary w-100 mb-2">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="{{ route('repair-tickets.pdf', $repairTicket) }}" class="btn btn-gradient-secondary w-100 mb-2">
                            <i class="fas fa-file-pdf"></i> Télécharger PDF
                        </a>
                        <form action="{{ route('repair-tickets.destroy', $repairTicket) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-gradient-danger w-100 delete-btn">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Gradient de fond pour le container */
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Cartes modernes */
        .card-modern {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }

        .card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(211, 47, 47, 0.15);
        }

        /* Header avec gradient */
        .card-header-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            color: white;
            padding: 15px 20px;
            font-weight: 600;
            font-size: 1.1rem;
            border: none;
        }

        .card-header-gradient i {
            margin-right: 8px;
        }

        /* Carte avec gradient complet */
        .card-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F, #ef4444);
            border: none;
        }

        /* Badges de statut */
        .status-badges {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .badge {
            padding: 10px 20px;
            font-size: 0.95rem;
            border-radius: 25px;
            font-weight: 600;
        }

        .badge-en_attente {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            color: white;
        }

        .badge-en_cours {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
        }

        .badge-termine {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
        }

        .badge-livre {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
        }

        .badge-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }

        .badge-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        /* Items d'information */
        .info-item {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            background: linear-gradient(135deg, rgba(194, 24, 91, 0.05), rgba(211, 47, 47, 0.05));
            transform: translateX(5px);
        }

        .info-item i {
            font-size: 1.5rem;
            margin-top: 5px;
        }

        .icon-gradient {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .info-item small {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        /* Informations financières */
        .financial-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }

        .financial-item {
            margin: 20px 0;
        }

        .financial-item small {
            display: block;
            opacity: 0.9;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Items de date */
        .date-item {
            display: flex;
            gap: 15px;
            align-items: flex-start;
            padding: 15px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .date-item:last-child {
            border-bottom: none;
        }

        .date-item i {
            font-size: 1.5rem;
            margin-top: 5px;
        }

        /* Détails de réparation */
        .details-content {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border-left: 4px solid #D32F2F;
            line-height: 1.8;
        }

        /* Boutons avec gradient */
        .btn-gradient-back {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient-back:hover {
            background: linear-gradient(135deg, #4b5563, #374151);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #C2185B, #D32F2F);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #D32F2F, #ef4444);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
        }

        .btn-gradient-secondary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient-secondary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-gradient-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-gradient-danger:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .status-badges {
                justify-content: center;
            }
            
            .info-item {
                flex-direction: column;
                text-align: center;
            }
            
            .date-item {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>

    <script>
        // Confirmation de suppression avec SweetAlert2
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#D32F2F',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-layout>