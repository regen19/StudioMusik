<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('detail_pesanan_jadwal_studio', function (Blueprint $table) {
            $table->id('id_detail_pesanan_jadwal_studio');
            $table->unsignedBigInteger('id_pesanan_jadwal_studio');

            $table->enum("status_persetujuan", ['Y', 'N', 'P'])->default("P");
            $table->enum("status_pengajuan", ['Y', 'X'])->default("Y");
            $table->enum("status_peminjaman", ['Y', 'N'])->default("N");

            $table->string("img_kondisi_awal")->nullable();
            $table->string("img_kondisi_akhir")->nullable();
            $table->integer("harga_sewa")->nullable();
            $table->string("rating")->nullable();
            $table->string("review")->nullable();
            $table->timestamps();

            $table->foreign('id_pesanan_jadwal_studio')->references('id_pesanan_jadwal_studio')->on('pesanan_jadwal_studio')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan_jadwal_studio');
    }
};
