<!-- ==========================================================================
     TAB 4: MANAJEMEN PAYROLL (PENGGAJIAN)
     ========================================================================== -->
<section id="panel-payroll" class="panel-section">
    <div class="action-bar">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="payroll-search" class="search-input" placeholder="Cari slip gaji karyawan...">
        </div>
        <?php if (strtolower(trim($user_role)) !== 'karyawan'): ?>
        <button class="btn btn-primary" id="btn-proses-payroll">
            <i class="fa-solid fa-calculator"></i> Proses Gaji Bulanan
        </button>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="table-wrapper">
            <table class="data-table" id="table-payroll">
                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Bulan</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Bonus</th>
                        <th>Potongan</th>
                        <th>Total Diterima</th>
                        <th>Status</th>
                        <th style="text-align: center; width: 150px;">Aksi</th>
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
     MODALS UNTUK PAYROLL
     ========================================================================== -->

<!-- 3. Modal Proses Gaji Bulanan -->
<div class="modal-overlay" id="modal-payroll">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-wallet"></i> Proses Slip Gaji Karyawan</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-payroll">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="payroll-karyawan">Pilih Karyawan</label>
                        <select id="payroll-karyawan" class="form-control" required></select>
                    </div>
                    <div class="form-group">
                        <label for="payroll-bulan">Bulan Penggajian</label>
                        <select id="payroll-bulan" class="form-control" required>
                            <option value="Mei 2026">Mei 2026</option>
                            <option value="Juni 2026">Juni 2026</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="payroll-bonus">Bonus Kinerja / Lembur (Rp)</label>
                        <input type="number" id="payroll-bonus" class="form-control" placeholder="0" required>
                    </div>
                    <div class="form-group">
                        <label for="payroll-potongan">Potongan / Denda Absen (Rp)</label>
                        <input type="number" id="payroll-potongan" class="form-control" placeholder="0" required>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-payroll">Kalkulasi & Posting Gaji</button>
        </div>
    </div>
</div>

<!-- 10. Modal Cetak Slip Gaji Modern -->
<div class="modal-overlay" id="modal-salary-slip">
    <div class="modal-content" style="max-width: 850px; background-color: #f1f5f9;">
        <div class="modal-header" style="background-color: var(--bg-card); border-bottom: 1px solid var(--border-color);">
            <h3 class="modal-title" style="color: var(--text-primary);"><i class="fa-solid fa-file-invoice-dollar"></i> Cetak Slip Gaji Karyawan</h3>
            <button class="modal-close" style="color: var(--text-secondary);"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding: 30px;">
            <div class="salary-slip-box" id="salary-slip-print-area">
                <div class="salary-slip-header">
                    <h2>BANK</h2>
                    <p style="font-size: 12px; color: #555; margin-top: 5px;">Gedung Sentral Perbankan Lantai 8-12, Sudirman, Jakarta</p>
                    <p style="font-weight: 600; margin-top: 10px; font-size: 15px; border-top: 1px solid #000; display: inline-block; padding-top: 5px;">SLIP GAJI RESMI KARYAWAN</p>
                </div>
                
                <div class="slip-details-grid">
                    <div>
                        <table>
                            <tr><td style="border:none; padding:3px 0; font-weight:600; width:120px;">NIP</td><td style="border:none; padding:3px 0;">: <span id="slip-nip">-</span></td></tr>
                            <tr><td style="border:none; padding:3px 0; font-weight:600;">Nama Karyawan</td><td style="border:none; padding:3px 0;">: <span id="slip-nama">-</span></td></tr>
                            <tr><td style="border:none; padding:3px 0; font-weight:600;">Divisi Kerja</td><td style="border:none; padding:3px 0;">: <span id="slip-divisi">-</span></td></tr>
                        </table>
                    </div>
                    <div>
                        <table>
                            <tr><td style="border:none; padding:3px 0; font-weight:600; width:120px;">Jabatan</td><td style="border:none; padding:3px 0;">: <span id="slip-jabatan">-</span></td></tr>
                            <tr><td style="border:none; padding:3px 0; font-weight:600;">Bulan Periode</td><td style="border:none; padding:3px 0;">: <span id="slip-bulan">-</span></td></tr>
                            <tr><td style="border:none; padding:3px 0; font-weight:600;">Metode Transfer</td><td style="border:none; padding:3px 0;">: Payroll Autodebet Bank</td></tr>
                        </table>
                    </div>
                </div>
                
                <table class="slip-table">
                    <thead>
                        <tr>
                            <th style="text-align:left;">DESKRIPSI PENERIMAAN (PENDAPATAN)</th>
                            <th style="text-align:right; width: 180px;">JUMLAH (RUPIAH)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Gaji Pokok Utama</td>
                            <td style="text-align:right;" id="slip-gajipokok">Rp -</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Jabatan Struktural</td>
                            <td style="text-align:right;" id="slip-tunjangan">Rp -</td>
                        </tr>
                        <tr>
                            <td>Bonus Kinerja Evaluasi & Lembur</td>
                            <td style="text-align:right;" id="slip-bonus">Rp -</td>
                        </tr>
                        <tr style="background-color: #fdf2f2;">
                            <td style="color:#c53030; border-bottom: 1px solid #e2e8f0; font-weight: 500;">Potongan Keterlambatan/Absensi (Denda)</td>
                            <td style="text-align:right; color:#c53030;" id="slip-potongan">Rp -</td>
                        </tr>
                        <tr class="slip-total-row">
                            <td style="font-weight: 700;">TOTAL GAJI BERSIH DITERIMA (NETTO)</td>
                            <td style="text-align:right; font-weight: 700;" id="slip-total">Rp -</td>
                        </tr>
                    </tbody>
                </table>
                
                <div class="slip-signatures">
                    <div class="signature-box">
                        <p>Penerima Karyawan,</p>
                        <div class="signature-space"></div>
                        <p style="text-decoration: underline; font-weight:600;" id="slip-sign-nama">-</p>
                    </div>
                    <div class="signature-box">
                        <p>Jakarta, <span id="slip-sign-tanggal">-</span></p>
                        <p>HR Department Manager,</p>
                        <div class="signature-space"></div>
                        <p style="text-decoration: underline; font-weight:600;">Budi Darmawan, M.M.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="background-color: var(--bg-card); border-top: 1px solid var(--border-color);">
            <button class="btn btn-secondary modal-close-btn" style="color:var(--text-primary);">Tutup</button>
            <button class="btn btn-primary" id="btn-print-slip"><i class="fa-solid fa-print"></i> Cetak / Print Slip</button>
        </div>
    </div>
</div>



