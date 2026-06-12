<?php
/**
 * SIMKAB - Sistem Informasi Manajemen Karyawan Bank
 * login.php - Portal Autentikasi Murni & Minimalis (SaaS Style)
 */
if (!file_exists(__DIR__ . '/sessions')) { mkdir(__DIR__ . '/sessions', 0777, true); }

session_save_path(__DIR__ . '/sessions');
@session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Portal SIMKAB</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <script>
        (function() {
            const savedTheme = localStorage.getItem('simkab-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    <style>
        /* --------------------------------------------------------------------------
           CLEAN MINIMALIST AUTHENTICATION STYLES
           -------------------------------------------------------------------------- */
        body {
            background-color: var(--bg-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: var(--text-primary);
        }

        /* Latar Belakang Grafis (Lebih redup agar tidak mengganggu) */
        .bg-layer {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 0.5s;
            z-index: -1;
        }
        .bg-layer.active { opacity: 0.15; }
        
        /* Kartu Login Minimalis */
        .login-container {
            display: flex;
            width: 100%;
            max-width: 900px;
            background: var(--bg-card-solid);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            z-index: 10;
            border: 1px solid var(--border-color);
        }

        /* Bagian Kiri: Branding Warna Solid */
        .brand-section {
            flex: 1;
            background: var(--bg-card-hover);
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid var(--border-color);
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: var(--primary);
            color: #fff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 24px;
        }

        .brand-title {
            font-size: 32px;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 16px;
            color: var(--text-primary);
        }

        .brand-subtitle {
            font-size: 15px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Bagian Kanan: Form Login yang Sangat Clean */
        .form-section {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .form-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 32px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-secondary);
        }

        .clean-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-primary);
            background: var(--bg-card-solid);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }

        .clean-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .btn-primary {
            width: 100%;
            background: var(--primary);
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .btn-primary:hover { background: var(--primary-light); }
        .btn-primary:disabled { background: var(--border-color); cursor: not-allowed; }

        /* Alert Box Minimalis */
        .alert-box {
            display: none;
            background: var(--danger-glow);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger);
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
            align-items: center;
            gap: 8px;
        }

        /* Demo Accounts - Clean Tags */
        .demo-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
        }

        .demo-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .demo-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .demo-tag {
            background: var(--bg-card-hover);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .demo-tag:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: var(--primary-glow);
        }

        /* Navigasi Kembali ke Landing */
        .back-link {
            position: absolute;
            top: 30px;
            left: 40px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }

        .back-link:hover { color: var(--primary); }

        @media (max-width: 768px) {
            .login-container { flex-direction: column; margin: 20px; }
            .brand-section { padding: 40px; border-right: none; border-bottom: 1px solid var(--border-color); }
            .form-section { padding: 40px; }
            .back-link { top: 15px; left: 20px; }
        }
    </style>
</head>
<body>

    <a href="landing.php" class="back-link">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <div class="login-container">
        <!-- Area Kiri: Branding -->
        <div class="brand-section">
            <div class="brand-logo">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h1 class="brand-title">Autentikasi Aman</h1>
            <p class="brand-subtitle">Silakan masuk menggunakan kredensial SIMKAB Anda untuk mengakses layanan manajemen terpadu.</p>
        </div>

        <!-- Area Kanan: Form -->
        <div class="form-section">
            <h2 class="form-title">Login</h2>
            <p class="form-subtitle">Sesi Anda dilindungi oleh enkripsi ujung-ke-ujung.</p>

            <div class="alert-box" id="error-alert">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span id="error-msg">Kredensial tidak valid.</span>
            </div>

            <form id="login-form">
                <div class="input-group">
                    <label class="input-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="clean-input" placeholder="Contoh: admin" required>
                </div>
                
                <div class="input-group">
                    <label class="input-label" for="password">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="clean-input" placeholder="Masukkan kata sandi" required>
                </div>

                <button type="submit" class="btn-primary" id="submit-btn">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="demo-section">
                <div class="demo-title">Pilih Akun Demo (Otomatis Isi)</div>
                <div class="demo-tags">
                    <button type="button" class="demo-tag" data-u="admin" data-p="admin123">Admin</button>
                    <button type="button" class="demo-tag" data-u="budi.darmawan" data-p="hrd123">HRD</button>
                    <button type="button" class="demo-tag" data-u="rizka.amanda" data-p="karyawan123">Pegawai</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
            // No theme button on login, just apply via root attr
            
            // Listen to localstorage changes if user changes theme on another page
            window.addEventListener('storage', (e) => {
                if(e.key === 'simkab-theme') {
                    document.documentElement.setAttribute('data-theme', e.newValue);
                }
            });

            // Pengisian Otomatis Akun Demo secara Instan (Clean)
            const inputUser = document.getElementById('username');
            const inputPass = document.getElementById('password');
            const demoTags = document.querySelectorAll('.demo-tag');

            demoTags.forEach(tag => {
                tag.addEventListener('click', () => {
                    inputUser.value = tag.getAttribute('data-u');
                    inputPass.value = tag.getAttribute('data-p');
                    inputUser.focus();
                });
            });

            // Proses Form Login AJAX
            const loginForm = document.getElementById('login-form');
            const submitBtn = document.getElementById('submit-btn');
            const alertBox = document.getElementById('error-alert');
            const errorMsg = document.getElementById('error-msg');

            loginForm.addEventListener('submit', (e) => {
                e.preventDefault();
                alertBox.style.display = 'none';
                
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Memverifikasi...';
                submitBtn.disabled = true;

                const formData = new FormData(loginForm);

                fetch('api.php?action=login', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        submitBtn.textContent = 'Berhasil! Mengalihkan...';
                        submitBtn.style.background = 'var(--success)';
                        setTimeout(() => { window.location.href = 'index.php'; }, 500);
                    } else {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        errorMsg.textContent = data.message;
                        alertBox.style.display = 'flex';
                    }
                })
                .catch(err => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    errorMsg.textContent = 'Koneksi ke server gagal.';
                    alertBox.style.display = 'flex';
                });
            });
        });
    </script>
</body>
</html>

