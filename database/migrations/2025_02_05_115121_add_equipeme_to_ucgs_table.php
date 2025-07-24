<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('ucgs', function (Blueprint $table) {
            $table->string('equipemen')->nullable(); // Ajout de la colonne equipement
        });
    }

    public function down()
    {
        Schema::table('ucgs', function (Blueprint $table) {
            $table->dropColumn('equipemen'); // Suppression de la colonne en cas de rollback
        });
    }
};
