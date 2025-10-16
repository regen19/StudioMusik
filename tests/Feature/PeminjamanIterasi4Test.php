<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Exports\DataPeminjamExport;
use Illuminate\Support\Facades\File;
use Mockery;

// Pastikan use ini dihapus jika menyebabkan bentrok: use Illuminate\Support\Facades\Excel; 

class PeminjamanIterasi4Test extends TestCase
{
    // Gunakan RefreshDatabase untuk memastikan database bersih
    use RefreshDatabase;

    // --- SETUP DATA ---
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Mocking Excel Facade (menggunakan FQCN untuk menghindari bentrok)
        \Maatwebsite\Excel\Facades\Excel::fake(); 

        // 1. Admin Roles
        $this->admin = User::factory()->create(['user_role' => 'admin']);
        $this->ukmbs = User::factory()->create(['user_role' => 'ukmbs']);
        
        // 2. User yang akan dihapus (Hanya data DB)
        $this->userToDelete = User::factory()->create([
            'user_role' => 'user', 
            'foto_user' => 'user_profile_123.jpg',
        ]);
        
        // 3. User lain yang tidak punya izin
        $this->guestUser = User::factory()->create(['user_role' => 'user']);
        
        // CATATAN PENTING: Logic File::put/File::exists/File::delete Dihapus dari sini.
        // I/O akan di-mock di dalam setiap test.
    }
    
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    // ======================================================================
    // US.AKN.3: MANAJEMEN AKUN
    // ======================================================================

    /** @test */
    public function admin_berhasil_menghapus_user_dan_memanggil_file_delete()
    {
        $id_hapus = $this->userToDelete->id_user;
        $file_name = $this->userToDelete->foto_user;
        $file_path_expected = public_path('storage/img_upload/data_user/' . $file_name);
        
        // MOCKING: Memalsukan I/O
        File::shouldReceive('exists')
            ->once()
            ->with($file_path_expected)
            ->andReturn(true); 
            
        File::shouldReceive('delete')
            ->once()
            ->with($file_path_expected)
            ->andReturn(true); 
        
        // Action
        $response = $this->actingAs($this->admin)
                         ->delete("/hapus_data_user/{$id_hapus}"); 

        // Assertions
        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id_user' => $id_hapus]);
    }
    
    /** @test */
    public function admin_gagal_menghapus_user_yang_tidak_ditemukan()
    {
        // MOCKING: Pastikan File::delete tidak dipanggil
        File::shouldReceive('exists')->never()->andReturn(true);
        File::shouldReceive('delete')->never();
        
        $id_fiktif = 99999;
        
        $response = $this->actingAs($this->admin)
                         ->delete("/hapus_data_user/{$id_fiktif}"); 

        $response->assertStatus(404);
    }

    /** @test */
    public function role_non_admin_gagal_menghapus_user()
    {
        // MOCKING: Pastikan File::delete tidak dipanggil
        File::shouldReceive('exists')->andReturn(true);
        File::shouldReceive('delete')->never();
        
        $id_hapus = $this->userToDelete->id_user;
        
        $response = $this->actingAs($this->guestUser)
                         ->delete("/hapus_data_user/{$id_hapus}"); 

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['id_user' => $id_hapus]);
    }

    // ======================================================================
    // US.ARS.1 & US.ARS.2: LAPORAN DAN FILTER
    // ======================================================================

    /** @test */
    public function admin_berhasil_mengunduh_laporan_tanpa_filter_arsip_default()
    {
        $response = $this->actingAs($this->ukmbs)
        ->get(route('ukmbs.peminjaman.export'));

        // Tentukan tahun saat ini secara eksplisit untuk nama file dan isi
        $currentYear = date('Y'); 

        // Assertion di sini diubah: 
        \Maatwebsite\Excel\Facades\Excel::assertDownloaded('Data_Peminjam_Semua_Bulan_'.$currentYear.'.xlsx', 
        function (DataPeminjamExport $export) use ($currentYear) {
        // Pastikan bulan adalah NULL
        $this->assertNull($export->bulan);
        // Pastikan tahun adalah tahun saat ini
        $this->assertEquals($currentYear, $export->tahun);
        return true;
        }
        );

        $response->assertStatus(200);
    }

    /** @test */
    public function admin_berhasil_mengunduh_laporan_dengan_filter_arsip_bulan_dan_tahun()
    {
        $bulan = 5; 
        $tahun = 2024;
        
        $response = $this->actingAs($this->ukmbs)
                         ->get(route('ukmbs.peminjaman.export', [
                             'bulan' => $bulan,
                             'tahun' => $tahun
                         ]));

        // Menggunakan FQCN untuk Excel
        \Maatwebsite\Excel\Facades\Excel::assertDownloaded('Data_Peminjam_Mei_2024.xlsx', 
            function (DataPeminjamExport $export) use ($bulan, $tahun) {
                
                // PHPDOC untuk IDE
                /** @var \App\Exports\DataPeminjamExport $export */ 
                
                $this->assertEquals($bulan, $export->bulan);
                $this->assertEquals($tahun, $export->tahun);
                return true;
            }
        );

        $response->assertStatus(200);
    }
}