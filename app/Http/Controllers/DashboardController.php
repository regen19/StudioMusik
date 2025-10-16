<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user  = auth()->user();

        $start = Carbon::now()->startOfMonth();
        $end   = Carbon::now()->endOfMonth();

        $studio_musik_approve = DB::table('pesanan_jadwal_studio as p')
            ->join('detail_pesanan_jadwal_studio as d', 'd.id_pesanan_jadwal_studio', '=', 'p.id_pesanan_jadwal_studio')
            ->whereBetween('p.tgl_pinjam', [$start, $end])
            ->where('d.status_peminjaman', 'Y')
            ->count();

        $peminjaman_alat = DB::table('pesanan_pinjam_alat')
            ->whereBetween('tgl_pinjam', [$start, $end])
            ->whereIn('status_persetujuan', ['P','Y'])
            ->where('status_pengembalian', 'N')
            ->count();

        $jasa_musik_approve = DB::table('pesanan_jasa_musik')
            ->whereBetween('tgl_produksi', [$start, $end])
            ->where('status_produksi', 'Y')
            ->count();

        $laporan = DB::table('laporan')
            ->whereBetween('tgl_laporan', [$start, $end])
            ->count();

        $jumlah_user = DB::table('users')->count();

        $data = [(object)['username' => $user->username]];

        return view('admin.dashboard', compact(
            'data',
            'studio_musik_approve',
            'peminjaman_alat',
            'jasa_musik_approve',
            'laporan',
            'jumlah_user'
        ));
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
