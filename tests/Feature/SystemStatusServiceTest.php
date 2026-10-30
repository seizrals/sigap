<?php

namespace Tests\Feature;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\Desa;
use App\Models\EwsAlert;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\PangkalanLpg;
use App\Models\Spbu;
use App\Models\User;
use App\Services\SystemStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_status_is_aman_when_no_issues(): void
    {
        $statuses = SystemStatusService::getHeaderStatuses();

        $this->assertEquals('AMAN', $statuses['pangan']['status']);
        $this->assertEquals('AMAN', $statuses['lpg']['status']);
        $this->assertEquals('AMAN', $statuses['bbm']['status']);
    }

    public function test_pangan_status_reflects_waspada_and_bahaya(): void
    {
        $user = User::factory()->create(['username' => 'user_pangan']);
        $kecamatan = Kecamatan::create(['kode_kecamatan' => '750501', 'nama_kecamatan' => 'Kwandang']);
        $komoditas = Komoditas::create([
            'kode_komoditas' => 'P01',
            'nama_komoditas' => 'Cabai Rawit',
            'satuan' => 'kg',
            'kategori' => 'Bumbu-bumbuan',
        ]);

        // Input data waspada
        DataPangan::create([
            'tanggal_input' => '2026-10-30',
            'kecamatan_id' => $kecamatan->id,
            'komoditas_id' => $komoditas->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 70000,
            'harga_hari_sebelumnya' => 60000,
            'perubahan_harga_persen' => 16.67,
            'status_harga' => 'naik_tinggi',
            'status_ketersediaan' => 'waspada',
            'status_verifikasi' => 'disetujui',
        ]);

        $statuses = SystemStatusService::getHeaderStatuses();
        $this->assertEquals('WASPADA', $statuses['pangan']['status']);

        // Input data bahaya (kosong)
        DataPangan::create([
            'tanggal_input' => '2026-10-30',
            'kecamatan_id' => $kecamatan->id,
            'komoditas_id' => $komoditas->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 120000,
            'harga_hari_sebelumnya' => 60000,
            'perubahan_harga_persen' => 100,
            'status_harga' => 'harga_tinggi',
            'status_ketersediaan' => 'kosong',
            'status_verifikasi' => 'disetujui',
        ]);

        $statuses = SystemStatusService::getHeaderStatuses();
        $this->assertEquals('BAHAYA', $statuses['pangan']['status']);
    }

    public function test_lpg_status_reflects_input_data(): void
    {
        $user = User::factory()->create(['username' => 'user_lpg']);
        $kecamatan = Kecamatan::create(['kode_kecamatan' => '750501', 'nama_kecamatan' => 'Kwandang']);
        $desa = Desa::create([
            'kecamatan_id' => $kecamatan->id,
            'kode_desa' => '7505012001',
            'nama_desa' => 'Moluo',
        ]);
        $pangkalan = PangkalanLpg::create([
            'kode_pangkalan' => 'LPG-01',
            'nama_pangkalan' => 'Pangkalan Maju',
            'kecamatan_id' => $kecamatan->id,
            'desa_id' => $desa->id,
        ]);

        DataLpg::create([
            'tanggal_input' => '2026-10-30',
            'kecamatan_id' => $kecamatan->id,
            'pangkalan_lpg_id' => $pangkalan->id,
            'user_id' => $user->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'status_ketersediaan' => 'menipis',
            'status_harga' => 'melampaui_het_ringan',
            'status_distribusi' => 'terhambat',
        ]);

        $statuses = SystemStatusService::getHeaderStatuses();
        $this->assertEquals('WASPADA', $statuses['lpg']['status']);
    }

    public function test_bbm_status_reflects_input_data(): void
    {
        $user = User::factory()->create(['username' => 'user_bbm']);
        $kecamatan = Kecamatan::create(['kode_kecamatan' => '750501', 'nama_kecamatan' => 'Kwandang']);
        $spbu = Spbu::create([
            'kode_spbu' => 'SPBU-01',
            'nama_spbu' => 'SPBU Kwandang',
            'kecamatan_id' => $kecamatan->id,
        ]);

        DataBbm::create([
            'tanggal_input' => '2026-10-30',
            'kecamatan_id' => $kecamatan->id,
            'spbu_id' => $spbu->id,
            'user_id' => $user->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'status_ketersediaan' => 'kosong',
            'status_antrean' => 'sangat_panjang',
        ]);

        $statuses = SystemStatusService::getHeaderStatuses();
        $this->assertEquals('BAHAYA', $statuses['bbm']['status']);
    }

    public function test_navbar_renders_synchronized_statuses_and_portal_publik_remains_clean(): void
    {
        $response = $this->get(route('portal-publik'));
        $response->assertOk();
        $response->assertDontSee('STATUS PANGAN');
        $response->assertDontSee('STATUS LPG');
        $response->assertDontSee('STATUS BBM');

        $user = User::factory()->create(['username' => 'admin_status', 'role' => 'admin_kabupaten']);
        $navResponse = $this->actingAs($user)->get(route('dashboard'));
        $navResponse->assertOk();
        $navResponse->assertSee('STATUS PANGAN');
        $navResponse->assertSee('STATUS LPG');
        $navResponse->assertSee('STATUS BBM');
    }
}
