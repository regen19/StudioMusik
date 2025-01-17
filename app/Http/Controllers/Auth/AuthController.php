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
                'username'  => 'nullable',
                'email'     => 'required|email|unique:users',
                'password'  => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors());
            }

            $user = User::create([
                'username' => $request->username,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'user_role' => "user",
            ]);

            return response()->json([
                'status' => 200,
                'redirect' => url('/'),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
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
}
