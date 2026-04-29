<?php
require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json; charset=utf-8');

startAdminSession();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

function extractYoutubeId(string $url): string {
    $patterns = [
        '/[?&]v=([^&?#]{11})/',
        '/youtu\.be\/([^?&#]{11})/',
        '/\/embed\/([^?&#]{11})/',
        '/\/live\/([^?&#]{11})/',
        '/\/shorts\/([^?&#]{11})/',
    ];
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $url, $m)) {
            return $m[1];
        }
    }
    return '';
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'list') {
    if (!isAdminLoggedIn()) jsonResponse(false, 'Unauthorized');
    $db   = getDB();
    $rows = $db->query("SELECT * FROM videos ORDER BY urutan ASC, id ASC")->fetchAll();
    jsonResponse(true, '', ['data' => $rows]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'get') {
    if (!isAdminLoggedIn()) jsonResponse(false, 'Unauthorized');
    $id  = (int)($_GET['id'] ?? 0);
    $db  = getDB();
    $row = $db->prepare("SELECT * FROM videos WHERE id = ?");
    $row->execute([$id]);
    $data = $row->fetch();
    if (!$data) jsonResponse(false, 'Video tidak ditemukan.');
    jsonResponse(true, '', ['data' => $data]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'public') {
    $db   = getDB();
    $rows = $db->query(
        "SELECT * FROM videos WHERE aktif = 1 AND urutan > 0 ORDER BY urutan ASC LIMIT 4"
    )->fetchAll();
    jsonResponse(true, '', ['data' => $rows]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'tambah') {
    if (!isAdminLoggedIn()) jsonResponse(false, 'Unauthorized');
    verifyCsrf();

    $judul    = trim($_POST['judul']    ?? '');
    $url      = trim($_POST['url']      ?? '');
    $kategori = trim($_POST['kategori'] ?? 'Lainnya');
    $urutan   = (int)($_POST['urutan']  ?? 0);
    $aktif    = isset($_POST['aktif']) ? 1 : 0;

    if ($judul === '')   jsonResponse(false, 'Judul wajib diisi.');
    if ($url   === '')   jsonResponse(false, 'URL YouTube wajib diisi.');

    $videoId = extractYoutubeId($url);
    if ($videoId === '') jsonResponse(false, 'URL YouTube tidak valid. Pastikan format URL benar.');

    $allowed = ['Kajian','Kegiatan','Ibadah','Terbaru','Lainnya'];
    if (!in_array($kategori, $allowed, true)) $kategori = 'Lainnya';

    $db  = getDB();
    $sql = "INSERT INTO videos (judul, url, video_id, kategori, urutan, aktif) VALUES (?,?,?,?,?,?)";
    $db->prepare($sql)->execute([$judul, $url, $videoId, $kategori, $urutan, $aktif]);

    jsonResponse(true, 'Video berhasil ditambahkan.', ['id' => $db->lastInsertId()]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'edit') {
    if (!isAdminLoggedIn()) jsonResponse(false, 'Unauthorized');
    verifyCsrf();

    $id       = (int)($_POST['id']       ?? 0);
    $judul    = trim($_POST['judul']     ?? '');
    $url      = trim($_POST['url']       ?? '');
    $kategori = trim($_POST['kategori']  ?? 'Lainnya');
    $urutan   = (int)($_POST['urutan']   ?? 0);
    $aktif    = isset($_POST['aktif']) ? 1 : 0;

    if ($id    === 0)  jsonResponse(false, 'ID tidak valid.');
    if ($judul === '')  jsonResponse(false, 'Judul wajib diisi.');
    if ($url   === '')  jsonResponse(false, 'URL YouTube wajib diisi.');

    $videoId = extractYoutubeId($url);
    if ($videoId === '') jsonResponse(false, 'URL YouTube tidak valid.');

    $allowed = ['Kajian','Kegiatan','Ibadah','Terbaru','Lainnya'];
    if (!in_array($kategori, $allowed, true)) $kategori = 'Lainnya';

    $db  = getDB();
    $sql = "UPDATE videos SET judul=?, url=?, video_id=?, kategori=?, urutan=?, aktif=? WHERE id=?";
    $db->prepare($sql)->execute([$judul, $url, $videoId, $kategori, $urutan, $aktif, $id]);

    jsonResponse(true, 'Video berhasil diperbarui.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'hapus') {
    if (!isAdminLoggedIn()) jsonResponse(false, 'Unauthorized');
    verifyCsrf();

    $id = (int)($_POST['id'] ?? 0);
    if ($id === 0) jsonResponse(false, 'ID tidak valid.');

    $db = getDB();
    $db->prepare("DELETE FROM videos WHERE id = ?")->execute([$id]);
    jsonResponse(true, 'Video berhasil dihapus.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $action === 'toggle') {
    if (!isAdminLoggedIn()) jsonResponse(false, 'Unauthorized');
    verifyCsrf();

    $id = (int)($_POST['id'] ?? 0);
    if ($id === 0) jsonResponse(false, 'ID tidak valid.');

    $db = getDB();
    $db->prepare("UPDATE videos SET aktif = NOT aktif WHERE id = ?")->execute([$id]);
    jsonResponse(true, 'Status video diperbarui.');
}

jsonResponse(false, 'Aksi tidak dikenali.');
