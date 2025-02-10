<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FormJasaMusikModel;
use App\Models\PesananJasaMusikModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class UserPesananJasaMusikController extends Controller
{
    public function index()
    {
        return view('user.jasa_musik_usr.pesanan_jasa_musik_usr');
    }

    public function riwayat_pesanan_jasa_musik()
    {
        return view('user.jasa_musik_usr.riwayat_pesanan_jasa_musik');
    }

    public function riwayat_pesanan_data()
    {
        $pesanan = DB::table('pesanan_jasa_musik')
            ->join('users', 'users.id_user', '=', 'pesanan_jasa_musik.id_user')
            ->join('master_jasa_musik', 'master_jasa_musik.id_jasa_musik', '=', 'pesanan_jasa_musik.id_jasa_musik')
            ->where('pesanan_jasa_musik.status_produksi', "Y")
            ->orWhere("pesanan_jasa_musik.status_produksi", "P")
            ->orderBy('pesanan_jasa_musik.id_pesanan_jasa_musik', 'DESC')
            ->get();

        $datatable = DataTables::of($pesanan)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function data_index()
    {
        $id_user = Auth::user()->id_user;
        $pesanan = DB::table('pesanan_jasa_musik')
            ->join('users', 'users.id_user', '=', 'pesanan_jasa_musik.id_user')
            ->join('master_jasa_musik', 'master_jasa_musik.id_jasa_musik', '=', 'pesanan_jasa_musik.id_jasa_musik')
            ->orderBy('pesanan_jasa_musik.id_pesanan_jasa_musik', 'DESC')
            ->where('pesanan_jasa_musik.id_user', $id_user)
            ->get();

        $datatable = DataTables::of($pesanan)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function list_data_jasa_musik()
    {
        return DB::table('master_jasa_musik')->get();
    }

    public function informasi_jasa_musik(string $id)
    {
        $jasa_musik = FormJasaMusikModel::where('jasa_musik_id', $id)->get();

        return $jasa_musik;
    }

    public function beri_rating_jasa(Request $request, string $id_pesanan_jasa_musik)
    {
        $validate = Validator::make($request->all(), [
            'rating' => 'required',
            'review' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'msg' => $validate->errors(),
            ], 422);
        }

        $pesanan = $request->only('rating', 'review');

        PesananJasaMusikModel::findOrFail($id_pesanan_jasa_musik)->update($pesanan);

        return response()->json([
            'msg' => 'Review Rating telah diubah',
        ], 200);
    }

    public function get_snap_token(Request $request)
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrans.serverKey');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => $request->input('biaya_paket'),
            ],
            'customer_details' => [
                'first_name' => $request->input('nama_user'),
                'phone' => $request->input('no_wa'),
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json($snapToken);
    }

    public function pembayaran_jasa_sukses(Request $request)
    {

        $id_pesanan_jasa_musik = $request->input('id_pesanan_jasa_musik');

        $data['status_pembayaran'] = 'Y';

        PesananJasaMusikModel::where('id_pesanan_jasa_musik', $id_pesanan_jasa_musik)->update($data);

        return response()->json(['status' => 'sukses']);
    }
}
