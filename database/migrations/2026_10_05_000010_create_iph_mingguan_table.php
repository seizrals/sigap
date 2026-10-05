<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iph_mingguan', function (Blueprint $table) {
            $table->id();
            $table->year('tahun');
            $table->tinyInteger('bulan');
            $table->string('minggu_ke', 10);
            $table->string('periode_label', 50);
            $table->date('tanggal_awal_periode');
            $table->date('tanggal_akhir_periode');
            $table->date('tanggal_publikasi');

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('sumber_data', 100)->default('BPS Kabupaten Gorontalo Utara');

            $table->decimal('iph_kabupaten', 10, 4)->nullable();
            $table->decimal('iph_sebelumnya', 10, 4)->nullable();
            $table->decimal('perubahan_iph_nominal', 10, 4)->nullable();
            $table->decimal('perubahan_iph_persen', 8, 3)->nullable();

            $table->decimal('inflasi_minggu_ini_persen', 8, 3)->nullable();
            $table->decimal('inflasi_ytd_persen', 8, 3)->nullable();
            $table->decimal('inflasi_yoy_persen', 8, 3)->nullable();

            $table->longText('analisis_ringkas')->nullable();
            $table->longText('komoditas_penyumbang_inflasi')->nullable();
            $table->longText('komoditas_penahan_inflasi')->nullable();

            $table->enum('status_verifikasi', ['draft', 'pending', 'disetujui', 'ditolak'])->default('draft');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();

            $table->json('metadata_grafik')->nullable();
            $table->json('lampiran_dokumen_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tahun', 'bulan']);
            $table->index('periode_label');
            $table->unique(['tahun', 'bulan', 'minggu_ke']);
        });

        Schema::create('iph_detail_komoditas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iph_mingguan_id')->constrained('iph_mingguan')->cascadeOnDelete();
            $table->foreignId('komoditas_id')->nullable()->constrained('komoditas')->nullOnDelete();

            $table->string('nama_komoditas', 150);
            $table->string('kategori_komoditas', 100)->nullable();

            $table->decimal('harga_minggu_ini', 15, 2);
            $table->decimal('harga_minggu_lalu', 15, 2)->nullable();
            $table->decimal('perubahan_nominal', 15, 2)->nullable();
            $table->decimal('perubahan_persen', 8, 3)->nullable();

            $table->decimal('sumbangan_inflasi_persen', 8, 3)->nullable();
            $table->decimal('sumbangan_inflasi_nominal', 10, 4)->nullable();
            $table->enum('jenis_dampak', ['penyumbang_inflasi', 'penahan_inflasi', 'netral'])->default('netral');

            $table->timestamps();

            $table->index('iph_mingguan_id');
            $table->index('jenis_dampak');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iph_detail_komoditas');
        Schema::dropIfExists('iph_mingguan');
    }
};
