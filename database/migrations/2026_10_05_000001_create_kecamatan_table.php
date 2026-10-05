<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kecamatan', 20)->unique();
            $table->string('nama_kecamatan', 100);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->integer('jumlah_desa')->default(0);
            $table->integer('jumlah_penduduk')->nullable();
            $table->enum('zona_risiko', ['hijau', 'kuning', 'merah'])->default('hijau');
            $table->text('catatan_geografis')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kecamatan');
    }
};
