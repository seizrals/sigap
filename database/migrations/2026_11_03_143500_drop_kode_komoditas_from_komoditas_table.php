<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('komoditas') && Schema::hasColumn('komoditas', 'kode_komoditas')) {
            Schema::table('komoditas', function (Blueprint $table) {
                $table->dropColumn('kode_komoditas');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('komoditas') && !Schema::hasColumn('komoditas', 'kode_komoditas')) {
            Schema::table('komoditas', function (Blueprint $table) {
                $table->string('kode_komoditas', 30)->nullable()->unique();
            });
        }
    }
};

