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
        Schema::create('facturefs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facturefs_id')->constrained()->onDelete('cascade'); 
            $table->string('libele');
            $table->string('formation');
            $table->decimal('prix_ht', 10, 2);
            $table->decimal('prix_total', 10, 2);
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
        Schema::dropIfExists('facturefs_items');
    }
};
