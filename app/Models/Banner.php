<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'image_path',
        'link_url',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ============================================
    // Accessor: URL lengkap gambar banner
    // Dipakai di controller: $banner->image_url
    // ============================================
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
