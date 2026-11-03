<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('kecamatan')
            ->where('nama_kecamatan', 'Ponelo Kepulauan')
            ->update([
                'latitude' => 0.8766,
                'longitude' => 122.8866,
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('kecamatan')
            ->where('nama_kecamatan', 'Ponelo Kepulauan')
            ->update([
                'latitude' => 0.8850,
                'longitude' => 122.8750,
            ]);
    }
};

