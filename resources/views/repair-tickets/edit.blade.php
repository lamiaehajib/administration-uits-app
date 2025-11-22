<x-app-layout>
<style>
    :root {
        --primary: #D32F2F;
        --primary-dark: #C2185B;
        --danger: #ef4444;
        --gradient: linear-gradient(135deg, #C2185B, #D32F2F);
    }

    /* Page Header */
    .page-header {
        background: var(--gradient);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        box-shadow: 0 10px 40px rgba(211, 47, 47, 0.3);
    }

    .page-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-header .ticket-id {
        background: rgba(255,255,255,0.2);
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 16px;
    }

    .page-header p {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-header {
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: 2px solid rgba(255,255,255,0.3);
        font-size: 14px;
    }

    .btn-header.back {
        background: rgba(255,255,255,0.2);
        color: white;
    }

    .btn-header.view {
        background: white;
        color: var(--primary);
        border: none;
    }

    .btn-header:hover {
        transform: translateY(-2px);
    }

    .btn-header.back:hover {
        background: white;
        color: var(--primary);
    }

    /* Ticket Status Banner */
    .status-banner {
        border-radius: 16px;
        padding: 20px 25px;
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .status-banner.en_attente { background: linear-gradient(135deg, #fef3c7, #fde68a); }
    .status-banner.en_cours { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
    .status-banner.termine { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
    .status-banner.livre { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); }

    .status-banner .status-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .status-banner .status-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .status-banner.en_attente .status-icon { background: #fbbf2420; color: #d97706; }
    .status-banner.en_cours .status-icon { background: #3b82f620; color: #2563eb; }
    .status-banner.termine .status-icon { background: #10b98120; color: #059669; }
    .status-banner.livre .status-icon { background: #6366f120; color: #4f46e5; }

    .status-banner h4 {
        margin: 0;
        font-size: 18px;
        color: #1f2937;
    }

    .status-banner p {
        margin: 3px 0 0 0;
        font-size: 13px;
        color: #6b7280;
    }

    .status-banner .created-date {
        text-align: right;
        font-size: 13px;
        color: #6b7280;
    }

    .status-banner .created-date strong {
        display: block;
        color: #374151;
        font-size: 15px;
    }

    /* Form Container */
    .form-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* Form Sections */
    .form-section {
        padding: 30px;
        border-bottom: 2px solid #f3f4f6;
    }

    .form-section:last-child {
        border-bottom: none;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px dashed #e5e7eb;
    }

    .section-title .icon {
        width: 45px;
        height: 45px;
        background: var(--gradient);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .section-title h4 {
        margin: 0;
        color: #1f2937;
        font-weight: 600;
        font-size: 18px;
    }

    .section-title p {
        margin: 3px 0 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .form-grid.three-cols {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    /* Form Group */
    .form-group {
        position: relative;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group label .required {
        color: var(--danger);
        margin-left: 3px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f9fafb;
        color: #1f2937;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    /* Input with prefix */
    .input-with-prefix {
        position: relative;
    }

    .input-with-prefix input {
        padding-left: 50px;
    }

    .input-with-prefix .prefix {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #C2185B10, #D32F2F10);
        border-radius: 12px 0 0 12px;
        color: var(--primary);
        font-weight: 600;
        font-size: 14px;
    }

    /* Money Input */
    .money-input {
        position: relative;
    }

    .money-input input {
        padding-right: 50px;
    }

    .money-input .suffix {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-weight: 600;
        font-size: 14px;
    }

    /* Device Type Icons */
    .device-types {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 10px;
    }

    .device-type-option {
        position: relative;
    }

    .device-type-option input {
        position: absolute;
        opacity: 0;
    }

    .device-type-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: none;
        letter-spacing: normal;
    }

    .device-type-option label i {
        font-size: 28px;
        color: #9ca3af;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .device-type-option label span {
        font-size: 11px;
        color: #6b7280;
    }

    .device-type-option input:checked + label {
        border-color: var(--primary);
        background: linear-gradient(135deg, #fdf2f8, #fff1f2);
    }

    .device-type-option input:checked + label i {
        color: var(--primary);
    }

    /* Status Select Custom */
    .status-options {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .status-option {
        position: relative;
    }

    .status-option input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .status-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px 10px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: none;
        letter-spacing: normal;
    }

    .status-option label i {
        font-size: 24px;
        margin-bottom: 8px;
    }

    .status-option label span {
        font-size: 12px;
        font-weight: 600;
    }

    .status-option input:checked + label {
        border-color: var(--primary);
        background: linear-gradient(135deg, #fdf2f8, #fff1f2);
    }

    .status-option.en_attente label i { color: #d97706; }
    .status-option.en_cours label i { color: #2563eb; }
    .status-option.termine label i { color: #059669; }
    .status-option.livre label i { color: #4f46e5; }

    .status-option input:checked + label i {
        color: var(--primary);
    }

    /* Calculator Preview */
    .calculator-preview {
        background: linear-gradient(135deg, #f8f9fa, #f3f4f6);
        border-radius: 16px;
        padding: 25px;
        margin-top: 20px;
    }

    .calculator-preview h5 {
        margin: 0 0 20px 0;
        color: #374151;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .calculator-preview h5 i {
        color: var(--primary);
    }

    .calc-row {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #e5e7eb;
    }

    .calc-row:last-child {
        border-bottom: none;
        padding-top: 15px;
        margin-top: 5px;
        border-top: 2px solid #e5e7eb;
    }

    .calc-row .label {
        color: #6b7280;
        font-size: 14px;
    }

    .calc-row .value {
        font-weight: 700;
        font-family: 'Courier New', monospace;
        font-size: 16px;
    }

    .calc-row .value.total { color: #1f2937; }
    .calc-row .value.avance { color: #059669; }
    .calc-row .value.reste { color: var(--danger); }

    /* History Timeline */
    .history-section {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
    }

    .history-section h5 {
        margin: 0 0 15px 0;
        font-size: 14px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .history-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .history-item:last-child {
        border-bottom: none;
    }

    .history-item .icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
    }

    .history-item .icon.create { background: #d1fae5; color: #059669; }
    .history-item .icon.update { background: #dbeafe; color: #2563eb; }

    .history-item .content {
        flex: 1;
    }

    .history-item .content p {
        margin: 0;
        font-size: 13px;
        color: #374151;
    }

    .history-item .content span {
        font-size: 11px;
        color: #9ca3af;
    }

    /* Form Actions */
    .form-actions {
        background: #f8f9fa;
        padding: 25px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .btn-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-submit {
        background: var(--gradient);
        color: white;
        padding: 14px 35px;
        border-radius: 12px;
        border: none;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(211, 47, 47, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #6b7280;
        padding: 14px 25px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-secondary:hover {
        border-color: var(--primary);
        color: var(--primary);
    }

    .btn-delete {
        background: #fee2e2;
        color: #dc2626;
        padding: 14px 25px;
        border-radius: 12px;
        border: none;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: #dc2626;
        color: white;
    }

    /* Error Messages */
    .error-message {
        color: var(--danger);
        font-size: 12px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-group.has-error input,
    .form-group.has-error select,
    .form-group.has-error textarea {
        border-color: var(--danger);
        background: #fef2f2;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-header {
            padding: 20px;
        }

        .page-header h2 {
            font-size: 22px;
        }

        .form-section {
            padding: 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .status-options {
            grid-template-columns: repeat(2, 1fr);
        }

        .device-types {
            grid-template-columns: repeat(3, 1fr);
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-group {
            width: 100%;
        }

        .btn-submit, .btn-secondary, .btn-delete {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2>
            <i class="fas fa-edit"></i> 
            Modifier Ticket 
            <span class="ticket-id">#{{ $repairTicket->id }}</span>
        </h2>
        <p>Dernière modification: {{ $repairTicket->updated_at->format('d/m/Y à H:i') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('repair-tickets.show', $repairTicket) }}" class="btn-header view">
            <i class="fas fa-eye"></i> Voir Détails
        </a>
        <a href="{{ route('repair-tickets.index') }}" class="btn-header back">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<!-- Status Banner -->
<div class="status-banner {{ $repairTicket->status }}">
    <div class="status-info">
        <div class="status-icon">
            @switch($repairTicket->status)
                @case('en_attente')
                    <i class="fas fa-clock"></i>
                    @break
                @case('en_cours')
                    <i class="fas fa-tools"></i>
                    @break
                @case('termine')
                    <i class="fas fa-check-circle"></i>
                    @break
                @case('livre')
                    <i class="fas fa-box"></i>
                    @break
            @endswitch
        </div>
        <div>
            <h4>{{ ucfirst(str_replace('_', ' ', $repairTicket->status)) }}</h4>
            <p>{{ $repairTicket->nom_complet }} • {{ $repairTicket->device_type }}</p>
        </div>
    </div>
    <div class="created-date">
        <span>Créé le</span>
        <strong>{{ $repairTicket->created_at->format('d/m/Y') }}</strong>
    </div>
</div>

<!-- Form Container -->
<form action="{{ route('repair-tickets.update', $repairTicket) }}" method="POST" id="ticketForm">
    @csrf
    @method('PUT')
    <div class="form-container">
        
        <!-- Section 1: Client Info -->
        <div class="form-section">
            <div class="section-title">
                <div class="icon"><i class="fas fa-user"></i></div>
                <div>
                    <h4>Informations Client</h4>
                    <p>Coordonnées du client</p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group @error('nom_complet') has-error @enderror">
                    <label>Nom Complet <span class="required">*</span></label>
                    <input type="text" name="nom_complet" value="{{ old('nom_complet', $repairTicket->nom_complet) }}" placeholder="Ex: Mohammed Alami">
                    @error('nom_complet')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group @error('phone') has-error @enderror">
                    <label>Téléphone</label>
                    <div class="input-with-prefix">
                        <span class="prefix">+212</span>
                        <input type="text" name="phone" value="{{ old('phone', $repairTicket->phone) }}" placeholder="6XX XXX XXX">
                    </div>
                    @error('phone')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Device Info -->
        <div class="form-section">
            <div class="section-title">
                <div class="icon"><i class="fas fa-mobile-alt"></i></div>
                <div>
                    <h4>Informations Appareil</h4>
                    <p>Détails de l'appareil à réparer</p>
                </div>
            </div>
            
            <!-- Device Type Selection -->
            <div class="form-group full-width" style="margin-bottom: 25px;">
                <label>Type d'Appareil <span class="required">*</span></label>
                <div class="device-types">
                    @php
                        $deviceTypes = ['Téléphone', 'Tablette', 'PC Portable', 'PC Bureau', 'Console', 'Autre'];
                        $deviceIcons = ['fa-mobile-alt', 'fa-tablet-alt', 'fa-laptop', 'fa-desktop', 'fa-gamepad', 'fa-cog'];
                    @endphp
                    @foreach($deviceTypes as $index => $type)
                    <div class="device-type-option">
                        <input type="radio" name="device_type" id="type_{{ $index }}" value="{{ $type }}" 
                            {{ old('device_type', $repairTicket->device_type) == $type ? 'checked' : '' }}>
                        <label for="type_{{ $index }}">
                            <i class="fas {{ $deviceIcons[$index] }}"></i>
                            <span>{{ $type }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
                @error('device_type')
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-grid">
                <div class="form-group @error('device_brand') has-error @enderror">
                    <label>Marque</label>
                    <select name="device_brand">
                        <option value="">Sélectionner une marque</option>
                        @php
                            $brands = ['Apple', 'Samsung', 'Huawei', 'Xiaomi', 'Oppo', 'OnePlus', 'HP', 'Dell', 'Lenovo', 'Asus','Acer', 'Sony', 'Autre'];
                        @endphp
                        @foreach($brands as $brand)
                        <option value="{{ $brand }}" {{ old('device_brand', $repairTicket->device_brand) == $brand ? 'selected' : '' }}>
                            {{ $brand }}
                        </option>
                        @endforeach
                    </select>
                    @error('device_brand')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group full-width @error('problem_description') has-error @enderror" style="margin-top: 20px;">
                <label>Description du Problème</label>
                <textarea name="problem_description" placeholder="Décrivez le problème en détail...">{{ old('problem_description', $repairTicket->problem_description) }}</textarea>
                @error('problem_description')
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Section 3: Dates -->
        <div class="form-section">
            <div class="section-title">
                <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <h4>Dates & Délais</h4>
                    <p>Planification de la réparation</p>
                </div>
            </div>
            <div class="form-grid three-cols">
                <div class="form-group @error('date_depot') has-error @enderror">
                    <label>Date de Dépôt <span class="required">*</span></label>
                    <input type="date" name="date_depot" value="{{ old('date_depot', $repairTicket->date_depot) }}">
                    @error('date_depot')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group @error('time_depot') has-error @enderror">
                    <label>Heure de Dépôt <span class="required">*</span></label>
                    <input type="time" name="time_depot" value="{{ old('time_depot', $repairTicket->time_depot) }}">
                    @error('time_depot')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group @error('estimated_completion') has-error @enderror">
                    <label>Date Estimée de Fin</label>
                    <input type="date" name="estimated_completion" value="{{ old('estimated_completion', $repairTicket->estimated_completion) }}">
                    @error('estimated_completion')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 4: Payment -->
        <div class="form-section">
            <div class="section-title">
                <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                <div>
                    <h4>Informations de Paiement</h4>
                    <p>Montants et avances</p>
                </div>
            </div>
            <div class="form-grid">
                <div class="form-group @error('montant_total') has-error @enderror">
                    <label>Montant Total <span class="required">*</span></label>
                    <div class="money-input">
                        <input type="number" name="montant_total" id="montant_total" 
                            value="{{ old('montant_total', $repairTicket->montant_total) }}" step="0.01" min="0">
                        <span class="suffix">DH</span>
                    </div>
                    @error('montant_total')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group @error('avance') has-error @enderror">
                    <label>Avance <span class="required">*</span></label>
                    <div class="money-input">
                        <input type="number" name="avance" id="avance" 
                            value="{{ old('avance', $repairTicket->avance) }}" step="0.01" min="0">
                        <span class="suffix">DH</span>
                    </div>
                    @error('avance')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Calculator Preview -->
            <div class="calculator-preview">
                <h5><i class="fas fa-calculator"></i> Résumé Paiement</h5>
                <div class="calc-row">
                    <span class="label">Montant Total</span>
                    <span class="value total" id="preview_total">0.00 DH</span>
                </div>
                <div class="calc-row">
                    <span class="label">Avance Payée</span>
                    <span class="value avance" id="preview_avance">0.00 DH</span>
                </div>
                <div class="calc-row">
                    <span class="label"><strong>Reste à Payer</strong></span>
                    <span class="value reste" id="preview_reste">0.00 DH</span>
                </div>
            </div>

            <!-- Payment History -->
            <div class="history-section">
                <h5><i class="fas fa-history"></i> Historique</h5>
                <div class="history-item">
                    <div class="icon create"><i class="fas fa-plus"></i></div>
                    <div class="content">
                        <p>Ticket créé</p>
                        <span>{{ $repairTicket->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
                @if($repairTicket->updated_at != $repairTicket->created_at)
                <div class="history-item">
                    <div class="icon update"><i class="fas fa-edit"></i></div>
                    <div class="content">
                        <p>Dernière modification</p>
                        <span>{{ $repairTicket->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Section 5: Status & Notes -->
        <div class="form-section">
            <div class="section-title">
                <div class="icon"><i class="fas fa-tasks"></i></div>
                <div>
                    <h4>Status & Notes</h4>
                    <p>État actuel et remarques</p>
                </div>
            </div>

            <!-- Status Selection -->
            <div class="form-group @error('status') has-error @enderror" style="margin-bottom: 25px;">
                <label>Status <span class="required">*</span></label>
                <div class="status-options">
                    <div class="status-option en_attente">
                        <input type="radio" name="status" id="status_attente" value="en_attente" 
                            {{ old('status', $repairTicket->status) == 'en_attente' ? 'checked' : '' }}>
                        <label for="status_attente">
                            <i class="fas fa-clock"></i>
                            <span>En Attente</span>
                        </label>
                    </div>
                    <div class="status-option en_cours">
                        <input type="radio" name="status" id="status_cours" value="en_cours" 
                            {{ old('status', $repairTicket->status) == 'en_cours' ? 'checked' : '' }}>
                        <label for="status_cours">
                            <i class="fas fa-tools"></i>
                            <span>En Cours</span>
                        </label>
                    </div>
                    <div class="status-option termine">
                        <input type="radio" name="status" id="status_termine" value="termine" 
                            {{ old('status', $repairTicket->status) == 'termine' ? 'checked' : '' }}>
                        <label for="status_termine">
                            <i class="fas fa-check-circle"></i>
                            <span>Terminé</span>
                        </label>
                    </div>
                    <div class="status-option livre">
                        <input type="radio" name="status" id="status_livre" value="livre" 
                            {{ old('status', $repairTicket->status) == 'livre' ? 'checked' : '' }}>
                        <label for="status_livre">
                            <i class="fas fa-box"></i>
                            <span>Livré</span>
                        </label>
                    </div>
                </div>
                @error('status')
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width @error('details') has-error @enderror">
                <label>Notes Additionnelles</label>
                <textarea name="details" placeholder="Remarques, accessoires laissés, informations supplémentaires...">{{ old('details', $repairTicket->details) }}</textarea>
                @error('details')
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn-delete" onclick="confirmDelete()">
                <i class="fas fa-trash"></i> Supprimer
            </button>
            <div class="btn-group">
                <a href="{{ route('repair-tickets.show', $repairTicket) }}" class="btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Enregistrer les modifications
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Hidden Delete Form -->
<form id="deleteForm" action="{{ route('repair-tickets.destroy', $repairTicket) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    // Calculator Preview
    const montantInput = document.getElementById('montant_total');
    const avanceInput = document.getElementById('avance');
    const previewTotal = document.getElementById('preview_total');
    const previewAvance = document.getElementById('preview_avance');
    const previewReste = document.getElementById('preview_reste');

    function updateCalculator() {
        const montant = parseFloat(montantInput.value) || 0;
        const avance = parseFloat(avanceInput.value) || 0;
        const reste = montant - avance;

        previewTotal.textContent = montant.toFixed(2) + ' DH';
        previewAvance.textContent = avance.toFixed(2) + ' DH';
        previewReste.textContent = reste.toFixed(2) + ' DH';

        if (reste < 0) {
            previewReste.style.color = '#059669';
            previewReste.textContent = '+ ' + Math.abs(reste).toFixed(2) + ' DH (Surplus)';
        } else if (reste === 0) {
            previewReste.style.color = '#059669';
            previewReste.textContent = '0.00 DH (Payé)';
        } else {
            previewReste.style.color = '#ef4444';
        }
    }

    montantInput.addEventListener('input', updateCalculator);
    avanceInput.addEventListener('input', updateCalculator);
    updateCalculator();

    // Delete Confirmation
    function confirmDelete() {
        Swal.fire({
            title: 'Supprimer ce ticket?',
            html: `
                <p style="margin-bottom: 10px;">Vous êtes sur le point de supprimer le ticket:</p>
                <strong style="color: #D32F2F;">#{{ $repairTicket->id }} - {{ $repairTicket->nom_complet }}</strong>
                <p style="margin-top: 10px; color: #6b7280; font-size: 14px;">Cette action est irréversible!</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash"></i> Oui, supprimer!',
            cancelButtonText: 'Annuler',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    }

    // Form Change Detection
    let formChanged = false;
    const form = document.getElementById('ticketForm');
    const initialData = new FormData(form);

    form.addEventListener('change', function() {
        formChanged = true;
    });

    form.addEventListener('input', function() {
        formChanged = true;
    });

    // Warn before leaving if changes were made
    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    // Don't warn when submitting
    form.addEventListener('submit', function() {
        formChanged = false;
    });

    // Auto-format phone number
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 9) value = value.slice(0, 9);
            
            if (value.length > 6) {
                value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
            } else if (value.length > 3) {
                value = value.slice(0, 3) + ' ' + value.slice(3);
            }
            
            e.target.value = value;
        });
    }

    // Status change notification
    const statusInputs = document.querySelectorAll('input[name="status"]');
    const originalStatus = '{{ $repairTicket->status }}';

    statusInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value !== originalStatus) {
                const statusNames = {
                    'en_attente': 'En Attente',
                    'en_cours': 'En Cours',
                    'termine': 'Terminé',
                    'livre': 'Livré'
                };
                
                Swal.fire({
                    icon: 'info',
                    title: 'Changement de status',
                    html: `Status changé de <strong>${statusNames[originalStatus]}</strong> à <strong>${statusNames[this.value]}</strong>`,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    });

    // Quick status buttons (Mark as complete, etc.)
    document.addEventListener('keydown', function(e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            form.submit();
        }
        
        // Ctrl+D to delete (with confirmation)
        if (e.ctrlKey && e.key === 'd') {
            e.preventDefault();
            confirmDelete();
        }
    });

    // Success message if redirected back with success
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Succès!',
        text: '{{ session('success') }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#10b981',
        color: '#fff',
        iconColor: '#fff'
    });
    @endif
</script>

</x-app-layout>