<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataAlatUserController extends Controller
{
    public function index()
    {
        $data_alat = DB::table("data_alat")
            
            ->get();

        

        return view('user.data_alat_studio_usr.data_alat_usr', compact([
            'data_alat'
        ]));
    }

    public function user_review_ruangan($id_ruangan)
    {
        $data_alat = DB::table("data_alat")
            ->where("id_ruangan", $id_ruangan)
            ->first();

        $review_user = DB::table("pesanan_jadwal_studio")
            ->leftJoin("detail_pesanan_jadwal_studio", "pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->leftJoin("data_alat", "data_alat.id_ruangan", "=", "pesanan_jadwal_studio.id_ruangan")
            ->leftJoin('users', 'users.id_user', "=", "pesanan_jadwal_studio.id_user")
            ->where("pesanan_jadwal_studio.id_ruangan", $id_ruangan)
            ->get();

        // dd($review_user);

        return view('user.data_alat_studio_usr.user_revies_ruangan', compact([
            'data_alat',
            'review_user'
        ]));
    }
}
