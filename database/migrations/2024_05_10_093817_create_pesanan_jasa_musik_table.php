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
        Schema::create('pesanan_jasa_musik', function (Blueprint $table) {
            $table->id('id_pesanan_jasa_musik');
            $table->unsignedBigInteger("id_user");
            $table->unsignedBigInteger("id_jasa_musik");
            $table->timestamp("tgl_produksi");
            $table->timestamp("tenggat_produksi");
            $table->longText("keterangan");
            $table->longText("keterangan_admin")->nullable();
            $table->enum("status_persetujuan", ['Y', 'N', 'P'])->default("P");
            $table->enum("status_pengajuan", ['Y', 'X'])->default("Y");
            $table->enum("status_produksi", ['Y', 'N', 'P'])->default("N");
            $table->string('rating')->nullable();
            $table->string('review')->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id_user')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('id_jasa_musik')->references('id_jasa_musik')->on('master_jasa_musik')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_jasa_musik');
    }
};
