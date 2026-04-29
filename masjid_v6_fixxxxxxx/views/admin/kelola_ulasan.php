    <?php
    require_once __DIR__ . '/../../includes/config.php';
    requireAdmin();
    $activePage = 'ulasan';
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='6' fill='%231a4731'/%3E%3Crect x='7' y='16' width='18' height='13' fill='%232e7d5a' rx='1'/%3E%3Cellipse cx='16' cy='16' rx='6' ry='2' fill='%232e7d5a'/%3E%3Cpath d='M10 16 Q10 9 16 8 Q22 9 22 16' fill='%232e7d5a'/%3E%3Cpolygon points='16,3 15,8 17,8' fill='%23c9a84c'/%3E%3Ccircle cx='16' cy='3' r='1.2' fill='%23c9a84c'/%3E%3Crect x='5' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='7,9 5.5,13 8.5,13' fill='%23c9a84c'/%3E%3Crect x='23' y='13' width='4' height='16' fill='%232e7d5a' rx='1'/%3E%3Cpolygon points='25,9 23.5,13 26.5,13' fill='%23c9a84c'/%3E%3Crect x='11' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3Crect x='18' y='20' width='3' height='4' fill='rgba(201%2C168%2C76%2C0.25)' rx='1.5'/%3E%3C/svg%3E">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Ulasan - Admin Masjid Raya Darussalam</title>
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
        <i class="fa-regular fa-comment-dots" style="color:var(--gold-500)"></i> Kelola Ulasan
        </div>
        <div class="topbar-sub">Tambah, tinjau, dan kelola semua ulasan pengunjung</div>
    </div>
</div>

    <div class="admin-content">

    <div class="ku-nav" role="tablist">
        <button type="button" class="ku-nav-btn active" data-tab="tambah" role="tab">
        <i class="fa-solid fa-plus"></i> Tambah Ulasan
        </button>
        <button type="button" class="ku-nav-btn" data-tab="pending" role="tab">
        <i class="fa-regular fa-clock"></i> Ulasan Pending
        <span id="ku-pending-badge" class="ku-badge" style="display:none">0</span>
        </button>
        <button type="button" class="ku-nav-btn" data-tab="semua" role="tab">
        <i class="fa-solid fa-list"></i> Semua Ulasan
        </button>
    </div>

    <div class="ku-pane active" id="ku-tab-tambah" role="tabpanel">
        <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="form-panel">
            
            <div class="text-center mb-2">
                <div style="width:52px;height:52px;background:linear-gradient(135deg,var(--green-500),var(--green-800));border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem">
                <i class="fa-solid fa-pen-to-square" style="color:var(--white);font-size:1.25rem"></i>
                </div>
                <h5 style="font-family:var(--font-display);color:var(--green-800);font-weight:700;margin-bottom:.3rem">Tulis Ulasan</h5>
                <p style="font-size:.82rem;color:var(--gray-400)">Bagikan pengalaman pengunjung secara manual</p>
            </div>
            <div style="height:2px;background:linear-gradient(to right,transparent,var(--gold-500),transparent);margin:.5rem 0 1.5rem"></div>

            <form id="tambah-form" novalidate enctype="multipart/form-data">
                
                <div style="margin-bottom:1.1rem">
                <label class="field-label">
                    <i class="fa-solid fa-star" style="color:var(--gold-500)"></i>
                    Rating Anda <span class="field-required">*</span>
                </label>
                <div class="star-picker" style="justify-content: flex-end; width: max-content; margin-top: .35rem;">
                    <input type="radio" id="t-star5" name="t_rating" value="5" checked><label for="t-star5" title="5 Bintang (Luar Biasa)"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="t-star4" name="t_rating" value="4"><label for="t-star4" title="4 Bintang (Bagus)"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="t-star3" name="t_rating" value="3"><label for="t-star3" title="3 Bintang (Cukup)"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="t-star2" name="t_rating" value="2"><label for="t-star2" title="2 Bintang (Kurang)"><i class="fa-solid fa-star"></i></label>
                    <input type="radio" id="t-star1" name="t_rating" value="1"><label for="t-star1" title="1 Bintang (Buruk)"><i class="fa-solid fa-star"></i></label>
                </div>
                <div id="t-rating-label" style="font-size:.78rem;color:var(--gray-400);margin-top:.25rem;min-height:1.2em;font-style:italic">Luar Biasa!</div>
                <div id="t-rating-error" style="color:#dc2626;font-size:.75rem;margin-top:.2rem;display:none"><i class="fa-solid fa-circle-exclamation"></i> <span class="msg"></span></div>
                </div>

                <div style="margin-bottom:.85rem">
                <label class="field-label" for="t-name">
                    <i class="fa-solid fa-user"></i> Nama Lengkap <span class="field-required">*</span>
                </label>
                <input type="text" id="t-name" class="field-input" placeholder="Masukkan nama (5-15 karakter)" maxlength="15" required>
                <div id="t-name-error" style="color:#dc2626;font-size:.75rem;margin-top:.2rem;display:none"><i class="fa-solid fa-circle-exclamation"></i> <span class="msg"></span></div>
                </div>

                <div style="margin-bottom:.85rem">
                <label class="field-label" for="t-kota">
                    <i class="fa-solid fa-location-dot"></i> Asal Kota
                </label>
                <input type="text" id="t-kota" class="field-input" placeholder="contoh: Samarinda (Opsional | Maks 20 Karakter)" maxlength="20">
                <div id="t-kota-error" style="color:#dc2626;font-size:.75rem;margin-top:.2rem;display:none"><i class="fa-solid fa-circle-exclamation"></i> <span class="msg"></span></div>
                </div>

                <div style="margin-bottom:.85rem">
                <label class="field-label" for="t-text">
                    <i class="fa-regular fa-comment-dots"></i> Ceritakan Pengalaman Anda <span class="field-required">*</span>
                </label>
                <textarea id="t-text" class="field-textarea" rows="4" placeholder="Ulasan Anda (10-500 karakter)" style="resize:vertical" maxlength="500"></textarea>
                <div id="t-text-count" style="font-size:.72rem;color:var(--gray-400);margin-top:.25rem;text-align:right">
                    0 / 500 karakter
                </div>
                <div id="t-text-error" style="color:#dc2626;font-size:.75rem;margin-top:.2rem;display:none"><i class="fa-solid fa-circle-exclamation"></i> <span class="msg"></span></div>
                </div>

                <div style="margin-bottom:1.1rem">
                <label class="field-label">
                    <i class="fa-regular fa-image"></i> Foto Kunjungan
                    <span style="color:var(--gray-400);font-weight:400;font-size:.76rem">(opsional, maks. 5MB)</span>
                </label>
                
                <div id="t-photo-dropzone" class="upload-zone" onclick="document.getElementById('t-photo').click()" style="padding:1.25rem;cursor:pointer">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem"></i>
                    <p style="margin-top:.35rem">Klik untuk memilih foto</p>
                    <span style="font-size:.72rem;color:var(--gray-400)">JPG, PNG, WEBP</span>
                    <input type="file" id="t-photo" accept="image/*" style="display:none">
                </div>
                
                <div id="t-photo-wrap" style="display:none;margin-top:.65rem">
                    <div class="review-photo-preview-box">
                    <img id="t-photo-preview" src="" alt="Preview foto" style="max-width:100%;border-radius:.5rem">
                    <button type="button" class="review-photo-remove" onclick="document.getElementById('t-photo').value=''; document.getElementById('t-photo-wrap').style.display='none'; document.getElementById('t-photo-dropzone').style.display='block';">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                    </div>
                </div>
                </div>

                <div class="alert-success mb-4" style="font-size:.78rem;font-weight:500;display:flex;align-items:flex-start;gap:.5rem">
                <i class="fa-solid fa-circle-info" style="margin-top:.1rem;flex-shrink:0"></i>
                Ulasan yang ditambahkan melalui panel admin akan langsung disetujui dan tampil di halaman publik.
                </div>

                <button type="submit" id="tambah-submit-btn" class="btn-green w-100" style="justify-content:center">
                <i class="fa-solid fa-paper-plane"></i> Kirim Ulasan
                </button>

            </form>
            </div>
        </div>
        </div>
    </div>

<div class="ku-pane" id="ku-tab-pending" role="tabpanel">
        <div class="data-table-wrap">
            <div class="data-table-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem;">
            <h6 style="margin:0;"><i class="fa-regular fa-clock" style="color:var(--gold-500)"></i> Ulasan Menunggu Persetujuan</h6>
            <div style="display:flex; gap:.5rem; align-items:center;">
                <select id="filter-star-pending" class="filter-dropdown" onchange="window._ctrl.sortPending()">
                    <option value="all">&#11088; Semua Ulasan</option>
                    <option value="5">&#11088;&#11088;&#11088;&#11088;&#11088; (5)</option>
                    <option value="4">&#11088;&#11088;&#11088;&#11088; (4)</option>
                    <option value="3">&#11088;&#11088;&#11088; (3)</option>
                    <option value="2">&#11088;&#11088; (2)</option>
                    <option value="1">&#11088; (1)</option>
                </select>
                <select id="sort-pending" class="filter-dropdown" onchange="window._ctrl.sortPending()">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                </select>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table class="data-table">
            <thead>
                <tr>
                <th>No</th>
                <th>Pengunjung</th>
                <th>Rating</th>
                <th>Ulasan</th>
                <th style="text-align:center">Foto</th>
                <th>Tanggal</th>
                <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pending-tbody">
                <tr><td colspan="7" class="text-center" style="padding:2rem;color:var(--gray-400)">
                <i class="fa-solid fa-spinner fa-spin"></i> Memuat...
                </td></tr>
            </tbody>
            </table>
        </div>
        </div>
    </div>

    <div class="ku-pane" id="ku-tab-semua" role="tabpanel">
        <div class="data-table-wrap">
        <div class="data-table-head" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:.5rem;">
            <h6 style="margin:0;"><i class="fa-solid fa-list" style="color:var(--gold-500)"></i> Semua Ulasan</h6>
            <div style="display:flex; gap:.5rem; align-items:center;">
                <select id="filter-star-all" class="filter-dropdown" onchange="window._ctrl.sortAll()">
                    <option value="all">&#11088; Semua Ulasan</option>
                    <option value="5">&#11088;&#11088;&#11088;&#11088;&#11088; (5)</option>
                    <option value="4">&#11088;&#11088;&#11088;&#11088; (4)</option>
                    <option value="3">&#11088;&#11088;&#11088; (3)</option>
                    <option value="2">&#11088;&#11088; (2)</option>
                    <option value="1">&#11088; (1)</option>
                </select>
                <select id="sort-all" class="filter-dropdown" onchange="window._ctrl.sortAll()">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                </select>
            </div>
        </div>
        <div style="overflow-x:auto">
            <table class="data-table">
            <thead>
                <tr>
                <th>No</th>
                <th>Pengunjung</th>
                <th>Rating</th>
                <th>Ulasan</th>
                <th style="text-align:center">Foto</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="all-tbody">
                <tr><td colspan="8" class="text-center" style="padding:2rem;color:var(--gray-400)">
                <i class="fa-solid fa-spinner fa-spin"></i> Memuat...
                </td></tr>
            </tbody>
            </table>
        </div>
        </div>
    </div>

    </div><?php include __DIR__ . '/_footer.php'; ?>
    </main>
    </div>

    <div class="modal fade" id="delete-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:var(--radius);border:2px solid var(--red-500)">
        <div class="modal-header modal-header-red">
        <h6 class="modal-title" style="font-family:var(--font-display);display:flex;align-items:center;gap:.5rem">
            <i class="fa-solid fa-trash"></i> Konfirmasi Hapus
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4 text-center">
        <i class="fa-solid fa-triangle-exclamation" style="font-size:2.5rem;color:var(--red-500);margin-bottom:.75rem;display:block"></i>
        <p style="font-weight:700;color:var(--gray-800);margin-bottom:.4rem">Yakin ingin menghapus ulasan ini?</p>
        <p style="font-size:.84rem;color:var(--gray-500)">Tindakan ini tidak dapat dibatalkan.</p>
        <input type="hidden" id="del-id">
        <input type="hidden" id="del-type" value="review">
        </div>
        <div class="modal-footer justify-content-center gap-2 border-0 pt-0 pb-4">
        <button type="button" class="act-btn act-view" data-bs-dismiss="modal" style="padding:.55rem 1.25rem">Batal</button>
        <button type="button" class="act-btn act-delete" style="padding:.55rem 1.25rem" onclick="window._ctrl.doDelete()">
            <i class="fa-solid fa-trash"></i> Ya, Hapus
        </button>
        </div>
    </div>
    </div>
    </div>

    <div class="modal fade" id="photo-preview-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:640px">
    <div class="modal-content" style="border-radius:var(--radius);background:#111;border:none">
        <div class="modal-header" style="border:none;padding:.65rem 1rem;background:rgba(0,0,0,.5)">
        <small style="color:rgba(255,255,255,.55);font-size:.8rem" id="photo-preview-name"></small>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-0 text-center" style="background:#000;border-radius:0 0 var(--radius) var(--radius)">
        <img id="photo-preview-img" src="" alt="Preview foto" style="max-width:100%;max-height:75vh;object-fit:contain;display:block;margin:0 auto">
        </div>
    </div>
    </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>window.APP_BASE = '<?= BASE_URL ?>';</script>
    <script src="../../assets/js/controller.js"></script>
    <script>MasjidCtrl.PageInit.kelolaUlasan();</script>
    </body>
    </html>