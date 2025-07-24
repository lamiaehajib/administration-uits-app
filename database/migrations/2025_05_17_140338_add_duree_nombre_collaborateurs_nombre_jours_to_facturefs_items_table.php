<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDureeNombreCollaborateursNombreJoursToFacturefsItemsTable extends Migration
{
    public function up()
    {
        Schema::table('facturefs_items', function (Blueprint $table) {
            $table->string('duree')->nullable();
            $table->integer('nombre_collaborateurs')->nullable()->after('duree');
            $table->integer('nombre_jours')->nullable()->after('nombre_collaborateurs');
        });
    }

    public function down()
    {
        Schema::table('facturefs_items', function (Blueprint $table) {
            $table->dropColumn(['duree', 'nombre_collaborateurs', 'nombre_jours']);
        });
    }
}