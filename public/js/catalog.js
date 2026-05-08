let currentCategory = 'all';

document.addEventListener('DOMContentLoaded', function () {
    // Live search saat mengetik
    const searchInput = document.getElementById('header-search');
    if (searchInput) {
        searchInput.addEventListener('keyup', filterProducts);
    }
});

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

function filterCategory(category) {
    currentCategory = category;
    
    document.querySelectorAll('.btn .btn-outline').forEach(btn => {
        btn.classList.remove('active');
    });
    event.currentTarget.classList.add('active');

    filterProducts();
}