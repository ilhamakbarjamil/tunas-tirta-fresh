<?php

namespace App\Models;

// Import library Filament
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Tambahkan "implements FilamentUser" di sini
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',      // Pastikan kolom ini ada
        'google_id', // Pastikan kolom ini ada
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi Keranjang
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    // --- LOGIKA KUNCI PINTU ADMIN ---
    // Fungsi ini wajib ada biar Admin bisa masuk Dashboard Filament
    public function canAccessPanel(Panel $panel): bool
    {
        // Kuncinya: Hanya user dengan role 'admin' yang boleh masuk
        return $this->role === 'admin';
    }
}