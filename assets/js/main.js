// main.js — Desa Wisata Pampang

document.addEventListener('DOMContentLoaded', function () {

  // =============================================
  // AOS Init
  // =============================================
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 750,
      once: true,
      offset: 60,
      easing: 'ease-out-cubic',
    });
  }

  // =============================================
  // Navbar scroll effect
  // =============================================
  const nav = document.getElementById('mainNav');
  function handleScroll() {
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 60);
    const btn = document.getElementById('backToTop');
    if (btn) btn.classList.toggle('show', window.scrollY > 400);
  }
  window.addEventListener('scroll', handleScroll, { passive: true });
  handleScroll();

  // =============================================
  // Mobile menu
  // =============================================
  const hamburger   = document.getElementById('hamburger');
  const mobileMenu  = document.getElementById('mobileMenu');
  const mobileOverlay = document.getElementById('mobileOverlay');
  const mobileClose = document.getElementById('mobileClose');

  function openMenu() {
    if (!mobileMenu) return;
    mobileMenu.classList.add('open');
    if (mobileOverlay) mobileOverlay.classList.add('active');
    if (hamburger) hamburger.classList.add('open');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    if (!mobileMenu) return;
    mobileMenu.classList.remove('open');
    if (mobileOverlay) mobileOverlay.classList.remove('active');
    if (hamburger) hamburger.classList.remove('open');
    document.body.style.overflow = '';
  }

  if (hamburger)      hamburger.addEventListener('click', () => mobileMenu.classList.contains('open') ? closeMenu() : openMenu());
  if (mobileOverlay)  mobileOverlay.addEventListener('click', closeMenu);
  if (mobileClose)    mobileClose.addEventListener('click', closeMenu);

  // =============================================
  // Back to Top
  // =============================================
  const backToTop = document.getElementById('backToTop');
  if (backToTop) {
    backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  // =============================================
  // Counter animation
  // =============================================
  const counters = document.querySelectorAll('[data-target]');
  if (counters.length) {
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting || entry.target.dataset.done) return;
        entry.target.dataset.done = '1';
        const target = +entry.target.dataset.target;
        const duration = 1800;
        const start = performance.now();
        function step(now) {
          const progress = Math.min((now - start) / duration, 1);
          const ease = 1 - Math.pow(1 - progress, 3);
          entry.target.textContent = Math.floor(ease * target).toLocaleString('id-ID');
          if (progress < 1) requestAnimationFrame(step);
          else entry.target.textContent = target.toLocaleString('id-ID');
        }
        requestAnimationFrame(step);
      });
    }, { threshold: 0.5 });
    counters.forEach(c => obs.observe(c));
  }

  // Attraction filter (services.php)
  const filterBtns = document.querySelectorAll('.filter-btn');
  const galleryItems = document.querySelectorAll('.gallery-item');

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const cat = btn.dataset.filter;
      galleryItems.forEach(item => {
        const show = cat === 'all' || item.dataset.category === cat;
        item.style.opacity = show ? '1' : '0.2';
        item.style.pointerEvents = show ? 'auto' : 'none';
        item.style.transform = show ? '' : 'scale(0.97)';
        item.style.transition = 'opacity .4s, transform .4s';
      });
    });
  });

  // Pampang Modal (services.php)
  document.querySelectorAll('[data-modal]').forEach(btn => {
    btn.addEventListener('click', () => {
      const modal = document.getElementById(btn.dataset.modal);
      if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      }
    });
  });
  document.querySelectorAll('.pampang-modal').forEach(modal => {
    modal.querySelector('.pampang-modal-overlay')?.addEventListener('click', () => closeModal(modal));
    modal.querySelector('.modal-close-btn')?.addEventListener('click',  () => closeModal(modal));
  });
  function closeModal(modal) {
    modal.classList.remove('show');
    document.body.style.overflow = '';
  }
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.pampang-modal.show').forEach(closeModal);
      closeMenu();
    }
  });

  // FAQ Category Filter (about.php)
  const faqTabs = document.querySelectorAll('.faq-tab');
  faqTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      faqTabs.forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      const cat = tab.dataset.cat;
      document.querySelectorAll('.faq-accordion-item-wrap').forEach(item => {
        const show = cat === 'all' || item.dataset.cat === cat;
        item.style.display = show ? '' : 'none';
      });
    });
  });

  // Star Rating highlight (ulasan.php)
  const ratingLabels = document.querySelectorAll('.star-rating label');
  ratingLabels.forEach((label, index) => {
    label.addEventListener('mouseenter', () => {
      const total = ratingLabels.length;
      ratingLabels.forEach((l, i) => {
        // star-rating is flex-direction: row-reverse, so index 0 is star-5
        l.style.color = (i >= index) ? 'var(--gold)' : 'var(--border)';
      });
    });
    label.addEventListener('mouseleave', () => {
      ratingLabels.forEach(l => l.style.color = '');
    });
  });

  // Rating bar animation (ulasan.php)
  const bars = document.querySelectorAll('.rating-bar-fill');
  if (bars.length) {
    const obs2 = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.style.width = e.target.dataset.width + '%';
          obs2.unobserve(e.target);
        }
      });
    }, { threshold: 0.3 });
    bars.forEach(b => { b.style.width = '0'; obs2.observe(b); });
  }

  // Hero parallax (light)
  const heroBg = document.querySelector('.hero-bg');
  if (heroBg) {
    window.addEventListener('scroll', () => {
      heroBg.style.transform = `scale(1.05) translateY(${window.scrollY * 0.15}px)`;
    }, { passive: true });
  }

  // Form validation feedback (ulasan.php)
  const ulasanForm = document.getElementById('ulasanForm');
  if (ulasanForm) {
    ulasanForm.addEventListener('submit', function (e) {
      const bintang = ulasanForm.querySelector('input[name="bintang"]:checked');
      if (!bintang) {
        e.preventDefault();
        const el = document.getElementById('bintangError');
        if (el) { el.style.display = 'block'; }
        return false;
      }
    });
  }

});
