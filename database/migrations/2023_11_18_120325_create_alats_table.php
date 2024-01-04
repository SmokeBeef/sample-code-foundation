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
        Schema::create('alats', function (Blueprint $table) {
            $table->id("alat_id");
            $table->unsignedBigInteger("alat_kategori_id")->nullable(false);
            $table->string("alat_nama")->nullable(false);
            $table->string("alat_deskripsi", 255)->nullable(false);
            $table->integer("alat_hargaperhari")->nullable(false);
            $table->integer("alat_stok")->nullable(false);
            $table->timestamps();

            $table->foreign("alat_kategori_id")->on("kategoris")->references("kategori_id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};
