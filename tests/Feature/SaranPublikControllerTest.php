<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\SaranPublik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaranPublikControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_market_report_categories_require_commodity_and_price(): void
    {
        foreach (['lonjakan_harga', 'kelangkaan_stok', 'pelanggaran_het'] as $kategori) {
            $response = $this->from('/')->post(route('saran-publik.store'), [
                'kategori' => $kategori,
                'kontak_pelapor' => '08123456789',
                'judul_laporan' => 'Laporan warga',
                'isi_laporan' => 'Isi laporan',
            ]);

            $response->assertSessionHasErrors([
                'nama_komoditas_dilaporkan',
                'harga_dilaporkan',
            ]);
        }
    }

    public function test_policy_and_other_categories_store_without_commodity_or_price(): void
    {
        foreach (['saran_kebijakan', 'lainnya'] as $kategori) {
            $this->post(route('saran-publik.store'), [
                'kategori' => $kategori,
                'kontak_pelapor' => '08123456789',
                'judul_laporan' => 'Laporan warga',
                'isi_laporan' => 'Isi laporan',
                'nama_komoditas_dilaporkan' => 'Cabai Rawit',
                'harga_dilaporkan' => 85000,
            ])->assertSessionHas('success');

            $this->assertDatabaseHas('saran_publik', [
                'kategori' => $kategori,
                'nama_komoditas_dilaporkan' => null,
                'harga_dilaporkan' => null,
            ]);
        }
    }

    public function test_contact_is_required_to_submit_a_report(): void
    {
        $response = $this->from('/')->post(route('saran-publik.store'), [
            'kategori' => 'lonjakan_harga',
            'judul_laporan' => 'Laporan warga',
            'isi_laporan' => 'Isi laporan',
            'nama_komoditas_dilaporkan' => 'Cabai Rawit',
            'harga_dilaporkan' => 85000,
        ]);

        $response->assertSessionHasErrors([
            'kontak_pelapor' => 'Kontak wajib diisi.',
        ]);
        $this->assertDatabaseMissing('saran_publik', [
            'judul_laporan' => 'Laporan warga',
        ]);
    }

    public function test_sidebar_contains_saran_publik_navigation_link(): void
    {
        $user = User::factory()->create(['username' => 'admin_saran_nav', 'role' => 'admin_kabupaten']);

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee(route('saran-publik.index'));
        $response->assertSee('Saran Publik');
    }

    public function test_portal_publik_submission_saves_and_is_visible_in_saran_publik(): void
    {
        $user = User::factory()->create(['username' => 'admin_saran_view', 'role' => 'admin_kabupaten']);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '750501',
            'nama_kecamatan' => 'Kwandang',
            'is_active' => true,
        ]);

        // Submit via portal publik
        $postResponse = $this->post(route('saran-publik.store'), [
            'kategori' => 'lonjakan_harga',
            'kontak_pelapor' => '089912345678',
            'nama_pelapor' => 'Warga Kwandang',
            'kecamatan_id' => $kecamatan->id,
            'lokasi_spesifik' => 'Pasar Moluo, Kwandang',
            'judul_laporan' => 'Cabai tembus 100 ribu',
            'isi_laporan' => 'Harga cabai melonjak tajam sejak kemarin pagi.',
            'nama_komoditas_dilaporkan' => 'Cabai Rawit',
            'harga_dilaporkan' => 100000,
        ]);

        $postResponse->assertRedirect(route('portal-publik'));
        $this->assertDatabaseHas('saran_publik', [
            'judul_laporan' => 'Cabai tembus 100 ribu',
            'nama_komoditas_dilaporkan' => 'Cabai Rawit',
            'harga_dilaporkan' => 100000,
            'kecamatan_id' => $kecamatan->id,
            'lokasi_spesifik' => 'Pasar Moluo, Kwandang',
        ]);

        // Verify visible in saran-publik index
        $indexResponse = $this->actingAs($user)->get(route('saran-publik.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('Cabai tembus 100 ribu');
        $indexResponse->assertSee('Kec. Kwandang');
        $indexResponse->assertSee('Pasar Moluo, Kwandang');
        $indexResponse->assertSee('100.000');
        $indexResponse->assertSee('Kontak Pelapor:');
        $indexResponse->assertSee('089912345678');
        $indexResponse->assertSee('WhatsApp');
    }

    public function test_more_than_four_reports_render_properly_with_kecamatan_badges(): void
    {
        $user = User::factory()->create(['username' => 'admin_multi_reports', 'role' => 'admin_kabupaten']);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '750502',
            'nama_kecamatan' => 'Tomilito',
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 6; $i++) {
            SaranPublik::create([
                'kode_laporan' => 'SRN-20261030-000' . $i,
                'kategori' => 'lonjakan_harga',
                'kontak_pelapor' => '0812345678' . $i,
                'nama_pelapor' => 'Pelapor ' . $i,
                'kecamatan_id' => $kecamatan->id,
                'lokasi_spesifik' => 'Lokasi Spesifik ' . $i,
                'judul_laporan' => 'Laporan Ke-' . $i,
                'isi_laporan' => 'Isi laporan ke-' . $i,
                'nama_komoditas_dilaporkan' => 'Beras',
                'harga_dilaporkan' => 15000 + ($i * 1000),
                'status' => 'masuk',
            ]);
        }

        $response = $this->actingAs($user)->get(route('saran-publik.index'));
        $response->assertOk();

        for ($i = 1; $i <= 6; $i++) {
            $response->assertSee('Laporan Ke-' . $i);
            $response->assertSee('Lokasi Spesifik ' . $i);
            $response->assertSee('SRN-20261030-000' . $i);
        }
        $response->assertSee('Kec. Tomilito');
    }

    public function test_saran_publik_filters_reports_by_category(): void
    {
        $user = User::factory()->create(['username' => 'admin_saran_filter', 'role' => 'admin_kabupaten']);

        SaranPublik::create([
            'kode_laporan' => 'SRN-20261029-0001',
            'kategori' => 'lonjakan_harga',
            'kontak_pelapor' => '08111111111',
            'judul_laporan' => 'Harga Beras Naik',
            'isi_laporan' => 'Kenaikan beras di tolitoli',
            'nama_komoditas_dilaporkan' => 'Beras',
            'harga_dilaporkan' => 16000,
            'status' => 'masuk',
        ]);

        SaranPublik::create([
            'kode_laporan' => 'SRN-20261029-0002',
            'kategori' => 'kelangkaan_stok',
            'kontak_pelapor' => '08222222222',
            'judul_laporan' => 'LPG 3KG Kosong',
            'isi_laporan' => 'Sudah 3 hari pangkalan kosong',
            'nama_komoditas_dilaporkan' => 'LPG 3 KG',
            'harga_dilaporkan' => 22000,
            'status' => 'masuk',
        ]);

        // Filter lonjakan_harga
        $respHarga = $this->actingAs($user)->get(route('saran-publik.index', ['kategori' => 'lonjakan_harga']));
        $respHarga->assertOk();
        $respHarga->assertSee('Harga Beras Naik');
        $respHarga->assertDontSee('LPG 3KG Kosong');

        // Filter kelangkaan_stok
        $respStok = $this->actingAs($user)->get(route('saran-publik.index', ['kategori' => 'kelangkaan_stok']));
        $respStok->assertOk();
        $respStok->assertSee('LPG 3KG Kosong');
        $respStok->assertDontSee('Harga Beras Naik');
    }

    public function test_saran_publik_index_displays_reports_without_input_form(): void
    {
        $user = User::factory()->create(['username' => 'admin_saran_page', 'role' => 'admin_kabupaten']);

        $response = $this->actingAs($user)->get(route('saran-publik.index'));
        $response->assertOk();
        $response->assertSee('Filter Kategori');
        $response->assertSee('Daftar Saran & Laporan Masuk', false);
        $response->assertDontSee('Form Input Saran & Laporan');
        $response->assertDontSee('Simpan Laporan');
    }

    public function test_portal_publik_renders_with_landmark_background_and_header_photos(): void
    {
        $response = $this->get(route('portal-publik'));
        $response->assertOk();
        $response->assertSee('KANAL PARTISIPASI PUBLIK');
        $response->assertSee('background_gorut.jpg');
        $response->assertSee('images/bupati.png');
        $response->assertSee('images/wabup.png');
        $response->assertSee('Masuk Sistem');
    }

    public function test_saran_publik_displays_contact_even_when_anonymous(): void
    {
        $user = User::factory()->create(['username' => 'admin_contact_tester', 'role' => 'admin_kabupaten']);

        SaranPublik::create([
            'kode_laporan' => 'SRN-20261029-9999',
            'kategori' => 'kelangkaan_stok',
            'kontak_pelapor' => '087788990011',
            'nama_pelapor' => 'Warga Rahasia',
            'anonim' => true,
            'judul_laporan' => 'Gas 3kg Langka',
            'isi_laporan' => 'Pangkalan selalu kosong',
            'nama_komoditas_dilaporkan' => 'LPG 3 Kg',
            'harga_dilaporkan' => 22000,
            'status' => 'masuk',
        ]);

        $response = $this->actingAs($user)->get(route('saran-publik.index'));
        $response->assertOk();
        $response->assertSee('Gas 3kg Langka');
        $response->assertSee('Anonim di Portal');
        $response->assertSee('087788990011');
        $response->assertSee('Kontak Pelapor:');
    }
}
