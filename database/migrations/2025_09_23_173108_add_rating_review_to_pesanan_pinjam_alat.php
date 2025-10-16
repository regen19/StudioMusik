<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pesanan_pinjam_alat', function (Blueprint $table) {
            if (!Schema::hasColumn('pesanan_pinjam_alat', 'rating')) {
                $table->tinyInteger('rating')->nullable()->after('status_pengembalian');
            }
            if (!Schema::hasColumn('pesanan_pinjam_alat', 'review')) {
                $table->text('review')->nullable()->after('rating');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_pinjam_alat', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan_pinjam_alat', 'review')) {
                $table->dropColumn('review');
            }
            if (Schema::hasColumn('pesanan_pinjam_alat', 'rating')) {
                $table->dropColumn('rating');
            }
        });
    }
};