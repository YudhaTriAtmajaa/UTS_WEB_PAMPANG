<?php
require_once __DIR__ . '/../includes/config.php';
startAdminSession();
$isAdmin = isAdminLoggedIn();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%231a4731'/%3E%3Crect x='7' y='16' width='18' height='13' fill='%232e7d5a' rx='1'/%3E%3Cellipse cx='16' cy='16' rx='6' ry='2' fill='%232e7d5a'/%3E%3Cpath d='M10 16 Q10 9 16 8 Q22 9 22 16' fill='%232e7d5a'/%3E%3Cpolygon points='16,3 15,8 17,8' fill='%23c9a84c'/%3E%3Ccircle cx='16' cy='3' r='1.2' fill='%23c9a84c'/%3E%3Crect x='5' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='7,9 5.5,13 8.5,13' fill='%23c9a84c'/%3E%3Crect x='23' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='25,9 23.5,13 26.5,13' fill='%23c9a84c'/%3E%3Crect x='11' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3Crect x='18' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3C/svg%3E">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ulasan Pengunjung - Masjid Raya Darussalam Samarinda</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php if ($isAdmin): ?>
<div class="admin-floating-bar" id="admin-floating-bar">
  <div class="admin-bar-left">
    <span class="admin-bar-badge"><i class="fa-solid fa-shield-halved"></i> Mode Admin</span>
    <div class="admin-bar-sep"></div>
    <span><i class="fa-solid fa-user" style="opacity:.6"></i> <?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?></span>
    <div class="admin-bar-sep"></div>
    <span style="opacity:.55;font-size:.75rem">Anda sedang melihat tampilan publik</span>
  </div>
  <div class="admin-bar-right">
    <a href="../views/admin/dashboard.php" class="admin-bar-link primary"><i class="fa-solid fa-gauge"></i> Dashboard Admin</a>
    <a href="#" class="admin-bar-link" id="admin-logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>
</div>
<?php endif; ?>

<nav class="site-nav">
  <div class="container">
    <a href="../index.php" class="nav-brand">
      <div class="nav-brand-icon"><i class="fa-solid fa-mosque"></i></div>
      <div class="nav-brand-text">
        <span class="brand-arabic">مسجد رايا دارالسلام</span>
        <span class="brand-name">Masjid Raya Darussalam</span>
      </div>
    </a>
    <ul class="nav-links">
      <li class="nav-item"><a href="../index.php"><i class="fa-solid fa-house"></i> Beranda</a></li>
      <li class="nav-item"><a href="detail.php"><i class="fa-solid fa-mosque"></i> Detail &amp; Fasilitas</a></li>
      <li class="nav-item"><a href="ulasan.php" class="active"><i class="fa-regular fa-comment-dots"></i> Ulasan</a></li>
    </ul>
    <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
  </div>
  <div class="nav-mobile" id="nav-mobile">
    <ul style="list-style:none;padding:0;margin:0">
      <li class="nav-item"><a href="../index.php"><i class="fa-solid fa-house"></i> Beranda</a></li>
      <li class="nav-item"><a href="detail.php"><i class="fa-solid fa-mosque"></i> Detail &amp; Fasilitas</a></li>
      <li class="nav-item"><a href="ulasan.php"><i class="fa-regular fa-comment-dots"></i> Ulasan</a></li>
    </ul>
  </div>
</nav>

<section class="pattern-dark" style="padding:4rem 0 3rem;position:relative">
  <div class="container" style="position:relative;z-index:1">
    <nav aria-label="breadcrumb" style="margin-bottom:.75rem">
      <ol class="breadcrumb" style="background:transparent;padding:0;margin:0">
        <li class="breadcrumb-item"><a href="../index.php" style="color:var(--gold-300);text-decoration:none;font-size:.83rem"><i class="fa-solid fa-house"></i> Beranda</a></li>
        <li class="breadcrumb-item active" style="color:rgba(255,255,255,.55);font-size:.83rem">Ulasan Pengunjung</li>
      </ol>
    </nav>
    <div style="font-family:var(--font-arabic);font-size:1.9rem;color:var(--gold-300);margin-bottom:.4rem">مسجد رايا دارالسلام</div>
    <h1 style="font-family:var(--font-display);font-size:clamp(1.8rem,4vw,3rem);font-weight:900;color:var(--white);margin-bottom:.75rem">
      Ulasan <span style="color:var(--gold-500)">Pengunjung</span>
    </h1>
    <p style="color:rgba(255,255,255,.65);max-width:540px;line-height:1.8">Bagikan pengalaman ibadah dan kunjungan Anda di Masjid Raya Darussalam Samarinda</p>
  </div>
</section>

<div id="review-list-app">
  <div class="container" style="padding:3rem 0;text-align:center;color:var(--gray-400)">
    <i class="fa-solid fa-spinner fa-spin fa-2x"></i>
    <p style="margin-top:.75rem">Memuat ulasan...</p>
  </div>
</div>

<footer class="site-footer">
  <div class="container footer-inner">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-arabic">مسجد رايا دارالسلام</div>
        <div class="footer-brand">Masjid Raya Darussalam</div>
        <p class="footer-desc">Pusat ibadah, pendidikan, dan wisata religi kebanggaan Kota Samarinda dan Kalimantan Timur.</p>
      </div>
      <div class="col-6 col-lg-2">
        <div class="footer-heading">Navigasi</div>
        <a href="../index.php" class="footer-link"><i class="fa-solid fa-house fa-fw"></i> Beranda</a>
        <a href="detail.php"   class="footer-link"><i class="fa-solid fa-mosque fa-fw"></i> Detail &amp; Fasilitas</a>
        <a href="ulasan.php"   class="footer-link"><i class="fa-regular fa-comment-dots fa-fw"></i> Ulasan</a>
        <a href="login.php"   class="footer-link"><i class="fa-regular fa-user fa-fw"></i> Admin</a>
      </div>
      <div class="col-6 col-lg-3">
        <div class="footer-heading">Kontak</div>
        <p style="font-size:.84rem;line-height:2.1;color:rgba(255,255,255,.55)">
          <i class="fa-solid fa-location-dot fa-fw"></i> Jl. K.H. Abdullah Marisie, Samarinda<br>
          <i class="fa-solid fa-phone fa-fw"></i> (0541) 123-456<br>
          <i class="fa-regular fa-envelope fa-fw"></i> info@darussalam-samarinda.id<br>
          <i class="fa-regular fa-clock fa-fw"></i> Buka 24 Jam
        </p>
      </div>
      <div class="col-lg-3">
        <div class="footer-heading">Ikuti Kami</div>
        <div class="d-flex flex-column gap-2">
          <a href="https://www.facebook.com/mesra.darussalamsmd/" target="_blank" class="footer-link" style="display:inline-flex;align-items:center;gap:.6rem;border:1px solid rgba(255,255,255,.12);padding:.45rem .9rem;border-radius:6px"><i class="fa-brands fa-facebook-f" style="width:14px;text-align:center"></i> Facebook</a>
          <a href="https://www.instagram.com/darussalamsmd/?utm_source=ig_web_button_share_sheet" target="_blank" class="footer-link" style="display:inline-flex;align-items:center;gap:.6rem;border:1px solid rgba(255,255,255,.12);padding:.45rem .9rem;border-radius:6px"><i class="fa-brands fa-instagram" style="width:14px;text-align:center"></i> Instagram</a>
          <a href="https://www.youtube.com/@MasjidRayaDarussalam" target="_blank" rel="noopener noreferrer" class="footer-link" style="display:inline-flex;align-items:center;gap:.6rem;border:1px solid rgba(255,255,255,.12);padding:.45rem .9rem;border-radius:6px"><i class="fa-brands fa-youtube" style="width:14px;text-align:center"></i> YouTube</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span style="color:rgba(255,255,255,.35)">&copy; <?= date('Y') ?> Masjid Raya Darussalam Samarinda</span>
    </div>
  </div>
</footer>

<div class="modal fade" id="logout-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:var(--radius);border:2px solid var(--red-500)">
      <div class="modal-header" style="background:var(--red-500);color:var(--white);border-bottom:none;">
        <h6 class="modal-title" style="font-family:var(--font-display);display:flex;align-items:center;gap:.5rem">
          <i class="fa-solid fa-right-from-bracket"></i> Konfirmasi Logout
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <i class="fa-solid fa-circle-question" style="font-size:2.5rem;color:var(--red-500);margin-bottom:.75rem;display:block"></i>
        <p style="font-weight:700;color:var(--gray-800);margin-bottom:.4rem">Yakin ingin keluar dari Mode Admin?</p>
        <p style="font-size:.84rem;color:var(--gray-500)">Sesi Anda akan diakhiri.</p>
      </div>
      <div class="modal-footer justify-content-center gap-2 border-0 pt-0 pb-4">
        <button type="button" class="act-btn act-view" data-bs-dismiss="modal" style="padding:.55rem 1.25rem">Batal</button>
        <button type="button" class="act-btn act-delete" style="padding:.55rem 1.25rem" onclick="window._ctrl.doLogout()">
          <i class="fa-solid fa-right-from-bracket"></i> Ya, Keluar
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script>window.APP_BASE = '<?= BASE_URL ?>';</script>
<script src="../assets/js/controller.js"></script>
<script src="../assets/js/vue-reviews.js"></script>
<script>
  VueReviewsApp.initList('#review-list-app');
  MasjidCtrl.PageInit.ulasan();
</script>

</body>
</html>