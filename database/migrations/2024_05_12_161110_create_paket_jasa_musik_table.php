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
        Schema::create('paket_jasa_musik', function (Blueprint $table) {
            $table->id('id_paket_jasa_musik');
            $table->unsignedBigInteger("id_jasa_musik");
            $table->string("nama_paket");
            $table->longText("deskripsi")->nullable();
            $table->longText("rincian_paket")->nullable();
            $table->string('biaya_paket')->nullable();
            $table->timestamps();

            $table->foreign('id_jasa_musik')->references('id_jasa_musik')->on('master_jasa_musik')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_jasa_musik');
    }
};
