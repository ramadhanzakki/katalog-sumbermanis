<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price_retail',
        'price_wholesale',
        'wholesale_min_qty',
        'stock',
        'image_path',
        'is_active',
    ];

    protected $casts = [
        'price_retail'     => 'decimal:2',
        'price_wholesale'  => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    protected $appends = [
        'image_url',
        'stock_label'
    ];

    // Auto-generate slug dari name
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    // Accessor: URL lengkap gambar produk
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return asset('img/no-image.png'); // gambar default jika belum ada foto
    }

    // Accessor: status stok dalam bentuk teks
    public function getStockLabelAttribute(): string
    {
        if ($this->stock <= 0)  return 'Habis';
        if ($this->stock <= 5)  return 'Terbatas';
        return 'Tersedia';
    }

    // Relasi: produk dimiliki oleh satu kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
