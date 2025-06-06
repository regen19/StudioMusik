<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanModel extends Model
{
    use HasFactory;

    protected $table = "laporan";
    protected $primaryKey = "id_laporan";
    protected $fillable = [
        "tgl_laporan",
        "jenis_laporan",
        "keterangan",
        "gambar",
    ];

    protected $casts = ['gambar' => 'array'];
}
