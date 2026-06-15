<!-- ==========================================================================
     TAB 2: CRUD DATA KARYAWAN
     ========================================================================== -->
<section id="panel-karyawan" class="panel-section">
    <?php if (strtolower(trim($user_role)) === 'karyawan'): ?>
        <!-- Tampilan Khusus Karyawan: Dasbor Profil Utama -->
        <div class="action-bar" style="padding-bottom: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="color: var(--text-primary); margin: 0;"><i class="fa-solid fa-user-circle"></i> Dasbor Profil Saya</h2>
            <button class="btn btn-primary" onclick="document.getElementById('modal-cetak-idcard').classList.add('active')" style="padding: 8px 16px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-id-badge"></i> Lihat & Cetak ID Card
            </button>
        </div>
        
        <div class="card" style="padding: 30px;">
            <div style="display: flex; gap: 30px; align-items: flex-start; flex-wrap: wrap;">
                <div style="width: 150px; height: 150px; border-radius: 50%; background-color: var(--bg-card-solid); display: flex; align-items: center; justify-content: center; font-size: 60px; color: var(--primary); border: 4px solid var(--border-color); overflow: hidden; position: relative; box-shadow: var(--shadow-soft);">
                    <img id="dashboard-foto-profil" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                    <i id="dashboard-icon-profil" class="fa-solid fa-user-tie"></i>
                </div>
                <div style="flex: 1; min-width: 300px;">
                    <h2 id="dashboard-nama" style="font-size: 28px; font-weight: 800; margin-bottom: 5px; color: var(--text-primary);">Memuat...</h2>
                    <p id="dashboard-jabatan" style="color: var(--primary-light); font-size: 16px; font-weight: 600; margin-bottom: 25px;">Memuat...</p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Nomor Induk Pegawai (NIP)</span>
                            <strong id="dashboard-nip" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Divisi Kerja</span>
                            <strong id="dashboard-divisi" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Nomor Telepon</span>
                            <strong id="dashboard-telepon" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Email Perusahaan</span>
                            <strong id="dashboard-email" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Tanggal Bergabung</span>
                            <strong id="dashboard-bergabung" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Status Karyawan</span>
                            <strong id="dashboard-status" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Gaji Pokok</span>
                            <strong id="dashboard-gaji" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Tunjangan Jabatan</span>
                            <strong id="dashboard-tunjangan" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                        <div style="background: var(--bg-main); padding: 15px; border-radius: 10px; border: 1px solid var(--border-color);">
                            <span style="display: block; font-size: 11px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Sisa Cuti Tahunan</span>
                            <strong id="dashboard-cuti" style="font-size: 15px; color: var(--text-primary);">...</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Tampilan Admin & HRD: Tabel Data Karyawan -->
        <div class="action-bar">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" id="karyawan-search" class="search-input" placeholder="Cari karyawan berdasarkan nama atau NIP...">
            </div>
            <div class="filter-group">
                <select id="karyawan-filter-divisi" class="select-filter">
                    <option value="">Semua Divisi</option>
                    <option value="Teknologi Informasi">Teknologi Informasi</option>
                    <option value="Operasional & Layanan">Operasional & Layanan</option>
                    <option value="Kredit & Pembiayaan">Kredit & Pembiayaan</option>
                    <option value="Human Resources">Human Resources</option>
                </select>
                <button class="btn btn-primary" id="btn-tambah-karyawan">
                    <i class="fa-solid fa-user-plus"></i> Tambah Karyawan
                </button>
            </div>
        </div>

        <div class="card" style="background: transparent; border: none; box-shadow: none; padding: 0;">
            <div id="grid-karyawan" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <!-- Terisi via JS -->
            </div>
        </div>
    <?php endif; ?>
</section>

<!-- ==========================================================================
     MODALS UNTUK KARYAWAN
     ========================================================================== -->

<!-- 1. Modal Tambah / Edit Karyawan -->
<div class="modal-overlay" id="modal-karyawan">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modal-karyawan-title"><i class="fa-solid fa-user-plus"></i> Tambah Karyawan Baru</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-karyawan">
                <input type="hidden" id="karyawan-id">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="karyawan-nip">NIP</label>
                        <input type="text" id="karyawan-nip" class="form-control" placeholder="Contoh: 199508122020031001" required>
                    </div>
                    <div class="form-group">
                        <label for="karyawan-nama">Nama Lengkap & Gelar</label>
                        <input type="text" id="karyawan-nama" class="form-control" placeholder="Contoh: Budi Santoso, S.E." required>
                    </div>
                    <div class="form-group">
                        <label for="karyawan-email">Email Perusahaan</label>
                        <input type="email" id="karyawan-email" class="form-control" placeholder="budi.santoso@bankraya.com" required>
                    </div>
                    <div class="form-group">
                        <label for="karyawan-telepon">Nomor Telepon/WA</label>
                        <input type="text" id="karyawan-telepon" class="form-control" placeholder="0812XXXXXXXX" required>
                    </div>
                    <div class="form-group">
                        <label for="karyawan-divisi">Divisi Kerja</label>
                        <select id="karyawan-divisi" class="form-control" required>
                            <option value="">Pilih Divisi</option>
                            <option value="Teknologi Informasi">Teknologi Informasi</option>
                            <option value="Operasional & Layanan">Operasional & Layanan</option>
                            <option value="Kredit & Pembiayaan">Kredit & Pembiayaan</option>
                            <option value="Human Resources">Human Resources</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="karyawan-jabatan">Jabatan Struktural</label>
                        <select id="karyawan-jabatan" class="form-control" required disabled>
                            <option value="">Pilih Divisi Terlebih Dahulu</option>
                        </select>
                    </div>
                    <div class="form-grid full-width" style="grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group">
                            <label for="karyawan-gaji">Gaji Pokok (Rp) - Auto</label>
                            <input type="number" id="karyawan-gaji" class="form-control" placeholder="Gaji Pokok Otomatis" readonly required>
                        </div>
                        <div class="form-group">
                            <label for="karyawan-tunjangan">Tunjangan Jabatan (Rp) - Auto</label>
                            <input type="number" id="karyawan-tunjangan" class="form-control" placeholder="Tunjangan Otomatis" readonly required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="karyawan-tglbergabung">Tanggal Mulai Bekerja</label>
                        <input type="date" id="karyawan-tglbergabung" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="karyawan-password">Password Login</label>
                        <input type="text" id="karyawan-password" class="form-control" placeholder="(Otomatis bankraya + 4 digit NIP jika kosong)">
                    </div>
                    <div class="form-group">
                        <label for="karyawan-status">Status Karyawan</label>
                        <select id="karyawan-status" class="form-control" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Non-aktif">Non-aktif</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Batal</button>
            <button class="btn btn-primary" type="submit" form="form-karyawan">Simpan Data</button>
        </div>
    </div>
</div>

<!-- 9. Modal Detail Karyawan (Profil) -->
<div class="modal-overlay" id="modal-detail-karyawan">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fa-solid fa-id-card"></i> Profil Karyawan Lengkap</h3>
            <button class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <div style="display: flex; gap: 24px; align-items: flex-start; flex-wrap: wrap;">
                <div style="width: 100px; height: 100px; border-radius: 50%; background-color: var(--primary-glow); display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: 700; color: var(--primary-light); border: 3px solid var(--primary); margin: 0 auto 20px;">
                    <span id="detail-avatar-text">XY</span>
                </div>
                <div style="flex: 1; min-width: 300px;">
                    <h2 id="detail-nama" style="font-size: 20px; font-weight: 700; margin-bottom: 4px;">-</h2>
                    <p id="detail-nip" style="color: var(--primary-light); font-size: 13px; font-weight: 600; margin-bottom: 20px;">NIP: -</p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 14px;">
                        <div>
                            <strong style="color: var(--text-secondary); display: block; font-size: 11px; text-transform: uppercase;">Divisi</strong>
                            <span id="detail-divisi">-</span>
                        </div>
                        <div>
                            <strong style="color: var(--text-secondary); display: block; font-size: 11px; text-transform: uppercase;">Jabatan</strong>
                            <span id="detail-jabatan">-</span>
                        </div>
                        <div>
                            <strong style="color: var(--text-secondary); display: block; font-size: 11px; text-transform: uppercase;">Email</strong>
                            <span id="detail-email">-</span>
                        </div>
                        <div>
                            <strong style="color: var(--text-secondary); display: block; font-size: 11px; text-transform: uppercase;">Nomor HP</strong>
                            <span id="detail-telepon">-</span>
                        </div>
                        <div>
                            <strong style="color: var(--text-secondary); display: block; font-size: 11px; text-transform: uppercase;">Gaji Pokok</strong>
                            <span id="detail-gaji">-</span>
                        </div>
                        <div>
                            <strong style="color: var(--text-secondary); display: block; font-size: 11px; text-transform: uppercase;">Tunjangan</strong>
                            <span id="detail-tunjangan">-</span>
                        </div>
                        <div>
                            <strong style="color: var(--text-secondary); display: block; font-size: 11px; text-transform: uppercase;">Tanggal Bergabung</strong>
                            <span id="detail-bergabung">-</span>
                        </div>
                        <div>
                            <strong style="color: var(--text-secondary); display: block; font-size: 11px; text-transform: uppercase;">Sisa Cuti Tahunan</strong>
                            <span id="detail-cuti">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary modal-close-btn">Tutup Profil</button>
        </div>
    </div>
</div>

<!-- Modal Cetak ID Card (Khusus Karyawan) -->
<div class="modal-overlay" id="modal-cetak-idcard">
    <div class="modal-content" style="max-width: 500px; padding: 20px; background: var(--bg-main);">
        <div class="modal-header hide-on-print">
            <h3 class="modal-title"><i class="fa-solid fa-id-badge"></i> Kartu Identitas Digital</h3>
            <button class="modal-close" onclick="document.getElementById('modal-cetak-idcard').classList.remove('active')"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="display: flex; justify-content: center; padding: 20px 0;">
            
            <div class="id-card" style="width: 100%; max-width: 420px; background: var(--bg-card-solid); border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); position: relative; border: 1px solid var(--border-color);">
                <!-- Card Header -->
                <div class="id-card-header" style="background: linear-gradient(135deg, var(--primary), var(--primary-light)); height: 130px; position: relative; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; padding-top: 25px;">
                    <div style="color: #ffffff; font-weight: 800; font-size: 22px; letter-spacing: 2px; text-transform: uppercase; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">SIMKAB BANK</div>
                    <div style="color: rgba(255,255,255,0.9); font-size: 11px; letter-spacing: 1.5px; font-weight: 500;">KARTU IDENTITAS PEGAWAI</div>
                </div>
                
                <!-- Avatar -->
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: -65px; position: relative; z-index: 2;">
                    <div style="width: 130px; height: 130px; background: var(--bg-card-solid); border-radius: 50%; padding: 6px; box-shadow: 0 10px 25px rgba(0,0,0,0.15); position: relative;">
                        <div style="width: 100%; height: 100%; background: var(--bg-main); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 55px; color: var(--primary); overflow: hidden; position: relative; border: 2px solid var(--border-color);">
                            <img id="img-foto-profil" src="" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                            <i id="icon-foto-profil" class="fa-solid fa-user-tie"></i>
                        </div>
                        <button onclick="document.getElementById('input-foto-profil').click()" class="btn-upload-foto hide-on-print" style="position: absolute; bottom: 0; right: 0; width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; border: 3px solid var(--bg-card-solid); display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); transition: transform 0.2s;" title="Upload Foto Baru">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                    <input type="file" id="input-foto-profil" accept="image/png, image/jpeg, image/jpg" style="display: none;">
                </div>
                
                <style>
                    .btn-upload-foto:hover { transform: scale(1.1); }
                    .btn-upload-foto:active { transform: scale(0.95); }
                </style>
                
                <!-- Card Body -->
                <div class="id-card-body" style="padding: 10px 30px 35px; text-align: center;">
                    <h2 id="profile-nama" style="margin: 10px 0 8px; color: var(--text-primary); font-size: 24px; font-weight: 700; letter-spacing: 0.5px; line-height: 1.2;">Memuat...</h2>
                    <div id="profile-jabatan" style="color: var(--primary); font-weight: 600; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 25px; padding: 6px 16px; background: rgba(0, 209, 178, 0.1); display: inline-block; border-radius: 20px; border: 1px solid rgba(0, 209, 178, 0.2);">Memuat...</div>
                    
                    <div style="display: grid; grid-template-columns: 1fr; gap: 15px; text-align: left; background: var(--bg-main); padding: 20px 25px; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border-color); padding-bottom: 12px;">
                            <span style="font-size: 13px; color: var(--text-muted);"><i class="fa-solid fa-hashtag" style="width: 22px; color: var(--primary);"></i> NIP</span>
                            <strong id="profile-nip" style="color: var(--text-primary); font-family: monospace; font-size: 14px;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border-color); padding-bottom: 12px;">
                            <span style="font-size: 13px; color: var(--text-muted);"><i class="fa-solid fa-sitemap" style="width: 22px; color: var(--primary);"></i> Divisi</span>
                            <strong id="profile-divisi" style="color: var(--text-primary); font-size: 13px;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border-color); padding-bottom: 12px;">
                            <span style="font-size: 13px; color: var(--text-muted);"><i class="fa-solid fa-phone" style="width: 22px; color: var(--primary);"></i> Telepon</span>
                            <strong id="profile-telepon" style="color: var(--text-primary); font-size: 13px;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed var(--border-color); padding-bottom: 12px;">
                            <span style="font-size: 13px; color: var(--text-muted);"><i class="fa-solid fa-envelope" style="width: 22px; color: var(--primary);"></i> Email</span>
                            <strong id="profile-email" style="color: var(--text-primary); font-size: 13px;">...</strong>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="font-size: 13px; color: var(--text-muted);"><i class="fa-solid fa-calendar-check" style="width: 22px; color: var(--primary);"></i> Bergabung</span>
                            <strong id="profile-tanggal" style="color: var(--text-primary); font-size: 13px;">...</strong>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; display: flex; flex-direction: column; align-items: center;">
                        <i class="fa-solid fa-barcode" style="font-size: 45px; color: var(--text-primary); opacity: 0.4; transform: scaleY(1.5);"></i>
                        <div style="font-size: 10px; color: var(--text-muted); margin-top: 15px; font-family: monospace; letter-spacing: 3px;">AUTHORIZATION CODE</div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer hide-on-print" style="justify-content: space-between;">
            <button class="btn btn-secondary" onclick="document.getElementById('modal-cetak-idcard').classList.remove('active')">Tutup</button>
            <button class="btn btn-primary" onclick="window.print()"><i class="fa-solid fa-print"></i> Cetak ID Sekarang</button>
        </div>
    </div>
</div>

<style>
    /* CSS Khusus untuk Print ID Card (Menyembunyikan Web) */
    @media print {
        body * {
            visibility: hidden;
        }
        #modal-cetak-idcard, #modal-cetak-idcard * {
            visibility: visible;
        }
        #modal-cetak-idcard {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background: none !important;
            display: flex !important;
            justify-content: center !important;
            align-items: flex-start !important;
            padding-top: 20px !important;
        }
        .modal-content {
            box-shadow: none !important;
            border: none !important;
            background: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .hide-on-print {
            display: none !important;
        }
        .id-card {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            box-shadow: none !important;
            border: 1px solid #ccc !important; /* Supaya terlihat di kertas putih jika background putih */
        }
    }
</style>

