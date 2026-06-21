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
    $total_pelatihan = $pdo->query("SELECT COUNT(*) FROM pelatihan")->fetchColumn() ?: 0;
    $avg_kinerja = $pdo->query("SELECT AVG(skor_akhir) FROM kinerja")->fetchColumn() ?: 0;
    $avg_kinerja_format = number_format($avg_kinerja, 1);
} catch (Exception $e) {
    $total_karyawan = 0;
    $total_divisi = 0;
    $total_pelatihan = 0;
    $avg_kinerja_format = '0.0';
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
        .dev-avatar { width: 220px; height: 220px; border-radius: 50%; background: var(--primary-glow); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 56px; margin: 0 auto 24px; font-weight: 800; border: 4px solid var(--primary); text-transform: uppercase; overflow: hidden; position: relative; box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.3); transition: transform 0.4s; }
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
            <a href="#informasi">Informasi</a>
            <a href="#fitur">Fitur Utama</a>
            <a href="#tim-pengembang">Tim Pengembang</a>
            <a href="#faq">Pusat Bantuan</a>
            <a href="#tentang-kami">Tentang Kami</a>
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

    <!-- Statistik Sistem (New Modern Banner) -->
    <section id="informasi" style="padding: 60px 20px; background: transparent;">
        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
            
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 32px 24px; text-align: center; box-shadow: var(--shadow-soft); position: relative; overflow: hidden; transition: var(--transition-smooth);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='var(--shadow-glow)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-soft)';">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; opacity: 0.03; color: var(--primary);"><i class="fa-solid fa-users"></i></div>
                <div style="width: 60px; height: 60px; background: var(--primary-glow); color: var(--primary); font-size: 24px; display: flex; align-items: center; justify-content: center; border-radius: 16px; margin: 0 auto 20px;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div style="font-size: 42px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;"><?php echo $total_karyawan; ?></div>
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Karyawan Aktif</div>
            </div>

            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 32px 24px; text-align: center; box-shadow: var(--shadow-soft); position: relative; overflow: hidden; transition: var(--transition-smooth);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='var(--shadow-glow)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-soft)';">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; opacity: 0.03; color: var(--success);"><i class="fa-solid fa-building"></i></div>
                <div style="width: 60px; height: 60px; background: var(--success-glow); color: var(--success); font-size: 24px; display: flex; align-items: center; justify-content: center; border-radius: 16px; margin: 0 auto 20px;">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div style="font-size: 42px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;"><?php echo $total_divisi; ?></div>
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Divisi & Departemen</div>
            </div>

            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 32px 24px; text-align: center; box-shadow: var(--shadow-soft); position: relative; overflow: hidden; transition: var(--transition-smooth);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='var(--shadow-glow)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-soft)';">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; opacity: 0.03; color: var(--warning);"><i class="fa-solid fa-graduation-cap"></i></div>
                <div style="width: 60px; height: 60px; background: rgba(245, 158, 11, 0.1); color: var(--warning); font-size: 24px; display: flex; align-items: center; justify-content: center; border-radius: 16px; margin: 0 auto 20px;">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div style="font-size: 42px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;"><?php echo $total_pelatihan; ?></div>
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Program Sertifikasi</div>
            </div>

            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: var(--border-radius-lg); padding: 32px 24px; text-align: center; box-shadow: var(--shadow-soft); position: relative; overflow: hidden; transition: var(--transition-smooth);" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='var(--shadow-glow)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='var(--shadow-soft)';">
                <div style="position: absolute; top: -20px; right: -20px; font-size: 100px; opacity: 0.03; color: var(--accent);"><i class="fa-solid fa-chart-line"></i></div>
                <div style="width: 60px; height: 60px; background: var(--accent-glow); color: var(--accent); font-size: 24px; display: flex; align-items: center; justify-content: center; border-radius: 16px; margin: 0 auto 20px;">
                    <i class="fa-solid fa-star"></i>
                </div>
                <div style="font-size: 42px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px;"><?php echo $avg_kinerja_format; ?></div>
                <div style="font-size: 14px; color: var(--text-muted); font-weight: 600; text-transform: uppercase; letter-spacing: 1.5px;">Rata-rata Kinerja</div>
            </div>
            
        </div>
    </section>

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


    <!-- NEW SECTION: TIM PENGEMBANG -->
    <section class="dev-section" id="tim-pengembang">
        <div class="section-header">
            <h2>Tim Pengembang Sistem</h2>
            <p>Sistem Informasi Manajemen Karyawan Bank (SIMKAB) ini dirancang dan dikembangkan dengan penuh dedikasi oleh talenta-talenta hebat dari <strong>Kelompok 3</strong>. Kami berkolaborasi untuk menghadirkan solusi teknologi yang modern, efisien, dan interaktif.</p>
        </div>
        <div class="dev-grid">
            <div class="dev-card leader">
                <div class="dev-avatar">
                    <span class="dev-initial">D</span>
                    <img src="assets/img/dirga_hd.jpeg" alt="Dirga" onerror="this.style.display='none'">
                </div>
                <div class="dev-badge">Ketua</div>
                <h3>La Ode Muhamad Dirga</h3>
                <p>E1E124007</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">F</span>
                    <img src="assets/img/fadhillah_hd.jpeg" alt="Fadhilah" onerror="this.style.display='none'">
                </div>
                <h3>Fadhilah Fajar R.H</h3>
                <p>E1E124033</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">G</span>
                    <img src="assets/img/gilang_hd.jpeg" alt="Gilang" onerror="this.style.display='none'">
                </div>
                <h3>Gilang Syah Fitrah R.</h3>
                <p>E1E124037</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">W</span>
                    <img src="assets/img/indah_hd.jpeg" alt="Wa Ode" onerror="this.style.display='none'">
                </div>
                <h3>Wa Ode Indah Nur Ramadhani</h3>
                <p>E1E124021</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">L</span>
                    <img src="assets/img/raihan_hd.jpeg" alt="Muammar" onerror="this.style.display='none'">
                </div>
                <h3>La Ode Muammar Raihan S.</h3>
                <p>E1E124063</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">A</span>
                    <img src="assets/img/asraf_hd.jpeg" alt="Asraf" onerror="this.style.display='none'">
                </div>
                <h3>Asraf Falaj</h3>
                <p>E1E124030</p>
            </div>
            <div class="dev-card">
                <div class="dev-avatar">
                    <span class="dev-initial">N</span>
                    <img src="assets/img/nikmal.jpeg" alt="Nikmal" onerror="this.style.display='none'">
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
                    Bagaimana cara melakukan proses Absensi (Clock In / Clock Out)?
                    <i class="fa-solid fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Karyawan dapat masuk ke menu <strong>Absensi</strong> di dashboard utama setelah login. Sistem akan mencatat waktu kehadiran secara <em>real-time</em>. Jika Anda melakukan <em>Clock In</em> melewati jam masuk yang telah ditetapkan, status akan otomatis tercatat oleh sistem sebagai "Terlambat".
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Apa saja syarat untuk mengajukan Cuti Tahunan melalui sistem?
                    <i class="fa-solid fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Pengajuan cuti dilakukan melalui modul <strong>Cuti & Izin</strong>. Pastikan Anda masih memiliki sisa kuota cuti tahunan dan pengajuan wajib dilakukan minimal H-3 sebelum tanggal pelaksanaan. Status persetujuan (<em>Approve/Reject</em>) dari HRD atau Atasan dapat dipantau langsung dari layar portal Anda.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Kapan Slip Gaji bulanan terbit dan dapat diunduh?
                    <i class="fa-solid fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    Data penggajian diproses pada akhir periode dan <em>Slip Gaji</em> digital akan otomatis diterbitkan oleh sistem setiap akhir bulan. Slip ini memuat rincian Gaji Pokok, Tunjangan, Potongan Keterlambatan, serta Bonus Kinerja, dan dapat diunduh dalam format PDF di menu <strong>Payroll</strong>.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question">
                    Bagaimana Sistem SIMKAB menilai Kinerja bulanan saya?
                    <i class="fa-solid fa-chevron-down faq-icon"></i>
                </div>
                <div class="faq-answer">
                    SIMKAB menggunakan algoritma penilaian kinerja yang menghasilkan predikat otomatis (Sangat Baik, Baik, Cukup, Kurang). Nilai akhir dihitung berdasarkan persentase kehadiran (modul Absensi) yang dikombinasikan dengan skor evaluasi atasan pada modul <strong>Penilaian Kinerja</strong>.
                </div>
            </div>
        </div>
    </section>

    <footer class="site-footer" id="tentang-kami" style="background: #0f172a; color: #e2e8f0; border-top: none; padding-top: 80px;">
        <div class="footer-grid" style="grid-template-columns: 1.2fr 1fr 1fr; gap: 40px;">
            <div style="padding-right: 20px;">
                <div class="brand-logo footer-brand" style="color: #ffffff; margin-bottom: 24px;">
                    <div class="brand-icon"><i class="fa-solid fa-layer-group"></i></div>
                    <div class="brand-text" style="font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">
                        SIMKAB <span style="color: var(--primary);">ENTERPRISE</span>
                    </div>
                </div>
                <p class="footer-desc" style="line-height: 1.8; color: #94a3b8; font-size: 15px; margin-bottom: 30px; max-width: 100%;">
                    Platform HRIS berstandar perbankan. Kami mengotomatisasi absensi, manajemen cuti, hingga kalkulasi <em>payroll</em> secara presisi, aman, dan transparan.
                </p>
                <div style="display: flex; gap: 16px;">
                    <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; color: #cbd5e1; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='var(--primary)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#cbd5e1';"><i class="fa-brands fa-linkedin-in"></i></a>
                    <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; color: #cbd5e1; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='var(--primary)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#cbd5e1';"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" style="width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; color: #cbd5e1; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='var(--primary)'; this.style.color='#fff';" onmouseout="this.style.background='rgba(255,255,255,0.05)'; this.style.color='#cbd5e1';"><i class="fa-brands fa-github"></i></a>
                </div>
            </div>
            <div class="footer-info">
                <h4 style="color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 24px; position: relative; padding-bottom: 12px;">
                    Informasi Perusahaan
                    <span style="content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: var(--primary); border-radius: 2px;"></span>
                </h4>
                <p style="color: #94a3b8; font-size: 15px; line-height: 1.8;">
                    Pionir penyedia infrastruktur HR digital. Kami membantu perusahaan agar dapat fokus pada pengembangan talenta, tanpa dipusingkan oleh beban administratif.
                </p>
            </div>
            <div class="footer-info">
                <h4 style="color: #ffffff; font-size: 18px; font-weight: 700; margin-bottom: 24px; position: relative; padding-bottom: 12px;">
                    Kontak & Lokasi
                    <span style="content: ''; position: absolute; left: 0; bottom: 0; width: 40px; height: 3px; background: var(--primary); border-radius: 2px;"></span>
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; color: #94a3b8; font-size: 15px; line-height: 2;">
                    <li style="display: flex; align-items: flex-start; margin-bottom: 16px;">
                        <i class="fa-solid fa-building" style="color: var(--primary); font-size: 18px; margin-right: 16px; margin-top: 4px; width: 20px; text-align: center;"></i>
                        <span>Gedung SIMKAB Tower Lt. 12<br>Jl. Jend. Sudirman Kav. 52<br>Jakarta Selatan, 12190</span>
                    </li>
                    <li style="display: flex; align-items: center; margin-bottom: 16px;">
                        <i class="fa-solid fa-envelope" style="color: var(--primary); font-size: 18px; margin-right: 16px; width: 20px; text-align: center;"></i>
                        <span>cs@simkab.co.id</span>
                    </li>
                    <li style="display: flex; align-items: center;">
                        <i class="fa-solid fa-phone" style="color: var(--primary); font-size: 18px; margin-right: 16px; width: 20px; text-align: center;"></i>
                        <span>(021) 1500-888</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom" style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px; margin-top: 40px; color: #64748b;">
            &copy; <?php echo date("Y"); ?> SIMKAB Enterprise. Sistem Informasi Terdaftar dan Diawasi secara Internal.
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



















