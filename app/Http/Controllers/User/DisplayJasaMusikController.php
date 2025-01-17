<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisplayJasaMusikController extends Controller
{
    public function view_jingle()
    {
        $jingle = DB::table("master_jasa_musik")
            ->where('id_jenis_jasa', 1)
            ->first();

        $rating =
            DB::table("pesanan_jasa_musik")
            ->join('users', "users.id_user", '=', 'pesanan_jasa_musik.id_user')
            ->where('pesanan_jasa_musik.id_jenis_jasa', 1)
            ->select("pesanan_jasa_musik.id_pesanan_jasa_musik", "pesanan_jasa_musik.rating", "pesanan_jasa_musik.review", "pesanan_jasa_musik.updated_at", 'users.username')
            ->get();

        $paket =
            DB::table('paket_jasa_musik')
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "paket_jasa_musik.id_jasa_musik")
            ->join("master_jenis_jasa", "master_jenis_jasa.id_jenis_jasa", "=", "master_jasa_musik.id_jenis_jasa")
            ->select("paket_jasa_musik.*", "master_jenis_jasa.nama_jenis_jasa")
            ->where('paket_jasa_musik.id_jasa_musik', 1)
            ->get();

        return view('user.jasa_musik_usr.jasa_jasa.pembuatan_jingle',  compact([
            'jingle',
            'rating',
            "paket"
        ]));
    }

    public function view_aransemen()
    {
        $aransemen = DB::table("master_jasa_musik")
            ->where('id_jenis_jasa', 2)
            ->first();

        $rating =
            DB::table("pesanan_jasa_musik")
            ->join('users', "users.id_user", '=', 'pesanan_jasa_musik.id_user')
            ->where('pesanan_jasa_musik.id_jenis_jasa', 2)
            ->select("pesanan_jasa_musik.id_pesanan_jasa_musik", "pesanan_jasa_musik.rating", "pesanan_jasa_musik.review", "pesanan_jasa_musik.updated_at", 'users.username')
            ->get();

        $paket =
            DB::table('paket_jasa_musik')
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "paket_jasa_musik.id_jasa_musik")
            ->join("master_jenis_jasa", "master_jenis_jasa.id_jenis_jasa", "=", "master_jasa_musik.id_jenis_jasa")
            ->select("paket_jasa_musik.*", "master_jenis_jasa.nama_jenis_jasa")
            ->where('paket_jasa_musik.id_jasa_musik', 2)
            ->get();

        return view('user.jasa_musik_usr.jasa_jasa.pembuatan_aransemen',  compact([
            'aransemen',
            'rating',
            "paket"
        ]));
    }

    public function view_minusone()
    {
        $minusone = DB::table("master_jasa_musik")
            ->where('id_jenis_jasa', 3)
            ->first();

        $rating =
            DB::table("pesanan_jasa_musik")
            ->join('users', "users.id_user", '=', 'pesanan_jasa_musik.id_user')
            ->where('pesanan_jasa_musik.id_jenis_jasa', 3)
            ->select("pesanan_jasa_musik.id_pesanan_jasa_musik", "pesanan_jasa_musik.rating", "pesanan_jasa_musik.review", "pesanan_jasa_musik.updated_at", 'users.username')
            ->get();


        $paket =
            DB::table('paket_jasa_musik')
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "paket_jasa_musik.id_jasa_musik")
            ->join("master_jenis_jasa", "master_jenis_jasa.id_jenis_jasa", "=", "master_jasa_musik.id_jenis_jasa")
            ->select("paket_jasa_musik.*", "master_jenis_jasa.nama_jenis_jasa")
            ->where('paket_jasa_musik.id_jasa_musik', 3)
            ->get();

        return view('user.jasa_musik_usr.jasa_jasa.pembuatan_minusone',  compact([
            'minusone',
            'rating',
            "paket"
        ]));
    }
}
