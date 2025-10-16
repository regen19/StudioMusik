<?php

<<<<<<< Updated upstream

=======
>>>>>>> Stashed changes
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
<<<<<<< Updated upstream
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ManageUserController extends Controller
{
=======
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;



class ManageUserController extends Controller
{
    /**
     * Tampilkan halaman manajemen user
     */
>>>>>>> Stashed changes
    public function index()
    {
        return view('admin.users.users');
    }

<<<<<<< Updated upstream
    public function data_index()
    {
        $users = DB::table('users')
            ->get();

        $datatable = DataTables::of($users)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
            'no_wa' => 'required',
            'email' => 'required|email'
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);
        return response()->json(['message' => 'User added successfully']);
    }

    public function show(string $id_user)
    {
        $data = User::where("id_user", $id_user)->first();

        if (empty($data)) {
            return response()->json([
                "msg" => "Data tidak ditemukan...",
            ], 404);
        } else {
            return response()->json($data);
        }
    }

    public function update(Request $request, string $id_user)
    {
        $validate = Validator::make($request->all(), [
            "username" => "required",
            "email" => "required",
            'no_wa' => "nullable",
       
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors()
            ], 422);
        }

        $data = User::findOrFail($id_user);

        return response()->json([
            'msg' => 'Data user tidak ditemukan',
        ], 404);
    }

    public function destroy(string $id_user)
    {
        $data = User::findOrFail($id_user);

        if ($data) {
            $path = '/storage/img_upload/data_user/' . $data->foto_user;
            if (File::exists(public_path($path))) {
                File::delete(public_path($path));
            }

            $data->delete();

            return response()->json(['msg' => 'Data berhasil dihapus'], 200);
        }

        return response()->json(['msg' => 'Data tidak ditemukan'], 404);
    }

    public function list_data_user()
    {
        $data =
            DB::table('data_user')
            ->select("id_user", "username", "email")
            ->get();

        return response()->json($data);
=======
    /**
     * Ambil data user untuk DataTables (AJAX)
     */
    public function data_index()
    {
        $users = User::select('id_user', 'username', 'no_wa', 'email', 'user_role')->get();

        return DataTables::of($users)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'no_wa'    => 'required|string|max:20',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['user_role'] = 'user';

        User::create($validated);

        return response()->json(['message' => 'User berhasil ditambahkan.'], 200);
    }

    /**
     * Tampilkan detail user berdasarkan ID
     */
    public function showById($id_user)
    {
        $user = User::find($id_user);

        if (!$user) {
            return response()->json(['error' => 'Data user tidak ditemukan'], 404);
        }

        return response()->json($user, 200);
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id_user)
    {
        try {
            $user = User::find($id_user);
    
            if (!$user) {
                return response()->json(['error' => 'Data user tidak ditemukan'], 404);
            }
    
            $validated = $request->validate([
                'username' => 'required|string|max:255',
                'no_wa'    => 'required|string|max:20',
                'email'    => 'required|email|max:255|unique:users,email,' . $id_user . ',id_user',
                'password' => 'nullable|string|min:6',
            ]);
    
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($request->password);
            } else {
                unset($validated['password']);
            }
    
            $user->update($validated);
    
            return response()->json(['message' => 'Data user berhasil diubah.'], 200);
    
        } catch (\Exception $e) {
            Log::error('User update error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus data user (beserta foto jika ada)
     */
    public function destroy($id_user)
    {
        $user = User::find($id_user);

        if (!$user) {
            return response()->json(['error' => 'Data user tidak ditemukan'], 404);
        }

        // Jika user memiliki foto, hapus juga file-nya
        if (!empty($user->foto_user)) {
            $path = public_path('storage/img_upload/data_user/' . $user->foto_user);
            if (File::exists($path)) {
                File::delete($path);
            }
        }

        $user->delete();

        return response()->json(['message' => 'Data user berhasil dihapus.'], 200);
>>>>>>> Stashed changes
    }
}
