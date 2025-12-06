<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up() {
        // Modifie l'enum pour ajouter 30_jours
        DB::statement("ALTER TABLE recus_ucgs MODIFY COLUMN type_garantie ENUM('30_jours', '90_jours', '180_jours', '360_jours', 'sans_garantie') DEFAULT '90_jours'");
    }

    public function down() {
        // Rollback (supprimer 30_jours)
        DB::statement("ALTER TABLE recus_ucgs MODIFY COLUMN type_garantie ENUM('90_jours', '180_jours', '360_jours', 'sans_garantie') DEFAULT '90_jours'");
    }
};