<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananJadwalAlatModel extends Model
{
    use HasFactory;

    protected $table = "detail_pesanan_jadwal_alat";
    protected $primaryKey = "id_detail_pesanan_jadwal_alat";
    protected $fillable = [
        "id_pesanan_jadwal_alat",
        "status_persetujuan",
        "status_pengajuan",
        "status_peminjaman",
        "img_kondisi_awal",
        "img_kondisi_akhir",
        "biaya_perawatan",
    ];
}
