<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorialPenggunaanAlatModel extends Model
{
    use HasFactory;

    protected $table = "tutorial_penggunaan_alat";
    protected $primaryKey = "id_tutorial";
    protected $fillable = [
        'nama_alat',
        'gambar_alat',
        'deskripsi',
    ];
}
