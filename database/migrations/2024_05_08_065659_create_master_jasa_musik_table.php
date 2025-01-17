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
        Schema::create('master_jasa_musik', function (Blueprint $table) {
            $table->id("id_jasa_musik");
            $table->string("nama_jenis_jasa");
            $table->longText("sk")->nullable();
            $table->longText("deskripsi")->nullable();
            $table->string('gambar')->nullable();
            // $table->longText("keterangan")->nullable();
            $table->string("biaya_produksi")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_jasa_musik');
    }
};
