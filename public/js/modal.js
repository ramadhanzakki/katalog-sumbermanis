function openModal(modalId) {
    document.getElementById(modalId).style.display = "flex";
    document.body.style.overflow = "hidden";
}

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