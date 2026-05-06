{{-- ============================================ --}}
{{-- PARTIAL: Header (Logo + Nama Toko + Tagline) --}}
{{-- Dipanggil dengan: @include('partials.header') --}}
{{-- ============================================ --}}

<header class="header">
    <div class="header-content">
        <div class="header-left">
            <a href="{{ route('auth.login') }}" class="navbar-brand">
                <img src="{{ asset('img/IMG-20260425-WA0012.jpg.jpeg') }}" alt="Logo Sumber Manis" style="height: 80px;">
            </a>
        </div>
        <div class="header-center">
            <h1>Sumber Manis</h1>
            <p>Toko Kelontong Terpercaya dengan Produk Berkualitas</p>
        </div>
        <div class="header-right">
        </div>
    </div>
</header>
