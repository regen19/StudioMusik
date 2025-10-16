<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';        // sesuai nama tabel
    protected $primaryKey = 'id_user'; // sesuai kolom primary key
    public $incrementing = true;       // karena id_user AUTO_INCREMENT
    protected $keyType = 'int';        // tipe kolom bigint

    protected $fillable = [
        'username',
        'email',
        'password',
        'no_wa',
        'user_role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 10+ otomatis hash
    ];
}
