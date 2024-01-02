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
            $table->id();
            $table->unsignedBigInteger("kategori_id")->nullable(false);
            $table->string("nama")->nullable(false);
            $table->string("deskripsi", 255)->nullable(false);
            $table->integer("hargaperhari")->nullable(false);
            $table->integer("stok")->nullable(false);
            $table->timestamps();

            $table->foreign("kategori_id")->on("kategoris")->references("id");
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
