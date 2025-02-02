<?php

namespace App\Http\Controllers;

use App\Models\TutorialPenggunaanAlatModel;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Yajra\DataTables\Facades\DataTables;

class TutorialPenggunaanAlatController extends Controller
{
    //
    public function index_user()
    {
        $data = DB::table("tutorial_penggunaan_alat")
            ->get();

        foreach ($data as $item) {
            $item->deskripsi = implode(' ', array_slice(explode(' ', html_entity_decode($item->deskripsi)), 0, 20));
        }

        return view('user.jadwal_studio_usr.tutorial_alat', compact([
            'data'
        ]));
    }

    public function detail_penggunaan_alat($id_tutorial)
    {
        $data = DB::table("tutorial_penggunaan_alat")
            ->where('id_tutorial', $id_tutorial)
            ->get();
        // dd($data);
        return view('user.jadwal_studio_usr.detail_penggunaan_alat', compact([
            'data'
        ]));
    }

    public function index_adm()
    {
        $data = DB::table("tutorial_penggunaan_alat")
            ->get();

        return view('admin.data_tutorial_alat.data_tutorial_alat', compact([
            'data'
        ]));
    }

    public function data_index()
    {
        $tutorial = DB::table('tutorial_penggunaan_alat')
            ->orderBy("id_tutorial", "DESC")
            ->get();

        $datatable = DataTables::of($tutorial)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = FacadesValidator::make($request->all(), [
            "nama_alat" => "required",
            "deskripsi" => "required",
            "gambar_alat" => "required|image|mimes:png,jpg,jpeg|max:1024",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $tutorial = $request->only('nama_alat', 'deskripsi');

        if ($request->hasFile('gambar_alat')) {
            $img = $request->file('gambar_alat');
            $nama_img = time() . "-" . $request->nama_alat . "." . $img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/tutorial_alat/'), $nama_img);
            $tutorial['gambar_alat'] = $nama_img;
        }

        TutorialPenggunaanAlatModel::create($tutorial);

        return response()->json([
            "msg" => "Jasa musik berhasil disimpan",
        ], 200);
    }

    public function update(Request $request, string $id_tutorial)
    {
        $validate = FacadesValidator::make($request->all(), [
            "nama_alat" => "required",
            "deskripsi" => "required",
            "gambar_alat" => "nullable|max:1024",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $data = TutorialPenggunaanAlatModel::findOrFail($id_tutorial);

        if ($data) {
            // Menghapus dan mengganti gambar jika ada file gambar baru yang diunggah
            if ($request->hasFile('gambar_alat')) {
                $path = 'storage/img_upload/tutorial_alat/' . $data->gambar_alat;
                if (File::exists(public_path($path))) {
                    File::delete(public_path($path));
                }

                $img = $request->file('gambar_alat');
                $extension = $img->getClientOriginalExtension();
                $nama_img = time() . "-" . str_replace(' ', '_', $request->nama_alat) . "." . $extension;
                $img->move(public_path('/storage/img_upload/tutorial_alat/'), $nama_img);
                $data->gambar_alat = $nama_img;
            }

            $data->nama_alat = $request->input('nama_alat');
            $data->deskripsi = $request->input('deskripsi');

            $data->save();

            return response()->json([
                'msg' => 'Data Alat berhasil diperbarui',
            ], 200);
        }
    }

    public function show(string $id_tutorial)
    {
        $data = DB::table('tutorial_penggunaan_alat')
            ->where("id_tutorial", $id_tutorial)
            ->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function destroy(string $id_tutorial)
    {
        $data = TutorialPenggunaanAlatModel::findOrFail($id_tutorial);

        if ($data) {
            $path = 'storage/img_upload/tutorial_alat/' . $data->gambar_alat;
            if (File::exists(public_path($path))) {
                File::delete(public_path($path));
            }

            $data->delete();

            return response()->json(['msg' => 'Data berhasil dihapus'], 200);
        }
    }
}
