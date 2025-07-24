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
    Schema::table('attestations_allinone', function (Blueprint $table) {
        $table->boolean('afficher_cachet')->default(1);
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('attestations_allinone', function (Blueprint $table) {
        $table->dropColumn('afficher_cachet');
    });
}

};
