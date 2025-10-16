<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\DataAlatModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
<<<<<<< Updated upstream

class DataAlatController extends Controller
{
    public function index()
    {
        return view('admin.data_alat.data_alat');
    }

    public function data_index()
    {
        $data_alat = DB::table('data_alat')
            ->get();

        $datatable = DataTables::of($data_alat)
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
            

            $data->save();

            return response()->json([
                'msg' => 'Data alat berhasil diperbarui',
            ], 200);
        }

        return response()->json([
            'msg' => 'Data alat tidak ditemukan',
        ], 404);
    }

    public function destroy(string $id_data_alat)
    {
        $data = DataAlatModel::findOrFail($id_data_alat);

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
=======
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Notification;
use App\Notifications\RequestPeminjamanBaru;
use App\Notifications\PeminjamanApproved;
use App\Notifications\PeminjamanRejected;
use App\Notifications\PeminjamanStatusNotification;

class DataAlatController extends Controller
{    
        public function __construct()
        {
                $this->middleware(function ($request, $next) {
                        $role = auth()->user()->user_role;
                        if (in_array($request->route()->getActionMethod(), ['create','store','edit','update','destroy'])
                                && in_array($role, ['ukmbs','k3l'])) {
                                abort(403, 'Hanya admin/user yang diizinkan melakukan perubahan.');
                        }
                        return $next($request);
                });
        }

      public function index()
      {
            return view('admin.data_alat.data_alat');
      }

      public function data_index()
      {
            $data_alat = DB::table('data_alat')
                  ->get();

            $datatable = DataTables::of($data_alat)
                  ->addIndexColumn()
                  ->toJson();

            return $datatable;
      }

// -----------------------------------------------------------------------
// FUNGSI STORE (TAMBAH DATA)
// -----------------------------------------------------------------------

      public function store(Request $request)
      {
            $validate = Validator::make($request->all(), [
                  "nama_alat" => "required",
                  "tipe_alat" => "required",
                  'jumlah_alat' => "nullable",
                  'biaya_perawatan' => "nullable",
                  
                  // Validasi untuk file foto saat menambah
                  "foto_alat" => "nullable|image|mimes:png,jpg,jpeg|max:1028",
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
            
                  // Penyimpanan file menggunakan Storage::disk('public')
                  $img->storeAs('img_upload/data_alat', $nama_img, 'public');
            
                  $data_jasa['foto_alat'] = $nama_img;
            } else {
                  $data_jasa['foto_alat'] = null;
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

// -----------------------------------------------------------------------
// FUNGSI UPDATE (UBAH DATA)
// -----------------------------------------------------------------------

      public function update(Request $request, string $id_alat)
      {
            $validate = Validator::make($request->all(), [
                  "nama_alat" => "required",
                  "tipe_alat" => "required",
                  'jumlah_alat' => "nullable",
                  'biaya_perawatan' => "nullable",
                  
                  // PERBAIKAN: Tambahkan validasi foto alat saat update
                  "foto_alat" => "nullable|image|mimes:png,jpg,jpeg|max:1024", 
            ]);

            if ($validate->fails()) {
                  return response()->json([
                        'msg' => $validate->errors()
                  ], 422);
            }

            $data = DataAlatModel::findOrFail($id_alat);

            if ($data) {
                  if ($request->hasFile('foto_alat')) {
                        
                        // 1. Hapus foto lama jika ada
                        if ($data->foto_alat) {
                              // Menggunakan Storage untuk menghapus file dari disk 'public'
                              Storage::disk('public')->delete('img_upload/data_alat/' . $data->foto_alat);
                        }

                        // 2. Simpan foto baru
                        $img = $request->file('foto_alat');
                        $extension = $img->getClientOriginalExtension();
                        $nama_img = time() . "-" . str_replace(' ', '_', $request->nama_alat) . "." . $extension;

                        // Menggunakan storeAs() dengan disk 'public'
                        $img->storeAs('img_upload/data_alat', $nama_img, 'public');

                        $data->foto_alat = $nama_img; // Simpan nama file baru ke database
                  }
                  
                  // Update data lainnya
                  $data->nama_alat = $request->input('nama_alat');
                  $data->tipe_alat = $request->input('tipe_alat');
                  $data->jumlah_alat = $request->input('jumlah_alat');
                  $data->biaya_perawatan = $request->input('biaya_perawatan');
                  
                  $data->save();

                  return response()->json([
                        'msg' => 'Data alat berhasil diperbarui',
                  ], 200);
            }

            return response()->json([
                  'msg' => 'Data alat tidak ditemukan',
            ], 404);
      }

// -----------------------------------------------------------------------
// FUNGSI DESTROY (HAPUS DATA)
// -----------------------------------------------------------------------

      public function destroy(string $id_data_alat)
      {
            $data = DataAlatModel::find($id_data_alat);
      
            if (!$data) {
                  return response()->json(['msg' => 'Data tidak ditemukan'], 404);
            }
      
            // Hapus file fisik dari disk 'public' sebelum menghapus data dari database
            if ($data->foto_alat && Storage::disk('public')->exists('img_upload/data_alat/' . $data->foto_alat)) {
                  Storage::disk('public')->delete('img_upload/data_alat/' . $data->foto_alat);
            }
      
            $data->delete();
      
            return response()->json(['msg' => 'Data berhasil dihapus'], 200);
      }
      
// ... (Bagian kode lainnya tidak diubah)
// ...
>>>>>>> Stashed changes

    public function list_data_alat()
    {
        $data =
            DB::table('data_alat')
            ->select("id_alat", "nama_alat", "tipe_alat")
            ->get();

        return response()->json($data);
    }
<<<<<<< Updated upstream
=======

    public function approve($id, Request $r)
    {
        $p = \App\Models\PesananPinjamAlat::with('user')->findOrFail($id);
        $p->status_persetujuan = 'Y';
        $p->ket_admin = $r->input('ket_admin', '');
        $p->save();

        if ($p->user) {
            $p->user->notify(new PeminjamanApproved($p));
        }

        return back()->with('ok','Pengajuan disetujui.');
    }

    public function reject($id, Request $r)
    {
        $p = \App\Models\PesananPinjamAlat::with('user')->findOrFail($id);
        $alasan = $r->input('alasan', '');

        $p->status_persetujuan = 'N';
        $p->ket_admin = $alasan;
        $p->save();

        if ($p->user) {
            $p->user->notify(new PeminjamanRejected($p, $alasan));
        }

        return back()->with('ok','Pengajuan ditolak.');
    }
>>>>>>> Stashed changes
}
