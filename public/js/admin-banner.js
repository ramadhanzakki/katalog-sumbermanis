let maxBanners = 6;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Banner admin loaded');
    loadBanners();
    
    // Form upload
    document.getElementById('banner-form').onsubmit = addBanner;
    
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

function addBanner(e) {
    e.preventDefault();
    console.log('Add banner clicked');
    
    const file = document.getElementById('banner-image').files[0];
    const link = document.getElementById('banner-link').value;
    
    if (!file) return alert('Pilih gambar!');
    if (!link) return alert('Masukkan link!');
    
    const reader = new FileReader();
    reader.onload = function(e) {
        let banners = JSON.parse(localStorage.getItem('banners') || '[]');
        
        if (banners.length >= maxBanners) {
            return alert('Maksimal 6 banner!');
        }
        
        banners.unshift({
            id: Date.now(),
            image: e.target.result,
            link: link,
            date: new Date().toLocaleDateString()
        });
        
        localStorage.setItem('banners', JSON.stringify(banners));
        loadBanners();
        
        // Reset form
        document.getElementById('banner-image').value = '';
        document.getElementById('banner-link').value = '';
        document.getElementById('banner-preview').style.display = 'none';
        
        alert('✅ Banner berhasil ditambahkan!');
    };
    reader.readAsDataURL(file);
}

function loadBanners() {
    const banners = JSON.parse(localStorage.getItem('banners') || '[]');
    const tbody = document.getElementById('banner-list');
    
    document.getElementById('total-banners').textContent = banners.length;
    document.getElementById('remaining-banners').textContent = maxBanners - banners.length;
    
    if (banners.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;padding:20px;color:#999;">📭 Belum ada banner</td></tr>';
        return;
    }
    
    tbody.innerHTML = banners.map(b => `
        <tr>
            <td><img src="${b.image}" style="width:50px;height:35px;object-fit:cover;border-radius:4px;"></td>
            <td style="font-size:13px;max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${b.link}</td>
            <td style="font-size:12px;color:#666;">${b.date}</td>
            <td>
                <button onclick="editBanner(${b.id})" style="background:#f39c12;color:white;border:none;padding:4px 8px;border-radius:4px;font-size:11px;cursor:pointer;margin-right:5px;"><i class="bi bi-pencil-square"></i> EDIT</button>
                <button onclick="deleteBanner('${b.id}')" style="background:#e74c3c;color:white;border:none;padding:4px 8px;border-radius:4px;font-size:11px;cursor:pointer;"><i class="bi bi-trash3-fill"></i> HAPUS</button>
            </td>
        </tr>
    `).join('');
}

function deleteBanner(id) {
    if (confirm('Hapus banner ini?')) {
        let banners = JSON.parse(localStorage.getItem('banners') || '[]');
        banners = banners.filter(b => b.id != id);
        localStorage.setItem('banners', JSON.stringify(banners));
        loadBanners();
    }
}

function editBanner(id) {
    const banners = JSON.parse(localStorage.getItem('banners') || '[]');
    const banner = banners.find(b => b.id == id);
    const newLink = prompt('Edit link:', banner.link);
    if (newLink !== null && newLink.trim()) {
        banner.link = newLink.trim();
        localStorage.setItem('banners', JSON.stringify(banners));
        loadBanners();
    }
}
