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
    Schema::table('facturefs', function (Blueprint $table) {
        $table->boolean('afficher_cachet')->default(false)->after('user_id');
    });
}

public function down()
{
    Schema::table('facturefs', function (Blueprint $table) {
        $table->dropColumn('afficher_cachet');
    });
}

};
