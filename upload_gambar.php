<?php
require_once 'config.php';

$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tabel = $_POST['tabel'] ?? '';
    $id    = (int)($_POST['id'] ?? 0);

    $allowedTables = ['wisata', 'foto_galeri'];
    if (!in_array($tabel, $allowedTables, true) || $id <= 0) {
        $message = 'Parameter tidak valid.';
        $msgType = 'danger';
    } elseif (empty($_FILES['gambar']['tmp_name'])) {
        $message = 'Tidak ada file yang dipilih.';
        $msgType = 'warning';
    } else {
        $file     = $_FILES['gambar'];
        $allowed  = ['image/jpeg','image/png','image/webp','image/gif'];
        $mime     = mime_content_type($file['tmp_name']);

        if (!in_array($mime, $allowed)) {
            $message = 'Format file tidak didukung. Gunakan JPG, PNG, WEBP, atau GIF.';
            $msgType = 'danger';
        } elseif ($file['size'] > 10 * 1024 * 1024) {
            $message = 'Ukuran file terlalu besar (maks 10 MB).';
            $msgType = 'danger';
        } else {
            $binaryData = file_get_contents($file['tmp_name']);
            try {
                $stmt = $pdo->prepare("UPDATE `{$tabel}` SET gambar = ?, gambar_mime = ? WHERE id = ?");
                $stmt->execute([$binaryData, $mime, $id]);
                $message = "Gambar berhasil disimpan ke tabel <strong>{$tabel}</strong> (ID: {$id}).";
                $msgType = 'success';
            } catch (PDOException $e) {
                $message = 'Gagal menyimpan gambar: ' . htmlspecialchars($e->getMessage());
                $msgType = 'danger';
            }
        }
    }
}

// Ambil data wisata & foto_galeri
$wisataList  = $pdo->query("SELECT id, nama, gambar_mime FROM wisata ORDER BY id")->fetchAll();
$galeriList  = $pdo->query("SELECT id, nama, halaman, gambar_mime FROM foto_galeri ORDER BY halaman, urutan")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Gambar - Admin Pampang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
body {
  background: #1a1008;
  color: #e8d5a0;
  font-family: 'Segoe UI', sans-serif;
}

.card {
  background: #231608;
  border: 1px solid #3d2b0f;
  border-radius: 12px;
}

.card-header {
  background: #2e1c08;
  border-bottom: 1px solid #3d2b0f;
  border-radius: 12px 12px 0 0 !important;
}

h1 {
  font-size: 26px;
  color: #c9a227;
}

.badge-mime {
  font-size: 10px;
  background: #3d2b0f;
  color: #c9a227;
  border-radius: 4px;
  padding: 2px 6px;
}

.preview-thumb {
  width: 60px;
  height: 45px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #3d2b0f;
}

.form-control,
.form-select {
  background: #2e1c08;
  border-color: #3d2b0f;
  color: #e8d5a0;
}

.form-control:focus,
.form-select:focus {
  background: #2e1c08;
  border-color: #c9a227;
  color: #e8d5a0;
  box-shadow: none;
}

label {
  color: #b09060;
  font-size: 13px;
}

.btn-upload {
  background: #c9a227;
  border: none;
  color: #1a1008;
  font-weight: 600;
}

.btn-upload:hover {
  background: #e0b830;
  color: #1a1008;
}

a.back-link {
  color: #c9a227;
  text-decoration: none;
  font-size: 14px;
}

a.back-link:hover {
  color: #e0b830;
}

.no-img {
  font-size: 11px;
  color: #6b5010;
}
  </style>
</head>
<body>
<div class="container py-5">
  <div class="d-flex align-items-center gap-3 mb-4">
    <a href="index.php" class="back-link"><i class="bi bi-arrow-left me-1"></i>Kembali ke Website</a>
    <span style="color:#3d2b0f">|</span>
    <h1 class="mb-0"><i class="bi bi-image me-2"></i>Upload Gambar ke Database</h1>
  </div>

  <?php if ($message): ?>
  <div class="alert alert-<?= $msgType ?> d-flex align-items-center mb-4" role="alert">
    <i class="bi bi-<?= $msgType==='success'?'check-circle':'exclamation-triangle' ?> me-2"></i>
    <?= $message ?>
  </div>
  <?php endif; ?>

  <div class="row g-4">
    <!-- Form Upload -->
    <div class="col-lg-5">
      <div class="card">
        <div class="card-header py-3">
          <h5 class="mb-0" style="color:#c9a227"><i class="bi bi-upload me-2"></i>Upload Gambar</h5>
        </div>
        <div class="card-body p-4">
          <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
              <label class="form-label">Tabel Tujuan</label>
              <select name="tabel" class="form-select" id="tabelSelect" required>
                <option value="">— Pilih Tabel —</option>
                <option value="wisata">wisata (Daya Tarik)</option>
                <option value="foto_galeri">foto_galeri (Galeri Halaman)</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">ID Record</label>
              <select name="id" class="form-select" id="idSelect" required>
                <option value="">— Pilih tabel dulu —</option>
              </select>
            </div>
            <div class="mb-4">
              <label class="form-label">File Gambar <span style="color:#6b5010">(JPG/PNG/WEBP/GIF, maks 10MB)</span></label>
              <input type="file" name="gambar" class="form-control" accept="image/*" required id="fileInput">
              <div id="previewWrap" class="mt-2 d-none">
                <img id="previewImg" src="" class="preview-thumb" alt="preview">
                <span id="previewName" class="ms-2" style="font-size:12px;color:#b09060"></span>
              </div>
            </div>
            <button type="submit" class="btn btn-upload w-100">
              <i class="bi bi-cloud-upload me-1"></i> Simpan ke Database
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Daftar Record -->
    <div class="col-lg-7">
      <!-- Wisata -->
      <div class="card mb-4">
        <div class="card-header py-3">
          <h5 class="mb-0" style="color:#c9a227"><i class="bi bi-map me-2"></i>Tabel: wisata</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm mb-0" style="color:#e8d5a0">
              <thead style="background:#2e1c08">
                <tr>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">ID</th>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">Nama</th>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">Status Gambar</th>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">Preview</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($wisataList as $w): ?>
                <tr style="border-color:#3d2b0f">
                  <td style="font-size:13px;border-color:#3d2b0f"><?= $w['id'] ?></td>
                  <td style="font-size:13px;border-color:#3d2b0f"><?= e($w['nama']) ?></td>
                  <td style="border-color:#3d2b0f">
                    <?php if ($w['gambar_mime']): ?>
                      <span class="badge-mime"><?= e($w['gambar_mime']) ?></span>
                    <?php else: ?>
                      <span class="no-img"><i class="bi bi-image me-1"></i>Belum ada</span>
                    <?php endif; ?>
                  </td>
                  <td style="border-color:#3d2b0f">
                    <?php if ($w['gambar_mime']): ?>
                      <img src="gambar.php?tabel=wisata&id=<?= $w['id'] ?>" class="preview-thumb" alt="">
                    <?php else: ?>
                      <span class="no-img">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Foto Galeri -->
      <div class="card">
        <div class="card-header py-3">
          <h5 class="mb-0" style="color:#c9a227"><i class="bi bi-images me-2"></i>Tabel: foto_galeri</h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm mb-0" style="color:#e8d5a0">
              <thead style="background:#2e1c08">
                <tr>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">ID</th>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">Nama</th>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">Halaman</th>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">Status Gambar</th>
                  <th style="color:#b09060;font-size:12px;border-color:#3d2b0f">Preview</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($galeriList as $g): ?>
                <tr style="border-color:#3d2b0f">
                  <td style="font-size:13px;border-color:#3d2b0f"><?= $g['id'] ?></td>
                  <td style="font-size:13px;border-color:#3d2b0f"><?= e($g['nama']) ?></td>
                  <td style="border-color:#3d2b0f"><span class="badge-mime"><?= e($g['halaman']) ?></span></td>
                  <td style="border-color:#3d2b0f">
                    <?php if ($g['gambar_mime']): ?>
                      <span class="badge-mime"><?= e($g['gambar_mime']) ?></span>
                    <?php else: ?>
                      <span class="no-img"><i class="bi bi-image me-1"></i>Belum ada</span>
                    <?php endif; ?>
                  </td>
                  <td style="border-color:#3d2b0f">
                    <?php if ($g['gambar_mime']): ?>
                      <img src="gambar.php?tabel=foto_galeri&id=<?= $g['id'] ?>" class="preview-thumb" alt="">
                    <?php else: ?>
                      <span class="no-img">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const wisataOptions = [
  <?php foreach ($wisataList as $w): ?>
  { id: <?= $w['id'] ?>, label: '<?= addslashes(e($w['nama'])) ?>', tabel: 'wisata' },
  <?php endforeach; ?>
];
const galeriOptions = [
  <?php foreach ($galeriList as $g): ?>
  { id: <?= $g['id'] ?>, label: '<?= addslashes(e($g['nama'])) ?> [<?= e($g['halaman']) ?>]', tabel: 'foto_galeri' },
  <?php endforeach; ?>
];

document.getElementById('tabelSelect').addEventListener('change', function() {
  const sel = document.getElementById('idSelect');
  sel.innerHTML = '<option value="">— Pilih ID —</option>';
  const opts = this.value === 'wisata' ? wisataOptions : galeriOptions;
  opts.forEach(o => {
    const opt = document.createElement('option');
    opt.value = o.id;
    opt.textContent = `#${o.id} — ${o.label}`;
    sel.appendChild(opt);
  });
});

document.getElementById('fileInput').addEventListener('change', function() {
  const file = this.files[0];
  const wrap = document.getElementById('previewWrap');
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('previewImg').src = e.target.result;
      document.getElementById('previewName').textContent = file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)';
    };
    reader.readAsDataURL(file);
    wrap.classList.remove('d-none');
  } else {
    wrap.classList.add('d-none');
  }
});
</script>
</body>
</html>
