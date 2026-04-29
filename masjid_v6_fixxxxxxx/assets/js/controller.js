const MasjidCtrl = (() => {

  function getApiBase() {
    if (window.APP_BASE) return window.APP_BASE.replace(/\/$/, '') + '/api/';
    const segs    = window.location.pathname.split('/').filter(Boolean);
    const project = segs[0] || '';
    const origin  = window.location.origin;
    return project ? `${origin}/${project}/api/` : `${origin}/api/`;
  }
  const API = getApiBase();
  
  const Utils = {
    toast(msg, type = 'success') {
      let stack = document.getElementById('toast-stack');
      if (!stack) {
        stack = document.createElement('div');
        stack.id        = 'toast-stack';
        stack.className = 'toast-stack';
        document.body.appendChild(stack);
      }
      const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', gold: 'fa-star', info: 'fa-circle-info' };
      const el = document.createElement('div');
      el.className = `toast-item${type === 'error' ? ' error' : type === 'gold' ? ' gold' : ''}`;
      el.innerHTML = `<i class="fa-solid ${icons[type] || icons.success}"></i><span>${msg}</span>`;
      stack.appendChild(el);
      setTimeout(() => {
        el.style.transition = '.3s'; el.style.opacity = '0'; el.style.transform = 'translateX(110%)';
        setTimeout(() => el.remove(), 350);
      }, 3200);
    },
    stars(rating) {
      const r = +rating || 0; let html = '';
      for (let i = 1; i <= 5; i++) html += `<i class="fa-solid fa-star" style="color:${i <= r ? '#c9a84c' : '#e5e7eb'};font-size:.82rem"></i>`;
      return html;
    },
    fmtDate(d) { return new Date(d).toLocaleDateString('id-ID', { day:'numeric', month:'long', year:'numeric' }); },
    el(id)     { return document.getElementById(id); },
    set(id, v) { const e = document.getElementById(id); if (e) e.textContent = v; },
    scrollReveal() {
      const obs = new IntersectionObserver((entries) => {
        entries.forEach((entry, i) => {
          if (entry.isIntersecting) { setTimeout(() => entry.target.classList.add('visible'), i * 80); obs.unobserve(entry.target); }
        });
      }, { threshold: 0.06, rootMargin: '0px 0px -40px 0px' });
      document.querySelectorAll('.fade-up:not(.visible)').forEach(el => obs.observe(el));
    },
    getOrCreateModal(el) {
      if (!el) throw new Error('Elemen modal tidak ditemukan di DOM');
      return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
    },
    async post(endpoint, formData) { const res = await fetch(API + endpoint, { method: 'POST', body: formData }); return res.json(); },
    async get(endpoint, params = {}) {
      const qs  = new URLSearchParams(params).toString();
      const res = await fetch(API + endpoint + (qs ? '?' + qs : ''));
      return res.json();
    },
  };

  const Prayer = { };

  const Home = {
    slideIdx: 0, slideVisible: 3, reviews: [],
    async init() { await this.updateStats(); await this.initSlider(); await this.loadHeroPhoto(); Utils.scrollReveal(); },
    async loadHeroPhoto() {
      const frame = Utils.el('hero-photo-frame'); if (!frame) return;
      try {
        const res = await Utils.get('gallery.php', { action: 'home_photo' });
        if (res.success && res.data && res.data.photo_url) frame.innerHTML = `<img src="${res.data.photo_url}" alt="${res.data.caption}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">`;
      } catch (e) { }
    },
    async updateStats() {
      const res = await Utils.get('reviews.php', { action: 'avg' });
      if (res.success) { Utils.set('stat-rating', res.avg); Utils.set('stat-reviews', res.count + '+'); }
    },
    async initSlider() {
      const res = await Utils.get('reviews.php', { action: 'approved' });
      if (!res.success) return;
      this.reviews = res.data.slice(0, 5);
      const track = Utils.el('reviews-track'); if (!track) return;
      track.innerHTML = this.reviews.map(r => `
        <div class="review-slide">
          <div class="review-card">
            <div class="reviewer-row">
              <div class="reviewer-avatar">${r.name.charAt(0)}</div>
              <div><div class="reviewer-name">${r.name}</div><div class="reviewer-meta"><i class="fa-regular fa-calendar" style="font-size:.65rem"></i> ${r.date_fmt} ${r.kota ? ' · ' + r.kota : ''}</div></div>
            </div>
            <div class="review-stars">${Utils.stars(r.rating)}</div>
            <p class="review-text">"${r.text}"</p>
            ${r.photo_url ? `<div class="review-photo-thumb"><img src="${r.photo_url}" alt="Foto ulasan" loading="lazy"></div>` : ''}
          </div>
        </div>
      `).join('');
      this.updateVisible(); this.renderSlider(); this.renderDots();
      Utils.el('slider-prev')?.addEventListener('click', () => this.prev());
      Utils.el('slider-next')?.addEventListener('click', () => this.next());
      let startX = 0;
      track.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
      track.addEventListener('touchend',   e => { const diff = startX - e.changedTouches[0].clientX; if (Math.abs(diff) > 50) diff > 0 ? this.next() : this.prev(); });
      window.addEventListener('resize', () => { this.updateVisible(); this.renderSlider(); });
    },
    updateVisible() { const w = window.innerWidth; this.slideVisible = w >= 992 ? 3 : w >= 640 ? 2 : 1; },
    maxIdx()        { return Math.max(0, this.reviews.length - this.slideVisible); },
    prev()          { if (this.slideIdx > 0) { this.slideIdx--; this.renderSlider(); } },
    next()          { if (this.slideIdx < this.maxIdx()) { this.slideIdx++; this.renderSlider(); } },
    renderSlider() {
      const track = Utils.el('reviews-track'); if (!track) return;
      const slideEl = track.querySelector('.review-slide'); if (!slideEl) return;
      track.style.transform = `translateX(-${this.slideIdx * (slideEl.offsetWidth + 20)}px)`;
      Utils.el('slider-prev')?.toggleAttribute('disabled', this.slideIdx === 0);
      Utils.el('slider-next')?.toggleAttribute('disabled', this.slideIdx >= this.maxIdx());
      document.querySelectorAll('.slider-dot').forEach((d, i) => d.classList.toggle('active', i === this.slideIdx));
    },
    renderDots() {
      const el = Utils.el('slider-dots'); if (!el) return;
      const total = Math.max(1, this.reviews.length - this.slideVisible + 1);
      el.innerHTML = Array.from({ length: total }, (_, i) => `<div class="slider-dot${i === 0 ? ' active' : ''}" data-dot="${i}"></div>`).join('');
      el.querySelectorAll('.slider-dot').forEach(d => d.addEventListener('click', () => { this.slideIdx = +d.dataset.dot; this.renderSlider(); }));
    },
  };

  const Auth = {
    initLogin() {
      fetch(API + 'auth.php?action=check').then(r => r.json()).then(res => { if (res.success) window.location.replace('admin/dashboard.php'); }).catch(() => {});
      Utils.el('login-form')?.addEventListener('submit', async e => {
        e.preventDefault();
        const btn = Utils.el('login-btn'); btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';
        const fd = new FormData(); fd.append('action', 'login'); fd.append('username', Utils.el('username').value); fd.append('password', Utils.el('password').value);
        try {
          const res = await fetch(API + 'auth.php', { method: 'POST', body: fd });
          const json = await res.json();
          if (json.success) { Utils.toast('Login berhasil!', 'success'); setTimeout(() => window.location.replace('admin/dashboard.php'), 700); } 
          else { const errEl = Utils.el('login-error'); if (errEl) errEl.style.display = 'flex'; btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Masuk ke Panel Admin'; }
        } catch (err) { Utils.toast('Gagal terhubung ke server.', 'error'); btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Masuk ke Panel Admin'; }
      });
      Utils.el('toggle-pw')?.addEventListener('click', () => {
        const pw = Utils.el('password'), ic = Utils.el('toggle-icon');
        pw.type = pw.type === 'password' ? 'text' : 'password'; ic.className = `fa-solid fa-eye${pw.type === 'password' ? '' : '-slash'}`;
      });
    },
    async logout() {
      window._ctrl.confirmLogout();
    },
  };

  const AdminDash = {
    async init() { await this.renderStats(); await this.renderPending(); await this.renderAll(); },
    async renderStats() {
      const res = await Utils.get('reviews.php', { action: 'stats' });
      if (!res.success) return;
      Utils.set('stat-total', res.total); Utils.set('stat-approved', res.approved); Utils.set('stat-pending', res.pending);
      Utils.set('stat-avg', res.avg); Utils.set('stat-fac', res.facCount); Utils.set('stat-gal', res.galCount);
    },
    _reviewRow(r, index) {
      const photoCell = (r.photo_url && r.photo_url.trim()) ? `<img src="${r.photo_url}" style="width:48px;height:36px;object-fit:cover;border-radius:4px;border:1px solid var(--gray-200);cursor:pointer" onclick="window._ctrl.previewPhoto('${r.photo_url.replace(/'/g,"\\'")}','${r.name.replace(/'/g,"\\'")}')">` : '<span style="color:var(--gray-300);font-size:.78rem">-</span>';
      return `<tr>
        <td>${index + 1}</td><td><strong>${r.name}</strong><br><small style="color:var(--gray-400)">${r.kota}</small></td>
        <td><div style="display:flex;gap:2px">${Utils.stars(r.rating)}</div></td>
        <td style="max-width:180px; white-space:normal; overflow-wrap:anywhere; word-break:break-word;">${r.text.slice(0, 70)}...</td>
        <td>${photoCell}</td><td>${r.date_fmt}</td>
      </tr>`;
    },
    async renderPending() {
      const el = Utils.el('pending-readonly-tbody'); if (!el) return;
      const res = await Utils.get('reviews.php', { action: 'pending' });
      if (!res.success) { el.innerHTML = '<tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--red-500)">Gagal memuat data.</td></tr>'; return; }
      const badge = Utils.el('pending-count-badge'); if (badge) { badge.textContent = res.data.length; badge.style.display = res.data.length > 0 ? 'inline-block' : 'none'; }
      el.innerHTML = res.data.length ? res.data.map((r, i) => this._reviewRow(r, i)).join('') : '<tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--gray-400)">Tidak ada ulasan yang menunggu persetujuan.</td></tr>';
    },
    async renderAll() {
      const el = Utils.el('latest-tbody'); if (!el) return;
      const res = await Utils.get('reviews.php', { action: 'approved' });
      if (!res.success) { el.innerHTML = '<tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--red-500)">Gagal memuat data.</td></tr>'; return; }
      const data = res.data.slice(0, 5);
      el.innerHTML = data.length ? data.map((r, i) => this._reviewRow(r, i)).join('') : '<tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--gray-400)">Belum ada ulasan yang disetujui.</td></tr>';
    }
  };

  const AdminUlasan = {
    allReviewsData: [],
    pendingReviewsData: [],

    async init() {
      this.bindTabs();
      await this.loadPending();
      await this.loadAll();
      this.bindForm();

      const urlParams = new URLSearchParams(window.location.search);
      const targetTab = urlParams.get('tab');
      if (targetTab) {
        const tabBtn = document.querySelector(`.ku-nav-btn[data-tab="${targetTab}"]`);
        if (tabBtn) tabBtn.click();
      }
    },
    bindTabs() {
      document.querySelectorAll('.ku-nav-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          document.querySelectorAll('.ku-nav-btn').forEach(b => b.classList.remove('active'));
          document.querySelectorAll('.ku-pane').forEach(p => p.classList.remove('active'));
          btn.classList.add('active');
          const target = document.getElementById('ku-tab-' + btn.dataset.tab);
          if (target) target.classList.add('active');
        });
      });
    },
    _reviewRow(r, index, isPending = false, total = 0, sortVal = 'newest') {
      const displayIndex = sortVal === 'oldest' ? (index + 1) : (total - index);
      const photoCell = (r.photo_url && r.photo_url.trim()) ? `<img src="${r.photo_url}" style="width:48px;height:36px;object-fit:cover;border-radius:4px;border:1px solid var(--gray-200);cursor:pointer;margin:0 auto" onclick="window._ctrl.previewPhoto('${r.photo_url.replace(/'/g,"\\'")}','${r.name.replace(/'/g,"\\'")}')">` : '<span style="color:var(--gray-300);font-size:.78rem">-</span>';
      const statusBadge = `<span class="badge-${r.status === 'approved' ? 'approved' : 'pending'}">${r.status === 'approved' ? 'Disetujui' : 'Menunggu'}</span>`;
      const fromTab = isPending ? 'pending' : 'semua';
      
      let actions = `<a class="act-btn act-edit me-1" href="aksi_edit.php?id=${r.id}&from=${fromTab}"><i class="fa-solid fa-pen"></i></a>
                    <button class="act-btn act-delete" onclick="window._ctrl.confirmDelete(${r.id},'review')"><i class="fa-solid fa-trash"></i></button>`;
      
      if (isPending) {
        actions = `<button class="act-btn act-approve me-1" onclick="window._ctrl.approve(${r.id})"><i class="fa-solid fa-check"></i> Approve</button>` + actions;
        return `<tr>
          <td>${displayIndex}</td><td><strong>${r.name}</strong><br><small style="color:var(--gray-400)">${r.kota}</small></td>
          <td><div style="display:flex;gap:2px">${Utils.stars(r.rating)}</div></td>
          <td style="max-width:180px; white-space:normal; overflow-wrap:anywhere; word-break:break-word;">${r.text.slice(0, 70)}...</td>
          <td style="text-align:center">${photoCell}</td>
          <td>${r.date_fmt}</td><td style="white-space:nowrap">${actions}</td>
        </tr>`;
      } else {
        return `<tr>
          <td>${displayIndex}</td><td><strong>${r.name}</strong><br><small style="color:var(--gray-400)">${r.kota}</small></td>
          <td><div style="display:flex;gap:2px">${Utils.stars(r.rating)}</div></td>
          <td style="max-width:180px; white-space:normal; overflow-wrap:anywhere; word-break:break-word;">${r.text.slice(0, 70)}...</td>
          <td style="text-align:center">${photoCell}</td>
          <td>${r.date_fmt}</td>
          <td>${statusBadge}</td><td style="white-space:nowrap">${actions}</td>
        </tr>`;
      }
    },
    async loadPending() {
      const tbody = document.getElementById('pending-tbody'); if (!tbody) return;
      tbody.innerHTML = '<tr><td colspan="7" class="text-center" style="padding:2rem;color:var(--gray-400)"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</td></tr>';
      const res = await Utils.get('reviews.php', { action: 'pending' });
      if (res.success) {
        this.pendingReviewsData = res.data;
        this.renderPendingTable();
      }
    },
    renderPendingTable() {
      const tbody = document.getElementById('pending-tbody'); if (!tbody) return;
      const sortVal = document.getElementById('sort-pending')?.value || 'newest';
      const starVal = document.getElementById('filter-star-pending')?.value || 'all';
      
      let data = [...this.pendingReviewsData];

      if (starVal !== 'all') {
        data = data.filter(r => parseInt(r.rating) === parseInt(starVal));
      }

      if (sortVal === 'oldest') data.reverse();
      
      const total = data.length;
      tbody.innerHTML = total ? data.map((r, index) => this._reviewRow(r, index, true, total, sortVal)).join('') : '<tr><td colspan="7" class="text-center" style="padding:2rem;color:var(--gray-400)">Tidak ada ulasan sesuai filter.</td></tr>';
      
      const badge = document.getElementById('ku-pending-badge');
      if (badge) { badge.textContent = this.pendingReviewsData.length; badge.style.display = this.pendingReviewsData.length ? 'inline-block' : 'none'; }
    },
    async loadAll() {
      const tbody = document.getElementById('all-tbody'); if (!tbody) return;
      tbody.innerHTML = '<tr><td colspan="8" class="text-center" style="padding:2rem;color:var(--gray-400)"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</td></tr>';
      const res = await Utils.get('reviews.php', { action: 'all' });
      if (res.success) { 
        this.allReviewsData = res.data;
        this.renderAllTable();
      }
    },
    renderAllTable() {
      const tbody = document.getElementById('all-tbody'); if (!tbody) return;
      const sortVal = document.getElementById('sort-all')?.value || 'newest';
      const starVal = document.getElementById('filter-star-all')?.value || 'all';
      
      let data = [...this.allReviewsData];

      if (starVal !== 'all') {
        data = data.filter(r => parseInt(r.rating) === parseInt(starVal));
      }

      if (sortVal === 'oldest') data.reverse();
      
      const total = data.length;
      tbody.innerHTML = total ? data.map((r, index) => this._reviewRow(r, index, false, total, sortVal)).join('') : '<tr><td colspan="8" class="text-center" style="padding:2rem;color:var(--gray-400)">Tidak ada ulasan sesuai filter.</td></tr>';
    },
    bindForm() {
      const form = document.getElementById('tambah-form'); if (!form) return;

      const tName = document.getElementById('t-name');
      const tKota = document.getElementById('t-kota');
      const blockEmoji = (e) => {
        e.target.value = e.target.value.replace(/\p{Extended_Pictographic}/gu, '');
      };
      if(tName) tName.addEventListener('input', blockEmoji);
      if(tKota) tKota.addEventListener('input', blockEmoji);

      const tText = document.getElementById('t-text');
      const tTextCount = document.getElementById('t-text-count');
      if (tText && tTextCount) {
        tText.addEventListener('input', () => {
          tTextCount.textContent = `${tText.value.length} / 500 karakter`;
        });
      }

      const ratingRadios = document.querySelectorAll('input[name="t_rating"]');
      const ratingLabel = document.getElementById('t-rating-label');
      const labels = { 5:'Luar Biasa!', 4:'Bagus', 3:'Cukup', 2:'Kurang', 1:'Buruk' };
      ratingRadios.forEach(radio => {
        radio.addEventListener('change', (e) => {
          if (ratingLabel) ratingLabel.textContent = labels[e.target.value];
        });
      });

      form.addEventListener('submit', async e => {
        e.preventDefault();

        let isValid = true;
        const tName = document.getElementById('t-name');
        const tKota = document.getElementById('t-kota');
        const tText = document.getElementById('t-text');
        const ratingVal = document.querySelector('input[name="t_rating"]:checked')?.value;

        const errName = document.getElementById('t-name-error');
        const errKota = document.getElementById('t-kota-error');
        const errText = document.getElementById('t-text-error');
        const errRating = document.getElementById('t-rating-error');

        tName.classList.remove('is-invalid');
        tKota.classList.remove('is-invalid');
        tText.classList.remove('is-invalid');
        if(errName) errName.style.display = 'none';
        if(errKota) errKota.style.display = 'none';
        if(errText) errText.style.display = 'none';
        if(errRating) errRating.style.display = 'none';

        if (!ratingVal) {
          isValid = false;
          if(errRating) { errRating.style.display = 'block'; errRating.querySelector('.msg').textContent = 'Pilih rating bintang terlebih dahulu'; }
        }

        const nameVal = tName.value.trim();
        if (!nameVal) {
          isValid = false; tName.classList.add('is-invalid');
          if(errName) { errName.style.display = 'block'; errName.querySelector('.msg').textContent = 'Nama lengkap wajib diisi'; }
        } else if (nameVal.length < 5 || nameVal.length > 15) {
          isValid = false; tName.classList.add('is-invalid');
          if(errName) { errName.style.display = 'block'; errName.querySelector('.msg').textContent = 'Nama harus 5-15 karakter'; }
        }

        const kotaVal = tKota.value.trim();
        if (kotaVal && kotaVal.length > 20) {
          isValid = false; tKota.classList.add('is-invalid');
          if(errKota) { errKota.style.display = 'block'; errKota.querySelector('.msg').textContent = 'Asal kota maksimal 20 karakter'; }
        }

        const textVal = tText.value.trim();
        if (!textVal) {
          isValid = false; tText.classList.add('is-invalid');
          if(errText) { errText.style.display = 'block'; errText.querySelector('.msg').textContent = 'Ceritakan pengalaman Anda'; }
        } else if (textVal.length < 10 || textVal.length > 500) {
          isValid = false; tText.classList.add('is-invalid');
          if(errText) { errText.style.display = 'block'; errText.querySelector('.msg').textContent = 'Ulasan harus 10-500 karakter'; }
        }

        if (!isValid) return;

        const btn = document.getElementById('tambah-submit-btn'); 
        btn.disabled = true; 
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
        
        const fd = new FormData();
        fd.append('action', 'add'); 
        fd.append('name', nameVal); 
        fd.append('kota', kotaVal);
        fd.append('text', textVal); 
        fd.append('rating', ratingVal);
        
        const file = document.getElementById('t-photo').files[0]; 
        if (file) fd.append('photo', file);
        
        const res = await fetch(API + 'reviews.php', { method: 'POST', body: fd }).then(r => r.json());
        Utils.toast(res.message || (res.success ? 'Berhasil' : 'Gagal'), res.success ? 'success' : 'error');
        
        if (res.success) { 
            form.reset(); 
            if(tTextCount) tTextCount.textContent = '0 / 500 karakter';
            if(ratingLabel) ratingLabel.textContent = 'Luar Biasa!';
            document.getElementById('t-photo-wrap').style.display = 'none'; 
            document.getElementById('t-photo-dropzone').style.display = 'block'; 
            
            await this.loadAll(); 
            const btnSemua = document.querySelector('.ku-nav-btn[data-tab="semua"]');
            if(btnSemua) btnSemua.click(); 
        }
        btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim Ulasan';
      });

      document.getElementById('t-photo')?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) { 
            const reader = new FileReader(); 
            reader.onload = e => { 
                document.getElementById('t-photo-preview').src = e.target.result; 
                document.getElementById('t-photo-wrap').style.display = 'block'; 
                document.getElementById('t-photo-dropzone').style.display = 'none';
            }; 
            reader.readAsDataURL(file); 
        }
      });
    }
  };

  const AdminActions = {
    async approve(id) {
      const fd = new FormData(); fd.append('action', 'approve'); fd.append('id', id);
      const res = await fetch(API + 'reviews.php', { method: 'POST', body: fd }).then(r => r.json());
      Utils.toast(res.message || (res.success ? 'Disetujui!' : 'Gagal!'), res.success ? 'success' : 'error');
      if (res.success) {
        if (document.getElementById('pending-readonly-tbody')) { await AdminDash.renderStats(); await AdminDash.renderPending(); await AdminDash.renderAll(); }
        if (document.getElementById('pending-tbody')) { await AdminUlasan.loadPending(); await AdminUlasan.loadAll(); }
      }
    },
    confirmDelete(id, type) {
      const idEl = Utils.el('del-id');
      if (idEl) idEl.value = id;
      
      const typeEl = Utils.el('del-type');
      if (typeEl) typeEl.value = type;
      
      const msgEl = Utils.el('del-message');
      if (msgEl) msgEl.textContent = ''; 
      
      const modalEl = Utils.el('delete-modal');
      if (modalEl) new bootstrap.Modal(modalEl).show();
    },
    async initEdit() {
      const id = new URLSearchParams(window.location.search).get('id'); if (!id) return;
      const res = await Utils.get('reviews.php', { action: 'one', id });
      const loading = Utils.el('form-loading'), formEl = Utils.el('edit-form'), notFound = Utils.el('not-found');
      if (loading) loading.style.display = 'none';
      if (!res.success) { if (notFound) notFound.style.display = 'flex'; return; }
      if (formEl) formEl.style.display = 'block';

const r = res.data;
      Utils.el('e-id').value = id; 
      Utils.el('e-name').value = r.name; 
      Utils.el('e-kota').value = r.kota; 
      Utils.el('e-text').value = r.text; 
      Utils.el('e-status').value = r.status; 
      
      const eName = document.getElementById('e-name');
      const eKota = document.getElementById('e-kota');
      const blockEmoji = (e) => {
        e.target.value = e.target.value.replace(/\p{Extended_Pictographic}/gu, '');
      };
      if(eName) eName.addEventListener('input', blockEmoji);
      if(eKota) eKota.addEventListener('input', blockEmoji);

      const eText = document.getElementById('e-text');
      const eTextCount = document.getElementById('e-text-count');
      if (eTextCount) eTextCount.textContent = `${r.text.length} / 500 karakter`;
      if (eText && eTextCount) {
          eText.addEventListener('input', () => {
              eTextCount.textContent = `${eText.value.length} / 500 karakter`;
          });
      }
      const labels = { 5:'Luar Biasa!', 4:'Bagus', 3:'Cukup', 2:'Kurang', 1:'Buruk' };
      const ratingRadio = document.querySelector(`input[name="e_rating"][value="${r.rating}"]`);
      if (ratingRadio) ratingRadio.checked = true;
      
      const eRatingRadios = document.querySelectorAll('input[name="e_rating"]');
      const eRatingLabel = document.getElementById('e-rating-label');
      if (eRatingLabel) eRatingLabel.textContent = labels[r.rating] || 'Luar Biasa!';
      
      eRatingRadios.forEach(radio => {
          radio.addEventListener('change', (e) => {
              if (eRatingLabel) eRatingLabel.textContent = labels[e.target.value];
          });
      });
      
      if (r.photo_url) { 
        const wrap = Utils.el('e-current-photo-wrap'), img = Utils.el('e-current-photo'); 
        if (img) img.src = r.photo_url; 
        if (wrap) wrap.style.display = 'block'; 
      }

      Utils.el('e-photo')?.addEventListener('change', function () {
        const f = this.files[0]; if (!f) return;
        if (f.size > 5 * 1024 * 1024) { Utils.toast('Ukuran foto maksimal 5MB', 'error'); this.value = ''; return; }
        const reader = new FileReader(); reader.onload = ev => { 
          const wrap = Utils.el('e-new-photo-wrap'), img = Utils.el('e-new-photo'); 
          if (img) img.src = ev.target.result; 
          if (wrap) wrap.style.display = 'block'; 
          const dropzone = Utils.el('e-photo-dropzone');
          if (dropzone) dropzone.style.display = 'none';
        }; reader.readAsDataURL(f);
      });

      Utils.el('e-remove-photo')?.addEventListener('click', async () => {
        const fd = new FormData(); fd.append('action', 'edit'); fd.append('id', id); fd.append('remove_photo', '1');
        fd.append('name', Utils.el('e-name').value); fd.append('kota', Utils.el('e-kota').value); fd.append('text', Utils.el('e-text').value); fd.append('status', Utils.el('e-status').value); 
        const ratingVal = document.querySelector('input[name="e_rating"]:checked')?.value || 5;
        fd.append('rating', ratingVal);
        const res = await fetch(API + 'reviews.php', { method: 'POST', body: fd }).then(r => r.json());
        if (res.success) { const wrap = Utils.el('e-current-photo-wrap'); if (wrap) wrap.style.display = 'none'; Utils.toast('Foto dihapus.', 'info'); }
      });

      Utils.el('edit-form')?.addEventListener('submit', async e => {
        e.preventDefault();

        let isValid = true;
        const eName = document.getElementById('e-name');
        const eKota = document.getElementById('e-kota');
        const eText = document.getElementById('e-text');
        const ratingVal = document.querySelector('input[name="e_rating"]:checked')?.value;

        const errName = document.getElementById('e-name-error');
        const errKota = document.getElementById('e-kota-error');
        const errText = document.getElementById('e-text-error');
        const errRating = document.getElementById('e-rating-error');

        eName.classList.remove('is-invalid');
        eKota.classList.remove('is-invalid');
        eText.classList.remove('is-invalid');
        if(errName) errName.style.display = 'none';
        if(errKota) errKota.style.display = 'none';
        if(errText) errText.style.display = 'none';
        if(errRating) errRating.style.display = 'none';

        if (!ratingVal) {
          isValid = false;
          if(errRating) { errRating.style.display = 'block'; errRating.querySelector('.msg').textContent = 'Pilih rating bintang terlebih dahulu'; }
        }

        const nameVal = eName.value.trim();
        if (!nameVal) {
          isValid = false; eName.classList.add('is-invalid');
          if(errName) { errName.style.display = 'block'; errName.querySelector('.msg').textContent = 'Nama lengkap wajib diisi'; }
        } else if (nameVal.length < 5 || nameVal.length > 15) {
          isValid = false; eName.classList.add('is-invalid');
          if(errName) { errName.style.display = 'block'; errName.querySelector('.msg').textContent = 'Nama harus 5-15 karakter'; }
        }

        const kotaVal = eKota.value.trim();
        if (kotaVal && kotaVal.length > 20) {
          isValid = false; eKota.classList.add('is-invalid');
          if(errKota) { errKota.style.display = 'block'; errKota.querySelector('.msg').textContent = 'Asal kota maksimal 20 karakter'; }
        }

        const textVal = eText.value.trim();
        if (!textVal) {
          isValid = false; eText.classList.add('is-invalid');
          if(errText) { errText.style.display = 'block'; errText.querySelector('.msg').textContent = 'Teks ulasan wajib diisi'; }
        } else if (textVal.length < 10 || textVal.length > 500) {
          isValid = false; eText.classList.add('is-invalid');
          if(errText) { errText.style.display = 'block'; errText.querySelector('.msg').textContent = 'Ulasan harus 10-500 karakter'; }
        }

        if (!isValid) return;

        const fd = new FormData();
        fd.append('action', 'edit'); fd.append('id', id);
        fd.append('name',   nameVal);
        fd.append('kota',   kotaVal);
        fd.append('text',   textVal);
        fd.append('status', Utils.el('e-status').value);
        fd.append('rating', ratingVal || 5);
        
        const file = Utils.el('e-photo')?.files[0];
        if (file) fd.append('photo', file);
        
        const btn = document.getElementById('edit-submit-btn');
        if (btn) { btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...'; }

        const res = await fetch(API + 'reviews.php', { method: 'POST', body: fd }).then(r => r.json());
        Utils.toast(res.message || (res.success ? 'Diperbarui!' : 'Gagal!'), res.success ? 'success' : 'error');
        
        if (res.success) {
          const urlParams = new URLSearchParams(window.location.search);
          const fromTab = urlParams.get('from') || 'semua';
          setTimeout(() => window.location.href = `kelola_ulasan.php?tab=${fromTab}`, 800);
        } else {
            if (btn) { btn.disabled = false; btn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan'; }
        }
      });
    }
  };

  const KelolaFasilitas = {
    async init() { await this.render(); },
    async render() {
      const tbody = Utils.el('fac-tbody'); if (!tbody) return;
      tbody.innerHTML = '<tr><td colspan="6" class="text-center" style="padding:1.5rem;color:var(--gray-400)"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</td></tr>';
      const res = await Utils.get('facilities.php');
      if (!res.success) { tbody.innerHTML = '<tr><td colspan="6" class="text-center" style="color:var(--red-500);padding:2rem">Gagal memuat data.</td></tr>'; return; }
      const items = res.data; Utils.set('stat-fac-count', items.length);
      tbody.innerHTML = items.length ? items.map((f, i) => {
          let badgeClass = 'badge-available';
          if (f.status === 'Tidak Tersedia') badgeClass = 'badge-unavailable';
          else if (f.status === 'Dalam Renovasi') badgeClass = 'badge-renovation';
          
          return `
            <tr>
              <td>${i + 1}</td>
              <td><div style="display:flex;align-items:center;gap:.6rem"><div class="fac-thumb">${f.photo_url ? `<img src="${f.photo_url}" alt="${f.name}" loading="lazy">` : '<i class="fa-regular fa-image"></i>'}</div><strong>${f.name}</strong></div></td>
              <td><span style="background:rgba(46,125,90,.1);color:var(--green-700);padding:.2rem .6rem;border-radius:20px;font-size:.75rem;font-weight:600">${f.tag}</span></td>
              <td>${f.capacity}</td><td><span class="${badgeClass}">${f.status}</span></td>
              <td style="white-space:nowrap">
                <button class="act-btn act-edit me-1 js-fac-edit" data-id="${f.id}"><i class="fa-solid fa-pen"></i> Edit</button>
                <button class="act-btn act-delete js-fac-del"     data-id="${f.id}"><i class="fa-solid fa-trash"></i></button>
              </td>
            </tr>`;
      }).join('') : '<tr><td colspan="6" class="text-center" style="padding:2rem;color:var(--gray-400)">Belum ada fasilitas.</td></tr>';

      tbody.onclick = e => {
        const editBtn = e.target.closest('.js-fac-edit'); const delBtn = e.target.closest('.js-fac-del');
        if (editBtn) this.openModal(editBtn.dataset.id);
        if (delBtn)  AdminActions.confirmDelete(delBtn.dataset.id, 'facility');
      };
    },
    async openModal(id = null) {
      let modal; try { modal = Utils.getOrCreateModal(Utils.el('fac-modal')); } catch (err) { return; }
      Utils.el('fac-form')?.reset(); Utils.el('fac-id').value = '';
      const removeFlag = Utils.el('fac-remove-photo'); if (removeFlag) removeFlag.value = '0';
      Utils.el('fac-modal-title').textContent = id ? 'Edit Fasilitas' : 'Tambah Fasilitas';
      const curWrap = Utils.el('fac-current-photo-wrap'), newWrap = Utils.el('fac-new-photo-wrap');
      if (curWrap) curWrap.style.display = 'none'; if (newWrap) newWrap.style.display = 'none';

      if (id) {
        const res = await Utils.get('facilities.php', { action: 'one', id });
        if (!res.success) return;
        const f = res.data;
        Utils.el('fac-id').value = f.id; Utils.el('fac-name').value = f.name; Utils.el('fac-tag').value = f.tag; Utils.el('fac-capacity').value = f.capacity; Utils.el('fac-status').value = f.status; Utils.el('fac-desc').value = f.desc;
        if (f.photo_url && curWrap) { const img = Utils.el('fac-current-photo'); if (img) img.src = f.photo_url; curWrap.style.display = 'block'; }
      }
      modal.show();
    },
    async saveForm() {
      const id = Utils.el('fac-id').value; const fd = new FormData();
      fd.append('action', id ? 'edit' : 'add'); if (id) fd.append('id', id);
      fd.append('name', Utils.el('fac-name').value); fd.append('tag', Utils.el('fac-tag').value); fd.append('capacity', Utils.el('fac-capacity').value); fd.append('status', Utils.el('fac-status').value); fd.append('desc', Utils.el('fac-desc').value); fd.append('remove_photo', Utils.el('fac-remove-photo')?.value || '0');
      const file = Utils.el('fac-photo')?.files[0]; if (file) fd.append('photo', file);

      const res = await fetch(API + 'facilities.php', { method: 'POST', body: fd }).then(r => r.json());
      Utils.toast(res.message || (res.success ? 'Berhasil!' : 'Gagal!'), res.success ? 'success' : 'error');
      if (res.success) { bootstrap.Modal.getInstance(Utils.el('fac-modal'))?.hide(); await this.render(); }
    },
  };

  const KelolaGaleri = {
    async init() { await this.render(); },
    async render() {
      const grid = Utils.el('gallery-admin-grid'); if (!grid) return;
      grid.innerHTML = '<div class="col-12 text-center" style="padding:2rem;color:var(--gray-400)"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>';
      const res = await Utils.get('gallery.php');
      if (!res.success) { grid.innerHTML = '<div class="col-12" style="color:var(--red-500);text-align:center;padding:2rem">Gagal memuat data.</div>'; return; }
      const items = res.data; Utils.set('stat-gal-count', items.length);

      if (!items.length) { grid.innerHTML = '<div class="col-12" style="text-align:center;padding:2rem;color:var(--gray-400)">Galeri kosong. Tambahkan foto baru.</div>'; return; }
      grid.innerHTML = items.map(g => `
        <div class="col-6 col-md-4 col-lg-3">
          <div class="gallery-admin-card${g.featured ? ' is-featured' : ''}${g.is_home ? ' is-home-photo' : ''}">
            <div class="gallery-admin-thumb">
              ${g.photo_url ? `<img src="${g.photo_url}" alt="${g.caption}" loading="lazy">` : '<i class="fa-regular fa-image"></i>'}
              ${g.featured ? '<span class="gallery-featured-badge"><i class="fa-solid fa-star"></i> Utama</span>' : ''}
              ${g.is_home  ? '<span class="gallery-home-badge"><i class="fa-solid fa-house"></i> Home</span>' : ''}
            </div>
            <div class="gallery-admin-info">
              <div class="gallery-admin-caption">${g.caption}</div>
              <div class="gallery-admin-actions" style="display:flex;gap:.35rem;flex-wrap:wrap">
                <button class="act-btn js-gal-home ${g.is_home ? 'act-home-active' : 'act-home'}" data-id="${g.id}" title="${g.is_home ? 'Lepas Home' : 'Jadikan Home'}" style="flex:1;justify-content:center">
                  <i class="fa-solid fa-house"></i>
                </button>
                <button class="act-btn act-edit js-gal-edit" data-id="${g.id}" style="flex:1;justify-content:center"><i class="fa-solid fa-pen"></i></button>
                <button class="act-btn act-delete js-gal-del" data-id="${g.id}" style="flex:1;justify-content:center"><i class="fa-solid fa-trash"></i></button>
              </div>
            </div>
          </div>
        </div>`).join('');

      grid.onclick = e => {
        const editBtn = e.target.closest('.js-gal-edit'); const delBtn  = e.target.closest('.js-gal-del'); const homeBtn = e.target.closest('.js-gal-home');
        if (editBtn) this.openModal(editBtn.dataset.id);
        if (delBtn)  AdminActions.confirmDelete(delBtn.dataset.id, 'gallery');
        if (homeBtn) this.setHomePhoto(homeBtn.dataset.id);
      };
    },
    async setHomePhoto(id) {
      try {
        const fd = new FormData(); fd.append('action', 'set_home'); fd.append('id', id);
        const res = await fetch(API + 'gallery.php', { method: 'POST', body: fd }).then(r => r.json());
        Utils.toast(res.message || (res.success ? 'Berhasil!' : 'Gagal.'), res.success ? 'success' : 'error');
        if (res.success) await this.render();
      } catch (err) { Utils.toast('Terjadi kesalahan.', 'error'); }
    },
    async openModal(id = null) {
      let modal; try { modal = Utils.getOrCreateModal(Utils.el('gal-modal')); } catch (err) { return; }
      Utils.el('gal-form')?.reset(); Utils.el('gal-id').value = '';
      const removeFlag = Utils.el('gal-remove-photo'); if (removeFlag) removeFlag.value = '0';
      Utils.el('gal-modal-title').textContent = id ? 'Edit Foto' : 'Tambah Foto';
      const curWrap = Utils.el('gal-current-photo-wrap'), newWrap = Utils.el('gal-new-photo-wrap');
      if (curWrap) curWrap.style.display = 'none'; if (newWrap) newWrap.style.display = 'none';

      if (id) {
        const res = await Utils.get('gallery.php', { action: 'one', id });
        if (!res.success) return;
        const g = res.data;
        Utils.el('gal-id').value = g.id; Utils.el('gal-caption').value = g.caption;
        const feat = Utils.el('gal-featured'); if (feat) feat.checked = !!g.featured;
        if (g.photo_url && curWrap) { const img = Utils.el('gal-current-photo'); if (img) img.src = g.photo_url; curWrap.style.display = 'block'; }
      }
      modal.show();
    },
    async saveForm() {
      const id = Utils.el('gal-id').value; const fd = new FormData();
      fd.append('action', id ? 'edit' : 'add'); if (id) fd.append('id', id);
      fd.append('caption', Utils.el('gal-caption').value); fd.append('featured', Utils.el('gal-featured')?.checked ? '1' : ''); fd.append('remove_photo', Utils.el('gal-remove-photo')?.value || '0');
      const file = Utils.el('gal-photo')?.files[0]; if (file) fd.append('photo', file);

      const res = await fetch(API + 'gallery.php', { method: 'POST', body: fd }).then(r => r.json());
      Utils.toast(res.message || (res.success ? 'Berhasil!' : 'Gagal!'), res.success ? 'success' : 'error');
      if (res.success) { bootstrap.Modal.getInstance(Utils.el('gal-modal'))?.hide(); await this.render(); }
    },
  };

  const DetailPage = {
    async init() { await Promise.all([this.renderFacilities(), this.renderGallery()]); Utils.scrollReveal(); },
    async renderFacilities() {
      const grid = Utils.el('facilities-grid'); if (!grid) return;
      const res  = await Utils.get('facilities.php'); if (!res.success) return;
      grid.innerHTML = res.data.map(f => {
          let badgeClass = 'badge-available';
          if (f.status === 'Tidak Tersedia') badgeClass = 'badge-unavailable';
          else if (f.status === 'Dalam Renovasi') badgeClass = 'badge-renovation';

          return `
            <div class="col-md-6 col-lg-4 fade-up">
              <div class="facility-card">
                <div class="facility-photo">${f.photo_url ? `<img src="${f.photo_url}" alt="${f.name}" loading="lazy">` : `<i class="fa-regular fa-image"></i><span>Foto Belum Tersedia</span>`}</div>
                <div class="facility-body"><span class="facility-tag">${f.tag}</span><div class="facility-name">${f.name}</div><p class="facility-desc">${f.desc}</p><div class="facility-meta"><span class="facility-cap"><i class="fa-solid fa-users"></i> ${f.capacity}</span><span class="${badgeClass}">${f.status}</span></div></div>
              </div>
            </div>`;
      }).join('');
      setTimeout(() => Utils.scrollReveal(), 50);
    },
    async renderGallery() {
      const grid = Utils.el('gallery-grid'); if (!grid) return;
      const res  = await Utils.get('gallery.php'); if (!res.success) return;
      const items = res.data.slice(0, 7); if (!items.length) return;
      grid.innerHTML = items.map((g, i) => `
        <div class="gallery-item" data-id="${g.id}" title="${g.caption}">
          ${g.photo_url ? `<img src="${g.photo_url}" alt="${g.caption}" loading="lazy">` : `<i class="fa-regular fa-image"></i><span>${i === 0 ? 'Foto Utama' : 'Foto ' + (i + 1)}</span>`}
          <div class="gallery-overlay"><span><i class="fa-solid fa-magnifying-glass-plus"></i> ${g.caption}</span></div>
        </div>`).join('');

      grid.querySelectorAll('.gallery-item').forEach(item => {
        item.addEventListener('click', () => {
          const g = res.data.find(x => x.id === +item.dataset.id); if (!g) return;
          const body = Utils.el('modal-gallery-body');
          if (body) {
            body.innerHTML = g.photo_url ? `<img src="${g.photo_url}" style="width:100%;border-radius:var(--radius-sm);max-height:450px;object-fit:contain" loading="lazy">` : `<div style="height:240px;background:var(--gray-100);display:flex;align-items:center;justify-content:center;border-radius:var(--radius-sm);flex-direction:column;gap:.5rem;color:var(--gray-400)"><i class="fa-regular fa-image" style="font-size:2.5rem;opacity:.4"></i><span style="font-size:.8rem;font-weight:600">Foto Belum Tersedia</span></div>`;
            body.innerHTML += `<p style="color:var(--gray-500);font-size:.84rem;text-align:center;margin-top:.75rem">${g.caption} - Masjid Raya Darussalam Samarinda</p>`;
          }
          Utils.getOrCreateModal(Utils.el('gallery-modal')).show();
        });
      });
    },
  };

  const PageInit = {
    home() {
      document.getElementById('hamburger')?.addEventListener('click', function () {
        this.classList.toggle('open');
        document.getElementById('nav-mobile').classList.toggle('open');
      });
      document.getElementById('admin-logout-btn')?.addEventListener('click', async e => {
        e.preventDefault();
        window._ctrl.confirmLogout();
      });
      document.addEventListener('DOMContentLoaded', () => { Home.init(); setTimeout(() => Home.renderDots(), 120); });
    },
    detail() {
      document.getElementById('hamburger')?.addEventListener('click', function () { 
        this.classList.toggle('open'); 
        document.getElementById('nav-mobile').classList.toggle('open'); 
      });
      document.getElementById('admin-logout-btn')?.addEventListener('click', async e => {
        e.preventDefault(); 
        window._ctrl.confirmLogout();
      });
      document.addEventListener('DOMContentLoaded', () => { DetailPage.init(); });
    },
    ulasan() {
      document.getElementById('hamburger')?.addEventListener('click', function () { 
        this.classList.toggle('open'); 
        document.getElementById('nav-mobile').classList.toggle('open'); 
      });
      document.getElementById('admin-logout-btn')?.addEventListener('click', async e => {
        e.preventDefault(); 
        window._ctrl.confirmLogout();
      });
    },
    kelolaUlasan() {
      document.addEventListener('DOMContentLoaded', () => { AdminUlasan.init(); });
    },
    login() { document.addEventListener('DOMContentLoaded', () => { Auth.initLogin(); }); },
    dashboard() {
      document.addEventListener('DOMContentLoaded', () => { AdminDash.init(); });
    },
    kelolaFasilitas() {
      document.addEventListener('DOMContentLoaded', () => {
        KelolaFasilitas.init();
        document.getElementById('fac-photo')?.addEventListener('change', function () {
          const f = this.files[0];
          if (!f) return;
          if (f.size > 5 * 1024 * 1024) { Utils.toast('Ukuran foto maksimal 5MB!', 'error'); this.value = ''; return; }
          const reader = new FileReader();
          reader.onload = ev => {
            const img  = document.getElementById('fac-new-photo');
            const wrap = document.getElementById('fac-new-photo-wrap');
            if (img)  img.src = ev.target.result;
            if (wrap) wrap.style.display = 'block';
          };
          reader.readAsDataURL(f);
        });
        document.getElementById('fac-cancel-new-photo')?.addEventListener('click', () => {
          const input = document.getElementById('fac-photo');
          const wrap  = document.getElementById('fac-new-photo-wrap');
          if (input) input.value = '';
          if (wrap)  wrap.style.display = 'none';
        });
        document.getElementById('fac-remove-photo-btn')?.addEventListener('click', () => {
          const removeFlag = document.getElementById('fac-remove-photo');
          if (removeFlag) removeFlag.value = '1';
          const curWrap = document.getElementById('fac-current-photo-wrap');
          if (curWrap) curWrap.style.display = 'none';
          Utils.toast('Foto lama akan dihapus saat disimpan.', 'info');
        });
      });
    },
    kelolaGaleri() {
      document.addEventListener('DOMContentLoaded', () => {
        KelolaGaleri.init();
        
        document.getElementById('gal-photo')?.addEventListener('change', function () {
          const f = this.files[0];
          if (!f) return;
          if (f.size > 5 * 1024 * 1024) { Utils.toast('Ukuran foto maksimal 5MB!', 'error'); this.value = ''; return; }
          const reader = new FileReader();
          reader.onload = ev => {
            const img  = document.getElementById('gal-new-photo');
            const wrap = document.getElementById('gal-new-photo-wrap');
            if (img)  img.src = ev.target.result;
            if (wrap) wrap.style.display = 'block';
          };
          reader.readAsDataURL(f);
        });
        document.getElementById('gal-cancel-new-photo')?.addEventListener('click', () => {
          const input = document.getElementById('gal-photo');
          const wrap  = document.getElementById('gal-new-photo-wrap');
          if (input) input.value = '';
          if (wrap)  wrap.style.display = 'none';
        });
        document.getElementById('gal-remove-photo-btn')?.addEventListener('click', () => {
          const removeFlag = document.getElementById('gal-remove-photo');
          if (removeFlag) removeFlag.value = '1';
          const curWrap = document.getElementById('gal-current-photo-wrap');
          if (curWrap) curWrap.style.display = 'none';
          Utils.toast('Foto lama dihapus saat disimpan.', 'info');
        });

        window._ctrl.switchGalTab = (tab) => {
          document.querySelectorAll('.ku-pane').forEach(el => el.classList.remove('active'));
          document.getElementById('tab-' + tab).classList.add('active');
          document.querySelectorAll('.ku-nav-btn').forEach(el => el.classList.remove('active'));
          document.getElementById('tab-btn-' + tab).classList.add('active');
          
          const btn = document.getElementById('btn-add-content');
          if (btn) {
            if (tab === 'foto') {
              btn.innerHTML = '<i class="fa-solid fa-plus"></i> Tambah Foto';
              btn.onclick = () => window._ctrl.openNewGal();
            } else {
              btn.innerHTML = '<i class="fa-solid fa-plus"></i> Tambah Video';
              btn.onclick = () => window._ctrl.vidOpenModal();
            }
          }
          if (tab === 'video' && typeof vidLoad === 'function') vidLoad();
        };

        const API_VID = window.APP_BASE + '/api/video.php';

        const vidHelpers = {
          extractYoutubeId(url) { const patterns = [ /[?&]v=([^&?#]{11})/, /youtu\.be\/([^?&#]{11})/, /\/embed\/([^?&#]{11})/, /\/live\/([^?&#]{11})/, /\/shorts\/([^?&#]{11})/ ]; for (const p of patterns) { const m = url.match(p); if (m) return m[1]; } return null; },
          escHtml(str) { return String(str).replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>').replace(/"/g, '"'); },
          updateToggle() { const cb = document.getElementById('vid-aktif'), track = document.getElementById('toggle-track'), thumb = document.getElementById('toggle-thumb'); if (!cb || !track || !thumb) return; track.style.background = cb.checked ? 'var(--green-500)' : 'var(--gray-300)'; thumb.style.left = cb.checked ? '23px' : '3px'; },
        };

        const vidLoad = async () => {
          const grid = document.getElementById('vid-grid'), count = document.getElementById('vid-count'); if (!grid) return;
          grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><i class="fa-solid fa-spinner fa-spin"></i><span>Memuat...</span></div>`;
          try {
            const res = await fetch(API_VID + '?action=list'); const json = await res.json();
            if (!json.success) throw new Error(json.message);
            if (count) count.textContent = `${json.data.length} video`;
            if (json.data.length === 0) { grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><i class="fa-brands fa-youtube"></i><span>Belum ada video. Klik <strong>Tambah Video</strong> untuk memulai.</span></div>`; return; }
            grid.innerHTML = json.data.map(v => {
              const thumb = `https://img.youtube.com/vi/${v.video_id}/hqdefault.jpg`; const isAktif = parseInt(v.aktif) === 1; const urutan = parseInt(v.urutan);
              return `
                <div class="vid-admin-card" id="card-${v.id}">
                  <div class="vid-thumb-wrap">
                    <img src="${thumb}" alt="${vidHelpers.escHtml(v.judul)}" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                    <div class="vid-no-thumb" style="display:none"><i class="fa-brands fa-youtube" style="font-size:2.5rem;color:rgba(255,255,255,.3)"></i></div>
                    <span class="vid-badge">${vidHelpers.escHtml(v.kategori)}</span>
                    ${urutan > 0 ? `<span class="vid-urutan-badge">#${urutan}</span>` : ''}
                    <span class="vid-status-badge ${isAktif ? 'aktif' : 'nonaktif'}">${isAktif ? '● Aktif' : '○ Nonaktif'}</span>
                  </div>
                  <div class="vid-card-body">
                    <div class="vid-card-title">${vidHelpers.escHtml(v.judul)}</div>
                    <div class="vid-card-url"><i class="fa-brands fa-youtube" style="color:#ff0000"></i> ${vidHelpers.escHtml(v.url)}</div>
                    <div class="vid-card-actions" style="display:flex;gap:.4rem;flex-wrap:wrap">
                      <button class="act-btn act-edit" style="flex:1;justify-content:center" onclick="window._ctrl.vidOpenEdit(${v.id})"><i class="fa-solid fa-pen"></i> Edit</button>
                      <button class="act-btn act-view" style="flex:1;justify-content:center" onclick="window._ctrl.vidToggle(${v.id})" title="${isAktif ? 'Nonaktifkan' : 'Aktifkan'}"><i class="fa-solid fa-${isAktif ? 'eye-slash' : 'eye'}"></i></button>
                      <button class="act-btn act-delete" style="flex:1;justify-content:center" onclick="window._ctrl.vidConfirmHapus(${v.id}, '${vidHelpers.escHtml(v.judul).replace(/'/g, "\\'")}')"><i class="fa-solid fa-trash"></i></button>
                    </div>
                  </div>
                </div>`;
            }).join('');
          } catch (e) { grid.innerHTML = `<div class="empty-state" style="grid-column:1/-1"><i class="fa-solid fa-triangle-exclamation" style="color:var(--red-500)"></i><span>Gagal memuat data: ${e.message}</span></div>`; }
        };

        window._ctrl.vidLoad = vidLoad;

        window._ctrl.vidOpenModal = async (id = null) => {
          const modal = new bootstrap.Modal(document.getElementById('vid-modal'));
          document.getElementById('vid-url').value = ''; document.getElementById('vid-judul').value = ''; document.getElementById('vid-kategori').value = 'Lainnya'; document.getElementById('vid-urutan').value = '0'; document.getElementById('vid-aktif').checked = true; document.getElementById('vid-id').value = '';
          document.getElementById('modal-title-text').textContent = id ? 'Edit Video' : 'Tambah Video YouTube'; document.getElementById('save-btn-text').textContent = id ? 'Update Video' : 'Simpan Video';
          document.getElementById('thumb-preview').style.display = 'none'; document.getElementById('thumb-placeholder').style.display = 'flex';
          vidHelpers.updateToggle();
          
          if (id) {
            try {
              const res = await fetch(API_VID + '?action=get&id=' + id); const json = await res.json();
              if (!json.success) throw new Error(json.message);
              const v = json.data;
              document.getElementById('vid-id').value = v.id; document.getElementById('vid-url').value = v.url; document.getElementById('vid-judul').value = v.judul; document.getElementById('vid-kategori').value = v.kategori; document.getElementById('vid-urutan').value = v.urutan; document.getElementById('vid-aktif').checked = parseInt(v.aktif) === 1;
              vidHelpers.updateToggle();
              if (v.video_id) { document.getElementById('thumb-preview').src = `https://img.youtube.com/vi/${v.video_id}/hqdefault.jpg`; document.getElementById('thumb-preview').style.display = 'block'; document.getElementById('thumb-placeholder').style.display = 'none'; }
            } catch (e) { Utils.toast('Gagal memuat data video: ' + e.message, 'error'); return; }
          }
          modal.show();
        };

        window._ctrl.vidOpenEdit = (id) => window._ctrl.vidOpenModal(id);

        window._ctrl.vidSave = async () => {
          const id = document.getElementById('vid-id').value, url = document.getElementById('vid-url').value.trim(), judul = document.getElementById('vid-judul').value.trim();
          if (!url) { Utils.toast('URL YouTube wajib diisi!', 'error'); return; }
          if (!judul) { Utils.toast('Judul video wajib diisi!', 'error'); return; }

          const fd = new FormData(); fd.append('action', id ? 'edit' : 'tambah'); if (id) fd.append('id', id);
          fd.append('url', url); fd.append('judul', judul); fd.append('kategori', document.getElementById('vid-kategori').value); fd.append('urutan', document.getElementById('vid-urutan').value);
          if (document.getElementById('vid-aktif').checked) fd.append('aktif', '1');
          
          const csrf = document.getElementById('vid-csrf');
          if (csrf) fd.append('csrf_token', csrf.value);

          try {
            const res = await fetch(API_VID, { method: 'POST', body: fd }); const json = await res.json();
            if (!json.success) throw new Error(json.message);
            Utils.toast(json.message, 'success'); bootstrap.Modal.getInstance(document.getElementById('vid-modal')).hide(); vidLoad();
          } catch (e) { Utils.toast('Error: ' + e.message, 'error'); }
        };

        window._ctrl.vidToggle = async (id) => {
          try {
            const fd = new FormData(); fd.append('action', 'toggle'); fd.append('id', id);
            const csrf = document.getElementById('vid-csrf');
            if (csrf) fd.append('csrf_token', csrf.value);
            const res = await fetch(API_VID, { method: 'POST', body: fd }); const json = await res.json();
            if (!json.success) throw new Error(json.message);
            Utils.toast(json.message, 'success'); vidLoad();
          } catch (e) { Utils.toast('Error: ' + e.message, 'error'); }
        };

        window._ctrl.vidConfirmHapus = (id, judul) => {
          document.getElementById('del-id').value = id;
          document.getElementById('del-type').value = 'video';
          document.getElementById('del-message').textContent = `Video "${judul}" akan dihapus secara permanen.`;
          new bootstrap.Modal(document.getElementById('delete-modal')).show();
        };

        document.getElementById('vid-aktif')?.addEventListener('change', vidHelpers.updateToggle);
        document.getElementById('vid-url')?.addEventListener('input', function () {
          const input = this; setTimeout(() => {
            const id = vidHelpers.extractYoutubeId(input.value.trim()), preview = document.getElementById('thumb-preview'), holder = document.getElementById('thumb-placeholder');
            if (id) { preview.src = `https://img.youtube.com/vi/${id}/hqdefault.jpg`; preview.style.display = 'block'; holder.style.display = 'none'; } else { preview.style.display = 'none'; holder.style.display = 'flex'; }
          }, 600);
        });
        document.getElementById('toggle-track')?.addEventListener('click', () => { document.getElementById('vid-aktif')?.click(); vidHelpers.updateToggle(); });
      });
    },
    aksiEdit() { document.addEventListener('DOMContentLoaded', async () => { await AdminActions.initEdit(); }); },
    aksiTambah() { document.addEventListener('DOMContentLoaded', () => { AdminUlasan.bindForm(); }); },
  };

  window._ctrl = window._ctrl || {};
  Object.assign(window._ctrl, {
    sortPending   : () => AdminUlasan.renderPendingTable(),
    sortAll       : () => AdminUlasan.renderAllTable(),
    approve       : id      => AdminActions.approve(id),
    confirmDelete : (id, t) => AdminActions.confirmDelete(id, t),
    doDelete      : async () => {
      const id = document.getElementById('del-id').value;
      const type = document.getElementById('del-type').value || 'gallery';
      bootstrap.Modal.getInstance(document.getElementById('delete-modal'))?.hide();
      
      const endpoints = { review: 'reviews.php', facility: 'facilities.php', gallery: 'gallery.php', video: 'video.php' };
      const ep = endpoints[type]; if (!ep) return;
      try {
        const fd = new FormData(); 
        fd.append('action', type === 'video' ? 'hapus' : 'delete'); 
        fd.append('id', id);
        
        if (type === 'video') {
            const csrf = document.getElementById('vid-csrf');
            if (csrf) fd.append('csrf_token', csrf.value);
        }
        
        const res = await fetch(API + ep, { method: 'POST', body: fd });
        const json = await res.json();
        if (!json.success) throw new Error(json.message);
        Utils.toast(json.message, 'success');
        
        if (type === 'review') {
          if (document.getElementById('pending-readonly-tbody')) { await AdminDash.renderStats(); await AdminDash.renderPending(); await AdminDash.renderAll(); }
          if (document.getElementById('pending-tbody')) { await AdminUlasan.loadPending(); await AdminUlasan.loadAll(); }
        }
        else if (type === 'video') {
          if (window._ctrl.vidLoad) window._ctrl.vidLoad(); else window.location.reload();
        }
        else if (type === 'facility') await KelolaFasilitas.render();
        else await KelolaGaleri.render();
      } catch (e) { Utils.toast('Error: ' + e.message, 'error'); }
    },
    confirmLogout : () => {
      const modalEl = document.getElementById('logout-modal');
      if (modalEl) new bootstrap.Modal(modalEl).show();
    },
    doLogout      : async () => {
      const modalInst = bootstrap.Modal.getInstance(document.getElementById('logout-modal'));
      if(modalInst) modalInst.hide();
      
      const fd = new FormData(); 
      fd.append('action', 'logout'); 
      await fetch(API + 'auth.php', { method: 'POST', body: fd });
      
      
      const rootUrl = window.APP_BASE || '../../index.php';
      window.location.replace(rootUrl);
    },
    openFacModal  : id      => KelolaFasilitas.openModal(id),
    openNewFac    : ()      => KelolaFasilitas.openModal(),
    saveFac       : ()      => KelolaFasilitas.saveForm(),
    openGalModal  : id      => KelolaGaleri.openModal(id),
    openNewGal    : ()      => KelolaGaleri.openModal(),
    saveGal       : ()      => KelolaGaleri.saveForm(),
    previewPhoto  : (url, name) => {
      const img = Utils.el('photo-preview-img'), nameEl = Utils.el('photo-preview-name');
      if (img) img.src = url; if (nameEl) nameEl.textContent = 'Foto dari: ' + name;
      const modalEl = Utils.el('photo-preview-modal'); if (modalEl) (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)).show();
    },
  });

  return { Home, Auth, AdminDash, AdminActions, DetailPage, KelolaFasilitas, KelolaGaleri, Prayer, Utils, PageInit };

})();