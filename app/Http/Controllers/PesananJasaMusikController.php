<?php

namespace App\Http\Controllers;

use App\Models\PesananJasaMusikModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Psy\Readline\Hoa\Console;
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
        $pesanan = DB::table("pesanan_jasa_musik")
            ->join("users", "users.id_user", "=", "pesanan_jasa_musik.id_user")
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "pesanan_jasa_musik.id_jasa_musik")
            ->orderBy('pesanan_jasa_musik.id_pesanan_jasa_musik', "DESC")
            ->get();

        $datatable = DataTables::of($pesanan)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id_jasa_musik" => "required",
            "tgl_produksi" => "required",
            "id_user" => "required|numeric",
            "no_wa" => "required|numeric",
            "keterangan" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $pesanan = $request->only('id_jasa_musik', 'tgl_produksi', 'id_user', 'no_wa', 'keterangan');
        $pesanan['status_persetujuan'] = "P";
        $pesanan['status_pengajuan'] = "Y";
        $pesanan['status_produksi'] = "N";

        $tanggalProduksi = $request->tgl_produksi;
        $tanggalProduksiCarbon = Carbon::parse($tanggalProduksi);
        $tanggalTenggat = $tanggalProduksiCarbon->addWeek();
        $pesanan['tenggat_produksi'] = $tanggalTenggat;

        PesananJasaMusikModel::create($pesanan);

        return response()->json([
            "msg" => "Pesanan Anda berhasil disimpan",
        ], 200);
    }

    public function show(string $id_pesanan_jasa_musik)
    {
        $data = DB::table('pesanan_jasa_musik')
            ->join("users", "users.id_user", "=", "pesanan_jasa_musik.id_user")
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "pesanan_jasa_musik.id_jasa_musik")
            ->where("pesanan_jasa_musik.id_pesanan_jasa_musik", $id_pesanan_jasa_musik)
            ->select('users.username', 'pesanan_jasa_musik.*', "master_jasa_musik.nama_jenis_jasa")
            ->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, string $id_pesanan_jasa_musik)
    {
        $validate = Validator::make($request->all(), [
            "id_jenis_jasa" => "required",
            "tgl_produksi" => "required|date",
            "id_user" => "required|numeric",
            "no_wa" => "required|numeric|min:12",
            "keterangan" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $pesanan = $request->only('id_jenis_jasa', 'tgl_produksi', 'id_user', 'no_wa', 'keterangan');

        PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik)->update($pesanan);

        return response()->json([
            "msg" => "Pesanan Anda telah diubah",
        ], 200);
    }

    public function destroy(string $id_pesanan_jasa_musik)
    {
        $data = PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik);
        $data->status_pengajuan = "X";
        $data->save();

        return response()->json(['msg' => 'Pesanan berhasil dihapus'], 200);
    }

    public function status_pesanan_jasa_musik(Request $request, string $id_pesanan_jasa_musik)
    {
        $validate = Validator::make($request->all(), [
            "status_persetujuan" => "required",
            "keterangan_admin" => "nullable",
            "status_produksi" => "required",
            "tenggat_produksi" => "required"
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $pesanan = $request->only('status_persetujuan', 'keterangan_admin', 'status_produksi', "tenggat_produksi");

        PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik)->update($pesanan);

        return response()->json([
            "msg" => "Status persetujuan telah diubah",
        ], 200);
    }

    public function select_paket_jasa(string $id_jasa_musik)
    {
        $paket = DB::table('paket_jasa_musik')
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "paket_jasa_musik.id_jasa_musik")
            ->join("master_jenis_jasa", "master_jenis_jasa.id_jenis_jasa", "=", "master_jasa_musik.id_jenis_jasa")
            // ->select("paket_jasa_musik.id_paket_jasa_musik", "paket_jasa_musik.nama_paket", "paket_jasa_musik.biaya_paket")
            ->where('paket_jasa_musik.id_jasa_musik', $id_jasa_musik)
            ->get();

        return response()->json($paket);
    }
}
