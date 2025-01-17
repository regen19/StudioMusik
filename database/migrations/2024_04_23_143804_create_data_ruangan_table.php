<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('data_ruangan', function (Blueprint $table) {
            $table->id('id_ruangan');
            $table->string("thumbnail");
            $table->string("nama_ruangan");
            $table->integer("kapasitas")->nullable();
            $table->longText("fasilitas")->nullable();
            $table->integer("harga_sewa")->nullable();
            $table->string("lokasi")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_ruangan');
    }
};
