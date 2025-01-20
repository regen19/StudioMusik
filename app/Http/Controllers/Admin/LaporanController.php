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
        return datatables()->of($laporans)->addIndexColumn()->addColumn('gambar', function ($row) {
            $img_html = '';
            $images = json_decode($row->gambar, true);
            if (is_array($images)) {
                foreach ($images as $img) {
                    $img_html .= '<a target="_blank" href="' . asset($img) . '"><img src="' . asset($img) . '" class="img-thumbnail" style="max-width: 50px; max-height: 50px;"></a> ';
                }
            }
            return $img_html;
        })->rawColumns(['gambar'])->make(true);
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
        if ($request->hasfile('gambar')) {
            foreach ($request->file('gambar') as $image) {
                $name = time() . '-Laporan.' . $image->getClientOriginalExtension();
                $path = $image->move(public_path('/storage/img_upload/laporan'), $name);
                $imagePaths[] = $path;
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

    public function destroy(string $id_laporan)
    {
        $data = LaporanModel::findOrFail($id_laporan);

        $images = json_decode($data->gambar, true);
        if (is_array($images)) {
            foreach ($images as $img) {
                Storage::delete($img);
            }
        }

        $data->delete();

        return response()->json(['msg' => 'Data berhasil dihapus'], 200);
    }
}

        // if ($data) {
        //     $path = 'storage/img_upload/' . $data->gambar;
        //     if (File::exists(public_path($path))) {
        //         File::delete(public_path($path));
        //     }

        //     $data->delete();

        //     return response()->json(['msg' => 'Data berhasil dihapus'], 200);
        // }
