# Sequence Diagrams SIMKAB

Berikut adalah 11 Sequence Diagram (termasuk Login) untuk modul-modul yang ada di dalam aplikasi SIMKAB Kelompok 3 RPL. Karena Anda sudah menginstal ekstensi Mermaid, diagram di bawah ini dapat langsung di-render atau dilihat visualisasinya.

## 1. Sequence Diagram - Login
Fitur untuk memvalidasi akses pengguna ke dalam sistem.

```mermaid
sequenceDiagram
    actor User
    participant UI as Halaman Login
    participant Server as login.php
    participant DB as Database

    User->>UI: Memasukkan Username & Password
    UI->>Server: POST Data Kredensial
    Server->>DB: Query Cek Username & Password
    DB-->>Server: Mengembalikan Hasil (Valid/Tidak)
    
    alt Kredensial Valid
        Server->>Server: Buat Sesi (Session)
        Server-->>UI: Redirect ke Dashboard
        UI-->>User: Tampilkan Halaman Dashboard
    else Kredensial Tidak Valid
        Server-->>UI: Pesan Error (Username/Password Salah)
        UI-->>User: Tampilkan Pesan Error
    end
```

---

## 2. Sequence Diagram - Absensi
Fitur untuk melakukan absensi kehadiran.

```mermaid
sequenceDiagram
    actor Karyawan
    participant UI as Menu Absensi
    participant Server as modules/absensi.php
    participant DB as Database

    Karyawan->>UI: Klik Tombol "Clock In" / "Clock Out"
    UI->>Server: Kirim Waktu & Lokasi
    Server->>DB: Insert/Update Data Absensi Karyawan
    DB-->>Server: Status Berhasil
    Server-->>UI: Notifikasi Absensi Sukses
    UI-->>Karyawan: Update Status Kehadiran di Layar
```

---

## 3. Sequence Diagram - Cuti
Fitur pengajuan cuti oleh karyawan ke atasan/HRD.

```mermaid
sequenceDiagram
    actor Karyawan
    participant UI as Form Pengajuan Cuti
    participant Server as modules/cuti.php
    participant DB as Database

    Karyawan->>UI: Isi Form & Tanggal Cuti
    UI->>Server: Submit Data Cuti
    Server->>Server: Validasi Sisa Cuti
    Server->>DB: Simpan Request Cuti (Status: Pending)
    DB-->>Server: Simpan Berhasil
    Server-->>UI: Pesan Menunggu Persetujuan
    UI-->>Karyawan: Tampilkan di Tabel Riwayat Cuti
```

---

## 4. Sequence Diagram - Karyawan (Manajemen Pegawai)
Fitur admin/HR untuk menambahkan data karyawan baru.

```mermaid
sequenceDiagram
    actor Admin
    participant UI as Form Tambah Karyawan
    participant Server as modules/karyawan.php
    participant DB as Database

    Admin->>UI: Input Data Pribadi & Jabatan
    UI->>Server: Simpan Data Baru
    Server->>DB: Insert into tb_karyawan
    DB-->>Server: Sukses Insert
    Server-->>UI: Data Karyawan Tersimpan
    UI-->>Admin: Refresh Tabel Karyawan
```

---

## 5. Sequence Diagram - Payroll (Penggajian)
Fitur perhitungan gaji dan pencetakan slip gaji.

```mermaid
sequenceDiagram
    actor HRD
    participant UI as Menu Payroll
    participant Server as modules/payroll.php
    participant DB as Database

    HRD->>UI: Generate Gaji Bulan Ini
    UI->>Server: Proses Payroll
    Server->>DB: Ambil Data Karyawan & Absensi
    DB-->>Server: Kembalikan Data
    Server->>Server: Kalkulasi Gaji, Tunjangan, Potongan
    Server->>DB: Simpan Record Penggajian
    DB-->>Server: Sukses
    Server-->>UI: Payroll Berhasil Dibuat
    UI-->>HRD: Tampilkan Ringkasan Gaji & Slip
```

---

## 6. Sequence Diagram - Kinerja
Fitur untuk menginput nilai atau Key Performance Indicator (KPI).

```mermaid
sequenceDiagram
    actor Manajer
    participant UI as Form Penilaian Kinerja
    participant Server as modules/kinerja.php
    participant DB as Database

    Manajer->>UI: Masukkan Nilai KPI Karyawan
    UI->>Server: Submit Nilai Kinerja
    Server->>DB: Update tb_kinerja
    DB-->>Server: Hasil Tersimpan
    Server-->>UI: Nilai Berhasil Diperbarui
    UI-->>Manajer: Tampilkan Grafik/Nilai Terbaru
```

---

## 7. Sequence Diagram - Mutasi
Fitur pemindahan divisi/jabatan karyawan.

```mermaid
sequenceDiagram
    actor Admin
    participant UI as Menu Mutasi
    participant Server as modules/mutasi.php
    participant DB as Database

    Admin->>UI: Pilih Karyawan & Divisi Tujuan
    UI->>Server: Proses Permintaan Mutasi
    Server->>DB: Catat Riwayat Mutasi
    Server->>DB: Update Data Divisi Karyawan Saat Ini
    DB-->>Server: Update Selesai
    Server-->>UI: Status Mutasi Berhasil
    UI-->>Admin: Refresh Detail Jabatan Karyawan
```

---

## 8. Sequence Diagram - Pelatihan
Fitur menjadwalkan dan mendata pelatihan karyawan.

```mermaid
sequenceDiagram
    actor HRD
    participant UI as Form Pelatihan
    participant Server as modules/pelatihan.php
    participant DB as Database

    HRD->>UI: Buat Jadwal Pelatihan Baru
    UI->>Server: Simpan Jadwal & Daftar Peserta
    Server->>DB: Insert Data Event Pelatihan
    DB-->>Server: Sukses Simpan
    Server-->>UI: Notifikasi Jadwal Terbuat
    UI-->>HRD: Tampilkan di Kalender Pelatihan
```

---

## 9. Sequence Diagram - Pengumuman
Fitur mempublikasikan informasi ke seluruh pengguna.

```mermaid
sequenceDiagram
    actor Admin
    participant UI as Form Pengumuman
    participant Server as modules/pengumuman.php
    participant DB as Database

    Admin->>UI: Buat Judul & Isi Pengumuman
    UI->>Server: Publish Informasi
    Server->>DB: Insert tb_pengumuman
    DB-->>Server: Berhasil
    Server-->>UI: Pengumuman Terbit
    UI-->>Admin: Kembali ke Halaman Utama
```

---

## 10. Sequence Diagram - Aset
Fitur pengelolaan dan peminjaman fasilitas/aset kantor.

```mermaid
sequenceDiagram
    actor Admin
    participant UI as Menu Aset
    participant Server as modules/aset.php
    participant DB as Database

    Admin->>UI: Assign Laptop/Kendaraan ke Karyawan
    UI->>Server: Simpan Peminjaman Aset
    Server->>DB: Update Penanggung Jawab Aset
    DB-->>Server: Update Sukses
    Server-->>UI: Status Aset Terpinjam
    UI-->>Admin: Perbarui Daftar Inventaris
```

---

## 11. Sequence Diagram - Dashboard
Fitur tampilan utama yang merangkum keseluruhan informasi.

```mermaid
sequenceDiagram
    actor User
    participant UI as Halaman Dashboard
    participant Server as modules/dashboard.php
    participant DB as Database

    User->>UI: Buka Menu Dashboard
    UI->>Server: GET Data Statistik
    Server->>DB: Query Jumlah Karyawan, Absensi Hari Ini, Pengumuman
    DB-->>Server: Kumpulan Data (Aggregate)
    Server-->>UI: Render Grafik & Kartu Info
    UI-->>User: Tampilkan Widget Dashboard Lengkap
```
