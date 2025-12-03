<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vérifier si la table existe
        if (Schema::hasTable('achats')) {
            // Méthode 1: Via SQL brut (plus sûr pour corriger AUTO_INCREMENT)
            DB::statement('ALTER TABLE `achats` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');
            
            echo "✅ La colonne 'id' de la table 'achats' a été corrigée avec AUTO_INCREMENT\n";
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Pas de rollback nécessaire
    }
};