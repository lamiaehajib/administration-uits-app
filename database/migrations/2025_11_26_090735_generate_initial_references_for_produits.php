<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        if (Schema::hasTable('produits')) {
            // Générer références lil produits li ma 3andhomch
            DB::statement("
                UPDATE produits 
                SET reference = CONCAT('PROD-', LPAD(id, 5, '0'))
                WHERE reference IS NULL
            ");
        }
    }

    public function down() {
        // Optionnel: ila bghiti tmsah les références, walakin hadchi ghaliban machi mouhim
        // DB::statement("UPDATE produits SET reference = NULL WHERE reference LIKE 'PROD-%'");
    }
};
