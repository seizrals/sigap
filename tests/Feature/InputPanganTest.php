<?php

namespace Tests\Feature;

use App\Models\DataPangan;
use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InputPanganTest extends TestCase
{
    use RefreshDatabase;

    public function test_pangan_page_lists_saved_records_and_request_modal(): void
    {
        $user = User::factory()->create([
            'username' => 'pangan-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);
        $komoditas = Komoditas::create([
            'kode_komoditas' => 'CBI-RWT',
            'nama_komoditas' => 'Cabai Rawit',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);
        Komoditas::create([
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
            'komoditas_id' => $komoditas->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 65000,
            'harga_hari_sebelumnya' => 60000,
            'status_ketersediaan' => 'waspada',
            'status_verifikasi' => 'pending',
        ]);

        $response = $this->actingAs($user)->get(route('input.pangan'));

        $response->assertOk();
        $response->assertSee('Tambah Permintaan');
        $response->assertSee('Cabai Rawit');
        $response->assertSee('Beras Medium');
        $response->assertSee('data-edit-pangan', false);
        $response->assertSee('Harga Sebelumnya');
        $response->assertSee('Periode Sebelumnya');
        $response->assertSee('Periode Saat Ini');
        $response->assertSee('Distribusi Dari Luar');
        $response->assertDontSee('Keterangan / Kendala Lapangan');
        $response->assertDontSee('Jenis Komoditas');
    }

    public function test_pangan_request_can_be_created_and_edited(): void
    {
        $user = User::factory()->create([
            'username' => 'pangan-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
        ]);
        $komoditas = Komoditas::create([
            'kode_komoditas' => 'CBI-RWT',
            'nama_komoditas' => 'Cabai Rawit',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);
        $komoditasLain = Komoditas::create([
            'kode_komoditas' => 'BRG-MDY',
            'nama_komoditas' => 'Beras Medium',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);
        $komoditasKosong = Komoditas::create([
            'kode_komoditas' => 'MNY-GRM',
            'nama_komoditas' => 'Minyak Goreng',
            'kategori' => 'pangan',
            'satuan' => 'Liter',
            'is_strategis' => true,
            'is_active' => true,
        ]);
        $payload = [
            'periode_sebelumnya' => '2026-10-27',
            'periode_saat_ini' => '2026-10-28',
            'entries' => [
                $komoditas->id => [
                    'komoditas_id' => $komoditas->id,
                    'kecamatan_id' => $kecamatan->id,
                    'harga_hari_ini' => 22000,
                    'harga_hari_sebelumnya' => 20000,
                    'stok_tersedia' => 120,
                    'satuan_stok' => 'Kg',
                    'sumber_distribusi_masuk' => '2 ton dari Gorontalo',
                    'status_ketersediaan' => 'aman',
                ],
                $komoditasLain->id => [
                    'komoditas_id' => $komoditasLain->id,
                    'kecamatan_id' => $kecamatan->id,
                    'harga_hari_ini' => 15000,
                    'harga_hari_sebelumnya' => 14500,
                    'status_ketersediaan' => 'aman',
                ],
                $komoditasKosong->id => [
                    'komoditas_id' => $komoditasKosong->id,
                    'satuan_stok' => 'Liter',
                ],
            ],
        ];

        $response = $this->actingAs($user)->post(route('input.pangan.store'), $payload);

        $response->assertRedirect(route('input.pangan'));
        $this->assertDatabaseHas('data_pangan', [
            'komoditas_id' => $komoditas->id,
            'harga_hari_ini' => 22000,
            'perubahan_harga_nominal' => 2000,
            'perubahan_harga_persen' => 10,
            'status_verifikasi' => 'pending',
            'keterangan_lapangan' => null,
        ]);
        $this->assertDatabaseHas('data_pangan', [
            'komoditas_id' => $komoditasLain->id,
            'harga_hari_ini' => 15000,
            'status_verifikasi' => 'pending',
        ]);
        $recordKomoditasLain = DataPangan::where('komoditas_id', $komoditasLain->id)->firstOrFail();
        $this->assertSame('2026-10-28', $recordKomoditasLain->tanggal_input->toDateString());
        $this->assertSame('2026-10-27', $recordKomoditasLain->tanggal_input_sebelumnya->toDateString());
        $this->assertDatabaseCount('data_pangan', 2);

        $record = DataPangan::where('komoditas_id', $komoditas->id)->firstOrFail();
        $this->assertSame('2026-10-28', $record->tanggal_input->toDateString());
        $this->assertSame('2026-10-27', $record->tanggal_input_sebelumnya->toDateString());
        $payload = [
            'periode_sebelumnya' => '2026-10-27',
            'periode_saat_ini' => '2026-10-28',
            'entries' => [
                $komoditas->id => [
                    'komoditas_id' => $komoditas->id,
                    'kecamatan_id' => $kecamatan->id,
                    'harga_hari_ini' => 24000,
                    'harga_hari_sebelumnya' => 20000,
                    'stok_tersedia' => 120,
                    'satuan_stok' => 'Kg',
                    'sumber_distribusi_masuk' => '2 ton dari Gorontalo',
                    'status_ketersediaan' => 'waspada',
                ],
            ],
        ];

        $response = $this->put(route('input.pangan.update', $record), $payload);

        $response->assertRedirect(route('input.pangan'));
        $this->assertDatabaseHas('data_pangan', [
            'id' => $record->id,
            'harga_hari_ini' => 24000,
            'perubahan_harga_nominal' => 4000,
            'perubahan_harga_persen' => 20,
            'status_harga' => 'naik_tinggi',
            'status_ketersediaan' => 'waspada',
            'status_verifikasi' => 'pending',
        ]);
        $record->refresh();
        $this->assertSame('2026-10-28', $record->tanggal_input->toDateString());
        $this->assertSame('2026-10-27', $record->tanggal_input_sebelumnya->toDateString());
    }
}
