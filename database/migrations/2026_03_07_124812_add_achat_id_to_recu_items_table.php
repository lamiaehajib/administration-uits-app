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
    Schema::table('recu_items', function (Blueprint $table) {
        $table->unsignedBigInteger('achat_id')->nullable()->after('produit_id');
        $table->foreign('achat_id')->references('id')->on('achats')->nullOnDelete();
    });
}

public function down()
{
    Schema::table('recu_items', function (Blueprint $table) {
        $table->dropForeign(['achat_id']);
        $table->dropColumn('achat_id');
    });
}
};
