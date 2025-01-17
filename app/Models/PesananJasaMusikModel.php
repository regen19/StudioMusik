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
        'id_jenis_jasa',
        'id_user',
        'no_wa',
        'keterangan',
        'keterangan_admin',
        'status_persetujuan',
        'status_pembayaran',
        'status_produksi',
        'review',
        'rating'
    ];
}
