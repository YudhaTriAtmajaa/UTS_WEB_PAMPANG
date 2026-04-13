<?php
require_once 'config.php';
$pageTitle  = 'Ulasan Pengunjung';
$activePage = 'ulasan';

$formMsg   = '';
$formError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = trim($_POST['nama']    ?? '');
    $bintang = (int)($_POST['bintang'] ?? 0);
    $isi     = trim($_POST['isi']     ?? '');
    $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));

    // Validation
    $errors = [];
    if (empty($nama) || mb_strlen($nama) < 2)         $errors[] = 'Nama minimal 2 karakter.';
    if ($bintang < 1 || $bintang > 5)                 $errors[] = 'Pilih jumlah bintang (1–5).';
    if (empty($isi)  || mb_strlen($isi) < 10)         $errors[] = 'Ulasan minimal 10 karakter.';
    if (empty($tanggal) || !strtotime($tanggal))      $errors[] = 'Tanggal tidak valid.';

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO ulasan (nama, bintang, isi, tanggal) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$nama, $bintang, $isi, $tanggal]);
        $formMsg = 'Terima kasih, ulasan Anda telah berhasil dikirimkan!';
        header("Location: ulasan.php?sent=1");
        exit;
    } else {
        $formError = implode(' ', $errors);
    }
}

if (isset($_GET['sent'])) {
    $formMsg = 'Terima kasih, ulasan Anda telah berhasil dikirimkan!';
}

$reviewStmt = $pdo->query("SELECT * FROM ulasan WHERE is_active=1 ORDER BY tanggal DESC, id DESC");
$reviews = $reviewStmt->fetchAll();

$totalReviews = count($reviews);
$avgRating    = 0;
$ratingCounts = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];

foreach ($reviews as $r) {
    $avgRating += $r['bintang'];
    $ratingCounts[$r['bintang']]++;
}
$avgRating = $totalReviews ? round($avgRating / $totalReviews, 1) : 0;

include 'header.php';
?>

<!-- PAGE HEADER -->
<div class="page-header">
  <div class="container-xl px-4 px-lg-5 page-header-inner">
    <div class="breadcrumb-pampang">
      <a href="index.php">Home</a><span>›</span><span>Ulasan</span>
    </div>
    <h1>Ulasan<br><em>Pengunjung</em></h1>
    <p>Cerita nyata dari mereka yang telah merasakan keajaiban Desa Budaya Pampang.</p>
  </div>
</div>

<!-- ===== ULASAN SECTION ===== -->
<section class="ulasan-section">
  <div class="container-xl px-4 px-lg-5">

    <?php if ($formMsg): ?>
    <div class="alert-pampang alert-pampang-success mb-4" data-aos="fade-down">
      <i class="bi bi-check-circle me-2"></i><?= e($formMsg) ?>
    </div>
    <?php endif; ?>

    <!-- ===== RATING ===== -->
    <?php if ($totalReviews > 0): ?>
    <div class="rating-summary-h" data-aos="fade-up">
      <!-- Skor besar -->
      <div class="rsh-score">
        <div class="rating-big"><?= number_format($avgRating, 1) ?></div>
        <div class="d-flex gap-1 my-2"><?= renderStars((int)round($avgRating)) ?></div>
        <div style="font-size:12px;color:var(--text-muted)">
          <i class="bi bi-people me-1"></i><?= $totalReviews ?> Ulasan
        </div>
      </div>
      <div class="rsh-divider"></div>
      <div class="rsh-bars">
        <?php for ($star = 5; $star >= 1; $star--): ?>
        <?php $pct = $totalReviews ? round($ratingCounts[$star] / $totalReviews * 100) : 0; ?>
        <div class="rsh-bar-row">
          <span class="rsh-bar-label"><?= $star ?></span>
          <i class="bi bi-star-fill" style="color:var(--gold);font-size:11px"></i>
          <div class="rating-bar">
            <div class="rating-bar-fill" data-width="<?= $pct ?>"></div>
          </div>
          <span class="rsh-bar-count"><?= $ratingCounts[$star] ?>×</span>
        </div>
        <?php endfor; ?>
      </div>
    </div>
    <?php endif; ?>

    <div class="row g-5 mt-1">
      <div class="col-lg-4">
        <!-- Form -->
        <div class="ulasan-form-wrap" data-aos="fade-up" data-aos-delay="100">
          <h3 style="font-family:'Cormorant Garamond',serif;font-size:26px;font-weight:600;color:var(--cream);margin-bottom:6px">
            <i class="bi bi-pencil-square text-gold me-2"></i>Tulis Ulasan
          </h3>
          <p style="color:var(--text-muted);font-size:13px;margin-bottom:28px">
            Bagikan pengalaman Anda berkunjung ke Desa Pampang.
          </p>

          <?php if ($formError): ?>
          <div class="alert-pampang alert-pampang-error mb-4">
            <i class="bi bi-exclamation-triangle me-2"></i><?= e($formError) ?>
          </div>
          <?php endif; ?>

          <form method="POST" action="ulasan.php" id="ulasanForm" novalidate>

            <!-- Nama -->
            <div class="mb-4">
              <label class="form-label-custom">
                <i class="bi bi-person me-1"></i>Nama Lengkap <span style="color:#c84040">*</span>
              </label>
              <input type="text" name="nama" class="form-control-dark"
                    placeholder="Masukkan nama Anda"
                    value="<?= e($_POST['nama'] ?? '') ?>" required>
            </div>

            <!-- Rating -->
            <div class="mb-4">
              <label class="form-label-custom">
                <i class="bi bi-star me-1"></i>Penilaian <span style="color:#c84040">*</span>
              </label>
              <div class="star-rating" role="group" aria-label="Rating bintang">
                <?php for ($s = 5; $s >= 1; $s--): ?>
                <input type="radio" id="star<?= $s ?>" name="bintang" value="<?= $s ?>"
                      <?= (($_POST['bintang'] ?? 0) == $s) ? 'checked' : '' ?>>
                <label for="star<?= $s ?>" title="<?= $s ?> bintang">
                  <i class="bi bi-star-fill"></i>
                </label>
                <?php endfor; ?>
              </div>
              <div id="bintangError" style="display:none;color:#c84040;font-size:12px;margin-top:8px">
                <i class="bi bi-exclamation-circle me-1"></i>Pilih jumlah bintang terlebih dahulu.
              </div>
            </div>

            <!-- Tanggal -->
            <div class="mb-4">
              <label class="form-label-custom">
                <i class="bi bi-calendar3 me-1"></i>Tanggal Kunjungan <span style="color:#c84040">*</span>
              </label>
              <input type="date" name="tanggal" class="form-control-dark"
                    value="<?= e($_POST['tanggal'] ?? date('Y-m-d')) ?>"
                    max="<?= date('Y-m-d') ?>" required>
            </div>

            <!-- Isi Ulasan -->
            <div class="mb-4">
              <label class="form-label-custom">
                <i class="bi bi-chat-text me-1"></i>Isi Ulasan <span style="color:#c84040">*</span>
              </label>
              <textarea name="isi" class="form-control-dark" rows="5"
                        placeholder="Ceritakan pengalaman Anda berkunjung ke Desa Pampang..."
                        required><?= e($_POST['isi'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn-pampang btn-pampang-primary w-100">
              <i class="bi bi-send me-2"></i>Kirim Ulasan
            </button>

          </form>
        </div>

        <!-- Contact Info -->
        <div class="card-dark p-4 mt-4" data-aos="fade-up" data-aos-delay="200">
          <h5 style="color:var(--cream);font-size:14px;font-weight:700;margin-bottom:16px">
            <i class="bi bi-info-circle text-gold me-2"></i>Informasi Kontak
          </h5>
          <div class="d-flex flex-column gap-3">
            <a href="mailto:<?= SITE_EMAIL ?>" style="font-size:13px;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;gap:10px;transition:color .3s"
              onmouseover="this.style.color='var(--cream)'" onmouseout="this.style.color='var(--text-muted)'">
              <i class="bi bi-envelope text-gold"></i><?= SITE_EMAIL ?>
            </a>
            <a href="tel:<?= SITE_PHONE ?>" style="font-size:13px;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;gap:10px;transition:color .3s"
              onmouseover="this.style.color='var(--cream)'" onmouseout="this.style.color='var(--text-muted)'">
              <i class="bi bi-telephone text-gold"></i><?= SITE_PHONE ?>
            </a>
            <div style="font-size:13px;color:var(--text-muted);display:flex;align-items:flex-start;gap:10px">
              <i class="bi bi-geo-alt text-gold mt-1"></i>
              <span>Jl. Wisata Budaya Pampang, Kecamatan Samarinda Utara, Kota Samarinda, <br>Kalimantan Timur</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Review Cards -->
      <div class="col-lg-8">
        <?php if (empty($reviews)): ?>
        <div class="text-center py-5" style="color:var(--text-muted)">
          <i class="bi bi-chat-square-text" style="font-size:48px;margin-bottom:16px;display:block;color:var(--border)"></i>
          <p>Belum ada ulasan. Jadilah yang pertama!</p>
        </div>
        <?php else: ?>

        <!-- Filter Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3" data-aos="fade-up">
          <span id="filterLabel" style="font-size:13px;color:var(--text-muted)">
            Menampilkan <strong style="color:var(--cream)" id="filterCount"><?= $totalReviews ?></strong> ulasan
          </span>
          <div class="d-flex gap-2 flex-wrap">
            <button class="star-filter-btn active" data-star="0">
              Semua
            </button>
            <?php for ($s = 5; $s >= 1; $s--): if ($ratingCounts[$s] > 0): ?>
            <button class="star-filter-btn" data-star="<?= $s ?>">
              <i class="bi bi-star-fill"></i> <?= $s ?> <span style="opacity:.65">(<?= $ratingCounts[$s] ?>)</span>
            </button>
            <?php endif; endfor; ?>
          </div>
        </div>

        <!-- Reviews Grid -->
        <div class="row g-3" id="reviewsGrid">
          <?php foreach ($reviews as $i => $r): ?>
          <div class="col-md-6 review-item" data-star="<?= (int)$r['bintang'] ?>" data-aos="fade-up" data-aos-delay="<?= ($i % 4) * 60 ?>">
            <div class="review-card">
              <div class="review-header">
                <div class="d-flex align-items-center gap-3">
                  <div class="review-avatar"><?= strtoupper(mb_substr($r['nama'],0,1)) ?></div>
                  <div>
                    <div class="review-nama"><?= e($r['nama']) ?></div>
                    <div class="review-tanggal">
                      <i class="bi bi-calendar3 me-1"></i>
                      <?= date('d M Y', strtotime($r['tanggal'])) ?>
                    </div>
                  </div>
                </div>
                <div class="d-flex gap-1">
                  <?= renderStars($r['bintang'], true) ?>
                </div>
              </div>
              <p class="review-body mt-3"><?= e($r['isi']) ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div id="noFilterResult" class="text-center py-5" style="display:none;color:var(--text-muted)">
          <i class="bi bi-star" style="font-size:40px;display:block;margin-bottom:12px;color:var(--border)"></i>
          <p>Tidak ada ulasan dengan bintang yang dipilih.</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

<script>
// ===== FILTER BINTANG =====
(function () {
  const btns  = document.querySelectorAll('.star-filter-btn');
  const items = document.querySelectorAll('.review-item');
  const count = document.getElementById('filterCount');
  const noRes = document.getElementById('noFilterResult');

  btns.forEach(btn => {
    btn.addEventListener('click', function () {
      btns.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      const star = parseInt(this.dataset.star, 10);
      let visible = 0;

      items.forEach(item => {
        const match = (star === 0) || (parseInt(item.dataset.star, 10) === star);
        item.style.display = match ? '' : 'none';
        if (match) visible++;
      });

      if (count) count.textContent = visible;
      if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
    });
  });
})();
</script>

<?php include 'footer.php'; ?>
