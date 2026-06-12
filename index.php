<?php
/**
 * SIMKAB - Sistem Informasi Manajemen Karyawan Bank
 * index.php - Berkas Utama Modular SPA (Single-Page Application)
 * Memuat tata letak perbankan gelap-emas & mengintegrasikan 10 fitur utama.
 */

if (!file_exists(__DIR__ . '/sessions')) { mkdir(__DIR__ . '/sessions', 0777, true); }

session_save_path(__DIR__ . '/sessions');
@session_start();

// Proteksi Sesi Aman - Jika belum login, alihkan ke landing/login page
if (!isset($_SESSION['user_id'])) {
    header('Location: landing.php');
    exit;
}

// Memuat Header Tata Letak
require_once 'includes/header.php';

// Memuat Navigasi Sidebar Tata Letak
require_once 'includes/sidebar.php';
?>

        <!-- ==========================================================================
             MAIN CONTENT WRAPPER
             ========================================================================== -->
        <main class="main-content">
            <!-- Main Header -->
            <header class="main-header">
                <div class="header-title-section">
                    <h1 id="page-title">Dashboard Analytics</h1>
                    <p id="page-subtitle">Ringkasan eksekutif dan statistik kinerja bank hari ini.</p>
                </div>
                <div class="header-actions">
                    <button class="theme-switch-btn dashboard-theme-btn" id="dashboard-theme-toggle" aria-label="Toggle Theme">
                        <i class="fa-solid fa-moon"></i>
                    </button>
                    <div class="system-badge">
                        <i class="fa-solid fa-circle"></i>
                        <span>SIMKAB Live Database</span>
                    </div>
                </div>
            </header>

            <!-- Content Body -->
            <div class="content-body">
                <style>
                    /* EMPLOYEE CARD GRID STYLES */
                    .employee-card-item {
                        background: var(--surface);
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
                        border: 1px solid var(--border-color);
                        transition: transform 0.2s, box-shadow 0.2s;
                        position: relative;
                        display: flex;
                        flex-direction: column;
                    }
                    .employee-card-item:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                    }
                    .emp-card-header {
                        height: 70px;
                        background: linear-gradient(135deg, var(--primary), var(--secondary));
                        position: relative;
                    }
                    .emp-avatar {
                        width: 70px;
                        height: 70px;
                        background: var(--background);
                        border-radius: 50%;
                        position: absolute;
                        bottom: -35px;
                        left: 20px;
                        border: 4px solid var(--surface);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 24px;
                        font-weight: 700;
                        color: var(--primary);
                        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    }
                    .emp-card-body {
                        padding: 45px 20px 20px 20px;
                        flex: 1;
                    }
                </style>
                <?php if (strtolower(trim($user_role)) === 'karyawan'): ?>
                <style>
                    /* HIDE ADMIN ACTIONS FOR KARYAWAN */
                    /* Sembunyikan kolom td Aksi di tabel Kinerja, Cuti, Pelatihan */
                    #table-kinerja td:last-child,
                    #table-cuti td:last-child {
                        display: none !important;
                    }                    /* Di Payroll, sembunyikan tombol Hapus, biarkan Cetak Slip */
                    .btn-hapus-payroll {
                        display: none !important;
                    }
                    /* Di Pengumuman, sembunyikan tombol Tambah dan Hapus */
                    #btn-tambah-pengumuman,
                    .btn-hapus-pengumuman {
                        display: none !important;
                    }
                </style>
                <?php endif; ?>
                <?php
                // Memanggil panel/halaman fitur secara modular
                require_once 'modules/dashboard.php';
                require_once 'modules/karyawan.php';
                require_once 'modules/kinerja.php';
                require_once 'modules/payroll.php';
                require_once 'modules/cuti.php';
                require_once 'modules/absensi.php';
                require_once 'modules/mutasi.php';
                require_once 'modules/pelatihan.php';
                require_once 'modules/aset.php';
                require_once 'modules/pengumuman.php';
                ?>
            </div>
        </main>
    </div>

<?php
// Memuat Footer Tata Letak & Pemanggilan Script Aplikasi
require_once 'includes/footer.php';
?>
