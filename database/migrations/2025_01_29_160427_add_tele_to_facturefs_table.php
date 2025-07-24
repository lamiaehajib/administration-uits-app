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
        $table->string('tele')->nullable();
    });
}

public function down()
{
    Schema::table('facturefs', function (Blueprint $table) {
        $table->dropColumn('tele');
    });
}

};
