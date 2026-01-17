<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('charges', function (Blueprint $table) {
            $table->id();
            
            // Informations de base
            $table->string('libelle'); // Nom de la charge
            $table->text('description')->nullable();
            $table->string('numero_reference')->unique(); // EX: CHG-2026-0001
            
            // Type et catégorie
            $table->enum('type', ['fixe', 'variable'])->default('variable');
            $table->foreignId('charge_category_id')->nullable()->constrained('charge_categories')->nullOnDelete();
            
            // Montant et dates
            $table->decimal('montant', 10, 2);
            $table->date('date_charge'); // Date de la dépense
            $table->date('date_echeance')->nullable(); // Pour les charges récurrentes
            
            // Paiement
            $table->enum('mode_paiement', ['especes', 'virement', 'cheque', 'carte', 'autre'])->default('especes');
            $table->string('reference_paiement')->nullable(); // N° chèque, virement, etc.
            $table->enum('statut_paiement', ['paye', 'impaye', 'partiel'])->default('paye');
            $table->decimal('montant_paye', 10, 2)->default(0);
            
            // Fournisseur (optionnel)
            $table->string('fournisseur')->nullable();
            $table->string('fournisseur_telephone')->nullable();
            
            // Récurrence (pour charges fixes)
            $table->boolean('recurrent')->default(false);
            $table->enum('frequence', ['mensuel', 'trimestriel', 'annuel', 'unique'])->default('mensuel');
            
            // Pièces jointes
            $table->string('facture_path')->nullable(); // Chemin vers la facture scannée
            
            // Notes et traçabilité
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Qui a créé
            
            $table->softDeletes();
            $table->timestamps();
            
            // Index
            $table->index('date_charge');
            $table->index('type');
            $table->index('statut_paiement');
        });
    }

    public function down()
    {
        Schema::dropIfExists('charges');
    }
};