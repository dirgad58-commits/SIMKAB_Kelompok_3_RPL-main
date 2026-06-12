# SIMKAB - Sistem Informasi Manajemen Karyawan Bank

Selamat datang di kode sumber (Source Code) **SIMKAB**! Ini adalah sistem pintar berbasis web yang dirancang untuk mendigitalkan dan menyederhanakan seluruh alur tata kelola Sumber Daya Manusia (SDM) di ekosistem perbankan.

Sistem ini dibangun dengan pendekatan arsitektur modular yang bersih (*Clean Modular Architecture*) tanpa framework yang berat, sehingga sangat mudah untuk dipelajari, dikembangkan, dan dimodifikasi.

---

## 📂 Struktur Direktori (Folder)

Untuk memudahkan Anda, kode sumber telah dikelompokkan ke dalam direktori-direktori dengan standar industri:

```text
/simkab
│
├── assets/                  # 🎨 File Antarmuka (Statis)
│   ├── css/                 # Kode styling (Cascading Style Sheets)
│   ├── js/                  # Skrip interaktif (Javascript)
│   └── img/                 # Aset visual (Logo, Foto Tim, Ikon)
│
├── config/                  # ⚙️ Pengaturan Inti & Database
│   ├── config.php           # Konfigurasi koneksi ke database MySQL
│   └── database.sql         # File Export Database (Import file ini ke phpMyAdmin)
│
├── includes/                # 🧩 Potongan Layout (Reusable Components)
│   ├── header.php           # Bagian atas situs (Meta tag, navigasi atas)
│   ├── sidebar.php          # Menu navigasi samping (untuk halaman Admin)
│   └── footer.php           # Bagian bawah situs (Copyright & script tambahan)
│
├── modules/                 # 🚀 Fitur & Modul Utama (Jantung Aplikasi)
│   ├── dashboard.php        # Halaman ringkasan analitik eksekutif
│   ├── karyawan.php         # Manajemen Data Karyawan & Profil
│   ├── absensi.php          # Catatan Kehadiran (Clock In/Out)
│   ├── cuti.php             # Sistem pengajuan & persetujuan cuti
│   ├── kinerja.php          # Modul Evaluasi Kinerja (KPI) & Grade
│   ├── payroll.php          # Sistem penggajian otomatis & cetak slip
│   ├── mutasi.php           # Catatan perpindahan cabang / promosi jabatan
│   ├── pelatihan.php        # Arsip sertifikasi & pengembangan skill
│   ├── aset.php             # Inventaris barang kantor (Laptop, Mobil, dll)
│   └── pengumuman.php       # Portal siaran informasi internal perusahaan
│
├── uploads/                 # 📁 Penyimpanan File Pengguna
│   # (Folder ini adalah tempat foto profil atau CV pelamar diunggah)
│
├── api.php                  # 🧠 Backend Controller (Pemroses Data via AJAX)
├── index.php                # 🚪 Pintu Gerbang Admin (Sistem Routing Otomatis)
├── landing.php              # 🌐 Halaman Publik / Presentasi Sistem (Hero, Fitur, Karir)
├── login.php                # 🔐 Halaman Autentikasi Masuk
└── logout.php               # 🚪 Skrip untuk keluar (Destroy Session)
```

---

## 🛠️ Penjelasan 10 Modul / Fitur Utama

Berikut adalah penjelasan rincian fitur yang berada di dalam folder `modules/`:

1. **Dashboard Analytics (`dashboard.php`)**
   Pusat kendali eksekutif yang menyajikan grafik statistik *real-time* seperti total karyawan aktif, jumlah divisi, dan kehadiran hari ini dalam satu layar.

2. **Manajemen Karyawan (`karyawan.php`)**
   Buku induk digital untuk mendaftarkan karyawan baru, memperbarui profil, melihat jabatan, hingga mengatur status aktif pegawai.

3. **Rekam Kehadiran (`absensi.php`)**
   Fitur yang mencatat jam masuk (*Clock In*) dan jam pulang (*Clock Out*) karyawan setiap hari secara presisi.

4. **Pengajuan Cuti (`cuti.php`)**
   Alur persetujuan cuti *paperless*. Karyawan dapat mengajukan cuti, dan sistem akan memotong kuota cuti tahunan secara otomatis setelah disetujui atasan.

5. **Evaluasi Kinerja / KPI (`kinerja.php`)**
   Modul pemberian skor penilaian berkala (Grade A/B/C/D) berdasarkan performa kerja. Skor ini sangat penting untuk penentuan promosi.

6. **Sistem Penggajian (`payroll.php`)**
   Otomatisasi kalkulasi gaji bersih yang telah dikurangi potongan (seperti BPJS) dan ditambah tunjangan. Fitur ini juga bisa mencetak Slip Gaji.

7. **Mutasi & Promosi (`mutasi.php`)**
   Sistem pencatatan riwayat perpindahan karyawan antar kantor cabang (misal: dari Kendari ke Baubau) atau kenaikan jabatan struktural.

8. **Pelatihan & Sertifikasi (`pelatihan.php`)**
   Manajemen pengembangan SDM. Karyawan dapat mengunggah bukti sertifikasi keahlian untuk diarsipkan dalam catatan kompetensi perusahaan.

9. **Manajemen Aset (`aset.php`)**
   Pencatatan inventaris barang perusahaan (misal: laptop dinas, mobil dinas) yang sedang dipinjamkan/dipegang oleh karyawan tertentu.

10. **Portal Pengumuman (`pengumuman.php`)**
    Papan buletin digital untuk HRD menyiarkan informasi massal, aturan baru, atau pemberitahuan penting ke seluruh karyawan secara instan.

---

## 🚀 Panduan Instalasi (Cara Menjalankan)

1. Pastikan Anda telah menginstal **XAMPP**.
2. Jalankan modul **Apache** dan **MySQL** di panel XAMPP.
3. Buka *phpMyAdmin* (`http://localhost/phpmyadmin`).
4. Buat database baru bernama **`db_simkab`**.
5. *Import* file **`database.sql`** (berada di folder `config/`) ke dalam database tersebut.
6. Simpan seluruh folder proyek ini ke dalam direktori `C:/xampp/htdocs/simkab`.
7. Buka browser dan akses `http://localhost/simkab`.

Selamat menjelajahi sistem ini! Semoga kode ini mudah dipahami dan dikembangkan lebih lanjut.

## 🔑 Panduan Login & Akun Demo

Sistem ini memiliki 3 level hak akses (Role) yang berbeda. Setelah sistem berjalan, klik tombol **"Portal Karyawan"** di pojok kanan atas halaman utama (atau langsung akses `http://localhost/simkab/login.php`).

Gunakan salah satu dari akun demo berikut untuk masuk ke dalam sistem:

### 1. Akun Administrator (Akses Penuh)
Akun ini memiliki hak akses tertinggi, termasuk mereset password, mengelola database pengguna, dan mengakses semua modul.
- **Username:** `admin`
- **Password:** `admin123`

### 2. Akun HRD (Manajemen SDM)
Akun khusus divisi HR. Memiliki hak untuk menyetujui pengajuan cuti, memproses slip gaji (Payroll), mengatur absensi, dan melakukan mutasi.
- **Username:** `budi.darmawan`
- **Password:** `hrd123`

### 3. Akun Karyawan (Staf Standar)
Akun untuk karyawan biasa. Hanya dapat melihat data pribadi sendiri, mengajukan cuti, melakukan absensi harian, dan melihat slip gaji miliknya.
- **Username:** `rizka.amanda`
- **Password:** `karyawan123`

> **Tips:** Cobalah login (masuk) menggunakan akun Karyawan terlebih dahulu untuk mengajukan cuti, kemudian keluar (logout) dan login sebagai HRD untuk melihat bagaimana cuti tersebut disetujui!

### 4. Akun Demo Reguler (Karyawan)
Akun ini adalah akun cadangan yang sebelumnya pernah Anda buat untuk mencoba-coba login sebagai karyawan biasa.
- **Username:** `akun.demo`
- **Password:** `demo123`
- **Kegunaan Utama:** Digunakan khusus jika Anda ingin melihat antarmuka (dashboard) sistem dari kacamata karyawan staf tanpa perlu khawatir mengubah data utama.
