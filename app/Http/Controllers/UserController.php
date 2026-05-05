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
        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Ambil banner yang aktif, urut sesuai sort_order
        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('user.index', [
            'products' => $products, 
            'categories' => $categories, 
            'banners' => $banners
        ]);
    }
}
