<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_komoditas', 30)->unique();
            $table->string('nama_komoditas', 150);
            $table->string('kategori', 50);
            $table->string('satuan', 30);
            $table->decimal('harga_het', 15, 2)->nullable();
            $table->decimal('harga_rata_rata_kabupaten', 15, 2)->nullable();
            $table->decimal('stok_aman_minimum', 12, 2)->nullable();
            $table->decimal('batas_atas_harga_normal', 15, 2)->nullable();
            $table->decimal('batas_bawah_harga_normal', 15, 2)->nullable();
            $table->integer('persentase_kenaikan_waspada')->default(10);
            $table->integer('persentase_kenaikan_bahaya')->default(25);
            $table->boolean('is_strategis')->default(true);
            $table->boolean('is_active')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('kategori');
            $table->index('is_strategis');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komoditas');
    }
};
