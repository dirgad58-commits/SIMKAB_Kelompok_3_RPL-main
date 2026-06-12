<!-- ==========================================================================
     TAB 7: MUTASI & PROMOSI
     ========================================================================== -->
<section id="panel-mutasi" class="panel-section">
    <div class="action-bar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="mutasi-search" class="search-input" placeholder="Cari mutasi atau promosi...">
        </div>
        <button class="btn btn-primary" id="btn-mutasikan-karyawan">
            <i class="fa-solid fa-shuffle"></i> Pengajuan Rotasi / Mutasi
        </button>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table" id="table-mutasi">
                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Jenis Aksi</th>
                        <th>Divisi (Lama &rarr; Baru)</th>
                        <th>Jabatan (Lama &rarr; Baru)</th>
                        <th>Tanggal Efektif</th>
                        <th>Keterangan Alasan</th>
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
     MODALS UNTUK MUTASI
     ========================================================================== -->

<!-- 5. Modal Pengajuan Mutasi / Promosi -->
<div class="modal-overlay" id="modal-mutasi">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-shuffle"></i> Formulir Pengajuan Mutasi / Promosi</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-mutasi">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="mutasi-karyawan">Pilih Karyawan</label>
                        <select id="mutasi-karyawan" class="form-control" required></select>
                    </div>
                    <div class="form-group">
                        <label for="mutasi-jenis">Jenis Penyesuaian Karir</label>
                        <select id="mutasi-jenis" class="form-control" required>
                            <option value="Promosi">Promosi (Kenaikan Jabatan)</option>
                            <option value="Mutasi">Mutasi (Rotasi Divisi/Tugas)</option>
                            <option value="Demosi">Demosi (Penurunan Jabatan)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mutasi-tanggal">Tanggal Efektif</label>
                        <input type="date" id="mutasi-tanggal" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="mutasi-divisi-baru">Divisi Baru</label>
                        <select id="mutasi-divisi-baru" class="form-control" required>
                            <option value="">Pilih Divisi</option>
                            <option value="Teknologi Informasi">Teknologi Informasi</option>
                            <option value="Operasional & Layanan">Operasional & Layanan</option>
                            <option value="Kredit & Pembiayaan">Kredit & Pembiayaan</option>
                            <option value="Human Resources">Human Resources</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mutasi-jabatan-baru">Jabatan Struktural Baru</label>
                        <select id="mutasi-jabatan-baru" class="form-control" required disabled>
                            <option value="">Pilih Divisi Terlebih Dahulu</option>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="mutasi-keterangan">Keterangan / Alasan Resmi</label>
                        <textarea id="mutasi-keterangan" class="form-control" placeholder="Tuliskan landasan keputusan penyesuaian karir ini (berdasarkan SK Direksi)..." style="height: 80px; resize: none;" required></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-mutasi">Simpan Mutasi</button>
        </div>
    </div>
</div>

