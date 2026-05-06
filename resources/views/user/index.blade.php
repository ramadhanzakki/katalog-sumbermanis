{{-- ============================================ --}}
{{-- PAGE: Halaman Utama Katalog (user/index)    --}}
{{-- Route: GET /                                --}}
{{-- Controller: UserController@index           --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('title', 'Toko Sumber Manis - Katalog Produk')

{{-- CSS khusus halaman ini --}}
@push('styles')
<link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
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
        {{-- Produk di-render oleh catalog.js via fetch ke API --}}
        <div class="product-grid" id="product-container">
            {{-- Diisi oleh JavaScript --}}
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
</script>

<script src="{{ asset('js/banner.js') }}"></script>
<script src="{{ asset('js/catalog.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/navbar.js') }}"></script>

@endpush
