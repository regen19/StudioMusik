<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\PengajuanUserEmail;
use App\Models\Admin\DetailPesananJadwalAlatModel;
use App\Models\PesananJadwalAlatModel;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\Table\TableExtension;
use Yajra\DataTables\Facades\DataTables;

class UserAlatDipinjamController extends Controller
{
    public function index()
    {
        $id_user = Auth::user()->id_user;
        $cek_pesanan = DB::table("pesanan_pinjam_alat")
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
            ->where("pesanan_pinjam_alat.id_user", $id_user)
            ->where(function ($query) {
                $query->where("pesanan_pinjam_alat.status_persetujuan", "P")
                    ->orWhere("pesanan_pinjam_alat.status_persetujuan", "Y");
            })
            ->where("pesanan_pinjam_alat.status_persetujuan", "N")
            ->where("pesanan_pinjam_alat.status_pengembalian", "N")
            ->first();

        return view('user.jadwal_alat_usr.jadwal_alat_usr', compact([
            'cek_pesanan'
        ]));
    }

    public function data_index()
    {
        $id_user = Auth::user()->id_user;
        $pesanan = DB::table('pesanan_pinjam_alat')
            ->join("users", "users.id_user", "=", "pesanan_pinjam_alat.id_user")
            ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")

            ->where("pesanan_pinjam_alat.id_user", $id_user)
            ->orderBy("pesanan_pinjam_alat.id_pesanan_pinjam_alat", "DESC")
            ->get();

        $datatable = DataTables::of($pesanan)
            ->addIndexColumn()
            ->toJson();

        return $datatable;
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            "id_user" => "required",
            "list_alat" => "required",
            "tgl_pinjam" => "required",
            "tgl_kembali" => "required",
            "waktu_mulai" => "required",
            "waktu_selesai" => "required",
            "no_wa" => "required",
            "ket_keperluan" => "required",
            "foto_jaminan" => "required",
            
        ]);

        if ($validate->fails()) {
            return response()->json([
                "msg" => $validate->errors()
            ], 422);
        }

        $pesanan = $request->all();

        $nama_img = "";
        if ($request->hasFile('foto_jaminan')) {
            $img = $request->file('foto_jaminan');
            $nama_img = time() . "-" . str_replace(' ', '_', $request->foto_jaminan) . "." . $img->getClientOriginalExtension();
            $img->move(public_path('/storage/img_upload/data_jaminan'), $nama_img);
        }

        $createdPesanan = PesananJadwalAlatModel::create([
            'id_user'=> $request->id_user,
            'no_wa'=> $request->no_wa,
            'tgl_pinjam' => $request->tgl_pinjam,
            'tgl_kembali' => $request->tgl_kembali,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
            'ket_keperluan' => $request->ket_keperluan,
            'foto_jaminan'=> $nama_img,
            'status_persetujuan' => 'P',
            'status_pengembalian' => 'N',
        ]);


        // Simpan semua data alat yang dipinjam
        foreach ($request->input('list-alat', []) as $alat) {
            DetailPesananJadwalAlatModel::create([
                'id_pesanan_pinjam_alat' => $createdPesanan->id_pesanan_pinjam_alat, // foreign key
                'id_alat' => $alat['id_alat'],
                'jumlah' => $alat['jumlah'],
            ]);
        }

        // $dataEmail = DB::table("pesanan_pinjam_alat")
        //     ->join("users", "users.id_user", "=", "pesanan_pinjam_alat.id_user")
        //     ->join("data_alat", "data_alat.id_alat", "=", "detail_.id_alat")
        //     ->select("pesanan_pinjam_alat.*", "users.username", "data_alat.nama_alat")
        //     ->where("pesanan_pinjam_alat.id_pesanan_pinjam_alat", $pesananModel->id_pesanan_pinjam_alat)
        //     ->first();

        $alatSedangDipinjam = DB::table("pesanan_pinjam_alat")
        ->select("*")
        ->join("detail_pesanan_pinjam_alat", "detail_pesanan_pinjam_alat.id_pesanan_pinjam_alat", "=", "pesanan_pinjam_alat.id_pesanan_pinjam_alat")
        ->where("pesanan_pinjam_alat.status_persetujuan", "Y")
        ->where("pesanan_pinjam_alat.status_pengembalian", "N")
        ->get();


        // $subject = "Pengajuan Peminjaman Studio Musik Baru Hari ini";
        // $view = "EmailNotif.PengajuanStudioMusikMail";
        // Mail::to('candrawahyuf@gmail.com')->send(new PengajuanUserEmail($dataEmail, $subject, $view));

        // return redirect('alat_dipinjam')->with('success', 'Pengajuan jadwal studio tersimpan!');

        return response()->json([
            "msg" => $request->id_user
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

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $request->input('biaya_perawatan'),
            ),
            'customer_details' => array(
                'first_name' => $request->input('nama_user'),
                'phone' => $request->input('no_wa'),
            ),
        );

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json($snapToken);
    }

    public function pembayaran_biaya_perawatan(Request $request)
    {

        $id_pesanan_pinjam_alat = $request->input('id_pesanan_pinjam_alat');

        $data['status_pembayaran'] = "Y";

        DetailPesananJadwalAlatModel::where("id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)->update($data);

        return response()->json(["status" => "sukses"]);
    }

    // public function pengembalian_alat(Request $request)
    // {

    //     $id_pesanan_pinjam_alat = $request->input('id_pesanan_pinjam_alat');

    //     $data['status_peminjaman'] = "Y";

    //     DetailPesananJadwalAlatModel::where("id_pesanan_pinjam_alat", $id_pesanan_pinjam_alat)->update($data);

    //     return response()->json(["status" => "sukses"]);
    // }

}
