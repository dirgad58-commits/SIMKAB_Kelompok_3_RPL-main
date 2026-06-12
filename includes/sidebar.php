        <!-- ==========================================================================
             SIDEBAR NAVIGASI (ROLE-BASED)
             ========================================================================== -->
        <aside class="sidebar" id="app-sidebar">
            <div class="sidebar-header">
                <div class="logo-wrapper">
                    <div class="logo-icon"><i class="fa-solid fa-layer-group"></i></div>
                    <span class="logo-text">SIMKAB BANK</span>
                </div>
            </div>
            
            <?php
            $user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'Karyawan';
            $user_name = isset($_SESSION['nama_karyawan']) ? $_SESSION['nama_karyawan'] : (isset($_SESSION['username']) ? $_SESSION['username'] : 'Staf Bank');

            // Hitung Inisial Avatar
            $avatar_initials = '';
            $words = explode(' ', str_replace([',', '.'], '', $user_name));
            foreach ($words as $w) {
                if (!empty($w)) $avatar_initials .= strtoupper($w[0]);
            }
            $avatar_initials = substr($avatar_initials, 0, 2);
            if (empty($avatar_initials)) $avatar_initials = 'ST';
            ?>
            
            <nav class="sidebar-menu">
                <!-- Menu Universal (Semua Peran) -->
                <a class="menu-item active" data-target="panel-dashboard">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard <?php echo strtolower(trim($user_role)) === 'karyawan' ? 'Pribadi' : 'Analytics'; ?></span>
                </a>

                <?php if (strtolower(trim($user_role)) === 'superadmin'): ?>
                    <!-- Menu Khusus Superadmin (Semua Akses) -->
                    <a class="menu-item" data-target="panel-karyawan">
                        <i class="fa-solid fa-users"></i>
                        <span>Direktori Karyawan</span>
                    </a>
                    <a class="menu-item" data-target="panel-kinerja">
                        <i class="fa-solid fa-star"></i>
                        <span>Penilaian Kinerja</span>
                    </a>
                    <a class="menu-item" data-target="panel-payroll">
                        <i class="fa-solid fa-wallet"></i>
                        <span>Manajemen Payroll</span>
                    </a>
                    <a class="menu-item" data-target="panel-mutasi">
                        <i class="fa-solid fa-shuffle"></i>
                        <span>Mutasi & Promosi</span>
                    </a>
                    <a class="menu-item" data-target="panel-aset">
                        <i class="fa-solid fa-laptop-code"></i>
                        <span>Aset & Inventaris</span>
                    </a>
                    <a class="menu-item" data-target="panel-cuti">
                        <i class="fa-solid fa-calendar-minus"></i>
                        <span>Kelola Cuti</span>
                    </a>
                    <a class="menu-item" data-target="panel-pelatihan">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>Pelatihan Karyawan</span>
                    </a>
                    <a class="menu-item" data-target="panel-pengumuman">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>Pengumuman Internal</span>
                    </a>
                <?php elseif (strtolower(trim($user_role)) === 'admin'): ?>
                    <a class="menu-item" data-target="panel-karyawan">
                        <i class="fa-solid fa-users"></i>
                        <span>Direktori Karyawan</span>
                    </a>
                    <a class="menu-item" data-target="panel-mutasi">
                        <i class="fa-solid fa-shuffle"></i>
                        <span>Mutasi & Promosi</span>
                    </a>
                    <a class="menu-item" data-target="panel-aset">
                        <i class="fa-solid fa-laptop-code"></i>
                        <span>Aset & Inventaris</span>
                    </a>
                    <a class="menu-item" data-target="panel-pengumuman">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>Pengumuman Internal</span>
                    </a>
                <?php elseif (strtolower(trim($user_role)) === 'hrd'): ?>
                    <!-- Menu Khusus HRD -->
                    <a class="menu-item" data-target="panel-karyawan">
                        <i class="fa-solid fa-users"></i>
                        <span>Direktori Karyawan</span>
                    </a>
                    <a class="menu-item" data-target="panel-kinerja">
                        <i class="fa-solid fa-star"></i>
                        <span>Penilaian Kinerja</span>
                    </a>
                    <a class="menu-item" data-target="panel-payroll">
                        <i class="fa-solid fa-wallet"></i>
                        <span>Manajemen Payroll</span>
                    </a>
                    <a class="menu-item" data-target="panel-mutasi">
                        <i class="fa-solid fa-shuffle"></i>
                        <span>Mutasi & Promosi</span>
                    </a>
                    <a class="menu-item" data-target="panel-cuti">
                        <i class="fa-solid fa-calendar-minus"></i>
                        <span>Kelola Cuti</span>
                    </a>
                    <a class="menu-item" data-target="panel-pelatihan">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>Pelatihan Karyawan</span>
                    </a>
                    <a class="menu-item" data-target="panel-pengumuman">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>Pengumuman Internal</span>
                    </a>
                <?php else: ?>
                    <!-- Menu Khusus Karyawan -->
                    <a class="menu-item" data-target="panel-karyawan">
                        <i class="fa-solid fa-id-badge"></i>
                        <span>Profil Saya</span>
                    </a>
                    <a class="menu-item" data-target="panel-kinerja">
                        <i class="fa-solid fa-star"></i>
                        <span>Riwayat Kinerja</span>
                    </a>
                    <a class="menu-item" data-target="panel-payroll">
                        <i class="fa-solid fa-wallet"></i>
                        <span>Slip Gaji Saya</span>
                    </a>
                    <a class="menu-item" data-target="panel-cuti">
                        <i class="fa-solid fa-calendar-minus"></i>
                        <span>Pengajuan Cuti</span>
                    </a>
                    <a class="menu-item" data-target="panel-pelatihan">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>Sertifikat Pelatihan</span>
                    </a>
                    <a class="menu-item" data-target="panel-pengumuman">
                        <i class="fa-solid fa-bullhorn"></i>
                        <span>Pengumuman Internal</span>
                    </a>
                <?php endif; ?>

                <!-- Menu Universal (Operasional) -->
                <a class="menu-item" data-target="panel-absensi">
                    <i class="fa-solid fa-clock"></i>
                    <span>Kehadiran & Absen</span>
                </a>
                
                <!-- Logout -->
                <a href="logout.php" class="menu-item logout-link" style="margin-top: 24px; border-top: 1px solid var(--border-color); color: var(--danger);">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Keluar Sistem</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile" title="Sesi Aktif: <?php echo htmlspecialchars($user_name); ?>">
                    <div class="user-avatar" style="background: var(--primary); color: #fff; font-weight: 700;">
                        <?php echo htmlspecialchars($avatar_initials); ?>
                    </div>
                    <div class="user-info">
                        <span class="user-name"><?php echo htmlspecialchars(strlen($user_name) > 18 ? substr($user_name, 0, 16) . '...' : $user_name); ?></span>
                        <span class="user-role" style="color: var(--primary); font-weight: 600; font-size: 11px;"><?php echo htmlspecialchars(strtoupper($user_role)); ?></span>
                    </div>
                </div>
                <button class="collapse-btn" id="sidebar-toggle" title="Ciutkan Sidebar">
                    <i class="fa-solid fa-chevron-left" id="toggle-icon"></i>
                </button>
            </div>
        </aside>
