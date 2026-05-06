<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Str::startsWith($this->image_path, ['http://', 'https://', '/', 'img/', 'storage/'])) {
            return asset($this->image_path);
        }

        return asset('storage/' . $this->image_path);
    }
}
