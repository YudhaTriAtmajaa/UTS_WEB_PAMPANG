<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');   
define('DB_PASS', '');  
define('DB_NAME', 'wisata_pampang_rev'); 
define('DB_CHARSET', 'utf8mb4');

define('SITE_NAME', 'Desa Wisata Budaya Pampang');
define('SITE_TAGLINE', 'Samarinda, Kalimantan Timur');
define('SITE_EMAIL', 'budaya.pampang82@gmail.com');
define('SITE_PHONE', '081254993755');

// ---- Koneksi PDO ----
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die('<div style="font-family:sans-serif;padding:40px;color:#c00;">
        <h2>Koneksi Database Gagal</h2>
        <p>' . htmlspecialchars($e->getMessage()) . '</p>
        <p>Pastikan MySQL berjalan dan setting di <code>config.php</code> sudah benar.</p>
    </div>');
}

// ---- Helper: rating bintang ----
function renderStars(int $n, bool $small = false): string {
    $cls = $small ? 'fs-6' : 'fs-5';
    $out = '';
    for ($i = 1; $i <= 5; $i++) {
        $out .= '<i class="bi bi-star-fill ' . $cls . ' ' . ($i <= $n ? 'text-warning' : 'text-secondary opacity-25') . '"></i>';
    }
    return $out;
}

// ---- Helper: sanitize output ----
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
