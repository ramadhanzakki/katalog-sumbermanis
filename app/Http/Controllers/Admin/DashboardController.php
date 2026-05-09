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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_retail' => 'required|numeric|min:0',
            'price_wholesale' => 'nullable|numeric|min:0',
            'wholesale_min_qty' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $photoName = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            
            // Simpan file ke disk 'public' dalam folder 'products'
            Storage::disk('public')->putFileAs('products', $photo, $photoName);
            
            // Simpan path relatif ke database (termasuk folder 'products/')
            $validated['image_path'] = 'products/' . $photoName;
        }

        // Hapus 'image' dari array agar tidak bentrok saat create (karena kolom di DB adalah image_path)
        unset($validated['image']);

        Product::create($validated);
        return redirect()->route('admin.dashboard')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_retail' => 'required|numeric|min:0',
            'price_wholesale' => 'nullable|numeric|min:0',
            'wholesale_min_qty' => 'required|integer|min:0',
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
                $validated['image_path'] = \Illuminate\Support\Facades\Storage::disk('public')->putFile('products', $file);
            }
        }

        // Hapus 'image' dari array agar tidak bentrok saat update
        unset($validated['image']);

        $product->update($validated);

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
