<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = User::all();

        return view('admin.dashboard', compact('data'));
    }

    public function dashboard_user()
    {

        $display = DB::table("master_jasa_musik")
            ->join('master_jenis_jasa', "master_jenis_jasa.id_jenis_jasa", "=", "master_jasa_musik.id_jenis_jasa")
            ->get();

        return view('user.dashboard_user', compact([
            'display'
        ]));
    }
}
