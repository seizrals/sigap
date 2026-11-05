<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('Masuk ke Sistem');
        $response->assertSee('LOGIN SISTEM');
        $response->assertSee('Akses Portal Publik');
        $response->assertDontSee('Username atau password salah.');
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testpetugas',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);

        $response = $this->from(route('login'))->post(route('login.post'), [
            'username' => 'testpetugas',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('username');
    }

    public function test_login_view_renders_validation_errors_properly_without_duplicate_password_field(): void
    {
        $view = $this->withViewErrors([
            'username' => 'Username atau password salah.',
        ])->view('auth.login');

        $view->assertSee('Username atau password salah.');
        $view->assertSee('Masuk ke Sistem');
        $view->assertSee('LOGIN SISTEM');

        $html = (string) $view;
        // Memastikan tidak ada duplikasi input password
        $this->assertEquals(1, substr_count($html, 'id="login-password"'));
        $this->assertEquals(0, substr_count($html, 'id="password"'));
        $this->assertEquals(0, substr_count($html, 'id="toggle-password"'));
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create([
            'username' => 'admin_gorut',
            'password' => bcrypt('secret123'),
            'is_active' => true,
        ]);

        $response = $this->post(route('login.post'), [
            'username' => 'admin_gorut',
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }
}
