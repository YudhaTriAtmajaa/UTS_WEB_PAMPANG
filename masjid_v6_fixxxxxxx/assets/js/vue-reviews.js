const { createApp, ref, computed, onMounted, watch } = Vue;

function getApiBase() {
  if (window.APP_BASE) return window.APP_BASE.replace(/\/$/, '') + '/api/';
  const segs    = window.location.pathname.split('/').filter(Boolean);
  const project = segs[0] || '';
  const origin  = window.location.origin;
  return project ? `${origin}/${project}/api/` : `${origin}/api/`;
}

function fmtDate(d) {
  return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
}

function initListApp(mountSelector) {
  return createApp({
    setup() {
      const API = getApiBase();
      const reviews      = ref([]);
      const loaded       = ref(false);
      const activeFilter = ref('all');

      async function fetchReviews() {
        try {
          const res  = await fetch(API + 'reviews.php?action=approved');
          const json = await res.json();
          reviews.value = Array.isArray(json.data) ? json.data : [];
        } catch (e) {
          reviews.value = [];
        } finally {
          loaded.value = true;
        }
      }

      window._vuReviewsRefresh = () => fetchReviews();

      const total = computed(() => reviews.value.length);
      const avg   = computed(() => {
        if (!total.value) return '0.0';
        const sum = reviews.value.reduce((s, r) => s + (+r.rating || 0), 0);
        return (sum / total.value).toFixed(1);
      });
      const avgStars = computed(() => Math.round(+avg.value));

      const barStats = computed(() =>
        [5,4,3,2,1].map(r => {
          const cnt = reviews.value.filter(x => +x.rating === r).length;
          const pct = total.value > 0 ? (cnt / total.value * 100) : 0;
          return { r, cnt, pct };
        })
      );

      const filtered = computed(() =>
        activeFilter.value === 'all'
          ? reviews.value
          : reviews.value.filter(x => +x.rating === +activeFilter.value)
      );

      function countByRating(r) {
        return reviews.value.filter(x => +x.rating === r).length;
      }

      const name         = ref('');
      const kota         = ref('');
      const text         = ref('');
      const rating       = ref(0);
      const hovered      = ref(0);
      const photoFile    = ref(null);
      const photoPreview = ref('');
      const submitting   = ref(false);
      const submitStatus = ref('');
      const submitMsg    = ref('');
      const errors       = ref({});

      watch(name, (newVal) => {
        name.value = newVal.replace(/\p{Extended_Pictographic}/gu, '');
      });
      watch(kota, (newVal) => {
        kota.value = newVal.replace(/\p{Extended_Pictographic}/gu, '');
      });

      const displayRating = computed(() => hovered.value || rating.value);
      const ratingLabel   = computed(() => {
        const labels = { 5:'Luar Biasa!', 4:'Bagus', 3:'Cukup', 2:'Kurang', 1:'Buruk' };
        return displayRating.value ? labels[displayRating.value] : 'Pilih rating';
      });

      function onPhotoChange(e) {
        const f = e.target.files[0];
        if (!f) return;
        if (f.size > 5 * 1024 * 1024) { alert('Ukuran foto maks 5MB'); return; }
        photoFile.value = f;
        const reader = new FileReader();
        reader.onload = ev => { photoPreview.value = ev.target.result; };
        reader.readAsDataURL(f);
      }

      function removePhoto() {
        photoFile.value    = null;
        photoPreview.value = '';
        const inp = document.getElementById('vue-review-photo');
        if (inp) inp.value = '';
      }

      function validate() {
        const e = {};
        if (!rating.value) e.rating = 'Pilih rating bintang terlebih dahulu';
        
        if (!name.value.trim()) e.name = 'Nama lengkap wajib diisi';
        else if (name.value.length < 5 || name.value.length > 15) e.name = 'Nama harus 5-15 karakter';

        if (kota.value && kota.value.length > 20) e.kota = 'Asal kota maksimal 20 karakter';

        if (!text.value.trim()) e.text = 'Ceritakan pengalaman Anda';
        else if (text.value.length < 10 || text.value.length > 500) e.text = 'Ulasan harus 10-500 karakter';

        errors.value = e;
        return Object.keys(e).length === 0;
      }

      async function submitReview() {
        if (!validate()) return;
        submitting.value   = true;
        submitStatus.value = '';
        try {
          const fd = new FormData();
          fd.append('action', 'add');
          fd.append('name',   name.value.trim());
          fd.append('kota',   kota.value.trim());
          fd.append('text',   text.value.trim());
          fd.append('rating', String(rating.value));
          if (photoFile.value) fd.append('photo', photoFile.value);

          const res  = await fetch(API + 'reviews.php', { method: 'POST', body: fd });
          const json = await res.json();

          if (json.success) {
            submitStatus.value = 'success';
            const isPending = json.status === 'pending';
            submitMsg.value = isPending
              ? 'Ulasan Anda berhasil dikirim! Ulasan sedang menunggu persetujuan admin.'
              : (json.message || 'Ulasan berhasil ditambahkan!');
            name.value = ''; kota.value = ''; text.value = '';
            rating.value = 0; hovered.value = 0;
            photoFile.value = null; photoPreview.value = '';
            errors.value = {};
            if (!isPending) await fetchReviews();
          } else {
            submitStatus.value = 'error';
            submitMsg.value    = json.message || 'Gagal mengirim ulasan. Coba lagi.';
          }
        } catch (err) {
          submitStatus.value = 'error';
          submitMsg.value    = 'Terjadi kesalahan. Periksa koneksi Anda.';
        } finally {
          submitting.value = false;
        }
      }

      onMounted(fetchReviews);

      return {
        reviews, loaded, activeFilter, filtered,
        total, avg, avgStars, barStats,
        countByRating, fmtDate,
        name, kota, text, rating, hovered, photoPreview,
        submitting, submitStatus, submitMsg, errors,
        displayRating, ratingLabel,
        onPhotoChange, removePhoto, submitReview,
      };
    },

    template: `
      <div>
        <div class="rating-summary-box">
          <div class="container">
            <div class="row align-items-center g-4">
              <div class="col-md-3 text-center">
                <div class="avg-big">{{ avg }}</div>
                <div style="display:flex;justify-content:center;gap:3px;margin:.4rem 0">
                  <i
                    v-for="i in 5" :key="i"
                    class="fa-solid fa-star"
                    :style="{color: i<=avgStars ? '#c9a84c' : '#e5e7eb', fontSize:'.82rem'}"
                  ></i>
                </div>
                <div class="avg-outof">dari 5.0 - {{ total }} ulasan</div>
              </div>
              <div class="col-md-9">
                <div class="d-flex flex-column gap-2">
                  <div
                    v-for="s in barStats" :key="s.r"
                    class="rating-bar-wrap"
                    style="cursor:pointer"
                    @click="activeFilter = String(s.r)"
                    :title="'Filter ' + s.r + ' bintang'"
                  >
                    <span class="rating-bar-lbl" style="width:36px">
                      {{ s.r }} <i class="fa-solid fa-star" style="font-size:.6rem;color:var(--gold-500)"></i>
                    </span>
                    <div class="rating-bar-track">
                      <div
                        :class="'rating-bar-fill rbar-' + s.r"
                        :style="{width: s.pct + '%', transition:'width .6s ease'}"
                      ></div>
                    </div>
                    <span class="rating-bar-cnt" style="width:26px">{{ s.cnt }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <section style="padding:4rem 0;background:var(--gray-50)">
          <div class="container">
            <div class="row g-5">

              <div class="col-lg-7">
                <div class="star-filter-bar">
                  <button
                    :class="['star-filter-btn', activeFilter==='all' ? 'active' : '']"
                    @click="activeFilter='all'"
                  >
                    <i class="fa-solid fa-list"></i> Semua
                    <span class="filter-count">{{ total }}</span>
                  </button>
                  <button
                    v-for="r in [5,4,3,2,1]" :key="r"
                    :class="['star-filter-btn', activeFilter===String(r) ? 'active' : '']"
                    @click="activeFilter=String(r)"
                  >
                    <span class="filter-stars">
                      <i v-for="i in r" :key="i" class="fa-solid fa-star"></i>
                    </span>
                    {{ r }} Bintang
                    <span class="filter-count">{{ countByRating(r) }}</span>
                  </button>
                </div>

                <p style="font-size:.82rem;color:var(--gray-400);margin-bottom:1rem">
                  <template v-if="!loaded">
                    <i class="fa-solid fa-spinner fa-spin"></i> Memuat ulasan...
                  </template>
                  <template v-else>
                    Menampilkan {{ filtered.length }} dari {{ total }} ulasan
                    <span v-if="activeFilter !== 'all'">
                      - Filter: {{ activeFilter }} bintang
                    </span>
                  </template>
                </p>

                <div v-if="!loaded" class="text-center" style="padding:2rem;color:var(--gray-400)">
                  <i class="fa-solid fa-spinner fa-spin fa-2x"></i>
                  <p style="margin-top:.75rem">Memuat ulasan...</p>
                </div>

                <div v-else-if="filtered.length === 0" style="display:flex; align-items:center; justify-content:center; min-height:250px; text-align:center;">
                  <div style="font-size:1.35rem; font-weight:700; color:var(--gray-500);">
                    "BELUM ADA ULASAN
                    <span v-if="activeFilter!=='all'">UNTUK {{ activeFilter }} BINTANG</span>."
                  </div>
                </div>

                <transition-group v-else name="review-list" tag="div" class="row g-3">
                  <div v-for="r in filtered" :key="r.id" class="col-12">
                    <div class="review-card">
                      <div class="reviewer-row" style="display:flex;align-items:flex-start;gap:.75rem;margin-bottom:.75rem">
                        <div class="reviewer-avatar">{{ (r.name||'?')[0].toUpperCase() }}</div>
                        <div style="flex:1;min-width:0">
                          <div class="reviewer-name">{{ r.name }}</div>
                          <div v-if="r.kota" class="reviewer-meta">
                            <i class="fa-solid fa-location-dot" style="font-size:.7rem"></i>
                            {{ r.kota }}
                          </div>
                        </div>
                        <div class="text-end" style="flex-shrink:0">
                          <div style="display:flex;gap:2px;justify-content:flex-end">
                            <i
                              v-for="i in 5" :key="i"
                              class="fa-solid fa-star"
                              :style="{color: i<=(+r.rating) ? '#c9a84c' : '#e5e7eb', fontSize:'.82rem'}"
                            ></i>
                          </div>
                          <div style="font-size:.72rem;color:var(--gray-400);margin-top:.2rem">
                            {{ fmtDate(r.created_at || r.tanggal) }}
                          </div>
                        </div>
                      </div>
                      <p class="review-text" style="font-size:.88rem;color:var(--gray-600);line-height:1.8;margin-bottom:0">{{ r.text }}</p>
                      <div v-if="r.photo_url || r.foto_url" style="margin-top:.65rem">
                        <img
                          :src="r.photo_url || r.foto_url"
                          alt="Foto ulasan"
                          style="max-width:100%;border-radius:.5rem;max-height:180px;object-fit:cover"
                          loading="lazy"
                        >
                      </div>
                    </div>
                  </div>
                </transition-group>
              </div>

              <div class="col-lg-5">
                <div class="form-panel" style="position:sticky;top:90px">
                  <div class="text-center mb-2">
                    <div style="width:52px;height:52px;background:linear-gradient(135deg,var(--green-500),var(--green-800));border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem">
                      <i class="fa-solid fa-pen-to-square" style="color:var(--white);font-size:1.25rem"></i>
                    </div>
                    <h5 style="font-family:var(--font-display);color:var(--green-800);font-weight:700;margin-bottom:.3rem">Tulis Ulasan</h5>
                    <p style="font-size:.82rem;color:var(--gray-400)">Bagikan pengalaman Anda kepada pengunjung lain</p>
                  </div>
                  <div style="height:2px;background:linear-gradient(to right,transparent,var(--gold-500),transparent);margin:.5rem 0 1.5rem"></div>

                  <transition name="fade-alert">
                    <div v-if="submitStatus==='success'" class="alert-success mb-3" style="display:flex;align-items:flex-start;gap:.5rem;font-size:.83rem">
                      <i class="fa-solid fa-circle-check" style="margin-top:.1rem;flex-shrink:0"></i>
                      {{ submitMsg }}
                    </div>
                    <div v-else-if="submitStatus==='error'" class="mb-3" style="display:flex;align-items:flex-start;gap:.5rem;font-size:.83rem;background:rgba(220,38,38,.08);border-left:3px solid #dc2626;padding:.75rem 1rem;border-radius:.5rem;color:#dc2626">
                      <i class="fa-solid fa-circle-xmark" style="margin-top:.1rem;flex-shrink:0"></i>
                      {{ submitMsg }}
                    </div>
                  </transition>

                  <div style="margin-bottom:1.1rem">
                    <label class="field-label">
                      <i class="fa-solid fa-star" style="color:var(--gold-500)"></i>
                      Rating Anda <span class="field-required">*</span>
                    </label>
                    <div style="display:flex;flex-direction:row-reverse;justify-content:flex-end;gap:.3rem;font-size:1.8rem;cursor:pointer;margin-top:.35rem">
                      <i
                        v-for="i in [5,4,3,2,1]" :key="i"
                        :class="['fa-star', i <= displayRating ? 'fa-solid' : 'fa-regular']"
                        :style="{color: i <= displayRating ? '#c9a84c' : '#d1d5db', transition:'color .15s'}"
                        @mouseenter="hovered = i"
                        @mouseleave="hovered = 0"
                        @click="rating = i"
                      ></i>
                    </div>
                    <div style="font-size:.78rem;color:var(--gray-400);margin-top:.25rem;min-height:1.2em;font-style:italic">
                      {{ ratingLabel }}
                    </div>
                    <div v-if="errors.rating" style="color:#dc2626;font-size:.75rem;margin-top:.2rem">
                      <i class="fa-solid fa-circle-exclamation"></i> {{ errors.rating }}
                    </div>
                  </div>

                  <div style="margin-bottom:.85rem">
                    <label class="field-label" for="vue-name">
                      <i class="fa-solid fa-user"></i> Nama Lengkap <span class="field-required">*</span>
                    </label>
                    <input
                      type="text" id="vue-name" v-model="name"
                      class="field-input" :class="{'is-invalid': errors.name}"
                      placeholder="Masukkan nama (5-15 karakter)" maxlength="15"
                    >
                    <div v-if="errors.name" style="color:#dc2626;font-size:.75rem;margin-top:.2rem">
                      <i class="fa-solid fa-circle-exclamation"></i> {{ errors.name }}
                    </div>
                  </div>

                  <div style="margin-bottom:.85rem">
                    <label class="field-label" for="vue-kota">
                      <i class="fa-solid fa-location-dot"></i> Asal Kota
                    </label>
                    <input
                      type="text" id="vue-kota" v-model="kota"
                      class="field-input" :class="{'is-invalid': errors.kota}"
                      placeholder="contoh: Samarinda (Opsional | Maks 20 Karakter)" maxlength="20"
                    >
                    <div v-if="errors.kota" style="color:#dc2626;font-size:.75rem;margin-top:.2rem">
                      <i class="fa-solid fa-circle-exclamation"></i> {{ errors.kota }}
                    </div>
                  </div>

                  <div style="margin-bottom:.85rem">
                    <label class="field-label" for="vue-text">
                      <i class="fa-regular fa-comment-dots"></i>
                      Ceritakan Pengalaman Anda <span class="field-required">*</span>
                    </label>
                    <textarea
                      id="vue-text" v-model="text"
                      class="field-textarea" rows="4"
                      :class="{'is-invalid': errors.text}"
                      placeholder="Ulasan Anda (10-500 karakter)"
                      style="resize:vertical" maxlength="500"
                    ></textarea>
                    <div style="font-size:.72rem;color:var(--gray-400);margin-top:.25rem;text-align:right">
                      {{ text.length }} / 500 karakter
                    </div>
                    <div v-if="errors.text" style="color:#dc2626;font-size:.75rem;margin-top:.2rem">
                      <i class="fa-solid fa-circle-exclamation"></i> {{ errors.text }}
                    </div>
                  </div>

                  <div style="margin-bottom:1.1rem">
                    <label class="field-label">
                      <i class="fa-regular fa-image"></i>
                      Foto Kunjungan
                      <span style="color:var(--gray-400);font-weight:400;font-size:.76rem">(opsional, maks. 5MB)</span>
                    </label>
                    <div
                      v-if="!photoPreview"
                      class="upload-zone"
                      style="padding:1.25rem;cursor:pointer"
                      @click="$refs.photoInput.click()"
                    >
                      <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.5rem"></i>
                      <p style="margin-top:.35rem">Klik untuk memilih foto</p>
                      <span style="font-size:.72rem;color:var(--gray-400)">JPG, PNG, WEBP</span>
                    </div>
                    <div v-else style="margin-top:.65rem">
                      <div class="review-photo-preview-box">
                        <img :src="photoPreview" alt="Preview foto" style="max-width:100%;border-radius:.5rem">
                        <button type="button" class="review-photo-remove" @click="removePhoto">
                          <i class="fa-solid fa-xmark"></i>
                        </button>
                      </div>
                    </div>
                    <input
                      ref="photoInput" type="file"
                      accept="image/*" style="display:none"
                      @change="onPhotoChange"
                      id="vue-review-photo"
                    >
                  </div>

                  <div class="alert-success mb-4" style="font-size:.78rem;font-weight:500;display:flex;align-items:flex-start;gap:.5rem">
                    <i class="fa-solid fa-circle-info" style="margin-top:.1rem;flex-shrink:0"></i>
                    Ulasan Anda akan ditinjau oleh tim kami sebelum ditampilkan.
                  </div>

                  <button
                    type="button"
                    class="btn-green w-100"
                    style="justify-content:center"
                    :disabled="submitting"
                    @click="submitReview"
                  >
                    <template v-if="submitting">
                      <i class="fa-solid fa-spinner fa-spin"></i> Mengirim...
                    </template>
                    <template v-else>
                      <i class="fa-solid fa-paper-plane"></i> Kirim Ulasan
                    </template>
                  </button>
                </div>
              </div>

            </div>
          </div>
        </section>
      </div>
    `
  }).mount(mountSelector);
}

window.VueReviewsApp = {
  initList : initListApp,
  initForm : () => {},
};