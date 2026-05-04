<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    
    public function index()
    {
        // Ambil semua kategori untuk tombol filter
        $categories = Category::orderBy('name')->get();

        // Ambil produk yang aktif, sekalian eager load kategorinya
        // agar tidak terjadi N+1 query
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(function ($product) {
                // Tambahkan field image_url dan category_name
                // agar bisa langsung dibaca oleh catalog.js
                $product->image_url    = $product->image_url;      // via accessor
                $product->category_name = $product->category->name ?? '';
                return $product;
            });

        // Ambil banner yang aktif, urut sesuai sort_order
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($banner) {
                // Tambahkan field image_url agar bisa dibaca oleh banner.js
                $banner->image_url = $banner->image_url; // via accessor
                return $banner;
            });

        return view('user.index', compact('products', 'categories', 'banners'));
    }
}
