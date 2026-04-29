/**
 * modal.js
 * Logika buka/tutup modal detail produk dan tampilkan info harga.
 *
 * Variabel yang harus tersedia (dari blade):
 *   const PRODUCTS_DATA = @json($products);
 *   const WA_NUMBER     = '{{ config("app.wa_number") }}'; // no WA toko
 */

/**
 * Buka modal dan isi dengan data produk berdasarkan ID.
 */
function openModal(id) {
    const products   = window.PRODUCTS_DATA || [];
    const produk     = products.find(p => p.id === id);
    if (!produk) return;

    // Isi konten modal
    document.getElementById('modal-img').src          = produk.image_url || 'https://placehold.co/400x400?text=No+Image';
    document.getElementById('modal-title').innerText  = produk.name;
    document.getElementById('modal-desc').innerText   = produk.description || '';
    document.getElementById('modal-price-retail').innerText = formatRupiah(produk.price_retail);

    // Harga Grosir — tampilkan hanya jika ada
    const grosirBox = document.getElementById('modal-grosir-box');
    if (produk.price_wholesale) {
        grosirBox.style.display = 'block';
        document.getElementById('modal-min-grosir').innerText      = produk.wholesale_min_qty || 0;
        document.getElementById('modal-price-grosir').innerText    = formatRupiah(produk.price_wholesale);
    } else {
        grosirBox.style.display = 'none';
    }

    // Tombol WhatsApp — generate pesan otomatis
    const waBtn = document.getElementById('modal-wa-btn');
    if (waBtn) {
        const pesan = `Halo, saya ingin menanyakan produk *${produk.name}* dengan harga ${formatRupiah(produk.price_retail)}. Apakah masih tersedia?`;
        const waUrl = `https://wa.me/${window.WA_NUMBER}?text=${encodeURIComponent(pesan)}`;
        waBtn.href = waUrl;
    }

    document.getElementById('productModal').style.display = 'block';
    document.body.style.overflow = 'hidden'; // cegah scroll di belakang modal
}

/**
 * Tutup modal.
 * Dipanggil saat klik tombol × atau klik area di luar modal.
 */
function closeModal(e) {
    // Jika dipanggil dari event klik overlay, cek target-nya
    if (e && e.target && e.target.id !== 'productModal') return;

    document.getElementById('productModal').style.display = 'none';
    document.body.style.overflow = ''; // kembalikan scroll
}

// Tutup modal dengan tombol Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('productModal');
        if (modal && modal.style.display === 'block') {
            closeModal();
        }
    }
});

// ============================================
// Helper
// ============================================
function formatRupiah(num) {
    if (!num && num !== 0) return '-';
    return 'Rp ' + Number(num).toLocaleString('id-ID');
}
