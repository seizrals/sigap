<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('kecamatan')) {
            return;
        }

        // 1. Reassign any orphaned duplicate records (kode 7505021, 7505031, 7505041) to official kecamatan IDs
        $duplicates = [
            '7505021' => '7505110', // Tomilito
            '7505031' => '7505090', // Monano
            '7505041' => '7505100', // Sumalata Timur
        ];

        $fkTables = [
            'users', 'desa', 'pasar_lokasi', 'pangkalan_lpg', 'spbu',
            'data_pangan', 'data_lpg', 'data_bbm', 'ews_alerts', 'saran_publik'
        ];

        foreach ($duplicates as $oldKode => $newKode) {
            $oldKec = DB::table('kecamatan')->where('kode_kecamatan', $oldKode)->first();
            $newKec = DB::table('kecamatan')->where('kode_kecamatan', $newKode)->first();

            if ($oldKec && $newKec) {
                foreach ($fkTables as $table) {
                    if (Schema::hasTable($table) && Schema::hasColumn($table, 'kecamatan_id')) {
                        DB::table($table)->where('kecamatan_id', $oldKec->id)->update(['kecamatan_id' => $newKec->id]);
                    }
                }
                // Delete duplicate kecamatan record
                DB::table('kecamatan')->where('id', $oldKec->id)->delete();
            }
        }

        // 2. Update accurate coordinates for all 11 official Kecamatan based on real Kantor Camat / Kantor Desa
        $officialKecamatans = [
            '7505010' => [
                'nama' => 'Atinggola',
                'lat' => 0.8991,
                'lng' => 123.1166, // Desa Atinggola / Pusat Pesisir
            ],
            '7505020' => [
                'nama' => 'Kwandang',
                'lat' => 0.8351,
                'lng' => 122.9197, // Pusat Kota / Kantor Camat Kwandang
            ],
            '7505030' => [
                'nama' => 'Sumalata',
                'lat' => 0.9405,
                'lng' => 122.3935, // Desa Bulontio Barat (Pusat Kecamatan Sumalata)
            ],
            '7505040' => [
                'nama' => 'Gentuma Raya',
                'lat' => 0.8722,
                'lng' => 123.0243, // Desa Gentuma (Pusat Kecamatan Gentuma Raya)
            ],
            '7505050' => [
                'nama' => 'Anggrek',
                'lat' => 0.8455,
                'lng' => 122.7792, // Kantor Camat Anggrek
            ],
            '7505060' => [
                'nama' => 'Ponelo Kepulauan',
                'lat' => 0.8850,
                'lng' => 122.8750, // Pulau Ponelo / Desa Ponelo
            ],
            '7505070' => [
                'nama' => 'Biau',
                'lat' => 0.9833,
                'lng' => 122.2500, // Desa Biau (Pusat Kecamatan Biau)
            ],
            '7505080' => [
                'nama' => 'Tolinggula',
                'lat' => 0.9946,
                'lng' => 122.1894, // Desa Tolinggula Pantai / Tolinggula Tengah
            ],
            '7505090' => [
                'nama' => 'Monano',
                'lat' => 0.8687,
                'lng' => 122.6679, // Desa Monano (Pusat Kecamatan Monano)
            ],
            '7505100' => [
                'nama' => 'Sumalata Timur',
                'lat' => 0.9301,
                'lng' => 122.4958, // Desa Buladu (Ibukota Kecamatan Sumalata Timur)
            ],
            '7505110' => [
                'nama' => 'Tomilito',
                'lat' => 0.8742,
                'lng' => 122.9615, // Desa Dambalo (Pusat Kecamatan Tomilito)
            ],
        ];

        foreach ($officialKecamatans as $kode => $data) {
            DB::table('kecamatan')
                ->where('kode_kecamatan', $kode)
                ->orWhere(function ($query) use ($kode, $data) {
                    $query->where('nama_kecamatan', $data['nama'])
                        ->whereNotIn('kode_kecamatan', ['7505021', '7505031', '7505041']);
                })
                ->update([
                    'kode_kecamatan' => $kode,
                    'nama_kecamatan' => $data['nama'],
                    'latitude' => $data['lat'],
                    'longitude' => $data['lng'],
                    'is_active' => true,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No action needed
    }
};

