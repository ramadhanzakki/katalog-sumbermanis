@extends('layouts.app')

@section('title', 'Toko Sumber Manis - Katalog Produk')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
@endpush

@section('content')

    @include('partials.header')
    @include('partials.navbar')

    <main id="catalog">

        {{-- Banner Carousel --}}
        <div class="carousel-container">
            <div id="banner-track" style="display:flex; transition:transform 0.7s ease-in-out; width: calc({{ $banners->chunk(2)->count() }} * 100%);">
                @foreach ($banners->chunk(2) as $slideIndex =>$bannerPair)
                    <div class="banner-slide" style="width: 100%; flex-shrink: 0;">
                        @foreach ($bannerPair as $banner)
                            <a href="{{ $banner->link_url ?? '#' }}" target="{{ $banner->link_url ? '_blank' : '_self' }}" rel="noopener noreferrer">
                                <img src="{{ $banner->image_url }}" alt="Banner Promo">
                            </a>
                        @endforeach
                        @if($loop->remaining == 1 && $bannerPair->count() == 1)
                            {{-- Placeholder untuk banner ganjil --}}
                            <div style="flex:1;"></div>
                        @endif
                    </div>
                @endforeach
            </div>
            <button id="banner-prev">&#10094;</button>
            <button id="banner-next">&#10095;</button>
        </div>
        <div id="carousel-dots">
            @for ($i = 0; $i < $banners->chunk(2)->count(); $i++)
                <span class="dot {{ $i === 0 ? 'active' : '' }}" onclick="goToSlide({{ $i }})"></span>
            @endfor
        </div>

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
                <div class="card {{ $product->stock <= 0 ? 'sold-out' : '' }}" data-category="{{ $product->category_name }}" data-product-id="{{ $product->id }}">
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
                        <p class="card-desc">{{ $product->description ?? '' }}</p>
                        <div class="card-footer">
                            <span class="new-price">{{ 'Rp ' . number_format($product->price_retail, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align:center; grid-column:1/-1; color:#999;">Belum ada produk.</p>
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
    // Data untuk modal (tetap butuh JS karena modal perlu akses data)
    const PRODUCTS_DATA   = @json($products);
    const CATEGORIES_DATA = @json($categories);
    const WA_NUMBER       = '{{ config("app.wa_number", "6281234567890") }}';
    const ASSET_URL       = '{{ asset("storage") }}';
    
    // Event listener untuk modal saat card diklik
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('click', function() {
                const productId = this.dataset.productId;
                if (productId) openModal(productId);
            });
        });
    });
</script>

<script src="{{ asset('js/banner.js') }}"></script>
<script src="{{ asset('js/catalog.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/navbar.js') }}"></script>

@endpush
