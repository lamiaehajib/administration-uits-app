<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->integer('quantite_vendue');
            $table->decimal('prix_vendu', 10, 2);
            $table->decimal('total_vendu', 10, 2);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('ventes');
    }
};
