<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kecamatan')) {
            // Perbaiki Jomilito -> Tomilito
            DB::table('kecamatan')
                ->where('nama_kecamatan', 'Jomilito')
                ->orWhere('nama_kecamatan', 'like', '%jomilito%')
                ->update([
                    'nama_kecamatan' => 'Tomilito',
                    'kode_kecamatan' => '7505021',
                    'latitude' => 0.8800,
                    'longitude' => 122.9500,
                ]);

            // Perbaiki Dengilo -> Monano
            DB::table('kecamatan')
                ->where('nama_kecamatan', 'Dengilo')
                ->orWhere('nama_kecamatan', 'like', '%dengilo%')
                ->update([
                    'nama_kecamatan' => 'Monano',
                    'kode_kecamatan' => '7505031',
                    'latitude' => 0.8500,
                    'longitude' => 122.8200,
                ]);

            // Perbaiki Buntulia -> Sumalata Timur
            DB::table('kecamatan')
                ->where('nama_kecamatan', 'Buntulia')
                ->orWhere('nama_kecamatan', 'like', '%buntulia%')
                ->update([
                    'nama_kecamatan' => 'Sumalata Timur',
                    'kode_kecamatan' => '7505041',
                    'latitude' => 0.9500,
                    'longitude' => 122.5800,
                ]);

            // Pastikan jika Tomilito belum ada sama sekali, tambahkan
            if (!DB::table('kecamatan')->where('nama_kecamatan', 'Tomilito')->exists()) {
                DB::table('kecamatan')->insert([
                    'kode_kecamatan' => '7505021',
                    'nama_kecamatan' => 'Tomilito',
                    'latitude' => 0.8800,
                    'longitude' => 122.9500,
                    'jumlah_desa' => 10,
                    'zona_risiko' => 'hijau',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Pastikan jika Monano belum ada sama sekali, tambahkan
            if (!DB::table('kecamatan')->where('nama_kecamatan', 'Monano')->exists()) {
                DB::table('kecamatan')->insert([
                    'kode_kecamatan' => '7505031',
                    'nama_kecamatan' => 'Monano',
                    'latitude' => 0.8500,
                    'longitude' => 122.8200,
                    'jumlah_desa' => 10,
                    'zona_risiko' => 'kuning',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Pastikan jika Sumalata Timur belum ada sama sekali, tambahkan
            if (!DB::table('kecamatan')->where('nama_kecamatan', 'Sumalata Timur')->exists()) {
                DB::table('kecamatan')->insert([
                    'kode_kecamatan' => '7505041',
                    'nama_kecamatan' => 'Sumalata Timur',
                    'latitude' => 0.9500,
                    'longitude' => 122.5800,
                    'jumlah_desa' => 10,
                    'zona_risiko' => 'hijau',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kecamatan')) {
            DB::table('kecamatan')
                ->where('nama_kecamatan', 'Tomilito')
                ->update(['nama_kecamatan' => 'Jomilito']);
        }
    }
};

