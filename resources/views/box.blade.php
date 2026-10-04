@extends('layouts.game')

@section('title', $box->name)

@section('content')
  @php
    $avaIndex = min($box->id, 4); // ava1–ava4

    // Nama file ikon bahan aktif yang sudah di-generate & remove-bg.
    // Key dicocokkan dari nama bahan (lowercase), bukan dari icon_key DB.
    $ingredientIcons = [
      // Box 1 — Brightening Zone
      'alpha arbutin' => 'alpha.webp',
      'vitamin c' => 'vitc.webp',
      'niacinamide' => 'niac.webp',
      'kojic acid' => 'kojic.webp',
      'aha' => 'aha.webp',

      // Box 2 — Acne Zone
      'bha (salicylic acid)' => 'bha.webp',
      'benzoyl peroxide' => 'benzo.webp',
      'ceramide' => 'cera.webp',
      'azelaic acid' => 'aze.webp',
      'centella asiatica (cica)' => 'cica.webp',

      // Box 3 — Aging & Antioxidant Zone
      'peptide' => 'Peptide.webp',
      'bakuchiol' => 'Bakuchiol.webp',
      'vitamin e' => 'vite.webp',
      'retinol' => 'Retinol.webp',
      'tranexamic acid' => 'Acid.webp',

      // Box 4 — Renew Zone (Niacinamide, Vitamin C, Retinol, BHA sudah terpetakan di atas)
      'lactic acid' => 'lactic.webp',
    ];

    // ASUMSI: box berikutnya ditentukan lewat kolom `order` di tabel boxes.
    // Sesuaikan kalau logic urutan stage Anda berbeda.
    $nextBox = \App\Models\Box::where('order', $box->order + 1)->first();
  @endphp

  <div id="boxApp" x-data="boxApp()" x-init="init()" class="box-game-wrap"
    style="background-image: url('{{ asset('images/bg.webp') }}');">

    {{-- ── NAVBAR (sama seperti home) ── --}}
    <header class="game-topbar box-topbar">
      <div class="game-logo">
        <a href="{{ route('home') }}">
          <img class="logo-img logo-img-desktop" src="{{ asset('images/logo-desktop.webp') }}" alt="Skincare Mix Lab">
          <img class="logo-img logo-img-mobile" src="{{ asset('images/logo.webp') }}" alt="Skincare Mix Lab">
        </a>
      </div>
      <nav class="game-nav">
        <button class="nav-btn nav-btn-green" id="musicBtn" onclick="toggleMusic()" aria-pressed="false">
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
        <a href="{{ route('encyclopedia') }}" class="nav-btn">
          <span class="nav-icon" aria-hidden="true">
            {{-- Lucide: book-open --}}
            <svg class="icon" viewBox="0 0 24 24">
              <path d="M12 7v14" />
              <path
                d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />
            </svg>
          </span>
          <span class="nav-label">ENCYCLOPEDIA</span>
        </a>
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

    {{-- Label stage: nempel di kiri atas halaman --}}
    <div class="box-stage-label">
      <span class="stage-num">Stage {{ $box->order ?? $box->id }}</span><span class="stage-sep"> · </span><span
        class="stage-name">{{ $box->name }}</span>
    </div>

    {{-- ── MAIN LAYOUT ── --}}
    <main class="box-main">

      {{-- Avatar kiri (40%). Sekaligus DROP ZONE untuk bahan aktif. --}}
      <aside class="box-avatar-col" x-ref="avatarZone"
        :class="{ 'drop-ready': drag.active, 'drop-over': drag.active && drag.over }">

        {{-- Shadow box (2 slot) tempat bahan aktif diletakkan.
        Klik slot yang terisi untuk mengembalikan bahan ke notes. --}}
        <div class="avatar-slots" x-show="!mixed || drag.active" x-cloak>
          <template x-for="i in [0, 1]" :key="i">
            <button type="button" class="avatar-slot"
              :class="{ 'filled': placed[i], 'next': drag.active && placed.length === i }" :disabled="!placed[i]"
              :aria-label="placed[i] ? 'Kembalikan ' + placed[i].name : 'Slot bahan ' + (i + 1)" @click="removeSlot(i)">
              <template x-if="placed[i]">
                <span class="slot-item">
                  <img x-show="placed[i].src" :src="placed[i].src" alt="" draggable="false">
                  <span class="slot-name" x-text="placed[i].name"></span>
                </span>
              </template>
              <span class="slot-empty" x-show="!placed[i]" x-text="'Bahan ' + (i + 1)"></span>
            </button>
          </template>
        </div>

        <div class="box-avatar-anchor">
          <img class="box-avatar-img" draggable="false" :class="{ 'avatar-swap': swapping, 'avatar-shake': shaking }"
            :src="sad ? '{{ asset('images/ava-sad.webp') }}'
                    : (after ? '{{ asset('images/ava-after.webp') }}' : '{{ asset('images/ava' . $avaIndex . '.webp') }}')"
            alt="{{ $box->name }} avatar">

          {{-- Efek bintang saat avatar berganti kondisi --}}
          <div class="avatar-fx" aria-hidden="true">
            <template x-for="s in sparkles" :key="s.id">
              <span class="sparkle"
                :style="`left:${s.x}%; top:${s.y}%; font-size:${s.size}px; color:${s.color}; --d:${s.delay}s; --dur:${s.dur}s`"
                x-text="s.ch"></span>
            </template>
          </div>
        </div>

        {{-- Text box kondisi awal, di depan avatar (area dada) --}}
        <div class="box-condition-box">
          <span class="box-condition-label"
            x-text="sad ? 'Kulit iritasi' : (after ? 'Kondisi akhir' : 'Kondisi awal')"></span>
          <p class="box-condition-desc" x-text="sad
                ? 'Kombinasi ini berisiko mengiritasi kulit. Pisahkan pemakaiannya di waktu berbeda.'
                : (after
                    ? '{{ addslashes($box->condition_label_after ?? 'Kondisi membaik setelah perawatan.') }}'
                    : '{{ addslashes($box->condition_label ?? 'Kondisi kulit belum ditentukan.') }}')">
          </p>
        </div>
      </aside>

      {{-- Konten kanan: notes panel dengan bahan aktif --}}
      <section class="box-content-col">

        <div class="notes-panel">
          <img class="notes-bg-img notes-bg-desktop" src="{{ asset('images/notes.webp') }}" alt="Notes" aria-hidden="true"
            draggable="false">
          <img class="notes-bg-img notes-bg-mobile" src="{{ asset('images/notes-mobile.webp') }}" alt=""
            aria-hidden="true" draggable="false" onload="this.closest('.notes-panel').classList.add('has-mobile-notes')"
            onerror="this.remove()">

          <div class="notes-content">
            <div class="notes-title-row">
              <h2 class="ingr-panel-title">{{ $box->name }}</h2>
            </div>

            {{-- Hint text --}}
            <p class="ingr-hint-text notes-hint" x-text="hintText || 'Seret bahan ke avatar untuk meletakkannya...'"></p>

            {{-- Ingredient Grid --}}
            <div class="ingredient-grid box-ingr-grid">
              @foreach($box->ingredients as $i => $ingr)
                @php
                  $iconFile = $ingredientIcons[strtolower($ingr->name)] ?? null;
                  $iconUrl = $iconFile ? asset('images/ingredients/' . $iconFile) : '';
                  $ingrName = addslashes($ingr->name);
                  $ingrHint = addslashes($ingr->hint_text ?? '');
                @endphp
                {{-- .placed = bahan sudah ada di slot avatar → kartu disembunyikan --}}
                <div class="ingr-card float-anim" style="animation-delay: {{ $i * 0.1 }}s" :class="{
                        'placed': sel.includes({{ $ingr->id }}),
                        'dragging': drag.active && drag.id === {{ $ingr->id }},
                        'used': used.includes({{ $ingr->id }})
                      }"
                  @pointerdown="dragStart($event, {{ $ingr->id }}, '{{ $ingrName }}', '{{ $ingrHint }}', '{{ $iconUrl }}')"
                  @dragstart.prevent>
                  @if($iconFile)
                    <img class="ingr-icon-img" src="{{ $iconUrl }}" alt="{{ $ingr->name }}" draggable="false">
                  @else
                    <span class="ingr-icon">{{ $ingr->icon_key }}</span>
                  @endif
                  <div class="ingr-name">{{ $ingr->name }}</div>
                </div>
              @endforeach
            </div>

            {{-- Tombol MIX & Reset --}}
            <div class="notes-mix-row">
              <button type="button" class="btn-mix-notes" :disabled="sel.length !== 2" @click="doMix()">
                ✨ MIX! ✨
              </button>
              <button type="button" class="btn-reset-notes" :disabled="sel.length === 0" @click="doReset()">
                ↩ Reset
              </button>
            </div>
          </div>
        </div>

        <p class="disclaimer">
          Seret 2 bahan ke avatar, lalu tekan MIX!<br>
          ⚠️ Uji toleransi sebelum pemakaian. Gunakan sunscreen SPF 30+ setiap hari. Bukan saran medis.
        </p>
      </section>
    </main>

    {{-- Ghost bahan yang mengikuti kursor/jari saat di-drag --}}
    <div class="drag-ghost" x-show="drag.active" x-cloak :style="`left:${drag.x}px; top:${drag.y}px`">
      <img x-show="drag.src" :src="drag.src" alt="" draggable="false">
      <span x-text="drag.name"></span>
    </div>

    {{-- Popup hasil --}}
    <div class="popup-overlay" :class="{ show: popup.visible }" @click.self="closePopup()">
      <div class="popup-box">
        <button type="button" class="popup-close" @click="closePopup()" aria-label="Tutup">✕</button>
        <div class="popup-status" :class="{
              'status-good': popup.status === 'hijau',
              'status-caution': popup.status === 'kuning',
              'status-danger': popup.status === 'merah'
            }" x-text="popup.status === 'hijau' ? '✅ Pasangan Ideal'
                  : (popup.status === 'merah' ? '⛔ Hindari Bersamaan' : '⚠️ Aman dengan Penyesuaian')"></div>
        <h3 class="popup-title" x-text="popup.title"></h3>
        <p class="popup-desc" x-text="popup.description"></p>
        <p class="popup-note">Toleransi kulit berbeda-beda. Lakukan patch test. Bukan saran medis.</p>
        <div class="popup-buttons">
          <button class="btn btn-secondary" @click="closePopup()">🔄 Coba Lain</button>
          <button type="button" class="btn btn-primary" @click="closePopup()">🪞 Lihat Wajah</button>
        </div>
      </div>
    </div>

  </div>
@endsection

@section('scripts')
  <style>
    /* Ikon musik: ditentukan class .is-music-on pada tombol */
    #musicBtn .icon[data-music-icon="on"] {
      display: none !important;
    }

    #musicBtn .icon[data-music-icon="off"] {
      display: inline-block !important;
    }

    #musicBtn.is-music-on .icon[data-music-icon="on"] {
      display: inline-block !important;
    }

    #musicBtn.is-music-on .icon[data-music-icon="off"] {
      display: none !important;
    }
  </style>
  <script>
    const BOX_ID = {{ $box->id }};
    const BOX_FACE_TYPE = '{{ $box->face_type }}';
    const CHECK_URL = '{{ route('box.pair.check', $box->id) }}';
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;

    // ── Sinkronisasi ikon musik (ON/OFF) ──
    (function () {
      function setup() {
        const btn = document.getElementById('musicBtn');
        const label = document.getElementById('musicLabel');
        if (!btn || !label) return;

        // Status dibaca dari label tombol ("MUSIC ON" / "MUSIC OFF")
        function isMusicOn() {
          const txt = (label.textContent || '').trim().toUpperCase();
          if (txt.endsWith('OFF')) return false;
          if (txt.endsWith('ON')) return true;
          return btn.getAttribute('aria-pressed') === 'true';
        }

        function sync() {
          btn.classList.toggle('is-music-on', isMusicOn());
        }

        new MutationObserver(sync).observe(btn, {
          subtree: true, childList: true, characterData: true,
          attributes: true, attributeFilter: ['aria-pressed'],
        });
        window.addEventListener('music-change', sync);
        btn.addEventListener('click', () => { setTimeout(sync, 100); setTimeout(sync, 400); });
        window.addEventListener('load', () => setTimeout(sync, 100));
        sync();
      }

      if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup);
      else setup();
    })();

    function boxApp() {
      return {
        sel: [],      // id bahan yang sudah ada di slot (dikirim ke server saat MIX)
        placed: [],   // detail bahan di slot: [{ id, name, src }]
        used: [],
        hintText: '',
        mixed: false,
        after: false,      // avatar sedang tampil kondisi "after"
        sad: false,        // avatar sedih (pasangan "merah")
        shaking: false,    // animasi goyang saat sedih
        swapping: false,   // flash singkat saat avatar berganti
        sparkles: [],      // partikel bintang
        popup: { visible: false, status: '', title: '', description: '' },

        // ── drag & drop state ──
        drag: { active: false, over: false, id: null, src: '', name: '', x: 0, y: 0 },
        _pending: null,

        init() { new Image().src = '{{ asset('images/ava-sad.webp') }}'; },

        // ── Transisi avatar + hujan bintang ──
        transitionAvatar(toAfter, sad = false) {
          this.swapping = !sad;
          this.shaking = sad;
          if (!sad) this.sparkle();          // bintang hanya untuk hasil baik
          setTimeout(() => { this.sad = sad; this.after = toAfter && !sad; }, 220);
          setTimeout(() => { this.swapping = false; this.shaking = false; }, 1000);
        },

        sparkle() {
          const colors = ['#ffd54f', '#ffffff', '#ff8fc4', '#b3e5fc', '#e1bee7'];
          const chars = ['✦', '✧', '★', '✨'];
          const base = Date.now();
          this.sparkles = Array.from({ length: 24 }, (_, i) => ({
            id: base + i,
            x: 4 + Math.random() * 92,
            y: 4 + Math.random() * 88,
            size: 12 + Math.random() * 24,
            delay: Math.random() * 0.5,
            dur: 0.9 + Math.random() * 0.7,
            color: colors[Math.floor(Math.random() * colors.length)],
            ch: chars[Math.floor(Math.random() * chars.length)],
          }));
          clearTimeout(this._sparkleTimer);
          this._sparkleTimer = setTimeout(() => { this.sparkles = []; }, 2000);
        },

        // ── Pointer-based drag (mouse + touch) ──
        dragStart(e, id, name, hint, src) {
          if (e.pointerType === 'mouse' && e.button !== 0) return;
          if (this._pending) return;
          if (this.sel.includes(id)) return; // sudah di slot

          this._pending = { id, name, hint, src, sx: e.clientX, sy: e.clientY, moved: false };

          const move = (ev) => this.dragMove(ev);
          const up = (ev) => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', up);
            window.removeEventListener('pointercancel', up);
            this.dragEnd(ev);
          };
          window.addEventListener('pointermove', move);
          window.addEventListener('pointerup', up);
          window.addEventListener('pointercancel', up);
        },

        dragMove(ev) {
          const p = this._pending;
          if (!p) return;
          // Ambang 8px: di bawah itu dianggap klik biasa
          if (!p.moved && Math.hypot(ev.clientX - p.sx, ev.clientY - p.sy) < 8) return;
          p.moved = true;
          this.drag = {
            active: true,
            id: p.id,
            src: p.src,
            name: p.name,
            x: ev.clientX,
            y: ev.clientY,
            over: this.isOverAvatar(ev.clientX, ev.clientY),
          };
        },

        dragEnd(ev) {
          const p = this._pending;
          this._pending = null;
          if (!p) return;

          if (!p.moved) {
            // Hanya klik → tampilkan petunjuk bahan saja (tidak meletakkan)
            if (!this.mixed) this.hintText = p.hint || '';
          } else if (ev.type !== 'pointercancel' && this.isOverAvatar(ev.clientX, ev.clientY)) {
            // Di-drop di area avatar → masuk slot, kartu di notes hilang
            this.pick(p.id, p.name, p.hint, p.src);
            // Opsional: auto-MIX begitu bahan kedua masuk
            // if (this.sel.length === 2) this.doMix();
          }
          this.drag = { active: false, over: false, id: null, src: '', name: '', x: 0, y: 0 };
        },

        isOverAvatar(x, y) {
          const r = this.$refs.avatarZone.getBoundingClientRect();
          return x >= r.left && x <= r.right && y >= r.top && y <= r.bottom;
        },

        // ── Logic game ──
        pick(id, name, hint, src) {
          if (this.used.includes(id)) return;

          // Trigger revert: kalau avatar sedang kondisi "after", bahan baru
          // mengembalikan avatar ke kondisi awal.
          if (this.mixed) {
            this.mixed = false;
            this.sel = [];
            this.placed = [];
            this.sad = false;
            this.used = [];
            this.after = false; // kembali ke kondisi awal tanpa animasi
          }

          if (this.sel.includes(id) || this.sel.length >= 2) return;

          this.hintText = hint || '';
          this.sel.push(id);
          this.placed.push({ id, name, src: src || '' });
        },

        // Kembalikan bahan dari slot ke notes
        removeSlot(i) {
          if (this.mixed || !this.placed[i]) return;
          const id = this.placed[i].id;
          this.placed.splice(i, 1);
          this.sel = this.sel.filter(x => x !== id);
          this.hintText = '';
        },

        doMix() {
          if (this.sel.length !== 2 || this.mixed) return;
          this.checkPair();
        },

        doReset() {
          this.sel = [];
          this.placed = [];
          this.hintText = '';
        },

        async checkPair() {
          try {
            const res = await fetch(CHECK_URL, {
              method: 'POST',
              headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
              body: JSON.stringify({ ingredient_a_id: this.sel[0], ingredient_b_id: this.sel[1] }),
            });
            const data = await res.json();
            if (res.ok) {
              // Avatar langsung berubah (dengan bintang) begitu MIX ditekan,
              // popup hasil menyusul setelah animasi sempat terlihat.
              this.popup = { ...data, visible: false };
              this.mixed = true;
              this.transitionAvatar(true, data.status === 'merah');
              setTimeout(() => {
                if (this.mixed) this.popup.visible = true;
              }, 1100);
            } else {
              this.doReset();
            }
          } catch (e) {
            this.doReset();
          }
        },

        closePopup() {
          this.popup.visible = false;
          // Bahan kembali ke notes; avatar tetap di kondisi "after"
          // sampai pemain memilih bahan baru.
          this.doReset();
        },
      };
    }
  </script>
@endsection