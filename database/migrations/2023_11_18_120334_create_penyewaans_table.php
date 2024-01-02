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
        Schema::create('penyewaans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("pelanggan_id")->nullable(false);
            $table->date("tglsewa")->nullable(false);
            $table->date("tglkembali")->nullable(false);
            $table->enum("sttspembayaran", ["Lunas", "Belum Dibayar", "DP"])->default("Belum Dibayar")->nullable(false);
            $table->enum("sttskembali", ["Sudah Kembali", "Belum Kembali"])->default("Belum Kembali")->nullable(false);
            $table->integer("totalharga")->nullable(false);
            $table->timestamps();
            
            $table->foreign("pelanggan_id")->on("pelanggans")->references("id");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaans');
    }
};
