<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->enum('type', ['service', 'produit'])->default('service')->after('facture_num');
        });
    }

    public function down()
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};