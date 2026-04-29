/**
 * catalog.js
 * Render kartu produk, filter kategori, dan live search.
 *
 * Variabel yang harus tersedia (dideklarasikan di blade):
 *   const PRODUCTS_DATA   = @json($products);    // koleksi produk dari DB
 *   const ASSET_URL       = '{{ asset("storage") }}'; // base URL storage
 */

let currentCategory = 'all';

document.addEventListener('DOMContentLoaded', function () {
    renderCatalog();

    // Live search saat mengetik
    const searchInput = document.getElementById('header-search');
    if (searchInput) {
        searchInput.addEventListener('keyup', filterProducts);
    }
});

/**
 * Render semua kartu produk ke #product-container.
 */
function renderCatalog() {
    const container = document.getElementById('product-container');
    if (!container) return;

    const products = window.PRODUCTS_DATA || [];
    container.innerHTML = '';

    if (products.length === 0) {
        container.innerHTML = '<p style="text-align:center; grid-column:1/-1; color:#999;">Belum ada produk.</p>';
        return;
    }

    products.forEach(p => {
        const card = buildProductCard(p);
        container.appendChild(card);
    });
}

/**
 * Buat satu elemen kartu produk.
 */
function buildProductCard(p) {
    const card = document.createElement('div');
    card.className  = 'card' + (p.stock <= 0 ? ' sold-out' : '');
    card.dataset.category = p.category_name || '';
    card.style.cursor = 'pointer';
    card.onclick = () => openModal(p.id);

    // Badge stok
    let badge = '';
    if (p.stock <= 0)       badge = '<span class="stock-badge stock-habis">Habis</span>';
    else if (p.stock <= 5)  badge = '<span class="stock-badge stock-terbatas">Terbatas</span>';
    else                    badge = '<span class="stock-badge stock-tersedia">Tersedia</span>';

    // URL gambar: pakai image_url yang sudah di-resolve controller,
    // atau fallback ke placeholder jika kosong.
    const imgSrc = p.image_url || 'https://placehold.co/300x200?text=No+Image';

    card.innerHTML = `
        <img src="${imgSrc}" class="card-image" onerror="this.src='https://placehold.co/300x200?text=No+Image'">
        <div class="card-content">
            ${badge}
            <h3 class="card-title">${escapeHtml(p.name)}</h3>
            <p class="card-desc">${escapeHtml(p.description || '')}</p>
            <div class="card-footer">
                <span class="new-price">${formatRupiah(p.price_retail)}</span>
            </div>
        </div>
    `;

    return card;
}

/**
 * Filter kartu berdasarkan kategori DAN keyword pencarian.
 * Dipanggil saat filter kategori diklik atau saat mengetik di search.
 */
function filterProducts() {
    const keyword = (document.getElementById('header-search')?.value || '').toLowerCase();
    const cards   = document.querySelectorAll('#product-container .card');

    cards.forEach(card => {
        const title    = (card.querySelector('.card-title')?.innerText || '').toLowerCase();
        const desc     = (card.querySelector('.card-desc')?.innerText  || '').toLowerCase();
        const category = (card.dataset.category || '').toLowerCase();

        const matchSearch   = title.includes(keyword) || desc.includes(keyword);
        const matchCategory = currentCategory === 'all' || category === currentCategory.toLowerCase();

        card.style.display = (matchSearch && matchCategory) ? 'block' : 'none';
    });
}

/**
 * Set kategori aktif lalu jalankan filter.
 * Dipanggil dari tombol filter di blade.
 */
function filterCategory(category) {
    currentCategory = category;

    // Update visual tombol aktif
    document.querySelectorAll('.btn.btn-outline').forEach(btn => {
        btn.classList.remove('active');
    });
    event.currentTarget.classList.add('active');

    filterProducts();
}

// ============================================
// Helpers
// ============================================

function formatRupiah(num) {
    if (!num && num !== 0) return '-';
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str));
    return div.innerHTML;
}
