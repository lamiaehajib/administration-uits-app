<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLibelleToTextInBonCommandeItemTable extends Migration
{
    public function up()
    {
        Schema::table('bon_commande_item', function (Blueprint $table) {
            // Change from string(255) to text
            $table->text('libelle')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('bon_commande_item', function (Blueprint $table) {
            $table->string('libelle')->nullable()->change();
        });
    }
}