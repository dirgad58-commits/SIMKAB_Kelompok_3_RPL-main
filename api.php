<?php
/**
 * SIMKAB - Sistem Informasi Manajemen Karyawan Bank
 * api.php - Central API Controller Handler (JSON Router & MySQL Queries)
 */

// Set Timezone default ke WITA (Kendari / Asia/Makassar)
date_default_timezone_set('Asia/Makassar');

// Aktifkan Sesi PHP & Ambil Peran Pengguna Aktif
if (!file_exists(__DIR__ . '/sessions')) { mkdir(__DIR__ . '/sessions', 0777, true); }

session_save_path(__DIR__ . '/sessions');
@session_start();
$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$id_karyawan_session = isset($_SESSION['id_karyawan']) ? $_SESSION['id_karyawan'] : '';

// Set Response Header ke JSON
header('Content-Type: application/json');

// Include Database Configuration
require_once 'config/config.php';

// Ambil parameter action dari GET atau POST
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Response standard template
$response = [
    "status" => "error",
    "message" => "Aksi tidak dikenali."
];

try {
    switch ($action) {
        
        // ==========================================================================
        // 1. DASHBOARD ANALYTICS STATS & CHARTS
        // ==========================================================================
        // ==========================================================================
        // 0. AUTHENTICATION & EXTERNAL JOBS
        // ==========================================================================
        case 'login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user_input = isset($_POST['username']) ? trim($_POST['username']) : '';
                $pass_input = isset($_POST['password']) ? trim($_POST['password']) : '';

                if (empty($user_input) || empty($pass_input)) {
                    throw new Exception("Username dan password harus diisi.");
                }

                $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
                $stmt->execute([$user_input]);
                $user = $stmt->fetch();

                if ($user && (password_verify($pass_input, $user['password']) || $pass_input === $user['password'])) {
                    // Set Session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['id_karyawan'] = $user['id_karyawan'];

                    // Ambil nama lengkap dari master karyawan jika terhubung
                    if ($user['id_karyawan']) {
                        $stmt = $pdo->prepare("SELECT nama FROM karyawan WHERE id = ?");
                        $stmt->execute([$user['id_karyawan']]);
                        $emp = $stmt->fetch();
                        $_SESSION['nama_karyawan'] = $emp ? $emp['nama'] : 'Pegawai Bank';
                    } else {
                        $_SESSION['nama_karyawan'] = 'Administrator Utama';
                    }

                    $response = [
                        "status" => "success",
                        "message" => "Login sukses!"
                    ];
                } else {
                    $response = [
                        "status" => "error",
                        "message" => "Nama pengguna atau kata sandi tidak valid."
                    ];
                }
            }
            break;

        case 'get_dashboard_stats':
            if (strtolower(trim($user_role)) === 'karyawan') {
                // Statistik Khusus Karyawan (ESS)
                // 1. Rekan Kerja di Divisi yang sama
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM karyawan WHERE divisi = (SELECT divisi FROM karyawan WHERE id = ?) AND status = 'Aktif'");
                $stmt->execute([$id_karyawan_session]);
                $total_karyawan = $stmt->fetch()['total'];

                // 2. Status Kehadiran Karyawan Hari Ini (1 = Hadir, 0 = Belum)
                $today = '2026-05-22';
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM absensi WHERE tanggal = ? AND id_karyawan = ? AND status = 'Hadir'");
                $stmt->execute([$today, $id_karyawan_session]);
                $hadir_hari_ini = $stmt->fetch()['total'];

                // 3. Sisa Jatah Cuti Karyawan
                $stmt = $pdo->prepare("SELECT sisa_cuti FROM karyawan WHERE id = ?");
                $stmt->execute([$id_karyawan_session]);
                $cuti_aktif = $stmt->fetch()['sisa_cuti'];
            } else {
                // Statistik HRD / Admin
                // 1. Total Karyawan Aktif
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM karyawan WHERE status = 'Aktif'");
                $total_karyawan = $stmt->fetch()['total'];

                // 2. Hadir Hari Ini
                $today = '2026-05-22';
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM absensi WHERE tanggal = ? AND status = 'Hadir'");
                $stmt->execute([$today]);
                $hadir_hari_ini = $stmt->fetch()['total'];

                // 3. Karyawan Cuti Aktif (Telah Disetujui)
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM cuti WHERE status = 'Disetujui'");
                $cuti_aktif = $stmt->fetch()['total'];
            }

            // 4. Pengumuman Prioritas Penting (Untuk Semua Peran)
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM pengumuman WHERE kategori = 'Penting'");
            $pengumuman_penting = $stmt->fetch()['total'];

            // 5. Data Chart Divisi (Doughnut)
            $stmt = $pdo->query("SELECT divisi, COUNT(*) as jumlah FROM karyawan WHERE status = 'Aktif' GROUP BY divisi");
            $divisi_data = $stmt->fetchAll();

            // 6. Data Chart Absensi (Bar)
            if (strtolower(trim($user_role)) === 'karyawan') {
                // Bar Absensi Pribadi
                $stmt = $pdo->prepare("SELECT status, COUNT(*) as jumlah FROM absensi WHERE id_karyawan = ? GROUP BY status");
                $stmt->execute([$id_karyawan_session]);
                $absensi_data = $stmt->fetchAll();
            } else {
                $stmt = $pdo->query("SELECT status, COUNT(*) as jumlah FROM absensi GROUP BY status");
                $absensi_data = $stmt->fetchAll();
            }

            $response = [
                "status" => "success",
                "data" => [
                    "stats" => [
                        "total_karyawan" => $total_karyawan,
                        "hadir_hari_ini" => $hadir_hari_ini,
                        "cuti_aktif" => $cuti_aktif,
                        "pengumuman_penting" => $pengumuman_penting
                    ],
                    "charts" => [
                        "divisi" => $divisi_data,
                        "absensi" => $absensi_data
                    ]
                ]
            ];
            break;

        // ==========================================================================
        // 2. FEATURE 2: CRUD DATA KARYAWAN
        // ==========================================================================
        case 'get_standar_jabatan':
            $stmt = $pdo->query("SELECT * FROM standar_jabatan ORDER BY divisi, grade ASC, gaji_pokok DESC");
            $jabatan = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $jabatan
            ];
            break;

        case 'get_karyawan':
            if (strtolower(trim($user_role)) === 'karyawan') {
                $stmt = $pdo->prepare("SELECT k.*, u.password as password_login FROM karyawan k LEFT JOIN users u ON k.id = u.id_karyawan WHERE k.id = ?");
                $stmt->execute([$id_karyawan_session]);
            } else {
                $stmt = $pdo->query("SELECT k.*, u.password as password_login FROM karyawan k LEFT JOIN users u ON k.id = u.id_karyawan ORDER BY k.id DESC");
            }
            $karyawan = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $karyawan
            ];
            break;

        case 'add_karyawan':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Generate ID Baru: EMP009, EMP010, dst.
                $stmt = $pdo->query("SELECT id FROM karyawan ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'EMP001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'EMP' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                $stmt = $pdo->prepare("INSERT INTO karyawan (id, nip, nama, email, telepon, divisi, jabatan, status, gaji_pokok, tunjangan, tanggal_bergabung, sisa_cuti) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 12)");
                $stmt->execute([
                    $new_id,
                    $_POST['nip'],
                    $_POST['nama'],
                    $_POST['email'],
                    $_POST['telepon'],
                    $_POST['divisi'],
                    $_POST['jabatan'],
                    $_POST['status'],
                    $_POST['gaji_pokok'],
                    $_POST['tunjangan'],
                    $_POST['tanggal_bergabung']
                ]);

                // --- AUTO CREATE USER ACCOUNT ---
                $username = $_POST['nip'];
                $default_password = 'bankraya' . substr($username, -4);
                
                // Plain text password for student demo
                $stmt_user = $pdo->prepare("INSERT INTO users (username, password, role, id_karyawan) VALUES (?, ?, 'Karyawan', ?)");
                $stmt_user->execute([$username, $default_password, $new_id]);
                
                $response = [
                    "status" => "success",
                    "message" => "Data karyawan berhasil disimpan.\nAkun Login Karyawan Terbuat otomatis:\nUsername: $username (Sesuai NIP-nya)\nPassword: $default_password (Kata 'bankraya' + 4 angka terakhir NIP)"
                ];
            }
            break;

        case 'edit_karyawan':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $stmt = $pdo->prepare("UPDATE karyawan SET nip = ?, nama = ?, email = ?, telepon = ?, divisi = ?, jabatan = ?, status = ?, gaji_pokok = ?, tunjangan = ?, tanggal_bergabung = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['nip'],
                    $_POST['nama'],
                    $_POST['email'],
                    $_POST['telepon'],
                    $_POST['divisi'],
                    $_POST['jabatan'],
                    $_POST['status'],
                    $_POST['gaji_pokok'],
                    $_POST['tunjangan'],
                    $_POST['tanggal_bergabung'],
                    $_POST['id']
                ]);

                // Update Password if provided
                if (!empty($_POST['password'])) {
                    $stmt_pw = $pdo->prepare("UPDATE users SET password = ? WHERE id_karyawan = ?");
                    $stmt_pw->execute([$_POST['password'], $_POST['id']]);
                }

                $response = [
                    "status" => "success",
                    "message" => "Data karyawan berhasil diubah."
                ];
            }
            break;

        case 'upload_foto':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
                if (strtolower(trim($user_role)) !== 'karyawan') {
                    echo json_encode(["status" => "error", "message" => "Hanya Karyawan yang dapat mengubah foto profil sendiri."]);
                    exit;
                }
                
                $fileTmpName = $_FILES['foto']['tmp_name'];
                $fileName = $_FILES['foto']['name'];
                $fileSize = $_FILES['foto']['size'];
                $fileError = $_FILES['foto']['error'];
                
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];
                
                if (in_array($fileExt, $allowed)) {
                    if ($fileError === 0) {
                        if ($fileSize < 5000000) { // 5MB limit
                            $newFileName = "profile_" . $id_karyawan_session . "_" . time() . "." . $fileExt;
                            $fileDestination = 'uploads/' . $newFileName;
                            
                            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                                // Update DB
                                $stmt = $pdo->prepare("UPDATE karyawan SET foto = ? WHERE id = ?");
                                $stmt->execute([$fileDestination, $id_karyawan_session]);
                                
                                echo json_encode(["status" => "success", "message" => "Foto profil berhasil diperbarui", "path" => $fileDestination]);
                            } else {
                                echo json_encode(["status" => "error", "message" => "Gagal menyimpan file."]);
                            }
                        } else {
                            echo json_encode(["status" => "error", "message" => "File terlalu besar (Maks 5MB)."]);
                        }
                    } else {
                        echo json_encode(["status" => "error", "message" => "Terjadi kesalahan upload file."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Hanya file JPG dan PNG yang diizinkan."]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Permintaan tidak valid."]);
            }
            break;

        case 'delete_karyawan':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("DELETE FROM karyawan WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Karyawan berhasil dihapus."
                ];
            }
            break;

        // ==========================================================================
        // 3. FEATURE 3: PENILAIAN KINERJA (KPI)
        // ==========================================================================
        case 'get_kinerja':
            if (strtolower(trim($user_role)) === 'karyawan') {
                $stmt = $pdo->prepare("SELECT k.*, emp.nama, emp.jabatan FROM kinerja k JOIN karyawan emp ON k.id_karyawan = emp.id WHERE k.id_karyawan = ? ORDER BY k.id DESC");
                $stmt->execute([$id_karyawan_session]);
            } else {
                $stmt = $pdo->query("SELECT k.*, emp.nama, emp.jabatan FROM kinerja k JOIN karyawan emp ON k.id_karyawan = emp.id ORDER BY k.id DESC");
            }
            $kinerja = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $kinerja
            ];
            break;

        case 'add_kinerja':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Generate ID KPI005, dst.
                $stmt = $pdo->query("SELECT id FROM kinerja ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'KPI001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'KPI' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                $dis = (float)$_POST['kedisiplinan'];
                $ker = (float)$_POST['kerjasama'];
                $ini = (float)$_POST['inisiatif'];
                $tar = (float)$_POST['target'];
                $skor = ($dis + $ker + $ini + $tar) / 4;

                $predikat = 'D (Kurang)';
                if ($skor >= 85) $predikat = 'A (Sangat Baik)';
                else if ($skor >= 70) $predikat = 'B (Baik)';
                else if ($skor >= 55) $predikat = 'C (Cukup)';

                $stmt = $pdo->prepare("INSERT INTO kinerja (id, id_karyawan, periode, kedisiplinan, kerjasama, inisiatif, target, skor_akhir, predikat, catatan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $new_id,
                    $_POST['id_karyawan'],
                    $_POST['periode'],
                    $dis,
                    $ker,
                    $ini,
                    $tar,
                    $skor,
                    $predikat,
                    $_POST['catatan']
                ]);

                $response = [
                    "status" => "success",
                    "message" => "Penilaian KPI kinerja berhasil disimpan."
                ];
            }
            break;

        case 'delete_kinerja':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("DELETE FROM kinerja WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Penilaian kinerja berhasil dihapus."
                ];
            }
            break;

        // ==========================================================================
        // 4. FEATURE 4: PAYROLL MANAGEMENT
        // ==========================================================================
        case 'get_payroll':
            if (strtolower(trim($user_role)) === 'karyawan') {
                $stmt = $pdo->prepare("SELECT p.*, emp.nama, emp.jabatan, emp.nip, emp.divisi FROM payroll p JOIN karyawan emp ON p.id_karyawan = emp.id WHERE p.id_karyawan = ? ORDER BY p.id DESC");
                $stmt->execute([$id_karyawan_session]);
            } else {
                $stmt = $pdo->query("SELECT p.*, emp.nama, emp.jabatan, emp.nip, emp.divisi FROM payroll p JOIN karyawan emp ON p.id_karyawan = emp.id ORDER BY p.id DESC");
            }
            $payroll = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $payroll
            ];
            break;

        case 'add_payroll':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $stmt = $pdo->query("SELECT id FROM payroll ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'PAY001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'PAY' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                // Ambil Gaji Pokok & Tunjangan asli karyawan dari DB
                $stmt = $pdo->prepare("SELECT gaji_pokok, tunjangan FROM karyawan WHERE id = ?");
                $stmt->execute([$_POST['id_karyawan']]);
                $emp = $stmt->fetch();

                if (!$emp) {
                    throw new Exception("Karyawan tidak ditemukan.");
                }

                $bonus = (float)$_POST['bonus'];
                $potongan = (float)$_POST['potongan'];
                $total = $emp['gaji_pokok'] + $emp['tunjangan'] + $bonus - $potongan;

                $stmt = $pdo->prepare("INSERT INTO payroll (id, id_karyawan, bulan, gaji_pokok, tunjangan, bonus, potongan, total_gaji, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Lunas')");
                $stmt->execute([
                    $new_id,
                    $_POST['id_karyawan'],
                    $_POST['bulan'],
                    $emp['gaji_pokok'],
                    $emp['tunjangan'],
                    $bonus,
                    $potongan,
                    $total
                ]);

                $response = [
                    "status" => "success",
                    "message" => "Payroll bulanan berhasil diproses."
                ];
            }
            break;

        case 'delete_payroll':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("DELETE FROM payroll WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Slip payroll berhasil dihapus."
                ];
            }
            break;

        // ==========================================================================
        // 5. FEATURE 5: MANAJEMEN CUTI (LEAVE REQUEST)
        // ==========================================================================
        case 'get_cuti':
            if (strtolower(trim($user_role)) === 'karyawan') {
                $stmt = $pdo->prepare("SELECT c.*, emp.nama, emp.sisa_cuti, emp.jabatan FROM cuti c JOIN karyawan emp ON c.id_karyawan = emp.id WHERE c.id_karyawan = ? ORDER BY c.id DESC");
                $stmt->execute([$id_karyawan_session]);
            } else {
                $stmt = $pdo->query("SELECT c.*, emp.nama, emp.sisa_cuti, emp.jabatan FROM cuti c JOIN karyawan emp ON c.id_karyawan = emp.id ORDER BY c.id DESC");
            }
            $cuti = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $cuti
            ];
            break;

        case 'add_cuti':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Proteksi session role Karyawan
                $idKaryawan = (strtolower(trim($user_role)) === 'karyawan') ? $id_karyawan_session : $_POST['id_karyawan'];

                $stmt = $pdo->query("SELECT id FROM cuti ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'LV001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'LV' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                // Cek sisa cuti jika jenisnya Cuti Tahunan
                if ($_POST['jenis_cuti'] === 'Cuti Tahunan') {
                    $stmt = $pdo->prepare("SELECT sisa_cuti FROM karyawan WHERE id = ?");
                    $stmt->execute([$idKaryawan]);
                    $sisa = $stmt->fetch()['sisa_cuti'];
                    if ($sisa <= 0) {
                        $response = ["status" => "error", "message" => "Batas sisa cuti tahunan karyawan ini sudah habis."];
                        echo json_encode($response);
                        exit;
                    }
                }

                $stmt = $pdo->prepare("INSERT INTO cuti (id, id_karyawan, jenis_cuti, tanggal_mulai, tanggal_selesai, alasan, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
                $stmt->execute([
                    $new_id,
                    $idKaryawan,
                    $_POST['jenis_cuti'],
                    $_POST['tanggal_mulai'],
                    $_POST['tanggal_selesai'],
                    $_POST['alasan']
                ]);

                $response = [
                    "status" => "success",
                    "message" => "Pengajuan cuti berhasil dikirim."
                ];
            }
            break;

        case 'approve_cuti':
            if (isset($_GET['id'])) {
                $pdo->beginTransaction();

                // Ambil data pengajuan cuti
                $stmt = $pdo->prepare("SELECT * FROM cuti WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $req = $stmt->fetch();

                if ($req && $req['status'] === 'Pending') {
                    // Update status cuti jadi Disetujui
                    $stmt = $pdo->prepare("UPDATE cuti SET status = 'Disetujui' WHERE id = ?");
                    $stmt->execute([$_GET['id']]);

                    // Jika Cuti Tahunan, hitung durasi & kurangi sisa cuti di master Karyawan!
                    if ($req['jenis_cuti'] === 'Cuti Tahunan') {
                        $start = new DateTime($req['tanggal_mulai']);
                        $end = new DateTime($req['tanggal_selesai']);
                        $duration = $start->diff($end)->days + 1;

                        // Kurangi jatah cuti di database
                        $stmt = $pdo->prepare("UPDATE karyawan SET sisa_cuti = GREATEST(0, sisa_cuti - ?) WHERE id = ?");
                        $stmt->execute([$duration, $req['id_karyawan']]);
                    }
                }

                $pdo->commit();
                $response = [
                    "status" => "success",
                    "message" => "Permohonan cuti disetujui."
                ];
            }
            break;

        case 'reject_cuti':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("UPDATE cuti SET status = 'Ditolak' WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Permohonan cuti ditolak."
                ];
            }
            break;

        // ==========================================================================
        // 6. FEATURE 6: LIVE ATTENDANCE (ABSENSI)
        // ==========================================================================
        case 'get_absensi':
            if (strtolower(trim($user_role)) === 'karyawan') {
                $stmt = $pdo->prepare("SELECT a.*, emp.nama, emp.jabatan FROM absensi a JOIN karyawan emp ON a.id_karyawan = emp.id WHERE a.id_karyawan = ? ORDER BY a.tanggal DESC, a.jam_masuk DESC");
                $stmt->execute([$id_karyawan_session]);
            } else {
                $stmt = $pdo->query("SELECT a.*, emp.nama, emp.jabatan FROM absensi a JOIN karyawan emp ON a.id_karyawan = emp.id ORDER BY a.tanggal DESC, a.jam_masuk DESC");
            }
            $absensi = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $absensi
            ];
            break;

        case 'manual_absen':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (strtolower(trim($user_role)) === 'karyawan') {
                    throw new Exception("Akses ditolak. Fitur ini hanya untuk Admin/HRD.");
                }
                
                $idKaryawan = $_POST['id_karyawan'];
                $tanggal = $_POST['tanggal'];
                $jamMasuk = $_POST['jam_masuk'] ?: null;
                $jamKeluar = $_POST['jam_keluar'] ?: null;
                $status_kehadiran = $_POST['status'];

                // Cek apakah sudah ada absen di tanggal tersebut
                $stmt = $pdo->prepare("SELECT id FROM absensi WHERE id_karyawan = ? AND tanggal = ?");
                $stmt->execute([$idKaryawan, $tanggal]);
                $existing = $stmt->fetch();

                if ($existing) {
                    // Update
                    $stmt = $pdo->prepare("UPDATE absensi SET jam_masuk = ?, jam_keluar = ?, status = ? WHERE id = ?");
                    $stmt->execute([$jamMasuk, $jamKeluar, $status_kehadiran, $existing['id']]);
                } else {
                    // Generate ID ABS
                    $stmt = $pdo->query("SELECT id FROM absensi ORDER BY id DESC LIMIT 1");
                    $last_id = $stmt->fetch();
                    $new_id = 'ABS001';
                    if ($last_id) {
                        $num = (int) substr($last_id['id'], 3);
                        $new_id = 'ABS' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                    }
                    // Insert
                    $stmt = $pdo->prepare("INSERT INTO absensi (id, id_karyawan, tanggal, jam_masuk, jam_keluar, status, foto, lokasi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$new_id, $idKaryawan, $tanggal, $jamMasuk, $jamKeluar, $status_kehadiran, 'Manual Input Admin', 'MANUAL']);
                }

                $response = [
                    "status" => "success",
                    "message" => "Data absensi manual berhasil disimpan."
                ];
            }
            break;

        case 'check_in':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $idKaryawan = $id_karyawan_session;
                $todayDate = date('Y-m-d');
                $nowTime = date('H:i:s');

                // Cek jika sudah check in hari ini
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM absensi WHERE id_karyawan = ? AND tanggal = ?");
                $stmt->execute([$idKaryawan, $todayDate]);
                if ($stmt->fetch()['total'] > 0) {
                    if (isset($_SESSION['username']) && $_SESSION['username'] === 'akun.demo') {
                        // DEMO BYPASS: Hapus log hari ini agar bisa demo ulang dari awal
                        $stmt_del = $pdo->prepare("DELETE FROM absensi WHERE id_karyawan = ? AND tanggal = ?");
                        $stmt_del->execute([$idKaryawan, $todayDate]);
                    } else {
                        $response = ["status" => "error", "message" => "Karyawan sudah melakukan Check-In hari ini."];
                        echo json_encode($response);
                        exit;
                    }
                }

                // Generate ID ABS
                $stmt = $pdo->query("SELECT id FROM absensi ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'ABS001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'ABS' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                $foto = isset($_POST['foto']) ? $_POST['foto'] : null;
                $lokasi = isset($_POST['lokasi']) ? $_POST['lokasi'] : null;

                // Menentukan Status Kehadiran (Otak Waktu)
                $status_kehadiran = 'Hadir'; // Default jika check-in sebelum 08:00

                $current_time = strtotime($nowTime);
                $time_08_00 = strtotime('08:00:00');
                $time_09_00 = strtotime('09:00:00');

                // Bypass waktu khusus Akun Demo (Selalu Hadir)
                if (isset($_SESSION['username']) && $_SESSION['username'] === 'akun.demo') {
                    $status_kehadiran = 'Hadir';
                } else {
                    if ($current_time > $time_09_00) {
                        $status_kehadiran = 'Tidak Hadir';
                    } elseif ($current_time > $time_08_00) {
                        $status_kehadiran = 'Terlambat';
                    }
                }

                $stmt = $pdo->prepare("INSERT INTO absensi (id, id_karyawan, tanggal, jam_masuk, jam_keluar, status, foto_masuk, lokasi_masuk) VALUES (?, ?, ?, ?, NULL, ?, ?, ?)");
                $stmt->execute([$new_id, $idKaryawan, $todayDate, $nowTime, $status_kehadiran, $foto, $lokasi]);

                $response = [
                    "status" => "success",
                    "message" => "Check-in berhasil tercatat."
                ];
            }
            break;

        case 'check_out':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $idKaryawan = $id_karyawan_session;
                $todayDate = date('Y-m-d');
                $nowTime = date('H:i:s');

                // Cek log check-in hari ini
                $stmt = $pdo->prepare("SELECT id, jam_keluar FROM absensi WHERE id_karyawan = ? AND tanggal = ?");
                $stmt->execute([$idKaryawan, $todayDate]);
                $log = $stmt->fetch();

                if (!$log) {
                    $response = ["status" => "error", "message" => "Karyawan belum melakukan Check-In masuk hari ini."];
                    echo json_encode($response);
                    exit;
                }

                if ($log['jam_keluar'] !== null) {
                    if (isset($_SESSION['username']) && $_SESSION['username'] === 'akun.demo') {
                        // DEMO BYPASS: Izinkan timpa jam keluar
                    } else {
                        $response = ["status" => "error", "message" => "Karyawan sudah melakukan Check-Out hari ini."];
                        echo json_encode($response);
                        exit;
                    }
                }

                $foto = isset($_POST['foto']) ? $_POST['foto'] : null;
                $lokasi = isset($_POST['lokasi']) ? $_POST['lokasi'] : null;

                $stmt = $pdo->prepare("UPDATE absensi SET jam_keluar = ?, foto_keluar = ?, lokasi_keluar = ? WHERE id = ?");
                $stmt->execute([$nowTime, $foto, $lokasi, $log['id']]);

                $response = [
                    "status" => "success",
                    "message" => "Check-out pulang berhasil tercatat."
                ];
            }
            break;

        case 'izin_sakit':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $idKaryawan = $id_karyawan_session;
                $jenis = $_POST['jenis']; // Sakit atau Izin
                $keterangan = $_POST['keterangan'];
                $foto = isset($_POST['foto']) ? $_POST['foto'] : null;
                $todayDate = date('Y-m-d');
                $nowTime = date('H:i:s');

                // Cek jika sudah absen hari ini
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM absensi WHERE id_karyawan = ? AND tanggal = ?");
                $stmt->execute([$idKaryawan, $todayDate]);
                if ($stmt->fetch()['total'] > 0) {
                    if (isset($_SESSION['username']) && $_SESSION['username'] === 'akun.demo') {
                        // DEMO BYPASS: Hapus log hari ini agar bisa demo ulang dari awal
                        $stmt_del = $pdo->prepare("DELETE FROM absensi WHERE id_karyawan = ? AND tanggal = ?");
                        $stmt_del->execute([$idKaryawan, $todayDate]);
                    } else {
                        $response = ["status" => "error", "message" => "Karyawan sudah tercatat absen/izin hari ini."];
                        echo json_encode($response);
                        exit;
                    }
                }

                // Generate ID ABS
                $stmt = $pdo->query("SELECT id FROM absensi ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'ABS001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'ABS' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                $stmt = $pdo->prepare("INSERT INTO absensi (id, id_karyawan, tanggal, jam_masuk, jam_keluar, status, foto_masuk, lokasi_masuk, keterangan) VALUES (?, ?, ?, ?, NULL, ?, ?, NULL, ?)");
                $stmt->execute([$new_id, $idKaryawan, $todayDate, $nowTime, $jenis, $foto, $keterangan]);

                $response = [
                    "status" => "success",
                    "message" => "Pengajuan $jenis berhasil direkam."
                ];
            }
            break;

        // ==========================================================================
        // 7. FEATURE 7: MUTASI & ROTASI KARIR
        // ==========================================================================
        case 'get_mutasi':
            $stmt = $pdo->query("SELECT m.*, emp.nama, emp.nip FROM mutasi m JOIN karyawan emp ON m.id_karyawan = emp.id ORDER BY m.id DESC");
            $mutasi = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $mutasi
            ];
            break;

        case 'add_mutasi':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $pdo->beginTransaction();

                $idKaryawan = $_POST['id_karyawan'];

                // Dapatkan divisi & jabatan lama dari database master karyawan
                $stmt = $pdo->prepare("SELECT divisi, jabatan FROM karyawan WHERE id = ?");
                $stmt->execute([$idKaryawan]);
                $emp = $stmt->fetch();

                if (!$emp) {
                    throw new Exception("Karyawan tidak ditemukan.");
                }

                // Generate ID MUT
                $stmt = $pdo->query("SELECT id FROM mutasi ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'MUT001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'MUT' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                // Insert Log Mutasi
                $stmt = $pdo->prepare("INSERT INTO mutasi (id, id_karyawan, jenis, divisi_lama, divisi_baru, jabatan_lama, jabatan_baru, tanggal, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $new_id,
                    $idKaryawan,
                    $_POST['jenis'],
                    $emp['divisi'],
                    $_POST['divisi_baru'],
                    $emp['jabatan'],
                    $_POST['jabatan_baru'],
                    $_POST['tanggal'],
                    $_POST['keterangan']
                ]);

                // Dapatkan standar gaji & tunjangan baru berdasarkan divisi & jabatan baru
                $stmtNew = $pdo->prepare("SELECT gaji_pokok, tunjangan FROM standar_jabatan WHERE divisi = ? AND nama_jabatan = ?");
                $stmtNew->execute([$_POST['divisi_baru'], $_POST['jabatan_baru']]);
                $newSalaryScale = $stmtNew->fetch();

                if ($newSalaryScale) {
                    $gajiBaru = $newSalaryScale['gaji_pokok'];
                    $tunjanganBaru = $newSalaryScale['tunjangan'];
                } else {
                    $gajiBaru = 0;
                    $tunjanganBaru = 0;
                }

                // UPDATE DATA KARYAWAN SECARA DYNAMIC DI MASTER DB!
                $stmt = $pdo->prepare("UPDATE karyawan SET divisi = ?, jabatan = ?, gaji_pokok = ?, tunjangan = ? WHERE id = ?");
                $stmt->execute([
                    $_POST['divisi_baru'],
                    $_POST['jabatan_baru'],
                    $gajiBaru,
                    $tunjanganBaru,
                    $idKaryawan
                ]);

                $pdo->commit();
                $response = [
                    "status" => "success",
                    "message" => "Mutasi dan penyesuaian divisi/jabatan karyawan berhasil."
                ];
            }
            break;

        // ==========================================================================
        // 8. FEATURE 8: PORTAL PELATIHAN
        // ==========================================================================
        case 'get_pelatihan':
            if (strtolower(trim($user_role)) === 'karyawan') {
                $stmt = $pdo->prepare("SELECT t.*, emp.nama, emp.divisi FROM pelatihan t JOIN karyawan emp ON t.id_karyawan = emp.id WHERE t.id_karyawan = ? ORDER BY t.id DESC");
                $stmt->execute([$id_karyawan_session]);
            } else {
                $stmt = $pdo->query("SELECT t.*, emp.nama, emp.divisi FROM pelatihan t JOIN karyawan emp ON t.id_karyawan = emp.id ORDER BY t.id DESC");
            }
            $pelatihan = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $pelatihan
            ];
            break;

        case 'approve_pelatihan':
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && strtolower(trim($user_role)) !== 'karyawan') {
                $stmt = $pdo->prepare("UPDATE pelatihan SET status_approval = 'Approved' WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Sertifikat berhasil disetujui."
                ];
            } else {
                $response = ["status" => "error", "message" => "Akses ditolak."];
            }
            break;

        case 'reject_pelatihan':
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && strtolower(trim($user_role)) !== 'karyawan') {
                $stmt = $pdo->prepare("UPDATE pelatihan SET status_approval = 'Rejected' WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Sertifikat ditolak."
                ];
            } else {
                $response = ["status" => "error", "message" => "Akses ditolak."];
            }
            break;

        case 'add_pelatihan':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $stmt = $pdo->query("SELECT id FROM pelatihan ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'TRN001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'TRN' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                // Jika role Karyawan, paksakan id_karyawan sesuai session
                $input_id_karyawan = $_POST['id_karyawan'];
                $status_approval = 'Approved'; // Default untuk admin
                if (strtolower(trim($user_role)) === 'karyawan') {
                    $input_id_karyawan = $id_karyawan_session;
                    $status_approval = 'Pending'; // Butuh verifikasi HRD
                }

                // Handle file upload
                $file_path = null;
                if (isset($_FILES['file_sertifikat']) && $_FILES['file_sertifikat']['error'] === UPLOAD_ERR_OK) {
                    $file_tmp = $_FILES['file_sertifikat']['tmp_name'];
                    $file_name = $_FILES['file_sertifikat']['name'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
                    if (in_array($file_ext, $allowed)) {
                        $new_file_name = 'sertifikat_' . $new_id . '_' . time() . '.' . $file_ext;
                        $upload_dir = 'uploads/sertifikat/';
                        if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);
                        if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                            $file_path = $upload_dir . $new_file_name;
                        }
                    }
                }

                $stmt = $pdo->prepare("INSERT INTO pelatihan (id, id_karyawan, nama_pelatihan, tanggal_sertifikat, status_sertifikat, penyelenggara, file_sertifikat, status_approval) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $new_id,
                    $input_id_karyawan,
                    $_POST['nama_pelatihan'],
                    $_POST['tanggal_sertifikat'],
                    $_POST['status_sertifikat'],
                    $_POST['penyelenggara'],
                    $file_path,
                    $status_approval
                ]);

                $response = [
                    "status" => "success",
                    "message" => "Data pelatihan berhasil ditambahkan" . ($status_approval === 'Pending' ? " dan menunggu verifikasi." : "."),
                    "file" => $file_path
                ];
            }
            break;

        case 'delete_pelatihan':
            if (isset($_GET['id'])) {
                $stmt = $pdo->prepare("DELETE FROM pelatihan WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Data pelatihan berhasil dihapus."
                ];
            }
            break;

        // ==========================================================================
        // 9. FEATURE 9: INVENTARIS ASET
        // ==========================================================================
        case 'get_aset':
            if (strtolower(trim($user_role)) === 'karyawan') {
                $stmt = $pdo->prepare("SELECT a.*, emp.nama, emp.jabatan FROM aset a JOIN karyawan emp ON a.id_karyawan = emp.id WHERE a.id_karyawan = ? ORDER BY a.id DESC");
                $stmt->execute([$id_karyawan_session]);
            } else {
                $stmt = $pdo->query("SELECT a.*, emp.nama, emp.jabatan FROM aset a JOIN karyawan emp ON a.id_karyawan = emp.id ORDER BY a.id DESC");
            }
            $aset = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $aset
            ];
            break;

        case 'add_aset':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $stmt = $pdo->query("SELECT id FROM aset ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'AST001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'AST' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                $stmt = $pdo->prepare("INSERT INTO aset (id, id_karyawan, nama_aset, kode_aset, tanggal_pinjam, tanggal_kembali, status) VALUES (?, ?, ?, ?, ?, NULL, 'Dipinjam')");
                $stmt->execute([
                    $new_id,
                    $_POST['id_karyawan'],
                    $_POST['nama_aset'],
                    $_POST['kode_aset'],
                    $_POST['tanggal_pinjam']
                ]);

                $response = [
                    "status" => "success",
                    "message" => "Peminjaman aset berhasil dikonfirmasi."
                ];
            }
            break;

        case 'kembalikan_aset':
            if (isset($_GET['id'])) {
                $today = date('Y-m-d');
                $stmt = $pdo->prepare("UPDATE aset SET status = 'Dikembalikan', tanggal_kembali = ? WHERE id = ?");
                $stmt->execute([$today, $_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Aset berhasil dikembalikan."
                ];
            }
            break;

        // ==========================================================================
        // 10. FEATURE 10: PORTAL PENGUMUMAN
        // ==========================================================================
        case 'get_pengumuman':
            $stmt = $pdo->query("SELECT * FROM pengumuman ORDER BY id DESC");
            $pengumuman = $stmt->fetchAll();
            $response = [
                "status" => "success",
                "data" => $pengumuman
            ];
            break;

        case 'add_pengumuman':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (strtolower(trim($user_role)) === 'karyawan') {
                    echo json_encode(["status" => "error", "message" => "Akses ditolak: Hanya Admin dan HRD yang dapat membuat pengumuman."]);
                    exit;
                }
                
                $stmt = $pdo->query("SELECT id FROM pengumuman ORDER BY id DESC LIMIT 1");
                $last_id = $stmt->fetch();
                $new_id = 'ANN001';
                if ($last_id) {
                    $num = (int) substr($last_id['id'], 3);
                    $new_id = 'ANN' . str_pad($num + 1, 3, '0', STR_PAD_LEFT);
                }

                $today = date('Y-m-d');
                $stmt = $pdo->prepare("INSERT INTO pengumuman (id, judul, konten, kategori, tanggal, pengirim) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $new_id,
                    $_POST['judul'],
                    $_POST['konten'],
                    $_POST['kategori'],
                    $today,
                    $_POST['pengirim']
                ]);

                $response = [
                    "status" => "success",
                    "message" => "Memo pengumuman internal berhasil diterbitkan."
                ];
            }
            break;

        case 'delete_pengumuman':
            if (isset($_GET['id'])) {
                if (strtolower(trim($user_role)) === 'karyawan') {
                    echo json_encode(["status" => "error", "message" => "Akses ditolak: Hanya Admin dan HRD yang dapat menghapus pengumuman."]);
                    exit;
                }

                $stmt = $pdo->prepare("DELETE FROM pengumuman WHERE id = ?");
                $stmt->execute([$_GET['id']]);
                $response = [
                    "status" => "success",
                    "message" => "Memo pengumuman berhasil dihapus."
                ];
            }
            break;
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $response = [
        "status" => "error",
        "message" => "Terjadi kesalahan server: " . $e->getMessage()
    ];
}

// Kembalikan Response JSON
echo json_encode($response);
exit;
?>

