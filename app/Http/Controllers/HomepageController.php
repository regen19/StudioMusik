<?php

namespace App\Http\Controllers;

use App\Models\PesananJadwalStudioModel;
use App\Models\PesananJasaMusikModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{
    public function index()
    {
        $pesananJasaMusik = PesananJasaMusikModel::select(
            'pesanan_jasa_musik.review',
            'pesanan_jasa_musik.rating',
            'users.username',
            'users.email',
            DB::raw("'Jasa Musik' as type")
        )
            ->join('users', 'pesanan_jasa_musik.id_user', '=', 'users.id_user')
            ->whereNotNull('pesanan_jasa_musik.rating')
            ->whereNotNull('pesanan_jasa_musik.review')
            ->orderBy('pesanan_jasa_musik.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->label = 'Jasa Musik';
                return $item;
            });

        $pesananJadwalStudio = PesananJadwalStudioModel::select(
            'detail_pesanan_jadwal_studio.review',
            'detail_pesanan_jadwal_studio.rating',
            'users.username',
            'users.email',
            DB::raw("'Studio Musik' as type")
        )
            ->join('detail_pesanan_jadwal_studio', 'pesanan_jadwal_studio.id_pesanan_jadwal_studio', '=', 'detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio')
            ->join('users', 'pesanan_jadwal_studio.id_user', '=', 'users.id_user')
            ->whereNotNull('detail_pesanan_jadwal_studio.rating')
            ->whereNotNull('detail_pesanan_jadwal_studio.review')
            ->orderBy('pesanan_jadwal_studio.created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                $item->label = 'Peminjaman Studio';
                return $item;
            });

        $gabunganPesanan = $pesananJasaMusik->concat($pesananJadwalStudio);
       

        $dataReview = $gabunganPesanan->sortByDesc('created_at');

        return view('homepage', compact('dataReview'));
    }
}
