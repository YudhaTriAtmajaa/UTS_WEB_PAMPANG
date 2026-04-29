<?php
require_once __DIR__ . '/../includes/config.php';
startAdminSession();
if (isAdminLoggedIn()) {
    header('Location: admin/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%231a4731'/%3E%3Crect x='7' y='16' width='18' height='13' fill='%232e7d5a' rx='1'/%3E%3Cellipse cx='16' cy='16' rx='6' ry='2' fill='%232e7d5a'/%3E%3Cpath d='M10 16 Q10 9 16 8 Q22 9 22 16' fill='%232e7d5a'/%3E%3Cpolygon points='16,3 15,8 17,8' fill='%23c9a84c'/%3E%3Ccircle cx='16' cy='3' r='1.2' fill='%23c9a84c'/%3E%3Crect x='5' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='7,9 5.5,13 8.5,13' fill='%23c9a84c'/%3E%3Crect x='23' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='25,9 23.5,13 26.5,13' fill='%23c9a84c'/%3E%3Crect x='11' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3Crect x='18' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3C/svg%3E">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Masjid Raya Darussalam Samarinda</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="login-page">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">

        <div class="text-center mb-4" style="position:relative;z-index:2">
          <a href="../index.php" style="color:rgba(255,255,255,.5);text-decoration:none;font-size:.82rem;transition:.3s" onmouseover="this.style.color='var(--gold-300)'" onmouseout="this.style.color='rgba(255,255,255,.5)'">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
          </a>
        </div>

        <div class="login-card">
          <div class="login-logo"><i class="fa-solid fa-mosque"></i></div>
          <h2 class="login-title">Panel Admin</h2>
          <p class="login-sub">Masuk ke sistem manajemen Masjid Raya Darussalam Samarinda</p>

          <div style="height:2px;background:linear-gradient(to right,transparent,var(--gold-500),transparent);margin-bottom:1.5rem"></div>

          <div class="alert-error mb-3" id="login-error" style="display:none;align-items:center;gap:.5rem">
            <i class="fa-solid fa-circle-xmark"></i> <span id="login-error-msg">Username atau password salah. Silakan coba lagi.</span>
          </div>

          <form id="login-form" novalidate>
            <div style="margin-bottom:.9rem">
              <label class="field-label" for="username"><i class="fa-solid fa-user"></i> Username</label>
              <input type="text" id="username" name="username" class="field-input" placeholder="Masukkan username" autocomplete="username" required>
            </div>
            <div style="margin-bottom:1.25rem;position:relative">
              <label class="field-label" for="password"><i class="fa-solid fa-key"></i> Password</label>
              <div style="position:relative">
                <input type="password" id="password" name="password" class="field-input" placeholder="Masukkan password" style="padding-right:2.8rem" autocomplete="current-password" required>
                <button type="button" id="toggle-pw" style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--gray-400);font-size:.9rem;padding:.25rem">
                  <i class="fa-solid fa-eye" id="toggle-icon"></i>
                </button>
              </div>
            </div>

            <button type="submit" class="btn-login" id="login-btn">
              <i class="fa-solid fa-right-to-bracket"></i> Masuk ke Panel Admin
            </button>
          </form>

          <p style="text-align:center;font-size:.76rem;color:var(--gray-400);margin-top:1.25rem">
            Akses terbatas untuk administrator resmi.
            <a href="../index.php" style="color:var(--green-600);font-weight:700;text-decoration:none">Kembali ke website</a>
          </p>
        </div>

        <div class="text-center mt-4" style="position:relative;z-index:2">
          <div style="font-family:var(--font-arabic);font-size:1.6rem;color:rgba(201,168,76,.4)">بسم الله الرحمن الرحيم</div>
        </div>

      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>window.APP_BASE = '<?= BASE_URL ?>';</script>
<script src="../assets/js/controller.js"></script>
<script>MasjidCtrl.PageInit.login();</script>

</body>
</html>
