<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonDeCommandeTable extends Migration
{
    public function up()
    {
        Schema::create('bon_de_commande', function (Blueprint $table) {
            $table->id();
            $table->string('titre'); // العنوان ديال البون دو كوماند
            $table->string('fichier_path')->nullable(); // مسار الملف المرفوع
            $table->date('date_commande')->nullable(); // تاريخ الأمر
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bon_de_commande');
    }
}