<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = DB::table('laporan')
            ->orderBy("id_laporan", "DESC")
            ->get();

        return view('admin.laporan.laporan_masalah');
    }

    public function data_index()
    {
        $laporans = LaporanModel::all();
        $data = datatables()->of($laporans)->addIndexColumn()->addColumn('gambar', function ($row) {
            $img_html = '';
            $images = json_decode($row->gambar, true);
            if (is_array($images)) {
                foreach ($images as $img) {
                    if (is_string($img)) {
                        $img_html .= '<a target="_blank" href="' . asset($img) . '"><img src="' . asset($img) . '" class="img-thumbnail" style="max-width: 50px; max-height: 50px;"></a> ';
                    }
                }
            }
            return $img_html;
        })->rawColumns(['gambar'])->make(true);

        return $data;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "jenis_laporan" => "required",
            "tgl_laporan" => "required",
            "gambar.*" => "required|image|mimes:png,jpg,jpeg|max:1024",
            "keterangan" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $imagePaths = [];
        if ($request->hasFile('gambar')) {
            foreach ($request->file('gambar') as $image) {
                $name =  uniqid() . '-Laporan-' . $request->jenis_laporan . "." . $image->getClientOriginalExtension();
                $image->move(public_path('/storage/img_upload/laporan'), $name);
                $imagePaths[] = '/storage/img_upload/laporan/' . $name;
            }
        }

        LaporanModel::create([
            'tgl_laporan' => $request->tgl_laporan,
            'jenis_laporan' => $request->jenis_laporan,
            'keterangan' => $request->keterangan,
            'gambar' => json_encode($imagePaths, JSON_UNESCAPED_SLASHES)
        ]);

        return response()->json([
            "msg" => "Jasa musik berhasil disimpan",
        ], 200);
    }

    public function update(Request $request, string $id_laporan)
    {
        $validate = Validator::make($request->all(), [
            "jenis_laporan" => "required",
            "tgl_laporan" => "required",
            "gambar.*" => "nullable|image|mimes:png,jpg,jpeg|max:1024",
            "keterangan" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data = LaporanModel::findOrFail($id_laporan);

        if ($data) {
            // Menghapus dan mengganti gambar jika ada file gambar baru yang diunggah
            if ($request->hasFile('gambar')) {
                // Hapus semua gambar lama
                foreach (json_decode($data->gambar) as $old_gambar) {
                    $path = public_path($old_gambar);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }

                // Simpan gambar baru
                $gambar_baru = [];
                foreach ($request->file('gambar') as $img) {
                    $extension = $img->getClientOriginalExtension();
                    $nama_img = uniqid() . "-Laporan-" . $request->jenis_laporan . "." . $extension;
                    $img->move(public_path('/storage/img_upload/laporan/'), $nama_img);
                    $gambar_baru[] = '/storage/img_upload/laporan/' . $nama_img;
                }
                $data->gambar = json_encode($gambar_baru, JSON_UNESCAPED_SLASHES);
            }

            $data->jenis_laporan = $request->input('jenis_laporan');
            $data->tgl_laporan = $request->input('tgl_laporan');
            $data->keterangan = $request->input('keterangan');

            $data->save();

            return response()->json([
                'msg' => 'Data laporan berhasil diperbarui',
            ], 200);
        }

        return response()->json([
            'msg' => 'Data laporan tidak ditemukan',
        ], 404);
    }


    public function show(string $id_laporan)
    {
        $data = DB::table('laporan')
            ->where("id_laporan", $id_laporan)
            ->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function destroy(string $id_laporan)
    {
        $data = LaporanModel::findOrFail($id_laporan);

        $images = json_decode($data->gambar, true);
        if (is_array($images)) {
            foreach ($images as $img) {
                if (file_exists(public_path($img))) {
                    unlink(public_path($img));
                }
            }
        }

        $data->delete();

        return response()->json(['msg' => 'Data berhasil dihapus'], 200);
    }
}
