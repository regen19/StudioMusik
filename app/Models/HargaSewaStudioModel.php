<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSewaStudioModel extends Model
{
    use HasFactory;

    protected $table = "harga_sewa_studio";
    protected $primaryKey = "id_harga_sewa_studio";
    protected $fillable = [
        "harga_sewa",
    ];
}
