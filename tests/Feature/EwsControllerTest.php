<?php

namespace Tests\Feature;

use App\Models\EwsAlert;
use App\Models\Kecamatan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EwsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_manual_alert_saves_its_point_and_uploaded_photos(): void
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
            'tingkat_risiko' => 'tinggi',
            'kecamatan_id' => $kecamatan->id,
            'judul_alert' => 'Harga pangan meningkat',
            'analisis_sistem' => 'Harga cabai melampaui batas normal.',
            'latitude' => 0.72,
            'longitude' => 123.05,
            'fotos' => [UploadedFile::fake()->image('bukti.jpg')],
        ]);

        $response->assertRedirect(route('ews.index'));
        $alert = EwsAlert::firstOrFail();

        $this->assertSame('0.7200000', $alert->latitude);
        $this->assertSame('123.0500000', $alert->longitude);
        $this->assertCount(1, $alert->foto_laporan_path);
        Storage::disk('public')->assertExists($alert->foto_laporan_path[0]);
    }

    public function test_manual_alert_rejects_a_point_outside_gorontalo(): void
    {
        $user = User::factory()->create([
            'username' => 'ews-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);

        $response = $this->actingAs($user)->from(route('ews.index'))->post(route('ews.store'), [
            'kategori' => 'pangan',
            'tingkat_risiko' => 'tinggi',
            'kecamatan_id' => $kecamatan->id,
            'judul_alert' => 'Lokasi di luar provinsi',
            'analisis_sistem' => 'Laporan harus ditolak.',
            'latitude' => 0.72,
            'longitude' => 124.0,
        ]);

        $response->assertSessionHasErrors([
            'latitude' => 'Titik lokasi harus berada di dalam Provinsi Gorontalo.',
        ]);
        $this->assertDatabaseMissing('ews_alerts', [
            'judul_alert' => 'Lokasi di luar provinsi',
        ]);
    }

    public function test_manual_alert_can_publish_only_one_market_recommendation(): void
    {
        $user = User::factory()->create([
            'username' => 'ews-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);
        $alert = EwsAlert::create([
            'kode_alert' => 'EWS-20261028-0001',
            'sumber_deteksi' => 'manual_admin',
            'kategori' => 'pangan',
            'tingkat_risiko' => 'tinggi',
            'status' => 'terbuka',
            'kecamatan_id' => $kecamatan->id,
            'latitude' => 0.72,
            'longitude' => 123.05,
            'judul_alert' => 'Harga pangan meningkat',
            'analisis_sistem' => 'Harga cabai melampaui batas normal.',
        ]);

        $this->actingAs($user)->post(route('ews.rekomendasi.store', $alert));
        $this->post(route('ews.rekomendasi.store', $alert));

        $this->assertDatabaseCount('ews_rekomendasi', 1);
        $this->assertDatabaseHas('ews_rekomendasi', [
            'ews_alert_id' => $alert->id,
            'tipe_rekomendasi' => 'operasi_pasar',
            'judul_rekomendasi' => 'Operasi Pasar: Harga pangan meningkat',
            'detail_rekomendasi' => 'Harga cabai melampaui batas normal.',
        ]);
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
            'kode_alert' => 'EWS-20261028-0002',
            'sumber_deteksi' => 'manual_admin',
            'kategori' => 'pangan',
            'tingkat_risiko' => 'tinggi',
            'status' => 'terbuka',
            'kecamatan_id' => $kecamatan->id,
            'latitude' => 0.72,
            'longitude' => 123.05,
            'judul_alert' => 'Alert tampil di EWS',
            'analisis_sistem' => 'Detail laporan lapangan.',
            'foto_laporan_path' => ['ews-alerts/bukti.jpg'],
        ]);

        $response = $this->actingAs($user)->get(route('ews.index'));

        $response->assertOk();
        $response->assertSee('Alert tampil di EWS');
        $response->assertSee('Detail laporan lapangan.');
        $response->assertSee('Lihat foto 1');
        $response->assertSee('open-alert-photo');
        $response->assertSee('ews-coordinate-input');
        $response->assertSee('openstreetmap.org');
        $response->assertDontSee('<img src="'.asset('storage/ews-alerts/bukti.jpg').'"');
    }
}
