<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->integer('quantite_restante')->default(0)->after('quantite');
            $table->index(['produit_id', 'date_achat']);
        });

        // ✅ Remplir quantite_restante pour les achats existants
        \Illuminate\Support\Facades\DB::statement('
            UPDATE achats 
            SET quantite_restante = quantite 
            WHERE quantite_restante = 0
        ');
    }

    public function down()
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->dropColumn('quantite_restante');
            $table->dropIndex(['produit_id', 'date_achat']);
        });
    }
};