<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_lpg', function (Blueprint $table) {
            $table->dropForeign(['pangkalan_lpg_id']);
        });

        Schema::table('data_lpg', function (Blueprint $table) {
            $table->unsignedBigInteger('pangkalan_lpg_id')->nullable()->change();
        });

        Schema::table('data_lpg', function (Blueprint $table) {
            $table->foreign('pangkalan_lpg_id')
                ->references('id')
                ->on('pangkalan_lpg')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::table('data_lpg')->whereNull('pangkalan_lpg_id')->exists()) {
            throw new RuntimeException('Cannot restore required LPG depot while records without a depot exist.');
        }

        Schema::table('data_lpg', function (Blueprint $table) {
            $table->dropForeign(['pangkalan_lpg_id']);
        });

        Schema::table('data_lpg', function (Blueprint $table) {
            $table->unsignedBigInteger('pangkalan_lpg_id')->nullable(false)->change();
        });

        Schema::table('data_lpg', function (Blueprint $table) {
            $table->foreign('pangkalan_lpg_id')
                ->references('id')
                ->on('pangkalan_lpg')
                ->cascadeOnDelete();
        });
    }
};
