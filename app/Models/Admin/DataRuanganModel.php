<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataRuanganModel extends Model
{
    use HasFactory;

    protected $table = "data_ruangan";
    protected $primaryKey = "id_ruangan";
    protected $fillable = [
        "thumbnail",
        "nama_ruangan",
        "kapasitas",
        "fasilitas",
        "lokasi",
        // "harga_sewa",
    ];
}
