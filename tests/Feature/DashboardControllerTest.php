<?php

namespace Tests\Feature;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\Spbu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_map_uses_filtered_pangan_records_from_database(): void
    {
        $user = User::factory()->create([
            'username' => 'dashboard-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
            'latitude' => 0.72,
            'longitude' => 123.05,
        ]);
        $komoditasTerpilih = Komoditas::create([
            'kode_komoditas' => 'CBI-RWT',
            'nama_komoditas' => 'Cabai Rawit',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);
        $komoditasLain = Komoditas::create([
            'kode_komoditas' => 'BRS-PRM',
            'nama_komoditas' => 'Beras Premium',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);

        DataPangan::create([
            'tanggal_input' => '2026-11-01',
            'kecamatan_id' => $kecamatan->id,
            'komoditas_id' => $komoditasTerpilih->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 84000,
            'stok_tersedia' => 15,
            'status_ketersediaan' => 'kurang',
            'status_verifikasi' => 'disetujui',
        ]);

        DataPangan::create([
            'tanggal_input' => '2026-11-01',
            'kecamatan_id' => $kecamatan->id,
            'komoditas_id' => $komoditasLain->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 15000,
            'stok_tersedia' => 120,
            'status_ketersediaan' => 'aman',
            'status_verifikasi' => 'disetujui',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard', [
            'map_filter_type' => 'pangan',
            'map_komoditas_id' => $komoditasTerpilih->id,
        ]));

        $response->assertOk();
        $response->assertSee('Filter Tampilan Peta');
        $response->assertSee('name="map_filter_type"', false);
        $response->assertSee('name="map_komoditas_id"', false);
        $response->assertViewHas('mapPoints', function ($mapPoints) use ($komoditasTerpilih): bool {
            return $mapPoints->contains(function (array $point) use ($komoditasTerpilih): bool {
                return $point['latitude'] === 0.72
                    && $point['longitude'] === 123.05
                    && $point['status_ketersediaan'] === 'kurang'
                    && $point['kategori'] === 'Pangan Strategis'
                    && $point['detail_items'][0]['judul'] === $komoditasTerpilih->nama_komoditas;
            });
        });
    }

    public function test_dashboard_map_uses_lpg_and_bbm_records_from_database(): void
    {
        $user = User::factory()->create([
            'username' => 'dashboard-map-db',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505010',
            'nama_kecamatan' => 'Atinggola',
            'latitude' => 0.74,
            'longitude' => 123.12,
        ]);

        DataLpg::create([
            'tanggal_input' => '2026-11-02',
            'kecamatan_id' => $kecamatan->id,
            'user_id' => $user->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 8,
            'distribusi_masuk_tabung' => 100,
            'distribusi_keluar_tabung' => 92,
            'harga_eceran_lapangan' => 22000,
            'status_ketersediaan' => 'menipis',
            'status_distribusi' => 'terhambat',
            'status_verifikasi' => 'disetujui',
        ]);

        $spbu = Spbu::create([
            'kecamatan_id' => $kecamatan->id,
            'kode_spbu' => 'SPBU-ATINGGOLA-01',
            'nama_spbu' => 'SPBU Atinggola',
            'is_active' => true,
        ]);

        DataBbm::create([
            'tanggal_input' => '2026-11-02',
            'kecamatan_id' => $kecamatan->id,
            'spbu_id' => $spbu->id,
            'user_id' => $user->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 2.5,
            'status_antrean' => 'panjang',
            'status_ketersediaan' => 'kosong',
            'harga_jual_per_liter' => 10000,
            'status_verifikasi' => 'disetujui',
        ]);

        $lpgResponse = $this->actingAs($user)->get(route('dashboard', [
            'map_filter_type' => 'lpg',
        ]));
        $lpgResponse->assertOk();
        $lpgResponse->assertViewHas('mapPoints', function ($mapPoints): bool {
            return $mapPoints->contains(fn (array $point): bool => $point['kecamatan'] === 'Atinggola'
                && $point['kategori'] === 'LPG 3 KG'
                && $point['status_ketersediaan'] === 'menipis'
                && $point['detail_items'][0]['judul'] === 'LPG 3 Kg (Subsidi)'
            );
        });

        $bbmResponse = $this->actingAs($user)->get(route('dashboard', [
            'map_filter_type' => 'bbm',
        ]));
        $bbmResponse->assertOk();
        $bbmResponse->assertViewHas('mapPoints', function ($mapPoints): bool {
            return $mapPoints->contains(fn (array $point): bool => $point['kecamatan'] === 'Atinggola'
                && $point['kategori'] === 'BBM Subsidi'
                && $point['status_ketersediaan'] === 'kosong'
                && str_contains($point['detail_items'][0]['judul'], 'Pertalite (JBKP)')
            );
        });
    }

    public function test_dashboard_pangan_monitor_shows_pending_inputs_filtered_by_location_commodity_and_period(): void
    {
        $user = User::factory()->create([
            'username' => 'pangan-monitor',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);
        $kecamatanLain = Kecamatan::create([
            'kode_kecamatan' => '7505010',
            'nama_kecamatan' => 'Atinggola',
        ]);
        $cabai = Komoditas::create([
            'kode_komoditas' => 'CBI-RWT',
            'nama_komoditas' => 'Cabai Rawit',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);
        $beras = Komoditas::create([
            'kode_komoditas' => 'BRG-MDY',
            'nama_komoditas' => 'Beras Medium',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);

        DataPangan::create([
            'tanggal_input' => '2026-10-28',
            'tanggal_input_sebelumnya' => '2026-10-27',
            'kecamatan_id' => $kecamatan->id,
            'komoditas_id' => $cabai->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 72000,
            'harga_hari_sebelumnya' => 60000,
            'stok_tersedia' => 15,
            'status_ketersediaan' => 'waspada',
            'status_verifikasi' => 'pending',
        ]);
        DataPangan::create([
            'tanggal_input' => '2026-10-28',
            'kecamatan_id' => $kecamatan->id,
            'komoditas_id' => $beras->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 15000,
            'status_ketersediaan' => 'aman',
            'status_verifikasi' => 'pending',
        ]);
        DataPangan::create([
            'tanggal_input' => '2026-10-28',
            'kecamatan_id' => $kecamatanLain->id,
            'komoditas_id' => $cabai->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 70000,
            'status_ketersediaan' => 'waspada',
            'status_verifikasi' => 'pending',
        ]);

        $this->assertSame(3, DataPangan::count());
        $this->assertSame(1, DataPangan::where('kecamatan_id', $kecamatan->id)
            ->where('komoditas_id', $cabai->id)
            ->where(function ($query): void {
                $query->whereDate('tanggal_input', '2026-10-27')
                    ->orWhereDate('tanggal_input', '2026-10-28');
            })
            ->count());

        $response = $this->actingAs($user)->get(route('dashboard', [
            'tab' => 'pangan',
            'pangan_kecamatan_id' => $kecamatan->id,
            'pangan_komoditas_id' => $cabai->id,
            'periode_sebelumnya' => '2026-10-27',
            'periode_saat_ini' => '2026-10-28',
        ]));

        $response->assertOk();
        $response->assertSee('name="pangan_kecamatan_id"', false);
        $response->assertSee('name="pangan_komoditas_id"', false);
        $response->assertSee('name="periode_sebelumnya"', false);
        $response->assertSee('name="periode_saat_ini"', false);
        $response->assertDontSee('Verifikasi');
        $rows = $response->viewData('dataPangan');
        $this->assertCount(1, $rows);
        $this->assertSame('Kwandang', $rows->first()['kecamatan']);
        $this->assertSame('Cabai Rawit', $rows->first()['komoditas']);
        $this->assertSame(60000.0, (float) $rows->first()['harga_lalu']);
        $this->assertSame(72000.0, (float) $rows->first()['harga_saat_ini']);
        $this->assertSame('waspada', $rows->first()['status_ketersediaan']);
    }

    public function test_header_displays_leader_photos_and_notification(): void
    {
        $user = User::factory()->create([
            'username' => 'admin-header-test',
            'role' => 'admin_kabupaten',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('images/bupati.png');
        $response->assertSee('images/wabup.png');
        $response->assertSee('Foto Bupati Gorontalo Utara');
        $response->assertSee('Foto Wakil Bupati Gorontalo Utara');
        $response->assertSee('id="sidebar-toggle-btn"', false);
        $response->assertSee('id="app-sidebar"', false);
        $response->assertSee('id="sidebar-hide-btn"', false);
        $response->assertDontSee('Cetak Laporan');
    }
}
