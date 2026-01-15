<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'snap_token', // Jaga-jaga kalau kolom ini sudah ada di database
    ];

    // Relasi: Order milik 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Order punya banyak Item
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}