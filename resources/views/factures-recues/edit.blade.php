<x-app-layout>
    <div class="container-fluid">
        <!-- 🔙 Bouton Retour -->
        <div class="mb-3">
            <a href="{{ route('factures-recues.show', $facture->id) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux détails
            </a>
        </div>

        <!-- 🎨 Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #1976D2, #2196F3); border-radius: 15px;">
                    <div class="card-body py-4">
                        <h2 class="text-white mb-2 fw-bold">
                            <i class="fas fa-edit"></i> Modifier la Facture
                        </h2>
                        <p class="text-white-50 mb-0">
                            Facture N° <strong>{{ $facture->numero_facture }}</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📝 Formulaire -->
        <form action="{{ route('factures-recues.update', $facture->id) }}" method="POST" enctype="multipart/form-data" id="editFactureForm">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- Colonne Principale -->
                <div class="col-lg-8">
                    <!-- Section 1: Type de Fournisseur -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold" style="color: #1976D2;">
                                <i class="fas fa-users"></i> Fournisseur
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Type Fournisseur -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">TYPE DE FOURNISSEUR <span class="text-danger">*</span></label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-card p-3 border rounded" style="cursor: pointer;">
                                            <input class="form-check-input" type="radio" name="type_fournisseur" 
                                                   id="typeConsultant" value="consultant" 
                                                   {{ $facture->fournisseur_type === 'App\Models\Consultant' ? 'checked' : '' }} 
                                                   required>
                                            <label class="form-check-label w-100" for="typeConsultant" style="cursor: pointer;">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-user-tie fa-2x text-info"></i>
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
                                            <input class="form-check-input" type="radio" name="type_fournisseur" 
                                                   id="typeFournisseur" value="fournisseur" 
                                                   {{ $facture->fournisseur_type === 'App\Models\Fournisseur' ? 'checked' : '' }} 
                                                   required>
                                            <label class="form-check-label w-100" for="typeFournisseur" style="cursor: pointer;">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        <i class="fas fa-building fa-2x text-primary"></i>
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
                            <div id="consultantSection" style="display: {{ $facture->fournisseur_type === 'App\Models\Consultant' ? 'block' : 'none' }};">
                                <label class="form-label fw-semibold">CONSULTANT <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <select class="form-select select2" id="consultant_id" name="fournisseur_id">
                                        <option value="">-- Sélectionner un consultant --</option>
                                        @foreach($consultants as $consultant)
                                        <option value="{{ $consultant->id }}" 
                                                data-nom="{{ $consultant->nom }}"
                                                data-email="{{ $consultant->email }}" 
                                                data-tel="{{ $consultant->telephone }}"
                                                {{ $facture->fournisseur_type === 'App\Models\Consultant' && $facture->fournisseur_id == $consultant->id ? 'selected' : '' }}>
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
                                <div id="consultantInfo" class="alert alert-info" style="display: {{ $facture->fournisseur_type === 'App\Models\Consultant' ? 'block' : 'none' }};">
                                    <small><i class="fas fa-info-circle"></i> <span id="consultantDetails"></span></small>
                                </div>
                            </div>

                            <!-- Sélection Fournisseur -->
                            <div id="fournisseurSection" style="display: {{ $facture->fournisseur_type === 'App\Models\Fournisseur' ? 'block' : 'none' }};">
                                <label class="form-label fw-semibold">FOURNISSEUR <span class="text-danger">*</span></label>
                                <div class="input-group mb-3">
                                    <select class="form-select select2" id="fournisseur_id" name="fournisseur_id">
                                        <option value="">-- Sélectionner un fournisseur --</option>
                                        @foreach($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}" 
                                                data-nom="{{ $fournisseur->nom_entreprise }}"
                                                data-email="{{ $fournisseur->email }}" 
                                                data-tel="{{ $fournisseur->telephone }}"
                                                {{ $facture->fournisseur_type === 'App\Models\Fournisseur' && $facture->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>
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
                                <div id="fournisseurInfo" class="alert alert-info" style="display: {{ $facture->fournisseur_type === 'App\Models\Fournisseur' ? 'block' : 'none' }};">
                                    <small><i class="fas fa-info-circle"></i> <span id="fournisseurDetails"></span></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Informations Facture -->
                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 10px;">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 fw-bold" style="color: #1976D2;">
                                <i class="fas fa-file-invoice"></i> Informations de la Facture
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Numéro Facture -->
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">NUMÉRO DE FACTURE <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('numero_facture') is-invalid @enderror" 
                                           name="numero_facture" 
                                           id="numero_facture"
                                           value="{{ old('numero_facture', $facture->numero_facture) }}" 
                                           required>
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
                                               value="{{ old('montant_ttc', $facture->montant_ttc) }}" 
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
                                           value="{{ old('date_facture', $facture->date_facture->format('Y-m-d')) }}" 
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
                                           value="{{ old('date_echeance', $facture->date_echeance?->format('Y-m-d')) }}">
                                    @error('date_echeance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Statut -->
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">STATUT <span class="text-danger">*</span></label>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check form-check-card p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="statut" 
                                                       id="statutEnAttente" value="en_attente"
                                                       {{ old('statut', $facture->statut) === 'en_attente' ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label w-100" for="statutEnAttente">
                                                    <i class="fas fa-clock text-warning"></i> En Attente
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-card p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="statut" 
                                                       id="statutPayee" value="payee"
                                                       {{ old('statut', $facture->statut) === 'payee' ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label w-100" for="statutPayee">
                                                    <i class="fas fa-check-circle text-success"></i> Payée
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check form-check-card p-3 border rounded">
                                                <input class="form-check-input" type="radio" name="statut" 
                                                       id="statutAnnulee" value="annulee"
                                                       {{ old('statut', $facture->statut) === 'annulee' ? 'checked' : '' }}
                                                       required>
                                                <label class="form-check-label w-100" for="statutAnnulee">
                                                    <i class="fas fa-times-circle text-danger"></i> Annulée
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('statut')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">DESCRIPTION / OBJET</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Description des services ou matériels facturés...">{{ old('description', $facture->description) }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Fichier PDF -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold">FICHIER PDF</label>
                                    
                                    @if($facture->fichier_pdf)
                                    <div class="alert alert-info mb-3">
                                        <i class="fas fa-file-pdf"></i> 
                                        Fichier actuel: <strong>{{ basename($facture->fichier_pdf) }}</strong>
                                        <a href="{{ route('factures-recues.download', $facture->id) }}" 
                                           class="btn btn-sm btn-outline-primary ms-2" target="_blank">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                    @endif
                                    
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
                                        <i class="fas fa-info-circle"></i> 
                                        {{ $facture->fichier_pdf ? 'Sélectionnez un nouveau fichier pour remplacer l\'ancien' : 'Format PDF uniquement, max 5MB' }}
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
                            <h6 class="mb-0 fw-bold" style="color: #1976D2;">
                                <i class="fas fa-calculator"></i> Résumé
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="summary-item mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Type</small>
                                <div class="fw-semibold" id="summaryType">
                                    @if($facture->fournisseur_type === 'App\Models\Consultant')
                                        <i class="fas fa-user-tie text-info"></i> Consultant
                                    @else
                                        <i class="fas fa-building text-primary"></i> Fournisseur
                                    @endif
                                </div>
                            </div>
                            <div class="summary-item mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Fournisseur</small>
                                <div class="fw-semibold" id="summaryFournisseur">
                                    {{ $facture->nom_fournisseur }}
                                </div>
                            </div>
                            <div class="summary-item mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Numéro Facture</small>
                                <div class="fw-semibold text-primary" id="summaryNumero">
                                    {{ $facture->numero_facture }}
                                </div>
                            </div>
                            <div class="summary-item mb-3 pb-3 border-bottom">
                                <small class="text-muted d-block mb-1">Montant TTC</small>
                                <div class="fs-4 fw-bold text-success" id="summaryMontant">
                                    {{ number_format($facture->montant_ttc, 2) }} DH
                                </div>
                            </div>
                            <div class="summary-item mb-3">
                                <small class="text-muted d-block mb-1">Statut actuel</small>
                                <span class="badge 
                                    @if($facture->statut === 'en_attente') bg-warning text-dark
                                    @elseif($facture->statut === 'payee') bg-success
                                    @else bg-danger
                                    @endif" id="summaryStatut">
                                    <i class="fas 
                                        @if($facture->statut === 'en_attente') fa-clock
                                        @elseif($facture->statut === 'payee') fa-check-circle
                                        @else fa-times-circle
                                        @endif"></i>
                                    {{ ucfirst($facture->statut) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 py-3">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Enregistrer les Modifications
                                </button>
                                <a href="{{ route('factures-recues.show', $facture->id) }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="card border-0 shadow-sm" style="border-radius: 10px; background: linear-gradient(135deg, #1976D215, #2196F315);">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3" style="color: #1976D2;">
                                <i class="fas fa-info-circle"></i> Informations
                            </h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2">
                                    <i class="fas fa-user text-muted"></i> 
                                    Créé par: <strong>{{ $facture->createdBy->name ?? 'N/A' }}</strong>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-calendar text-muted"></i> 
                                    Créé le: <strong>{{ $facture->created_at->format('d/m/Y H:i') }}</strong>
                                </li>
                                @if($facture->updated_at != $facture->created_at)
                                <li class="mb-0">
                                    <i class="fas fa-edit text-muted"></i> 
                                    Modifié le: <strong>{{ $facture->updated_at->format('d/m/Y H:i') }}</strong>
                                </li>
                                @endif
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
                <div class="modal-header" style="background: linear-gradient(135deg, #1976D2, #2196F3);">
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
                    <button type="button" class="btn btn-primary" id="saveConsultant">
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
                <div class="modal-header" style="background: linear-gradient(135deg, #1976D2, #2196F3);">
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
                    <button type="button" class="btn btn-primary" id="saveFournisseur">
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
    // 2. AFFICHER LES INFOS DU FOURNISSEUR AU CHARGEMENT
    // ============================================
    function updateFournisseurInfo() {
        const type = $('input[name="type_fournisseur"]:checked').val();
        
        if (type === 'consultant') {
            const selected = $('#consultant_id').find(':selected');
            if (selected.val()) {
                const email = selected.data('email') || '';
                const tel = selected.data('tel') || '';
                const nom = selected.text().split(' - ')[0].trim();
                
                let info = nom;
                if (email) info += ` | ${email}`;
                if (tel) info += ` | ${tel}`;
                
                $('#consultantDetails').text(info);
            }
        } else {
            const selected = $('#fournisseur_id').find(':selected');
            if (selected.val()) {
                const email = selected.data('email') || '';
                const tel = selected.data('tel') || '';
                const nom = selected.text().split(' - ')[0].trim();
                
                let info = nom;
                if (email) info += ` | ${email}`;
                if (tel) info += ` | ${tel}`;
                
                $('#fournisseurDetails').text(info);
            }
        }
    }
    
    // Initialiser les infos au chargement de la page
    updateFournisseurInfo();

    // ============================================
    // 3. TOGGLE CONSULTANT/FOURNISSEUR SECTIONS
    // ============================================
    $('input[name="type_fournisseur"]').on('change', function() {
        const type = $(this).val();
        
        if (type === 'consultant') {
            $('#consultantSection').slideDown(300);
            $('#fournisseurSection').slideUp(300);
            
            // Gérer les champs required
            $('#consultant_id').prop('required', true);
            $('#fournisseur_id').prop('required', false).val(null).trigger('change');
            
            // Mettre à jour le résumé
            $('#summaryType').html('<i class="fas fa-user-tie text-info"></i> Consultant');
        } else {
            $('#fournisseurSection').slideDown(300);
            $('#consultantSection').slideUp(300);
            
            // Gérer les champs required
            $('#fournisseur_id').prop('required', true);
            $('#consultant_id').prop('required', false).val(null).trigger('change');
            
            // Mettre à jour le résumé
            $('#summaryType').html('<i class="fas fa-building text-primary"></i> Fournisseur');
        }
        
        // Masquer les infos
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
        } else {
            $('#consultantInfo, #fournisseurInfo').slideUp(300);
            $('#summaryFournisseur').html('<i class="fas fa-question-circle text-muted"></i> Non sélectionné');
        }
    });

    // ============================================
    // 5. UPDATE MONTANT SUMMARY
    // ============================================
    $('input[name="montant_ttc"]').on('input', function() {
        const montant = parseFloat($(this).val()) || 0;
        $('#summaryMontant').text(montant.toFixed(2) + ' DH');
    });

    // ============================================
    // 6. UPDATE STATUT SUMMARY
    // ============================================
    $('input[name="statut"]').on('change', function() {
        const statut = $(this).val();
        let html = '';
        
        if (statut === 'en_attente') {
            html = '<i class="fas fa-clock"></i> En Attente';
            $('#summaryStatut').removeClass().addClass('badge bg-warning text-dark').html(html);
        } else if (statut === 'payee') {
            html = '<i class="fas fa-check-circle"></i> Payée';
            $('#summaryStatut').removeClass().addClass('badge bg-success').html(html);
        } else if (statut === 'annulee') {
            html = '<i class="fas fa-times-circle"></i> Annulée';
            $('#summaryStatut').removeClass().addClass('badge bg-danger').html(html);
        }
    });

    // ============================================
    // 7. UPDATE NUMERO FACTURE SUMMARY
    // ============================================
    $('input[name="numero_facture"]').on('input', function() {
        const numero = $(this).val();
        if (numero) {
            $('#summaryNumero').text(numero);
        } else {
            $('#summaryNumero').html('<i class="fas fa-question-circle text-muted"></i> Non défini');
        }
    });

    // ============================================
    // 8. PDF FILE PREVIEW
    // ============================================
    $('#pdfFile').on('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const fileName = file.name;
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            
            // Vérifier le type de fichier
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
            
            // Vérifier la taille (max 5MB)
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
            
            // Afficher le preview
            $('#pdfFileName').text(`${fileName} (${fileSize} MB)`);
            $('#pdfPreview').slideDown(300);
        }
    });

    // Bouton pour supprimer le PDF sélectionné
    $('#removePdf').on('click', function() {
        $('#pdfFile').val('');
        $('#pdfPreview').slideUp(300);
    });

    // ============================================
    // 9. VALIDATION AVANT SOUMISSION
    // ============================================
    $('#editFactureForm').on('submit', function(e) {
        const type = $('input[name="type_fournisseur"]:checked').val();
        
        // Vérifier le type de fournisseur
        if (!type) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez sélectionner le type de fournisseur'
            });
            return false;
        }
        
        // Vérifier le fournisseur sélectionné
        let fournisseurId;
        let fournisseurName;
        
        if (type === 'consultant') {
            fournisseurId = $('#consultant_id').val();
            fournisseurName = 'consultant';
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
        const numeroFacture = $('input[name="numero_facture"]').val();
        if (!numeroFacture || numeroFacture.trim() === '') {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez saisir le numéro de facture'
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
                text: 'Veuillez saisir un montant valide supérieur à 0'
            });
            return false;
        }
        
        // Vérifier le statut
        const statut = $('input[name="statut"]:checked').val();
        if (!statut) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez sélectionner un statut'
            });
            return false;
        }
        
        // Vérifier les dates
        const dateFacture = $('input[name="date_facture"]').val();
        const dateEcheance = $('input[name="date_echeance"]').val();
        
        if (!dateFacture) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Veuillez saisir la date de facture'
            });
            return false;
        }
        
        // Vérifier que la date d'échéance est après la date de facture
        if (dateEcheance && dateEcheance < dateFacture) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'La date d\'échéance doit être égale ou postérieure à la date de facture'
            });
            return false;
        }
        
        // Afficher le loader pendant la soumission
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
    // 10. SAVE CONSULTANT VIA AJAX
    // ============================================
    $('#saveConsultant').on('click', function() {
        const btn = $(this);
        const form = $('#addConsultantForm');
        const nom = form.find('input[name="nom"]').val();
        
        // Validation
        if (!nom || nom.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Le nom du consultant est requis'
            });
            return;
        }
        
        // Désactiver le bouton
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
        
        // Appel AJAX
        $.ajax({
            url: '/api/consultants',
            method: 'POST',
            data: form.serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success: function(response) {
                if (response.success) {
                    const consultant = response.consultant;
                    
                    // Construire le texte de l'option
                    let optionText = consultant.nom;
                    if (consultant.prenom) {
                        optionText += ' ' + consultant.prenom;
                    }
                    if (consultant.specialite) {
                        optionText += ' - ' + consultant.specialite;
                    }
                    
                    // Créer et ajouter la nouvelle option
                    const newOption = new Option(optionText, consultant.id, true, true);
                    $(newOption).attr('data-email', consultant.email || '');
                    $(newOption).attr('data-tel', consultant.telephone || '');
                    $(newOption).attr('data-nom', consultant.nom);
                    
                    $('#consultant_id').append(newOption).trigger('change');
                    
                    // Fermer le modal
                    $('#addConsultantModal').modal('hide');
                    
                    // Réinitialiser le formulaire
                    form[0].reset();
                    
                    // Message de succès
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
                let errorMessage = 'Une erreur est survenue';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Afficher la première erreur de validation
                    const errors = xhr.responseJSON.errors;
                    const firstError = Object.values(errors)[0];
                    errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: errorMessage
                });
            },
            complete: function() {
                // Réactiver le bouton
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
            }
        });
    });

    // ============================================
    // 11. SAVE FOURNISSEUR VIA AJAX
    // ============================================
    $('#saveFournisseur').on('click', function() {
        const btn = $(this);
        const form = $('#addFournisseurForm');
        const nomEntreprise = form.find('input[name="nom_entreprise"]').val();
        
        // Validation
        if (!nomEntreprise || nomEntreprise.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Attention',
                text: 'Le nom de l\'entreprise est requis'
            });
            return;
        }
        
        // Désactiver le bouton
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enregistrement...');
        
        // Appel AJAX
        $.ajax({
            url: '/api/fournisseurs',
            method: 'POST',
            data: form.serialize() + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            success: function(response) {
                if (response.success) {
                    const fournisseur = response.fournisseur;
                    
                    // Construire le texte de l'option
                    let optionText = fournisseur.nom_entreprise;
                    if (fournisseur.type_materiel) {
                        optionText += ' - ' + fournisseur.type_materiel;
                    }
                    
                    // Créer et ajouter la nouvelle option
                    const newOption = new Option(optionText, fournisseur.id, true, true);
                    $(newOption).attr('data-email', fournisseur.email || '');
                    $(newOption).attr('data-tel', fournisseur.telephone || '');
                    $(newOption).attr('data-nom', fournisseur.nom_entreprise);
                    
                    $('#fournisseur_id').append(newOption).trigger('change');
                    
                    // Fermer le modal
                    $('#addFournisseurModal').modal('hide');
                    
                    // Réinitialiser le formulaire
                    form[0].reset();
                    
                    // Message de succès
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
                let errorMessage = 'Une erreur est survenue';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Afficher la première erreur de validation
                    const errors = xhr.responseJSON.errors;
                    const firstError = Object.values(errors)[0];
                    errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur!',
                    text: errorMessage
                });
            },
            complete: function() {
                // Réactiver le bouton
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Enregistrer');
            }
        });
    });

    // ============================================
    // 12. RESET MODAL FORMS ON CLOSE
    // ============================================
    $('#addConsultantModal').on('hidden.bs.modal', function() {
        $('#addConsultantForm')[0].reset();
    });

    $('#addFournisseurModal').on('hidden.bs.modal', function() {
        $('#addFournisseurForm')[0].reset();
    });

    // ============================================
    // 13. KEYBOARD SHORTCUTS (OPTIONNEL)
    // ============================================
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + S pour sauvegarder
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $('#editFactureForm').submit();
        }
        
        // Escape pour annuler (retour)
        if (e.key === 'Escape' && !$('.modal.show').length) {
            window.location.href = $('#editFactureForm').find('a.btn-outline-secondary').attr('href');
        }
    });

    // ============================================
    // 14. CONFIRMATION AVANT DE QUITTER
    // ============================================
    let formModified = false;
    
    // Détecter les modifications
    $('#editFactureForm input, #editFactureForm select, #editFactureForm textarea').on('change', function() {
        formModified = true;
    });
    
    // Avertir avant de quitter si des modifications non sauvegardées
    $(window).on('beforeunload', function(e) {
        if (formModified) {
            const message = 'Vous avez des modifications non enregistrées. Êtes-vous sûr de vouloir quitter?';
            e.returnValue = message;
            return message;
        }
    });
    
    // Ne pas avertir si le formulaire est soumis
    $('#editFactureForm').on('submit', function() {
        formModified = false;
    });

    // ============================================
    // 15. AUTO-SAVE DRAFT (OPTIONNEL - LOCALSTORAGE)
    // ============================================
    // Note: Cette fonctionnalité sauvegarde temporairement les données
    // dans le navigateur pour éviter la perte de données
    
    function saveDraft() {
        const formData = {
            type_fournisseur: $('input[name="type_fournisseur"]:checked').val(),
            fournisseur_id: $('#consultant_id').val() || $('#fournisseur_id').val(),
            numero_facture: $('input[name="numero_facture"]').val(),
            montant_ttc: $('input[name="montant_ttc"]').val(),
            date_facture: $('input[name="date_facture"]').val(),
            date_echeance: $('input[name="date_echeance"]').val(),
            statut: $('input[name="statut"]:checked').val(),
            description: $('textarea[name="description"]').val()
        };
        
        localStorage.setItem('facture_edit_draft', JSON.stringify(formData));
    }
    
    // Sauvegarder automatiquement toutes les 30 secondes
    setInterval(saveDraft, 30000);
    
    // Nettoyer le draft après soumission réussie
    $('#editFactureForm').on('submit', function() {
        localStorage.removeItem('facture_edit_draft');
    });

    // ============================================
    // 16. ANIMATIONS ET EFFETS VISUELS
    // ============================================
    
    // Animer le bouton de soumission au survol
    $('button[type="submit"]').hover(
        function() {
            $(this).addClass('shadow-lg');
        },
        function() {
            $(this).removeClass('shadow-lg');
        }
    );
    
    // Mettre en évidence les champs modifiés
    $('#editFactureForm input, #editFactureForm select, #editFactureForm textarea').on('change', function() {
        $(this).addClass('border-success').delay(1500).queue(function() {
            $(this).removeClass('border-success').dequeue();
        });
    });

    console.log('✅ Script d\'édition de facture chargé avec succès');
});
</script>
@endpush
</x-app-layout>