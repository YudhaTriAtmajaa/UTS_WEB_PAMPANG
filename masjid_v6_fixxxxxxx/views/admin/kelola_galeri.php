<?php
require_once __DIR__ . '/../../includes/config.php';
requireAdmin();
$activePage = 'galeri';
$csrf       = getCsrfToken();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%231a4731'/%3E%3Crect x='7' y='16' width='18' height='13' fill='%232e7d5a' rx='1'/%3E%3Cellipse cx='16' cy='16' rx='6' ry='2' fill='%232e7d5a'/%3E%3Cpath d='M10 16 Q10 9 16 8 Q22 9 22 16' fill='%232e7d5a'/%3E%3Cpolygon points='16,3 15,8 17,8' fill='%23c9a84c'/%3E%3Ccircle cx='16' cy='3' r='1.2' fill='%23c9a84c'/%3E%3Crect x='5' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='7,9 5.5,13 8.5,13' fill='%23c9a84c'/%3E%3Crect x='23' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='25,9 23.5,13 26.5,13' fill='%23c9a84c'/%3E%3Crect x='11' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3Crect x='18' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3C/svg%3E">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola Galeri - Masjid Raya Darussalam</title>
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
      <div class="topbar-title"><i class="fa-solid fa-images" style="color:var(--gold-500)"></i> Kelola Galeri & Video</div>
      <div class="topbar-sub">Manajemen foto galeri dan video YouTube Masjid Raya Darussalam</div>
    </div>
    <button class="act-btn act-approve" style="padding:.5rem 1rem;font-size:.82rem" id="btn-add-content" onclick="window._ctrl.openNewGal()">
      <i class="fa-solid fa-plus"></i> Tambah Foto
    </button>
  </div>

  <div class="admin-content">

    <div class="ku-nav" role="tablist">
      <button type="button" class="ku-nav-btn active" onclick="window._ctrl.switchGalTab('foto')" id="tab-btn-foto">
        <i class="fa-solid fa-images"></i> Foto Galeri
      </button>
      <button type="button" class="ku-nav-btn" onclick="window._ctrl.switchGalTab('video')" id="tab-btn-video">
        <i class="fa-brands fa-youtube"></i> Video YouTube
      </button>
    </div>

    <div id="tab-foto" class="ku-pane active">
      <div class="data-table-wrap" style="overflow:hidden">
        <div class="data-table-head"><h6><i class="fa-solid fa-images"></i> Foto Galeri</h6></div>
        <div class="row g-3 p-3" id="gallery-admin-grid">
          <div class="col-12 text-center" style="padding:2rem;color:var(--gray-400)">
            <i class="fa-solid fa-spinner fa-spin"></i> Memuat...
          </div>
        </div>
      </div>
    </div>

    <div id="tab-video" class="ku-pane">
      <div class="form-panel-video mb-4" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#6ee7b7">
        <div class="d-flex align-items-start gap-3">
          <i class="fa-solid fa-circle-info" style="color:var(--green-600);font-size:1.3rem;margin-top:.1rem"></i>
          <div style="font-size:.83rem;color:var(--green-800);line-height:1.8">
            <strong>Cara Kerja:</strong><br>
            • <strong>Urutan 1-4</strong> → tampil di slider homepage secara berurutan<br>
            • <strong>Urutan 0</strong> → video tersimpan tapi tidak tampil di homepage<br>
            • URL YouTube yang didukung: <code>youtu.be/ID</code>, <code>youtube.com/watch?v=ID</code>, <code>youtube.com/live/ID</code>, <code>youtube.com/shorts/ID</code>
          </div>
        </div>
      </div>

      <div class="data-table-wrap mb-2">
        <div class="data-table-head">
          <h6><i class="fa-solid fa-film"></i> Daftar Video</h6>
          <span id="vid-count" style="font-size:.8rem;color:var(--gray-400)">Memuat...</span>
        </div>
        <div class="admin-content" style="padding:1.25rem">
          <div id="vid-grid" class="vid-admin-grid">
            <div class="empty-state" style="grid-column:1/-1">
              <i class="fa-solid fa-spinner fa-spin"></i>
              <span>Memuat data video...</span>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <?php include __DIR__ . '/_footer.php'; ?>
</main>
</div>

<div class="modal fade" id="gal-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:var(--radius)">
      <div class="modal-header" style="background:var(--green-800);color:var(--white);border-radius:var(--radius) var(--radius) 0 0">
        <h6 class="modal-title" style="font-family:var(--font-display);display:flex;align-items:center;gap:.5rem">
          <i class="fa-solid fa-images" style="color:var(--gold-300)"></i>
          <span id="gal-modal-title">Tambah Foto</span>
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <form id="gal-form" novalidate>
          <input type="hidden" id="gal-id">
          <input type="hidden" id="gal-remove-photo" value="0">
          <div class="mb-3">
            <label class="field-label" for="gal-caption"><i class="fa-solid fa-quote-left"></i> Keterangan Foto <span class="field-required">*</span></label>
            <input type="text" id="gal-caption" class="field-input" placeholder="cth: Tampak Depan Masjid" required>
          </div>
          <div class="mb-3">
            <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;font-size:.84rem;font-weight:600;color:var(--gray-700)">
              <input type="checkbox" id="gal-featured" style="width:16px;height:16px;accent-color:var(--gold-500)">
              <i class="fa-solid fa-star" style="color:var(--gold-500)"></i>
              Tampilkan sebagai Foto Utama
            </label>
            <p style="font-size:.75rem;color:var(--gray-400);margin-top:.3rem;margin-left:1.5rem">Foto utama ditampilkan paling besar di galeri publik.</p>
          </div>
          <div class="mb-3">
            <label class="field-label"><i class="fa-regular fa-image"></i> File Foto <span class="field-required">*</span></label>
            <div id="gal-current-photo-wrap" style="display:none;margin-bottom:.75rem">
              <p style="font-size:.76rem;color:var(--gray-400);margin-bottom:.4rem">Foto saat ini:</p>
              <div style="display:flex;align-items:flex-start;gap:.75rem;flex-wrap:wrap">
                <img id="gal-current-photo" src="" alt="Foto galeri" style="max-width:200px;height:130px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--gray-200)">
                <button type="button" id="gal-remove-photo-btn" style="background:#fff1f2;color:#dc2626;border:1px solid #fecaca;border-radius:var(--radius-sm);padding:.35rem .75rem;font-size:.78rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.35rem;align-self:flex-end">
                  <i class="fa-solid fa-trash-can"></i> Hapus Foto
                </button>
              </div>
            </div>
            <input type="file" id="gal-photo" accept="image/*" style="display:none">
            <label for="gal-photo" class="upload-zone" id="gal-upload-zone" style="padding:1.25rem;cursor:pointer;display:block">
              <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem"></i>
              <p style="margin-top:.35rem">Klik untuk memilih foto baru</p>
              <span style="font-size:.72rem;color:var(--gray-400)">JPG, PNG, WEBP - Maks. 5MB</span>
            </label>
            <div id="gal-new-photo-wrap" style="display:none;margin-top:.65rem">
              <p style="font-size:.76rem;color:var(--gray-400);margin-bottom:.3rem">Preview foto baru:</p>
              <div style="display:flex;align-items:flex-start;gap:.75rem;flex-wrap:wrap">
                <img id="gal-new-photo" src="" alt="Preview" style="max-width:200px;height:130px;object-fit:cover;border-radius:var(--radius-sm);border:2px solid var(--green-500)">
                <button type="button" id="gal-cancel-new-photo" style="background:#f0fdf4;color:var(--green-700);border:1px solid var(--green-200);border-radius:var(--radius-sm);padding:.35rem .75rem;font-size:.78rem;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:.35rem;align-self:flex-end">
                  <i class="fa-solid fa-xmark"></i> Batal
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer border-0 gap-2 pb-4">
        <button type="button" class="act-btn act-view" data-bs-dismiss="modal" style="padding:.6rem 1.25rem">Batal</button>
        <button type="button" class="btn-green" style="border-radius:var(--radius-sm)" onclick="window._ctrl.saveGal()">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Foto
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="vid-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:640px">
    <div class="modal-content" style="border-radius:var(--radius);border:1.5px solid var(--gray-200)">
      <div class="modal-header" style="background:var(--green-800);color:white;border-radius:calc(var(--radius) - 2px) calc(var(--radius) - 2px) 0 0">
        <h6 class="modal-title" style="font-family:var(--font-display);display:flex;align-items:center;gap:.5rem">
          <i class="fa-brands fa-youtube" style="color:#ff0000"></i>
          <span id="modal-title-text">Tambah Video YouTube</span>
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4">
        <input type="hidden" id="vid-id">
        <input type="hidden" id="vid-csrf" value="<?= htmlspecialchars($csrf) ?>">
        <div class="row g-3">
          <div class="col-12">
            <label class="form-label-vid"><i class="fa-brands fa-youtube" style="color:#ff0000"></i> URL YouTube <span style="color:var(--red-500)">*</span></label>
            <input type="url" id="vid-url" class="form-control-vid" placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/...">
            <div class="urutan-hint">Paste URL video dari YouTube, lalu tunggu preview thumbnail muncul otomatis</div>
          </div>
          <div class="col-12">
            <div id="thumb-placeholder" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem;background:var(--gray-50);border-radius:var(--radius-sm);border:1px dashed var(--gray-200);gap:.5rem">
              <i class="fa-brands fa-youtube" style="font-size:2rem"></i>
              <span style="font-size:.8rem;color:var(--gray-400)">Thumbnail akan muncul setelah URL diisi</span>
            </div>
            <img id="thumb-preview" src="" alt="Preview Thumbnail" style="display:none;max-width:100%;border-radius:var(--radius-sm);margin-top:.75rem">
          </div>
          <div class="col-12">
            <label class="form-label-vid"><i class="fa-solid fa-heading"></i> Judul Video <span style="color:var(--red-500)">*</span></label>
            <input type="text" id="vid-judul" class="form-control-vid" placeholder="Contoh: Kajian Islami - Masjid Raya Darussalam">
          </div>
          <div class="col-6">
            <label class="form-label-vid"><i class="fa-solid fa-tag"></i> Kategori</label>
            <select id="vid-kategori" class="form-control-vid form-select-vid">
              <option value="Kajian">Kajian</option>
              <option value="Kegiatan">Kegiatan</option>
              <option value="Ibadah">Ibadah</option>
              <option value="Terbaru">Terbaru</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label-vid"><i class="fa-solid fa-arrow-up-1-9"></i> Urutan Tampil</label>
            <select id="vid-urutan" class="form-control-vid form-select-vid">
              <option value="0">0 - Tidak tampil</option>
              <option value="1">1 - Posisi 1</option>
              <option value="2">2 - Posisi 2</option>
              <option value="3">3 - Posisi 3</option>
              <option value="4">4 - Posisi 4</option>
            </select>
            <div class="urutan-hint">Slider homepage menampilkan maks. 4 video</div>
          </div>
          <div class="col-12">
            <label style="display:flex;align-items:center;gap:.65rem;cursor:pointer;user-select:none">
              <div style="position:relative;width:44px;height:24px;flex-shrink:0">
                <input type="checkbox" id="vid-aktif" checked style="opacity:0;width:0;height:0;position:absolute">
                <span id="toggle-track" style="position:absolute;inset:0;background:var(--green-500);border-radius:99px;transition:.3s;cursor:pointer"></span>
                <span id="toggle-thumb" style="position:absolute;top:3px;left:3px;width:18px;height:18px;background:white;border-radius:50%;transition:.3s;pointer-events:none"></span>
              </div>
              <span style="font-size:.85rem;font-weight:600;color:var(--gray-700)">Video Aktif (tampil di website)</span>
            </label>
          </div>
        </div>
      </div>
      <div class="modal-footer border-0 pt-0 pb-4 px-4 gap-2 justify-content-end">
        <button type="button" class="act-btn act-view" data-bs-dismiss="modal" style="padding:.55rem 1.25rem">Batal</button>
        <button type="button" class="act-btn act-approve" style="padding:.55rem 1.5rem" onclick="window._ctrl.vidSave()">
          <i class="fa-solid fa-floppy-disk"></i> <span id="save-btn-text">Simpan Video</span>
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
        <p style="font-weight:700;color:var(--gray-800);margin-bottom:.4rem">Yakin ingin menghapus item ini?</p>
        <p id="del-message" style="font-size:.84rem;color:var(--gray-500)"></p>
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
<script>MasjidCtrl.PageInit.kelolaGaleri();</script>

</body>
</html>