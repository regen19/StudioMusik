<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = User::all();

        $bulan = date("m");
        $studio_musik_approve = DB::table('pesanan_jadwal_studio')
            ->join("detail_pesanan_jadwal_studio", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->where("detail_pesanan_jadwal_studio.status_peminjaman", "Y")
            ->whereMonth("pesanan_jadwal_studio.tgl_pinjam", $bulan)
            ->count();

        $jasa_musik_approve = DB::table('pesanan_jasa_musik')
            ->where("status_produksi", "Y")
            ->whereMonth("tgl_produksi", $bulan)
            ->count();

        $laporan = DB::table('laporan')
            ->whereMonth("tgl_laporan", $bulan)
            ->count();

        $jumlah_user = DB::table('users')
            ->count();

        return view('admin.dashboard', compact(['data', 'studio_musik_approve', 'jasa_musik_approve', 'laporan', 'jumlah_user']));
    }

    public function dashboard_user()
    {

        $display = DB::table("master_jasa_musik")
            ->get();

        return view('user.dashboard_user', compact([
            'display'
        ]));
    }
}
