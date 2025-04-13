<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DataAlatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DataAlatController extends Controller
{
    public function index()
    {
        return view('admin.data_alat.data_alat');
    }

    public function data_index()
    {
        $jasa_musik = DB::table('data_alat')
            ->get();

        $datatable = DataTables::of($jasa_musik)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "nama_alat" => "required",
            "tipe_alat" => "required",
            'jumlah_alat' => "nullable",
            'biaya_perawatan' => "nullable",
            // "harga_sewa" => "required",
            "foto_alat" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data_jasa = $request->only('nama_alat', 'tipe_alat', 'jumlah_alat', 'biaya_perawatan');

        if ($request->hasFile('foto_alat')) {
            $img = $request->file('foto_alat');
            $nama_img = time() . "-" . str_replace(' ', '_', $request->nama_alat) . "." . $img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/data_alat'), $nama_img);
            $data_jasa['foto_alat'] = $nama_img;
        }

        DataAlatModel::create($data_jasa);

        return response()->json([
            "msg" => "Data alat berhasil disimpan",
        ], 200);
    }


    public function show(string $id_alat)
    {
        $data = DataAlatModel::where("id_alat", $id_alat)->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, string $id_alat)
    {
        $validate = Validator::make($request->all(), [
            "nama_alat" => "required",
            "tipe_alat" => "required",
            'jumlah_alat' => "nullable",
            'biaya_perawatan' => "nullable",
            // "harga_sewa" => "required",
            // "foto_alat" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }

        $data = DataAlatModel::findOrFail($id_alat);

        if ($data) {
            // Menghapus dan mengganti gambar jika ada file gambar baru yang diunggah
            if ($request->hasFile('foto_alat')) {
                $path = 'storage/img_upload/data_alat' . $data->foto_alat;
                if (File::exists(public_path($path))) {
                    File::delete(public_path($path));
                }

                $img = $request->file('foto_alat');
                $extension = $img->getClientOriginalExtension();
                $nama_img = time() . "-" . str_replace(' ', '_', $request->nama_alat) . "." . $extension;
                $img->move(public_path('/storage/img_upload/data_alat'), $nama_img);
                $data->foto_alat = $nama_img;
            }

            $data->nama_alat = $request->input('nama_alat');
            $data->tipe_alat = $request->input('tipe_alat');
            $data->jumlah_alat = $request->input('jumlah_alat');
            $data->biaya_perawatan = $request->input('biaya_perawatan');
            // $data->harga_sewa = $request->input('harga_sewa');

            $data->save();

            return response()->json([
                'msg' => 'Data alat berhasil diperbarui',
            ], 200);
        }

        return response()->json([
            'msg' => 'Data alat tidak ditemukan',
        ], 404);
    }

    public function destroy(string $id_jasa_musik)
    {
        $data = DataAlatModel::findOrFail($id_jasa_musik);

        if ($data) {
            $path = '/storage/img_upload/data_alat/' . $data->foto_alat;
            if (File::exists(public_path($path))) {
                File::delete(public_path($path));
            }

            $data->delete();

            return response()->json(['msg' => 'Data berhasil dihapus'], 200);
        }

        return response()->json(['msg' => 'Data tidak ditemukan'], 404);
    }

    public function list_data_alat()
    {
        $data =
            DB::table('data_alat')
            ->select("id_alat", "nama_alat", "tipe_alat")
            ->get();

        return response()->json($data);
    }
}
