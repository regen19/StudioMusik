<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananJadwalStudioModel extends Model
{
    use HasFactory;

    protected $table = "pesanan_jadwal_studio";
    protected $primaryKey = "id_pesanan_jadwal_studio";
    protected $fillable = [
        'id_user',
        'id_ruangan',
        'tgl_pinjam',
        'waktu_mulai',
        'waktu_selesai',
        'img_jaminan',
        'ket_keperluan',
        'no_wa',
        'ket_admin',
    ];
}
