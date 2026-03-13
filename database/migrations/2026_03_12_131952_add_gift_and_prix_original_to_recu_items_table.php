<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recu_items', function (Blueprint $table) {
            // ✅ Gift flag
            $table->boolean('is_gift')->default(false)->after('remise_appliquee');
            // ✅ Prix original sauvegardé (pour charge + affichage)
            $table->decimal('prix_original', 10, 2)->nullable()->after('is_gift');
        });
    }

    public function down(): void
    {
        Schema::table('recu_items', function (Blueprint $table) {
            $table->dropColumn(['is_gift', 'prix_original']);
        });
    }
};