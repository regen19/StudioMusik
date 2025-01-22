<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        $user = [
            [
                'id_user' => 1,
                'username' => "ADMIN",
                'email' => 'admin@gmail.com',
                'no_wa' => '0811',
                'password' => bcrypt('111'),
                'user_role' => "admin" //atasan
            ],
            [
                'id_user' => 2,
                'username' => "K3L",
                'email' => 'k3l@gmail.com',
                'no_wa' => '0822',
                'password' => bcrypt('222'),
                'user_role' => "k3l" //atasan
            ],
            [
                'id_user' => 3,
                'username' => "MAHASISWA",
                'email' => 'mhs@gmail.com',
                'no_wa' => '0833',
                'password' => bcrypt('333'),
                'user_role' => "user" //atasan
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
