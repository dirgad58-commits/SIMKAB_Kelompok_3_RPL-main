<?php
/**
 * SIMKAB - Sistem Informasi Manajemen Karyawan Bank
 * config.php - Konfigurasi Koneksi Database MySQL via PDO
 */

// Set Timezone default ke WITA (Kendari / Asia/Makassar)
date_default_timezone_set('Asia/Makassar');

$host = 'localhost';
$db_name = 'db_simkab';
$username = 'root';
$password = ''; // Default password XAMPP biasanya kosong

try {
    // Inisialisasi koneksi PDO dengan opsi UTF-8
    $pdo = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8mb4", $username, $password);
    
    // Set Error Mode ke Exception untuk memudahkan debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode ke associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $exception) {
    // Mengembalikan JSON error jika koneksi gagal (karena web diakses via AJAX)
    header('Content-Type: application/json');
    echo json_encode([
        "status" => "error",
        "message" => "Koneksi database gagal: " . $exception->getMessage()
    ]);
    exit;
}
?>
