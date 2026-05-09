<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kelola Banner</title>
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
                <li><a href="{{ route('admin.dashboard') }}"><i class="bi bi-box-seam-fill"></i> Kelola Produk</a></li>
                <li><a href="{{ route('admin.banner.index') ?? '#' }}" class="active"><i class="bi bi-file-richtext"></i> Kelola Banner</a></li>
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
                <h2><i class="bi bi-file-richtext"></i> Kelola Banner Produk</h2>
                <a href="{{ route('user.index') }}" class="btn-lihat" target="_blank"><i class="bi bi-shop"></i> Lihat Website</a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                    <ul style="margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success" style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- STATS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3 id="total-banners">{{ $totalBanners }}</h3>
                    <p>Total Banner</p>
                </div>
                <div class="stat-card">
                    <h3 id="max-banners">6</h3>
                    <p>Batas Maksimal</p>
                </div>
                <div class="stat-card">
                    <h3 id="remaining-banners">{{ $availableSlotBanners }}</h3>
                    <p>Sisa Slot Banner</p>
                </div>
            </div>

            <!-- FORM TAMBAH BANNER -->
            <div class="form-container">
                <h3><i class="bi bi-plus-circle"></i> Tambah Banner Baru</h3>
                <p>NOTE: sebelum memasukan banner, pastikan ukuran banner adalah 500 x 250 px <i class="bi bi-emoji-smile"></i></p>

                <form action="{{ route('admin.banner.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        
                        @if($selectBanner && $selectBanner->image_path)
                        <div style="margin-bottom: 8px;">
                            <img src="{{ $selectBanner->image_url }}" alt="Gambar saat ini" style="max-width: 150px; border-radius: 8px;">
                            <p style="font-size: 0.8rem; color: #aaa; margin-top: 4px;">Gambar saat ini. Upload baru untuk mengganti.</p>
                        </div>
                        @endif
                        
                        <label><i class="bi bi-images"></i> Upload Gambar Banner</label>
                        
                        <input type="file" name="image" id="banner-image" style="cursor: pointer;" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                            <div style="color: red; font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <img id="banner-preview" style="max-width:300px; max-height:150px; border-radius:8px; margin-top:10px; display:none; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
                    
                    <button type="submit" class="btn-submit mt-3">
                        <i class="bi bi-upload"></i> Upload Banner
                    </button>
                </form>
            </div>

            <!-- DAFTAR BANNER -->
            <div class="form-container">
                <h3><i class="bi bi-list-ul"></i> Daftar Banner</h3>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="banner-list">
                            @forelse ($banners as $banner)
                                <tr>
                                    <td><img src="{{ $banner->getImageUrlAttribute() }}" alt="{{ $banner->image_path }}" class="product-img"></td>
                                    <td>{{ $banner->updated_at }}</td>
                                    <td>
                                        <div class="action-btns">
                                            {{-- Tombol Edit: link ke ?edit=ID, page scroll ke atas otomatis --}}
                                            <a href="{{ route('admin.banner.index') }}?edit={{ $banner->id }}" class="btn-edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('admin.banner.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?');" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete"><i class="bi bi-trash-fill"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada banner.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script src="{{ asset('js/admin-banner.js') }}"></script>
</body>
</html>
