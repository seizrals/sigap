<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasar_lokasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->string('nama_lokasi', 150);
            $table->string('tipe_lokasi', 50);
            $table->string('alamat', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('penanggung_jawab', 100)->nullable();
            $table->string('no_hp_pj', 20)->nullable();
            $table->integer('jumlah_pedagang')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('kecamatan_id');
            $table->index('tipe_lokasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasar_lokasi');
    }
};
