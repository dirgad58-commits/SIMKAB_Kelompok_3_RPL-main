<!-- ==========================================================================
     TAB 9: ASET & INVENTARIS KANTOR
     ========================================================================== -->
<section id="panel-aset" class="panel-section">
    <div class="action-bar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="aset-search" class="search-input" placeholder="Cari peminjaman aset...">
        </div>
        <button class="btn btn-primary" id="btn-tambah-aset">
            <i class="fa-solid fa-hand-holding-hand"></i> Peminjaman Aset Baru
        </button>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table" id="table-aset">
                <thead>
                    <tr>
                        <th>Karyawan Peminjam</th>
                        <th>Nama Aset</th>
                        <th>Kode Register Aset</th>
                        <th>Tanggal Pinjam</th>
                        <th>Tanggal Kembali</th>
                        <th>Status Aset</th>
                        <th style="text-align: center; width: 150px;">Aksi Admin</th>
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
     MODALS UNTUK ASET
     ========================================================================== -->

<!-- 7. Modal Peminjaman Aset Baru -->
<div class="modal-overlay" id="modal-aset">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-laptop-code"></i> Formulir Peminjaman Inventaris</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-aset">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="aset-karyawan">Pilih Karyawan Peminjam</label>
                        <select id="aset-karyawan" class="form-control" required></select>
                    </div>
                    <div class="form-group">
                        <label for="aset-nama">Nama Aset / Inventaris</label>
                        <input type="text" id="aset-nama" class="form-control" placeholder="Contoh: Laptop HP ProBook" required>
                    </div>
                    <div class="form-group">
                        <label for="aset-kode">Kode Inventaris (Register)</label>
                        <input type="text" id="aset-kode" class="form-control" placeholder="Contoh: AST-BANK-049" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="aset-tgl-pinjam">Tanggal Penyerahan (Pinjam)</label>
                        <input type="date" id="aset-tgl-pinjam" class="form-control" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-aset">Konfirmasi Pinjam</button>
        </div>
    </div>
</div>

