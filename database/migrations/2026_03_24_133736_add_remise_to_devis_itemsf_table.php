<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devis_itemsf', function (Blueprint $table) {
            // Remise en pourcentage (ex: 10 = 10%)
            $table->decimal('remise', 5, 2)->default(0)->after('prix_unitaire');
            // Prix après remise (calculé)
            $table->decimal('prix_apres_remise', 15, 2)->default(0)->after('remise');
        });
    }

    public function down(): void
    {
        Schema::table('devis_itemsf', function (Blueprint $table) {
            $table->dropColumn(['remise', 'prix_apres_remise']);
        });
    }
};