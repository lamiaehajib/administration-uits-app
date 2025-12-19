<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->cascadeOnDelete();
            
            // ✅ المواصفات
            $table->string('ram')->nullable();        // 16GB, 32GB...
            $table->string('ssd')->nullable();        // 512GB, 1TB NVMe...
            $table->string('cpu')->nullable();        // i7-9850H...
            $table->string('gpu')->nullable();        // NVIDIA Quadro T1000...
            $table->string('ecran')->nullable();      // 15.6" FHD IPS...
            $table->text('autres_specs')->nullable(); // JSON pour specs supplémentaires
            
            // ✅ IMPORTANT: Prix et Stock
            $table->decimal('prix_supplement', 10, 2)->default(0); // 🔥 NOUVEAU
            $table->integer('quantite_stock')->default(0);         // 🔥 NOUVEAU
            
            $table->string('variant_name')->nullable(); // Auto-généré
            $table->string('sku')->unique();
            
            $table->boolean('actif')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour performance
            $table->index(['produit_id', 'actif']);
        });

        // ✅ Ajouter colonne dans recu_items
        Schema::table('recu_items', function (Blueprint $table) {
            $table->foreignId('product_variant_id')
                ->nullable()
                ->after('produit_id')
                ->constrained('product_variants')
                ->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('recu_items', function (Blueprint $table) {
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');
        });
        
        Schema::dropIfExists('product_variants');
    }
};