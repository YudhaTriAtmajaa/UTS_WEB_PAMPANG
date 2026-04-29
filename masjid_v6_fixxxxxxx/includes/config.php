<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'masjidraya');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host      = $_SERVER['HTTP_HOST'] ?? 'localhost';

define('BASE_PATH', dirname(__DIR__));

$_docRoot   = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '', '/');
$_basePath  = rtrim(BASE_PATH, '/');
$_subFolder = '';
if ($_docRoot !== '' && strpos($_basePath, $_docRoot) === 0) {
    $_subFolder = substr($_basePath, strlen($_docRoot));
}
define('BASE_URL', $protocol . '://' . $host . $_subFolder);

define('UPLOAD_DIR',      BASE_PATH . '/uploads/');
define('UPLOAD_REVIEWS',  BASE_PATH . '/uploads/reviews/');
define('UPLOAD_FACILITIES', BASE_PATH . '/uploads/facilities/');
define('UPLOAD_GALLERY',  BASE_PATH . '/uploads/gallery/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);
define('ALLOWED_TYPES',   ['image/jpeg', 'image/png', 'image/webp', 'image/gif']);

define('SESSION_KEY',     'mrd_admin_session');
define('SESSION_LIFETIME', 3600);

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', DB_HOST, DB_NAME, DB_CHARSET);
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die(json_encode([
                'success' => false,
                'message' => 'Koneksi database gagal. Periksa konfigurasi DB.',
            ]));
        }
    }
    return $pdo;
}

function startAdminSession(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_KEY);
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path'     => '/',
            'secure'   => false,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function isAdminLoggedIn(): bool {
    startAdminSession();
    if (empty($_SESSION['admin_id']) || empty($_SESSION['expires'])) return false;
    if (time() > $_SESSION['expires']) {
        session_destroy();
        return false;
    }
    $_SESSION['expires'] = time() + SESSION_LIFETIME;
    return true;
}

function requireAdmin(): void {
    if (!isAdminLoggedIn()) {
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        if (str_contains($accept, 'application/json') || 
            ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
            http_response_code(401);
            die(json_encode(['success' => false, 'message' => 'Sesi tidak valid. Silakan login kembali.']));
        }
        $path  = $_SERVER['PHP_SELF'] ?? '/';
        $depth = substr_count($path, '/') - 1;
        $back  = str_repeat('../', max(0, $depth - 1)) . 'login.php';
        header('Location: ' . $back);
        exit;
    }
}

/**
 * @param string
 * @param string 
 * @return string 
 */
function handlePhotoUpload(string $fieldName, string $subDir): string {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return '';
    }
    $file = $_FILES[$fieldName];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Upload error code: ' . $file['error']);
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('Ukuran foto maksimal 5MB.');
    }

    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    if (!in_array($mimeType, ALLOWED_TYPES, true)) {
        throw new RuntimeException('Tipe file tidak diizinkan. Gunakan JPG, PNG, atau WEBP.');
    }

    $ext      = match($mimeType) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        default      => 'jpg',
    };
    $filename = uniqid($subDir . '_', true) . '.' . $ext;
    $destDir  = BASE_PATH . '/uploads/' . $subDir . '/';
    
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    $destPath = $destDir . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        throw new RuntimeException('Gagal menyimpan file foto.');
    }

    return 'uploads/' . $subDir . '/' . $filename;
}

function deletePhotoFile(string $photoPath): void {
    if ($photoPath === '') return;
    $fullPath = BASE_PATH . '/' . ltrim($photoPath, '/');
    if (file_exists($fullPath)) {
        @unlink($fullPath);
    }
}

function photoUrl(string $path): string {
    if ($path === '') return '';
    return BASE_URL . '/' . ltrim($path, '/');
}

function jsonResponse(bool $success, string $message = '', array $data = []): never {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    exit;
}

function getCsrfToken(): string {
    startAdminSession();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void {
    $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        jsonResponse(false, 'Token keamanan tidak valid. Refresh halaman dan coba lagi.');
    }
}
