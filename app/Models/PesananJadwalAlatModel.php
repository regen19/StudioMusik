<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananJadwalAlatModel extends Model
{
    use HasFactory;

    protected $table = "pesanan_pinjam_alat";
    protected $primaryKey = "id_pesanan_pinjam_alat";
    protected $fillable = [
        'id_user',
        'id_alat',
        'tgl_pinjam',
        'tgl_kembali',
        'waktu_mulai',
        'waktu_selesai',
        'ket_keperluan',
        'ket_admin',
        'foto_jaminan',
    ];

    protected $attributes = [
        'ket_admin' => "",
    ];
}
