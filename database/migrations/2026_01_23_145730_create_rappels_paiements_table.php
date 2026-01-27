<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('rappels_paiements', function (Blueprint $table) {
        $table->id();
        
        $table->enum('type_source', ['depense_fixe', 'depense_variable']);
        
        // This line creates source_id, source_type AND the index automatically     
        $table->morphs('source'); 
        
        $table->date('date_echeance');
        $table->date('date_rappel');
        $table->integer('jours_avant');
        
        $table->string('titre');
        $table->text('message');
        $table->decimal('montant', 12, 2);
        
        $table->enum('statut', [
            'en_attente',
            'envoye',
            'lu',
            'annule'
        ])->default('en_attente');
        
        $table->json('destinataires')->nullable();
        
        $table->timestamp('envoye_le')->nullable();
        $table->timestamp('lu_le')->nullable();
        
        $table->boolean('notification_email')->default(true);
        $table->boolean('notification_app')->default(true);
        $table->boolean('notification_sms')->default(false);
        
        $table->timestamps();
        
        // Manual indexes for other columns
        $table->index('statut');
        $table->index('date_rappel');
        
        // DO NOT add the source index here, it's already done by ->morphs()
    });
}

    public function down()
    {
        Schema::dropIfExists('rappels_paiements');
    }
};