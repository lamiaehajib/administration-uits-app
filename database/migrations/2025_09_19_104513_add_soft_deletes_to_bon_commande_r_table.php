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
      Schema::table('bon_commande_r', function (Blueprint $table) {
            $table->softDeletes(); // 👈 Hadchi kayzid la colonne 'deleted_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bon_commande_r', function (Blueprint $table) {
            $table->dropSoftDeletes(); // 👈 Hadchi kaymass7 'deleted_at'
        });
    }
};
