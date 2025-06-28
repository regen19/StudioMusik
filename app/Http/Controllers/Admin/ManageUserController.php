<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ManageUserController extends Controller
{
    public function index()
    {
        return view('admin.users.users');
    }

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
    }
}
