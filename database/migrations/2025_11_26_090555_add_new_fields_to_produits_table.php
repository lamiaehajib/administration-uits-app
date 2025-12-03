<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (Schema::hasTable('produits')) {
            Schema::table('produits', function (Blueprint $table) {
                // Reference produit (code unique)
                if (!Schema::hasColumn('produits', 'reference')) {
                    $table->string('reference')->unique()->nullable()->after('nom');
                }

                // Description
                if (!Schema::hasColumn('produits', 'description')) {
                    $table->text('description')->nullable()->after('reference');
                }

                // Prix achat (important lil marge)
                if (!Schema::hasColumn('produits', 'prix_achat')) {
                    $table->decimal('prix_achat', 10, 2)->default(0)->after('description');
                }
                
                // Actif/Inactif
                if (!Schema::hasColumn('produits', 'actif')) {
                    $table->boolean('actif')->default(true)->after('total_vendu');
                }

                // Soft delete
                if (!Schema::hasColumn('produits', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
        }
    }

    public function down() {
        if (Schema::hasTable('produits')) {
            Schema::table('produits', function (Blueprint $table) {
                if (Schema::hasColumn('produits', 'reference')) {
                    $table->dropColumn('reference');
                }
                if (Schema::hasColumn('produits', 'description')) {
                    $table->dropColumn('description');
                }
                if (Schema::hasColumn('produits', 'prix_achat')) {
                    $table->dropColumn('prix_achat');
                }
                if (Schema::hasColumn('produits', 'actif')) {
                    $table->dropColumn('actif');
                }
                if (Schema::hasColumn('produits', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};