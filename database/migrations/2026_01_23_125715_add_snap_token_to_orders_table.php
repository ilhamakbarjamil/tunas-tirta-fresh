<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
{
    // Cek dulu: Apakah kolom 'snap_token' SUDAH ADA di tabel 'orders'?
    if (!Schema::hasColumn('orders', 'snap_token')) {
        // Kalau BELUM ada, baru kita buat
        Schema::table('orders', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
        });
    }
}

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('snap_token');
        });
    }
};