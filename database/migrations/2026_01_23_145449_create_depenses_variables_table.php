<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('depenses_variables', function (Blueprint $table) {
            $table->id();
            
            
            $table->enum('type', [
                'facture_recue',     
                'prime',              
                'cnss',               
                'publication',        
                'transport',         
                'dgi',                
                'comptabilite',       
                'autre'              
            ]);
            
            
            $table->string('libelle'); 
            $table->text('description')->nullable(); 
            $table->decimal('montant', 12, 2); 
            
            
            $table->date('date_depense'); 
            $table->year('annee'); 
            $table->tinyInteger('mois'); 
            
            // ========================================
            // 
            // ========================================
            $table->unsignedBigInteger('facture_recue_id')->nullable();
            
            // ========================================
            // 
            // ========================================
            $table->unsignedBigInteger('user_mgmt_id')->nullable(); 
            $table->string('nom_employe')->nullable(); 
            $table->string('poste_employe')->nullable(); 
            $table->decimal('montant_salaire', 12, 2)->nullable(); 
            $table->string('type_prime')->nullable(); 
            $table->text('motif_prime')->nullable(); 
            
            // ========================================
            //
            // ========================================
            $table->decimal('montant_salaire_base', 12, 2)->nullable(); 
            $table->decimal('taux_cnss', 5, 2)->nullable(); 
            $table->json('repartition_cnss')->nullable(); 
            
            // ========================================
            //
            // ========================================
            $table->string('plateforme')->nullable(); // Facebook, Google Ads, Instagram...
            $table->string('campagne')->nullable(); 
            $table->date('date_debut_campagne')->nullable();
            $table->date('date_fin_campagne')->nullable();
            
            // ========================================
            // ✅(type = transport)
            // ========================================
            $table->string('type_transport')->nullable(); // taxi, essence, péage...
            $table->string('beneficiaire')->nullable(); 
            $table->string('trajet')->nullable(); 
            $table->decimal('distance_km', 8, 2)->nullable(); 
            
            // ========================================
            // ✅ 
            // ========================================
            $table->enum('statut', ['en_attente', 'validee', 'payee', 'annulee'])->default('en_attente');
            $table->unsignedBigInteger('validee_par')->nullable();
            $table->timestamp('validee_le')->nullable();
            
           
            $table->json('fichiers_justificatifs')->nullable();
            
            
            $table->text('notes_internes')->nullable();
            
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // ✅ Foreign Keys
            $table->foreign('facture_recue_id')->references('id')->on('factures_recues')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validee_par')->references('id')->on('users')->onDelete('set null');
            
            // ✅ Indexes
            $table->index('type');
            $table->index('statut');
            $table->index(['annee', 'mois']);
            $table->index('date_depense');
            $table->index('facture_recue_id');
            $table->index('user_mgmt_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('depenses_variables');
    }
};