<?php

namespace App\Http\Controllers;

use App\Mail\PengajuanUserEmail;
use App\Models\PesananJasaMusikInformasiModel;
use App\Models\PesananJasaMusikModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PesananJasaMusikController extends Controller
{
    public function index()
    {
        return view('admin.jasa_musik.pesanan_jasa_musik');
    }

    public function data_index()
    {
        $id_user = Auth::user()->id_user;
        $pesanan = DB::table('pesanan_jasa_musik')
            ->join('users', 'users.id_user', '=', 'pesanan_jasa_musik.id_user')
            ->join('master_jasa_musik', 'master_jasa_musik.id_jasa_musik', '=', 'pesanan_jasa_musik.id_jasa_musik')
            ->orderBy('pesanan_jasa_musik.id_pesanan_jasa_musik', 'DESC')
            ->get();

        $datatable = DataTables::of($pesanan)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function download_file_pesanan_jasa_musik($filename)
    {
        $filePath = public_path('storage/pesanan/jasa_musik_file/' . $filename);

        if (file_exists($filePath)) {
            return response()->download($filePath, $filename);
        }

        return abort(404, 'File not found');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id_jasa_musik' => 'required',
            'tenggat_produksi' => 'required',
            'id_user' => 'required|numeric',
            'keterangan' => 'required',
            'informasi' => 'required|array',
            'informasi.*.nama_field' => 'required',
            'informasi.*.value_field' => 'required_without:informasi.*.file',
            'informasi.*.file' => 'required_without:informasi.*.value_field',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
            ], 422);
        }

        $pesanan = $request->only('id_jasa_musik', 'id_user', 'keterangan');
        $pesanan['status_persetujuan'] = 'P';
        $pesanan['status_pengajuan'] = 'Y';
        $pesanan['status_produksi'] = 'N';
        $pesanan['tenggat_produksi'] = $request->tenggat_produksi;
        $pesanan['tgl_produksi'] = null;

        $pesananModel = PesananJasaMusikModel::create($pesanan);
        foreach ($request->informasi as $index => $data) {
            if ($data['tipe_field'] == 'file') {
                $file = $request->file("informasi.$index.file");
                $nama_file = time() . '-' . str_replace(' ', '_', $file->getClientOriginalName());
                $file->move(public_path('/storage/pesanan/jasa_musik_file'), $nama_file);
                $value_field = $nama_file;
            } else {
                $value_field = $data['value_field'];
            }
            PesananJasaMusikInformasiModel::create([
                'pesanan_jasa_musik_id' => $pesananModel->id_pesanan_jasa_musik,
                'nama_field' => $data['nama_field'],
                'tipe_field' => $data['tipe_field'],
                'value_field' => $value_field,
            ]);
        }

        $dataEmail = DB::table("pesanan_jasa_musik")
            ->join("users", "users.id_user", "=", "pesanan_jasa_musik.id_jasa_musik")
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "pesanan_jasa_musik.id_jasa_musik")
            ->select("pesanan_jasa_musik.*", "users.username", "master_jasa_musik.nama_jenis_jasa")
            ->where("pesanan_jasa_musik.id_pesanan_jasa_musik", $pesananModel->id_pesanan_jasa_musik)
            ->first();

        $subject = "Pengajuan Jasa Musik Baru Hari ini";
        $view = "EmailNotif.PengajuanJasaMusikMail";
        Mail::to('musikitera@gmail.com')->send(new PengajuanUserEmail($dataEmail, $subject, $view));
        Mail::to('regenvoid@gmail.com')->send(new PengajuanUserEmail($dataEmail, $subject, $view));

        return response()->json([
            'msg' => 'Pesanan Anda berhasil disimpan',
        ], 200);
    }

    public function show(string $id_pesanan_jasa_musik)
    {
        $data = PesananJasaMusikModel::with(['jasaInformasi', 'masterJasaMusik', 'users'])->find($id_pesanan_jasa_musik);

        if (empty($data)) {
            return response()->json([
                'msg' => 'Data tidak ditemukan...',
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function informasi_pesanan_jasa_musik(string $id)
    {
        $jasa_musik = PesananJasaMusikInformasiModel::where('pesanan_jasa_musik_id', $id)->get();

        return $jasa_musik;
    }

    public function update(Request $request, string $id_pesanan_jasa_musik)
    {
        $validate = Validator::make($request->all(), [
            'id_jasa_musik' => 'required',
            'tenggat_produksi' => 'required',
            'id_user' => 'required|numeric',
            'keterangan' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data1 = $request->only('id_jasa_musik', 'tenggat_produksi', 'keterangan');

        $jasa = PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik);
        $id_jasa_musik_lama = $jasa->id_jasa_musik;

        $infoLama = PesananJasaMusikInformasiModel::where("pesanan_jasa_musik_id", $id_pesanan_jasa_musik)->get();

        if ($id_jasa_musik_lama != $request->id_jasa_musik) {
            foreach ($infoLama as $index => $data) {
                if ($data['tipe_field'] == 'file') {

                    $path = '/storage/pesanan/jasa_musik_file/' . $data['value_field'];
                    if (File::exists(public_path($path))) {
                        File::delete(public_path($path));
                    }
                }
            }
        }

        $jasa->update($data1);
        PesananJasaMusikInformasiModel::where("pesanan_jasa_musik_id", $id_pesanan_jasa_musik)->delete();

        foreach ($request->informasi as $index => $dataBaru) {
            // Cek apakah informasi sudah ada di database
            $infoLamaItem = $infoLama->where('nama_field', $dataBaru['nama_field'])->first();

            if ($dataBaru['tipe_field'] == 'file') {
                // Cek apakah ada file baru di request
                if (isset($dataBaru['file']) && $dataBaru['file'] instanceof \Illuminate\Http\UploadedFile) {
                    // Hapus file lama jika ada di database

                    $patH = public_path('/storage/pesanan/jasa_musik_file/' . $infoLamaItem->value_field);
                    if ($infoLamaItem && File::exists($patH)) {
                        File::delete($patH);
                    }

                    // Simpan file baru
                    $file = $dataBaru['file'];
                    $nama_file = time() . '-' . str_replace(' ', '_', $file->getClientOriginalName());
                    $file->move(public_path('/storage/pesanan/jasa_musik_file'), $nama_file);
                    $value_field = $nama_file;
                } else {
                    // Jika tidak ada file baru, gunakan file lama
                    $value_field = $infoLamaItem ? $infoLamaItem->value_field : null;
                }
            } else {
                // Jika bukan file, langsung gunakan value dari request
                $value_field = $dataBaru['value_field'];
            }

            // Update atau buat baru
            PesananJasaMusikInformasiModel::updateOrCreate(
                [
                    'pesanan_jasa_musik_id' => $id_pesanan_jasa_musik,
                    'nama_field' => $dataBaru['nama_field'],
                ],
                [
                    'tipe_field' => $dataBaru['tipe_field'],
                    'value_field' => $value_field,
                ]
            );
        }

        return response()->json([
            "msg" => "Pesanan Jasa Musik Telah Diubah",
            "status" => 200,
        ], 200);
    }

    public function destroy(string $id_pesanan_jasa_musik)
    {
        $data = PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik);
        $data->status_pengajuan = 'X';
        $data->save();

        return response()->json(['msg' => 'Pesanan berhasil dihapus'], 200);
    }

    public function status_pesanan_jasa_musik(Request $request, string $id_pesanan_jasa_musik)
    {
        $validate = Validator::make($request->all(), [
            'status_persetujuan' => 'required',
            'keterangan_admin' => 'nullable',
            'status_produksi' => 'required',
            'tenggat_produksi' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
            ], 422);
        }

        $pesanan = $request->only('status_persetujuan', 'keterangan_admin', 'status_produksi', 'tenggat_produksi', 'tgl_produksi');

        PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik)->update($pesanan);

        $id_user = DB::table("pesanan_jasa_musik")
            ->where("id_pesanan_jasa_musik", $id_pesanan_jasa_musik)
            ->pluck("id_user")
            ->first();

        $email = DB::table("users")
            ->where("id_user", $id_user)
            ->pluck("email")
            ->first();

        $dataEmail = DB::table("pesanan_jasa_musik")
            ->join("users", "users.id_user", "=", "pesanan_jasa_musik.id_jasa_musik")
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "pesanan_jasa_musik.id_jasa_musik")
            ->select("pesanan_jasa_musik.*", "users.username", "master_jasa_musik.nama_jenis_jasa")
            ->where("pesanan_jasa_musik.id_pesanan_jasa_musik", $id_pesanan_jasa_musik)
            ->first();

        $subject = "Persetujuan Peminjaman Studi Musik";
        $view = "EmailNotif.PersetujuanJasaMusik";
        Mail::to($email)->send(new PengajuanUserEmail($dataEmail, $subject, $view));

        return response()->json([
            'msg' => 'Status persetujuan telah diubah',
        ], 200);
    }

    public function selesaikan_produksi_pesanan_jasa_musik($id_pesanan_jasa_musik)
    {

        PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik)->update([
            "status_produksi" => "Y"
        ]);

        $id_user = DB::table("pesanan_jasa_musik")
            ->where("id_pesanan_jasa_musik", $id_pesanan_jasa_musik)
            ->pluck("id_user")
            ->first();

        $email = DB::table("users")
            ->where("id_user", $id_user)
            ->pluck("email")
            ->first();

        $dataEmail = DB::table("pesanan_jasa_musik")
            ->join("users", "users.id_user", "=", "pesanan_jasa_musik.id_jasa_musik")
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "pesanan_jasa_musik.id_jasa_musik")
            ->select("pesanan_jasa_musik.*", "users.username", "master_jasa_musik.nama_jenis_jasa")
            ->where("pesanan_jasa_musik.id_pesanan_jasa_musik", $id_pesanan_jasa_musik)
            ->first();

        $subject = "Persetujuan Peminjaman Studi Musik";
        $view = "EmailNotif.PersetujuanJasaMusik";
        Mail::to($email)->send(new PengajuanUserEmail($dataEmail, $subject, $view));

        return response()->json([
            'msg' => 'Produksi telah selesai',
        ], 200);
    }
}
