<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyewaan_details', function (Blueprint $table) {
            $table->id('penyewaan_detail_id');
            $table->unsignedBigInteger("penyewaan_detail_penyewaan_id")->nullable(false);
            $table->unsignedBigInteger("penyewaan_detail_alat_id")->nullable(false);
            $table->integer("penyewaan_detail_jumlah")->nullable(false);
            $table->integer("penyewaan_detail_subharga")->nullable(false);
            $table->timestamps();

            
            $table->foreign("penyewaan_detail_penyewaan_id")->on("penyewaans")->references("penyewaan_id");
            $table->foreign("penyewaan_detail_alat_id")->on("alats")->references("alat_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaan_details');
    }
};
