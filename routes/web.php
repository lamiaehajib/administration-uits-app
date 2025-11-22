<?php

use App\Http\Controllers\AchatController;
use App\Http\Controllers\AttestationAllinoneController;
use App\Http\Controllers\AttestationController;
use App\Http\Controllers\AttestationFormationController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BonCommandeRController;
use App\Http\Controllers\BonDeCommandeController;
use App\Http\Controllers\BonLivraisonController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\DevisfController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RepairTicketController;
use App\Http\Controllers\ReussiteController;
use App\Http\Controllers\ReussitefController;
use App\Http\Controllers\RoleController;
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




Route::resource('produits', ProduitController::class);
Route::resource('categories', CategoryController::class);
Route::resource('achats', AchatController::class);
Route::resource('ventes', VenteController::class);
Route::get('marges/calculer/{produit_id}', [MargeController::class, 'calculer'])->name('marges.calculer');
Route::get('/get-produits-by-category/{category_id}', [ProduitController::class, 'getProduitsByCategory']);
Route::get('/totals', [ProduitController::class, 'getTotals'])->name('produits.totals');
Route::get('/rapport-ventes', [ProduitController::class, 'exportPDF'])->name('rapport.pdf');






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
      });

require __DIR__.'/auth.php';
