<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLibelleToFacturefsItemsTable extends Migration
{
    public function up()
    {
        Schema::table('facturefs_items', function (Blueprint $table) {
            $table->string('libelle')->nullable()->after('id'); // ولا حط after عمود آخر حسب ترتيبك
        });
    }

    public function down()
    {
        Schema::table('facturefs_items', function (Blueprint $table) {
            $table->dropColumn('libelle');
        });
    }
}
