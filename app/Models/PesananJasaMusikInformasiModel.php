<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananJasaMusikInformasiModel extends Model
{
    use HasFactory;

    protected $table = 'pesanan_jasa_musik_informasi';

    protected $fillable = [
        'pesanan_jasa_musik_id',
        'nama_field',
        'tipe_field',
        'value_field',
    ];
}
