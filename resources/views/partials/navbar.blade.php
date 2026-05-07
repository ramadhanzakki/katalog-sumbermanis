{{-- ============================================ --}}
{{-- PARTIAL: Navbar (Search + Menu + Hamburger) --}}
{{-- Dipanggil dengan: @include('partials.navbar') --}}
{{-- ============================================ --}}

<nav class="navbar">
    <a href="{{ route('user.index') }}" class="navbar-brand">Sumber<span>Manis</span></a>

    {{-- Search Bar --}}
    <div class="search-wrapper">
        <div class="search-container">
            <input
                type="text"
                id="header-search"
                class="search-input"
                placeholder="Cari Produk di Sumber Manis"
            >
        </div>
        <div class="search-icon-btn" onclick="filterProducts()">
            <i class="bi bi-search"></i>
        </div>
    </div>

    {{-- Menu Navigasi --}}
    <ul class="nav-menu" id="nav-menu">
        <li>
            <a href="{{ route('user.index') }}"
                class="nav-link {{ request()->routeIs('user.index') ? 'active' : '' }}">
                <i class="bi bi-house-fill"></i> Beranda
            </a>
        </li>
        <li>
            <a href="{{ route('user.index') }}#katalogProduk" class="nav-link">
                <i class="bi bi-box-seam-fill"></i> Produk
            </a>
        </li>
        <li class="nav-dropdown">
            <a href="javascript:void(0)" class="nav-link dropdown-toggle">
                <i class="bi bi-telephone-fill"></i> Kontak ▾
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="https://wa.link/xqaqlb" target="_blank">
                        <i class="bi bi-whatsapp"></i> WhatsApp Admin
                    </a>
                </li>
                <li>
                    <a href="https://instagram.com/xszhull" target="_blank">
                        <i class="bi bi-instagram"></i> Instagram
                    </a>
                </li>
                <li>
                    <a href="https://shopee.co.id/bosssumbermanis" target="_blank">
                        <i class="bi bi-bag-plus-fill"></i> Shopee
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="{{ route('user.index') }}#tentang" class="nav-link">
                <i class="bi bi-info-circle-fill"></i> Tentang
            </a>
        </li>
    </ul>

    {{-- Hamburger (mobile) --}}
    <button class="hamburger" onclick="toggleMenu()">
        <span></span><span></span><span></span>
    </button>
</nav>
