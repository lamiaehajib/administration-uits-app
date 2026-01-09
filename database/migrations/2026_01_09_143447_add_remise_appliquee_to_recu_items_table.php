<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recu_items', function (Blueprint $table) {
            $table->boolean('remise_appliquee')->default(false)->after('marge_totale');
        });
    }

    public function down(): void
    {
        Schema::table('recu_items', function (Blueprint $table) {
            $table->dropColumn('remise_appliquee');
        });
    }
};