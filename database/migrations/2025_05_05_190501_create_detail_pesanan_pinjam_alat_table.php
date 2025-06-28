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
        Schema::create('detail_pesanan_pinjam_alat', function (Blueprint $table) {
            $table->id('id_detail_pesanan_pinjam_alat');
            $table->unsignedBigInteger('id_pesanan_pinjam_alat');
            $table->unsignedBigInteger('id_alat');
            $table->integer('jumlah');
            $table->string('img_kondisi_awal')->nullable();
            $table->string('img_kondisi_akhir')->nullable();
            $table->integer('biaya_perawatan')->nullable();
            $table->timestamps();
            $table->foreign('id_alat')->references('id_alat')->on('data_alat')->cascadeOnDelete();
            $table->foreign('id_pesanan_pinjam_alat')->references('id_pesanan_pinjam_alat')->on('pesanan_pinjam_alat')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan_pinjam_alat');
    }
};
