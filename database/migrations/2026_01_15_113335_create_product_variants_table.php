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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Contoh: "500 gram", "1 Kg", "Per Box"
            $table->decimal('price', 12, 0); // Harga khusus untuk varian ini
            $table->timestamps();
        });
        
        // Kita juga perlu update tabel Cart agar tahu user pilih varian yang mana
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->nullable()->after('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('product_variant_id');
        });
    }
};
