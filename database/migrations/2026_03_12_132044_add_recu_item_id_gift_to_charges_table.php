<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            // ✅ Lien vers l'item gift pour permettre suppression/restauration automatique
            $table->unsignedBigInteger('recu_item_id_gift')
                  ->nullable()
                  ->after('user_id')
                  ->comment('Si cette charge vient d\'un gift, référence le recu_item');
        });
    }

    public function down(): void
    {
        Schema::table('charges', function (Blueprint $table) {
            $table->dropColumn('recu_item_id_gift');
        });
    }
};