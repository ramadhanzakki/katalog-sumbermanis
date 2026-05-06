function handleFormSubmit(event) {
    event.preventDefault();
    
    const form = document.getElementById('product-form');
    const formData = new FormData(form);
    
    const url = form.action;
    const method = form.method;
    
    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || document.querySelector('input[name="_token"]').value
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload page to show updated data
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the product.');
    });
}

function editProduct(product) {
    document.getElementById('form-title').innerHTML = '<i class="bi bi-pencil-square"></i> Edit Produk';
    const updateUrl = document.getElementById('product-form').dataset.updateUrl.replace(':id', product.id);
    document.getElementById('product-form').action = updateUrl;
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
    const storeUrl = document.getElementById('product-form').dataset.storeUrl;
    document.getElementById('product-form').action = storeUrl;
    document.getElementById('form-method').value = 'POST';
    document.getElementById('product-form').reset();
    
    document.getElementById('submit-btn').innerHTML = '<i class="bi bi-upload"></i> Simpan Produk';
    document.getElementById('cancel-btn').style.display = 'none';
    document.getElementById('image-preview').style.display = 'none';
    document.getElementById('image-preview').src = '';
}
