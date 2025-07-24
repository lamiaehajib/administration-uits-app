<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFormationFromFacturefsItemsTable extends Migration
{
    public function up()
    {
        Schema::table('facturefs_items', function (Blueprint $table) {
            $table->dropColumn('formation');
        });
    }

    public function down()
    {
        Schema::table('facturefs_items', function (Blueprint $table) {
            $table->string('formation')->nullable()->after('libele');
        });
    }
}