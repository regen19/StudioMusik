<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailDataAlatModel extends Model
{
    use HasFactory;

    protected $table = "detail_data_alat";
    protected $primaryKey = "id_detail_data_alat";
    protected $fillable = [
        "id_alat",
        "img_ruangan",
    ];
}
