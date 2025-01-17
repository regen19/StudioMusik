<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_jenis_jasa', function (Blueprint $table) {
            $table->id('id_jenis_jasa');
            $table->string('nama_jenis_jasa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_jenis_jasa');
    }
};
