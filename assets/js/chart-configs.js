/**
 * SIMKAB - Sistem Informasi Manajemen Karyawan Bank
 * js/chart-configs.js - Konfigurasi Dinamis Grafik Visualisasi (Chart.js)
 */

let chartDivisiInstance = null;
let chartKehadiranInstance = null;

class SIMKABCharts {
    /**
     * Render atau Update Grafik Distribusi Divisi (Doughnut Chart)
     */
    static renderDivisiChart() {
        const ctx = document.getElementById('chart-divisi');
        if (!ctx) return;

        // Ambil data karyawan ter-update
        const karyawanList = SIMKABData.getKaryawan();

        // Hitung sebaran divisi
        const divisiCounts = {
            'Teknologi Informasi': 0,
            'Operasional & Layanan': 0,
            'Kredit & Pembiayaan': 0,
            'Human Resources': 0
        };

        karyawanList.forEach(emp => {
            if (emp.status === 'Aktif' && divisiCounts.hasOwnProperty(emp.divisi)) {
                divisiCounts[emp.divisi]++;
            }
        });

        const labels = Object.keys(divisiCounts);
        const dataValues = Object.values(divisiCounts);

        // Hancurkan instance chart lama jika sudah ada untuk menghindari error rendering ulang
        if (chartDivisiInstance) {
            chartDivisiInstance.destroy();
        }

        // Ambil token desain CSS untuk mode terang/gelap dinamis
        const styles = window.getComputedStyle(document.documentElement);
        const textSecondary = styles.getPropertyValue('--text-secondary').trim() || '#94a3b8';
        const textPrimary = styles.getPropertyValue('--text-primary').trim() || '#ffffff';
        const bgCardSolid = styles.getPropertyValue('--bg-card-solid').trim() || '#0f1626';
        const borderColorVar = styles.getPropertyValue('--border-color').trim() || 'rgba(30, 41, 59, 0.6)';

        // Buat Chart Baru
        chartDivisiInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: [
                        '#3b82f6', // Ocean Blue
                        '#f59e0b', // Amber
                        '#10b981', // Emerald
                        '#6366f1'  // Electric Indigo
                    ],
                    borderColor: bgCardSolid, // Sesuai warna card background
                    borderWidth: 3,
                    hoverOffset: 12
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textSecondary,
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11,
                                weight: '500'
                            },
                            padding: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: bgCardSolid,
                        titleColor: textPrimary,
                        bodyColor: textSecondary,
                        borderColor: borderColorVar,
                        borderWidth: 1,
                        padding: 10,
                        bodyFont: {
                            family: 'Plus Jakarta Sans'
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    /**
     * Render atau Update Grafik Rekapitulasi Absensi Bulanan (Bar Chart)
     */
    static renderKehadiranChart() {
        const ctx = document.getElementById('chart-kehadiran');
        if (!ctx) return;

        // Ambil data absensi
        const absensiList = SIMKABData.getAbsensi();

        // Hitung total status kehadiran
        let countHadir = 0;
        let countIzin = 0;
        let countSakit = 0;
        let countAlpa = 0;

        absensiList.forEach(log => {
            switch (log.status) {
                case 'Hadir': countHadir++; break;
                case 'Izin': countIzin++; break;
                case 'Sakit': countSakit++; break;
                case 'Alpa': countAlpa++; break;
            }
        });

        // Hancurkan instance chart lama jika sudah ada
        if (chartKehadiranInstance) {
            chartKehadiranInstance.destroy();
        }

        // Ambil token desain CSS untuk mode terang/gelap dinamis
        const styles = window.getComputedStyle(document.documentElement);
        const textSecondary = styles.getPropertyValue('--text-secondary').trim() || '#94a3b8';
        const textPrimary = styles.getPropertyValue('--text-primary').trim() || '#ffffff';
        const bgCardSolid = styles.getPropertyValue('--bg-card-solid').trim() || '#0f1626';
        const borderColorVar = styles.getPropertyValue('--border-color').trim() || 'rgba(30, 41, 59, 0.6)';

        // Buat Chart Baru
        chartKehadiranInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Hadir (Log)', 'Izin', 'Sakit', 'Mangkir / Alpa'],
                datasets: [{
                    label: 'Frekuensi Absensi',
                    data: [countHadir, countIzin, countSakit, countAlpa],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.85)', // Emerald Solid
                        'rgba(59, 130, 246, 0.85)', // Blue Solid
                        'rgba(245, 158, 11, 0.85)', // Amber Solid
                        'rgba(239, 68, 68, 0.85)'   // Red Solid
                    ],
                    borderColor: [
                        '#10b981',
                        '#3b82f6',
                        '#f59e0b',
                        '#ef4444'
                    ],
                    borderWidth: 2,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: bgCardSolid,
                        titleColor: textPrimary,
                        bodyColor: textSecondary,
                        borderColor: borderColorVar,
                        borderWidth: 1,
                        padding: 10,
                        bodyFont: {
                            family: 'Plus Jakarta Sans'
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: borderColorVar
                        },
                        ticks: {
                            color: textSecondary,
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: borderColorVar
                        },
                        ticks: {
                            color: textSecondary,
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            },
                            stepSize: 2
                        }
                    }
                }
            }
        });
    }

    /**
     * Update Seluruh Grafik Sekaligus
     */
    static updateAllCharts() {
        this.renderDivisiChart();
        this.renderKehadiranChart();
    }
}
