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
        Schema::create('data_alat', function (Blueprint $table) {
            $table->id('id_alat');
            $table->string('nama_alat');
            $table->string('tipe_alat');
            $table->integer('jumlah_alat');
            $table->string('foto_alat');
            $table->integer('biaya_perawatan');
            $table->enum('status', ['Tersedia', 'Dipinjam', 'Rusak'])->default('Tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_alat');
    }
};
