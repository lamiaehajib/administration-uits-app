<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonCommandeItemTable extends Migration
{
    public function up()
    {
        Schema::create('bon_commande_item', function (Blueprint $table) {
            $table->id(); // id (auto-incrementing primary key, nullable)
            $table->string('libelle')->nullable(); // libelle
            $table->integer('quantite')->nullable(); // quantite
            $table->decimal('prix_ht', 15, 2)->nullable(); // prix_ht
            $table->decimal('prix_total', 15, 2)->nullable(); // prix_total
            $table->foreignId('bon_commande_r_id')->nullable()->constrained('bon_commande_r')->onDelete('cascade'); // foreign key to bon_commande_r
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('bon_commande_item');
    }
}