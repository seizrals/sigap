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

    public function test_pangan_page_renders_direct_input_form_without_popup_or_history_table(): void
    {
        $user = User::factory()->create([
            'username' => 'pangan-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
            'is_active' => true,
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

        $response = $this->actingAs($user)->get(route('input.pangan'));

        $response->assertOk();
        $response->assertSee('Form Input Pangan Strategis');
        $response->assertSee('Cabai Rawit');
        $response->assertSee('Beras Medium');
        $response->assertSee('Harga Sebelumnya');
        $response->assertSee('opacity-60');
        $response->assertSee('Tanggal Input');
        $response->assertDontSee('Periode Sebelumnya'); // tanggal sebelumnya dihapus
        $response->assertDontSee('pangan-modal'); // tidak popup saja
        $response->assertDontSee('Tambah Permintaan'); // tombol modal dihapus
    }

    public function test_pangan_auto_resolves_previous_price_and_date_sequence(): void
    {
        $user = User::factory()->create([
            'username' => 'pangan-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
            'is_active' => true,
        ]);
        $komoditas = Komoditas::create([
            'kode_komoditas' => 'CBI-RWT',
            'nama_komoditas' => 'Cabai Rawit',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);

        // Langkah 1: Input tanggal 3 saat belum ada data sama sekali -> harga sebelumnya masih 0
        $payloadTgl3 = [
            'tanggal_input' => '2026-11-03',
            'kecamatan_id' => $kecamatan->id,
            'entries' => [
                $komoditas->id => [
                    'komoditas_id' => $komoditas->id,
                    'kecamatan_id' => $kecamatan->id,
                    'harga_hari_ini' => 50000,
                    'stok_tersedia' => 100,
                    'satuan_stok' => 'Kg',
                    'status_ketersediaan' => 'aman',
                ],
            ],
        ];

        $res1 = $this->actingAs($user)->post(route('input.pangan.store'), $payloadTgl3);
        $res1->assertSessionHasNoErrors();
        $res1->assertRedirect(route('input.pangan'));

        $recordTgl3 = DataPangan::whereDate('tanggal_input', '2026-11-03')->firstOrFail();
        $this->assertEquals(50000, (float) $recordTgl3->harga_hari_ini);
        $this->assertEquals(0, (float) $recordTgl3->harga_hari_sebelumnya);
        $this->assertNull($recordTgl3->tanggal_input_sebelumnya);
        $this->assertSame('stabil', $recordTgl3->status_harga);

        // Langkah 2: Input tanggal 4 -> harga tgl 3 (50000) otomatis masuk di harga sebelumnya tgl 4
        $payloadTgl4 = [
            'tanggal_input' => '2026-11-04',
            'kecamatan_id' => $kecamatan->id,
            'entries' => [
                $komoditas->id => [
                    'komoditas_id' => $komoditas->id,
                    'kecamatan_id' => $kecamatan->id,
                    'harga_hari_ini' => 52000,
                    'status_ketersediaan' => 'aman',
                ],
            ],
        ];

        $res2 = $this->actingAs($user)->post(route('input.pangan.store'), $payloadTgl4);
        $res2->assertRedirect(route('input.pangan'));

        $recordTgl4 = DataPangan::whereDate('tanggal_input', '2026-11-04')->firstOrFail();
        $this->assertEquals(52000, (float) $recordTgl4->harga_hari_ini);
        $this->assertEquals(50000, (float) $recordTgl4->harga_hari_sebelumnya);
        $this->assertSame('2026-11-03', $recordTgl4->tanggal_input_sebelumnya?->format('Y-m-d'));
        $this->assertEquals(2000, (float) $recordTgl4->perubahan_harga_nominal);
        $this->assertEquals(4.0, (float) $recordTgl4->perubahan_harga_persen);

        // Langkah 3: Input tanggal 5 -> harga tgl 4 (52000) otomatis masuk di harga sebelumnya tgl 5
        $payloadTgl5 = [
            'tanggal_input' => '2026-11-05',
            'kecamatan_id' => $kecamatan->id,
            'entries' => [
                $komoditas->id => [
                    'komoditas_id' => $komoditas->id,
                    'kecamatan_id' => $kecamatan->id,
                    'harga_hari_ini' => 55000,
                    'status_ketersediaan' => 'aman',
                ],
            ],
        ];

        $res3 = $this->actingAs($user)->post(route('input.pangan.store'), $payloadTgl5);
        $res3->assertRedirect(route('input.pangan'));

        $recordTgl5 = DataPangan::whereDate('tanggal_input', '2026-11-05')->firstOrFail();
        $this->assertEquals(55000, (float) $recordTgl5->harga_hari_ini);
        $this->assertEquals(52000, (float) $recordTgl5->harga_hari_sebelumnya);
        $this->assertSame('2026-11-04', $recordTgl5->tanggal_input_sebelumnya?->format('Y-m-d'));
        $this->assertEquals(3000, (float) $recordTgl5->perubahan_harga_nominal);
    }

    public function test_pangan_previous_prices_endpoint_returns_json(): void
    {
        $user = User::factory()->create([
            'username' => 'pangan-operator',
            'role' => 'admin_kabupaten',
        ]);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
            'is_active' => true,
        ]);
        $komoditas = Komoditas::create([
            'kode_komoditas' => 'CBI-RWT',
            'nama_komoditas' => 'Cabai Rawit',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
            'is_strategis' => true,
            'is_active' => true,
        ]);

        DataPangan::create([
            'tanggal_input' => '2026-11-03',
            'kecamatan_id' => $kecamatan->id,
            'komoditas_id' => $komoditas->id,
            'user_id' => $user->id,
            'harga_hari_ini' => 48000,
            'harga_hari_sebelumnya' => 0,
            'status_ketersediaan' => 'aman',
        ]);

        $response = $this->actingAs($user)->getJson(route('input.pangan.previous-prices', [
            'kecamatan_id' => $kecamatan->id,
            'tanggal_input' => '2026-11-04',
        ]));

        $response->assertOk();
        $response->assertJson([
            $komoditas->id => [
                'harga' => 48000,
                'tanggal' => '03/11/2026',
                'tanggal_raw' => '2026-11-03',
            ],
        ]);
    }
}
