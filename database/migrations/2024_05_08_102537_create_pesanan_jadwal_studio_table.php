<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_jadwal_studio', function (Blueprint $table) {
            $table->id('id_pesanan_jadwal_studio');
            $table->unsignedBigInteger("id_user");
            $table->unsignedBigInteger("id_ruangan");
            $table->bigInteger("no_wa");
            $table->date("tgl_pinjam");
            $table->time("waktu_mulai");
            $table->time("waktu_selesai");
            $table->string("img_jaminan");
            $table->longText("ket_keperluan");
            $table->longText("ket_admin")->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
            $table->foreign('id_ruangan')->references('id_ruangan')->on('data_ruangan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_jadwal_studio');
    }
};
