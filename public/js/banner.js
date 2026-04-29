/**
 * banner.js
 * Logika carousel banner untuk halaman user (katalog).
 * Data banner dikirim dari Laravel via variabel global BANNERS_DATA
 * yang diisi di blade sebelum script ini di-load.
 *
 * Variabel yang harus tersedia (dideklarasikan di blade):
 *   const BANNERS_DATA = @json($banners);  // array banner dari DB
 */

let currentSlide = 0;

document.addEventListener('DOMContentLoaded', function () {
    renderBanners();

    const btnPrev = document.getElementById('banner-prev');
    const btnNext = document.getElementById('banner-next');

    if (btnPrev) btnPrev.onclick = prevBanner;
    if (btnNext) btnNext.onclick = nextBanner;

    // Auto-slide setiap 4 detik
    setInterval(nextBanner, 4000);
});

/**
 * Render semua banner dari BANNERS_DATA ke dalam carousel.
 * Setiap slide menampilkan 2 banner berdampingan.
 */
function renderBanners() {
    const banners = window.BANNERS_DATA || [];
    const track   = document.getElementById('banner-track');
    const dots    = document.getElementById('carousel-dots');

    if (!track) return;

    track.innerHTML = '';
    dots.innerHTML  = '';

    if (banners.length === 0) {
        track.innerHTML = `
            <div style="width:100%;height:250px;background:#f0f0f0;border-radius:20px;
                        display:flex;align-items:center;justify-content:center;
                        color:#999;border:2px dashed #ddd;">
                Tidak ada banner
            </div>`;
        return;
    }

    // Kelompokkan 2 banner per slide
    for (let i = 0; i < banners.length; i += 2) {
        const slide = document.createElement('div');
        slide.className = 'banner-slide';

        // Banner kiri
        slide.appendChild(buildBannerItem(banners[i]));

        // Banner kanan (jika ada), atau placeholder kosong
        if (banners[i + 1]) {
            slide.appendChild(buildBannerItem(banners[i + 1]));
        } else {
            const empty = document.createElement('div');
            empty.style.cssText = 'flex:1;height:250px;background:#f8f9fa;border-radius:20px;' +
                                  'display:flex;align-items:center;justify-content:center;' +
                                  'color:#999;border:1px dashed #ddd;';
            empty.textContent = 'Kosong';
            slide.appendChild(empty);
        }

        track.appendChild(slide);

        // Dot indikator
        const dot    = document.createElement('span');
        dot.className = i === 0 ? 'dot active' : 'dot';
        dot.onclick   = () => { currentSlide = i / 2; updateCarousel(); };
        dots.appendChild(dot);
    }

    updateCarousel();
}

/**
 * Buat elemen <a><img> untuk satu banner.
 * image_path sudah di-resolve ke URL lengkap oleh Controller
 * sebelum di-pass ke view ($banner->image_url).
 */
function buildBannerItem(banner) {
    const link       = document.createElement('a');
    link.href        = banner.link_url || '#';
    link.target      = banner.link_url ? '_blank' : '_self';
    link.rel         = 'noopener noreferrer';

    const img        = document.createElement('img');
    img.src          = banner.image_url;   // sudah full URL dari controller
    img.alt          = 'Banner Promo';
    img.style.cssText = 'flex:1;height:250px;object-fit:cover;border-radius:20px;';

    link.appendChild(img);
    return link;
}

function updateCarousel() {
    const track  = document.getElementById('banner-track');
    const slides = document.querySelectorAll('.banner-slide');
    const dots   = document.querySelectorAll('.dot');

    if (!track || slides.length === 0) return;

    track.style.transform = `translateX(-${currentSlide * 100}%)`;

    dots.forEach((dot, i) => dot.classList.toggle('active', i === currentSlide));
}

function nextBanner() {
    const slides = document.querySelectorAll('.banner-slide');
    if (slides.length === 0) return;
    currentSlide = (currentSlide + 1) % slides.length;
    updateCarousel();
}

function prevBanner() {
    const slides = document.querySelectorAll('.banner-slide');
    if (slides.length === 0) return;
    currentSlide = currentSlide === 0 ? slides.length - 1 : currentSlide - 1;
    updateCarousel();
}
