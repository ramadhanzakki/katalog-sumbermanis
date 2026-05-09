let maxBanners = 6;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Banner admin loaded');
    
    // Preview gambar
    document.getElementById('banner-image').onchange = previewImage;
});

function previewImage() {
    const file = this.files[0];
    const preview = document.getElementById('banner-preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}