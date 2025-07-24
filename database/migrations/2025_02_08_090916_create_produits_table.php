<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->decimal('prix_vendu', 10, 2)->nullable(); 
            $table->integer('quantite_stock')->default(0);
            $table->integer('total_vendu')->default(0);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('produits');
    }
};
