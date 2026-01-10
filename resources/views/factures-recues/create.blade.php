<x-app-layout>
    <div class="container-fluid">
        <!-- 🔙 Bouton Retour -->
        <div class="mb-3">
            <a href="{{ route('factures-recues.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <!-- 🎨 Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #C2185B, #D32F2F); border-radius: 15px;">
                    <div class="card-body py-4">
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-plus-circle"></i> Nouvelle Facture Reçue
                        </h2>
                        <p class="text-white-50 mb-0">Enregistrer une nouvelle facture de consultant ou fournisseur</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📝 Formulaire -->
        <form action="{{ route('factures-recues.store') }}" method="POST" enctype="multipart/form-data" id="createFactureForm">
            @csrf
            
            <div class="row g-4">
                <!-- Colonne Principale -->
                <div class="col-lg-8">
                    <!-- Section 1: Type de Fournisseur -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold hight">
                                <i class="fas fa-users"></i> Étape 1: Sélectionner le Fournisseur
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Type Fournisseur -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">TYPE DE FOURNISSEUR <span class="text-danger">*</span></label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-card p-3 border rounded" style="cursor: pointer;">
                                            <input class="form-check-input" type="radio" name="type_fournisseur" id="typeConsultant" value="consultant" required>
                                            <label class="form-check-label w-100" for="typeConsultant" style="cursor: pointer;">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-user-tie fa-2x hight"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="d-block">Consultant</strong>
                                                        <small class="text-muted">Prestation de service</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check form-check-card p-3 border rounded" style="cursor: pointer;">
                                            <input class="form-check-input" type="radio" name="type_fournisseur" id="typeFournisseur" value="fournisseur" required>
                                            <label class="form-check-label w-100" for="typeFournisseur" style="cursor: pointer;">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-building fa-2x hight"></i>
                                                    </div>
                                                    <div>
                                                        <strong class="d-block">Fournisseur</strong>
                                                        <small class="text-muted">Matériel & équipements</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('type_fournisseur')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sélection Consultant -->
                            <div id="consultantSection" style="display: none;">
                                <label class="form-label fw-semibold">CONSULTANT <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <select class="form-select select2" id="consultant_id" name="fournisseur_id">
                                        <option value="">-- Sélectionner un consultant --</option>
                                        @foreach($consultants as $consultant)
                                        <option value="{{ $consultant->id }}" 
                                                data-nom="{{ $consultant->nom }}"
                                                data-email="{{ $consultant->email }}" 
                                                data-tel="{{ $consultant->telephone }}">
                                            {{ $consultant->nom_complet }} 
                                            @if($consultant->specialite)
                                            - {{ $consultant->specialite }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addConsultantModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div id="consultantInfo" class="alert alert-info" style="display: none;">
                                    <small><i class="fas fa-info-circle"></i> <span id="consultantDetails"></span></small>
                                </div>
                            </div>

                            <!-- Sélection Fournisseur -->
                            <div id="fournisseurSection" style="display: none;">
                                <label class="form-label fw-semibold">FOURNISSEUR <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <select class="form-select select2" id="fournisseur_id" name="fournisseur_id">
                                        <option value="">-- Sélectionner un fournisseur --</option>
                                        @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}" 
                                                data-nom="{{ $fournisseur->nom_entreprise }}"
                                                data-email="{{ $fournisseur->email }}" 
                                                data-tel="{{ $fournisseur->telephone }}">
                                            {{ $fournisseur->nom_entreprise }}
                                            @if($fournisseur->type_materiel)
                                            - {{ $fournisseur->type_materiel }}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#addFournisseurModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div id="fournisseurInfo" class="alert alert-info" style="display: none;">
                                    <small><i class="fas fa-info-circle"></i> <span id="fournisseurDetails"></span></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Informations Facture -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold hight">
                                <i class="fas fa-file-invoice"></i> Étape 2: Informations de la Facture
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Numéro Facture (READONLY - AUTO GÉNÉRÉ) -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NUMÉRO DE FACTURE <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light @error('numero_facture') is-invalid @enderror" 
                                               name="numero_facture" 
                                               id="numero_facture"
                                               placeholder="Sera généré automatiquement" 
                                               value="{{ old('numero_facture') }}" 
                                               readonly
                                               required>
                                        <button type="button" class="btn btn-outline-primary" id="regenerateNumero" disabled>
                                            <i class="fas fa-sync"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Le numéro est généré automatiquement selon le format: 
                                        <strong>C[Lettre][ID][JJ][MM][AA]</strong> pour Consultant ou 
                                        <strong>F[Lettre][ID][JJ][MM][AA]</strong> pour Fournisseur
                                    </small>
                                    @error('numero_facture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Montant TTC -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">MONTANT TTC (DH) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" min="0" 
                                               class="form-control @error('montant_ttc') is-invalid @enderror" 
                                               name="montant_ttc" 
                                               placeholder="0.00" 
                                               value="{{ old('montant_ttc') }}" 
                                               required>
                                        <span class="input-group-text">DH</span>
                                        @error('montant_ttc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Date Facture -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">DATE DE FACTURE <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_facture') is-invalid @enderror" 
                                           name="date_facture" 
                                           id="date_facture"
                                           value="{{ old('date_facture', date('Y-m-d')) }}" 
                                           required>
                                    @error('date_facture')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Date Échéance -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">DATE D'ÉCHÉANCE</label>
                                    <input type="date" class="form-control @error('date_echeance') is-invalid @enderror" 
                                           name="date_echeance" 
                                           id="date_echeance"
                                           value="{{ old('date_echeance') }}">
                                    @error('date_echeance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb"></i> Conseil: +30 jours
                                        <a href="#" id="set30Days" class="text-decoration-none">(Appliquer)</a>
                                    </small>
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">DESCRIPTION / OBJET</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Description des services ou matériels facturés...">{{ old('description') }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fichier PDF -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">FICHIER PDF</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control @error('fichier_pdf') is-invalid @enderror" 
                                               name="fichier_pdf" 
                                               accept=".pdf"
                                               id="pdfFile">
                                        <label class="input-group-text" for="pdfFile">
                                            <i class="fas fa-upload"></i>
                                        </label>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Format PDF uniquement, max 5MB
                                    </small>
                                    @error('fichier_pdf')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                    <div id="pdfPreview" class="mt-2" style="display: none;">
                                        <div class="alert alert-success mb-0">
                                            <i class="fas fa-file-pdf"></i> 
                                            <span id="pdfFileName"></span>
                                            <button type="button" class="btn-close float-end" id="removePdf"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colonne Sidebar -->
                <div class="col-lg-4">
                    <!-- Résumé -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px; position: sticky; top: 20px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="mb-0 fw-bold hight">
                                <i class="fas fa-calculator"></i> Résumé
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="summary-item mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Type</small>
                                <div class="fw-semibold" id="summaryType">
                                    <i class="fas fa-question-circle text-muted"></i> Non sélectionné
                                </div>
                            </div>
                            <div class="summary-item mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Fournisseur</small>
                                <div class="fw-semibold" id="summaryFournisseur">
                                    <i class="fas fa-question-circle text-muted"></i> Non sélectionné
                                </div>
                            </div>
                            <div class="summary-item mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Numéro Facture</small>
                                <div class="fw-semibold text-primary" id="summaryNumero">
                                    <i class="fas fa-hourglass-half text-muted"></i> En attente...
                                </div>
                            </div>
                            <div class="summary-item mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Montant TTC</small>
                                <div class="fs-4 fw-bold text-success" id="summaryMontant">0.00 DH</div>
                            </div>
                            <div class="summary-item mb-3">
                                <small class="text-muted d-block mb-1">Statut initial</small>
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-clock"></i> En Attente
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 py-3">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger btn-lg">
                                    <i class="fas fa-save"></i> Enregistrer la Facture
                                </button>
                                <a href="{{ route('factures-recues.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Aide -->
                    <div class="card border-0 shadow-sm" style="border-radius: 10px; background: linear-gradient(135deg, #C2185B15, #D32F2F15);">
                        <div class="card-body">
                            <h6 class="fw-bold hight mb-3">
                                <i class="fas fa-question-circle"></i> Format du Numéro
                            </h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i> <strong>Consultant:</strong> C[Lettre][ID][JJ][MM][AA]
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success"></i> <strong>Fournisseur:</strong> F[Lettre][ID][JJ][MM][AA]
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-info-circle text-info"></i> Exemple: <code>CY3100126</code>
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-info-circle text-info"></i> La lettre est prise du nom
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Ajouter Consultant -->
    <div class="modal fade" id="addConsultantModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-user-tie"></i> Ajouter un Consultant
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addConsultantForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Prénom</label>
                                <input type="text" class="form-control" name="prenom">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Téléphone</label>
                                <input type="text" class="form-control" name="telephone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Spécialité</label>
                                <input type="text" class="form-control" name="specialite">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">CIN</label>
                                <input type="text" class="form-control" name="cin">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tarif Horaire (DH)</label>
                                <input type="number" step="0.01" class="form-control" name="tarif_heure">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="saveConsultant">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Fournisseur -->
    <div class="modal fade" id="addFournisseurModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-building"></i> Ajouter un Fournisseur
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addFournisseurForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nom Entreprise <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nom_entreprise" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Contact Nom</label>
                                <input type="text" class="form-control" name="contact_nom">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Téléphone</label>
                                <input type="text" class="form-control" name="telephone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">ICE</label>
                                <input type="text" class="form-control" name="ice">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">IF</label>
                                <input type="text" class="form-control" name="if">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Type de Matériel</label>
                                <input type="text" class="form-control" name="type_materiel">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-danger" id="saveFournisseur">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
$(document).ready(function() {
    // ============================================
    // 1. INITIALIZE SELECT2
    // ============================================
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Sélectionner...',
        allowClear: true
    });

    // ============================================
    // 2. FONCTION: GÉNÉRER NUMÉRO FACTURE
    // ============================================
    function genererNumeroFacture() {
        const type = $('input[name="type_fournisseur"]:checked').val();
        
        // CORRECTION: Récupérer le bon ID selon le type
        let fournisseurId;
        if (type === 'consultant') {
            fournisseurId = $('#consultant_id').val();
        } else if (type === 'fournisseur') {
            fournisseurId = $('#fournisseur_id').val();
        }
        
        const dateFacture = $('#date_facture').val();

        // Validation des champs requis
        if (!type || !fournisseurId || !dateFacture) {
            $('#numero_facture').val('').attr('placeholder', 'Sélectionnez fournisseur et date');
            $('#summaryNumero').html('<i class="fas fa-hourglass-half text-muted"></i> En attente...');
            $('#regenerateNumero').prop('disabled', true);
            return;
        }

        // Activer le bouton de régénération
        $('#regenerateNumero').prop('disabled', false);

        // Appel AJAX pour générer le numéro
        $.ajax({
            url: '/factures-recues/generate-numero',
            method: 'POST',
            data: {
                type_fournisseur: type,
                fournisseur_id: fournisseurId,
                date_facture: dateFacture,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#numero_facture').val('Génération...').addClass('border-warning');
                $('#summaryNumero').html('<i class="fas fa-spinner fa-spin text-warning"></i> Génération...');
            },
            success: function(response) {
                if (response.success) {
                    $('#numero_facture').val(response.numero_facture).removeClass('border-warning');
                    $('#summaryNumero').html('<i class="fas fa-check-circle text-success"></i> ' + response.numero_facture);
                    
                    // Animation de succès
                    $('#numero_facture').addClass('border-success');
                    setTimeout(() => {
                        $('#numero_facture').removeClass('border-success');
                    }, 1500);
                }
            },
            error: function(xhr) {
                console.error('Erreur génération numéro:', xhr);
                $('#numero_facture').val('').removeClass('border-warning');
                $('#summaryNumero').html('<i class="fas fa-exclamation-circle text-danger"></i> Erreur');
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: xhr.responseJSON?.message || 'Impossible de générer le numéro de facture'
                });
            }
        });
    }

    // ============================================
    // 3. TOGGLE CONSULTANT/FOURNISSEUR SECTIONS
    // ============================================
    $('input[name="type_fournisseur"]').on('change', function() {
        const type = $(this).val();
        
        if (type === 'consultant') {
            $('#consultantSection').slideDown(300);
            $('#fournisseurSection').slideUp(300);
            
            // CORRECTION: Gérer les champs required
            $('#consultant_id').prop('required', true);
            $('#fournisseur_id').prop('required', false).val(null).trigger('change');
            
            $('#summaryType').html('<i class="fas fa-user-tie text-info"></i> Consultant');
        } else {
            $('#fournisseurSection').slideDown(300);
            $('#consultantSection').slideUp(300);
            
            // CORRECTION: Gérer les champs required
            $('#fournisseur_id').prop('required', true);
            $('#consultant_id').prop('required', false).val(null).trigger('change');
            
            $('#summaryType').html('<i class="fas fa-building text-primary"></i> Fournisseur');
        }
        
        // Reset
        $('#summaryFournisseur').html('<i class="fas fa-question-circle text-muted"></i> Non sélectionné');
        $('#numero_facture').val('');
        $('#summaryNumero').html('<i class="fas fa-hourglass-half text-muted"></i> En attente...');
        $('#consultantInfo, #fournisseurInfo').slideUp(300);
    });

    // ============================================
    // 4. CONSULTANT/FOURNISSEUR CHANGE
    // ============================================
    $('#consultant_id, #fournisseur_id').on('change', function() {
        const selected = $(this).find(':selected');
        const nom = selected.text().split(' - ')[0].trim();
        const email = selected.data('email') || '';
        const tel = selected.data('tel') || '';
        const isConsultant = $(this).attr('id') === 'consultant_id';
        
        if ($(this).val()) {
            let info = nom;
            if (email) info += ` | ${email}`;
            if (tel) info += ` | ${tel}`;
            
            if (isConsultant) {
                $('#consultantDetails').text(info);
                $('#consultantInfo').slideDown(300);
                $('#summaryFournisseur').html(`<i class="fas fa-user-tie text-info"></i> ${nom}`);
            } else {
                $('#fournisseurDetails').text(info);
                $('#fournisseurInfo').slideDown(300);
                $('#summaryFournisseur').html(`<i class="fas fa-building text-primary"></i> ${nom}`);
            }
            
            // Générer le numéro de facture
            genererNumeroFacture();
        } else {
            $('#consultantInfo, #fournisseurInfo').slideUp(300);
            $('#summaryFournisseur').html('<i class="fas fa-question-circle text-muted"></i> Non sélectionné');
            $('#numero_facture').val('');
            $('#summaryNumero').html('<i class="fas fa-hourglass-half text-muted"></i> En attente...');
        }
    });

    // ============================================
    // 5. RÉGÉNÉRER LE NUMÉRO (BOUTON)
    // ============================================
    $('#regenerateNumero').on('click', function(e) {
        e.preventDefault();
        genererNumeroFacture();
    });

    // ============================================
    // 6. GÉNÉRER QUAND DATE CHANGE
    // ============================================
    $('#date_facture').on('change', function() {
        const type = $('input[name="type_fournisseur"]:checked').val();
        const fournisseurId = type === 'consultant' ? $('#consultant_id').val() : $('#fournisseur_id').val();
        
        if (type && fournisseurId) {
            genererNumeroFacture();
        }
    });

    // ============================================
    // 7. UPDATE MONTANT SUMMARY
    // ============================================
    $('input[name="montant_ttc"]').on('input', function() {
        const montant = parseFloat($(this).val()) || 0;
        $('#summaryMontant').text(montant.toFixed(2) + ' DH');
    });

    // ============================================
    // 8. VALIDATION AVANT SOUMISSION - CORRECTION IMPORTANTE
    // ============================================
    $('#createFactureForm').on('submit', function(e) {
        const type = $('input[name="type_fournisseur"]:checked').val();
        
        // Vérifier le type
        if (!type) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez sélectionner le type de fournisseur'
            });
            return false;
        }
        
        // CORRECTION: Vérifier le bon champ selon le type
        let fournisseurId;
        let fournisseurName;
        
        if (type === 'consultant') {
            fournisseurId = $('#consultant_id').val();
            fournisseurName = 'consultant';
            
            // IMPORTANT: S'assurer que le bon name est utilisé pour la soumission
            if (fournisseurId) {
                // Créer un input hidden avec le bon name
                $('input[name="fournisseur_id"]').remove();
                $('<input>').attr({
                    type: 'hidden',
                    name: 'fournisseur_id',
                    value: fournisseurId
                }).appendTo(this);
            }
        } else {
            fournisseurId = $('#fournisseur_id').val();
            fournisseurName = 'fournisseur';
        }
        
        if (!fournisseurId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: `Veuillez sélectionner un ${fournisseurName}`
            });
            return false;
        }
        
        // Vérifier le numéro de facture
        const numeroFacture = $('#numero_facture').val();
        if (!numeroFacture || numeroFacture === 'Génération...') {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Le numéro de facture n\'a pas été généré. Veuillez patienter ou cliquer sur régénérer.'
            });
            return false;
        }
        
        // Vérifier le montant
        const montant = parseFloat($('input[name="montant_ttc"]').val());
        if (!montant || montant <= 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez saisir un montant valide'
            });
            return false;
        }
        
        // Show loading
        Swal.fire({
            title: 'Enregistrement en cours...',
            html: 'Veuillez patienter',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        return true;
    });

    // ============================================
    // 9. SET 30 DAYS ECHEANCE
    // ============================================
    $('#set30Days').on('click', function(e) {
        e.preventDefault();
        const dateFacture = $('#date_facture').val();
        
        if (dateFacture) {
            const date = new Date(dateFacture);
            date.setDate(date.getDate() + 30);
            const formattedDate = date.toISOString().split('T')[0];
            $('#date_echeance').val(formattedDate);
            
            Swal.fire({
                icon: 'success',
                title: 'Date mise à jour!',
                text: 'Échéance fixée à +30 jours',
                timer: 1500,
                showConfirmButton: false
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez d\'abord saisir la date de facture'
            });
        }
    });

    // ============================================
    // 10. PDF FILE PREVIEW
    // ============================================
    $('#pdfFile').on('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            if (file.type !== 'application/pdf') {
                Swal.fire({
                    icon: 'error',
                    title: 'Format invalide',
                    text: 'Veuillez sélectionner un fichier PDF'
                });
                $(this).val('');
                $('#pdfPreview').slideUp();
                return;
            }
            
            if (fileSize > 5) {
                Swal.fire({
                    icon: 'error',
                    title: 'Fichier trop volumineux',
                    text: 'Le fichier ne doit pas dépasser 5MB'
                });
                $(this).val('');
                $('#pdfPreview').slideUp();
                return;
            }
            
            $('#pdfFileName').text(`${fileName} (${fileSize} MB)`);
            $('#pdfPreview').slideDown(300);
        }
    });

    $('#removePdf').on('click', function() {
        $('#pdfFile').val('');
        $('#pdfPreview').slideUp(300);
    });

    // ============================================
    // 11. SAVE CONSULTANT/FOURNISSEUR (AJAX)
    // ============================================
    $('#saveConsultant').on('click', function() {
        const btn = $(this);
        const form = $('#addConsultantForm');
        const nom = form.find('input[name="nom"]').val();
        
        if (!nom) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Le nom est requis'
            });
            return;
        }
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
        
        $.ajax({
            url: '/api/consultants',
            method: 'POST',
            data: form.serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success: function(response) {
                if (response.success) {
                    const consultant = response.consultant;
                    const optionText = consultant.nom + (consultant.prenom ? ' ' + consultant.prenom : '') + 
                                     (consultant.specialite ? ' - ' + consultant.specialite : '');
                    
                    const newOption = new Option(optionText, consultant.id, true, true);
                    $(newOption).attr('data-email', consultant.email || '');
                    $(newOption).attr('data-tel', consultant.telephone || '');
                    $(newOption).attr('data-nom', consultant.nom);
                    
                    $('#consultant_id').append(newOption).trigger('change');
                    $('#addConsultantModal').modal('hide');
                    form[0].reset();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: xhr.responseJSON?.message || 'Une erreur est survenue'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
            }
        });
    });

    $('#saveFournisseur').on('click', function() {
        const btn = $(this);
        const form = $('#addFournisseurForm');
        const nomEntreprise = form.find('input[name="nom_entreprise"]').val();
        
        if (!nomEntreprise) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Le nom de l\'entreprise est requis'
            });
            return;
        }
        
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
        
        $.ajax({
            url: '/api/fournisseurs',
            method: 'POST',
            data: form.serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success: function(response) {
                if (response.success) {
                    const fournisseur = response.fournisseur;
                    const optionText = fournisseur.nom_entreprise + 
                                     (fournisseur.type_materiel ? ' - ' + fournisseur.type_materiel : '');
                    
                    const newOption = new Option(optionText, fournisseur.id, true, true);
                    $(newOption).attr('data-email', fournisseur.email || '');
                    $(newOption).attr('data-tel', fournisseur.telephone || '');
                    $(newOption).attr('data-nom', fournisseur.nom_entreprise);
                    
                    $('#fournisseur_id').append(newOption).trigger('change');
                    $('#addFournisseurModal').modal('hide');
                    form[0].reset();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: xhr.responseJSON?.message || 'Une erreur est survenue'
                });
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
            }
        });
    });

    // Reset modal forms
    $('#addConsultantModal, #addFournisseurModal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });
});
</script>
@endpush
</x-app-layout>