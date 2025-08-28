<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bon_livraison', function (Blueprint $table) {
            // Change the column to a larger decimal type
            $table->decimal('tva', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('bon_livraison', function (Blueprint $table) {
            // Revert the column to its original type
            $table->decimal('tva', 5, 2)->change();
        });
    }
};