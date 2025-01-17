<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketJasaMusikModel extends Model
{
    use HasFactory;

    protected $table = "paket_jasa_musik";
    protected $primaryKey = "id_paket_jasa_musik";
    protected $fillable = [
        'id_jasa_musik',
        'nama_paket',
        'deskripsi',
        'rincian_paket',
        'biaya_paket'
    ];
}
