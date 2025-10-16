<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAlat extends Model
{
    protected $table = 'data_alat';
    protected $primaryKey = 'id_alat';
    public $timestamps = false;
    protected $guarded = [];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesananPinjamAlat::class, 'id_alat', 'id_alat');
    }
}