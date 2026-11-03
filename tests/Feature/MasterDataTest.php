<?php

namespace Tests\Feature;

use App\Models\Kecamatan;
use App\Models\Komoditas;
use App\Models\Spbu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_master_data_index_and_has_delete_modal(): void
    {
        $user = User::factory()->create(['role' => 'admin_kabupaten']);

        $response = $this->actingAs($user)->get(route('master-data.index'));

        $response->assertOk();
        $response->assertSee('id="deleteModal"', false);
        $response->assertSee('Konfirmasi Hapus');
        $response->assertSee('Pangan Strategis');
        $response->assertDontSee('<option value="lpg">LPG</option>', false);
        $response->assertDontSee('<option value="bbm">BBM</option>', false);
    }

    public function test_can_store_komoditas_without_kode_komoditas(): void
    {
        $user = User::factory()->create(['role' => 'admin_kabupaten']);

        $payload = [
            'nama_komoditas' => 'Bawang Goreng Krispi',
            'kategori' => 'pangan',
            'satuan' => 'Toples',
        ];

        $response = $this->actingAs($user)->post(route('master-data.komoditas.store'), $payload);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('master-data.index', ['tab' => 'komoditas']));

        $this->assertDatabaseHas('komoditas', [
            'nama_komoditas' => 'Bawang Goreng Krispi',
            'kategori' => 'pangan',
            'satuan' => 'Toples',
        ]);
    }

    public function test_can_update_komoditas(): void
    {
        $user = User::factory()->create(['role' => 'admin_kabupaten']);
        $komoditas = Komoditas::create([
            'nama_komoditas' => 'Tepung Beras',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
        ]);

        $response = $this->actingAs($user)->put(route('master-data.komoditas.update', $komoditas), [
            'nama_komoditas' => 'Tepung Beras Rose Brand',
            'kategori' => 'pangan',
            'satuan' => 'Bungkus',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('master-data.index', ['tab' => 'komoditas']));

        $this->assertDatabaseHas('komoditas', [
            'id' => $komoditas->id,
            'nama_komoditas' => 'Tepung Beras Rose Brand',
            'satuan' => 'Bungkus',
        ]);
    }

    public function test_can_delete_komoditas(): void
    {
        $user = User::factory()->create(['role' => 'admin_kabupaten']);
        $komoditas = Komoditas::create([
            'nama_komoditas' => 'Ubi Jalar',
            'kategori' => 'pangan',
            'satuan' => 'Kg',
        ]);

        $response = $this->actingAs($user)->delete(route('master-data.komoditas.destroy', $komoditas));

        $response->assertRedirect(route('master-data.index', ['tab' => 'komoditas']));
        $this->assertSoftDeleted('komoditas', ['id' => $komoditas->id]);
    }

    public function test_can_store_and_delete_spbu(): void
    {
        $user = User::factory()->create(['role' => 'admin_kabupaten']);
        $kecamatan = Kecamatan::create([
            'kode_kecamatan' => '7505020',
            'nama_kecamatan' => 'Kwandang',
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->post(route('master-data.spbu.store'), [
            'nama_spbu' => 'SPBU Molingkapoto 74.962.01',
            'kecamatan_id' => $kecamatan->id,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('spbu', [
            'nama_spbu' => 'SPBU Molingkapoto 74.962.01',
            'kecamatan_id' => $kecamatan->id,
        ]);

        $spbu = Spbu::where('nama_spbu', 'SPBU Molingkapoto 74.962.01')->firstOrFail();
        $delResponse = $this->actingAs($user)->delete(route('master-data.spbu.destroy', $spbu));

        $delResponse->assertRedirect(route('master-data.index', ['tab' => 'spbu']));
        $this->assertSoftDeleted('spbu', ['id' => $spbu->id]);
    }
}

