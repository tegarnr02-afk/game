  @extends('layouts.game')

  @section('title', 'Ensiklopedia Bahan Aktif')

  @section('content')
    <div id="encyApp" x-data="encyApp()" @keydown.escape.window="close()" class="ency-wrap"
      :class="{ 'is-locked': current }"
      style="background-image: url('{{ asset('images/bg.webp') }}');">

      {{-- ── NAVBAR ── --}}
      <header class="game-topbar ency-topbar">
        <div class="game-logo">
          <a href="{{ route('home') }}">
            <img class="logo-img logo-img-desktop" src="{{ asset('images/logo-desktop.webp') }}" alt="Skincare Mix Lab">
            <img class="logo-img logo-img-mobile" src="{{ asset('images/logo.webp') }}" alt="Skincare Mix Lab">
          </a>
        </div>
      <nav class="game-nav">
    {{-- Musik: ikon berganti lewat atribut data-music-icon (disinkronkan oleh script di bawah) --}}
    <button class="nav-btn nav-btn-green" id="musicBtn" type="button" onclick="toggleMusic()" aria-pressed="false">
      <span class="nav-icon" aria-hidden="true">
        {{-- Lucide: volume-x (musik OFF) --}}
        <svg class="icon" data-music-icon="off" viewBox="0 0 24 24">
          <path
            d="M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z" />
          <line x1="22" x2="16" y1="9" y2="15" />
          <line x1="16" x2="22" y1="9" y2="15" />
        </svg>
        {{-- Lucide: volume-2 (musik ON) --}}
        <svg class="icon" data-music-icon="on" viewBox="0 0 24 24" hidden>
          <path
            d="M11 4.702a.705.705 0 0 0-1.203-.498L6.413 7.587A1.4 1.4 0 0 1 5.416 8H3a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h2.416a1.4 1.4 0 0 1 .997.413l3.383 3.384A.705.705 0 0 0 11 19.298z" />
          <path d="M16 9a5 5 0 0 1 0 6" />
          <path d="M19.364 18.364a9 9 0 0 0 0-12.728" />
        </svg>
      </span>
      <span class="nav-label" id="musicLabel">MUSIC OFF</span>
    </button>

    <a href="{{ route('home') }}" class="nav-btn">
      <span class="nav-icon" aria-hidden="true">
        {{-- Lucide: house --}}
        <svg class="icon" viewBox="0 0 24 24">
          <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
          <path
            d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
        </svg>
      </span>
      <span class="nav-label">BERANDA</span>
    </a>
  </nav>
      </header>

      {{-- ── ISI ── --}}
      <main class="ency-main">
        <section class="ency-panel">

          <div class="ency-head">
            <h1 class="ency-title">Ensiklopedia Bahan Aktif</h1>
            <p class="ency-sub">Kenali <span x-text="items.length"></span> bahan aktif yang ada di game, dari fungsi sampai cara pakainya.</p>
          </div>

          {{-- Pencarian --}}
          <label class="ency-search">
            <span class="ency-search-icon" aria-hidden="true">&#128269;</span>
            <input type="search" x-model="q" placeholder="Cari bahan, misalnya: retinol, jerawat, pencerah..."
              aria-label="Cari bahan aktif" autocomplete="off">
          </label>

          {{-- Filter kategori --}}
          <div class="ency-chips" role="tablist" aria-label="Filter kategori">
            <template x-for="c in cats" :key="c">
              <button type="button" class="ency-chip" role="tab"
                :class="{ 'is-active': cat === c }" :aria-selected="cat === c"
                @click="cat = c" x-text="c"></button>
            </template>
          </div>

          <p class="ency-count" x-show="filtered.length" x-cloak>
            Menampilkan <strong x-text="filtered.length"></strong> bahan
          </p>

          {{-- Grid kartu --}}
          <div class="ency-grid">
          <template x-for="i in paged" :key="i.slug">
              <button type="button" class="ency-card" :class="catClass(i.categories[0])" @click="open(i)">
                <span class="ency-card-icon">
                  <img x-show="i.icon_url" :src="i.icon_url" :alt="i.name" draggable="false">
                  <span x-show="!i.icon_url" x-text="i.name.charAt(0)"></span>
                </span>
                <span class="ency-card-name" x-text="i.name"></span>
                <span class="ency-card-tag" x-text="i.tagline"></span>
                <span class="ency-card-cats">
                  <template x-for="c in i.categories" :key="c">
                    <span class="ency-mini" x-text="c"></span>
                  </template>
                </span>
              </button>
            </template>
          </div>

                  {{-- Pager (mobile) --}}
          <div class="ency-pager" x-show="isMobile && totalPages > 1" x-cloak>
            <button type="button" class="ency-pager-btn" @click="prevPage()" :disabled="page === 0">&#8592; Prev</button>
            <span class="ency-pager-info"><span x-text="page + 1"></span> / <span x-text="totalPages"></span></span>
            <button type="button" class="ency-pager-btn" @click="nextPage()" :disabled="page >= totalPages - 1">Next &#8594;</button>
          </div>

          <div class="ency-empty" x-show="!filtered.length" x-cloak>
            <p>Bahan tidak ditemukan.</p>
            <button type="button" class="ency-reset" @click="q = ''; cat = 'Semua'">Reset pencarian</button>
          </div>

          <p class="ency-disclaimer">
            Informasi bersifat edukasi umum, bukan saran medis. Lakukan patch test, pakai sunscreen SPF 30+ setiap hari,
            dan konsultasikan ke dokter kulit bila kulitmu bermasalah, sedang hamil, atau menyusui.
          </p>
        </section>
      </main>

      {{-- ── DETAIL (modal) ── --}}
      <div class="ency-modal" x-show="current" x-cloak x-transition.opacity
        @click.self="close()" role="dialog" aria-modal="true" :aria-label="current ? current.name : ''">
        <template x-if="current">
          <article class="ency-sheet" :class="catClass(current.categories[0])">
            <button type="button" class="ency-close" @click="close()" aria-label="Tutup">&#10005;</button>

            <div class="ency-sheet-body" x-ref="modalBody">

              <header class="ency-detail-head">
                <span class="ency-detail-icon">
                  <img x-show="current.icon_url" :src="current.icon_url" :alt="current.name" draggable="false">
                  <span x-show="!current.icon_url" x-text="current.name.charAt(0)"></span>
                </span>
                <div class="ency-detail-titles">
                  <h2 x-text="current.name"></h2>
                  <p class="ency-aka" x-text="current.aka"></p>
                  <div class="ency-detail-tags">
                    <template x-for="c in current.categories" :key="c">
                      <span class="ency-mini" x-text="c"></span>
                    </template>
                  </div>
                </div>
              </header>

              <p class="ency-lead" x-text="current.tagline"></p>

              <section class="ency-block">
                <h3>Apa itu?</h3>
                <p x-text="current.about"></p>
              </section>

              <section class="ency-block">
                <h3>Manfaat</h3>
                <ul class="ency-list">
                  <template x-for="b in current.benefits" :key="b"><li x-text="b"></li></template>
                </ul>
              </section>

              <section class="ency-block">
                <h3>Cara pakai</h3>
                <div class="ency-usage">
                  <div><span>Waktu</span><strong x-text="current.usage.waktu"></strong></div>
                  <div><span>Frekuensi</span><strong x-text="current.usage.frekuensi"></strong></div>
                  <div><span>Kadar umum</span><strong x-text="current.usage.kadar"></strong></div>
                </div>
                <p class="ency-skin"><b>Cocok untuk:</b> <span x-text="current.skin"></span></p>
              </section>

              <section class="ency-block" x-show="current.pairs.length">
                <h3>Cocok dipadukan dengan</h3>
                <div class="ency-pill-row">
                  <template x-for="p in current.pairs" :key="p"><span class="ency-pill is-good" x-text="p"></span></template>
                </div>
              </section>

              <section class="ency-block" x-show="current.avoid.length">
                <h3>Hati-hati dipadukan dengan</h3>
                <div class="ency-pill-row">
                  <template x-for="a in current.avoid" :key="a"><span class="ency-pill is-warn" x-text="a"></span></template>
                </div>
              </section>

              <section class="ency-block ency-caution">
                <h3>Perhatian</h3>
                <ul class="ency-list">
                  <template x-for="c in current.cautions" :key="c"><li x-text="c"></li></template>
                </ul>
              </section>

              <p class="ency-zones" x-show="current.zones.length">
                Ditemukan di:
                <template x-for="z in current.zones" :key="z"><span class="ency-zone" x-text="z"></span></template>
              </p>
            </div>

            <footer class="ency-sheet-nav">
              <button type="button" @click="step(-1)">&#8592; Sebelumnya</button>
              <button type="button" @click="step(1)">Berikutnya &#8594;</button>
            </footer>
          </article>
        </template>
      </div>

    </div>
  @endsection

  @section('scripts')
    <script>
      // ── Sinkronisasi ikon musik (ON/OFF) di navbar ──
  (function () {
    function setup() {
      const btn = document.getElementById('musicBtn');
      const label = document.getElementById('musicLabel');
      if (!btn || !label) return;

      function isMusicOn() {
        const txt = (label.textContent || '').trim().toUpperCase();
        if (txt.endsWith('OFF')) return false;
        if (txt.endsWith('ON')) return true;
        return btn.getAttribute('aria-pressed') === 'true';
      }

      let last = null;
      function sync() {
        const on = isMusicOn();
        if (on === last) return;
        last = on;
        btn.classList.toggle('is-music-on', on);
        btn.querySelectorAll('[data-music-icon]').forEach((el) => {
          const show = el.dataset.musicIcon === (on ? 'on' : 'off');
          el.hidden = !show;
          el.style.setProperty('display', show ? 'inline-block' : 'none', 'important');
        });
      }

      new MutationObserver(sync).observe(btn, {
        subtree: true, childList: true, characterData: true,
        attributes: true, attributeFilter: ['aria-pressed'],
      });
      window.addEventListener('music-change', sync);
      btn.addEventListener('click', () => { setTimeout(sync, 100); setTimeout(sync, 400); });
      setInterval(sync, 300);
      sync();
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup);
    else setup();
  })();
      window.ENCY_DATA = @json($items);

      function encyApp() {
        return {
          items: window.ENCY_DATA || [],
          q: '',
          cat: 'Semua',
          current: null,

                  isMobile: false,
          page: 0,
          perPage: 4,

          init() {
            const mq = window.matchMedia('(max-width: 640px)');
            this.isMobile = mq.matches;
            mq.addEventListener('change', e => { this.isMobile = e.matches; this.page = 0; });
            this.$watch('q', () => { this.page = 0; });
            this.$watch('cat', () => { this.page = 0; });
          },

          get totalPages() {
            return this.isMobile ? Math.max(1, Math.ceil(this.filtered.length / this.perPage)) : 1;
          },

          get paged() {
            if (!this.isMobile) return this.filtered;
            const start = this.page * this.perPage;
            return this.filtered.slice(start, start + this.perPage);
          },

          prevPage() { if (this.page > 0) this.page--; },
          nextPage() { if (this.page < this.totalPages - 1) this.page++; },

          get cats() {
            const set = new Set();
            this.items.forEach(i => (i.categories || []).forEach(c => set.add(c)));
            return ['Semua', ...set];
          },

          get filtered() {
            const q = this.q.trim().toLowerCase();
            return this.items.filter(i => {
              const okCat = this.cat === 'Semua' || (i.categories || []).includes(this.cat);
              const hay = [i.name, i.aka, i.tagline, ...(i.categories || [])].join(' ').toLowerCase();
              return okCat && (!q || hay.includes(q));
            });
          },

          open(i) { this.current = i; },
          close() { this.current = null; },

          step(dir) {
            const list = this.filtered;
            if (!this.current || !list.length) return;
            let idx = list.findIndex(x => x.slug === this.current.slug);
            idx = (idx + dir + list.length) % list.length;
            this.current = list[idx];
            this.$nextTick(() => { if (this.$refs.modalBody) this.$refs.modalBody.scrollTop = 0; });
          },

          catClass(c) {
            return 'ency-cat-' + String(c || '').toLowerCase().replace(/[^a-z]+/g, '-').replace(/^-|-$/g, '');
          },
        };
      }
    </script>
  @endsection