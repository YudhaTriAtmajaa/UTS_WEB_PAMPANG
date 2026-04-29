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
  <title>Detail & Fasilitas - Masjid Raya Darussalam Samarinda</title>
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
      <li class="nav-item"><a href="detail.php" class="active"><i class="fa-solid fa-mosque"></i> Detail & Fasilitas</a></li>
      <li class="nav-item"><a href="ulasan.php"><i class="fa-regular fa-comment-dots"></i> Ulasan</a></li>
    </ul>
    <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
  </div>
  <div class="nav-mobile" id="nav-mobile">
    <ul style="list-style:none;padding:0;margin:0">
      <li class="nav-item"><a href="../index.php"><i class="fa-solid fa-house"></i> Beranda</a></li>
      <li class="nav-item"><a href="detail.php"><i class="fa-solid fa-mosque"></i> Detail & Fasilitas</a></li>
      <li class="nav-item"><a href="ulasan.php"><i class="fa-regular fa-comment-dots"></i> Ulasan</a></li>
    </ul>
  </div>
</nav>

<section class="pattern-dark" style="padding:4rem 0 3rem;position:relative">
  <div class="container" style="position:relative;z-index:1">
    <nav aria-label="breadcrumb" style="margin-bottom:.75rem">
      <ol class="breadcrumb" style="background:transparent;padding:0;margin:0">
        <li class="breadcrumb-item"><a href="../index.php" style="color:var(--gold-300);text-decoration:none;font-size:.83rem"><i class="fa-solid fa-house"></i> Beranda</a></li>
        <li class="breadcrumb-item active" style="color:rgba(255,255,255,.55);font-size:.83rem">Detail & Fasilitas</li>
      </ol>
    </nav>
    <div style="font-family:var(--font-arabic);font-size:1.9rem;color:var(--gold-300);margin-bottom:.4rem">مسجد رايا دارالسلام</div>
    <h1 style="font-family:var(--font-display);font-size:clamp(1.8rem,4vw,3rem);font-weight:900;color:var(--white);margin-bottom:.75rem">
      Detail & <span style="color:var(--gold-500)">Fasilitas</span>
    </h1>
    <p style="color:rgba(255,255,255,.65);max-width:560px;line-height:1.8;font-size:.96rem">Informasi lengkap, fasilitas modern, galeri foto, dan lokasi Masjid Raya Darussalam Samarinda.</p>
  </div>
</section>

<div style="background:var(--white);border-bottom:2px solid rgba(201,168,76,.2);padding:1.25rem 0;box-shadow:var(--shadow-xs)">
  <div class="container">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3"><div class="quick-info-item"><div class="qi-icon"><i class="fa-solid fa-location-dot"></i></div><div class="qi-title">Lokasi</div><div class="qi-val">Jl. K.H. Abdullah Marisie, Samarinda</div></div></div>
      <div class="col-6 col-md-3"><div class="quick-info-item"><div class="qi-icon"><i class="fa-regular fa-clock"></i></div><div class="qi-title">Jam Operasional</div><div class="qi-val">24 Jam, 7 Hari Seminggu</div></div></div>
      <div class="col-6 col-md-3"><div class="quick-info-item"><div class="qi-icon"><i class="fa-solid fa-ticket"></i></div><div class="qi-title">Tiket Masuk</div><div class="qi-val">Gratis untuk semua pengunjung</div></div></div>
      <div class="col-6 col-md-3"><div class="quick-info-item"><div class="qi-icon"><i class="fa-solid fa-square-parking"></i></div><div class="qi-title">Parkir</div><div class="qi-val">Tersedia, Luas & Gratis</div></div></div>
    </div>
  </div>
</div>

<section style="padding:5rem 0;background:var(--white)">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-7">
        <span class="section-badge"><i class="fa-solid fa-book-open"></i> Deskripsi</span>
        <h2 class="section-title mt-2 mb-3">Tentang Masjid Raya <span>Darussalam</span></h2>
        <div class="gold-line"></div>
        <div style="color:var(--gray-500);line-height:1.9;font-size:.95rem;margin-top:1.25rem">
          <p>Masjid Raya Darussalam Samarinda adalah masjid agung yang berdiri megah di jantung Kota Samarinda, ibu kota Provinsi Kalimantan Timur. Dengan arsitektur memadukan gaya Islam klasik dan modern, masjid ini telah menjadi ikon kebanggaan masyarakat Kalimantan Timur sejak berdirinya pada tahun 1974.</p>
          <p style="margin-top:1rem">Nama "Darussalam" dalam bahasa Arab berarti <em>"Rumah Kedamaian"</em>. Nama ini sangat sesuai dengan fungsi dan atmosfer di dalam masjid, tempat di mana setiap pengunjung dapat merasakan kedamaian jiwa dan ketenangan spiritual.</p>
          <p style="margin-top:1rem">Masjid ini memiliki kapasitas mampu menampung hingga 10.000 jamaah sekaligus. Kubah utama berwarna hijau yang megah menjadi ciri khas visual yang paling menonjol dan dapat terlihat dari berbagai sudut kota Samarinda.</p>
        </div>
        <div class="d-flex flex-wrap gap-2 mt-3">
          <span style="background:var(--green-100);color:var(--green-800);padding:.35rem .9rem;border-radius:50px;font-size:.78rem;font-weight:600;border:1px solid rgba(46,125,90,.15);display:inline-flex;align-items:center;gap:.35rem"><i class="fa-solid fa-check"></i> Parkir Gratis</span>
          <span style="background:var(--green-100);color:var(--green-800);padding:.35rem .9rem;border-radius:50px;font-size:.78rem;font-weight:600;border:1px solid rgba(46,125,90,.15);display:inline-flex;align-items:center;gap:.35rem"><i class="fa-solid fa-check"></i> Tempat Ibadah</span>
          <span style="background:var(--green-100);color:var(--green-800);padding:.35rem .9rem;border-radius:50px;font-size:.78rem;font-weight:600;border:1px solid rgba(46,125,90,.15);display:inline-flex;align-items:center;gap:.35rem"><i class="fa-solid fa-check"></i> Bersih & Nyaman</span>
          <span style="background:var(--green-100);color:var(--green-800);padding:.35rem .9rem;border-radius:50px;font-size:.78rem;font-weight:600;border:1px solid rgba(46,125,90,.15);display:inline-flex;align-items:center;gap:.35rem"><i class="fa-solid fa-check"></i> Buka 24 Jam</span>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="form-panel" style="position:sticky;top:90px">
          <h6 style="font-family:var(--font-display);color:var(--green-800);font-weight:700;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:2px solid rgba(201,168,76,.25);display:flex;align-items:center;gap:.5rem">
            <i class="fa-solid fa-circle-info" style="color:var(--gold-500)"></i> Informasi Detail
          </h6>
          <table class="info-table">
            <tr><td class="lbl"><i class="fa-solid fa-building fa-fw" style="color:var(--gray-300);margin-right:.35rem"></i>Nama Resmi</td><td class="val">Masjid Raya Darussalam</td></tr>
            <tr><td class="lbl"><i class="fa-regular fa-calendar fa-fw" style="color:var(--gray-300);margin-right:.35rem"></i>Tahun Berdiri</td><td class="val">1974</td></tr>
            <tr><td class="lbl"><i class="fa-solid fa-users fa-fw" style="color:var(--gray-300);margin-right:.35rem"></i>Kapasitas</td><td class="val">± 15.000 Jamaah</td></tr>
            <tr><td class="lbl"><i class="fa-solid fa-ruler-combined fa-fw" style="color:var(--gray-300);margin-right:.35rem"></i>Luas Bangunan</td><td class="val">± 10.150 m²</td></tr>
            <tr><td class="lbl"><i class="fa-solid fa-location-dot fa-fw" style="color:var(--gray-300);margin-right:.35rem"></i>Alamat</td><td class="val">Jl. K.H. Abdullah Marisie, Samarinda</td></tr>
            <tr><td class="lbl"><i class="fa-regular fa-clock fa-fw" style="color:var(--gray-300);margin-right:.35rem"></i>Jam Operasional</td><td class="val" style="color:var(--green-600)">Buka 24 Jam</td></tr>
            <tr><td class="lbl"><i class="fa-solid fa-ticket fa-fw" style="color:var(--gray-300);margin-right:.35rem"></i>Biaya Masuk</td><td class="val" style="color:var(--green-600);font-weight:800">GRATIS</td></tr>
          </table>
          <a href="ulasan.php" class="btn-green w-100 mt-4" style="justify-content:center;border-radius:var(--radius-sm)">
            <i class="fa-solid fa-pen-to-square"></i> Tulis Ulasan Anda
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="pattern-light" style="padding:5rem 0">
  <div class="container">
    <div class="text-center mb-5 fade-up">
      <span class="section-badge"><i class="fa-solid fa-building-columns"></i> Fasilitas</span>
      <h2 class="section-title mt-3">Fasilitas Lengkap & <span>Modern</span></h2>
      <div class="gold-line center"></div>
      <p class="section-desc mx-auto mt-3">Semua fasilitas dirancang untuk memberikan kenyamanan maksimal bagi jamaah dan pengunjung</p>
    </div>
    <div class="row g-4" id="facilities-grid">
      <div class="col-12 text-center" style="padding:2rem;color:var(--gray-400)"><i class="fa-solid fa-spinner fa-spin"></i> Memuat fasilitas...</div>
    </div>
  </div>
</section>

<section style="padding:5rem 0;background:var(--white)">
  <div class="container">
    <div class="text-center mb-5 fade-up">
      <span class="section-badge"><i class="fa-solid fa-images"></i> Galeri Foto</span>
      <h2 class="section-title mt-3">Keindahan Visual <span>Darussalam</span></h2>
      <div class="gold-line center"></div>
      <p class="section-desc mx-auto mt-3">Abadikan momen berharga Anda di Masjid Raya Darussalam</p>
    </div>
    <div class="gallery-grid fade-up" id="gallery-grid">
      <div style="text-align:center;padding:2rem;color:var(--gray-400);grid-column:1/-1"><i class="fa-solid fa-spinner fa-spin"></i> Memuat galeri...</div>
    </div>
    <p style="color:var(--gray-400);font-size:.78rem;text-align:center;margin-top:.75rem;font-style:italic">
      <i class="fa-solid fa-circle-info"></i> Klik gambar untuk memperbesar.
    </p>
  </div>
</section>

<section class="pattern-light" style="padding:5rem 0">
  <div class="container">
    <div class="text-center mb-5 fade-up">
      <span class="section-badge"><i class="fa-solid fa-map-location-dot"></i> Lokasi</span>
      <h2 class="section-title mt-3">Temukan <span>Lokasi Kami</span></h2>
      <div class="gold-line center"></div>
      <p class="section-desc mx-auto mt-3">
        <i class="fa-solid fa-location-dot" style="color:var(--green-600)"></i>
        Jl. K.H. Abdullah Marisie, Samarinda, Kalimantan Timur
      </p>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-10 fade-up">
        <div class="map-frame" style="display:block;padding:0;overflow:hidden;min-height:420px">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.664391212782!2d117.1449241749647!3d-0.5032952994917729!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df67fa059882e59%3A0xfff0cf40b79facbb!2sMasjid%20Raya%20Darussalam!5e0!3m2!1sid!2sid!4v1776333978140!5m2!1sid!2sid" 
            width="100%" height="420" style="border:0;display:block" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="text-center mt-3">
          <a href="https://maps.google.com?q=Masjid+Raya+Darussalam+Samarinda" target="_blank" rel="noopener noreferrer" class="btn-green">
            <i class="fa-brands fa-google"></i> Buka di Google Maps
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

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
        <a href="detail.php"   class="footer-link"><i class="fa-solid fa-mosque fa-fw"></i> Detail & Fasilitas</a>
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
      <span style="color:rgba(255,255,255,.35)">© <?= date('Y') ?> Masjid Raya Darussalam Samarinda</span>
    </div>
  </div>
</footer>

<div class="modal fade" id="gallery-modal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius);border:2px solid rgba(201,168,76,.3)">
      <div class="modal-header modal-header-green">
        <h6 class="modal-title" style="font-family:var(--font-display);display:flex;align-items:center;gap:.5rem"><i class="fa-solid fa-image"></i> Galeri Foto</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4" id="modal-gallery-body"></div>
    </div>
  </div>
</div>

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
<script>MasjidCtrl.PageInit.detail();</script>

</body>
</html>