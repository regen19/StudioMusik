<?php

namespace App\Http\Controllers;

use App\Mail\PengajuanUserEmail;
use App\Models\PesananJasaMusikInformasiModel;
use App\Models\PesananJasaMusikModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'tgl_deadline' => 'required',
            'id_user' => 'required|numeric',
            'keterangan' => 'required',
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
        $pesanan['tenggat_produksi'] = $request->tgl_deadline;
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

        $subject = "Pengajuan Jenis Jasa Baru Hari ini";
        $view = "EmailNotif.PengajuanJasaMusikMail";
        Mail::to('candrawahyuf@gmail.com')->send(new PengajuanUserEmail($dataEmail, $subject, $view));

        return response()->json([
            'msg' => 'Pesanan Anda berhasil disimpan',
        ], 200);
    }

    public function show(string $id_pesanan_jasa_musik)
    {
        $data = DB::table('pesanan_jasa_musik')
            ->join('users', 'users.id_user', '=', 'pesanan_jasa_musik.id_user')
            ->join('master_jasa_musik', 'master_jasa_musik.id_jasa_musik', '=', 'pesanan_jasa_musik.id_jasa_musik')
            ->where('pesanan_jasa_musik.id_pesanan_jasa_musik', $id_pesanan_jasa_musik)
            ->select('users.username', 'pesanan_jasa_musik.*', 'master_jasa_musik.nama_jenis_jasa')
            ->first();

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
            'id_jenis_jasa' => 'required',
            'tgl_produksi' => 'required|date',
            'id_user' => 'required|numeric',
            'no_wa' => 'required|numeric|min:12',
            'keterangan' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
            ], 422);
        }

        $pesanan = $request->only('id_jenis_jasa', 'tgl_produksi', 'id_user', 'no_wa', 'keterangan');

        PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik)->update($pesanan);

        return response()->json([
            'msg' => 'Pesanan Anda telah diubah',
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

    public function select_paket_jasa(string $id_jasa_musik)
    {
        $paket = DB::table('paket_jasa_musik')
            ->join('master_jasa_musik', 'master_jasa_musik.id_jasa_musik', '=', 'paket_jasa_musik.id_jasa_musik')
            ->join('master_jenis_jasa', 'master_jenis_jasa.id_jenis_jasa', '=', 'master_jasa_musik.id_jenis_jasa')
            // ->select("paket_jasa_musik.id_paket_jasa_musik", "paket_jasa_musik.nama_paket", "paket_jasa_musik.biaya_paket")
            ->where('paket_jasa_musik.id_jasa_musik', $id_jasa_musik)
            ->get();

        return response()->json($paket);
    }
}
