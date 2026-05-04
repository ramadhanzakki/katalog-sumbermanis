<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $lowStock = Product::where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $outOfStock = Product::where('stock', '<=', 0)->count();
        $categories = Category::all();
        $products = Product::with('category')->latest()->paginate(10);

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
