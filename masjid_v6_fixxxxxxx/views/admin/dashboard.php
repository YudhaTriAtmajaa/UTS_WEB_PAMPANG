<?php
require_once __DIR__ . '/../../includes/config.php';
requireAdmin();
$activePage = 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%231a4731'/%3E%3Crect x='7' y='16' width='18' height='13' fill='%232e7d5a' rx='1'/%3E%3Cellipse cx='16' cy='16' rx='6' ry='2' fill='%232e7d5a'/%3E%3Cpath d='M10 16 Q10 9 16 8 Q22 9 22 16' fill='%232e7d5a'/%3E%3Cpolygon points='16,3 15,8 17,8' fill='%23c9a84c'/%3E%3Ccircle cx='16' cy='3' r='1.2' fill='%23c9a84c'/%3E%3Crect x='5' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='7,9 5.5,13 8.5,13' fill='%23c9a84c'/%3E%3Crect x='23' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='25,9 23.5,13 26.5,13' fill='%23c9a84c'/%3E%3Crect x='11' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3Crect x='18' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3C/svg%3E">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin - Masjid Raya Darussalam</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="admin-wrap">

<?php include __DIR__ . '/_sidebar.php'; ?>

<main class="admin-main">
  <div class="admin-topbar">
    <div>
      <div class="topbar-title">
        <i class="fa-solid fa-chart-line" style="color:var(--gold-500)"></i> Dashboard
      </div>
      <div class="topbar-sub">Ringkasan data &amp; aktivitas Masjid Raya Darussalam</div>
    </div>
    </div>

  <div class="admin-content">

    <div class="row g-3 mb-4">
      <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-regular fa-comment-dots"></i></div>
          <div><div class="stat-value" id="stat-total">-</div><div class="stat-label">Total Ulasan</div></div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
          <div class="stat-icon gold"><i class="fa-solid fa-check-circle"></i></div>
          <div><div class="stat-value" id="stat-approved">-</div><div class="stat-label">Disetujui</div></div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
          <div class="stat-icon gray"><i class="fa-regular fa-clock"></i></div>
          <div><div class="stat-value" id="stat-pending">-</div><div class="stat-label">Menunggu</div></div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
          <div class="stat-icon gold"><i class="fa-solid fa-star"></i></div>
          <div><div class="stat-value" id="stat-avg">-</div><div class="stat-label">Avg Rating</div></div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-building-columns"></i></div>
          <div><div class="stat-value" id="stat-fac">-</div><div class="stat-label">Fasilitas</div></div>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg-2">
        <div class="stat-card">
          <div class="stat-icon gray"><i class="fa-solid fa-images"></i></div>
          <div><div class="stat-value" id="stat-gal">-</div><div class="stat-label">Foto Galeri</div></div>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="data-table-wrap">
        <div class="data-table-head" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.5rem">
          <h6 style="margin:0;display:flex;align-items:center;gap:.6rem">
            <i class="fa-regular fa-clock" style="color:var(--gold-500)"></i>
            Ulasan Menunggu Persetujuan
            <span id="pending-count-badge"
              style="background:var(--red-500);color:#fff;font-size:.62rem;font-weight:800;padding:.1rem .45rem;border-radius:50px;min-width:18px;text-align:center;display:none">
            </span>
          </h6>
          <a href="kelola_ulasan.php" class="act-btn act-approve" style="padding:.38rem .85rem;font-size:.76rem">
            <i class="fa-solid fa-arrow-right"></i> Kelola di sini
          </a>
        </div>
        <div style="overflow-x:auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th><th>Pengunjung</th><th>Rating</th><th>Ulasan</th><th>Foto</th><th>Tanggal</th>
              </tr>
            </thead>
            <tbody id="pending-readonly-tbody">
              <tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--gray-400)">
                <i class="fa-solid fa-spinner fa-spin"></i> Memuat...
              </td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <div class="data-table-wrap">
        <div class="data-table-head">
          <h6><i class="fa-solid fa-star" style="color:var(--gold-500)"></i> 5 Ulasan Terbaru (Disetujui)</h6>
        </div>
        <div style="overflow-x:auto">
          <table class="data-table">
            <thead>
              <tr>
                <th>ID</th><th>Pengunjung</th><th>Rating</th><th>Ulasan</th><th>Foto</th><th>Tanggal</th>
              </tr>
            </thead>
            <tbody id="latest-tbody">
              <tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--gray-400)">
                <i class="fa-solid fa-spinner fa-spin"></i> Memuat...
              </td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  <?php include __DIR__ . '/_footer.php'; ?>
</main>
</div>

<div class="modal fade" id="photo-preview-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:640px">
    <div class="modal-content" style="border-radius:var(--radius);background:#111;border:none">
      <div class="modal-header" style="border:none;padding:.65rem 1rem;background:rgba(0,0,0,.5)">
        <small style="color:rgba(255,255,255,.55);font-size:.8rem" id="photo-preview-name"></small>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0 text-center" style="background:#000;border-radius:0 0 var(--radius) var(--radius)">
        <img id="photo-preview-img" src="" alt="Preview foto"
          style="max-width:100%;max-height:75vh;object-fit:contain;display:block;margin:0 auto">
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>window.APP_BASE = '<?= BASE_URL ?>';</script>
<script src="../../assets/js/controller.js"></script>
<script>MasjidCtrl.PageInit.dashboard();</script>
</body>
</html>