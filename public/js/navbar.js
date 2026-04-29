/**
 * navbar.js
 * Hamburger menu toggle untuk mobile.
 */

function toggleMenu() {
    const navMenu   = document.getElementById('nav-menu');
    const hamburger = document.querySelector('.hamburger');
    let overlay     = document.getElementById('nav-overlay');

    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');

    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id        = 'nav-overlay';
        overlay.className = 'nav-overlay';
        overlay.onclick   = toggleMenu;
        document.body.appendChild(overlay);
        overlay.classList.add('active');
    } else {
        overlay.classList.toggle('active');
    }

    document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
}
