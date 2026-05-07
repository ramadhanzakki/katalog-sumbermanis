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
                <div class="card {{ $product->stock <= 0 ? 'sold-out' : '' }}" 
                    data-category="{{ $product->category_name }}" 
                    data-product-id="{{ $product->id }}"
                    onclick="openModal('productModal{{ $product->id }}')">

                    <img src="{{ $product->getImageUrlAttribute() }}" 
                        class="card-image" 
                        alt="{{ $product->name }}">

                    <div class="card-content">

                        @if ($product->stock <= 0)
                            <span class="stock-badge stock-habis">Habis</span>
                        @elseif ($product->stock <= 5)
                            <span class="stock-badge stock-terbatas">Terbatas</span>
                        @else
                            <span class="stock-badge stock-tersedia">Tersedia</span>
                        @endif

                        <h3 class="card-title">{{ $product->name }}</h3>

                        <p class="card-desc">
                            {{ $product->description ?? '' }}
                        </p>

                        <div class="card-footer">
                            <span class="new-price">
                                {{ 'Rp ' . number_format($product->price_retail, 0, ',', '.') }}
                            </span>
                        </div>

                    </div>
                </div>
            @empty
                <p style="text-align:center; grid-column:1/-1; color:#999;">
                    Belum ada produk.
                </p>
            @endforelse
        </div>

    </main>

    {{-- SECTION: Tentang Kami          --}}
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

    {{-- MODAL --}}
    @foreach ($products as $product)
        <div id="productModal{{ $product->id }}" 
            class="modal-overlay" 
            onclick="closeModal(event)">

            <div class="modal-content" onclick="event.stopPropagation()">
                <span class="modal-close" onclick="closeModal()">&times;</span>

                <div class="modal-body">

                    {{-- Gambar Produk --}}
                    <div class="modal-left">
                        <img id="modal-img" src="{{ $product->getImageUrlAttribute() }}" alt="Foto Produk">
                    </div>

                    {{-- Detail Produk --}}
                    <div class="modal-right">
                        <h2 id="modal-title" class="modal-title">{{ $product->name }}</h2>
                        <p id="modal-desc" class="modal-desc">{{ $product->description }}</p>

                        {{-- Kotak Harga --}}
                        <div class="price-box">
                            {{-- Harga Satuan --}}
                            <div class="price-row">
                                <span class="price-label">Harga Satuan:</span>
                                <span id="modal-price-retail" class="price-val">Rp {{ $product->price_retail }}</span>
                            </div>

                            {{-- Harga Grosir (tersembunyi jika tidak ada) --}}
                            <div id="modal-grosir-box" style="price-row">
                                <div class="price-row">
                                    <span class="price-label">
                                        Harga Grosir (<span id="modal-min-grosir">{{ $product->wholesale_min_qty }}</span> pcs):
                                    </span>
                                    <span id="modal-price-grosir" class="price-val price-grosir">Rp {{ $product->price_wholesale }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Hubungi via WhatsApp --}}
                        <a id="modal-wa-btn"
                        href="https://wa.link/xqaqlb"
                        target="_blank"
                        class="btn-buy-large"
                        style="display: block; text-align: center; text-decoration: none; margin-top: 10px;">
                            <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                        </a>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

@endsection

{{-- JS khusus halaman ini          --}}
@push('scripts')



<script src="{{ asset('js/banner.js') }}"></script>
<script src="{{ asset('js/catalog.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/navbar.js') }}"></script>

@endpush
