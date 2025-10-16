<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('detail_pesanan_pinjam_alat', function (Blueprint $table) {
        // Tambahkan kolom 'status_pengajuan' dengan tipe string (VARCHAR)
        // dan batasan panjang 1 karakter (untuk menyimpan 'Y' atau 'N')
        $table->enum('status_pengajuan', ['Y', 'N', 'X'])->after('status_persetujuan'); 
        // Atau: $table->enum('status_pengajuan', ['Y', 'N'])->after('status_persetujuan');
    });
    
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pesanan_pinjam_alat', function (Blueprint $table) {
            //
        });
    }
};
