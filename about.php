<?php
require_once 'config.php';
$pageTitle  = 'About';
$activePage = 'about';

// Fetch FAQ from database
$faqStmt = $pdo->query("SELECT * FROM faq WHERE is_active=1 ORDER BY urutan ASC");
$faqs = $faqStmt->fetchAll();

$faqByCategory = [];
$allCategories = [];
foreach ($faqs as $faq) {
    $cat = $faq['kategori'];
    if (!in_array($cat, $allCategories)) $allCategories[] = $cat;
    $faqByCategory[$cat][] = $faq;
}

include 'header.php';
?>

<!-- PAGE HEADER -->
<div class="page-header">
  <div class="container-xl px-4 px-lg-5 page-header-inner">
    <div class="breadcrumb-pampang">
      <a href="index.php">Home</a>
      <span>›</span><span>About</span>
    </div>
    <h1>Tentang<br><em>Desa Pampang</em></h1>
    <p>Mengenal lebih dekat warisan budaya Dayak Kenyah yang tak ternilai.</p>
  </div>
</div>

<!-- ===== ABOUT INTRO ===== -->
<section class="about-intro" style="background:var(--bg-dark);padding:80px 0">
  <div class="container-xl px-4 px-lg-5">
    <div class="row g-5 align-items-center">
      <!-- Images -->
      <div class="col-lg-5" data-aos="fade-right">
        <div class="about-img-stack">
          <img src="gambar.php?tabel=foto_galeri&id=3" alt="Tarian Dayak Kenyah">
          <img src="gambar.php?tabel=foto_galeri&id=4" alt="Desa Pampang">
          <div class="about-badge">55+<span>Tahun<br>Budaya</span></div>
        </div>
      </div>
      <div class="col-lg-7" data-aos="fade-left" data-aos-delay="150">
        <div class="section-label">Sejarah & Identitas</div>
        <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(32px,4vw,48px);font-weight:600;color:var(--cream);line-height:1.2;margin-bottom:24px">
          Akar yang Kuat,<br><em style="color:var(--gold);font-style:italic">Tradisi yang Hidup</em>
        </h2>
        <p style="color:var(--text-muted);font-size:15px;line-height:1.9;margin-bottom:18px">
          Desa Budaya Pampang adalah permukiman asli suku Dayak Kenyah yang terletak di Kecamatan Samarinda Utara, Kota Samarinda, Kalimantan Timur. Komunitas ini terbentuk sekitar tahun 1970-an ketika sekelompok Dayak Kenyah bermigrasi dari pedalaman Kalimantan.
        </p>
        <p style="color:var(--text-muted);font-size:15px;line-height:1.9;margin-bottom:18px">
          Meski berpindah tempat, semangat melestarikan adat istiadat tidak pernah padam. Pampang menjadi bukti nyata bahwa budaya dapat tetap lestari di tengah arus modernisasi. Setiap ukiran, tarian, dan kerajinan adalah warisan leluhur yang dijaga dengan penuh kebanggaan.
        </p>
        <p style="color:var(--text-muted);font-size:15px;line-height:1.9">
          Kini, Desa Pampang menjadi salah satu destinasi wisata budaya paling berpengaruh di Kalimantan Timur, menarik ribuan wisatawan domestik dan mancanegara setiap tahunnya.
        </p>
        <div class="d-flex gap-3 mt-4 flex-wrap">
          <a href="services.php" class="btn-pampang btn-pampang-primary">
            Lihat Daya Tarik <i class="bi bi-arrow-right"></i>
          </a>
          <a href="ulasan.php" class="btn-pampang btn-pampang-outline">
            <i class="bi bi-chat-square-text me-1"></i> Ulasan Pengunjung
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== STATS GRID ===== -->
<section class="stats-grid-section" style="background:var(--bg-section);padding:80px 0">
  <div class="container-xl px-4 px-lg-5">
    <div class="row g-0">
      <?php
      $statsData = [
        ['1970','','Tahun Berdiri'],
        ['50','+','Kepala Keluarga'],
        ['12','','Jenis Tarian'],
        ['300','m','Panjang Lamin'],
      ];
      foreach ($statsData as $i => [$num, $unit, $label]): ?>
      <div class="col-6 col-md-3" data-aos="zoom-in" data-aos-delay="<?= $i * 80 ?>">
        <div class="stat-block">
          <span class="stat-block-num" data-target="<?= $num ?>">0</span>
          <?php if ($unit): ?><span style="font-family:'Cormorant Garamond',serif;font-size:26px;color:var(--gold)"><?= $unit ?></span><?php endif; ?>
          <span class="stat-block-label"><?= $label ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== TIMELINE ===== -->
<section class="timeline-section" style="background:var(--bg-dark)">
  <div class="container-xl px-4 px-lg-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-label" style="justify-content:center">Perjalanan Sejarah</div>
      <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(30px,4vw,46px);font-weight:600;color:var(--cream)">
        Jejak Waktu<br><em style="color:var(--gold);font-style:italic">Desa Pampang</em>
      </h2>
    </div>
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="timeline">
          <?php
          $timeline = [
            ['1970','Migrasi dari Pedalaman','Sekelompok masyarakat Dayak Kenyah bermigrasi dari pedalaman Kabupaten Malinau menuju Samarinda, membawa seluruh tradisi dan adat istiadat mereka.'],
            ['1991','Diresmikan sebagai Desa Budaya','Pemerintah Provinsi Kalimantan Timur secara resmi menetapkan Pampang sebagai Desa Budaya - pengakuan atas nilai tinggi warisan budaya yang dijaga.'],
            ['1997','Pertunjukan Rutin Dimulai','Mulai diadakan pertunjukan budaya rutin setiap hari Minggu, mengundang wisatawan untuk menyaksikan tarian dan tradisi Dayak Kenyah secara langsung.'],
            ['2010','Renovasi Rumah Lamin','Rumah Lamin adat mengalami renovasi besar untuk mempertahankan keaslian arsitektur sambil meningkatkan kapasitas kunjungan wisata.'],
            ['2018','Penghargaan Wisata Budaya','Desa Pampang mendapatkan pengakuan sebagai salah satu destinasi wisata budaya terbaik di Kalimantan Timur dari Kementerian Pariwisata.'],
            ['2024','Era Digital & Ekspansi','Desa Pampang mulai memanfaatkan media digital untuk memperkenalkan budaya Dayak Kenyah kepada generasi muda dan wisatawan internasional.'],
          ];
          foreach ($timeline as $i => [$year, $title, $desc]): ?>
          <div class="timeline-item" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
            <div class="timeline-dot"></div>
            <div>
              <div class="timeline-year"><?= $year ?></div>
              <h4><?= $title ?></h4>
              <p><?= $desc ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>


<!-- ===== PILLAR ===== -->
<section style="padding:80px 0;background:var(--bg-section)">
  <div class="container-xl px-4 px-lg-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-label" style="justify-content:center">Nilai Inti</div>
      <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(32px,4vw,48px);font-weight:600;color:var(--cream)">
        Pilar Kehidupan<br><em style="color:var(--gold);font-style:italic">Budaya Pampang</em>
      </h2>
    </div>
    <div class="row g-3">
      <?php
      $pillars = [
        ['bi-music-note-beamed','Seni & Pertunjukan','Tarian adat Dayak Kenyah ditampilkan setiap minggu, menjaga makna dan estetika yang telah diwariskan selama berabad-abad.'],
        ['bi-house-heart','Arsitektur Komunal','Rumah Lamin - simbol kebersamaan dan persatuan - menjadi jantung kehidupan desa yang tetap dihuni hingga kini.'],
        ['bi-gem','Kerajinan Tangan','Manik-manik, ukiran kayu, dan anyaman rotan adalah ekspresi identitas yang dikerjakan dengan ketelitian dan cinta.'],
        ['bi-tree','Kearifan Lokal','Filosofi hidup berdamai dengan alam tercermin dalam setiap aspek kehidupan masyarakat Dayak Kenyah.'],
        ['bi-people','Komunitas Hidup','Lebih dari 50 kepala keluarga tinggal dan menjalani kehidupan sehari-hari di desa ini, menjadikannya budaya yang benar-benar hidup.'],
        ['bi-book','Pendidikan Budaya','Generasi muda dididik langsung oleh tetua dalam tradisi, bahasa, dan nilai-nilai leluhur sejak usia dini.'],
      ];
      foreach ($pillars as $i => [$icon, $title, $desc]): ?>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= ($i % 3) * 100 ?>">
        <div class="card-dark p-4 h-100">
          <div class="experience-icon mb-3">
            <i class="bi <?= $icon ?>"></i>
          </div>
          <h4 style="font-size:16px;font-weight:700;color:var(--cream);margin-bottom:12px"><?= $title ?></h4>
          <p style="font-size:13px;color:var(--text-muted);line-height:1.8;margin:0"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<section class="faq-section" id="faq">
  <div class="container-xl px-4 px-lg-5">
    <div class="row g-5">
      <div class="col-lg-4" data-aos="fade-right">
        <div style="position:sticky;top:110px">
          <div class="section-label">Pertanyaan Umum</div>
          <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(30px,3.5vw,44px);font-weight:600;color:var(--cream);line-height:1.2;margin-bottom:16px">
            Ada Pertanyaan?<br><em style="color:var(--gold);font-style:italic">Kami Siap Menjawab</em>
          </h2>
          <p style="color:var(--text-muted);font-size:14px;line-height:1.8;margin-bottom:28px">
            Temukan jawaban atas pertanyaan yang sering diajukan pengunjung tentang Desa Wisata Budaya Pampang.
          </p>
          <!-- Category Filter -->
          <div class="faq-tabs flex-column d-flex gap-2">
            <button class="faq-tab active" data-cat="all">
              <i class="bi bi-grid me-2"></i>Semua Kategori
            </button>
            <?php foreach ($allCategories as $cat): ?>
            <button class="faq-tab" data-cat="<?= e($cat) ?>">
              <?php
              $catIcon = match($cat) {
                'Jadwal'    => 'bi-calendar3',
                'Tiket'     => 'bi-ticket-perforated',
                'Lokasi'    => 'bi-geo-alt',
                'Fasilitas' => 'bi-building',
                default     => 'bi-question-circle',
              };
              ?>
              <i class="bi <?= $catIcon ?> me-2"></i><?= e($cat) ?>
            </button>
            <?php endforeach; ?>
          </div>
          <div class="mt-4 pt-4" style="border-top:1px solid var(--border)">
            <p style="color:var(--text-muted);font-size:13px">Tidak menemukan jawaban?</p>
            <a href="https://wa.me/081254993755" target="_blank" class="btn-pampang btn-pampang-ghost mt-2">
              <i class="bi bi-envelope me-1"></i> Hubungi Kami
            </a>
          </div>
        </div>
      </div>

      <!-- Accordion -->
      <div class="col-lg-8" data-aos="fade-left" data-aos-delay="100">
        <div class="accordion faq-accordion" id="faqAccordion">
          <?php foreach ($faqs as $faq): ?>
          <div class="faq-accordion-item-wrap" data-cat="<?= e($faq['kategori']) ?>">
            <div class="accordion-item faq-accordion-item mb-2">
              <h3 class="accordion-header">
                <button class="accordion-button collapsed d-flex align-items-center gap-3"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#faq<?= $faq['id'] ?>"
                        aria-expanded="false">
                  <span class="faq-num"><?= str_pad($faq['urutan'], 2, '0', STR_PAD_LEFT) ?></span>
                  <span class="flex-grow-1"><?= e($faq['pertanyaan']) ?></span>
                  <?php
                  $catBadgeIcon = match($faq['kategori']) {
                    'Jadwal'    => 'bi-calendar3',
                    'Tiket'     => 'bi-ticket-perforated',
                    'Lokasi'    => 'bi-geo-alt',
                    'Fasilitas' => 'bi-building',
                    default     => 'bi-tag',
                  };
                  ?>
                  <span class="faq-category-badge d-none d-md-flex align-items-center gap-1">
                    <i class="bi <?= $catBadgeIcon ?>"></i> <?= e($faq['kategori']) ?>
                  </span>
                </button>
              </h3>
              <div id="faq<?= $faq['id'] ?>" class="accordion-collapse collapse" data-bs-parent="">
                <div class="accordion-body">
                  <i class="bi bi-check-circle text-gold me-2"></i><?= e($faq['jawaban']) ?>
                </div>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Still have questions CTA -->
        <div class="card-dark p-4 mt-4 d-flex align-items-center gap-4 flex-wrap" data-aos="fade-up">
          <div class="experience-icon flex-shrink-0">
            <i class="bi bi-headset"></i>
          </div>
          <div>
            <h5 style="color:var(--cream);font-size:15px;font-weight:700;margin-bottom:6px">Masih ada pertanyaan?</h5>
            <p style="color:var(--text-muted);font-size:13px;margin:0">Hubungi kami langsung via WhatsApp atau email untuk informasi lebih lanjut.</p>
          </div>
          <div class="d-flex gap-2 ms-auto flex-wrap">
            <a href="https://wa.me/081254993755" target="_blank" class="btn-pampang btn-pampang-primary" style="padding:10px 20px">
              <i class="bi bi-whatsapp me-1"></i><?= SITE_PHONE ?>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<?php include 'footer.php'; ?>
