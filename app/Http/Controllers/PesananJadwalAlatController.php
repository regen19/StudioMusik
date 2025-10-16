<?php

namespace App\Http\Controllers;

use App\Mail\PengajuanUserEmail;
<<<<<<< Updated upstream
use App\Models\Admin\DetailPesananJadwalAlatModel;
use App\Models\HargaSewaAlatModel;
use App\Models\PesananJadwalAlatModel;
=======
use App\Models\PesananJadwalAlatModel;
use App\Models\DetailPesananJadwalAlatModel;
use App\Notifications\RequestPeminjamanBaru;
use App\Models\User;
>>>>>>> Stashed changes
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
<<<<<<< Updated upstream

use function Laravel\Prompts\select;
=======
use Illuminate\Support\Facades\Http;
>>>>>>> Stashed changes

class PesananJadwalAlatController extends Controller
{
    public function index()
    {
        return view('admin.jadwal_alat.jadwal_alat');
    }

    public function cek_tanggal_kosong(Request $request)
    {
<<<<<<< Updated upstream

        $tgl_pinjam = $request->input("tgl_pinjam");
        $id_alat = $request->input("id_alat");
        $waktu_mulai = $request->input("waktu_mulai");
        $waktu_selesai = $request->input("waktu_selesai");

        // Cek apakah hari Sabtu atau Minggu
        $dayOfWeek = \Carbon\Carbon::parse($tgl_pinjam)->dayOfWeek;
        if (in_array($dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            return response()->json(["status" => "weekend"]);
        }

        $cek_tanggal =
            DB::table('pesanan_pinjam_alat')
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
            ->where('pesanan_pinjam_alat.tgl_pinjam', $tgl_pinjam)
            ->where("pesanan_pinjam_alat.id_alat", $id_alat)
            ->where(function ($query) use ($waktu_mulai, $waktu_selesai) {
                $query->whereBetween('pesanan_pinjam_alat.waktu_mulai', [$waktu_mulai, $waktu_selesai])
                    ->orWhereBetween('pesanan_pinjam_alat.waktu_selesai', [$waktu_mulai, $waktu_selesai])
                    ->orWhere(function ($query) use ($waktu_mulai, $waktu_selesai) {
                        $query->where('pesanan_pinjam_alat.waktu_mulai', '<', $waktu_selesai)
                            ->where('pesanan_pinjam_alat.waktu_selesai', '>', $waktu_mulai);
                    });
            })
            ->get();

        if ($cek_tanggal->isEmpty()) {
            return response()->json([]);
        } else {
            foreach ($cek_tanggal as $item) {
                if ($item->status_peminjaman === "Y" && $item->status_pengajuan === "Y" && $item->status_persetujuan === "Y") {
                    return response()->json(["status" => "ada"]);
                }
                if ($item->status_persetujuan !== "N" && $item->status_pengajuan === "Y") {
                    return response()->json(["status" => "ada2"]);
                }
            }
            return response()->json([]);
        }
=======
        $tgl_pinjam    = $request->input("tgl_pinjam");
        $id_alat       = $request->input("id_alat");
        $waktu_mulai   = $request->input("waktu_mulai");
        $waktu_selesai = $request->input("waktu_selesai");

        $dow = Carbon::parse($tgl_pinjam)->dayOfWeek;
        if (in_array($dow, [Carbon::SATURDAY, Carbon::SUNDAY])) {
            return response()->json(["status" => "weekend"]);
        }

        $cek = DB::table('pesanan_pinjam_alat as p')
            ->join('detail_pesanan_pinjam_alat as d', 'd.id_pesanan_pinjam_alat', '=', 'p.id_pesanan_pinjam_alat')
            ->whereDate('p.tgl_pinjam', $tgl_pinjam)
            ->where('d.id_alat', $id_alat)
            ->where(function ($q) use ($waktu_mulai, $waktu_selesai) {
                $q->whereBetween('p.waktu_mulai', [$waktu_mulai, $waktu_selesai])
                  ->orWhereBetween('p.waktu_selesai', [$waktu_mulai, $waktu_selesai])
                  ->orWhere(function ($qq) use ($waktu_mulai, $waktu_selesai) {
                      $qq->where('p.waktu_mulai', '<', $waktu_selesai)
                         ->where('p.waktu_selesai', '>', $waktu_mulai);
                  });
            })
            ->get();

        if ($cek->isEmpty()) {
            return response()->json([]);
        }

        foreach ($cek as $row) {
            if ($row->status_peminjaman === "Y" && $row->status_pengajuan === "Y" && $row->status_persetujuan === "Y") {
                return response()->json(["status" => "ada"]);
            }
            if ($row->status_persetujuan !== "N" && $row->status_pengajuan === "Y") {
                return response()->json(["status" => "ada2"]);
            }
        }
        return response()->json([]);
>>>>>>> Stashed changes
    }

    public function data_index()
    {
<<<<<<< Updated upstream
        $pesanan = DB::table('pesanan_pinjam_alat')
            ->join("users", "users.id_user", "=", "pesanan_pinjam_alat.id_user")
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
            ->join("data_alat", "data_alat.id_alat", "=", "pesanan_pinjam_alat.id_alat")
            ->orderBy("pesanan_pinjam_alat.id_pesanan_pinjam_alat", "DESC")
            ->get();

        $datatable = DataTables::of($pesanan)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi data input
            $validate = Validator::make($request->all(), [
                "id_user" => "required",
                "id_alat" => "required",
                'tgl_pinjam' => "nullable",
                'waktu_mulai' => "nullable",
                'waktu_selesai' => "nullable",
                'ket_keperluan' => "nullable",
                "foto_jaminan" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
            ]);

            if ($validate->fails()) {
                return response()->json([
                    "msg" => $validate->errors()
                ], 422);
            }

            // Simpan data pesanan
            $pesanan = $request->only('id_user', 'id_alat', 'tgl_pinjam', 'waktu_mulai', 'waktu_selesai', 'ket_keperluan');

            // Proses upload gambar jaminan jika ada
            if ($request->hasFile('foto_jaminan')) {
                $img = $request->file('foto_jaminan');
                $nama_img = $request->tgl_pinjam . "- Jaminan " . str_replace(' ', '_', $request->id_user) . "." . $img->getClientOriginalExtension();
                $img->move(public_path('/storage/img_upload/pesanan_jadwal'), $nama_img);
                $pesanan['foto_jaminan'] = $nama_img;
            }

            // Simpan pesanan ke dalam tabel PesananJadwalAlatModel
            $jadwalAlat = PesananJadwalAlatModel::create($pesanan);

            $detailPesanan = [
                "id_pesanan_pinjam_alat" => $jadwalAlat->id_pesanan_pinjam_alat,
                "status_persetujuan" => "P",
                "status_pengajuan" => "Y",
                "status_peminjaman" => "N",
            ];

            // Simpan detail pesanan ke dalam tabel DetailPesananJadwalAlatModel
            DetailPesananJadwalAlatModel::create($detailPesanan);

            $dataEmail = DB::table("pesanan_pinjam_alat")
                ->join("users", "users.id_user", "=", "pesanan_pinjam_alat.id_user")
                ->join("data_alat", "data_alat.id_alat", "=", "pesanan_pinjam_alat.id_alat")
                ->select(
                    "pesanan_pinjam_alat.*",
                    "users.username",
                    "data_alat.nama_alat"
                )
                ->where("pesanan_pinjam_alat.id_pesanan_pinjam_alat", $jadwalAlat->id_pesanan_pinjam_alat)
                ->first();

            $subject = "Pengajuan Peminjaman alat Musik Baru Hari ini";
            $view = "EmailNotif.PengajuanalatMusikMail";
            Mail::to('candrawahyuf@gmail.com')->send(new PengajuanUserEmail($dataEmail, $subject, $view));

            // Commit transaksi
            DB::commit();

            return response()->json([
                "msg" => "Pesanan Anda berhasil disimpan",
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();

            // Tangani kesalahan yang terjadi
            return response()->json([
                "msg" => "Terjadi kesalahan saat menyimpan pesanan: " . $e->getMessage(),
=======
        $q = DB::table('pesanan_pinjam_alat as p')
            ->join('users as u', 'u.id_user', '=', 'p.id_user')
            ->join('detail_pesanan_pinjam_alat as d', 'd.id_pesanan_pinjam_alat', '=', 'p.id_pesanan_pinjam_alat')
            ->join('data_alat as a', 'a.id_alat', '=', 'd.id_alat')
            ->select(
                'p.id_pesanan_pinjam_alat',
                'p.tgl_pinjam', 'p.tgl_kembali',
                'p.waktu_mulai', 'p.waktu_selesai',
                'p.ket_keperluan',
                'p.status_persetujuan', 'p.status_pengembalian',
                'u.username',
                'a.nama_alat', 'a.foto_alat',
                'd.jumlah'
            )
            ->orderByDesc('p.id_pesanan_pinjam_alat');

        return DataTables::of($q)->addIndexColumn()->toJson();
    }

    public function store(Request $r)
    {
        $v = \Validator::make($r->all(), [
            'id_user'       => 'required|integer',
            'tgl_pinjam'    => 'required|date',
            'tgl_kembali'   => 'nullable|date|after_or_equal:tgl_pinjam',
            'waktu_mulai'   => 'required',
            'waktu_selesai' => 'required',
            'ket_keperluan' => 'required|string',
            'foto_jaminan'  => 'nullable|image|mimes:png,jpg,jpeg|max:1024',

            'list_alat'     => 'nullable',
            'list-alat'     => 'nullable',
            'id_alat'       => 'nullable|integer',
            'jumlah'        => 'nullable|integer|min:1',
        ]);
        if ($v->fails()) return response()->json(['msg'=>$v->errors()], 422);

        \DB::beginTransaction();
        try {
            $p = new \App\Models\PesananJadwalAlatModel();
            $p->id_user             = (int)$r->id_user;
            $p->tgl_pinjam          = $r->tgl_pinjam;
            $p->tgl_kembali         = $r->tgl_kembali ?: $r->tgl_pinjam;
            $p->waktu_mulai         = $r->waktu_mulai;
            $p->waktu_selesai       = $r->waktu_selesai;
            $p->ket_keperluan       = $r->ket_keperluan;
            $p->status_persetujuan  = 'P';
            $p->status_pengembalian = 'N';
            $p->ket_admin           = '';

            if ($r->hasFile('foto_jaminan')) {
                $img  = $r->file('foto_jaminan');
                $nama = ($r->tgl_pinjam ?? date('Y-m-d')).'-Jaminan-'.$r->id_user.'.'.$img->getClientOriginalExtension();
                $img->move(public_path('/storage/img_upload/pesanan_jadwal'), $nama);
                $p->foto_jaminan = $nama;
            }
            $p->save();

            $raw = $r->input('list_alat', $r->input('list-alat', null));
            if ($raw === null && $r->filled('id_alat')) {
                $raw = [[
                    'id_alat' => (int)$r->id_alat,
                    'jumlah'  => (int)$r->input('jumlah', 1),
                ]];
            }
            if (is_string($raw)) {
                $dec = json_decode($raw, true);
                if (json_last_error() === JSON_ERROR_NONE) $raw = $dec;
            }
            if (is_object($raw)) $raw = [$raw];
            if (!is_array($raw))  $raw = (array)$raw;

            $items = collect($raw)->map(function ($row) {
                    if (is_string($row)) {
                        $tmp = json_decode($row, true);
                        if (json_last_error() === JSON_ERROR_NONE) $row = $tmp;
                    }
                    if (is_object($row)) $row = (array)$row;

                    $id     = (int)($row['id_alat'] ?? $row['id'] ?? 0);
                    $jumlah = (int)($row['jumlah'] ?? $row['qty'] ?? $row['quantity'] ?? 0);
                    if ($jumlah <= 0) $jumlah = 1;

                    return ['id_alat' => $id, 'jumlah' => $jumlah];
                })
                ->filter(fn($x) => $x['id_alat'] > 0 && $x['jumlah'] > 0)
                ->values();

            if ($items->isEmpty()) {
                throw new \RuntimeException('Tidak ada alat yang dikirim.');
            }

            foreach ($items as $it) {
                \App\Models\DetailPesananJadwalAlatModel::create([
                    'id_pesanan_pinjam_alat' => $p->id_pesanan_pinjam_alat,
                    'id_alat'                => $it['id_alat'],
                    'jumlah'                 => $it['jumlah'],
                    'status_persetujuan'     => 'P',
                    'status_pengajuan'       => 'Y',
                    'status_peminjaman'      => 'N',
                ]);
            }

            \DB::afterCommit(function () use ($p) {
                $roles = ['admin','ukmbs','k3l'];
                
                $targets = \App\Models\User::whereIn('user_role', $roles)->get();

                //Filter dan Kirim notif WA HANYA ke  K3L (Fonnte)
                $k3l_users = $targets->filter(fn($user) => $user->user_role === 'k3l');

                foreach ($k3l_users as $user_k3l) {
                    $no_wa = $user_k3l->no_wa;
                    
                    try {
                        Http::withHeaders([
                            'Authorization' => env('FONNTE_TOKEN'),
                        ])->post('https://api.fonnte.com/send', [
                            'target'      => $no_wa,
                            'message'     => "Anda menerima request peminjaman baru",
                            'countryCode' => '62', // Kode negara Indonesia
                        ]);
                    } catch (\Throwable $e) {
                        \Log::error("Gagal mengirim WA via Fonnte ke K3L ({$user_k3l->username}): " . $e->getMessage());
                    }
                }

                foreach ($targets as $user) {
                    $user->notify(new \App\Notifications\RequestPeminjamanBaru($p));
                }
            });

           
            \DB::commit();
            return response()->json(['msg' => 'Pesanan Anda berhasil disimpan'], 200);

        } catch (\Throwable $e) {
            \DB::rollBack();
            return response()->json([
                'message' => $e->getMessage().' @'.$e->getFile().':'.$e->getLine()
>>>>>>> Stashed changes
            ], 500);
        }
    }

<<<<<<< Updated upstream
    public function show(string $id_pesanan_pinjam_alat)
    {
        $data = DB::table('pesanan_pinjam_alat')
            ->join("users", "users.id_user", "=", "pesanan_pinjam_alat.id_user")
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
            ->where("pesanan_pinjam_alat.id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)
            ->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, string $id_pesanan_pinjam_alat)
    {
        $validate = Validator::make($request->all(), [
            "id_user" => "required",
            "id_alat" => "required",
            'tgl_pinjam' => "nullable",
            'no_wa' => "nullable",
            'waktu_mulai' => "nullable",
            'waktu_selesai' => "nullable",
            'ket_keperluan' => "nullable",
            // "foto_jaminan" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data = PesananJadwalAlatModel::findOrFail($id_pesanan_pinjam_alat);

        if ($data) {
            // Menghapus dan mengganti gambar jika ada file gambar baru yang diunggah
            if ($request->hasFile('foto_jaminan')) {
                $path = 'storage/img_upload/pesanan_jadwal' . $data->foto_jaminan;
                if (File::exists(public_path($path))) {
                    File::delete(public_path($path));
                }

                $img = $request->file('foto_jaminan');
                $extension = $img->getClientOriginalExtension();
                $nama_img = time() . "-" . str_replace(' ', '_', $request->id_user) . "." . $extension;
                $img->move(public_path('/storage/img_upload/pesanan_jadwal'), $nama_img);
                $data->foto_jaminan = $nama_img;
            }

            $data->id_alat = $request->input('id_alat');
            $data->tgl_pinjam = $request->input('tgl_pinjam');
            $data->no_wa = $request->input('no_wa');
            $data->waktu_mulai = $request->input('waktu_mulai');
            $data->waktu_selesai = $request->input('waktu_selesai');
            $data->ket_keperluan = $request->input('ket_keperluan');

            $data->save();

            return response()->json([
                'msg' => 'Data alat berhasil diperbarui',
            ], 200);
        }
    }

    public function destroy(string $id_pesanan_pinjam_alat)
    {
        $data = DetailPesananJadwalAlatModel::findOrFail($id_pesanan_pinjam_alat);
        $path = '/storage/img_upload/pesanan_jadwal/' . $data->foto_jaminan;

        if ($data) {
            $path = '/storage/img_upload/pesanan_jadwal/' . $data->foto_jaminan;
            if (File::exists(public_path($path))) {
                File::delete(public_path($path));
            }

            $data->status_pengajuan = "X";
            $data->save();

            return response()->json(['msg' => 'Data berhasil dihapus'], 200);
        }
    }

    public function simpan_img_kondisi_alat(Request $request, string $id_pesanan_pinjam_alat)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kondisi_awal' => 'nullable|image|mimes:jpeg,png,jpg|max:1000',
                'kondisi_akhir' => 'nullable|image|mimes:jpeg,png,jpg|max:1000',
            ]
        );


        if ($validator->fails()) {
            return response()->json(['message' => 'Validasi gagal.', 'errors' => $validator->errors(),], 422);
        }
        $data = DetailPesananJadwalAlatModel::findOrFail($id_pesanan_pinjam_alat);
        if ($data) {
            if ($request->hasFile('kondisi_awal')) {
                $pathAwal = 'storage/img_upload/kondisi/awal/' . $data->img_kondisi_awal;
                if (File::exists(public_path($pathAwal))) {
                    File::delete(public_path($pathAwal));
                }
                $imgAwal = $request->file('kondisi_awal');
                $extensionAwal = $imgAwal->getClientOriginalExtension();
                $namaImgAwal = "awal-" . str_replace(' ', '_', $id_pesanan_pinjam_alat) . "." . $extensionAwal;
                $imgAwal->move(public_path('/storage/img_upload/kondisi/awal'), $namaImgAwal);
                $data->img_kondisi_awal = $namaImgAwal;
            }
            if ($request->hasFile('kondisi_akhir')) {
                $pathAkhir = 'storage/img_upload/kondisi/akhir/' . $data->img_kondisi_akhir;
                if (File::exists(public_path($pathAkhir))) {
                    File::delete(public_path($pathAkhir));
                }
                $imgAkhir = $request->file('kondisi_akhir');
                $extensionAkhir = $imgAkhir->getClientOriginalExtension();
                $namaImgAkhir = "akhir-" . str_replace(' ', '_', $id_pesanan_pinjam_alat) . "." . $extensionAkhir;
                $imgAkhir->move(public_path('/storage/img_upload/kondisi/akhir'), $namaImgAkhir);
                $data->img_kondisi_akhir = $namaImgAkhir;
            }
            $data->save();
            return response()->json(['msg' => 'Upload gambar kondisi berhasil diperbarui',], 200);
        }
        return response()->json(['message' => 'Tidak ada file yang diupload'], 400);
    }


    public function status_pesanan_pinjam_alat(Request $request, string $id_pesanan_pinjam_alat)
    {
        $validate = Validator::make($request->all(), [
            "status_persetujuan" => "required",
            "ket_admin" => "nullable"
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        // Mengambil ID detail pesanan jadwal alat
        $id_detail_pesanan_pinjam_alat = DB::table('pesanan_pinjam_alat')
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
            ->where("detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)
            ->select("detail_pesanan_pinjam_alat.id_detail_pesanan_pinjam_alat")
            ->first();

        $id_user = DB::table("pesanan_pinjam_alat")
            ->where("id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)
            ->pluck("id_user")
            ->first();

        $email = DB::table("users")
            ->where("id_user", $id_user)
            ->pluck("email")
            ->first();

        $dataEmail = DB::table("pesanan_pinjam_alat")
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
            ->join("users", "users.id_user", "=", "pesanan_pinjam_alat.id_user")
            ->join("data_alat", "data_alat.id_alat", "=", "pesanan_pinjam_alat.id_alat")
            ->select(
                "pesanan_pinjam_alat.*",
                "users.username",
                "detail_pesanan_pinjam_alat.status_persetujuan",
                "data_alat.nama_alat"
            )
            ->where("pesanan_pinjam_alat.id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)
            ->first();

        $subject = "Persetujuan Peminjaman Alat Musik";
        $view = "EmailNotif.PersetujuanalatMusik";
        Mail::to($email)->send(new PengajuanUserEmail($dataEmail, $subject, $view));

        if (!$id_detail_pesanan_pinjam_alat) {
            return response()->json([
                "msg" => "Detail pesanan jadwal alat tidak ditemukan"
            ], 404);
        }

        // Mengupdate keterangan_admin di tabel pesanan_pinjam_alat
        PesananJadwalAlatModel::findOrFail($id_pesanan_pinjam_alat)->update([
            'ket_admin' => $request->input('ket_admin')
        ]);

        // Mengupdate status_persetujuan di tabel detail_pesanan_pinjam_alat
        DetailPesananJadwalAlatModel::findOrFail($id_detail_pesanan_pinjam_alat->id_detail_pesanan_pinjam_alat)->update([
            'status_persetujuan' => $request->input('status_persetujuan')
        ]);

        return response()->json([
            "msg" => "Status persetujuan telah diubah",
        ], 200);
=======
    public function show(string $id)
    {
        $header = DB::table('pesanan_pinjam_alat as p')
            ->join('users as u', 'u.id_user', '=', 'p.id_user')
            ->select('p.*','u.username')
            ->where('p.id_pesanan_pinjam_alat', $id)
            ->first();

        if (!$header) return response()->json(['msg'=>'Data tidak ditemukan...'],404);

        $details = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('data_alat as a', 'a.id_alat','=','d.id_alat')
            ->select('d.id_detail_pesanan_pinjam_alat','d.id_alat','a.nama_alat','d.jumlah')
            ->where('d.id_pesanan_pinjam_alat',$id)
            ->get();

        return response()->json(['header'=>$header,'details'=>$details]);
    }

    public function update(Request $r, string $id)
    {
        $v = Validator::make($r->all(), [
            'tgl_pinjam'    => 'nullable|date',
            'tgl_kembali'   => 'nullable|date|after_or_equal:tgl_pinjam',
            'no_wa'         => 'nullable',
            'waktu_mulai'   => 'nullable',
            'waktu_selesai' => 'nullable',
            'ket_keperluan' => 'nullable',
            'foto_jaminan'  => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
        ]);
        if ($v->fails()) return response()->json(['msg'=>$v->errors()],422);

        $p = PesananJadwalAlatModel::findOrFail($id);

        if ($r->hasFile('foto_jaminan')) {
            $old = public_path('storage/img_upload/pesanan_jadwal/'.$p->foto_jaminan);
            if ($p->foto_jaminan && file_exists($old)) @unlink($old);

            $img  = $r->file('foto_jaminan');
            $nama = time().'-'.$p->id_user.'.'.$img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/pesanan_jadwal'), $nama);
            $p->foto_jaminan = $nama;
        }

        foreach (['tgl_pinjam','tgl_kembali','no_wa','waktu_mulai','waktu_selesai','ket_keperluan'] as $f) {
            if ($r->filled($f)) $p->{$f} = $r->input($f);
        }
        $p->save();

        return response()->json(['msg'=>'Data alat berhasil diperbarui'],200);
    }

    public function destroy(string $id)
    {
        $detail = DetailPesananJadwalAlatModel::where('id_pesanan_pinjam_alat',$id)->firstOrFail();
        if ($detail->foto_jaminan ?? null) {
            $path = public_path('/storage/img_upload/pesanan_jadwal/'.$detail->foto_jaminan);
            if (file_exists($path)) @unlink($path);
        }
        $detail->status_pengajuan = 'X';
        $detail->save();

        return response()->json(['msg'=>'Data berhasil dihapus'],200);
    }

    public function simpan_img_kondisi_alat(Request $r, string $id_pesanan)
    {
        $v = Validator::make($r->all(), [
            'kondisi_awal'  => 'nullable|image|mimes:jpeg,png,jpg|max:1000',
            'kondisi_akhir' => 'nullable|image|mimes:jpeg,png,jpg|max:1000',
        ]);
        if ($v->fails()) return response()->json(['message'=>'Validasi gagal.','errors'=>$v->errors()],422);

        $d = DetailPesananJadwalAlatModel::where('id_pesanan_pinjam_alat',$id_pesanan)->firstOrFail();

        if ($r->hasFile('kondisi_awal')) {
            $old = public_path('storage/img_upload/kondisi/awal/'.$d->img_kondisi_awal);
            if ($d->img_kondisi_awal && file_exists($old)) @unlink($old);

            $img  = $r->file('kondisi_awal');
            $nama = 'awal-'.$id_pesanan.'.'.$img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/kondisi/awal'), $nama);
            $d->img_kondisi_awal = $nama;
        }

        if ($r->hasFile('kondisi_akhir')) {
            $old = public_path('storage/img_upload/kondisi/akhir/'.$d->img_kondisi_akhir);
            if ($d->img_kondisi_akhir && file_exists($old)) @unlink($old);

            $img  = $r->file('kondisi_akhir');
            $nama = 'akhir-'.$id_pesanan.'.'.$img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/kondisi/akhir'), $nama);
            $d->img_kondisi_akhir = $nama;
        }

        $d->save();
        return response()->json(['msg'=>'Upload gambar kondisi berhasil diperbarui'],200);
    }

    public function status_pesanan_pinjam_alat(Request $r, string $id)
    {
        $v = Validator::make($r->all(), [
            'status_persetujuan' => 'required|in:P,Y,N',
            'ket_admin'          => 'nullable|string',
        ]);
        if ($v->fails()) return response()->json(['msg'=>$v->errors()],422);

        DB::transaction(function () use ($r,$id) {
            $p = PesananJadwalAlatModel::lockForUpdate()->findOrFail($id);
            $p->ket_admin          = $r->input('ket_admin','');
            $p->status_persetujuan = $r->input('status_persetujuan');
            if ($p->status_persetujuan === 'Y' && $p->status_pengembalian === null) {
                $p->status_pengembalian = 'N';
            }
            $p->save();

            DetailPesananJadwalAlatModel::where('id_pesanan_pinjam_alat',$id)
                ->update(['status_persetujuan'=>$p->status_persetujuan]);
        });

        try {
            $id_user = DB::table('pesanan_pinjam_alat')->where('id_pesanan_pinjam_alat',$id)->value('id_user');
            $email   = DB::table('users')->where('id_user',$id_user)->value('email');
            $payload = DB::table('pesanan_pinjam_alat')->where('id_pesanan_pinjam_alat',$id)->first();

            Mail::to($email)->send(new PengajuanUserEmail(
                $payload,
                'Persetujuan Peminjaman Alat Musik',
                'EmailNotif.PersetujuanalatMusik'
            ));
        } catch (\Throwable $e) {}

        return response()->json(['msg'=>'Status persetujuan telah diubah'],200);
    }

    public function cancel($id)
    {
        DB::transaction(function () use ($id) {
            $header = PesananJadwalAlatModel::findOrFail($id);

            if ($header->foto_jaminan) {
                @unlink(public_path('storage/img_upload/pesanan_jadwal/'.$header->foto_jaminan));
            }

            $details = DetailPesananJadwalAlatModel::where('id_pesanan_pinjam_alat', $id)->get();
            foreach ($details as $d) {
                if ($d->img_kondisi_awal)  @unlink(public_path('storage/img_upload/kondisi/awal/'.$d->img_kondisi_awal));
                if ($d->img_kondisi_akhir) @unlink(public_path('storage/img_upload/kondisi/akhir/'.$d->img_kondisi_akhir));
            }
            DetailPesananJadwalAlatModel::where('id_pesanan_pinjam_alat', $id)->delete();

            $header->delete();
        });

        return response()->json(['msg' => 'Pengajuan dihapus.']);
    }

    public function detail_json(string $id)
    {
        $h = DB::table('pesanan_pinjam_alat as p')
            ->join('users as u','u.id_user','=','p.id_user')
            ->select(
                'p.*','u.username',
                DB::raw("DATE_FORMAT(p.tgl_pinjam, '%Y-%m-%d') as tgl_str")
            )
            ->where('p.id_pesanan_pinjam_alat',$id)->first();

        if (!$h) return response()->json(['message'=>'Data tidak ditemukan'],404);

        $d = DB::table('detail_pesanan_pinjam_alat as d')
            ->join('data_alat as a','a.id_alat','=','d.id_alat')
            ->select('d.*','a.nama_alat')
            ->where('d.id_pesanan_pinjam_alat',$id)
            ->first();

        $urlJaminan = $h->foto_jaminan
            ? asset('storage/img_upload/pesanan_jadwal/'.$h->foto_jaminan) : null;

        $urlAwal  = $d && $d->img_kondisi_awal
            ? asset('storage/img_upload/kondisi/awal/'.$d->img_kondisi_awal) : null;

        $urlAkhir = $d && $d->img_kondisi_akhir
            ? asset('storage/img_upload/kondisi/akhir/'.$d->img_kondisi_akhir) : null;

        return response()->json([
            'id'                 => $h->id_pesanan_pinjam_alat,
            'username'           => $h->username,
            'tgl_pinjam'         => $h->tgl_str,
            'waktu_mulai'        => $h->waktu_mulai,
            'waktu_selesai'      => $h->waktu_selesai,
            'ket_keperluan'      => $h->ket_keperluan,
            'ket_admin'          => $h->ket_admin,
            'status_persetujuan' => $h->status_persetujuan,
            'status_pengembalian'=> $h->status_pengembalian,
            'rating'             => $h->rating,
            'review'             => $h->review,
            'url_foto_jaminan'   => $urlJaminan,
            'url_kondisi_awal'   => $urlAwal,
            'url_kondisi_akhir'  => $urlAkhir,
        ]);
    }

    public function simpan_review(Request $r, string $id)
    {
        $r->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $p = PesananJadwalAlatModel::findOrFail($id);

        if ($p->status_pengembalian !== 'Y') {
            return response()->json(['message'=>'Barang belum dikembalikan.'], 422);
        }

        $p->rating = (int)$r->rating;
        $p->review = $r->input('review','');
        $p->save();

        return response()->json(['msg'=>'Review tersimpan.']);
    }

    public function kondisi_index()
    {
        return view('ukmbs.peminjaman.kondisi_index');
>>>>>>> Stashed changes
    }

    // public function bayar_alat_musik(Request $request)
    // {

    //     $validate = Validator::make($request->all(), [
    //         "id_pesanan_pinjam_alat" => "required"
    //     ]);

    //     $id_pesanan_pinjam_alat = $request->input("id_pesanan_pinjam_alat");

    //     if ($validate->fails()) {
    //         return response()->json([
    //             "msg" => $validate->errors()
    //         ], 422);
    //     }

    //     $datanya = DB::table('pesanan_pinjam_alat')
    //         ->where("id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)
    //         ->first();

    //     // Set your Merchant Server Key
    //     \Midtrans\Config::$serverKey = config('midtrans.server_key');
    //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    //     \Midtrans\Config::$isProduction = false;
    //     // Set sanitization on (default)
    //     \Midtrans\Config::$isSanitized = true;
    //     // Set 3DS transaction for credit card to true
    //     \Midtrans\Config::$is3ds = true;

    //     $params = array(
    //         'transaction_details' => array(
    //             'order_id' => $id_pesanan_pinjam_alat,
    //             'gross_amount' => $datanya->harga_perawatan,
    //         ),
    //         'customer_details' => array(
    //             'id_user' => $datanya->id_user,
    //         ),
    //     );

    //     $snapToken = \Midtrans\Snap::getSnapToken($params);
    // }
}
