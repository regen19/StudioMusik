<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\PengajuanUserEmail;
<<<<<<< Updated upstream
use App\Models\Admin\DetailPesananJadwalAlatModel;
use App\Models\PesananJadwalAlatModel;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\Table\TableExtension;
use Yajra\DataTables\Facades\DataTables;
=======
use App\Models\PesananPinjamAlat;
use App\Models\DetailPesananPinjamAlat;
use File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\Table\TableExtension;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RequestPeminjamanBaru;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
>>>>>>> Stashed changes

class UserAlatDipinjamController extends Controller
{
    public function index()
    {
        $id_user = Auth::user()->id_user;
<<<<<<< Updated upstream
        $cek_pesanan = DB::table("pesanan_pinjam_alat")
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
            ->where("pesanan_pinjam_alat.id_user", $id_user)
            ->where(function ($query) {
                $query->where("pesanan_pinjam_alat.status_persetujuan", "P")
                    ->orWhere("pesanan_pinjam_alat.status_persetujuan", "Y");
            })
            ->where("pesanan_pinjam_alat.status_persetujuan", "N")
            ->where("pesanan_pinjam_alat.status_pengembalian", "N")
            ->first();

        return view('user.jadwal_alat_usr.jadwal_alat_usr', compact([
            'cek_pesanan'
        ]));
    }

    public function data_index()
    {
        $id_user = Auth::user()->id_user;
        $pesanan = DB::table('pesanan_pinjam_alat')
            ->join("users", "users.id_user", "=", "pesanan_pinjam_alat.id_user")
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")

            ->where("pesanan_pinjam_alat.id_user", $id_user)
            ->orderBy("pesanan_pinjam_alat.id_pesanan_pinjam_alat", "DESC")
            ->get();

        $datatable = DataTables::of($pesanan)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id_user" => "required",
            "list_alat" => "required",
            "tgl_pinjam" => "required",
            "tgl_kembali" => "required",
            "waktu_mulai" => "required",
            "waktu_selesai" => "required",
            "no_wa" => "required",
            "ket_keperluan" => "required",
            "foto_jaminan" => "required",
            
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $pesanan = $request->all();

        $nama_img = "";
        if ($request->hasFile('foto_jaminan')) {
            $img = $request->file('foto_jaminan');
            $nama_img = time() . "-" . str_replace(' ', '_', $request->foto_jaminan) . "." . $img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/data_jaminan'), $nama_img);
        }

        $createdPesanan = PesananJadwalAlatModel::create([
            'id_user'=> $request->id_user,
            'no_wa'=> $request->no_wa,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'ket_keperluan' => $request->ket_keperluan,
            'foto_jaminan'=> $nama_img,
            'status_persetujuan' => 'P',
            'status_pengembalian' => 'N',
        ]);


        // Simpan semua data alat yang dipinjam
        foreach ($request->input('list-alat', []) as $alat) {
            DetailPesananJadwalAlatModel::create([
                'id_pesanan_pinjam_alat' => $createdPesanan->id_pesanan_pinjam_alat, // foreign key
                'id_alat' => $alat['id_alat'],
                'jumlah' => $alat['jumlah'],
            ]);
        }

        // $dataEmail = DB::table("pesanan_pinjam_alat")
        //     ->join("users", "users.id_user", "=", "pesanan_pinjam_alat.id_user")
        //     ->join("data_alat", "data_alat.id_alat", "=", "detail_.id_alat")
        //     ->select("pesanan_pinjam_alat.*", "users.username", "data_alat.nama_alat")
        //     ->where("pesanan_pinjam_alat.id_pesanan_pinjam_alat", $pesananModel->id_pesanan_pinjam_alat)
        //     ->first();

        $alatSedangDipinjam = DB::table("pesanan_pinjam_alat")
        ->select("*")
        ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
        ->where("pesanan_pinjam_alat.status_persetujuan", "Y")
        ->where("pesanan_pinjam_alat.status_pengembalian", "N")
        ->get();


        // $subject = "Pengajuan Peminjaman Studio Musik Baru Hari ini";
        // $view = "EmailNotif.PengajuanStudioMusikMail";
        // Mail::to('candrawahyuf@gmail.com')->send(new PengajuanUserEmail($dataEmail, $subject, $view));

        // return redirect('alat_dipinjam')->with('success', 'Pengajuan jadwal studio tersimpan!');

        return response()->json([
            "msg" => $request->id_user
=======

        $cek_pesanan = DB::table("pesanan_pinjam_alat")
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
            ->where("pesanan_pinjam_alat.id_user", $id_user)
            ->whereIn("pesanan_pinjam_alat.status_persetujuan", ["P","Y"])
            ->where("pesanan_pinjam_alat.status_pengembalian", "N")
            ->first();

        return view('user.jadwal_alat_usr.jadwal_alat_usr', compact('cek_pesanan'));
    }

    public function data_index()
{
    $id_user = Auth::user()->id_user;

    $rows = DB::table('pesanan_pinjam_alat as p')
        ->leftJoin('detail_pesanan_pinjam_alat as d', 'd.id_pesanan_pinjam_alat', '=', 'p.id_pesanan_pinjam_alat')
        ->leftJoin('data_alat as a', 'a.id_alat', '=', 'd.id_alat')
        ->where('p.id_user', $id_user)
        ->orderByDesc('p.id_pesanan_pinjam_alat')
        ->select(
            'p.id_pesanan_pinjam_alat',
            'p.tgl_pinjam',
            'p.tgl_kembali',
            'p.status_persetujuan',
            'p.status_pengembalian',
            'd.jumlah',
            'a.nama_alat',
            'a.foto_alat',
            'a.biaya_perawatan' // ambil biaya per unit
        )
        ->get()
        ->groupBy('id_pesanan_pinjam_alat')
        ->map(function ($group) {
            $h = $group->first();

            // detail + subtotal per baris
            $details = $group->filter(fn($r) => !is_null($r->nama_alat) || !is_null($r->jumlah))
                ->map(function ($r) {
                    $jumlah = (int) ($r->jumlah ?? 0);
                    $biaya  = (int) ($r->biaya_perawatan ?? 0);
                    return [
                        'jumlah'          => $jumlah,
                        'biaya_per_unit'  => $biaya,
                        'subtotal_biaya'  => $jumlah * $biaya,
                        'alat'            => [
                            'nama_alat' => $r->nama_alat,
                            'foto_alat' => $r->foto_alat,
                        ],
                    ];
                })
                ->values();

            $totalBiaya = $details->sum('subtotal_biaya');

            return [
                'id_pesanan_pinjam_alat' => $h->id_pesanan_pinjam_alat,
                'tgl_pinjam'             => $h->tgl_pinjam,
                'tgl_kembali'            => $h->tgl_kembali,
                'status_persetujuan'     => $h->status_persetujuan,
                'status_pengembalian'    => $h->status_pengembalian,
                'details'                => $details,
                'total_biaya_perawatan'  => $totalBiaya,
            ];
        })
        ->values();

    return DataTables::of($rows)->addIndexColumn()->toJson();
}

    public function store(Request $request)
    {
        if ($request->has('list-alat') && !$request->has('list_alat')) {
            $request->merge(['list_alat' => $request->input('list-alat')]);
        }

        $v = Validator::make($request->all(), [
            'id_user'               => 'required|integer|exists:users,id_user',
            'tgl_pinjam'            => 'required|date',
            'tgl_kembali'           => 'required|date|after_or_equal:tgl_pinjam',
            'waktu_mulai'           => 'required',
            'waktu_selesai'         => 'required',
            'ket_keperluan'         => 'required|string',
            'no_wa'                 => 'nullable|string',
            'foto_jaminan'          => 'required|file|mimes:jpg,jpeg,png|max:2048',

            'list_alat'             => 'required|array|min:1',
            'list_alat.*.id_alat'   => 'required|integer|exists:data_alat,id_alat',
            'list_alat.*.jumlah'    => 'required|integer|min:1',
        ], [
            'list_alat.required'    => 'Minimal pilih satu alat.',
        ]);

        if ($v->fails()) {
            return response()->json(['msg' => $v->errors()], 422);
        }

        $mulai   = Carbon::parse($request->tgl_pinjam)->startOfDay();
        $selesai = Carbon::parse($request->tgl_kembali ?? $request->tgl_pinjam)->endOfDay();

        $ids = collect($request->input('list_alat', []))
            ->pluck('id_alat')
            ->map(fn($v) => (int) $v)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json(['msg' => ['list_alat' => ['Minimal 1 alat.']]], 422);
        }

        $stok = DB::table('data_alat')
            ->whereIn('id_alat', $ids)
            ->pluck('jumlah_alat', 'id_alat');

        $terpesan = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('pesanan_pinjam_alat as p', 'p.id_pesanan_pinjam_alat', '=', 'd.id_pesanan_pinjam_alat')
            ->whereIn('d.id_alat', $ids)
            ->whereIn('p.status_persetujuan', ['P', 'Y'])
            ->where('p.status_pengembalian', 'N')
            ->whereDate('p.tgl_pinjam', '<=', $selesai->toDateString())
            ->whereDate('p.tgl_kembali', '>=', $mulai->toDateString())
            ->groupBy('d.id_alat')
            ->pluck(DB::raw('SUM(d.jumlah)'), 'd.id_alat');

        $violations = [];
        foreach ($request->input('list_alat') as $row) {
            $id   = (int) $row['id_alat'];
            $req  = (int) $row['jumlah'];
            $sisa = max(((int) ($stok[$id] ?? 0)) - ((int) ($terpesan[$id] ?? 0)), 0);

            if ($req > $sisa) {
                $violations[] = [
                    'id_alat' => $id,
                    'sisa'    => $sisa,
                    'diminta' => $req,
                ];
            }
        }

        if (!empty($violations)) {
            return response()->json([
                'message'    => 'Stok tidak mencukupi untuk periode yang dipilih.',
                'violations' => $violations,
            ], 422);
        }

        $nama_img = null;
        if ($request->hasFile('foto_jaminan')) {
            $nama_img = $this->safeName($request->file('foto_jaminan'), 'jaminan-'.$request->id_user);
            Storage::disk('public')->putFileAs('img_upload/data_jaminan', $request->file('foto_jaminan'), $nama_img);

            Storage::disk('public')->exists('img_upload/data_jaminan/'.$nama_img) ?: Log::error('File gagal disimpan', [
                'path' => 'img_upload/data_jaminan/'.$nama_img
            ]);
        }

        $pesanan = DB::transaction(function () use ($request, $nama_img) {
            $payload = $request->only([
                'id_user',
                'tgl_pinjam',
                'tgl_kembali',
                'waktu_mulai',
                'waktu_selesai',
                'ket_keperluan',
                'no_wa',
            ]);

            $payload['id_user'] = $payload['id_user'] ?? Auth::user()->id_user;

            $payload['ket_admin']           = '';
            $payload['foto_jaminan']        = $nama_img;
            $payload['status_persetujuan']  = 'P';
            $payload['status_pengembalian'] = 'N';

            /** @var \App\Models\PesananPinjamAlat $header */
            $header = PesananPinjamAlat::create($payload);

            foreach ($request->input('list_alat') as $row) {
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
        
        try {
            $targets = User::whereIn('user_role', ['admin', 'k3l', 'ukmbs'])->get();
            if ($targets->isNotEmpty()) {
                // notif wa
              Http::withHeaders([
                    'Authorization' => env('FONNTE_TOKEN'),
               ])->post('https://api.fonnte.com/send', [
                  'target' => $pesanan->k3l->no_wa,
                  'message' => "Request peminjaman baru ",
                  'countryCode' => '62', // Kode negara Indonesia
              ]);
                Notification::send($targets, new RequestPeminjamanBaru($pesanan));
            }
        } catch (\Throwable $e) {
        }

        return response()->json([
            'msg' => 'Pengajuan tersimpan (menunggu persetujuan)',
            'id'  => $pesanan->id_pesanan_pinjam_alat,
>>>>>>> Stashed changes
        ], 200);
    }

    public function get_snap_token(Request $request)
    {
<<<<<<< Updated upstream
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
=======
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
>>>>>>> Stashed changes
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $request->input('biaya_perawatan'),
            ),
            'customer_details' => array(
                'first_name' => $request->input('nama_user'),
                'phone' => $request->input('no_wa'),
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json($snapToken);
    }

    public function pembayaran_biaya_perawatan(Request $request)
    {
<<<<<<< Updated upstream

        $id_pesanan_pinjam_alat = $request->input('id_pesanan_pinjam_alat');

        $data['status_pembayaran'] = "Y";

        DetailPesananJadwalAlatModel::where("id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)->update($data);
=======
        $id = $request->input('id_pesanan_pinjam_alat');

        DB::table('detail_pesanan_pinjam_alat')
        ->where("id_pesanan_pinjam_alat", $id)
        ->update(['status_pembayaran' => 'Y', 'updated_at' => now()]);
>>>>>>> Stashed changes

        return response()->json(["status" => "sukses"]);
    }

<<<<<<< Updated upstream
=======
    public function show($id)
    {
        try {
            $p = DB::table('pesanan_pinjam_alat')->where('id_pesanan_pinjam_alat', (int)$id)->first();
            if (!$p) return response()->json(['message' => 'Data tidak ditemukan.'], 404);

            $user = DB::table('users')->where('id_user', $p->id_user)->first();
            $username = $user->username ?? $user->name ?? ($user->nama ?? $user->nama_user ?? null);
            $noWa     = $user->no_wa    ?? $user->phone ?? ($user->no_hp ?? $user->nomor_wa ?? null);

            $d = DB::table('detail_pesanan_pinjam_alat')
                ->where('id_pesanan_pinjam_alat', (int)$id)
                ->orderBy('id_detail_pesanan_pinjam_alat')
                ->first();

            $resp = (object)[
                'id_pesanan_pinjam_alat' => $p->id_pesanan_pinjam_alat,
                'tgl_pinjam'             => $p->tgl_pinjam,
                'tgl_kembali'            => $p->tgl_kembali,
                'waktu_mulai'            => $p->waktu_mulai,
                'waktu_selesai'          => $p->waktu_selesai,
                'ket_keperluan'          => $p->ket_keperluan,
                'ket_admin'              => $p->ket_admin,
                'status_persetujuan'     => $p->status_persetujuan,
                'status_pengembalian'    => $p->status_pengembalian,
                'foto_jaminan'           => $p->foto_jaminan,
                'username'               => $username,
                'no_wa'                  => $noWa,
                'rating'                 => $p->rating ?? null,
                'review'                 => $p->review ?? null,
            ];

            $resp->url_foto_jaminan  = $p->foto_jaminan
                ? Storage::url('img_upload/data_jaminan/'.rawurlencode($p->foto_jaminan))
                : null;

            $resp->url_kondisi_awal  = ($d && $d->img_kondisi_awal)
                ? Storage::url('img_upload/kondisi/awal/'.rawurlencode($d->img_kondisi_awal))
                : null;

            $resp->url_kondisi_akhir = ($d && $d->img_kondisi_akhir)
                ? Storage::url('img_upload/kondisi/akhir/'.rawurlencode($d->img_kondisi_akhir))
                : null;

            return response()->json($resp);
        } catch (\Throwable $e) {
            Log::error('SHOW pesanan_pinjam_alat gagal', ['id'=>$id,'msg'=>$e->getMessage(),'line'=>$e->getLine()]);
            return response()->json(['message' => 'Terjadi kesalahan di server.'], 500);
        }
    }

    public function simpanImgKondisi($id, Request $request)
    {
        $request->validate([
            'kondisi_awal'  => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'kondisi_akhir' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if (!$request->hasFile('kondisi_awal') && !$request->hasFile('kondisi_akhir')) {
            return response()->json(['message' => 'Tidak ada file yang diunggah.'], 422);
        }

        $pinjam = PesananPinjamAlat::findOrFail($id);

        if ($request->hasFile('kondisi_awal')) {
            $name = time().'-awal.'.$request->file('kondisi_awal')->getClientOriginalExtension();
            $path = $request->file('kondisi_awal')->storeAs('img_upload/kondisi/awal', $name, 'public');
            $pinjam->img_kondisi_awal = $name;
        }

        if ($request->hasFile('kondisi_akhir')) {
            $name = time().'-akhir.'.$request->file('kondisi_akhir')->getClientOriginalExtension();
            $path = $request->file('kondisi_akhir')->storeAs('img_upload/kondisi/akhir', $name, 'public');
            $pinjam->img_kondisi_akhir = $name;
        }

        $pinjam->save();

        return response()->json(['ok' => true]);
    }

    public function simpan_img_kondisi_alat(Request $r, $id)
    {
        $pesanan = PesananPinjamAlat::findOrFail($id);

        if ($r->hasFile('kondisi_awal')) {
            $field  = 'img_kondisi_awal';
            $folder = 'img_upload/kondisi/awal';
            $file   = $r->file('kondisi_awal');
        } elseif ($r->hasFile('kondisi_akhir')) {
            $field  = 'img_kondisi_akhir';
            $folder = 'img_upload/kondisi/akhir';
            $file   = $r->file('kondisi_akhir');
        } else {
            return response()->json(['message' => 'File tidak ditemukan'], 422);
        }

        $name = $this->safeName($file, ($field === 'img_kondisi_awal' ? 'awal-'.$id : 'akhir-'.$id));
        $file->storeAs('public/'.$folder, $name);

        Storage::disk('public')->exists($folder.'/'.$name) ?: Log::error('File gagal disimpan', ['path' => $folder.'/'.$name]);

        $pesanan->$field = $name;
        $pesanan->save();

        return response()->json(['ok' => true]);
    }

    public function uploadKondisi(Request $request, $id)
    {
        $request->validate([
            'detail_id'     => 'nullable|integer|exists:detail_pesanan_pinjam_alat,id',
            'kondisi_awal'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'kondisi_akhir' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if (!$request->hasFile('kondisi_awal') && !$request->hasFile('kondisi_akhir')) {
            return response()->json(['message' => 'Tidak ada file yang diunggah.'], 422);
        }

        $q = DB::table('detail_pesanan_pinjam_alat')
            ->where('id_pesanan_pinjam_alat', (int) $id);

        if ($request->filled('detail_id')) {
            $q->where('id', (int) $request->detail_id);
        }

        $exists = (clone $q)->exists();
        if (!$exists) {
            return response()->json(['message' => 'Detail pesanan tidak ditemukan.'], 404);
        }

        $updates = [];
        $urls    = [];

        if ($request->hasFile('kondisi_awal')) {
            $name = $this->safeName($request->file('kondisi_awal'), 'awal-'.$id);
            $path = 'img_upload/kondisi/awal';
            $request->file('kondisi_awal')->storeAs('public/'.$path, $name);

            Storage::disk('public')->exists($path.'/'.$name) ?: Log::error('File gagal disimpan', ['path' => $path.'/'.$name]);

            $updates['img_kondisi_awal'] = $name;
            $urls['img_kondisi_awal']    = asset('storage/'.$path.'/'.rawurlencode($name));
        }

        if ($request->hasFile('kondisi_akhir')) {
            $name = $this->safeName($request->file('kondisi_akhir'), 'akhir-'.$id);
            $path = 'img_upload/kondisi/akhir';
            $request->file('kondisi_akhir')->storeAs('public/'.$path, $name);

            Storage::disk('public')->exists($path.'/'.$name) ?: Log::error('File gagal disimpan', ['path' => $path.'/'.$name]);

            $updates['img_kondisi_akhir'] = $name;
            $urls['img_kondisi_akhir']    = asset('storage/'.$path.'/'.rawurlencode($name));
        }

        $updates['updated_at'] = now();

        $affected = $q->limit(1)->update($updates);

        if (!$affected) {
            return response()->json(['message' => 'Tidak ada baris yang diubah.'], 500);
        }

        return response()->json([
            'ok'      => true,
            'updates' => $updates,
            'urls'    => $urls,
        ]);
    }

    public function selesai($id)
    {
        DB::table('pesanan_pinjam_alat')
        ->where('id_pesanan_pinjam_alat', (int)$id)
        ->update(['status_pengembalian' => 'Y', 'updated_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function review($id, Request $r)
    {
        $r->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        DB::table('pesanan_pinjam_alat')
        ->where('id_pesanan_pinjam_alat', (int)$id)
        ->update([
            'rating' => $r->rating,
            'review' => $r->review,
            'updated_at' => now(),
        ]);

        return response()->json(['ok' => true]);
    }

    private function safeName(\Illuminate\Http\UploadedFile $f, string $prefix): string
    {
        $ext  = strtolower($f->getClientOriginalExtension());
        $base = pathinfo($f->getClientOriginalName(), PATHINFO_FILENAME);
        $base = preg_replace('/[^A-Za-z0-9_\-]+/','-', $base);
        return $prefix.'-'.now()->format('YmdHis').'-'.Str::random(6).'.'.$ext;
    }

>>>>>>> Stashed changes
    // public function pengembalian_alat(Request $request)
    // {

    //     $id_pesanan_pinjam_alat = $request->input('id_pesanan_pinjam_alat');

    //     $data['status_peminjaman'] = "Y";

    //     DetailPesananJadwalAlatModel::where("id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)->update($data);

    //     return response()->json(["status" => "sukses"]);
    // }

}
