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
    Schema::create('devis_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('devis_id')->constrained()->onDelete('cascade'); // رابطه مع جدول devis
        $table->string('libele');
        $table->integer('quantite');
        $table->decimal('prix_unitaire', 10, 2);
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
        Schema::dropIfExists('devis_items');
    }
};
