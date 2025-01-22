<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormJasaMusikModel extends Model
{
    use HasFactory;

    protected $table = 'form_jasa_musik';

    protected $fillable = [
        'jasa_musik_id',
        'nama_field',
        'jenis_field',
    ];
}
