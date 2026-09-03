<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerAdvertisment extends Model
{
    use HasFactory;

    // ✅ Tambahkan baris ini untuk mengizinkan field diisi otomatis
    protected $fillable = [
        'link',
        'thumbnail',
        'is_active',
        'type',
    ];
}