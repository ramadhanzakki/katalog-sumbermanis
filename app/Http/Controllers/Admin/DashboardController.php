<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::all();
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $lowStock = Product::where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $outOfStock = Product::where('stock', '<=', 0)->count();

        // Cek apakah ada parameter ?edit=id untuk mode edit
        $selectedProduct = null;
        if ($request->query('edit')) {
            $selectedProduct = Product::find($request->query('edit'));
        }

        return view('admin.dashboard', [
            'totalProducts'   => $totalProducts,
            'totalStock'      => $totalStock,
            'lowStock'        => $lowStock,
            'outOfStock'      => $outOfStock,
            'categories'      => $categories,
            'products'        => $products,
            'selectedProduct' => $selectedProduct,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_retail' => 'required|numeric|min:0',
            'price_wholesale' => 'nullable|numeric|min:0',
            'wholesale_min_qty' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);
        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_retail' => 'required|numeric|min:0',
            'price_wholesale' => 'nullable|numeric|min:0',
            'wholesale_min_qty' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        // Jika ada upload gambar baru
        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil dihapus!');
    }
}
