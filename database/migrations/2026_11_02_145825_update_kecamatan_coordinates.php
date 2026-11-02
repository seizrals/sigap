<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $coordinates = [
            'Atinggola' => ['lat' => 0.8872, 'lng' => 123.3614],
            'Kwandang' => ['lat' => 0.8389, 'lng' => 122.9234],
            'Sumalata' => ['lat' => 0.9856, 'lng' => 122.4939],
            'Gentuma Raya' => ['lat' => 0.9167, 'lng' => 123.1500],
            'Anggrek' => ['lat' => 0.8333, 'lng' => 122.8333],
            'Ponelo Kepulauan' => ['lat' => 0.8833, 'lng' => 122.8833],
            'Biau' => ['lat' => 0.9667, 'lng' => 122.3333],
            'Tolinggula' => ['lat' => 0.9547, 'lng' => 122.2575],
            'Monano' => ['lat' => 0.8667, 'lng' => 122.7500],
            'Sumalata Timur' => ['lat' => 0.9750, 'lng' => 122.5833],
            'Tomilito' => ['lat' => 0.8500, 'lng' => 123.0167],
        ];

        foreach ($coordinates as $name => $coords) {
            DB::table('kecamatan')
                ->where('nama_kecamatan', $name)
                ->update([
                    'latitude' => $coords['lat'],
                    'longitude' => $coords['lng'],
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse coordinates to something reasonable if needed, or leave as is
    }
};
