<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailDataRuanganModel extends Model
{
    use HasFactory;

    protected $table = "detail_data_ruangan";
    protected $primaryKey = "id_detail_data_ruangan";
    protected $fillable = [
        "id_ruangan",
        "img_ruangan",
    ];
}
