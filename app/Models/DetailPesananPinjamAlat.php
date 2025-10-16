<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPesananPinjamAlat extends Model
{
    protected $table = 'detail_pesanan_pinjam_alat';
    protected $primaryKey = 'id_detail_pesanan_pinjam_alat';

    protected $fillable = [
        'id_pesanan_pinjam_alat',
        'id_alat',
        'jumlah',
        'img_kondisi_awal',
        'img_kondisi_akhir',
        'biaya_perawatan',
    ];

    public function pesanan() {
        return $this->belongsTo(PesananPinjamAlat::class, 'id_pesanan_pinjam_alat', 'id_pesanan_pinjam_alat');
    }
    
    public function alat()
    {
        return $this->belongsTo(\App\Models\Admin\DataAlatModel::class, 'id_alat', 'id_alat');
    }
}