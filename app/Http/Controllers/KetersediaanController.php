<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class KetersediaanController extends Controller
{
    public function page(Request $r)
    {
        $start = Carbon::parse($r->start ?? now())->toDateString();
        $end   = Carbon::parse($r->end   ?? $start)->toDateString();

        $stokCol = Schema::hasColumn('data_alat','jumlah_alat') ? 'jumlah_alat'
                 : (Schema::hasColumn('data_alat','stok') ? 'stok' : null);

        $qtyCol  = Schema::hasColumn('detail_pesanan_pinjam_alat','jumlah') ? 'jumlah'
                 : (Schema::hasColumn('detail_pesanan_pinjam_alat','qty') ? 'qty' : 'jumlah');

        $dipinjam = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('pesanan_pinjam_alat as p','p.id_pesanan_pinjam_alat','=','d.id_pesanan_pinjam_alat')
            ->selectRaw('d.id_alat, SUM(d.' . $qtyCol . ') as qty')
            ->where('p.status_persetujuan', 'Y')
            ->where(function($q){
                $q->whereNull('p.status_pengembalian')
                  ->orWhere('p.status_pengembalian','N');
            })
            ->groupBy('d.id_alat')
            ->pluck('qty','id_alat');

        $terpesan = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('pesanan_pinjam_alat as p', 'p.id_pesanan_pinjam_alat', '=', 'd.id_pesanan_pinjam_alat')
            ->where('p.status_persetujuan', 'Y')
            ->groupBy('d.id_alat')
            ->selectRaw('d.id_alat, SUM(d.' . $qtyCol . ') AS qty')
            ->pluck('qty', 'id_alat');

        $alat = DB::table('data_alat')
            ->select('id_alat','nama_alat')
            ->when($stokCol, fn($q) => $q->addSelect($stokCol.' as stok'))
            ->orderBy('nama_alat')
            ->get()
            ->map(function ($a) use ($dipinjam, $terpesan) {
                $stok    = (int)($a->stok ?? 0);
                $pinjam  = (int)($dipinjam[$a->id_alat] ?? 0);
                $booked  = (int)($terpesan[$a->id_alat] ?? 0);

                return (object)[
                    'id_alat'   => $a->id_alat,
                    'nama_alat' => $a->nama_alat,
                    'stok'      => $stok,
                    'dipinjam'  => $pinjam,
                    'terpesan'  => $booked,
                    'sisa'      => max($stok - $pinjam, 0),
                ];
            });

        return view('user.jadwal_alat_usr.ketersediaan', [
            'tglMulai'     => $start,
            'tglSelesai'   => $end,
            'ketersediaan' => $alat,
        ]);
    }

    public function data(Request $r)
    {
        $start = Carbon::parse($r->get('start', now()))->toDateString();
        $end   = Carbon::parse($r->get('end', $start))->toDateString();

        $stokCol = Schema::hasColumn('data_alat','jumlah_alat') ? 'jumlah_alat'
                 : (Schema::hasColumn('data_alat','stok') ? 'stok' : null);

        $qtyCol  = Schema::hasColumn('detail_pesanan_pinjam_alat','jumlah') ? 'jumlah'
                 : (Schema::hasColumn('detail_pesanan_pinjam_alat','qty') ? 'qty' : 'jumlah');

        $dipinjam = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('pesanan_pinjam_alat as p','p.id_pesanan_pinjam_alat','=','d.id_pesanan_pinjam_alat')
            ->selectRaw('d.id_alat, SUM(d.' . $qtyCol . ') as qty')
            ->where('p.status_persetujuan','Y')
            ->where(function($q){
                $q->whereNull('p.status_pengembalian')
                  ->orWhere('p.status_pengembalian','N');
            })
            ->groupBy('d.id_alat')
            ->pluck('qty','id_alat');

        $terpesan = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('pesanan_pinjam_alat as p', 'p.id_pesanan_pinjam_alat', '=', 'd.id_pesanan_pinjam_alat')
            ->where('p.status_persetujuan', 'Y')
            ->groupBy('d.id_alat')
            ->selectRaw('d.id_alat, SUM(d.' . $qtyCol . ') AS qty')
            ->pluck('qty', 'id_alat');

        $rows = DB::table('data_alat as a')
            ->select('a.id_alat','a.nama_alat')
            ->when($stokCol, fn($q) => $q->addSelect('a.'.$stokCol.' as stok'))
            ->orderBy('a.nama_alat')
            ->get()
            ->map(function ($a) use ($dipinjam, $terpesan) {
                $stok   = (int)($a->stok ?? 0);
                $pinjam = (int)($dipinjam[$a->id_alat] ?? 0);
                $booked = (int)($terpesan[$a->id_alat] ?? 0);

                return [
                    'id_alat'          => $a->id_alat,
                    'nama_alat'        => $a->nama_alat,
                    'stok'             => $stok,
                    'dipinjam'         => $pinjam,
                    'terpesan_periode' => $booked,
                    'sisa'             => max($stok - $pinjam, 0),
                    'sisa_periode'     => max($stok - $booked, 0),
                ];
            });

        return response()->json(['data' => $rows]);
    }

    public function uploadKondisi(Request $req, $id)
    {
        $detail = DetailPesananPinjamAlat::where('id_pesanan_pinjam_alat', $id)->firstOrFail();

        if ($req->hasFile('kondisi_awal')) {
            $path = $req->file('kondisi_awal')->store('img_upload/kondisi/awal', 'public');
            $detail->img_kondisi_awal = basename($path);
        }

        if ($req->hasFile('kondisi_akhir')) {
            $path = $req->file('kondisi_akhir')->store('img_upload/kondisi/akhir', 'public');
            $detail->img_kondisi_akhir = basename($path);
        }

        $detail->save();

        return response()->json(['ok' => true]);
    }

    public function setSelesai($id)
    {
        DB::table('pesanan_pinjam_alat')
        ->where('id_pesanan_pinjam_alat', $id)
        ->update(['status_pengembalian' => 'Y', 'updated_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function simpanReview(Request $r, $id)
    {
        $r->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        DB::table('pesanan_pinjam_alat')
        ->where('id_pesanan_pinjam_alat', $id)
        ->update([
            'rating' => $r->rating,
            'review' => $r->review,
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }
}