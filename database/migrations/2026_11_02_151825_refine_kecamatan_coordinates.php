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
            'Atinggola' => ['lat' => 0.9400, 'lng' => 123.3800],
            'Kwandang' => ['lat' => 0.8350, 'lng' => 122.9250],
            'Sumalata' => ['lat' => 0.9800, 'lng' => 122.4800],
            'Gentuma Raya' => ['lat' => 0.9100, 'lng' => 123.1500],
            'Anggrek' => ['lat' => 0.8300, 'lng' => 122.8000],
            'Ponelo Kepulauan' => ['lat' => 0.8850, 'lng' => 122.8850],
            'Biau' => ['lat' => 0.9600, 'lng' => 122.3400],
            'Tolinggula' => ['lat' => 0.9450, 'lng' => 122.2600],
            'Monano' => ['lat' => 0.8600, 'lng' => 122.7400],
            'Sumalata Timur' => ['lat' => 0.9700, 'lng' => 122.5700],
            'Tomilito' => ['lat' => 0.8500, 'lng' => 123.0200],
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
        // No action needed
    }
};
