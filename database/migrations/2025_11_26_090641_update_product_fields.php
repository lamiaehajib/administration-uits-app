<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (Schema::hasTable('produits')) {
            Schema::table('produits', function (Blueprint $table) {
                
                // Renommer prix_vendu → prix_vente
                if (Schema::hasColumn('produits', 'prix_vendu') && !Schema::hasColumn('produits', 'prix_vente')) {
                    $table->renameColumn('prix_vendu', 'prix_vente');
                }

                // Stock alerte
                if (!Schema::hasColumn('produits', 'stock_alerte')) {
                    // Darori tkon f blastaha b 'after' hit 'quantite_stock' kayna
                    $table->integer('stock_alerte')->default(5)->after('quantite_stock');
                }
            });
        }
    }

    public function down() {
        if (Schema::hasTable('produits')) {
            Schema::table('produits', function (Blueprint $table) {
                // Rja3 'prix_vente' → 'prix_vendu'
                if (Schema::hasColumn('produits', 'prix_vente') && !Schema::hasColumn('produits', 'prix_vendu')) {
                    $table->renameColumn('prix_vente', 'prix_vendu');
                }

                if (Schema::hasColumn('produits', 'stock_alerte')) {
                    $table->dropColumn('stock_alerte');
                }
            });
        }
    }
};