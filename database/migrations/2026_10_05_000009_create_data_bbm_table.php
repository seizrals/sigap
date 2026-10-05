<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_bbm', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_input');
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->foreignId('spbu_id')->constrained('spbu')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('jenis_bbm', ['Pertalite (JBKP)', 'Pertamax', 'Pertamax Turbo', 'Solar (JBT)', 'Dexlite', 'Pertamina Dex', 'Bio Solar'])->default('Pertalite (JBKP)');

            $table->decimal('stok_tersedia_kl', 10, 3)->nullable();
            $table->decimal('stok_masuk_hari_ini_kl', 10, 3)->nullable();
            $table->decimal('penjualan_harian_rata_kl', 10, 3)->nullable();
            $table->decimal('estimasi_hari_ketersediaan', 6, 2)->nullable();

            $table->boolean('distribusi_nelayan')->default(false);
            $table->decimal('kuota_nelayan_harian_kl', 10, 3)->nullable();
            $table->decimal('realisasi_nelayan_kl', 10, 3)->nullable();

            $table->boolean('distribusi_pertanian')->default(false);
            $table->decimal('kuota_pertanian_harian_kl', 10, 3)->nullable();
            $table->decimal('realisasi_pertanian_kl', 10, 3)->nullable();

            $table->enum('status_antrean', ['tidak_ada', 'pendek', 'panjang', 'sangat_panjang'])->default('tidak_ada');
            $table->integer('estimasi_waktu_tunggu_menit')->nullable();
            $table->enum('status_ketersediaan', ['aman', 'waspada', 'menipis', 'kosong'])->default('aman');
            $table->enum('status_verifikasi', ['draft', 'pending', 'disetujui', 'ditolak'])->default('draft');

            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();

            $table->decimal('harga_jual_per_liter', 15, 2)->nullable();
            $table->text('keterangan_kendala')->nullable();
            $table->json('foto_bukti_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('tanggal_input');
            $table->index('kecamatan_id');
            $table->index('spbu_id');
            $table->index('jenis_bbm');
            $table->index('status_verifikasi');
            $table->index(['tanggal_input', 'kecamatan_id', 'jenis_bbm'], 'idx_bbm_tgl_kec_jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_bbm');
    }
};
