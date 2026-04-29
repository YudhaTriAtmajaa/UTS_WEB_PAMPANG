<?php
require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json; charset=utf-8');

startAdminSession();

$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'check') {
        jsonResponse(isAdminLoggedIn(), '', [
            'username' => $_SESSION['admin_username'] ?? '',
        ]);
    }
    jsonResponse(false, 'Action tidak dikenal.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($action === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$username || !$password) {
            jsonResponse(false, 'Username dan password wajib diisi.');
        }

        $db   = getDB();
        $stmt = $db->prepare("SELECT id, username, password FROM admins WHERE username=? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if (!$admin || $password !== $admin['password']) {
    sleep(1);
    jsonResponse(false, 'Username atau password salah.');
}

        // Set session
        session_regenerate_id(true);
        $_SESSION['admin_id']       = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['expires']        = time() + SESSION_LIFETIME;

        jsonResponse(true, 'Login berhasil!', [
            'username' => $admin['username'],
        ]);
    }

    if ($action === 'logout') {
        session_destroy();
        jsonResponse(true, 'Logout berhasil.');
    }

    jsonResponse(false, 'Action tidak dikenal.');
}

jsonResponse(false, 'Method tidak diizinkan.');
