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
            <div class="form-group" style="width: 100%; text-align: left;">
                <label for="absensi-karyawan-select">Pilih Karyawan</label>
                <select id="absensi-karyawan-select" class="form-control">
                    <!-- Karyawan loaded dynamically -->
                </select>
            </div>
            <?php else: ?>
            <div class="form-group" style="display: none;">
                <select id="absensi-karyawan-select" class="form-control"></select>
            </div>
            <?php endif; ?>
            
            <div class="check-actions-wrapper">
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

