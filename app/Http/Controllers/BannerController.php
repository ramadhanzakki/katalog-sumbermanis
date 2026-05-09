<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function index(Request $request){
        $banners = Banner::latest()->get();
        $totalBanners = Banner::count();
        $availableSlotBanners = 6 - Banner::count();
        $selectBanner = null;

        if ($request->query('edit')) {
            $selectBanner = Banner::find($request->query('edit'));
        }

        return view('admin.banner', [
            'banners' => $banners,
            'totalBanners' => $totalBanners,
            'availableSlotBanners' => $availableSlotBanners,
            'selectBanner' => $selectBanner
        ]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $photoName = Str::uuid() . '.' . $photo->getClientOriginalExtension();
            
            // Simpan file ke disk 'public' dalam folder 'banners'
            Storage::disk('public')->putFileAs('banners', $photo, $photoName);
            
            // Simpan path relatif ke database (termasuk folder 'banners/')
            $validated['image_path'] = 'banners/' . $photoName;
        }

        unset($validated['image']);

        Banner::create($validated);
        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil ditambahkan!');
    }

    public function update(Request $request, Banner $banner){
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            if ($photo && $photo->isValid()) {
                // Hapus file lama jika ada
                if ($banner->image_path) {
                    Storage::disk('public')->delete($banner->image_path);
                }
                // Simpan file baru
                $validated['image_path'] = Storage::disk('public')->putFile('products', $photo);
            }
        }

        unset($validated['image']);

        $banner->update($validated);
        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil diperbarui!');
    }

    public function destroy(Banner $banner){
        if ($banner->image_path) {
            Storage::disk('public')->delete($banner->image_path);
        }
        $banner->delete();

        return redirect()->route('admin.banner.index')->with('success', 'Banner berhasil dihapus!');
    }
}
