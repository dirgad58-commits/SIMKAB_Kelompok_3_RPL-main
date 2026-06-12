<!-- ==========================================================================
     TAB 1: DASHBOARD ANALYTICS (ROLE-BASED)
     ========================================================================== -->
<section id="panel-dashboard" class="panel-section active">
    
    <?php if ($user_role === 'Admin' || $user_role === 'HRD'): ?>
        <!-- ==================== VIEW ADMIN / HRD ==================== -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Total Karyawan</h3>
                    <div class="stat-value" id="stat-total-karyawan">1,248</div>
                </div>
                <div class="stat-icon" style="color: var(--primary);"><i class="fa-solid fa-users"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Hadir Hari Ini</h3>
                    <div class="stat-value" id="stat-hadir-hari-ini">1,192</div>
                </div>
                <div class="stat-icon" style="color: var(--success);"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Karyawan Cuti</h3>
                    <div class="stat-value" id="stat-karyawan-cuti">45</div>
                </div>
                <div class="stat-icon" style="color: var(--warning);"><i class="fa-solid fa-umbrella-beach"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Peringatan Sistem</h3>
                    <div class="stat-value" id="stat-pengumuman-baru">2</div>
                </div>
                <div class="stat-icon" style="color: var(--danger);"><i class="fa-solid fa-triangle-exclamation"></i></div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-chart-line"></i> Tren Kehadiran Nasional</h2>
                </div>
                <div style="position: relative; height: 320px;">
                    <canvas id="chart-kehadiran"></canvas>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-chart-pie"></i> Distribusi Divisi</h2>
                </div>
                <div style="position: relative; height: 320px;">
                    <canvas id="chart-divisi"></canvas>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- ==================== VIEW KARYAWAN ==================== -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Kehadiran Bulan Ini</h3>
                    <div class="stat-value" style="color: var(--success);">21 Hari</div>
                </div>
                <div class="stat-icon" style="color: var(--success);"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Sisa Kuota Cuti</h3>
                    <div class="stat-value">8 Hari</div>
                </div>
                <div class="stat-icon" style="color: var(--primary);"><i class="fa-solid fa-umbrella-beach"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Nilai Kinerja (KPI)</h3>
                    <div class="stat-value" style="color: var(--accent);">A- (92)</div>
                </div>
                <div class="stat-icon" style="color: var(--accent);"><i class="fa-solid fa-star"></i></div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <h3>Pengumuman Baru</h3>
                    <div class="stat-value" style="color: var(--warning);">1 Pesan</div>
                </div>
                <div class="stat-icon" style="color: var(--warning);"><i class="fa-solid fa-envelope"></i></div>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="card" style="grid-column: span 2;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-bullseye"></i> Grafik Pencapaian Target Kinerja (YTD)</h2>
                </div>
                <div style="position: relative; height: 320px;">
                    <!-- Digunakan canvas yang sama agar tidak error pada script JS global, tetapi penamaannya bisa disesuaikan -->
                    <canvas id="chart-kehadiran"></canvas> 
                </div>
            </div>
        </div>
    <?php endif; ?>

</section>

