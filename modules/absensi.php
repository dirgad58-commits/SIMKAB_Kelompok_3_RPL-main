<!-- ==========================================================================
     TAB 6: ABSENSI & KEHADIRAN HARI INI
     ========================================================================== -->
<section id="panel-absensi" class="panel-section">
    <div class="attendance-control-panel">
        <!-- Left Check In Widget -->
        <div class="clock-widget">
            <h2 class="card-title" style="color: var(--primary-light);"><i class="fa-solid fa-clock-rotate-left"></i> E-Absensi</h2>
            <div class="live-clock" id="live-clock">00:00:00</div>
            <div class="live-date" id="live-date">Jumat, 22 Mei 2026</div>
            
            <?php if (strtolower(trim($user_role)) !== 'karyawan'): ?>
            <div class="admin-notice" style="margin-top: 20px; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 8px; text-align: center; border: 1px dashed var(--border-color);">
                <?php if (strtolower(trim($user_role)) === 'admin'): ?>
                    <i class="fa-solid fa-user-shield" style="font-size: 32px; color: var(--warning); margin-bottom: 10px;"></i>
                    <h3 style="font-size: 14px; color: var(--text-primary); margin-bottom: 5px;">Mode Super Admin</h3>
                    <p style="font-size: 12px; color: var(--text-secondary); line-height: 1.4;">Anda memiliki hak akses tertinggi. Gunakan fungsi ini untuk melakukan intervensi atau koreksi data absensi seluruh karyawan jika diperlukan.</p>
                <?php else: ?>
                    <i class="fa-solid fa-user-tie" style="font-size: 32px; color: #1abc9c; margin-bottom: 10px;"></i>
                    <h3 style="font-size: 14px; color: var(--text-primary); margin-bottom: 5px;">Mode HRD (Human Resources)</h3>
                    <p style="font-size: 12px; color: var(--text-secondary); line-height: 1.4;">Sebagai staf HRD, Anda memiliki wewenang untuk mencatat kehadiran manual bagi karyawan yang mengalami kendala teknis.</p>
                <?php endif; ?>
                <button class="btn btn-secondary" id="btn-absen-manual" style="margin-top: 15px; width: 100%; border-color: var(--primary); color: var(--primary-light);"><i class="fa-solid fa-user-clock"></i> Input Absen Manual</button>
            </div>
            <?php endif; ?>
            
            <div class="form-group" style="display: none;">
                <select id="absensi-karyawan-select" class="form-control"></select>
            </div>
            
            <div class="check-actions-wrapper" style="margin-top: 20px;">
                <button class="btn btn-primary" id="btn-check-in"><i class="fa-solid fa-sign-in-alt"></i> Check In</button>
                <button class="btn btn-secondary" id="btn-check-out"><i class="fa-solid fa-sign-out-alt"></i> Check Out</button>
                <button class="btn btn-primary" style="background-color: #f39c12; border-color: #e67e22; margin-top: 10px; width: 100%;" id="btn-ajukan-izin"><i class="fa-solid fa-notes-medical"></i> Ajukan Sakit / Izin</button>
            </div>
            
            <div class="biometric-widget" style="margin-top: 20px; text-align: center; background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px;">
                <p style="font-size: 12px; margin-bottom: 8px;"><i class="fa-solid fa-location-dot"></i> Verifikasi Lokasi Geofencing</p>
                <p id="gps-status" style="font-size: 11px; margin-top: 10px; color: #ffeb3b;"><i class="fa-solid fa-spinner fa-spin"></i> Mendeteksi Lokasi Anda...</p>
                <p id="gps-distance" style="font-size: 11px; margin-top: 5px; color: var(--text-secondary);">Jarak dari kantor: Menghitung...</p>
                <button id="btn-get-location" class="btn btn-secondary" style="font-size: 12px; padding: 4px 10px; margin-top: 5px;"><i class="fa-solid fa-satellite-dish"></i> Pindai Lokasi</button>
            </div>
        </div>

        <!-- Right Attendance Table Log -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-list-check"></i> Rekap Absensi Terkini</h2>
            </div>
            <div class="table-wrapper" style="max-height: 350px; overflow-y: auto;">
                <table class="data-table" id="table-absensi">
                    <thead>
                        <tr>
                            <th>Nama Karyawan</th>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Terisi via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Ajukan Izin/Sakit -->
<div class="modal-overlay" id="modal-izin">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-notes-medical"></i> Form Pengajuan Sakit & Izin</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-izin">
                <div class="form-group">
                    <label>Jenis Pengajuan</label>
                    <select id="izin-jenis" class="form-control" required>
                        <option value="Sakit">Sakit (Memerlukan Surat Dokter)</option>
                        <option value="Izin">Izin (Keperluan Mendesak)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Keterangan / Alasan</label>
                    <textarea id="izin-keterangan" class="form-control" style="min-height: 80px; padding: 10px;" placeholder="Tuliskan alasan lengkap Anda..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Unggah Surat Bukti (Opsional namun disarankan)</label>
                    <input type="file" id="izin-file" class="form-control" accept="image/*" style="padding: 10px;">
                    <p style="font-size: 11px; color: var(--text-secondary); margin-top: 5px;">*Gunakan format gambar JPG/PNG</p>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn" type="button">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-izin" style="background-color: #f39c12; border-color: #e67e22;">Kirim Pengajuan</button>
        </div>
    </div>
</div>

<!-- Modal Input Absen Manual -->
<div class="modal-overlay" id="modal-absen-manual">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-user-clock"></i> Input Absen Manual</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-absen-manual">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="manual-karyawan">Pilih Karyawan</label>
                    <select id="manual-karyawan" class="form-control" required>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="manual-tanggal">Tanggal Absensi</label>
                    <input type="date" id="manual-tanggal" class="form-control" required>
                </div>
                <div class="form-grid" style="margin-bottom: 15px;">
                    <div class="form-group">
                        <label for="manual-jam-masuk">Jam Masuk</label>
                        <input type="time" id="manual-jam-masuk" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="manual-jam-keluar">Jam Keluar</label>
                        <input type="time" id="manual-jam-keluar" class="form-control">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="manual-status">Status Kehadiran</label>
                    <select id="manual-status" class="form-control" required>
                        <option value="Hadir">Hadir</option>
                        <option value="Terlambat">Terlambat</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Tidak Hadir">Tidak Hadir</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn" type="button">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-absen-manual"><i class="fa-solid fa-save"></i> Simpan Absen</button>
        </div>
    </div>
</div>
