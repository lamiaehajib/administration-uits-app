<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('charge_categories', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->unique(); // Loyer, Salaires, Publicité, etc.
            $table->string('code', 10)->unique(); // CAT-001
            $table->text('description')->nullable();
            $table->enum('type_defaut', ['fixe', 'variable'])->default('variable');
            $table->string('icone')->nullable(); // Pour l'interface
            $table->string('couleur', 7)->default('#3B82F6'); // Couleur hex
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charge_categories');
    }
};