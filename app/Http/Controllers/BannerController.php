<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request){
        $banners = Banner::latest()->get();
        $totalBanners = Banner::count();
        $availableSlotBanners = 6 - Banner::count();

        return view('admin.banner', [
            'banners' => $banners,
            'totalBanners' => $totalBanners,
            'availableSlotBanners' => $availableSlotBanners
        ]);
    }

    public function store(){

    }

    public function update(){

    }

    public function destroy(Banner $banners){
        if ($banners->image_path) {
            Storage::disk('public')->delete($banners->image_path);
        }
        $banners->delete();

        return redirect('admin.banner')->with('success', 'Banner berhasil dihapus!');
    }
}
