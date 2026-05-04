<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Relasi ke kategori
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();   // jika kategori dihapus, produknya ikut terhapus

            // Informasi produk
            $table->string('name');                  // Nama produk, misal "Gula Pasir 1kg"
            $table->string('slug')->unique();        // URL-friendly, misal "gula-pasir-1kg"
            $table->text('description')->nullable(); // Deskripsi produk, boleh kosong

            // Harga — pakai decimal agar tidak ada pembulatan yang meleset
            $table->decimal('price_retail', 12, 2);              // Harga eceran
            $table->decimal('price_wholesale', 12, 2)->nullable(); // Harga grosir, boleh kosong
            $table->integer('wholesale_min_qty')->default(5);    // Min. beli untuk harga grosir

            // Stok & gambar
            $table->integer('stock')->default(0);       // Jumlah stok tersedia
            $table->string('image_path')->nullable();   // Path gambar, misal "products/abc.jpg"

            // Status tampil di halaman user
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
