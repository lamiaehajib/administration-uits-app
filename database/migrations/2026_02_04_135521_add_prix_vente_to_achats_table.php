<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('achats', function (Blueprint $table) {
            // ✅ Prix de vente suggéré pour ce batch
            $table->decimal('prix_vente_suggere', 10, 2)->nullable()->after('prix_achat');
            
            // ✅ Marge souhaitée en pourcentage
            $table->decimal('marge_pourcentage', 5, 2)->default(20)->after('prix_vente_suggere');
        });

        // ✅ Calculer prix_vente_suggere pour achats existants (marge 20%)
        \Illuminate\Support\Facades\DB::statement('
            UPDATE achats 
            SET prix_vente_suggere = prix_achat * 1.20,
                marge_pourcentage = 20
            WHERE prix_vente_suggere IS NULL
        ');
    }

    public function down()
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->dropColumn(['prix_vente_suggere', 'marge_pourcentage']);
        });
    }
};