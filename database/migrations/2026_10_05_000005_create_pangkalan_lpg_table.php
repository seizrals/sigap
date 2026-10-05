<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pangkalan_lpg', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->foreignId('desa_id')->constrained('desa')->cascadeOnDelete();
            $table->string('kode_pangkalan', 30)->unique();
            $table->string('nama_pangkalan', 150);
            $table->string('nama_pemilik', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('no_izin_usaha', 50)->nullable();
            $table->enum('status_izin', ['aktif', 'tidak_aktif', 'proses'])->default('aktif');
            $table->integer('kapasitas_stok_maks')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('kecamatan_id');
            $table->index('desa_id');
            $table->index('status_izin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pangkalan_lpg');
    }
};
