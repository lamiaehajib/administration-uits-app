<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('bon_livraison_items')) {
            Schema::create('bon_livraison_items', function (Blueprint $table) {
                $table->id();
                $table->string('libelle')->nullable();
                $table->integer('quantite')->nullable();
                $table->decimal('prix_ht', 10, 2)->nullable();
                $table->decimal('prix_total', 10, 2)->nullable();
                $table->foreignId('bon_livraison_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_livraison_items');
    }
};