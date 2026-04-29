<?php
require_once __DIR__ . '/../includes/config.php';
startAdminSession();

if (($_GET['action'] ?? '') === 'logout-redirect') {
    session_destroy();
    header('Location: ../index.php');
    exit;
}

// Fallback
header('Location: ../index.php');
exit;
