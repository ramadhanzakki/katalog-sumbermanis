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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
    <script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
