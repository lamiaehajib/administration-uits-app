<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('recu_ucg_id')->nullable()->constrained('recus_ucgs')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            $table->enum('type', ['entree', 'sortie', 'ajustement', 'retour']);
            $table->integer('quantite');
            $table->integer('stock_avant');
            $table->integer('stock_apres');
            $table->string('reference')->nullable();
            $table->text('motif')->nullable();
            
            $table->timestamps();
            
            $table->index(['produit_id', 'type', 'created_at']);
        });
    }

    public function down() {
        Schema::dropIfExists('stock_movements');
    }
};