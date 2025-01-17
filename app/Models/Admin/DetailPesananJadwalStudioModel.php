<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananJadwalStudioModel extends Model
{
    use HasFactory;

    protected $table = "detail_pesanan_jadwal_studio";
    protected $primaryKey = "id_detail_pesanan_jadwal_studio";
    protected $fillable = [
        "id_pesanan_jadwal_studio",
        "status_persetujuan",
        "status_pembayaran",
        "status_peminjaman",
        "img_kondisi_awal",
        "img_kondisi_akhir",
        // "harga_sewa",
        "rating",
        "review",
    ];
}
