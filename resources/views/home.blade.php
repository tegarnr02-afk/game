@extends('layouts.game')

@section('title', 'Skin Salon – Home')

@section('content')
  <div class="home-game" x-data="homeApp()" style="
      --home-bg: url('{{ asset('images/home.webp') }}');
      --home-bg-mobile: url('{{ asset('images/home-mobile.webp') }}');
      background-image: var(--home-bg);
      background-size: 100% 100%;
      background-position: center;
    ">
    {{-- ── HEADER ─────────────────────────────────────────────── --}}
    <header class="game-topbar">
      <div class="game-logo">
        <img class="logo-img logo-img-desktop" src="{{ asset('images/logo-desktop.webp') }}" alt="Skincare Mix Lab">
        <img class="logo-img logo-img-mobile" src="{{ asset('images/logo.webp') }}" alt="Skincare Mix Lab">
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

        <button class="nav-btn" type="button" @click="openSettings()">
          <span class="nav-icon" aria-hidden="true">
            {{-- Lucide: settings --}}
            <svg class="icon" viewBox="0 0 24 24">
              <path
                d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
              <circle cx="12" cy="12" r="3" />
            </svg>
          </span>
          <span class="nav-label">SETTINGS</span>
        </button>
      </nav>
    </header>

    {{-- ── MAIN CONTENT ──────────────────────────────────────── --}}
    <main class="game-main home-main">
      <section class="hero-left home-copy" aria-label="Welcome">
        <div class="hero-tagline">
          <p>Healthy Skin<br>Happy You</p>
        </div>
        <div class="hero-text">
          <img class="hero-heading-art" src="{{ asset('images/teks.webp') }}" alt="Mix, Match &amp; Glow!">
          <img class="hero-sub-art" src="{{ asset('images/teks2.webp') }}"
            alt="Learn skincare ingredients through fun challenges">
        </div>
      </section>

      <section class="stage-grid" id="stageGrid" aria-label="Choose a stage">
        @foreach($boxes as $i => $box)
          @php
            $color = ['pink', 'blue', 'blue', 'pink'][$i] ?? 'pink';
            $effectiveLocked = $box->is_locked && !$isPro;
            $stageNum = $i + 1;
            $imgSuffix = (!$effectiveLocked && in_array($stageNum, [3, 4])) ? '-un' : '';
            $stageImg = 'images/stage' . $stageNum . $imgSuffix . '.webp';
          @endphp
          <article class="stage-card stage-image-card stage-image-{{ $stageNum }} {{ $effectiveLocked ? 'is-locked' : '' }}"
            @if($effectiveLocked) @click="openUpgrade('Stage {{ $stageNum }}')" role="button" tabindex="0"
            @keydown.enter="openUpgrade('Stage {{ $stageNum }}')" @else @click="goToBox({{ $box->id }})" role="link"
            tabindex="0" @keydown.enter="goToBox({{ $box->id }})" @endif>
            <img class="stage-art" src="{{ asset($stageImg) }}" alt="Stage {{ $stageNum }}">
          </article>
        @endforeach
      </section>
    </main>

    {{-- ── UPGRADE MODAL ─────────────────────────────────────── --}}
    <div class="upgrade-modal" x-cloak x-show="upgrade.visible" x-transition.opacity
      @keydown.escape.window="closeUpgrade()" @click.self="closeUpgrade()">
      <section class="upgrade-modal-card" role="dialog" aria-modal="true" aria-labelledby="upgradeTitle">
        <button class="upgrade-close" type="button" aria-label="Tutup" @click="closeUpgrade()">&times;</button>
        <div class="upgrade-icon" aria-hidden="true">
          {{-- Lucide: crown --}}
          <svg class="icon" viewBox="0 0 24 24">
            <path
              d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z" />
            <path d="M5 21h14" />
          </svg>
        </div>
        <p class="upgrade-kicker">PRO MEMBER</p>
        <h2 id="upgradeTitle" x-text="upgrade.stage + ' terkunci'">Stage terkunci</h2>
        <p>Buka stage premium, tantangan eksklusif, dan perjalanan skincare yang lebih lengkap.</p>
        <div class="upgrade-modal-actions">
          <button class="upgrade-primary" type="button" @click="goToUpgrade()">
            {{-- Lucide: sparkles --}}
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z" />
              <path d="M20 3v4" />
              <path d="M22 5h-4" />
              <path d="M4 17v2" />
              <path d="M5 18H3" />
            </svg>
            UPGRADE KE PRO
          </button>
          <button class="upgrade-secondary" type="button" @click="closeUpgrade()">NANTI SAJA</button>
        </div>
      </section>
    </div>

    {{-- ── SETTINGS MODAL ───────────────────────────────────── --}}
    <div class="upgrade-modal" x-cloak x-show="settings.visible" x-transition.opacity
      @keydown.escape.window="closeSettings()" @click.self="closeSettings()"
      @music-change.window="settings.musicOn = $event.detail.on">
      <section class="settings-card" role="dialog" aria-modal="true" aria-labelledby="settingsTitle">
        <button class="upgrade-close" type="button" aria-label="Tutup" @click="closeSettings()">&times;</button>
        <h2 class="settings-title" id="settingsTitle">
          {{-- Lucide: settings --}}
          <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
            <path
              d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z" />
            <circle cx="12" cy="12" r="3" />
          </svg>
          Pengaturan
        </h2>

        <div class="settings-profile">
          <div class="settings-avatar" aria-hidden="true">
            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
          </div>
          <div class="settings-profile-info">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ auth()->user()->email }}</span>
          </div>
          <div class="settings-badges">
            @if(auth()->user()->is_pro)
              <span class="plan-badge plan-pro">
                {{-- Lucide: crown --}}
                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
                  <path
                    d="M11.562 3.266a.5.5 0 0 1 .876 0L15.39 8.87a1 1 0 0 0 1.516.294L21.183 5.5a.5.5 0 0 1 .798.519l-2.834 10.246a1 1 0 0 1-.956.734H5.81a1 1 0 0 1-.957-.734L2.02 6.02a.5.5 0 0 1 .798-.519l4.276 3.664a1 1 0 0 0 1.516-.294z" />
                  <path d="M5 21h14" />
                </svg>
                PRO
              </span>
            @else
              <span class="plan-badge plan-free">FREE</span>
            @endif
            @if(auth()->user()->is_admin)
              <span class="plan-badge plan-admin"><x-admin.icon name="shield" /> ADMIN</span>
            @endif
          </div>
        </div>

        @if (auth()->user()->is_admin)
          <a href="{{ route('admin.dashboard') }}" class="settings-admin-link">
            {{-- Lucide: shield --}}
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
            </svg>
            Dashboard Admin
          </a>
        @endif

        <div class="settings-section">
          <h3>Suara</h3>
          <div class="settings-row">
            <span>Musik latar</span>
            <button type="button" class="settings-switch" role="switch" aria-label="Musik latar"
              :aria-checked="settings.musicOn" @click="toggleSettingsMusic()"></button>
          </div>
          <div class="settings-row settings-row-col">
            <label for="musicVolume">Volume musik <strong x-text="settings.volume + '%'"></strong></label>
            <input id="musicVolume" class="settings-range" type="range" min="0" max="100" step="5"
              :value="settings.volume" @input="changeVolume($event.target.value)">
          </div>
        </div>

        <div class="settings-section">
          <h3>Cara bermain</h3>
          <ol class="settings-steps">
            <li>Pilih salah satu stage di halaman utama.</li>
            <li>Seret bahan aktif ke slot di dekat karakter.</li>
            <li>Tekan <b>MIX</b> untuk melihat apakah kombinasinya cocok.</li>
            <li>Baca penjelasannya, lalu coba kombinasi lain. Buka <b>Encyclopedia</b> bila ingin tahu lebih banyak.</li>
          </ol>
        </div>

        <div class="settings-section">
          <h3>Tentang</h3>
          <p class="settings-about">Skincare Mix Lab adalah game edukasi untuk mengenal bahan aktif skincare.
            Informasi bersifat edukasi umum, bukan saran medis. Konsultasikan ke dokter kulit bila kulitmu bermasalah.</p>
        </div>

        <div class="settings-section settings-logout">
          <button type="button" class="settings-logout-btn" x-show="!settings.confirmLogout"
            @click="settings.confirmLogout = true">
            {{-- Lucide: log-out --}}
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="m16 17 5-5-5-5" />
              <path d="M21 12H9" />
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            </svg>
            Keluar
          </button>

          <div class="settings-logout-confirm" x-cloak x-show="settings.confirmLogout">
            <p>Yakin mau keluar?</p>
            <div class="settings-logout-actions">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="settings-logout-btn">Ya, keluar</button>
              </form>
              <button type="button" class="settings-logout-cancel" @click="settings.confirmLogout = false">Batal</button>
            </div>
          </div>
        </div>
      </section>
    </div>

    {{-- ── BOTTOM BAR ────────────────────────────────────────── --}}
    <footer class="game-footer">
      <span class="footer-msg">
        {{-- Lucide: flask-conical --}}
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
          <path
            d="M14 2v6a2 2 0 0 0 .245.96l5.51 10.08A2 2 0 0 1 18 22H6a2 2 0 0 1-1.755-2.96l5.51-10.08A2 2 0 0 0 10 8V2" />
          <path d="M6.453 15h11.094" />
          <path d="M8.5 2h7" />
        </svg>
        Choose a mirror to start your skincare journey!
        {{-- Lucide: sparkles --}}
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true">
          <path
            d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z" />
          <path d="M20 3v4" />
          <path d="M22 5h-4" />
          <path d="M4 17v2" />
          <path d="M5 18H3" />
        </svg>
      </span>
    </footer>

  </div>
@endsection

@section('scripts')
  <script>
    const BOXES_JSON = @json($boxesJson);

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

    function homeApp() {
      return {
        upgrade: { visible: false, stage: '' },
        settings: { visible: false, musicOn: false, volume: 100, confirmLogout: false },

        init() {
          this.settings.volume = Math.round(musicVolume * 100);
          this.settings.musicOn = musicOn;
        },
        goToBox(id) {
          const box = BOXES_JSON.find((item) => item.id === id);
          if (box && !box.locked) window.location.href = box.url;
        },
        openUpgrade(stage) {
          this.upgrade = { visible: true, stage };
        },
        closeUpgrade() {
          this.upgrade.visible = false;
        },
        goToUpgrade() {
          window.location.href = @json(route('upgrade'));
        },
        openSettings() {
          this.settings.musicOn = musicOn;
          this.settings.visible = true;
        },
        closeSettings() {
          this.settings.visible = false;
          this.settings.confirmLogout = false;
        },
        toggleSettingsMusic() { toggleMusic(); },
        changeVolume(v) {
          this.settings.volume = Number(v);
          setMusicVolume(this.settings.volume / 100);
        },
      };
    }
  </script>
@endsection