<?php
require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json; charset=utf-8');

startAdminSession();

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$db     = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($action) {

        case 'all':
        case '':
            $limit  = 100;
            $rows = $db->query(
                "SELECT id, caption, photo, featured, is_home FROM gallery ORDER BY featured DESC, id ASC LIMIT {$limit}"
            )->fetchAll();
            foreach ($rows as &$r) {
                $r['photo_url'] = $r['photo'] ? photoUrl($r['photo']) : '';
                $r['featured']  = (bool)(int)$r['featured'];
                $r['is_home']   = (bool)(int)$r['is_home'];
            }
            jsonResponse(true, '', ['data' => $rows]);

        case 'one':
            requireAdmin();
            $id = (int)($_GET['id'] ?? 0);
            $st = $db->prepare("SELECT * FROM gallery WHERE id=?");
            $st->execute([$id]);
            $r = $st->fetch();
            if (!$r) jsonResponse(false, 'Foto tidak ditemukan.');
            $r['photo_url'] = $r['photo'] ? photoUrl($r['photo']) : '';
            $r['featured']  = (bool)(int)$r['featured'];
            $r['is_home']   = (bool)(int)$r['is_home'];
            jsonResponse(true, '', ['data' => $r]);

        case 'home_photo':
            $st = $db->query("SELECT id, caption, photo FROM gallery WHERE is_home=1 AND photo != '' LIMIT 1");
            $r  = $st->fetch();
            if (!$r) jsonResponse(true, '', ['data' => null]);
            $r['photo_url'] = photoUrl($r['photo']);
            jsonResponse(true, '', ['data' => $r]);

        default:
            jsonResponse(false, 'Action tidak dikenal.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    switch ($action) {

        case 'add':
            $caption  = trim($_POST['caption']  ?? 'Foto Masjid');
            $featured = !empty($_POST['featured']) ? 1 : 0;
            $is_home  = !empty($_POST['is_home'])  ? 1 : 0;

            $photo = '';
            if (!empty($_FILES['photo']['name'])) {
                try { $photo = handlePhotoUpload('photo', 'gallery'); }
                catch (RuntimeException $e) { jsonResponse(false, $e->getMessage()); }
            }

            if ($is_home) $db->exec("UPDATE gallery SET is_home=0");
            // Fix bug foto utama ganda
            if ($featured) $db->exec("UPDATE gallery SET featured=0");

            $db->prepare(
                "INSERT INTO gallery (caption, photo, featured, is_home) VALUES (?,?,?,?)"
            )->execute([$caption, $photo, $featured, $is_home]);
            jsonResponse(true, 'Foto berhasil ditambahkan ke galeri!', ['id' => $db->lastInsertId()]);

        case 'edit':
            $id       = (int)($_POST['id']      ?? 0);
            $caption  = trim($_POST['caption']  ?? 'Foto Masjid');
            $featured = !empty($_POST['featured']) ? 1 : 0;
            $removePhoto = ($_POST['remove_photo'] ?? '') === '1';

            $old = $db->prepare("SELECT photo FROM gallery WHERE id=?");
            $old->execute([$id]);
            $oldRow = $old->fetch();
            if (!$oldRow) jsonResponse(false, 'Foto tidak ditemukan.');

            $photo = $oldRow['photo'];
            if ($removePhoto && $photo) { deletePhotoFile($photo); $photo = ''; }

            if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
                try {
                    if ($photo) deletePhotoFile($photo);
                    $photo = handlePhotoUpload('photo', 'gallery');
                } catch (RuntimeException $e) { jsonResponse(false, $e->getMessage()); }
            }

            if ($featured) $db->exec("UPDATE gallery SET featured=0 WHERE id != " . $id);
            $db->prepare(
                "UPDATE gallery SET caption=?, photo=?, featured=? WHERE id=?"
            )->execute([$caption, $photo, $featured, $id]);
            jsonResponse(true, 'Data galeri berhasil diperbarui!');

        case 'set_home':
            $id = (int)($_POST['id'] ?? 0);
            if (!$id) jsonResponse(false, 'ID tidak valid.');
            $row = $db->prepare("SELECT id, caption, is_home FROM gallery WHERE id=?");
            $row->execute([$id]);
            $r = $row->fetch();
            if (!$r) jsonResponse(false, 'Foto tidak ditemukan.');

            if ($r['is_home']) {
                $db->prepare("UPDATE gallery SET is_home=0 WHERE id=?")->execute([$id]);
                jsonResponse(true, 'Foto utama home page dilepas.');
            } else {
                $db->exec("UPDATE gallery SET is_home=0");
                $db->prepare("UPDATE gallery SET is_home=1 WHERE id=?")->execute([$id]);
                jsonResponse(true, 'Foto "' . htmlspecialchars($r['caption']) . '" diterapkan sebagai foto utama home page!');
            }

        case 'delete':
            $id  = (int)($_POST['id'] ?? 0);
            $row = $db->prepare("SELECT photo FROM gallery WHERE id=?");
            $row->execute([$id]);
            $r = $row->fetch();
            if ($r && $r['photo']) deletePhotoFile($r['photo']);
            $db->prepare("DELETE FROM gallery WHERE id=?")->execute([$id]);
            jsonResponse(true, 'Foto berhasil dihapus dari galeri.');

        default:
            jsonResponse(false, 'Action tidak dikenal.');
    }
}

jsonResponse(false, 'Method tidak diizinkan.');