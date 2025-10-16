<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\PesananPinjamAlat;
use App\Models\DetailPesananPinjamAlat;
use App\Models\Admin\DataAlatModel as DataAlat;
use App\Notifications\PeminjamanApproved;
use App\Notifications\PeminjamanRejected;
use App\Notifications\PeminjamanStatusNotification;
use App\Notifications\BarangRestock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Http;

class PeminjamanIterasi3Test extends TestCase
{
    use RefreshDatabase;

    // --- SETUP DATA ---
    
    protected function setUp(): void
    {
        parent::setUp();

        // 1. Mocking/Faking external services
        Notification::fake();
        Http::fake();

        // 2. Buat User Roles (ID di-handle otomatis oleh Factory)
        $this->user = User::factory()->create(['user_role' => 'user', 'no_wa' => '08123456789']);
        $this->k3l = User::factory()->create(['user_role' => 'k3l', 'username' => 'admin_k3l', 'no_wa' => '08111111111']);
        $this->ukmbs = User::factory()->create(['user_role' => 'ukmbs', 'username' => 'admin_ukmbs', 'no_wa' => '08222222222']);
        $this->admin = User::factory()->create(['user_role' => 'admin', 'username' => 'super_admin', 'no_wa' => '08333333333']);

        // 3. Data Alat Musik Tersedia
        $this->alat = DataAlat::create([
            'id_alat' => 1,
            'nama_alat' => 'Gitar Akustik Yamaha',
            'tipe_alat' => 'Gitar',
            'jumlah_alat' => 5, // Stok Awal
            'status' => 'Tersedia',
            'biaya_perawatan' => 5000,
        ]);
        
        // 4. Buat Pesanan Pinjam status 'P' (Pending)
        $this->pesanan = PesananPinjamAlat::create([
            'id_user' => $this->user->id_user,
            'status_persetujuan' => 'P',
            'status_pengembalian' => 'N',
            'tgl_pinjam' => now()->addDay()->format('Y-m-d'),
            'tgl_kembali' => now()->addDays(2)->format('Y-m-d'),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '10:00:00',
            'ket_keperluan' => 'Penting',
            'foto_jaminan' => 'jaminan.jpg',
            'ket_admin' => '', // PERBAIKAN: Menambahkan field ket_admin agar NOT NULL tidak dilanggar
        ]);

        // 5. Buat Detail Pesanan (Pinjam 2 unit)
        $this->detail = DetailPesananPinjamAlat::create([
            'id_pesanan_pinjam_alat' => $this->pesanan->id_pesanan_pinjam_alat,
            'id_alat' => $this->alat->id_alat,
            'jumlah' => 2, // Jumlah yang dipinjam
            'status_persetujuan' => 'P',
            'status_peminjaman' => 'P',
            'biaya_perawatan' => 0,
        ]);

        // 6. Rute untuk approval dan rejection (MENGGUNAKAN NAMA RUTE YANG BENAR)
        $this->approveRoute = route('k3l.peminjaman.approve', ['id' => $this->pesanan->id_pesanan_pinjam_alat]);
        $this->rejectRoute = route('k3l.peminjaman.reject', ['id' => $this->pesanan->id_pesanan_pinjam_alat]);
        $this->pengembalianRoute = route('ukmbs.pengembalian', ['id' => $this->pesanan->id_pesanan_pinjam_alat]);
    }

    // ----------------------------------------------------------------------
    // TEST 1: US.PJM.4 - Persetujuan (Approve) Berhasil
    // ----------------------------------------------------------------------

    /** @test */
    public function admin_k3l_berhasil_menyetujui_peminjaman_yang_pending()
    {
        $stok_awal = $this->alat->jumlah_alat;
        
        $response = $this->actingAs($this->k3l)->post($this->approveRoute);

        // 1. Assert Response & Redirect
        $response->assertSessionHas('success', 'Peminjaman disetujui.');
        $response->assertRedirect();

        // 2. Assert Status di DB
        $this->pesanan->refresh();
        $this->detail->refresh();
        $this->assertEquals('Y', $this->pesanan->status_persetujuan);
        $this->assertEquals('Y', $this->detail->status_persetujuan);

        // 3. Assert Stok (Memastikan logic pengurangan stok berfungsi)
        $this->alat->refresh();
        $stok_sekarang = $stok_awal - $this->detail->jumlah; // 5 - 2 = 3
        $this->assertEquals($stok_sekarang, $this->alat->jumlah_alat);
    }

    // ----------------------------------------------------------------------
    // TEST 2: US.NOT.2 - Notifikasi Terkirim Setelah Persetujuan
    // ----------------------------------------------------------------------

    /** @test */
    public function notifikasi_terkirim_ke_user_dan_admin_setelah_persetujuan()
    {
        $this->actingAs($this->k3l)->post($this->approveRoute);
        
        $targets = User::whereIn('user_role', ['admin', 'k3l', 'ukmbs'])->get();
        
        // 1. Assert Laravel Notifications
        Notification::assertSentTo($this->user, PeminjamanApproved::class);
        Notification::assertSentTo($targets, PeminjamanApproved::class);

        // 2. Assert HTTP/WA (Fonnte) API Calls ke User
        Http::assertSent(fn ($request) => 
            $request['target'] === $this->user->no_wa &&
            str_contains($request['message'], 'Request anda sudah di approve')
        );

        // 3. Assert HTTP/WA (Fonnte) API Calls ke Admin UKMBS
        Http::assertSent(fn ($request) => 
            $request['target'] === $this->ukmbs->no_wa &&
            str_contains($request['message'], 'sudah di approve')
        );
    }
    
    // ----------------------------------------------------------------------
    // TEST 3: US.PJM.4 - Penolakan (Reject) Berhasil
    // ----------------------------------------------------------------------

    /** @test */
    public function admin_k3l_berhasil_menolak_peminjaman_yang_pending()
    {
        $rejectionData = ['ket_admin' => 'Alasan Penolakan: Alat harus digunakan untuk kegiatan resmi lainnya.'];
        
        $response = $this->actingAs($this->k3l)->post($this->rejectRoute, $rejectionData);

        // 1. Assert Response & Redirect
        $response->assertSessionHas('success', 'Peminjaman ditolak.');
        $response->assertRedirect();

        // 2. Assert Status di DB
        $this->pesanan->refresh();
        $this->assertEquals('N', $this->pesanan->status_persetujuan);
        $this->assertEquals($rejectionData['ket_admin'], $this->pesanan->ket_admin);
    }
    
    // ----------------------------------------------------------------------
    // TEST 4: US.NOT.2 - Notifikasi Terkirim Setelah Penolakan
    // ----------------------------------------------------------------------

    /** @test */
    public function notifikasi_terkirim_ke_user_dan_admin_setelah_penolakan()
    {
        $rejectionData = ['ket_admin' => 'Stok tidak ada.'];
        $this->actingAs($this->k3l)->post($this->rejectRoute, $rejectionData);
        
        $targets = User::whereIn('user_role', ['admin', 'k3l', 'ukmbs'])->get();
        
        // 1. Assert Laravel Notifications
        Notification::assertSentTo($this->user, PeminjamanRejected::class);
        Notification::assertSentTo($targets, PeminjamanRejected::class);

        // 2. Assert HTTP/WA (Fonnte) API Calls
        Http::assertSent(fn ($request) => 
            $request['target'] === $this->user->no_wa &&
            str_contains($request['message'], 'Request anda ditolak')
        );
    }

    // ----------------------------------------------------------------------
    // TEST 5: US.PJM.4 - Penolakan Gagal (Validasi Alasan)
    // ----------------------------------------------------------------------
    
    /** @test */
    public function penolakan_gagal_jika_alasan_admin_tidak_disediakan()
    {
        // Data tanpa 'ket_admin' (atau string kosong)
        $response = $this->actingAs($this->k3l)->post($this->rejectRoute, ['ket_admin' => '']);

        $response->assertStatus(302); 
        $response->assertSessionHasErrors(['ket_admin']);

        // Pastikan status peminjaman TIDAK BERUBAH
        $this->pesanan->refresh();
        $this->assertEquals('P', $this->pesanan->status_persetujuan);
    }
    
    // ----------------------------------------------------------------------
    // TEST 6: US.PJM.4 - Approve/Reject Gagal (Status Bukan Pending)
    // ----------------------------------------------------------------------

    /** @test */
    public function approve_dan_reject_gagal_jika_status_bukan_pending()
    {
        // 1. Ubah status menjadi 'Y' (sudah diproses)
        $this->pesanan->status_persetujuan = 'Y';
        $this->pesanan->save();

        // Coba Approve (harusnya gagal 400)
        $responseApprove = $this->actingAs($this->k3l)->post($this->approveRoute);
        $responseApprove->assertStatus(400); 

        // Coba Reject (harusnya gagal 400)
        $responseReject = $this->actingAs($this->k3l)->post($this->rejectRoute, ['ket_admin' => 'alasan']);
        $responseReject->assertStatus(400); 
    }

    // ----------------------------------------------------------------------
    // TEST 7: US.PJM.4 - Pengembalian Berhasil
    // ----------------------------------------------------------------------

    /** @test */
    public function admin_ukmbs_berhasil_mencatat_pengembalian_alat()
    {
        // 1. SETUP: Pesanan harus disetujui ('Y') terlebih dahulu
        $this->pesanan->update(['status_persetujuan' => 'Y']);
        $this->detail->update(['status_persetujuan' => 'Y']);
        
        // Simulasikan stok berkurang setelah approval (stok_awal 5 - pinjam 2 = 3)
        $stok_sebelum_kembali = $this->alat->jumlah_alat - $this->detail->jumlah;
        $this->alat->update(['jumlah_alat' => $stok_sebelum_kembali]); 

        // 2. ACTION: Admin UKMBS mencatat pengembalian
        $response = $this->actingAs($this->ukmbs)->post($this->pengembalianRoute);

        // 3. ASSERTION
        $response->assertSessionHas('success', 'Pengembalian dicatat & stok diperbarui');
        $response->assertRedirect();

        // Cek DB Status Pengembalian
        $this->pesanan->refresh();
        $this->assertEquals('Y', $this->pesanan->status_pengembalian);

        // Cek Stok: Stok harus bertambah kembali (3 + 2 = 5)
        $this->alat->refresh();
        $stok_sekarang = $this->alat->jumlah_alat;
        $this->assertEquals(5, $stok_sekarang);
        
        // Cek Notifikasi BarangRestock (menggunakan filtered target)
        $targets_restock = User::whereIn('user_role', ['admin', 'k3l', 'ukmbs'])
                            ->where('id_user', '!=', $this->ukmbs->id_user) // Admin UKMBS yang sedang login dikecualikan
                            ->get();
        
        Notification::assertSentTo($targets_restock, \App\Notifications\BarangRestock::class);
        
        // Cek Notifikasi User
        Notification::assertSentTo($this->user, \App\Notifications\PeminjamanStatusNotification::class);
    }
}