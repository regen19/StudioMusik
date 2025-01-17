<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterJenisJasaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MasterJenisJasaController extends Controller
{
    public function index()
    {
        $jenis_jasa = MasterJenisJasaModel::orderBy('id_jenis_jasa', 'DESC')->get();

        $datatable = DataTables::of($jenis_jasa)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "nama_jenis_jasa" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data_jasa = $request->only('nama_jenis_jasa');

        MasterJenisJasaModel::create($data_jasa);

        return response()->json([
            "msg" => "Jasa musik berhasil disimpan",
        ], 200);
    }


    public function show(string $id_jenis_jasa)
    {
        $data = MasterJenisJasaModel::where("id_jenis_jasa", $id_jenis_jasa)->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, string $id_jenis_jasa)
    {
        $validate = Validator::make($request->all(), [
            "nama_jenis_jasa" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data_jasa = $request->only('nama_jenis_jasa');

        MasterJenisJasaModel::findOrFail($id_jenis_jasa)->update($data_jasa);

        return response()->json([
            "msg" => "Jenis jasa berhasil diubah",
        ], 200);
    }

    public function destroy(string $id_jenis_jasa)
    {
        $data = MasterJenisJasaModel::findOrFail($id_jenis_jasa);
        $data->delete();

        return response()->json(['msg' => 'Data berhasil dihapus'], 200);
    }
}
