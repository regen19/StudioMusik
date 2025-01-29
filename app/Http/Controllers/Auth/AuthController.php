<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login_page');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->errors()->first(),
            ]);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'k3l') {
                return response()->json([
                    'status' => 200,
                    'redirect' => url('/dashboard'),
                ]);
            } else if (Auth::user()->user_role == 'user') {
                return response()->json([
                    'status' => 200,
                    'redirect' => url('/dashboard_user'),
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => "Email atau password salah.",
            ]);
        }
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username'  => 'required',
                'email'     => 'required|email|unique:users',
                'no_wa'  => 'required',
                'password'  => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "msg" => $validator->errors()
                ], 422);
            }

            $user = User::create([
                'username' => $request->username,
                'email'         => $request->email,
                'no_wa'         => $request->no_wa,
                'password'      => Hash::make($request->password),
                'user_role' => "user",
            ]);

            return response()->json([
                'status' => 200,
                'redirect' => url('/login'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong',
                'msg' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => 200,
            'redirect' => url('/'),
        ]);
    }

    public function profile_user()
    {
        $user = auth()->user();

        return view('user.profil_user', compact(['user']));
    }

    public function edit_profile(Request $request, string $id_user)
    {
        $validate = Validator::make($request->all(), [
            "username" => "required",
            "email" => "required|email",
            "no_wa" => "required|numeric",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors(),
                "status" => 422,
            ], 422);
        }

        $profil = $request->only('username', 'no_wa', 'email');

        User::findOrFail($id_user)->update($profil);

        return response()->json([
            "msg" => "Profile telah diperbarui",
            "status" => 200,
        ], 200);
    }
}
