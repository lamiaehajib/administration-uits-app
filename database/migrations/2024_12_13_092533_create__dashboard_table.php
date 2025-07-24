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
    Schema::create('_dashboard', function (Blueprint $table) {
        $table->id();

        // Factures Items
        $table->foreignId('factures_id')->nullable()->constrained('factures')->onDelete('cascade');
        $table->foreignId('devis_id')->nullable()->constrained('devis')->onDelete('cascade');
        $table->foreignId('devisf_id')->nullable()->constrained('devisf')->onDelete('cascade');
        $table->foreignId('reussitef_id')->nullable()->constrained('reussitefs')->onDelete('cascade');
        $table->foreignId('reussite_id')->nullable()->constrained('reussites')->onDelete('cascade');
        $table->foreignId('attestation_allinone_id')->nullable()->constrained('attestations_allinone')->onDelete('cascade');
        $table->foreignId('attestation_formation_id')->nullable()->constrained('attestations_formation')->onDelete('cascade');
        $table->foreignId('attestation_id')->nullable()->constrained('attestations')->onDelete('cascade');

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_dashboard');
    }
};
