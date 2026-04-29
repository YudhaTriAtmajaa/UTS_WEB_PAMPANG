<?php

require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json; charset=utf-8');

startAdminSession();

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$db     = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($action) {

        case 'approved':
            $rows = $db->query(
                "SELECT id, name, kota, rating, text, photo, created_at 
                FROM reviews WHERE status='approved' 
                ORDER BY id DESC"
            )->fetchAll();
            foreach ($rows as &$r) {
                $r['photo_url']   = (!empty(trim((string)$r['photo']))) ? photoUrl(trim($r['photo'])) : '';
                $r['date_fmt']    = date('d F Y', strtotime($r['created_at']));
                $r['date']        = substr($r['created_at'], 0, 10);
            }
            jsonResponse(true, '', ['data' => $rows]);

        case 'all':
            requireAdmin();
            $rows = $db->query(
                "SELECT id, name, kota, rating, text, photo, status, created_at 
                FROM reviews ORDER BY id DESC"
            )->fetchAll();
            foreach ($rows as &$r) {
                $r['photo_url'] = (!empty(trim((string)$r['photo']))) ? photoUrl(trim($r['photo'])) : '';
                $r['date_fmt']  = date('d F Y', strtotime($r['created_at']));
                $r['date']      = substr($r['created_at'], 0, 10);
            }
            jsonResponse(true, '', ['data' => $rows]);

        case 'pending':
            requireAdmin();
            $rows = $db->query(
                "SELECT id, name, kota, rating, text, photo, status, created_at 
                FROM reviews WHERE status='pending' ORDER BY id DESC"
            )->fetchAll();
            foreach ($rows as &$r) {
                $r['photo_url'] = (!empty(trim((string)$r['photo']))) ? photoUrl(trim($r['photo'])) : '';
                $r['date_fmt']  = date('d F Y', strtotime($r['created_at']));
                $r['date']      = substr($r['created_at'], 0, 10);
            }
            jsonResponse(true, '', ['data' => $rows]);

        case 'one':
            requireAdmin();
            $id   = (int)($_GET['id'] ?? 0);
            $row  = $db->prepare("SELECT * FROM reviews WHERE id=?");
            $row->execute([$id]);
            $r = $row->fetch();
            if (!$r) jsonResponse(false, 'Ulasan tidak ditemukan.');
            $r['photo_url'] = (!empty(trim((string)$r['photo']))) ? photoUrl(trim($r['photo'])) : '';
            $r['date']      = substr($r['created_at'], 0, 10);
            jsonResponse(true, '', ['data' => $r]);

        case 'stats':
            requireAdmin();
            $total    = (int)$db->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
            $approved = (int)$db->query("SELECT COUNT(*) FROM reviews WHERE status='approved'")->fetchColumn();
            $pending  = (int)$db->query("SELECT COUNT(*) FROM reviews WHERE status='pending'")->fetchColumn();
            $avgRow   = $db->query("SELECT AVG(rating) FROM reviews WHERE status='approved'")->fetchColumn();
            $avg      = $avgRow ? number_format((float)$avgRow, 1) : '0.0';
            $facCount = (int)$db->query("SELECT COUNT(*) FROM facilities")->fetchColumn();
            $galCount = (int)$db->query("SELECT COUNT(*) FROM gallery")->fetchColumn();
            jsonResponse(true, '', compact('total','approved','pending','avg','facCount','galCount'));

        case 'avg':
            $avg = $db->query("SELECT AVG(rating) FROM reviews WHERE status='approved'")->fetchColumn();
            $cnt = (int)$db->query("SELECT COUNT(*) FROM reviews WHERE status='approved'")->fetchColumn();
            jsonResponse(true, '', [
                'avg'   => $avg ? number_format((float)$avg, 1) : '0.0',
                'count' => $cnt,
            ]);

        default:
            jsonResponse(false, 'Action tidak dikenal.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    switch ($action) {

        case 'add':
            $name   = trim($_POST['name']   ?? '');
            $kota   = trim($_POST['kota']   ?? '');
            $text   = trim($_POST['text']   ?? '');
            $rating = (int)($_POST['rating'] ?? 5);
            $isAdmin= isAdminLoggedIn();

            if (!$name) jsonResponse(false, 'Nama wajib diisi.');
            if (!$text) jsonResponse(false, 'Teks ulasan wajib diisi.');
            if ($rating < 1 || $rating > 5) $rating = 5;

            $photo = '';
            if (!empty($_FILES['photo']['name'])) {
                try { $photo = handlePhotoUpload('photo', 'reviews'); }
                catch (RuntimeException $e) { jsonResponse(false, $e->getMessage()); }
            }

            $status = $isAdmin ? 'approved' : 'pending';
            $stmt   = $db->prepare(
                "INSERT INTO reviews (name, kota, rating, text, photo, status) VALUES (?,?,?,?,?,?)"
            );
            $stmt->execute([$name, $kota, $rating, $text, $photo, $status]);
            $newId = $db->lastInsertId();

            jsonResponse(true, $isAdmin
                ? 'Ulasan ditambahkan dan langsung disetujui!'
                : 'Ulasan berhasil dikirim! Menunggu persetujuan admin.',
                ['id' => $newId, 'status' => $status]
            );

        case 'edit':
            requireAdmin();
            $id     = (int)($_POST['id'] ?? 0);
            $name   = trim($_POST['name']   ?? '');
            $kota   = trim($_POST['kota']   ?? '');
            $text   = trim($_POST['text']   ?? '');
            $rating = (int)($_POST['rating'] ?? 5);
            $status = in_array($_POST['status'] ?? '', ['approved','pending']) ? $_POST['status'] : 'pending';
            $removePhoto = ($_POST['remove_photo'] ?? '') === '1';

            if (!$name) jsonResponse(false, 'Nama wajib diisi.');

            $old = $db->prepare("SELECT photo FROM reviews WHERE id=?");
            $old->execute([$id]);
            $oldRow = $old->fetch();
            if (!$oldRow) jsonResponse(false, 'Ulasan tidak ditemukan.');

            $photo = $oldRow['photo'];

            if ($removePhoto && $photo) {
                deletePhotoFile($photo);
                $photo = '';
            }

            if (!empty($_FILES['photo']['name']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
                try {
                    if ($photo) deletePhotoFile($photo); // hapus foto lama
                    $photo = handlePhotoUpload('photo', 'reviews');
                } catch (RuntimeException $e) {
                    jsonResponse(false, $e->getMessage());
                }
            }

            $stmt = $db->prepare(
                "UPDATE reviews SET name=?, kota=?, rating=?, text=?, photo=?, status=? WHERE id=?"
            );
            $stmt->execute([$name, $kota, $rating, $text, $photo, $status, $id]);
            jsonResponse(true, 'Ulasan berhasil diperbarui!');

        case 'approve':
            requireAdmin();
            $id = (int)($_POST['id'] ?? 0);
            $db->prepare("UPDATE reviews SET status='approved' WHERE id=?")->execute([$id]);
            jsonResponse(true, 'Ulasan berhasil disetujui!');

        case 'delete':
            requireAdmin();
            $id  = (int)($_POST['id'] ?? 0);
            $row = $db->prepare("SELECT photo FROM reviews WHERE id=?");
            $row->execute([$id]);
            $r = $row->fetch();
            if ($r && $r['photo']) deletePhotoFile($r['photo']);
            
            $db->prepare("DELETE FROM reviews WHERE id=?")->execute([$id]);
            
            $db->query("ALTER TABLE reviews AUTO_INCREMENT = 1");
            
            jsonResponse(true, 'Ulasan berhasil dihapus.');

        default:
            jsonResponse(false, 'Action tidak dikenal.');
    }
}

jsonResponse(false, 'Method tidak diizinkan.');