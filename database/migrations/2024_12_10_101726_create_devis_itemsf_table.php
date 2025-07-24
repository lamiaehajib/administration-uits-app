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
        Schema::create('devis_itemsf', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('devis_id');
$table->foreign('devis_id')->references('id')->on('devisf')->onDelete('cascade');


            // رابطه مع جدول devisf
        $table->string('libele');
        $table->string('formation');
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
        Schema::dropIfExists('devis_itemsf');
    }
};
