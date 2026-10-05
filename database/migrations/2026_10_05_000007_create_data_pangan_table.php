<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_pangan', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_input');
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->foreignId('pasar_lokasi_id')->nullable()->constrained('pasar_lokasi')->nullOnDelete();
            $table->foreignId('komoditas_id')->constrained('komoditas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->decimal('harga_hari_ini', 15, 2);
            $table->decimal('harga_hari_sebelumnya', 15, 2)->nullable();
            $table->decimal('perubahan_harga_nominal', 15, 2)->nullable();
            $table->decimal('perubahan_harga_persen', 8, 2)->nullable();

            $table->decimal('stok_tersedia', 12, 2)->nullable();
            $table->string('satuan_stok', 30)->nullable();
            $table->decimal('stok_masuk_hari_ini', 12, 2)->nullable();
            $table->string('sumber_distribusi_masuk', 255)->nullable();
            $table->decimal('stok_keluar_hari_ini', 12, 2)->nullable();

            $table->enum('status_harga', ['stabil', 'naik_wajar', 'naik_tinggi', 'turun', 'harga_tinggi'])->default('stabil');
            $table->enum('status_ketersediaan', ['aman', 'waspada', 'kurang', 'kosong'])->default('aman');
            $table->enum('status_verifikasi', ['draft', 'pending', 'disetujui', 'ditolak'])->default('draft');

            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();

            $table->text('keterangan_lapangan')->nullable();
            $table->json('foto_bukti_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('tanggal_input');
            $table->index('kecamatan_id');
            $table->index('komoditas_id');
            $table->index('status_verifikasi');
            $table->index(['tanggal_input', 'kecamatan_id', 'komoditas_id'], 'idx_pangan_tgl_kec_kom');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_pangan');
    }
};
