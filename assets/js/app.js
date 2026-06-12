/**
 * SIMKAB - Sistem Informasi Manajemen Karyawan Bank
 * js/app.js - Logika Interaksi DOM, Event Handlers, AJAX Fetch, & State Controller
 */

// ==========================================================================
// 1. IN-MEMORY STATE FOR FE COMPATIBILITY WITH CHART-CONFIGS.JS & DOM
// ==========================================================================
window.SIMKABData = {
    karyawan: [],
    absensi: [],
    kinerja: [],
    cuti: [],
    payroll: [],
    mutasi: [],
    pelatihan: [],
    aset: [],
    pengumuman: [],
    getKaryawan() { return this.karyawan; },
    getAbsensi() { return this.absensi; },
    getKinerja() { return this.kinerja; },
    getCuti() { return this.cuti; },
    getPayroll() { return this.payroll; },
    getMutasi() { return this.mutasi; },
    getPelatihan() { return this.pelatihan; },
    getAset() { return this.aset; },
    getPengumuman() { return this.pengumuman; }
};

document.addEventListener('DOMContentLoaded', () => {
    
    // ==========================================================================
    // 2. STATE & ROUTING INITIALIZATION
    // ==========================================================================
    
    // Element DOM Utama
    const sidebar = document.getElementById('app-sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const toggleIcon = document.getElementById('toggle-icon');
    const menuItems = document.querySelectorAll('.menu-item');
    const panelSections = document.querySelectorAll('.panel-section');
    const pageTitle = document.getElementById('page-title');
    const pageSubtitle = document.getElementById('page-subtitle');

    // ==========================================================================
    // TEMA & CHART SINKRONISASI (Premium Dual-Theme Sync)
    // ==========================================================================
    const dashboardThemeToggle = document.getElementById('dashboard-theme-toggle');
    if (dashboardThemeToggle) {
        const themeIcon = dashboardThemeToggle.querySelector('i');
        
        function updateDashboardThemeIcon(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'fa-solid fa-sun';
            } else {
                themeIcon.className = 'fa-solid fa-moon';
            }
        }

        // Ambil tema aktif saat ini
        const activeTheme = document.documentElement.getAttribute('data-theme') || 'light';
        updateDashboardThemeIcon(activeTheme);

        dashboardThemeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', currentTheme);
            localStorage.setItem('simkab-theme', currentTheme);
            updateDashboardThemeIcon(currentTheme);

            // Perbarui Chart.js seketika dengan token warna CSS yang baru
            if (typeof SIMKABCharts !== 'undefined') {
                SIMKABCharts.updateAllCharts();
            }
        });
    }
    
    // Subtitle Map untuk tab routing
    const pageSubtitles = {
        'panel-dashboard': 'Ringkasan eksekutif dan statistik kinerja bank hari ini.',
        'panel-karyawan': 'Kelola, cari, dan mutakhirkan seluruh data staf perbankan Anda.',
        'panel-kinerja': 'Catat key performance indicator (KPI) bulanan dan tahunan karyawan.',
        'panel-payroll': 'Kalkulasi slip gaji bruto, tunjangan, insentif, dan cetak slip resmi.',
        'panel-cuti': 'Panel otorisasi perizinan cuti tahunan dan status pengajuan.',
        'panel-absensi': 'Pencatatan jam masuk & pulang kerja harian karyawan terintegrasi.',
        'panel-mutasi': 'Rekam jejak perpindahan jabatan, promosi, dan mutasi staf.',
        'panel-pelatihan': 'Hub sertifikasi kompetensi perbankan dan keikutsertaan pelatihan.',
        'panel-aset': 'Lacak serah terima aset laptop, inventaris, dan fasilitas dinas.',
        'panel-pengumuman': 'Memo internal, edaran direksi, dan bulletin kebijakan terbaru.'
    };

    // FUNGSI UTAMA SINKRONISASI DATA VIA AJAX FETCH
    async function refreshAllData() {
        try {
            const [
                resKaryawan, resAbsensi, resKinerja, resCuti, resPayroll,
                resMutasi, resPelatihan, resAset, resPengumuman
            ] = await Promise.all([
                fetch('api.php?action=get_karyawan').then(r => r.json()),
                fetch('api.php?action=get_absensi').then(r => r.json()),
                fetch('api.php?action=get_kinerja').then(r => r.json()),
                fetch('api.php?action=get_cuti').then(r => r.json()),
                fetch('api.php?action=get_payroll').then(r => r.json()),
                fetch('api.php?action=get_mutasi').then(r => r.json()),
                fetch('api.php?action=get_pelatihan').then(r => r.json()),
                fetch('api.php?action=get_aset').then(r => r.json()),
                fetch('api.php?action=get_pengumuman').then(r => r.json())
            ]);

            if (resKaryawan.status === 'success') {
                window.SIMKABData.karyawan = resKaryawan.data.map(e => ({
                    id: e.id,
                    nip: e.nip,
                    nama: e.nama,
                    email: e.email,
                    telepon: e.telepon,
                    divisi: e.divisi,
                    jabatan: e.jabatan,
                    status: e.status,
                    gajiPokok: parseFloat(e.gaji_pokok),
                    tunjangan: parseFloat(e.tunjangan),
                    tanggalBergabung: e.tanggal_bergabung,
                    sisaCuti: parseInt(e.sisa_cuti),
                    foto: e.foto
                }));
            }
            if (resAbsensi.status === 'success') {
                window.SIMKABData.absensi = resAbsensi.data.map(a => ({
                    id: a.id,
                    idKaryawan: a.id_karyawan,
                    tanggal: a.tanggal,
                    jamMasuk: a.jam_masuk ? a.jam_masuk.slice(0, 5) : '',
                    jamKeluar: a.jam_keluar ? a.jam_keluar.slice(0, 5) : '',
                    status: a.status
                }));
            }
            if (resKinerja.status === 'success') {
                window.SIMKABData.kinerja = resKinerja.data.map(k => ({
                    id: k.id,
                    idKaryawan: k.id_karyawan,
                    periode: k.periode,
                    kedisiplinan: parseFloat(k.kedisiplinan),
                    kerjasama: parseFloat(k.kerjasama),
                    inisiatif: parseFloat(k.inisiatif),
                    target: parseFloat(k.target),
                    skorAkhir: parseFloat(k.skor_akhir),
                    predikat: k.predikat,
                    catatan: k.catatan
                }));
            }
            if (resCuti.status === 'success') {
                window.SIMKABData.cuti = resCuti.data.map(c => ({
                    id: c.id,
                    idKaryawan: c.id_karyawan,
                    jenisCuti: c.jenis_cuti,
                    tanggalMulai: c.tanggal_mulai,
                    tanggalSelesai: c.tanggal_selesai,
                    alasan: c.alasan,
                    status: c.status
                }));
            }
            if (resPayroll.status === 'success') {
                window.SIMKABData.payroll = resPayroll.data.map(p => ({
                    id: p.id,
                    idKaryawan: p.id_karyawan,
                    bulan: p.bulan,
                    gajiPokok: parseFloat(p.gaji_pokok),
                    tunjangan: parseFloat(p.tunjangan),
                    bonus: parseFloat(p.bonus),
                    potongan: parseFloat(p.potongan),
                    totalGaji: parseFloat(p.total_gaji),
                    status: p.status
                }));
            }
            if (resMutasi.status === 'success') {
                window.SIMKABData.mutasi = resMutasi.data.map(m => ({
                    id: m.id,
                    idKaryawan: m.id_karyawan,
                    jenis: m.jenis,
                    divisiLama: m.divisi_lama,
                    divisiBaru: m.divisi_baru,
                    jabatanLama: m.jabatan_lama,
                    jabatanBaru: m.jabatan_baru,
                    tanggal: m.tanggal,
                    keterangan: m.keterangan
                }));
            }
            if (resPelatihan.status === 'success') {
                window.SIMKABData.pelatihan = resPelatihan.data.map(t => ({
                    id: t.id,
                    idKaryawan: t.id_karyawan,
                    namaPelatihan: t.nama_pelatihan,
                    tanggalSertifikat: t.tanggal_sertifikat,
                    statusSertifikat: t.status_sertifikat,
                    penyelenggara: t.penyelenggara,
                    file_sertifikat: t.file_sertifikat,
                    status_approval: t.status_approval
                }));
            }
            if (resAset.status === 'success') {
                window.SIMKABData.aset = resAset.data.map(a => ({
                    id: a.id,
                    idKaryawan: a.id_karyawan,
                    namaAset: a.nama_aset,
                    kodeAset: a.kode_aset,
                    tanggalPinjam: a.tanggal_pinjam,
                    tanggalKembali: a.tanggal_kembali,
                    status: a.status
                }));
            }
            if (resPengumuman.status === 'success') {
                window.SIMKABData.pengumuman = resPengumuman.data.map(p => ({
                    id: p.id,
                    judul: p.judul,
                    konten: p.konten,
                    kategori: p.kategori,
                    tanggal: p.tanggal,
                    pengirim: p.pengirim
                }));
            }
        } catch (e) {
            console.error("SIMKAB: Kesalahan saat sinkronisasi data dari DB:", e);
        }
    }

    // Inisialisasi Data Awal & UI Grafik
    (async () => {
        await loadStandardJabatanFromDB();
        await refreshAllData();
        updateDashboardStats();
        if (typeof SIMKABCharts !== 'undefined') {
            SIMKABCharts.updateAllCharts();
        }
    })();
    
    // Routing Navigasi Sidebar
    menuItems.forEach(item => {
        item.addEventListener('click', async () => {
            const target = item.getAttribute('data-target');
            
            // Toggle active menu
            menuItems.forEach(mi => mi.classList.remove('active'));
            item.classList.add('active');
            
            // Toggle active panel
            panelSections.forEach(panel => panel.classList.remove('active'));
            const targetPanel = document.getElementById(target);
            if (targetPanel) {
                targetPanel.classList.add('active');
                
                // Update Header
                pageTitle.textContent = item.querySelector('span').textContent;
                pageSubtitle.textContent = pageSubtitles[target] || '';
            }

            // Sync Data & Render Ulang Modul Aktif
            await refreshAllData();
            if (target === 'panel-dashboard') {
                updateDashboardStats();
                if (typeof SIMKABCharts !== 'undefined') {
                    SIMKABCharts.updateAllCharts();
                }
            } else {
                renderModuleData(target);
            }
        });
    });

    // Collapse Sidebar Toggle
    sidebarToggle.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        if (sidebar.classList.contains('collapsed')) {
            toggleIcon.className = 'fa-solid fa-chevron-right';
        } else {
            toggleIcon.className = 'fa-solid fa-chevron-left';
        }
    });

    // Format Rupiah Helper
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(angka);
    }

    // ==========================================================================
    // 3. MASTER REFRESH & RENDER ROUTER
    // ==========================================================================
    
    function renderModuleData(panelId) {
        switch(panelId) {
            case 'panel-karyawan':
                renderKaryawanTable();
                break;
            case 'panel-kinerja':
                renderKinerjaTable();
                populateKaryawanDropdowns();
                break;
            case 'panel-payroll':
                renderPayrollTable();
                populateKaryawanDropdowns();
                break;
            case 'panel-cuti':
                renderCutiTable();
                populateKaryawanDropdowns();
                updateCutiStats();
                break;
            case 'panel-absensi':
                renderAbsensiTable();
                populateKaryawanDropdowns();
                break;
            case 'panel-mutasi':
                renderMutasiTable();
                populateKaryawanDropdowns();
                break;
            case 'panel-pelatihan':
                renderPelatihanTable();
                populateKaryawanDropdowns();
                break;
            case 'panel-aset':
                renderAsetTable();
                populateKaryawanDropdowns();
                break;
            case 'panel-pengumuman':
                renderPengumumanList();
                break;
        }
    }

    // ==========================================================================
    // 4. FEATURE 1: DASHBOARD STATS CALCULATOR
    // ==========================================================================
    
    function updateDashboardStats() {
        const karyawan = SIMKABData.getKaryawan();
        const absensi = SIMKABData.getAbsensi();
        const cuti = SIMKABData.getCuti();
        const pengumuman = SIMKABData.getPengumuman();

        // 1. Total Karyawan Aktif
        const activeKaryawan = karyawan.filter(e => e.status === 'Aktif').length;
        document.getElementById('stat-total-karyawan').textContent = activeKaryawan;

        // 2. Hadir Hari Ini (2026-05-22 sesuai local time mock)
        const todayStr = '2026-05-22';
        const presentToday = absensi.filter(a => a.tanggal === todayStr && a.status === 'Hadir').length;
        document.getElementById('stat-hadir-hari-ini').textContent = presentToday;

        // 3. Karyawan Cuti Aktif (Telah Disetujui)
        const activeCuti = cuti.filter(c => c.status === 'Disetujui').length;
        document.getElementById('stat-karyawan-cuti').textContent = activeCuti;

        // 4. Pengumuman Prioritas Penting
        const importantAnnounce = pengumuman.filter(p => p.kategori === 'Penting').length;
        document.getElementById('stat-pengumuman-baru').textContent = importantAnnounce;
    }

    // ==========================================================================
    // 5. MODAL MANAGEMENT SYSTEM
    // ==========================================================================
    
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
        }
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
        }
    }

    // Assign close event to all modal overlays & close buttons
    document.querySelectorAll('.modal-close, .modal-close-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const openModal = btn.closest('.modal-overlay');
            if (openModal) {
                openModal.classList.remove('active');
            }
        });
    });

    // Populate dropdown karyawan untuk form-form interaksi
    function populateKaryawanDropdowns() {
        const karyawanList = SIMKABData.getKaryawan().filter(e => e.status === 'Aktif');
        const dropdownIds = [
            'kinerja-karyawan', 
            'payroll-karyawan', 
            'cuti-karyawan', 
            'mutasi-karyawan', 
            'pelatihan-karyawan', 
            'aset-karyawan',
            'absensi-karyawan-select'
        ];
        
        dropdownIds.forEach(id => {
            const selectEl = document.getElementById(id);
            if (selectEl) {
                // Kosongkan dulu
                selectEl.innerHTML = '';
                // Tambah default option (kecuali untuk absensi)
                if (id !== 'absensi-karyawan-select') {
                    selectEl.innerHTML = '<option value="">-- Pilih Anggota Karyawan --</option>';
                }
                
                karyawanList.forEach(emp => {
                    const opt = document.createElement('option');
                    opt.value = emp.id;
                    opt.textContent = `${emp.nama} (${emp.nip} - ${emp.jabatan})`;
                    selectEl.appendChild(opt);
                });
            }
        });
    }

    // ==========================================================================
    // 6. FEATURE 2: CRUD DATA KARYAWAN
    // ==========================================================================
    
    const btnTambahKaryawan = document.getElementById('btn-tambah-karyawan');
    const modalKaryawan = document.getElementById('modal-karyawan');
    const formKaryawan = document.getElementById('form-karyawan');
    const inputSearchKaryawan = document.getElementById('karyawan-search');
    const filterDivisiKaryawan = document.getElementById('karyawan-filter-divisi');

    // ==========================================================================
    // STANDAR JABATAN & SKALA GAJI PERBANKAN (DYNAMIC FROM DATABASE MySQL)
    // ==========================================================================
    let JABATAN_SALARY_MATRIX = {};

    async function loadStandardJabatanFromDB() {
        try {
            const res = await fetch('api.php?action=get_standar_jabatan').then(r => r.json());
            if (res.status === 'success' && res.data) {
                JABATAN_SALARY_MATRIX = {};
                res.data.forEach(item => {
                    if (!JABATAN_SALARY_MATRIX[item.divisi]) {
                        JABATAN_SALARY_MATRIX[item.divisi] = {};
                    }
                    JABATAN_SALARY_MATRIX[item.divisi][item.nama_jabatan] = {
                        gaji: parseFloat(item.gaji_pokok),
                        tunjangan: parseFloat(item.tunjangan),
                        grade: item.grade
                    };
                });
                console.log("SIMKAB: Standard Jabatan loaded dynamically from MySQL Database!");
            }
        } catch (e) {
            console.error("SIMKAB: Failed to fetch standar_jabatan from DB:", e);
        }
    }

    const selectDivisi = document.getElementById('karyawan-divisi');
    const selectJabatan = document.getElementById('karyawan-jabatan');
    const inputGaji = document.getElementById('karyawan-gaji');
    const inputTunjangan = document.getElementById('karyawan-tunjangan');

    function populateStandardJabatan(divisiValue, preselectedValue = "") {
        if (!selectJabatan) return;
        selectJabatan.innerHTML = '';
        
        if (!divisiValue || !JABATAN_SALARY_MATRIX[divisiValue]) {
            selectJabatan.innerHTML = '<option value="">Pilih Divisi Terlebih Dahulu</option>';
            selectJabatan.disabled = true;
            if (inputGaji) inputGaji.value = '';
            if (inputTunjangan) inputTunjangan.value = '';
            return;
        }

        selectJabatan.disabled = false;
        selectJabatan.innerHTML = '<option value="">-- Pilih Jabatan Standard --</option>';
        
        const jabatans = JABATAN_SALARY_MATRIX[divisiValue];
        for (const jb in jabatans) {
            const opt = document.createElement('option');
            opt.value = jb;
            opt.textContent = jb;
            selectJabatan.appendChild(opt);
        }

        if (preselectedValue) {
            selectJabatan.value = preselectedValue;
            updateStandardSalaries(divisiValue, preselectedValue);
        }
    }

    function updateStandardSalaries(divisiValue, jabatanValue) {
        if (!inputGaji || !inputTunjangan) return;
        
        if (divisiValue && jabatanValue && JABATAN_SALARY_MATRIX[divisiValue] && JABATAN_SALARY_MATRIX[divisiValue][jabatanValue]) {
            const scale = JABATAN_SALARY_MATRIX[divisiValue][jabatanValue];
            inputGaji.value = scale.gaji;
            inputTunjangan.value = scale.tunjangan;
        } else {
            inputGaji.value = '';
            inputTunjangan.value = '';
        }
    }

    if (selectDivisi) {
        selectDivisi.addEventListener('change', () => {
            populateStandardJabatan(selectDivisi.value);
        });
    }

    if (selectJabatan) {
        selectJabatan.addEventListener('change', () => {
            updateStandardSalaries(selectDivisi.value, selectJabatan.value);
        });
    }

    // Buka Modal Tambah
    if (btnTambahKaryawan) {
        btnTambahKaryawan.addEventListener('click', () => {
            formKaryawan.reset();
            populateStandardJabatan("");
            document.getElementById('karyawan-id').value = '';
            document.getElementById('karyawan-password').value = '';
            document.getElementById('modal-karyawan-title').innerHTML = '<i class="fa-solid fa-user-plus"></i> Tambah Karyawan Baru';
            showModal('modal-karyawan');
        });
    }

    // Render Table Karyawan
    function renderKaryawanTable() {
        const container = document.getElementById('grid-karyawan');
        if (!container) {
            // Isi data Kartu Profil Khusus Karyawan
            const list = SIMKABData.getKaryawan();
            if (list && list.length > 0) {
                const me = list[0]; 
                
                const elNama = document.getElementById('profile-nama');
                const elJabatan = document.getElementById('profile-jabatan');
                const elNip = document.getElementById('profile-nip');
                const elDivisi = document.getElementById('profile-divisi');
                const elTelepon = document.getElementById('profile-telepon');
                const elEmail = document.getElementById('profile-email');
                const elTanggal = document.getElementById('profile-tanggal');
                
                // Dashboard Elements
                const dashNama = document.getElementById('dashboard-nama');
                const dashJabatan = document.getElementById('dashboard-jabatan');
                const dashNip = document.getElementById('dashboard-nip');
                const dashDivisi = document.getElementById('dashboard-divisi');
                const dashTelepon = document.getElementById('dashboard-telepon');
                const dashEmail = document.getElementById('dashboard-email');
                const dashTanggal = document.getElementById('dashboard-bergabung');
                const dashStatus = document.getElementById('dashboard-status');
                const dashGaji = document.getElementById('dashboard-gaji');
                const dashTunjangan = document.getElementById('dashboard-tunjangan');
                const dashCuti = document.getElementById('dashboard-cuti');
                
                if(elNama) elNama.textContent = me.nama;
                if(elJabatan) elJabatan.textContent = me.jabatan;
                if(elNip) elNip.textContent = me.nip;
                if(elDivisi) elDivisi.textContent = me.divisi;
                if(elTelepon) elTelepon.textContent = me.telepon || '-';
                if(elEmail) elEmail.textContent = me.email || '-';
                
                if(dashNama) dashNama.textContent = me.nama;
                if(dashJabatan) dashJabatan.textContent = me.jabatan;
                if(dashNip) dashNip.textContent = me.nip;
                if(dashDivisi) dashDivisi.textContent = me.divisi;
                if(dashTelepon) dashTelepon.textContent = me.telepon || '-';
                if(dashEmail) dashEmail.textContent = me.email || '-';
                if(dashStatus) dashStatus.textContent = me.status || 'Aktif';
                if(dashGaji) dashGaji.textContent = 'Rp ' + (me.gajiPokok ? me.gajiPokok.toLocaleString('id-ID') : '0');
                if(dashTunjangan) dashTunjangan.textContent = 'Rp ' + (me.tunjangan ? me.tunjangan.toLocaleString('id-ID') : '0');
                if(dashCuti) dashCuti.textContent = (me.sisaCuti !== undefined ? me.sisaCuti : 12) + ' Hari';

                let formattedDate = '-';
                if (me.tanggalBergabung) {
                    try {
                        const d = new Date(me.tanggalBergabung);
                        formattedDate = d.toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
                    } catch(e) {
                        formattedDate = me.tanggalBergabung;
                    }
                }
                if(elTanggal) elTanggal.textContent = formattedDate;
                if(dashTanggal) dashTanggal.textContent = formattedDate;
                
                const imgFoto = document.getElementById('img-foto-profil');
                const iconFoto = document.getElementById('icon-foto-profil');
                const dashImgFoto = document.getElementById('dashboard-foto-profil');
                const dashIconFoto = document.getElementById('dashboard-icon-profil');
                
                if (me.foto && me.foto !== '') {
                    const cacheBuster = '?t=' + new Date().getTime();
                    if (imgFoto) {
                        imgFoto.src = me.foto + cacheBuster;
                        imgFoto.style.display = 'block';
                        if (iconFoto) iconFoto.style.display = 'none';
                    }
                    if (dashImgFoto) {
                        dashImgFoto.src = me.foto + cacheBuster;
                        dashImgFoto.style.display = 'block';
                        if (dashIconFoto) dashIconFoto.style.display = 'none';
                    }
                } else {
                    if (imgFoto) imgFoto.style.display = 'none';
                    if (iconFoto) iconFoto.style.display = 'block';
                    if (dashImgFoto) dashImgFoto.style.display = 'none';
                    if (dashIconFoto) dashIconFoto.style.display = 'block';
                }
            }
            return;
        }

        const list = SIMKABData.getKaryawan();
        const searchQuery = inputSearchKaryawan ? inputSearchKaryawan.value.toLowerCase() : '';
        const divisiFilter = filterDivisiKaryawan ? filterDivisiKaryawan.value : '';

        container.innerHTML = '';

        const filtered = list.filter(emp => {
            const matchSearch = emp.nama.toLowerCase().includes(searchQuery) || emp.nip.includes(searchQuery);
            const matchDivisi = divisiFilter === '' || emp.divisi === divisiFilter;
            return matchSearch && matchDivisi;
        });

        if (filtered.length === 0) {
            container.innerHTML = '<div style="grid-column: 1 / -1; text-align:center; padding: 40px; color: var(--text-secondary); background: var(--surface); border-radius: 12px;">Tidak ada data karyawan ditemukan.</div>';
            return;
        }

        filtered.forEach(emp => {
            const card = document.createElement('div');
            card.className = 'employee-card-item';
            
            // Generate initials (2 letters)
            let initials = 'U';
            if (emp.nama) {
                const parts = emp.nama.replace(/[^\w\s]/g, '').trim().split(/\s+/);
                if (parts.length >= 2) {
                    initials = (parts[0][0] + parts[1][0]).toUpperCase();
                } else if (parts.length === 1) {
                    initials = parts[0].substring(0, 2).toUpperCase();
                }
            }
            
            card.innerHTML = `
                <div class="emp-card-header">
                    <div class="emp-avatar">${initials}</div>
                    <span class="badge ${emp.status === 'Aktif' ? 'active' : 'inactive'}" style="position: absolute; top: 15px; right: 15px; font-size: 10px;">${emp.status}</span>
                </div>
                <div class="emp-card-body">
                    <h3 class="emp-name" style="margin-bottom: 4px; color: var(--text-primary); font-size: 16px;">${emp.nama}</h3>
                    <p class="emp-jabatan" style="color: var(--primary-light); font-weight: 600; font-size: 12px; margin-bottom: 15px;">${emp.jabatan}</p>
                    
                    <div class="emp-details" style="display: flex; flex-direction: column; gap: 8px; font-size: 12px; color: var(--text-secondary);">
                        <div style="display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-id-badge" style="width: 14px;"></i> ${emp.nip}</div>
                        <div style="display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-building-user" style="width: 14px;"></i> <span class="badge info" style="font-size: 10px;">${emp.divisi}</span></div>
                        <div style="display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-envelope" style="width: 14px;"></i> <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">${emp.email}</span></div>
                    </div>
                </div>
                <div class="emp-card-footer" style="display: flex; justify-content: center; gap: 10px; padding: 15px; background: rgba(0,0,0,0.1); border-top: 1px solid var(--border-color);">
                    <button class="btn btn-secondary btn-icon btn-detail" data-id="${emp.id}" title="Detail Profil"><i class="fa-solid fa-id-card"></i></button>
                    <button class="btn btn-secondary btn-icon btn-edit" data-id="${emp.id}" title="Edit Data"><i class="fa-solid fa-pen-to-square" style="color:var(--primary-light);"></i></button>
                    <button class="btn btn-danger btn-icon btn-hapus" data-id="${emp.id}" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            `;
            container.appendChild(card);
        });

        // Attach action events
        attachKaryawanActionEvents();
    }

    // Listener inputs filter/search karyawan
    if (inputSearchKaryawan) inputSearchKaryawan.addEventListener('keyup', renderKaryawanTable);
    if (filterDivisiKaryawan) filterDivisiKaryawan.addEventListener('change', renderKaryawanTable);
    
    // Upload Foto Profil Karyawan
    const inputFotoProfil = document.getElementById('input-foto-profil');
    if (inputFotoProfil) {
        inputFotoProfil.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            
            const formData = new FormData();
            formData.append('foto', file);
            
            try {
                const res = await fetch('api.php?action=upload_foto', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());
                
                if (res.status === 'success') {
                    await refreshAllData();
                    renderKaryawanTable(); // re-render to show updated photo
                } else {
                    alert(res.message || 'Gagal mengunggah foto.');
                }
            } catch (err) {
                console.error("Gagal upload foto:", err);
                alert("Terjadi kesalahan saat upload foto.");
            }
        });
    }

    // Save/Update Form Karyawan
    if (formKaryawan) {
        formKaryawan.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const id = document.getElementById('karyawan-id').value;
            const nip = document.getElementById('karyawan-nip').value;
            const nama = document.getElementById('karyawan-nama').value;
            const email = document.getElementById('karyawan-email').value;
            const telepon = document.getElementById('karyawan-telepon').value;
            const divisi = document.getElementById('karyawan-divisi').value;
            const jabatan = document.getElementById('karyawan-jabatan').value;
            const gaji = parseFloat(document.getElementById('karyawan-gaji').value);
            const tunjangan = parseFloat(document.getElementById('karyawan-tunjangan').value);
            const tglBergabung = document.getElementById('karyawan-tglbergabung').value;
            const status = document.getElementById('karyawan-status').value;
            const password = document.getElementById('karyawan-password').value;

            const formData = new FormData();
            if (id) formData.append('id', id);
            formData.append('nip', nip);
            formData.append('nama', nama);
            formData.append('email', email);
            formData.append('telepon', telepon);
            formData.append('divisi', divisi);
            formData.append('jabatan', jabatan);
            formData.append('status', status);
            formData.append('gaji_pokok', gaji);
            formData.append('tunjangan', tunjangan);
            formData.append('tanggal_bergabung', tglBergabung);
            if (password) formData.append('password', password);

            const action = id ? 'edit_karyawan' : 'add_karyawan';
            
            try {
                const res = await fetch(`api.php?action=${action}`, {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    closeModal('modal-karyawan');
                    await refreshAllData();
                    renderKaryawanTable();
                    updateDashboardStats();
                    if (typeof SIMKABCharts !== 'undefined') {
                        SIMKABCharts.updateAllCharts();
                    }
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal menyimpan data karyawan:", err);
                alert("Gagal menghubungi server backend.");
            }
        });
    }

    // Attach Event Klik Aksi
    function attachKaryawanActionEvents() {
        // Detail Profil
        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const emp = SIMKABData.getKaryawan().find(e => e.id === id);
                if (emp) {
                    // Populate detail modal
                    document.getElementById('detail-avatar-text').textContent = emp.nama.slice(0, 2).toUpperCase();
                    document.getElementById('detail-nama').textContent = emp.nama;
                    document.getElementById('detail-nip').textContent = 'NIP: ' + emp.nip;
                    document.getElementById('detail-divisi').textContent = emp.divisi;
                    document.getElementById('detail-jabatan').textContent = emp.jabatan;
                    document.getElementById('detail-email').textContent = emp.email;
                    document.getElementById('detail-telepon').textContent = emp.telepon;
                    document.getElementById('detail-gaji').textContent = formatRupiah(emp.gajiPokok);
                    document.getElementById('detail-tunjangan').textContent = formatRupiah(emp.tunjangan);
                    document.getElementById('detail-bergabung').textContent = emp.tanggalBergabung;
                    document.getElementById('detail-cuti').textContent = emp.sisaCuti + ' Hari';
                    
                    showModal('modal-detail-karyawan');
                }
            });
        });

        // Edit Data
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const emp = SIMKABData.getKaryawan().find(e => e.id === id);
                if (emp) {
                    document.getElementById('karyawan-id').value = emp.id;
                    document.getElementById('karyawan-nip').value = emp.nip;
                    document.getElementById('karyawan-nama').value = emp.nama;
                    document.getElementById('karyawan-email').value = emp.email;
                    document.getElementById('karyawan-telepon').value = emp.telepon;
                    document.getElementById('karyawan-divisi').value = emp.divisi;
                    
                    // Populate standardized jabatan options, then pre-select current one
                    populateStandardJabatan(emp.divisi, emp.jabatan);

                    document.getElementById('karyawan-gaji').value = emp.gajiPokok;
                    document.getElementById('karyawan-tunjangan').value = emp.tunjangan;
                    document.getElementById('karyawan-tglbergabung').value = emp.tanggalBergabung;
                    document.getElementById('karyawan-status').value = emp.status;
                    document.getElementById('karyawan-password').value = emp.password_login || '';

                    document.getElementById('modal-karyawan-title').innerHTML = '<i class="fa-solid fa-pen-to-square"></i> Edit Data Karyawan';
                    showModal('modal-karyawan');
                }
            });
        });

        // Hapus Karyawan
        document.querySelectorAll('.btn-hapus').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                const emp = SIMKABData.getKaryawan().find(e => e.id === id);
                
                if (emp && confirm(`Apakah Anda yakin ingin menghapus data karyawan "${emp.nama}"?`)) {
                    try {
                        const res = await fetch(`api.php?action=delete_karyawan&id=${id}`).then(r => r.json());
                        if (res.status === 'success') {
                            await refreshAllData();
                            renderKaryawanTable();
                            updateDashboardStats();
                            if (typeof SIMKABCharts !== 'undefined') {
                                SIMKABCharts.updateAllCharts();
                            }
                        } else {
                            alert(res.message);
                        }
                    } catch (err) {
                        console.error("Gagal menghapus karyawan:", err);
                    }
                }
            });
        });
    }

    // ==========================================================================
    // 7. FEATURE 3: PENILAIAN KINERJA (KPI)
    // ==========================================================================
    
    const btnTambahKinerja = document.getElementById('btn-tambah-kinerja');
    const formKinerja = document.getElementById('form-kinerja');
    const inputSearchKinerja = document.getElementById('kinerja-search');

    if (btnTambahKinerja) {
        btnTambahKinerja.addEventListener('click', () => {
            formKinerja.reset();
            showModal('modal-kinerja');
        });
    }

    function renderKinerjaTable() {
        const tableBody = document.querySelector('#table-kinerja tbody');
        if (!tableBody) return;

        const list = SIMKABData.getKinerja();
        const karyawanList = SIMKABData.getKaryawan();
        const search = inputSearchKinerja.value.toLowerCase();

        tableBody.innerHTML = '';

        const filtered = list.filter(kpi => {
            const emp = karyawanList.find(e => e.id === kpi.idKaryawan);
            if (!emp) return false;
            return emp.nama.toLowerCase().includes(search) || kpi.periode.toLowerCase().includes(search);
        });

        if (filtered.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center; color: var(--text-secondary);">Tidak ada data penilaian kinerja ditemukan.</td></tr>';
            return;
        }

        filtered.forEach(kpi => {
            const emp = karyawanList.find(e => e.id === kpi.idKaryawan);
            let badgeClass = 'grade-a';
            if (kpi.predikat.includes('B')) badgeClass = 'grade-b';
            else if (kpi.predikat.includes('C')) badgeClass = 'grade-c';
            else if (kpi.predikat.includes('D')) badgeClass = 'grade-d';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div style="font-weight:600; color: var(--text-primary);">${emp ? emp.nama : 'Unknown'}</div>
                    <span style="font-size:11px; color:var(--text-secondary);">${emp ? emp.jabatan : ''}</span>
                </td>
                <td><strong>${kpi.periode}</strong></td>
                <td>${kpi.kedisiplinan}</td>
                <td>${kpi.kerjasama}</td>
                <td>${kpi.inisiatif}</td>
                <td>${kpi.target}</td>
                <td><strong style="color:var(--primary-light);">${kpi.skorAkhir.toFixed(2)}</strong></td>
                <td><span class="kpi-score-badge ${badgeClass}" style="font-size:11px; padding:4px 8px;">${kpi.predikat}</span></td>
                <td style="text-align: center;">
                    <button class="btn btn-secondary btn-icon btn-hapus-kinerja" data-id="${kpi.id}"><i class="fa-solid fa-trash-can" style="color:var(--danger);"></i></button>
                </td>
            `;
            tableBody.appendChild(tr);
        });

        // Event hapus KPI
        document.querySelectorAll('.btn-hapus-kinerja').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                if (confirm('Hapus penilaian kinerja ini?')) {
                    try {
                        const res = await fetch(`api.php?action=delete_kinerja&id=${id}`).then(r => r.json());
                        if (res.status === 'success') {
                            await refreshAllData();
                            renderKinerjaTable();
                        } else {
                            alert(res.message);
                        }
                    } catch (err) {
                        console.error("Gagal menghapus KPI:", err);
                    }
                }
            });
        });
    }

    if (inputSearchKinerja) inputSearchKinerja.addEventListener('keyup', renderKinerjaTable);

    // Save KPI Penilaian
    if (formKinerja) {
        formKinerja.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const idKaryawan = document.getElementById('kinerja-karyawan').value;
            const periode = document.getElementById('kinerja-periode').value;
            const dis = parseFloat(document.getElementById('kinerja-kedisiplinan').value);
            const ker = parseFloat(document.getElementById('kinerja-kerjasama').value);
            const ini = parseFloat(document.getElementById('kinerja-inisiatif').value);
            const tar = parseFloat(document.getElementById('kinerja-target').value);
            const catatan = document.getElementById('kinerja-catatan').value;

            if (!idKaryawan) {
                alert('Silakan pilih karyawan terlebih dahulu.');
                return;
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('periode', periode);
            formData.append('kedisiplinan', dis);
            formData.append('kerjasama', ker);
            formData.append('inisiatif', ini);
            formData.append('target', tar);
            formData.append('catatan', catatan);

            try {
                const res = await fetch('api.php?action=add_kinerja', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    closeModal('modal-kinerja');
                    await refreshAllData();
                    renderKinerjaTable();
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal menyimpan KPI:", err);
            }
        });
    }

    // ==========================================================================
    // 8. FEATURE 4: MANAJEMEN PAYROLL & SLIP PRINTING
    // ==========================================================================
    
    const btnProsesPayroll = document.getElementById('btn-proses-payroll');
    const formPayroll = document.getElementById('form-payroll');
    const inputSearchPayroll = document.getElementById('payroll-search');

    if (btnProsesPayroll) {
        btnProsesPayroll.addEventListener('click', () => {
            formPayroll.reset();
            showModal('modal-payroll');
        });
    }

    function renderPayrollTable() {
        const tableBody = document.querySelector('#table-payroll tbody');
        if (!tableBody) return;

        const list = SIMKABData.getPayroll();
        const karyawanList = SIMKABData.getKaryawan();
        const search = inputSearchPayroll.value.toLowerCase();

        tableBody.innerHTML = '';

        const filtered = list.filter(pay => {
            const emp = karyawanList.find(e => e.id === pay.idKaryawan);
            if (!emp) return false;
            return emp.nama.toLowerCase().includes(search) || pay.bulan.toLowerCase().includes(search);
        });

        if (filtered.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="9" style="text-align:center; color: var(--text-secondary);">Tidak ada rekaman slip penggajian ditemukan.</td></tr>';
            return;
        }

        filtered.forEach(pay => {
            const emp = karyawanList.find(e => e.id === pay.idKaryawan);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div style="font-weight:600; color: var(--text-primary);">${emp ? emp.nama : 'Unknown'}</div>
                    <span style="font-size:11px; color:var(--text-secondary);">${emp ? emp.jabatan : ''}</span>
                </td>
                <td><strong>${pay.bulan}</strong></td>
                <td>${formatRupiah(pay.gajiPokok)}</td>
                <td>${formatRupiah(pay.tunjangan)}</td>
                <td style="color:var(--success);">+${formatRupiah(pay.bonus)}</td>
                <td style="color:var(--danger);">${pay.potongan > 0 ? `-${formatRupiah(pay.potongan)}` : 'Rp0'}</td>
                <td><strong style="color:var(--primary-light);">${formatRupiah(pay.totalGaji)}</strong></td>
                <td><span class="badge active">${pay.status}</span></td>
                <td style="text-align: center;">
                    <div style="display:flex; gap:6px; justify-content:center;">
                        <button class="btn btn-secondary btn-icon btn-lihat-slip" data-id="${pay.id}"><i class="fa-solid fa-file-invoice-dollar" style="color:var(--primary-light);"></i></button>
                        <button class="btn btn-danger btn-icon btn-hapus-payroll" data-id="${pay.id}"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                </td>
            `;
            tableBody.appendChild(tr);
        });

        // Attach Payroll Action events
        attachPayrollActionEvents();
    }

    if (inputSearchPayroll) inputSearchPayroll.addEventListener('keyup', renderPayrollTable);

    // Save Payroll Slip
    if (formPayroll) {
        formPayroll.addEventListener('submit', async (e) => {
            e.preventDefault();

            const idKaryawan = document.getElementById('payroll-karyawan').value;
            const bulan = document.getElementById('payroll-bulan').value;
            const bonus = parseFloat(document.getElementById('payroll-bonus').value) || 0;
            const potongan = parseFloat(document.getElementById('payroll-potongan').value) || 0;

            const emp = SIMKABData.getKaryawan().find(e => e.id === idKaryawan);
            if (!emp) {
                alert('Pilih karyawan valid terlebih dahulu.');
                return;
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('bulan', bulan);
            formData.append('bonus', bonus);
            formData.append('potongan', potongan);

            try {
                const res = await fetch('api.php?action=add_payroll', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    closeModal('modal-payroll');
                    await refreshAllData();
                    renderPayrollTable();
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal memproses payroll:", err);
            }
        });
    }

    function attachPayrollActionEvents() {
        // Hapus Slip
        document.querySelectorAll('.btn-hapus-payroll').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                if (confirm('Apakah Anda yakin ingin menghapus slip gaji ini?')) {
                    try {
                        const res = await fetch(`api.php?action=delete_payroll&id=${id}`).then(r => r.json());
                        if (res.status === 'success') {
                            await refreshAllData();
                            renderPayrollTable();
                        } else {
                            alert(res.message);
                        }
                    } catch (err) {
                        console.error("Gagal menghapus payroll:", err);
                    }
                }
            });
        });

        // Detail Slip Cetak
        document.querySelectorAll('.btn-lihat-slip').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id');
                const pay = SIMKABData.getPayroll().find(p => p.id === id);
                const emp = SIMKABData.getKaryawan().find(e => e.id === pay.idKaryawan);

                if (pay && emp) {
                    document.getElementById('slip-nip').textContent = emp.nip;
                    document.getElementById('slip-nama').textContent = emp.nama;
                    document.getElementById('slip-divisi').textContent = emp.divisi;
                    document.getElementById('slip-jabatan').textContent = emp.jabatan;
                    document.getElementById('slip-bulan').textContent = pay.bulan;
                    
                    document.getElementById('slip-gajipokok').textContent = formatRupiah(pay.gajiPokok);
                    document.getElementById('slip-tunjangan').textContent = formatRupiah(pay.tunjangan);
                    document.getElementById('slip-bonus').textContent = formatRupiah(pay.bonus);
                    document.getElementById('slip-potongan').textContent = formatRupiah(pay.potongan);
                    document.getElementById('slip-total').textContent = formatRupiah(pay.totalGaji);
                    
                    document.getElementById('slip-sign-nama').textContent = emp.nama;
                    document.getElementById('slip-sign-tanggal').textContent = '28 ' + pay.bulan;

                    showModal('modal-salary-slip');
                }
            });
        });
    }

    // Print Action Event
    const btnPrintSlip = document.getElementById('btn-print-slip');
    if (btnPrintSlip) {
        btnPrintSlip.addEventListener('click', () => {
            const printContent = document.getElementById('salary-slip-print-area').innerHTML;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>SIMKAB - Slip Gaji</title>
                    <style>
                        body { font-family: 'Plus Jakarta Sans', Arial, sans-serif; padding: 20px; }
                        .salary-slip-box { border: 2px solid #ddd; padding: 30px; max-width: 800px; margin: 0 auto; color:#000; }
                        .salary-slip-header { text-align: center; border-bottom: 3px double #000; padding-bottom: 15px; margin-bottom: 20px; }
                        .slip-details-grid { display: grid; grid-template-columns: 1fr 1fr; margin-bottom: 30px; font-size:14px; }
                        .slip-details-grid table td { padding: 4px; }
                        .slip-table { width:100%; border-collapse:collapse; margin-bottom:30px; }
                        .slip-table th, .slip-table td { border: 1px solid #ddd; padding: 8px 12px; font-size:13px; }
                        .slip-table th { background-color: #f2f2f2; text-align: left; }
                        .slip-total-row { font-weight:800; background-color: #e6e6e6; }
                        .slip-signatures { display: flex; justify-content: space-between; margin-top: 50px; font-size:14px; }
                        .signature-box { text-align:center; width:200px; }
                        .signature-space { height: 60px; }
                    </style>
                </head>
                <body onload="window.print();window.close();">
                    <div class="salary-slip-box">${printContent}</div>
                </body>
                </html>
            `);
            printWindow.document.close();
        });
    }

    // ==========================================================================
    // 9. FEATURE 5: PENGAJUAN CUTI (LEAVE REQUEST)
    // ==========================================================================
    
    const btnAjukanCuti = document.getElementById('btn-ajukan-cuti');
    const formCuti = document.getElementById('form-cuti');
    const inputSearchCuti = document.getElementById('cuti-search');

    if (btnAjukanCuti) {
        btnAjukanCuti.addEventListener('click', () => {
            formCuti.reset();
            showModal('modal-cuti');
        });
    }

    function updateCutiStats() {
        const cutiList = SIMKABData.getCuti();
        document.getElementById('cnt-cuti-total').textContent = cutiList.length;
        document.getElementById('cnt-cuti-pending').textContent = cutiList.filter(c => c.status === 'Pending').length;
        document.getElementById('cnt-cuti-approved').textContent = cutiList.filter(c => c.status === 'Disetujui').length;
    }

    function renderCutiTable() {
        const tableBody = document.querySelector('#table-cuti tbody');
        if (!tableBody) return;

        const list = SIMKABData.getCuti();
        const karyawanList = SIMKABData.getKaryawan();
        const search = inputSearchCuti.value.toLowerCase();

        tableBody.innerHTML = '';

        const filtered = list.filter(lv => {
            const emp = karyawanList.find(e => e.id === lv.idKaryawan);
            if (!emp) return false;
            return emp.nama.toLowerCase().includes(search) || lv.jenisCuti.toLowerCase().includes(search);
        });

        if (filtered.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center; color: var(--text-secondary);">Tidak ada pengajuan cuti ditemukan.</td></tr>';
            return;
        }

        filtered.forEach(lv => {
            const emp = karyawanList.find(e => e.id === lv.idKaryawan);
            
            let statusBadge = 'pending';
            if (lv.status === 'Disetujui') statusBadge = 'approved';
            else if (lv.status === 'Ditolak') statusBadge = 'rejected';

            const showActions = lv.status === 'Pending';

            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div style="font-weight:600; color: var(--text-primary);">${emp ? emp.nama : 'Unknown'}</div>
                    <span style="font-size:11px; color:var(--text-secondary);">Sisa Cuti: ${emp ? emp.sisaCuti : 0} Hari</span>
                </td>
                <td><span class="badge info">${lv.jenisCuti}</span></td>
                <td>${lv.tanggalMulai}</td>
                <td>${lv.tanggalSelesai}</td>
                <td style="font-size:13px; max-width: 200px;">${lv.alasan}</td>
                <td><span class="badge ${statusBadge}">${lv.status}</span></td>
                <td style="text-align: center;">
                    ${showActions ? `
                        <div style="display:flex; gap:6px; justify-content:center;">
                            <button class="btn btn-success btn-icon btn-approve-cuti" data-id="${lv.id}"><i class="fa-solid fa-check"></i></button>
                            <button class="btn btn-danger btn-icon btn-reject-cuti" data-id="${lv.id}"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    ` : `<span style="font-size:12px; color:var(--text-muted);">Diproses</span>`}
                </td>
            `;
            tableBody.appendChild(tr);
        });

        attachCutiActionEvents();
    }

    if (inputSearchCuti) inputSearchCuti.addEventListener('keyup', renderCutiTable);

    // Save Leave Request
    if (formCuti) {
        formCuti.addEventListener('submit', async (e) => {
            e.preventDefault();

            const idKaryawan = document.getElementById('cuti-karyawan').value;
            const jenisCuti = document.getElementById('cuti-jenis').value;
            const tglMulai = document.getElementById('cuti-tgl-mulai').value;
            const tglSelesai = document.getElementById('cuti-tgl-selesai').value;
            const alasan = document.getElementById('cuti-alasan').value;

            if (!idKaryawan) {
                alert('Pilih karyawan pengaju terlebih dahulu.');
                return;
            }

            const emp = SIMKABData.getKaryawan().find(e => e.id === idKaryawan);
            if (jenisCuti === 'Cuti Tahunan' && emp.sisaCuti <= 0) {
                alert('Maaf, sisa kuota cuti tahunan karyawan ini sudah habis (0).');
                return;
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('jenis_cuti', jenisCuti);
            formData.append('tanggal_mulai', tglMulai);
            formData.append('tanggal_selesai', tglSelesai);
            formData.append('alasan', alasan);

            try {
                const res = await fetch('api.php?action=add_cuti', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    closeModal('modal-cuti');
                    await refreshAllData();
                    renderCutiTable();
                    updateCutiStats();
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal mengirim pengajuan cuti:", err);
            }
        });
    }

    function attachCutiActionEvents() {
        // Approve Cuti
        document.querySelectorAll('.btn-approve-cuti').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                try {
                    const res = await fetch(`api.php?action=approve_cuti&id=${id}`).then(r => r.json());
                    if (res.status === 'success') {
                        await refreshAllData();
                        renderCutiTable();
                        updateCutiStats();
                        updateDashboardStats();
                    } else {
                        alert(res.message);
                    }
                } catch (err) {
                    console.error("Gagal approve cuti:", err);
                }
            });
        });

        // Reject Cuti
        document.querySelectorAll('.btn-reject-cuti').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                try {
                    const res = await fetch(`api.php?action=reject_cuti&id=${id}`).then(r => r.json());
                    if (res.status === 'success') {
                        await refreshAllData();
                        renderCutiTable();
                        updateCutiStats();
                    } else {
                        alert(res.message);
                    }
                } catch (err) {
                    console.error("Gagal reject cuti:", err);
                }
            });
        });
    }

    // ==========================================================================
    // 10. FEATURE 6: LIVE ATTENDANCE (ABSENSI KARYAWAN)
    // ==========================================================================
    
    const liveClock = document.getElementById('live-clock');
    const liveDate = document.getElementById('live-date');
    const btnCheckIn = document.getElementById('btn-check-in');
    const btnCheckOut = document.getElementById('btn-check-out');
    const selectAbsenKaryawan = document.getElementById('absensi-karyawan-select');

    // Live Digital Clock Timer
    function updateClock() {
        if (!liveClock) return;
        const now = new Date();
        
        // Time format
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        liveClock.textContent = `${hours}:${minutes}:${seconds}`;

        // Date format Indonesian
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        liveDate.textContent = now.toLocaleDateString('id-ID', options);
    }
    
    setInterval(updateClock, 1000);
    updateClock();

    function renderAbsensiTable() {
        const tableBody = document.querySelector('#table-absensi tbody');
        if (!tableBody) return;

        const list = SIMKABData.getAbsensi();
        const karyawanList = SIMKABData.getKaryawan();

        tableBody.innerHTML = '';

        // Tampilkan 15 logs absensi terbaru
        const latestLogs = [...list].reverse().slice(0, 15);

        if (latestLogs.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" style="text-align:center; color: var(--text-secondary);">Tidak ada riwayat absensi.</td></tr>';
            return;
        }

        latestLogs.forEach(log => {
            const emp = karyawanList.find(e => e.id === log.idKaryawan);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div style="font-weight:600; color: var(--text-primary);">${emp ? emp.nama : 'Unknown'}</div>
                    <span style="font-size:11px; color:var(--text-secondary);">${emp ? emp.jabatan : ''}</span>
                </td>
                <td><strong>${log.tanggal}</strong></td>
                <td style="color:var(--success); font-weight:600;">${log.jamMasuk || '-'}</td>
                <td style="color:var(--text-muted);">${log.jamKeluar || '-'}</td>
                <td><span class="badge ${log.status === 'Hadir' ? 'active' : 'inactive'}">${log.status}</span></td>
            `;
            tableBody.appendChild(tr);
        });
    }

    // Geofencing Variables
    let currentLocation = null;
    let isWithinRadius = false;
    const btnGetLocation = document.getElementById('btn-get-location');
    const gpsStatus = document.getElementById('gps-status');
    const gpsDistance = document.getElementById('gps-distance');
    
    // Koordinat Kantor (Berdasarkan link Maps: Fakultas Teknik UHO)
    const OFFICE_LAT = -4.0113509;
    const OFFICE_LNG = 122.5177928;
    const MAX_RADIUS_METERS = 500; // Radius toleransi 500 meter

    // Fungsi Haversine Formula untuk menghitung jarak dalam meter
    function getDistanceFromLatLonInM(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Radius bumi dalam meter
        const dLat = (lat2 - lat1) * (Math.PI / 180);
        const dLon = (lon2 - lon1) * (Math.PI / 180);
        const a = 
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) * 
            Math.sin(dLon / 2) * Math.sin(dLon / 2); 
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)); 
        const d = R * c;
        return d;
    }

    if (btnGetLocation) {
        btnGetLocation.addEventListener('click', () => {
            if (navigator.geolocation) {
                gpsStatus.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memindai satelit...';
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const userLat = pos.coords.latitude;
                        const userLng = pos.coords.longitude;
                        currentLocation = `${userLat},${userLng}`;
                        
                        const distance = getDistanceFromLatLonInM(OFFICE_LAT, OFFICE_LNG, userLat, userLng);
                        gpsDistance.textContent = `Jarak dari kantor: ${distance.toFixed(0)} meter`;

                        if (distance <= MAX_RADIUS_METERS) {
                            isWithinRadius = true;
                            gpsStatus.innerHTML = `<i class="fa-solid fa-location-dot"></i> Dalam Jangkauan`;
                            gpsStatus.style.color = 'var(--success)';
                        } else {
                            isWithinRadius = false;
                            gpsStatus.innerHTML = `<i class="fa-solid fa-triangle-exclamation"></i> Di Luar Jangkauan Kantor!`;
                            gpsStatus.style.color = 'var(--danger)';
                        }
                    },
                    (err) => {
                        gpsStatus.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Gagal akses GPS. Pastikan izin aktif.';
                        gpsStatus.style.color = 'var(--danger)';
                    },
                    { enableHighAccuracy: true }
                );
            } else {
                gpsStatus.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Browser tidak support GPS.';
            }
        });
    }

    // Check-In Action
    if (btnCheckIn) {
        btnCheckIn.addEventListener('click', async () => {
            const idKaryawan = selectAbsenKaryawan.value;
            if (!idKaryawan) {
                alert('Pilih karyawan absensi terlebih dahulu.');
                return;
            }

            // Validasi Geofencing
            if (window.CURRENT_USER !== 'akun.demo') {
                if (!currentLocation) {
                    alert('Peringatan: Anda belum memindai lokasi (Klik tombol Pindai Lokasi)!');
                    return;
                }
                if (!isWithinRadius) {
                    alert('Akses Ditolak: Anda berada di luar radius kantor yang diizinkan!');
                    return;
                }
            } else {
                if (!currentLocation) currentLocation = OFFICE_LAT + ',' + OFFICE_LNG; // Bypass GPS untuk Demo
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('lokasi', currentLocation);

            try {
                const res = await fetch('api.php?action=check_in', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    await refreshAllData();
                    renderAbsensiTable();
                    updateDashboardStats();
                    if (typeof SIMKABCharts !== 'undefined') {
                        SIMKABCharts.updateAllCharts();
                    }
                    alert("Berhasil Check In!\nKoordinat lokasi Anda valid.");
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal check-in:", err);
            }
        });
    }

    // Check-Out Action
    if (btnCheckOut) {
        btnCheckOut.addEventListener('click', async () => {
            const idKaryawan = selectAbsenKaryawan.value;
            if (!idKaryawan) {
                alert('Pilih karyawan absensi terlebih dahulu.');
                return;
            }

            // Validasi Geofencing
            if (window.CURRENT_USER !== 'akun.demo') {
                if (!currentLocation) {
                    alert('Peringatan: Anda belum memindai lokasi (Klik tombol Pindai Lokasi)!');
                    return;
                }
                if (!isWithinRadius) {
                    alert('Akses Ditolak: Anda berada di luar radius kantor yang diizinkan!');
                    return;
                }
            } else {
                if (!currentLocation) currentLocation = OFFICE_LAT + ',' + OFFICE_LNG; // Bypass GPS untuk Demo
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('lokasi', currentLocation);

            try {
                const res = await fetch('api.php?action=check_out', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    await refreshAllData();
                    renderAbsensiTable();
                    alert("Berhasil Check Out!\nKoordinat lokasi kepulangan direkam.");
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal check-out:", err);
            }
        });
    }

    // Pengajuan Izin/Sakit Action
    const btnAjukanIzin = document.getElementById('btn-ajukan-izin');
    const formIzin = document.getElementById('form-izin');
    
    if (btnAjukanIzin) {
        btnAjukanIzin.addEventListener('click', () => {
            const idKaryawan = selectAbsenKaryawan.value;
            if (!idKaryawan) {
                alert('Pilih karyawan absensi terlebih dahulu di panel absen.');
                return;
            }
            formIzin.reset();
            showModal('modal-izin');
        });
    }

    if (formIzin) {
        formIzin.addEventListener('submit', async (e) => {
            e.preventDefault();
            const idKaryawan = selectAbsenKaryawan.value;
            const jenis = document.getElementById('izin-jenis').value;
            const keterangan = document.getElementById('izin-keterangan').value;
            const fileInput = document.getElementById('izin-file');
            
            let fotoBase64 = null;
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const reader = new FileReader();
                fotoBase64 = await new Promise((resolve) => {
                    reader.onloadend = () => resolve(reader.result);
                    reader.readAsDataURL(file);
                });
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('jenis', jenis);
            formData.append('keterangan', keterangan);
            if (fotoBase64) formData.append('foto', fotoBase64);

            try {
                const res = await fetch('api.php?action=izin_sakit', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    await refreshAllData();
                    renderAbsensiTable();
                    closeModal('modal-izin');
                    alert(`Pengajuan ${jenis} berhasil dikirim!`);
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal mengirim pengajuan:", err);
            }
        });
    }

    // ==========================================================================
    // 11. FEATURE 7: MUTASI, ROTASI & PROMOSI
    // ==========================================================================
    
    const selectDivisiMutasi = document.getElementById('mutasi-divisi-baru');
    const selectJabatanMutasi = document.getElementById('mutasi-jabatan-baru');
    const btnMutasiKaryawan = document.getElementById('btn-mutasikan-karyawan');
    const formMutasi = document.getElementById('form-mutasi');
    const inputSearchMutasi = document.getElementById('mutasi-search');

    function populateMutasiJabatan(divisiValue, preselectedValue = "") {
        if (!selectJabatanMutasi) return;
        selectJabatanMutasi.innerHTML = '';
        
        if (!divisiValue || !JABATAN_SALARY_MATRIX[divisiValue]) {
            selectJabatanMutasi.innerHTML = '<option value="">Pilih Divisi Terlebih Dahulu</option>';
            selectJabatanMutasi.disabled = true;
            return;
        }

        selectJabatanMutasi.disabled = false;
        selectJabatanMutasi.innerHTML = '<option value="">-- Pilih Jabatan Baru Standard --</option>';
        
        const jabatans = JABATAN_SALARY_MATRIX[divisiValue];
        for (const jb in jabatans) {
            const opt = document.createElement('option');
            opt.value = jb;
            opt.textContent = jb;
            selectJabatanMutasi.appendChild(opt);
        }

        if (preselectedValue) {
            selectJabatanMutasi.value = preselectedValue;
        }
    }

    if (selectDivisiMutasi) {
        selectDivisiMutasi.addEventListener('change', () => {
            populateMutasiJabatan(selectDivisiMutasi.value);
        });
    }

    if (btnMutasiKaryawan) {
        btnMutasiKaryawan.addEventListener('click', () => {
            formMutasi.reset();
            populateMutasiJabatan("");
            showModal('modal-mutasi');
        });
    }

    function renderMutasiTable() {
        const tableBody = document.querySelector('#table-mutasi tbody');
        if (!tableBody) return;

        const list = SIMKABData.getMutasi();
        const karyawanList = SIMKABData.getKaryawan();
        const search = inputSearchMutasi.value.toLowerCase();

        tableBody.innerHTML = '';

        const filtered = list.filter(m => {
            const emp = karyawanList.find(e => e.id === m.idKaryawan);
            if (!emp) return false;
            return emp.nama.toLowerCase().includes(search) || m.jenis.toLowerCase().includes(search);
        });

        if (filtered.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center; color: var(--text-secondary);">Tidak ada riwayat mutasi/promosi karyawan ditemukan.</td></tr>';
            return;
        }

        filtered.forEach(m => {
            const emp = karyawanList.find(e => e.id === m.idKaryawan);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div style="font-weight:600; color: var(--text-primary);">${emp ? emp.nama : 'Unknown'}</div>
                    <span style="font-size:11px; color:var(--text-secondary);">${emp ? emp.nip : ''}</span>
                </td>
                <td><span class="badge ${m.jenis === 'Promosi' ? 'active' : 'info'}">${m.jenis}</span></td>
                <td>
                    <div style="font-size:12px; color:var(--text-secondary); text-decoration: line-through;">${m.divisiLama}</div>
                    <div style="font-weight:600; color:var(--success);">&rarr; ${m.divisiBaru}</div>
                </td>
                <td>
                    <div style="font-size:12px; color:var(--text-secondary); text-decoration: line-through;">${m.jabatanLama}</div>
                    <div style="font-weight:600; color:var(--success);">&rarr; ${m.jabatanBaru}</div>
                </td>
                <td><strong>${m.tanggal}</strong></td>
                <td style="font-size:13px; max-width:250px;">${m.keterangan}</td>
            `;
            tableBody.appendChild(tr);
        });
    }

    if (inputSearchMutasi) inputSearchMutasi.addEventListener('keyup', renderMutasiTable);

    // Save Mutation
    if (formMutasi) {
        formMutasi.addEventListener('submit', async (e) => {
            e.preventDefault();

            const idKaryawan = document.getElementById('mutasi-karyawan').value;
            const jenis = document.getElementById('mutasi-jenis').value;
            const tgl = document.getElementById('mutasi-tanggal').value;
            const divBaru = document.getElementById('mutasi-divisi-baru').value;
            const jabBaru = document.getElementById('mutasi-jabatan-baru').value;
            const ket = document.getElementById('mutasi-keterangan').value;

            if (!idKaryawan) {
                alert('Pilih karyawan valid terlebih dahulu.');
                return;
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('jenis', jenis);
            formData.append('tanggal', tgl);
            formData.append('divisi_baru', divBaru);
            formData.append('jabatan_baru', jabBaru);
            formData.append('keterangan', ket);

            try {
                const res = await fetch('api.php?action=add_mutasi', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    closeModal('modal-mutasi');
                    await refreshAllData();
                    renderMutasiTable();
                    updateDashboardStats();
                    if (typeof SIMKABCharts !== 'undefined') {
                        SIMKABCharts.updateAllCharts();
                    }
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal memutasikan karyawan:", err);
            }
        });
    }

    // ==========================================================================
    // 12. FEATURE 8: PORTAL PELATIHAN & SERTIFIKASI
    // ==========================================================================
    
    const btnTambahPelatihan = document.getElementById('btn-tambah-pelatihan');
    const formPelatihan = document.getElementById('form-pelatihan');
    const inputSearchPelatihan = document.getElementById('pelatihan-search');

    if (btnTambahPelatihan) {
        btnTambahPelatihan.addEventListener('click', () => {
            formPelatihan.reset();
            
            // Sembunyikan field Pilih Karyawan jika role adalah karyawan
            if (window.userRole === 'karyawan') {
                const groupKaryawan = document.getElementById('group-pelatihan-karyawan');
                if (groupKaryawan) groupKaryawan.style.display = 'none';
                
                // Set default value for select if exist
                const selectKaryawan = document.getElementById('pelatihan-karyawan');
                const listKaryawan = window.SIMKABData.getKaryawan ? window.SIMKABData.getKaryawan() : [];
                if (selectKaryawan && listKaryawan.length > 0) {
                    selectKaryawan.value = listKaryawan[0].id;
                }
            }
            
            showModal('modal-pelatihan');
        });
    }

    function renderPelatihanTable() {
        const tableBody = document.querySelector('#table-pelatihan tbody');
        if (!tableBody) return;

        const list = SIMKABData.getPelatihan();
        const karyawanList = SIMKABData.getKaryawan();
        const search = inputSearchPelatihan.value.toLowerCase();

        tableBody.innerHTML = '';

        const filtered = list.filter(t => {
            const emp = karyawanList.find(e => e.id === t.idKaryawan);
            if (!emp) return false;
            return emp.nama.toLowerCase().includes(search) || t.namaPelatihan.toLowerCase().includes(search);
        });

        if (filtered.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" style="text-align:center; color: var(--text-secondary);">Tidak ada riwayat pelatihan karyawan ditemukan.</td></tr>';
            return;
        }

        filtered.forEach(t => {
            const emp = karyawanList.find(e => e.id === t.idKaryawan);
            
            let statusBadge = 'active';
            if (t.statusSertifikat === 'Kedaluwarsa') statusBadge = 'inactive';
            else if (t.statusSertifikat === 'Sedang Berjalan') statusBadge = 'pending';

            let buktiHtml = '-';
            if (t.file_sertifikat) {
                buktiHtml = `<a href="${t.file_sertifikat}" target="_blank" class="btn btn-primary" style="padding: 4px 10px; font-size: 11px;"><i class="fa-solid fa-file-contract"></i> Lihat</a>`;
            }
            
            let approvalBadge = '';
            if (t.status_approval === 'Pending') {
                approvalBadge = '<span class="badge pending" style="margin-left: 8px;"><i class="fa-solid fa-clock"></i> Menunggu Verifikasi</span>';
            } else if (t.status_approval === 'Approved') {
                approvalBadge = '<span class="badge active" style="margin-left: 8px;"><i class="fa-solid fa-check"></i> Terverifikasi</span>';
            } else if (t.status_approval === 'Rejected') {
                approvalBadge = '<span class="badge inactive" style="margin-left: 8px;"><i class="fa-solid fa-xmark"></i> Ditolak</span>';
            }

            let tr = document.createElement('tr');
            
            let actionHtml = '';
            if (window.userRole !== 'karyawan') {
                let approvalButtons = '';
                if (t.status_approval === 'Pending') {
                    approvalButtons = `
                        <button class="btn btn-primary btn-icon btn-approve-pelatihan" data-id="${t.id}" title="Setujui"><i class="fa-solid fa-check"></i></button>
                        <button class="btn btn-danger btn-icon btn-reject-pelatihan" data-id="${t.id}" title="Tolak"><i class="fa-solid fa-xmark"></i></button>
                    `;
                }
                actionHtml = `<td style="text-align: center; white-space: nowrap;">
                    ${approvalButtons}
                    <button class="btn btn-secondary btn-icon btn-hapus-pelatihan" data-id="${t.id}" title="Hapus"><i class="fa-solid fa-trash-can"></i></button>
                </td>`;
            }

            tr.innerHTML = `
                <td>
                    <div style="font-weight: 600; color: var(--text-primary);">${emp ? emp.nama : t.idKaryawan}</div>
                    <div style="font-size: 11px; color: var(--text-muted);">${emp ? emp.divisi : ''}</div>
                </td>
                <td>
                    <strong>${t.namaPelatihan}</strong><br>
                    ${approvalBadge}
                </td>
                <td>${t.penyelenggara}</td>
                <td>${t.tanggalSertifikat}</td>
                <td><span class="badge ${statusBadge}">${t.statusSertifikat}</span></td>
                <td style="text-align: center;">${buktiHtml}</td>
                ${actionHtml}
            `;
            tableBody.appendChild(tr);
        });

        // Event hapus pelatihan
        document.querySelectorAll('.btn-hapus-pelatihan').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                if (confirm('Hapus pencatatan pelatihan ini?')) {
                    try {
                        const res = await fetch(`api.php?action=delete_pelatihan&id=${id}`).then(r => r.json());
                        if (res.status === 'success') {
                            await refreshSIMKABData();
                            renderPelatihanTable();
                        } else {
                            alert(res.message);
                        }
                    } catch (err) {
                        console.error("Gagal menghapus pelatihan:", err);
                    }
                }
            });
        });

        // Event Approve Pelatihan
        document.querySelectorAll('.btn-approve-pelatihan').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                if (confirm('Setujui sertifikat ini?')) {
                    try {
                        const res = await fetch(`api.php?action=approve_pelatihan&id=${id}`).then(r => r.json());
                        if (res.status === 'success') {
                            await refreshSIMKABData();
                            renderPelatihanTable();
                        } else alert(res.message);
                    } catch(err) { console.error(err); }
                }
            });
        });

        // Event Reject Pelatihan
        document.querySelectorAll('.btn-reject-pelatihan').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = e.currentTarget.getAttribute('data-id');
                if (confirm('Tolak sertifikat ini?')) {
                    try {
                        const res = await fetch(`api.php?action=reject_pelatihan&id=${id}`).then(r => r.json());
                        if (res.status === 'success') {
                            await refreshSIMKABData();
                            renderPelatihanTable();
                        } else alert(res.message);
                    } catch(err) { console.error(err); }
                }
            });
        });
    }

    if (inputSearchPelatihan) inputSearchPelatihan.addEventListener('keyup', renderPelatihanTable);

    // Save Training Log
    if (formPelatihan) {
        formPelatihan.addEventListener('submit', async (e) => {
            e.preventDefault();

            const idKaryawan = document.getElementById('pelatihan-karyawan') ? document.getElementById('pelatihan-karyawan').value : '';
            const nama = document.getElementById('pelatihan-nama').value;
            const pen = document.getElementById('pelatihan-penyelenggara').value;
            const tgl = document.getElementById('pelatihan-tanggal').value;
            const status = document.getElementById('pelatihan-status').value;
            const fileInput = document.getElementById('pelatihan-file');

            if (window.userRole !== 'karyawan' && !idKaryawan) {
                alert("Pilih karyawan!");
                return;
            }
            if (!nama || !pen || !tgl || !status) {
                alert("Lengkapi formulir pelatihan!");
                return;
            }
            
            // Check file size (2MB max)
            if (fileInput && fileInput.files.length > 0) {
                const fileSize = fileInput.files[0].size / 1024 / 1024; // MB
                if (fileSize > 2) {
                    alert("Ukuran file sertifikat maksimal 2MB!");
                    return;
                }
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('nama_pelatihan', nama);
            formData.append('penyelenggara', pen);
            formData.append('tanggal_sertifikat', tgl);
            formData.append('status_sertifikat', status);
            
            if (fileInput && fileInput.files.length > 0) {
                formData.append('file_sertifikat', fileInput.files[0]);
            }

            try {
                const res = await fetch('api.php?action=add_pelatihan', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    closeModal('modal-pelatihan');
                    formPelatihan.reset();
                    // Refetch data
                    await refreshSIMKABData();
                    renderPelatihanTable();
                    alert(res.message);
                } else {
                    alert("Gagal menyimpan pelatihan: " + res.message);
                }
            } catch(err) {
                console.error("Gagal menyimpan pelatihan:", err);
                alert("Terjadi kesalahan jaringan.");
            }
        });
    }

    // ==========================================================================
    // 13. FEATURE 9: INVENTARIS / ASET KANTOR
    // ==========================================================================
    
    const btnTambahAset = document.getElementById('btn-tambah-aset');
    const formAset = document.getElementById('form-aset');
    const inputSearchAset = document.getElementById('aset-search');

    if (btnTambahAset) {
        btnTambahAset.addEventListener('click', () => {
            formAset.reset();
            showModal('modal-aset');
        });
    }

    function renderAsetTable() {
        const tableBody = document.querySelector('#table-aset tbody');
        if (!tableBody) return;

        const list = SIMKABData.getAset();
        const karyawanList = SIMKABData.getKaryawan();
        const search = inputSearchAset.value.toLowerCase();

        tableBody.innerHTML = '';

        const filtered = list.filter(a => {
            const emp = karyawanList.find(e => e.id === a.idKaryawan);
            if (!emp) return false;
            return emp.nama.toLowerCase().includes(search) || a.namaAset.toLowerCase().includes(search) || a.kodeAset.toLowerCase().includes(search);
        });

        if (filtered.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="7" style="text-align:center; color: var(--text-secondary);">Tidak ada data peminjaman aset kantor ditemukan.</td></tr>';
            return;
        }

        filtered.forEach(a => {
            const emp = karyawanList.find(e => e.id === a.idKaryawan);
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>
                    <div style="font-weight:600; color: var(--text-primary);">${emp ? emp.nama : 'Unknown'}</div>
                    <span style="font-size:11px; color:var(--text-secondary);">${emp ? emp.jabatan : ''}</span>
                </td>
                <td><strong>${a.namaAset}</strong></td>
                <td><code>${a.kodeAset}</code></td>
                <td>${a.tanggalPinjam}</td>
                <td>${a.tanggalKembali || `<span style="font-size:12px; color:var(--warning);">Masih Dipakai</span>`}</td>
                <td><span class="badge ${a.status === 'Dipinjam' ? 'pending' : 'approved'}">${a.status}</span></td>
                <td style="text-align: center;">
                    ${a.status === 'Dipinjam' ? `
                        <button class="btn btn-secondary btn-kembalikan-aset" data-id="${a.id}"><i class="fa-solid fa-rotate-left"></i> Kembalikan</button>
                    ` : `<span style="font-size:12px; color:var(--text-muted);">Selesai</span>`}
                </td>
            `;
            tableBody.appendChild(tr);
        });

        // Event kembalikan aset
        document.querySelectorAll('.btn-kembalikan-aset').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                try {
                    const res = await fetch(`api.php?action=kembalikan_aset&id=${id}`).then(r => r.json());
                    if (res.status === 'success') {
                        await refreshAllData();
                        renderAsetTable();
                    } else {
                        alert(res.message);
                    }
                } catch (err) {
                    console.error("Gagal mengembalikan aset:", err);
                }
            });
        });
    }

    if (inputSearchAset) inputSearchAset.addEventListener('keyup', renderAsetTable);

    // Save Asset Loan
    if (formAset) {
        formAset.addEventListener('submit', async (e) => {
            e.preventDefault();

            const idKaryawan = document.getElementById('aset-karyawan').value;
            const nama = document.getElementById('aset-nama').value;
            const kode = document.getElementById('aset-kode').value;
            const tgl = document.getElementById('aset-tgl-pinjam').value;

            if (!idKaryawan) {
                alert('Pilih karyawan peminjam valid.');
                return;
            }

            const formData = new FormData();
            formData.append('id_karyawan', idKaryawan);
            formData.append('nama_aset', nama);
            formData.append('kode_aset', kode);
            formData.append('tanggal_pinjam', tgl);

            try {
                const res = await fetch('api.php?action=add_aset', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    closeModal('modal-aset');
                    await refreshAllData();
                    renderAsetTable();
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal meminjamkan aset:", err);
            }
        });
    }

    // ==========================================================================
    // 14. FEATURE 10: PORTAL PENGUMUMAN (BULLETIN BOARD)
    // ==========================================================================
    
    const btnTambahPengumuman = document.getElementById('btn-tambah-pengumuman');
    const formPengumuman = document.getElementById('form-pengumuman');
    const inputSearchPengumuman = document.getElementById('pengumuman-search');

    if (btnTambahPengumuman) {
        btnTambahPengumuman.addEventListener('click', () => {
            formPengumuman.reset();
            showModal('modal-pengumuman');
        });
    }

    function renderPengumumanList() {
        const container = document.getElementById('container-pengumuman');
        if (!container) return;

        const list = SIMKABData.getPengumuman();
        const search = inputSearchPengumuman.value.toLowerCase();

        container.innerHTML = '';

        // Tampilkan pengumuman terbaru di atas
        const latestAnnouncements = [...list].reverse();

        const filtered = latestAnnouncements.filter(p => {
            return p.judul.toLowerCase().includes(search) || p.konten.toLowerCase().includes(search) || p.kategori.toLowerCase().includes(search);
        });

        if (filtered.length === 0) {
            container.innerHTML = '<div class="card" style="text-align:center; color: var(--text-secondary); padding:40px;">Tidak ada memo pengumuman internal bank saat ini.</div>';
            return;
        }

        filtered.forEach(p => {
            let catBadgeClass = 'info';
            if (p.kategori === 'Penting') catBadgeClass = 'inactive'; // Red badge style
            else if (p.kategori === 'Umum') catBadgeClass = 'pending'; // Gold badge style

            const div = document.createElement('div');
            div.className = 'announcement-card';
            div.innerHTML = `
                <div class="announcement-header">
                    <div class="announcement-meta">
                        <span><i class="fa-solid fa-user-shield"></i> ${p.pengirim}</span>
                        <span><i class="fa-solid fa-calendar-days"></i> ${p.tanggal}</span>
                    </div>
                    <span class="badge ${catBadgeClass}">${p.kategori}</span>
                </div>
                <h3 class="announcement-title">${p.judul}</h3>
                <p class="announcement-body">${p.konten}</p>
                <div style="display:flex; justify-content:flex-end;">
                    <button class="btn btn-danger btn-icon btn-hapus-pengumuman" data-id="${p.id}"><i class="fa-solid fa-trash-can"></i></button>
                </div>
            `;
            container.appendChild(div);
        });

        // Event hapus pengumuman
        document.querySelectorAll('.btn-hapus-pengumuman').forEach(btn => {
            btn.addEventListener('click', async () => {
                const id = btn.getAttribute('data-id');
                if (confirm('Hapus postingan memo pengumuman ini?')) {
                    try {
                        const res = await fetch(`api.php?action=delete_pengumuman&id=${id}`).then(r => r.json());
                        if (res.status === 'success') {
                            await refreshAllData();
                            renderPengumumanList();
                            updateDashboardStats();
                        } else {
                            alert(res.message);
                        }
                    } catch (err) {
                        console.error("Gagal menghapus pengumuman:", err);
                    }
                }
            });
        });
    }

    if (inputSearchPengumuman) inputSearchPengumuman.addEventListener('keyup', renderPengumumanList);

    // Save Announcement
    if (formPengumuman) {
        formPengumuman.addEventListener('submit', async (e) => {
            e.preventDefault();

            const judul = document.getElementById('pengumuman-judul').value;
            const kategori = document.getElementById('pengumuman-kategori').value;
            const pengirim = document.getElementById('pengumuman-pengirim').value;
            const konten = document.getElementById('pengumuman-konten').value;

            const formData = new FormData();
            formData.append('judul', judul);
            formData.append('kategori', kategori);
            formData.append('pengirim', pengirim);
            formData.append('konten', konten);

            try {
                const res = await fetch('api.php?action=add_pengumuman', {
                    method: 'POST',
                    body: formData
                }).then(r => r.json());

                if (res.status === 'success') {
                    closeModal('modal-pengumuman');
                    await refreshAllData();
                    renderPengumumanList();
                    updateDashboardStats();
                } else {
                    alert(res.message);
                }
            } catch (err) {
                console.error("Gagal menyimpan pengumuman:", err);
            }
        });
    }
});
