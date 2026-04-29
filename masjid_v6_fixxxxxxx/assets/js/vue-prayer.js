const { createApp, ref, computed, onMounted, onUnmounted} = Vue;

const PRAYER_API    = 'https://api.aladhan.com/v1/timingsByCity';
const PRAYER_PARAMS = 'city=Samarinda&country=Indonesia&method=20';

const PRAYER_ORDER = [
  { key: 'Subuh',   arabic: 'الفجر',  apiKey: 'Fajr'    },
  { key: 'Syuruq',  arabic: 'الشروق', apiKey: 'Sunrise' },
  { key: 'Dzuhur',  arabic: 'الظهر',  apiKey: 'Dhuhr'   },
  { key: 'Ashar',   arabic: 'العصر',  apiKey: 'Asr'     },
  { key: 'Maghrib', arabic: 'المغرب', apiKey: 'Maghrib' },
  { key: 'Isya',    arabic: 'العشاء', apiKey: 'Isha'    },
];

const FALLBACK = [
  { key:'Subuh',   arabic:'الفجر',  h:4,  m:53 },
  { key:'Syuruq',  arabic:'الشروق', h:6,  m:7  },
  { key:'Dzuhur',  arabic:'الظهر',  h:12, m:8  },
  { key:'Ashar',   arabic:'العصر',  h:15, m:23 },
  { key:'Maghrib', arabic:'المغرب', h:18, m:21 },
  { key:'Isya',    arabic:'العشاء', h:19, m:31 },
];

const DAYS   = ['Ahad','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const MONTHS = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

function pad(n) { return String(n).padStart(2, '0'); }

function fmtCountdown(sec) {
  const s = Math.max(0, sec);
  const h = Math.floor(s / 3600);
  const m = Math.floor((s % 3600) / 60);
  const ss = s % 60;
  if (h > 0) return `${h} jam ${pad(m)} mnt`;
  if (m > 0) return `${m} mnt ${pad(ss)} dtk`;
  return `${ss} dtk`;
}

window.VuePrayerApp = function (mountSelector) {
  const app = createApp({
    setup() {
      const schedule     = ref([]);
      const loaded       = ref(false);
      const apiNote      = ref('loading'); // 'loading' | 'ok' | 'offline'
      const liveClock    = ref('00:00:00');
      const liveDate     = ref('Memuat...');
      const activeIdx    = ref(-1);
      const nextIdx      = ref(0);
      const countdownVal = ref('--:--');
      const countdownLbl = ref('Menuju Waktu Sholat');
      const countdownPrayer = ref('--');

      let timer = null;
      async function fetchSchedule() {
        try {
          const today = new Date();
          const url   = `${PRAYER_API}?${PRAYER_PARAMS}&date=${today.getDate()}-${today.getMonth()+1}-${today.getFullYear()}`;
          const res   = await fetch(url, { signal: AbortSignal.timeout(6000) });
          if (!res.ok) throw new Error('HTTP ' + res.status);
          const json = await res.json();
          const t    = json.data.timings;
          schedule.value = PRAYER_ORDER.map(p => {
            const raw  = t[p.apiKey] || '';
            const [h, m] = raw.split(':').map(Number);
            return { key: p.key, arabic: p.arabic, h: h || 0, m: m || 0 };
          });
          loaded.value  = true;
          apiNote.value = 'ok';
        } catch (err) {
          console.warn('Prayer API gagal, pakai fallback:', err.message);
          schedule.value = FALLBACK.map(p => ({ ...p }));
          loaded.value   = true;
          apiNote.value  = 'offline';
        }
      }
      function computeActive() {
        if (!schedule.value.length) return;
        const now  = new Date();
        const mins = now.getHours() * 60 + now.getMinutes();
        const pMins = schedule.value.map(p => p.h * 60 + p.m);
        let ai = -1;
        for (let i = pMins.length - 1; i >= 0; i--) {
          if (mins >= pMins[i]) { ai = i; break; }
        }
        const ni = (ai + 1) % pMins.length;
        let diffMin = pMins[ni] - mins;
        if (diffMin < 0) diffMin += 24 * 60;
        const diffSec = diffMin * 60 - now.getSeconds();

        activeIdx.value = ai;
        nextIdx.value   = ni;

        const next = schedule.value[ni];
        if (next) {
          countdownVal.value    = fmtCountdown(diffSec);
          countdownLbl.value    = 'Menuju ' + next.key;
          countdownPrayer.value = `${next.key} - ${pad(next.h)}:${pad(next.m)}`;
        }
      }
      function tick() {
        const now = new Date();
        liveClock.value = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
        liveDate.value  = `${DAYS[now.getDay()]}, ${now.getDate()} ${MONTHS[now.getMonth()]} ${now.getFullYear()}`;
        computeActive();
      }
      function cardClass(idx) {
        return {
          'prayer-card': true,
          'is-active'  : idx === activeIdx.value,
          'is-next'    : idx === nextIdx.value && idx !== activeIdx.value,
        };
      }

      function timeStr(p) {
        return p ? `${pad(p.h)}:${pad(p.m)}` : '--:--';
      }
      onMounted(async () => {
        await fetchSchedule();
        tick();
        timer = setInterval(tick, 1000);
      });

      onUnmounted(() => {
        if (timer) clearInterval(timer);
      });

      return {
        schedule, loaded, apiNote,
        liveClock, liveDate,
        activeIdx, nextIdx,
        countdownVal, countdownLbl, countdownPrayer,
        cardClass, timeStr,
      };
    },

    template: `
      <section class="prayer-section" style="padding:4rem 0">
        <div class="prayer-top-bar">
          <div class="container">
            <div class="row align-items-center g-3">
              <div class="col-md-6 text-center text-md-start">
                <div class="clock-display">{{ liveClock }}</div>
                <div class="clock-date">{{ liveDate }}</div>
              </div>
              <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                <div class="countdown-box">
                  <span class="countdown-label">{{ countdownLbl }}</span>
                  <span class="countdown-value">{{ countdownVal }}</span>
                  <span class="countdown-prayer">{{ countdownPrayer }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="container mt-4" style="position:relative;z-index:1">
          <div class="text-center mb-4 fade-up">
            <span class="section-badge section-badge-gold">
              <i class="fa-solid fa-moon"></i> Jadwal Sholat
            </span>
            <h2 class="section-title mt-2" style="color:var(--white)">
              Waktu Sholat <span>Hari Ini</span>
            </h2>
            <p style="color:rgba(255,255,255,.5);font-size:.83rem;margin-top:.4rem">
              <template v-if="apiNote==='loading'">
                <i class="fa-solid fa-spinner fa-spin"></i> Mengambil jadwal dari Aladhan API...
              </template>
              <template v-else-if="apiNote==='ok'">
                <i class="fa-solid fa-circle-check" style="color:var(--gold-300)"></i>
                Jadwal diambil dari Aladhan API - Samarinda, Kalimantan Timur
              </template>
              <template v-else>
                <i class="fa-solid fa-triangle-exclamation" style="color:rgba(255,255,255,.4)"></i>
                Menggunakan jadwal offline - Samarinda, Kalimantan Timur
              </template>
            </p>
          </div>

          <div class="row g-3">
            <div
              v-for="(p, idx) in schedule"
              :key="p.key"
              class="col-6 col-md-4 col-lg-2"
            >
              <div :class="cardClass(idx)">
                <span class="prayer-arabic">{{ p.arabic }}</span>
                <span class="prayer-name-id">{{ p.key }}</span>
                <span class="prayer-time">{{ timeStr(p) }}</span>
                <span v-if="idx === activeIdx" class="prayer-badge">Sedang</span>
                <span
                  v-else-if="idx === nextIdx"
                  class="prayer-badge"
                  style="background:transparent;border:1px solid var(--gold-500);color:var(--gold-300)"
                >Berikutnya</span>
              </div>
            </div>
          </div>
        </div>
      </section>
    `,
  });

  app.mount(mountSelector);
  return app;
};
