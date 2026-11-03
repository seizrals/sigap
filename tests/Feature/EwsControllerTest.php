<?php

namespace Tests\Feature;

use App\Models\DataBbm;
use App\Models\DataLpg;
use App\Models\DataPangan;
use App\Models\EwsAlert;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\Spbu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EwsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_alert_saves_its_data_and_uploaded_photos(): void
    {
        Storage::fake('public');
        $user = User::factory()->create([
            'username' => 'ews-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);

        $response = $this->actingAs($user)->post(route('ews.store'), [
            'kategori' => 'pangan',
            'status_ketersediaan' => 'kritis',
            'kecamatan_id' => $kecamatan->id,
            'judul_alert' => 'Harga pangan meningkat',
            'analisis_sistem' => 'Harga cabai melampaui batas normal.',
            'fotos' => [UploadedFile::fake()->image('bukti.jpg')],
        ]);

        $response->assertRedirect(route('ews.index'));
        $alert = EwsAlert::firstOrFail();

        $this->assertSame('kritis', $alert->status_ketersediaan);
        $this->assertCount(1, $alert->foto_laporan_path);
        $this->assertTrue(Storage::disk('public')->exists($alert->foto_laporan_path[0]));
    }

    public function test_manual_alert_can_be_resolved_with_proof(): void
    {
        Storage::fake('public');
        $user = User::factory()->create([
            'username' => 'ews-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);
        $alert = EwsAlert::create([
            'kode_alert' => 'EWS-20261103-0001',
            'sumber_deteksi' => 'manual_admin',
            'kategori' => 'pangan',
            'status_ketersediaan' => 'waspada',
            'status' => 'terbuka',
            'kecamatan_id' => $kecamatan->id,
            'judul_alert' => 'Peringatan Awal',
            'analisis_sistem' => 'Ada kendala stok.',
        ]);

        $response = $this->actingAs($user)->post(route('ews.resolve', $alert), [
            'tindak_lanjut' => 'Sudah dilakukan sidak pasar dan distribusi lancar.',
            'bukti_penanganan' => UploadedFile::fake()->image('hasil_sidak.jpg'),
        ]);

        $response->assertRedirect(route('ews.index'));
        $alert->refresh();

        $this->assertSame('selesai', $alert->status);
        $this->assertSame('Sudah dilakukan sidak pasar dan distribusi lancar.', $alert->tindak_lanjut);
        $this->assertNotNull($alert->bukti_penanganan_path);
        $this->assertSame($user->id, $alert->ditangani_oleh);
        $this->assertTrue(Storage::disk('public')->exists($alert->bukti_penanganan_path));
    }

    public function test_ews_center_shows_saved_alert_and_photo_as_a_link(): void
    {
        $user = User::factory()->create([
            'username' => 'ews-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);
        EwsAlert::create([
            'kode_alert' => 'EWS-20261103-0002',
            'sumber_deteksi' => 'manual_admin',
            'kategori' => 'pangan',
            'status_ketersediaan' => 'kritis',
            'status' => 'terbuka',
            'kecamatan_id' => $kecamatan->id,
            'judul_alert' => 'Alert tampil di EWS',
            'analisis_sistem' => 'Detail laporan lapangan.',
            'foto_laporan_path' => ['ews-alerts/bukti.jpg'],
        ]);

        $response = $this->actingAs($user)->get(route('ews.index'));

        $response->assertOk();
        $response->assertSee('Alert tampil di EWS');
        $response->assertSee('Detail laporan lapangan.');
        $response->assertSee('Lihat Foto 1');
        $response->assertSee('open-alert-photo');
        $response->assertSee('Selesai Ditindaklanjuti');
    }

    public function test_ews_center_shows_automatic_alerts_from_pangan_lpg_and_bbm(): void
    {
        $user = User::factory()->create([
            'username' => 'ews-source-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);
        $komoditas = Komoditas::create([
            'nama_komoditas' => 'Cabai Rawit',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
        ]);

        // Pangan: waspada (masuk EWS) dan aman (tidak masuk EWS)
        DataPangan::create([
            'tanggal_input' => '2026-11-03',
            'kecamatan_id' => $kecamatan->id,
            'komoditas_id' => $komoditas->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 85000,
            'stok_tersedia' => 12,
            'status_ketersediaan' => 'waspada',
            'status_verifikasi' => 'disetujui',
        ]);

        // LPG: kritis (masuk EWS)
        DataLpg::create([
            'tanggal_input' => '2026-11-03',
            'kecamatan_id' => $kecamatan->id,
            'user_id' => $user->id,
            'jenis_lpg' => 'LPG 3 Kg (Subsidi)',
            'stok_tersedia_tabung' => 3,
            'distribusi_masuk_tabung' => 0,
            'distribusi_keluar_tabung' => 200,
            'harga_eceran_lapangan' => 25000,
            'status_ketersediaan' => 'kritis',
            'status_distribusi' => 'terhambat',
            'status_verifikasi' => 'disetujui',
        ]);

        // BBM: aman (tidak masuk EWS)
        $spbu = Spbu::create([
            'kecamatan_id' => $kecamatan->id,
            'kode_spbu' => 'SPBU-KWD-01',
            'nama_spbu' => 'SPBU Kwandang',
            'is_active' => true,
        ]);
        DataBbm::create([
            'tanggal_input' => '2026-11-03',
            'kecamatan_id' => $kecamatan->id,
            'spbu_id' => $spbu->id,
            'user_id' => $user->id,
            'jenis_bbm' => 'Pertalite (JBKP)',
            'stok_tersedia_kl' => 12,
            'status_antrean' => 'pendek',
            'status_ketersediaan' => 'aman',
            'harga_jual_per_liter' => 10000,
            'status_verifikasi' => 'disetujui',
        ]);

        $response = $this->actingAs($user)->get(route('ews.index'));

        $response->assertOk();
        // Alert otomatis dari pangan & LPG berasal dari data database
        $response->assertSee('Anomali Stok: Cabai Rawit');
        $response->assertSee('Anomali Stok: LPG 3 Kg (Subsidi)');
        // Data dengan status aman tidak memicu alert
        $response->assertDontSee('Anomali Stok: Pertalite (JBKP)');
    }
}
