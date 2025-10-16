<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Admin\DataAlatModel;

class DataAlatControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function bisa_menambahkan_data_alat_dengan_upload_foto()
    {
        // Buat user admin palsu untuk lolos middleware isAdmin
        $user = User::factory()->create(['user_role' => 'admin']);
        $this->actingAs($user);

        Storage::fake('public');
        $file = UploadedFile::fake()->image('alat.jpg');

        $response = $this->postJson('/add_data_alat', [
            'nama_alat' => 'Drum Set',
            'tipe_alat' => 'Pukul',
            'jumlah_alat' => 5,
            'biaya_perawatan' => 15000,
            'foto_alat' => $file,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['msg' => 'Data alat berhasil disimpan']);

        $this->assertDatabaseHas('data_alat', [
            'nama_alat' => 'Drum Set',
            'tipe_alat' => 'Pukul',
        ]);
    }

    /** @test */
    public function bisa_melihat_detail_data_alat()
    {
        $user = User::factory()->create(['user_role' => 'admin']);
        $this->actingAs($user);

        $alat = DataAlatModel::factory()->create();

        $response = $this->postJson("/showById_data_alat/{$alat->id_alat}");

        $response->assertStatus(200)
                 ->assertJsonFragment([
                     'id_alat' => $alat->id_alat,
                     'nama_alat' => $alat->nama_alat,
                 ]);
    }

    /** @test */
    public function bisa_memperbarui_data_dan_ganti_foto()
    {
        $user = User::factory()->create(['user_role' => 'admin']);
        $this->actingAs($user);

        Storage::fake('public');

        $alat = DataAlatModel::factory()->create([
            'nama_alat' => 'Saxophone',
            'tipe_alat' => 'Tiup',
        ]);

        $newFile = UploadedFile::fake()->image('alat_baru.png');

        $response = $this->postJson("/edit_data_alat/{$alat->id_alat}", [
            'nama_alat' => 'Saxophone Silver',
            'tipe_alat' => 'Tiup',
            'jumlah_alat' => 5,
            'biaya_perawatan' => 25000,
            'foto_alat' => $newFile,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['msg' => 'Data alat berhasil diperbarui']);

        $this->assertDatabaseHas('data_alat', [
            'id_alat' => $alat->id_alat,
            'nama_alat' => 'Saxophone Silver',
        ]);
    }

    /** @test */
    public function bisa_menghapus_data_alat_beserta_fotonya()
    {
        $user = User::factory()->create(['user_role' => 'admin']);
        $this->actingAs($user);

        Storage::fake('public');

        $filename = time() . '-hapus_gitar.jpg';
        $alat = DataAlatModel::factory()->create([
            'nama_alat' => 'Gitar Elektrik',
            'foto_alat' => $filename,
        ]);

        Storage::disk('public')->put('img_upload/data_alat/' . $filename, 'fake content');

        $response = $this->deleteJson("/hapus_data_alat/{$alat->id_alat}");

        $response->assertStatus(200)
                 ->assertJson(['msg' => 'Data berhasil dihapus']);

        $this->assertDatabaseMissing('data_alat', [
            'id_alat' => $alat->id_alat,
        ]);

        Storage::disk('public')->assertMissing('img_upload/data_alat/' . $filename);
    }
}
