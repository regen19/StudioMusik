<?php

namespace App\Http\Controllers;

use App\Models\Admin\DetailPesananJadwalStudioModel;
use App\Models\HargaSewaStudioModel;
use App\Models\PesananJadwalStudioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PesananJadwalStudioController extends Controller
{
    public function index()
    {
        return view('admin.jadwal_studio.jadwal_studio');
    }

    public function cek_tanggal_kosong(Request $request)
    {

        $tgl_pinjam = $request->input("tgl_pinjam");
        $id_ruangan = $request->input("id_ruangan");

        $cek_tanggal =
            DB::table('pesanan_jadwal_studio')
            ->join("detail_pesanan_jadwal_studio", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->where('pesanan_jadwal_studio.tgl_pinjam', $tgl_pinjam)
            ->where("pesanan_jadwal_studio.id_ruangan", $id_ruangan)
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
    }

    public function data_index()
    {
        $pesanan = DB::table('pesanan_jadwal_studio')
            ->join("users", "users.id_user", "=", "pesanan_jadwal_studio.id_user")
            ->join("detail_pesanan_jadwal_studio", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->join("data_ruangan", "data_ruangan.id_ruangan", "=", "pesanan_jadwal_studio.id_ruangan")
            ->orderBy("pesanan_jadwal_studio.id_pesanan_jadwal_studio", "DESC")
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
                "id_ruangan" => "required",
                'tgl_pinjam' => "nullable",
                'waktu_mulai' => "nullable",
                'waktu_selesai' => "nullable",
                'ket_keperluan' => "nullable",
                "img_jaminan" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
            ]);

            if ($validate->fails()) {
                return response()->json([
                    "msg" => $validate->errors()
                ], 422);
            }

            // Simpan data pesanan
            $pesanan = $request->only('id_user', 'id_ruangan', 'tgl_pinjam', 'waktu_mulai', 'waktu_selesai', 'ket_keperluan');

            // Proses upload gambar jaminan jika ada
            if ($request->hasFile('img_jaminan')) {
                $img = $request->file('img_jaminan');
                $nama_img = $request->tgl_pinjam . "- Jaminan " . str_replace(' ', '_', $request->id_user) . "." . $img->getClientOriginalExtension();
                $img->move(public_path('/storage/img_upload/pesanan_jadwal'), $nama_img);
                $pesanan['img_jaminan'] = $nama_img;
            }

            // Simpan pesanan ke dalam tabel PesananJadwalStudioModel
            $jadwalStudio = PesananJadwalStudioModel::create($pesanan);

            $detailPesanan = [
                "id_pesanan_jadwal_studio" => $jadwalStudio->id_pesanan_jadwal_studio,
                "status_persetujuan" => "P",
                "status_pengajuan" => "Y",
                "status_peminjaman" => "N",
            ];

            // Simpan detail pesanan ke dalam tabel DetailPesananJadwalStudioModel
            DetailPesananJadwalStudioModel::create($detailPesanan);

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
            ], 500);
        }
    }

    public function show(string $id_pesanan_jadwal_studio)
    {
        $data = DB::table('pesanan_jadwal_studio')
            ->join("users", "users.id_user", "=", "pesanan_jadwal_studio.id_user")
            ->join("detail_pesanan_jadwal_studio", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->where("pesanan_jadwal_studio.id_pesanan_jadwal_studio", $id_pesanan_jadwal_studio)
            ->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, string $id_pesanan_jadwal_studio)
    {
        $validate = Validator::make($request->all(), [
            "id_user" => "required",
            "id_ruangan" => "required",
            'tgl_pinjam' => "nullable",
            'no_wa' => "nullable",
            'waktu_mulai' => "nullable",
            'waktu_selesai' => "nullable",
            'ket_keperluan' => "nullable",
            // "img_jaminan" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data = PesananJadwalStudioModel::findOrFail($id_pesanan_jadwal_studio);

        if ($data) {
            // Menghapus dan mengganti gambar jika ada file gambar baru yang diunggah
            if ($request->hasFile('img_jaminan')) {
                $path = 'storage/img_upload/pesanan_jadwal' . $data->img_jaminan;
                if (File::exists(public_path($path))) {
                    File::delete(public_path($path));
                }

                $img = $request->file('img_jaminan');
                $extension = $img->getClientOriginalExtension();
                $nama_img = time() . "-" . str_replace(' ', '_', $request->id_user) . "." . $extension;
                $img->move(public_path('/storage/img_upload/pesanan_jadwal'), $nama_img);
                $data->img_jaminan = $nama_img;
            }

            $data->id_ruangan = $request->input('id_ruangan');
            $data->tgl_pinjam = $request->input('tgl_pinjam');
            $data->no_wa = $request->input('no_wa');
            $data->waktu_mulai = $request->input('waktu_mulai');
            $data->waktu_selesai = $request->input('waktu_selesai');
            $data->ket_keperluan = $request->input('ket_keperluan');

            $data->save();

            return response()->json([
                'msg' => 'Data ruangan berhasil diperbarui',
            ], 200);
        }
    }

    public function destroy(string $id_pesanan_jadwal_studio)
    {
        $data = DetailPesananJadwalStudioModel::findOrFail($id_pesanan_jadwal_studio);
        $path = '/storage/img_upload/pesanan_jadwal/' . $data->img_jaminan;

        if ($data) {
            $path = '/storage/img_upload/pesanan_jadwal/' . $data->img_jaminan;
            if (File::exists(public_path($path))) {
                File::delete(public_path($path));
            }

            $data->status_pengajuan = "X";
            $data->save();

            return response()->json(['msg' => 'Data berhasil dihapus'], 200);
        }
    }

    public function simpan_img_kondisi_ruangan(Request $request, string $id_pesanan_jadwal_studio)
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
        $data = DetailPesananJadwalStudioModel::findOrFail($id_pesanan_jadwal_studio);
        if ($data) {
            if ($request->hasFile('kondisi_awal')) {
                $pathAwal = 'storage/img_upload/kondisi/awal/' . $data->img_kondisi_awal;
                if (File::exists(public_path($pathAwal))) {
                    File::delete(public_path($pathAwal));
                }
                $imgAwal = $request->file('kondisi_awal');
                $extensionAwal = $imgAwal->getClientOriginalExtension();
                $namaImgAwal = "awal-" . str_replace(' ', '_', $id_pesanan_jadwal_studio) . "." . $extensionAwal;
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
                $namaImgAkhir = "akhir-" . str_replace(' ', '_', $id_pesanan_jadwal_studio) . "." . $extensionAkhir;
                $imgAkhir->move(public_path('/storage/img_upload/kondisi/akhir'), $namaImgAkhir);
                $data->img_kondisi_akhir = $namaImgAkhir;
            }
            $data->save();
            return response()->json(['msg' => 'Upload gambar kondisi berhasil diperbarui',], 200);
        }
        return response()->json(['message' => 'Tidak ada file yang diupload'], 400);
    }


    public function status_pesanan_jadwal_studio(Request $request, string $id_pesanan_jadwal_studio)
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

        // Mengambil ID detail pesanan jadwal studio
        $id_detail_pesanan_jadwal_studio = DB::table('pesanan_jadwal_studio')
            ->join("detail_pesanan_jadwal_studio", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->where("detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio", $id_pesanan_jadwal_studio)
            ->select("detail_pesanan_jadwal_studio.id_detail_pesanan_jadwal_studio")
            ->first();

        if (!$id_detail_pesanan_jadwal_studio) {
            return response()->json([
                "msg" => "Detail pesanan jadwal studio tidak ditemukan"
            ], 404);
        }

        // Mengupdate keterangan_admin di tabel pesanan_jadwal_studio
        PesananJadwalStudioModel::findOrFail($id_pesanan_jadwal_studio)->update([
            'ket_admin' => $request->input('ket_admin')
        ]);

        // Mengupdate status_persetujuan di tabel detail_pesanan_jadwal_studio
        DetailPesananJadwalStudioModel::findOrFail($id_detail_pesanan_jadwal_studio->id_detail_pesanan_jadwal_studio)->update([
            'status_persetujuan' => $request->input('status_persetujuan')
        ]);

        return response()->json([
            "msg" => "Status persetujuan telah diubah",
        ], 200);
    }

    // public function bayar_studio_musik(Request $request)
    // {

    //     $validate = Validator::make($request->all(), [
    //         "id_pesanan_jadwal_studio" => "required"
    //     ]);

    //     $id_pesanan_jadwal_studio = $request->input("id_pesanan_jadwal_studio");

    //     if ($validate->fails()) {
    //         return response()->json([
    //             "msg" => $validate->errors()
    //         ], 422);
    //     }

    //     $datanya = DB::table('pesanan_jadwal_studio')
    //         ->where("id_pesanan_jadwal_studio", $id_pesanan_jadwal_studio)
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
    //             'order_id' => $id_pesanan_jadwal_studio,
    //             'gross_amount' => $datanya->harga_perawatan,
    //         ),
    //         'customer_details' => array(
    //             'id_user' => $datanya->id_user,
    //         ),
    //     );

    //     $snapToken = \Midtrans\Snap::getSnapToken($params);
    // }
}
