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
            $table->id();
            $table->unsignedBigInteger("penyewaan_id")->nullable(false);
            $table->unsignedBigInteger("alat_id")->nullable(false);
            $table->integer("jumlah")->nullable(false);
            $table->integer("subharga")->nullable(false);
            $table->timestamps();

            
            $table->foreign("penyewaan_id")->on("penyewaans")->references("id");
            $table->foreign("alat_id")->on("alats")->references("id");
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
