<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom_entreprise'); // إسم الشركة
            $table->string('contact_nom')->nullable(); // إسم الشخص المسؤول
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('ice')->nullable(); // Identifiant Commun de l'Entreprise
            $table->string('if')->nullable(); // Identifiant Fiscal
            $table->text('adresse')->nullable();
            $table->string('type_materiel')->nullable(); // نوع المواد: Informatique, Bureautique, etc.
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fournisseurs');
    }
};