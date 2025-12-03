<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('achats')) {
            Schema::table('achats', function (Blueprint $table) {
                // 1. Zid 'user_id' (Foreign Key) - بدون after() باش نتجنبو المشاكل
                if (!Schema::hasColumn('achats', 'user_id')) {
                    $table->foreignId('user_id')
                          ->nullable()
                          ->constrained('users')
                          ->nullOnDelete();
                }

                // 2. Zid 'fournisseur'
                if (!Schema::hasColumn('achats', 'fournisseur')) {
                    $table->string('fournisseur')->nullable();
                }

                // 3. Zid 'numero_bon'
                if (!Schema::hasColumn('achats', 'numero_bon')) {
                    $table->string('numero_bon')->nullable();
                }

                // 4. Zid 'date_achat'
                if (!Schema::hasColumn('achats', 'date_achat')) {
                    $table->date('date_achat')->nullable();
                }

                // 5. Zid 'notes'
                if (!Schema::hasColumn('achats', 'notes')) {
                    $table->text('notes')->nullable();
                }

                // 6. Zid Soft Delete
                if (!Schema::hasColumn('achats', 'deleted_at')) {
                    $table->softDeletes();
                }
            });
            
            // 7. Update date_achat (حط date من created_at)
            DB::statement("
                UPDATE achats 
                SET date_achat = DATE(created_at)
                WHERE date_achat IS NULL
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('achats')) {
            Schema::table('achats', function (Blueprint $table) {
                // Drop Foreign Key ديال user_id قبل ما نمسحو العمود
                if (Schema::hasColumn('achats', 'user_id')) {
                    // نلقاو اسم الـ foreign key constraint
                    $foreignKeys = DB::select(
                        "SELECT CONSTRAINT_NAME 
                         FROM information_schema.KEY_COLUMN_USAGE 
                         WHERE TABLE_NAME = 'achats' 
                         AND COLUMN_NAME = 'user_id' 
                         AND REFERENCED_TABLE_NAME IS NOT NULL"
                    );
                    
                    if (!empty($foreignKeys)) {
                        $constraintName = $foreignKeys[0]->CONSTRAINT_NAME;
                        DB::statement("ALTER TABLE achats DROP FOREIGN KEY {$constraintName}");
                    }
                    
                    $table->dropColumn('user_id');
                }

                // مسح الأعمدة الأخرى
                $columnsToDelete = ['fournisseur', 'numero_bon', 'date_achat', 'notes'];
                
                foreach ($columnsToDelete as $column) {
                    if (Schema::hasColumn('achats', $column)) {
                        $table->dropColumn($column);
                    }
                }
                
                // مسح Soft Delete
                if (Schema::hasColumn('achats', 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};