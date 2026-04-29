{{-- ============================================================ --}}
{{-- PARTIAL: Modal Detail Produk                                --}}
{{-- Dipanggil dengan: @include('partials.product-modal')        --}}
{{-- Hanya include di halaman yang menampilkan produk (katalog)  --}}
{{-- ============================================================ --}}

<div id="productModal" class="modal-overlay" onclick="closeModal(event)">
    <div class="modal-content" onclick="event.stopPropagation()">
        <span class="modal-close" onclick="closeModal()">&times;</span>

        <div class="modal-body">

            {{-- Gambar Produk --}}
            <div class="modal-left">
                <img id="modal-img" src="" alt="Foto Produk">
            </div>

            {{-- Detail Produk --}}
            <div class="modal-right">
                <h2 id="modal-title" class="modal-title">Nama Produk</h2>
                <p id="modal-desc" class="modal-desc">Deskripsi produk...</p>

                {{-- Kotak Harga --}}
                <div class="price-box">
                    {{-- Harga Satuan --}}
                    <div class="price-row">
                        <span class="price-label">Harga Satuan:</span>
                        <span id="modal-price-retail" class="price-val">Rp 0</span>
                    </div>

                    {{-- Harga Grosir (tersembunyi jika tidak ada) --}}
                    <div id="modal-grosir-box" style="display: none; border-top: 1px dashed #ccc; margin-top: 8px; padding-top: 8px;">
                        <div class="price-row">
                            <span class="price-label">
                                Harga Grosir (&gt; <span id="modal-min-grosir">0</span> pcs):
                            </span>
                            <span id="modal-price-grosir" class="price-val price-grosir">Rp 0</span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Hubungi via WhatsApp --}}
                <a id="modal-wa-btn"
                   href="#"
                   target="_blank"
                   class="btn-buy-large"
                   style="display: block; text-align: center; text-decoration: none; margin-top: 10px;">
                    <i class="bi bi-whatsapp"></i> Hubungi via WhatsApp
                </a>
            </div>

        </div>
    </div>
</div>
