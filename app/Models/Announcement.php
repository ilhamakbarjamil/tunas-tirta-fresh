<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // Tambahkan daftar kolom yang boleh diisi di sini:
    protected $fillable = [
        'title',
        'description',
        'image',
        'is_active',
        'button_text',
        'button_link',
    ];
}