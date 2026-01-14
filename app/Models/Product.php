<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    // Izinkan semua kolom ini diisi
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_available',
    ];

    // Definisi relasi ke Category (agar Filament bisa baca)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
