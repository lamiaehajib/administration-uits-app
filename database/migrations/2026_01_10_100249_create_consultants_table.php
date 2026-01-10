<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('consultants', function (Blueprint $table) {
            $table->id();
            $table->string('nom'); // إسم الخبير
            $table->string('prenom')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('telephone')->nullable();
            $table->string('specialite')->nullable(); // التخصص: Informatique, Gestion, etc.
            $table->text('adresse')->nullable();
            $table->string('cin')->nullable(); // بطاقة التعريف
            $table->decimal('tarif_heure', 10, 2)->nullable(); // السعر للساعة
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes(); // باش نقدرو نحيدوهم بلا ما نمسحوهم نهائيا
        });
    }

    public function down()
    {
        Schema::dropIfExists('consultants');
    }
};