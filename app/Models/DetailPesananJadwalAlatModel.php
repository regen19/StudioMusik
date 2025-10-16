<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesananJadwalAlatModel extends Model
{
    protected $table = 'detail_pesanan_pinjam_alat';
    protected $primaryKey = 'id_detail_pesanan_pinjam_alat';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan_pinjam_alat',
        'id_alat',
        'jumlah',
        'img_kondisi_awal',
        'img_kondisi_akhir',
        'biaya_perawatan',
        'status_persetujuan',
        'status_pengajuan',
        'status_peminjaman',
    ];

    public function alat()
    {
        return $this->belongsTo(\App\Models\DataAlatModel::class, 'id_alat', 'id_alat');
    }
}