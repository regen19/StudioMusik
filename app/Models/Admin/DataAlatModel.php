<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataAlatModel extends Model
{
    use HasFactory;

<<<<<<< Updated upstream
    protected $table = "data_alat";
    protected $primaryKey = "id_alat";
    protected $fillable = [
        "foto_alat",
        "nama_alat",
        "tipe_alat",
        "jumlah_alat",
        "biaya_perawatan",
        "status",
    ];
}
=======
    protected $table = 'data_alat';
    protected $primaryKey = 'id_alat';
    protected $fillable = [
        'foto_alat',
        'nama_alat',
        'tipe_alat',
        'jumlah_alat',
        'biaya_perawatan',
        'status',
    ];

    public function detailPeminjaman()
    {
        return $this->hasMany(
            \App\Models\Admin\DetailPesananJadwalAlatModel::class,
            'id_alat',
            'id_alat'
        );
    }
}
>>>>>>> Stashed changes
