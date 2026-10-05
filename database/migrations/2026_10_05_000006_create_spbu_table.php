<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spbu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->string('kode_spbu', 30)->unique();
            $table->string('nama_spbu', 150);
            $table->string('tipe_spbu', 50)->default('SPBU Umum');
            $table->string('nama_pengelola', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('jenis_bbm_tersedia')->nullable();
            $table->integer('kapasitas_tanki_pertalite_kl')->nullable();
            $table->integer('kapasitas_tanki_pertamax_kl')->nullable();
            $table->integer('kapasitas_tanki_solar_kl')->nullable();
            $table->integer('kapasitas_tanki_dexlite_kl')->nullable();
            $table->boolean('melayani_nelayan')->default(false);
            $table->boolean('melayani_pertanian')->default(false);
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('kecamatan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spbu');
    }
};
