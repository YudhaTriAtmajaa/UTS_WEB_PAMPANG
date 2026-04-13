<?php?>

<!-- ===== FOOTER ===== -->
<footer class="pampang-footer">
  <div class="container-xl px-4 px-lg-5">
    <div class="row g-4 footer-main align-items-start">

      <!-- Brand -->
      <div class="col-lg-4 d-flex flex-column">
        <div class="footer-brand mb-3">
          Desa Wisata <span>Pampang</span>
        </div>
        <p class="footer-desc">
          Pelestarian budaya suku Dayak Kenyah di Kalimantan Timur. Tempat di mana tradisi bukan sekadar sejarah, melainkan nafas kehidupan.
        </p>
        <div class="d-flex gap-2 mt-3">
          <a href="https://www.instagram.com/budayapampang/" target="_blank" class="footer-social-btn"><i class="bi bi-instagram"></i></a>
          <a href="https://web.facebook.com/pages/Desa%20Budaya%20Pampang/162506137738335/#" target="_blank" class="footer-social-btn"><i class="bi bi-facebook"></i></a>
          <a href="https://wa.me/081254993755" target="_blank" class="footer-social-btn"><i class="bi bi-whatsapp"></i></a>
        </div>
      </div>

      <!-- Navigasi -->
      <div class="col-6 col-lg-2">
        <h6 class="footer-col-title">Navigasi</h6>
        <ul class="footer-links">
          <li><a href="index.php"><i class="bi bi-arrow-right me-1"></i>Home</a></li>
          <li><a href="about.php"><i class="bi bi-arrow-right me-1"></i>About</a></li>
          <li><a href="services.php"><i class="bi bi-arrow-right me-1"></i>Services</a></li>
          <li><a href="ulasan.php"><i class="bi bi-arrow-right me-1"></i>Reviews</a></li>
        </ul>
      </div>

      <!-- Kontak -->
      <div class="col-6 col-lg-3">
        <h6 class="footer-col-title">Kontak</h6>
        <ul class="footer-links">
          <li>
            <a href="mailto:<?= SITE_EMAIL ?>">
              <i class="bi bi-envelope me-2"></i><?= SITE_EMAIL ?>
            </a>
          </li>
          <li>
            <a href="tel:<?= SITE_PHONE ?>">
              <i class="bi bi-telephone me-2"></i><?= SITE_PHONE ?>
            </a>
          </li>
          <li>
            <span class="text-muted-footer">
              <i class="bi bi-geo-alt me-2"></i>Kec. Samarinda Utara, Kaltim
            </span>
          </li>
        </ul>
      </div>

      <!-- Jam Buka -->
      <div class="col-6 col-lg-2">
        <h6 class="footer-col-title">Jam Buka</h6>
        <ul class="footer-links">
          <li><span class="text-muted-footer">Sen – Jum</span></li>
          <li><span class="text-gold">07.00 – 17.00</span></li>
          <li><span class="text-muted-footer">Sabtu – Minggu</span></li>
          <li><span class="text-gold">07.00 – 17.00</span></li>
          <li><span class="text-muted-footer">Atraksi Tarian - Sabtu, Minggu</span></li>
          <li><span class="text-gold">14.00 – 16.00</span></li>
        </ul>
      </div>

    </div><!-- /row -->
  </div>

  <div class="footer-bottom">
    <div class="container-xl px-4 px-lg-5 d-flex flex-wrap justify-content-between align-items-center gap-2">
      <p class="mb-0">&copy; <?= date('Y') ?> <?= SITE_NAME ?>. All rights reserved.</p>
      <p class="mb-0 text-muted-footer">Samarinda, Kalimantan Timur</p>
    </div>
  </div>
</footer>

<button id="backToTop" title="Kembali ke atas" aria-label="Kembali ke atas">
  <i class="bi bi-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AOS -->
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<!-- Custom JS -->
<script src="assets/js/main.js"></script>
</body>
</html>