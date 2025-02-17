<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterJasaMusikModel extends Model
{
    use HasFactory;

    protected $table = 'master_jasa_musik';

    protected $primaryKey = 'id_jasa_musik';

    protected $fillable = [
        'nama_jenis_jasa',
        'sk',
        'deskripsi',
        'gambar',
    ];

    public function formJasa(): HasMany
    {
        return $this->hasMany(
            FormJasaMusikModel::class,
            'jasa_musik_id',
            'id_jasa_musik'
        );
    }
}
