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
            $rows = $db->query("SELECT id, name, tag, capacity, status, `desc`, photo FROM facilities ORDER BY id ASC")->fetchAll();
            foreach ($rows as &$r) {
                $r['photo_url'] = $r['photo'] ? photoUrl($r['photo']) : '';
            }
            jsonResponse(true, '', ['data' => $rows]);

        case 'one':
            requireAdmin();
            $id  = (int)($_GET['id'] ?? 0);
            $st  = $db->prepare("SELECT * FROM facilities WHERE id=?");
            $st->execute([$id]);
            $r = $st->fetch();
            if (!$r) jsonResponse(false, 'Fasilitas tidak ditemukan.');
            $r['photo_url'] = $r['photo'] ? photoUrl($r['photo']) : '';
            jsonResponse(true, '', ['data' => $r]);

        default:
            jsonResponse(false, 'Action tidak dikenal.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireAdmin();
    switch ($action) {

        case 'add':
            $name     = trim($_POST['name']     ?? '');
            $tag      = trim($_POST['tag']      ?? '');
            $capacity = trim($_POST['capacity'] ?? '');
            $status   = trim($_POST['status']   ?? 'Tersedia');
            $desc     = trim($_POST['desc']     ?? '');

            if (!$name) jsonResponse(false, 'Nama fasilitas wajib diisi.');

            $photo = '';
            if (!empty($_FILES['photo']['name'])) {
                try { $photo = handlePhotoUpload('photo', 'facilities'); }
                catch (RuntimeException $e) { jsonResponse(false, $e->getMessage()); }
            }

            $db->prepare(
                "INSERT INTO facilities (name, tag, capacity, status, `desc`, photo) VALUES (?,?,?,?,?,?)"
            )->execute([$name, $tag, $capacity, $status, $desc, $photo]);
            jsonResponse(true, 'Fasilitas berhasil ditambahkan!', ['id' => $db->lastInsertId()]);

        case 'edit':
            $id       = (int)($_POST['id']       ?? 0);
            $name     = trim($_POST['name']      ?? '');
            $tag      = trim($_POST['tag']       ?? '');
            $capacity = trim($_POST['capacity']  ?? '');
            $status   = trim($_POST['status']    ?? 'Tersedia');
            $desc     = trim($_POST['desc']      ?? '');
            $removePhoto = ($_POST['remove_photo'] ?? '') === '1';

            if (!$name) jsonResponse(false, 'Nama fasilitas wajib diisi.');

            $old = $db->prepare("SELECT photo FROM facilities WHERE id=?");
            $old->execute([$id]);
            $oldRow = $old->fetch();
            if (!$oldRow) jsonResponse(false, 'Fasilitas tidak ditemukan.');

            $photo = $oldRow['photo'];
            if ($removePhoto && $photo) { deletePhotoFile($photo); $photo = ''; }

            if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
                try {
                    if ($photo) deletePhotoFile($photo);
                    $photo = handlePhotoUpload('photo', 'facilities');
                } catch (RuntimeException $e) { jsonResponse(false, $e->getMessage()); }
            }

            $db->prepare(
                "UPDATE facilities SET name=?, tag=?, capacity=?, status=?, `desc`=?, photo=? WHERE id=?"
            )->execute([$name, $tag, $capacity, $status, $desc, $photo, $id]);
            jsonResponse(true, 'Fasilitas berhasil diperbarui!');

        case 'delete':
            $id  = (int)($_POST['id'] ?? 0);
            $row = $db->prepare("SELECT photo FROM facilities WHERE id=?");
            $row->execute([$id]);
            $r = $row->fetch();
            if ($r && $r['photo']) deletePhotoFile($r['photo']);
            $db->prepare("DELETE FROM facilities WHERE id=?")->execute([$id]);
            jsonResponse(true, 'Fasilitas berhasil dihapus.');

        default:
            jsonResponse(false, 'Action tidak dikenal.');
    }
}

jsonResponse(false, 'Method tidak diizinkan.');
