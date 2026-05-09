<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');            // Path gambar banner, misal "banners/promo.jpg"
            $table->integer('sort_order')->default(0); // Urutan tampil di slider, angka kecil = pertama
            $table->boolean('is_active')->default(true); // Toggle tampil/sembunyi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
