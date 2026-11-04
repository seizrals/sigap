<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoGorutFaviconTest extends TestCase
{
    use RefreshDatabase;

    public function test_portal_publik_has_logo_gorut_favicon(): void
    {
        $response = $this->get(route('portal-publik'));

        $response->assertOk();
        $response->assertSee('images/logo_gorut.png');
        $response->assertSee('rel="icon"', false);
    }

    public function test_login_page_has_logo_gorut_favicon(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertSee('images/logo_gorut.png');
        $response->assertSee('rel="icon"', false);
    }

    public function test_dashboard_and_main_layout_has_logo_gorut_favicon(): void
    {
        $user = User::factory()->create(['role' => 'admin_kabupaten']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('images/logo_gorut.png');
        $response->assertSee('rel="icon"', false);
    }
}

