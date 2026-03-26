<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('important_info', function (Blueprint $table) {
            $table->text('info')->change();
        });
    }

    public function down()
    {
        Schema::table('important_info', function (Blueprint $table) {
            $table->string('info', 255)->change();
        });
    }
};