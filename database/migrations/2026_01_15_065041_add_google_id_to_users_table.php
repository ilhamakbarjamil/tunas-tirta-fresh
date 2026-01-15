<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom google_id
            $table->string('google_id')->nullable()->after('email');
            
            // Password jadi nullable (opsional) karena login google gak pake password
            $table->string('password')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
            // Kembalikan password jadi wajib (hati-hati kalau rollback)
            $table->string('password')->nullable(false)->change();
        });
    }
};