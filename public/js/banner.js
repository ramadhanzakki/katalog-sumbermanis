let currentSlide = 0;

document.addEventListener('DOMContentLoaded', function () {

    const btnPrev = document.getElementById('banner-prev');
    const btnNext = document.getElementById('banner-next');

    if (btnPrev) btnPrev.onclick = prevBanner;
    if (btnNext) btnNext.onclick = nextBanner;

    // Matikan auto-slide, geser manual saja
    // setInterval(nextBanner, 4000);
});

function updateCarousel() {
    const track  = document.getElementById('banner-track');
    const slides = document.querySelectorAll('.banner-slide');
    const dots   = document.querySelectorAll('.dot');

    if (!track || slides.length === 0) return;

    // Geser berdasarkan persentase (100% per slide)
    track.style.transform = `translateX(-${currentSlide * 100}%)`;

    // Update dot indicators
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

function goToSlide(index) {
    currentSlide = index;
    updateCarousel();
}