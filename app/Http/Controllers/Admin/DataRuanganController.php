<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DataRuanganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DataRuanganController extends Controller
{
    public function index()
    {
        return view('admin.data_ruangan.data_ruangan');
    }

    public function data_index()
    {
        $jasa_musik = DB::table('data_ruangan')
            ->get();

        $datatable = DataTables::of($jasa_musik)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "nama_ruangan" => "required",
            "kapasitas" => "required",
            'lokasi' => "nullable",
            'fasilitas' => "nullable",
            // "harga_sewa" => "required",
            "thumbnail" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data_jasa = $request->only('nama_ruangan', 'kapasitas', 'lokasi', 'fasilitas');

        if ($request->hasFile('thumbnail')) {
            $img = $request->file('thumbnail');
            $nama_img = time() . "-" . str_replace(' ', '_', $request->nama_ruangan) . "." . $img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/data_ruangan'), $nama_img);
            $data_jasa['thumbnail'] = $nama_img;
        }

        DataRuanganModel::create($data_jasa);

        return response()->json([
            "msg" => "Data ruangan berhasil disimpan",
        ], 200);
    }


    public function show(string $id_ruangan)
    {
        $data = DataRuanganModel::where("id_ruangan", $id_ruangan)->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, string $id_ruangan)
    {
        $validate = Validator::make($request->all(), [
            "nama_ruangan" => "required",
            "kapasitas" => "required",
            'lokasi' => "nullable",
            'fasilitas' => "nullable",
            // "harga_sewa" => "required",
            // "thumbnail" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }

        $data = DataRuanganModel::findOrFail($id_ruangan);

        if ($data) {
            // Menghapus dan mengganti gambar jika ada file gambar baru yang diunggah
            if ($request->hasFile('thumbnail')) {
                $path = 'storage/img_upload/data_ruangan' . $data->thumbnail;
                if (File::exists(public_path($path))) {
                    File::delete(public_path($path));
                }

                $img = $request->file('thumbnail');
                $extension = $img->getClientOriginalExtension();
                $nama_img = time() . "-" . str_replace(' ', '_', $request->nama_ruangan) . "." . $extension;
                $img->move(public_path('/storage/img_upload/data_ruangan'), $nama_img);
                $data->thumbnail = $nama_img;
            }

            $data->nama_ruangan = $request->input('nama_ruangan');
            $data->kapasitas = $request->input('kapasitas');
            $data->lokasi = $request->input('lokasi');
            $data->fasilitas = $request->input('fasilitas');
            

            $data->save();

            return response()->json([
                'msg' => 'Data ruangan berhasil diperbarui',
            ], 200);
        }

        return response()->json([
            'msg' => 'Data ruangan tidak ditemukan',
        ], 404);
    }

    public function destroy(string $id_jasa_musik)
    {
        $data = DataRuanganModel::findOrFail($id_jasa_musik);

        if ($data) {
            $path = '/storage/img_upload/data_ruangan/' . $data->thumbnail;
            if (File::exists(public_path($path))) {
                File::delete(public_path($path));
            }

            $data->delete();

            return response()->json(['msg' => 'Data berhasil dihapus'], 200);
        }

        return response()->json(['msg' => 'Data tidak ditemukan'], 404);
    }

    public function list_data_ruangan()
    {
        $data =
            DB::table('data_ruangan')
            ->select("id_ruangan", "nama_ruangan", "harga_sewa")
            ->get();

        return response()->json($data);
    }
}
