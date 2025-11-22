<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repair_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Client Info
            $table->string('nom_complet');
            $table->string('phone')->nullable();
            
            // Device Info
            $table->string('device_type'); // PC, Laptop, Imprimante...
            $table->string('device_brand')->nullable(); // HP, Dell...
            $table->text('problem_description')->nullable();
            
            // Date & Time
            $table->date('date_depot');
            $table->time('time_depot');
            $table->date('estimated_completion')->nullable();
            
            // Payment Info
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->decimal('avance', 10, 2)->default(0);
            $table->decimal('reste', 10, 2)->default(0);
            
            // Details & Status
            $table->text('details')->nullable();
            $table->enum('status', ['en_attente', 'en_cours', 'termine', 'livre'])->default('en_cours');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repair_tickets');
    }
};