<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('depenses_fixes', function (Blueprint $table) {
            $table->id();
            
           
            $table->enum('type', [
                'salaire',           
                'loyer',             
                'internet',          //  Maroc Telecom
                'mobile',            //  Maroc Telecom
                'srmc',              // SRMC
                'femme_menage',      //  
                'frais_aups',        //  AUPS
                'autre'              
            ]);
            
            // ✅  (  'autre')
            $table->string('libelle')->nullable(); //   (  autre)
            $table->text('description')->nullable(); //  
            
            // ✅  
            $table->decimal('montant_mensuel', 12, 2); //  
            
            // ✅   
            $table->string('reference_contrat')->nullable(); //  
            
            // ✅  
            $table->date('date_debut'); // تاريخ 
            $table->date('date_fin')->nullable(); // تاريخ النهاية (null = بدون نهاية)
            
            // ✅ الحالة
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            
            // ✅ إعدادات التنبيه
            $table->boolean('rappel_actif')->default(true); // تفعيل التنبيه
            $table->integer('jour_paiement')->default(1); // اليوم من الشهر (1-31)
            $table->integer('rappel_avant_jours')->default(3); // التنبيه قبل كم يوم
            
            // ✅ الملفات المرفقة
            $table->string('fichier_contrat')->nullable(); // ملف العقد PDF
            $table->json('fichiers_justificatifs')->nullable(); // ملفات إضافية
            
            // ✅ التتبع
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes(); // للحذف الناعم
            
            // ✅ Foreign Keys
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            
            // ✅ Indexes للأداء
            $table->index('type');
            $table->index('statut');
            $table->index(['date_debut', 'date_fin']);
            $table->index('jour_paiement');
        });
    }

    public function down()
    {
        Schema::dropIfExists('depenses_fixes');
    }
};