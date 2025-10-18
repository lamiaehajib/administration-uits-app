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
        // This modification requires the 'doctrine/dbal' package to be installed (which is standard in modern Laravel).
        Schema::table('facturefs_items', function (Blueprint $table) {
            // Change the 'libelle' column from string/varchar (default 255) to text (up to 65,535 chars)
            $table->text('libelle')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturefs_items', function (Blueprint $table) {
            // Revert back to string, setting a maximum length (e.g., 255)
            $table->string('libelle', 255)->change();
        });
    }
};
