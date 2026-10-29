<?php

namespace Tests\Feature;

use App\Models\DataPangan;
use App\Models\EwsAlert;
use App\Models\EwsRekomendasi;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_published_market_recommendation_is_visible_in_dashboard_and_gis_data(): void
    {
        $user = User::factory()->create([
            'username' => 'dashboard-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);
        $alert = EwsAlert::create([
            'kode_alert' => 'EWS-20261028-0003',
            'sumber_deteksi' => 'manual_admin',
            'kategori' => 'pangan',
            'tingkat_risiko' => 'tinggi',
            'status' => 'terbuka',
            'kecamatan_id' => $kecamatan->id,
            'latitude' => 0.72,
            'longitude' => 123.05,
            'judul_alert' => 'Peringatan pasar',
            'analisis_sistem' => 'Keterangan rekomendasi di peta.',
        ]);
        EwsRekomendasi::create([
            'ews_alert_id' => $alert->id,
            'tipe_rekomendasi' => 'operasi_pasar',
            'judul_rekomendasi' => 'Operasi Pasar: Peringatan pasar',
            'detail_rekomendasi' => 'Keterangan rekomendasi di peta.',
            'dibuat_oleh' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Operasi Pasar: Peringatan pasar');
        $response->assertSee('Keterangan rekomendasi di peta.');
        $response->assertViewHas('rekomendasiPeta', function ($rekomendasiPeta): bool {
            return $rekomendasiPeta->contains(fn (array $point): bool => $point['latitude'] === 0.72
                && $point['longitude'] === 123.05
                && $point['zona'] === 'merah'
                && $point['detail'] === 'Keterangan rekomendasi di peta.'
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
