<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('budgets_mensuels', function (Blueprint $table) {
            // ========================================
            // 💰 REVENUS (mn Benefices)
            // ========================================
            $table->decimal('revenu_formations', 12, 2)->default(0)->after('mois');
            $table->decimal('revenu_services', 12, 2)->default(0);
            $table->decimal('revenu_stages', 12, 2)->default(0);
            $table->decimal('revenu_portail', 12, 2)->default(0);
            $table->decimal('revenu_brut_total', 12, 2)
                ->storedAs('revenu_formations + revenu_services + revenu_stages + revenu_portail');
            
            // ========================================
            // 🎯 ALLOCATION BUDGET (Pourcentage)
            // ========================================
            $table->decimal('pourcentage_budget', 5, 2)->default(30.00);
            $table->decimal('budget_alloue', 12, 2)
                ->storedAs('revenu_brut_total * (pourcentage_budget / 100)');
            
            // ========================================
            // 📈 BENEFICE NET
            // ========================================
            $table->decimal('benefice_net', 12, 2)
                ->storedAs('revenu_brut_total - (depense_fixes_realisee + depense_variables_realisee)');
            
            $table->decimal('taux_marge_nette', 5, 2)
                ->storedAs('CASE WHEN revenu_brut_total > 0 THEN (benefice_net / revenu_brut_total * 100) ELSE 0 END');
            
            // ========================================
            // ⚠️ CONFORMITE BUDGET
            // ========================================
            $table->boolean('budget_respecte')
                ->storedAs('(depense_fixes_realisee + depense_variables_realisee) <= budget_alloue');
            
            $table->decimal('ecart_budget', 12, 2)
                ->storedAs('budget_alloue - (depense_fixes_realisee + depense_variables_realisee)');
        });
    }

    public function down()
    {
        Schema::table('budgets_mensuels', function (Blueprint $table) {
            $table->dropColumn([
                'revenu_formations',
                'revenu_services', 
                'revenu_stages',
                'revenu_portail',
                'revenu_brut_total',
                'pourcentage_budget',
                'budget_alloue',
                'benefice_net',
                'taux_marge_nette',
                'budget_respecte',
                'ecart_budget'
            ]);
        });
    }
};