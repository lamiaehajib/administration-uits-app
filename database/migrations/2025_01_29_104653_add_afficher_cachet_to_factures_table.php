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
    Schema::table('factures', function (Blueprint $table) {
        $table->boolean('afficher_cachet')->default(1);
    });
}

public function down()
{
    Schema::table('factures', function (Blueprint $table) {
        $table->dropColumn('afficher_cachet');
    });
}

};
