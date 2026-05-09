let currentSlide = 0;
let itemsPerView = 2;
let totalPages = 0;

function initCarousel() {
    const track = document.getElementById('banner-track');
    const slides = document.querySelectorAll('.banner-slide');
    if (!track || slides.length === 0) return;

    // Responsive itemsPerView
    if (window.innerWidth <= 768) {
        itemsPerView = 1;
    } else {
        itemsPerView = 2;
    }

    totalPages = Math.ceil(slides.length / itemsPerView);

    // Generate dots
    const dotsContainer = document.getElementById('carousel-dots');
    if (dotsContainer) {
        dotsContainer.innerHTML = '';
        for (let i = 0; i < totalPages; i++) {
            const dot = document.createElement('span');
            dot.className = `dot ${i === currentSlide ? 'active' : ''}`;
            dot.onclick = () => goToSlide(i);
            dotsContainer.appendChild(dot);
        }
    }

    // Ensure currentSlide is within bounds
    if (currentSlide >= totalPages) {
        currentSlide = Math.max(0, totalPages - 1);
    }

    updateCarousel();
}

document.addEventListener('DOMContentLoaded', function () {
    const btnPrev = document.getElementById('banner-prev');
    const btnNext = document.getElementById('banner-next');

    if (btnPrev) btnPrev.onclick = prevBanner;
    if (btnNext) btnNext.onclick = nextBanner;

    initCarousel();
    
    // Re-initialize on window resize
    window.addEventListener('resize', initCarousel);
});

function updateCarousel() {
    const track  = document.getElementById('banner-track');
    const dots   = document.querySelectorAll('.dot');
    
    if (!track || totalPages === 0) return;

    track.style.transform = `translateX(-${currentSlide * 100}%)`;

    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === currentSlide);
    });
}

function nextBanner() {
    if (totalPages === 0) return;
    currentSlide = (currentSlide + 1) % totalPages;
    updateCarousel();
}

function prevBanner() {
    if (totalPages === 0) return;
    currentSlide = currentSlide === 0 ? totalPages - 1 : currentSlide - 1;
    updateCarousel();
}

function goToSlide(index) {
    currentSlide = index;
    updateCarousel();
}