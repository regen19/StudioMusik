<?php

use App\Http\Controllers\Admin\DataRuanganController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MasterJasaMusikController;
use App\Http\Controllers\Admin\PaketJasaMusikController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesananJadwalStudioController;
use App\Http\Controllers\PesananJasaMusikController;
use App\Http\Controllers\TutorialPenggunaanAlatController;
use App\Http\Controllers\User\DataRuanganUserController;
use App\Http\Controllers\User\DisplayJasaMusikController;
use App\Http\Controllers\User\UserJadwalStudioController;
use App\Http\Controllers\User\UserPesananJasaMusikController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get("/", [AuthController::class, 'index'])->name("login");
Route::post("/authenticate", [AuthController::class, 'login']);
Route::post("/register", [AuthController::class, 'register']);
// Route::post("/register", [AuthController::class, 'register']);


Route::get('/forbidden_403', function () {
    return view('errors.forbidden_403');
});

Route::get('/system_error_500', function () {
    return view('errors.system_error_500');
});


Route::middleware('auth')->group(function () {
    Route::get("/logout", [AuthController::class, 'logout']);
    Route::get("/profile_user", [AuthController::class, 'profile_user']);
    Route::post("/edit_profile/{id}", [AuthController::class, 'edit_profile']);

    Route::middleware('isAdmin')->group(function () {
        Route::get("/dashboard", [DashboardController::class, 'dashboard']);

        // MASTER JASA MUSIK
        Route::get("/master_jasa_musik", [MasterJasaMusikController::class, 'index']);
        Route::get("/fetch_master_jasa_musik", [MasterJasaMusikController::class, 'data_index']);
        Route::post("/add_master_jasa_musik", [MasterJasaMusikController::class, 'store']);
        Route::post("/showById_master_jasa_musik/{id}", [MasterJasaMusikController::class, 'show']);
        Route::post("/edit_master_jasa_musik/{id}", [MasterJasaMusikController::class, 'update']);
        Route::delete("/hapus_master_jasa_musik/{id}", [MasterJasaMusikController::class, 'destroy']);

        // DATA RUANGAN STUDIO
        Route::get("/data_ruangan", [DataRuanganController::class, 'index']);
        Route::get("/fetch_data_ruangan", [DataRuanganController::class, 'data_index']);
        Route::post("/add_data_ruangan", [DataRuanganController::class, 'store']);
        Route::post("/showById_data_ruangan/{id}", [DataRuanganController::class, 'show']);
        Route::post("/edit_data_ruangan/{id}", [DataRuanganController::class, 'update']);
        Route::delete("/hapus_data_ruangan/{id}", [DataRuanganController::class, 'destroy']);

        // PESANAN JADWAL STUDIO
        Route::get("/data_peminjam_ruangan", [PesananJadwalStudioController::class, 'index']);
        Route::get("/fetch_pesanan_jadwal_studio", [PesananJadwalStudioController::class, 'data_index']);
        Route::post("/status_pesanan_jadwal_studio/{id}", [PesananJadwalStudioController::class, 'status_pesanan_jadwal_studio']);
        // Route::post("/harga_sewa_studio", [PesananJadwalStudioController::class, 'harga_sewa_studio']);
        Route::get("/lihat_harga_sewa_studio", [PesananJadwalStudioController::class, 'lihat_harga_studio']);

        // PESANAN JASA MUSIK
        Route::get("/pesanan_jasa_musik", [PesananJasaMusikController::class, 'index']);
        Route::get("/fetch_pesanan_jasa_musik", [PesananJasaMusikController::class, 'data_index']);
        Route::post("/status_pesanan_jasa_musik/{id}", [PesananJasaMusikController::class, 'status_pesanan_jasa_musik']);

        // LAPORAN
        Route::get("/laporan_admin", [LaporanController::class, 'index']);
        Route::get("/fetch_laporan_masalah", [LaporanController::class, 'data_index']);
        Route::post("/add_laporan_masalah", [LaporanController::class, 'store']);
        Route::delete("/hapus_laporan_masalah/{id}", [LaporanController::class, 'destroy']);

        // PAKET HARGA JASA MUSIK
        Route::get("/paket_harga_jasa_musik/{id}", [PaketJasaMusikController::class, 'index']);
        Route::get("/fetch_paket_harga_jasa_musik", [PaketJasaMusikController::class, 'data_index']);
        Route::post("/add_paket_harga", [PaketJasaMusikController::class, 'store']);
        Route::delete("/hapus_paket_harga/{id}", [PaketJasaMusikController::class, 'destroy']);
        Route::post("/showByID_paket_harga/{id}", [PaketJasaMusikController::class, 'show']);
        Route::put("/edit_paket_harga/{id}", [PaketJasaMusikController::class, 'update']);

        // TUTORIAL PENGGUNAAN ALAT
        Route::get("/data_tutorial_alat", [TutorialPenggunaanAlatController::class, 'index_adm']);
        Route::get("/fetch_tutorial_alat", [TutorialPenggunaanAlatController::class, 'data_index']);
        Route::post("/add_tutorial_alat", [TutorialPenggunaanAlatController::class, 'store']);
        Route::delete("/hapus_tutorial_alat/{id}", [TutorialPenggunaanAlatController::class, 'destroy']);
    });

    Route::middleware('isUser')->group(function () {
        Route::get("/dashboard_user", [DashboardController::class, 'dashboard_user']);

        // DATA RUANGAN
        Route::get("/data_ruangan_studio", [DataRuanganUserController::class, 'index']);
        Route::get("/user_review_ruangan/{id}", [DataRuanganUserController::class, 'user_review_ruangan']);

        // JADWAL STUDIO SAYA
        Route::get("/jadwal_studio_saya", [UserJadwalStudioController::class, 'index']);
        Route::get("/fetch_jadwal_studio_saya", [UserJadwalStudioController::class, 'data_index']);

        // PESANAN JASA MUSIK SAYA
        Route::get("/pesanan_jasa_musik_saya", [UserPesananJasaMusikController::class, 'index']);
        Route::get("/fetch_jasa_musik_saya", [UserPesananJasaMusikController::class, 'data_index']);
        Route::post("/beri_rating_jasa/{id}", [UserPesananJasaMusikController::class, 'beri_rating_jasa']);
        Route::get("/list_data_jasa_musik", [UserPesananJasaMusikController::class, 'list_data_jasa_musik']);

        // DISLAY JASA MUSIK 
        Route::get("/pembuatan_jasa_musik/{id}", [DisplayJasaMusikController::class, 'pembuatan_jasa_musik']);
    });

    // ALL ROLE
    Route::get("/lihat_harga_sewa_studio", [PesananJadwalStudioController::class, 'lihat_harga_studio']);

    // RATING STUDIO
    Route::post("/beri_rating_studio/{id}", [UserJadwalStudioController::class, 'beri_rating_studio']);

    // JADWAL STUDIO
    Route::post("/add_pesanan_jadwal_studio", [PesananJadwalStudioController::class, 'store']);
    Route::post("/showById_pesanan_jadwal_studio/{id}", [PesananJadwalStudioController::class, 'show']);
    Route::post("/edit_pesanan_jadwal_studio/{id}", [PesananJadwalStudioController::class, 'update']);
    Route::delete("/hapus_pesanan_jadwal_studio/{id}", [PesananJadwalStudioController::class, 'destroy']);
    Route::post("/bayar_studio_musik", [PesananJadwalStudioController::class, 'bayar_studio_musik']);
    Route::post("/cek_tanggal_kosong", [PesananJadwalStudioController::class, 'cek_tanggal_kosong']);
    // Upload kondisi ruangan
    Route::post("/upload_img_kondisi_awal", [PesananJadwalStudioController::class, 'upload_img_kondisi_awal']);
    Route::post("/simpan_img_kondisi_ruangan/{id}", [PesananJadwalStudioController::class, 'simpan_img_kondisi_ruangan']);

    // PESANAN JASA MUSIK
    Route::post("/add_pesanan_jasa_musik", [PesananJasaMusikController::class, 'store']);
    Route::post("/showById_pesanan_jasa_musik/{id}", [PesananJasaMusikController::class, 'show']);
    Route::put("/edit_pesanan_jasa_musik/{id}", [PesananJasaMusikController::class, 'update']);
    Route::delete("/hapus_pesanan_jasa_musik/{id}", [PesananJasaMusikController::class, 'destroy']);
    Route::post("/select_paket_jasa/{id}", [PesananJasaMusikController::class, 'select_paket_jasa']);

    // LIST DATA RUANGAN
    Route::get("/list_data_ruangan", [DataRuanganController::class, 'list_data_ruangan']);

    // BAYARAN JADWAL
    Route::get("/get_snap_token", [UserJadwalStudioController::class, 'get_snap_token']);
    Route::post("/pembayaran_studio_sukses", [UserJadwalStudioController::class, 'pembayaran_studio_sukses']);
    Route::post("/pengembalian_ruangan", [UserJadwalStudioController::class, 'pengembalian_ruangan']);

    // BAYARAN JASA MUSIK
    Route::get("/get_snap_token_jasa", [UserPesananJasaMusikController::class, 'get_snap_token']);
    Route::post("/pembayaran_jasa_sukses", [UserPesananJasaMusikController::class, 'pembayaran_jasa_sukses']);

    // TUTORIAL PENGGUNAAN ALAT
    Route::get("/tutorial_penggunaan_alat", [TutorialPenggunaanAlatController::class, 'index_user']);
    Route::get("/detail_penggunaan_alat/{id}", [TutorialPenggunaanAlatController::class, 'detail_penggunaan_alat']);
    Route::post("/fetch_data_tutorial", [TutorialPenggunaanAlatController::class, 'data_index']);
});
