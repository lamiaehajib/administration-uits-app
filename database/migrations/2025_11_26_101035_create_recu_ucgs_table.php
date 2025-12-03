<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('recus_ucgs', function (Blueprint $table) {
            $table->id();
            $table->string('numero_recu')->unique(); // UCG-2025-0001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            // Info Client
            $table->string('client_nom');
            $table->string('client_prenom')->nullable();
            $table->string('client_telephone')->nullable();
            $table->string('client_email')->nullable();
            $table->text('client_adresse')->nullable();
            
            // Equipement o détails
            $table->string('equipement')->nullable();
            $table->text('details')->nullable();
            
            // Garantie
            $table->enum('type_garantie', ['90_jours', '180_jours', '360_jours', 'sans_garantie'])->default('90_jours');
            $table->date('date_garantie_fin')->nullable();
            
            // Statut
            $table->enum('statut', ['en_cours', 'livre', 'annule', 'retour'])->default('en_cours');
            $table->enum('statut_paiement', ['paye', 'partiel', 'impaye'])->default('impaye');
            
            // Montants
            $table->decimal('sous_total', 10, 2)->default(0);
            $table->decimal('remise', 10, 2)->default(0);
            $table->decimal('tva', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('montant_paye', 10, 2)->default(0);
            $table->decimal('reste', 10, 2)->default(0);
            
            // Paiement
            $table->enum('mode_paiement', ['especes', 'carte', 'cheque', 'virement', 'credit'])->default('especes');
            $table->date('date_paiement')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('numero_recu');
            $table->index(['statut', 'statut_paiement']);
            $table->index('created_at');
        });
    }

    public function down() {
        Schema::dropIfExists('recus_ucgs');
    }
};