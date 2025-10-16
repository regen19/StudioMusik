<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananPinjamAlat extends Model
{
    protected $table = 'pesanan_pinjam_alat';
    protected $primaryKey = 'id_pesanan_pinjam_alat';
    protected $fillable = [
        'id_user','tgl_pinjam','tgl_kembali','waktu_mulai','waktu_selesai',
        'ket_keperluan','ket_admin','foto_jaminan','status_persetujuan',
        'status_pengembalian','img_kondisi_awal','img_kondisi_akhir',
    ];

    protected $attributes = [
        'status_persetujuan'  => 'P',
        'status_pengembalian' => 'N',
    ];

    protected static function booted()
    {
        static::creating(function ($m) {
            $m->status_persetujuan  = $m->status_persetujuan  ?: 'P';
            $m->status_pengembalian = $m->status_pengembalian ?: 'N';
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function details()
    {
        return $this->hasMany(DetailPesananPinjamAlat::class, 'id_pesanan_pinjam_alat', 'id_pesanan_pinjam_alat');
    }
}