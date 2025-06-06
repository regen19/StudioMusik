<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataRuanganUserController extends Controller
{
    public function index()
    {
        $data_ruangan = DB::table("data_ruangan")
            // ->leftJoin("detail_data_ruangan", "detail_data_ruangan.id_ruangan", "=", "data_ruangan.id_ruangan")
            ->get();

        // dd($data_ruangan);

        return view('user.data_ruangan_studio_usr.data_ruangan_usr', compact([
            'data_ruangan'
        ]));
    }

    public function user_review_ruangan($id_ruangan)
    {
        $data_ruangan = DB::table("data_ruangan")
            ->where("id_ruangan", $id_ruangan)
            ->first();

        $review_user = DB::table("pesanan_jadwal_studio")
            ->leftJoin("detail_pesanan_jadwal_studio", "pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->leftJoin("data_ruangan", "data_ruangan.id_ruangan", "=", "pesanan_jadwal_studio.id_ruangan")
            ->leftJoin('users', 'users.id_user', "=", "pesanan_jadwal_studio.id_user")
            ->where("pesanan_jadwal_studio.id_ruangan", $id_ruangan)
            ->where("detail_pesanan_jadwal_studio.rating", "!=", null)
            ->select("users.username", "users.email", "detail_pesanan_jadwal_studio.*")
            ->get();

        // dd($review_user);

        return view('user.data_ruangan_studio_usr.user_revies_ruangan', compact([
            'data_ruangan',
            'review_user'
        ]));
    }
}
