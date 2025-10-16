<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

use App\Notifications\RequestPeminjamanBaru;
use App\Models\PesananJadwalAlatModel as OldPesananJadwalAlat;
use App\Models\Admin\DetailPesananJadwalAlatModel as OldDetailPesananJadwalAlat;

use App\Models\PesananPinjamAlat;
use App\Models\DetailPesananPinjamAlat;
use App\Models\Admin\DataAlatModel as DataAlat;
use App\Models\User;
use App\Support\Notify;
use Illuminate\Support\Facades\Http;

class PeminjamanController extends Controller
{

    public function index()
    {
        $id_user = Auth::user()->id_user;

        $cek_pesanan = DB::table('pesanan_pinjam_alat')
            ->join('detail_pesanan_pinjam_alat', 'detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat', '=', 'pesanan_pinjam_alat.id_pesanan_pinjam_alat')
            ->where('pesanan_pinjam_alat.id_user', $id_user)
            ->whereIn('pesanan_pinjam_alat.status_persetujuan', ['P','Y'])
            ->where('pesanan_pinjam_alat.status_pengembalian', 'N')
            ->first();

        $alat = DB::table('data_alat')->orderBy('nama_alat')->get();

        return view('user.jadwal_alat_usr.jadwal_alat_usr', compact('cek_pesanan','alat'));
    }

    public function create()
    {
        $alat = DB::table('data_alat')->orderBy('nama_alat')->get();
        return view('user.jadwal_alat_usr.form_pengajuan', compact('alat'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tgl_pinjam'            => 'required|date',
            'tgl_kembali'           => 'required|date|after_or_equal:tgl_pinjam',
            'waktu_mulai'           => 'required',
            'waktu_selesai'         => 'required',
            'ket_keperluan'         => 'required',
            'no_wa'                 => 'required',
            'foto_jaminan'          => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'list_alat'             => 'required|array|min:1',
            'list_alat.*.id_alat'   => 'required|integer|exists:data_alat,id_alat',
            'list_alat.*.jumlah'    => 'required|integer|min:1',
        ]);
        if ($validate->fails()) {
            return response()->json(['msg' => $validate->errors()], 422);
        }

        $nama_img = '';
        if ($request->hasFile('foto_jaminan')) {
            $img = $request->file('foto_jaminan');
            $baseName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
            $nama_img = time().'-'.str_replace(' ', '_', $baseName).'.'.$img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/data_jaminan'), $nama_img);
        }

        $created = DB::transaction(function () use ($request, $nama_img) {
            /** @var PesananPinjamAlat $header */
            $header = PesananPinjamAlat::create([
                'id_user'             => Auth::user()->id_user,
                'tgl_pinjam'          => $request->tgl_pinjam,
                'tgl_kembali'         => $request->tgl_kembali,
                'waktu_mulai'         => $request->waktu_mulai,
                'waktu_selesai'       => $request->waktu_selesai,
                'ket_keperluan'       => $request->ket_keperluan,
                'ket_admin'           => '',
                'foto_jaminan'        => $nama_img,
                'status_persetujuan'  => 'P',
                'status_pengembalian' => 'N',
            ]);

            foreach ($request->list_alat as $row) {
                DetailPesananPinjamAlat::create([
                    'id_pesanan_pinjam_alat' => $header->id_pesanan_pinjam_alat,
                    'id_alat'                => (int) $row['id_alat'],
                    'jumlah'                 => (int) $row['jumlah'],
                    'img_kondisi_awal'       => null,
                    'img_kondisi_akhir'      => null,
                    'biaya_perawatan'        => 0,
                ]);
            }

            return $header;
        });
        
        

        if ($created) {
            $targets = \App\Models\User::whereIn('user_role', ['admin', 'k3l', 'admin_ukmbs'])->get();
            if ($targets->isNotEmpty()) {
                
                Notification::send($targets, notification: new RequestPeminjamanBaru($created));
            }

            return response()->json(['msg' => 'Pengajuan tersimpan'], 200);
        }

        return response()->json(['msg' => 'Gagal menyimpan pengajuan'], 500);
    }

    public function show($id)
    {
        $id_user = Auth::user()->id_user;

        $data = DB::table('pesanan_pinjam_alat as p')
            ->join('users as u','u.id_user','=','p.id_user')
            ->where('p.id_pesanan_pinjam_alat',$id)
            ->where('p.id_user',$id_user)
            ->select('p.*','u.username')
            ->first();

        if (!$data) { abort(404); }

        $detail = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('data_alat as a','a.id_alat','=','d.id_alat')
            ->where('d.id_pesanan_pinjam_alat',$id)
            ->select('d.*','a.nama_alat')
            ->get();

        return view('user.jadwal_alat_usr.detail_pengajuan', compact('data','detail'));
    }

    public function destroy($id)
    {
        $id_user = Auth::user()->id_user;

        $data = PesananPinjamAlat::where('id_user', $id_user)
            ->where('status_persetujuan','P')
            ->where('id_pesanan_pinjam_alat', $id)
            ->firstOrFail();

        $data->delete();

        return back()->with('success','Pengajuan dibatalkan.');
    }

    public function ketersediaan(Request $r)
    {
        $tglMulai   = $r->tgl_mulai   ?? now()->toDateString();
        $tglSelesai = $r->tgl_selesai ?? now()->addDays(14)->toDateString();

        $booked = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('pesanan_pinjam_alat as p','p.id_pesanan_pinjam_alat','=','d.id_pesanan_pinjam_alat')
            ->where('p.status_persetujuan','Y')
            ->where(function($q) use ($tglMulai,$tglSelesai){
                $q->whereBetween('p.tgl_pinjam', [$tglMulai,$tglSelesai])
                  ->orWhereBetween('p.tgl_kembali', [$tglMulai,$tglSelesai])
                  ->orWhere(function($qq) use ($tglMulai,$tglSelesai){
                      $qq->where('p.tgl_pinjam','<=',$tglMulai)
                         ->where('p.tgl_kembali','>=',$tglSelesai);
                  });
            })
            ->select('d.id_alat', DB::raw('SUM(d.jumlah) as jumlah_dipesan'))
            ->groupBy('d.id_alat');

        $ketersediaan = DB::table('data_alat as a')
            ->leftJoinSub($booked,'b', function($join){
                $join->on('a.id_alat','=','b.id_alat');
            })
            ->select(
                'a.id_alat','a.nama_alat','a.jumlah_alat',
                DB::raw('COALESCE(b.jumlah_dipesan,0) as jumlah_dipesan'),
                DB::raw('(a.jumlah_alat - COALESCE(b.jumlah_dipesan,0)) as sisa')
            )
            ->orderBy('a.nama_alat')
            ->get();

        return view('user.jadwal_alat_usr.ketersediaan', compact('ketersediaan','tglMulai','tglSelesai'));
    }

    public function jadwal(Request $r)
    {
        $tanggal = $r->input('tanggal', date('Y-m-d'));
        $mulai   = $r->input('mulai',   '00:00:00');
        $selesai = $r->input('selesai', '23:59:59');
        if ($mulai >= $selesai) { [$mulai, $selesai] = [$selesai, $mulai]; }

        $dipinjam = DB::table('detail_pesanan_pinjam_alat AS d')
            ->join('pesanan_pinjam_alat AS p', 'p.id_pesanan_pinjam_alat', '=', 'd.id_pesanan_pinjam_alat')
            ->where('p.status_persetujuan', 'Y')
            ->where('p.status_pengembalian', 'N')
            ->whereDate('p.tgl_pinjam', '<=', $tanggal)
            ->whereDate('p.tgl_kembali', '>=', $tanggal)
            ->where(function($q) use ($mulai, $selesai) {
                $q->where('p.waktu_mulai', '<', $selesai)
                  ->where('p.waktu_selesai', '>', $mulai);
            })
            ->select('d.id_alat', DB::raw('SUM(d.jumlah) AS total_dipinjam'))
            ->groupBy('d.id_alat');

        $alat = DB::table('data_alat AS a')
            ->leftJoinSub($dipinjam, 'x', fn($j) => $j->on('a.id_alat','=','x.id_alat'))
            ->select(
                'a.id_alat','a.nama_alat','a.jumlah_alat',
                DB::raw('COALESCE(x.total_dipinjam,0) AS total_dipinjam'),
                DB::raw('(a.jumlah_alat - COALESCE(x.total_dipinjam,0)) AS sisa')
            )
            ->orderBy('a.nama_alat')
            ->get();

        return view('jadwal.alat', compact('alat','tanggal','mulai','selesai'));
    }
}