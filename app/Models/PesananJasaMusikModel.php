<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PesananJasaMusikModel extends Model
{
    use HasFactory;

    protected $table = "pesanan_jasa_musik";
    protected $primaryKey = "id_pesanan_jasa_musik";
    protected $fillable = [
        'tgl_produksi',
        'tenggat_produksi',
        'id_jasa_musik',
        'id_user',
        'keterangan',
        'keterangan_admin',
        'status_persetujuan',
        'status_pengajuan',
        'status_produksi',
        'review',
        'rating'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function jasaInformasi(): HasMany
    {
        return $this->hasMany(
            PesananJasaMusikInformasiModel::class,
            'pesanan_jasa_musik_id',
            'id_pesanan_jasa_musik',
        );
    }

    public function masterJasaMusik(): HasOne
    {
        return $this->hasOne(
            MasterJasaMusikModel::class,
            'id_jasa_musik',
            'id_jasa_musik'
        );
    }

    public function users(): HasOne
    {
        return $this->HasOne(
            User::class,
            'id_user',
            'id_user'
        );
    }
}
