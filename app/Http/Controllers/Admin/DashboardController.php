<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::all();
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $lowStock = Product::where('stock', '<=', 5)->where('stock', '>', 0)->count();
        $outOfStock = Product::where('stock', '<=', 0)->count();

        return view('admin.dashboard', [
            'totalProducts' => $totalProducts,
            'totalStock' => $totalStock,
            'lowStock' => $lowStock,
            'outOfStock' => $outOfStock,
            'categories' => $categories,
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_retail' => 'required|numeric|min:0',
            'price_wholesale' => 'nullable|numeric|min:0',
            'wholesale_min_qty' => 'nullable|integer|min:1',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file && $file->isValid()) {
                $validated['image_path'] = $file->store('products', 'public');
            } else {
                $validated['image_path'] = null;
            }
        } else {
            $validated['image_path'] = null;
        }

        Product::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil ditambahkan!']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_retail' => 'required|numeric|min:0',
            'price_wholesale' => 'nullable|numeric|min:0',
            'wholesale_min_qty' => 'nullable|integer|min:1',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            if ($file && $file->isValid()) {
                // Hapus file lama jika ada
                if ($product->image_path) {
                    Storage::disk('public')->delete($product->image_path);
                }
                // Simpan file baru
                $validated['image_path'] = $file->store('products', 'public');
            }
        }

        $product->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Produk berhasil diperbarui!']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus!');
    }
}
