document.addEventListener('DOMContentLoaded', function() {
    console.log('Image product admin loaded');
    
    // Preview gambar
    document.getElementById('product-image').onchange = previewImage;
});

function previewImage() {
    const file = this.files[0];
    const preview = document.getElementById('image-preview');
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}