<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historique_salaires_api', function (Blueprint $table) {
            $table->id();
            
            // ✅ الفترة
            $table->year('annee');
            $table->tinyInteger('mois'); // 1-12
            
            // ✅ الإحصائيات
            $table->integer('nombre_employes'); // عدد الموظفين
            $table->decimal('montant_total', 12, 2); // مجموع الرواتب
            
            // ✅ التفاصيل الكاملة (JSON)
            $table->json('details_salaires'); // [{id, name, poste, salaire}, ...]
            
            // ✅ الحالة
            $table->enum('statut', [
                'importe',    // تم الاستيراد من API
                'valide',     // تم التحقق منه
                'integre'     // تم دمجه في depenses_fixes
            ])->default('importe');
            
            // ✅ التتبع
            $table->unsignedBigInteger('importe_par')->nullable();
            $table->timestamp('importe_le')->nullable();
            
            $table->timestamps();
            
            // ✅ Foreign Keys
            $table->foreign('importe_par')->references('id')->on('users')->onDelete('set null');
            
            // ✅ Unique: فقط استيراد واحد لكل شهر
            $table->unique(['annee', 'mois']);
            
            // ✅ Indexes
            $table->index('statut');
            $table->index(['annee', 'mois']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('historique_salaires_api');
    }
};