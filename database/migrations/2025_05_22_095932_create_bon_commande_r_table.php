<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonCommandeRTable extends Migration
{
    public function up()
    {
        Schema::create('bon_commande_r', function (Blueprint $table) {
            $table->id(); 
            $table->string('bon_num')->nullable(); // bon_num
            $table->string('titre')->nullable(); // titre
            $table->string('prestataire')->nullable(); // prestataire
            $table->string('tele')->nullable(); // tele
            $table->string('ice')->nullable(); // ice
            $table->string('adresse')->nullable(); // adresse
            $table->string('ref')->nullable(); // ref
            $table->json('important')->nullable(); // important (JSON type)
            $table->decimal('total_ht', 15, 2)->nullable(); // total_ht
            $table->decimal('total_ttc', 15, 2)->nullable(); // total_ttc
            $table->decimal('tva', 5, 2)->nullable(); // tva
            $table->date('date')->nullable(); // date
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // user_id (foreign key, nullable)
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('bon_commande_r');
    }
}