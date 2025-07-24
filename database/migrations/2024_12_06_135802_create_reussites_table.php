<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('reussites', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->string('prenom');
        $table->string('duree_stage');
        $table->decimal('montant_paye', 10, 2);
        $table->date('date_paiement');
        $table->date('prochaine_paiement')->nullable();
        $table->decimal('rest', 10, 2)->nullable();
        $table->string('CIN')->nullable(); // Added CIN field
        $table->string('tele')->nullable(); // Added tele field
        $table->string('gmail')->nullable(); // Added gmail field
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reussites');
    }
};
