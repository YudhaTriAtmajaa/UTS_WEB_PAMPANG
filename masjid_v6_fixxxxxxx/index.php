<?php
require_once __DIR__ . '/includes/config.php';
startAdminSession();
$isAdmin = isAdminLoggedIn();

$total_fac = 6;
try {
    $db_stats = getDB();
    $total_fac = $db_stats->query("SELECT COUNT(*) FROM facilities")->fetchColumn();
} catch (Exception $e) {}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%231a4731'/%3E%3Crect x='7' y='16' width='18' height='13' fill='%232e7d5a' rx='1'/%3E%3Cellipse cx='16' cy='16' rx='6' ry='2' fill='%232e7d5a'/%3E%3Cpath d='M10 16 Q10 9 16 8 Q22 9 22 16' fill='%232e7d5a'/%3E%3Cpolygon points='16,3 15,8 17,8' fill='%23c9a84c'/%3E%3Ccircle cx='16' cy='3' r='1.2' fill='%23c9a84c'/%3E%3Crect x='5' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='7,9 5.5,13 8.5,13' fill='%23c9a84c'/%3E%3Crect x='23' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='25,9 23.5,13 26.5,13' fill='%23c9a84c'/%3E%3Crect x='11' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3Crect x='18' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3C/svg%3E">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masjid Raya Darussalam Samarinda</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
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
    <a href="views/admin/dashboard.php" class="admin-bar-link primary"><i class="fa-solid fa-gauge"></i> Dashboard Admin</a>
    <a href="api/auth.php?action=logout-redirect" class="admin-bar-link" id="admin-logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>
</div>
<?php endif; ?>

<nav class="site-nav">
  <div class="container">
    <a href="index.php" class="nav-brand">
      <div class="nav-brand-icon"><i class="fa-solid fa-mosque"></i></div>
      <div class="nav-brand-text">
        <span class="brand-arabic">مسجد رايا دارالسلام</span>
        <span class="brand-name">Masjid Raya Darussalam</span>
      </div>
    </a>
    <ul class="nav-links">
      <li class="nav-item"><a href="index.php" class="active"><i class="fa-solid fa-house"></i> Beranda</a></li>
      <li class="nav-item"><a href="views/detail.php"><i class="fa-solid fa-mosque"></i> Detail &amp; Fasilitas</a></li>
      <li class="nav-item"><a href="views/ulasan.php"><i class="fa-regular fa-comment-dots"></i> Ulasan</a></li>
    </ul>
    <button class="nav-hamburger" id="hamburger"><span></span><span></span><span></span></button>
  </div>
  <div class="nav-mobile" id="nav-mobile">
    <ul style="list-style:none;padding:0;margin:0">
      <li class="nav-item"><a href="index.php"><i class="fa-solid fa-house"></i> Beranda</a></li>
      <li class="nav-item"><a href="views/detail.php"><i class="fa-solid fa-mosque"></i> Detail &amp; Fasilitas</a></li>
      <li class="nav-item"><a href="views/ulasan.php"><i class="fa-regular fa-comment-dots"></i> Ulasan</a></li>
    </ul>
  </div>
</nav>

<section class="hero">
  <div class="hero-bg"></div>
  <div class="container py-5">
    <div class="row align-items-center gy-5">
      <div class="col-lg-6 hero-content">
        <div class="hero-badge"><i class="fa-solid fa-location-dot"></i> Wisata Religi - Samarinda, Kaltim</div>
        <div class="hero-arabic">مسجد رايا دارالسلام</div>
        <h1 class="hero-title">Masjid Raya<br><span class="accent">Darussalam</span><br>Samarinda</h1>
        <p class="hero-desc">Masjid kebanggaan Kalimantan Timur dengan arsitektur megah dan suasana ibadah yang khusyuk. Berdiri kokoh sebagai pusat spiritual dan budaya Islam di jantung Kota Tepian.</p>
        <div class="hero-stats">
          <div><span class="stat-num">1974</span><span class="stat-lbl">Tahun Berdiri</span></div>
          <div class="stat-divider"></div>
          <div><span class="stat-num">15K+</span><span class="stat-lbl">Kapasitas Jamaah</span></div>
          <div class="stat-divider"></div>
          <div><span class="stat-num" id="stat-rating">5.0</span><span class="stat-lbl">Rating Pengunjung</span></div>
          <div class="stat-divider"></div>
          <div><span class="stat-num" id="stat-reviews">50+</span><span class="stat-lbl">Ulasan</span></div>
        </div>
        <div class="d-flex flex-wrap gap-3">
          <a href="views/detail.php" class="btn-primary"><i class="fa-solid fa-mosque"></i> Jelajahi Masjid</a>
          <a href="views/ulasan.php" class="btn-outline"><i class="fa-solid fa-pen-to-square"></i> Tulis Ulasan</a>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="mosque-card">
          <div class="hero-photo-frame" id="hero-photo-frame"></div>
          <div class="info-pills">
            <span class="info-pill gold"><i class="fa-solid fa-location-dot"></i> Samarinda, Kaltim</span>
            <span class="info-pill green"><i class="fa-solid fa-mosque"></i> Masjid Agung Kota</span>
            <span class="info-pill"><i class="fa-regular fa-clock"></i> Buka 24 Jam</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section style="padding:5rem 0;background:var(--white)">
  <div class="container">
    <div class="text-center mb-5 fade-up">
      <span class="section-badge"><i class="fa-solid fa-star"></i> Keistimewaan</span>
      <h2 class="section-title mt-3">Mengapa Mengunjungi <span>Darussalam</span></h2>
      <div class="gold-line center"></div>
      <p class="section-desc mx-auto mt-3">Masjid Raya Darussalam bukan sekadar tempat ibadah, melainkan simbol keagungan Islam di Kalimantan Timur</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3 fade-up"><div class="feature-card"><div class="feature-icon"><i class="fa-solid fa-landmark"></i></div><div class="feature-title">Arsitektur Megah</div><p class="feature-text">Perpaduan gaya Islam klasik dan modern dengan kubah hijau ikonik yang menjadi landmark Samarinda.</p></div></div>
      <div class="col-md-6 col-lg-3 fade-up"><div class="feature-card"><div class="feature-icon"><i class="fa-solid fa-hands-praying"></i></div><div class="feature-title">Pusat Ibadah Utama</div><p class="feature-text">Menampung hingga 15.000 jamaah, menjadikannya masjid terbesar dan pusat kegiatan Islam di Samarinda.</p></div></div>
      <div class="col-md-6 col-lg-3 fade-up"><div class="feature-card"><div class="feature-icon"><i class="fa-solid fa-book-open"></i></div><div class="feature-title">Pusat Pendidikan</div><p class="feature-text">Perpustakaan Islam, ruang kajian, dan program pendidikan keagamaan untuk semua kalangan usia.</p></div></div>
      <div class="col-md-6 col-lg-3 fade-up"><div class="feature-card"><div class="feature-icon"><i class="fa-solid fa-map-location-dot"></i></div><div class="feature-title">Wisata Religi</div><p class="feature-text">Destinasi wisata religi populer dengan taman indah, spot foto, dan lingkungan asri yang menenangkan.</p></div></div>
    </div>
  </div>
</section>

<div id="prayer-app">
  <section class="prayer-section" style="padding:4rem 0;min-height:260px">
    <div class="container mt-4" style="text-align:center;padding-top:2rem">
      <i class="fa-solid fa-spinner fa-spin fa-2x" style="color:rgba(255,255,255,.5)"></i>
    </div>
  </section>
</div>

<section class="pattern-light" style="padding:5rem 0">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-5 fade-up">
        <span class="section-badge"><i class="fa-solid fa-scroll"></i> Sejarah</span>
        <h2 class="section-title mt-3">Perjalanan Sejarah<br><span>Darussalam</span></h2>
        <div class="gold-line"></div>
        <p style="color:var(--gray-500);line-height:1.85;margin-bottom:1.25rem;font-size:.95rem">Masjid Raya Darussalam Samarinda merupakan masjid agung yang menjadi pusat kegiatan keislaman di Kota Samarinda, Ibu Kota Provinsi Kalimantan Timur.</p>
        <p style="color:var(--gray-500);line-height:1.85;font-size:.95rem">Nama <em>"Darussalam"</em> yang bermakna <strong>"Negeri yang damai"</strong> mencerminkan misi utama masjid ini sebagai tempat yang memberikan ketenangan spiritual dan persatuan umat Islam di Kalimantan Timur.</p>
        <div class="row g-3 mt-3">
          <div class="col-4 text-center"><div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900;color:var(--green-800)">50+</div><div style="font-size:.75rem;color:var(--gray-400)">Tahun Berdiri</div></div>
          <div class="col-4 text-center"><div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900;color:var(--green-800)"><?= $total_fac ?></div><div style="font-size:.75rem;color:var(--gray-400)">Fasilitas Utama</div></div>
          <div class="col-4 text-center"><div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900;color:var(--green-800)">24/7</div><div style="font-size:.75rem;color:var(--gray-400)">Setiap Hari</div></div>
        </div>
      </div>
      <div class="col-lg-7 fade-up">
        <div class="form-panel">
          <h6 style="font-family:var(--font-display);color:var(--green-800);font-weight:700;margin-bottom:1.5rem;display:flex;align-items:center;gap:.5rem"><i class="fa-solid fa-timeline" style="color:var(--gold-500)"></i> Linimasa Sejarah</h6>
          <div class="timeline">
            <div class="timeline-item"><div class="timeline-year">1974 - Pendirian</div><p class="timeline-text">Masjid Raya Darussalam resmi didirikan sebagai masjid agung Kota Samarinda atas inisiatif Pemerintah Daerah Kalimantan Timur.</p></div>
            <div class="timeline-item"><div class="timeline-year">1985 - Renovasi Pertama</div><p class="timeline-text">Perluasan bangunan utama untuk mengakomodasi pertumbuhan jumlah jamaah yang semakin meningkat.</p></div>
            <div class="timeline-item"><div class="timeline-year">1995 - Pembangunan Menara</div><p class="timeline-text">Dua menara kembar yang megah mulai dibangun, menjadikannya landmark kota yang lebih ikonik.</p></div>
            <div class="timeline-item"><div class="timeline-year">2005 - Modernisasi Fasilitas</div><p class="timeline-text">Penambahan sistem pendingin udara, renovasi tempat wudhu, perpustakaan Islam, dan aula serbaguna.</p></div>
            <div class="timeline-item"><div class="timeline-year">2015 - Revitalisasi</div><p class="timeline-text">Revitalisasi besar meliputi perbaikan kubah, lampu LED eksterior, dan penataan taman.</p></div>
            <div class="timeline-item" style="margin-bottom:0"><div class="timeline-year">2026 - Masa Kini</div><p class="timeline-text">Terus berkembang sebagai pusat ibadah, pendidikan, dan wisata religi yang membanggakan Kalimantan Timur.</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section style="padding:5rem 0;background:var(--white)">
  <div class="container">
    <div class="text-center mb-5 fade-up">
      <span class="section-badge"><i class="fa-regular fa-comment-dots"></i> Testimoni</span>
      <h2 class="section-title mt-3">Kata Mereka tentang <span>Darussalam</span></h2>
      <div class="gold-line center"></div>
      <p class="section-desc mx-auto mt-3">Pengalaman nyata dari para pengunjung yang telah merasakan keindahan dan kekhusyukan masjid ini</p>
    </div>
    <div class="reviews-slider-wrap">
      <div class="reviews-track" id="reviews-track"></div>
    </div>
    <div class="slider-controls">
      <button class="slider-btn" id="slider-prev"><i class="fa-solid fa-chevron-left"></i></button>
      <div class="slider-dots" id="slider-dots"></div>
      <button class="slider-btn" id="slider-next"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
    <div class="text-center mt-4"><a href="views/ulasan.php" class="btn-green"><i class="fa-solid fa-list"></i> Lihat Semua Ulasan</a></div>
  </div>
</section>

<section style="padding:5rem 0;background:var(--gray-50)">
  <div class="container">
    <div class="text-center mb-4 fade-up">
      <span class="section-badge mb-2" style="display:inline-flex"><i class="fa-brands fa-youtube" style="color:#ff0000"></i> Video Terkini</span>
      <h2 class="section-title mt-2">Video dari <span>Channel Kami</span></h2>
      <div class="gold-line center" style="margin-top:.75rem"></div>
      <p style="font-size:.85rem;color:var(--gray-500);margin-top:.4rem">Kajian &amp; kegiatan dari YouTube <strong>@MasjidRayaDarussalam</strong></p>
    </div>
    
    <div class="row g-3">
        <?php
        try {
          $db_videos = getDB();
          $stmt_vid  = $db_videos->query("SELECT * FROM videos WHERE aktif = 1 AND urutan > 0 ORDER BY urutan ASC LIMIT 4");
          $videos_db = $stmt_vid->fetchAll();
        } catch (Exception $e) {
          $videos_db = [];
        }
        if (empty($videos_db)) {
          $videos_db = [
            ['video_id'=>'F615KRrMRno','url'=>'https://youtu.be/F615KRrMRno','judul'=>'Kajian Islami - Masjid Raya Darussalam Samarinda','kategori'=>'Kajian'],
            ['video_id'=>'4ImiQyULfoI','url'=>'https://youtu.be/4ImiQyULfoI','judul'=>'Kegiatan Keagamaan Masjid Raya Darussalam','kategori'=>'Kegiatan'],
            ['video_id'=>'UnuiYOydiWI','url'=>'https://youtu.be/UnuiYOydiWI','judul'=>'Sholat Berjamaah di Masjid Raya Darussalam','kategori'=>'Ibadah'],
            ['video_id'=>'U3z7DpPbvko','url'=>'https://www.youtube.com/live/U3z7DpPbvko','judul'=>'Video Terbaru - Masjid Raya Darussalam','kategori'=>'Terbaru'],
          ];
        }
        foreach ($videos_db as $vid):
          $vid_url   = htmlspecialchars($vid['url'], ENT_QUOTES);
          $vid_judul = htmlspecialchars($vid['judul']);
          $vid_kat   = htmlspecialchars($vid['kategori']);
          $vid_thumb = 'https://img.youtube.com/vi/' . htmlspecialchars($vid['video_id']) . '/hqdefault.jpg';
        ?>
        <div class="col-12 col-sm-6 col-lg-3">
          <div class="vid-card-wrap">
            <div class="vid-thumb-card" onclick="openVideo('<?= $vid_url ?>')">
              <div class="vid-thumb-img">
                <img src="<?= $vid_thumb ?>" alt="<?= $vid_kat ?>"
                    onerror="this.parentElement.style.background='var(--green-800)';this.style.display='none'">
                <div class="vid-play-btn"><i class="fa-solid fa-play"></i></div>
                <div class="vid-category-badge"><?= $vid_kat ?></div>
              </div>
              <div class="vid-card-info">
                <div class="vid-card-channel"><i class="fa-brands fa-youtube" style="color:#ff0000;font-size:.8rem"></i> Masjid Raya Darussalam</div>
                <div class="vid-card-title"><?= $vid_judul ?></div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <div class="text-center mt-5">
      <a href="https://www.youtube.com/@MasjidRayaDarussalam" target="_blank" rel="noopener noreferrer" class="btn-green" style="border-radius:50px">
        <i class="fa-brands fa-youtube"></i> Kunjungi Channel YouTube Kami
      </a>
    </div>
  </div>
</section>

<div id="vid-modal" style="display:none;position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,.85);align-items:center;justify-content:center">
  <div style="position:relative;width:90%;max-width:820px">
    <button onclick="closeVideo()" style="position:absolute;top:-2.5rem;right:0;background:none;border:none;color:white;font-size:1.5rem;cursor:pointer;opacity:.8"><i class="fa-solid fa-xmark"></i></button>
    <div style="position:relative;padding-top:56.25%;background:#000;border-radius:var(--radius);overflow:hidden">
      <iframe id="vid-iframe" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%"></iframe>
    </div>
  </div>
</div>

<section class="cta-section" style="padding:5rem 0">
  <div class="container text-center" style="position:relative;z-index:1">
    <div class="fade-up">
      <div style="font-family:var(--font-arabic);font-size:2.5rem;color:var(--gold-300);margin-bottom:.5rem">أهلاً وسهلاً</div>
      <h2 style="font-family:var(--font-display);font-size:2.2rem;font-weight:700;color:var(--white);margin-bottom:.75rem">Selamat Datang di Masjid<br>Raya Darussalam</h2>
      <p style="color:rgba(255,255,255,.65);max-width:520px;margin:0 auto 2rem;line-height:1.85">Kunjungi kami dan rasakan ketenangan serta kedamaian spiritual yang sesungguhnya di jantung Kota Samarinda.</p>
      <div class="d-flex justify-content-center flex-wrap gap-3">
        <a href="views/detail.php" class="btn-primary"><i class="fa-solid fa-map-location-dot"></i> Lokasi &amp; Fasilitas</a>
        <a href="views/ulasan.php" class="btn-outline"><i class="fa-solid fa-pen-to-square"></i> Beri Ulasan</a>
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
        <a href="index.php" class="footer-link"><i class="fa-solid fa-house fa-fw"></i> Beranda</a>
        <a href="views/detail.php" class="footer-link"><i class="fa-solid fa-mosque fa-fw"></i> Detail &amp; Fasilitas</a>
        <a href="views/ulasan.php" class="footer-link"><i class="fa-regular fa-comment-dots fa-fw"></i> Ulasan</a>
        <a href="views/login.php"   class="footer-link"><i class="fa-regular fa-user fa-fw"></i> Admin</a>
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
<script>
  window.APP_BASE = '<?= BASE_URL ?>';
  function openVideo(url) {
      let videoId = '';
      const match = url.match(/[?&]v=([^&?#]+)/) || url.match(/youtu\.be\/([^?&#]+)/) || url.match(/\/embed\/([^?&#]+)/) || url.match(/\/live\/([^?&#]+)/);
      if (match) videoId = match[1];
      const iframe = document.getElementById('vid-iframe');
      if (iframe && videoId) {
          iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
          document.getElementById('vid-modal').style.display = 'flex';
      }
  }
  function closeVideo() {
      const iframe = document.getElementById('vid-iframe');
      if (iframe) iframe.src = '';
      document.getElementById('vid-modal').style.display = 'none';
  }
</script>
<script src="assets/js/controller.js"></script>
<script src="assets/js/vue-prayer.js"></script>
<script>
  MasjidCtrl.PageInit.home();
  VuePrayerApp('#prayer-app');
</script>
</body>
</html>