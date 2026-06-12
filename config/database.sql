-- ==========================================================================
-- SIMKAB - Sistem Informasi Manajemen Karyawan Bank
-- database.sql - Skema Tabel Relasional & Data Awal (Seeding) untuk MySQL
-- ==========================================================================

CREATE DATABASE IF NOT EXISTS `db_simkab`;
USE `db_simkab`;

-- --------------------------------------------------------------------------
-- 1. TABEL KARYAWAN (Master Data)
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `karyawan` (
    `id` VARCHAR(10) PRIMARY KEY,
    `nip` VARCHAR(25) NOT NULL UNIQUE,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `telepon` VARCHAR(20) NOT NULL,
    `divisi` VARCHAR(50) NOT NULL,
    `jabatan` VARCHAR(100) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'Aktif',
    `gaji_pokok` DECIMAL(15,2) NOT NULL,
    `tunjangan` DECIMAL(15,2) NOT NULL,
    `tanggal_bergabung` DATE NOT NULL,
    `sisa_cuti` INT DEFAULT 12
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Karyawan
INSERT INTO `karyawan` (`id`, `nip`, `nama`, `email`, `telepon`, `divisi`, `jabatan`, `status`, `gaji_pokok`, `tunjangan`, `tanggal_bergabung`, `sisa_cuti`) VALUES
('EMP001', '198805122012031001', 'Hendra Setiawan, S.E.', 'hendra.setiawan@bankraya.com', '081234567890', 'Kredit & Pembiayaan', 'Head of Credit Analyst', 'Aktif', 12500000.00, 3000000.00, '2012-03-15', 8),
('EMP002', '199211042015082002', 'Rizka Amanda, S.Kom.', 'rizka.amanda@bankraya.com', '082345678901', 'Teknologi Informasi', 'Senior System Administrator', 'Aktif', 11000000.00, 2500000.00, '2015-08-01', 11),
('EMP003', '199507182018011003', 'Aditya Pratama', 'aditya.pratama@bankraya.com', '083456789012', 'Operasional & Layanan', 'Customer Service Officer', 'Aktif', 6500000.00, 1200000.00, '2018-01-20', 10),
('EMP004', '199602282019052004', 'Siti Rahmawati, S.E.', 'siti.rahmawati@bankraya.com', '084567890123', 'Operasional & Layanan', 'Teller Supervisor', 'Aktif', 7500000.00, 1500000.00, '2019-05-10', 5),
('EMP005', '199009152014021005', 'Budi Darmawan, M.M.', 'budi.darmawan@bankraya.com', '085678901234', 'Human Resources', 'HR Manager', 'Aktif', 15000000.00, 4000000.00, '2014-02-15', 12),
('EMP006', '199712012021092006', 'Dian Sastro, S.Psi.', 'dian.sastro@bankraya.com', '086789012345', 'Human Resources', 'Recruitment Specialist', 'Aktif', 7000000.00, 1200000.00, '2021-09-01', 12),
('EMP007', '199404252017041007', 'Fauzan Ahsan, S.E.', 'fauzan.ahsan@bankraya.com', '087890123456', 'Kredit & Pembiayaan', 'Account Officer', 'Aktif', 8000000.00, 1800000.00, '2017-04-12', 9),
('EMP008', '199803142022022008', 'Nabila Putri, S.Kom.', 'nabila.putri@bankraya.com', '088901234567', 'Teknologi Informasi', 'Frontend Developer', 'Aktif', 8500000.00, 1800000.00, '2022-02-14', 12);

-- --------------------------------------------------------------------------
-- 2. TABEL ABSENSI
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `absensi` (
    `id` VARCHAR(10) PRIMARY KEY,
    `id_karyawan` VARCHAR(10) NOT NULL,
    `tanggal` DATE NOT NULL,
    `jam_masuk` TIME DEFAULT NULL,
    `jam_keluar` TIME DEFAULT NULL,
    `status` VARCHAR(20) DEFAULT 'Hadir',
    `foto_masuk` LONGTEXT DEFAULT NULL,
    `lokasi_masuk` VARCHAR(100) DEFAULT NULL,
    `foto_keluar` LONGTEXT DEFAULT NULL,
    `lokasi_keluar` VARCHAR(100) DEFAULT NULL,
    `keterangan` TEXT DEFAULT NULL,
    FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Absensi
INSERT INTO `absensi` (`id`, `id_karyawan`, `tanggal`, `jam_masuk`, `jam_keluar`, `status`) VALUES
('ABS001', 'EMP001', '2026-05-22', '07:45:00', '17:05:00', 'Hadir'),
('ABS002', 'EMP002', '2026-05-22', '07:55:00', '17:15:00', 'Hadir'),
('ABS003', 'EMP003', '2026-05-22', '08:15:00', '17:00:00', 'Hadir'),
('ABS004', 'EMP004', '2026-05-22', '07:30:00', '17:00:00', 'Hadir'),
('ABS005', 'EMP005', '2026-05-22', '07:50:00', '17:30:00', 'Hadir'),
('ABS006', 'EMP006', '2026-05-22', NULL, NULL, 'Izin'),
('ABS007', 'EMP007', '2026-05-22', '07:40:00', '17:05:00', 'Hadir'),
('ABS008', 'EMP008', '2026-05-22', NULL, NULL, 'Sakit'),
-- Absensi kemarin
('ABS009', 'EMP001', '2026-05-21', '07:42:00', '17:02:00', 'Hadir'),
('ABS010', 'EMP002', '2026-05-21', '07:48:00', '17:10:00', 'Hadir'),
('ABS011', 'EMP003', '2026-05-21', '07:58:00', '17:00:00', 'Hadir'),
('ABS012', 'EMP004', '2026-05-21', '07:35:00', '17:05:00', 'Hadir'),
('ABS013', 'EMP005', '2026-05-21', '07:51:00', '17:40:00', 'Hadir'),
('ABS014', 'EMP006', '2026-05-21', '07:45:00', '17:00:00', 'Hadir'),
('ABS015', 'EMP007', '2026-05-21', '07:38:00', '17:00:00', 'Hadir'),
('ABS016', 'EMP008', '2026-05-21', '07:55:00', '17:12:00', 'Hadir');

-- --------------------------------------------------------------------------
-- 3. TABEL PENILAIAN KINERJA (KPI)
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `kinerja` (
    `id` VARCHAR(10) PRIMARY KEY,
    `id_karyawan` VARCHAR(10) NOT NULL,
    `periode` VARCHAR(20) NOT NULL,
    `kedisiplinan` INT NOT NULL,
    `kerjasama` INT NOT NULL,
    `inisiatif` INT NOT NULL,
    `target` INT NOT NULL,
    `skor_akhir` DECIMAL(5,2) NOT NULL,
    `predikat` VARCHAR(30) NOT NULL,
    `catatan` TEXT,
    FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Kinerja
INSERT INTO `kinerja` (`id`, `id_karyawan`, `periode`, `kedisiplinan`, `kerjasama`, `inisiatif`, `target`, `skor_akhir`, `predikat`, `catatan`) VALUES
('KPI001', 'EMP001', 'Q1 2026', 95, 90, 92, 96, 93.25, 'A (Sangat Baik)', 'Kinerja kepemimpinan luar biasa, melampaui target pencairan kredit divisi.'),
('KPI002', 'EMP002', 'Q1 2026', 90, 92, 88, 90, 90.00, 'A (Sangat Baik)', 'Sangat sigap menangani isu keamanan sistem core banking, kerja tim berjalan baik.'),
('KPI003', 'EMP003', 'Q1 2026', 80, 85, 78, 82, 81.25, 'B (Baik)', 'Pelayanan CS stabil, perlu ditingkatkan dalam aspek inisiatif penyelesaian komplain.'),
('KPI004', 'EMP004', 'Q1 2026', 88, 82, 80, 85, 83.75, 'B (Baik)', 'Pengawasan teller berjalan lancar, minim selisih kas harian.');

-- --------------------------------------------------------------------------
-- 4. TABEL CUTI
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cuti` (
    `id` VARCHAR(10) PRIMARY KEY,
    `id_karyawan` VARCHAR(10) NOT NULL,
    `jenis_cuti` VARCHAR(50) NOT NULL,
    `tanggal_mulai` DATE NOT NULL,
    `tanggal_selesai` DATE NOT NULL,
    `alasan` TEXT NOT NULL,
    `status` VARCHAR(20) DEFAULT 'Pending',
    FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Cuti
INSERT INTO `cuti` (`id`, `id_karyawan`, `jenis_cuti`, `tanggal_mulai`, `tanggal_selesai`, `alasan`, `status`) VALUES
('LV001', 'EMP003', 'Cuti Tahunan', '2026-06-01', '2026-06-03', 'Acara keluarga di luar kota', 'Disetujui'),
('LV002', 'EMP004', 'Cuti Tahunan', '2026-05-10', '2026-05-14', 'Mudik lebaran tambahan', 'Disetujui'),
('LV003', 'EMP001', 'Cuti Penting', '2026-05-25', '2026-05-28', 'Pernikahan saudara kandung', 'Pending'),
('LV004', 'EMP007', 'Cuti Tahunan', '2026-07-10', '2026-07-12', 'Liburan tahunan', 'Pending');

-- --------------------------------------------------------------------------
-- 5. TABEL PAYROLL (PENGGAJIAN)
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `payroll` (
    `id` VARCHAR(10) PRIMARY KEY,
    `id_karyawan` VARCHAR(10) NOT NULL,
    `bulan` VARCHAR(20) NOT NULL,
    `gaji_pokok` DECIMAL(15,2) NOT NULL,
    `tunjangan` DECIMAL(15,2) NOT NULL,
    `bonus` DECIMAL(15,2) NOT NULL,
    `potongan` DECIMAL(15,2) NOT NULL,
    `total_gaji` DECIMAL(15,2) NOT NULL,
    `status` VARCHAR(20) DEFAULT 'Lunas',
    FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Payroll
INSERT INTO `payroll` (`id`, `id_karyawan`, `bulan`, `gaji_pokok`, `tunjangan`, `bonus`, `potongan`, `total_gaji`, `status`) VALUES
('PAY001', 'EMP001', 'April 2026', 12500000.00, 3000000.00, 1500000.00, 0.00, 17000000.00, 'Lunas'),
('PAY002', 'EMP002', 'April 2026', 11000000.00, 2500000.00, 1200000.00, 0.00, 14700000.00, 'Lunas'),
('PAY003', 'EMP003', 'April 2026', 6500000.00, 1200000.00, 500000.00, 100000.00, 8100000.00, 'Lunas'),
('PAY004', 'EMP004', 'April 2026', 7500000.00, 1500000.00, 600000.00, 0.00, 9600000.00, 'Lunas');

-- --------------------------------------------------------------------------
-- 6. TABEL MUTASI (Karir Path)
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `mutasi` (
    `id` VARCHAR(10) PRIMARY KEY,
    `id_karyawan` VARCHAR(10) NOT NULL,
    `jenis` VARCHAR(20) NOT NULL,
    `divisi_lama` VARCHAR(50) NOT NULL,
    `divisi_baru` VARCHAR(50) NOT NULL,
    `jabatan_lama` VARCHAR(100) NOT NULL,
    `jabatan_baru` VARCHAR(100) NOT NULL,
    `tanggal` DATE NOT NULL,
    `keterangan` TEXT NOT NULL,
    FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Mutasi
INSERT INTO `mutasi` (`id`, `id_karyawan`, `jenis`, `divisi_lama`, `divisi_baru`, `jabatan_lama`, `jabatan_baru`, `tanggal`, `keterangan`) VALUES
('MUT001', 'EMP001', 'Promosi', 'Kredit & Pembiayaan', 'Kredit & Pembiayaan', 'Senior Credit Analyst', 'Head of Credit Analyst', '2024-01-01', 'Kenaikan jabatan berdasarkan pencapaian target tahunan divisi.'),
('MUT002', 'EMP004', 'Mutasi', 'Operasional - Teller', 'Operasional & Layanan', 'Senior Teller', 'Teller Supervisor', '2025-06-15', 'Rotasi internal untuk penguatan fungsi kontrol teller counter.');

-- --------------------------------------------------------------------------
-- 7. TABEL PELATIHAN & SERTIFIKASI
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pelatihan` (
    `id` VARCHAR(10) PRIMARY KEY,
    `id_karyawan` VARCHAR(10) NOT NULL,
    `nama_pelatihan` VARCHAR(150) NOT NULL,
    `tanggal_sertifikat` DATE NOT NULL,
    `status_sertifikat` VARCHAR(30) NOT NULL,
    `penyelenggara` VARCHAR(100) NOT NULL,
    `file_sertifikat` VARCHAR(255) NULL,
    `status_approval` VARCHAR(20) NOT NULL DEFAULT 'Approved',
    FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Pelatihan
INSERT INTO `pelatihan` (`id`, `id_karyawan`, `nama_pelatihan`, `tanggal_sertifikat`, `status_sertifikat`, `penyelenggara`) VALUES
('TRN001', 'EMP001', 'Sertifikasi Manajemen Risiko BSMR Level 2', '2024-05-10', 'Valid', 'Badan Sertifikasi Manajemen Risiko (BSMR)'),
('TRN002', 'EMP002', 'Certified IT Security Specialist (CISS)', '2025-09-20', 'Valid', 'EC-Council Indonesia'),
('TRN003', 'EMP003', 'Customer Excellence Service & APU-PPT Training', '2023-11-15', 'Kedaluwarsa', 'Internal Bank Academy');

-- --------------------------------------------------------------------------
-- 8. TABEL ASET (Inventaris)
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `aset` (
    `id` VARCHAR(10) PRIMARY KEY,
    `id_karyawan` VARCHAR(10) NOT NULL,
    `nama_aset` VARCHAR(150) NOT NULL,
    `kode_aset` VARCHAR(50) NOT NULL,
    `tanggal_pinjam` DATE NOT NULL,
    `tanggal_kembali` DATE DEFAULT NULL,
    `status` VARCHAR(20) DEFAULT 'Dipinjam',
    FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Aset
INSERT INTO `aset` (`id`, `id_karyawan`, `nama_aset`, `kode_aset`, `tanggal_pinjam`, `tanggal_kembali`, `status`) VALUES
('AST001', 'EMP002', 'Laptop Lenovo ThinkPad L14', 'AST-BANK-0992', '2015-08-05', NULL, 'Dipinjam'),
('AST002', 'EMP002', 'Token VPN SecurID Key', 'TOK-BANK-0443', '2020-03-01', NULL, 'Dipinjam'),
('AST003', 'EMP003', 'ID Card Badge & Akses Pintu CS', 'IDC-BANK-003', '2018-01-20', NULL, 'Dipinjam'),
('AST004', 'EMP001', 'Mobil Dinas Toyota Avanza', 'CAR-BANK-001', '2022-05-10', '2026-05-10', 'Dikembalikan');

-- --------------------------------------------------------------------------
-- 9. TABEL PENGUMUMAN
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pengumuman` (
    `id` VARCHAR(10) PRIMARY KEY,
    `judul` VARCHAR(200) NOT NULL,
    `konten` TEXT NOT NULL,
    `kategori` VARCHAR(20) NOT NULL,
    `tanggal` DATE NOT NULL,
    `pengirim` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Pengumuman
INSERT INTO `pengumuman` (`id`, `judul`, `konten`, `kategori`, `tanggal`, `pengirim`) VALUES
('ANN001', 'Sosialisasi Kebijakan Keamanan Siber Core Banking', 'Sehubungan dengan maraknya aksi phishing, diingatkan kepada seluruh karyawan terutama di bagian Operasional dan TI untuk tidak membagikan kredensial VPN atau token kepada pihak manapun. Sistem TI akan melakukan reset berkala sandi akun pada akhir pekan ini.', 'Penting', '2026-05-20', 'Divisi Kepatuhan & TI'),
('ANN002', 'Program Pelatihan BSMR Level 1 Periode Juni 2026', 'Dibuka pendaftaran program persiapan sertifikasi BSMR Level 1 untuk staf Kredit, Layanan Pelanggan, dan Operasional. Pendaftaran dibuka hingga tanggal 30 Mei 2026 melalui portal HRD SIMKAB.', 'Umum', '2026-05-18', 'Human Resources'),
('ANN003', 'Jadwal Audit Eksternal Keuangan Kuartal I', 'Diberitahukan bahwa audit eksternal dari OJK dan KAP akan dimulai tanggal 1 Juni 2026. Harap divisi Kredit dan Operasional mempersiapkan seluruh berkas transaksi pendukung yang diperlukan.', 'Penting', '2026-05-15', 'Direksi Operasional');

-- --------------------------------------------------------------------------
-- 10. TABEL LOGIN PENGGUNA (users)
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('Admin', 'HRD', 'Karyawan') NOT NULL,
    `id_karyawan` VARCHAR(10) NULL,
    FOREIGN KEY (`id_karyawan`) REFERENCES `karyawan`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seeding Data Users (Admin: admin123, HRD: hrd123, Karyawan: karyawan123)
-- Password disimpan dalam Teks Biasa (Plain text) khusus untuk kebutuhan demonstrasi tugas.
INSERT INTO `users` (`username`, `password`, `role`, `id_karyawan`) VALUES
('admin', 'admin123', 'Admin', NULL),
('budi.darmawan', 'hrd123', 'HRD', 'EMP005'),
('rizka.amanda', 'karyawan123', 'Karyawan', 'EMP002');

-- --------------------------------------------------------------------------
-- 11. TABEL PELAMAR KARIR EKSTERNAL (pelamar)
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pelamar` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `nama` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `telepon` VARCHAR(20) NOT NULL,
    `posisi` VARCHAR(100) NOT NULL,
    `cv_link` VARCHAR(255) NOT NULL,
    `pesan` TEXT,
    `tanggal_apply` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------------------------
-- 12. TABEL STANDAR JABATAN & SKALA GAJI (standar_jabatan)
-- --------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `standar_jabatan` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `divisi` VARCHAR(50) NOT NULL,
    `nama_jabatan` VARCHAR(100) NOT NULL UNIQUE,
    `gaji_pokok` DECIMAL(15,2) NOT NULL,
    `tunjangan` DECIMAL(15,2) NOT NULL,
    `grade` VARCHAR(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `standar_jabatan` (`divisi`, `nama_jabatan`, `gaji_pokok`, `tunjangan`, `grade`) VALUES
('Teknologi Informasi', 'Head of IT Division', 18000000.00, 5000000.00, 'Grade 1'),
('Teknologi Informasi', 'Senior System Administrator', 11000000.00, 2500000.00, 'Grade 2'),
('Teknologi Informasi', 'Frontend Developer', 8500000.00, 1800000.00, 'Grade 3'),
('Teknologi Informasi', 'IT Support Officer', 5500000.00, 1000000.00, 'Grade 4'),
('Kredit & Pembiayaan', 'Head of Credit Division', 16500000.00, 4500000.00, 'Grade 1'),
('Kredit & Pembiayaan', 'Head of Credit Analyst', 12500000.00, 3000000.00, 'Grade 1'),
('Kredit & Pembiayaan', 'Senior Credit Analyst', 9500000.00, 2000000.00, 'Grade 2'),
('Kredit & Pembiayaan', 'Account Officer', 8000000.00, 1800000.00, 'Grade 3'),
('Kredit & Pembiayaan', 'Credit Operations Clerk', 5000000.00, 1000000.00, 'Grade 4'),
('Operasional & Layanan', 'Branch Operations Manager', 14000000.00, 3500000.00, 'Grade 1'),
('Operasional & Layanan', 'Teller Supervisor', 7500000.00, 1500000.00, 'Grade 2'),
('Operasional & Layanan', 'Customer Service Officer', 6500000.00, 1200000.00, 'Grade 3'),
('Operasional & Layanan', 'Teller Representative', 4800000.00, 800000.00, 'Grade 4'),
('Human Resources', 'HR Manager', 15000000.00, 4000000.00, 'Grade 1'),
('Human Resources', 'Recruitment Specialist', 7000000.00, 1200000.00, 'Grade 2'),
('Human Resources', 'HR Operations Assistant', 5800000.00, 1000000.00, 'Grade 3'),
('Human Resources', 'Office Clerk', 4500000.00, 800000.00, 'Grade 4');

