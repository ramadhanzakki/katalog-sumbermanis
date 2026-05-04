<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    // ============================================
    // Auto-generate slug dari name sebelum disimpan
    // ============================================
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // ============================================
    // Relasi: satu kategori punya banyak produk
    // ============================================
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
