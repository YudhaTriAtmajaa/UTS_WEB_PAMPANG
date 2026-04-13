<?php
require_once 'config.php';
$pageTitle  = 'Daya Tarik Wisata';
$activePage = 'services';

$stmt = $pdo->query("SELECT * FROM wisata ORDER BY id ASC");
$wisataItems = $stmt->fetchAll();

include 'header.php';
?>

<!-- PAGE HEADER -->
<div class="page-header">
  <div class="container-xl px-4 px-lg-5 page-header-inner">
    <div class="breadcrumb-pampang">
      <a href="index.php">Home</a><span>›</span><span>Daya Tarik Wisata</span>
    </div>
    <h1>Daya Tarik<br><em>Wisata</em></h1>
    <p>Eksplorasi kekayaan budaya dan pengalaman autentik Dayak Kenyah yang tak tertandingi.</p>
  </div>
</div>

<!-- ATTRACTION GRID -->
<section style="padding:40px 0 80px;background:var(--bg-section)">
  <div class="container-xl px-4 px-lg-5">
    <div class="attraction-grid">

      <!-- Tarian Adat -->
      <div class="attraction-item featured gallery-item" data-category="seni" data-aos="fade-up">
        <div class="attraction-img">
          <span class="attraction-tag"><i class="bi bi-star-fill me-1"></i>Featured</span>
          <img src="gambar.php?tabel=wisata&id=1" alt="Tarian Adat" loading="lazy">
        </div>
        <div class="attraction-body">
          <div class="section-label">Seni & Budaya</div>
          <h3>Tarian Adat<br>Dayak Kenyah</h3>
          <div class="attraction-meta">
            <div class="attraction-meta-item"><i class="bi bi-clock"></i> Setiap Minggu 14.00</div>
            <div class="attraction-meta-item"><i class="bi bi-geo-alt"></i> Halaman Lamin</div>
          </div>
          <p>Tarian adat Dayak Kenyah adalah jendela jiwa - setiap gerakan merupakan bahasa tubuh yang menceritakan hubungan manusia dengan alam, leluhur, dan sesama. Dari Tari Kancet Ledo yang gemulai hingga Tari Kancet Papatai yang gagah berani.</p>
          <button class="btn-pampang btn-pampang-ghost mt-2" data-modal="modalTarian">
            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- Rumah Lamin -->
      <div class="attraction-item gallery-item" data-category="arsitektur" data-aos="fade-up" data-aos-delay="100">
        <div class="attraction-img">
          <span class="attraction-tag">Arsitektur</span>
          <img src="gambar.php?tabel=wisata&id=2" alt="Rumah Lamin" loading="lazy">
        </div>
        <div class="attraction-body">
          <div class="section-label">Warisan Arsitektur</div>
          <h3>Rumah Lamin Dayak</h3>
          <div class="attraction-meta">
            <div class="attraction-meta-item"><i class="bi bi-rulers"></i> Hingga 300m</div>
            <div class="attraction-meta-item"><i class="bi bi-tree"></i> Kayu Ulin</div>
          </div>
          <p>Simbol persatuan dan kebersamaan komunitas Dayak Kenyah. Satu rumah, puluhan keluarga, satu jiwa.</p>
          <button class="btn-pampang btn-pampang-ghost mt-2" data-modal="modalLamin">
            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- Kerajinan -->
      <div class="attraction-item gallery-item" data-category="kerajinan" data-aos="fade-up" data-aos-delay="100">
        <div class="attraction-img">
          <span class="attraction-tag">Kerajinan</span>
          <img src="gambar.php?tabel=wisata&id=3" alt="Kerajinan" loading="lazy">
        </div>
        <div class="attraction-body">
          <div class="section-label">Seni Kerajinan</div>
          <h3>Kerajinan Manik & Ukiran</h3>
          <div class="attraction-meta">
            <div class="attraction-meta-item"><i class="bi bi-palette"></i> Motif Khas</div>
            <div class="attraction-meta-item"><i class="bi bi-bag-check"></i> Tersedia Dijual</div>
          </div>
          <p>Setiap manik yang dirangkai adalah ekspresi identitas budaya yang membutuhkan ketelitian dan dedikasi bertahun-tahun.</p>
          <button class="btn-pampang btn-pampang-ghost mt-2" data-modal="modalKerajinan">
            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

      <!-- Pertunjukan -->
      <div class="attraction-item gallery-item" data-category="event" data-aos="fade-up" data-aos-delay="200">
        <div class="attraction-img">
          <span class="attraction-tag">Event Rutin</span>
          <img src="gambar.php?tabel=wisata&id=4" alt="Pertunjukan" loading="lazy">
        </div>
        <div class="attraction-body">
          <div class="section-label">Pertunjukan Budaya</div>
          <h3>Pertunjukan Mingguan</h3>
          <div class="attraction-meta">
            <div class="attraction-meta-item"><i class="bi bi-calendar-week"></i> Setiap Minggu</div>
            <div class="attraction-meta-item"><i class="bi bi-hourglass-split"></i> ±2 Jam</div>
          </div>
          <p>Pertunjukan budaya lengkap yang menampilkan tarian, musik tradisional, dan interaksi langsung dengan komunitas.</p>
          <button class="btn-pampang btn-pampang-ghost mt-2" data-modal="modalPertunjukan">
            Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
          </button>
        </div>
      </div>

<!-- Tradisi Leluhur -->
        <div class="attraction-item gallery-item" data-category="tradisi" data-aos="fade-up" data-aos-delay="250">
          <div class="attraction-img">
            <span class="attraction-tag">Tradisi</span>
            <img src="gambar.php?tabel=wisata&id=5" alt="Tradisi Leluhur" loading="lazy">
          </div>
          <div class="attraction-body">
            <div class="section-label">Warisan Leluhur</div>
            <h3>Tradisi Leluhur</h3>
            <div class="attraction-meta">
              <div class="attraction-meta-item"><i class="bi bi-calendar-check"></i> Setiap Hari</div>
              <div class="attraction-meta-item"><i class="bi bi-geo-alt"></i> Desa Pampang</div>
            </div>
            <p>Sosok para nenek bertelinga panjang, simbol ketangguhan budaya Dayak Kenyah yang masih terjaga hingga kini.</p>
            <button class="btn-pampang btn-pampang-ghost mt-2" data-modal="tradisiLeluhur">
              Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
            </button>
          </div>
        </div>

    </div>
  </div>
</section>

<!-- ===== EXPERIENCE STRIP ===== -->
<div class="experience-strip">
  <div class="container-xl px-4 px-lg-5">
    <div class="row g-3">
      <?php
      $experiences = [
        ['bi-people','Interaksi Langsung','Berinteraksi dengan warga setempat, mencoba pakaian adat, dan belajar langsung dari pembuat kerajinan berpengalaman.'],
        ['bi-music-note-list','Musik Tradisional','Dengarkan harmoni alat musik khas Dayak seperti Sape, Gong, dan Sampe yang menciptakan atmosfer magis dan autentik.'],
        ['bi-basket2','Wisata Kuliner','Cicipi makanan tradisional khas Dayak Kenyah yang menggunakan bahan-bahan alami dari hutan Kalimantan.'],
      ];
      foreach ($experiences as $i => [$icon, $title, $desc]): ?>
      <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?= $i * 100 ?>">
        <div class="experience-item">
          <div class="experience-icon"><i class="bi <?= $icon ?>"></i></div>
          <h4 style="font-size:16px;font-weight:700;color:var(--cream);margin-bottom:14px"><?= $title ?></h4>
          <p style="font-size:13px;color:var(--text-muted);line-height:1.9;margin:0"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- ===== TIPS ===== -->
<section class="tips-section">
  <div class="container-xl px-4 px-lg-5">
    <div class="text-center mb-5" data-aos="fade-up">
      <div class="section-label" style="justify-content:center">Panduan Wisata</div>
      <h2 style="font-family:'Cormorant Garamond',serif;font-size:clamp(30px,4vw,46px);font-weight:600;color:var(--cream)">
        Tips Berkunjung<br><em style="color:var(--gold);font-style:italic">ke Pampang</em>
      </h2>
      <p style="color:var(--text-muted);font-size:15px;max-width:500px;margin:14px auto 0;line-height:1.8">
        Agar kunjungan Anda lebih berkesan dan bermakna.
      </p>
    </div>
    <div class="row g-3">
      <?php
      $tips = [
        ['01','bi-calendar-check','Datang Hari Minggu','Pertunjukan budaya terlengkap digelar setiap Minggu pukul 14.00 WITA. Datanglah lebih awal untuk mendapat tempat terbaik dan sempat menjelajahi area desa sebelum pertunjukan.'],
        ['02','bi-person-check','Kenakan Pakaian Sopan','Hormati adat setempat dengan berpakaian sopan dan menutup bahu serta lutut saat memasuki area adat. Anda juga bisa menyewa pakaian adat Dayak untuk foto.'],
        ['03','bi-person-badge','Gunakan Pemandu Lokal','Pemandu lokal dapat memberikan penjelasan mendalam tentang makna di balik setiap tarian dan kerajinan. Ini sangat memperkaya pengalaman wisata Anda.'],
        ['04','bi-bag-heart','Beli Souvenir Asli','Dukung pengrajin lokal dengan membeli kerajinan tangan asli buatan warga. Hindari replika massal dan pilih produk langsung dari tangan pembuatnya.'],
      ];
      foreach ($tips as $i => [$num, $icon, $title, $desc]): ?>
      <div class="col-md-6" data-aos="fade-up" data-aos-delay="<?= ($i % 2) * 100 ?>">
        <div class="tip-card">
          <div class="tip-num"><?= $num ?></div>
          <div>
            <h4><i class="bi <?= $icon ?> text-gold me-2"></i><?= $title ?></h4>
            <p><?= $desc ?></p>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===== MODALS ===== -->

<!-- Modal: Tarian -->
<div class="pampang-modal" id="modalTarian">
  <div class="pampang-modal-overlay"></div>
  <div class="pampang-modal-box">
    <button class="modal-close-btn"><i class="bi bi-x-lg"></i></button>
    <div class="modal-img-wrap"><img src="gambar.php?tabel=wisata&id=1" alt="Tarian Adat"></div>
    <div class="modal-body-inner">
      <span class="modal-tag"><i class="bi bi-music-note-beamed me-1"></i>Seni & Budaya</span>
      <h3>Tarian Adat Dayak Kenyah</h3>
      <p>Tarian adat Dayak Kenyah memiliki beragam jenis yang masing-masing memiliki makna dan fungsi tersendiri dalam kehidupan sosial dan spiritual masyarakat.</p>
      <p>Setiap gerakan, kostum, dan iringan musik adalah narasi visual yang telah diwariskan selama berabad-abad dari generasi ke generasi.</p>
      <ul class="modal-detail-list">
        <li><strong>Kancet Ledo</strong> Tarian perempuan yang lembut dan anggun, melambangkan keindahan alam</li>
        <li><strong>Kancet Papatai</strong> Tarian perang yang menggambarkan keberanian dan kegagahan prajurit</li>
        <li><strong>Tari Hudoq</strong> Tarian ritual panen dengan topeng kayu yang sakral</li>
        <li><strong><i class="bi bi-clock me-1"></i>Jadwal</strong> Setiap Minggu, 14.00 WITA</li>
        <li><strong><i class="bi bi-hourglass me-1"></i>Durasi</strong> ± 90 menit pertunjukan lengkap</li>
      </ul>
    </div>
  </div>
</div>

<!-- Modal: Lamin -->
<div class="pampang-modal" id="modalLamin">
  <div class="pampang-modal-overlay"></div>
  <div class="pampang-modal-box">
    <button class="modal-close-btn"><i class="bi bi-x-lg"></i></button>
    <div class="modal-img-wrap"><img src="gambar.php?tabel=wisata&id=2" alt="Rumah Lamin"></div>
    <div class="modal-body-inner">
      <span class="modal-tag"><i class="bi bi-house-door me-1"></i>Arsitektur Tradisional</span>
      <h3>Rumah Lamin Dayak</h3>
      <p>Rumah Lamin adalah mahakarya arsitektur komunal Dayak Kenyah. Dibangun tanpa paku menggunakan teknik tradisional yang telah terbukti bertahan ratusan tahun.</p>
      <ul class="modal-detail-list">
        <li><strong><i class="bi bi-rulers me-1"></i>Panjang</strong> Hingga 300 meter</li>
        <li><strong><i class="bi bi-tree me-1"></i>Material</strong> Kayu Ulin (besi), tahan ratusan tahun</li>
        <li><strong><i class="bi bi-people me-1"></i>Penghuni</strong> 50-60 kepala keluarga</li>
        <li><strong><i class="bi bi-bookmark me-1"></i>Fungsi</strong> Tempat tinggal, ritual adat, penyimpanan pusaka</li>
        <li><strong><i class="bi bi-brush me-1"></i>Hiasan</strong> Ukiran motif enggang dan naga</li>
      </ul>
    </div>
  </div>
</div>

<!-- Modal: Kerajinan -->
<div class="pampang-modal" id="modalKerajinan">
  <div class="pampang-modal-overlay"></div>
  <div class="pampang-modal-box">
    <button class="modal-close-btn"><i class="bi bi-x-lg"></i></button>
    <div class="modal-img-wrap"><img src="gambar.php?tabel=wisata&id=3" alt="Kerajinan"></div>
    <div class="modal-body-inner">
      <span class="modal-tag"><i class="bi bi-gem me-1"></i>Seni Kerajinan</span>
      <h3>Kerajinan Manik & Ukiran</h3>
      <p>Kerajinan Dayak Kenyah adalah perpaduan seni dan filosofi. Setiap motif memiliki makna - dari simbol status sosial hingga perlindungan spiritual.</p>
      <ul class="modal-detail-list">
        <li><strong><i class="bi bi-palette me-1"></i>Motif Utama</strong> Kalong (burung enggang) dan Naga</li>
        <li><strong><i class="bi bi-box me-1"></i>Material</strong> Manik kaca, kayu ulin, rotan</li>
        <li><strong><i class="bi bi-bag me-1"></i>Produk</strong> Kalung, ikat kepala, pakaian adat, ukiran</li>
        <li><strong><i class="bi bi-shop me-1"></i>Beli</strong> Langsung dari pengrajin di area desa</li>
        <li><strong><i class="bi bi-cash-coin me-1"></i>Harga</strong> Rp 25.000 – Rp 2.000.000</li>
      </ul>
    </div>
  </div>
</div>

<!-- Modal: Pertunjukan -->
<div class="pampang-modal" id="modalPertunjukan">
  <div class="pampang-modal-overlay"></div>
  <div class="pampang-modal-box">
    <button class="modal-close-btn"><i class="bi bi-x-lg"></i></button>
    <div class="modal-img-wrap"><img src="gambar.php?tabel=wisata&id=4" alt="Pertunjukan"></div>
    <div class="modal-body-inner">
      <span class="modal-tag"><i class="bi bi-calendar-event me-1"></i>Event Budaya</span>
      <h3>Pertunjukan Mingguan</h3>
      <p>Setiap Minggu sore, Desa Pampang berubah menjadi panggung budaya hidup yang menampilkan kekayaan seni Dayak Kenyah dalam satu pertunjukan komprehensif.</p>
      <p>Pengunjung tidak hanya menyaksikan, tetapi juga diundang untuk berpartisipasi - mencoba pakaian adat, berfoto bersama penari, dan belajar gerakan dasar tarian.</p>
      <ul class="modal-detail-list">
        <li><strong><i class="bi bi-clock me-1"></i>Jadwal</strong> Setiap Minggu, 14.00 WITA</li>
        <li><strong><i class="bi bi-geo-alt me-1"></i>Lokasi</strong> Halaman Rumah Lamin Pampang</li>
        <li><strong><i class="bi bi-hourglass me-1"></i>Durasi</strong> ± 90 – 120 menit</li>
        <li><strong><i class="bi bi-ticket me-1"></i>Tiket</strong> Sangat terjangkau, hubungi kami</li>
        <li><strong><i class="bi bi-camera me-1"></i>Interaktif</strong> Foto dengan penari, coba pakaian adat</li>
      </ul>
    </div>
  </div>
</div>

<!-- Modal:Tradisi Leluhur -->
<div class="pampang-modal" id="tradisiLeluhur">
  <div class="pampang-modal-overlay"></div>
  <div class="pampang-modal-box">
    <button class="modal-close-btn"><i class="bi bi-x-lg"></i></button>
    <div class="modal-img-wrap"><img src="gambar.php?tabel=wisata&id=5" alt="Tradisi Leluhur"></div>
    <div class="modal-body-inner">
      <span class="modal-tag"><i class="bi bi-calendar-event me-1"></i>Event Budaya</span>
      <h3>Tradisi Leluhur</h3>
      <p>Sosok para nenek bertelinga panjang atau Telingaan Aruu merupakan jiwa dari keaslian budaya di Desa Pampang, di mana kehadiran mereka memberikan dimensi sejarah yang nyata bagi setiap pengunjung.</p>
      <p>Keberadaan mereka bukan sekadar objek foto, melainkan simbol ketangguhan tradisi yang masih bertahan di tengah gempuran zaman modern, memperlihatkan bagaimana standar kecantikan dan kehormatan di masa lalu tetap dijaga dengan penuh martabat.</p>
      <ul class="modal-detail-list">
        <li><strong><i class="bi bi-clock me-1"></i>Jadwal</strong> Setiap Hari Bisa Dikunjungi</li>
        <li><strong><i class="bi bi-geo-alt me-1"></i>Lokasi</strong> Desa Wisata Budaya Pampang</li>
        <li><strong><i class="bi bi-hourglass me-1"></i>Umur</strong> 70-80 tahun</li>
        <li><strong><i class="bi bi-ticket me-1"></i>Bahan Anting</strong> Logam berat, paling sering adalah tembaga atau kuningan</li>
        <li><strong><i class="bi bi-camera me-1"></i>Pengguna</strong> Wanita Dayak</li>
      </ul>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
