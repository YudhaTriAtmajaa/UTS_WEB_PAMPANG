<?php
require_once 'config.php';
$pageTitle  = 'Home';
$activePage = 'home';
include 'header.php';
?>

<!-- ===== HERO ===== -->
<section class="hero" id="home">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content container-fluid px-4 px-lg-5">
    <div class="row">
      <div class="col-lg-7">
        <div class="d-flex align-items-center gap-3 mb-4" data-aos="fade-right" data-aos-delay="100">
          <div style="width:40px;height:1px;background:var(--gold)"></div>
          <span style="font-size:11px;letter-spacing:3px;text-transform:uppercase;color:var(--gold);font-weight:600">
            Warisan Budaya Dayak Kenyah
          </span>
        </div>
        <h1 data-aos="fade-up" data-aos-delay="200">
          Desa Wisata<br><em>Budaya Pampang</em>
        </h1>
        <p class="hero-desc mt-4 mb-4" data-aos="fade-up" data-aos-delay="350">
          Menjelajahi kekayaan budaya suku Dayak Kenyah yang telah berabad-abad melestarikan tradisi, seni, dan kearifan lokal di jantung Kalimantan Timur.
        </p>
        <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="500">
          <a href="about.php" class="btn-pampang btn-pampang-primary">
            Jelajahi Sekarang <i class="bi bi-arrow-right"></i>
          </a>
          <a href="services.php" class="btn-pampang btn-pampang-outline">
            <i class="bi bi-map me-1"></i> Lihat Wisata
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="hero-scroll">
    <div class="hero-scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- ===== STATS STRIP ===== -->
<div class="stats-strip">
  <div class="container-xl px-4 px-lg-5">
    <div class="row g-0 text-center">
      <div class="col-6 col-md-3 py-4">
        <div class="stat-number">1991</div>
        <div class="stat-label">Tahun Berdiri</div>
      </div>
      <div class="col-6 col-md-3 py-4 border-start border-secondary border-opacity-25">
        <div class="stat-number">50+</div>
        <div class="stat-label">Keluarga Dayak</div>
      </div>
      <div class="col-6 col-md-3 py-4 border-start border-secondary border-opacity-25">
        <div class="stat-number">12</div>
        <div class="stat-label">Jenis Tarian</div>
      </div>
      <div class="col-6 col-md-3 py-4 border-start border-secondary border-opacity-25">
        <div class="stat-number">300m</div>
        <div class="stat-label">Panjang Rumah Lamin</div>
      </div>
    </div>
  </div>
</div>

<!-- ===== INTRO ===== -->
<section class="py-5 py-lg-0" style="padding-top:100px!important;padding-bottom:100px!important;background:var(--bg-dark)">
  <div class="container-xl px-4 px-lg-5">
    <div class="row g-5 align-items-center">
      <!-- Images -->
      <div class="col-lg-5" data-aos="fade-right">
        <div class="about-img-stack">
          <img src="gambar.php?tabel=foto_galeri&id=1" alt="Tarian Dayak">
          <img src="gambar.php?tabel=foto_galeri&id=2" alt="Desa Pampang">
        </div>
      </div>
      <div class="col-lg-7" data-aos="fade-left" data-aos-delay="150">
        <div class="section-label">Tentang Pampang</div>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(34px,4vw,50px);font-weight:600;color:var(--cream);line-height:1.15;margin-bottom:24px">
          Warisan Leluhur yang<br><em style="font-style:italic;color:var(--gold)">Tetap Hidup</em>
        </h2>
        <p style="color:var(--text-muted);font-size:15px;line-height:1.9;margin-bottom:16px">
          Desa Budaya Pampang adalah permukiman asli suku Dayak Kenyah di Kecamatan Samarinda Utara. Di sini, tradisi bukan sekadar sejarah - melainkan nafas kehidupan sehari-hari.
        </p>
        <p style="color:var(--text-muted);font-size:15px;line-height:1.9;margin-bottom:32px">
          Setiap ukiran, setiap langkah tarian, dan setiap anyaman adalah cerita tentang hubungan manusia dengan alam dan leluhur yang tak pernah putus.
        </p>

        <div class="row g-3 mb-4">
          <?php
          $features = [
            ['bi-music-note-beamed', 'Pertunjukan Rutin', 'Setiap Minggu'],
            ['bi-house-door',        'Rumah Lamin Asli',  'Kayu Ulin'],
            ['bi-gem',               'Kerajinan Tangan',  'Manik & Ukiran'],
            ['bi-tree',              'Kearifan Lokal',    'Harmoni Alam'],
          ];
          foreach ($features as [$icon, $title, $sub]): ?>
          <div class="col-6">
            <div class="d-flex align-items-start gap-3">
              <div class="intro-feature-icon flex-shrink-0">
                <i class="bi <?= $icon ?>"></i>
              </div>
              <div>
                <strong style="display:block;font-size:13px;color:var(--cream);margin-bottom:2px"><?= $title ?></strong>
                <span style="font-size:12px;color:var(--text-muted)"><?= $sub ?></span>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <a href="about.php" class="btn-pampang btn-pampang-primary">
          Pelajari Lebih Lanjut <i class="bi bi-arrow-right"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- ===== HIGHLIGHTS ===== -->
<section style="padding:100px 0;background:var(--bg-section)">
  <div class="container-xl px-4 px-lg-5 mb-5">
    <div class="text-center" data-aos="fade-up">
      <div class="section-label" style="justify-content:center">Daya Tarik Utama</div>
      <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(34px,4.5vw,52px);font-weight:600;color:var(--cream);margin-bottom:14px">
        Pengalaman yang<br>Tak Terlupakan
      </h2>
      <p style="color:var(--text-muted);font-size:15px;max-width:500px;margin:0 auto;line-height:1.8">
        Dari tarian adat hingga kerajinan tangan, setiap sudut Desa Pampang menyimpan keajaiban budaya.
      </p>
    </div>
  </div>

  <div class="highlights-grid" data-aos="fade-up" data-aos-delay="100">
    <?php
    $highlights = [
  ['gambar.php?tabel=wisata&id=1',
  'Seni Pertunjukan','Tarian Adat Dayak Kenyah',
  'Saksikan keindahan gerak tari yang menceritakan keberanian dan penghormatan alam.'],
  ['gambar.php?tabel=wisata&id=2',
  'Arsitektur','Rumah Lamin',
  'Rumah adat komunal yang mencapai 300 meter dari kayu Ulin.'],
  ['gambar.php?tabel=wisata&id=3',
  'Kerajinan','Seni Manik & Ukiran',
  'Kerajinan tangan khas dengan motif burung enggang yang memukau.'],
  ['gambar.php?tabel=wisata&id=4',
  'Event','Pertunjukan Mingguan',
  'Setiap Minggu pukul 14.00 WITA, sajikan tradisi budaya lengkap.'],
  ['gambar.php?tabel=wisata&id=5',
  'Tradisi','Tradisi Leluhur',
  'Sosok para nenek bertelinga panjang, simbol ketangguhan budaya Dayak Kenyah.'],
];

    foreach ($highlights as $idx => [$img, $tag, $title, $desc]): ?>
    <div class="highlight-card <?= $idx === 3 ? 'highlight-card-full' : '' ?>">
      <img src="<?= e($img) ?>" alt="<?= e($title) ?>" loading="lazy">
      <div class="highlight-overlay"></div>
      <div class="highlight-info">
        <span class="highlight-tag"><?= e($tag) ?></span>
        <div class="highlight-title"><?= e($title) ?></div>
        <div class="highlight-desc"><?= e($desc) ?></div>
        <a href="services.php" class="highlight-link">Lihat Detail <i class="bi bi-arrow-right"></i></a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===== ULASAN TEASER ===== -->
<?php
$stmt = $pdo->query("SELECT * FROM ulasan WHERE is_active=1 ORDER BY tanggal DESC LIMIT 3");
$latestReviews = $stmt->fetchAll();
if ($latestReviews): ?>
<section style="padding:80px 0;background:var(--bg-section)">
  <div class="container-xl px-4 px-lg-5">
    <div class="d-flex justify-content-between align-items-end mb-5 flex-wrap gap-3">
      <div data-aos="fade-right">
        <div class="section-label">Ulasan Pengunjung</div>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(30px,3.5vw,44px);font-weight:600;color:var(--cream)">
          Apa Kata Mereka?
        </h2>
      </div>
      <a href="ulasan.php" class="btn-pampang btn-pampang-ghost" data-aos="fade-left">
        Semua Ulasan <i class="bi bi-arrow-right"></i>
      </a>
    </div>
    <div class="row g-3">
      <?php foreach ($latestReviews as $i => $r): ?>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
        <div class="review-card">
          <div class="review-header">
            <div class="d-flex align-items-center gap-3">
              <div class="review-avatar"><?= strtoupper(mb_substr($r['nama'],0,1)) ?></div>
              <div>
                <div class="review-nama"><?= e($r['nama']) ?></div>
                <div class="review-tanggal">
                  <i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($r['tanggal'])) ?>
                </div>
              </div>
            </div>
            <div><?= renderStars($r['bintang'], true) ?></div>
          </div>
          <p class="review-body"><?= e(mb_substr($r['isi'],0,160)) . (mb_strlen($r['isi'])>160?'…':'') ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===== MAP / LOCATION ===== -->
<section class="map-section-embed">
  <div class="container-xl px-4 px-lg-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-label" style="justify-content:center">Lokasi</div>
      <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(30px,4vw,46px);font-weight:600;color:var(--cream);margin-bottom:12px">
        Temukan <em style="color:var(--gold)">Desa Pampang</em>
      </h2>
      <p style="color:var(--text-muted);font-size:15px;max-width:480px;margin:0 auto">
        Kecamatan Samarinda Utara, Kota Samarinda - sekitar 20 km dari pusat kota
      </p>
    </div>
    <div class="map-embed-wrapper" data-aos="fade-up" data-aos-delay="100">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3990.1!2d117.2300917!3d-0.3774807!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2df5d9b0f0b2b7ad%3A0xf30e84b529acc826!2sWisata%20Budaya%20Pampang!5e0!3m2!1sid!2sid!4v1713000000001!5m2!1sid!2sid"
        width="100%"
        height="480"
        style="border:0;border-radius:12px;display:block;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"
        title="Lokasi Desa Budaya Pampang, Samarinda Utara">
      </iframe>
    </div>
    <div class="text-center mt-4" data-aos="fade-up">
      <a href="https://maps.app.goo.gl/9GFdP7iSsmwhdj518" target="_blank" rel="noopener noreferrer" class="btn-pampang btn-pampang-outline">
        <i class="bi bi-box-arrow-up-right me-1"></i> Buka di Google Maps
      </a>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
