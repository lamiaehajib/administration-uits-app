<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('recu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recu_ucg_id')->constrained('recus_ucgs')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('restrict');
            
            $table->string('produit_nom'); // n7tafdo bl nom 7ta lo t7adaf produit
            $table->string('produit_reference')->nullable();
            $table->integer('quantite');
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('prix_achat', 10, 2)->default(0); // lil calcul marge
            $table->decimal('sous_total', 10, 2);
            $table->decimal('marge_unitaire', 10, 2)->default(0);
            $table->decimal('marge_totale', 10, 2)->default(0);
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['recu_ucg_id', 'produit_id']);
        });
    }

    public function down() {
        Schema::dropIfExists('recu_items');
    }
};