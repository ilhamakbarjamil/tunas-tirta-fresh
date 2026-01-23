<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();      // Judul Promo
            $table->text('description')->nullable();  // Teks Isi
            $table->string('image')->nullable();      // Foto Banner
            $table->boolean('is_active')->default(true); // Status Aktif/Mati
            $table->string('button_text')->default('Belanja Sekarang'); // Tulisan Tombol
            $table->string('button_link')->nullable(); // Link arah tombol (opsional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
