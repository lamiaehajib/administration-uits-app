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

    .page-header p {
        margin: 5px 0 0 0;
        opacity: 0.9;
        font-size: 14px;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: 2px solid rgba(255,255,255,0.3);
    }

    .btn-back:hover {
        background: white;
        color: var(--primary);
        transform: translateY(-2px);
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

    .form-group .input-icon {
        position: absolute;
        right: 15px;
        top: 45px;
        color: #9ca3af;
        pointer-events: none;
    }

    .form-group input[type="date"],
    .form-group input[type="time"] {
        cursor: pointer;
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

    .btn-reset {
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
    }

    .btn-reset:hover {
        border-color: var(--danger);
        color: var(--danger);
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

        .btn-submit, .btn-reset {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div>
        <h2><i class="fas fa-plus-circle"></i> Nouveau Ticket</h2>
        <p>Créer un nouveau ticket de réparation</p>
    </div>
    <a href="{{ route('repair-tickets.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> Retour à la liste
    </a>
</div>

<!-- Form Container -->
<form action="{{ route('repair-tickets.store') }}" method="POST" id="ticketForm">
    @csrf
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
                    <input type="text" name="nom_complet" value="{{ old('nom_complet') }}" placeholder="Ex: Mohammed Alami">
                    <i class="fas fa-user input-icon"></i>
                    @error('nom_complet')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group @error('phone') has-error @enderror">
                    <label>Téléphone</label>
                    <div class="input-with-prefix">
                        <span class="prefix">+212</span>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="6XX XXX XXX">
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
                    <div class="device-type-option">
                        <input type="radio" name="device_type" id="type_phone" value="Téléphone" {{ old('device_type') == 'Téléphone' ? 'checked' : '' }}>
                        <label for="type_phone">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Téléphone</span>
                        </label>
                    </div>
                    <div class="device-type-option">
                        <input type="radio" name="device_type" id="type_tablet" value="Tablette" {{ old('device_type') == 'Tablette' ? 'checked' : '' }}>
                        <label for="type_tablet">
                            <i class="fas fa-tablet-alt"></i>
                            <span>Tablette</span>
                        </label>
                    </div>
                    <div class="device-type-option">
                        <input type="radio" name="device_type" id="type_laptop" value="PC Portable" {{ old('device_type') == 'PC Portable' ? 'checked' : '' }}>
                        <label for="type_laptop">
                            <i class="fas fa-laptop"></i>
                            <span>PC Portable</span>
                        </label>
                    </div>
                    <div class="device-type-option">
                        <input type="radio" name="device_type" id="type_desktop" value="PC Bureau" {{ old('device_type') == 'PC Bureau' ? 'checked' : '' }}>
                        <label for="type_desktop">
                            <i class="fas fa-desktop"></i>
                            <span>PC Bureau</span>
                        </label>
                    </div>
                    <div class="device-type-option">
                        <input type="radio" name="device_type" id="type_console" value="Console" {{ old('device_type') == 'Console' ? 'checked' : '' }}>
                        <label for="type_console">
                            <i class="fas fa-gamepad"></i>
                            <span>Console</span>
                        </label>
                    </div>
                    <div class="device-type-option">
                        <input type="radio" name="device_type" id="type_other" value="Autre" {{ old('device_type') == 'Autre' ? 'checked' : '' }}>
                        <label for="type_other">
                            <i class="fas fa-cog"></i>
                            <span>Autre</span>
                        </label>
                    </div>
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
                        <option value="Apple" {{ old('device_brand') == 'Apple' ? 'selected' : '' }}>Apple</option>
                        <option value="Samsung" {{ old('device_brand') == 'Samsung' ? 'selected' : '' }}>Samsung</option>
                        <option value="Huawei" {{ old('device_brand') == 'Huawei' ? 'selected' : '' }}>Huawei</option>
                        <option value="Xiaomi" {{ old('device_brand') == 'Xiaomi' ? 'selected' : '' }}>Xiaomi</option>
                        <option value="Oppo" {{ old('device_brand') == 'Oppo' ? 'selected' : '' }}>Oppo</option>
                        <option value="OnePlus" {{ old('device_brand') == 'OnePlus' ? 'selected' : '' }}>OnePlus</option>
                        <option value="HP" {{ old('device_brand') == 'HP' ? 'selected' : '' }}>HP</option>
                        <option value="Dell" {{ old('device_brand') == 'Dell' ? 'selected' : '' }}>Dell</option>
                        <option value="Lenovo" {{ old('device_brand') == 'Lenovo' ? 'selected' : '' }}>Lenovo</option>
                        <option value="Asus" {{ old('device_brand') == 'Asus' ? 'selected' : '' }}>Asus</option>
                        <option value="Sony" {{ old('device_brand') == 'Sony' ? 'selected' : '' }}>Sony</option>
                        <option value="Acer" {{ old('device_brand') == 'Acer' ? 'selected' : '' }}>Acer</option>
                        <option value="Autre" {{ old('device_brand') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('device_brand')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group full-width @error('problem_description') has-error @enderror" style="margin-top: 20px;">
                <label>Description du Problème</label>
                <textarea name="problem_description" placeholder="Décrivez le problème en détail...">{{ old('problem_description') }}</textarea>
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
                    <input type="date" name="date_depot" value="{{ old('date_depot', date('Y-m-d')) }}">
                    @error('date_depot')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group @error('time_depot') has-error @enderror">
                    <label>Heure de Dépôt <span class="required">*</span></label>
                    <input type="time" name="time_depot" value="{{ old('time_depot', date('H:i')) }}">
                    @error('time_depot')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group @error('estimated_completion') has-error @enderror">
                    <label>Date Estimée de Fin</label>
                    <input type="date" name="estimated_completion" value="{{ old('estimated_completion') }}">
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
                        <input type="number" name="montant_total" id="montant_total" value="{{ old('montant_total', 0) }}" step="0.01" min="0">
                        <span class="suffix">DH</span>
                    </div>
                    @error('montant_total')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group @error('avance') has-error @enderror">
                    <label>Avance <span class="required">*</span></label>
                    <div class="money-input">
                        <input type="number" name="avance" id="avance" value="{{ old('avance', 0) }}" step="0.01" min="0">
                        <span class="suffix">DH</span>
                    </div>
                    @error('avance')
                    <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Calculator Preview -->
            <div class="calculator-preview">
                <h5><i class="fas fa-calculator"></i> Résumé</h5>
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
                        <input type="radio" name="status" id="status_attente" value="en_attente" {{ old('status', 'en_attente') == 'en_attente' ? 'checked' : '' }}>
                        <label for="status_attente">
                            <i class="fas fa-clock"></i>
                            <span>En Attente</span>
                        </label>
                    </div>
                    <div class="status-option en_cours">
                        <input type="radio" name="status" id="status_cours" value="en_cours" {{ old('status') == 'en_cours' ? 'checked' : '' }}>
                        <label for="status_cours">
                            <i class="fas fa-tools"></i>
                            <span>En Cours</span>
                        </label>
                    </div>
                    <div class="status-option termine">
                        <input type="radio" name="status" id="status_termine" value="termine" {{ old('status') == 'termine' ? 'checked' : '' }}>
                        <label for="status_termine">
                            <i class="fas fa-check-circle"></i>
                            <span>Terminé</span>
                        </label>
                    </div>
                    <div class="status-option livre">
                        <input type="radio" name="status" id="status_livre" value="livre" {{ old('status') == 'livre' ? 'checked' : '' }}>
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
                <textarea name="details" placeholder="Remarques, accessoires laissés, informations supplémentaires...">{{ old('details') }}</textarea>
                @error('details')
                <div class="error-message"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <button type="button" class="btn-reset" onclick="resetForm()">
                <i class="fas fa-undo"></i> Réinitialiser
            </button>
            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> Créer le Ticket
            </button>
        </div>
    </div>
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

        // Change color if negative
        if (reste < 0) {
            previewReste.style.color = '#059669';
            previewReste.textContent = '+ ' + Math.abs(reste).toFixed(2) + ' DH (Surplus)';
        } else {
            previewReste.style.color = '#ef4444';
        }
    }

    montantInput.addEventListener('input', updateCalculator);
    avanceInput.addEventListener('input', updateCalculator);

    // Initial calculation
    updateCalculator();

    // Reset Form
    function resetForm() {
        Swal.fire({
            title: 'Réinitialiser le formulaire?',
            text: "Toutes les données saisies seront perdues!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D32F2F',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Oui, réinitialiser!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('ticketForm').reset();
                updateCalculator();
                Swal.fire({
                    icon: 'success',
                    title: 'Formulaire réinitialisé!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    background: '#10b981',
                    color: '#fff'
                });
            }
        });
    }

    // Form Validation Enhancement
    document.getElementById('ticketForm').addEventListener('submit', function(e) {
        const nomComplet = document.querySelector('input[name="nom_complet"]').value.trim();
        const deviceType = document.querySelector('input[name="device_type"]:checked');
        const montant = parseFloat(montantInput.value) || 0;
        const avance = parseFloat(avanceInput.value) || 0;

        let errors = [];

        if (!nomComplet) {
            errors.push('Le nom complet est requis');
        }

        if (!deviceType) {
            errors.push('Veuillez sélectionner un type d\'appareil');
        }

        if (avance > montant) {
            errors.push('L\'avance ne peut pas dépasser le montant total');
        }

        if (errors.length > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Erreurs de validation',
                html: errors.map(err => `<p style="margin: 5px 0;">• ${err}</p>`).join(''),
                confirmButtonColor: '#D32F2F'
            });
        }
    });

    // Auto-format phone number
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 9) value = value.slice(0, 9);
            
            // Format: 6XX XXX XXX
            if (value.length > 6) {
                value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
            } else if (value.length > 3) {
                value = value.slice(0, 3) + ' ' + value.slice(3);
            }
            
            e.target.value = value;
        });
    }

    // Keyboard shortcut: Ctrl+S to submit
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            document.getElementById('ticketForm').submit();
        }
    });
</script>

</x-app-layout>