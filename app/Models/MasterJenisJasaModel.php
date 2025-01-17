<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterJenisJasaModel extends Model
{
    use HasFactory;

    protected $table = "master_jenis_jasa";
    protected $primaryKey = "id_jenis_jasa";
    protected $fillable = [
        'nama_jenis_jasa',
    ];
}
