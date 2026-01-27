<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budgets_mensuels', function (Blueprint $table) {
            $table->id();
            
            // ✅ الفترة
            $table->year('annee');
            $table->tinyInteger('mois'); // 1-12
            
            // ========================================
            // ✅ الميزانية المخططة (Prévisionnel)
            // ========================================
            $table->decimal('budget_fixes', 12, 2)->default(0);
            $table->decimal('budget_variables', 12, 2)->default(0);
            $table->decimal('budget_total', 12, 2)->storedAs('budget_fixes + budget_variables');
            
            // ========================================
            // ✅ المصروف الحقيقي (Réalisé)
            // ========================================
            $table->decimal('depense_fixes_realisee', 12, 2)->default(0);
            $table->decimal('depense_variables_realisee', 12, 2)->default(0);
            $table->decimal('depense_totale_realisee', 12, 2)->storedAs('depense_fixes_realisee + depense_variables_realisee');
            
            // ========================================
            // ✅ الفرق (Écart) - حساب تلقائي
            // ========================================
            $table->decimal('ecart_fixes', 12, 2)->storedAs('budget_fixes - depense_fixes_realisee');
            $table->decimal('ecart_variables', 12, 2)->storedAs('budget_variables - depense_variables_realisee');
            $table->decimal('ecart_total', 12, 2)->storedAs('budget_total - depense_totale_realisee');
            
            // ========================================
            // ✅ النسب المئوية - حساب تلقائي
            // ========================================
            $table->decimal('taux_execution_fixes', 5, 2)->storedAs('CASE WHEN budget_fixes > 0 THEN (depense_fixes_realisee / budget_fixes * 100) ELSE 0 END');
            $table->decimal('taux_execution_variables', 5, 2)->storedAs('CASE WHEN budget_variables > 0 THEN (depense_variables_realisee / budget_variables * 100) ELSE 0 END');
            $table->decimal('taux_execution_total', 5, 2)->storedAs('CASE WHEN budget_total > 0 THEN (depense_totale_realisee / budget_total * 100) ELSE 0 END');
            
            // ✅ الحالة
            $table->enum('statut', [
                'previsionnel',  // تخطيط فقط
                'en_cours',      // الشهر الجاري
                'cloture'        // مغلق
            ])->default('previsionnel');
            
            // ✅ ملاحظات
            $table->text('notes')->nullable();
            
            // ✅ تنبيهات
            $table->boolean('alerte_depassement')->default(false); // هل تم تجاوز الميزانية؟
            $table->timestamp('date_alerte')->nullable(); // متى تم التنبيه
            
            // ✅ التتبع
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            
            // ✅ Foreign Keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            // ✅ Unique: ميزانية واحدة فقط لكل شهر
            $table->unique(['annee', 'mois']);
            
            // ✅ Indexes
            $table->index('statut');
            $table->index(['annee', 'mois']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('budgets_mensuels');
    }
};