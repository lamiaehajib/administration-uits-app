<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeFormationNullableInDevisItemsfTable extends Migration
{
    public function up()
    {
        Schema::table('devis_itemsf', function (Blueprint $table) {
            $table->string('formation')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('devis_itemsf', function (Blueprint $table) {
            $table->string('formation')->nullable(false)->change(); // ترجعها غير nullable
        });
    }
}
