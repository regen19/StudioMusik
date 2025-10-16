<?php

use App\Http\Controllers\AdminUkmbs\PeminjamanController;
use Illuminate\Support\Facades\Route;

// ====== EXISTING CONTROLLERS (punya kamu) ======
use App\Http\Controllers\Admin\DataRuanganController;
use App\Http\Controllers\Admin\DataAlatController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MasterJasaMusikController;
use App\Http\Controllers\Admin\PaketJasaMusikController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesananJadwalStudioController;
use App\Http\Controllers\PesananJadwalAlatController;
use App\Http\Controllers\PesananJasaMusikController;
use App\Http\Controllers\TutorialPenggunaanAlatController;
use App\Http\Controllers\User\DataRuanganUserController;
use App\Http\Controllers\User\DataAlatUserController;
use App\Http\Controllers\User\DisplayJasaMusikController;
use App\Http\Controllers\User\UserJadwalStudioController;
use App\Http\Controllers\User\UserAlatDipinjamController;
use App\Http\Controllers\User\UserPesananJasaMusikController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\KetersediaanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\DetailPesananController;
// ====== NEW CONTROLLERS (fitur peminjaman yang kamu minta) ======
use App\Http\Controllers\User\PeminjamanController as UserPeminjamanController;
use App\Http\Controllers\AdminUkmbs\PeminjamanController as UkmbsPeminjamanController;
use App\Http\Controllers\AdminK3l\ApprovalController as K3lApprovalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//  Homepage
Route::get('/', [HomepageController::class, 'index'])->name('homepage');

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/authenticate', [AuthController::class, 'login'])->name('authenticate');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/forbidden_403', fn () => view('errors.forbidden_403'));
Route::get('/system_error_500', fn () => view('errors.system_error_500'));

Route::middleware('auth')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/profile_user', [AuthController::class, 'profile_user']);
    Route::post('/edit_profile/{id}', [AuthController::class, 'edit_profile']);
    Route::get('/peminjaman/{id}/detail', [PesananJadwalAlatController::class,'detail_json'])->name('pinjam-alat.detail');
    // Route::post('/simpan_img_kondisi_alat/{id}', [UserAlatDipinjamController::class, 'simpan_img_kondisi_alat'])->name('pinjam-alat.kondisi');


    /* ===== Notifikasi ===== */
    Route::get('/notifications', [NotificationController::class,'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class,'readAll'])->name('notifications.readAll');
    Route::post('/notifications/{id}/read', [NotificationController::class,'markRead'])->name('notifications.read');

    /*
    |--------------------------------------------------------------------------
    | SHARED VIEW (admin + k3l + ukmbs)
    | - Dashboard & Alat Musik (GET saja / view only)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin,k3l,ukmbs'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        // Alat Musik (view)
        Route::get('/data_alat', [DataAlatController::class, 'index'])->name('alat.index');
        Route::get('/fetch_data_alat', [DataAlatController::class, 'data_index'])->name('alat.data');


        Route::get('/data_peminjam_alat', [PesananJadwalAlatController::class, 'index'])->name('alat.peminjam');
        Route::get('/fetch_pesanan_pinjam_alat', [PesananJadwalAlatController::class, 'data_index'])->name('alat.peminjam.data');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA (isAdmin)
    | - Route modifikasi data (tetap admin only)
    |--------------------------------------------------------------------------
    */
    Route::middleware('isAdmin')->group(function () {

        // MASTER JASA MUSIK
        Route::get('/master_jasa_musik', [MasterJasaMusikController::class, 'index']);
        Route::get('/fetch_master_jasa_musik', [MasterJasaMusikController::class, 'data_index']);
        Route::post('/add_master_jasa_musik', [MasterJasaMusikController::class, 'store']);
        Route::post('/showById_master_jasa_musik/{id}', [MasterJasaMusikController::class, 'show']);
        Route::post('/edit_master_jasa_musik/{id}', [MasterJasaMusikController::class, 'update']);
        Route::delete('/hapus_master_jasa_musik/{id}', [MasterJasaMusikController::class, 'destroy']);

        // DATA RUANGAN STUDIO
        Route::get('/data_ruangan', [DataRuanganController::class, 'index']);
        Route::get('/fetch_data_ruangan', [DataRuanganController::class, 'data_index']);
        Route::post('/add_data_ruangan', [DataRuanganController::class, 'store']);
        Route::post('/showById_data_ruangan/{id}', [DataRuanganController::class, 'show']);
        Route::post('/edit_data_ruangan/{id}', [DataRuanganController::class, 'update']);
        Route::delete('/hapus_data_ruangan/{id}', [DataRuanganController::class, 'destroy']);

<<<<<<< Updated upstream
        // DATA ALAT MUSIK
        Route::get('/data_alat', [DataAlatController::class, 'index']);
        Route::get('/fetch_data_alat', [DataAlatController::class, 'data_index']);
=======
        // DATA ALAT MUSIK (MODIFY ONLY)
>>>>>>> Stashed changes
        Route::post('/add_data_alat', [DataAlatController::class, 'store']);
        Route::post('/showById_data_alat/{id}', [DataAlatController::class, 'show']);
        Route::post('/edit_data_alat/{id}', [DataAlatController::class, 'update']);
        Route::delete('/hapus_data_alat/{id}', [DataAlatController::class, 'destroy']);

        // PESANAN JADWAL STUDIO
        Route::get('/data_peminjam_ruangan', [PesananJadwalStudioController::class, 'index']);
        Route::get('/fetch_pesanan_jadwal_studio', [PesananJadwalStudioController::class, 'data_index']);
        Route::post('/status_pesanan_jadwal_studio/{id}', [PesananJadwalStudioController::class, 'status_pesanan_jadwal_studio']);
        Route::get('/lihat_harga_sewa_studio', [PesananJadwalStudioController::class, 'lihat_harga_studio']);

<<<<<<< Updated upstream
        // PESANAN JADWAL ALAT
        Route::get('/data_peminjam_alat', [PesananJadwalAlatController::class, 'index']);
        Route::get('/fetch_pesanan_pinjam_alat', [PesananJadwalAlatController::class, 'data_index']);
        Route::post('/status_pesanan_pinjam_alat/{id}', [PesananJadwalAlatController::class, 'status_pesanan_pinjam_alat']);
        // Route::post("/harga_sewa_Alat", [PesananJadwalAlatController::class, 'harga_sewa_Alat']);
=======
        // PESANAN JADWAL ALAT (MODIFY ONLY)
        Route::post('/status_pesanan_pinjam_alat/{id}', [PesananJadwalAlatController::class, 'status_pesanan_pinjam_alat']);
>>>>>>> Stashed changes
        Route::get('/lihat_harga_sewa_alat', [PesananJadwalAlatController::class, 'lihat_harga_alat']);

        // PESANAN JASA MUSIK
        Route::get('/pesanan_jasa_musik', [PesananJasaMusikController::class, 'index']);
        Route::get('/fetch_pesanan_jasa_musik', [PesananJasaMusikController::class, 'data_index']);
        Route::post('/status_pesanan_jasa_musik/{id}', [PesananJasaMusikController::class, 'status_pesanan_jasa_musik']);

        // LAPORAN
        Route::get('/laporan_admin', [LaporanController::class, 'index']);
        Route::get('/fetch_laporan_masalah', [LaporanController::class, 'data_index']);
        Route::post('/add_laporan_masalah', [LaporanController::class, 'store']);
        Route::delete('/hapus_laporan_masalah/{id}', [LaporanController::class, 'destroy']);

        // PAKET HARGA JASA MUSIK
        Route::get('/paket_harga_jasa_musik/{id}', [PaketJasaMusikController::class, 'index']);
        Route::get('/fetch_paket_harga_jasa_musik', [PaketJasaMusikController::class, 'data_index']);
        Route::post('/add_paket_harga', [PaketJasaMusikController::class, 'store']);
        Route::delete('/hapus_paket_harga/{id}', [PaketJasaMusikController::class, 'destroy']);
        Route::post('/showByID_paket_harga/{id}', [PaketJasaMusikController::class, 'show']);
        Route::put('/edit_paket_harga/{id}', [PaketJasaMusikController::class, 'update']);

        // TUTORIAL PENGGUNAAN ALAT
        Route::get('/data_tutorial_alat', [TutorialPenggunaanAlatController::class, 'index_adm']);
        Route::get('/fetch_tutorial_alat', [TutorialPenggunaanAlatController::class, 'data_index']);
        Route::post('/add_tutorial_alat', [TutorialPenggunaanAlatController::class, 'store']);
        Route::delete('/hapus_tutorial_alat/{id}', [TutorialPenggunaanAlatController::class, 'destroy']);

<<<<<<< Updated upstream
        // MANAGE USERS
        Route::get('/data_user', [ManageUserController::class, 'index']);
        Route::get('/fetch_data_user', [ManageUserController::class, 'data_index']);
        Route::post('/add_data_user', [ManageUserController::class, 'store']);
        Route::post('/showById_data_user/{id}', [ManageUserController::class, 'show']);
        Route::post('/edit_data_user/{id}', [ManageUserController::class, 'update']);
        Route::delete('/hapus_data_user/{id}', [ManageUserController::class, 'destroy']);                 
});
=======
    // =========================================
// 👤 ROUTE UNTUK MANAJEMEN USER (Admin)
// =========================================
        // Manajemen User
        Route::get('/data_user', [ManageUserController::class, 'index']);
        Route::get('/fetch_data_user', [ManageUserController::class, 'data_index']);
        Route::post('/add_data_user', [ManageUserController::class, 'store']);
        Route::post('/showById_data_user/{id_user}', [ManageUserController::class, 'showById']);
        Route::put('/edit_data_user/{id_user}', [ManageUserController::class, 'update']);
        Route::delete('/hapus_data_user/{id_user}', [ManageUserController::class, 'destroy']);
>>>>>>> Stashed changes

    });
    




    /*
    |--------------------------------------------------------------------------
    | ADMIN K3L
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:k3l'])
        ->prefix('k3l')
        ->as('k3l.')
        ->group(function () {
            Route::get('peminjaman', [K3lApprovalController::class, 'index'])
                ->name('peminjaman.index');

            Route::get('alat',   [K3lApprovalController::class, 'alat'])->name('alat');
            Route::get('jadwal', [\App\Http\Controllers\AdminK3l\ApprovalController::class, 'jadwal'])->name('jadwal');

            Route::post('peminjaman/{id}/approve', [K3lApprovalController::class, 'approve'])
                ->name('peminjaman.approve');
            Route::post('peminjaman/{id}/reject',  [K3lApprovalController::class, 'reject'])
                ->name('peminjaman.reject');
        });

    /*
    |--------------------------------------------------------------------------
    | ADMIN UKMBS
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:ukmbs'])
        ->prefix('ukmbs')
        ->as('ukmbs.')
        ->group(function () {
            Route::get('peminjaman', [UkmbsPeminjamanController::class, 'index'])
                ->name('peminjaman.index');
            Route::post('pengembalian/{id}', [UkmbsPeminjamanController::class, 'pengembalian'])
                ->name('pengembalian');
            Route::get('peminjaman/kondisi', [PesananJadwalAlatController::class,'kondisi_index'])
                ->name('peminjaman.kondisi.index');
            Route::post('peminjaman/{id}/kondisi', [PesananJadwalAlatController::class,'simpan_img_kondisi_alat'])
                ->name('peminjaman.kondisi.upload');
            Route::get('peminjaman/export', [PeminjamanController::class, 'export'])
                ->name('peminjaman.export');
           
        });

    // Route::middleware('ukmbs')->group(function () {
    //     // DATA ALAT MUSIK (MODIFY ONLY)
    //     Route::post('/add_data_alat', [DataAlatController::class, 'store']);
    //     Route::post('/showById_data_alat/{id}', [DataAlatController::class, 'show']);
    //     Route::post('/edit_data_alat/{id}', [DataAlatController::class, 'update']);
    //     Route::delete('/hapus_data_alat/{id}', [DataAlatController::class, 'destroy']);
    // });
    /*
    |--------------------------------------------------------------------------
    | USER AREA (isUser)
    |--------------------------------------------------------------------------
    */
    Route::middleware('isUser')->group(function () {
        Route::get('/dashboard_user', [DashboardController::class, 'dashboard_user']);

        // DATA RUANGAN (tetap)
        Route::get('/data_ruangan_studio', [DataRuanganUserController::class, 'index']);
        Route::get('/user_review_ruangan/{id}', [DataRuanganUserController::class, 'user_review_ruangan']);

<<<<<<< Updated upstream
        // DATA ALAT
        Route::get('/data_alat_user', [DataAlatUserController::class, 'index']);
        Route::get('/user_review_alat/{id}', [DataAlatUserController::class, 'user_review_alat']);

        // JADWAL STUDIO SAYA
        Route::get("/jadwal_studio_saya", [UserJadwalStudioController::class, 'index']);
        Route::get("/fetch_jadwal_studio_saya", [UserJadwalStudioController::class, 'data_index']);

        // ALAT DIPINJAM
        Route::get("/alat_dipinjam", [UserAlatDipinjamController::class, 'index']);
        Route::get("/fetch_alat_dipinjam", [UserAlatDipinjamController::class, 'data_index']);

        // PESANAN JASA MUSIK SAYA
=======
        // DATA ALAT (tetap)
        Route::get('/data_alat_user', [DataAlatUserController::class, 'index']);
        Route::get('/user_review_alat/{id}', [DataAlatUserController::class, 'user_review_alat']);

        // JADWAL STUDIO SAYA (tetap)
        Route::get('/jadwal_studio_saya', [UserJadwalStudioController::class, 'index']);
        Route::get('/fetch_jadwal_studio_saya', [UserJadwalStudioController::class, 'data_index']);

        // ALAT DIPINJAM (tetap)
        Route::get('/alat_dipinjam', [UserAlatDipinjamController::class, 'index']);
        Route::get('/fetch_alat_dipinjam', [UserAlatDipinjamController::class, 'data_index']);

        // BATALKAN PENGAJUAN (USER)
        Route::delete('/pesanan-pinjam-alat/{id}', [PesananJadwalAlatController::class, 'cancel'])->name('pesanan.cancel');

        // DETAIL ALAT DIPINJAM
        Route::match(['GET','POST'], '/showById_pesanan_pinjam_alat/{id}', [UserAlatDipinjamController::class, 'show'])->name('pinjam.show');

        // PESANAN JASA MUSIK SAYA (tetap)
>>>>>>> Stashed changes
        Route::get('/pesanan_jasa_musik_saya', [UserPesananJasaMusikController::class, 'index']);
        Route::get('/fetch_jasa_musik_saya', [UserPesananJasaMusikController::class, 'data_index']);
        Route::post('/beri_rating_jasa/{id}', [UserPesananJasaMusikController::class, 'beri_rating_jasa']);
        Route::get('/list_data_jasa_musik', [UserPesananJasaMusikController::class, 'list_data_jasa_musik']);
        Route::get('/informasi_jasa_musik/{id}', [UserPesananJasaMusikController::class, 'informasi_jasa_musik']);

        // DISPLAY JASA MUSIK (tetap)
        Route::get('/pembuatan_jasa_musik/{id}', [DisplayJasaMusikController::class, 'pembuatan_jasa_musik']);

        // === Peminjaman Alat (USER) (tetap)
        Route::prefix('user')->group(function () {
            Route::resource('peminjaman', UserPeminjamanController::class)->only(['index','create','store','show','destroy']);
            Route::get('ketersediaan', [UserPeminjamanController::class, 'ketersediaan'])->name('user.ketersediaan');
        });

        // USER: simpan review (user boleh review saja)
        Route::post('/pinjam-alat/{id}/review', [PesananJadwalAlatController::class,'simpan_review'])->name('pinjam-alat.review');
    });

    /*
    |--------------------------------------------------------------------------
    | ALL ROLE
    |--------------------------------------------------------------------------
    */

    // RATING STUDIOSS
    Route::post('/beri_rating_studio/{id}', [UserJadwalStudioController::class, 'beri_rating_studio']);

    // JADWAL STUDIO
    Route::post('/add_pesanan_jadwal_studio', [PesananJadwalStudioController::class, 'store']);
    Route::post('/showById_pesanan_jadwal_studio/{id}', [PesananJadwalStudioController::class, 'show']);
    Route::post('/edit_pesanan_jadwal_studio/{id}', [PesananJadwalStudioController::class, 'update']);
    Route::delete('/hapus_pesanan_jadwal_studio/{id}', [PesananJadwalStudioController::class, 'destroy']);
    Route::post('/bayar_studio_musik', [PesananJadwalStudioController::class, 'bayar_studio_musik']);
    Route::post('/cek_tanggal_kosong', [PesananJadwalStudioController::class, 'cek_tanggal_kosong']);
<<<<<<< Updated upstream
    
    // JADWAL ALAT
    Route::post('/add_pesanan_pinjam_alat', [UserAlatDipinjamController::class, 'store']);
    Route::post('/showById_pesanan_pinjam_alat/{id}', [UserAlatDipinjamController::class, 'show']);
=======

    // JADWAL ALAT
    Route::post('/add_pesanan_pinjam_alat', [PesananJadwalAlatController::class, 'store']);
>>>>>>> Stashed changes
    Route::post('/edit_pesanan_pinjam_alat/{id}', [UserAlatDipinjamController::class, 'update']);
    Route::delete('/hapus_pesanan_pinjam_alat/{id}', [UserAlatDipinjamController::class, 'destroy']);
    Route::post('/bayar_alat_musik', [UserAlatDipinjamController::class, 'bayar_alat_musik']);
    Route::post('/cek_tanggal_kosong', [UserAlatDipinjamController::class, 'cek_tanggal_kosong']);

    // Upload kondisi ruangan
    Route::post('/upload_img_kondisi_awal', [PesananJadwalStudioController::class, 'upload_img_kondisi_awal']);
    Route::post('/simpan_img_kondisi_ruangan/{id}', [PesananJadwalStudioController::class, 'simpan_img_kondisi_ruangan']);

    // PESANAN JASA MUSIK
    Route::post('/add_pesanan_jasa_musik', [PesananJasaMusikController::class, 'store']);
    Route::post('/showById_pesanan_jasa_musik/{id}', [PesananJasaMusikController::class, 'show']);
    Route::get('/informasi_pesanan_jasa_musik/{id}', [PesananJasaMusikController::class, 'informasi_pesanan_jasa_musik']);
    Route::get('/download_file_pesanan/{filename}', [PesananJasaMusikController::class, 'download_file_pesanan_jasa_musik']);
    Route::put('/edit_pesanan_jasa_musik/{id}', [PesananJasaMusikController::class, 'update']);
    Route::delete('/hapus_pesanan_jasa_musik/{id}', [PesananJasaMusikController::class, 'destroy']);
    Route::post('/select_paket_jasa/{id}', [PesananJasaMusikController::class, 'select_paket_jasa']);

    // LIST DATA RUANGAN/ALAT (umum)
    Route::get('/list_data_ruangan', [DataRuanganController::class, 'list_data_ruangan']);
    Route::get('/list_data_alat', [DataAlatController::class, 'list_data_alat']);

    // LIST DATA ALAT
    Route::get('/list_data_alat', [DataAlatController::class, 'list_data_alat']);

    // BAYARAN JADWAL
    Route::get('/get_snap_token', [UserJadwalStudioController::class, 'get_snap_token']);
    Route::post('/pembayaran_studio_sukses', [UserJadwalStudioController::class, 'pembayaran_studio_sukses']);
    Route::post('/pengembalian_ruangan', [UserJadwalStudioController::class, 'pengembalian_ruangan']);

    // BAYARAN BIAYA PERAWATAN
<<<<<<< Updated upstream
    Route::get('/get_snap_token', [UserJadwalStudioController::class, 'get_snap_token']);
    Route::post('/pembayaran_biaya_perawatan_sukses', [UserJadwalStudioController::class, 'pembayaran_biaya_perawatan_sukses']);
=======
    Route::get('/get_snap_token_alat', [UserAlatDipinjamController::class, 'get_snap_token'])->name('alat.snap');
    Route::post('/pembayaran_studio_sukses', [UserJadwalStudioController::class, 'pembayaran_studio_sukses']);
    Route::post('/pembayaran_biaya_perawatan_sukses', [UserAlatDipinjamController::class, 'pembayaran_biaya_perawatan'])->name('alat.payment.success');
>>>>>>> Stashed changes
    Route::post('/pengembalian_alat', [UserJadwalStudioController::class, 'pengembalian_alat']);

    // BAYARAN JASA MUSIK
    Route::get('/get_snap_token_jasa', [UserPesananJasaMusikController::class, 'get_snap_token']);
    Route::post('/pembayaran_jasa_sukses', [UserPesananJasaMusikController::class, 'pembayaran_jasa_sukses']);

    // TUTORIAL PENGGUNAAN ALAT (user)
    Route::get('/tutorial_penggunaan_alat', [TutorialPenggunaanAlatController::class, 'index_user']);
    Route::get('/detail_penggunaan_alat/{id}', [TutorialPenggunaanAlatController::class, 'detail_penggunaan_alat']);
    Route::post('/fetch_data_tutorial', [TutorialPenggunaanAlatController::class, 'data_index']);
<<<<<<< Updated upstream
;
=======

    // Jadwal Alat
    Route::get('/jadwal-alat', [KetersediaanController::class, 'page'])->name('jadwal.alat');

    // Ketersediaan alat
    Route::get('/ketersediaan',       [KetersediaanController::class, 'page'])->name('ketersediaan.page');
    Route::get('/ketersediaan/data',  [KetersediaanController::class, 'data'])->name('ketersediaan.data');

    // === PINJAM ALAT (ALL ROLE)
    Route::post('/pinjam-alat/{id}/selesai', [UserAlatDipinjamController::class, 'selesai'])->name('pinjam-alat.selesai');
}); // end auth group
>>>>>>> Stashed changes
