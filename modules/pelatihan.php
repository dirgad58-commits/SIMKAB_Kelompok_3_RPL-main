<!-- ==========================================================================
     TAB 8: PELATIHAN & SERTIFIKASI
     ========================================================================== -->
<section id="panel-pelatihan" class="panel-section">
    <div class="action-bar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="pelatihan-search" class="search-input" placeholder="Cari sertifikasi atau pelatihan...">
        </div>
        <button class="btn btn-primary" id="btn-tambah-pelatihan">
            <i class="fa-solid fa-plus"></i> 
            <?php echo (strtolower(trim($user_role)) === 'karyawan') ? 'Ajukan Sertifikat Baru' : 'Catat Pelatihan & Sertifikat'; ?>
        </button>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table" id="table-pelatihan">
                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Nama Program Pelatihan</th>
                        <th>Penyelenggara / Penerbit</th>
                        <th>Tanggal Keluar</th>
                        <th>Status Sertifikat</th>
                        <th>Bukti</th>
                        <?php if (strtolower(trim($user_role)) !== 'karyawan'): ?>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <!-- Terisi via JS -->
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ==========================================================================
     MODALS UNTUK PELATIHAN
     ========================================================================== -->

<!-- 6. Modal Registrasi Pelatihan & Sertifikat -->
<div class="modal-overlay" id="modal-pelatihan">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-graduation-cap"></i> Input Data Pelatihan & Sertifikasi</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-pelatihan">
                <div class="form-grid">
                    <div class="form-group" id="group-pelatihan-karyawan">
                        <label for="pelatihan-karyawan">Pilih Karyawan</label>
                        <select id="pelatihan-karyawan" class="form-control" required></select>
                    </div>
                    <div class="form-group">
                        <label for="pelatihan-nama">Nama Program Pelatihan / Sertifikasi</label>
                        <input type="text" id="pelatihan-nama" class="form-control" placeholder="Contoh: Sertifikasi Manajemen Risiko BSMR Level 1" required>
                    </div>
                    <div class="form-group">
                        <label for="pelatihan-penyelenggara">Penyelenggara / Penerbit Sertifikat</label>
                        <input type="text" id="pelatihan-penyelenggara" class="form-control" placeholder="Contoh: LSP Perbankan, EC-Council" required>
                    </div>
                    <div class="form-group">
                        <label for="pelatihan-tanggal">Tanggal Terbit Sertifikat</label>
                        <input type="date" id="pelatihan-tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="pelatihan-status">Status Masa Berlaku</label>
                        <select id="pelatihan-status" class="form-control" required>
                            <option value="Valid">Valid (Aktif)</option>
                            <option value="Kedaluwarsa">Kedaluwarsa (Expired)</option>
                            <option value="Sedang Berjalan">Sedang Berjalan / Proses</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pelatihan-file">Unggah Bukti Sertifikat</label>
                        <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 5px;">Format: JPG, PNG, PDF. Maks: 2MB.</div>
                        <input type="file" id="pelatihan-file" class="form-control" accept="image/png, image/jpeg, image/jpg, application/pdf">
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-pelatihan">Catat Program</button>
        </div>
    </div>
</div>

