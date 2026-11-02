<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_bbm', function (Blueprint $table) {
            if (!Schema::hasColumn('data_bbm', 'distribusi_masuk_nelayan_kl')) {
                $table->decimal('distribusi_masuk_nelayan_kl', 10, 3)->nullable()->after('realisasi_nelayan_kl');
            }
            if (!Schema::hasColumn('data_bbm', 'distribusi_keluar_nelayan_kl')) {
                $table->decimal('distribusi_keluar_nelayan_kl', 10, 3)->nullable()->after('distribusi_masuk_nelayan_kl');
            }
            if (!Schema::hasColumn('data_bbm', 'distribusi_masuk_pertanian_kl')) {
                $table->decimal('distribusi_masuk_pertanian_kl', 10, 3)->nullable()->after('realisasi_pertanian_kl');
            }
            if (!Schema::hasColumn('data_bbm', 'distribusi_keluar_pertanian_kl')) {
                $table->decimal('distribusi_keluar_pertanian_kl', 10, 3)->nullable()->after('distribusi_masuk_pertanian_kl');
            }
        });
    }

    public function down(): void
    {
        Schema::table('data_bbm', function (Blueprint $table) {
            $table->dropColumn([
                'distribusi_masuk_nelayan_kl',
                'distribusi_keluar_nelayan_kl',
                'distribusi_masuk_pertanian_kl',
                'distribusi_keluar_pertanian_kl',
            ]);
        });
    }
};

