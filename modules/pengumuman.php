<!-- ==========================================================================
     TAB 10: PORTAL PENGUMUMAN & KEBIJAKAN
     ========================================================================== -->
<section id="panel-pengumuman" class="panel-section">
    <div class="action-bar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="pengumuman-search" class="search-input" placeholder="Cari postingan pengumuman...">
        </div>
        <button class="btn btn-primary" id="btn-tambah-pengumuman">
            <i class="fa-solid fa-bullhorn"></i> Tulis Pengumuman Baru
        </button>
    </div>

    <div class="announcements-list" id="container-pengumuman">
        <!-- Loaded dynamically via JS -->
    </div>
</section>

<!-- ==========================================================================
     MODALS UNTUK PENGUMUMAN
     ========================================================================== -->

<!-- 8. Modal Tulis Pengumuman Baru -->
<div class="modal-overlay" id="modal-pengumuman">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-bullhorn"></i> Buat Pengumuman Baru</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-pengumuman">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="pengumuman-judul">Judul Pengumuman</label>
                        <input type="text" id="pengumuman-judul" class="form-control" placeholder="Tuliskan judul pengumuman yang jelas & padat..." required>
                    </div>
                    <div class="form-group">
                        <label for="pengumuman-kategori">Prioritas / Kategori</label>
                        <select id="pengumuman-kategori" class="form-control" required>
                            <option value="Umum">Umum (Info Biasa)</option>
                            <option value="Penting">Penting (Tindakan Segera)</option>
                            <option value="Info">Info (Pengumuman Santai)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pengumuman-pengirim">Divisi Pengirim</label>
                        <input type="text" id="pengumuman-pengirim" class="form-control" placeholder="Contoh: Divisi Keamanan Informasi" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="pengumuman-konten">Isi Pengumuman Lengkap</label>
                        <textarea id="pengumuman-konten" class="form-control" placeholder="Tuliskan isi memo internal bank..." style="height: 150px; resize: none;" required></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-pengumuman">Publish Memo</button>
        </div>
    </div>
</div>

