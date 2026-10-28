<?php

namespace Tests\Feature;

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
}
