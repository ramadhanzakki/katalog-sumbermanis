{{-- ============================================ --}}
{{-- PARTIAL: Header (Logo + Nama Toko + Tagline) --}}
{{-- Dipanggil dengan: @include('partials.header') --}}
{{-- ============================================ --}}

<header class="header">
    <div class="header-content">
        <div class="header-left">
            <a href="{{ route('auth.login') }}" class="navbar-brand">
                <img src="{{ asset('img/logomitra.png') }}" alt="Logo Sumber Manis" style="height: 80px;">
            </a>
        </div>
        <div class="header-center">
            <h1>Sumber Manis</h1>
            <p>Toko Kelontong Terpercaya dengan Produk Berkualitas</p>
        </div>
        <div class="header-right">
            {{-- Slot kosong, bisa diisi tombol login jika diperlukan --}}
        </div>
    </div>
</header>
