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
        Schema::create('form_jasa_musik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jasa_musik_id');
            $table->string('nama_field');
            $table->string('jenis_field');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_jasa_musik');
    }
};
