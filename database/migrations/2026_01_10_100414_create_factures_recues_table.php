<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('factures_recues', function (Blueprint $table) {
            $table->id();
            
            // Informations de base
            $table->string('numero_facture')->unique(); // رقم الفاتورة
            $table->date('date_facture'); // تاريخ الفاتورة
            $table->date('date_echeance')->nullable(); // تاريخ الاستحقاق
            
            // Type de fournisseur (Polymorphic)
            $table->string('fournisseur_type'); // App\Models\Consultant or App\Models\Fournisseur
            $table->unsignedBigInteger('fournisseur_id'); // ID dyal Consultant wla Fournisseur
            
            // Montants
            
            $table->decimal('montant_ttc', 12, 2); // المبلغ مع الضريبة
            
            // Détails
            $table->text('description')->nullable(); // وصف الخدمة/المنتج
            $table->string('statut')->default('en_attente'); // en_attente, payee, annulee
            
            // Pièce jointe
            $table->string('fichier_pdf')->nullable(); // الفاتورة PDF
            
            // Traçabilité
            $table->foreignId('created_by')->nullable()->constrained('users'); // User li créa
            $table->foreignId('updated_by')->nullable()->constrained('users'); // User li modifa
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour la recherche polymorphique
            $table->index(['fournisseur_type', 'fournisseur_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('factures_recues');
    }
};