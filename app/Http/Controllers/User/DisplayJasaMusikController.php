<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MasterJasaMusikModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisplayJasaMusikController extends Controller
{
    public function pembuatan_jasa_musik($id_jasa_musik)
    {
        $jenis_jasa = DB::table("master_jasa_musik")
            ->where('id_jasa_musik', $id_jasa_musik)
            ->first();

        if ($jenis_jasa === null) {

            return redirect('/system_error_500');
        }

        $rating =
            DB::table("pesanan_jasa_musik")
            ->join('users', "users.id_user", '=', 'pesanan_jasa_musik.id_user')
            ->where('pesanan_jasa_musik.id_jasa_musik', $id_jasa_musik)
            ->select("pesanan_jasa_musik.id_pesanan_jasa_musik", "pesanan_jasa_musik.rating", "pesanan_jasa_musik.review", "pesanan_jasa_musik.updated_at", 'users.username')
            ->get();

        return view('user.jasa_musik_usr.jasa_jasa.pembuatan_jasa_musik',  compact([
            'jenis_jasa',
            'rating',
        ]));
    }
}
