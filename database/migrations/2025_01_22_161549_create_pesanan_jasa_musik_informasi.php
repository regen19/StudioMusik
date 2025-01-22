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
        Schema::create('pesanan_jasa_musik_informasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_jasa_musik_id');
            $table->string('tipe_field');
            $table->string('nama_field');
            $table->text('value_field');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_jasa_musik_informasi');
    }
};
