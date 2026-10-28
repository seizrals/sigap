<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('iph_bulanan')) {
            Schema::create('iph_bulanan', function (Blueprint $table) {
                $table->id();
                $table->year('tahun');
                $table->tinyInteger('bulan');
                $table->string('nama_bulan', 20);
                $table->decimal('iph_bulanan', 8, 4);
                $table->integer('ranking')->nullable();
                $table->text('komoditas_andil')->nullable();
                $table->string('status_kategori', 50)->default('terkendali');
                $table->text('catatan')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();

                $table->index(['tahun', 'bulan']);
            });
        }

        if (Schema::hasTable('iph_mingguan')) {
            Schema::table('iph_mingguan', function (Blueprint $table) {
                if (!Schema::hasColumn('iph_mingguan', 'ranking')) {
                    $table->integer('ranking')->nullable()->after('iph_kabupaten');
                }
                if (!Schema::hasColumn('iph_mingguan', 'komoditas_andil')) {
                    $table->text('komoditas_andil')->nullable()->after('ranking');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('iph_bulanan');

        if (Schema::hasTable('iph_mingguan')) {
            Schema::table('iph_mingguan', function (Blueprint $table) {
                if (Schema::hasColumn('iph_mingguan', 'komoditas_andil')) {
                    $table->dropColumn('komoditas_andil');
                }
                if (Schema::hasColumn('iph_mingguan', 'ranking')) {
                    $table->dropColumn('ranking');
                }
            });
        }
    }
};

