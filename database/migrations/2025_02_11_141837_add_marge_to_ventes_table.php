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
    Schema::table('ventes', function (Blueprint $table) {
        $table->decimal('marge', 10, 2)->nullable()->after('total_vendu');
    });
}

public function down()
{
    Schema::table('ventes', function (Blueprint $table) {
        $table->dropColumn('marge');
    });
}
};
