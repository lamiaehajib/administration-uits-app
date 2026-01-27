<form action="{{ route('depenses.budgets.update', $budget->id) }}" method="POST" id="editBudgetForm">
    @csrf
    @method('PUT')
    
    <div class="row g-3">
        <div class="col-12">
            <div class="alert alert-info small mb-3">
                <i class="fas fa-info-circle me-2"></i>
                Budget pour <strong>{{ $budget->mois_nom }} {{ $budget->annee }}</strong>
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label">Budget Fixes (DH) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="budget_fixes" class="form-control" required value="{{ $budget->budget_fixes }}" placeholder="0.00">
            <small class="text-muted">Dépenses réalisées: {{ number_format($budget->depense_fixes_realisee, 2) }} DH</small>
        </div>
        
        <div class="col-md-6">
            <label class="form-label">Budget Variables (DH) <span class="text-danger">*</span></label>
            <input type="number" step="0.01" name="budget_variables" class="form-control" required value="{{ $budget->budget_variables }}" placeholder="0.00">
            <small class="text-muted">Dépenses réalisées: {{ number_format($budget->depense_variables_realisee, 2) }} DH</small>
        </div>

        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <small class="text-muted d-block">Budget Total</small>
                            <h5 class="mb-0 text-info" id="budget_total_preview">
                                {{ number_format($budget->budget_fixes + $budget->budget_variables, 2) }} DH
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Dépenses Totales</small>
                            <h5 class="mb-0 text-danger">
                                {{ number_format($budget->depense_totale_realisee, 2) }} DH
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Écart Actuel</small>
                            @php
                                $ecart = $budget->budget_total - $budget->depense_totale_realisee;
                            @endphp
                            <h5 class="mb-0 {{ $ecart >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $ecart >= 0 ? '+' : '' }}{{ number_format($ecart, 2) }} DH
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12">
            <label class="form-label">Statut <span class="text-danger">*</span></label>
            <select name="statut" class="form-select" required>
                <option value="previsionnel" {{ $budget->statut == 'previsionnel' ? 'selected' : '' }}>Prévisionnel</option>
                <option value="en_cours" {{ $budget->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                <option value="cloture" {{ $budget->statut == 'cloture' ? 'selected' : '' }}>Clôturé</option>
            </select>
            <small class="text-muted">
                @if($budget->statut == 'cloture')
                    ⚠️ Attention: Le budget est actuellement clôturé
                @else
                    Le statut "Clôturé" empêchera toute modification future
                @endif
            </small>
        </div>
        
        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Remarques, objectifs, commentaires...">{{ $budget->notes }}</textarea>
        </div>

        <!-- Prévisions vs Réel -->
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-chart-bar me-2"></i>Résumé des Modifications
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-end">Budget Actuel</th>
                                    <th class="text-end">Budget Nouveau</th>
                                    <th class="text-end">Différence</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-muted">Fixes:</td>
                                    <td class="text-end">{{ number_format($budget->budget_fixes, 2) }} DH</td>
                                    <td class="text-end" id="new_budget_fixes">{{ number_format($budget->budget_fixes, 2) }} DH</td>
                                    <td class="text-end" id="diff_fixes">-</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Variables:</td>
                                    <td class="text-end">{{ number_format($budget->budget_variables, 2) }} DH</td>
                                    <td class="text-end" id="new_budget_variables">{{ number_format($budget->budget_variables, 2) }} DH</td>
                                    <td class="text-end" id="diff_variables">-</td>
                                </tr>
                                <tr class="border-top fw-bold">
                                    <td class="text-muted">TOTAL:</td>
                                    <td class="text-end">{{ number_format($budget->budget_total, 2) }} DH</td>
                                    <td class="text-end" id="new_budget_total">{{ number_format($budget->budget_total, 2) }} DH</td>
                                    <td class="text-end" id="diff_total">-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-4 d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i>Annuler
        </button>
        <button type="submit" class="btn btn-warning">
            <i class="fas fa-save me-2"></i>Mettre à jour
        </button>
    </div>
</form>

<script>
    $(document).ready(function() {
        const oldBudgetFixes = {{ $budget->budget_fixes }};
        const oldBudgetVariables = {{ $budget->budget_variables }};
        const oldBudgetTotal = {{ $budget->budget_total }};

        // Update preview on input change
        function updatePreview() {
            const newFixes = parseFloat($('input[name="budget_fixes"]').val()) || 0;
            const newVariables = parseFloat($('input[name="budget_variables"]').val()) || 0;
            const newTotal = newFixes + newVariables;

            // Update display
            $('#new_budget_fixes').text(newFixes.toFixed(2) + ' DH');
            $('#new_budget_variables').text(newVariables.toFixed(2) + ' DH');
            $('#new_budget_total').text(newTotal.toFixed(2) + ' DH');
            $('#budget_total_preview').text(newTotal.toFixed(2) + ' DH');

            // Calculate differences
            const diffFixes = newFixes - oldBudgetFixes;
            const diffVariables = newVariables - oldBudgetVariables;
            const diffTotal = newTotal - oldBudgetTotal;

            // Display differences
            $('#diff_fixes').html(formatDiff(diffFixes));
            $('#diff_variables').html(formatDiff(diffVariables));
            $('#diff_total').html(formatDiff(diffTotal));
        }

        function formatDiff(diff) {
            if (diff === 0) return '<span class="text-muted">-</span>';
            const sign = diff > 0 ? '+' : '';
            const color = diff > 0 ? 'text-danger' : 'text-success';
            return `<span class="${color}">${sign}${diff.toFixed(2)} DH</span>`;
        }

        // Listen to input changes
        $('input[name="budget_fixes"], input[name="budget_variables"]').on('input', updatePreview);

        // Initial update
        updatePreview();
    });

    // Form submit handler
    $('#editBudgetForm').on('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editModal').modal('hide');
                Swal.fire('Succès!', 'Budget mis à jour avec succès.', 'success');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors;
                if (errors) {
                    let errorMsg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Erreur!', errorMsg, 'error');
                } else {
                    Swal.fire('Erreur!', 'Impossible de mettre à jour.', 'error');
                }
            }
        });
    });
</script>