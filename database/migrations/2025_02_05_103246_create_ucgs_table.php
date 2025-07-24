<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ucgs', function (Blueprint $table) {
            $table->id();
            $table->string('nom')->nullable();
            $table->string('prenom')->nullable();
            $table->enum('recu_garantie', ['180 jours', '90 jours', '360 jours'])->nullable();
            $table->text('details')->nullable();
            $table->decimal('montant_paye', 10, 2)->nullable();
            $table->date('date_paiement')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('ucgs');
    }
};


