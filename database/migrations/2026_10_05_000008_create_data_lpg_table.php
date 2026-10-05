<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_lpg', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_input');
            $table->foreignId('kecamatan_id')->constrained('kecamatan')->cascadeOnDelete();
            $table->foreignId('desa_id')->nullable()->constrained('desa')->nullOnDelete();
            $table->foreignId('pangkalan_lpg_id')->constrained('pangkalan_lpg')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('jenis_lpg', ['LPG 3 Kg (Subsidi)', 'LPG 5.5 Kg (Non Subsidi)', 'LPG 12 Kg', 'LPG 50 Kg', 'Bright Gas'])->default('LPG 3 Kg (Subsidi)');

            $table->integer('stok_tersedia_tabung')->default(0);
            $table->integer('distribusi_masuk_tabung')->default(0);
            $table->string('sumber_pemasok', 255)->nullable();
            $table->integer('distribusi_keluar_tabung')->default(0);
            $table->integer('permintaan_harian_tabung')->nullable();

            $table->decimal('harga_pangkalan', 15, 2)->nullable();
            $table->decimal('harga_het_pemerintah', 15, 2)->nullable();
            $table->decimal('harga_eceran_lapangan', 15, 2)->nullable();
            $table->decimal('selisih_harga_eceran_het', 15, 2)->nullable();
            $table->decimal('persentase_kenaikan_harga', 8, 2)->nullable();

            $table->enum('status_harga', ['sesuai_het', 'melampaui_het_ringan', 'melampaui_het_tinggi'])->default('sesuai_het');
            $table->enum('status_ketersediaan', ['aman', 'waspada', 'menipis', 'kosong'])->default('aman');
            $table->enum('status_distribusi', ['lancar', 'terhambat', 'macet', 'panic_buying'])->default('lancar');
            $table->enum('status_verifikasi', ['draft', 'pending', 'disetujui', 'ditolak'])->default('draft');

            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('catatan_verifikasi')->nullable();

            $table->text('keterangan_kendala')->nullable();
            $table->json('foto_bukti_path')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('tanggal_input');
            $table->index('kecamatan_id');
            $table->index('pangkalan_lpg_id');
            $table->index('jenis_lpg');
            $table->index('status_verifikasi');
            $table->index(['tanggal_input', 'kecamatan_id', 'jenis_lpg'], 'idx_lpg_tgl_kec_jenis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_lpg');
    }
};
