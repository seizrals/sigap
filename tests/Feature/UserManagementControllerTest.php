<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_see_user_management_menu_and_page(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-users',
            'role' => User::ROLE_ADMIN,
        ]);

        $dashboardResponse = $this->actingAs($admin)->get(route('dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertSee(route('users.index'));
        $dashboardResponse->assertSee('Manajemen Pengguna');

        $indexResponse = $this->actingAs($admin)->get(route('users.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Daftar Pengguna');
        $indexResponse->assertSee('Tambah Pengguna');
        $indexResponse->assertSee('Pejabat OPD/Instansi');
    }

    public function test_admin_can_create_kecamatan_user(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-create-user',
            'role' => User::ROLE_ADMIN,
        ]);

        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505999',
            'nama_kecamatan' => 'Monano',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Operator Monano',
            'username' => 'operator-monano',
            'email' => 'operator.monano@example.com',
            'role' => User::ROLE_KECAMATAN,
            'kecamatan_id' => $kecamatan->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Operator Monano',
            'username' => 'operator-monano',
            'role' => User::ROLE_KECAMATAN,
            'kecamatan_id' => $kecamatan->id,
        ]);
    }

    public function test_kecamatan_is_not_required_for_admin_or_opd(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-create-opd',
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->post(route('users.store'), [
            'name' => 'Pejabat Dinas',
            'username' => 'pejabat-opd',
            'email' => 'pejabat.opd@example.com',
            'role' => User::ROLE_OPD,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Pejabat Dinas',
            'username' => 'pejabat-opd',
            'role' => User::ROLE_OPD,
            'kecamatan_id' => null,
        ]);
    }

    public function test_kecamatan_role_requires_wilayah(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-require-wilayah',
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->from(route('users.index'))->actingAs($admin)->post(route('users.store'), [
            'name' => 'Operator Tanpa Wilayah',
            'username' => 'operator-tanpa-wilayah',
            'email' => 'operator.tanpa.wilayah@example.com',
            'role' => User::ROLE_KECAMATAN,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasErrors([
            'kecamatan_id' => 'Wilayah kecamatan wajib dipilih untuk user per wilayah.',
        ]);
    }

    public function test_admin_can_update_user_role_and_clear_wilayah_when_not_needed(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-update-user',
            'role' => User::ROLE_ADMIN,
        ]);

        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505888',
            'nama_kecamatan' => 'Atinggola',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'username' => 'operator-atinggola',
            'role' => User::ROLE_KECAMATAN,
            'kecamatan_id' => $kecamatan->id,
        ]);

        $response = $this->actingAs($admin)->put(route('users.update', $user), [
            'name' => 'Admin Atinggola',
            'username' => 'admin-atinggola',
            'email' => 'admin.atinggola@example.com',
            'role' => User::ROLE_ADMIN,
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Admin Atinggola',
            'username' => 'admin-atinggola',
            'role' => User::ROLE_ADMIN,
            'kecamatan_id' => null,
        ]);
    }

    public function test_admin_can_open_edit_user_page(): void
    {
        $admin = User::factory()->create([
            'username' => 'admin-edit-page',
            'role' => User::ROLE_ADMIN,
        ]);

        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505777',
            'nama_kecamatan' => 'Kwandang',
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'username' => 'operator-kwandang',
            'name' => 'Operator Kwandang',
            'role' => User::ROLE_KECAMATAN,
            'kecamatan_id' => $kecamatan->id,
        ]);

        $response = $this->actingAs($admin)->get(route('users.edit', $user));
        $response->assertOk();
        $response->assertSee('Pejabat OPD/Instansi');
        $response->assertSee('Perbarui Data Pengguna');
        $response->assertSee('Operator Kwandang');
        $response->assertSee('operator-kwandang');
        $response->assertSee('Kwandang');
    }

    public function test_non_admin_cannot_access_user_management(): void
    {
        $user = User::factory()->create([
            'username' => 'operator-biasa',
            'role' => User::ROLE_KECAMATAN,
        ]);

        $dashboardResponse = $this->actingAs($user)->get(route('dashboard'));
        $dashboardResponse->assertOk();
        $dashboardResponse->assertDontSee(route('users.index'));
        $dashboardResponse->assertDontSee('Manajemen Pengguna');

        $response = $this->actingAs($user)->get(route('users.index'));
        $response->assertForbidden();
    }
}
