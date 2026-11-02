<?php

namespace Tests\Feature;

use App\Models\DataBbm;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\Spbu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BbmDistributionInOutTest extends TestCase
{
    use RefreshDatabase;

    public function test_bbm_input_saves_distribusi_masuk_dan_keluar_when_checked(): void
    {
        $user = User::factory()->create(['username' => 'operator-bbm', 'role' => 'admin_kabupaten']);
        $kecamatan = Kecamatan::create(['kode_kecamatan' => '750501', 'nama_kecamatan' => 'Kwandang']);
        $spbu = Spbu::create([
            'kode_spbu' => 'SPBU-01',
            'nama_spbu' => 'SPBU Kwandang',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('input.bbm.store'), [
            'tanggal_input' => '2026-11-02',
            'kecamatan_id' => $kecamatan->id,
            'spbu_id' => $spbu->id,
            'jenis_bbm' => 'Bio Solar',
            'stok_tersedia_kl' => 15.000,
            'status_antrean' => 'tidak_ada',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 6800,
            'distribusi_nelayan' => '1',
            'distribusi_masuk_nelayan_kl' => 10.500,
            'distribusi_keluar_nelayan_kl' => 8.250,
            'distribusi_pertanian' => '1',
            'distribusi_masuk_pertanian_kl' => 5.000,
            'distribusi_keluar_pertanian_kl' => 4.100,
        ]);

        $response->assertRedirect(route('input.bbm'));

        $this->assertDatabaseHas('data_bbm', [
            'spbu_id' => $spbu->id,
            'distribusi_nelayan' => true,
            'distribusi_masuk_nelayan_kl' => 10.500,
            'distribusi_keluar_nelayan_kl' => 8.250,
            'distribusi_pertanian' => true,
            'distribusi_masuk_pertanian_kl' => 5.000,
            'distribusi_keluar_pertanian_kl' => 4.100,
        ]);

        // In dashboard monitoring, check that Distribusi Masuk and Keluar appear
        $dashboard = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'bbm',
            'bbm_tanggal' => '2026-11-02',
            'bbm_jenis' => 'Bio Solar',
        ]));
        $dashboard->assertOk();
        $dashboard->assertSee('Distribusi Masuk:');
        $dashboard->assertSee('10,500 KL');
        $dashboard->assertSee('Distribusi Keluar:');
        $dashboard->assertSee('8,250 KL');
        $dashboard->assertSee('5,000 KL');
        $dashboard->assertSee('4,100 KL');
    }

    public function test_bbm_monitoring_does_not_show_masuk_keluar_when_not_checked(): void
    {
        $user = User::factory()->create(['username' => 'operator-bbm-2', 'role' => 'admin_kabupaten']);
        $kecamatan = Kecamatan::create(['kode_kecamatan' => '750501', 'nama_kecamatan' => 'Kwandang']);
        $spbu = Spbu::create([
            'kode_spbu' => 'SPBU-02',
            'nama_spbu' => 'SPBU Pontolo',
            'kecamatan_id' => $kecamatan->id,
            'is_active' => true,
        ]);

        DataBbm::create([
            'tanggal_input' => '2026-11-02',
            'kecamatan_id' => $kecamatan->id,
            'spbu_id' => $spbu->id,
            'user_id' => $user->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 20.000,
            'status_antrean' => 'tidak_ada',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 10000,
            'distribusi_nelayan' => false,
            'distribusi_pertanian' => false,
        ]);

        $dashboard = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'bbm',
            'bbm_tanggal' => '2026-11-02',
        ]));
        $dashboard->assertOk();
        $dashboard->assertDontSee('Distribusi Masuk:');
        $dashboard->assertDontSee('Distribusi Keluar:');
    }

    public function test_sp2kp_commodities_exist_and_appear_in_filter_and_input(): void
    {
        $user = User::factory()->create(['username' => 'admin_sp2kp', 'role' => 'admin_kabupaten']);

        // Run SP2KP seeder / migration
        $this->artisan('db:seed', ['--class' => 'DatabaseSeeder']);

        // Check essential SP2KP commodities exist in DB
        $this->assertDatabaseHas('komoditas', ['nama_komoditas' => 'Beras SPHP Bulog', 'is_strategis' => true]);
        $this->assertDatabaseHas('komoditas', ['nama_komoditas' => 'Minyakita', 'is_strategis' => true]);
        $this->assertDatabaseHas('komoditas', ['nama_komoditas' => 'Cabai Rawit Merah', 'is_strategis' => true]);
        $this->assertDatabaseHas('komoditas', ['nama_komoditas' => 'Daging Sapi Paha Belakang', 'is_strategis' => true]);
        $this->assertDatabaseHas('komoditas', ['nama_komoditas' => 'Ikan Bandeng', 'is_strategis' => true]);
        $this->assertDatabaseHas('komoditas', ['nama_komoditas' => 'Bawang Putih Honan', 'is_strategis' => true]);

        // Check they appear in input pangan strategis
        $inputPangan = $this->actingAs($user)->get(route('input.pangan'));
        $inputPangan->assertOk();
        $inputPangan->assertSee('Beras SPHP Bulog');
        $inputPangan->assertSee('Minyakita');
        $inputPangan->assertSee('Cabai Rawit Merah');

        // Check they appear in monitoring dashboard pangan strategis filter
        $dashboard = $this->actingAs($user)->get(route('dashboard'));
        $dashboard->assertOk();
        $dashboard->assertSee('Beras SPHP Bulog');
        $dashboard->assertSee('Minyakita');
        $dashboard->assertSee('Cabai Rawit Merah');
        $dashboard->assertSee('Daging Sapi Paha Belakang');
    }
}
