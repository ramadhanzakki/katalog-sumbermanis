<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Sumber Manis</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                <li><a href="{{ route('admin.banner.index') }}"><i class="bi bi-file-richtext"></i> Kelola Banner</a></li>
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
                <strong>Terdapat kesalahan, mohon periksa form:</strong>
                <ul class="mb-0 mt-1">
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
                @if($selectedProduct)
                    <h3 id="form-title"><i class="bi bi-pencil-square"></i> Edit Produk: {{ $selectedProduct->name }}</h3>
                    <form
                        action="{{ route('admin.products.update', $selectedProduct->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf
                        @method('PUT')
                @else
                    <h3 id="form-title"><i class="bi bi-plus-square-fill"></i> Tambah Produk Baru</h3>
                    <form
                        action="{{ route('admin.products.store') }}"
                        method="POST"
                        enctype="multipart/form-data"
                    >
                        @csrf
                @endif

                    <div class="form-grid">
                        <div class="form-group">
                            <label><i class="bi bi-pencil-square"></i> Nama Produk</label>
                            <input
                                type="text"
                                name="name"
                                placeholder="Contoh: Gula Merah"
                                value="{{ old('name', $selectedProduct->name ?? '') }}"
                                required
                            >
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label><i class="bi bi-card-list"></i> Kategori</label>
                            <select name="category_id" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('category_id', $selectedProduct->category_id ?? '') == $category->id ? 'selected' : '' }}
                                    >{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label><i class="bi bi-tag-fill"></i> Harga Retail (Rp)</label>
                            <input
                                type="number"
                                name="price_retail"
                                placeholder="Contoh: 550000"
                                value="{{ old('price_retail', $selectedProduct->price_retail ?? '') }}"
                                required
                            >
                            @error('price_retail')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label><i class="bi bi-tags-fill"></i> Harga Grosir (Rp)</label>
                            <input
                                type="number"
                                name="price_wholesale"
                                placeholder="Contoh: 500000"
                                value="{{ old('price_wholesale', $selectedProduct->price_wholesale ?? '') }}"
                            >
                            @error('price_wholesale')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label><i class="bi bi-plus-slash-minus"></i> Jumlah Min. Grosir</label>
                            <input
                                type="number"
                                name="wholesale_min_qty"
                                placeholder="Contoh: 5"
                                value="{{ old('wholesale_min_qty', $selectedProduct->wholesale_min_qty ?? 5) }}"
                            >
                            @error('wholesale_min_qty')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label><i class="bi bi-boxes"></i> Stok</label>
                            <input
                                type="number"
                                name="stock"
                                placeholder="Contoh: 100"
                                value="{{ old('stock', $selectedProduct->stock ?? '') }}"
                                required
                            >
                            @error('stock')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label><i class="bi bi-info-lg"></i> Deskripsi Produk</label>
                            <textarea name="description" rows="3" placeholder="Deskripsi produk...">{{ old('description', $selectedProduct->description ?? '') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label><i class="bi bi-images"></i> Upload Gambar</label>
                            @if($selectedProduct && $selectedProduct->image_path)
                                <div style="margin-bottom: 8px;">
                                    <img src="{{ $selectedProduct->image_url }}" alt="Gambar saat ini" style="max-width: 150px; border-radius: 8px;">
                                    <p style="font-size: 0.8rem; color: #aaa; margin-top: 4px;">Gambar saat ini. Upload baru untuk mengganti.</p>
                                </div>
                            @endif
                            <input
                                type="file"
                                name="image"
                                class="form-control"
                            >
                            <img id="image-preview" src="" style="max-width: 200px; margin-top: 10px; display: none; border-radius: 8px;">
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div style="margin-top: 1rem; display: flex; gap: 10px;">
                        <button type="submit" class="btn-submit">
                            @if($selectedProduct)
                                <i class="bi bi-save"></i> Update Produk
                            @else
                                <i class="bi bi-upload"></i> Simpan Produk
                            @endif
                        </button>
                        @if($selectedProduct)
                            <a href="{{ route('admin.dashboard') }}" class="btn-cancel" style="display: inline-block; text-decoration: none;">
                                <i class="bi bi-x-circle-fill"></i> Batal
                            </a>
                        @endif
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
                    <tbody>
                        @forelse($products as $index => $product)
                            <tr>
                                <td>{{ $products->firstItem() + $index }}</td>
                                <td><img src="{{ $product->getImageUrlAttribute() }}" alt="{{ $product->image_path }}" class="product-img"></td>
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
                                        {{-- Tombol Edit: link ke ?edit=ID, page scroll ke atas otomatis --}}
                                        <a href="{{ route('admin.dashboard') }}?edit={{ $product->id }}" class="btn-edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
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

    {{-- Auto scroll ke form jika sedang mode edit --}}
    @if($selectedProduct)
    <script>
        window.addEventListener('load', () => {
            document.querySelector('.form-container').scrollIntoView({ behavior: 'smooth' });
        });
    </script>
    @endif
</body>
</html>
