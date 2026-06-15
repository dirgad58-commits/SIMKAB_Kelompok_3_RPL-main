<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMKAB - Sistem Informasi Manajemen Karyawan Bank</title>
    
    <!-- Link FontAwesome 6 CDN untuk Ikon Modern -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Link Custom CSS File -->
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo filemtime(__DIR__ . '/../assets/css/style.css'); ?>">
    
    <!-- Anti-flicker instant theme loading -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('simkab-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
        window.CURRENT_USER = '<?= isset($_SESSION['username']) ? $_SESSION['username'] : '' ?>';
        window.USER_ROLE = '<?= isset($_SESSION['role']) ? $_SESSION['role'] : '' ?>';
    </script>
    
    <!-- Chart.js CDN untuk Visualisasi Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <div class="app-container">

