<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ews_alerts', function (Blueprint $table) {
            $table->id();
            $table->string('kode_alert', 30)->unique();
            $table->enum('sumber_deteksi', ['otomatis_sistem', 'manual_operator', 'laporan_masyarakat', 'manual_admin'])->default('otomatis_sistem');
            $table->enum('kategori', ['pangan', 'lpg', 'bbm', 'multikomoditas']);
            $table->enum('tingkat_risiko', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('sedang');
            $table->enum('status', ['terbuka', 'diproses', 'selesai', 'diabaikan'])->default('terbuka');

            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->foreignId('komoditas_id')->nullable()->constrained('komoditas')->nullOnDelete();
            $table->foreignId('pangkalan_lpg_id')->nullable()->constrained('pangkalan_lpg')->nullOnDelete();
            $table->foreignId('spbu_id')->nullable()->constrained('spbu')->nullOnDelete();

            $table->string('judul_alert', 200);
            $table->longText('analisis_sistem');
            $table->longText('analisis_penyebab')->nullable();
            $table->json('data_pemicu')->nullable();
            $table->json('nilai_terukur')->nullable();

            $table->decimal('nilai_indikasi_harga_persen', 8, 2)->nullable();
            $table->decimal('nilai_indikasi_stok_persen', 8, 2)->nullable();
            $table->integer('lama_zonasi_berurutan_hari')->nullable();

            $table->timestamp('waktu_terdeteksi')->useCurrent();
            $table->timestamp('waktu_terakhir_update')->useCurrentOnUpdate()->nullable();

            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('waktu_penanganan')->nullable();

            $table->longText('tindak_lanjut')->nullable();
            $table->longText('catatan_penanganan')->nullable();
            $table->text('bukti_penanganan_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('kategori');
            $table->index('tingkat_risiko');
            $table->index('status');
            $table->index('kecamatan_id');
            $table->index('waktu_terdeteksi');
        });

        Schema::create('ews_rekomendasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ews_alert_id')->constrained('ews_alerts')->cascadeOnDelete();

            $table->enum('tipe_rekomendasi', ['operasi_pasar', 'intervensi_distribusi', 'sidak_lapangan', 'rapat_koordinasi', 'imbauan_publik', 'lainnya']);
            $table->string('judul_rekomendasi', 200);
            $table->longText('detail_rekomendasi');

            $table->string('target_pelaksana', 150)->nullable();
            $table->integer('estimasi_waktu_pelaksanaan_jam')->nullable();
            $table->decimal('estimasi_biaya', 15, 2)->nullable();

            $table->enum('status_rekomendasi', ['diusulkan', 'disetujui', 'ditolak', 'dijalankan', 'selesai'])->default('diusulkan');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('waktu_persetujuan')->nullable();

            $table->longText('catatan_hasil')->nullable();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->index('ews_alert_id');
            $table->index('tipe_rekomendasi');
            $table->index('status_rekomendasi');
        });

        Schema::create('monitoring_triggers', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();

            $table->json('pemicu_harga')->nullable();
            $table->json('pemicu_pangan')->nullable();
            $table->json('pemicu_lpg')->nullable();
            $table->json('pemicu_bbm')->nullable();

            $table->integer('total_pemicu_terdeteksi')->default(0);

            $table->enum('status_komposit', ['aman', 'waspada', 'bahaya'])->default('aman');
            $table->timestamp('waktu_update_terakhir')->nullable();

            $table->unique(['tanggal', 'kecamatan_id']);
            $table->index('tanggal');
            $table->index('kecamatan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monitoring_triggers');
        Schema::dropIfExists('ews_rekomendasi');
        Schema::dropIfExists('ews_alerts');
    }
};
