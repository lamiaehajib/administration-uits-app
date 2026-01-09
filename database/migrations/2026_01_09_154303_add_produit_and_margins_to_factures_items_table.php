<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('factures_items', function (Blueprint $table) {
            $table->foreignId('produit_id')->nullable()->after('factures_id')->constrained('produits')->nullOnDelete();
            $table->decimal('prix_achat', 10, 2)->nullable()->after('prix_ht');
            $table->decimal('marge_unitaire', 10, 2)->nullable()->after('prix_achat');
            $table->decimal('marge_totale', 10, 2)->nullable()->after('marge_unitaire');
        });
    }

    public function down()
    {
        Schema::table('factures_items', function (Blueprint $table) {
            $table->dropForeign(['produit_id']);
            $table->dropColumn(['produit_id', 'prix_achat', 'marge_unitaire', 'marge_totale']);
        });
    }
};