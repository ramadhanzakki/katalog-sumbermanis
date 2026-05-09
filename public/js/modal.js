function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
    document.body.style.overflow = "hidden";
}

document.addEventListener('DOMContentLoaded', function () {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(modal => {
        // Cari elemen di dalam masing-masing modal berdasarkan ID
        const grosirBox = modal.querySelector('#modal-grosir-box');
        const minGrosirEl = modal.querySelector('#modal-min-grosir');
        const priceGrosirEl = modal.querySelector('#modal-price-grosir');

        if (grosirBox && minGrosirEl && priceGrosirEl) {
            const minQty = parseInt(minGrosirEl.innerText) || 0;
            const priceText = priceGrosirEl.innerText.replace(/[^0-9]/g, '');
            const price = parseInt(priceText) || 0;

            // Sembunyikan box grosir jika harga 0 (atau tidak diset) DAN min qty 0
            if (minQty === 0 && price === 0) {
                grosirBox.style.display = 'none';
            }
        }
    });
});

function closeModal(event) {
    
    // Klik di luar modal
    if (event && event.target.classList.contains("modal-overlay")) {
        event.target.style.display = "none";
    }

    // Klik tombol close
    if (!event) {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.style.display = "none";
        });
    }

    document.body.style.overflow = "auto";
}

// Klik tombol ESC
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        document.querySelectorAll('.modal-overlay').forEach(modal => {
            modal.style.display = "none";
        });

        document.body.style.overflow = "auto";
    }
});