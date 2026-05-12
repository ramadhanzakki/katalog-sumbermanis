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
        $categories = Category::orderBy('name')->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->orderBy('created_at')
            ->get()
            ->map(function ($product) {
                $product->image_url    = $product->image_url; 
                $product->category_name = $product->category->name ?? '';
                return $product;
            });

        $banners = Banner::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($banner) {
                $banner->image_url = $banner->image_url;
                return $banner;
            });

        return view('user.index', [
            'products' => $products, 
            'categories' => $categories, 
            'banners' => $banners
        ]);
    }
}
