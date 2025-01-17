<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('detail_data_ruangan', function (Blueprint $table) {
            $table->id("id_detail_ruangan");
            $table->unsignedBigInteger('id_ruangan');
            $table->string("img_ruangan");
            $table->timestamps();

            $table->foreign('id_ruangan')->references('id_ruangan')->on('data_ruangan')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_data_ruangan');
    }
};
