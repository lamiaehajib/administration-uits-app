<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('recu_items', function (Blueprint $table) {
            // Ajouter colonnes pour système de remise flexible
            // Note: remise_appliquee existe déjà
            $table->decimal('remise_montant', 10, 2)->default(0)->after('remise_appliquee')
                ->comment('Montant fixe de la remise en DH');
            
            $table->decimal('remise_pourcentage', 5, 2)->default(0)->after('remise_montant')
                ->comment('Pourcentage de remise (0-100)');
            
            $table->decimal('total_apres_remise', 10, 2)->default(0)->after('remise_pourcentage')
                ->comment('Total après application de la remise');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recu_items', function (Blueprint $table) {
            $table->dropColumn([
                'remise_montant',
                'remise_pourcentage',
                'total_apres_remise'
            ]);
        });
    }
};