<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormJasaMusikModel;
use App\Models\MasterJasaMusikModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class MasterJasaMusikController extends Controller
{
    public function index()
    {
        return view('admin.jasa_musik.master_jasa_musik');
    }

    public function data_index()
    {
        $jasa_musik = MasterJasaMusikModel::with('formJasa');

        $datatable = DataTables::eloquent($jasa_musik)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama_jenis_jasa' => 'required',
            'sk' => 'nullable',
            'informasi_jasa_musik' => 'required',
            'deskripsi' => 'nullable',
            'gambar' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            // "keterangan" => "nullable",
            // 'biaya_produksi' => "nullable"
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
            ], 422);
        }

        $data_jasa = $request->only('nama_jenis_jasa', 'sk', 'deskripsi');

        if ($request->hasFile('gambar')) {
            $img = $request->file('gambar');
            $nama_img = time() . '-' . str_replace(' ', '_', $request->nama_jenis_jasa) . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/jasa_musik'), $nama_img);
            $data_jasa['gambar'] = $nama_img;
        }

        $jasa_musik = MasterJasaMusikModel::create($data_jasa);
        foreach (json_decode($request->informasi_jasa_musik, true) as $form) {
            FormJasaMusikModel::create([
                'jasa_musik_id' => $jasa_musik->id_jasa_musik,
                'jenis_field' => $form['jenis_field'],
                'nama_field' => $form['nama_field'],
            ]);
        }

        return response()->json([
            'msg' => 'Jasa musik berhasil disimpan',
        ], 200);
    }

    public function show(string $id_jasa_musik)
    {
        $data = MasterJasaMusikModel::with('formJasa')->where('id_jasa_musik', $id_jasa_musik)->first();

        if (empty($data)) {
            return response()->json([
                'msg' => 'Data tidak ditemukan...',
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, string $id_jasa_musik)
    {
        $validate = Validator::make($request->all(), [
            'nama_jenis_jasa' => 'nullable',
            'sk' => 'nullable',
            'deskripsi' => 'nullable',
            'informasi_jasa_musik' => 'required',
            // 'gambar' => 'nullable|image|mimes:png,jpg,jpeg|max:1024',
            // 'keterangan' => 'nullable',
            'biaya_produksi' => 'nullable',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
            ], 422);
        }

        $data = MasterJasaMusikModel::findOrFail($id_jasa_musik);

        if ($data) {
            // Menghapus dan mengganti gambar jika ada file gambar baru yang diunggah
            if ($request->hasFile('gambar')) {
                $path = '/storage/img_upload/jasa_musik/' . $data->gambar;
                if (File::exists(public_path($path))) {
                    File::delete(public_path($path));
                }

                $img = $request->file('gambar');
                $extension = $img->getClientOriginalExtension();
                $nama_img = time() . '-' . str_replace(' ', '_', $request->nama_jenis_jasa) . '.' . $extension;
                $img->move(public_path('/storage/img_upload/jasa_musik'), $nama_img);
                $data->gambar = $nama_img;
            }

            // Memperbarui data jasa musik berdasarkan input yang valid
            FormJasaMusikModel::where('jasa_musik_id', $id_jasa_musik)->delete();
            foreach (json_decode($request->informasi_jasa_musik, true) as $form) {
                FormJasaMusikModel::create([
                    'jasa_musik_id' => $id_jasa_musik,
                    'jenis_field' => $form['jenis_field'],
                    'nama_field' => $form['nama_field'],
                ]);
            }
            $data->nama_jenis_jasa = $request->input('nama_jenis_jasa');
            $data->sk = $request->input('sk');
            $data->deskripsi = $request->input('deskripsi');
            // $data->keterangan = $request->input('keterangan');
            $data->biaya_produksi = $request->input('biaya_produksi');

            $data->save();

            return response()->json([
                'msg' => 'Jasa musik berhasil diperbarui',
            ], 200);
        }

        return response()->json([
            'msg' => 'Data jasa musik tidak ditemukan',
        ], 404);
    }

    public function destroy(string $id_jasa_musik)
    {
        $data = MasterJasaMusikModel::findOrFail($id_jasa_musik);

        if ($data) {
            $path = '/storage/img_upload/jasa_musik/' . $data->gambar;
            if (File::exists(public_path($path))) {
                File::delete(public_path($path));
            }

            $data->delete();
            FormJasaMusikModel::where('jasa_musik_id', $id_jasa_musik)->delete();

            return response()->json(['msg' => 'Data berhasil dihapus'], 200);
        }
    }
}
