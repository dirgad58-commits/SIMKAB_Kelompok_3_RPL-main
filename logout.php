<?php
/**
 * SIMKAB - Sistem Informasi Manajemen Karyawan Bank
 * logout.php - Penghancur Sesi Aman & Pengalihan ke Landing Page
 */

if (!file_exists(__DIR__ . '/sessions')) { mkdir(__DIR__ . '/sessions', 0777, true); }

session_save_path(__DIR__ . '/sessions');
@session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke landing page / login
header('Location: login.php');
exit;

