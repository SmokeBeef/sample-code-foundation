<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pelanggan_datas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("pelanggan_id")->nullable(false);
            $table->enum("jenis", ["KTP", "SIM"])->nullable(false);
            $table->string("file", 255)->nullable(false);
            $table->timestamps();

            $table->foreign("pelanggan_id")->on("pelanggans")->references("id")->onDelete("CASCADE");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggan_datas');
    }
};
