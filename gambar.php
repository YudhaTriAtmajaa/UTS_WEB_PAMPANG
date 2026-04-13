<?php
require_once 'config.php';

$tabel = $_GET['tabel'] ?? '';
$id    = (int)($_GET['id']    ?? 0);

// Whitelist tabel yang boleh diakses
$allowedTables = ['wisata', 'foto_galeri'];
if (!in_array($tabel, $allowedTables, true) || $id <= 0) {
    http_response_code(400);
    exit('Parameter tidak valid');
}

try {
    $stmt = $pdo->prepare("SELECT gambar, gambar_mime FROM `{$tabel}` WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row || empty($row['gambar'])) {
        header('Content-Type: image/svg+xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="500" viewBox="0 0 800 500">';
        echo '<rect width="800" height="500" fill="#1a1008"/>';
        echo '<rect x="340" y="200" width="120" height="90" rx="8" fill="none" stroke="#8B6914" stroke-width="2"/>';
        echo '<circle cx="365" cy="225" r="8" fill="#8B6914"/>';
        echo '<polyline points="340,290 380,250 410,270 450,230 460,290" fill="none" stroke="#8B6914" stroke-width="2"/>';
        echo '<text x="400" y="330" font-family="Arial" font-size="14" fill="#8B6914" text-anchor="middle">Upload foto ke database</text>';
        echo '<text x="400" y="350" font-family="Arial" font-size="11" fill="#6b5010" text-anchor="middle">Gunakan upload_gambar.php</text>';
        echo '</svg>';
        exit;
    }

    $mime = $row['gambar_mime'] ?: 'image/jpeg';
    header('Content-Type: ' . $mime);
    header('Cache-Control: public, max-age=86400');
    echo $row['gambar'];

} catch (PDOException $e) {
    http_response_code(500);
    exit('Database error');
}
