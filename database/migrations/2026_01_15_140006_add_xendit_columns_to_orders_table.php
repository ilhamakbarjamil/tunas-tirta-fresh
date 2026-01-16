<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom untuk menyimpan Link Pembayaran dari Xendit
            $table->string('payment_url')->nullable()->after('total_price');
            // Kolom untuk ID unik transaksi Xendit
            $table->string('external_id')->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_url', 'external_id']);
        });
    }
};