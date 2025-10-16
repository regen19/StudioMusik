<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageUserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat akun admin agar lolos middleware isAdmin
        $this->admin = User::factory()->create([
            'user_role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        $this->actingAs($this->admin);
    }

    /** @test */
    public function admin_bisa_mengakses_halaman_data_user()
    {
        $response = $this->get('/data_user');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_bisa_mendapatkan_data_user_via_ajax()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/fetch_data_user');
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_bisa_menambah_user_baru()
    {
        $data = [
            'username' => 'testuser',
            'no_wa' => '08123456789',
            'email' => 'test@example.com',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/add_data_user', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    /** @test */
    public function validasi_gagal_saat_tambah_user_tanpa_email()
    {
        $data = [
            'username' => 'invaliduser',
            'no_wa' => '08123456789',
            'password' => 'secret123',
        ];

        $response = $this->postJson('/add_data_user', $data);
        $response->assertStatus(422);
    }

    /** @test */
    public function admin_bisa_melihat_detail_user()
    {
        $user = User::factory()->create();

        $response = $this->postJson("/showById_data_user/{$user->id_user}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['email' => $user->email]);
    }

    /** @test */
    public function admin_bisa_update_data_user_tanpa_ganti_password()
    {
        $user = User::factory()->create(['user_role' => 'user']);

        $data = [
            'username' => 'Updated User',
            'no_wa' => '081234567890',
            'email' => 'updated@example.com',
            // tidak mengirim password agar tetap sama
        ];

        $response = $this->putJson("/edit_data_user/{$user->id_user}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
    }

    /** @test */
    public function admin_bisa_update_data_user_dengan_password_baru()
    {
        $user = User::factory()->create(['user_role' => 'user']);

        $data = [
            'username' => 'UpdatedPassUser',
            'no_wa' => '081234567890',
            'email' => 'updatedpass@example.com',
            'password' => 'newpassword123',
        ];

        $response = $this->putJson("/edit_data_user/{$user->id_user}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', ['email' => 'updatedpass@example.com']);
    }

    /** @test */
    public function admin_bisa_menghapus_user()
    {
        $user = User::factory()->create(['user_role' => 'user']);

        $response = $this->deleteJson("/hapus_data_user/{$user->id_user}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id_user' => $user->id_user]);
    }
}
