<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'name',   // Contoh isi: "Tenderloin", "1 Kg", "Pack Kecil"
        'price',  // Harga khusus varian ini
        'stock',  // Stok khusus varian ini
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}