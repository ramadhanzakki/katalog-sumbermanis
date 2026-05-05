<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::updateOrCreate(
            ['email' => 'admin@sumbermanis.com'],
            [
                'name'     => 'Admin SumberManis',
                'password' => Hash::make('admin123'),
                'role'     => 'superadmin',
            ]
        );

        // 2. Kategori Produk
        $categories = [
            'Minuman',
            'Makanan',
            'Bumbu-Bumbu',
            'Bahan Dapur',
            'Snack',
            'Kebutuhan Rumah',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        // 3. Contoh Produk
        $minuman   = Category::where('name', 'Minuman')->first();
        $bahanDapur = Category::where('name', 'Bahan Dapur')->first();
        $bumbu     = Category::where('name', 'Bumbu-Bumbu')->first();

        $products = [
            [
                'category_id'       => $bahanDapur->id,
                'name'              => 'Gula Pasir 1kg',
                'description'       => 'Gula pasir putih berkualitas tinggi, cocok untuk masak dan minuman.',
                'price_retail'      => 14000,
                'price_wholesale'   => 13000,
                'wholesale_min_qty' => 5,
                'stock'             => 100,
                'is_active'         => true,
            ],
            [
                'category_id'       => $bahanDapur->id,
                'name'              => 'Minyak Goreng 2L',
                'description'       => 'Minyak goreng jernih untuk kebutuhan memasak sehari-hari.',
                'price_retail'      => 32000,
                'price_wholesale'   => 30000,
                'wholesale_min_qty' => 3,
                'stock'             => 50,
                'is_active'         => true,
            ],
            [
                'category_id'       => $minuman->id,
                'name'              => 'Teh Botol Sosro 350ml',
                'description'       => 'Minuman teh manis siap minum dalam kemasan botol.',
                'price_retail'      => 5000,
                'price_wholesale'   => 4500,
                'wholesale_min_qty' => 12,
                'stock'             => 3,   // stok terbatas
                'is_active'         => true,
            ],
            [
                'category_id'       => $bumbu->id,
                'name'              => 'Kecap Manis ABC 135ml',
                'description'       => 'Kecap manis kental dengan cita rasa gurih dan manis.',
                'price_retail'      => 8500,
                'price_wholesale'   => null,  // tidak ada harga grosir
                'wholesale_min_qty' => 5,
                'stock'             => 0,   // stok habis
                'is_active'         => true,
            ],
        ];

        foreach ($products as $data) {
            Product::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        // 4. Contoh Banner
        $banners = [
            [
                'sort_order' => 1,
                'image_path' => 'img/pm_banner_260302_iYZ1.webp',
                'link_url' => null,
                'is_active' => true
            ],
            [
                'sort_order' => 2,
                'image_path' => 'img/pm_banner_260310_U6CO.webp',
                'link_url' => null,
                'is_active' => true
            ],
            [
                'sort_order' => 2,
                'image_path' => 'img/pm_banner_260311_Sb6P.webp',
                'link_url' => null,
                'is_active' => true
            ]
        ];

        foreach ($banners as $bannersData) {
            Banner::firstOrCreate(
                ['sort_order' => $bannersData['sort_order']],
                $bannersData
            );
        }
    }
}
