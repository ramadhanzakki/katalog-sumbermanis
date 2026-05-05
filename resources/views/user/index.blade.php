{{-- ============================================ --}}
{{-- PAGE: Halaman Utama Katalog (user/index)    --}}
{{-- Route: GET /                                --}}
{{-- Controller: UserController@index           --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Toko Sumber Manis - Katalog Produk')

{{-- CSS khusus halaman ini --}}
@push('styles')
<style>
    /* Modal */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 9999;
        overflow: auto;
    }
    .modal-content {
        background-color: #ffffff;
        margin: 5% auto;
        width: 90%; max-width: 900px;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.5);
        position: relative;
        animation: slideDown 0.3s;
        overflow: hidden;
        display: flex;
        flex-wrap: wrap;
    }
    .modal-close {
        position: absolute;
        top: 15px; right: 20px;
        color: #ff8a8a;
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10;
        line-height: 1;
    }
    .modal-close:hover { color: #f70707; }
    .modal-body { display: flex; flex-wrap: wrap; width: 100%; }
    .modal-left {
        flex: 1; min-width: 350px;
        background: #f8f9fa;
        display: flex; align-items: center; justify-content: center;
        padding: 20px;
    }
    .modal-left img { max-width: 100%; max-height: 640px; border-radius: 8px; object-fit: cover; }
    .modal-right { flex: 1; padding: 30px; min-width: 350px; display: flex; flex-direction: column; }
    .modal-title { font-size: 2rem; margin: 0 0 10px 0; color: #2c3e50; line-height: 1.2; }
    .modal-desc { color: #666; margin-bottom: 20px; font-size: 0.95rem; line-height: 1.5; }
    .price-box { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 8px; padding: 15px; margin-bottom: 20px; }
    .price-row { display: flex; justify-content: space-between; margin-bottom: 8px; align-items: center; }
    .price-label { font-size: 0.9rem; color: #7f8c8d; }
    .price-val { font-size: 1.2rem; font-weight: bold; color: #2c3e50; }
    .price-grosir { color: #CC0000; font-size: 1.3rem; }
    .btn-buy-large {
        width: 100%; padding: 15px;
        background: #e67e22; color: white;
        border: none; border-radius: 6px;
        font-size: 1.1rem; font-weight: bold;
        cursor: pointer; transition: background 0.3s;
    }
    .btn-buy-large:hover { background: #d35400; }
    @keyframes slideDown {
        from { transform: translateY(-50px); opacity: 0; }
        to   { transform: translateY(0); opacity: 1; }
    }
    @media (max-width: 768px) {
        .modal-content { flex-direction: column; margin: 10% auto; }
        .modal-left img { max-height: 300px; }
    }

    /* Search bar */
    .search-wrapper {
        display: flex; align-items: center; gap: 6px;
        margin-right: 15px auto 0; flex: 1;
        border-radius: 10px; border: 1px ridge #00000018;
        max-width: none;
    }
    .search-container {
        flex: 1; background: #ffffff; border-radius: 10px;
        padding: 6px 15px; border: 1px solid #eee;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .search-container:focus-within { border-color: #C23B33; }
    .search-input { width: 100%; border: none; outline: none; padding: 5px 0; font-size: 1rem; color: #333; background: transparent; }
    .search-icon-btn {
        background: #5aa8b7; color: white;
        border: none; border-radius: 10px;
        width: 45px; height: 45px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .search-icon-btn:hover { color: #000000; }
    @media (max-width: 768px) {
        .search-wrapper { max-width: 100%; }
        .search-icon-btn { width: 40px; height: 40px; }
    }

    /* Banner carousel */
    .banner-slide img {
        width: 500px !important; height: 250px !important;
        object-fit: cover; border-radius: 20px; flex-shrink: 0;
    }
    .carousel-container {
        position: relative; width: 100%;
        max-width: 1040px; margin: 50px auto;
        overflow: hidden; border-radius: 20px;
    }
    .banner-slide {
        display: flex; gap: 20px;
        min-width: 1040px; height: 250px;
    }
    @media (max-width: 1100px) {
        .banner-slide img { width: 48% !important; max-width: 500px; }
        .banner-slide { min-width: 100%; }
    }
    .carousel-container:hover #banner-prev,
    .carousel-container:hover #banner-next { opacity: 1; }
    #banner-prev, #banner-next {
        position: absolute; top: 50%; transform: translateY(-50%);
        background: rgba(0,0,0,0.5); color: white;
        border: none; font-size: 28px; padding: 8px 20px;
        cursor: pointer; border-radius: 100%;
        opacity: 0; transition: 0.3s;
    }
    #banner-prev { left: 10px; }
    #banner-next { right: 30px; }
    #carousel-dots { text-align: center; margin-top: 15px; }
    .dot {
        display: inline-block; width: 10px; height: 10px;
        background: #bbb; border-radius: 50%; margin: 0 6px;
        cursor: pointer; transition: 0.3s;
    }
    .dot.active { background: #ff6a00; transform: scale(1.2); }

    .section-header h2 { margin-bottom: 4px; }
    .section-header p { margin: 0; color: #888; font-size: 14px; }
</style>
@endpush

@section('content')

    @include('partials.header')
    @include('partials.navbar')

    {{-- ============================== --}}
    {{-- MAIN: Katalog Produk           --}}
    {{-- ============================== --}}
    <main id="catalog">

        {{-- Banner Carousel --}}
        <div class="carousel-container">
            <div id="banner-track" style="display:flex; transition:transform 0.7s ease-in-out;"></div>
            <button id="banner-prev">&#10094;</button>
            <button id="banner-next">&#10095;</button>
        </div>
        <div id="carousel-dots"></div>

        {{-- Header Katalog --}}
        <div class="catalog-header" id="katalogProduk">
            <div class="section-header">
                <h2 style="color: var(--dark);">
                    <i class="bi bi-box-seam-fill"></i> Produk
                </h2><br>
                <p style="margin-top: -10px; color: gray;">
                    Temukan berbagai kebutuhan harian Anda
                </p>
            </div>
        </div>

        {{-- Filter Kategori --}}
        {{-- Kategori diambil dari database, dikirim controller via $categories --}}
        <div style="margin-bottom: 2rem; display: flex; gap: 10px; flex-wrap: wrap;">
            <button class="btn btn-outline active" onclick="filterCategory('all')">
                <i class="bi bi-grid"></i> Semua
            </button>
            @foreach ($categories as $category)
                <button class="btn btn-outline" onclick="filterCategory('{{ $category->name }}')">
                    <i class="bi bi-basket"></i> {{ $category->name }}
                </button>
            @endforeach
        </div>

        {{-- Grid Produk --}}
        <div class="product-grid" id="product-container">
            @forelse ($products as $product)
                <div class="card {{ $product->stock <= 0 ? 'sold out' : '' }}" data-category="{{ $product->category_name }}">
                    <img src="{{ $product->image_url }}" class="card-image" alt="{{ $product->name }}">
                    <div class="card-content">
                        @if ($product->stock <= 0)
                            <span class="stock-badge stock-habis">Habis</span>
                        @elseif ($product->stock <= 5)
                            <span class="stock-badge stock-terbatas">Terbatas</span>
                        @else
                            <span class="stock-badge stock-tersedia">Tersedia</span>
                        @endif
                        <h3 class="card-title">{{ $product->name }}</h3>
                        <p class="card-desc">{{ $product->description }}</p>
                        <div class="card-footer">
                            <span>{{ 'Rp ' . number_format($product->price_retail, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; grid-column: 1/-1; color:#999 ">Belum ada product.</p>
            @endforelse
        </div>

    </main>

    {{-- ============================== --}}
    {{-- SECTION: Tentang Kami          --}}
    {{-- ============================== --}}
    <section id="tentang" style="padding: 2rem; background: white; margin-top: 2rem;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h2>Tentang Kami</h2>
            <p style="margin-top: 1rem; color: #666;">
                Toko SumberManis adalah toko supplier sembako terpercaya.
            </p>
            <p style="margin-top: 1rem; color: #666;">
                Alamat : Batu, Malang &nbsp;|&nbsp; Jam : 08.00 – 21.00
            </p>
        </div>
    </section>

    @include('partials.footer')

    {{-- Modal detail produk --}}
    @include('partials.product-modal')

@endsection

{{-- ============================== --}}
{{-- JS khusus halaman ini          --}}
{{-- ============================== --}}
@push('scripts')

{{-- Data produk & kategori dari Laravel dikirim ke JS --}}
<script>
    const PRODUCTS_DATA   = @json($products);
    const CATEGORIES_DATA = @json($categories);
    const WA_NUMBER       = '{{ config("app.wa_number", "6281234567890") }}';
    const ASSET_URL       = '{{ asset("storage") }}';

    // Event Listener untuk modal
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function () {
                const productId = this.dataset.productId;
                if (productId) openModal(productId);
            })
        })
    })
</script>

<script src="{{ asset('js/banner.js') }}"></script>
<script src="{{ asset('js/catalog.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/navbar.js') }}"></script>

@endpush
