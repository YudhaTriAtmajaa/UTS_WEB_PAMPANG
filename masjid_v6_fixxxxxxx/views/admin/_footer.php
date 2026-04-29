<?php?>
<footer class="site-footer" style="margin-top:auto">
  <div class="container-fluid px-4 footer-inner">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-arabic">مسجد رايا دارالسلام</div>
        <div class="footer-brand">Masjid Raya Darussalam</div>
        <p class="footer-desc">Pusat ibadah, pendidikan, dan wisata religi kebanggaan Kota Samarinda dan Kalimantan Timur.</p>
      </div>
      <div class="col-6 col-lg-2">
        <div class="footer-heading">Navigasi</div>
        <a href="../../index.php"   class="footer-link"><i class="fa-solid fa-house fa-fw"></i> Beranda</a>
        <a href="../../views/detail.php"     class="footer-link"><i class="fa-solid fa-mosque fa-fw"></i> Detail &amp; Fasilitas</a>
        <a href="../../views/ulasan.php"     class="footer-link"><i class="fa-regular fa-comment-dots fa-fw"></i> Ulasan</a>
      </div>
      <div class="col-6 col-lg-3">
        <div class="footer-heading">Kontak</div>
        <p style="font-size:.84rem;line-height:2.1;color:rgba(255,255,255,.55)">
          <i class="fa-solid fa-location-dot fa-fw"></i> Jl. K.H. Abdullah Marisie, Samarinda<br>
          <i class="fa-solid fa-phone fa-fw"></i> (0541) 123-456<br>
          <i class="fa-regular fa-envelope fa-fw"></i> info@darussalam-samarinda.id<br>
          <i class="fa-regular fa-clock fa-fw"></i> Buka 24 Jam
        </p>
      </div>
      <div class="col-lg-3">
        <div class="footer-heading">Ikuti Kami</div>
        <div class="d-flex flex-column gap-2">
          <a href="https://www.facebook.com/mesra.darussalamsmd/" target="_blank" class="footer-link" style="display:inline-flex;align-items:center;gap:.6rem;border:1px solid rgba(255,255,255,.12);padding:.45rem .9rem;border-radius:6px"><i class="fa-brands fa-facebook-f" style="width:14px;text-align:center"></i> Facebook</a>
          <a href="https://www.instagram.com/darussalamsmd/?utm_source=ig_web_button_share_sheet" target="_blank" class="footer-link" style="display:inline-flex;align-items:center;gap:.6rem;border:1px solid rgba(255,255,255,.12);padding:.45rem .9rem;border-radius:6px"><i class="fa-brands fa-instagram" style="width:14px;text-align:center"></i> Instagram</a>
          <a href="https://www.youtube.com/@MasjidRayaDarussalam" target="_blank" rel="noopener noreferrer" class="footer-link" style="display:inline-flex;align-items:center;gap:.6rem;border:1px solid rgba(255,255,255,.12);padding:.45rem .9rem;border-radius:6px"><i class="fa-brands fa-youtube" style="width:14px;text-align:center"></i> YouTube</a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <span style="color:rgba(255,255,255,.35)">&copy; <?= date('Y') ?> Masjid Raya Darussalam Samarinda</span>
    </div>
  </div>
</footer>

<div class="modal fade" id="logout-modal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px">
    <div class="modal-content" style="border-radius:var(--radius);border:2px solid var(--red-500)">
      <div class="modal-header modal-header-red">
        <h6 class="modal-title" style="font-family:var(--font-display);display:flex;align-items:center;gap:.5rem">
          <i class="fa-solid fa-right-from-bracket"></i> Konfirmasi Logout
        </h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-4 text-center">
        <i class="fa-solid fa-circle-question" style="font-size:2.5rem;color:var(--red-500);margin-bottom:.75rem;display:block"></i>
        <p style="font-weight:700;color:var(--gray-800);margin-bottom:.4rem">Yakin ingin keluar dari Mode Admin?</p>
        <p style="font-size:.84rem;color:var(--gray-500)">Sesi Anda akan diakhiri.</p>
      </div>
      <div class="modal-footer justify-content-center gap-2 border-0 pt-0 pb-4">
        <button type="button" class="act-btn act-view" data-bs-dismiss="modal" style="padding:.55rem 1.25rem">Batal</button>
        <button type="button" class="act-btn act-delete" style="padding:.55rem 1.25rem" onclick="window._ctrl.doLogout()">
          <i class="fa-solid fa-right-from-bracket"></i> Ya, Keluar
        </button>
      </div>
    </div>
  </div>
</div>