<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\PengajuanUserEmail;
use App\Models\Admin\DetailPesananJadwalStudioModel;
use App\Models\PesananJadwalStudioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use function Laravel\Prompts\table;

class UserJadwalStudioController extends Controller
{
    public function index()
    {
        $id_user = Auth::user()->id_user;
        $cek_pesanan = DB::table("pesanan_jadwal_studio")
            ->join("detail_pesanan_jadwal_studio", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->where("pesanan_jadwal_studio.id_user", $id_user)
            ->where(function ($query) {
                $query->where("detail_pesanan_jadwal_studio.status_persetujuan", "P")
                    ->orWhere("detail_pesanan_jadwal_studio.status_persetujuan", "Y");
            })
            ->where("detail_pesanan_jadwal_studio.status_peminjaman", "N")
            ->where("detail_pesanan_jadwal_studio.status_pengajuan", "Y")
            ->first();

        return view('user.jadwal_studio_usr.jadwal_studio_usr', compact([
            'cek_pesanan'
        ]));
    }

    public function data_index()
    {
        $id_user = Auth::user()->id_user;
        $pesanan = DB::table('pesanan_jadwal_studio')
            ->join("users", "users.id_user", "=", "pesanan_jadwal_studio.id_user")
            ->join("detail_pesanan_jadwal_studio", "detail_pesanan_jadwal_studio.id_pesanan_jadwal_studio", "=", "pesanan_jadwal_studio.id_pesanan_jadwal_studio")
            ->join("data_ruangan", "data_ruangan.id_ruangan", "=", "pesanan_jadwal_studio.id_ruangan")
            ->where("pesanan_jadwal_studio.id_user", $id_user)
            ->orderBy("pesanan_jadwal_studio.id_pesanan_jadwal_studio", "DESC")
            ->get();

        $datatable = DataTables::of($pesanan)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    // public function get_snap_token(Request $request)
    // {
    //     // Set your Merchant Server Key
    //     \Midtrans\Config::$serverKey = config('midtrans.serverKey');
    //     // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
    //     \Midtrans\Config::$isProduction = false;
    //     // Set sanitization on (default)
    //     \Midtrans\Config::$isSanitized = true;
    //     // Set 3DS transaction for credit card to true
    //     \Midtrans\Config::$is3ds = true;

    //     $params = array(
    //         'transaction_details' => array(
    //             'order_id' => rand(),
    //             'gross_amount' => $request->input('harga_perawatan'),
    //         ),
    //         'customer_details' => array(
    //             'first_name' => $request->input('nama_user'),
    //             'phone' => $request->input('no_wa'),
    //         ),
    //     );

    //     $snapToken = \Midtrans\Snap::getSnapToken($params);

    //     return response()->json($snapToken);
    // }

    public function pembayaran_studio_sukses(Request $request)
    {

        $id_pesanan_jadwal_studio = $request->input('id_pesanan_jadwal_studio');

        $data['status_pembayaran'] = "Y";

        DetailPesananJadwalStudioModel::where("id_pesanan_jadwal_studio", $id_pesanan_jadwal_studio)->update($data);

        return response()->json(["status" => "sukses"]);
    }

    public function pengembalian_ruangan(Request $request)
    {
        $id_pesanan_jadwal_studio = $request->input('id_pesanan_jadwal_studio');

        $cek = DB::table("detail_pesanan_jadwal_studio")
            ->where("id_pesanan_jadwal_studio", $id_pesanan_jadwal_studio)
            ->first();

        if ($cek && $cek->img_kondisi_awal != null && $cek->img_kondisi_akhir != null) {
            DetailPesananJadwalStudioModel::where("id_pesanan_jadwal_studio", $id_pesanan_jadwal_studio)
                ->update(['status_peminjaman' => "Y"]);

            return response()->json(["status" => "sukses"]);
        } else {
            return response()->json(["status" => "gagal"]);
        }
    }


    function beri_rating_studio(Request $request, string $id_pesanan_jadwal_studio)
    {
        $validate = Validator::make($request->all(), [
            "rating" => "required",
            "review" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $rating = $request->only('rating', 'review');

        DetailPesananJadwalStudioModel::findOrFail($id_pesanan_jadwal_studio)->update($rating);

        return response()->json([
            "msg" => "Status rating telah diubah",
        ], 200);
    }
}
