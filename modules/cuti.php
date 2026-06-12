<!-- ==========================================================================
     TAB 5: PENGAJUAN CUTI
     ========================================================================== -->
<section id="panel-cuti" class="panel-section">
    <!-- Leave Info Summary Cards -->
    <div class="leave-balance-container">
        <div class="balance-card">
            <div class="title">Total Permohonan Cuti</div>
            <div class="value" id="cnt-cuti-total">0</div>
        </div>
        <div class="balance-card">
            <div class="title">Menunggu Persetujuan</div>
            <div class="value" id="cnt-cuti-pending" style="color: var(--warning);">0</div>
        </div>
        <div class="balance-card">
            <div class="title">Telah Disetujui</div>
            <div class="value" id="cnt-cuti-approved" style="color: var(--success);">0</div>
        </div>
    </div>

    <div class="action-bar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="cuti-search" class="search-input" placeholder="Cari pengajuan cuti...">
        </div>
        <button class="btn btn-primary" id="btn-ajukan-cuti">
            <i class="fa-solid fa-calendar-plus"></i> Form Pengajuan Cuti
        </button>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table" id="table-cuti">
                <thead>
                    <tr>
                        <th>Karyawan</th>
                        <th>Jenis Cuti</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <?php if (strtolower(trim($user_role)) !== 'karyawan'): ?>
                        <th style="text-align: center; width: 200px;">Aksi Admin (Persetujuan)</th>
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
     MODALS UNTUK CUTI
     ========================================================================== -->

<!-- 4. Modal Pengajuan Cuti Karyawan -->
<div class="modal-overlay" id="modal-cuti">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-calendar-minus"></i> Formulir Pengajuan Cuti</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-cuti">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="cuti-karyawan">Karyawan yang Mengajukan</label>
                        <select id="cuti-karyawan" class="form-control" required></select>
                    </div>
                    <div class="form-group">
                        <label for="cuti-jenis">Jenis Cuti</label>
                        <select id="cuti-jenis" class="form-control" required>
                            <option value="Cuti Tahunan">Cuti Tahunan</option>
                            <option value="Cuti Sakit">Cuti Sakit</option>
                            <option value="Cuti Melahirkan">Cuti Melahirkan</option>
                            <option value="Cuti Penting">Cuti Penting</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cuti-tgl-mulai">Tanggal Mulai</label>
                        <input type="date" id="cuti-tgl-mulai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="cuti-tgl-selesai">Tanggal Berakhir</label>
                        <input type="date" id="cuti-tgl-selesai" class="form-control" required>
                    </div>
                    <div class="form-group full-width">
                        <label for="cuti-alasan">Alasan Mengambil Cuti</label>
                        <textarea id="cuti-alasan" class="form-control" placeholder="Tuliskan keterangan detail alasan mengambil cuti..." style="height: 80px; resize: none;" required></textarea>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-cuti">Kirim Pengajuan</button>
        </div>
    </div>
</div>

