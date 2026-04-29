<?php
require_once __DIR__ . '/../../includes/config.php';
requireAdmin();
$activePage = 'ulasan'; 
$id = (int)($_GET['id'] ?? 0);
$from = htmlspecialchars($_GET['from'] ?? 'semua');

if (!$id) { header('Location: kelola_ulasan.php'); exit; } 
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%231a4731'/%3E%3Crect x='7' y='16' width='18' height='13' fill='%232e7d5a' rx='1'/%3E%3Cellipse cx='16' cy='16' rx='6' ry='2' fill='%232e7d5a'/%3E%3Cpath d='M10 16 Q10 9 16 8 Q22 9 22 16' fill='%232e7d5a'/%3E%3Cpolygon points='16,3 15,8 17,8' fill='%23c9a84c'/%3E%3Ccircle cx='16' cy='3' r='1.2' fill='%23c9a84c'/%3E%3Crect x='5' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='7,9 5.5,13 8.5,13' fill='%23c9a84c'/%3E%3Crect x='23' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='25,9 23.5,13 26.5,13' fill='%23c9a84c'/%3E%3Crect x='11' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3Crect x='18' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3C/svg%3E">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Ulasan - Masjid Raya Darussalam</title>
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
      <div class="topbar-title"><i class="fa-solid fa-pen" style="color:var(--gold-500)"></i> Edit Ulasan</div>
      <div class="topbar-sub">Perbarui data ulasan pengunjung</div>
    </div>
    <a href="kelola_ulasan.php?tab=<?= $from ?>" class="act-btn act-view" style="padding:.5rem 1rem;font-size:.82rem"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
  </div>

  <div class="admin-content">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div class="form-panel">
            
          <div class="text-center mb-2">
            <div style="width:52px;height:52px;background:linear-gradient(135deg,var(--green-500),var(--green-800));border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem">
              <i class="fa-solid fa-pen" style="color:var(--white);font-size:1.25rem"></i>
            </div>
            <h5 style="font-family:var(--font-display);color:var(--green-800);font-weight:700;margin-bottom:.3rem">Edit Ulasan</h5>
            <p style="font-size:.82rem;color:var(--gray-400)">Perbarui data ulasan pengunjung secara manual</p>
          </div>
          <div style="height:2px;background:linear-gradient(to right,transparent,var(--gold-500),transparent);margin:.5rem 0 1.5rem"></div>

          <div class="alert-error mb-3" id="not-found" style="display:none;align-items:center;gap:.5rem">
            <i class="fa-solid fa-circle-xmark"></i> Ulasan tidak ditemukan.
          </div>
          <div id="form-loading" style="text-align:center;padding:2.5rem;color:var(--gray-400)">
            <i class="fa-solid fa-spinner fa-spin" style="font-size:1.5rem"></i><br>
            <span style="font-size:.85rem;margin-top:.5rem;display:block">Memuat data ulasan...</span>
          </div>

          <form id="edit-form" novalidate style="display:none">
            <input type="hidden" id="e-id" value="<?= $id ?>">
            
            <div style="margin-bottom:1.1rem">
              <label class="field-label">
                <i class="fa-solid fa-star" style="color:var(--gold-500)"></i>
                Rating <span class="field-required">*</span>
              </label>
              <div class="star-picker" style="justify-content: flex-end; width: max-content; margin-top: .35rem;">
                <input type="radio" id="e-star5" name="e_rating" value="5"><label for="e-star5" title="5 Bintang (Luar Biasa)"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="e-star4" name="e_rating" value="4"><label for="e-star4" title="4 Bintang (Bagus)"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="e-star3" name="e_rating" value="3"><label for="e-star3" title="3 Bintang (Cukup)"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="e-star2" name="e_rating" value="2"><label for="e-star2" title="2 Bintang (Kurang)"><i class="fa-solid fa-star"></i></label>
                <input type="radio" id="e-star1" name="e_rating" value="1"><label for="e-star1" title="1 Bintang (Buruk)"><i class="fa-solid fa-star"></i></label>
              </div>
              <div id="e-rating-label" style="font-size:.78rem;color:var(--gray-400);margin-top:.25rem;min-height:1.2em;font-style:italic">Luar Biasa!</div>
              <div id="e-rating-error" style="color:#dc2626;font-size:.75rem;margin-top:.2rem;display:none"><i class="fa-solid fa-circle-exclamation"></i> <span class="msg"></span></div>
            </div>

            <div style="margin-bottom:.85rem">
              <label class="field-label" for="e-name">
                <i class="fa-solid fa-user"></i> Nama Lengkap <span class="field-required">*</span>
              </label>
              <input type="text" id="e-name" class="field-input" maxlength="15" required>
              <div id="e-name-error" style="color:#dc2626;font-size:.75rem;margin-top:.2rem;display:none"><i class="fa-solid fa-circle-exclamation"></i> <span class="msg"></span></div>
            </div>

            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="field-label" for="e-kota">
                  <i class="fa-solid fa-location-dot"></i> Asal Kota
                </label>
                <input type="text" id="e-kota" class="field-input" maxlength="20">
                <div id="e-kota-error" style="color:#dc2626;font-size:.75rem;margin-top:.2rem;display:none"><i class="fa-solid fa-circle-exclamation"></i> <span class="msg"></span></div>
              </div>
              <div class="col-md-6">
                <label class="field-label" for="e-status"><i class="fa-solid fa-circle-dot"></i> Status</label>
                <select id="e-status" class="field-select">
                  <option value="approved">Disetujui</option>
                  <option value="pending">Menunggu</option>
                </select>
              </div>
            </div>

            <div style="margin-bottom:.85rem">
              <label class="field-label" for="e-text">
                <i class="fa-regular fa-comment-dots"></i> Teks Ulasan <span class="field-required">*</span>
              </label>
              <textarea id="e-text" class="field-textarea" rows="4" style="resize:vertical" maxlength="500"></textarea>
              <div id="e-text-count" style="font-size:.72rem;color:var(--gray-400);margin-top:.25rem;text-align:right">
                0 / 500 karakter
              </div>
              <div id="e-text-error" style="color:#dc2626;font-size:.75rem;margin-top:.2rem;display:none"><i class="fa-solid fa-circle-exclamation"></i> <span class="msg"></span></div>
            </div>

            <div class="mb-4">
              <label class="field-label"><i class="fa-regular fa-image"></i> Foto Pendukung</label>
              
              <div id="e-current-photo-wrap" style="display:none;margin-bottom:.75rem">
                <p style="font-size:.76rem;color:var(--gray-400);margin-bottom:.4rem">Foto saat ini:</p>
                <div class="review-photo-preview-box">
                  <img id="e-current-photo" src="" alt="Foto ulasan" style="width:100%;height:180px;object-fit:cover;display:block">
                  <button type="button" id="e-remove-photo" class="review-photo-remove" title="Hapus foto">
                    <i class="fa-solid fa-trash"></i>
                  </button>
                </div>
              </div>

              <div id="e-photo-dropzone" class="upload-zone" onclick="document.getElementById('e-photo').click()" style="padding:1.25rem;cursor:pointer;">
                <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem"></i>
                <p style="margin-top:.35rem">Klik untuk mengganti foto</p>
                <span style="font-size:.72rem;color:var(--gray-400)">JPG, PNG, WEBP - Maks. 5MB</span>
                <input type="file" id="e-photo" accept="image/*" style="display:none">
              </div>

              <div id="e-new-photo-wrap" style="display:none;margin-top:.65rem">
                <p style="font-size:.76rem;color:var(--gray-400);margin-bottom:.4rem">Foto baru yang dipilih:</p>
                <div class="review-photo-preview-box">
                  <img id="e-new-photo" src="" alt="Preview" style="width:100%;height:180px;object-fit:cover;display:block">
                  <button type="button" class="review-photo-remove" onclick="document.getElementById('e-photo').value=''; document.getElementById('e-new-photo-wrap').style.display='none'; document.getElementById('e-photo-dropzone').style.display='block';">
                    <i class="fa-solid fa-xmark"></i>
                  </button>
                </div>
              </div>
            </div>

            <button type="submit" id="edit-submit-btn" class="btn-green w-100" style="justify-content:center;margin-bottom:.5rem;">
              <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
            <a href="kelola_ulasan.php?tab=<?= $from ?>" class="btn-outline w-100" style="justify-content:center;color:var(--gray-700);border-color:var(--gray-300);">
              <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include __DIR__ . '/_footer.php'; ?>
</main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script>window.APP_BASE = '<?= BASE_URL ?>';</script>
<script src="../../assets/js/controller.js"></script>
<script>MasjidCtrl.PageInit.aksiEdit();</script>

</body>
</html>