<?php

namespace Tests\Feature;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\Kecamatan;
use App\Models\Spbu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InputDistributionTest extends TestCase
{
    use RefreshDatabase;

    public function test_lpg_input_is_visible_in_dashboard_with_matching_monitoring_fields(): void
    {
        $user = User::factory()->create(['username' => 'lpg-operator', 'role' => 'admin_kabupaten']);
        $kecamatan = Kecamatan::create(['kode_kecamatan' => '7505020', 'nama_kecamatan' => 'Kwandang']);
        $response = $this->actingAs($user)->post(route('input.lpg.store'), [
            'tanggal_input' => '2026-10-28',
            'kecamatan_id' => $kecamatan->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 80,
            'distribusi_masuk_tabung' => 100,
            'distribusi_keluar_tabung' => 20,
            'harga_pangkalan' => 18500,
            'harga_eceran_lapangan' => 20000,
            'status_ketersediaan' => 'waspada',
            'status_distribusi' => 'lancar',
        ]);

        $response->assertRedirect(route('input.lpg'));
        $this->assertDatabaseHas('data_lpg', [
            'pangkalan_lpg_id' => null,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 80,
            'distribusi_masuk_tabung' => 100,
            'distribusi_keluar_tabung' => 20,
            'status_verifikasi' => 'pending',
            'keterangan_kendala' => null,
        ]);

        $kecamatanLain = Kecamatan::create(['kode_kecamatan' => '7505030', 'nama_kecamatan' => 'Sumalata']);
        DataLpg::create([
            'tanggal_input' => '2026-10-27',
            'kecamatan_id' => $kecamatanLain->id,
            'user_id' => $user->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'status_verifikasi' => 'pending',
        ]);

        $record = DataLpg::firstOrFail();
        $dashboard = $this->get(route('dashboard', [
            'tab' => 'lpg',
            'lpg_kecamatan_id' => $kecamatan->id,
            'lpg_tanggal' => '2026-10-28',
        ]));
        $dashboard->assertViewHas('dataLpg', fn ($rows): bool => $rows->contains('id', $record->id));
        $dashboard->assertViewHas('dataLpg', fn ($rows): bool => $rows->count() === 1);
        $dashboard->assertSee('LPG 3 Kg (Subsidi)');
        $inputPage = $this->get(route('input.lpg'));
        $inputPage->assertDontSee('Keterangan / Kendala');
        $inputPage->assertDontSee('Nama Pangkalan');
    }

    public function test_bbm_input_uses_active_spbu_districts_and_is_visible_in_monitoring(): void
    {
        $user = User::factory()->create(['username' => 'bbm-operator', 'role' => 'admin_kabupaten']);
        $kecamatan = Kecamatan::create(['kode_kecamatan' => '7505020', 'nama_kecamatan' => 'Kwandang']);
        Kecamatan::create(['kode_kecamatan' => '7505030', 'nama_kecamatan' => 'Sumalata']);
        $spbu = Spbu::create([
            'kecamatan_id' => $kecamatan->id,
            'kode_spbu' => 'SPBU-TEST',
            'nama_spbu' => 'SPBU Kwandang Test',
            'is_active' => true,
        ]);

        $inputPage = $this->actingAs($user)->get(route('input.bbm'));
        $inputPage->assertOk();
        $inputPage->assertSee('SPBU Kwandang Test');
        $inputPage->assertDontSee('Sumalata');
        $inputPage->assertDontSee('Keterangan / Kendala');

        $response = $this->post(route('input.bbm.store'), [
            'tanggal_input' => '2026-10-28',
            'kecamatan_id' => $kecamatan->id,
            'spbu_id' => $spbu->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 15.5,
            'stok_masuk_hari_ini_kl' => 5,
            'penjualan_harian_rata_kl' => 2.5,
            'estimasi_hari_ketersediaan' => 6.2,
            'distribusi_nelayan' => '1',
            'kuota_nelayan_harian_kl' => 1.5,
            'realisasi_nelayan_kl' => 1,
            'distribusi_pertanian' => '0',
            'status_antrean' => 'pendek',
            'estimasi_waktu_tunggu_menit' => 15,
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 10000,
        ]);

        $response->assertRedirect(route('input.bbm'));
        $this->assertDatabaseHas('data_bbm', [
            'spbu_id' => $spbu->id,
            'kecamatan_id' => $kecamatan->id,
            'stok_tersedia_kl' => 15.5,
            'distribusi_nelayan' => true,
            'distribusi_pertanian' => false,
            'status_verifikasi' => 'pending',
            'keterangan_kendala' => null,
        ]);

        $record = DataBbm::firstOrFail();
        DataBbm::create([
            'tanggal_input' => '2026-10-28',
            'kecamatan_id' => $kecamatan->id,
            'spbu_id' => $spbu->id,
            'user_id' => $user->id,
            'jenis_bbm' => 'Bio Solar',
            'status_antrean' => 'panjang',
            'status_ketersediaan' => 'waspada',
            'status_verifikasi' => 'pending',
        ]);
        $dashboard = $this->get(route('dashboard', [
            'tab' => 'bbm',
            'bbm_jenis' => 'Pertalite (JBKP)',
            'bbm_tanggal' => '2026-10-28',
        ]));
        $dashboard->assertViewHas('dataBbm', fn ($rows): bool => $rows->contains('id', $record->id));
        $dashboard->assertViewHas('dataBbm', fn ($rows): bool => $rows->count() === 1);
        $dashboard->assertSee('SPBU Kwandang Test');
        $dashboard->assertSee('Pertalite (JBKP)');
        $dashboard->assertSee('Nelayan');
        $dashboard->assertSee('Pertanian');
        $dashboard->assertSee('Ya');
        $dashboard->assertSee('Tidak');
        $dashboard->assertDontSee('Kuota Nelayan (KL)');
        $dashboard->assertDontSee('Realisasi Nelayan (KL)');
        $dashboard->assertDontSee('Kuota Pertanian (KL)');
        $dashboard->assertDontSee('Realisasi Pertanian (KL)');
        $dashboard->assertDontSee('Estimasi Hari');

        $bioSolarRecord = DataBbm::where('jenis_bbm', 'Bio Solar')->firstOrFail();
        $bioSolarDashboard = $this->get(route('dashboard', [
            'tab' => 'bbm',
            'bbm_jenis' => 'Bio Solar',
            'bbm_tanggal' => '2026-10-28',
        ]));
        $bioSolarDashboard->assertViewHas('dataBbm', function ($rows) use ($bioSolarRecord): bool {
            return $rows->count() === 1 && $rows->first()->id === $bioSolarRecord->id;
        });
        $bioSolarDashboard->assertSee('bg-rose-100 text-rose-700', false);
    }

    public function test_bbm_rejects_an_spbu_that_does_not_belong_to_selected_district(): void
    {
        $user = User::factory()->create(['username' => 'bbm-validator', 'role' => 'admin_kabupaten']);
        $kwandang = Kecamatan::create(['kode_kecamatan' => '7505020', 'nama_kecamatan' => 'Kwandang']);
        $atinggola = Kecamatan::create(['kode_kecamatan' => '7505010', 'nama_kecamatan' => 'Atinggola']);
        $spbu = Spbu::create([
            'kecamatan_id' => $atinggola->id,
            'kode_spbu' => 'SPBU-ATINGGOLA',
            'nama_spbu' => 'SPBU Atinggola',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->from(route('input.bbm'))->post(route('input.bbm.store'), [
            'tanggal_input' => '2026-10-28',
            'kecamatan_id' => $kwandang->id,
            'spbu_id' => $spbu->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'status_antrean' => 'tidak_ada',
            'status_ketersediaan' => 'aman',
        ]);

        $response->assertSessionHasErrors('spbu_id');
        $this->assertDatabaseCount('data_bbm', 0);
    }

    public function test_lpg_monitoring_filters_by_district_and_date_without_pangkalan_or_verification_column(): void
    {
        $user = User::factory()->create(['username' => 'lpg-filter-tester', 'role' => 'admin_kabupaten']);
        $kwandang = Kecamatan::create(['kode_kecamatan' => '7505020', 'nama_kecamatan' => 'Kwandang']);
        $sumalata = Kecamatan::create(['kode_kecamatan' => '7505030', 'nama_kecamatan' => 'Sumalata']);

        $rec1 = DataLpg::create([
            'tanggal_input' => '2026-10-25',
            'kecamatan_id' => $kwandang->id,
            'user_id' => $user->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 50,
            'distribusi_masuk_tabung' => 10,
            'distribusi_keluar_tabung' => 5,
            'status_verifikasi' => 'draft',
        ]);

        $rec2 = DataLpg::create([
            'tanggal_input' => '2026-10-26',
            'kecamatan_id' => $kwandang->id,
            'user_id' => $user->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 60,
            'distribusi_masuk_tabung' => 20,
            'distribusi_keluar_tabung' => 10,
            'status_verifikasi' => 'pending',
        ]);

        $rec3 = DataLpg::create([
            'tanggal_input' => '2026-10-25',
            'kecamatan_id' => $sumalata->id,
            'user_id' => $user->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 70,
            'distribusi_masuk_tabung' => 30,
            'distribusi_keluar_tabung' => 15,
            'status_verifikasi' => 'disetujui',
        ]);

        // Filter by kecamatan Kwandang only -> returns rec1 and rec2, not rec3
        $respKec = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'lpg',
            'lpg_kecamatan_id' => $kwandang->id,
        ]));
        $respKec->assertOk();
        $respKec->assertViewHas('dataLpg', function ($rows) use ($rec1, $rec2, $rec3): bool {
            return $rows->contains('id', $rec1->id)
                && $rows->contains('id', $rec2->id)
                && !$rows->contains('id', $rec3->id);
        });

        // Filter by date 2026-10-26 only -> returns rec2 only
        $respDate = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'lpg',
            'lpg_tanggal' => '2026-10-26',
        ]));
        $respDate->assertOk();
        $respDate->assertViewHas('dataLpg', function ($rows) use ($rec2): bool {
            return $rows->count() === 1 && $rows->first()->id === $rec2->id;
        });

        // Filter by both Kwandang AND 2026-10-25 -> returns rec1 only
        $respBoth = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'lpg',
            'lpg_kecamatan_id' => $kwandang->id,
            'lpg_tanggal' => '2026-10-25',
        ]));
        $respBoth->assertOk();
        $respBoth->assertViewHas('dataLpg', function ($rows) use ($rec1): bool {
            return $rows->count() === 1 && $rows->first()->id === $rec1->id;
        });

        // Verify that Nama Pangkalan and Verifikasi are not present in LPG tab
        $respBoth->assertDontSee('Nama Pangkalan');
        $respBoth->assertDontSee('Harga Pangkalan');
        $respBoth->assertSee('Harga HET');
    }

    public function test_bbm_monitoring_filters_by_fuel_type_and_date_and_omits_unused_metrics(): void
    {
        $user = User::factory()->create(['username' => 'bbm-filter-tester', 'role' => 'admin_kabupaten']);
        $kwandang = Kecamatan::create(['kode_kecamatan' => '7505020', 'nama_kecamatan' => 'Kwandang']);
        $spbu = Spbu::create([
            'kecamatan_id' => $kwandang->id,
            'kode_spbu' => 'SPBU-FILTER',
            'nama_spbu' => 'SPBU Filter Test',
            'is_active' => true,
        ]);

        $pertalite1 = DataBbm::create([
            'tanggal_input' => '2026-10-25',
            'kecamatan_id' => $kwandang->id,
            'spbu_id' => $spbu->id,
            'user_id' => $user->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 10,
            'status_antrean' => 'tidak_ada',
            'status_ketersediaan' => 'aman',
            'status_verifikasi' => 'pending',
        ]);

        $pertalite2 = DataBbm::create([
            'tanggal_input' => '2026-10-26',
            'kecamatan_id' => $kwandang->id,
            'spbu_id' => $spbu->id,
            'user_id' => $user->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 12,
            'status_antrean' => 'pendek',
            'status_ketersediaan' => 'aman',
            'status_verifikasi' => 'disetujui',
        ]);

        $solar1 = DataBbm::create([
            'tanggal_input' => '2026-10-25',
            'kecamatan_id' => $kwandang->id,
            'spbu_id' => $spbu->id,
            'user_id' => $user->id,
            'jenis_bbm' => 'Bio Solar',
            'stok_tersedia_kl' => 8,
            'status_antrean' => 'panjang',
            'status_ketersediaan' => 'waspada',
            'status_verifikasi' => 'draft',
        ]);

        // Filter Pertalite
        $respPertalite = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'bbm',
            'bbm_jenis' => 'Pertalite',
        ]));
        $respPertalite->assertOk();
        $respPertalite->assertViewHas('dataBbm', function ($rows) use ($pertalite1, $pertalite2, $solar1): bool {
            return $rows->contains('id', $pertalite1->id)
                && $rows->contains('id', $pertalite2->id)
                && !$rows->contains('id', $solar1->id);
        });

        // Filter Bio Solar
        $respSolar = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'bbm',
            'bbm_jenis' => 'Bio Solar',
        ]));
        $respSolar->assertOk();
        $respSolar->assertViewHas('dataBbm', function ($rows) use ($solar1, $pertalite1): bool {
            return $rows->contains('id', $solar1->id)
                && !$rows->contains('id', $pertalite1->id);
        });

        // Filter Date 2026-10-26
        $respDate = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'bbm',
            'bbm_tanggal' => '2026-10-26',
        ]));
        $respDate->assertOk();
        $respDate->assertViewHas('dataBbm', function ($rows) use ($pertalite2, $pertalite1): bool {
            return $rows->contains('id', $pertalite2->id)
                && !$rows->contains('id', $pertalite1->id);
        });

        // Filter Bio Solar AND 2026-10-25
        $respBoth = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'bbm',
            'bbm_jenis' => 'Bio Solar',
            'bbm_tanggal' => '2026-10-25',
        ]));
        $respBoth->assertOk();
        $respBoth->assertViewHas('dataBbm', function ($rows) use ($solar1): bool {
            return $rows->count() === 1 && $rows->first()->id === $solar1->id;
        });

        // Verify unused items and red badge are not in BBM view
        $respBoth->assertDontSee('Masuk (KL)');
        $respBoth->assertDontSee('Penjualan/Hari (KL)');
        $respBoth->assertDontSee('Estimasi Hari');
        $respBoth->assertDontSee('Kuota Nelayan (KL)');
        $respBoth->assertDontSee('Realisasi Nelayan (KL)');
        $respBoth->assertDontSee('Kuota Pertanian (KL)');
        $respBoth->assertDontSee('Realisasi Pertanian (KL)');
        $respBoth->assertDontSee('Tunggu (Menit)');
        $respBoth->assertDontSee('bg-red-500 text-white px-4 py-2 rounded-xl text-center shadow-sm', false);
    }

    public function test_four_spbu_gorontalo_utara_and_bbm_monitoring(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $spbus = Spbu::whereIn('kode_spbu', ['SPBU-001', 'SPBU-002', 'SPBU-003', 'SPBU-004'])->get();
        $this->assertCount(4, $spbus);

        $spbuNames = $spbus->pluck('nama_spbu')->all();
        $this->assertContains('SPBU 74.962.01 Pontolo Kwandang', $spbuNames);
        $this->assertContains('SPBU 74.962.02 Ilangata Anggrek', $spbuNames);
        $this->assertContains('SPBU 74.962.04 Kota Jin Atinggola', $spbuNames);
        $this->assertContains('SPBU 76.965.01 Tolite Jaya Tolinggula', $spbuNames);

        $admin = User::first();

        // 1. In input BBM page, all 4 SPBUs are present
        $inputBbm = $this->actingAs($admin)->get(route('input.bbm'));
        $inputBbm->assertOk();
        $inputBbm->assertSee('SPBU 74.962.01 Pontolo Kwandang');
        $inputBbm->assertSee('SPBU 74.962.02 Ilangata Anggrek');
        $inputBbm->assertSee('SPBU 74.962.04 Kota Jin Atinggola');
        $inputBbm->assertSee('SPBU 76.965.01 Tolite Jaya Tolinggula');

        // 2. In monitoring BBM dashboard, subsidized fuel records for all 4 SPBUs are present
        $dashboardBbm = $this->actingAs($admin)->get(route('dashboard', ['tab' => 'bbm']));
        $dashboardBbm->assertOk();
        $dashboardBbm->assertSee('SPBU 74.962.01 Pontolo Kwandang');
        $dashboardBbm->assertSee('SPBU 74.962.02 Ilangata Anggrek');
        $dashboardBbm->assertSee('SPBU 74.962.04 Kota Jin Atinggola');
        $dashboardBbm->assertSee('SPBU 76.965.01 Tolite Jaya Tolinggula');
        $dashboardBbm->assertSee('Harga Eceran');
        $dashboardBbm->assertSee('Rp 10.000');
        $dashboardBbm->assertSee('Rp 6.800');

        $dashboardBbm->assertViewHas('dataBbm', function ($records): bool {
            // 4 SPBU x 2 jenis BBM (Pertalite & Bio Solar) = 8 monitoring records
            return $records->count() >= 8
                && $records->contains('jenis_bbm', 'Pertalite (JBKP)')
                && $records->contains('jenis_bbm', 'Bio Solar');
        });
    }
}
