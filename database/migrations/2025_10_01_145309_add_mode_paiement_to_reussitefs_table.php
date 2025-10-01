<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('reussitefs', function (Blueprint $table) {
        // Ajout du champ 'mode_paiement' avec les valeurs spécifiques
        $table->enum('mode_paiement', ['espèce', 'virement', 'chèque'])->nullable()->after('rest');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
      public function down()
    {
        Schema::table('reussitefs', function (Blueprint $table) {
            // Suppression du champ 'mode_paiement'
            $table->dropColumn('mode_paiement');
        });
    }
};
