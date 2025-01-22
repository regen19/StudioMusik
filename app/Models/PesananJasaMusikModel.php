<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananJasaMusikModel extends Model
{
    use HasFactory;

    protected $table = "pesanan_jasa_musik";
    protected $primaryKey = "id_pesanan_jasa_musik";
    protected $fillable = [
        'tgl_produksi',
        'tenggat_produksi',
        'id_jasa_musik',
        'id_user',
        'keterangan',
        'keterangan_admin',
        'status_persetujuan',
        'status_pengajuan',
        'status_produksi',
        'review',
        'rating'
    ];
}
