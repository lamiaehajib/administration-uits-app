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
        Schema::create('bon_livraison', function (Blueprint $table) {
            $table->id();
            $table->string('bon_num')->nullable();
            $table->string('titre')->nullable();
            $table->string('client')->nullable();
            $table->string('tele')->nullable();
            $table->string('ice')->nullable();
            $table->string('adresse')->nullable();
            $table->string('ref')->nullable();
            $table->json('important')->nullable();
            $table->decimal('total_ht', 10, 2)->nullable();
            $table->decimal('total_ttc', 10, 2)->nullable();
            $table->decimal('tva', 5, 2)->nullable();
            $table->date('date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bon_livraison');
    }
};