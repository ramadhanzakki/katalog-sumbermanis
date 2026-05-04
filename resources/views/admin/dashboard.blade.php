<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sumber Manis</title>
    <!-- Ganti href css jika ada file css global, untuk contoh ini menggunakan tag style bawaan -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="shortcut icon" href="{{ asset('foto/logomitra.png') }}" type="image/png">
    <style>
        html, body{
            overscroll-behavior: none;
        }
        /* Admin Specific Styles */
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 260px;
            background-color: #2c3e50;
            color: white;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 2rem;
            text-align: center;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-menu {
            list-style: none;
            flex: 1;
            padding-left: 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 0.5rem;
        }
        
        .sidebar-menu a {
            color: #bbb;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background-color: #5aa8b7;
            color: white;
        }
        
        .admin-content {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
            background-color: #FFF6E5;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-left: auto;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        /* Form Styles */
        .form-container {
            background: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .form-container h3 {
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .btn-submit {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            background-color: #219a52;
        }
        
        .btn-cancel {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            display: none;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f8f9fa;
        }
        
        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        
        .stock-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .stock-available {
            background-color: #d4edda;
            color: #155724;
        }
        
        .stock-limited {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .stock-out {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .action-btns {
            display: flex;
            gap: 8px;
        }
        
        .btn-edit {
            background-color: #f39c12;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-bottom: 3px;
        }
        
        .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .btn-edit:hover {
            background-color: #e67e22;
        }
        
        .btn-delete:hover {
            background-color: #c0392b;
        }
        
        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-card h3 {
            font-size: 2rem;
            color: #4292a2;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .admin-layout {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .admin-content {
                margin-left: 0;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
        .pagination{
            margin-top:20px;
            text-align:center;
        }

        .btn-lihat {
            padding: 10px 20px;
            text-decoration: none;
            border: 2px solid #83adb5;
            font-weight: bold;
            color: #ffffff;
            border-radius: 8px;
            background-color: #5aa8b7;
        }
        .btn-lihat:hover{
            box-shadow: 0 10px 25px #5aa8b7b7;
            transform:scale(1.05);
            color: white;
        }
    </style>
</head>
<body>

    <!-- LAYOUT ADMIN -->
    <div class="admin-layout">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <i class="bi bi-database-fill-gear"></i> Dasboard<span>SM</span>
            </div>
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="active"><i class="bi bi-box-seam-fill"></i> Kelola Produk</a></li>
                <!-- <li><a href="#"><i class="bi bi-file-richtext"></i> Kelola Banner</a></li> -->
                <li style="margin-top: auto;">
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: #bbb; padding: 12px 15px; width: 100%; text-align: left; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <i class="bi bi-door-closed-fill"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="admin-content">
            <div class="admin-header">
                <h2><i class="bi bi-box-seam-fill"></i> Kelola Data Produk</h2>
                <a href="{{ route('user.index') }}" target="_blank" class="btn-lihat"><i class="bi bi-shop"></i> Lihat Website</a>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>{{ $totalProducts }}</h3>
                    <p>Total Produk</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $totalStock }}</h3>
                    <p>Total Stok</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $lowStock }}</h3>
                    <p>Stok Menipis</p>
                </div>
                <div class="stat-card">
                    <h3>{{ $outOfStock }}</h3>
                    <p>Stok Habis</p>
                </div>
            </div>

            <!-- FORM TAMBAH/EDIT PRODUK -->
            <div class="form-container">
                <h3 id="form-title"><i class="bi bi-plus-square-fill"></i> Tambah Produk Baru</h3>
                <form id="product-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    
                    <div id="alert-container">
                        <div class="form-grid">
                            <div class="form-group">
                                <label><i class="bi bi-pencil-square"></i> Nama Produk</label>
                                <input type="text" id="product-name" name="name" placeholder="Contoh: Sepatu Running" required>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="bi bi-card-list"></i> Kategori</label>
                                <select id="product-category" name="category_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="bi bi-tag-fill"></i> Harga Retail (Rp)</label>
                                <input type="number" id="product-price" name="price_retail" placeholder="Contoh: 550000" required>
                            </div>
                            
                            <div class="form-group">
                                <label><i class="bi bi-tags-fill"></i> Harga Grosir (Rp)</label>
                                <input type="number" id="product-wholesale-price" name="price_wholesale" placeholder="Contoh: 500000">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="bi bi-plus-slash-minus"></i> Jumlah Min. Grosir</label>
                                <input type="number" id="product-wholesale-qty" name="wholesale_min_qty" placeholder="Contoh: 5" value="5">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="bi bi-boxes"></i> Stok</label>
                                <input type="number" id="product-stock" name="stock" placeholder="Contoh: 100" required>
                            </div>
                            
                            <div class="form-group full-width">
                                <label><i class="bi bi-info-lg"></i> Deskripsi Produk</label>
                                <textarea id="product-desc" name="description" rows="3" placeholder="Deskripsi produk..."></textarea>
                            </div>
                            
                            <div class="form-group full-width">
                                <label><i class="bi bi-images"></i> Upload Gambar</label>
                                <input type="file" id="product-image-file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                                <img id="image-preview" src="" style="max-width: 200px; margin-top: 10px; display: none; border-radius: 8px;">
                            </div>
                        </div>
                        
                        <div style="margin-top: 1rem; display: flex; gap: 10px;">
                            <button type="submit" class="btn-submit" id="submit-btn"><i class="bi bi-upload"></i> Simpan Produk</button>
                            <button type="button" class="btn-cancel" id="cancel-btn" onclick="cancelEdit()" style="display:none;"><i class="bi bi-x-circle-fill"></i> Batal</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- TABEL DAFTAR PRODUK -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Harga Retail</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body">
                        @forelse($products as $index => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $index }}</td>
                                <td><img src="{{ $product->image_url }}" alt="Produk" class="product-img"></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? '-' }}</td>
                                <td>Rp {{ number_format($product->price_retail, 0, ',', '.') }}</td>
                                <td>
                                    @if($product->stock > 5)
                                        <span class="stock-badge stock-available">{{ $product->stock }} (Tersedia)</span>
                                    @elseif($product->stock > 0)
                                        <span class="stock-badge stock-limited">{{ $product->stock }} (Terbatas)</span>
                                    @else
                                        <span class="stock-badge stock-out">0 (Habis)</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button type="button" class="btn-edit" onclick="editProduct({{ $product->toJson() }})"><i class="bi bi-pencil-square"></i></button>
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
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
            document.getElementById('product-form').action = '{{ route('admin.products.store') }}';
            document.getElementById('form-method').value = 'POST';
            document.getElementById('product-form').reset();
            
            document.getElementById('submit-btn').innerHTML = '<i class="bi bi-upload"></i> Simpan Produk';
            document.getElementById('cancel-btn').style.display = 'none';
            document.getElementById('image-preview').style.display = 'none';
            document.getElementById('image-preview').src = '';
        }
    </script>
</body>
</html>
