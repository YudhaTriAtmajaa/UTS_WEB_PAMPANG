<?php
require_once __DIR__ . '/../../includes/config.php';
requireAdmin();
$activePage = 'fasilitas';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%231a4731'/%3E%3Crect x='7' y='16' width='18' height='13' fill='%232e7d5a' rx='1'/%3E%3Cellipse cx='16' cy='16' rx='6' ry='2' fill='%232e7d5a'/%3E%3Cpath d='M10 16 Q10 9 16 8 Q22 9 22 16' fill='%232e7d5a'/%3E%3Cpolygon points='16,3 15,8 17,8' fill='%23c9a84c'/%3E%3Ccircle cx='16' cy='3' r='1.2' fill='%23c9a84c'/%3E%3Crect x='5' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='7,9 5.5,13 8.5,13' fill='%23c9a84c'/%3E%3Crect x='23' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='25,9 23.5,13 26.5,13' fill='%23c9a84c'/%3E%3Crect x='11' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3Crect x='18' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3C/svg%3E">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Fasilitas - Masjid Raya Darussalam</title>
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
      <div class="topbar-title"><i class="fa-solid fa-building-columns" style="color:var(--gold-500)"></i> Kelola Fasilitas</div>
      <div class="topbar-sub">Manajemen data fasilitas Masjid Raya Darussalam <span id="stat-fac-count">-</span> fasilitas</div>
    </div>
    <button class="act-btn act-approve" style="padding:.5rem 1rem;font-size:.82rem" onclick="window._ctrl.openNewFac()">
      <i class="fa-solid fa-plus"></i> Tambah Fasilitas
    </button>
  </div>

  <div class="admin-content">
    <div class="data-table-wrap">
      <div class="data-table-head"><h6><i class="fa-solid fa-building-columns"></i> Daftar Fasilitas</h6></div>
      <div style="overflow-x:auto">
        <table class="data-table">
          <thead>
            <tr><th style="width:60px">No</th><th>Fasilitas</th><th style="width:120px">Kategori</th><th style="width:140px">Kapasitas</th><th style="width:130px">Status</th><th style="width:180px">Aksi</th></tr>
          </thead>
          <tbody id="fac-tbody">
            <tr><td colspan="6" class="text-center" style="padding:1.5rem;color:var(--gray-400)"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/_footer.php'; ?>
</main>
</div>

<div class="modal fade" id="fac-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="border-radius:var(--radius)">
      <div class="modal-header" style="background:var(--green-800);color:var(--white);border-radius:var(--radius) var(--radius) 0 0">
        <h6 class="modal-title" style="font-family:var(--font-display);display:flex;align-items:center;gap:.5rem">
          <i class="fa-solid fa-building-columns" style="color:var(--gold-300)"></i>
          <span id="fac-modal-title">Tambah Fasilitas</span>
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="fac-form" novalidate>
          <input type="hidden" id="fac-id">
          <input type="hidden" id="fac-remove-photo" value="0">
          <div class="row g-3 mb-3">
            <div class="col-md-8">
              <label class="field-label" for="fac-name"><i class="fa-solid fa-tag"></i> Nama Fasilitas <span class="field-required">*</span></label>
              <input type="text" id="fac-name" class="field-input" placeholder="cth: Ruang Sholat Utama" required>
            </div>
            <div class="col-md-4">
              <label class="field-label" for="fac-tag"><i class="fa-solid fa-layer-group"></i> Kategori</label>
              <select id="fac-tag" class="field-select">
                <option value="Ibadah">Ibadah</option>
                <option value="Sanitasi">Sanitasi</option>
                <option value="Pendidikan">Pendidikan</option>
                <option value="Fasilitas Umum">Fasilitas Umum</option>
                <option value="Parkir">Parkir</option>
                <option value="Outdoor">Outdoor</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="field-label" for="fac-capacity"><i class="fa-solid fa-users"></i> Kapasitas</label>
              <input type="text" id="fac-capacity" class="field-input" placeholder="cth: 10.000 Jamaah">
            </div>
            <div class="col-md-6">
              <label class="field-label" for="fac-status"><i class="fa-solid fa-circle-dot"></i> Status</label>
              <select id="fac-status" class="field-select">
                <option value="Tersedia">Tersedia</option>
                <option value="Tidak Tersedia">Tidak Tersedia</option>
                <option value="Dalam Renovasi">Dalam Renovasi</option>
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label class="field-label" for="fac-desc"><i class="fa-regular fa-comment-dots"></i> Deskripsi</label>
            <textarea id="fac-desc" class="field-textarea" rows="3" style="resize:vertical" placeholder="Deskripsi singkat fasilitas..."></textarea>
          </div>
          <div class="mb-3">
            <label class="field-label"><i class="fa-regular fa-image"></i> Foto Fasilitas <span style="color:var(--gray-400);font-weight:400;font-size:.76rem">(opsional, maks. 5MB)</span></label>
            <div id="fac-current-photo-wrap" style="display:none;margin-bottom:.75rem">
              <p style="font-size:.76rem;color:var(--gray-400);margin-bottom:.4rem">Foto saat ini:</p>
              <div style="display:flex;align-items:flex-start;gap:.75rem;flex-wrap:wrap">
                <img id="fac-current-photo" src="" alt="Foto fasilitas" style="max-width:180px;height:120px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--gray-200)">
                <button type="button" id="fac-remove-photo-btn" style="background:#fff1f2;color:#dc2626;border:1px solid #fecaca;border-radius:var(--radius-sm);padding:.35rem .75rem;font-size:.78rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.35rem;align-self:flex-end">
                  <i class="fa-solid fa-trash-can"></i> Hapus Foto
                </button>
              </div>
            </div>
            <input type="file" id="fac-photo" accept="image/*" style="display:none">
            <label for="fac-photo" class="upload-zone" id="fac-upload-zone" style="padding:1rem;cursor:pointer;display:block">
              <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.2rem"></i>
              <p style="margin-top:.25rem;font-size:.85rem">Klik untuk memilih foto baru</p>
              <span style="font-size:.72rem;color:var(--gray-400)">JPG, PNG, WEBP - Maks. 5MB</span>
            </label>
            <div id="fac-new-photo-wrap" style="display:none;margin-top:.6rem">
              <p style="font-size:.76rem;color:var(--gray-400);margin-bottom:.3rem">Preview foto baru:</p>
              <div style="display:flex;align-items:flex-start;gap:.75rem;flex-wrap:wrap">
                <img id="fac-new-photo" src="" alt="Preview" style="max-width:180px;height:120px;object-fit:cover;border-radius:var(--radius-sm);border:2px solid var(--green-500)">
                <button type="button" id="fac-cancel-new-photo" style="background:#f0fdf4;color:var(--green-700);border:1px solid var(--green-200);border-radius:var(--radius-sm);padding:.35rem .75rem;font-size:.78rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.35rem;align-self:flex-end">
                  <i class="fa-solid fa-xmark"></i> Batal
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 gap-2 pb-4">
        <button type="button" class="act-btn act-view" data-bs-dismiss="modal" style="padding:.6rem 1.25rem">Batal</button>
        <button type="button" class="btn-green" style="border-radius:var(--radius-sm)" onclick="window._ctrl.saveFac()">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Fasilitas
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="delete-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:var(--radius);border:2px solid var(--red-500)">
      <div class="modal-header" style="background:var(--red-500);color:var(--white);border-radius:var(--radius) var(--radius) 0 0 !important">
        <h6 class="modal-title" style="font-family:var(--font-display);display:flex;align-items:center;gap:.5rem"><i class="fa-solid fa-trash"></i> Konfirmasi Hapus</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <i class="fa-solid fa-triangle-exclamation" style="font-size:2.5rem;color:var(--red-500);margin-bottom:.75rem;display:block"></i>
        <p style="font-weight:700;color:var(--gray-800);margin-bottom:.4rem">Yakin ingin menghapus data ini?</p>
        <p style="font-size:.84rem;color:var(--gray-500)">Foto yang terkait juga akan dihapus dari server.</p>
        <input type="hidden" id="del-id"><input type="hidden" id="del-type">
      </div>
      <div class="modal-footer justify-content-center gap-2 border-0 pt-0 pb-4">
        <button type="button" class="act-btn act-view" data-bs-dismiss="modal" style="padding:.55rem 1.25rem">Batal</button>
        <button type="button" class="act-btn act-delete" style="padding:.55rem 1.25rem" onclick="window._ctrl.doDelete()"><i class="fa-solid fa-trash"></i> Ya, Hapus</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>window.APP_BASE = '<?= BASE_URL ?>';</script>
<script src="../../assets/js/controller.js"></script>
<script>MasjidCtrl.PageInit.kelolaFasilitas();</script>

</body>
</html>