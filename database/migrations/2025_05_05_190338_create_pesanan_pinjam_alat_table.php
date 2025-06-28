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
        Schema::create('pesanan_pinjam_alat', function (Blueprint $table) {
            $table->id('id_pesanan_pinjam_alat');
            $table->unsignedBigInteger('id_user');
            
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->time("waktu_mulai");
            $table->time("waktu_selesai");
            $table->longText('ket_keperluan');
            $table->longText('ket_admin');
            $table->string('foto_jaminan');
            $table->enum('status_persetujuan', ['Y', 'N', 'P'])->default('P');
            $table->enum('status_pengembalian', ['Y', 'N'])->default('N');
            
            $table->timestamps();
            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_pinjam_alat');
    }
};
