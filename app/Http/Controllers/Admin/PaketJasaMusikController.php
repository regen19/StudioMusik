<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaketJasaMusikModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PaketJasaMusikController extends Controller
{
    public function index(Request $request)
    {
        $segments = $request->segments();

        $musik = DB::table('master_jenis_jasa')
            ->join("master_jasa_musik", "master_jasa_musik.id_jenis_jasa", "=", "master_jenis_jasa.id_jenis_jasa")
            ->where("master_jasa_musik.id_jasa_musik", $segments[1])
            ->first("nama_jenis_jasa");

        return view('admin.jasa_musik.paket_harga_jasa_musik', compact([
            'musik',
        ]));
    }

    public function data_index(Request $request)
    {
        $segments = $request->input('id_jasa_musik');
        $paket = DB::table('paket_jasa_musik')
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "paket_jasa_musik.id_jasa_musik")
            ->join("master_jenis_jasa", "master_jenis_jasa.id_jenis_jasa", "=", "master_jasa_musik.id_jenis_jasa")
            ->select("paket_jasa_musik.*", "master_jenis_jasa.nama_jenis_jasa")
            ->where('paket_jasa_musik.id_jasa_musik', $segments)
            ->get();

        $datatable = DataTables::of($paket)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id_jasa_musik" => "required",
            "nama_paket" => "required",
            "deskripsi" => "required",
            "rincian_paket" => "required",
            "biaya_paket" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $laporan = $request->only('id_jasa_musik', 'nama_paket', 'deskripsi', 'rincian_paket', 'biaya_paket');

        PaketJasaMusikModel::create($laporan);

        return response()->json([
            "msg" => "Paket Jasa musik berhasil disimpan",
        ], 200);
    }

    public function show(string $id_paket_jasa_musik)
    {
        $paket = DB::table('paket_jasa_musik')
            ->join("master_jasa_musik", "master_jasa_musik.id_jasa_musik", "=", "paket_jasa_musik.id_jasa_musik")
            ->join("master_jenis_jasa", "master_jenis_jasa.id_jenis_jasa", "=", "master_jasa_musik.id_jenis_jasa")
            ->select("paket_jasa_musik.*", "master_jenis_jasa.nama_jenis_jasa")
            ->where('paket_jasa_musik.id_paket_jasa_musik', $id_paket_jasa_musik)
            ->get();

        if (empty($paket)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($paket);
        }
    }

    public function update(Request $request, string $id_paket_jasa_musik)
    {
        $validate = Validator::make($request->all(), [
            "id_jasa_musik" => "required",
            "nama_paket" => "required",
            "deskripsi" => "required",
            "rincian_paket" => "required",
            "biaya_paket" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $paket = $request->only('id_jasa_musik', 'nama_paket', 'deskripsi', 'rincian_paket', 'biaya_paket');

        PaketJasaMusikModel::where("id_paket_jasa_musik", $id_paket_jasa_musik)->update($paket);

        return response()->json([
            "msg" => "Paket Jasa musik berhasil disimpan",
        ], 200);
    }

    public function destroy(string $id_paket_jasa_musik)
    {
        $data = PaketJasaMusikModel::findOrFail($id_paket_jasa_musik);
        $data->delete();

        return response()->json(['msg' => 'Data berhasil dihapus'], 200);
    }
}
