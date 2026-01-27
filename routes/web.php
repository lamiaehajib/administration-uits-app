<?php

use App\Http\Controllers\AchatController;
use App\Http\Controllers\AttestationAllinoneController;
use App\Http\Controllers\AttestationController;
use App\Http\Controllers\AttestationFormationController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BeneficeController;
use App\Http\Controllers\BeneficeUitsController;
use App\Http\Controllers\BonCommandeRController;
use App\Http\Controllers\BonDeCommandeController;
use App\Http\Controllers\BonLivraisonController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChargeController;
use App\Http\Controllers\DashboardStockController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\DevisfController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\FactureRecueController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecuUcgController;
use App\Http\Controllers\RepairTicketController;
use App\Http\Controllers\ReussiteController;
use App\Http\Controllers\ReussitefController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacturefController;
use App\Http\Controllers\MargeController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\UcgController;
use App\Http\Controllers\VenteController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    set_time_limit(300); 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('users', UserController::class);
Route::resource('roles', RoleController::class);




  Route::put('/devis/{id}/restore', [DevisController::class, 'restore'])
      ->name('devis.restore');

Route::delete('/devis/{id}/forceDelete', [DevisController::class, 'forceDelete'])
      ->name('devis.forceDelete');

      
Route::get('/devis/corbeille', [DevisController::class, 'corbeille'])
      ->name('devis.corbeille');

Route::resource('attestations', AttestationController::class);

Route::get('/attestations/pdf/{id}', [AttestationController::class, 'generatePDF'])->name('attestations.pdf');

Route::resource('attestations_formation', AttestationFormationController::class)->parameters(['attestations_formation' => 'attestation']);
Route::get('attestations_formation/{attestation}/pdf', [AttestationFormationController::class, 'generatePDF'])->name('attestations_formation.pdf');

Route::resource('attestations_allinone', AttestationAllinoneController::class)->parameters(['attestations_allinone' => 'attestation']);



Route::get('attestations_allinone/{attestation}/pdf', [AttestationAllinoneController::class, 'generatePDF'])->name('attestations_allinone.pdf');
Route::post('/reussites/{reussite}/duplicate', [ReussiteController::class, 'duplicate'])
    ->name('reussites.duplicate');
Route::resource('reussites', ReussiteController::class);
Route::get('reussites/{reussite}/pdf', [ReussiteController::class, 'generatePDF'])->name('reussites.pdf');


Route::get('/ucg/corbeille', [UcgController::class, 'corbeille'])
      ->name('ucg.corbeille');

// 2. Route dyal Restauration
Route::put('/ucg/{id}/restore', [UcgController::class, 'restore'])
      ->name('ucg.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/ucg/{id}/forceDelete', [UcgController::class, 'forceDelete'])
      ->name('ucg.forceDelete');


Route::resource('ucgs', UcgController::class);
Route::get('/ucg/{ucg}/pdf', [UcgController::class, 'generatePDF'])
      ->name('ucg.pdf')
      ->withTrashed(); ;



Route::get('/reussitef/corbeille', [ReussitefController::class, 'corbeille'])
      ->name('reussitef.corbeille');

// 2. Route dyal Restauration
Route::put('/reussitef/{id}/restore', [ReussitefController::class, 'restore'])
      ->name('reussitef.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/reussitef/{id}/forceDelete', [ReussitefController::class, 'forceDelete'])
      ->name('reussitef.forceDelete');
Route::post('/reussitesf/{reussitef}/duplicate', [ReussitefController::class, 'duplicate'])
    ->name('reussitesf.duplicate');
Route::resource('reussitesf', ReussitefController::class);
Route::get('/reussitesf/{id}/pdf', [ReussitefController::class, 'downloadPDF'])->name('reussitesf.pdf');

Route::resource('devis', DevisController::class)->parameters(['devis' => 'devis']);
Route::get('devis/{id}/pdf', [DevisController::class, 'downloadPDF'])->name('devis.downloadPDF');



Route::get('/devisf/corbeille', [DevisfController::class, 'corbeille'])
      ->name('devisf.corbeille');

// 2. Route dyal Restauration
Route::put('/devisf/{id}/restore', [DevisfController::class, 'restore'])
      ->name('devisf.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/devisf/{id}/forceDelete', [DevisfController::class, 'forceDelete'])
      ->name('devisf.forceDelete');

Route::resource('devisf', DevisfController::class)->parameters(['devisf' => 'devisf']);

Route::get('devisf/{id}/pdf', [DevisfController::class, 'downloadPDF'])->name('devisf.downloadPDF');



Route::get('/factures/corbeille', [FactureController::class, 'corbeille'])
      ->name('factures.corbeille');

// 2. Route dyal Restauration
Route::put('/factures/{id}/restore', [FactureController::class, 'restore'])
      ->name('factures.restore');


      
// 3. Route dyal Suppression Définitive
Route::delete('/factures/{id}/forceDelete', [FactureController::class, 'forceDelete'])
      ->name('factures.forceDelete');
Route::resource('factures', FactureController::class)->parameters(['facture' => 'facture']);

Route::get('factures/{id}/pdf', [FactureController::class, 'downloadPDF'])->name('factures.downloadPDF');



Route::get('/factures/produits-by-category/{categoryId}', [FactureController::class, 'getProduitsByCategory'])
    ->name('factures.produits-by-category');



Route::get('/facturef/corbeille', [FacturefController::class, 'corbeille'])
      ->name('facturef.corbeille');

// 2. Route dyal Restauration
Route::put('/facturef/{id}/restore', [FacturefController::class, 'restore'])
      ->name('facturef.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/facturef/{id}/forceDelete', [FacturefController::class, 'forceDelete'])
      ->name('facturef.forceDelete');
      
Route::resource('facturefs', FacturefController::class);

// Specific routes for downloading PDF
Route::get('facturefs/{id}/download', [FacturefController::class, 'downloadPDF'])->name('facturefs.downloadPDF');




// Route::resource('produits', ProduitController::class);
Route::resource('categories', CategoryController::class);
// Route::resource('achats', AchatController::class);
// Route::resource('ventes', VenteController::class);
// Route::get('marges/calculer/{produit_id}', [MargeController::class, 'calculer'])->name('marges.calculer');
// Route::get('/get-produits-by-category/{category_id}', [ProduitController::class, 'getProduitsByCategory']);








// 1. Route dyal Affichage Corbeille
Route::get('/boncommandes/corbeille', [BonCommandeRController::class, 'corbeille'])
      ->name('boncommandes.corbeille');

// 2. Route dyal Restauration
Route::put('/boncommandes/{id}/restore', [BonCommandeRController::class, 'restore'])
      ->name('boncommandes.restore');

// 3. Route dyal Suppression Définitive
Route::delete('/boncommandes/{id}/forceDelete', [BonCommandeRController::class, 'forceDelete'])
      ->name('boncommandes.forceDelete');

    


Route::get('bon_commande_r/{id}/pdf', [BonCommandeRController::class, 'downloadPDF'])->name('bon_commande_r.pdf');

Route::resource('bon_commande_r', BonCommandeRController::class);


Route::resource('bon_de_commande', BonDeCommandeController::class);
Route::get('bon_de_commande/{bon_de_commande}/download', [BonDeCommandeController::class, 'download'])->name('bon_de_commande.download');

Route::resource('bon_livraisons', BonLivraisonController::class);
Route::get('bon_livraisons/{id}/download', [BonLivraisonController::class, 'downloadPDF'])->name('bon_livraisons.download');

Route::get('factures/create_from_devis/{devis}', [App\Http\Controllers\FactureController::class, 'createFromDevis'])->name('factures.create_from_devis');


Route::get('/facturefs/create-from-devis/{devis}', [FacturefController::class, 'createFromDevisf'])
    ->name('facturefs.create_from_devisf');

Route::get('/devis/{devis}/duplicate', [App\Http\Controllers\DevisController::class, 'duplicate'])->name('devis.duplicate');

Route::get('/devisf/{devisf}/duplicate', [App\Http\Controllers\DevisfController::class, 'duplicate'])->name('devisf.duplicate');

Route::get('/factures/{facture}/duplicate', [App\Http\Controllers\FactureController::class, 'duplicate'])->name('factures.duplicate');

Route::get('/facturefs/{facturef}/duplicate', [App\Http\Controllers\FacturefController::class, 'duplicate'])->name('facturefs.duplicate');



Route::get('/devis/{devis}', [DevisController::class, 'show'])
      ->name('devis.show')
      ->withTrashed();
    
Route::get('/factures/{facture}', [FactureController::class, 'show'])
      ->name('factures.show')
      ->withTrashed(); 

Route::get('/facturefs/{facturef}', [FacturefController::class, 'show'])
      ->name('facturefs.show')
      ->withTrashed();

      Route::get('/download-backup', [BackupController::class, 'downloadBackup'])->name('download.backup');

      Route::resource('repair-tickets', RepairTicketController::class);
    Route::get('repair-tickets/{repairTicket}/pdf', [RepairTicketController::class, 'downloadPdf'])
        ->name('repair-tickets.pdf');


        Route::prefix('produits/{produit}/variants')->name('produits.variants.')->group(function () {
    Route::get('/', [ProductVariantController::class, 'index'])->name('index');
    Route::get('/create', [ProductVariantController::class, 'create'])->name('create');
    Route::post('/', [ProductVariantController::class, 'store'])->name('store');
    Route::get('/{variant}', [ProductVariantController::class, 'show'])->name('show');
    Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
    Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('update');
    Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
    
    // Actions supplémentaires
    Route::post('/{variant}/ajuster-stock', [ProductVariantController::class, 'ajusterStock'])->name('ajuster-stock');
    Route::post('/{variant}/duplicate', [ProductVariantController::class, 'duplicate'])->name('duplicate');
    Route::post('/{variant}/toggle-actif', [ProductVariantController::class, 'toggleActif'])->name('toggle-actif');
});

// Routes API pour les variants (pour AJAX)
Route::prefix('api/variants')->name('api.variants.')->group(function () {
    Route::get('/produit/{id}', [ProductVariantController::class, 'getVariants'])->name('by-produit');
    Route::get('/{id}', [ProductVariantController::class, 'getVariant'])->name('show');
    Route::get('/search', [ProductVariantController::class, 'search'])->name('search');
});

 Route::get('/produits/{id}/quick-edit-data', [ProduitController::class, 'getQuickEditData'])
        ->name('produits.quick_edit_data');
    Route::post('/produits/{id}/quick-update', [ProduitController::class, 'quickUpdate'])
        ->name('produits.quick_update');

        
Route::controller(ProduitController::class)->prefix('produits')->name('produits.')->group(function () {
    Route::get('totals', 'getTotals')->name('totals');
    Route::get('export-pdf', 'exportPDF')->name('export_pdf');
    Route::get('rapport', 'rapport')->name('rapport'); // هذا المسار الآن يتعرف عليه أولاً
});
Route::get('produits/trash', [ProduitController::class, 'trash'])->name('produits.trash');

// Route لاستعادة منتج
Route::post('produits/{id}/restore', [ProduitController::class, 'restore'])->name('produits.restore');

// Route للحذف النهائي
Route::delete('produits/{id}/force-delete', [ProduitController::class, 'forceDelete'])->name('produits.forceDelete');

// 2. الـ Route الأساسي (Resource) - يجب أن يأتي ثانياً
Route::resource('produits', ProduitController::class);

// 3. API/AJAX Route (غالباً في routes/api.php أو يتم إضافتها هنا)
Route::get('produits/by-category/{category_id}', [ProduitController::class, 'getProduitsByCategory'])->name('produits.by_category');


Route::get('/achats/get-produits', [AchatController::class, 'getProduits'])->name('achats.getProduits');
Route::resource('achats', AchatController::class);

Route::controller(App\Http\Controllers\RecuUcgController::class)->prefix('recus')->name('recus.')->group(function () {
    // Route pour afficher la corbeille
    Route::get('trash', 'trash')->name('trash');
    
    // Route pour restaurer un reçu
    Route::put('{id}/restore', 'restore')->name('restore');
    
    // Route pour supprimer définitivement un reçu
    Route::delete('{id}/force-delete', 'forceDelete')->name('forceDelete');
});Route::get('/api/produits/category/{categoryId}', [RecuUcgController::class, 'getProduitsByCategory'])
    ->name('api.produits.by-category');


    Route::post('recus/{recu}/items/{item}/appliquer-remise', 
    [RecuUcgController::class, 'appliquerRemise'])
    ->name('recus.items.appliquer-remise');

Route::resource('recus', RecuUcgController::class);
Route::post('recus/{recu}/items', [RecuUcgController::class, 'addItem'])->name('recus.items.add');
Route::delete('recus/{recu}/items/{item}', [RecuUcgController::class, 'removeItem'])->name('recus.items.remove');
Route::patch('recus/{recu}/statut', [RecuUcgController::class, 'updateStatut'])->name('recus.statut');

Route::get('recus/{recu}/print', [RecuUcgController::class, 'print'])->name('recus.print');

Route::prefix('paiements')->name('paiements.')->group(function () {
    Route::get('/', [PaiementController::class, 'index'])->name('index');
    Route::post('/recu/{recu}', [PaiementController::class, 'store'])->name('store');
    Route::get('/{paiement}', [PaiementController::class, 'show'])->name('show');
    Route::delete('/{paiement}', [PaiementController::class, 'destroy'])->name('destroy');
    Route::get('/rapport/generate', [PaiementController::class, 'rapport'])->name('rapport');
});

Route::prefix('stock')->group(function () {
    Route::get('/movements', [StockMovementController::class, 'index'])->name('stock.movements.index');
    Route::get('/movements/produit/{produit}', [StockMovementController::class, 'produit'])->name('stock.movements.produit');
    Route::post('/movements/ajustement', [StockMovementController::class, 'ajustement'])->name('stock.movements.ajustement');
    Route::get('/statistiques', [StockMovementController::class, 'statistiques'])->name('stock.statistiques');
});

Route::get('/dashboardstock', [DashboardStockController::class, 'index'])->name('dashboardstock');
    Route::get('/dashboardstock/stats', [DashboardStockController::class, 'getStats'])->name('dashboardstock.stats');



// ============================================
// 🧾 FACTURES REÇUES
// ============================================
Route::prefix('factures-recues')->name('factures-recues.')->group(function () {
    
    // 📋 Liste & Affichage
    Route::get('/', [FactureRecueController::class, 'index'])->name('index');
    Route::get('/trash', [FactureRecueController::class, 'trash'])->name('trash');
    
    // ✏️ Création - IMPORTANT: AVANT {id}
    Route::get('/create', [FactureRecueController::class, 'create'])->name('create');
    Route::post('/store', [FactureRecueController::class, 'store'])->name('store');
    
    // 🔢 Générer numéro de facture (AJAX)
    Route::post('/generate-numero', [FactureRecueController::class, 'generateNumeroFacture'])->name('generate-numero');
    
    // 🔄 Édition
    Route::get('/{id}/edit', [FactureRecueController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FactureRecueController::class, 'update'])->name('update');
    Route::patch('/{id}', [FactureRecueController::class, 'update'])->name('patch');
    
    // ⚡ Édition Rapide (AJAX)
    Route::post('/{id}/quick-edit', [FactureRecueController::class, 'quickEdit'])->name('quick-edit');
    
    // 📋 Duplication
    Route::post('/{id}/duplicate', [FactureRecueController::class, 'duplicate'])->name('duplicate');
    
    // 🗑️ Suppression
    Route::delete('/{id}', [FactureRecueController::class, 'destroy'])->name('destroy');
    Route::delete('/{id}/force', [FactureRecueController::class, 'forceDestroy'])->name('force-destroy');
    
    // 🔄 Restauration
    Route::post('/{id}/restore', [FactureRecueController::class, 'restore'])->name('restore');
    
    // 📥 Téléchargement
    Route::get('/{id}/download', [FactureRecueController::class, 'downloadPDF'])->name('download');
    
    // 🔄 Actions en masse (AJAX)
    Route::post('/bulk/update-status', [FactureRecueController::class, 'bulkUpdateStatus'])->name('bulk.update-status');
    Route::post('/bulk/delete', [FactureRecueController::class, 'bulkDelete'])->name('bulk.delete');
    
    // 📄 Afficher - IMPORTANT: EN DERNIER
    Route::get('/{id}', [FactureRecueController::class, 'show'])->name('show');
});

Route::prefix('api')->name('api.')->group(function () {
    
    // Créer nouveau consultant/fournisseur via modal
    Route::post('/consultants', [FactureRecueController::class, 'storeConsultant'])->name('consultants.store');
    Route::post('/fournisseurs', [FactureRecueController::class, 'storeFournisseur'])->name('fournisseurs.store');
    
    // Obtenir les détails (pour auto-remplissage)
    Route::get('/consultants/{id}', [FactureRecueController::class, 'getConsultant'])->name('consultants.get');
    Route::get('/fournisseurs/{id}', [FactureRecueController::class, 'getFournisseur'])->name('fournisseurs.get');

    // ✅ Routes pour mise à jour des consultants et fournisseurs (CORRIGÉES)
    Route::put('/consultants/{id}', [FactureRecueController::class, 'updateConsultant'])->name('consultants.update');
    Route::put('/fournisseurs/{id}', [FactureRecueController::class, 'updateFournisseur'])->name('fournisseurs.update');
});


   // 1. DASHBOARD (avant /charges/{charge})
Route::get('/charges/dashboard', [ChargeController::class, 'dashboard'])->name('charges.dashboard');

// 2. EXPORT (avant /charges/{charge})
Route::get('/charges/export', [ChargeController::class, 'export'])->name('charges.export');

// 3. STATISTIQUES API (avant /charges/{charge})
Route::get('/charges/statistiques/api', [ChargeController::class, 'statistiques'])->name('charges.statistiques');

// 4. INDEX
Route::get('/charges', [ChargeController::class, 'index'])->name('charges.index');

// 5. CATÉGORIES - Routes spécifiques AVANT les routes avec {category}
Route::post('/charges/categories', [ChargeController::class, 'storeCategory'])->name('charges.categories.store');
Route::get('/charges/categories/{category}/details', [ChargeController::class, 'getCategoryDetails'])->name('charges.categories.details');
Route::put('/charges/categories/{category}', [ChargeController::class, 'updateCategory'])->name('charges.categories.update');
Route::delete('/charges/categories/{category}', [ChargeController::class, 'destroyCategory'])->name('charges.categories.destroy');
Route::post('/charges/categories/{category}/toggle', [ChargeController::class, 'toggleCategory'])->name('charges.categories.toggle');

// 6. CHARGES - CRUD (avec paramètre {charge})
Route::post('/charges', [ChargeController::class, 'store'])->name('charges.store');
Route::get('/charges/{charge}', [ChargeController::class, 'show'])->name('charges.show');
Route::put('/charges/{charge}', [ChargeController::class, 'update'])->name('charges.update');
Route::delete('/charges/{charge}', [ChargeController::class, 'destroy'])->name('charges.destroy');

// 7. ACTIONS SPÉCIFIQUES SUR UNE CHARGE
Route::post('/charges/{charge}/marquer-payee', [ChargeController::class, 'marquerPayee'])->name('charges.marquer-payee');
Route::post('/charges/{charge}/ajouter-paiement', [ChargeController::class, 'ajouterPaiement'])->name('charges.ajouter-paiement');
Route::post('/charges/{charge}/generer-prochaine', [ChargeController::class, 'genererProchaine'])->name('charges.generer-prochaine');
Route::post('/charges/{id}/restore', [ChargeController::class, 'restore'])->name('charges.restore');
    
Route::get('/benefices/dashboard', [BeneficeController::class, 'dashboard'])->name('benefices.dashboard');


Route::get('/benefice', [BeneficeUitsController::class, 'index'])->name('beneficier.index');
    Route::get('/benefice/export', [BeneficeUitsController::class, 'exportCSV'])->name('beneficier.export.csv');




    Route::prefix('depenses')->name('depenses.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DepenseController::class, 'dashboard'])->name('dashboard');
    
    // Dépenses Fixes
    Route::prefix('fixes')->name('fixes.')->group(function () {
        Route::get('/', [DepenseController::class, 'indexFixes'])->name('index');
        Route::get('/create', [DepenseController::class, 'createFixe'])->name('create');
        Route::post('/', [DepenseController::class, 'storeFixe'])->name('store');
        Route::get('/{id}', [DepenseController::class, 'showFixe'])->name('show');
        Route::get('/{id}/edit', [DepenseController::class, 'editFixe'])->name('edit');
        Route::put('/{id}', [DepenseController::class, 'updateFixe'])->name('update');
        Route::delete('/{id}', [DepenseController::class, 'destroyFixe'])->name('destroy');
    });
    
    // Dépenses Variables
    Route::prefix('variables')->name('variables.')->group(function () {
        Route::get('/', [DepenseController::class, 'indexVariables'])->name('index');
        Route::get('/create', action: [DepenseController::class, 'createVariable'])->name('create');
        Route::post('/', [DepenseController::class, 'storeVariable'])->name('store');
        Route::get('/{id}', [DepenseController::class, 'showVariable'])->name('show');
        Route::get('/{id}/edit', [DepenseController::class, 'editVariable'])->name('edit');
        Route::put('/{id}', [DepenseController::class, 'updateVariable'])->name('update');
        Route::delete('/{id}', [DepenseController::class, 'destroyVariable'])->name('destroy');
        Route::post('/{id}/valider', [DepenseController::class, 'validerVariable'])->name('valider');
    });
    
    // Budgets
    Route::prefix('budgets')->name('budgets.')->group(function () {
        Route::get('/', [DepenseController::class, 'indexBudgets'])->name('index');
        Route::get('/create', [DepenseController::class, 'createBudget'])->name('create');
        Route::post('/', [DepenseController::class, 'storeBudget'])->name('store');
        Route::get('/{id}', [DepenseController::class, 'showBudget'])->name('show');
        Route::get('/{id}/edit', [DepenseController::class, 'editBudget'])->name('edit');
        Route::put('/{id}', [DepenseController::class, 'updateBudget'])->name('update');
        Route::post('/{id}/cloturer', [DepenseController::class, 'cloturerBudget'])->name('cloturer');
    });
    
    // Import Salaires
    Route::post('/importer-salaires', [DepenseController::class, 'importerSalaires'])->name('importer-salaires');
    Route::get('/salaires/historique', [DepenseController::class, 'historiqueSalaires'])->name('salaires.historique');
    Route::get('/salaires/{id}', [DepenseController::class, 'showHistoriqueSalaire'])->name('salaires.show');
    
    // API / AJAX
    Route::get('/api/test-connection', [DepenseController::class, 'testApiConnection']);
    Route::get('/api/employees', [DepenseController::class, 'getEmployees']);
    Route::get('/api/employees/{id}', [DepenseController::class, 'getEmployee']);
    Route::get('/api/stats', [DepenseController::class, 'getStats']);
    
    // Export
    Route::get('/export', [DepenseController::class, 'export'])->name('export');
});
      });

require __DIR__.'/auth.php';
