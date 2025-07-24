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
        Schema::create('devis', function (Blueprint $table) {
            $table->id();
            $table->string('devis_num')->nullable();
            $table->date('date')->nullable();
            $table->string('titre')->nullable();
            $table->string('client')->nullable();
            $table->string('contact')->nullable();
            $table->string('ref')->nullable();
            $table->decimal('total_ht', 10, 2)->default(0); // Total Hors Taxes
            $table->decimal('tva', 10, 2)->default(0);     // TVA
            $table->decimal('total_ttc', 10, 2)->default(0); // Total TTC
            $table->json('important')->nullable();
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
        Schema::dropIfExists('devis');
    }
};
