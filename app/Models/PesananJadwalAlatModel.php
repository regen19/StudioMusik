<?php

namespace App\Models;

<<<<<<< Updated upstream
use Illuminate\Database\Eloquent\Factories\HasFactory;
=======
>>>>>>> Stashed changes
use Illuminate\Database\Eloquent\Model;

class PesananJadwalAlatModel extends Model
{
<<<<<<< Updated upstream
    use HasFactory;

    protected $table = "pesanan_pinjam_alat";
    protected $primaryKey = "id_pesanan_pinjam_alat";
    protected $fillable = [
        'id_user',
        'id_alat',
        'tgl_pinjam',
        'tgl_kembali',
        'waktu_mulai',
        'waktu_selesai',
        'ket_keperluan',
        'ket_admin',
        'foto_jaminan',
    ];

    protected $attributes = [
        'ket_admin' => "",
    ];
}
=======
    protected $table = 'pesanan_pinjam_alat';
    protected $primaryKey = 'id_pesanan_pinjam_alat';
    public $timestamps = true;

    protected $fillable = [
        'id_user','tgl_pinjam','tgl_kembali','waktu_mulai','waktu_selesai',
        'ket_keperluan','status_persetujuan','status_pengembalian',
        'ket_admin','foto_jaminan'
    ];
    
    public function details()
    {
        return $this->hasMany(DetailPesananJadwalAlatModel::class, 'id_pesanan_pinjam_alat', 'id_pesanan_pinjam_alat')
                    ->with(['alat:id_alat,nama_alat,foto_alat']);
    }
}
>>>>>>> Stashed changes
