function previewImage(input) {
    const preview = document.getElementById('image-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '';
        preview.style.display = 'none';
    }
}

function editProduct(product) {
    document.getElementById('form-title').innerHTML = '<i class="bi bi-pencil-square"></i> Edit Produk';
    document.getElementById('product-form').action = '/admin/products/' + product.id;
    document.getElementById('form-method').value = 'PUT';
    
    document.getElementById('product-name').value = product.name;
    document.getElementById('product-category').value = product.category_id;
    document.getElementById('product-price').value = parseInt(product.price_retail);
    document.getElementById('product-wholesale-price').value = product.price_wholesale ? parseInt(product.price_wholesale) : '';
    document.getElementById('product-wholesale-qty').value = product.wholesale_min_qty || '';
    document.getElementById('product-stock').value = product.stock;
    document.getElementById('product-desc').value = product.description || '';
    
    document.getElementById('submit-btn').innerHTML = '<i class="bi bi-save"></i> Update Produk';
    document.getElementById('cancel-btn').style.display = 'inline-block';
    
    if (product.image_path) {
        document.getElementById('image-preview').src = '/storage/' + product.image_path;
        document.getElementById('image-preview').style.display = 'block';
    } else {
        document.getElementById('image-preview').style.display = 'none';
    }
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function cancelEdit() {
    document.getElementById('form-title').innerHTML = '<i class="bi bi-plus-square-fill"></i> Tambah Produk Baru';
    // Note: This URL might need to be dynamic if the route changes, but for now hardcoded based on view
    document.getElementById('product-form').action = '/admin/products'; 
    document.getElementById('form-method').value = 'POST';
    document.getElementById('product-form').reset();
    
    document.getElementById('submit-btn').innerHTML = '<i class="bi bi-upload"></i> Simpan Produk';
    document.getElementById('cancel-btn').style.display = 'none';
    document.getElementById('image-preview').style.display = 'none';
    document.getElementById('image-preview').src = '';
}
