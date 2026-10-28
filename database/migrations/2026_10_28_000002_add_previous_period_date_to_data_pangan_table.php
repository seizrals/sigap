<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pangan', function (Blueprint $table) {
            $table->date('tanggal_input_sebelumnya')->nullable()->after('tanggal_input');
        });
    }

    public function down(): void
    {
        Schema::table('data_pangan', function (Blueprint $table) {
            $table->dropColumn('tanggal_input_sebelumnya');
        });
    }
};
