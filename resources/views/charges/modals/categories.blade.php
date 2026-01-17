<div class="modal fade" id="modalCategories" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #7B1FA2, #9C27B0); color: white;">
                <h5 class="modal-title">
                    <i class="fas fa-tags me-2"></i>
                    Gestion des Catégories
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body">
                <div class="row">
                    <!-- Formulaire ajout catégorie -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-plus-circle me-2" style="color: #7B1FA2;"></i>
                                    Nouvelle Catégorie
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('charges.categories.store') }}" id="formAjouterCategorie">
                                    @csrf
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            Nom <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" name="nom" class="form-control" required placeholder="Ex: Loyer">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            Description
                                        </label>
                                        <textarea name="description" class="form-control" rows="2" placeholder="Détails de la catégorie..."></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            Type par Défaut <span class="text-danger">*</span>
                                        </label>
                                        <select name="type_defaut" class="form-select" required>
                                            <option value="fixe">Fixe</option>
                                            <option value="variable" selected>Variable</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            Icône
                                        </label>
                                        <select name="icone" class="form-select">
                                            <option value="">Aucune</option>
                                            <option value="fa-money-bill-wave">💰 salaires</option>
                                            <option value="fa-bolt">⚡ Électricité</option>
                                            <option value="fa-tint">💧 Eau</option>
                                            <option value="fa-wifi">📶 Internet</option>
                                            <option value="fa-phone">📞 Téléphone</option>
                                            <option value="fa-car">🚗 Transport</option>
                                            <option value="fa-utensils">🍽️ Nourriture</option>
                                            <option value="fa-shopping-cart">🛒 Courses</option>
                                            <option value="fa-briefcase">💼 Bureau</option>
                                            <option value="fa-tools">🔧 Maintenance</option>
                                            <option value="fa-shield-alt">🛡️ Assurance</option>
                                            <option value="fa-file-invoice">📄 Administratif</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            Couleur
                                        </label>
                                        <div class="input-group">
                                            <input type="color" name="couleur" class="form-control form-control-color" value="#7B1FA2">
                                            <input type="text" class="form-control" value="#7B1FA2" id="couleurHex">
                                        </div>
                                        <small class="text-muted">Choisissez une couleur distinctive</small>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-save me-1"></i> Créer la Catégorie
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Liste des catégories -->
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-list me-2" style="color: #7B1FA2;"></i>
                                    Catégories Existantes ({{ $categories->count() }})
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light sticky-top">
                                            <tr>
                                                <th>Catégorie</th>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Charges</th>
                                                <th class="text-center">Statut</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($categories as $cat)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px; background-color: {{ $cat->couleur }}20;">
                                                                @if($cat->icone)
                                                                    <i class="fas {{ $cat->icone }}" style="color: {{ $cat->couleur }};"></i>
                                                                @else
                                                                    <i class="fas fa-tag" style="color: {{ $cat->couleur }};"></i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <strong style="color: {{ $cat->couleur }};">{{ $cat->nom }}</strong>
                                                            @if($cat->description)
                                                                <br><small class="text-muted">{{ Str::limit($cat->description, 50) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @if($cat->type_defaut == 'fixe')
                                                        <span class="badge" style="background: linear-gradient(135deg, #C2185B, #D32F2F);">
                                                            <i class="fas fa-lock me-1"></i>Fixe
                                                        </span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-chart-line me-1"></i>Variable
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary">
                                                        {{ $cat->charges->count() }} charge(s)
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($cat->actif)
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Active
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-ban me-1"></i>Inactive
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button type="button" class="btn btn-warning" 
                                                                onclick="modifierCategorie({{ $cat->id }})" 
                                                                title="Modifier">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <form method="POST" action="{{ route('charges.categories.toggle', $cat) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-{{ $cat->actif ? 'secondary' : 'success' }}" 
                                                                    title="{{ $cat->actif ? 'Désactiver' : 'Activer' }}">
                                                                <i class="fas fa-{{ $cat->actif ? 'ban' : 'check' }}"></i>
                                                            </button>
                                                        </form>
                                                        
                                                        @if($cat->charges->count() == 0)
                                                        <button type="button" class="btn btn-danger" 
                                                                onclick="supprimerCategorie({{ $cat->id }})" 
                                                                title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        @else
                                                        <button type="button" class="btn btn-danger" disabled 
                                                                title="Impossible de supprimer (contient des charges)">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">Aucune catégorie créée</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Catégorie -->
<div class="modal fade" id="modalModifierCategorie" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formModifierCategorie" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header" style="background: linear-gradient(135deg, #F57C00, #FF6F00); color: white;">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        Modifier la Catégorie
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Nom <span class="text-danger">*</span>
                        </label>
                        <input type="text" id="editCatNom" name="nom" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Description
                        </label>
                        <textarea id="editCatDescription" name="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Type par Défaut <span class="text-danger">*</span>
                        </label>
                        <select id="editCatType" name="type_defaut" class="form-select" required>
                            <option value="fixe">Fixe</option>
                            <option value="variable">Variable</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Icône
                        </label>
                        <select id="editCatIcone" name="icone" class="form-select">
                            <option value="">Aucune</option>
                            <option value="fa-money-bill-wave">💰 salaire</option>
                            <option value="fa-bolt">⚡ Électricité</option>
                            <option value="fa-tint">💧 Eau</option>
                            <option value="fa-wifi">📶 Internet</option>
                            <option value="fa-phone">📞 Téléphone</option>
                            <option value="fa-car">🚗 Transport</option>
                            <option value="fa-utensils">🍽️ Nourriture</option>
                            <option value="fa-shopping-cart">🛒 Courses</option>
                            <option value="fa-briefcase">💼 Bureau</option>
                            <option value="fa-tools">🔧 Maintenance</option>
                            <option value="fa-shield-alt">🛡️ Assurance</option>
                            <option value="fa-file-invoice">📄 Administratif</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Couleur
                        </label>
                        <div class="input-group">
                            <input type="color" id="editCatCouleur" name="couleur" class="form-control form-control-color">
                            <input type="text" class="form-control" id="editCatCouleurHex">
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="editCatActif" name="actif">
                            <label class="form-check-label fw-bold" for="editCatActif">
                                Catégorie Active
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Annuler
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Synchroniser couleur picker avec input texte (ajout)
    document.querySelector('input[name="couleur"]').addEventListener('input', function() {
        document.getElementById('couleurHex').value = this.value;
    });

    // Modifier catégorie
    window.modifierCategorie = function(id) {
        fetch(`/charges/categories/${id}`)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    const cat = data.category;
                    
                    document.getElementById('formModifierCategorie').action = `/charges/categories/${cat.id}`;
                    document.getElementById('editCatNom').value = cat.nom;
                    document.getElementById('editCatDescription').value = cat.description || '';
                    document.getElementById('editCatType').value = cat.type_defaut;
                    document.getElementById('editCatIcone').value = cat.icone || '';
                    document.getElementById('editCatCouleur').value = cat.couleur || '#7B1FA2';
                    document.getElementById('editCatCouleurHex').value = cat.couleur || '#7B1FA2';
                    document.getElementById('editCatActif').checked = cat.actif;
                    
                    // Synchroniser couleur
                    document.getElementById('editCatCouleur').addEventListener('input', function() {
                        document.getElementById('editCatCouleurHex').value = this.value;
                    });
                    
                    new bootstrap.Modal(document.getElementById('modalModifierCategorie')).show();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                Swal.fire('Erreur', 'Impossible de charger la catégorie', 'error');
            });
    };

    // Supprimer catégorie
    window.supprimerCategorie = function(id) {
        Swal.fire({
            title: 'Supprimer cette catégorie?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#D32F2F',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/charges/categories/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        });
    };
</script>
@endpush