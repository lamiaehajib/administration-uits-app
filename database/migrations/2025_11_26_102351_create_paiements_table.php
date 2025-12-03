<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recu_ucg_id')->constrained('recus_ucgs')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            
            $table->decimal('montant', 10, 2);
            $table->enum('mode_paiement', ['especes', 'carte', 'cheque', 'virement']);
            $table->string('reference')->nullable(); // numero cheque/transaction
            $table->date('date_paiement');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['recu_ucg_id', 'date_paiement']);
        });
    }

    public function down() {
        Schema::dropIfExists('paiements');
    }
};