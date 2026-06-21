<?php
/**
 * SIMKAB - Sistem Informasi Manajemen Karyawan Bank
 * landing.php - Halaman Muka Publik (Expanded & Detailed)
 */
if (!file_exists(__DIR__ . '/sessions')) { mkdir(__DIR__ . '/sessions', 0777, true); }

session_save_path(__DIR__ . '/sessions');
@session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=db_simkab;charset=utf8", "root", "");
    $total_karyawan = $pdo->query("SELECT COUNT(*) FROM karyawan")->fetchColumn() ?: 0;
    $total_divisi = $pdo->query("SELECT COUNT(DISTINCT divisi) FROM karyawan WHERE divisi != ''")->fetchColumn() ?: 0;
} catch (Exception $e) {
    $total_karyawan = 0;
    $total_divisi = 0;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BANK - SIMKAB</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/assets/css/style.css'); ?>">
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('simkab-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    <style>
        /* Landing Page Specific Styles */
        .landing-nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(var(--bg-main-rgb), 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
        }

        .brand-logo {
            display: flex; align-items: center; gap: 12px;
            font-weight: 800; font-size: 20px; color: var(--text-primary); text-decoration: none;
        }

        .brand-icon {
            width: 36px; height: 36px; background: var(--primary); color: #fff;
            border-radius: 10px; display: flex; align-items: center; justify-content: center;
        }

        .nav-links { display: flex; gap: 32px; align-items: center; }
        .nav-links a { color: var(--text-secondary); text-decoration: none; font-weight: 600; font-size: 15px; transition: 0.2s; }
        .nav-links a:hover { color: var(--primary); }

        /* Hero Section */
        .hero-section {
            padding: 180px 40px 120px;
            text-align: center; width: 100%;
        }

        .hero-badge {
            display: inline-block; background: var(--primary-glow); color: var(--primary);
            padding: 8px 16px; border-radius: 50px; font-weight: 700; font-size: 13px; margin-bottom: 24px; border: 1px solid var(--border-color);
        }

        .hero-title {
            font-size: 64px; font-weight: 800; line-height: 1.1; color: var(--text-primary);
            margin-bottom: 24px; letter-spacing: -2px;
        }
                .hero-title span { 
            background: linear-gradient(90deg, var(--primary), #0ea5e9, var(--primary));
            background-size: 200% auto;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: gradientText 3s linear infinite;
        }
        @keyframes gradientText {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .hero-subtitle {
            font-size: 20px; color: var(--text-muted); line-height: 1.6;
            margin-bottom: 48px; max-width: 750px; margin-left: auto; margin-right: auto;
        }

        .cta-buttons { display: flex; gap: 16px; justify-content: center; }

        .btn-primary-large {
            background: var(--primary); color: #ffffff; padding: 18px 36px; border-radius: 12px;
            font-weight: 700; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;
        }
        .btn-primary-large:hover { background: var(--primary-light); transform: translateY(-2px); box-shadow: var(--shadow-glow); }

        .btn-secondary-large {
            background: var(--bg-card); color: var(--text-primary); padding: 18px 36px; border-radius: 12px;
            font-weight: 700; font-size: 16px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; border: 1px solid var(--border-color); transition: 0.2s;
        }
        .btn-secondary-large:hover { border-color: var(--primary); color: var(--primary); }        /* Global Landing Page Section Header Override */
        .section-header { text-align: center !important; display: block !important; margin-bottom: 60px !important; }
        .section-header h2 { font-size: 32px; font-weight: 800; color: var(--text-primary); margin-bottom: 16px; text-align: center !important; }
        .section-header p { font-size: 16px; color: var(--text-muted); max-width: 700px; margin: 0 auto; line-height: 1.6; text-align: center !important; }

        /* Features Section */
        .features-section { padding: 100px 40px; background: var(--bg-main); border-top: 1px solid var(--border-color); }
        .section-header { text-align: center; margin-bottom: 60px; }
        .section-header h2 { font-size: 40px; font-weight: 800; color: var(--text-primary); margin-bottom: 16px; letter-spacing: -1px; }
        .section-header p { font-size: 18px; color: var(--text-muted); max-width: 600px; margin: 0 auto; }

        .features-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px; max-width: 1200px; margin: 0 auto;
        }        .feature-card {
            background: var(--bg-card-solid); padding: 40px; border-radius: var(--border-radius-lg);
            border: 1px solid var(--border-color); box-shadow: var(--shadow-soft); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative;
        }
        .feature-card:hover { transform: translateY(-10px) scale(1.02); border-color: var(--primary); box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.25); z-index: 10; }
        .feature-icon {
            width: 56px; height: 56px; background: var(--primary-glow); color: var(--primary);
            border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 24px;
        }
        .feature-card h3 { font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; }
        .feature-card p { font-size: 15px; color: var(--text-muted); line-height: 1.6; }

        /* Career Section */
        .career-section { padding: 100px 40px; background: var(--bg-card-solid); border-top: 1px solid var(--border-color); }
        .job-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; max-width: 1200px; margin: 0 auto; }        .job-card {
            background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); cursor: pointer; position: relative;
        }
        .job-card:hover { border-color: var(--primary); transform: translateY(-10px) scale(1.02); box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.25); z-index: 10; }
        .job-title { font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; }
        .job-meta { display: flex; gap: 16px; font-size: 14px; color: var(--text-muted); margin-bottom: 24px; }
        .job-meta span { display: flex; align-items: center; gap: 6px; }

        /* FAQ Section (New) */
        .faq-section { padding: 100px 40px; background: var(--bg-card-solid); border-top: 1px solid var(--border-color); }
        .faq-container { max-width: 800px; margin: 0 auto; }
        .faq-item { border: 1px solid var(--border-color); border-radius: 12px; margin-bottom: 16px; overflow: hidden; background: var(--bg-main); transition: 0.3s; }
        .faq-question { padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; cursor: pointer; font-weight: 600; color: var(--text-primary); font-size: 16px; }
        .faq-question:hover { background: var(--bg-card-hover); }
        .faq-answer { padding: 0 24px; max-height: 0; overflow: hidden; transition: max-height 0.3s ease; color: var(--text-muted); font-size: 15px; line-height: 1.6; }
        .faq-item.active .faq-answer { padding: 0 24px 20px; max-height: 300px; }
        .faq-item.active .faq-icon { transform: rotate(180deg); color: var(--primary); }
        .faq-icon { transition: transform 0.3s; color: var(--text-muted); }        /* Developer Section */
        .dev-section { padding: 100px 40px; background: var(--bg-main); border-top: 1px solid var(--border-color); }
        .dev-grid { display: flex; flex-wrap: wrap; justify-content: center; gap: 24px; max-width: 1200px; margin: 0 auto; }
        .dev-card {
            width: 260px; /* Fixed width prevents weird percentage wrapping on laptop screens */
            background: var(--bg-card-solid); border: 1px solid var(--border-color); border-radius: 16px; padding: 40px 20px; text-align: center; 
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative;
            box-shadow: 0 10px 30px -10px rgba(0,0,0,0.08); cursor: default;
        }
        .dev-card:hover { transform: translateY(-10px) scale(1.02); border-color: var(--primary); box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.25); z-index: 10; }
        .dev-avatar { width: 90px; height: 90px; border-radius: 50%; background: var(--primary-glow); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 36px; margin: 0 auto 24px; font-weight: 800; border: 2px solid var(--primary); text-transform: uppercase; overflow: hidden; position: relative; box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.3); transition: transform 0.4s; }
        .dev-card:hover .dev-avatar { transform: scale(1.1) rotate(5deg); }
        .dev-avatar img { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; z-index: 2; transition: transform 0.4s; }
        .dev-card:hover .dev-avatar img { transform: scale(1.1); }
        .dev-initial { position: absolute; z-index: 1; }
        .dev-card.leader { border-color: var(--primary); background: linear-gradient(145deg, var(--bg-card-solid), rgba(99, 102, 241, 0.05)); }
        .dev-card h3 { font-size: 18px; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; line-height: 1.4; letter-spacing: -0.5px; }
        .dev-card p { font-size: 14px; color: var(--text-muted); font-family: monospace; font-weight: 600; background: rgba(100,100,100,0.1); padding: 6px 10px; border-radius: 6px; display: inline-block; }
        .dev-badge { display: inline-block; background: var(--primary); color: #fff; padding: 6px 16px; border-radius: 50px; font-size: 11px; font-weight: 800; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(99, 102, 241, 0.4); letter-spacing: 1.5px; text-transform: uppercase; }

        /* Footer */
        .site-footer { padding: 80px 60px 40px; background: var(--bg-main); border-top: 1px solid var(--border-color); }
        .footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px; max-width: 1200px; margin: 0 auto 60px; }
        .footer-brand { margin-bottom: 20px; }
        .footer-desc { color: var(--text-muted); font-size: 15px; line-height: 1.6; max-width: 400px; }
        .footer-links h4 { font-size: 16px; font-weight: 700; color: var(--text-primary); margin-bottom: 24px; }
        .footer-links ul { list-style: none; padding: 0; }
        .footer-links li { margin-bottom: 12px; }
        .footer-links a { color: var(--text-secondary); text-decoration: none; font-size: 15px; transition: 0.2s; }
        .footer-links a:hover { color: var(--primary); }
        .footer-bottom { text-align: center; padding-top: 40px; border-top: 1px solid var(--border-color); color: var(--text-muted); font-size: 14px; }

        @media (max-width: 768px) {
            .landing-nav { padding: 20px; }
            .hero-title { font-size: 40px; }
            .stats-section { grid-template-columns: 1fr; gap: 20px; }
            .footer-grid { grid-template-columns: 1fr; gap: 40px; }
        }
        
        /* Modal Style - Hidden by Default */
        .apply-modal { display: none; background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; max-width: 600px; margin: 40px auto 0; box-shadow: var(--shadow-soft); }
        .input-field { width: 100%; padding: 14px 16px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-card-solid); color: var(--text-primary); margin-bottom: 16px; font-size: 14px; transition: 0.2s; font-family: inherit; }
        .input-field:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }
            /* Hero Background Elements */
        .hero-section { position: relative; overflow: hidden; z-index: 1; }
        .hero-bg-shape { position: absolute; border-radius: 50%; filter: blur(100px); z-index: -2; }
        .shape-1 { top: -20%; left: -10%; width: 60vw; height: 60vw; max-width: 800px; max-height: 800px; background: var(--primary); opacity: 0.25; animation: floatBg 12s ease-in-out infinite alternate; }
        .shape-2 { bottom: -20%; right: -10%; width: 70vw; height: 70vw; max-width: 1000px; max-height: 1000px; background: #0284c7; opacity: 0.15; animation: floatBgRev 15s ease-in-out infinite alternate; }
        .hero-bg-grid {
            position: absolute; inset: 0; z-index: -1; opacity: 0.5;
            background-image: 
                radial-gradient(var(--primary) 1px, transparent 1px),
                radial-gradient(var(--primary) 1px, transparent 1px);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
            mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 100%);
            -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 30%, rgba(0,0,0,0) 100%);
        }
        @keyframes floatBg { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(60px, 40px) scale(1.1); } }
        @keyframes floatBgRev { 0% { transform: translate(0, 0) scale(1); } 100% { transform: translate(-40px, -60px) scale(1.05); } }

        /* General Body Setup */
        /* 5-Column Features Override */
        .features-grid { grid-template-columns: repeat(5, 1fr) !important; max-width: 1400px !important; gap: 20px !important; }
        .feature-card { padding: 24px !important; }
        .feature-icon { width: 48px !important; height: 48px !important; font-size: 20px !important; margin-bottom: 16px !important; }
        .feature-card h3 { font-size: 18px !important; margin-bottom: 8px !important; }
        .feature-card p { font-size: 13px !important; }
        @media (max-width: 1200px) { .features-grid { grid-template-columns: repeat(3, 1fr) !important; } }
        @media (max-width: 768px) { .features-grid { grid-template-columns: repeat(2, 1fr) !important; } }
        @media (max-width: 480px) { .features-grid { grid-template-columns: 1fr !important; } }
</style>
</head>
<body>

    <nav class="landing-nav">
        <a href="landing.php" class="brand-logo">
            <div class="brand-icon"><i class="fa-solid fa-layer-group"></i></div>
            <div class="brand-text">SIMKAB</div>
        </a>
        <div class="nav-links">
            <a href="#fitur">Fitur Utama</a>
            <a href="#tim-pengembang">Tim Pengembang</a>
            <a href="#faq">Pusat Bantuan</a>
            <a href="#karir">Karir</a>
        </div>
        <div class="nav-links">
            <button class="theme-switch-btn" id="theme-btn" style="background: transparent; border: none; color: var(--text-secondary); cursor: pointer; font-size: 18px; padding: 4px;">
                <i class="fa-solid fa-moon"></i>
            </button>
            <a href="login.php" style="background: var(--bg-card-solid); border: 1px solid var(--border-color); padding: 8px 16px; border-radius: 8px; font-size: 13px;">Login</a>
        </div>
    </nav>

    <header class="hero-section">
        <!-- Abstract Background Shapes -->
        <div class="hero-bg-shape shape-1"></div>
        <div class="hero-bg-shape shape-2"></div>
        <div class="hero-bg-grid"></div>

        <div class="hero-badge"><i class="fa-solid fa-shield-check"></i> Platform Manajemen SDM Enterprise</div>
        <h1 class="hero-title">Infrastruktur Digital <span>Karyawan Bank</span></h1>
        <p class="hero-subtitle">Solusi lengkap yang menyederhanakan absensi, payroll, dan evaluasi kinerja dalam satu ekosistem terpadu berstandar keamanan perbankan tinggi.</p>
        
        <div class="cta-buttons">
            <a href="login.php" class="btn-primary-large">
                Akses Portal <i class="fa-solid fa-arrow-right"></i>
            </a>
            <a href="#fitur" class="btn-secondary-large">
                Pelajari Fitur
            </a>
        </div>
    </header>

    <!-- Fitur Section -->
    <section class="features-section" id="fitur" style="padding: 100px 40px; background: var(--bg-main);">
        <div class="section-header">
            <h2>10 Modul & Fitur Utama</h2>
            <p>Sistem Informasi Manajemen Karyawan Bank (SIMKAB) ditenagai oleh 10 modul mutakhir yang terintegrasi secara seamless untuk digitalisasi total tata kelola SDM perbankan Anda.</p>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-chart-pie"></i></div>
                <h3>Dashboard Analytics</h3>
                <p>Ringkasan eksekutif dan statistik kinerja SDM real-time dalam satu layar cerdas.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-users"></i></div>
                <h3>Manajemen Karyawan</h3>
                <p>Kelola profil lengkap, divisi, dan status aktif seluruh karyawan secara terpusat.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-star"></i></div>
                <h3>Evaluasi Kinerja</h3>
                <p>Pemantauan KPI, penilaian atasan, dan penentuan grade karyawan berbasis data.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-money-check-dollar"></i></div>
                <h3>Sistem Penggajian</h3>
                <p>Otomatisasi kalkulasi gaji, tunjangan, potongan absensi, hingga cetak slip gaji.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <h3>Pengajuan Cuti</h3>
                <p>Alur persetujuan cuti digital tanpa kertas dengan perhitungan kuota sisa otomatis.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-clock"></i></div>
                <h3>Rekam Kehadiran</h3>
                <p>Sistem absensi (Clock In/Out) yang terekam akurat dan terhubung langsung ke Payroll.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-arrow-right-arrow-left"></i></div>
                <h3>Mutasi & Promosi</h3>
                <p>Pencatatan riwayat perpindahan cabang, divisi, maupun kenaikan jabatan struktural.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-certificate"></i></div>
                <h3>Pelatihan & Sertifikasi</h3>
                <p>Pengembangan kompetensi dengan arsip bukti sertifikasi untuk meningkatkan standar bank.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-laptop"></i></div>
                <h3>Manajemen Aset</h3>
                <p>Inventarisasi aset perusahaan (Laptop, Kendaraan) yang dipegang oleh tiap karyawan.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fa-solid fa-bullhorn"></i></div>
                <h3>Portal Pengumuman</h3>
                <p>Penyiaran informasi internal, kebijakan baru, dan notifikasi penting secara massal.</p>
            </div>
        </div>
    </section>


        <!-- Formulir Lamaran -->
            <!-- Karir / Rekrutmen Section -->
    <section class="career-section" id="karir" style="padding: 100px 40px; background: var(--bg-card-solid); border-top: 1px solid var(--border-color);">
        <div class="section-header">
            <h2>Peluang Rekrutmen & Talenta</h2>
            <p>Kami mencari individu terbaik untuk membangun inovasi digital dan ekosistem perbankan di Sulawesi Tenggara.</p>
        </div>
        <div class="career-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; max-width: 900px; margin: 0 auto; margin-bottom: 40px;">
            
            <div class="career-card" style="background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                    <div>
                        <h3 style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">IT System Administrator</h3>
                        <div style="display: flex; gap: 12px; font-size: 13px; color: var(--text-muted); flex-wrap: wrap;">
                            <span><i class="fa-solid fa-location-dot"></i> Kantor Pusat (Kendari)</span>
                            <span><i class="fa-solid fa-briefcase"></i> Penuh Waktu</span>
                        </div>
                    </div>
                </div>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px; line-height: 1.6;">Bertanggung jawab mengelola infrastruktur server, keamanan database, dan uptime sistem SIMKAB.</p>
                <button class="btn-secondary-large btn-apply" data-posisi="IT System Administrator" style="width: 100%; justify-content: center; padding: 12px; font-size: 14px; background: var(--bg-card); color: var(--primary); border-color: var(--primary);">Kirim Lamaran Pekerjaan</button>
            </div>

            <div class="career-card" style="background: var(--bg-main); border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; transition: 0.3s; box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                    <div>
                        <h3 style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin-bottom: 8px;">HR Business Partner</h3>
                        <div style="display: flex; gap: 12px; font-size: 13px; color: var(--text-muted); flex-wrap: wrap;">
                            <span><i class="fa-solid fa-location-dot"></i> Cabang Baubau</span>
                            <span><i class="fa-solid fa-briefcase"></i> Penuh Waktu</span>
                        </div>
                    </div>
                </div>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 24px; line-height: 1.6;">Mengeksekusi strategi SDM cabang, menyaring talenta lokal Sultra, dan memantau KPI perbankan.</p>
                <button class="btn-secondary-large btn-apply" data-posisi="HR Business Partner" style="width: 100%; justify-content: center; padding: 12px; font-size: 14px; background: var(--bg-card); color: var(--primary); border-color: var(--primary);">Kirim Lamaran Pekerjaan</button>
            </div>

        </div>

        <!-- Formulir Lamaran -->
        <div class="apply-modal" id="apply-modal">
            <h3 style="margin-bottom: 24px; color: var(--text-primary); font-size: 24px;">Formulir Aplikasi: <br><span id="job-title-display" style="color: var(--primary);">Posisi</span></h3>
            <div id="apply-alert" style="display: none; background: var(--success-glow); color: var(--success); padding: 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; font-weight: 600; border: 1px solid var(--success);"></div>

            <form id="apply-form">
                <input type="hidden" name="posisi" id="posisi-input">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <input type="text" name="nama" class="input-field" placeholder="Nama Lengkap KTP" required>
                    <input type="email" name="email" class="input-field" placeholder="Alamat Email Aktif" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <input type="tel" name="telepon" class="input-field" placeholder="Nomor WhatsApp" required>
                    <input type="url" name="cv_link" class="input-field" placeholder="Tautan LinkedIn atau Drive CV" required>
                </div>
                <textarea name="pesan" class="input-field" placeholder="Ceritakan singkat pencapaian terbaik Anda..." rows="4"></textarea>
                
                <div style="display: flex; gap: 16px; justify-content: flex-end; margin-top: 10px;">
                    <button type="button" class="btn-secondary-large" id="close-modal" style="padding: 12px 24px; font-size: 14px;">Tutup</button>
                    <button type="submit" class="btn-primary-large" id="submit-apply" style="padding: 12px 24px; font-size: 14px;">Kirim Berkas Lengkap</button>
                </div>
            </form>
        </div>
    </section>    <!-- NEW SECTION: TIM PENGEMBANG -->
    <section class="dev-section" id="tim-pengembang">
        <div class="section-header">
            <h2>Tim Pengembang Sistem</h2>
            <p>Sistem Informasi Manajemen Karyawan Bank (SIMKAB) ini dirancang dan dikembangkan dengan penuh dedikasi oleh talenta-talenta hebat dari <strong>Kelompok 3</strong>. Kami berkolaborasi untuk menghadirkan solusi teknologi yang modern, efisien, dan interaktif.</p>
        </div>
        <div class="dev-grid">
            <div class="dev-card leader">
                <div class="dev-avatar">
                    <span class="dev-initial">D</span>
                    <img src="assets/img/dev_dirga.jpg" alt="Dirga" onerror="this.style.display='none'">
                </div>
                <div class="dev-badge">Ketua</div>
                <h3>La Ode Muhamad Dirga</h3>
                <p>E1E124007</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">F</span>
                    <img src="assets/img/fadhillah.jpeg" alt="Fadhilah" onerror="this.style.display='none'">
                </div>
                <h3>Fadhilah Fajar R.H</h3>
                <p>E1E124033</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">G</span>
                    <img src="assets/img/dev_gilang.jpg" alt="Gilang" onerror="this.style.display='none'">
                </div>
                <h3>Gilang Syah Fitrah R.</h3>
                <p>E1E124037</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">W</span>
                    <img src="assets/img/indah.jpeg" alt="Wa Ode" onerror="this.style.display='none'">
                </div>
                <h3>Wa Ode Indah Nur Ramadhani</h3>
                <p>E1E124021</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">L</span>
                    <img src="assets/img/raihan.jpeg" alt="Muammar" onerror="this.style.display='none'">
                </div>
                <h3>La Ode Muammar Raihan S.</h3>
                <p>E1E124063</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">A</span>
                    <img src="assets/img/asraf.jpeg" alt="Asraf" onerror="this.style.display='none'">
                </div>
                <h3>Asraf Falaj</h3>
                <p>E1E124030</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">N</span>
                    <img src="assets/img/dev_nikmal.jpg" alt="Nikmal" onerror="this.style.display='none'">
                </div>
                <h3>Nikmal Anakoruo</h3>
                <p>E1E124047</p>
            </div>
        </div>
    </section>

    <!-- NEW SECTION: FAQ -->
    <section class="faq-section" id="faq">
        <div class="section-header">
            <h2>Pertanyaan yang Sering Diajukan</h2>
            <p>Jawaban cepat untuk pertanyaan umum mengenai penggunaan sistem SIMKAB.</p>
        </div>
        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    Apakah data pribadi saya aman di dalam sistem?
                    <i class="fa-solid fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Tentu. Sistem ini dibangun dengan standar keamanan perbankan (enkripsi SSL 256-bit). Data pribadi, rekapan medis, hingga perhitungan gaji dilindungi ketat dan hanya dapat diakses oleh divisi terkait dengan hak akses khusus.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Bagaimana jika saya lupa kata sandi login?
                    <i class="fa-solid fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Anda dapat menghubungi tim IT Support internal (Ext. 112) atau menggunakan fitur Lupa Sandi pada layar Login (jika diaktifkan oleh admin). Permintaan reset sandi akan dikirim ke alamat surel (email) kantor yang terdaftar di database.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Apakah SIMKAB bisa diakses dari luar jaringan kantor?
                    <i class="fa-solid fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Secara *default*, sistem ini terhubung ke *intranet* bank demi keamanan ketat. Namun, karyawan lapangan atau yang sedang WFH dapat menggunakan VPN Perusahaan untuk mengakses portal pengumuman dan pengajuan cuti secara fleksibel.
                </div>
            </div>
        </div>
    </section>

    <footer class="site-footer">
        <div class="footer-grid">
            <div>
                <div class="brand-logo footer-brand">
                    <div class="brand-text" style="font-size: 24px; margin-bottom: 16px;">
                        SIMKAB ENTERPRISE
                    </div>
                </div>
                <p class="footer-desc">Platform Sistem Informasi Manajemen Karyawan yang dirancang untuk otomatisasi SDM, efisiensi penggajian, dan transparansi data.</p>
            </div>
            <div class="footer-links">
                <h4>Perusahaan</h4>
                <ul>
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Tata Kelola</a></li>
                    <li><a href="#">Hubungan Investor</a></li>
                    <li><a href="#karir">Karir</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h4>Legalitas</h4>
                <ul>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat Ketentuan</a></li>
                    <li><a href="#">Keamanan Data</a></li>
                    <li><a href="#">Sertifikasi ISO</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; 2026 Bank Terdaftar dan diawasi oleh Otoritas Jasa Keuangan (OJK).
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Theme Toggle
            const themeBtn = document.getElementById('theme-btn');
            const themeIcon = themeBtn.querySelector('i');
            
            function applyTheme(theme) {
                if(theme === 'dark') {
                    themeIcon.className = 'fa-solid fa-sun';
                } else {
                    themeIcon.className = 'fa-solid fa-moon';
                }
            }
            
            let currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            applyTheme(currentTheme);
            
            themeBtn.addEventListener('click', () => {
                currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', currentTheme);
                localStorage.setItem('simkab-theme', currentTheme);
                applyTheme(currentTheme);
            });

            // Career Form Logic
            const jobCards = document.querySelectorAll('.btn-apply');
            const applyModal = document.getElementById('apply-modal');
            const jobDisplay = document.getElementById('job-title-display');
            const posInput = document.getElementById('posisi-input');
            const closeBtn = document.getElementById('close-modal');
            const applyForm = document.getElementById('apply-form');
            const alertBox = document.getElementById('apply-alert');
            const submitBtn = document.getElementById('submit-apply');

            jobCards.forEach(card => {
                card.addEventListener('click', () => {
                    const title = card.getAttribute('data-posisi');
                    jobDisplay.textContent = title;
                    posInput.value = title;
                    applyModal.style.display = 'block';
                    alertBox.style.display = 'none';
                    applyModal.scrollIntoView({behavior: 'smooth', block: 'center'});
                });
            });

            closeBtn.addEventListener('click', () => {
                applyModal.style.display = 'none';
                applyForm.reset();
            });

            applyForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Memproses Berkas...';
                submitBtn.disabled = true;

                const formData = new FormData(applyForm);
                fetch('api.php?action=apply_job', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    
                    if(data.status === 'success') {
                        alertBox.textContent = data.message;
                        alertBox.className = '';
                        alertBox.style.display = 'block';
                        alertBox.style.background = 'var(--success-glow)';
                        alertBox.style.color = 'var(--success)';
                        applyForm.reset();
                    } else {
                        alertBox.textContent = data.message;
                        alertBox.style.display = 'block';
                        alertBox.style.background = 'var(--danger-glow)';
                        alertBox.style.color = 'var(--danger)';
                        alertBox.style.border = '1px solid var(--danger)';
                    }
                })
                .catch(err => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    alertBox.textContent = 'Terjadi kesalahan jaringan pada server.';
                    alertBox.style.display = 'block';
                    alertBox.style.background = 'var(--danger-glow)';
                    alertBox.style.color = 'var(--danger)';
                });
            });

            // FAQ Logic
            const faqItems = document.querySelectorAll('.faq-item');
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                question.addEventListener('click', () => {
                    const isActive = item.classList.contains('active');
                    // Close all FAQs first
                    faqItems.forEach(i => i.classList.remove('active'));
                    // Open clicked if it wasn't active
                    if(!isActive) {
                        item.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>



















