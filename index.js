// ============================================
// 1. FUNGSI UTAMA (LOCALSTORAGE)
// ============================================
function getProducts() {
    const data = localStorage.getItem('products');
    return data ? JSON.parse(data) : [];
}

function saveProducts(products) {
    localStorage.setItem('products', JSON.stringify(products));
}

function getCart() {
    const data = localStorage.getItem('cart');
    return data ? JSON.parse(data) : [];
}

function saveCart(cart) {
    localStorage.setItem('cart', JSON.stringify(cart));
}

function formatRupiah(num) {
    return "Rp " + num.toLocaleString('id-ID');
}

// ============================================
// 2. LOGIKA ADMIN (Jika di halaman Admin)
// ============================================
            if (document.getElementById('product-table-body')) {
                
                // Load Tabel
                let currentPage = 1;
                const rowsPerPage = 6;
                function loadAdminProducts() {

                const products = getProducts();
                const tbody = document.getElementById('product-table-body');
                tbody.innerHTML = '';

                const start = (currentPage - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                const paginatedProducts = products.slice(start, end);

                paginatedProducts.forEach((p, index) => {

                    let stockClass = 'stock-available';
                    let stockText = p.stock;

                    if (p.stock <= 0) {
                        stockClass = 'stock-out';
                        stockText = 'Habis';
                    } 
                    else if (p.stock <= 5) {
                        stockClass = 'stock-terbatas';
                    }

                    tbody.innerHTML += `
                        <tr>
                            <td>${start + index + 1}</td>
                            <td><img src="${p.image}" class="product-img" onerror="this.src='https://placehold.co/100'"></td>
                            <td><strong>${p.name}</strong><br><small>${p.description}</small></td>
                            <td>${p.category}</td>
                            <td>${formatRupiah(p.price)}</td>
                            <td>${p.wholesalePrice ? formatRupiah(p.wholesalePrice) : '-'}</td>
                            <td><span class="stock-badge ${stockClass}">${stockText}</span></td>
                            <td>
                                <button class="btn-edit" onclick="editProduct(${p.id})">
                                <i class="bi bi-box-arrow-up"></i> Edit</button>

                                <button class="btn-delete" onclick="deleteProduct(${p.id})">
                                <i class="bi bi-trash3-fill"></i> Hapus</button>
                            </td>
                        </tr>
                    `;
                });

                updateStats();
                renderPagination(products.length);

            }
            
            /* render pagination */
            
                function renderPagination(totalProducts){

                const pageCount = Math.ceil(totalProducts / rowsPerPage);
                const paginationContainer = document.getElementById("pagination");

                if(!paginationContainer) return;

                paginationContainer.innerHTML = "";

                for(let i = 1; i <= pageCount; i++){

                    paginationContainer.innerHTML += `
                    <button class="page-btn ${i === currentPage ? 'active' : ''}" 
                    onclick="changePage(${i})">${i}</button>
                    `;

                }

            }
            function changePage(page){

                currentPage = page;

                loadAdminProducts();

            }
    // Update Stats
    function updateStats() {
        const products = getProducts();
        document.getElementById('total-products').innerText = products.length;
        document.getElementById('total-stock').innerText = products.reduce((sum, p) => sum + p.stock, 0);
        document.getElementById('low-stock').innerText = products.filter(p => p.stock > 0 && p.stock <= 5).length;
        document.getElementById('out-of-stock').innerText = products.filter(p => p.stock <= 0).length;
    }

    // Add Product
    document.getElementById('product-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const products = getProducts();
        const isEdit = document.getElementById('product-id').value !== '';
        
        const newProduct = {
            id: isEdit ? parseInt(document.getElementById('product-id').value) : Date.now(),
            name: document.getElementById('product-name').value,
            category: document.getElementById('product-category').value,
            price: parseInt(document.getElementById('product-price').value) || 0,
            wholesalePrice: parseInt(document.getElementById('product-wholesale-price').value) || 0,
            wholesaleQty: parseInt(document.getElementById('product-wholesale-qty').value) || 0,
            stock: parseInt(document.getElementById('product-stock').value) || 0,
            description: document.getElementById('product-desc').value,
            image: document.getElementById('product-image').value || "https://placehold.co/400x300/3498db/white?text=Produk"
        };

        if (isEdit) {
            showAlert("Produk berhasil diupdate!", "warning");
        } else {
            showAlert("Produk berhasil ditambahkan!", "success");
        }

            function showAlert(message, type="success") {
                const alertContainer = document.getElementById("alert-container");

                alertContainer.innerHTML = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                setTimeout(() => {
                    location.reload();
                }, 3000);
            }

        if (isEdit) {
            const index = products.findIndex(p => p.id === newProduct.id);
            const oldProduct = products[index];

            // jika harga berubah, simpan harga lama
            if (oldProduct.price !== newProduct.price) {
                newProduct.oldPrice = oldProduct.price;
            } else {
                newProduct.oldPrice = oldProduct.oldPrice || null;
            }

            products[index] = newProduct;
        } else {
            newProduct.oldPrice = null;
            products.push(newProduct);
        }

        saveProducts(products);
        loadAdminProducts();

        document.getElementById("product-form").reset();
        document.getElementById("product-id").value = "";

        cancelEdit(); // kembali ke mode tambah

        /* alert('✅ Produk berhasil disimpan!'); */
    });

    // Edit Product
    window.editProduct = function(id) {
         /* alert("Anda sedang mengedit produk!"); */
        const products = getProducts();
        const p = products.find(x => x.id === id);
        if (!p) return;

        document.getElementById('product-id').value = p.id;
        document.getElementById('product-name').value = p.name;
        document.getElementById('product-price').value = p.price;
        document.getElementById('product-wholesale-price').value = p.wholesalePrice;
        document.getElementById('product-wholesale-qty').value = p.wholesaleQty;
        document.getElementById('product-stock').value = p.stock;
        document.getElementById('product-category').value = p.category;
        document.getElementById('product-desc').value = p.description;
        document.getElementById('product-image').value = p.image;

        document.getElementById('form-title').innerHTML = '<i class="bi bi-pencil-square"></i> Edit Produk';
        document.getElementById('submit-btn').innerHTML = '<i class="bi bi-arrow-repeat"></i> Update Produk';
        document.getElementById('cancel-btn').style.display = 'inline-block';

        document.getElementById("product-name").focus();
        document.getElementById("product-form").scrollIntoView({
            behavior: "smooth"
        });
    };

    // Delete Product
    window.deleteProduct = function(id) {

Swal.fire({
    title: "Yakin ingin menghapus?",
    text: "Produk yang dihapus tidak bisa dikembalikan!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal"
}).then((result) => {

    if (result.isConfirmed) {

        let products = getProducts();

        products = products.filter(p => p.id !== id);

        saveProducts(products);

        loadAdminProducts();

        Swal.fire({
            title: "Terhapus!",
            text: "Produk berhasil dihapus.",
            icon: "success"
        });

    }

});

};

    // Cancel Edit
    window.cancelEdit = function() {
        document.getElementById('product-form').reset();
        document.getElementById('product-id').value = '';
        document.getElementById('form-title').innerHTML = '<i class="bi bi-plus-square-fill"></i> Tambah Produk Baru';
        document.getElementById('submit-btn').innerHTML = '<i class="bi bi-upload"></i> Simpan Produk';
        document.getElementById('cancel-btn').style.display = 'none';
    };

    // Preview Image
    window.previewImage = function(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('image-preview');
                preview.src = e.target.result;
                preview.style.display = 'block';
                document.getElementById('product-image').value = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Init
    loadAdminProducts();
}

// ============================================
// 3. LOGIKA USER (Jika di halaman User)
// ============================================
if (document.getElementById('product-container')) {
    
    // Render Katalog
    function renderUserCatalog() {
        const container = document.getElementById('product-container');
        const products = getProducts();
        container.innerHTML = '';

        if (products.length === 0) {
            container.innerHTML = '<p style="text-align:center; grid-column:1/-1;">Belum ada produk.</p>';
            return;
        }

        products.forEach(p => {
            let badge = '';
            if (p.stock <= 0) badge = '<span class="stock-badge stock-habis">Habis</span>';
            else if (p.stock <= 5) badge = '<span class="stock-badge stock-terbatas">Terbatas</span>';
            else badge = '<span class="stock-badge stock-tersedia">Tersedia</span>';

            // LOGIKA HARGA
        let priceHTML = "";

        if(p.oldPrice){
            priceHTML = `
            <div>
            <span class="old-price">${formatRupiah(p.oldPrice)}</span>
            <span class="new-price">${formatRupiah(p.price)}</span>
            </div>
            `;
        }else{
            priceHTML = `
            <div>
            <span class="new-price">${formatRupiah(p.price)}</span>
            </div>
            
            `;
        }
            container.innerHTML += `
                <div class="card" data-category="${p.category} onclick="openModal(${p.id})" style="cursor:pointer;">
                    <img src="${p.image}" class="card-image">
                    <div class="card-content">
                        ${badge}
                        <h3 class="card-title">${p.name}</h3>
                        <p class="card-desc">${p.description}</p>
                        
                        <div class="card-footer">
                             ${priceHTML}
                         </div>
                         
                    </div>
                </div>
            `;
        });
        updateCartCount();
    }

    // Modal Functions (Sederhana - Tanpa Qty/Total)
    let produkAktif = null;

    window.openModal = function(id) {
        const products = getProducts();
        produkAktif = products.find(p => p.id === id);
        if (!produkAktif) return;

        // Isi Data Modal
        document.getElementById('modal-img').src = produkAktif.image;
        document.getElementById('modal-title').innerText = produkAktif.name;
        document.getElementById('modal-desc').innerText = produkAktif.description;
        document.getElementById('modal-price-retail').innerText = formatRupiah(produkAktif.price);
        
        // Grosir
        const box = document.getElementById('modal-grosir-box');
        if (produkAktif.wholesalePrice) {
            box.style.display = 'block';
            document.getElementById('modal-min-grosir').innerText = produkAktif.wholesaleQty;
            document.getElementById('modal-price-grosir').innerText = formatRupiah(produkAktif.wholesalePrice);
        } else {
            box.style.display = 'none';
        }

        // Stok
        /* const btnBeli = document.querySelector('.btn-buy-large');
        if (produkAktif.stock <= 0) {
            btnBeli.disabled = true;
            btnBeli.innerText = 'Stok Habis';
        } else {
            btnBeli.disabled = false;
            btnBeli.innerText = 'Beli Sekarang';
        } */

        document.getElementById('productModal').style.display = 'block';
    };

    window.closeModal = function(e) {
        if (!e || e.target.id === 'productModal') {
            document.getElementById('productModal').style.display = 'none';
        }
    };

    window.beliProduk = function() {
        if (!produkAktif) return;
        alert(`✅ Berhasil!\n\nProduk: ${produkAktif.name}\nTotal: ${formatRupiah(produkAktif.price)}`);
    };

    // Cart Functions
    window.addToCart = function(id) {
        const products = getProducts();
        const p = products.find(x => x.id === id);
        if (!p || p.stock <= 0) { alert('Stok Habis!'); return; }
        
        let cart = getCart();
        cart.push(p);
        saveCart(cart);
        updateCartCount();
        alert(p.name + ' masuk keranjang!');
    };

    function updateCartCount() {
        const count = getCart().length;
        const badge = document.getElementById('cart-count');
        if (badge) badge.innerText = count;
    }

    // Init
    renderUserCatalog();
}

// ============================================
// 4. NAVBAR HAMBURGER MENU
// ============================================
function toggleMenu() {
    const navMenu = document.getElementById('nav-menu');
    const hamburger = document.querySelector('.hamburger');
    const overlay = document.getElementById('nav-overlay');
    
    navMenu.classList.toggle('active');
    hamburger.classList.toggle('active');
    
    if (!overlay) {
        const newOverlay = document.createElement('div');
        newOverlay.id = 'nav-overlay';
        newOverlay.className = 'nav-overlay';
        newOverlay.onclick = toggleMenu;
        document.body.appendChild(newOverlay);
        newOverlay.classList.add('active');
    } else {
        overlay.classList.toggle('active');
    }
    
    document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
}

// ============================================
// 5. SEARCH & FILTER
// ============================================
    // --- FUNGSI PENCARIAN (LIVE SEARCH) ---
    
    // 1. Event Listener agar pencarian jalan saat mengetik (Live Search)
    document.getElementById('header-search').addEventListener('keyup', function() {
        searchProducts();
    });

    // 2. Fungsi Utama Pencarian
    function searchProducts() {
        // Ambil nilai dari input
        let input = document.getElementById('header-search').value.toLowerCase();
        
        // Ambil semua kartu produk
        let cards = document.getElementsByClassName('product-card'); // Pastikan class di HTML Anda adalah 'product-card'
        
        // Loop setiap produk
        for (let i = 0; i < cards.length; i++) {
            let title = cards[i].querySelector('.product-title'); // Ambil elemen judul produk
            let desc = cards[i].querySelector('.product-desc');   // Ambil elemen deskripsi (opsional)
            
            let textValue = "";
            if (title) textValue = title.innerText.toLowerCase();
            if (desc) textValue += " " + desc.innerText.toLowerCase();

            // Cek apakah kata kunci ada di judul atau deskripsi
            if (textValue.indexOf(input) > -1) {
                cards[i].style.display = ""; // Tampilkan
            } else {
                cards[i].style.display = "none"; // Sembunyikan
            }
        }

        // Opsional: Tampilkan pesan jika tidak ada hasil
        if (input.length > 0) {
            let totalVisible = 0;
            for (let i = 0; i < cards.length; i++) {
                if (cards[i].style.display !== "none") totalVisible++;
            }
            
            if (totalVisible === 0) {
                // Jika kosong, Anda bisa menampilkan pesan "Produk tidak ditemukan"
                // Contoh: alert("Produk tidak ditemukan!");
            }
        }
    }
/* function searchProducts() {
    const input = document.getElementById('search-input').value.toLowerCase();
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        const title = card.querySelector('.card-title').innerText.toLowerCase();
        const desc = card.querySelector('.card-desc').innerText.toLowerCase();
        
        if (title.includes(input) || desc.includes(input)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
} */

        function filterCategory(category){

        const cards = document.querySelectorAll('.card');

        cards.forEach(card => {

        const cardCategory = card.dataset.category;

        if(category === "all" || cardCategory === category){
        card.style.display = "block";
        }else{
        card.style.display = "none";
        }

        });

        }
// ============================================
// 6. AUTH CHECK (Opsional)
// ============================================
function checkAuth() {
    const userMenu = document.getElementById('user-menu');
    const adminMenu = document.getElementById('admin-menu');
    
    if (localStorage.getItem('isLoggedIn') === 'true') {
        if (localStorage.getItem('isAdmin') === 'true') {
            adminMenu.style.display = 'block';
        } else {
            userMenu.style.display = 'block';
        }
    }
}

function logout() {
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('isAdmin');
    location.reload();
}

    // --- VARIABEL GLOBAL UNTUK FILTER ---
    let currentCategory = 'all'; // Kategori default

    // --- FUNGSI FILTER KATEGORI ---
    function filterCategory(category) {
        currentCategory = category; // Simpan kategori yang dipilih
        
        // 1. Update Status Aktif pada Tombol
        updateActiveButton(category);
        
        // 2. Render ulang produk dengan filter kategori
        renderUserCatalog();
    }

    // --- FUNGSI UPDATE TOMBOL AKTIF ---
    function updateActiveButton(category) {
        // Hapus class 'active' dari semua tombol
        let buttons = document.querySelectorAll('.btn-outline');
        buttons.forEach(btn => btn.classList.remove('active'));
        
        // Tambahkan class 'active' ke tombol yang diklik
        // (Asumsi tombol punya onclick="filterCategory('...')")
        let activeBtn = document.querySelector(`button[onclick="filterCategory('${category}')"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
        }
    }

    // --- FUNGSI PENCARIAN (LIVE SEARCH) ---
    function searchProducts() {
        let input = document.getElementById('header-search').value.toLowerCase();
        let cards = document.getElementsByClassName('product-card');
        
        for (let i = 0; i < cards.length; i++) {
            let title = cards[i].querySelector('.product-title');
            let desc = cards[i].querySelector('.product-desc');
            let category = cards[i].getAttribute('data-category'); // Asumsi ada atribut data-category
            
            let textValue = "";
            if (title) textValue = title.innerText.toLowerCase();
            if (desc) textValue += " " + desc.innerText.toLowerCase();

            // LOGIKA PENTING: Cek KATA KUNCI DAN KATEGORI
            let matchesSearch = textValue.indexOf(input) > -1;
            let matchesCategory = (category === currentCategory) || (currentCategory === 'all');

            if (matchesSearch && matchesCategory) {
                cards[i].style.display = ""; // Tampilkan
                cards[i].classList.add('animate-product'); // Tambah animasi
            } else {
                cards[i].style.display = "none"; // Sembunyikan
                cards[i].classList.remove('animate-product'); // Hapus animasi
            }
        }
    }

    // --- FUNGSI RENDER PRODUK DENGAN ANIMASI ---
    function renderUserCatalog() {
        // Asumsi Anda punya array produk 'products' di script.js
        // Contoh: const products = [...];
        
        let container = document.getElementById('product-container');
        container.innerHTML = ''; // Bersihkan container

        // Filter produk berdasarkan kategori dulu
        let filteredProducts = products.filter(p => {
            return currentCategory === 'all' || p.category === currentCategory;
        });

        // Render produk
        filteredProducts.forEach((product, index) => {
            let card = document.createElement('div');
            card.className = 'product-card animate-product'; // Tambah class animasi
            card.setAttribute('data-category', product.category); // Simpan kategori
            
            // Isi HTML card (Sesuaikan dengan struktur card Anda)
            card.innerHTML = `
                <img src="${product.image}" class="product-img" alt="${product.name}">
                <div class="card-body">
                    <h6 class="product-title">${product.name}</h6>
                    <p class="product-desc">${product.description}</p>
                    <p class="price">Rp ${product.price.toLocaleString()}</p>
                    <button class="btn btn-outline-primary btn-sm w-100" onclick="openModal(${product.id})">Lihat Detail</button>
                </div>
            `;
            container.appendChild(card);
        });
    }

    // --- EVENT LISTENER SEARCH ---
    document.getElementById('header-search').addEventListener('keyup', function() {
        searchProducts();
    });