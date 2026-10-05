<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saran_publik', function (Blueprint $table) {
            $table->id();
            $table->string('kode_laporan', 30)->unique();
            $table->enum('kategori', ['lonjakan_harga', 'kelangkaan_stok', 'saran_kebijakan', 'pelanggaran_het', 'lainnya']);
            $table->enum('sub_kategori', ['pangan', 'lpg', 'bbm', 'umum'])->default('umum');

            $table->string('nama_pelapor', 100)->nullable();
            $table->string('kontak_pelapor', 50)->nullable();
            $table->boolean('anonim')->default(false);

            $table->foreignId('kecamatan_id')->nullable()->constrained('kecamatan')->nullOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->string('lokasi_spesifik', 200)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->string('judul_laporan', 200);
            $table->longText('isi_laporan');

            $table->decimal('harga_dilaporkan', 15, 2)->nullable();
            $table->string('nama_komoditas_dilaporkan', 150)->nullable();

            $table->enum('status', ['masuk', 'diverifikasi', 'diproses', 'ditindaklanjuti', 'selesai', 'diarsipkan', 'tidak_valid'])->default('masuk');
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('waktu_validasi')->nullable();

            $table->longText('catatan_validasi')->nullable();
            $table->longText('tindak_lanjut')->nullable();
            $table->foreignId('ditindaklanjuti_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('waktu_tindak_lanjut')->nullable();

            $table->json('foto_bukti_path')->nullable();

            $table->boolean('menghasilkan_ews')->default(false);
            $table->foreignId('ews_alert_id')->nullable()->constrained('ews_alerts')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('kategori');
            $table->index('status');
            $table->index('kecamatan_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saran_publik');
    }
};
